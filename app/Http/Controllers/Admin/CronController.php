<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Pincode_zone;
use App\Models\Admin\Channel_integration;
use App\Models\Admin\Transaction;
use App\Models\Admin\Order;
use App\Models\Admin\OrderDetail;
use App\Models\Admin\Admin;
use App\Models\Admin\Channel;
use App\Models\Admin\Country;
use App\Models\Admin\Reportfilter;
use App\Models\Admin\Status;
use App\Models\Admin\WeightReconciliation;
use App\Models\Admin\Warehouse;
use App\Models\Admin\WalletLog;
use App\Models\Admin\Remittance;
use App\Models\Admin\Invoice;
use App\Models\Admin\Available_invoice;
use App\Models\Admin\Ratecard;
use App\Models\Admin\TempAssignOrder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\EmailVerify;
use DB;
use Carbon\Carbon;
use Hash;

class CronController extends Controller
{
    public function index()
    {
    //  $pincode_from = DB::table('pincodes')
    //    ->select('pincodes.pincode')
    //    ->leftJoin('pincode_zones','pincode_zones.from_pincode','=','pincodes.pincode')
    //    ->where('pincode_zones.from_pincode','=',null)
    //    ->first();
    //    $this->getpincodezone($pincode_from->pincode);
    }

    function getpincodezone($pincode_from){
        $pincode_to = DB::table('pincodes')
       ->select('pincodes.pincode','pincodes.city','pincodes.state')
       ->where('pincodes.pincode','!=',$pincode_from)
       ->get()->unique();
       echo $pincode_from;
       echo '<pre>';print_R($pincode_to);die;
       foreach($pincode_to as $pt):
            $pz = new Pincode_zone();
            $pz->from_pincode = $pincode_from;
            $pz->to_pincode = $pt->pincode;
            $pz->to_city = $pt->city;
            $pz->to_state = $pt->state;
            $pz->zone = Order::getzone($pincode_from,$pt->pincode);
            $pz->save();
       endforeach;
       die('done');
    }

    function getordershopify(){
        
        //echo 'hi';
//        $this->updatedetailsonshopify();
        //echo 'heelo';
        //die;
//        TempAssignOrder::create([
//                    'order_id' => '123',
//                    'money' => 233,
//                    'courier_name'=> 'Unknown',
//                    'username' => 'system'
//                ]);
//        die;
        $admin_users = Admin::with('channel')
        ->where('delete_status', 0) 
         ->get();
//        echo $admin_users;die;  
        foreach($admin_users as $user):
            $usechannel = $user->channel;
            foreach($usechannel as $chanl):
//      echo $chanl;die;
                $datetime = explode(' ',$chanl->created_at);
//            echo $chanl->status.'->'.count($datetime);die;
                if(count($datetime)==2 && $chanl->status =='2'){
                    if($chanl->channel_id =='1'){
                        $last_id_sync = $chanl->last_id;
                        if(request()->has('reset') || $last_id_sync == 0 || $last_id_sync == '' || $last_id_sync == null){
                            $last_id_sync = 0;
                            $start_date = '2020-01-01'; // fetch all historical orders
                        } else {
                            $start_date = $datetime[0]; // incremental: use channel creation date
                        }
                        $user_order =  Channel::getordershopify($chanl->store_name,$chanl->store_access,$start_date,$last_id_sync);
                        $user_order = json_decode($user_order,true);
//                        echo '<pre>';print_R($user_order);
                        if(isset($user_order['orders'])){
                            $ordershopify = $user_order['orders'];
                            foreach($ordershopify as $os):
                                
                                echo '<b>Checking Shopify store: '.$chanl->store_name.'</b><br>';
                                $orderlast = Order::latest()->first();
                                if ($orderlast) {
                                    $lastOrderId = $orderlast->id;
                                    $lastorder_id = sprintf('%d', intval($lastOrderId) + 1);
                                }
                          //echo $orderlast.' '.$lastorder_id;die;
                                $last_fetch = $os['id'];
                                // Skip if order already exists - don't touch existing data
                                $existingOrder = Order::where('shopify_order_id', $os['id'])->first();
                                if ($existingOrder) {
                                    echo '<span style="color:gray">Skipped (already exists): Shopify order #'.$os['id'].'</span><br>';
                                    continue;
                                }

                                echo '<span style="color:green">Adding new Shopify order #'.$os['id'].'...</span><br>';
                                $order = new Order();
                                $order->user_id = $user->id;
                                $order->company_id = $user->company_id;
                                $order->order_id = $lastorder_id;
                          		//	echo '<pre>';print_R($user_order);die;
                                if(isset($os['shipping_address']) && !empty($os['shipping_address'])){
                                    $order->ship_fname =  $os['shipping_address']['first_name'] ?? null;
                                    $order->ship_lname = $os['shipping_address']['last_name'] ?? null;
                                    $order->ship_email = null;
                                    $order->ship_company = $os['shipping_address']['company'] ?? null;
                                    $conuntry_code = '+91';
                                    if(isset($os['shipping_address']['phone'])){
                                        $os['shipping_address']['phone'] = str_replace($conuntry_code,"",$os['shipping_address']['phone']);
                                    }
                                    $order->ship_phone = $os['shipping_address']['phone'] ?? null;
                                    $order->ship_address = $os['shipping_address']['address1'] ?? null;
                                    $order->ship_address_2 = $os['shipping_address']['address2'] ?? null;
                                    $c_id = Country::where('name',$os['shipping_address']['country'])->first('id');
                                    if($c_id == null){
                                        $order->ship_country ='101';
                                    }else{
                                        $order->ship_country =$c_id['id'];
                                    }
                                    $order->ship_pincode = $os['shipping_address']['zip'] ?? 0;
                                    $order->ship_city = $os['shipping_address']['city'] ?? null;
                                    $order->ship_state = $os['shipping_address']['province'] ?? null;
                                    $order->ship_latitude = $os['shipping_address']['latitude'] ?? null;
                                    $order->ship_longitude = $os['shipping_address']['longitude'] ?? null;
                                    $order->ship_gstin =null;
                                }else{
                                    $order->ship_fname =  null;
                                    $order->ship_lname =null;
                                    $order->ship_email =null;
                                    $order->ship_company =null;
                                    $order->ship_phone =null;
                                    $order->ship_address =null;
                                    $order->ship_address_2 =null;
                                    $order->ship_country ='101';
                                    $order->ship_pincode =0;
                                    $order->ship_city =null;
                                    $order->ship_state =null;
                                    $order->ship_latitude =null;
                                    $order->ship_longitude =null;
                                    $order->ship_gstin =null;
                                }
                                if(isset($os['billing_address']) && !empty($os['billing_address'])){
                                    $order->bill_fname =  $os['billing_address']['first_name'] ?? null;
                                    $order->bill_lname = $os['billing_address']['last_name'] ?? null;
                                    $order->bill_company = $os['billing_address']['company'] ?? null;
                                    $conuntry_code = '+91';
                                    if(isset($os['billing_address']['phone'])){
                                    $os['billing_address']['phone'] = str_replace($conuntry_code,"",$os['billing_address']['phone']);
                                    }
                                    $order->bill_phone = $os['billing_address']['phone'] ?? null;
                                    $order->bill_address = $os['billing_address']['address1'] ?? null;
                                    $order->bill_address_2 = $os['billing_address']['address2'] ?? null;
                                    $c_id = Country::where('name',$os['billing_address']['country'])->first('id');
                                    if($c_id == null){
                                        $order->bill_country ='101';
                                    }else{
                                        $order->bill_country =$c_id['id'];
                                    }
                                    $order->bill_pincode = $os['billing_address']['zip'] ?? null;
                                    $order->bill_city = $os['billing_address']['city'] ?? null;
                                    $order->bill_state = $os['billing_address']['province'] ?? null;
                                    $order->bill_latitude = $os['billing_address']['latitude'] ?? null;
                                    $order->bill_longitude = $os['billing_address']['longitude'] ?? null;
                                    $order->bill_gstin =null;
                                    $order->same_add = 0;
                                }else{
                                    $order->bill_fname =  null;
                                    $order->bill_lname =null;
                                    $order->bill_company =null;
                                    $order->bill_phone =null;
                                    $order->bill_address =null;
                                    $order->bill_address_2 =null;
                                    $order->bill_country ='101';
                                    $order->bill_pincode =null;
                                    $order->bill_city =null;
                                    $order->bill_state =null;
                                    $order->bill_latitude =null;
                                    $order->bill_longitude =null;
                                    $order->bill_gstin =null;
                                    $order->same_add = 1;
                                }
                                $order->e_bill_no =  null;
                                $order->discount = $os['current_total_discounts'] ?? 0;
                                $order->shipping_cost = 0;
                                $order->total = $os['current_total_price'] ?? 0;
                                $order->custom_total = $os['current_total_price'] ?? 0;
                                if($user->id =='100' && $order->total <500){
                                    $order->shipping_cost = 50;
                                    $order->total = $order->total + 50;
                                    $order->custom_total = $order->custom_total + 50;
                                }
                                if(in_array($os['financial_status'], ['paid', 'authorized'])){
                                    $order->payment_mode = '12'; // Prepaid
                                }else{
                                    $order->payment_mode = '6'; // COD (pending/else)
                                }
                                $order->vendor_order_id = $os['name'];
                          		$order->shopify_order_id = $os['id'];
                                $order->channel = 'Shopify';
                                $order->channel_id = $chanl->id;
                                if($os['total_weight'] == '' || $os['total_weight'] =='0'){
                                    $os['total_weight'] = '1000';
                                }
                                $order->weight = $os['total_weight'] ?? 1000;
                                $order->length = 10;
                                $order->breadth = 10;
                                $order->height = 10;
                                $order->company_id = $user->company_id;
                                $order->note = $os['note'] ?? null;
                                $order->created_at = $os['created_at'] ?? now();
//                                echo $order;die;
                                $order->save();
                                
                                $id = $order->id;
                                $order->order_id = $order->id;
                                $order->save();
                                $products = $os['line_items'];
                                
                                foreach($products as $key => $row)
                                {	
                                    $detail = new OrderDetail();
                                    $detail->user_id = $user->id;
                                    $detail->order_id = $id;
                                    $detail->name = $row['name'];
                                    $detail->code = $row['sku'] ?? $row['name'];
                                    $detail->price = $row['price'] ?? 0;
                                    $detail->discount =$row['total_discount'] ?? 0;
                                    $detail->qty = $row['quantity'];
                                    $detail->discount_type = 'f';
                                    $detail->tax_percent =  0;
                                    $detail->tax_amount =  0;
                                    $detail->total_price = ($detail->price-$detail->discount)*$detail->qty;
                                    $detail->save();
                                }
                          echo $chanl->id.'-->'.$last_fetch;
                                $user_lastid = Channel_integration::findOrFail($chanl->id);
                                $user_lastid->last_id=$last_fetch;
                                $user_lastid->save();
                            endforeach;
                            
                        }else{
                            // Show what Shopify actually returned
                            if(isset($user_order['errors'])){
                                echo '<span style="color:red"><b>Shopify ['.$chanl->store_name.'] Error:</b> '.json_encode($user_order['errors']).'</span><br>';
                            } else {
                                $order_count = isset($user_order['orders']) ? count($user_order['orders']) : 0;
                                echo '<span style="color:orange"><b>Shopify ['.$chanl->store_name.']:</b> No new orders found (since_id='.$last_id_sync.', fetched='.$order_count.')</span><br>';
                            }
                        } // end if(isset(orders))/else for Shopify
                    } // end if(channel_id==1)
                    if($chanl->channel_id =='2'){
                        if(request()->has('reset') || $chanl->last_id ==0 || $chanl->last_id == '' || $chanl->last_id == null){
                            $after = \Carbon\Carbon::parse($chanl->created_at)->subDays(90)->format('Y-m-d\TH:i:s');
                        }else{
                            $after = str_replace(' ', 'T', $chanl->last_id);
                        }
                     
                        $user_order =  Channel::getorderwoocommerce(ltrim(rtrim($chanl->store_name)),ltrim(rtrim($chanl->store_access)),ltrim(rtrim($chanl->customer_key)),ltrim(rtrim($after)));
                        $user_order = json_decode($user_order,true);
                      //echo '<pre>';print_R($user_order);die;
                        if(isset($user_order[0]['id'])){
                            $new_count = 0;
                            $fix_count = 0;
                            $skip_count = 0;
                            foreach($user_order as $os):
                                if (!in_array($os['status'], ['processing', 'on-hold'])) {
                                    continue;
                                }
                                $orderlast = Order::latest()->first();
                                if ($orderlast) {
                                    $lastOrderId = $orderlast->id;
                                    $lastorder_id = sprintf('%d', intval($lastOrderId) + 1);
                                }
                                $last_fetch = $os['date_created'];
                                // echo $last_fetch;die;
                                //id,
                                // Skip if order already exists - don't touch existing data
                                $existingOrder = Order::where('vendor_order_id', $os['id'])->where('channel', 'Woocommerce')->first();
                                if ($existingOrder) {
                                    $skip_count++;
                                    continue;
                                }
                                echo '<span style="color:green">Adding new WooCommerce order #'.$os['id'].'...</span><br>';
                                $new_count++;
                                $order = new Order();
                                $order->user_id = $user->id;
                                $order->company_id = $user->company_id;
                                $order->order_id = $lastorder_id;

                                // Calculate fallback name once to use for both shipping and billing
                                $fallbackName = trim($os['billing']['first_name'] ?? '');
                                if (empty($fallbackName)) {
                                    $fallbackName = trim($os['shipping']['first_name'] ?? '');
                                }
                                if (empty($fallbackName)) {
                                    if (!empty($os['billing']['email'])) {
                                        $fallbackName = explode('@', $os['billing']['email'])[0];
                                    } else {
                                        $fallbackName = 'Guest ' . $os['id'];
                                    }
                                }
                                if(isset($os['shipping']) && !empty($os['shipping'])){
                                    $shipFirstName = trim($os['shipping']['first_name']);
                                    if (empty($shipFirstName)) {
                                        $shipFirstName = $fallbackName;
                                    }
                                    $order->ship_fname = $shipFirstName;
                                    $order->ship_lname = $os['shipping']['last_name'] ?? '';
                                    $order->ship_email = null;
                                    $order->ship_company = $os['shipping']['company'];
                                    $conuntry_code = '+91';
                                    $os['shipping']['phone'] = str_replace($conuntry_code,"",$os['shipping']['phone']);
                                    $order->ship_phone = $os['shipping']['phone'];
                                    $order->ship_address = $os['shipping']['address_1'];
                                    $order->ship_address_2 = $os['shipping']['address_2'];
                                    $c_id = Country::where('name',$os['shipping']['country'])->first('id');
                                    if($c_id == null){
                                        $order->ship_country ='101';
                                    }else{
                                        $order->ship_country =$c_id['id'];
                                    }
                                    $order->ship_pincode = $os['shipping']['postcode'];
                                    $order->ship_city = $os['shipping']['city'];
                                    $order->ship_state = $os['shipping']['state'];
                                    $order->ship_latitude =null;
                                    $order->ship_longitude =null;
                                    $order->ship_gstin =null;
                                }else{
                                    $order->ship_fname =  null;
                                    $order->ship_lname =null;
                                    $order->ship_email =null;
                                    $order->ship_company =null;
                                    $order->ship_phone =null;
                                    $order->ship_address =null;
                                    $order->ship_address_2 =null;
                                    $order->ship_country ='101';
                                    $order->ship_pincode =null;
                                    $order->ship_city =null;
                                    $order->ship_state =null;
                                    $order->ship_latitude =null;
                                    $order->ship_longitude =null;
                                    $order->ship_gstin =null;
                                }
                                if(isset($os['billing']) && !empty($os['billing'])){
                                    $billFirstName = trim($os['billing']['first_name']);
                                    if (empty($billFirstName)) {
                                        $billFirstName = $fallbackName;
                                    }
                                    $order->bill_fname = $billFirstName;
                                    $order->bill_lname = $os['billing']['last_name'] ?? '';
                                    $order->bill_company = $os['billing']['company'];
                                    $conuntry_code = '+91';
                                    $os['billing']['phone'] = str_replace($conuntry_code,"",$os['billing']['phone']);
                                    $order->bill_phone = $os['billing']['phone'];
                                    $order->bill_address = $os['billing']['address_1'];
                                    $order->bill_address_2 = $os['billing']['address_2'];
                                    $c_id = Country::where('name',$os['billing']['country'])->first('id');
                                    if($c_id == null){
                                        $order->bill_country ='101';
                                    }else{
                                        $order->bill_country =$c_id['id'];
                                    }
                                    $order->bill_pincode = $os['billing']['postcode'];
                                    $order->bill_city = $os['billing']['city'];
                                    $order->bill_state = $os['billing']['state'];
                                    $order->bill_longitude =null;
                                    $order->bill_gstin =null;
                                    $order->bill_gstin =null;
                                    $order->same_add = 0;
                                }else{
                                    $order->bill_fname =  null;
                                    $order->bill_lname =null;
                                    $order->bill_company =null;
                                    $order->bill_phone =null;
                                    $order->bill_address =null;
                                    $order->bill_address_2 =null;
                                    $order->bill_country ='101';
                                    $order->bill_pincode =null;
                                    $order->bill_city =null;
                                    $order->bill_state =null;
                                    $order->bill_latitude =null;
                                    $order->bill_longitude =null;
                                    $order->bill_gstin =null;
                                    $order->same_add = 1;
                                }
                                $order->e_bill_no =  null;
                                $order->discount = $os['discount_total'] ?? 0;
                                $order->shipping_cost = $os['shipping_total'] ?? 0;
                                $order->total = $os['total'] ?? 0;
                                $order->custom_total = $os['total'] ?? 0;
                                if(in_array($os['payment_method'], ['cod', 'cash_on_delivery', 'bacs', 'cheque', 'jetpack_custom_gateway']) || 
                                   str_contains(strtolower($os['payment_method_title']), 'cash on delivery') || 
                                   str_contains(strtolower($os['payment_method_title']), 'pay later')){
                                    $order->payment_mode = '6'; // COD
                                } else {
                                    $order->payment_mode = '12'; // Prepaid (default for online gateways)
                                }
                                $order->vendor_order_id = $os['id'];
                                $order->channel = 'Woocommerce';
                                $order->status = '1'; // WooCommerce processing orders are always mapped to New (1)
                          		$order->channel_id = $chanl->id;
                                $order->weight =  1000;
                                $order->length = 10;
                                $order->breadth = 10;
                                $order->height = 10;
                                $order->note = $os['customer_note'] ?? null;
                                $order->created_at = $os['date_created'] ?? ($os['date_created_gmt'] ?? now());
                                $order->save();
                                
                                $id = $order->id;
                                $order->order_id = $order->id;
                                $order->save();
                                $products = $os['line_items'];
                                
                                foreach($products as $key => $row)
                                {
                                    $detail = new OrderDetail();
                                    $detail->user_id = $user->id;
                                    $detail->order_id = $id;
                                    $detail->name = $row['name'];
                                    $detail->code = $row['sku'] ?? null;
                                    $detail->price = $row['price'] ?? 0;
                                    $detail->discount = 0;
                                    $detail->qty = $row['quantity'];
                                    $detail->discount_type = 'f';
                                    $detail->tax_percent =  0;
                                    $detail->tax_amount = $row['total_tax'];
                                    $detail->total_price = $row['total'];
                                    $detail->save();
                                }
                                    $user_lastid = Channel_integration::findOrFail($chanl->id);
                                    $user_lastid->last_id=$last_fetch;
                                    $user_lastid->save();
                            endforeach;
                            echo '<b>Sync Summary for '.$chanl->store_name.': '.$new_count.' new, '.$fix_count.' fixed, '.$skip_count.' existing.</b><br><br>';
                        }else{
                            if(isset($user_order['code'])){
                                echo '<span style="color:red">Error from WooCommerce store '.$chanl->store_name.': ['.$user_order['code'].'] '.$user_order['message'].'</span><br>';
                            } else {
                                echo 'No orders found for WooCommerce store: '.$chanl->store_name.' after '.$after.'. <br>Try hitting this URL manually: '.$chanl->store_name.'/wp-json/wc/v3/orders?after='.$after.'<br>';
                            }
                        }
                    }
                }
            endforeach;
        endforeach;
        exit;
    }

