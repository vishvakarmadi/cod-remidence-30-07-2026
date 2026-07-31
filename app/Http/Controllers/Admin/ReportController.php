<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use App\Models\Admin\Warehouse;
use App\Models\Admin\Country;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use App\Models\Admin\Reportfilter;
use App\Models\Admin\ActivityLog;
use App\Models\Admin\Remittance;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use DB;
use Hash;

class ReportController extends Controller
{
    public function index()
    {   
        $fy = get_fin_year();
        $f_year = explode('&&',$fy);
        $re_data = array();
        $user_id = Auth::guard('admin')->user()->id;
        $re_data['user_id'][] =$user_id;
        $re_data['start_date'] =$f_year[0];
        $re_data['end_date'] =$f_year[1];
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $user_id = Auth::guard('admin')->user()->id;
        // $orders = Order::with('detail')
        // ->where(['user_id' => $user_id])
        // ->where('status' ,'!=' ,'1')
        // ->where('created_at','>=',$re_data['start_date'])->where('created_at','<=',$re_data['end_date'])
        // ->get();
        $order_q = Order::with('detail')
        ->join('admins', 'orders.user_id', '=', 'admins.id')
        ->where(['orders.user_id' => $user_id])->where('orders.status','!=', 1)->where('orders.created_at','>=',$re_data['start_date'])->where('orders.created_at','<=',$re_data['end_date']);
        $orders = $order_q->select( 'orders.user_id','orders.payment_mode','orders.status','admins.name as seller_name', 'admins.email as seller_email','admins.company_name','admins.sm', 'admins.mobile', 'admins.id as use_id')->get();
    // echo '<pre>';print_R($orders);die;
        if(Auth::guard('admin')->user()->role_id =='1'){
            $users = Admin::where('delete_status','0')->get();    
        }elseif(Auth::guard('admin')->user()->role_id =='2'){
            $sub_user_id = Admin::getsubuserid(Auth::guard('admin')->user()->id);
            $users = Admin::where('delete_status',0)
            ->whereIn('id',$sub_user_id)
            ->get();
        }else{
            $users = Admin::where('id',$user_id)->where('delete_status','0')->get();
        }
        return view('admin.reports.view', compact('orders','couriers','re_data','users'));
    }

    public function index_filter()
    {   
        $re_data['user_id'] =$_REQUEST['user_id'];
        $re_data['start_date'] =$_REQUEST['start_date'].' 00:00:00';
        $re_data['end_date'] =$_REQUEST['end_date'].' 23:59:59';
        $re_data['ship_courier_id'] =0;
        $user_id = Auth::guard('admin')->user()->id;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $user_id = Auth::guard('admin')->user()->id;
         // $orders = Order::with('detail')
        // ->whereIn('user_id' , $re_data['user_id'])
        // ->where('status' ,'!=' ,'1')
        // ->where('created_at','>=',$re_data['start_date'])->where('created_at','<=',$re_data['end_date'])
        // ->get();
        $order_q = Order::with('detail')
        ->join('admins', 'orders.user_id', '=', 'admins.id')
        ->whereIn('orders.user_id',$re_data['user_id'])->where('orders.status','!=', 1)->where('orders.created_at','>=',$re_data['start_date'])->where('orders.created_at','<=',$re_data['end_date']);
        $orders = $order_q->select('orders.*', 'admins.name as seller_name', 'admins.email as seller_email','admins.company_name','admins.sm', 'admins.mobile', 'admins.id as use_id')->get();
    // echo '<pre>';print_R($orders);die;
        if(Auth::guard('admin')->user()->role_id =='1'){
            $users = Admin::where('delete_status','0')->get();    
        }elseif(Auth::guard('admin')->user()->role_id =='2'){
            $sub_user_id = Admin::getsubuserid(Auth::guard('admin')->user()->id);
            $users = Admin::where('delete_status',0)
            ->whereIn('id',$sub_user_id)
            ->get();
        }else{
            $users = Admin::where('id',$user_id)->where('delete_status','0')->get();
        }
        return view('admin.reports.view', compact('orders','couriers','re_data','users'));
    }
    
