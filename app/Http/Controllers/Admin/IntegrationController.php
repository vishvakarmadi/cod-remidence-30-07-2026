<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Integration;
use App\Models\Admin\Pincode;
use App\Models\Admin\Courier;
use App\Models\Admin\Channel;
use App\Models\Admin\Servicable_pincode;
use App\Models\Admin\Channel_integration;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use App\Models\Admin\Ratecard;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Auth;
use DB;

class IntegrationController extends Controller
{
    public function index()
    {
        // Self-healing migrations for Parcel Uncle fields in integrations table
        if (!\Schema::hasColumn('integrations', 'pu_carrier_title') || 
            !\Schema::hasColumn('integrations', 'pu_api_key') || 
            !\Schema::hasColumn('integrations', 'pu_mode')) {
            try {
                \Schema::table('integrations', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!\Schema::hasColumn('integrations', 'pu_carrier_title')) {
                        $table->string('pu_carrier_title')->nullable()->after('courier_id');
                    }
                    if (!\Schema::hasColumn('integrations', 'pu_api_key')) {
                        $table->text('pu_api_key')->nullable()->after('pu_carrier_title');
                    }
                    if (!\Schema::hasColumn('integrations', 'pu_mode')) {
                        $table->string('pu_mode')->nullable()->after('pu_api_key');
                    }
                });
            } catch (\Exception $e) {
                \Log::error("Failed to run self-healing migration: " . $e->getMessage());
            }
        }

        // Self-healing migrations for India Post fields in integrations table
        if (!\Schema::hasColumn('integrations', 'ip_carrier_title') || 
            !\Schema::hasColumn('integrations', 'ip_customer_id') || 
            !\Schema::hasColumn('integrations', 'ip_password') ||
            !\Schema::hasColumn('integrations', 'ip_contract_id') ||
            !\Schema::hasColumn('integrations', 'ip_mode') ||
            !\Schema::hasColumn('integrations', 'ip_service_type')) {
            try {
                \Schema::table('integrations', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!\Schema::hasColumn('integrations', 'ip_carrier_title')) {
                        $table->string('ip_carrier_title')->nullable()->after('courier_id');
                    }
                    if (!\Schema::hasColumn('integrations', 'ip_customer_id')) {
                        $table->string('ip_customer_id')->nullable()->after('ip_carrier_title');
                    }
                    if (!\Schema::hasColumn('integrations', 'ip_password')) {
                        $table->string('ip_password')->nullable()->after('ip_customer_id');
                    }
                    if (!\Schema::hasColumn('integrations', 'ip_contract_id')) {
                        $table->string('ip_contract_id')->nullable()->after('ip_password');
                    }
                    if (!\Schema::hasColumn('integrations', 'ip_mode')) {
                        $table->string('ip_mode')->nullable()->after('ip_contract_id');
                    }
                    if (!\Schema::hasColumn('integrations', 'ip_service_type')) {
                        $table->string('ip_service_type')->nullable()->after('ip_mode');
                    }
                });
            } catch (\Exception $e) {
                \Log::error("Failed to run self-healing migration for India Post: " . $e->getMessage());
            }
        }

        // Ensure India Post couriers are activated (status = 1) for the logged-in user's company
        try {
            $companyId = auth()->guard('admin')->user()->company_id;
            Courier::where('company_id', $companyId)->where('courier_id', 14)->update(['status' => 1]);
        } catch (\Exception $e) {
            \Log::error("Failed to activate India Post courier: " . $e->getMessage());
        }

        // Self-healing migration for ip_pincodes table
        if (!\Schema::hasTable('ip_pincodes')) {
            try {
                \Schema::create('ip_pincodes', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('awb')->unique();
                    $table->enum('used', ['0', '1'])->default('0');
                    $table->integer('company_id')->nullable();
                    $table->timestamps();
                });
            } catch (\Exception $e) {
                \Log::error("Failed to create ip_pincodes table: " . $e->getMessage());
            }
        }

        $data['couriers'] = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $data['check'] = Integration::query()->where('user_id', auth()->guard('admin')->user()->id)->get()->pluck('courier_id')->toArray();
        $data['ecom'] = Integration::query()->where('user_id', auth()->guard('admin')->user()->id)->where('courier_id', 1)->first();
        $data['delhivery'] = Integration::query()->where('user_id', auth()->guard('admin')->user()->id)->where('courier_id', 2)->first();
        $data['bludart'] = Integration::query()->where('user_id', auth()->guard('admin')->user()->id)->where('courier_id', 3)->first();
        $data['express'] = Integration::query()->where('user_id', auth()->guard('admin')->user()->id)->where('courier_id', 4)->first();
        $data['dtdc'] = Integration::query()->where('user_id', auth()->guard('admin')->user()->id)->where('courier_id', 5)->first();
        $data['parcel_uncle'] = Integration::query()->where('user_id', auth()->guard('admin')->user()->id)->where('courier_id', 13)->first();
        $data['indiapost'] = Integration::query()->where('user_id', auth()->guard('admin')->user()->id)->where('courier_id', 14)->first();
        return view('admin.integration.index',compact('data'));
    }
    public function channel()
    {
        $user = Auth::guard('admin')->user(); 
        $currentcompany_id = $user->company_id;
        $data['channel'] = Channel::where('company_id', $currentcompany_id)->get();
        $data['channel_data'] = Channel_integration::query()->where('user_id', $user->id)->where('status','!=','4')->get();
        return view('admin.integration.channel',compact('data'));

    }
    public function test()
    {
        return view('admin.integration.test');
    }

    public function test_xb()
    {
        $xb_user = env('XBEES_USERNAME', 'admin@Hyloship.com');
        $xb_pass = env('XBEES_PASSWORD', 'Xpress@1234567');
        $xb_secret = env('XBEES_SECRETKEY', '5babb4d7a6c80b45ade918fb4e429068c8480e6125925c474d8d67a27f8190db');
        $xb_key = env('XBEES_XB_KEY', 'Plmng39338VdtHa');

        $steps = [];

        // Step 1: Token Generation
        $token_res = Integration::generatetoken_xbess($xb_user, $xb_pass, $xb_secret);
        $token_data = json_decode($token_res, true);
        $steps['step1_token'] = [
            'url' => 'https://userauthapis.xbees.in/api/auth/generateToken',
            'response' => $token_data
        ];

        // Step 2: AWB Generation (Try to get a NEW BatchID)
        $awb_gen_data = json_encode([
            "BusinessUnit" => "ECOM",
            "ServiceType" => "FORWARD",
            "DeliveryType" => "COD"
        ]);
        $awb_res = Integration::generate_awb_series_xbess($awb_gen_data, $xb_user, $xb_pass, $xb_secret, $xb_key);
        $awb_data = json_decode($awb_res, true);
        $steps['step2_generate'] = [
            'url' => 'https://xbclientapi.xbees.in/POSTShipmentService.svc/AWBNumberSeriesGeneration',
            'payload' => json_decode($awb_gen_data),
            'response' => $awb_data
        ];

        // Step 3: Get Series (Fetch AWBs for the NEW BatchID OR Fallback)
        $batch_id = $awb_data['BatchID'] ?? 'Tvo2T';
        $get_awb_data = json_encode([
            "BusinessUnit" => "ECOM",
            "ServiceType" => "FORWARD",
            "BatchID" => $batch_id
        ]);
        $fetch_res = Integration::get_awb_series_xbess($get_awb_data, $xb_user, $xb_pass, $xb_secret, $xb_key);
        $fetch_data = json_decode($fetch_res, true);
        
        // Persist AWBs into pool
        $added_count = 0;
        if (isset($fetch_data['AWBNoSeries']) && !empty($fetch_data['AWBNoSeries'])) {
            $awbs = explode(',', $fetch_data['AWBNoSeries']);
            $current_company_id = Auth::guard('admin')->user()->company_id;
            
            // Chunked insertion for performance
            $awb_chunks = array_chunk($awbs, 1000);
            foreach ($awb_chunks as $chunk) {
                $insert_data = [];
                foreach ($chunk as $awb) {
                    $awb = trim($awb);
                    if (!empty($awb)) {
                        $insert_data[] = [
                            'awb' => $awb,
                            'used' => '0',
                            'company_id' => $current_company_id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }
                // Use insertOrIgnore if supported, else check existence (manual check is safer for compatibility)
                try {
                    DB::table('xb_pincodes')->insertOrIgnore($insert_data);
                    $added_count += count($insert_data);
                } catch (\Exception $e) {
                    // Fallback for older Laravel versions
                    foreach ($insert_data as $row) {
                        if (!DB::table('xb_pincodes')->where('awb', $row['awb'])->exists()) {
                            DB::table('xb_pincodes')->insert($row);
                            $added_count++;
                        }
                    }
                }
            }
        }

        $steps['step3_fetch'] = [
            'url' => 'https://xbclientapi.xbees.in/TrackingService.svc/GetAWBNumberGeneratedSeries',
            'batch_id_used' => $batch_id,
            'added_to_pool' => $added_count,
            'response' => $fetch_data
        ];

        // Step 4: Serviceability Check (Test with a known pincode from DOCT.MD)
        $svc_check_data = json_encode([
            "BusinessUnit" => "eComm",
            "BusinessFlow" => "Forward",
            "BusinessService" => "Delivery",
            "Pincode" => "410210"
        ]);
        $svc_res = Integration::chk_serviceable_pincode_xbess($svc_check_data, $xb_user, $xb_pass, $xb_secret, $xb_key);
        $steps['step4_serviceability'] = [
            'url' => 'https://xbmasterapi.xbees.in/expose/get/serviceabilitypincode/details',
            'pincode_tested' => '410210',
            'response' => json_decode($svc_res, true)
        ];

        // Step 5: Current Status (Try a sample AWB if we got one in Step 3)
        $sample_awb = $fetch_data['AWBNoSeries'][0] ?? '153933860000000';
        $status_res = Integration::get_current_status_xbess($sample_awb, $xb_user, $xb_pass, $xb_secret, $xb_key);
        $steps['step5_current_status'] = [
            'url' => 'https://apishipmenttracking.xbees.in/GetCurrentShipmentStatus',
            'awb_tested' => $sample_awb,
            'response' => json_decode($status_res, true)
        ];

        return response()->json([
            'status' => 'Diagnostic Complete',
            'config_used' => [
                'xb_user' => $xb_user,
                'xb_key' => $xb_key
            ],
            'steps' => $steps
        ]);
    }

    public function test_manifest()
    {
        $user = auth()->guard('admin')->user();
        
        // Fetch dynamic integration credentials
        $xb_integration = Integration::where('user_id', $user->id)->where('courier_id', 4)->first();
        $xb_user = ($xb_integration && $xb_integration->xusername) ? $xb_integration->xusername : env('XBEES_USERNAME', 'admin@Hyloship.com');
        $xb_pass = ($xb_integration && $xb_integration->xpassword) ? $xb_integration->xpassword : env('XBEES_PASSWORD', 'Xpress@1234567');
        $xb_secret = ($xb_integration && $xb_integration->secret_key) ? $xb_integration->secret_key : env('XBEES_SECRETKEY', '5babb4d7a6c80b45ade918fb4e429068c8480e6125925c474d8d67a27f8190db');
        $xb_biz_name = ($xb_integration && $xb_integration->b_account_name) ? $xb_integration->b_account_name : env('XBEES_BUSINESS_ACCOUNT', 'Hyloship');
        $xb_key = ($xb_integration && $xb_integration->xxb_key) ? $xb_integration->xxb_key : env('XBEES_XB_KEY', 'Plmng39338VdtHa');
        $xb_vendor = ($xb_integration && $xb_integration->vendor_code) ? $xb_integration->vendor_code : 'VENDOR123';

        // Fetch dynamic order and warehouse records
        $warehouse = \App\Models\Admin\Warehouse::where('company_id', $user->company_id)->first() ?? \App\Models\Admin\Warehouse::first();
        $order = \App\Models\Admin\Order::where('company_id', $user->company_id)->orderBy('id', 'desc')->first() ?? \App\Models\Admin\Order::orderBy('id', 'desc')->first();

        if (!$warehouse) {
            return response()->json([
                'status' => 'error',
                'message' => 'No warehouse found in the database. Please create a warehouse before testing the manifest.'
            ]);
        }
        
        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'No order found in the database. Please create an order before testing the manifest.'
            ]);
        }

        $warehousereturn = ($order && $order->return_warehouse_id) ? \App\Models\Admin\Warehouse::find($order->return_warehouse_id) : null;
        if (!$warehousereturn) {
            $warehousereturn = $warehouse;
        }

        // Step 1: Use the existing BatchID we know works
        $batch_id = 'Tvo2T';
        $get_awb_data = json_encode([
            "BusinessUnit" => "ECOM",
            "ServiceType" => "FORWARD",
            "BatchID" => $batch_id
        ]);
        $fetch_res = Integration::get_awb_series_xbess($get_awb_data, $xb_user, $xb_pass, $xb_secret, $xb_key);
        $fetch_data = json_decode($fetch_res, true);
        
        // Pick a fresh AWB from the series (e.g., index 10 to avoid previous tests)
        $fresh_awb = '';
        if (isset($fetch_data['AWBNoSeries']) && count($fetch_data['AWBNoSeries']) > 10) {
            $fresh_awb = $fetch_data['AWBNoSeries'][rand(10, count($fetch_data['AWBNoSeries']) - 1)]; 
        }

        if (!$fresh_awb) {
            return response()->json(['error' => 'Failed to fetch AWB from batch Tvo2T', 'raw_response' => $fetch_res]);
        }

        // Step 2: Manifest it
        $test_order_no = (string)$order->order_id . rand(1000, 9999);
        $expess_data = array(
            'BusinessAccountName' => $xb_biz_name,
            'OrderNo' => $test_order_no,
            'SubOrderNo' => $test_order_no,
            'OrderType' => (strip_tags($order->payment_mode) == 'C.O.D' || strip_tags($order->payment_mode) == 'COD') ? 'COD' : 'PrePaid',
            'CollectibleAmount' => (strip_tags($order->payment_mode) == 'C.O.D' || strip_tags($order->payment_mode) == 'COD') ? (string)$order->total : '0',
            'DeclaredValue' => (string)$order->total,
            'Quantity' => (string)count($order->detail),
            'PickupType' => 'Vendor',
            'ServiceType' => 'SD',
            'AirWayBillNO' => $fresh_awb,
            'DropDetails' => array(
                'Addresses' => array(
                    array(
                        'Name' => $order->ship_fname . ' ' . $order->ship_lname,
                        'Address' => $order->ship_address . ' ' . $order->ship_address_2,
                        'City' => $order->ship_city,
                        'EmailID' => $order->ship_email ?? 'test@example.com',
                        'PinCode' => (string)$order->ship_pincode,
                        'State' => $order->ship_state,
                        'Type' => 'Primary',
                    )
                ),
                'ContactDetails' => array(
                    array(
                        'PhoneNo' => (string)$order->ship_phone,
                        'Type' => 'Primary',
                    )
                )
            ),
            'PickupDetails' => array(
                'Addresses' => array(
                    array(
                        'Name' => $warehouse->name,
                        'Address' => $warehouse->address . ' ' . $warehouse->address_2,
                        'City' => $warehouse->city,
                        'EmailID' => $warehouse->email ?? 'warehouse@example.com',
                        'PinCode' => (string)$warehouse->pincode,
                        'State' => $warehouse->state,
                        'Type' => 'Primary',
                    )
                ),
                'ContactDetails' => array(
                    array(
                        'PhoneNo' => (string)$warehouse->phone,
                        'Type' => 'Primary',
                    )
                ),
                'PickupVendorCode' => (string)$xb_vendor,
            ),
            'RTODetails' => array(
                'Addresses' => array(
                    array(
                        'Name' => $warehousereturn->name,
                        'Address' => $warehousereturn->address . ' ' . $warehousereturn->address_2,
                        'City' => $warehousereturn->city,
                        'EmailID' => $warehousereturn->email ?? 'rto@example.com',
                        'PinCode' => (string)$warehousereturn->pincode,
                        'State' => $warehousereturn->state,
                        'Type' => 'Primary',
                    )
                ),
                'ContactDetails' => array(
                    array(
                        'PhoneNo' => (string)$warehousereturn->phone,
                        'Type' => 'Primary',
                    )
                )
            ),
            'ManifestID' => date('YmdHi') . $test_order_no,
        );

        $manifestRes = Integration::shipment_express(json_encode($expess_data), $xb_user, $xb_pass, $xb_secret, $xb_key);
        
        return response()->json([
            'awb_used' => $fresh_awb,
            'request_payload' => $expess_data,
            'raw_response' => $manifestRes,
            'decoded_response' => json_decode($manifestRes, true)
        ]);
    }

    public function remove_courier($id){
        Integration::whereUserId(auth()->guard('admin')->user()->id)->whereCourierId($id)->first()->delete();
        return redirect()->route('admin.integration.index')->with('success','Channel Removed successfully');
    }

    public function store(Request $request)
    {
        if(Integration::whereUserId(auth()->guard('admin')->user()->id)->whereCourierId(request('courier'))->exists()){
            $integration = Integration::whereUserId(auth()->guard('admin')->user()->id)->whereCourierId(request('courier'))->first();
        } else {
            $integration = new Integration();
        }
        $integration->courier_id = $request->courier;
        $integration->user_id = auth()->guard('admin')->user()->id;
        $integration->bcarrier_title = $request->bcarrier_title;
        $integration->server = $request->input('server');
        $integration->login_id = $request->login_id;
        $integration->licence_key = $request->licence_key;
        $integration->bd_client_id = $request->bd_client_id;
        $integration->bd_client_secret = $request->bd_client_secret;
        $integration->vendor_code = $request->vendor_code;
        $integration->origin_area = $request->origin_area;
        $integration->pre_paid = $request->pre_paid;
        $integration->cod = $request->cod;
        $integration->isToPayCustomer = $request->isToPayCustomer;
        $integration->packtype = $request->packtype;
        $integration->gst_status = $request->gst_status;
        $integration->auto_pickup = $request->auto_pickup;
        $integration->otp_no = $request->otp_no;
        $integration->esclation_status = $request->esclation_status;

        $integration->dcarrier_title = $request->dcarrier_title;
        $integration->dship_mode = $request->dship_mode;
        $integration->dclient = $request->dclient;
        $integration->dapi_token = $request->dapi_token;

        $integration->xcarrier_title = $request->xcarrier_title;
        $integration->xnshipment_type = $request->xnshipment_type;
        $integration->xxb_key = $request->xxb_key;
        $integration->xnaccount_mode = $request->xnaccount_mode;
        $integration->xusername = $request->xusername;
        $integration->xpassword = $request->xpassword;
        $integration->secret_key = $request->secret_key;
        $integration->b_account_name = $request->b_account_name;
        $integration->xwaccount_mode = $request->xwaccount_mode;

        $integration->mcarrier_title = $request->mcarrier_title;
        $integration->mship_mode = $request->mship_mode;
        $integration->mclient = $request->mclient;
        $integration->mapi_token = $request->mapi_token;

        $integration->ecarrier_title = $request->ecarrier_title;
        $integration->eusername = $request->eusername;
        $integration->epassword = $request->epassword;
        $integration->customer_code = $request->customer_code;
        $integration->otp_enable = $request->otp_enable;

        $integration->bd_login_id = $request->bd_login_id;
        $integration->bd_licence_key = $request->bd_licence_key;
        $integration->bd_customer_code = $request->bd_customer_code;
        $integration->bd_origin_area = $request->bd_origin_area;

        $integration->pu_carrier_title = $request->pu_carrier_title;
        $integration->pu_api_key = $request->pu_api_key;
        $integration->pu_mode = $request->pu_mode;

        $integration->ip_carrier_title = $request->ip_carrier_title;
        $integration->ip_customer_id = $request->ip_customer_id;
        $integration->ip_password = $request->ip_password;
        $integration->ip_contract_id = $request->ip_contract_id;
        $integration->ip_mode = $request->ip_mode;
        $integration->ip_service_type = $request->ip_service_type;

        $integration->save();

        return redirect()->route('admin.integration.index')->with('success','Channel Updated successfully');
    }

    public function manage_courier(){
        // Load couriers from courier.json file (static data)
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);
    
        // Get the logged-in admin's company_id
        $companyId = auth()->guard('admin')->user()->company_id;
    
        // Self-healing: Check if Courier ID 14 (India Post) exists for this company
        $ip_exists = Courier::where('company_id', $companyId)->where('courier_id', 14)->exists();
        if (!$ip_exists) {
            try {
                Courier::create([
                    'courier' => 'India Post',
                    'status' => 1,
                    'image' => 'kimipostlogo.png',
                    'company_id' => $companyId,
                    'courier_id' => 14,
                    'mode' => 'air'
                ]);
                Courier::create([
                    'courier' => 'India Post',
                    'status' => 1,
                    'image' => 'kimipostlogo.png',
                    'company_id' => $companyId,
                    'courier_id' => 14,
                    'mode' => 'surface'
                ]);
            } catch (\Exception $e) {
                \Log::error("Failed to seed India Post courier: " . $e->getMessage());
            }
        }

        // Fetch couriers from the database based on company_id (dynamic data)
        $couriersFromDb = Courier::where('company_id', $companyId)->get();
    
        // Count the number of active couriers (status == 1)
        $count = $couriersFromDb->where('status', 1)->count();
    
        // Pass both the JSON data and the database data to the view
        return view('admin.integration.manage', compact('couriers', 'couriersFromDb','companyId' ,'count'));
    }

    
    public function courier_priority()
    {  
        $user = Auth::guard('admin')->user();
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);
        $currcompany = $user->company_id;
        $courierCount = Courier::where('company_id', $currcompany)->count();
        if($courierCount!=null){
        $usercourier_priority = $user->courier_priority;
        $usercourier_priority = $usercourier_priority 
            ? explode(',', $usercourier_priority) 
            : [];
        $courierfromdb = Courier::select('courier_id', 'mode')
            ->where('company_id', $currcompany)
            ->groupBy('courier_id', 'mode')
            ->get();
        $c_array = [];
        foreach ($courierfromdb as $courier) {
            $c_array[] = $courier->courier_id . '' . $courier->mode;
        }
        return view('admin.integration.priority', compact('couriers', 'usercourier_priority', 'courierfromdb', 'c_array'));
    }
    else{
        $couriers = array();
        return view('admin.integration.priority',compact('couriers'))->with('error','No Rate card found');;
        }
    }

    public function prioritystore(Request $request)
    {   
        $id = auth()->guard('admin')->user()->id;
        $admin = Admin::where('id', $id)->first();
        $admin->courier_priority = $request->input('0').','.$request->input('1').','.$request->input('2').','.$request->input('3');
        $admin->save();
        return redirect()->route('admin.integration.courier_priority')->with('success','Priority has been updated');
    }
    
    public function pincode_download($id){
        $user_id = auth()->guard('admin')->user()->ratecard ? auth()->guard('admin')->user()->id : 1;
        $pincode = Pincode::where(['user_id' => $user_id, 'courier_id' => $id])->get();

        dd($pincode);

    }

    public function download_pincode_format(){
        // Create sample data with correct headers
        $data = [
            [
                'Currier Name' => 'Ecom Express',
                'Pin code' => '400001',
                'City Name' => 'Mumbai',
                'State Name' => 'Maharashtra',
                'Mode(Surface/Air)' => 'Air',
                'Pickup (Yes/No)' => 'Yes',
                'Delivery (Yes/No)' => 'Yes'
            ],
            [
                'Currier Name' => 'Ecom Express',
                'Pin code' => '110001',
                'City Name' => 'New Delhi',
                'State Name' => 'Delhi',
                'Mode(Surface/Air)' => 'Surface',
                'Pickup (Yes/No)' => 'Yes',
                'Delivery (Yes/No)' => 'Yes'
            ],
            [
                'Currier Name' => 'Ecom Express',
                'Pin code' => '560001',
                'City Name' => 'Bangalore',
                'State Name' => 'Karnataka',
                'Mode(Surface/Air)' => 'Air',
                'Pickup (Yes/No)' => 'Yes',
                'Delivery (Yes/No)' => 'Yes'
            ]
        ];

        // Create CSV file content
        $filename = 'pincode_format.csv';
        $handle = fopen('php://memory', 'r+');
        
        // Write headers
        $headers = ['Currier Name', 'Pin code', 'City Name', 'State Name', 'Mode(Surface/Air)', 'Pickup (Yes/No)', 'Delivery (Yes/No)'];
        fputcsv($handle, $headers);
        
        // Write data rows
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csv_content = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv_content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function courier_status_all(Request $request)
    { 
        $companyId = $request->input('company_id');
        $status = $request->input('status'); // This should be 0 or 1
        if (!in_array($status, [0, 1])) {
            return response()->json(['message' => 'Invalid status'], 400);
        }

        $updated = Courier::where('company_id', $companyId)
                          ->update(['status' => $status]);

        if ($updated) {
            createlogs('all_changed','courier',$companyId);
            return response()->json(['message' => 'Status updated successfully']);
            
        }
        return response()->json(['message' => 'No couriers found with the given company ID'], 404);
    }


    public function courier_status(Request $request)
    { 

        $courier = Courier::where('courier_id', $request->courier_id)
                          ->where('mode', $request->mode)
                          ->where('company_id', $request->company_id)
                          ->first();

        if (!$courier) {
            return response()->json(['message' => 'Courier not found, invalid company, or incorrect mode'], 404);
        }
        $courier->status = $request->status;
        $courier->save();
        
        createlogs('changed','courier',$request->courier_id);
        
        return response()->json(['message' => 'Courier status updated successfully']);
    }
  public function channel_save(Request $request, $id = 0)
    {

        if ($id) {
            $ci = Channel_integration::where(['store_name' => $request->store_name])
            ->where('id','!=',$id)
            ->where('status','!=','4')
            ->first();
        if($ci){
               return redirect()->route('admin.integration.channel')->with('error', 'Store url is already present');
              }
        }else{
              $ci = Channel_integration::where(['store_name' => $request->store_name])->where('status','!=','4')->first();
              if($ci){
               return redirect()->route('admin.integration.channel')->with('error', 'Store url is already present');
              }
        }
    
    
        $vv = $request->validate([
            'store_name' => 'required',
            'store_access' => 'required',
            'customer_key' => 'required',
        ]);
    
        $user_id = Auth::guard('admin')->user()->id;
        
        if ($id) {
                $warehouse = Channel_integration::findOrFail($id);
          		$warehouse->status = '1';
                $message  = "Please Note that the order will be updated automatically every 5 minutes.";
            
        } else {
            $warehouse = new Channel_integration();
            $user_id = Auth::guard('admin')->user()->id;
          	$warehouse->last_id = 0;
            $warehouse->user_id = $user_id;
          	$message  = "Please Note that the order will be updated automatically every 5 minutes.";
        }
        $warehouse->channel_id = $request->channel_id;
        $store_name = $request->store_name;
        if ($request->channel_id == '1') {
            $store_name = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $store_name);
            $store_name = str_replace('.myshopify.com', '', $store_name);
            $store_name = trim($store_name, '/');
        }
        $warehouse->store_name = $store_name;
        $warehouse->customer_key = $request->customer_key;
        $warehouse->store_access = $request->store_access;
        $warehouse->updated_at = now();
        $warehouse->save();
        
        return redirect()->route('admin.integration.channel')->with('success', $message);

    }
    
    function delete_channel($id){
            $admin = Channel_integration::findOrFail($id);
            $stname = $admin->store_name;
            $admin->status = 4;
            $admin->store_name = $stname.'-c';
            $admin->save();
            return redirect()->route('admin.integration.channel')->with('success', 'Deleted successfully!');
    }
    
    function distroy_channel($id){
            $admin = Channel_integration::findOrFail($id);
            $admin->status = 4;
            $admin->save();
            return redirect()->route('admin.integration.channel')->with('success', 'Deleted successfully!');
    }
    
    function courier_serviceable(){
        $re_data = $serpincode = $pincodedata = array();
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        if(isset($_REQUEST['pincodes']) && $_REQUEST['pincodes'] !=''){
            $re_data['pincodes'] =$_REQUEST['pincodes'];
            $_REQUEST['pincodes'] = explode(',', $_REQUEST['pincodes']);
            $serpincode = Servicable_pincode::wherein('pincode',$_REQUEST['pincodes'])->where('company_id',Auth::guard('admin')->user()->company_id)->get();
            foreach($serpincode as $pncd){
                $pincodedata[$pncd->pincode]['pincode'] = $pncd->pincode;
                $pincodedata[$pncd->pincode]['city'] = $pncd->city;
                $pincodedata[$pncd->pincode]['state'] = $pncd->state;
                for($i=1;$i<15;$i++){
                    if($i == $pncd->courier_id){
                        $pincodedata[$pncd->pincode][$pncd->courier_id]['courier_id'] = $pncd->courier_id;
                        $pincodedata[$pncd->pincode][$pncd->courier_id]['courier_name'] = isset($couriers[$pncd->courier_id]) ? $couriers[$pncd->courier_id]['name'] : 'Courier #'.$pncd->courier_id;
                        $pincodedata[$pncd->pincode][$pncd->courier_id]['type'] = $pncd->type;
                        $pincodedata[$pncd->pincode][$pncd->courier_id]['mode'] = $pncd->mode;
                        $pincodedata[$pncd->pincode][$pncd->courier_id]['payment'] = $pncd->payment;
                        $pincodedata[$pncd->pincode][$pncd->courier_id]['active'] = $pncd->active;
                        $pincodedata[$pncd->pincode][$pncd->courier_id]['shipment_type'] = $pncd->shipment_type;
                    }
                }
            }
        }
        return view('admin.integration.serviceable',compact('re_data','pincodedata'));
    }
    
    function createserviceable(){// couriervise pincode
        return view('admin.integration.createserviceable');
    }
    
    function storeserviceablepincode(Request $request){
        try {
            $collections = (new FastExcel)->import($request->file('excel'));
        } catch (\Exception $exception) {
            return back()->with('error','You have uploaded a wrong format file, please upload the right file.');
        }
        
        $c_data = [
            'ecom express'   => 1,
            'delhivery'      => 2,
            'bluedart'       => 3,
            'xpressbees'     => 4,
            'dtdc'           => 5,
            'smartr'         => 6,
            'ekart'          => 7,
            'shadowfax'      => 8,
            'ats'            => 9,
            'blitz'          => 10,
            'shree maruti'   => 11,
            'pickndel'       => 12,
            'pikndel'        => 12,
            'parcel uncle'   => 13,
            'india post'     => 14,
        ];
        
        $company_id = Auth::guard('admin')->user()->company_id;

        foreach ($collections as $row) {
            $normalizedRow = [];
            foreach ($row as $key => $value) {
                $cleanKey = strtolower(trim(preg_replace('/\s+/', ' ', $key)));
                $normalizedRow[$cleanKey] = trim($value);
            }

            $pincode = $normalizedRow['pincode'] ?? $normalizedRow['pin code'] ?? null;
            if (empty($pincode)) {
                continue;
            }

            $courierName = $normalizedRow['courier'] ?? $normalizedRow['courier name'] ?? $normalizedRow['currier name'] ?? '';
            $courier_id = $c_data[strtolower(trim($courierName))] ?? null;
            if (empty($courier_id)) {
                continue;
            }

            $city = $normalizedRow['city'] ?? $normalizedRow['city name'] ?? null;
            $state = $normalizedRow['state'] ?? $normalizedRow['state name'] ?? null;
            $mode = $normalizedRow['transfer'] ?? $normalizedRow['mode(surface/air)'] ?? null;
            $payment = $normalizedRow['payment'] ?? $normalizedRow['payment mode'] ?? null;
            $type = $normalizedRow['mode'] ?? $normalizedRow['type'] ?? $normalizedRow['pickup/delivery'] ?? null;
            $shipment_type = $normalizedRow['shipmet types'] ?? $normalizedRow['shipment types'] ?? $normalizedRow['shipment_type'] ?? null;

            $pin = Servicable_pincode::where([
                'pincode' => $pincode,
                'courier_id' => $courier_id,
                'company_id' => $company_id,
                'mode' => $mode,
                'type' => $type,
                'payment' => $payment,
                'shipment_type' => $shipment_type
            ])->first();

            if (!$pin) {
                $pin = new Servicable_pincode();
                $pin->pincode = $pincode;
                $pin->courier_id = $courier_id;
                $pin->company_id = $company_id;
                $pin->mode = $mode;
                $pin->type = $type;
                $pin->payment = $payment;
                $pin->shipment_type = $shipment_type;
            }

            $pin->city = $city;
            $pin->state = $state;
            $pin->active = '1';
            $pin->save();
        }
        return redirect()->route('admin.integration.createserviceable')->with('success','Rate Imported Successfully');
    }

    public function download_serviceable_pincodes() {
        $company_id = Auth::guard('admin')->user()->company_id;
        $records = Servicable_pincode::where('company_id', $company_id)->get();
        
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);

        $data = [];
        if ($records->isEmpty()) {
            $data[] = [
                'pincode' => '110059',
                'City' => 'New delhi',
                'State' => 'delhi',
                'Courier' => 'Ecom Express',
                'Transfer' => 'surface',
                'Payment' => 'both',
                'Mode' => 'pickup',
                'Shipment Types' => 'Forward'
            ];
        } else {
            foreach ($records as $record) {
                $courierName = isset($couriers[$record->courier_id]) ? $couriers[$record->courier_id]['name'] : 'Courier #'.$record->courier_id;
                $data[] = [
                    'pincode' => $record->pincode,
                    'City' => $record->city,
                    'State' => $record->state,
                    'Courier' => $courierName,
                    'Transfer' => $record->mode,
                    'Payment' => $record->payment,
                    'Mode' => $record->type,
                    'Shipment Types' => $record->shipment_type
                ];
            }
        }

        return (new FastExcel($data))->download('serviceable_pincodes.xlsx');
    }
    
    function pincode(){ // all india pincode
        return view('admin.integration.pincode');
    }
    
    function storepincode(Request $request){
        try {
            $collections = (new FastExcel)->import($request->file('excel'));
        } catch (\Exception $exception) {
            return back()->with('error','You have uploaded a wrong format file, please upload the right file.');
        }
        foreach ($collections as $row) {
           
          $pin = new Pincode();
          $pin->courier_id = '1';
          $pin->user_id = Auth::guard('admin')->user()->id;
          $pin->company_id = Auth::guard('admin')->user()->company_id;
          $pin->pincode = $row['Pin code'];
          $pin->city = $row['City Name'];
          $pin->state = $row['State Name'];
          $pin->cod = ($row['Delivery (Yes/No)'] == 'Yes') ? 't' : 'f';
          $pin->prepaid = ($row['Delivery (Yes/No)'] == 'Yes') ? 't' : 'f';
          $pin->pickup = ($row['Pickup (Yes/No)'] == 'Yes') ? 't' : 'f';
          $pin->zone = 'z1';
          $pin->metro = ($row['Mode(Surface/Air)'] == 'Air') ? '1' : '0';
          $pin->special = '0';
          $pin->north_east = '0';
          $pin->save();
        }
        return back()->with('success','Pincode Imported Successfully');
    }

    public function store_ip_barcodes(Request $request)
    {
        if (!$request->hasFile('excel')) {
            return back()->with('error', 'Please upload an Excel/CSV file.');
        }

        try {
            $collections = (new FastExcel)->import($request->file('excel'));
        } catch (\Exception $exception) {
            return back()->with('error', 'You have uploaded an invalid format file.');
        }

        $company_id = Auth::guard('admin')->user()->company_id;
        $imported = 0;

        foreach ($collections as $row) {
            $barcode = null;
            foreach (['barcode', 'Barcode', 'awb', 'AWB', 'barcode_no'] as $key) {
                if (isset($row[$key])) {
                    $barcode = trim($row[$key]);
                    break;
                }
            }
            if (empty($barcode) && is_array($row)) {
                $barcode = trim(current($row));
            }

            if (!empty($barcode)) {
                $exists = DB::table('ip_pincodes')->where('awb', $barcode)->exists();
                if (!$exists) {
                    DB::table('ip_pincodes')->insert([
                        'awb' => $barcode,
                        'used' => '0',
                        'company_id' => $company_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $imported++;
                }
            }
        }

        return back()->with('success', "Successfully imported {$imported} India Post barcode(s).");
    }
}