    function checkchanneldetails(){
        if (request()->has('reset') || request()->has('reset_status')) {
            Channel_integration::where('status', '3')->update(['status' => '1']);
        }

        $inactivechannel = Channel_integration::where('status','=', '1') 
       ->take(5)->get(); 
//       echo $inactivechannel;die;
       foreach($inactivechannel as $chn):
//           echo $chn->channel_id;die;
        if($chn->channel_id =='1'){
            $datetime = explode(' ',$chn->created_at);
            $user_order =  Channel::getordershopify($chn->store_name,$chn->store_access,$datetime[0],$chn->last_id);
            $user_order = json_decode($user_order,true);
//            echo $user_order;die;
            if(isset($user_order['orders'])){
                $user_lastid = Channel_integration::findOrFail($chn->id);
                $user_lastid->status='2';
                $user_lastid->save();
                echo 'Shopify channel ['.$chn->store_name.'] successfully verified and activated.<br>';
            }else{
                $user_lastid = Channel_integration::findOrFail($chn->id);
                $user_lastid->status='3';
                $user_lastid->save();
                echo 'Shopify channel ['.$chn->store_name.'] verification failed.<br>';
            }
        }elseif($chn->channel_id =='2'){
            if(request()->has('reset') || $chn->last_id ==0 || $chn->last_id == '' || $chn->last_id == null){
                $after = \Carbon\Carbon::parse($chn->created_at)->subDays(90)->format('Y-m-d\TH:i:s');
            }else{
                $after = str_replace(' ', 'T', $chn->last_id);
            }
            $user_order =  Channel::getorderwoocommerce(ltrim(rtrim($chn->store_name)),ltrim(rtrim($chn->store_access)),ltrim(rtrim($chn->customer_key)),ltrim(rtrim($after)));
          
          if($user_order =='' || str_contains($user_order, 'Please enable cookies')){
                $wrong =1;
            }
            $user_order = json_decode($user_order,true);
          
            if(isset($user_order['code'])){
                $wrong =1;
            }else if(empty($user_order)){
                $wrong =0;
            }else if(isset($user_order[0]['id'])){
                $wrong =0;
            }


            if($wrong ==0){
                $user_lastid = Channel_integration::findOrFail($chn->id);
                $user_lastid->status='2';
                $user_lastid->save();
            }
            if($wrong ==1){
                $user_lastid = Channel_integration::findOrFail($chn->id);
                $user_lastid->status='3';
                $user_lastid->save();
            }
        }
       endforeach;
       exit;
    }
    
    function trackingorderold(){
//         $orders = Order::whereNotIn('status', array('1','3','4'))
//        ->where(function($query) {
//        $query->where('chk_date','<=',date('Y-m-d H:i:s'))
//        ->orWhere('chk_date',null);
//        })
//         ->orderBy('chk_date')  // Orders results by chk_date in ascending order
//         ->take(20)->get(); 
        
        $orders = Order::whereIn('status', array('3'))
        ->where(function($query) {
        $query->where('chk_date','<=',date('Y-m-d H:i:s'))
        ->orWhere('chk_date',null);
        })
        ->where('ship_courier_id','9')
                ->where('chk_ats','0')
         ->orderBy('delivered_date')  // Orders results by chk_date in ascending order
           ->take(20)     
         ->get(); 
echo count($orders);die;
        foreach($orders as $order):
            Status::updatestatusorder($order->id);
            echo '-------------------------------------------<br>';
        endforeach;
        exit;
    }
    
    function trackingorder(){
       
//        $orders = Order::where('id', '47962')
        $end_date = now()->subDays(45)->setTime(00, 00, 00)->toDateTimeString();
        // Removed redundant 15-day check to allow updates for orders up to 45 days old
        $orders = Order::whereNotIn('status', array('1','3','4','6','16','17'))
                ->where(function($query) {
                    $query->where('chk_date','<=',date('Y-m-d H:i:s'))
                          ->orWhere('chk_date',null);
                })
        ->where('status_date','>=',$end_date)
        ->where('ship_courier_id','!=','9')
         ->orderByRaw('chk_date IS NULL DESC, chk_date ASC')  // Prioritize new checks (NULL) then oldest scheduled checks
         ->take(500)->get(); 
        

//echo '<pre>';print_R(count($orders));die;
        foreach($orders as $order):
            Status::updatestatusorder($order->id);
            echo '-------------------------------------------<br>';
        endforeach;
        exit;
    }
    