    public function mis(){
        $fy = get_fin_year();
        $f_year = explode('&&',$fy);
        $re_data = array();
        $user_data = Auth::guard('admin')->user();
        $user_id = $user_data->id;
        $company_id = $user_data->company_id;
        $re_data['user_id'][] =$user_id;
        $re_data['status_mis'][] =array('14');
        $re_data['start_date'] =$f_year[0];
        $re_data['end_date'] =$f_year[1];
        $re_data['ship_courier_id'] =0;
        // echo $_REQUEST;die;
        if($user_data->role_id =='1' || $user_id =='21'){
            $users = Admin::where('delete_status','0')->where('company_id',$company_id)->get();    
        }elseif($user_data->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user_data->id);
            $users = Admin::where('delete_status',0)->where('company_id',$company_id)
            ->whereIn('id',$sub_user_id)
            ->get();
        }else{
            $users = Admin::where('id',$user_id)->where('company_id',$company_id)->where('delete_status','0')->get();
        }
        
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        
        // $orders = Order::with('detail')->where(['user_id' => $user_id])->where('status','!=', 1)->where('created_at','>=',$re_data['start_date'])->where('created_at','<=',$re_data['end_date'])->get();
        $order_q = Order::with('detail')
            ->join('admins', 'orders.user_id', '=', 'admins.id')
            ->leftJoin('profiles', function($join) {
                $join->on('orders.user_id', '=', 'profiles.user_id')
                     ->whereRaw('profiles.id = (SELECT id FROM profiles WHERE profiles.user_id = orders.user_id LIMIT 1)');
            })
            ->leftJoin('warehouses', 'warehouses.id', '=', 'orders.warehouse_id')
            ->leftJoin('states', 'states.id', '=', 'profiles.state')     
            ->leftJoin('order_courier_datas', 'order_courier_datas.order_id', '=','orders.id')     
            ->where(['orders.user_id' => $user_id])->where('status','=',14)->where('orders.created_at','>=',$re_data['start_date'])->where('orders.created_at','<=',$re_data['end_date'])
            ->where('orders.company_id',$company_id);
        $orders = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id','profiles.bank_name','profiles.beneficiary_name','account_no','ifsc_code','profiles.gst','billing_address','profiles.city','states.name as pstate','warehouses.pincode as zip_code','warehouses.city as wcity','warehouses.state as wstate','order_courier_datas.c_action','order_courier_datas.c_place','order_courier_datas.c_remarks','order_courier_datas.c_date')->get();
        // echo '<pre>';print_R($orders);die;
        $warehouse = WareHouse::where('user_id',$user_id)->get();
        $counrtries = Country::get();
        
        // echo $warehouse->find(7);die;
        return view('admin.reports.mis', compact('orders','couriers','warehouse','counrtries','re_data','users','user_id'));
    }

    function mis_filter(){
//        echo '<pre>';print_R($_REQUEST);die;
        $re_data['user_id'] =$_REQUEST['user_id'];
        $re_data['status_mis'] =$_REQUEST['status_mis'];
        $re_data['start_date'] =$_REQUEST['start_date'].' 00:00:00';
        $re_data['end_date'] =$_REQUEST['end_date'].' 23:59:59';
       
//        $re_data['awb'] ='28838110007206';
        $user_data = Auth::guard('admin')->user();
        $user_id = $user_data->id;
        $company_id = $user_data->company_id;
        
        if($user_data->role_id =='1' || $user_id =='21'){
            $users = Admin::where('delete_status','0')->where('company_id',$company_id)->get();    
        }elseif($user_data->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user_data->id);
            $users = Admin::where('delete_status',0)->where('company_id',$company_id)
            ->whereIn('id',$sub_user_id)
            ->get();
        }else{
            $users = Admin::where('id',$user_id)->where('company_id',$company_id)->where('delete_status','0')->get();
        }

        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        
