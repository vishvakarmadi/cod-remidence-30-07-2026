<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\CustomerQuery;
use App\Models\Admin\VisitedCustomer;
use App\Models\Admin\Ratecard;
use App\Models\Admin\Order;
use Illuminate\Support\Facades\Mail;
use App\Models\Admin\Warehouse;
use Rap2hpoutre\FastExcel\FastExcel;
use DB;

class FrontController extends Controller
{
    public function addquery(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'email' => 'nullable|email',
            'query_string' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => true,'message' => $validator->errors()], 422);
        }
        $hardBannedKeywords = [
            'weed','ganja','marijuana','hash','charas','cocaine','heroin',
            'gun','pistol','revolver','rifle','bullet','ammo','grenade',
            'rdx','tnt','dynamite',
            'fake currency','counterfeit',
            'ivory','tiger skin',
            'military id','government seal'
        ];

        $bannedFound = null;
        $normalizedQuery = strtolower(preg_replace('/[^a-z]/i', '', $request->query_string));

        foreach ($hardBannedKeywords as $word) {
            $normalizedWord = strtolower(preg_replace('/[^a-z]/i', '', $word));
            if (str_contains($normalizedQuery, $normalizedWord)) {
                $bannedFound = $word;
                break;
            }
        }
        
        $subject = $bannedFound 
            ? "⚠️ Banned Keyword Alert" 
            : "New Customer Query Submitted";

        $body = $bannedFound 
            ? "Banned keyword '{$bannedFound}' detected in query by {$request->name} (Phone: {$request->phone})"
            : "New query submitted by {$request->name} (Phone: {$request->phone} & Email: {$request->email}).\n\nQuery:\n{$request->query_string}";

        Mail::raw($body, function ($message) {
            $message->to('sales@hyloship.com')       // main admin
                    ->bcc(['adityav3011@gmail.com'])           // CC recipients
                    ->subject('New Customer Query Notification');
        });

        // Response
        if ($bannedFound) {
            return response()->json([
                'status'  => 0,
                'message' => "Your query contains a restricted keyword: '{$bannedFound}'",
            ], 422);
        }
        // // Store in DB
        $customerQuery = CustomerQuery::create([
            'name'  => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'query' => $request->query_string
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Query submitted successfully',
            'data'    => $customerQuery
        ], 201);
    }
    
    public function addvisitedguest(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name'  => 'nullable|string|max:255',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'ip_address' =>'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => true,'message' => $validator->errors()], 422);
        }
        // // Store in DB
        $customerQuery = VisitedCustomer::create([
            'name'  => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'ip_address' => $request->ip_address
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Query submitted successfully',
            'data'    => $customerQuery
        ], 201);
    }
    
    public function calculaterate(Request $request){
        // echo 'hi';die;
        $validate = Validator::make($request->all(), [
            'pickup_pin' => 'required|numeric',
            'drop_pin' => 'required|numeric',
            'length' => 'required|numeric',
            'breadth' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'payment' => 'required',
            'value' =>  request('payment') == 'cod' ? 'required|numeric' : '',
            'shipment_type' => 'required',
        ])->validate();
        $user_id = auth()->guard('admin')->user()->id;
        $rateuser = Ratecard::where(['status' => 1,'user_id' => auth()->guard('admin')->user()->id])->first();
        if($rateuser == null){
            return [
                'status' => 0,
                'message' => 'Ratecard not found'
            ];
        }
        
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
       
        $cour_id =array(2,7,10,4,8,11,12);
        $zone_id = Order::getzone(request('pickup_pin'),request('drop_pin'));
        // echo $zone_id;die;
        for($i=0;$i<count($cour_id);$i++){
            $pincodes[] = array(
                "courier_id" => $cour_id[$i],  
                "zone" => $zone_id,
            ); 
        }

        $get = [];
        $payment = request('payment');
        foreach($pincodes as $row){
            $zone = zone($row['zone']);
            $c_id = $row['courier_id'];
            // $count = intVal(($order->weight/1000) / 0.5) - 1; //to convert weight into kg
            foreach(['Air','Surface'] as $transport){
                foreach(['0.5','1','1.5','2','3','3.5','5','10','20','30','50'] as $c_weigt){
                    // echo $c_id.'-->'.$transport.'-->'.$user_id.'-->'.$c_weigt;die;
                    $rate = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $user_id,'weight' =>$c_weigt,'additional' =>0])->first();
                    if($c_weigt=='0.5'){
                        $wadd = '0.5';
                    }else{
                        $wadd = '1';
                    }
                    $rateadd = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $user_id,'weight' =>$wadd,'additional' =>1])->first();
                    
                    // $add = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport, 'weight' => 'add','status' => 1,'user_id' => $user_id])->first();
                    
                    $vol_weight = (request('length')*request('breadth')*request('height'))/vol_weigh($c_id);
                    $weight_to_be_taken = $vol_weight > (request('weight')) ? $vol_weight : (request('weight'));
                    // echo $vol_weight;die;
                    if($rate == null){
                        //  return [
                        //     'status' => 0,
                        //     'message' => 'Rate card not found for this user'
                        // ];
                        continue;
                    }
                    $percent = ((request('value') * $rate->cod) / 100); //4339
                    $cod = $payment == 'cod' ? $percent > $rate->cod_charges ? $percent : $rate->cod_charges : 0; //0
                    $remainging_weight = $weight_to_be_taken - $c_weigt;
                    $freight = $rate->$zone;
                    
                    if($remainging_weight<0){
                        $remainging_weight =0;
                    }
                    $count =$remainging_charge=0;
                    if($rateadd == null){

                    }else{
                        $count = ceil($remainging_weight/$rateadd->weight);
                        $remainging_charge = $count * $rateadd->$zone;
                    }
                    
                    // echo $remainging_weight.'-->'.$count.'-->'.$remainging_charge.'--'.$rate->$zone.'--'.$zone.'--'.$freight;die;
                    // $freight = $rate->$zone * $count;
                    // $freight = ($rate->$zone) + (@$add->$zone * $count); // 45
                    $freight = $freight + $remainging_charge;
                    if(request('shipment_type') == 'reverse'){
                        $ratereverse = Ratecard::where(['courier_id' => $c_id, 'transport' => 'Reverse','status' => 1,'user_id' => $user_id])->first();
                        $freight = $freight + @$ratereverse->$zone;
                    }
                    $gst = (($freight + $cod) * 18) / 100;
                    if($payment == 'cod'){
                        $total = $gst + $freight + $cod;
                    } else {
                        $total = $gst + $freight;
                    }
                   
                    // echo $freight.'-->'.$cod.'-->'.$gst.'<br>';
                    // $total = ($total * 118)/100; // 18% gst
                    $withoutgsttotal = $total - $gst;
                    $get[] = [
                        'courier_id' => $rate->courier_id,
                        'name' => $couriers[$rate->courier_id]['name'],
                        'img' => asset('public/courier').'/'.$couriers[$rate->courier_id]['image'],
                        'mode' => $transport == 'Air' ? 'fa-plane' : 'fa-truck',
                        'weight_used' => $c_weigt,
                        'weight' => round($weight_to_be_taken,2).' kg',
                        'price' => 'Rs.'.number_format($total,2),
                        'zone' => $zone,

                        'courier' => $couriers[$rate->courier_id]['name'] .' '. $rate->transport . ' ('.$c_weigt.' KG)',
                        'freight_charge' => 'Rs '.number_format($freight,2),
                        'cod' => 'Rs '.number_format($cod,2),
                        'gst' => 'Rs '.number_format($gst,2),
                        'total' => 'Rs '.number_format($total,2),
                        'withoutgsttotal' => 'Rs '.number_format($withoutgsttotal,2),
                    ];
                    
                }    
            }
        }
    //    die;
        if(count($get) == 0){
            return [
                'status' => 0,
                'message' => 'Pincode is not Servicable!'
            ];
        }
        return [
            'status' => 1, 
            'message' => 'Success',
            'data' => $get,
            'request' => $request->all(),
        ];
           
    }
    
    public function addWarehouse(Request $request)
    {
        // ✅ Token auth check (admin guard)
       
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'status' => 0,
                'message' => 'Token missing'
            ], 401);
        }

        // ✅ Match token from DB
        $admin = Admin::where('bearer_token', $token)->first();
        if (!$admin) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid token'
            ], 401);
        }

        // ✅ Manually login user
        Auth::guard('admin')->login($admin);
        try {
            // Create fake request to reuse existing function
            $fakeRequest = Request::create(
                '/warehouse-save',
                'POST',
                $request->all()
            );

            // Pass logged-in admin user
            $fakeRequest->setUserResolver(function () {
                return Auth::guard('admin')->user();
            });

            // Call existing controller method
            $controller = app()->make(\App\Http\Controllers\Admin\WarehouseController::class);

            $controller->warehouse_save($fakeRequest, 0);

            return response()->json([
                'status' => 1,
                'message' => 'Warehouse created successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'status' => 0,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getWarehouse(Request $request)
        {
            // ✅ Get token
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Token missing'
                ], 401);
            }

            // ✅ Validate token
            $admin = Admin::where('bearer_token', $token)->first();

            if (!$admin) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Invalid token'
                ], 401);
            }

            // ✅ Login user
            Auth::guard('admin')->login($admin);

            // ✅ Now get logged-in user
            $user = Auth::guard('admin')->user();

            $current_company = $user->company_id;
            $user_id = $user->id;

            // ✅ Fetch warehouses
            $warehouse = Warehouse::where('user_id', $user_id)
                ->where('company_id', $current_company)
                ->where('deleted', '0')
                ->get();

            // ✅ Return response
            return response()->json([
                'status' => 1,
                'message' => 'Warehouse list fetched successfully',
                'data' => $warehouse
            ]);
        }
    
    public function deleteWarehouse(Request $request, $id)
    {
        // ✅ Get token
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'status' => 0,
                'message' => 'Token missing'
            ], 401);
        }

        // ✅ Validate token
        $admin = Admin::where('bearer_token', $token)->first();

        if (!$admin) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid token'
            ], 401);
        }

        // ✅ Login user
        Auth::guard('admin')->login($admin);

        $user = Auth::guard('admin')->user();

        // ✅ Find warehouse (with ownership check)
        $warehouse = Warehouse::where('id', $id)
            ->where('user_id', $user->id)
            ->where('company_id', $user->company_id)
            ->where('deleted','0')
            ->first();

        if (!$warehouse) {
            return response()->json([
                'status' => 0,
                'message' => 'Warehouse not found'
            ], 404);
        }

        // ✅ Soft delete (recommended)
        $warehouse->deleted = '1';
        $warehouse->save();

        // ✅ OPTIONAL: If you want hard delete
        // $warehouse->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Warehouse deleted successfully'
        ]);
    }
    
    public function bulkOrderApi(Request $request)
        {
            // ✅ Token check
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Token missing'
                ], 401);
            }
            
            // ✅ File validation
            if (!$request->hasFile('excel')) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Excel file is required'
                ], 422);
            }

            try {
                $collections = (new FastExcel)->import($request->file('excel'));
            } catch (\Exception $exception) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Invalid file format'
                ], 422);
            }

            // ✅ Required fields
            $requiredFields = [
                'Order_ID*', 'First_Name*', 'Last_Name*', 'Phone_No*', 'Address_1*',
                'Country*', 'City*', 'State*', 'Pincode*',
                'Billing address is same as Shipping address(Y/N)*',
                'weight_(gms)*', 'length_(cms)*', 'breadth_(cms)*', 'height_(cms)*',
                'order_total_amount*', 'payment_mode(cod/pre-paid)*',
                'Product_Name*', 'SKU*', 'Unit_Price*', 'qty*'
            ];

            // ✅ Header validation
            if (array_key_first($collections[0]) != 'Order_ID*') {
                return response()->json([
                    'status' => 0,
                    'message' => 'Wrong file format. Please upload correct template.'
                ], 422);
            }

            // ✅ Max limit
            if (count($collections) > 230) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Max 220 orders allowed per upload'
                ], 422);
            }


            $admin = Admin::where('bearer_token', $token)->first();

            if (!$admin) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Invalid token'
                ], 401);
            }

            // ✅ Login user
            Auth::guard('admin')->login($admin);

            try {
                // ✅ Create fake request (IMPORTANT)
                $fakeRequest = Request::create(
                    '/bulk-order-store',
                    'POST',
                    [
                        'action_id' => 'create' // optional
                    ],
                    [],
                    ['excel' => $request->file('excel')] // file pass
                );

                // attach user
                $fakeRequest->setUserResolver(function () use ($admin) {
                    return $admin;
                });

                // ✅ Call existing controller
                $controller = app()->make(\App\Http\Controllers\Admin\OrderController::class);

                $response = $controller->bulkorderstore($fakeRequest);

                return response()->json([
                    'status' => 1,
                    'message' => 'Orders uploaded successfully'
                ]);

            } catch (\Exception $e) {

                return response()->json([
                    'status' => 0,
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    
    public function getorders(Request $request) {
        // ✅ Get token
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'status' => 0,
                'message' => 'Token missing'
            ], 401);
        }

        // ✅ Validate token
        $admin = Admin::where('bearer_token', $token)->first();

        if (!$admin) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid token'
            ], 401);
        }

        // ✅ Login user
        Auth::guard('admin')->login($admin);

        // ✅ Now get logged-in user
        $user = Auth::guard('admin')->user();

        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $user_id = Auth::guard('admin')->user()->id;
        $current_company = Auth::guard('admin')->user()->company_id;
        $warehouse = WareHouse::where('user_id',$user_id)->where('company_id',$current_company)->get();
        $re_data = array();
        $sortField = 'updated_at';
        $sortDirection = 'desc';
        $fy = get_fin_year();
        $f_year = explode('&&',$fy);
        $re_data['start_date'] ='2024-01-01';
        $re_data['end_date'] =$f_year[1];
        if(Auth::guard('admin')->user()->role_id =='1' ){
                $order_q = Order::with('detail')->where('created_at','>=', $re_data['start_date'])->where('created_at','<=', $re_data['end_date']);
        }elseif(Auth::guard('admin')->user()->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user_id);
            $order_q = Order::with('detail')->whereIn('user_id' , $sub_user_id)->where('created_at','>=', $re_data['start_date'])->where('created_at','<=', $re_data['end_date']);
        }else{
            $order_q = Order::with('detail')->where(['user_id' => $user_id])->where('created_at','>=', $re_data['start_date'])->where('created_at','<=', $re_data['end_date']);
        }
        $order_q->where('company_id',$current_company);
        
        $orders = $order_q->get()->map(function ($order) {

            return [
                'id' => $order->id,
                'order_id' => $order->order_id,
                'vendor_order_id' => $order->vendor_order_id,

                'customer_name' => $order->ship_fname . ' ' . $order->ship_lname,
                'phone' => $order->ship_phone,
                'city' => $order->ship_city,
                'state' => $order->ship_state,
                'pincode' => $order->ship_pincode,

                'payment_mode' => strip_tags($order->payment_mode),
                'status' => strip_tags($order->status),

                'weight' => $order->weight,
                'length' => $order->length,
                'height' => $order->height,
                'width' => $order->breadth,
                'total' => $order->total,

                'created_at' => $order->created_at,

                // ✅ product summary
                'products' => $order->detail->map(function ($item) {
                    return [
                        'name' => $item->name,
                        'sku' => $item->code,
                        'qty' => $item->qty,
                        'price' => $item->price
                    ];
                })
            ];
        });

            // ✅ Return response
            return response()->json([
                'status' => 1,
                'message' => 'Orders list fetched successfully',
                'data' => $orders
            ]);
    }
    
    public function deleteOrder(Request $request, $id)
    {
        // ✅ Get token
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'status' => 0,
                'message' => 'Token missing'
            ], 401);
        }

        // ✅ Validate token
        $admin = Admin::where('bearer_token', $token)->first();

        if (!$admin) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid token'
            ], 401);
        }

        // ✅ Login user
        Auth::guard('admin')->login($admin);

        $user = Auth::guard('admin')->user();
        $user_id = $user->id;
        $current_company_id = $user->company_id;

        // ✅ Fetch order
        $order = DB::table('orders')
            ->where('user_id', $user_id)
            ->where('id', $id)
            ->where('status', '1') // only NEW orders
            ->where('company_id', $current_company_id)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => 0,
                'message' => 'Only new or your own orders can be deleted'
            ], 403);
        }

        // ✅ Fetch order details
        $order_details = DB::table('order_details')
            ->where('user_id', $user_id)
            ->where('order_id', $id)
            ->where('company_id', $current_company_id)
            ->get();

        DB::beginTransaction();

        try {
            // ✅ Archive order
            $archiveColumns = \Illuminate\Support\Facades\Schema::getColumnListing('archive_orders');
            $orderArray = (array)$order;
            if(!empty($archiveColumns)) {
                $orderArray = array_intersect_key($orderArray, array_flip($archiveColumns));
            }
            DB::table('archive_orders')->insert($orderArray);

            // ✅ Archive all order details (IMPORTANT: use loop)
            $archiveDetailColumns = \Illuminate\Support\Facades\Schema::getColumnListing('archive_order_details');
            foreach ($order_details as $detail) {
                $detailArray = (array)$detail;
                if(!empty($archiveDetailColumns)) {
                    $detailArray = array_intersect_key($detailArray, array_flip($archiveDetailColumns));
                }
                if(!empty($detailArray)){
                    DB::table('archive_order_details')->insert($detailArray);
                }
            }

            // ✅ Delete original records
            DB::table('orders')
                ->where('id', $id)
                ->where('user_id', $user_id)
                ->where('company_id', $current_company_id)
                ->delete();

            DB::table('order_details')
                ->where('order_id', $id)
                ->where('user_id', $user_id)
                ->where('company_id', $current_company_id)
                ->delete();

            // ✅ Log
            createlogs('deleted', 'order', $id);

            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Order deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 0,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}