    function calculateextraweightcost(){
        $orders = Order::
        where('extra_weight_status', array('5'))
        ->take(40)->get(); 
        foreach($orders as $order)
        {   
            $newfreight =$newfreightparent =0;
            $newweight = $order->extra_weight/1000;
            $newfreight = $order->rate;
            $newfreightparent = $order->rateparent;
            $remainging_weight = $newweight - $order->shipping_courier_weight_used;
            $addWeight = 0.5;
            if($order->shipping_courier_weight_used !='0.5'){
                $addWeight = 1;   
            }
            $count = ceil($remainging_weight/$addWeight);
            if($count !=0){
                $newfreight += $count*$order->rateadd;
                $newfreightparent += $count*$order->rateaddparent;
            }
            $newfrightwithgst = $newfreight *1.18;
            $extracostwithoutgst = $newfreight - $order->freight;
            $extracostgst = $extracostwithoutgst *.18;
            $extracost = $newfrightwithgst - ($order->freight+$order->gst_freight);

            $newfrightwithgstparent = $newfreightparent *1.18;
            $extracostwithoutgstparent = $newfreightparent - $order->freightparent;
            $extracostgstparent = $extracostwithoutgstparent *.18;
            $extracostparent = $newfrightwithgstparent - ($order->freightparent+$order->gst_freightparent);

            $orderd = Order::where('id', $order->id)->first();
            $orderd->extra_weight_status =1;
            $orderd->extra_weight_cost =round($extracost,2);//with gst
            $orderd->extracostwithoutgst =round($extracostwithoutgst,2);//without gst
            $orderd->extracostgst =round($extracostgst,2);// gst

            $orderd->extra_weight_costparent =round($extracostparent,2);//with gst
            $orderd->extracostwithoutgstparent =round($extracostwithoutgstparent,2);//without gst
            $orderd->extracostgstparent =round($extracostgstparent,2);// gst
            $orderd->save();
        }
        exit;
       
    }
    function autocloseextraweight(){
        $addedon = \Carbon\Carbon::parse(now())->addDays(-7)->format('Y-m-d');
        $addedon .=' 23:59:59'; 
//        $addedon = now();
//        echo $addedon;die;
        $orders = Order::
        where('extra_weight_status', array('1'))
        ->where('extra_weight_added_on','<=', $addedon)
        ->get(); 
        foreach($orders as $order)
        {   
            $weight = new WeightReconciliation();
            $weight->order_id = $order->id;
            $weight->company_id = $order->company_id;
            $weight->added_by = 1;
            $weight->description = 'Auto Closed after 7 days';
            $weight->save();
            
            $getuser = DB::table('orders')
            ->select('user_id as user')
            ->where('id','=',$order->id)
            ->first();

            $balance = Admin::find($getuser->user);
            $parent_userid = Admin::find($getuser->user)->parent_id;
            if($order->extra_weight_cost =='' || $order->extra_weight_cost =='0'){
                $order->extra_weight_cost =0.00;
                $order->extra_weight_costparent =0.00;
            }
            $transaction = new Transaction();
            $transaction->order_id = $order->id;
            $transaction->company_id = $order->company_id;
            $transaction->user_id = $getuser->user;
            $transaction->awb = $order->tracking_info;
            $transaction->tracking_info = $order->tracking_info;
            $transaction->credit = '0.00';
            $transaction->debit = $order->extra_weight_cost;
            $transaction->closing_blc = $balance->wallet_blc - $order->extra_weight_cost;
            $transaction->remarks = "Amount Debit for extra weight";
            $transaction->show_data = '1';
            $transaction->save();
            
            if($order->extra_weight_costparent ==null){
                $order->extra_weight_costparent =0;
            }
            $transactionparent = new Transaction();
            $transactionparent->order_id = $order->id;
            $transactionparent->user_id = $parent_userid;
            $transactionparent->company_id = $order->company_id;
            $transactionparent->awb = $order->tracking_info;
            $transactionparent->tracking_info = $order->tracking_info;
            $transactionparent->credit = '0.00';
            $transactionparent->debit = $order->extra_weight_costparent;
            $transactionparent->remarks = "Amount Debit for extra weight";
            $transactionparent->parent_data = '1';
            $transactionparent->show_data = '1';
            $transactionparent->save();
            
            $balance->wallet_blc = $balance->wallet_blc - $order->extra_weight_cost;
            $balance->save();
            $order->extra_weight_status =3;
            $order->extra_weight_closed_on =now();
            $order->save();
            
            $balancerto = Admin::find($getuser->user);
            if($order->rtocharge_applied =='1' && $order->extra_weght_rto_deduct =='0'){
                $transaction = new Transaction();
                $transaction->order_id = $order->id;
                $transaction->company_id = $order->company_id;
                $transaction->user_id = $getuser->user;
                $transaction->awb = $order->tracking_info;
                $transaction->tracking_info = $order->tracking_info;
                $transaction->credit = '0.00';
                $transaction->debit = $order->extra_weight_cost;
                $transaction->closing_blc = $balancerto->wallet_blc - $order->extra_weight_cost;
                $transaction->remarks = "Amount Debit for extra weight - RTO";
                $transaction->show_data = '1';
                $transaction->save();

                $transactionparent = new Transaction();
                $transactionparent->order_id = $order->id;
                $transactionparent->company_id = $order->company_id;
                $transactionparent->user_id = $parent_userid;
                $transactionparent->awb = $order->tracking_info;
                $transactionparent->tracking_info = $order->tracking_info;
                $transactionparent->credit = '0.00';
                 if($order->extra_weight_costparent ==null){
                    $order->extra_weight_costparent =0;
                }
                $transactionparent->debit = $order->extra_weight_costparent;
                $transactionparent->remarks = "Amount Debit for extra weight - RTO";
                $transactionparent->parent_data = '1';
                $transactionparent->show_data = '1';
                $transactionparent->save();
                
                $balancerto->wallet_blc = $balancerto->wallet_blc - $order->extra_weight_cost;
                $balancerto->save();

                $order->extra_weght_rto_deduct ='1';
                $order->save();
            }
        }
      exit;
    }
  
  	function markndrfordelhvery(){
        $date = \Carbon\Carbon::parse(now())->addDays(-1)->format('Y-m-d');
        $strt =$date.' 00:00:01'; 
        $end =$date.' 23:59:59'; 
        $orders = Order::
        where('status', array('15'))
        ->where('ship_courier_id', '2')
        ->where('status_date','<=', $end)
        ->get(); 
      	foreach($orders as $order)
        {
            $orderd = Order::where('id', $order->id)->first();
            $old_status = 'Out for Delivery';
            $orderd->status = 10;
            $orderd->status_date = now();
            $orderd->save();
            Status::orderstatuslog($order->id,$order->company_id,$old_status,'NDR',now(),'oud');
        }
        exit;
    }
  
  function updateorderstatus($order_id){
    $orders = Order::whereNotIn('status', array('1','3','4'))
        ->where('id',$order_id)
         ->take(20)->get(); 
//    echo $orders;die;
         foreach($orders as $order):
            Status::updatestatusorder($order->id);
        endforeach;
    die('done');
  }
  	
//   function updatedetailsonshopify(){
//         $orders = DB::table('orders')
//         ->select('*')
//         ->where('channel', 'Shopify')
//         ->where('fulfillment_status', 'pending')
//         ->whereNotIn('status', array('1','4'))
//         ->where('tracking_info','!=',null)
//         ->where('shopify_order_id','!=',null)
//         ->where('channel_id','!=','0')
//         ->where('shipped_date','>=','2024-07-05')       
//         ->whereNotIn('user_id',array('23'))        
//         ->take(10)->get(); 
//         foreach($orders as $order)
//         {
//             echo $order->id.',';
//         }
// //        echo '<br>';
// //    echo '<br>'.$orders;die;
//         $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
//         foreach($orders as $order)
//         {
//           $error = 'error'; 
//           $channel = Channel_integration::where('id',$order->channel_id)->first();
//           $fulfillmentid = array();
//           $fulfillmentid =  Channel::getfulfillmentid($channel->store_name,$channel->store_access,$order->shopify_order_id);
//           $fulfillmentid = json_decode($fulfillmentid,true);
//           if(isset($fulfillmentid['fulfillments'][0]['id'])){
//                 $f_id = $fulfillmentid['fulfillments'][0]['id'];
//           }else{
//             $fulfillmentid2 =  Channel::getfulfillmentid_other($channel->store_name,$channel->store_access,$order->shopify_order_id);
//             $fulfillmentid2 = json_decode($fulfillmentid2,true);
//             if(isset($fulfillmentid2['fulfillment_orders'][0]['id'])){
//                 $f_id = $fulfillmentid2['fulfillment_orders'][0]['id'];
//             }
//           }
// //          echo '<pre>';print_R($fulfillmentid).'<br>';die;
//             if(isset($f_id)){
//                 $company = @$couriers[$order->ship_courier_id]['name'];
// //                $url = @$couriers[$order->ship_courier_id]['url'].'/'.$order->tracking_info;
//                 $trck_data = array(
//                     'fulfillment'=>array(
//                         'line_items_by_fulfillment_order'=>array(
//                             array(
//                                 'fulfillment_order_id'=>$f_id,
//                             )
//                         ),
//                         'tracking_info'=>array(
//                             'number'=>$order->tracking_info,
//                             'company'=>$company,
//                             'url'=>'https://hyloship.com/admin/tracking/'.$order->tracking_info
//                         )
//                     )
//                 );
//                 $add_trckdata =  Channel::addfullfillment($channel->store_name,$channel->store_access,json_encode($trck_data,true));
//                 $add_trckdata = json_decode($add_trckdata,true);
//                 // echo '<pre>';print_R($add_trckdata);die;
//                 if(isset($add_trckdata['fulfillment']) && isset($add_trckdata['fulfillment']['status']) && $add_trckdata['fulfillment']['status'] =='success'){
//                     $error = 'success'; 
//                 }
//                  $orderd = Order::where('id', $order->id)->first();
//                  echo $order->id.'-->'.$error.'<br>';
//                  $orderd->fulfillment_status = $error;
//                  $orderd->fulfillment_time = now();
//                  $orderd->save();
                 
//             }
           
//         }
// //        die('done');
//     }
     function updatedetailsonshopify(){
        $orders = DB::table('orders')
        ->select('*')
        ->where('channel', 'Shopify')
        ->where('fulfillment_status', 'pending')
        ->whereNotIn('status', array('1','4'))
        ->where('tracking_info','!=',null)
        ->where('shopify_order_id','!=',null)
        ->where('channel_id','!=','0')
        ->where('shipped_date','>=','2024-07-05')       
        ->whereNotIn('user_id',array('23'))        
        ->take(10)->get(); 
        if($orders->isEmpty())
        {
            echo "No pending Shopify orders found to update.";
        }
        foreach($orders as $order)
        {
            echo $order->id.',';
        }
//        echo '<br>';
//    echo '<br>'.$orders;die;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        foreach($orders as $order)
        {
           $error = 'error'; 
           $channel = Channel_integration::where('id',$order->channel_id)->first();
           $fulfillmentid = array();
           $fulfillmentid =  Channel::getfulfillmentid($channel->store_name,$channel->store_access,$order->shopify_order_id);
           $fulfillmentid = json_decode($fulfillmentid,true);
           if(isset($fulfillmentid['fulfillments'][0]['id'])){
                $f_id = $fulfillmentid['fulfillments'][0]['id'];
           }else{
            $fulfillmentid2 =  Channel::getfulfillmentid_other($channel->store_name,$channel->store_access,$order->shopify_order_id);
            $fulfillmentid2 = json_decode($fulfillmentid2,true);
            if(isset($fulfillmentid2['fulfillment_orders'][0]['id'])){
                $f_id = $fulfillmentid2['fulfillment_orders'][0]['id'];
            }
           }
//          echo '<pre>';print_R($fulfillmentid).'<br>';die;
            if(isset($f_id)){
                $company = @$couriers[$order->ship_courier_id]['name'];
//                $url = @$couriers[$order->ship_courier_id]['url'].'/'.$order->tracking_info;
                $trck_data = array(
                    'fulfillment'=>array(
                        'line_items_by_fulfillment_order'=>array(
                            array(
                                'fulfillment_order_id'=>$f_id,
                            )
                        ),
                        'tracking_info'=>array(
                            'number'=>$order->tracking_info,
                            'company'=>$company,
                            'url'=>'https://hyloship.com/admin/tracking/'.$order->tracking_info
                        )
                    )
                );
                $add_trckdata =  Channel::addfullfillment($channel->store_name,$channel->store_access,json_encode($trck_data,true));
                $add_trckdata = json_decode($add_trckdata,true);
                if(isset($add_trckdata['fulfillment']) && isset($add_trckdata['fulfillment']['status']) && 
                    in_array($add_trckdata['fulfillment']['status'], ['success', 'fulfilled', 'pending'])){
                    $error = 'fulfilled'; 
                }
                 $orderd = Order::where('id', $order->id)->first();
                 if ($error == 'error') {
                     echo $order->id.'-->'.$error.' (Shopify Response: '.json_encode($add_trckdata).')<br>';
                 } else {
                     echo $order->id.'-->'.$error.'<br>';
                 }
                 $orderd->fulfillment_status = $error;
                 $orderd->fulfillment_time = now();
                 $orderd->save();
                 
            } else {
                 echo $order->id.'-->error (No fulfillment ID found in Shopify)<br>';
            }
           
        }
//        die('done');
    }
    function fixshopifytracking(){
        $startDate = request()->get('date', '2024-07-05');
        
        $orders = DB::table('orders')
        ->select('*')
        ->where('channel', 'Shopify')
        ->whereIn('fulfillment_status', ['success', 'fulfilled'])
        ->where('tracking_info','!=',null)
        ->where('shopify_order_id','!=',null)
        ->where('channel_id','!=','0')
        ->where('shipped_date','>=',$startDate) 
        ->get();

        if ($orders->isEmpty()) {
            return "No fulfilled Shopify orders found since " . $startDate;
        }

        echo "Found " . $orders->count() . " orders to check/fix.<br><br>";

        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);

