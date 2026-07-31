<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Order;
use App\Models\Admin\OrderDetail;
use App\Models\Admin\Admin;
use App\Models\Admin\Warehouse;
use DB;
use Carbon\Carbon;
use PDF;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard(){
        if (Auth::guard('admin')->user()->terms_condition_accept == 1) {
            $role_id = Auth::guard('admin')->user()->role_id;
            
            $tshipment = $delivredorder = $rtoorder = $revenue = $cd = $intransit = 0;
            $user_id = Auth::guard('admin')->user()->id;
            $current_company = Auth::guard('admin')->user()->company_id;
            
            // Fetch orders based on the role of the admin
            if ($role_id == 1) {
                $orderall = Order::select('tracking_info','cod_amount','shipping_cost','total','payment_mode','status','ship_courier_id','zone')->where('company_id',$current_company)->get();
            } elseif ($role_id == '2') {
                $sub_user_id = Admin::getsubuserid($user_id);
                $orderall = Order::select('tracking_info','cod_amount','shipping_cost','total','payment_mode','status','ship_courier_id','zone')->where('company_id',$current_company)->whereIn('user_id', $sub_user_id)->get();
            } else {
                $orderall = Order::select('tracking_info','cod_amount','shipping_cost','total','payment_mode','status','ship_courier_id','zone')->where('company_id',$current_company)->where('user_id', $user_id)->get();
            }
            
            $zonearray = ['within_city', 'within_state', 'metro_to_metro', 'rest_of_india', 'north_east'];
            $zonedaat = array_fill_keys($zonearray, 0);
            
            $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);
            $statusarray = ['New', 'Shipped', 'Delivered', 'Canceled', 'RTO', 'RTO Delivered', 'NDR', 'Pickup Pending', 'RTO In Transit', 'In Transit', 'Out for Delivery', 'Lost', 'Damaged'];
            
            $courierdata = [];
            $statusdata = [];
    
            foreach ($couriers as $courier) {
                $courierdata[$courier['name']] = 0;
                foreach ($statusarray as $sta) {
                    $statusdata[$courier['name']][$sta] = 0;
                }
            }
            
            foreach ($orderall as $order) {
                if (!empty($order->tracking_info) && $order->tracking_info != '0') {
                    $tshipment += 1;
                    if (isset($zonedaat[$order->zone])) {
                        $zonedaat[$order->zone] += 1;
                    }
                    
                    if (!empty($order->ship_courier_id) && isset($couriers[$order->ship_courier_id])) {
                        $courier_name = $couriers[$order->ship_courier_id]['name'];
                        $courierdata[$courier_name] = ($courierdata[$courier_name] ?? 0) + 1;
                        $cd = 1;
    
                        // Update status data
                        $status = strip_tags($order->status);
                        $statusdata[$courier_name][$status] = ($statusdata[$courier_name][$status] ?? 0) + 1;
                    }
                }
                
                // Check for Delivered status
                if ((strip_tags($order->status) == 'Delivered' && !empty($order->tracking_info) && $order->tracking_info != '0')) {
                    $delivredorder += 1;
                    $revenue += $order->total;
                }
    
                // Check for RTO status
                if (in_array(strip_tags($order->status), ['RTO', 'RTO Delivered', 'RTO In Transit'])) {
                    $rtoorder += 1;
                }
                if(strip_tags($order->status) == 'In Transit'){
                    $intransit+=1;
                }
            }
            
            $orderCount = count($orderall);
            
            $deliveredPercentage = ($tshipment != 0) ? round(($delivredorder / $tshipment) * 100, 2) : 0;
            $transitPercentage = ($tshipment != 0) ? round(($intransit / $tshipment) * 100, 2) : 0;
            $rtopercentage = ($tshipment != 0) ? round(($rtoorder / $tshipment) * 100, 2) : 0;
            
            $percentage = ($orderCount > 0) ? round(($tshipment / $orderCount) * 100, 2) : 0;
            $percentage2 = ($orderCount > 0) ? round(($delivredorder / $orderCount) * 100, 2) : 0;
            $percentage3 = ($orderCount > 0) ? round(($rtoorder / $orderCount) * 100, 2) : 0;
            $percentage4 = ($orderCount > 0) ? round(($intransit / $orderCount) * 100, 2) : 0;
            $avgrevenue = ($delivredorder != 0) ? round(($revenue / $delivredorder), 2) : 0;
            
            $pieDataPoints = [];
            foreach ($zonedaat as $key => $val) {
                if ($val != 0) {
                    $pieDataPoints[] = [
                        'value' => $val,
                        'category' => ucwords(str_replace('_', ' ', $key))
                    ];
                }
            }

            $barDataPoints = [];
            foreach ($courierdata as $key => $val) {
                if ($val != 0) {
                    $barPercentage = ($tshipment != 0) ? round(($val / $tshipment) * 100, 2) : 0;
                    $barDataPoints[] = [
                        'value' => $val,
                        'category' => ucwords(str_replace('_', ' ', $key)),
                        'percentage' => $barPercentage
                    ];
                }
            }
            
            return view('admin.home3', compact(
                'role_id',
                'tshipment',
                'delivredorder',
                'rtoorder',
                'revenue',
                'intransit',
                'deliveredPercentage',
                'transitPercentage',
                'rtopercentage',
                'percentage',
                'percentage2',
                'percentage3',
                'percentage4',
                'avgrevenue',
                'pieDataPoints',
                'barDataPoints',
                'statusdata',
                'courierdata'
            ));
        } else {
            return view('admin.tc'); 
        }  
    }

    public function indexnew() {
        if (Auth::guard('admin')->user()->terms_condition_accept == 1) {
            $role_id = 'td';
            $tshipment = $delivredorder = $rtoorder = $revenue = $cd = $intransit = 0;
            $user_id = Auth::guard('admin')->user()->id;
            $current_company = Auth::guard('admin')->user()->company_id;
            
            // Fetch orders based on the role of the admin
            if (Auth::guard('admin')->user()->role_id == 1) {
               
            //$orderall = Order::select('id','user_id','order_id','tracking_info','cod_amount','warehouse_id','return_warehouse_id','shipping_cost','total','payment_mode','status','ship_courier_id','shipping_courier_cost','shipping_courier_type','shipping_courier_weight_used','shipping_courier_weight','shipment_id','zone','rev_zone','created_at')->get(); // Adjust columns as needed
            
             $orderall = Order::select('tracking_info','cod_amount','shipping_cost','total','payment_mode','status','ship_courier_id','zone')->where('company_id',$current_company)->get();
             
            } elseif (Auth::guard('admin')->user()->role_id == '2') {
                $sub_user_id = Admin::getsubuserid($user_id);
                $orderall = Order::select('tracking_info','cod_amount','shipping_cost','total','payment_mode','status','ship_courier_id','zone')->where('company_id',$current_company)->whereIn('user_id', $sub_user_id)->get();
            } else {
                $orderall = Order::select('tracking_info','cod_amount','shipping_cost','total','payment_mode','status','ship_courier_id','zone')->where('company_id',$current_company)->where('user_id', $user_id)->get();
            }
            
            $zonearray = ['within_city', 'within_state', 'metro_to_metro', 'rest_of_india', 'north_east'];
            $zonedaat = array_fill_keys($zonearray, 0);
            
            $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);
            $statusarray = ['New', 'Shipped', 'Delivered', 'Canceled', 'RTO', 'RTO Delivered', 'NDR', 'Pickup Pending', 'RTO In Transit', 'In Transit', 'Out for Delivery', 'Lost', 'Damaged'];
            
            $courierdata = [];
            $statusdata = [];
    
            foreach ($couriers as $courier) {
                $courierdata[$courier['name']] = 0;
                foreach ($statusarray as $sta) {
                    $statusdata[$courier['name']][$sta] = 0;
                }
            }
            
            foreach ($orderall as $order) {
                if (!empty($order->tracking_info) && $order->tracking_info != '0') {
                   
                    $tshipment += 1;
                    if (isset($zonedaat[$order->zone])) {
                        $zonedaat[$order->zone] += 1;
                    }
                    
                    if (!empty($order->ship_courier_id) && isset($couriers[$order->ship_courier_id])) {
                        $courier_name = $couriers[$order->ship_courier_id]['name'];
                        $courierdata[$courier_name] = ($courierdata[$courier_name] ?? 0) + 1;
                        $cd = 1;
    
                        // Update status data
                        $status = strip_tags($order->status);
                        $statusdata[$courier_name][$status] = ($statusdata[$courier_name][$status] ?? 0) + 1;
                    }
                }
                
                // Check for Delivered status
                if ((strip_tags($order->status) == 'Delivered' && !empty($order->tracking_info) && $order->tracking_info != '0')) {
                    $delivredorder += 1;
                    $revenue += $order->total;
                }
    
                // Check for RTO status
                if (in_array(strip_tags($order->status), ['RTO', 'RTO Delivered', 'RTO In Transit'])) {
                    $rtoorder += 1;
                }
                if(strip_tags($order->status) == 'In Transit'){
                    $intransit+=1;
                    
                }

                
            }
           
            return response()->json([
                'cd' => $cd,
                'statusdata' => $statusdata,
                'courierdata' => $courierdata,
                'zonedaat' => $zonedaat,
                'orderall' => $orderall,
                'role_id' => $role_id,
                'tshipment' => $tshipment,
                'delivredorder' => $delivredorder,
                'rtoorder' => $rtoorder,
                'revenue' => $revenue,
                'intransit' => $intransit,
            ]);
        }
    }
    
    public function index(){
        
        if(Auth::guard('admin')->user()->terms_condition_accept == 1){
            $role_id='td';
            $tshipment = $delivredorder = $rtoorder = $revenue = $cd = 0;
            $user_id = Auth::guard('admin')->user()->id; 
            if(Auth::guard('admin')->user()->role_id == 1){
               
               $orderall = Order::select('id','user_id','order_id','tracking_info','cod_amount','warehouse_id','return_warehouse_id','shipping_cost','total','payment_mode','status','ship_courier_id','shipping_courier_cost','shipping_courier_type','shipping_courier_weight_used','shipping_courier_weight','shipment_id','zone','rev_zone','created_at')->get(); // Adjust columns as needed
            }elseif(Auth::guard('admin')->user()->role_id =='2'){
                $sub_user_id = Admin::getsubuserid($user_id);
                $orderall = Order::select('id','user_id','order_id','tracking_info','cod_amount','warehouse_id','return_warehouse_id','shipping_cost','total','payment_mode','status','ship_courier_id','shipping_courier_cost','shipping_courier_type','shipping_courier_weight_used','shipping_courier_weight','shipment_id','zone','rev_zone','created_at')->whereIn('user_id',$sub_user_id)->get();
//                $orderall = Order::whereIn('user_id',$sub_user_id)->get();
            }else{
                $orderall = Order::select('id','user_id','order_id','tracking_info','cod_amount','warehouse_id','return_warehouse_id','shipping_cost','total','payment_mode','status','ship_courier_id','shipping_courier_cost','shipping_courier_type','shipping_courier_weight_used','shipping_courier_weight','shipment_id','zone','rev_zone','created_at')->where('user_id',$user_id)->get();
            }
//           echo 'ho';die;
            $zonearray= array('within_city','within_state','metro_to_metro','rest_of_india','north_east');
            foreach($zonearray as $zone):
                $zonedaat[$zone] =0; 
            endforeach;
            $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
            $statusarray = array('New','Shipped','Delivered','Canceled','RTO','RTO Delivered','NDR','Pickup Pending','RTO In Transit','In Transit','Out for Delivery','Lost','Damaged','Destroyed');
            foreach($couriers as $courier):
                $courierdata[$courier['name']] =0; 
                foreach($statusarray as $sta):
                    $statusdata[$courier['name']][$sta] =0; 
                endforeach;
            endforeach;
            
            foreach($orderall as $order):
                if($order->tracking_info !='' && $order->tracking_info !=null &&  $order->tracking_info !='0'){
                    $tshipment +=1;
                    $zonedaat[$order->zone] +=1;
                    if($order->ship_courier_id){
                        $courierdata[$couriers[$order->ship_courier_id]['name']] +=1;
                        $cd =1;
                    }
                }
                if((strip_tags($order->status) =='Delivered' && $order->tracking_info !='' && $order->tracking_info !=null &&  $order->tracking_info !='0') ){
                    $delivredorder +=  1;
                    $revenue = $revenue + $order->total;
                    
                }
                
                if(strip_tags($order->status) =='RTO' || strip_tags($order->status) =='RTO Delivered' || strip_tags($order->status) =='RTO In Transit' ){
                    $rtoorder +=  1;
                }
                if($order->ship_courier_id !='0'){
                        $statusdata[$couriers[$order->ship_courier_id]['name']][strip_tags($order->status)] +=1;
                    
                }
                
            endforeach;
            // echo '<pre>';print_R($statusdata['Ecom Express']);die;
            return view('admin.home2',compact('cd','statusdata','courierdata','zonedaat','orderall','role_id','tshipment','delivredorder','rtoorder','revenue'));
        }else{
            return view('admin.tc');
        }
    }
    public function infilter(Request $request)
    {   
        $role_id= $request->role_id;
        $tshipment = $delivredorder = $rtoorder = $revenue =$cd= 0;
        $user_id = Auth::guard('admin')->user()->id;
//        if($role_id=='ty'){
//            $re_data['start_date'] = date('Y').'-01-01 00:00:00';
//            $re_data['end_date'] = date('Y').'-12-31 23:59:59';
//        }
//        if($role_id=='tm'){
//            $re_data['start_date'] = date('Y-m').'-01 00:00:00';
//            $re_data['end_date'] = date('Y-m').'-31 23:59:59';
//        }
        if($role_id=='tw'){//last seven
            $now = date('Y-m-d');
            $re_data['start_date'] =  date('Y-m-d', strtotime($now. ' - 6 days')).' 00:00:00';
            $re_data['end_date'] = date('Y-m-d').' 23:59:59';
        }
        if($role_id=='tod'){//today
            $re_data['start_date'] = date('Y-m-d').' 00:00:00';
            $re_data['end_date'] = date('Y-m-d').' 23:59:59';
        }
        if($role_id=='yes'){//Yesterday
            $now = date('Y-m-d');
            $re_data['start_date'] = date('Y-m-d', strtotime($now. ' - 1 days')).' 00:00:00';
            $re_data['end_date'] = date('Y-m-d', strtotime($now. ' - 1 days')).' 23:59:59';
        }
        if($role_id=='lt'){//last three
            $now = date('Y-m-d');
            $re_data['start_date'] = date('Y-m-d', strtotime($now. ' - 2 days')).' 00:00:00';
            $re_data['end_date'] = date('Y-m-d').' 23:59:59';
        }
        if($role_id=='lf'){//last fifteen
            $now = date('Y-m-d');
            $re_data['start_date'] = date('Y-m-d', strtotime($now. ' - 14 days')).' 00:00:00';
            $re_data['end_date'] = date('Y-m-d').' 23:59:59';
        }
        if($role_id=='lth'){//last 30
            $now = date('Y-m-d');
            $re_data['start_date'] = date('Y-m-d', strtotime($now. ' - 29 days')).' 00:00:00';
            $re_data['end_date'] = date('Y-m-d').' 23:59:59';
        }
//        echo $role_id.'<br>';
//        echo '<pre>';print_r($re_data);die;
        if(Auth::guard('admin')->user()->role_id == 1){
            if($role_id=='td'){
                $orderall = Order::select('id','user_id','order_id','tracking_info','remittance_id','cod_amount','cod_status','cod_date','vendor_order_id','shopify_order_id','channel_id','channel','fulfillment_status','fulfillment_time','warehouse_id','return_warehouse_id','weight','length','breadth','height','discount','shipping_cost','total','custom_total','note','tags','payment_mode','status','ndr_action','ndr_action_date','shipped_date','picked_date','manifest_date','ship_courier_id','rev_ship_courier_id','shipping_courier_cost','rev_shipping_courier_cost','shipping_courier_type','shipping_courier_weight_used','shipping_courier_weight','shipment_id','extra_weight','extra_weight_cost','extracostwithoutgst','extracostgst','extra_weight_status','extra_weight_added_by','extra_weight_added_on','extra_weight_closed_on','extra_weght_rto_deduct','cod','gst','gst_freight','gst_cod','freight','codrefunded','rto_charge','rto_charge_gst','rto_charge_witoutgst','rtocharge_applied','reverse_charge','rate','rateadd','other_charges','zone','rev_zone','manifest_id','manifestprod_id','total_attempt','rev_tracking_info','cancel_date','rto_date','rto_received_date','return_date','delivered_date','reverse_order','shipping_courier_costparent','codparent','gstparent','gst_freightparent','gst_codparent','freightparent','reverse_chargeparent','rateparent','rateaddparent','rto_chargeparent','rto_charge_gstparent','rto_charge_witoutgstparent','extra_weight_costparent','extracostwithoutgstparent','extracostgstparent','created_at','updated_at')->get(); 
            }else{
                $orderall = Order::where('created_at','>=',$re_data['start_date'])->where('created_at','<=',$re_data['end_date'])
                ->get();
            }
        }elseif(Auth::guard('admin')->user()->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user_id);
            if($role_id=='td'){
                $orderall = Order::whereIn('user_id',$sub_user_id)->get();
            }else{
                $orderall = Order::whereIn('user_id',$sub_user_id)
                ->where('created_at','>=',$re_data['start_date'])->where('created_at','<=',$re_data['end_date'])
                ->get();
            }
        }else{
            if($role_id=='td'){
                $orderall = Order::where('user_id',$user_id)->get();
            }else{
                $orderall = Order::where('user_id',$user_id)
                ->where('created_at','>=',$re_data['start_date'])->where('created_at','<=',$re_data['end_date'])
                ->get();
            }
        }
        $zonearray= array('within_city','within_state','metro_to_metro','rest_of_india','north_east');
        foreach($zonearray as $zone):
            $zonedaat[$zone] =0; 
        endforeach;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $statusarray = array('New','Shipped','Delivered','Canceled','RTO','RTO Delivered','NDR','Pickup Pending','RTO In Transit','In Transit','Out for Delivery','Lost','Damaged');
        foreach($couriers as $courier):
            $courierdata[$courier['name']] =0; 
            foreach($statusarray as $sta):
                $statusdata[$courier['name']][$sta] =0; 
            endforeach;
            
        endforeach;
        foreach($orderall as $order):
            if($order->tracking_info !='' && $order->tracking_info !=null &&  $order->tracking_info !='0'){
                $tshipment +=1;
                $zonedaat[$order->zone] +=1;
                if($order->ship_courier_id){
                    $courierdata[$couriers[$order->ship_courier_id]['name']] +=1;
                    $cd =1;
                }
            }
            if(strip_tags($order->status) =='Delivered' && $order->tracking_info !='' && $order->tracking_info !=null &&  $order->tracking_info !='0'){
                $delivredorder +=  1;
            }
            if(strip_tags($order->status) =='RTO' || strip_tags($order->status) =='RTO Delivered' || strip_tags($order->status) =='RTO In Transit'){
                $rtoorder +=  1;
            }
            if($order->ship_courier_id !='0'){
                $statusdata[$couriers[$order->ship_courier_id]['name']][strip_tags($order->status)] +=1;
            }
            $revenue = $revenue + $order->shipping_courier_cost;
        endforeach;
        // echo '<pre>';print_R($cd);die;
        return view('admin.home2',compact('cd','statusdata','courierdata','zonedaat','orderall','role_id','tshipment','delivredorder','rtoorder','revenue'));
    }
    public function shipmentold(){
        if(Auth::guard('admin')->user()->terms_condition_accept == 1){
            $user_id = Auth::guard('admin')->user()->id;
            $modedata = array();
            $tshipment = $intrsit = $delivredorder = $rtoorder = $todayshipment = $todaypickpending = $todayood = $todaydeliverd = $todayrev = $todaybefore12pickpending =0;
            if(Auth::guard('admin')->user()->role_id == 1){
                $orderall = Order::select('id','user_id','order_id','tracking_info','cod_amount','cod_status','cod_date','shipping_cost','total','custom_total','payment_mode','status','shipped_date','picked_date','manifest_date','ship_courier_id','cod','gst','gst_freight','gst_cod','freight','codrefunded','zone','rev_zone','manifest_id','manifestprod_id','delivered_date','reverse_order','created_at','updated_at','status_date')->get(); 
            }elseif(Auth::guard('admin')->user()->role_id =='2'){
                $sub_user_id = Admin::getsubuserid($user_id);
                $orderall = Order::whereIn('user_id',$sub_user_id)->get();
            }else{
                $orderall = Order::where('user_id',$user_id)->get();
            }

            $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
            $statusarray = array('C.O.D','Pre-Paid','Reverse');
            foreach($couriers as $courier):
                $courierdata[$courier['name']] =0; 
                foreach($statusarray as $sta):
                    $modedata[$courier['name']][$sta] =0; 
                endforeach;
                
            endforeach;
            $today['start_date'] = date('Y-m-d').' 00:00:00';
            $today['end_datebefore12'] = date('Y-m-d').' 11:59:59';
            $today['end_date'] = date('Y-m-d').' 23:59:59';
//            echo '<pre>';print_R($orderall);die;
            foreach($orderall as $order):
                if($order->tracking_info !='' && $order->tracking_info !=null &&  $order->tracking_info !='0'){
                    $tshipment +=1;
                }
                if(in_array(strip_tags($order->status),array('In Transit','NDR','Out for Delivery','Lost','Damaged'))){
                    $intrsit +=1;
                }
                if(strip_tags($order->status) =='Delivered' && $order->tracking_info !='' && $order->tracking_info !=null &&  $order->tracking_info !='0'){
                    $delivredorder +=  1;
                }
                $rto = array('RTO','RTO In Transit','RTO Delivered');
                if(in_array(strip_tags($order->status),$rto)){
                    $rtoorder +=  1;
                }
                if($order->tracking_info !='' && $order->tracking_info !=null &&  $order->tracking_info !='0' && $order->shipped_date >=$today['start_date'] && $order->shipped_date <=$today['end_date']){
                    $todayshipment +=1;
                    $todayrev =  $todayrev + $order->shipping_courier_cost;
                }
                if($order->tracking_info !='' && $order->tracking_info !=null &&  $order->tracking_info !='0' && strip_tags($order->status) =='Pickup Pending' && $order->manifest_date >=$today['start_date'] && $order->manifest_date <=$today['end_date']){
                    $todaypickpending +=1;
                }
                if($order->tracking_info !='' && $order->tracking_info !=null &&  $order->tracking_info !='0' && strip_tags($order->status) =='Pickup Pending' && $order->manifest_date >=$today['start_date'] && $order->manifest_date <=$today['end_datebefore12']){
                    $todaybefore12pickpending +=1;
                }
                if(in_array(strip_tags($order->status),array('Out for Delivery')) && ($order->status_date >=$today['start_date'] && $order->status_date <=$today['end_date'])){
                    $todayood +=1;
                }
                if(in_array(strip_tags($order->status),array('Delivered')) && ($order->delivered_date >=$today['start_date'] && $order->delivered_date <=$today['end_date'])){
                    $todaydeliverd +=1;
                }
                if($order->ship_courier_id !='0'){
                    $modedata[$couriers[$order->ship_courier_id]['name']][strip_tags($order->payment_mode)] +=1;
                }
                // $revenue = $revenue + $order->shipping_courier_cost;
            endforeach;
            return view('admin.shipmentold',compact('orderall','tshipment','intrsit','delivredorder','rtoorder','todayshipment','todaypickpending','todayood','todaydeliverd','todayrev','modedata','todaybefore12pickpending'));
        }else{
            return view('admin.tc');
        } 
    }
    
    public function shipment()
    {      
        if(Auth::guard('admin')->user()->terms_condition_accept == 1){
            return view('admin.shipment');
        }else{
            return view('admin.tc');
        } 

    }
    public function shipmentload(){
        if(Auth::guard('admin')->user()->terms_condition_accept == 1){
            $user_id = Auth::guard('admin')->user()->id;
            $current_company = Auth::guard('admin')->user()->company_id;
            $modedata = array();
            $tshipment = $intrsit = $delivredorder = $rtoorder = $todayshipment = $todaypickpending = $todayood = $todaydeliverd = $todayrev = $todaybefore12pickpending =0;
            if(Auth::guard('admin')->user()->role_id == 1){
                //$orderall = Order::select('id','user_id','order_id','tracking_info','cod_amount','cod_status','cod_date','channel_id','channel','warehouse_id','return_warehouse_id','weight','length','breadth','height','discount','shipping_cost','total','custom_total','note','tags','payment_mode','status','ndr_action','ndr_action_date','shipped_date','picked_date','manifest_date','ship_courier_id','rev_ship_courier_id','shipping_courier_cost','rev_shipping_courier_cost','shipping_courier_type','shipping_courier_weight_used','shipping_courier_weight','shipment_id','extra_weight','extra_weight_cost','extracostwithoutgst','extracostgst','extra_weight_status','extra_weight_added_by','extra_weight_added_on','extra_weight_closed_on','extra_weght_rto_deduct','cod','gst','gst_freight','gst_cod','freight','codrefunded','rto_charge','rto_charge_gst','rto_charge_witoutgst','rtocharge_applied','reverse_charge','rate','rateadd','other_charges','zone','rev_zone','manifest_id','manifestprod_id','total_attempt','rev_tracking_info','cancel_date','rto_date','rto_received_date','return_date','delivered_date','reverse_order','shipping_courier_costparent','codparent','gstparent','gst_freightparent','gst_codparent','freightparent','reverse_chargeparent','rateparent','rateaddparent','rto_chargeparent','rto_charge_gstparent','rto_charge_witoutgstparent','extra_weight_costparent','extracostwithoutgstparent','extracostgstparent','created_at','updated_at','status_date')->get(); 
                $orderall = Order::select('tracking_info','status_date','shipped_date','delivered_date','payment_mode','status','ship_courier_id','manifest_date')->where('company_id',$current_company)->get();

            }elseif(Auth::guard('admin')->user()->role_id =='2'){
                $sub_user_id = Admin::getsubuserid($user_id);
                $orderall = Order::select('tracking_info','status_date','shipped_date','delivered_date','payment_mode','status','ship_courier_id','manifest_date')->where('company_id',$current_company)->whereIn('user_id',$sub_user_id)->get();
            }else{
                $orderall = Order::select('tracking_info','status_date','shipped_date','delivered_date','payment_mode','status','ship_courier_id','manifest_date')->where('company_id',$current_company)->where('user_id',$user_id)->get();
            }

            $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
            $statusarray = array('C.O.D','Pre-Paid','Reverse');
            foreach($couriers as $courier):
                $courierdata[$courier['name']] =0; 
                foreach($statusarray as $sta):
                    $modedata[$courier['name']][$sta] =0; 
                endforeach;
                
            endforeach;
            $today['start_date'] = date('Y-m-d').' 00:00:00';
            $today['end_datebefore12'] = date('Y-m-d').' 11:59:59';
            $today['end_date'] = date('Y-m-d').' 23:59:59';
//            echo '<pre>';print_R($orderall);die;
            foreach($orderall as $order):
                if($order->tracking_info !='' && $order->tracking_info !=null &&  $order->tracking_info !='0'){
                    $tshipment +=1;
                }
                if(in_array(strip_tags($order->status),array('In Transit','NDR','Out for Delivery','Lost','Damaged'))){
                    $intrsit +=1;
                }
                if(strip_tags($order->status) =='Delivered' && $order->tracking_info !='' && $order->tracking_info !=null &&  $order->tracking_info !='0'){
                    $delivredorder +=  1;
                }
                $rto = array('RTO','RTO In Transit','RTO Delivered');
                if(in_array(strip_tags($order->status),$rto)){
                    $rtoorder +=  1;
                }
                if($order->tracking_info !='' && $order->tracking_info !=null &&  $order->tracking_info !='0' && $order->shipped_date >=$today['start_date'] && $order->shipped_date <=$today['end_date']){
                    $todayshipment +=1;
                    $todayrev =  $todayrev + $order->shipping_courier_cost;
                }
                if($order->tracking_info !='' && $order->tracking_info !=null &&  $order->tracking_info !='0' && strip_tags($order->status) =='Pickup Pending' && $order->manifest_date >=$today['start_date'] && $order->manifest_date <=$today['end_date']){
                    $todaypickpending +=1;
                }
                if($order->tracking_info !='' && $order->tracking_info !=null &&  $order->tracking_info !='0' && strip_tags($order->status) =='Pickup Pending' && $order->manifest_date >=$today['start_date'] && $order->manifest_date <=$today['end_datebefore12']){
                    $todaybefore12pickpending +=1;
                }
                if(in_array(strip_tags($order->status),array('Out for Delivery')) && ($order->status_date >=$today['start_date'] && $order->status_date <=$today['end_date'])){
                    $todayood +=1;
                }
                if(in_array(strip_tags($order->status),array('Delivered')) && ($order->delivered_date >=$today['start_date'] && $order->delivered_date <=$today['end_date'])){
                    $todaydeliverd +=1;
                }
                if($order->ship_courier_id !='0'){
                    $modedata[$couriers[$order->ship_courier_id]['name']][strip_tags($order->payment_mode)] +=1;
                }
                // $revenue = $revenue + $order->shipping_courier_cost;
            endforeach;
            
            // dd($todayshipment);
            return response()->json([
                'orderall' => $orderall,
                'tshipment' => $tshipment,
                'intrsit' => $intrsit,
                'delivredorder' => $delivredorder,
                'rtoorder' => $rtoorder,
                'todayshipment' => $todayshipment,
                'todaypickpending' => $todaypickpending,
                'todayood' => $todayood,
                'todaydeliverd' => $todaydeliverd,
                'todayrev' => $todayrev,
                'modedata' => $modedata,
                'todaybefore12pickpending' => $todaybefore12pickpending,
            ]);

            //return view('admin.shipment',compact('orderall','tshipment','intrsit','delivredorder','rtoorder','todayshipment','todaypickpending','todayood','todaydeliverd','todayrev','modedata','todaybefore12pickpending'));
        }
    }

    
    public function topold(){
        if(Auth::guard('admin')->user()->terms_condition_accept == 1){
            $user_id = Auth::guard('admin')->user()->id;
            echo Auth::guard('admin')->user()->role_id;die;
            if(Auth::guard('admin')->user()->role_id =='1' ){
                $top10cus =Order::select('user_id','admins.name', DB::raw('count(*) as total'))
                ->leftJoin('admins', 'admins.id', '=', 'orders.user_id')
                ->where('reverse_order','0')
                ->orderBy('total', 'desc')
                ->groupBy('user_id')
                ->take(10)
                ->get();
                $top10pr =Order::select(DB::raw('REPLACE(SUBSTRING(order_details.name, 1, 25), "/", " ") AS name'), DB::raw('sum(order_details.qty) as total'))
                ->leftJoin('order_details', 'order_details.order_id', '=', 'orders.id')
                ->where('reverse_order','0')
                ->orderBy('total', 'desc')
                ->groupBy(DB::raw('REPLACE(SUBSTRING(order_details.name, 1, 25), "/", " ")'))
                ->take(10)
                ->get();
                $top10pin =Order::select('ship_pincode','pincodes.city', DB::raw('count(distinct orders.id) as total'))
                ->join('pincodes', 'pincodes.pincode', '=', 'orders.ship_pincode')
                ->where('reverse_order','0')
                ->where('ship_pincode','!=','0')
                ->where('ship_pincode','!=','')
                ->where('ship_pincode','!=',null)
                ->orderBy('total', 'desc')
                ->groupBy('ship_pincode')
                ->take(10)
                ->get();
                $top10st =Order::select('pincodes.state', DB::raw('count(distinct orders.id) as total'))
                ->Join('pincodes', 'pincodes.pincode', '=', 'orders.ship_pincode')
                ->where('ship_pincode','!=','0')
                ->where('ship_pincode','!=','')
                ->where('ship_pincode','!=',null)
                ->where('reverse_order','0')
                ->orderBy('total', 'desc')
                ->groupBy('pincodes.state')
                ->take(10)
                ->get();
            }elseif(Auth::guard('admin')->user()->role_id =='2' ){
                $sub_user_id = Admin::getsubuserid($user_id);
                $top10cus =Order::select('ship_fname','ship_lname', DB::raw('count(*) as total'))
                ->whereIn('user_id',$sub_user_id)
                ->where('reverse_order','0')
                ->orderBy('total', 'desc')
                ->groupBy('ship_fname','ship_lname')
                ->take(10)
                ->get();
                $top10pr =Order::select(DB::raw('REPLACE(SUBSTRING(order_details.name, 1, 25), "/", " ") AS name'), DB::raw('sum(order_details.qty) as total'))
                ->leftJoin('order_details', 'order_details.order_id', '=', 'orders.id')
                ->whereIn('orders.user_id',$sub_user_id)
                ->where('reverse_order','0')
                ->orderBy('total', 'desc')
                ->groupBy(DB::raw('REPLACE(SUBSTRING(order_details.name, 1, 25), "/", " ")'))
                ->take(10)
                ->get();
                $top10pin =Order::select('ship_pincode','pincodes.city', DB::raw('count(distinct orders.id) as total'))
                ->Join('pincodes', 'pincodes.pincode', '=', 'orders.ship_pincode')
                ->where('ship_pincode','!=','0')
                ->where('ship_pincode','!=','')
                ->where('ship_pincode','!=',null)
                ->whereIn('orders.user_id',$sub_user_id)
                ->where('reverse_order','0')
                ->orderBy('total', 'desc')
                ->groupBy('ship_pincode')
                ->take(10)
                ->get();
                $top10st =Order::select('pincodes.state',DB::raw('count(distinct orders.id) as total'))
                ->Join('pincodes', 'pincodes.pincode', '=', 'orders.ship_pincode')
                ->where('ship_pincode','!=','0')
                ->where('ship_pincode','!=','')
                ->where('ship_pincode','!=',null)
                ->whereIn('orders.user_id',$sub_user_id)
                ->where('reverse_order','0')
                ->orderBy('total', 'desc')
                ->groupBy('pincodes.state')
                ->take(10)
                ->get();
            }else{
                $top10cus =Order::select('ship_fname','ship_lname', DB::raw('count(*) as total'))
                    ->where('user_id',$user_id)
                    ->where('reverse_order','0')
                    ->orderBy('total', 'desc')
                    ->groupBy('ship_fname','ship_lname')
                    ->take(10)
                    ->get();
                    $top10pr =Order::select(DB::raw('REPLACE(SUBSTRING(order_details.name, 1, 25), "/", " ") AS name'), DB::raw('sum(order_details.qty) as total'))
                    ->leftJoin('order_details', 'order_details.order_id', '=', 'orders.id')
                    ->where('orders.user_id',$user_id)
                    ->where('reverse_order','0')
                    ->orderBy('total', 'desc')
                    ->groupBy(DB::raw('REPLACE(SUBSTRING(order_details.name, 1, 25), "/", " ")'))
                    ->take(10)
                    ->get();
                    $top10pin =Order::select('ship_pincode','pincodes.city', DB::raw('count(distinct orders.id) as total'))
                    ->Join('pincodes', 'pincodes.pincode', '=', 'orders.ship_pincode')
                    ->where('ship_pincode','!=','0')
                    ->where('ship_pincode','!=','')
                    ->where('ship_pincode','!=',null)
                    ->where('orders.user_id',$user_id)
                    ->where('reverse_order','0')
                    ->orderBy('total', 'desc')
                    ->groupBy('ship_pincode')
                    ->take(10)
                    ->get();
                    $top10st =Order::select('pincodes.state', DB::raw('count(distinct orders.id) as total'))
                    ->Join('pincodes', 'pincodes.pincode', '=', 'orders.ship_pincode')
                    ->where('ship_pincode','!=','0')
                    ->where('ship_pincode','!=','')
                    ->where('ship_pincode','!=',null)
                    ->where('orders.user_id',$user_id)
                    ->where('reverse_order','0')
                    ->orderBy('total', 'desc')
                    ->groupBy('pincodes.state')
                    ->take(10)
                    ->get();
            }
            
           return view('admin.topold',compact('top10st','top10pin','top10pr','top10cus'));
        }else{
            return view('admin.tc');
        }
    }
    
    public function top(){
        if(Auth::guard('admin')->user()->terms_condition_accept == 1){
            return view('admin.top');
        }else{
            return view('admin.tc');
        }
    }
    
    public function topload(){
        $user_id = Auth::guard('admin')->user()->id;
        $top10pin = array();
        if(Auth::guard('admin')->user()->role_id =='1' ){
            // Optimize Top 10 Customers
            $top10cus = Order::select('user_id', 'admins.name', DB::raw('count(*) as total'))
                ->leftJoin('admins', 'admins.id', '=', 'orders.user_id')
                ->where('reverse_order', '0')
                ->groupBy('user_id', 'admins.name') // Group by both user_id and admins.name
                ->orderBy('total', 'desc')
                ->take(10)
                ->get();
           
            // Optimize Top 10 Products
            $top10pr = OrderDetail::select(DB::raw('REPLACE(SUBSTRING(order_details.name, 1, 25), "/", " ") AS name'), DB::raw('sum(order_details.qty) as total'))
                ->groupBy('name') // Group by the aliased name
                ->orderBy('total', 'desc')
                ->take(10)
                ->get();
                
            // Optimize Top 10 Pincodes
            // $top10pin = Order::select('ship_pincode',  DB::raw('count(ship_pincode) as total'))
            //     ->where('reverse_order', '0')
            //     ->whereNotNull('ship_pincode') // Combine checks into one
            //     ->where('ship_pincode', '!=', '0')
            //     ->where('ship_pincode', '!=', '')
            //     ->groupBy('ship_pincode') // Group by both fields
            //     ->orderBy('total', 'desc')
            //     ->take(10)
            //     ->get();
                
            // // Optimize Top 10 States
            // $top10st = Order::select('pincodes.state', DB::raw('count(distinct orders.id) as total'))
            //     ->join('pincodes', 'pincodes.pincode', '=', 'orders.ship_pincode')
            //     ->where('reverse_order', '0')
            //     ->whereNotNull('ship_pincode') // Combine checks into one
            //     ->where('ship_pincode', '!=', '0')
            //     ->where('ship_pincode', '!=', '')
            //     ->groupBy('pincodes.state') // Group by state
            //     ->orderBy('total', 'desc')
            //     ->take(10)
            //     ->get();
            //     echo '.top10st.'.now();die;
        }elseif(Auth::guard('admin')->user()->role_id =='2' ){
            $sub_user_id = Admin::getsubuserid($user_id);
            // Optimize Top 10 Customers
            $top10cus = Order::select('user_id', 'admins.name', DB::raw('count(*) as total'))
                ->leftJoin('admins', 'admins.id', '=', 'orders.user_id')
                ->where('reverse_order', '0')
                ->whereIn('user_id',$sub_user_id)    
                ->groupBy('user_id', 'admins.name') // Group by both user_id and admins.name
                ->orderBy('total', 'desc')
                ->take(10)
                ->get();

            // Optimize Top 10 Products
            $top10pr = OrderDetail::select(DB::raw('REPLACE(SUBSTRING(order_details.name, 1, 25), "/", " ") AS name'), DB::raw('sum(order_details.qty) as total'))
                ->whereIn('user_id',$sub_user_id)    
                ->groupBy('name') // Group by the aliased name
                ->orderBy('total', 'desc')
                ->take(10)
                ->get();

            // Optimize Top 10 Pincodes
            // $top10pin = Order::select('ship_pincode', DB::raw('count(distinct orders.id) as total'))
            //     ->where('reverse_order', '0')
            //     ->whereIn('user_id',$sub_user_id)    
            //     ->whereNotNull('ship_pincode') // Combine checks into one
            //     ->where('ship_pincode', '!=', '0')
            //     ->where('ship_pincode', '!=', '')
            //     ->groupBy('ship_pincode') // Group by both fields
            //     ->orderBy('total', 'desc')
            //     ->take(10)
            //     ->get();

            // Optimize Top 10 States
            // $top10st = Order::select('pincodes.state', DB::raw('count(distinct orders.id) as total'))
            //     ->join('pincodes', 'pincodes.pincode', '=', 'orders.ship_pincode')
            //     ->where('reverse_order', '0')
            //     ->whereIn('user_id',$sub_user_id)
            //     ->whereNotNull('ship_pincode') // Combine checks into one
            //     ->where('ship_pincode', '!=', '0')
            //     ->where('ship_pincode', '!=', '')
            //     ->groupBy('pincodes.state') // Group by state
            //     ->orderBy('total', 'desc')
            //     ->take(10)
            //     ->get();
        }else{
            // Optimize Top 10 Customers
            $top10cus = Order::select('user_id', 'admins.name', DB::raw('count(*) as total'))
                ->leftJoin('admins', 'admins.id', '=', 'orders.user_id')
                ->where('reverse_order', '0')
                ->where('user_id',$user_id)   
                ->groupBy('user_id', 'admins.name') // Group by both user_id and admins.name
                ->orderBy('total', 'desc')
                ->take(10)
                ->get();

            // Optimize Top 10 Products
            $top10pr = OrderDetail::select(DB::raw('REPLACE(SUBSTRING(order_details.name, 1, 25), "/", " ") AS name'), DB::raw('sum(order_details.qty) as total'))
                ->where('user_id',$user_id)  
                ->groupBy('name') // Group by the aliased name
                ->orderBy('total', 'desc')
                ->take(10)
                ->get();

            // Optimize Top 10 Pincodes
            // $top10pin = Order::select('ship_pincode', DB::raw('count(distinct orders.id) as total'))
            //     ->where('reverse_order', '0')
            //     ->where('user_id',$user_id)
            //     ->whereNotNull('ship_pincode') // Combine checks into one
            //     ->where('ship_pincode', '!=', '0')
            //     ->where('ship_pincode', '!=', '')
            //     ->groupBy('ship_pincode') // Group by both fields
            //     ->orderBy('total', 'desc')
            //     ->take(10)
            //     ->get();

            // Optimize Top 10 States
            // $top10st = Order::select('pincodes.state', DB::raw('count(distinct orders.id) as total'))
            //     ->join('pincodes', 'pincodes.pincode', '=', 'orders.ship_pincode')
            //     ->where('reverse_order', '0')
            //     ->where('user_id',$user_id)
            //     ->whereNotNull('ship_pincode') // Combine checks into one
            //     ->where('ship_pincode', '!=', '0')
            //     ->where('ship_pincode', '!=', '')
            //     ->groupBy('pincodes.state') // Group by state
            //     ->orderBy('total', 'desc')
            //     ->take(10)
            //     ->get();
        }
        return response()->json([
                'top10pin' => $top10pin,
                'top10pr' => $top10pr,
                'top10cus' => $top10cus,
            ]);
//        return view('admin.topold',compact('top10st','top10pin','top10pr','top10cus'));
        
    }
    
    public function ndr(){
        if(Auth::guard('admin')->user()->terms_condition_accept == 1){
            return view('admin.ndr');
        }
    }

    public function employee()
    {   
        return Redirect()->route('admin.dashboard');
        // if(Auth::guard('admin')->user()->role_id != 1)
        // {
            $user_id = Auth::guard('admin')->user()->id;
            $today = Order::with('detail')->whereUserId($user_id)->whereDate('created_at', Carbon::today())->sum('total');
            $today_count = Order::with('detail')->whereUserId($user_id)->whereDate('created_at', Carbon::today())->count();
            $yesterday =Order::with('detail')->whereUserId($user_id)->whereDate('created_at', Carbon::yesterday())->sum('total');
            $yesterday_count = Order::whereUserId($user_id)->whereDate('created_at', Carbon::yesterday())->count();
            $startDate = Carbon::now()->subDays(7);
            $endDate = Carbon::now();
            $week = Order::with('detail')
                ->whereUserId($user_id)->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total');
            $week_count = Order::with('detail')
                ->whereUserId($user_id)->whereBetween('created_at', [$startDate, $endDate])
                ->count();
            $month = Order::with('detail')
                ->whereUserId($user_id)->whereDate('created_at', '>=', Carbon::now()->subMonths(1))
                ->sum('total');
            $month_count = Order::with('detail')
                ->whereUserId($user_id)->whereDate('created_at', '>=', Carbon::now()->subMonths(1))
                ->count();
            $warehouse = WareHouse::where('user_id',$user_id)->get();


            $order = Order::with('detail')->where('user_id',$user_id)->orderBy('total', 'DESC')->get();

            //shipment status start
            //cod remittance start
            $order = Order::whereUserId($user_id)->where('status',3)->where('payment_mode',6)->get();
            $reductionfee = 0;
            $reduction = 0;
            
            $total = 0;
            $currentdate = \Carbon\Carbon::today()->format('Y-m-d h:m:s');
			$reductionmax = 0;
			$targetDate = Carbon::now();
            $future = 0;
            $lastremit = 0;
		
            foreach($order as $row){
                $reductionfee += (($row->total) + 35); //1,575
                $reduction += ($row->total) * (2/100); //28
                $reductionmax = max($reduction, $reductionfee);
                

                $delivery_date = \Carbon\Carbon::parse($row->delivered_date);
                $targetDate = $delivery_date->addDays(5)->format('Y-m-d h:m:s');
                $row->cod_date = $targetDate;
                $row->save();
                
                $lastremit = $delivery_date->addDays(1)->format('Y-m-d h:m:s');
                $future = $delivery_date->addDays(1)->format('Y-m-d h:m:s');
                $futureamount = Order::where('cod_date', '>', $targetDate)->get();
                $totalreduction = 0;
                $totalreductionfee = 0;
                foreach($futureamount as $data){
                    $totalreductionfee += ($data->total) + 35; //235
                    $totalreduction += ($data->total) * (2/100); //4
                    $totalreductionmax = max($totalreduction, $totalreductionfee);
                }
            }
            $admin = Admin::where('id',Auth::guard('admin')->user()->id)->first();
            $admin->last_remit_amount = $reductionmax;
            $admin->save();
            $totalreductionmax=0;
            //cod remittance end

            //filter code
            if (request()->start_date || request()->end_date) {
                $date_from = Carbon::parse(request()->start_date)->toDateTimeString();
                $date_to = Carbon::parse(request()->end_date)->toDateTimeString();
                $data = Order::with('detail')->whereUserId($user_id)->whereBetween('created_at', [$date_from, $date_to])->get();
                $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
                foreach($data as $row){
                    if($row->ship_courier_id != 0){
                        $courierdata = $couriers[$row->ship_courier_id]['name'];
                        $courier_ordercount = Order::with('detail')->whereUserId($user_id)->where('ship_courier_id',$row->ship_courier_id)->whereBetween('created_at', [$date_from, $date_to])->count() ?? 0;
                    }
                }
                $courier_bookeddata = Order::with('detail')->whereUserId($user_id)->where('status','1')->whereBetween('created_at', [$date_from, $date_to])->count();
                $courier_delivereddata = Order::with('detail')->whereUserId($user_id)->where('status','3')->whereBetween('created_at', [$date_from, $date_to])->count();
                $courier_rtodata = Order::with('detail')->whereUserId($user_id)->where('status','5')->whereBetween('created_at', [$date_from, $date_to])->count();
                $bookeddata = Order::with('detail')->whereUserId($user_id)->where('status','1')->whereBetween('created_at', [$date_from, $date_to])->count();
                $delivereddata = Order::with('detail')->whereUserId($user_id)->where('status','3')->whereBetween('created_at', [$date_from, $date_to])->count();
                $rtodata = Order::with('detail')->whereUserId($user_id)->where('status','5')->whereBetween('created_at', [$date_from, $date_to])->count();
                $total_orders = count($data);
                $courier_ordercount = 0;
                $courierdata = 0;
            } 
            $courierdata = 0;
            $courier_rtodata = 0;
            $courier_delivereddata = 0;
            $courier_bookeddata = 0;
            $courier_ordercount = 0;
            $rtodata = 0;
            $delivereddata = 0;
            $bookeddata =0;

            return view('admin.home',compact('courier_rtodata','courier_delivereddata','courier_bookeddata','courier_ordercount','courierdata','rtodata','delivereddata','bookeddata','today','yesterday','week','month','today_count','yesterday_count','week_count','month_count','order','courierdata','today','yesterday','week','month','total','order','reductionmax','targetDate','currentdate','future','totalreductionmax','lastremit'));
        // }
        // else
        // {
        //     return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        // }
    }

    public function fetchOrders(){
        $date_from = request('start_date');
        $date_to = request('end_date');
        $user_id = Auth::guard('admin')->user()->id;
        return Order::with('detail')->whereBetween('created_at', [$date_from, $date_to])->where('user_id',$user_id)->orderBy('total', 'DESC')->get();
    }
    
    public function accept_tc(){
        $user_ip = getenv('REMOTE_ADDR');
//$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
//$country = $geo["geoplugin_countryName"];
//$city = $geo["geoplugin_city"].','.$geo["geoplugin_region"];
//        echo '<pre>';print_R($city);echo $_SERVER['REMOTE_ADDR'];die;
//        $geo_location = ($_SERVER['REMOTE_ADDR']);
//        echo $this->getdevicetype($_SERVER["HTTP_USER_AGENT"]);die;
        $admin_d = Admin::where('id', Auth::guard('admin')->user()->id)->first();
        $admin_d->terms_condition_accept = '1';
        $admin_d->tc_accepted_at = now();
        $admin_d->tc_accepted_ipaddress = $_SERVER['REMOTE_ADDR'];
        $admin_d->save();
        return redirect()->route('admin.dashboard')->with('success', 'Accepted Successfully!');
    }
    private function getdevicetype($userAgent)
    {
        $devicesTypes = array(
        "laptop"   => array("msie 10", "msie 9", "msie 8", "windows.*firefox", "windows.*chrome", "x11.*chrome", "x11.*firefox", "macintosh.*chrome", "macintosh.*firefox", "opera"),
        "tablet"   => array("tablet", "android", "ipad", "tablet.*firefox"),
        "mobile"   => array("mobile ", "android.*mobile", "iphone", "ipod", "opera mobi", "opera mini"),
        "bot"      => array("googlebot", "mediapartners-google", "adsbot-google", "duckduckbot", "msnbot", "bingbot", "ask", "facebook", "yahoo", "addthis")
        );
 	foreach($devicesTypes as $deviceType => $devices) {           
        foreach($devices as $device) {
            if(preg_match("/" . $device . "/i", $userAgent)) {
                $deviceName = $deviceType;
            }
        }
    }
  //  return ucfirst($deviceName);
    return $deviceName;
    }
    public function tc_contract(){
        // Fetch additional data to pass to the PDF template
//        $users = User::all(); // Assuming you have a User model
        $session = Auth::guard('admin')->user();
        $data = [
            'title' => 'Sample PDF Title',
            'content' => 'Sample PDF Content',
//            'users' => $users, // Pass the users data to the template
        ];
        
        $general_setting = DB::table('general_settings')->where('id','1')->first();
//         echo '<pre>';print_R($invoice);die;
//         return view('admin.pdf.tc');

        // Generate the PDF using the template
        $pdf = PDF::loadView('admin.pdf.tc',compact('session'));
        // die;
        // Optional: You can save the PDF to a file if needed
        // $pdf->save('path/to/save/pdf.pdf');

        // Return the PDF as a download response
        $name = 'Agreement Aframax-'.$session->company_name.'.pdf';
        return $pdf->download($name);
    }
}