//         $orders = Order::with('detail')->whereIn('user_id' , $re_data['user_id'])->where('status','!=', 1)->where('created_at','>=',$re_data['start_date'])->where('created_at','<=',$re_data['end_date'])->get();
//        $order_q = Order::with('detail')
//        ->join('admins', 'orders.user_id', '=', 'admins.id')
//        ->leftJoin('profiles', function($join) {
//            $join->on('orders.user_id', '=', 'profiles.user_id')
//                 ->whereRaw('profiles.id = (SELECT id FROM profiles WHERE profiles.user_id = orders.user_id LIMIT 1)');
//        })
//        ->leftJoin('warehouses', 'warehouses.id', '=', 'orders.warehouse_id')
//        ->leftJoin('states', 'states.id', '=', 'profiles.state')
//        ->whereIn('orders.user_id' , $re_data['user_id'])->whereIn('orders.status' , $re_data['status_mis'])->where('orders.created_at','>=',$re_data['start_date'])->where('orders.created_at','<=',$re_data['end_date']);
//        $orders = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id','profiles.bank_name','profiles.beneficiary_name','account_no','ifsc_code','profiles.gst',
//        'billing_address','profiles.city','states.name as pstate','profiles.zip_code','warehouses.city as wcity','warehouses.state as wstate')->get();

        $order_q = Order::with('detail')
            ->select('orders.created_at','orders.company_id','orders.status','orders.user_id','orders.id','orders.payment_mode','vendor_order_id','channel','shipped_date','picked_date','rto_received_date','status_date','delivered_date','discount','total','shipping_courier_type','ship_courier_id','tracking_info','manifest_id','remittance_id','warehouse_id','return_warehouse_id','total_attempt','ship_fname','ship_lname','ship_phone','ship_email','ship_pincode','ship_city','ship_state','ship_country','ship_address','ship_address_2','same_add','bill_pincode','bill_city','bill_state','bill_country','bill_address','bill_address_2','length','breadth','height','weight','extra_weight','extra_weight_closed_on','extracostwithoutgst','freight','cod','rtocharge_applied','extra_weight_status','extra_weight_status','extracostgst','gst_cod','gst_freight','rto_charge_gst','zone','rto_date','rev_tracking_info','rto_charge_witoutgst','e_bill_no','tags','other_charges','freight','cod','admins.name', 'admins.email', 'admins.mobile', 'admins.company_name', 'admins.sm', 'admins.id as use_id',
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
            ->leftJoin('order_courier_datas', 'order_courier_datas.order_id', '=','orders.id')        
            ->whereIn('orders.user_id', $re_data['user_id'])
            ->whereIn('orders.status', $re_data['status_mis'])
            ->where('orders.created_at', '>=', $re_data['start_date'])
            ->where('orders.created_at', '<=', $re_data['end_date'])
            ->where('orders.company_id',$company_id);
            
           if(isset($_REQUEST['ship_courier_id']) && $_REQUEST['ship_courier_id'] !=0){
              $re_data['ship_courier_id'] =$_REQUEST['ship_courier_id'];
              $order_q = $order_q->where('orders.ship_courier_id', $_REQUEST['ship_courier_id']);
           }else{
               $re_data['ship_courier_id'] =0;
           }
        $orders = $order_q->get();

        if(count($orders)>1000){
            $orders= array();
            $warehouse= array();
            $counrtries= array();
            $addedtocron= true;
            $filterde = new Reportfilter();
            $filterde['user_id'] = $user_id;
            $filterde['company_id'] = $company_id;
            $filterde['filter_type'] = 'mis';
            $filterde['filter_start_date'] = $re_data['start_date'];
            $filterde['filter_end_date'] = $re_data['end_date'];
            $filterde['filter_courier_id'] = $re_data['ship_courier_id'];
            $filterde['filter_userid'] = implode(',',$re_data['user_id']);
            $filterde['filter_status'] = implode(',',$re_data['status_mis']);
            $filterde->save();
            return view('admin.reports.mis', compact('orders','couriers','warehouse','counrtries','re_data','users','user_id','addedtocron'));
        }else{
//         echo '<pre>';print_r(count($orders));die;
            $warehouse = WareHouse::where('user_id',$re_data['user_id'])->get();
            $counrtries = Country::get();
            
            return view('admin.reports.mis', compact('orders','couriers','warehouse','counrtries','re_data','users','user_id'));
        }
    }
    
    function remittance(){

        $user = Auth::guard('admin')->user();
        $orders= array();
        if($user->role_id =='1'){
            $order_q = Order::with('detail')
            ->join('admins', 'orders.user_id', '=', 'admins.id')
            ->leftJoin('profiles', 'orders.user_id', '=', 'profiles.user_id')
            ->where('status',3)->where('payment_mode',6);
            $orders = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.id as use_id','profiles.bank_name','profiles.beneficiary_name','account_no','ifsc_code')->get();
            $allusers = Admin::where('delete_status',0)->get();  
        }
        // echo '<pre>';print_R($orders);die;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        return view('admin.reports.remittance', compact('orders','couriers'));  
    }
    
    function mis_filternew(){
//        echo '<pre>';print_R($_REQUEST);die;
        $re_data['user_id'] =$_REQUEST['user_id'];
        $re_data['status_mis'] =$_REQUEST['status_mis'];
        $re_data['start_date'] =$_REQUEST['start_date'].' 00:00:00';
        $re_data['end_date'] =$_REQUEST['end_date'].' 23:59:59';
//        $re_data['awb'] ='28838110007206';
        $user_id = Auth::guard('admin')->user()->id;
        
        if(Auth::guard('admin')->user()->role_id =='1' || $user_id =='21'){
            $users = Admin::where('delete_status','0')->get();    
        }elseif(Auth::guard('admin')->user()->role_id =='2'){
            $sub_user_id = Admin::getsubuserid(Auth::guard('admin')->user()->id);
            $users = Admin::where('delete_status',0)
            ->whereIn('id',$sub_user_id)
            ->get();
        }else{
            $users = Admin::where('id',$user_id)->where('delete_status','0')->get();
        }

        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        
        // $orders = Order::with('detail')->whereIn('user_id' , $re_data['user_id'])->where('status','!=', 1)->where('created_at','>=',$re_data['start_date'])->where('created_at','<=',$re_data['end_date'])->get();
        $order_q = Order::with('detail')
        ->join('admins', 'orders.user_id', '=', 'admins.id')
        ->leftJoin('profiles', function($join) {
            $join->on('orders.user_id', '=', 'profiles.user_id')
                 ->whereRaw('profiles.id = (SELECT id FROM profiles WHERE profiles.user_id = orders.user_id LIMIT 1)');
        })
        ->leftJoin('warehouses', 'warehouses.id', '=', 'orders.warehouse_id')
        ->leftJoin('states', 'states.id', '=', 'profiles.state')
        ->whereIn('orders.user_id' , $re_data['user_id'])->whereIn('orders.status' , $re_data['status_mis'])->where('orders.created_at','>=',$re_data['start_date'])->where('orders.created_at','<=',$re_data['end_date']);
        $orders = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id','profiles.bank_name','profiles.beneficiary_name','account_no','ifsc_code','profiles.gst','billing_address','profiles.city','states.name as pstate','warehouses.pincode as zip_code','warehouses.city as wcity','warehouses.state as wstate')->get();
    
         echo '<pre>';print_r(($orders));die;
        $warehouse = WareHouse::where('user_id',$re_data['user_id'])->get();
        $counrtries = Country::get();
        $exceldat ='';
         $exceldat .='<table>'; 
         $exceldat .='<tr>
                            <th>Order ID</th>
                            <th>Channel</th>
                            <th>Seller</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>GST</th>
                            <th>Billing Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Pincode</th>
                            <th>Created</th>
                            <th>READY TO SHIP DATE</th>
                            <th>Delivered</th>
                            <th>Status</th>
                            <th>SKU</th>
                            <th>Qty</th>
                            <th>Order Subtotal</th>
                            <th>Order Discount Amount</th>
                            <th>Total Amount</th>
                            <th>Payment Mode</th>
                            <!-- <th>Shipping Amount</th> -->
                            <th>Invoiceid</th>
                            <th>Invoice Date</th>
                            <th>Courier Name</th>
                            <th>Tracking Number</th>
                            <th>Manifest ID</th>
                            <th>Remittance ID</th>
                            <!-- <th>Shipment Status</th>
                            <th>Pickup date</th> -->
                            <th>Pickup Warehouse</th>
                            <th>Pickup Address</th>
                            <th>Warehouse Phone</th>
                            <th>RTO Warehouse</th>
                            <th>Warehouse Phone</th>
                            <th>Total Attempts</th>
                            <th>Customer Name</th>
                            <th>Customer Phone</th>
                            <th>Customer Email</th>
                            <th>Pincode</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>Address</th>
                            <th>Billing Pin code</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>Address</th>
                            <th>Dimension(CM)</th>
                            <th>Weight(kg)</th>
                            <th>Vol.Weight(kg)</th>
                            <th>Used Weight(kg)</th>
                            <th>ExtraWeightDate</th>
                            <th>Courier Extra Weight Charges</th>
                            <!-- <th>Shipping Charge(Freight + COD)</th> -->
                            <th>SHIPPING (FREIGHT)</th>
                            <th>COD CHARGES</th>
                            <th>CGST+SGST</th>
                            <th>IGST</th>
                            <th>Zone</th>
                            <th>RTO Date</th>
                            <th>RTO AWB</th>
                            <th>RTO Charge</th>
                            <th>RTO extra weight charge</th>
                            <th>Other Charges</th>
                            <th>TOTAL CHARGES</th>
                            <th>E-WayBill Number</th>
                            <th>Tag</th>
                            <th>Place of supply</th>
                            <!-- <th>GST(CGST+SGST/IGST)</th> -->
                            <th>TOTAL INVOICE VALUE</th>
                            <th>Company name</th>
                            <th>SM</th>
                        </tr>';
        foreach($orders as  $order):
           $exceldat .='<tr>'; 
            $exceldat .='<td>'.$order->vendor_order_id.'</td>'; 
           $exceldat .='</tr>'; 
        endforeach;
        echo $exceldat;die;
        return view('admin.reports.mis', compact('orders','couriers','warehouse','counrtries','re_data','users','user_id'));
    }
    
    function requestedreport(){
        $currentuser = Auth::guard('admin')->user();
        if($currentuser->admin =='1'){
            $requestedreport = Reportfilter::where('company_id',$currentuser->company_id)->get();
        }else{
            $requestedreport = Reportfilter::where('company_id',$currentuser->company_id)->where('user_id',$currentuser->id)->get();
        }
        return view('admin.reports.requestedreport', compact('requestedreport'));
       
    }
    
    function dtrqrp($id){
        $currentuser = Auth::guard('admin')->user();
        $requestedreport = Reportfilter::where('user_id',$currentuser->id)->where('id',$id)->first();
        if($requestedreport){
            Reportfilter::where('id', $id)->delete();
            return Redirect()->back()->with('success', 'Deleted successfully!');
        }
        return Redirect()->back()->with('error', 'No Access!');
        
    }
    
    public function logs(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $current_company = $user->company_id;
        $query = ActivityLog::query();
        $query->where('company_id',$current_company);
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = date('Y-m-d 00:00:00', strtotime($request->input('start_date')));
            $endDate = date('Y-m-d 23:59:59', strtotime($request->input('end_date')));
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else { 
            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 23:59:59');
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }
    
        if ($request->filled('search')) {
            $query->where('action', 'like', '%' . $request->input('search') . '%');
        }
        
        $logs = $query->get();
        $users = Admin::where('delete_status', '0')->get();
        return view('admin.role.logs', [
            'logs' => $logs,
            'users' => $users,
        ]);
    }
    
    public function rem_report(Request $request)
    {
        $query = Remittance::query();

     
        if ($request->filled('id')) {
            $query->where('id', $request->input('id'));
        }
    
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        $remittances = $query->paginate(10); 
        $orderIds = [];
        
        foreach ($remittances as $remittance) {
            $orderIds = array_merge($orderIds, explode(',', $remittance->order_id));
        }
    
        // Remove duplicates and ensure integer values
        $orderIds = array_unique(array_map('intval', $orderIds));
    
        // Fetch all orders using the query builder
        $orders = DB::table('orders')
            ->whereIn('id', $orderIds)
            ->get()
            ->keyBy('id');
    
        $formattedRemittances = [];
        foreach ($remittances as $remittance) {
            $remittanceOrderIds = explode(',', $remittance->order_id); // Split order_id values
    
            foreach ($remittanceOrderIds as $orderId) {
                $orderId = intval($orderId); // Ensure it's an integer
                $order = $orders->get($orderId); // Use the collection method 'get'
                
                $formattedRemittances[] = [
                    'id' => $remittance->id,
                    'order_id' => $orderId,
                    'total' => $order ? $order->total : 'N/A', // Handle null case
                    'vendor_order_id' => $order ? $order->vendor_order_id : 'N/A', // Handle null case
                    'amount' => $remittance->amount,
                    'cod_amount' => $remittance->cod_amount,
                    'shipping_amount' => $remittance->shipping_amount,
                    'paid' => $remittance->paid,
                    'recharge' => $remittance->recharge,
                    'start_date' => $remittance->start_date,
                    'end_date' => $remittance->end_date,
                    'status' => $remittance->status,
                    'utr' => $remittance->utr,
                ];
            }
        }
    
        // Convert to a Collection for Blade template
        $remittancesCollection = collect($formattedRemittances);

        return view('admin.reports.remittance_report', [
            'remittances' => $remittancesCollection,
            'pagination' => $remittances->appends($request->except('page')) // Preserves filters when navigating pages
        ]);
    }

}