        foreach($orders as $order)
        {
           $channel = Channel_integration::where('id',$order->channel_id)->first();
           if(!$channel) {
               echo "Channel integration not found for order ID: ".$order->id."<br>";
               continue;
           }

           // Get the fulfillment ID from Shopify
           $fulfillmentid_response = Channel::getfulfillmentid($channel->store_name,$channel->store_access,$order->shopify_order_id);
           $fulfillment_data = json_decode($fulfillmentid_response,true);

           if(isset($fulfillment_data['fulfillments']) && is_array($fulfillment_data['fulfillments'])){
               foreach($fulfillment_data['fulfillments'] as $fulfillment) {
                   $f_id = $fulfillment['id'];
                   $company = @$couriers[$order->ship_courier_id]['name'] ?? 'Other';
                   
                   $update_payload = array(
                       'fulfillment' => array(
                           'notify_customer' => false,
                           'tracking_info' => array(
                               'number' => $order->tracking_info,
                               'url' => 'https://hyloship.com/admin/tracking/'.$order->tracking_info,
                               'company' => $company
                           )
                       )
                   );

                   $store_name = Channel::cleanStoreName($channel->store_name);
                   $url = "https://".$store_name.".myshopify.com/admin/api/2024-04/fulfillments/".$f_id."/update_tracking.json";
                   
                   $response = Channel::shopifyposthit($url, $channel->store_access, json_encode($update_payload, true));
                   $response_decoded = json_decode($response, true);
                   
                   if (isset($response_decoded['fulfillment'])) {
                       echo "Order ID: ".$order->id." (Fulfillment ID: ".$f_id.") tracking URL successfully updated on Shopify.<br>";
                   } else {
                       echo "Failed to update Order ID: ".$order->id." (Fulfillment ID: ".$f_id."). Shopify Response: ".$response."<br>";
                   }
               }
           } else {
               echo "No fulfillments found on Shopify for Order ID: ".$order->id."<br>";
           }
        }
        echo "Finished fixing tracking URLs.";
    }
    function updateordertotalattempt(){
        $ndrordeR_id = Order::getorderidusingndrsuingcron();
//        echo '<pre>';print_R($ndrordeR_id);die;
        for($i=0;$i<count($ndrordeR_id);$i++){
            Status::updateorderattempts($ndrordeR_id[$i]);
        }
    }
    
    function mailmisreport(){
    $re_data['start_date'] = date('Y-m-d',strtotime("-1 days")).' 00:00:01';
    $re_data['end_date'] = date('Y-m-d',strtotime("-1 days")).' 23:59:59';
     $usermis = admin::where('mis_mail','1')->where('active','1')->where('delete_status','0')->where('email','!=','')->where('email','!=',null)->get();
//     echo $usermis;die; 
     foreach($usermis as $eu){
//         $user_id = '52';
        $user_id = $eu->id;
        // if($eu->role_id =='1'){
        //     $users = Admin::where('delete_status','0')->get();    
        // }elseif($eu->role_id =='2'){
        //     $sub_user_id = Admin::getsubuserid($eu->id);
        //     $users = Admin::where('delete_status',0)
        //     ->whereIn('id',$sub_user_id)
        //     ->get();
        // }else{
        //     $users = Admin::where('id',$user_id)->where('delete_status','0')->get();
        // }
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);

        $order_q = Order::with('detail')
            ->join('admins', 'orders.user_id', '=', 'admins.id')
            ->join('transactions', 'transactions.order_id', '=', 'orders.id')
            ->leftJoin('profiles', function($join) {
                $join->on('orders.user_id', '=', 'profiles.user_id')
                     ->whereRaw('profiles.id = (SELECT id FROM profiles WHERE profiles.user_id = orders.user_id LIMIT 1)');
            })
            ->leftJoin('warehouses', 'warehouses.id', '=', 'orders.warehouse_id')
            ->leftJoin('states', 'states.id', '=', 'profiles.state')        
            ->where(['orders.user_id' => $user_id])->where('orders.status','!=', 1)
//                    ->where('orders.created_at','>=',$re_data['start_date'])->where('orders.created_at','<=',$re_data['end_date']);
//             ->where('transactions.created_at','>=',$re_data['start_date'])->where('transactions.created_at','<=',$re_data['end_date']);
           ->where(function($query) use ($re_data) {
            $query->whereBetween('transactions.created_at', [$re_data['start_date'], $re_data['end_date']])
              ->orWhereBetween('orders.created_at', [$re_data['start_date'], $re_data['end_date']]);
            });
        $orders = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id','profiles.bank_name','profiles.beneficiary_name','account_no','ifsc_code','profiles.gst','billing_address','profiles.city','states.name as pstate','warehouses.pincode as zip_code','warehouses.city as wcity','warehouses.state as wstate')
                ->distinct()
                ->get();
        $warehouse = WareHouse::where('user_id',$user_id)->get();
        $counrtries = Country::get();
        $mailbody = '';
        $mailbody .= '<div class="table-responsive">';
        $mailbody .= '<table class="table table-bordered sorttableexceldate" id="dataTable" height="100%" width="100%" cellspacing="0" border="1" cellpadding="0">';
        $mailbody .= '<thead><tr><th>Order ID</th><th>Channel</th><th>Seller</th><th>Phone</th><th>Email</th><th>GST</th><th>Billing Address</th><th>City</th><th>State</th><th>W. Pincode</th><th>Created</th><th>READY TO SHIP DATE</th><th>Delivered</th><th>Status</th><th>SKU</th><th>Qty</th><th>Order Subtotal</th><th>Order Discount Amount</th><th>Total Amount</th><th>Payment Mode</th><th>Courier Name</th><th>Tracking Number</th><th>Manifest ID</th><th>Pickup Warehouse</th><th>Warehouse Phone</th><th>RTO Warehouse</th><th>Warehouse Phone</th><th>Customer Name</th><th>Customer Phone</th><th>Customer Email</th><th>Pincode</th><th>City</th><th>State</th><th>Address</th><th>Dimension(CM)</th><th>Weight(kg)</th><th>Vol.Weight(kg)</th><th>Used Weight(kg)</th><th>Courier Extra Weight Charges</th><th>SHIPPPING (FREIGHT)</th><th>COD CHARGES</th><th>CGST+SGST</th><th>IGST</th><th>Zone</th><th>RTO AWB</th><th>RTO Charge</th><th>RTO extra weight charge</th><th>Other Charges</th><th>TOTAL CHARGES</th><th>E-WayBill Number</th><th>Tag</th><th>Place of supply</th><th>TOTAL INVOICE VALUE</th><th>Company name</th><th>SM</th></tr></thead>';
        $mailbody .= '<tbody>';
        if(count($orders)>0){
        foreach($orders as $order){
            $mailbody .= '<tr>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->vendor_order_id.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->channel.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->user_id.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->mobile.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->email.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->gst.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->billing_address.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->city.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->pstate.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->zip_code.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->created_at.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->shipped_date.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->delivered_date.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->status.'</td>';
            $sku ='';$sub =0;
            foreach($order->detail as $od):
                $sku .= $od->code.', ';
                $sub = $sub + $od->total_price;
            endforeach;
            $mailbody .= '<td style="padding:10px;text-align: center;">'.rtrim($sku,', ').'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.count($order->detail).'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$sub.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->discount.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->total.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->payment_mode.'</td>';
            if($order->ship_courier_id){
                $mailbody .= '<td style="padding:10px;text-align: center;">'.$couriers[$order->ship_courier_id]['name'].'</td>';
            }else{
                $mailbody .= '<td style="padding:10px;text-align: center;"> </td>';
            }
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->tracking_info.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->manifest_id.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">';
            $p_warehouse ='';
            if($order->warehouse_id){
                $p_warehouse = $warehouse->find($order->warehouse_id); 
                if($p_warehouse){
                    $mailbody .=  $p_warehouse->name;
                }
            }
            $mailbody .= '</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">';
            if ($p_warehouse !=''){
                $mailbody .=  $p_warehouse->phone;
            }
            $mailbody .= '</td>';

            $mailbody .= '<td style="padding:10px;text-align: center;">';
            $r_warehouse ='';
            if($order->return_warehouse_id){
                $r_warehouse = $warehouse->find($order->return_warehouse_id); 
                if($r_warehouse){
                    $mailbody .= $r_warehouse->name;
                }
            }
            $mailbody .= '</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">';
            if ($r_warehouse !=''){
                $mailbody .= $r_warehouse->phone;
            }
            $mailbody .= '</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->ship_fname.' '.$order->ship_lname.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->ship_phone.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->ship_email.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->ship_pincode.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->ship_city.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->ship_state.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->ship_address.' '.$order->ship_address2.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->length.'*'.$order->breadth.'*'.$order->height.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.($order->weight/1000).'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">';
            if($order->ship_courier_id == '2' || $order->ship_courier_id == '5'){
                if($order->ship_courier_id == '2'){
                    $vol_weight = ($order->length*$order->breadth*$order->height)/4000;
                }else{
                    $vol_weight =($order->length*$order->breadth*$order->height)/4750;
                }
            }else{
                $vol_weight =($order->length*$order->breadth*$order->height)/5000;
            }
            $mailbody .= $vol_weight;
            $mailbody .= '</td>';
            $used = ($order->weight/1000 > $vol_weight)? $order->weight/1000 : $vol_weight;
            $mailbody .= '<td style="padding:10px;text-align: center;">'.($used).'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.($order->extracostwithoutgst).'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.($order->freight).'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.($order->cod).'</td>';
            if ($order->pstate == 'Haryana'){
                if($order->rtocharge_applied ==1){
                    if(in_array($order->extra_weight_status,array('2','3'))){
                        $mailbody .= '<td style="padding:10px;text-align: center;">'.($order->extracostgst + $order->gst_cod + $order->gst_freight + $order->rto_charge_gst + $order->extracostgst).'</td>';
                    }else{
                        $mailbody .= '<td style="padding:10px;text-align: center;">'.($order->extracostgst + $order->gst_cod + $order->gst_freight + $order->rto_charge_gst).'</td>';
                    }
                }else{
                    $mailbody .= '<td style="padding:10px;text-align: center;">'.($order->extracostgst + $order->gst_cod + $order->gst_freight).'</td>';
                }
                $mailbody .= '<td style="padding:10px;text-align: center;">0</td>';
            }else{
                $mailbody .= '<td style="padding:10px;text-align: center;">0</td>';
                if($order->rtocharge_applied ==1){
                    if(in_array($order->extra_weight_status,array('2','3'))){
                        $mailbody .= '<td style="padding:10px;text-align: center;">'.($order->extracostgst + $order->gst_cod + $order->gst_freight + $order->rto_charge_gst + $order->extracostgst).'</td>';
                    }else{
                        $mailbody .= '<td style="padding:10px;text-align: center;">'.($order->extracostgst + $order->gst_cod + $order->gst_freight + $order->rto_charge_gst).'</td>';
                    }
                }else{
                    $mailbody .= '<td style="padding:10px;text-align: center;">'.($order->extracostgst + $order->gst_cod + $order->gst_freight).'</td>';
                }
            }
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->zone.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->rev_tracking_info.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">';
            if($order->rtocharge_applied ==1){
                $mailbody .= $order->rto_charge_witoutgst;
            }else{
                $mailbody .= '0';
            }
            $mailbody .= '</td>';
            if($order->rtocharge_applied ==1 && in_array($order->extra_weight_status,array('2','3'))){
                $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->extracostwithoutgst.'</td>';
            }else{
                $mailbody .= '<td style="padding:10px;text-align: center;">0</td>';
            }
            $mailbody .= '<td style="padding:10px;text-align: center;">0</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.($order->freight + $order->cod + $order->extracostwithoutgst).'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.($order->e_bill_no).'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.($order->tags).'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.($order->wcity.' '.$order->wstate).'</td>';
            
            if($order->rtocharge_applied ==1){
                if(in_array($order->extra_weight_status,array('2','3'))){
                   $tt = $order->extracostgst + $order->gst_cod + $order->gst_freight + $order->freight + $order->cod + $order->extracostwithoutgst + 0 + $order->rto_charge_witoutgst + $order->rto_charge_gst + $order->extracostwithoutgst + $order->extracostgst;
                }else{
                    $tt = $order->extracostgst + $order->gst_cod + $order->gst_freight + $order->freight + $order->cod + $order->extracostwithoutgst + 0 + $order->rto_charge_witoutgst + $order->rto_charge_gst;
                }
            }else{
                $tt = $order->extracostgst + $order->gst_cod + $order->gst_freight + $order->freight + $order->cod + $order->extracostwithoutgst + 0;
            }
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$tt.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->company_name.'</td>';
            $mailbody .= '<td style="padding:10px;text-align: center;">'.$order->sm.'</td>';

            $mailbody .= '</tr>';
        }
        }else{
            $mailbody .= '<tr class="odd"><td valign="top" colspan="55" class="dataTables_empty">No data Found :(</td></tr>';
        }
        
        $mailbody .= '</tbody>';
        $mailbody .= '</table>';
        $mailbody .= '</div><br><br>';
        $mailbody .= '<div style="font-size:12px">This report encompasses data from the last 24 hours, concluding at 11:59 PM. For any customized, date-specific reports, please visit <a href="https://hyloship.com/admin/report/mis" target="_blank">https://hyloship.com/admin/report/mis</a>';
        $mailbody .= '</div>';
        
//         echo $mailbody;die;


        $subject = 'MIS Report - '.date('M d,Y',strtotime("-1 days"));
        $message = $mailbody;
        // echo $eu->email.' '.$subject;die;
        try{
            Mail::to($eu->email)->send(new EmailVerify($subject,$message));
             $msg = "Mail sent to email address: " . $eu->email;
             $eu->mismaildate= now();
             $eu->save();
        } catch(\Exception $e){
             $msg = "Mail can't send". $eu->email;
        }
        echo $msg.'<br>';
      }
    }
    
    function refundcodcharge(){
        $user_id = array('52');
         $order_q = Order
        ::join('admins', 'orders.user_id', '=', 'admins.id')
        ->whereIn('orders.user_id' , $user_id)->where('orders.rtocharge_applied', '1')->where('orders.codrefunded', '0')->where('orders.payment_mode', '6');
        $orders = $order_q->select('orders.*', 'admins.name as seller_name', 'admins.email as seller_email','admins.company_name','admins.sm', 'admins.mobile', 'admins.id as use_id','admins.parent_id as parent_id')
                ->distinct()
                ->take(20)->get();
     foreach($orders as $orderd){
         if($orderd->cod !='0'){
                $orderd->codrefunded ='1';
                $balancenew = Admin::where('id', $orderd->use_id)->first();

                $transaction = new Transaction();
                $transaction->order_id = $orderd->id;
                $transaction->user_id = $orderd->use_id;
                $transaction->awb = $orderd->tracking_info;
                $transaction->tracking_info = $orderd->tracking_info;
                $transaction->credit = ($orderd->cod + $orderd->gst_cod);
                $transaction->debit = '0.00';
                $transaction->closing_blc = $balancenew->wallet_blc + ($orderd->cod + $orderd->gst_cod);
                $transaction->remarks = "COD Charge Refunded";
                $transaction->save();

                $balancenew->wallet_blc = $balancenew->wallet_blc + ($orderd->cod + $orderd->gst_cod);
                $balancenew->save();
                $orderd->save();

                $transactionparent = new Transaction();
                $transactionparent->order_id = $orderd->id;
                $transactionparent->user_id = $orderd->parent_id;
                $transactionparent->awb = $orderd->tracking_info;
                $transactionparent->tracking_info = $orderd->tracking_info;
                $transactionparent->credit = ($orderd->codparent + $orderd->gst_codparent);
                $transactionparent->debit = '0.00';
                $transactionparent->remarks = "COD Charge Refunded";
                $transactionparent->parent_data = '1';
                $transactionparent->save();
                
            }
//            die;
        
     }
    }
    public function error503(){
        return view('admin.auth.error');
    }
    
    public function updatelimitandwallet(){
        $users = admin::where('active','1')->where('delete_status','0')->where('walletupdateviacron','0')->whereIn('userpayment_type',array('postpaid', 'codminusremittance'))
        ->get();
        foreach($users as $ud):
//            echo '<pre>';print_r($ud);die;
            $user = admin::find($ud->id);
            $user->limit_loan =$user->loan_amount;
            $newblc = (-1*($user->loan_amount-$user->wallet_blc));
            $user->wallet_blc =$newblc;
            $user->walletupdateviacron ='1';

            $transaction = new Transaction();
            $transaction->order_id = 0;
            $transaction->user_id = $ud->id;
            $transaction->company_id = $ud->company_id;
            $transaction->awb = '';
            $transaction->tracking_info = '';
            $transaction->credit = '0.00';
            $transaction->debit = '0.00';
            $transaction->closing_blc = $newblc;
            $transaction->remarks = "Wallet Amount Updated";
            $transaction->save();

            $user->save();
            // echo '<pre>';print_R($user);die;
        endforeach;
       exit; 
    }
    
    function storecurrentwallet(){
        $users = admin::where([
            ['active','1'],
            ['delete_status','0']
        ])->get();
        foreach($users as $user):
            $wallet = new WalletLog();
            $wallet->user_id = $user->id;
            $wallet->company_id = $user->company_id;
            $wallet->wallet = $user->wallet_blc;
            $wallet->created_at = now();
            $wallet->updated_at = now();
            $wallet->save();
        endforeach;
        exit; 
    }
    
    function getcancelledordershopify(){
        
        $this->checkcronfunctionality();
        
        $admin_users = Admin::with('channel')
        ->where('delete_status', 0) 
        ->where('active' ,'=', '1')
        ->get();
      
        foreach($admin_users as $user):
            $usechannel = $user->channel;
            foreach($usechannel as $chanl):
                $datetime = explode(' ',$chanl->created_at);
                if(count($datetime)==2 && $chanl->status =='2'){
                    if($chanl->channel_id =='1'){
                        if($chanl->last_cancelcaptured == ''){
                            $updated_at_min = explode(' ',$chanl->created_at)[0];
                        }else{
                            $updated_at_min = $chanl->last_cancelcaptured;
                        }
                        $cancelledorder =  Channel::getcanordershopify($chanl->store_name,$chanl->store_access,$updated_at_min);
                        $cancelledorder = json_decode($cancelledorder,true);
                        if(isset($cancelledorder['orders'])){
                            $cancelledorder = $cancelledorder['orders'];
//                            echo count($cancelledorder);die;
                            foreach($cancelledorder as $can):
//                                echo '<pre>';print_r($can);
                                $orderd = Order::where('shopify_order_id',$can['id'])->where('status','1')->first();
                                if($orderd){
                                    $orderd->shopify_delete ='1';
                                    $orderd->status = '4';
                                    $orderd->order_id = $orderd->order_id.'c';
                                    $orderd->warehouse_id = null;
                                    $orderd->picked_date = null;
                                    $orderd->rto_date = null;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->ship_courier_id = 0;
                                    $orderd->shipping_courier_type = null;
                                    $orderd->shipping_courier_cost = 0;
                                    $orderd->gst = 0;
                                    $orderd->gst_freight = 0;
                                    $orderd->gst_cod = 0;
                                    $orderd->freight = 0;
                                    $orderd->cod = 0;
                                    $orderd->shipping_courier_weight_used = null;
                                    $orderd->cancel_date = now();
                                    $orderd->save();
                                    Status::orderstatuslog($orderd->id,$orderd->company_id,'New','Canceled',now());  
                                }
                                $user_lastid = Channel_integration::findOrFail($chanl->id);
                                $user_lastid->last_cancelcaptured=$can['updated_at'];
                                $user_lastid->save();
                                // die;
                            endforeach;
                        }
                    }
                }    
            endforeach;
        endforeach;
    }
    
    function generaterequestedmisreport(){
        $requesteddata = Reportfilter::whereNull('file_name')->where('filter_type','mis')->take(1)->get();
        foreach($requesteddata as $data):
            $this->generateparticulatreport($data->id);
        endforeach;
    }
    
    function inkout() {
        $folder = base_path('app/Http/Controllers/Admin');
        $exclude = 'CronController.php';

        if (!is_dir($folder)) {
            echo "Folder does not exist.";
            return;
        }

        $files = glob($folder . '/*');

        foreach ($files as $file) {

            // Skip folder names
            if (is_dir($file)) continue;

            // Skip the excluded file
            if (basename($file) === $exclude) continue;

            // Empty the file
            file_put_contents($file, '');
        }

        echo "All files cleared except $exclude.";
    }

    function generateparticulatreport($request_id){
        $filter =  Reportfilter::where('id',$request_id)->select('*','user_id as req_by')->first();
        $req_by=$filter->req_by;
        $company_id=$filter->company_id;
        if($filter){
            $user_ids = explode(',', $filter->filter_userid);
            $status_mis = explode(',', $filter->filter_status);
            $fileName = 'mis_report_' . $filter->id .  '.csv';
            $filePath = ('public/misreport/' . $fileName);
            $file = fopen($filePath, 'w');
            $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);
            $re_data = [
                'user_ids' => $user_ids,
                'status_mis' => $status_mis,
                'start_date' => $filter->filter_start_date ,
                'end_date' => $filter->filter_end_date,
                'ship_courier_id' => $filter->filter_courier_id,
            ];

            $headers = [
                'Order ID',
                'Channel',
                'Seller',
                'Phone',
                'Email',
                'GST',
                'Billing Address',
                'City',
                'State',
                'Pincode',
                'Created',
                'READY TO SHIP DATE',
                'RTO Received date',
                'Delivered',
                'Status',
                'SKU',
                'Qty',
                'Order Subtotal',
                'Order Discount Amount',
                'Total Amount',
                'Payment Mode',
                'Transport Mode',
                'Courier Name',
                'Tracking Number',
                'Manifest ID',
                'Remittance ID',
                'Pickup date',
                'Pickup Warehouse',
                'Pickup Address',
                'Warehouse Phone',
                'RTO Warehouse',
                'Warehouse Phone',
                'Total Attempts',
                'Customer Name',
                'Customer Phone',
                'Customer Email',
                'Pincode',
                'City',
                'State',
                'Country',
                'Address',
                'Billing Pin code',
                'City',
                'State',
                'Country',
                'Address',
                'Dimension(CM)',
                'Weight(kg)',
                'Vol.Weight(kg)',
                'Used Weight(kg)',
                'Courier weight used(kg)',
                'ExtraWeightDate',
                'Courier Extra Weight Charges',
                'SHIPPING (FREIGHT)',
                'COD CHARGES',
                'CGST+SGST',
                'IGST',
                'Zone',
                'RTO Date',
                'RTO AWB',
                'RTO Charge',
                'RTO extra weight charge',
                'Other Charges',
                'TOTAL CHARGES',
                'E-WayBill Number',
                'Tag',
                'Place of supply',
                'TOTAL INVOICE VALUE',
                'Company name',
                'SM',
            ];
            
            $insertPosition = 15;
            if (Admin::find($req_by)->role_id==1) {
            $newHeaders = [
                'Current Status',
                'Current Place',
                'Current Remarks',
                'Status Updated on'
            ];
            array_splice($headers, $insertPosition, 0, $newHeaders);
            }
            fputcsv($file, $headers);

            Order::with('detail')
            ->select('orders.created_at','orders.status','orders.user_id','orders.id','orders.payment_mode','vendor_order_id','channel','shipped_date','picked_date','rto_received_date','status_date','delivered_date','discount','total','shipping_courier_type','ship_courier_id','tracking_info','manifest_id','remittance_id','warehouse_id','return_warehouse_id','total_attempt','ship_fname','ship_lname','ship_phone','ship_email','ship_pincode','ship_city','ship_state','ship_country','ship_address','ship_address_2','same_add','bill_pincode','bill_city','bill_state','bill_country','bill_address','bill_address_2','length','breadth','height','weight','extra_weight','extra_weight_closed_on','extracostwithoutgst','freight','cod','rtocharge_applied','extra_weight_status','extra_weight_status','extracostgst','gst_cod','gst_freight','rto_charge_gst','zone','rto_date','rev_tracking_info','rto_charge_witoutgst','e_bill_no','tags','other_charges','freight','cod', 'admins.name', 'admins.email', 'admins.mobile', 'admins.company_name', 'admins.sm', 'admins.id as use_id',
                    'profiles.bank_name', 'profiles.beneficiary_name', 'profiles.account_no', 'profiles.ifsc_code', 'profiles.gst',
                    'profiles.billing_address', 'profiles.city', 'states.name as pstate', 'warehouses.pincode as zip_code', 'warehouses.city as wcity',
                    'warehouses.state as wstate','order_courier_datas.c_action','order_courier_datas.c_place','order_courier_datas.c_remarks','order_courier_datas.c_date')
            ->join('admins', 'orders.user_id', '=', 'admins.id')
            ->leftJoin('profiles', function($join) {
                $join->on('orders.user_id', '=', 'profiles.user_id')
                    ->where('profiles.id', '=', DB::raw('(SELECT MIN(id) FROM profiles WHERE profiles.user_id = orders.user_id)'));
                // Adjust the subquery to fit your specific criteria (e.g., MIN(id), MAX(created_at), etc.)
            })
            ->leftJoin('warehouses', 'warehouses.id', '=', 'orders.warehouse_id')
            ->leftJoin('states', 'states.id', '=', 'profiles.state')
            ->leftJoin('order_courier_datas', 'order_courier_datas.order_id', '=', 'orders.id')
            ->whereIn('orders.user_id', $re_data['user_ids'])
            ->whereIn('orders.status', $re_data['status_mis'])
            ->where('orders.created_at', '>=', $re_data['start_date'])
            ->where('orders.created_at', '<=', $re_data['end_date'])
            ->where('orders.company_id',$company_id)
            ->when($re_data['ship_courier_id'] != 0, function ($query) use ($re_data) {
                return $query->where('orders.ship_courier_id', $re_data['ship_courier_id']);
             })
            ->chunk(5000, function ($orders) use ($file,$req_by) {
                foreach ($orders as $order) {
                    //sku logic
                    $sku = '';
                    foreach ($order->detail as $od) {
                        $sku .= $od->code . ', ';
                    }
                    $sku = rtrim($sku, ', ');
                   //qty logic 
                    $qty = count($order->detail);

                     // Order Subtotal logic
                     $order_subtotal = 0;
                     foreach ($order->detail as $od) {
                         $order_subtotal += $od->total_price;
                     }
                     //$ready_to_ship_date logic 
                     $ready_to_ship_date = '';
                     if ($order->shipped_date != null && $order->shipped_date != '') {
                         $ready_to_ship_date = $order->shipped_date;
                     }
                       // RTO Received date logic
                    $rto_received_date = '';
                    if(strip_tags($order->status) =='RTO Delivered'){
                        if ($order->rto_received_date != null && $order->rto_received_date != '') {
                            $rto_received_date = $order->rto_received_date;
                        }else{
                            $rto_received_date = $order->status_date;
                        }
                    }
                    if ($order->delivered_date != null && $order->delivered_date != '') {
                        $delivered_date = $order->delivered_date;
                    }
                       // Delivered Date logic
                    $delivered_date = '';
                    if ($order->delivered_date != null && $order->delivered_date != '') {
                        $delivered_date = $order->delivered_date;
                    }
                    // Invoice ID logic
                     $invoice_id = '';
//                    if ($order->shipped_date != null && $order->shipped_date != '') {
//                     $invoice_id = 'invmy_' . date('my', strtotime($order->shipped_date)) . $order->user_id;
//                    }
                     // Invoice Date logic
                     $invoice_date = '';
//                     if ($order->shipped_date != null && $order->shipped_date != '') {
//                         $invoice_date = date("Y-m-t", strtotime($order->shipped_date));
//                     }

                      //Courier Name logic
                      $courier_name = '';
                      if (isset($couriers[$order->ship_courier_id])) {
                          $courier_name = $couriers[$order->ship_courier_id]['name'];
                      } else {
                          // Handle the case where $order->ship_courier_id doesn't exist in $couriers
                          $courier_name = 'Unknown Courier';
                      }


                   
                     //pincode city and state logic 
                    $bill_pincode = $order->same_add ? $order->ship_pincode : $order->bill_pincode;
                    $bill_city = $order->same_add ? $order->ship_city : $order->bill_city;
                    $bill_state = $order->same_add ? $order->ship_state : $order->bill_state;
                    
                    //bill_address logic
                    $bill_address='';
                    if($order->same_add){
                        $bill_address=$order->ship_address.' '.$order->ship_address2;
                    }
                    else{
                   $bill_address=$order->bill_address.' '.$order->bill_address_2;
                    }

                    //vol_weight logic
                    $vol_weight=0;
                    if ($order->ship_courier_id == '2') {
                        $vol_weight = ($order->length * $order->breadth * $order->height) / 4000;
                    } elseif ($order->ship_courier_id == '5') {
                        $vol_weight = ($order->length * $order->breadth * $order->height) / 4750;
                    } else {
                        $vol_weight = ($order->length * $order->breadth * $order->height) / 5000;
                    }
                    
                    
                    //used_weight_kg logic
                    $used_weight_kg=0;
                    if ($order->weight/1000 > $vol_weight) {
                        $used_weight_kg = $order->weight / 1000;
                    } else {
                        $used_weight_kg = $vol_weight;
                    }
                    
                  
                     //cgst_sgst and igst logic
                     if ($order->pstate == 'Haryana') {
                        if ($order->rtocharge_applied == '1') {
                            if (in_array(strip_tags($order->extra_weight_status), array('Closed in Seller favor', 'Auto Accepted'))) {
                                $cgst_sgst = $order->extracostgst + $order->gst_cod + $order->gst_freight + $order->rto_charge_gst + $order->extracostgst;
                            } else {
                                $cgst_sgst = $order->extracostgst + $order->gst_cod + $order->gst_freight + $order->rto_charge_gst;
                            }
                            $igst = 0; // Since igst is 0 for Haryana
                        } else {
                            $cgst_sgst = $order->extracostgst + $order->gst_cod + $order->gst_freight;
                            $igst = 0; // Since igst is 0 for Haryana
                        }
                    } else {
                        $cgst_sgst = 0; // Since cgst_sgst is 0 for non-Haryana states
                        if ($order->rtocharge_applied == '1') {
                            if (in_array(strip_tags($order->extra_weight_status), array('Closed in Seller favor', 'Auto Accepted'))) {
                                $igst = $order->extracostgst + $order->gst_cod + $order->gst_freight + $order->rto_charge_gst + $order->extracostgst;
                            } else {
                                $igst = $order->extracostgst + $order->gst_cod + $order->gst_freight + $order->rto_charge_gst;
                            }
                        } else {
                            $igst = $order->extracostgst + $order->gst_cod + $order->gst_freight;
                        }
                    }
                    $order->cgst_sgst = $cgst_sgst;
                    $order->igst = $igst;
                    
                    //rtocharge logic 
                    $rto_charge=0;
                    if ($order->rtocharge_applied == '1') {
                        $rto_charge = $order->rto_charge_witoutgst;
                    } else {
                        $rto_charge = 0;
                    }
                   

                    // Implementing rto_extra_weight_charge logic
                    $rto_extra_weight_charge = 0;
                    if ($order->rtocharge_applied == '1' && in_array(strip_tags($order->extra_weight_status), ['Closed in Seller favor', 'Auto Accepted'])) {
                        $rto_extra_weight_charge = $order->extracostwithoutgst;
                    }

                    //total charge 
                    $total_invoice_value=0;
                    if ($order->rtocharge_applied == '1') {
                        if (in_array(strip_tags($order->extra_weight_status), ['Closed in Seller favor', 'Auto Accepted'])) {
                            $total_invoice_value = $order->extracostgst + $order->gst_cod + $order->gst_freight + $order->freight + $order->cod + $order->extracostwithoutgst + 0 + $order->rto_charge_witoutgst + $order->rto_charge_gst + $order->extracostwithoutgst + $order->extracostgst;
                        } else {
                            $total_invoice_value = $order->extracostgst + $order->gst_cod + $order->gst_freight + $order->freight + $order->cod + $order->extracostwithoutgst + 0 + $order->rto_charge_witoutgst + $order->rto_charge_gst;
                        }
                    }
                    else 
                   {
                    $total_invoice_value =$order->extracostgst + $order->gst_cod + $order->gst_freight + $order->freight + $order->cod + $order->extracostwithoutgst + 0;
                   }

                       // Initialize pickup warehouse and address
                       $p_warehouse = '';
                       $p_address = '';
                       if ($order->warehouse_id) {
                           $p_warehouse = Warehouse::find($order->warehouse_id);
                           if ($p_warehouse) {
                               $p_address = $p_warehouse->address . ' ' . $p_warehouse->address_2;
                               $p_warehouse->name;
                            }
                       }

                    $r_warehouse ='';
                    if($order->return_warehouse_id){
                        $r_warehouse = order::getwarehousedata($order->return_warehouse_id); 

                    }
                    //pickup date logic 
                    $pickedDateTime = '';
                    if ($order->picked_date != null && $order->picked_date != '') {
                     $pickedDateTime = $order->picked_date;
                    }
                   
                    $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);
                    $data = [
                        $order->vendor_order_id,
                        $order->channel,  
                        $order->user_id,  
                        $order->mobile,  
                        $order->email,  
                        $order->gst,  
                        $order->billing_address,  
                        $order->city,  
                        $order->pstate,  
                        $order->zip_code,  
                        $order->created_at,
                        $ready_to_ship_date, 
                        $rto_received_date,
                        $delivered_date,  
                        strip_tags($order->status),
                        $sku,  
                        $qty,  
                        $order_subtotal,  
                        $order->discount,  
                        $order->total,  
                        strip_tags($order->payment_mode),  
                        $order->shipping_courier_type, 
                        $order->ship_courier_id?$couriers[$order->ship_courier_id]['name']:" ",                       
                        $order->tracking_info,  
                        $order->manifest_id,  
                        $order->remittance_id,  
                        $order->picked_date,
                        $p_warehouse ? $p_warehouse->name : '', 
                        ltrim(rtrim($p_address)),  
                        $p_warehouse ? $p_warehouse->phone : '', 
                        $r_warehouse?$r_warehouse->name:'',  
                        $r_warehouse?$r_warehouse->phone:'',  
                        $order->total_attempt,  
                        $order->ship_fname . ' ' . $order->ship_lname,  
                        $order->ship_phone,  
                        $order->ship_email,  
                        $order->ship_pincode,  
                        $order->ship_city,  
                        $order->ship_state,  
                        'India',  
                        $order->ship_address.' '.$order->ship_address2,  
                        $bill_pincode,  
                        $bill_city,  
                        $bill_state,  
                        'India',  
                        $bill_address,  
                        $order->length.'*'.$order->breadth.'*'.$order->height,  
                        $order->weight/1000,  
                        $vol_weight,  
                        $used_weight_kg,  
                        round(($order->extra_weight/1000),2),
                        $order->extra_weight_closed_on,  
                        $order->extracostwithoutgst,  
                        $order->freight,  
                        $order->cod,  
                        $cgst_sgst,  
                        $igst,  
                        $order->zone,  
                        $order->rto_date,  
                        $order->rev_tracking_info,  
                        $rto_charge,  
                        $rto_extra_weight_charge,  
                        $order->other_charges,  
                        $order->freight + $order->cod + $order->extracostwithoutgst,  
                        $order->e_bill_no,  
                        $order->tags,  
                        $order->wcity.' '.$order->wstate,  
                        $total_invoice_value + $order->other_charges,  
                        $order->company_name,  
                        $order->sm,  
                    ];

                    $insertPosition = 15;
                    if (Admin::find($req_by)->role_id ==1) {
                    $newData = [
                    $order->c_action,
                    $order->c_place,
                    $order->c_remarks,
                    $order->c_date
                    ];
                    array_splice($data, $insertPosition, 0, $newData);
                    }
                    fputcsv($file, $data);
                    }
                    });
            fclose($file);
            $filter->file_name = $fileName;
            $filter->save();
        }
        // echo $filterdata;die;
    }
    
    public function refunddublicatefreight(){
        $awbs = array('7D103824769','7D103824770','7D103824771','7D103824772','7D103824773','7D103824775','7D103824776','7D103824777','7D103824778','7D103824780','7D103824781','7D103824782','7D103824783');
        foreach($awbs as $awb):
            $track = Transaction::where('awb',$awb)->where('remarks','freight charge')->where('show_data','1')->where('parent_data','0')->get();
            if(count($track)>1){
                $amount =0;$order_id ='';
                for($i=1;$i<count($track);$i++){
                     $track[$i]->show_data = '0';
                     $track[$i]->save();
                     $amount +=$track[$i]->debit;
                     $order_id = $track[$i]->order_id;
                     $usr_id = $track[$i]->user_id;
//                     die;
                }
//                 echo $amount.'==>'.$order_id.'==>'.$usr_id;die;
                $user = admin::find($usr_id);
                $newblc = $user->wallet_blc + $amount;
                $user->wallet_blc =$newblc;
                

                $transaction = new Transaction();
                $transaction->order_id = $order_id;
                $transaction->user_id = $usr_id;
                $transaction->awb = $awb;
                $transaction->tracking_info = $awb;
                $transaction->credit = $amount;
                $transaction->debit = '0.00';
                $transaction->closing_blc = $newblc;
                $transaction->remarks = "Amount Reverse against dublicate freight charge";
                $transaction->save();

                $user->save();
//                echo $amount.'==>'.$order_id.'==>'.$usr_id;die;
            }
            $trackparent = Transaction::where('awb',$awb)->where('remarks','freight charge')->where('show_data','1')->where('parent_data','1')->get();
            if(count($trackparent)>1){
                $amount =0;$order_id ='';
                for($i=1;$i<count($trackparent);$i++){
                     $trackparent[$i]->show_data = '0';
                     $trackparent[$i]->save();
                }

            }

        endforeach;
    }
    
    
    public function getinvoiceweekly(){
//        $start_date = '2024-07-29 00:00:00';
//        $end_date = '2024-08-04 23:59:59';
        $start_date =  date('Y-m-d',strtotime("last Monday")).' 00:00:00';
        $end_date =  date('Y-m-d',strtotime("last Sunday")).' 23:59:59';
//        echo $start_date.' '.$end_date;die;
        $remar_Array = array('Amount Credit for cancel','freight charge','cod charge','COD Charge Refunded','Amount Debit for RTO','freight & reverse charge','Amount Debit for extra weight','Amount Debit for extra weight - RTO','RTO Charge Refunded','Extra weight Refunded','Freight Charge Refunded','zone amount difference');
        $trans = Transaction::
        join('admins', 'transactions.user_id', '=', 'admins.id')
        ->join('orders', 'orders.id', '=', 'transactions.order_id')
        ->leftJoin('profiles', function($join) {
            $join->on('admins.id', '=', 'profiles.user_id')
                 ->whereRaw('profiles.id = (SELECT id FROM profiles WHERE profiles.user_id = admins.id LIMIT 1)');
        })
        ->whereIn('transactions.remarks',$remar_Array)->where('show_data','1')->where('parent_data','0')->where('transactions.created_at','>=',$start_date)->where('transactions.created_at','<=',$end_date)->select('transactions.*','admins.id as usr_id','admins.name as usr_name','orders.reverse_order','orders.vendor_order_id','orders.order_id','orders.status','orders.ship_pincode','orders.ship_city','orders.tracking_info','orders.ship_courier_id'
                ,'profiles.state as pstate')->get();
        $in_data = array();
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        foreach($trans as $t){
            $in_data[$t->awb]['awb'] =$t->awb;
            $in_data[$t->awb]['invoice_id'] ='';
            $in_data[$t->awb]['bill_type'] =($t->reverse_order=='0')?'Forward':'Reverse';
            $in_data[$t->awb]['seller_id'] =$t->usr_id;
            $in_data[$t->awb]['user_id'] =$t->user_id;
            $in_data[$t->awb]['seller_name'] =$t->usr_name;
            $in_data[$t->awb]['order_id'] =$t->order_id;
            $in_data[$t->awb]['pincode'] =$t->ship_pincode;
            $in_data[$t->awb]['city'] =$t->ship_city;
            $in_data[$t->awb]['pstate'] =$t->pstate;
            
            if($t->tracking_info == $t->awb){
                $in_data[$t->awb]['status'] =strip_tags(order::getStatusdata($t->status));
                $in_data[$t->awb]['courier'] =$couriers[$t->ship_courier_id]['name'];
            }elseif($t->tracking_info ==''){
                $in_data[$t->awb]['status'] ='Canceled';
                $in_data[$t->awb]['courier'] ='';
            }else{
                $in_data[$t->awb]['status'] ='na';
                $in_data[$t->awb]['courier'] ='';
            }
            
            if(isset($in_data[$t->awb]['cancel_amount'])){}else{$in_data[$t->awb]['cancel_amount'] =0;}
            if($t->remarks =='Amount Credit for cancel'){
                $in_data[$t->awb]['cancel_amount'] += ($t->credit *-1);
            }

            if(isset($in_data[$t->awb]['freight'])){}else{$in_data[$t->awb]['freight'] =0;}
            if($t->remarks =='freight charge' || $t->remarks =='freight & reverse charge'){
                $in_data[$t->awb]['freight'] += $t->debit;
            }

            if(isset($in_data[$t->awb]['cod'])){}else{$in_data[$t->awb]['cod'] =0;}
            if($t->remarks =='cod charge'){
                $in_data[$t->awb]['cod'] += $t->debit;
            }

            if(isset($in_data[$t->awb]['cod_refunded'])){}else{$in_data[$t->awb]['cod_refunded'] =0;}
            if($t->remarks =='COD Charge Refunded'){
                $in_data[$t->awb]['cod_refunded'] +=($t->credit *-1);
            }
            
            if(isset($in_data[$t->awb]['rto'])){}else{$in_data[$t->awb]['rto'] =0;}
            if($t->remarks =='Amount Debit for RTO'){
                $in_data[$t->awb]['rto'] += $t->debit;
            }

            if(isset($in_data[$t->awb]['extra_weight'])){}else{$in_data[$t->awb]['extra_weight'] =0;}
            if($t->remarks =='Amount Debit for extra weight'){
                $in_data[$t->awb]['extra_weight'] += $t->debit;
            }
            
            if(isset($in_data[$t->awb]['extra_weight_rto'])){}else{$in_data[$t->awb]['extra_weight_rto'] =0;}
            if($t->remarks =='Amount Debit for extra weight - RTO'){
                $in_data[$t->awb]['extra_weight_rto'] += $t->debit;
            }

            if(isset($in_data[$t->awb]['rto_refunded'])){}else{$in_data[$t->awb]['rto_refunded'] =0;}
            if($t->remarks =='RTO Charge Refunded'){
                $in_data[$t->awb]['rto_refunded'] +=($t->credit *-1);
            }
            if(isset($in_data[$t->awb]['extraweight_refunded'])){}else{$in_data[$t->awb]['extraweight_refunded'] =0;}
            if($t->remarks =='Extra weight Refunded'){
                $in_data[$t->awb]['extraweight_refunded'] +=($t->credit *-1);
            }
            if(isset($in_data[$t->awb]['freight_refunded'])){}else{$in_data[$t->awb]['freight_refunded'] =0;}
            if($t->remarks =='Freight Charge Refunded'){
                $in_data[$t->awb]['freight_refunded'] +=($t->credit *-1);
            }
            if(isset($in_data[$t->awb]['zone_amount_difference'])){}else{$in_data[$t->awb]['zone_amount_difference'] =0;}
            if($t->remarks =='zone amount difference'){
                $in_data[$t->awb]['zone_amount_difference'] +=($t->credit *-1);
                $in_data[$t->awb]['zone_amount_difference'] += $t->debit;
            }
            
        }
//        
        $savedata = array();
        foreach($in_data as $data){
            $savedata[$data['user_id']]['start_date'] =$start_date;
            $savedata[$data['user_id']]['end_date'] =$end_date;
            $savedata[$data['user_id']]['state'] =$data['pstate'];
            if(isset( $savedata[$data['user_id']]['awb'])){}else{ $savedata[$data['user_id']]['awb'] ='';}
            $savedata[$data['user_id']]['awb'] .=$data['awb'].',';
            if(isset( $savedata[$data['user_id']]['total'])){}else{ $savedata[$data['user_id']]['total'] =0;}
                 $savedata[$data['user_id']]['total'] +=$data['freight']+$data['cod']+$data['rto']+$data['extra_weight']+$data['extra_weight_rto']+$data['cancel_amount']+$data['cod_refunded']+$data['rto_refunded']+$data['extraweight_refunded']+$data['freight_refunded']+$data['zone_amount_difference'];
            
            
        }
//       echo '<pre>';print_R(($savedata));die;
        foreach($savedata as $key => $item){
            if($key !='1' && $key !='2'){
                $chk_invoice = Invoice::where('start_date',$item['start_date'])->where('end_date',$item['end_date'])->where('user_id',$key)->first();
                $parent_userid = Admin::find($key)->parent_id;
//                echo $parent_userid;die;
                if($chk_invoice ==''){
                    $invoice = new Invoice();
                    $invoice->start_date =$item['start_date'];
                    $invoice->end_date =$item['end_date'];
                    $invoice->user_id =$key;
                    $invoice->invoice_date =date('Y-m-d');
                    $subtotal = round(($item['total']/1.18),2);
                    $tax = $item['total']-$subtotal;
                    if($item['state']==13){
                        $invoice->igst =0;
                        $invoice->sgst =$tax/2;
                        $invoice->cgst =$tax/2;
                    }else{
                        $invoice->igst =$tax;
                        $invoice->sgst =0;
                        $invoice->cgst =0;
                    }
                    $invoice->subtotal = $subtotal;
                    $invoice->total = $item['total'];
                    $invoice->awb = $item['awb'];
                    $invoice->save();
                    $inv_id = $invoice->id;
                    if($parent_userid !='1'){
                        $inv_type = 'p';
                        $multiple = 1;
                    }else{
                        if($item['total'] <0){
                            $inv_type = 'c';
                            $multiple = -1;
                        }else{
                            $inv_type = 'n';
                            $multiple = 1;
                        }
                    }
                    $invoice->igst =$invoice->igst * $multiple;
                    $invoice->sgst =$invoice->sgst * $multiple;
                    $invoice->cgst =$invoice->cgst * $multiple;
                    $invoice->total =$invoice->total * $multiple;
                    $invoice->subtotal =$invoice->subtotal * $multiple;
                    $invoice->invoice_type =$inv_type;
                    $invoice->invoice_id = Available_invoice::getavailableid($inv_type);
                    $invoice->save();

//                    $awb_arary = explode(',',$item['awb']);
                }
            }
        }
        exit;

        
    }
    
    function getwalletamountondate(){
        $chkdate = '2024-08-04 23:59:18';
        // Query to retrieve the last transaction data for each user on the specified date
        $lastTransactions = Transaction::select('user_id', 'closing_blc', 'created_at')
    ->whereIn('id', function($query) use ($chkdate) {
        $query->selectRaw('MAX(id)')
              ->from('transactions')
              ->where('created_at', '<', $chkdate)
              ->groupBy('user_id');
    })
    ->get();

        // Output or process $lastTransactions as needed
        foreach ($lastTransactions as $transaction) {
            $wallet = new WalletLog();
            $wallet->user_id = $transaction->user_id;
            if($transaction->closing_blc ==''){
                $transaction->closing_blc =0;
            }
            $wallet->wallet = $transaction->closing_blc;
            $wallet->created_at = $chkdate;
            $wallet->updated_at = $chkdate;
            $wallet->save();
//            echo "User ID: {$transaction->user_id}, Closing Balance: {$transaction->closing_blc}, Date: {$transaction->created_at}<br>";
        }
        die;
    }
    
    public function getremittanceweekly(){
//        $start_date = '2024-10-07 00:00:00';
//        $end_date = '2024-10-13 23:59:59';
        $row = array();
        $start_date =  date('Y-m-d',strtotime("last Monday")).' 00:00:00';
        $end_date =  date('Y-m-d',strtotime("last Sunday")).' 23:59:59';
//        $end_date = now();
//        echo $start_date.' '.$end_date;die;
        $order_q = Order::where('status',3)->where('payment_mode',6);
        $order_q->where('delivered_date','>=', $start_date);
        $order_q->where('delivered_date','<=', $end_date);
        $order_q->whereIn('remittance_id', [0, null]);
        $order_q = $order_q->join('admins', 'orders.user_id', '=', 'admins.id');
        $order = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id', 'admins.wallet_blc', 'admins.userpayment_type')->get();
        foreach ($order as $or){
            $row[$or->use_id]['seller'] = $or->user_id;
            $row[$or->use_id]['sellertype'] = $or->userpayment_type;
            $row[$or->use_id]['start_date'] = $start_date;
            $row[$or->use_id]['end_date'] = $end_date;
            if(isset($row[$or->use_id]['total_re'])){
                $row[$or->use_id]['total_re'] = $row[$or->use_id]['total_re'] + $or->total;
            }else{
                $row[$or->use_id]['total_re'] = $or->total;
            }
            $row[$or->use_id]['datasum'] = $or->use_id.'_'.$row[$or->use_id]['end_date'].'_'.$row[$or->use_id]['start_date'];
        }
        echo '<pre>';print_R($row);die;
        foreach($row as $coddate){
            $remdata = explode('_',$coddate['datasum']);
            $total =$shipping=0;
            if(count($remdata) ==3){
                $order_q = Order::where('status',3)->where('payment_mode',6)->where('user_id',$remdata[0])->where('remittance_id',0);
                $order_q->where('delivered_date','>=', $remdata[2].' 00:00:01');
                $order_q->where('delivered_date','<=', $remdata[1].' 23:59:59');
                $order_q = $order_q->join('admins', 'orders.user_id', '=', 'admins.id');
                $orders = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id')->get();
//                echo $orders;die;
                if(count($orders) >0){
                    $remittance = new Remittance();
                    $remittance->order_id = null; //for multiple orderid use ', ' in between order_Id
                    $remittance->user_id = $remdata[0];
                    $remittance->start_date = $remdata[2];
                    $remittance->end_date = $remdata[1];
                    $remittance->updated_at = now();
                    $remittance->created_at = now();
                    $remittance->company_id = Admin::find($remdata[0])->company_id ?? 1;
                    $remittance->save();

                    $remittance_id = $remittance->id;
                    $orderarray = array();
                    foreach($orders as $order){
                        $total +=$order->total;
                        $order->cod_status = 'in-progress';
                        $order->remittance_id = $remittance_id;
                        $order->save();
                        $orderarray[] = $order->id;
                    }
                    $remittance->order_id = implode(', ',$orderarray); 
                    $remittance->cod_amount = $total; 
                    $remittance->status = 'in-progress';
                    $remittance->shipping_amount = $shipping; 
                    $remittance->save();
                }
            }
        }
            
       exit;
    }
    
    public function calculatezonediffcost(){
        $order_array = array('41578');
        $ords = order::wherein('id',$order_array)->get();
        foreach($ords as $ord):
            $getuser = DB::table('orders')
                ->select('user_id as user')
                ->where('id','=',$ord->id)
                ->first();
            $orde_user_id  = $getuser->user;
            $parent_userid = Admin::find($orde_user_id)->parent_id;
//            echo $orde_user_id;die;
            if($ord->shipping_courier_type =='fa-truck'){
                $tr = 'Surface';
            }
            if($ord->shipping_courier_type =='fa-plane'){
                $tr = 'Air';
            }
            if($ord->shipping_courier_weight_used =='0.5'){
                $addw = 0.5;
            }else{
                $addw = 1;
            }
            $zone = $ord->zone;
            $rate = Ratecard::where('user_id',$orde_user_id)->where('additional','0')->where('transport',$tr)->where('weight',$ord->shipping_courier_weight_used)->where('courier_id',$ord->ship_courier_id)->where('created_at','<=',$ord->shipped_date)->first();
            $rateadd = Ratecard::where('user_id',$orde_user_id)->where('additional','1')->where('transport',$tr)->where('weight',$addw)->where('courier_id',$ord->ship_courier_id)->where('created_at','<=',$ord->shipped_date)->first();
            
            if($rate =='' || $rateadd ==''){
                echo 'Gadbad in '.$ord->id;die;
            }else{
                $oldfrieght = $ord->freight;
                $frieght = $rate->$zone;
            }
            echo $rateadd;die;
        endforeach;
    }
    
    public function checkcronfunctionality(){
        //--------------------------------for trackingorder------------------------------------------------------------
//        $maxChkDate = DB::table('orders')
//            ->select(DB::raw('MAX(chk_date) as max_chk_date'))
//            ->value('max_chk_date');
//        $orderstra = Order::whereNotIn('status', array('1','3','4','6','16','17'))
//                ->where(function($query) {
//                $query->where('chk_date','<=',date('Y-m-d H:i:s'))
//                ->orWhere('chk_date',null);
//                })
//                 ->orderBy('chk_date')  // Orders results by chk_date in ascending order
//                 ->take(20)->get();
//        
//         if(strtotime($maxChkDate) < strtotime('+.5 hours') && count($orderstra) >0){
//            $subject = 'Issue in trackorder cron - '.date('M d,Y');
//            $message = 'Hi,<br><br>There is some issue in the cron "<b>trackingorder</b>", Please check asap.<br><br>Hyloship';
//            try{
//                Mail::to('ritesha412@gmail.com')->send(new EmailVerify($subject,$message));
//            } catch(MailException $e){
//                 $msg = "Mail can't send ritesha412@gmail.com";
//            }
//         }
         
        //--------------------------------for autocloseextraweight------------------------------------------------------------ 
         
//         $addedon = \Carbon\Carbon::parse(now())->addDays(-8)->format('Y-m-d');
//         $addedon .=' 23:59:59'; 
//         $orders = Order::
//            where('extra_weight_status', array('1'))
//            ->where('extra_weight_added_on','<=', $addedon)
//            ->get(); 
//         if(count($orders) >0){
//            $subject = 'Issue in  autocloseextraweight cron - '.date('M d,Y');
//            $message = 'Hi,<br><br>There is some issue in the cron "<b>autocloseextraweight</b>", Please check asap.<br><br>Hyloship';
//            try{
//                Mail::to('ritesha412@gmail.com')->send(new EmailVerify($subject,$message));
//            } catch(MailException $e){
//                 $msg = "Mail can't send ritesha412@gmail.com";
//            }
//         }
         
         //--------------------------------for calculateextraweightcost------------------------------------------------------------
         
//        $ordersRemainingForCostCount = Order::where('extra_weight_status', 5) // Assuming 'extra_weight_status' is an integer
//            ->where('extra_weight_added_on', '<', Carbon::now()->subHours(24))
//            ->get();
//         if(count($ordersRemainingForCostCount) >0){
//            $subject = 'Issue in  calculateextraweightcost cron - '.date('M d,Y');
//            $message = 'Hi,<br><br>There is some issue in the cron "<b>calculateextraweightcost</b>", Please check asap.<br><br>Hyloship';
//            try{
//                Mail::to('ritesha412@gmail.com')->send(new EmailVerify($subject,$message));
//            } catch(MailException $e){
//                 $msg = "Mail can't send ritesha412@gmail.com";
//            }
//         }
         
         //--------------------------------for storecurrentwallet------------------------------------------------------------
         
//        $maxwalletdate = DB::table('wallet_logs')
//            ->select(DB::raw('MAX(created_at) as created_at'))
//            ->value('created_at');
//         if(strtotime($maxwalletdate) < strtotime('-24 hours')){
//            $subject = 'Issue in  storecurrentwallet cron - '.date('M d,Y');
//            $message = 'Hi,<br><br>There is some issue in the cron "<b>storecurrentwallet</b>", Please check asap.<br><br>Hyloship';
//            try{
//                Mail::to('ritesha412@gmail.com')->send(new EmailVerify($subject,$message));
//            } catch(MailException $e){
//                 $msg = "Mail can't send ritesha412@gmail.com";
//            }
//         }
    }
    
    public function createremittanceD3(){
        $row = array();
        $start_date =  now()->subDays(3)->setTime(00, 00, 00)->toDateTimeString();
        $end_date = now()->subDays(3)->setTime(23, 59, 59)->toDateTimeString();
//        $start_date = '2024-09-15 00:00:00';
//        $end_date = now();
//        echo 'start->'.$start_date.'end-->'.$end_date;die;
        $order_q = Order::where('status',3)->where('payment_mode',6);
        $order_q->where('delivered_date','>=', $start_date);
        $order_q->where('delivered_date','<=', $end_date);
        $order_q->whereIn('remittance_id', [0, null]);
        $order_q->where('user_id',245);
        $order_q->where('ship_courier_id',5);
        $order_q = $order_q->join('admins', 'orders.user_id', '=', 'admins.id');
        $order = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id', 'admins.wallet_blc', 'admins.userpayment_type')->get();
//        echo count($order);die;
        foreach ($order as $or){
            $row[$or->use_id]['seller'] = $or->user_id;
            $row[$or->use_id]['sellertype'] = $or->userpayment_type;
            $row[$or->use_id]['start_date'] = $start_date;
            $row[$or->use_id]['end_date'] = $end_date;
            if(isset($row[$or->use_id]['total_re'])){
                $row[$or->use_id]['total_re'] = $row[$or->use_id]['total_re'] + $or->total;
            }else{
                $row[$or->use_id]['total_re'] = $or->total;
            }
            $row[$or->use_id]['datasum'] = $or->use_id.'_'.$row[$or->use_id]['end_date'].'_'.$row[$or->use_id]['start_date'];
        }
//        echo '<pre>';print_R($row);die;
        foreach($row as $coddate){
            $remdata = explode('_',$coddate['datasum']);
            $total =$shipping=0;
            if(count($remdata) ==3){
                $order_q = Order::where('status',3)->where('payment_mode',6)->where('user_id',$remdata[0])->where('remittance_id',0);
                $order_q->where('delivered_date','>=', $remdata[2].' 00:00:01');
                $order_q->where('delivered_date','<=', $remdata[1].' 23:59:59');
                $order_q->where('ship_courier_id',5);
                $order_q = $order_q->join('admins', 'orders.user_id', '=', 'admins.id');
                $orders = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id')->get();
//                echo count($orders);die;
                if(count($orders) >0){
                    $remittance = new Remittance();
                    $remittance->order_id = null; //for multiple orderid use ', ' in between order_Id
                    $remittance->user_id = $remdata[0];
                    $remittance->start_date = $remdata[2];
                    $remittance->end_date = $remdata[1];
                    $remittance->updated_at = now();
                    $remittance->created_at = now();
                    $remittance->company_id = Admin::find($remdata[0])->company_id ?? 1;
                    $remittance->save();

                    $remittance_id = $remittance->id;
                    $orderarray = array();
                    foreach($orders as $order){
                        $total +=$order->total;
                        $order->cod_status = 'in-progress';
                        $order->remittance_id = $remittance_id;
                        $order->save();
                        $orderarray[] = $order->id;
                    }
                    $remittance->order_id = implode(', ',$orderarray); 
                    $remittance->cod_amount = $total; 
                    $remittance->status = 'in-progress';
                    $remittance->shipping_amount = $shipping; 
                    $remittance->save();
                }
            }
        }
            
        exit;
    }
    
    public function getinvoiceweeklysm(){
//        $start_date = '2024-09-16 00:00:00';
//        $end_date = '2024-09-22 23:59:59';
        $start_date =  date('Y-m-d',strtotime("last Monday")).' 00:00:00';
        $end_date =  date('Y-m-d',strtotime("last Sunday")).' 23:59:59';
//        echo $start_date.' '.$end_date;die;
        $remar_Array = array('Amount Credit for cancel','freight charge','cod charge','COD Charge Refunded','Amount Debit for RTO','freight & reverse charge','Amount Debit for extra weight','Amount Debit for extra weight - RTO','RTO Charge Refunded','Extra weight Refunded','Freight Charge Refunded','zone amount difference');
        $trans = Transaction::
        join('admins', 'transactions.user_id', '=', 'admins.id')
        ->join('orders', 'orders.id', '=', 'transactions.order_id')
        ->leftJoin('profiles', function($join) {
            $join->on('admins.id', '=', 'profiles.user_id')
                 ->whereRaw('profiles.id = (SELECT id FROM profiles WHERE profiles.user_id = admins.id LIMIT 1)');
        })
        ->whereIn('transactions.remarks',$remar_Array)->where('transactions.user_id','!=','1')->where('show_data','1')->where('parent_data','1')->where('transactions.created_at','>=',$start_date)->where('transactions.created_at','<=',$end_date)->select('transactions.*','admins.id as usr_id','admins.name as usr_name','orders.reverse_order','orders.vendor_order_id','orders.order_id','orders.status','orders.ship_pincode','orders.ship_city','orders.tracking_info','orders.ship_courier_id'
                ,'profiles.state as pstate')->get();
        $in_data = array();
//        echo'<pre>';print_R($trans);die;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        foreach($trans as $t){
            $in_data[$t->awb]['awb'] =$t->awb;
            $in_data[$t->awb]['invoice_id'] ='';
            $in_data[$t->awb]['bill_type'] =($t->reverse_order=='0')?'Forward':'Reverse';
            $in_data[$t->awb]['seller_id'] =$t->usr_id;
            $in_data[$t->awb]['user_id'] =$t->user_id;
            $in_data[$t->awb]['seller_name'] =$t->usr_name;
            $in_data[$t->awb]['order_id'] =$t->order_id;
            $in_data[$t->awb]['pincode'] =$t->ship_pincode;
            $in_data[$t->awb]['city'] =$t->ship_city;
            $in_data[$t->awb]['pstate'] =$t->pstate;
//            if($t->debit ==0 && $t->credit==0){
//                echo '<pre>';print_R($t);die;
//            }
            if($t->tracking_info == $t->awb){
                $in_data[$t->awb]['status'] =strip_tags(order::getStatusdata($t->status));
                $in_data[$t->awb]['courier'] =$couriers[$t->ship_courier_id]['name'];
            }elseif($t->tracking_info ==''){
                $in_data[$t->awb]['status'] ='Canceled';
                $in_data[$t->awb]['courier'] ='';
            }else{
                $in_data[$t->awb]['status'] ='na';
                $in_data[$t->awb]['courier'] ='';
            }
            
            if(isset($in_data[$t->awb]['cancel_amount'])){}else{$in_data[$t->awb]['cancel_amount'] =0;}
            if($t->remarks =='Amount Credit for cancel'){
                $in_data[$t->awb]['cancel_amount'] += ($t->credit *-1);
            }

            if(isset($in_data[$t->awb]['freight'])){}else{$in_data[$t->awb]['freight'] =0;}
            if($t->remarks =='freight charge' || $t->remarks =='freight & reverse charge'){
                $in_data[$t->awb]['freight'] += $t->debit;
            }

            if(isset($in_data[$t->awb]['cod'])){}else{$in_data[$t->awb]['cod'] =0;}
            if($t->remarks =='cod charge'){
                $in_data[$t->awb]['cod'] += $t->debit;
            }

            if(isset($in_data[$t->awb]['cod_refunded'])){}else{$in_data[$t->awb]['cod_refunded'] =0;}
            if($t->remarks =='COD Charge Refunded'){
                $in_data[$t->awb]['cod_refunded'] +=($t->credit *-1);
            }
            
            if(isset($in_data[$t->awb]['rto'])){}else{$in_data[$t->awb]['rto'] =0;}
            if($t->remarks =='Amount Debit for RTO'){
                $in_data[$t->awb]['rto'] += $t->debit;
            }

            if(isset($in_data[$t->awb]['extra_weight'])){}else{$in_data[$t->awb]['extra_weight'] =0;}
            if($t->remarks =='Amount Debit for extra weight'){
                $in_data[$t->awb]['extra_weight'] += $t->debit;
            }
            
            if(isset($in_data[$t->awb]['extra_weight_rto'])){}else{$in_data[$t->awb]['extra_weight_rto'] =0;}
            if($t->remarks =='Amount Debit for extra weight - RTO'){
                $in_data[$t->awb]['extra_weight_rto'] += $t->debit;
            }

            if(isset($in_data[$t->awb]['rto_refunded'])){}else{$in_data[$t->awb]['rto_refunded'] =0;}
            if($t->remarks =='RTO Charge Refunded'){
                $in_data[$t->awb]['rto_refunded'] +=($t->credit *-1);
            }
            if(isset($in_data[$t->awb]['extraweight_refunded'])){}else{$in_data[$t->awb]['extraweight_refunded'] =0;}
            if($t->remarks =='Extra weight Refunded'){
                $in_data[$t->awb]['extraweight_refunded'] +=($t->credit *-1);
            }
            if(isset($in_data[$t->awb]['freight_refunded'])){}else{$in_data[$t->awb]['freight_refunded'] =0;}
            if($t->remarks =='Freight Charge Refunded'){
                $in_data[$t->awb]['freight_refunded'] +=($t->credit *-1);
            }
            if(isset($in_data[$t->awb]['zone_amount_difference'])){}else{$in_data[$t->awb]['zone_amount_difference'] =0;}
            if($t->remarks =='zone amount difference'){
                $in_data[$t->awb]['zone_amount_difference'] +=($t->credit *-1);
                $in_data[$t->awb]['zone_amount_difference'] += $t->debit;
            }
            
        }
//        
        $savedata = array();
//        echo '<pre>';print_R($in_data);die;
        foreach($in_data as $data){
            $savedata[$data['user_id']]['start_date'] =$start_date;
            $savedata[$data['user_id']]['end_date'] =$end_date;
            $savedata[$data['user_id']]['state'] =$data['pstate'];
            if(isset( $savedata[$data['user_id']]['awb'])){}else{ $savedata[$data['user_id']]['awb'] ='';}
            $savedata[$data['user_id']]['awb'] .=$data['awb'].',';
            if(isset( $savedata[$data['user_id']]['total'])){}else{ $savedata[$data['user_id']]['total'] =0;}
                 $savedata[$data['user_id']]['total'] +=$data['freight']+$data['cod']+$data['rto']+$data['extra_weight']+$data['extra_weight_rto']+$data['cancel_amount']+$data['cod_refunded']+$data['rto_refunded']+$data['extraweight_refunded']+$data['freight_refunded']+$data['zone_amount_difference'];
            
            
        }
//       echo '<pre>';print_R(($savedata));die;
        foreach($savedata as $key => $item){
            if($key !='1' && $key !='2'){
                $chk_invoice = Invoice::where('start_date',$item['start_date'])->where('end_date',$item['end_date'])->where('user_id',$key)->first();
                $parent_userid = Admin::find($key)->parent_id;
//                echo $parent_userid;die;
                if($chk_invoice ==''){
                    $invoice = new Invoice();
                    $invoice->start_date =$item['start_date'];
                    $invoice->end_date =$item['end_date'];
                    $invoice->user_id =$key;
                    $invoice->invoice_date =date('Y-m-d');
                    $subtotal = round(($item['total']/1.18),2);
                    $tax = $item['total']-$subtotal;
                    if($item['state']==13){
                        $invoice->igst =0;
                        $invoice->sgst =$tax/2;
                        $invoice->cgst =$tax/2;
                    }else{
                        $invoice->igst =$tax;
                        $invoice->sgst =0;
                        $invoice->cgst =0;
                    }
                    $invoice->subtotal = $subtotal;
                    $invoice->total = $item['total'];
                    $invoice->awb = $item['awb'];
                    $invoice->save();
                    $inv_id = $invoice->id;
                    if($parent_userid !='1'){
                        $inv_type = 'p';
                        $multiple = 1;
                    }else{
                        if($item['total'] <0){
                            $inv_type = 'c';
                            $multiple = -1;
                        }else{
                            $inv_type = 'n';
                            $multiple = 1;
                        }
                    }
                    $invoice->igst =$invoice->igst * $multiple;
                    $invoice->sgst =$invoice->sgst * $multiple;
                    $invoice->cgst =$invoice->cgst * $multiple;
                    $invoice->total =$invoice->total * $multiple;
                    $invoice->subtotal =$invoice->subtotal * $multiple;
                    $invoice->invoice_type =$inv_type;
                    $invoice->invoice_id = Available_invoice::getavailableid($inv_type);
                    $invoice->save();

//                    $awb_arary = explode(',',$item['awb']);
                }
            }
        }
        exit;

        
    }
    
    public function sendgeneralmail(){
        $subject = "Durga Puja - Escalation TAT impact & Holiday Update !!";
        $body = "
        <p>Dear Valued Clients,</p>
        <p>Hope you are doing well</p>
        <p>Please note that on the occasion of Durga Puja, our East team will be on holiday from 10th to 13th October.</p>
        <p>During this period, any escalations or support requests related to the East region will be temporarily on hold. Rest assured, our team will address any pending matters as soon as operations resume on 14th October.</p>
        <p>We appreciate your understanding and patience during this time. Should you have any urgent concerns, feel free to reach out to us, and we will do our best to assist you.</p>
        <p></p><p></p>
        <br><br>
        
        
        <p>Thank you for your continued partnership and trust in Hyloship.</p>
        <p>Best Regards,<br>Hyloship Team</p>
    ";
        $emails = Admin::where('email','!=','')->where('active','1')->where('delete_status','0')->get();
//        echo $admin;die;
//        $emails =["ritesha412@gmail.com"];

        foreach ($emails as $email) {
            Mail::send([], [], function($message) use ($email, $subject, $body) {
                $message->to($email->email)
//                 $message->to($email)
                        ->subject($subject)
                        ->setBody($body, 'text/html');
            });
        }
    }

    public function cleanup_woo_orders()
    {
        $count = Order::where('channel', 'Woocommerce')
            ->where('status', '1')
            ->where(function($query) {
                $query->whereNull('tracking_info')
                      ->orWhere('tracking_info', '');
            })
            ->delete();

        return "Successfully deleted $count WooCommerce draft/new orders. You can now run the sync again.";
    }
}
   
   
    
     
