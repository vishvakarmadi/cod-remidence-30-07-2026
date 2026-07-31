<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\Manifest;
use App\Models\Admin\Order;
use App\Models\Admin\Pincode;
use App\Models\Admin\Ratecard;
use App\Models\Admin\Integration;
use App\Models\Admin\Integration_more;
use App\Models\Admin\Integration_courier;
use App\Models\Admin\TempAssignOrder;
use App\Mail\EmailVerify;
use App\Models\Admin\Courier;
use App\Models\Admin\OrderDetail;
use App\Models\Admin\Admin;
use App\Models\Admin\Status;
use App\Models\Admin\Warehouse;
use App\Models\Admin\Xb_pincode;
use App\Models\Admin\ApiLog;
use App\Models\Admin\Servicable_pincode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use DOMDocument;
use DB;
use App\Models\Admin\Transaction;
use App\Models\Admin\Remittance;
use App\Models\Admin\ActivityLog;
use App\Models\Admin\Channel_integration;
use App\Models\Admin\Channel;
use PDF;
use Illuminate\Support\Facades\Mail;
use NumberFormatter;
use App\Models\LabelSetting;

class OrderController extends Controller
{
    //customer_code is to be changed for prod
    public function __construct()
    {
        $this->middleware('admin');
    }
	
    public function shipment_report()
    {
        $user = Auth::guard('admin')->user();
        $current_company = $user->company_id;
        $user_id = $user->id;
        if($user->role_id =='1' ){
            $order =Order::select('status', DB::raw('count(*) as total'))
            ->where('company_id',$current_company)
            ->groupBy('status')
            ->get();
        }elseif($user->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user_id);
            $order =Order::select('status', DB::raw('count(*) as total'))
            ->where('company_id',$current_company)
            ->whereIn('user_id',$sub_user_id)
            ->groupBy('status')
            ->get();
        }else{
            $order =Order::select('status', DB::raw('count(*) as total'))
            ->where('company_id',$current_company)
            ->where('user_id',$user_id)
            ->groupBy('status')
            ->get();
        }
       return view('admin.order.shipment_report',compact('order'));
    }
    public function shipment_list($count)
    {
        $order = Order::orderBy('shipped_date', 'desc')->where('status',2)->get();

        return view('admin.order.shipment_list',compact('order'));
}

    public function index1()
    {
       $user_id = Auth::guard('admin')->user()->id;
       $warehouse = WareHouse::orderBy('default', 'desc')->where('user_id',$user_id)->where('deleted','0')->get();
       $re_data = array();
       $s_array = array('1');
       $sortField = 'id';
       $sortDirection = 'desc';
        if(!empty($_REQUEST)){
             echo '<pre>';print_R($_REQUEST);die;
            if(isset($_REQUEST['sortField']) && $_REQUEST['sortField'] !=''){
                $sortField = $_REQUEST['sortField'];
             }
             if(isset($_REQUEST['sortDirection']) && $_REQUEST['sortDirection'] !=''){
                $sortDirection = $_REQUEST['sortDirection'];
             }
           
            if(Auth::guard('admin')->user()->role_id =='1' ){
                $order_q = Order::with('detail');
            }elseif(Auth::guard('admin')->user()->role_id =='2'){
                $sub_user_id = Admin::getsubuserid($user_id);
                $order_q = Order::with('detail')->whereIn('user_id' , $sub_user_id);
            }else{
                $order_q = Order::with('detail')->where(['user_id' => $user_id]);
            }

            if(isset($_REQUEST['vendor_order_id']) && $_REQUEST['vendor_order_id'] !=''){
              $ven = explode(',',$_REQUEST['vendor_order_id']);
              $order_q->whereIn('vendor_order_id', $ven);
            }
            if(isset($_REQUEST['status_order']) && $_REQUEST['status_order'] !=''){
              if( $_REQUEST['status_order']=='1'){
                  $s_array = array('1');
              }
              if( $_REQUEST['status_order']=='4'){
                  $s_array = array('4');
              }
              if( $_REQUEST['status_order']=='0'){
                  $s_array = array('1','4');
              }
              $order_q->whereIn('status', $s_array);
            }
            if(isset($_REQUEST['seller_id']) && $_REQUEST['seller_id'] !='0'){
                $order_q->where('user_id', $_REQUEST['seller_id']);
            }
            
            $totalOrdersCount = $order_q->count(); // Get the total count of orders
            $order = $order_q->orderBy($sortField, $sortDirection)->paginate(25);
            
            $re_data = $_REQUEST;
        }else{
            if(Auth::guard('admin')->user()->role_id =='1' ){
                $order_q = Order::with('detail');
            }elseif(Auth::guard('admin')->user()->role_id =='2'){
                $sub_user_id = Admin::getsubuserid($user_id);
                $order_q = Order::with('detail')->whereIn('user_id' , $sub_user_id);
            }else{
                $order_q = Order::with('detail')->where(['user_id' => $user_id]);
            }
            // $order = $order_q->orderBy('id', 'desc')->paginate(50);
            $totalOrdersCount = $order_q->orderBy('id', 'desc')->whereIn('status', $s_array)->count(); // Get the total count of orders
            $order = $order_q->orderBy($sortField, $sortDirection)->whereIn('status', $s_array)->paginate(25);
            
                
        }
        if(Auth::guard('admin')->user()->role_id =='1' ){
            $userwithneworder = Order::where('status', array('1','4'))
            ->join('admins', 'orders.user_id', '=', 'admins.id');
        }elseif(Auth::guard('admin')->user()->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user_id);
            $userwithneworder = Order::where('status', array('1','4'))->whereIn('user_id' , $sub_user_id)
            ->join('admins', 'orders.user_id', '=', 'admins.id');
        }else{
            $userwithneworder = Order::where('status', array('1','4'))->where(['user_id' => $user_id])
            ->join('admins', 'orders.user_id', '=', 'admins.id');
        }
        $userwithneworder = $userwithneworder->select('admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id')
        ->distinct('admins.name')->orderBy('admins.name')->get();
    echo '<pre>';print_R($userwithneworder);die;
        return view('admin.order.index', compact('sortField','sortDirection','order','warehouse','re_data','userwithneworder','totalOrdersCount'));
    }
    
    public function index()
    {
        $user = Auth::guard('admin')->user();
        $user_id = $user->id;
        $role_id = $user->role_id;
        $current_company = $user->company_id;
        // Fetch warehouses
        $warehouse = WareHouse::orderBy('default', 'desc')
            ->where('user_id', $user_id)
            ->where('deleted', '0')
            ->where('company_id',$current_company)
            ->get();
        
        // Initialize query for orders
        $orderQuery = Order::with('detail')->select(
            'user_id', 'channel', 'id', 'order_id', 'vendor_order_id', 
            'status', 'payment_mode', 'total', 'weight', 'length', 
            'breadth', 'height', 'ship_fname', 'ship_lname', 'reverse_order','custom_total'
        );

        $sub_user_ids = Admin::getsubuserid($user_id);

        // Apply role-based filters
        if ($role_id == '2') {
            $orderQuery->whereIn('user_id', $sub_user_ids);
        } elseif ($role_id != '1') {
            $orderQuery->where('user_id', $user_id);
        }

        // Handle request filters
        $sortField = request('sortField', 'id');
        $sortDirection = request('sortDirection', 'desc');
        $vendorOrderIds = request('vendor_order_id', '');
        $statusOrder = request('status_order', '1');
        $sellerId = request('seller_id', $user_id);

        if ($vendorOrderIds) {
            $orderQuery->whereIn('vendor_order_id', explode(',', $vendorOrderIds));
        }
        if (request('payment_mode')) {
            $orderQuery->where('payment_mode', request('payment_mode'));
        }

        $statusArray = ($statusOrder == '0') ? ['1', '4'] : [$statusOrder];
        $orderQuery->whereIn('status', $statusArray);

        if ($sellerId != '0') {
            $orderQuery->where('user_id', $sellerId);
        } else {
            $orderQuery->where('user_id', $user_id);
        }
        $orderQuery->where('company_id',$current_company);
        // Get total orders count before pagination
        $totalOrdersCount = $orderQuery->count();

        $allOrders = $orderQuery->get(); // Fetch all relevant orders

        // Gather criteria for duplicate checking
        $duplicateCheckData = $allOrders->map(function ($order) {
            return [
                'user_id' => $order->user_id,
                'ship_fname' => $order->ship_fname,
                'ship_lname' => $order->ship_lname,
                'ship_phone' => $order->ship_phone,
                'total' => $order->total,
                'order_id' => $order->id // Keep original order ID for reference
            ];
        });

        // Check for duplicates in bulk
        $duplicates = $this->checkForDuplicates($duplicateCheckData,$statusArray);

        // Attach duplicate status to orders
        foreach ($allOrders as $order) {
            $order->duplicate = $duplicates[$order->id] ?? 'no'; // Default to 'no' if not found
        }
       
        // Now use the original query to apply pagination
        $orders = $orderQuery->orderBy($sortField, $sortDirection)->paginate(25); // This will give you a paginator instance

        // Attach duplicate status to paginated orders
        foreach ($orders as $order) {
            $order->duplicate = $duplicates[$order->id] ?? 'no'; // Attach the duplicate status
        }
        $order = $orders;
        
        if(!isset($_REQUEST['seller_id'])){
            $_REQUEST['seller_id'] = $user_id;
        }
        $re_data = $_REQUEST;
        // Fetch users with new orders based on role
        $userwithneworder = Admin::where('delete_status', '0')
            ->where('active', '1')
            ->where('company_id',$current_company)
            ->when($role_id == '2', function ($query) use ($sub_user_ids) {
                return $query->whereIn('id', $sub_user_ids);
            })
            ->when($role_id != '1', function ($query) use ($user_id) {
                return $query->where('id', $user_id);
            })
            ->get();
//echo '<pre>';print_R($order);die;
        // Prepare data for view
        return view('admin.order.index', compact(
            'sortField', 'sortDirection', 'order', 'warehouse', 
            'userwithneworder', 'totalOrdersCount','re_data'
        ));
    }
    
    private function checkForDuplicates($orderData,$statusArray)
    {   
//        echo '<pre>';print_R($orderData);die;
        // Create a query to fetch potential duplicates based on the criteria
        $duplicateOrders = Order::whereIn('id', $orderData->pluck('order_id')->toArray())
            ->whereIn('status', $statusArray)
            ->get()
            ->groupBy(function ($item) {
                return "{$item->ship_fname}|{$item->ship_lname}|{$item->ship_phone}|{$item->user_id}|{$item->total}";
            });

        // Prepare a map for duplicate statuses
        $duplicateMap = [];

        foreach ($duplicateOrders as $key => $group) {
            if ($group->count() > 1) { // More than one means duplicates exist
                foreach ($group as $order) {
                    $duplicateMap[$order->id] = 'yes'; // Mark all duplicates as 'yes'
                }
            }
        }

        return $duplicateMap; // Return a map of order IDs to duplicate status
    }
    
    
   public function printMultiple4x6Labels(Request $request)
    {
        $ids = array_keys($request->id);

        $orders = Order::with('detail')
            ->whereIn('id', $ids)
            ->where('tracking_info', '!=', null)
            ->get();

        $user = Auth::guard('admin')->user();

        $couriers = json_decode(
            file_get_contents(resource_path('views/admin/courier.json')),
            true
        );

        foreach ($orders as $order) {
            if ($order->warehouse_id) {
                $order['warehouse'] = Warehouse::find($order->warehouse_id);
            }
        }

        return view(
            'admin.order.print4x6',
            compact('orders', 'couriers', 'user')
        );
    }
    



public function printMultipleLabels(Request $request)
        {
            $ids = array_keys($request->id);

            $orders = Order::with('detail')
                ->whereIn('id', $ids)
                ->where('tracking_info', '!=', null)
                ->get();

            $user = Auth::guard('admin')->user();

            // Load label settings to determine printer type
            $labelSetting = LabelSetting::where('user_id', $user->id)->first();

            $couriers = json_decode(
                file_get_contents(resource_path('views/admin/courier.json')),
                true
            );

            foreach ($orders as $order) {
                if ($order->warehouse_id) {
                    $order['warehouse'] = Warehouse::find($order->warehouse_id);
                }
            }
            // dd($labelSetting);

            $only_invoice = $labelSetting ? $labelSetting->premium_invoice_only : false;

            // printer_type: 1 = Thermal 4x6, 2 = Standard A4 (4 labels per sheet)
            if ($labelSetting && $labelSetting->printer_type == 2) {
                // A4 Layout Option: 0 = Only Label (4 per page), 1 = Label + Invoice
                // If only_invoice setting is ON, we must use the layout that supports invoices
                $view = ($labelSetting->a4_print_option == 1 || $only_invoice) 
                    ? 'admin.order.A4_Label_and_invoice' 
                    : 'admin.order.multiple_label_print1';
            } else {
                // If it's a thermal user but they forced "Only Invoice", 
                // thermal labels don't typically support full invoices, 
                // so we might switch to Premium layout if only_invoice is true.
                if ($only_invoice) {
                    $view = 'admin.order.courierprint';
                    $label_arra = $orders; // courierprint expects label_arra
                    return view($view, compact('label_arra', 'couriers', 'user', 'labelSetting', 'only_invoice'));
                }
                $view = 'admin.order.print4x61';
            }
            // dd($view);

            return view(
                $view,
                compact('orders', 'couriers', 'user', 'labelSetting', 'only_invoice')
            );
        }


    public function printMultipleLabelsWithInvoice(Request $request)
    {
        $ids = array_keys($request->id);
        $label_arra = Order::with('detail')->whereIn('id', $ids)->get();
        $user = Auth::guard('admin')->user();
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        
        // Respect global setting if not specifically overridden
        $labelSetting = LabelSetting::where('user_id', $user->id)->first();
        $only_invoice = $labelSetting ? $labelSetting->premium_invoice_only : false;
        
        return view('admin.order.courierprint', compact('label_arra','couriers','user', 'only_invoice'));
    }

    public function printMultipleInvoicesOnly(Request $request)
    {
        $ids = array_keys($request->id);
        $label_arra = Order::with('detail')->whereIn('id', $ids)->get();
        $user = Auth::guard('admin')->user();
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        
        $only_invoice = true;
        
        $labelSetting = LabelSetting::where('user_id', $user->id)->first();
        if ($labelSetting && $labelSetting->printer_type == 2) {
            // Standard A4 user wants only invoices -> use A4 layout with only_invoice flag
            $orders = $label_arra;
            return view('admin.order.A4_Label_and_invoice', compact('orders', 'couriers', 'user', 'labelSetting', 'only_invoice'));
        }
        
        // Default to Premium Portrait for Only Invoice
        return view('admin.order.courierprint', compact('label_arra','couriers','user', 'only_invoice'));
    }


    public function all_order()
    {
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
//echo '<pre>';print_R($_REQUEST);die;
        if(!empty($_REQUEST)){
            if(isset($_REQUEST['seller_id']) && $_REQUEST['seller_id'] !='0'  && $_REQUEST['seller_id'] !=''){
                $order_q = Order::with('detail')
                    ->where(['user_id' => $_REQUEST['seller_id']]);
            }else{
                if(Auth::guard('admin')->user()->role_id =='1' ){
                    $order_q = Order::with('detail');
                }elseif(Auth::guard('admin')->user()->role_id =='2'){
                    $sub_user_id = Admin::getsubuserid($user_id);
                    $order_q = Order::with('detail')
                    ->whereIn('user_id' , $sub_user_id);
                }else{
                    $order_q = Order::with('detail')
                    ->where(['user_id' => $user_id]);
                }
            }
            $order_q->where('company_id',$current_company);
             if(isset($_REQUEST['payment_mode']) && $_REQUEST['payment_mode'] !=''){
                $order_q->where('payment_mode', $_REQUEST['payment_mode']);
             }
             if(isset($_REQUEST['sortField']) && $_REQUEST['sortField'] !=''){
                $sortField = $_REQUEST['sortField'];
             }
             if(isset($_REQUEST['sortDirection']) && $_REQUEST['sortDirection'] !=''){
                $sortDirection = $_REQUEST['sortDirection'];
             }
            if(isset($_REQUEST['vendor_order_id']) && $_REQUEST['vendor_order_id'] !=''){
              $ven = explode(',',$_REQUEST['vendor_order_id']);
              $order_q->whereIn('vendor_order_id', $ven);
            }
            if(isset($_REQUEST['tracking_info']) && $_REQUEST['tracking_info'] !=''){
               $tracking_info_Array = explode(',',$_REQUEST['tracking_info']);
               $order_q->whereIn('tracking_info', $tracking_info_Array);
            }
            if(isset($_REQUEST['order_status']) && $_REQUEST['order_status'] !='0'){
                $order_q->where('status', $_REQUEST['order_status']);
            }
            if(isset($_REQUEST['buyer_name']) && $_REQUEST['buyer_name'] !=''){
              $buyer_name = $_REQUEST['buyer_name'];
               $order_q->whereRaw("CONCAT(ship_fname, ' ', ship_lname) = ?", [$buyer_name]);
            }
           	if(isset($_REQUEST['ship_courier_id']) && $_REQUEST['ship_courier_id'] !='0'){
                $order_q->where('ship_courier_id', $_REQUEST['ship_courier_id']);
            }
            if(isset($_REQUEST['start_date']) && $_REQUEST['start_date'] !=''){
                $_REQUEST['start_date'] = $_REQUEST['start_date'].' 00:00:00';
                $order_q->where('created_at','>=', $_REQUEST['start_date']);
            }else{
                $_REQUEST['start_date'] =$re_data['start_date'];
            }
            if(isset($_REQUEST['end_date']) && $_REQUEST['end_date'] !=''){
                $_REQUEST['end_date'] = $_REQUEST['end_date'].' 23:59:59';
                $order_q->where('created_at','<=', $_REQUEST['end_date']);
            }else{
                $_REQUEST['end_date'] =$re_data['end_date'];
            }
            if(isset($_REQUEST['exceptnewcancel'])){
                $order_q->whereNOTIN('status', array('1','4'));
            }
            if(isset($_REQUEST['delivered'])){
                $_REQUEST['order_status'] = '3';
                $order_q->where('status', array('3'));
            }
            if(isset($_REQUEST['rto'])){
                $order_q->whereIN('status', array('5','6','13'));
            }
            if(isset($_REQUEST['intransit'])){ 
                $order_q->whereIN('status', array('14','16','17','15','10'));
            }
            if(isset($_REQUEST['intrait'])){ 
                $order_q->whereIN('status', array('14'));
            }
            if(isset($_REQUEST['shipped_today'])){ 
                $order_q->where('shipped_date','>=',date('Y-m-d').' 00:00:00');
                $order_q->where('shipped_date','<=', date('Y-m-d').' 23:59:59');
                $order_q->where('tracking_info','!=', null);
            }
            if(isset($_REQUEST['manifest_today'])){ 
                $order_q->where('manifest_date','>=',date('Y-m-d').' 00:00:00');
                $order_q->where('manifest_date','<=', date('Y-m-d').' 23:59:59');
                $order_q->where('tracking_info','!=', null);
                $order_q->where('status','12');
                
            }
            if(isset($_REQUEST['manifested_before12'])){ 
                $order_q->where('manifest_date','>=',date('Y-m-d').' 00:00:00');
                $order_q->where('manifest_date','<=', date('Y-m-d').' 11:59:59');
                $order_q->where('tracking_info','!=', null);
                $order_q->where('status','12');
                
            }
            if(isset($_REQUEST['delivered_today'])){ 
                $_REQUEST['order_status'] = '3';
                $order_q->where('delivered_date','>=',date('Y-m-d').' 00:00:00');
                $order_q->where('delivered_date','<=', date('Y-m-d').' 23:59:59');
                $order_q->where('tracking_info','!=', null);
                $order_q->where('status','3');
                
            }
            if(isset($_REQUEST['ood'])){ 
                $_REQUEST['order_status'] = '15';
                $order_q->where('status_date','>=',date('Y-m-d').' 00:00:00');
                $order_q->where('status_date','<=', date('Y-m-d').' 23:59:59');
                $order_q->where('tracking_info','!=', null);
                $order_q->where('status','15');
                
            }
            if(isset($_REQUEST['ndr'])){ 
               $order_q->where('shipped_date' ,'!=', null);
            }
            if(isset($_REQUEST['role_id'])){
                $role_id = $_REQUEST['role_id'];
                if($role_id=='tw'){//last seven
                    $now = date('Y-m-d');
                    $_REQUEST['start_date'] = date('Y-m-d', strtotime($now. ' - 6 days')).' 00:00:00';
                    $_REQUEST['end_date'] = date('Y-m-d').' 23:59:59';
                } 
                if($role_id=='tod'){//today
                    $_REQUEST['start_date'] = date('Y-m-d').' 00:00:00';
                    $_REQUEST['end_date'] = date('Y-m-d').' 23:59:59';
                }
                if($role_id=='yes'){//Yesterday
                    $now = date('Y-m-d');
                    $_REQUEST['start_date'] = date('Y-m-d', strtotime($now. ' - 1 days')).' 00:00:00';
                    $_REQUEST['end_date'] = date('Y-m-d', strtotime($now. ' - 1 days')).' 23:59:59';
                }
                if($role_id=='lt'){//last three
                    $now = date('Y-m-d');
                    $_REQUEST['start_date'] = date('Y-m-d', strtotime($now. ' - 2 days')).' 00:00:00';
                    $_REQUEST['end_date'] = date('Y-m-d').' 23:59:59';
                }
                if($role_id=='lf'){//last fifteen
                    $now = date('Y-m-d');
                    $_REQUEST['start_date'] = date('Y-m-d', strtotime($now. ' - 14 days')).' 00:00:00';
                    $_REQUEST['end_date'] = date('Y-m-d').' 23:59:59';
                }
                if($role_id=='lth'){//last 30
                    $now = date('Y-m-d');
                    $_REQUEST['start_date'] = date('Y-m-d', strtotime($now. ' - 29 days')).' 00:00:00';
                    $_REQUEST['end_date'] = date('Y-m-d').' 23:59:59';
                }
                $order_q->where('created_at','>=', $_REQUEST['start_date']);

                $order_q->where('created_at','<=', $_REQUEST['end_date']);
            }
//            $order = $order_q->paginate(50);
             $re_data = $_REQUEST;
        }else{
//            echo $re_data['start_date'].'-->'.$re_data['end_date'];die;
            if(Auth::guard('admin')->user()->role_id =='1' ){
                $order_q = Order::with('detail')->where('created_at','>=', $re_data['start_date'])->where('created_at','<=', $re_data['end_date']);
            }elseif(Auth::guard('admin')->user()->role_id =='2'){
                $sub_user_id = Admin::getsubuserid($user_id);
                $order_q = Order::with('detail')->whereIn('user_id' , $sub_user_id)->where('created_at','>=', $re_data['start_date'])->where('created_at','<=', $re_data['end_date']);
            }else{
                $order_q = Order::with('detail')->where(['user_id' => $user_id])->where('created_at','>=', $re_data['start_date'])->where('created_at','<=', $re_data['end_date']);
            }
        }
        $order_q->where('company_id',$current_company);
        
        // Hide New Orders ('1') by default unless specifically searched
        $isSearchingForNew = isset($_REQUEST['order_status']) && $_REQUEST['order_status'] == '1';
        $isSearchingByText = !empty($_REQUEST['vendor_order_id']) || !empty($_REQUEST['tracking_info']) || !empty($_REQUEST['buyer_name']);
        
        if (!$isSearchingForNew && !$isSearchingByText) {
            $order_q->where('status', '!=', '1');
        }
        // Clone query for seller filter
        $sellerQuery = clone $order_q;

        // Get seller IDs from filtered data
        $sellerIds = $sellerQuery->get()->map(function ($order) {
                return $order->getRawOriginal('user_id');
            })->unique();

        // Get seller list
        $sellers = Admin::whereIn('id', $sellerIds)
            ->select('id', 'name')
            ->get();
        $totalOrdersCount = $order_q->count(); // Get the total count of orders
//        echo $totalOrdersCount;die;
        $order = $order_q->orderBy($sortField, $sortDirection)->paginate(50);
        return view('admin.order.all', compact('order','warehouse','couriers','re_data','sortField','sortDirection','totalOrdersCount','sellers'));
    }

   public function all_order1()
    {
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
//echo '<pre>';print_R($_REQUEST);die;
        if(!empty($_REQUEST)){
            if(isset($_REQUEST['seller_id']) && $_REQUEST['seller_id'] !='0'  && $_REQUEST['seller_id'] !=''){
                $order_q = Order::with('detail')
                    ->where(['user_id' => $_REQUEST['seller_id']]);
            }else{
                if(Auth::guard('admin')->user()->role_id =='1' ){
                    $order_q = Order::with('detail');
                }elseif(Auth::guard('admin')->user()->role_id =='2'){
                    $sub_user_id = Admin::getsubuserid($user_id);
                    $order_q = Order::with('detail')
                    ->whereIn('user_id' , $sub_user_id);
                }else{
                    $order_q = Order::with('detail')
                    ->where(['user_id' => $user_id]);
                }
            }
            $order_q->where('company_id',$current_company);
             if(isset($_REQUEST['payment_mode']) && $_REQUEST['payment_mode'] !=''){
                $order_q->where('payment_mode', $_REQUEST['payment_mode']);
             }
             if(isset($_REQUEST['sortField']) && $_REQUEST['sortField'] !=''){
                $sortField = $_REQUEST['sortField'];
             }
             if(isset($_REQUEST['sortDirection']) && $_REQUEST['sortDirection'] !=''){
                $sortDirection = $_REQUEST['sortDirection'];
             }
            if(isset($_REQUEST['vendor_order_id']) && $_REQUEST['vendor_order_id'] !=''){
              $ven = explode(',',$_REQUEST['vendor_order_id']);
              $order_q->whereIn('vendor_order_id', $ven);
            }
            if(isset($_REQUEST['tracking_info']) && $_REQUEST['tracking_info'] !=''){
               $tracking_info_Array = explode(',',$_REQUEST['tracking_info']);
               $order_q->whereIn('tracking_info', $tracking_info_Array);
            }
            if(isset($_REQUEST['order_status']) && $_REQUEST['order_status'] !='0'){
                $order_q->where('status', $_REQUEST['order_status']);
            }
            if(isset($_REQUEST['buyer_name']) && $_REQUEST['buyer_name'] !=''){
              $buyer_name = $_REQUEST['buyer_name'];
               $order_q->whereRaw("CONCAT(ship_fname, ' ', ship_lname) = ?", [$buyer_name]);
            }
           	if(isset($_REQUEST['ship_courier_id']) && $_REQUEST['ship_courier_id'] !='0'){
                $order_q->where('ship_courier_id', $_REQUEST['ship_courier_id']);
            }
            if(isset($_REQUEST['start_date']) && $_REQUEST['start_date'] !=''){
                $_REQUEST['start_date'] = $_REQUEST['start_date'].' 00:00:00';
                $order_q->where('created_at','>=', $_REQUEST['start_date']);
            }else{
                $_REQUEST['start_date'] =$re_data['start_date'];
            }
            if(isset($_REQUEST['end_date']) && $_REQUEST['end_date'] !=''){
                $_REQUEST['end_date'] = $_REQUEST['end_date'].' 23:59:59';
                $order_q->where('created_at','<=', $_REQUEST['end_date']);
            }else{
                $_REQUEST['end_date'] =$re_data['end_date'];
            }
            if(isset($_REQUEST['exceptnewcancel'])){
                $order_q->whereNOTIN('status', array('1','4'));
            }
            if(isset($_REQUEST['delivered'])){
                $_REQUEST['order_status'] = '3';
                $order_q->where('status', array('3'));
            }
            if(isset($_REQUEST['rto'])){
                $order_q->whereIN('status', array('5','6','13'));
            }
            if(isset($_REQUEST['intransit'])){ 
                $order_q->whereIN('status', array('14','16','17','15','10'));
            }
            if(isset($_REQUEST['intrait'])){ 
                $order_q->whereIN('status', array('14'));
            }
            if(isset($_REQUEST['shipped_today'])){ 
                $order_q->where('shipped_date','>=',date('Y-m-d').' 00:00:00');
                $order_q->where('shipped_date','<=', date('Y-m-d').' 23:59:59');
                $order_q->where('tracking_info','!=', null);
            }
            if(isset($_REQUEST['manifest_today'])){ 
                $order_q->where('manifest_date','>=',date('Y-m-d').' 00:00:00');
                $order_q->where('manifest_date','<=', date('Y-m-d').' 23:59:59');
                $order_q->where('tracking_info','!=', null);
                $order_q->where('status','12');
                
            }
            if(isset($_REQUEST['manifested_before12'])){ 
                $order_q->where('manifest_date','>=',date('Y-m-d').' 00:00:00');
                $order_q->where('manifest_date','<=', date('Y-m-d').' 11:59:59');
                $order_q->where('tracking_info','!=', null);
                $order_q->where('status','12');
                
            }
            if(isset($_REQUEST['delivered_today'])){ 
                $_REQUEST['order_status'] = '3';
                $order_q->where('delivered_date','>=',date('Y-m-d').' 00:00:00');
                $order_q->where('delivered_date','<=', date('Y-m-d').' 23:59:59');
                $order_q->where('tracking_info','!=', null);
                $order_q->where('status','3');
                
            }
            if(isset($_REQUEST['ood'])){ 
                $_REQUEST['order_status'] = '15';
                $order_q->where('status_date','>=',date('Y-m-d').' 00:00:00');
                $order_q->where('status_date','<=', date('Y-m-d').' 23:59:59');
                $order_q->where('tracking_info','!=', null);
                $order_q->where('status','15');
                
            }
            if(isset($_REQUEST['ndr'])){ 
               $order_q->where('shipped_date' ,'!=', null);
            }
            if(isset($_REQUEST['role_id'])){
                $role_id = $_REQUEST['role_id'];
                if($role_id=='tw'){//last seven
                    $now = date('Y-m-d');
                    $_REQUEST['start_date'] = date('Y-m-d', strtotime($now. ' - 6 days')).' 00:00:00';
                    $_REQUEST['end_date'] = date('Y-m-d').' 23:59:59';
                } 
                if($role_id=='tod'){//today
                    $_REQUEST['start_date'] = date('Y-m-d').' 00:00:00';
                    $_REQUEST['end_date'] = date('Y-m-d').' 23:59:59';
                }
                if($role_id=='yes'){//Yesterday
                    $now = date('Y-m-d');
                    $_REQUEST['start_date'] = date('Y-m-d', strtotime($now. ' - 1 days')).' 00:00:00';
                    $_REQUEST['end_date'] = date('Y-m-d', strtotime($now. ' - 1 days')).' 23:59:59';
                }
                if($role_id=='lt'){//last three
                    $now = date('Y-m-d');
                    $_REQUEST['start_date'] = date('Y-m-d', strtotime($now. ' - 2 days')).' 00:00:00';
                    $_REQUEST['end_date'] = date('Y-m-d').' 23:59:59';
                }
                if($role_id=='lf'){//last fifteen
                    $now = date('Y-m-d');
                    $_REQUEST['start_date'] = date('Y-m-d', strtotime($now. ' - 14 days')).' 00:00:00';
                    $_REQUEST['end_date'] = date('Y-m-d').' 23:59:59';
                }
                if($role_id=='lth'){//last 30
                    $now = date('Y-m-d');
                    $_REQUEST['start_date'] = date('Y-m-d', strtotime($now. ' - 29 days')).' 00:00:00';
                    $_REQUEST['end_date'] = date('Y-m-d').' 23:59:59';
                }
                $order_q->where('created_at','>=', $_REQUEST['start_date']);

                $order_q->where('created_at','<=', $_REQUEST['end_date']);
            }
//            $order = $order_q->paginate(50);
             $re_data = $_REQUEST;
        }else{
//            echo $re_data['start_date'].'-->'.$re_data['end_date'];die;
            if(Auth::guard('admin')->user()->role_id =='1' ){
                $order_q = Order::with('detail')->where('created_at','>=', $re_data['start_date'])->where('created_at','<=', $re_data['end_date']);
            }elseif(Auth::guard('admin')->user()->role_id =='2'){
                $sub_user_id = Admin::getsubuserid($user_id);
                $order_q = Order::with('detail')->whereIn('user_id' , $sub_user_id)->where('created_at','>=', $re_data['start_date'])->where('created_at','<=', $re_data['end_date']);
            }else{
                $order_q = Order::with('detail')->where(['user_id' => $user_id])->where('created_at','>=', $re_data['start_date'])->where('created_at','<=', $re_data['end_date']);
            }
        }
        $order_q->where('company_id',$current_company);
        
        // Hide New Orders ('1') by default unless specifically searched
        $isSearchingForNew = isset($_REQUEST['order_status']) && $_REQUEST['order_status'] == '1';
        $isSearchingByText = !empty($_REQUEST['vendor_order_id']) || !empty($_REQUEST['tracking_info']) || !empty($_REQUEST['buyer_name']);
        
        if (!$isSearchingForNew && !$isSearchingByText) {
            $order_q->where('status', '!=', '1');
        }
        // Clone query for seller filter
        $sellerQuery = clone $order_q;

        // Get seller IDs from filtered data
        $sellerIds = $sellerQuery->get()->map(function ($order) {
                return $order->getRawOriginal('user_id');
            })->unique();

        // Get seller list
        $sellers = Admin::whereIn('id', $sellerIds)
            ->select('id', 'name')
            ->get();
        $totalOrdersCount = $order_q->count(); // Get the total count of orders
//        echo $totalOrdersCount;die;
        $order = $order_q->orderBy($sortField, $sortDirection)->paginate(50);
        return view('admin.order.all', compact('order','warehouse','couriers','re_data','sortField','sortDirection','totalOrdersCount','sellers'));
    }
    
    



    public function create()
    {
        $counrtries = Country::where('id','101')->get();
        $order = Order::latest()->first();
        if ($order) {
            $lastOrderId = $order->id;
            $order_id = sprintf('%03d', intval($lastOrderId) + 1);
        } else {
            $order_id = '001';
        }
        return view('admin.order.create',compact('order_id','counrtries'));
    }

    public function store(Request $request)
    {
        $msdsProducts = [
            // 🔴 Flammable
            // 'petrol','diesel','kerosene','paints','paint thinner','varnish','lacquer',
            // 'spray paint','nail polish','nail polish remover','deodorant',
            // 'room freshener','lighter fluid',

            // 🟠 Chemicals & industrial
            'acetone','methanol','ethanol','isopropyl alcohol','toluene','xylene',
            'formaldehyde','hydrogen peroxide','ammonia','sodium hydroxide','hydrochloric acid',
            'sulphuric acid','nitric acid',

            // 🟡 Cleaning & household
            // 'toilet cleaner','floor cleaner','phenyl','bleach','detergent','dishwashing liquid',
            // 'glass cleaner','drain cleaner','disinfectant','hand sanitizer',

            // 🔵 Aerosols & compressed gas
            'deodorant spray','hair spray','insecticide spray','pesticide spray','air duster',
            'fire extinguisher','gas cartridge','co2 cylinder','oxygen cylinder',

            // ⚫ Adhesives / resins
            'fevicol','epoxy','hardener','super glue','silicone','rubber adhesive','pu foam',

            // 🟤 Oils & lubricants
            'engine oil','gear oil','hydraulic oil','transformer oil','grease','cutting oil',
            'brake fluid','coolant','antifreeze',

            // 🟢 Cosmetics
            'hair dye','hair straightening','skin peel','chemical exfoliant','sunscreen','tattoo ink',
            'permanent makeup',

            // 🔶 Batteries
            'lithium-ion battery','lithium metal battery','power bank','lead-acid battery','battery cell','ups battery','ev battery',

            // 🔺 Lab & medical
            'lab reagent','diagnostic chemical','x-ray chemical','pathology reagent','medical disinfectant','formalin',

            // ❌ Extremely restricted
            'explosive','radioactive','biohazard','toxic gas','human specimen'
        ];
        
        $hardBannedKeywords = [
            'weed','ganja','marijuana','hash','charas','cocaine','heroin',
            'gun','pistol','revolver','rifle','bullet','ammo','grenade',
            'rdx','tnt','dynamite',
            'fakecurrency','counterfeit',
            'ivory','tigerskin',
            'militaryid','governmentseal'
        ];
//        echo '<pre>';print_R($request->all());die;
        if (!empty($request->name)) {
            foreach ($request->name as $index => $productName) {

                // normalize text for banned check
                $clean = strtolower($productName);
                $clean = str_replace(['@', '4', '$'], 'a', $clean);
                $clean = str_replace(['1', '!', '|'], 'i', $clean);
                $clean = preg_replace('/[^a-z]/', '', $clean);

                // Hard banned keywords
                foreach ($hardBannedKeywords as $word) {
                    if (str_contains($clean, $word)) {
                        Mail::raw(
                            "⚠️ Banned keyword '{$word}' detected in Product row ".($index+1)." : {$productName} by : ".Auth::guard('admin')->user()->name, 
                            function ($message) {
                                $message->to('sales@hyloship.com')
//                                        ->bcc(['ritesha412@gmail.com'])
                                        ->subject('Banned Keyword Alert');
                            }
                        );
                        return redirect()->route('admin.order.create')
                            ->with('error', "❌ Product row " . ($index + 1) ." contains restricted product '{$productName}'");
                    }
                }

                // MSDS-required products
                foreach ($msdsProducts as $msdsItem) {
                    if (str_contains(strtolower($productName), $msdsItem)) {
                        $msdsUploaded = $request->msds[$index] ?? null;
                        if (!$msdsUploaded) {
                            Mail::raw(
                                "⚠️ Product '{$productName}' in row ".($index+1)." requires MSDS but none uploaded. Added by: ".Auth::guard('admin')->user()->name,
                                function ($message) {
                                    $message->to('sales@hyloship.com')
//                                            ->bcc(['ritesha412@gmail.com'])
                                            ->subject('MSDS Missing Alert');
                                }
                            );
                            return redirect()->back()->with('error', "❌ Product row " . ($index + 1) ." ('{$productName}') requires MSDS. Please upload.");
                        }
                    }
                }
            }
        }

//        echo '<pre>';print_R($request->name);die;
if ($request->invoice_no) {
            $duplicate = Order::where('invoice_no', $request->invoice_no)->first();
            if ($duplicate) {
                return redirect()->back()->with('error', "❌ Invoice Number '{$request->invoice_no}' is already used in Order #{$duplicate->id}");
            }
        }
        $order = new Order();
        $user_id = Auth::guard('admin')->user()->id;
        $current_company = Auth::guard('admin')->user()->company_id;
        $order->user_id = $user_id;
        $order->order_id = $request->order_id;
        $order->ship_fname = $request->ship_fname ?? null;
        $order->ship_lname = $request->ship_lname ?? null;
        $order->ship_email = $request->ship_email ?? null;
        $order->ship_company = $request->ship_company ?? null;
        $order->ship_phone = $request->ship_phone ?? null;
        $order->ship_address = $request->ship_address ?? null;
        $order->ship_address_2 = $request->ship_address_2 ?? null;
        $order->ship_country = $request->ship_country ?? null;
        $order->ship_pincode = $request->ship_pincode ?? null;
        $order->ship_city = $request->ship_city ?? null;
        $order->ship_state = $request->ship_state ?? null;
        $order->ship_latitude = $request->ship_latitude ?? null;
        $order->ship_longitude = $request->ship_longitude ?? null;
        $order->ship_gstin = $request->ship_gstin ?? null;
        $order->bill_fname = $request->bill_fname ?? null;
        $order->bill_lname = $request->bill_lname ?? null;
        $order->bill_company = $request->bill_company ?? null;
        $order->bill_phone = $request->bill_phone ?? null;
        $order->bill_address = $request->bill_address ?? null;
        $order->bill_address_2 = $request->bill_address_2 ?? null;
        $order->bill_country = $request->bill_country ?? null;
        $order->bill_pincode = $request->bill_pincode ?? null;
        $order->bill_city = $request->bill_city ?? null;
        $order->bill_state = $request->bill_state ?? null;
        $order->bill_latitude = $request->bill_latitude ?? null;
        $order->bill_longitude = $request->bill_longitude ?? null;
        $order->bill_gstin = $request->bill_gstin ?? null;
        $order->e_bill_no = $request->e_bill_no ?? null;
        $order->invoice_no = $request->invoice_no ?? null;
        $order->same_add = $request->same_add;
        $order->discount = $request->order_discount ?? 0;
        $order->shipping_cost = $request->shipping_cost;
        $order->total = $request->total ?? 0;
        $order->custom_total = $request->custom_total;
        $order->payment_mode = $request->payment_mode;
        $order->vendor_order_id = $request->vendor_order_id;
        $order->channel = $request->channel ?? 'Hyloship';
        $order->weight = $request->weight;
        $order->length = $request->length;
        $order->breadth = $request->breadth;
        $order->height = $request->height;
        $order->note = $request->note ?? null;
        $order->company_id = $current_company;
        $order->save();

        $id = $order->id;
        $order->order_id = $id;
        $order->save();
        foreach ($request->name as $key => $name) {

            // Handle MSDS file upload if provided
            $msdsPath = null;
            if ($request->hasFile('msds.' . $key)) {
                $file = $request->file('msds.' . $key);
                $filename = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/msds'), $filename); // directly in public/uploads/msds
                $msdsPath = 'uploads/msds/'.$filename; // path for DB
            }


            OrderDetail::create([
                'user_id' => $user_id,
                'order_id' => $order->id,
                'name' => $name,
                'code' => $request->code[$key] ?? $name,
                'price' => $request->price[$key],
                'discount' => $request->discount[$key] ?? 0,
                'qty' => $request->qty[$key],
                'discount_type' => $request->discount_type[$key] ?? 0,
                'tax_percent' => $request->tax_percent[$key] ?? 0,
                'tax_amount' => $request->tax_amount[$key] ?? 0,
                'total_price' => $request->total_price[$key],
                'msds_file' => $msdsPath, // store path in DB
                'company_id' => $current_company
            ]);
        }

        //create logs
        createlogs('created', 'order', $order->id);
        return redirect()->route('admin.order.index')->with('success', 'Order Created Successfully!');
    }

    public function edit($id)
    {
        $user_id = Auth::guard('admin')->user()->id;
        $current_company = Auth::guard('admin')->user()->company_id;
        // if($user_id == 1){
            $order = Order::with('detail')->where('user_id', $user_id)->where('id',$id)->whereIN('status',array('1','4'))->where('company_id',$current_company)->first();
            $counrtries = Country::where('id','101')->get();
//            $counrtries = array();
        // dd($order);
            if($order){
                return view('admin.order.edit', compact('order','counrtries'));
            }
            else{
               return redirect()->route('admin.order.index')->with('error',"You don't have permission to access this");
            }
        // }
    }

   
    public function update(Request $request,$id)
    {
        $hardBannedKeywords = [
            'weed','ganja','marijuana','hash','charas','cocaine','heroin',
            'gun','pistol','revolver','rifle','bullet','ammo','grenade',
            'rdx','tnt','dynamite',
            'fakecurrency','counterfeit',
            'ivory','tigerskin',
            'militaryid','governmentseal'
        ];

        if (!empty($request->name)) {
            foreach ($request->name as $index => $productName) {

                // Step 1: replace common obfuscation symbols
                $clean = strtolower($productName);
                $clean = str_replace(['@', '4', '$'], 'a', $clean);
                $clean = str_replace(['1', '!', '|'], 'i', $clean);

                // Step 2: check banned keywords with regex to avoid false positives
                foreach ($hardBannedKeywords as $word) {
                    $wordRegex = implode('[\s\W_]*', str_split($word));
                    if (preg_match('/\b' . $wordRegex . '\b/i', $clean)) {
                        Mail::raw(
                            "⚠️ Banned keyword '{$word}' detected in Product row ".($index+1)." : {$productName} by : ".Auth::guard('admin')->user()->name, 
                            function ($message) {
                                $message->to('sales@hyloship.com') // admin email
//                                        ->bcc(['ritesha412@gmail.com'])
                                        ->subject('Banned Keyword Alert.');
                            }
                        );
                        return back()->with('error',"❌ Product row " . ($index + 1) ." contains restricted product '{$productName}' (Matched: '{$word}')");
                    }
                }
            }
        }

        $order = Order::findOrFail($id);
        $user_id = Auth::guard('admin')->user()->id;
        $current_company = Auth::guard('admin')->user()->company_id;
        // Store the original values for comparison
        $originalOrder = $order->getOriginal();
        $originalOrderTrimmed = array_map('trim', $originalOrder);
        $order->user_id = $user_id;
        $order->order_id = $request->order_id;
        $order->ship_fname = $request->ship_fname ?? null;
        $order->ship_lname = $request->ship_lname ?? null;
        $order->ship_email = $request->ship_email ?? null;
        $order->ship_company = $request->ship_company ?? null;
        $order->ship_phone = $request->ship_phone ?? null;
        $order->ship_address = $request->ship_address ?? null;
        $order->ship_address_2 = $request->ship_address_2 ?? null;
        $order->ship_country = $request->ship_country ?? null;
        $order->ship_pincode = $request->ship_pincode ?? null;
        $order->ship_city = $request->ship_city ?? null;
        $order->ship_state = $request->ship_state ?? null;
        $order->ship_latitude = $request->ship_latitude ?? null;
        $order->ship_longitude = $request->ship_longitude ?? null;
        $order->ship_gstin = $request->ship_gstin ?? null;
        $order->bill_fname = $request->bill_fname ?? null;
         $order->invoice_no = $request->invoice_no ?? null;
        $order->bill_lname = $request->bill_lname ?? null;
        $order->bill_company = $request->bill_company ?? null;
        $order->bill_phone = $request->bill_phone ?? null;
        $order->bill_address = $request->bill_address ?? null;
        $order->bill_address_2 = $request->bill_address_2 ?? null;
        $order->bill_country = $request->bill_country ?? null;
        $order->bill_pincode = $request->bill_pincode ?? null;
        $order->bill_city = $request->bill_city ?? null;
        $order->bill_state = $request->bill_state ?? null;
        $order->bill_latitude = $request->bill_latitude ?? null;
        $order->bill_longitude = $request->bill_longitude ?? null;
        $order->bill_gstin = $request->bill_gstin ?? null;
        $order->e_bill_no = $request->e_bill_no ?? null;
        $order->same_add = $request->same_add;
        $order->discount = $request->order_discount;
        $order->shipping_cost = $request->shipping_cost;
        $order->total = $request->total ?? 0;
        $order->custom_total = $request->custom_total;
        $order->payment_mode = $request->payment_mode;
        $order->vendor_order_id = $request->vendor_order_id;
        $order->channel = $request->channel ?? 'Hyloship';
        $order->weight = $request->weight;
        $order->length = $request->length;
        $order->breadth = $request->breadth;
        $order->height = $request->height;
        $order->note = $request->note ?? null;
        $order->company_id = $current_company;
        $order->save();

        OrderDetail::where('order_id',$id)->delete();
        if (!empty(request()->name)) {
            foreach(request()->name as $key => $row)
            {
                $detail = new OrderDetail();
                $user_id = Auth::guard('admin')->user()->id;
                $detail->user_id = $user_id;
                $detail->order_id = $id;
                $detail->name = $request->name[$key];
                $detail->code = $request->code[$key];
                $detail->price = $request->price[$key];
                $detail->discount = $request->discount[$key];
                $detail->qty = $request->qty[$key];
                $detail->discount_type = $request->discount_type[$key];
                $detail->tax_percent = $request->tax_percent[$key] ?? 0;
                $detail->tax_amount = $request->tax_amount[$key] ?? 0;
                $detail->total_price = $request->total_price[$key];
                $detail->company_id = $current_company;
                $detail->save();
            }
        }
        $currentOrderTrimmed = array_map('trim', $order->getAttributes());

        $changedFields = array_diff_assoc($currentOrderTrimmed,$originalOrderTrimmed);
        unset($changedFields['channel'],$changedFields['user_id'],$changedFields['payment_mode'], $changedFields['updated_at'], $changedFields['extra_weight_status'],$changedFields['status']);

        $oldValues = [];
        foreach ($changedFields as $key => $newValue) {
            $oldValues[$key] = $originalOrderTrimmed[$key] ?? 'N/A';
        }

        createlogs('updated','order', $id,$changedFields,$oldValues);
        
        return redirect()->route('admin.order.index')->with('success', 'Order Updated Successfully!');
    }

    public function tags($id){
        $order = Order::find($id);
        $order->tags = json_encode(request()->tags);
        $order->save();
        return Redirect()->back()->with('success', 'Tags Added successfully!');
    }


    public function detail($id)
    {
        $warehouse = WareHouse::get();
        $order = Order::with('detail','billCountry','shipCountry')->findOrFail($id);
        $c_name='';
        if($order['ship_courier_id']){
            $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
            $c_name =@$couriers[$order['ship_courier_id']]['name'];
        }
        if($order->tags != null){
            $tags = implode(',',json_decode($order->tags));
        } else {
            $tags = '';
        }
        return view('admin.order.detail', compact('warehouse','order','tags','c_name'));
    }

    public function invoice($id)
    {
        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        $order_detail = Order::with('customers','product')->findOrFail($id);
        $product_list = DB::table('order_details')->where('order_id',$id)->get();
        return view('admin.order.invoice', compact('order_detail','product_list','g_setting'));
    }

    public function destroy($id)
    {
        Order::where('id', $id)->delete();
        OrderDetail::where('order_id', $id)->delete();
        createlogs('deleted', 'order', $id);
        return Redirect()->back()->with('success', 'Order is deleted successfully!');
    }

   
    public function action()
    {
        $u_data = Auth::guard('admin')->user();
        $user_id = $u_data->id;
        
        if(request()->status == 'downloadrem'){
            $userarray = array();
            foreach(request()->id as $id){
                $remdata = explode('_',$id);
                $userarray[] = $remdata[0];
            }
//            echo request()->status.'<br>'.request()->start_date.'<br>'.request()->end_date;
            return redirect()->route('codrem', [
                'user_id[]' => $userarray,
                'start_date' => request()->start_date,
                'end_date' => request()->end_date,
            ]);
            
        }
        if(request()->status == 'remitance'){
            
            foreach(request()->id as $id){
                $remdata = explode('_',$id);
                $total =$shipping=0;
                // echo '<pre>';print_R($remdata);die;
                if(count($remdata) ==3){
                    $order_q = Order::where('status',3)->where('payment_mode',6)->where('user_id',$remdata[0])->where('remittance_id',0);
                    $order_q->where('delivered_date','>=', $remdata[2].' 00:00:01');
                    $order_q->where('delivered_date','<=', $remdata[1].' 23:59:59');
                    $order_q = $order_q->join('admins', 'orders.user_id', '=', 'admins.id');
                    $orders = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id')->get();
                    if(count($orders) >0){
                        $remittance = new Remittance();
                        $remittance->order_id = null; //for multiple orderid use ', ' in between order_Id
                        $remittance->user_id = $remdata[0];
                        // $remittance->amount = $rem_amount;
                        $remittance->start_date = $remdata[2];
                        $remittance->end_date = $remdata[1];
                        $remittance->updated_at = now();
                        $remittance->created_at = now();
                        $remittance->save();
                        
                        $remittance_id = $remittance->id;
                        $orderarray = array();
                        foreach($orders as $order){
                            $total +=$order->total;
                            // $shipping += $order->freight +$order->gst_freight;
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
            
            return Redirect()->back()->with('success', 'Remittance created successfully');
        }
        if(request()->status == 'delete')
        {   
            $current_company_id = $u_data->company_id;
            foreach(request()->id as $id)
            {
                $order = DB::table('orders')->where('user_id', $user_id)->where('id', $id)->where('status','1')->where('company_id',$current_company_id)->first();
                $order_details=DB::table('order_details')->where('user_id', $user_id)->where('order_id', $id)->where('company_id',$current_company_id)->first();
                
              	if(isset($order->id)){
                    $orderArray = (array)$order;
                    DB::table('archive_orders')->insert($orderArray);
                    $order_details_array=(array)$order_details;
                    DB::table('archive_order_details')->insert($order_details_array);
                    
                $orders = DB::table('orders')->where('user_id', $user_id)->where('company_id',$current_company_id)->where('id', $id)->delete();
                $orderdetail = DB::table('order_details')->where('user_id', $user_id)->where('company_id',$current_company_id)->where('order_id', $id)->delete();
                createlogs('deleted', 'order', $id);
                // return redirect()->route('admin.order.index')->with('success', 'Orders Deleted Successfully!');
                }
                else{
                    return back()->with('error',"Only new OR self user order can be deleted");
                }
            }
            return redirect()->route('admin.order.index')->with('success', 'Orders Deleted Successfully!');
        }
        if(request()->status == 'awb')
        {   
            $manifest_order_array = array();
            if(isset(request()->multiplewarehouse_id)){
                
                $ware = request()->multiplewarehouse_id;
                $return_warehouse_id = request()->multiplereturn_warehouse_id;
//                echo $ware.' '.$return_warehouse_id;die;
                if($ware != 0){
                    $error ='';
                    request()->id =array_unique(request()->id);
                    
                    foreach(request()->id as $id)
                    {
                        DB::beginTransaction();
                        try {
                            $getuser = DB::table('orders')
                                ->select('user_id as user')
                                ->where('id','=',$id)
                                ->first();
                            $required_corr_data=array();
                            $cour_id ='';
                            $user_id = $orde_user_id  = $getuser->user;
                            $parent_userid = Admin::find($orde_user_id)->parent_id;
                            $current_company_id = Admin::find($orde_user_id)->company_id;
                            $company_id = $current_company_id; // Fix undefined variable company_id in loop
                            $courier_priority = Admin::find($user_id)->courier_priority;
                            $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
                            if($courier_priority == null || $courier_priority ==''){
                                DB::rollBack();
                                return redirect()->route('admin.integration.courier_priority')->with('error', "Please add courier priority"); 
                            }else{
                                $c_priority = explode(',',$courier_priority);
                            }
                        $pickup = Warehouse::whereId($ware)->pluck('pincode')->first();
                        $order = Order::find($id);
                        $pincodepicup_sevice = Pincode::firstOrCreate(['pincode' => $pickup], ['company_id' => $current_company_id ?? 1]);
                        $pincodedrop_sevice = Pincode::firstOrCreate(['pincode' => $order->ship_pincode], ['company_id' => $current_company_id ?? 1]);
                        if(!$pincodepicup_sevice || !$pincodedrop_sevice){
                            $error .= $id .': Pincode is not Servicable! - Hyloship'. '\n';
                        }else{
                        
                        if($order->tracking_info ==''){
                            $availableCouriers = [];  
                            $courierss = DB::table('couriers')
                            ->where('status', '=', '1')  
                            ->where('company_id', '=', $current_company_id)
                            ->get(['courier_id', 'mode']); 

                            foreach ($courierss as $courier) {
                            $availableCouriers[] = [
                            'courier_id' => $courier->courier_id,
                            'transport' => $courier->mode,  
                            ];
                            }  
                            if($order->ship_pincode !='' && $order->ship_fname !='' && $order->ship_fname !=0){
                                if($order->reverse_order =='0'){

                                  $old_status = strip_tags($order->status);
                                  $drop = $order->ship_pincode;
                                  $drop = ltrim(rtrim($drop));
                                  $pickup = ltrim(rtrim($pickup));
                                  $dom = new DOMDocument();
                                  $dom->loadHTML($order->payment_mode);
                                  $spans = $dom->getElementsByTagName('span');
                                  $text = '';
                                  foreach ($spans as $span) {
                                      $text = $span->textContent;
                                  }
                                  $payment = $text == 'C.O.D' ? 'cod' : 'prepaid';
                                  $typecourier = $pincodes =array() ;
                                  for($i=0;$i<count($c_priority);$i++){
                                      $c_priority_arry = explode('_',$c_priority[$i]);
                                      
                                     if(count($c_priority_arry)==2){
                                         $sercourier = array();

                                          if($c_priority_arry[0] == '1'){
                                              if($payment == 'cod'){
                                                  $p_t = 'cod';
                                              }else{
                                                  $p_t = 'ppd';
                                              }

                                              if($c_priority_arry[1] =='A'){
                                                  $m_t = 'air';
                                              }else{
                                                  $m_t = 'surface';
                                              }
                                            $chkserdrop = Servicable_pincode::
                                                    where('pincode',$drop)
                                                    ->where(function($query) {
                                                        $query->where('type', 'drop')
                                                              ->orWhere('type', 'both');
                                                    })
                                                    ->where(function($query)use ($m_t) {
                                                        $query->where('mode', $m_t)
                                                              ->orWhere('mode', 'both');
                                                    })
                                                    ->where(function($query)use ($p_t) {
                                                        $query->where('payment', $p_t)
                                                              ->orWhere('payment', 'both');
                                                    })
                                                    ->where('courier_id','1')->where('active','1')->where('company_id',$current_company_id)
                                                    ->first(); 
                                            $chkserpickup = Servicable_pincode::
                                                    where('pincode',$pickup)
                                                    ->where(function($query) {
                                                        $query->where('type', 'pickup')
                                                              ->orWhere('type', 'both');
                                                    })
                                                    ->where(function($query)use ($m_t) {
                                                        $query->where('mode', $m_t)
                                                              ->orWhere('mode', 'both');
                                                    })
                                                    ->where(function($query)use ($p_t) {
                                                        $query->where('payment', $p_t)
                                                              ->orWhere('payment', 'both');
                                                    })
                                                    ->where('courier_id','1')->where('active','1')->where('company_id',$current_company_id)
                                                    ->first(); 
                                                     if (!Servicable_pincode::where('courier_id','1')->where('company_id',$current_company_id)->exists()) {
                                                         $chkserpickup = true;
                                                         $chkserdrop = true;
                                                     }
                                            if($chkserpickup && $chkserdrop){
                                                $found = false;
                                                foreach ($availableCouriers as $courier) {
                                                    if ($courier['courier_id'] == 1 && $courier['transport'] == $m_t) {
                                                        $found = true;
                                                        break;
                                                    }
                                                }
                                                if ($found) {
                                                    $cour_id = 1;
                                                    $transport_used = $c_priority_arry[1];
                                                    break;
                                                }
                                            }        
                                          }
                                          if($c_priority_arry[0] == '2'){
                                                if($payment == 'cod'){
                                                    $p_t = 'cod';
                                                }else{
                                                    $p_t = 'ppd';
                                                }

                                                if($c_priority_arry[1] =='A'){
                                                    $m_t = 'air';
                                                }else{
                                                    $m_t = 'surface';
                                                }
                                                $chkserdrop = Servicable_pincode::
                                                      where('pincode',$drop)
                                                      ->where(function($query) {
                                                          $query->where('type', 'drop')
                                                                ->orWhere('type', 'both');
                                                      })
                                                      ->where(function($query)use ($m_t) {
                                                          $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                      })
                                                      ->where(function($query)use ($p_t) {
                                                          $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                      })
                                                      ->where('courier_id','2')->where('active','1')->where('company_id',$current_company_id)
                                                      ->first(); 
                                                $chkserpickup = Servicable_pincode::
                                                      where('pincode',$pickup)
                                                      ->where(function($query) {
                                                          $query->where('type', 'pickup')
                                                                ->orWhere('type', 'both');
                                                      })
                                                      ->where(function($query)use ($m_t) {
                                                          $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                      })
                                                      ->where(function($query)use ($p_t) {
                                                          $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                      })
                                                      ->where('courier_id','2')->where('active','1')->where('company_id',$current_company_id)
                                                      ->first(); 
                                                      if (!Servicable_pincode::where('courier_id','2')->where('company_id',$current_company_id)->exists()) {
                                                          $chkserpickup = true;
                                                          $chkserdrop = true;
                                                      }
                                                if($chkserpickup && $chkserdrop){
                                                    $found = false;
                                                    foreach ($availableCouriers as $courier) {
                                                        if ($courier['courier_id'] == 2 && $courier['transport'] == $m_t) {
                                                            $found = true;
                                                            break;  
                                                        }
                                                    }
                                                    if ($found) {
                                                        $cour_id = 2;
                                                        $transport_used = $c_priority_arry[1];
                                                        break;
                                                    }
                                                }
                                          }
                                          if($c_priority_arry[0] == '3'){
                                              if($payment == 'cod'){
                                                    $p_t = 'cod';
                                                }else{
                                                    $p_t = 'ppd';
                                                }

                                                if($c_priority_arry[1] =='A'){
                                                    $m_t = 'air';
                                                }else{
                                                    $m_t = 'surface';
                                                }
                                                $chkserdrop = Servicable_pincode::
                                                      where('pincode',$drop)
                                                      ->where(function($query) {
                                                          $query->where('type', 'drop')
                                                                ->orWhere('type', 'both');
                                                      })
                                                      ->where(function($query)use ($m_t) {
                                                          $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                      })
                                                      ->where(function($query)use ($p_t) {
                                                          $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                      })
                                                      ->where('courier_id','3')->where('active','1')->where('company_id',$current_company_id)
                                                      ->first(); 
                                                $chkserpickup = Servicable_pincode::
                                                      where('pincode',$pickup)
                                                      ->where(function($query) {
                                                          $query->where('type', 'pickup')
                                                                ->orWhere('type', 'both');
                                                      })
                                                      ->where(function($query)use ($m_t) {
                                                          $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                      })
                                                      ->where(function($query)use ($p_t) {
                                                          $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                      })
                                                      ->where('courier_id','3')->where('active','1')->where('company_id',$current_company_id)
                                                      ->first(); 
                                                if($chkserpickup && $chkserdrop){
                                                    $found = false;
                                                    foreach ($availableCouriers as $courier) {
                                                        if ($courier['courier_id'] == 3 && $courier['transport'] == $m_t) {
                                                            $found = true;
                                                            break;  
                                                        }
                                                    }
                                                    if ($found) {
                                                        $cour_id = 3;
                                                        $transport_used = $c_priority_arry[1];
                                                        break;
                                                    }
                                                }
                                         }
                                          if($c_priority_arry[0] == '4'){
                                              if($payment == 'cod'){
                                                    $p_t = 'cod';
                                                }else{
                                                    $p_t = 'ppd';
                                                }

                                                if($c_priority_arry[1] =='A'){
                                                    $m_t = 'air';
                                                }else{
                                                    $m_t = 'surface';
                                                }
                                                $chkserdrop = Servicable_pincode::
                                                      where('pincode',$drop)
                                                      ->where(function($query) {
                                                          $query->where('type', 'drop')
                                                                ->orWhere('type', 'both');
                                                      })
                                                      ->where(function($query)use ($m_t) {
                                                          $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                      })
                                                      ->where(function($query)use ($p_t) {
                                                          $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                      })
                                                      ->where('courier_id','4')->where('active','1')->where('company_id',$current_company_id)
                                                      ->first(); 
                                                $chkserpickup = Servicable_pincode::
                                                      where('pincode',$pickup)
                                                      ->where(function($query) {
                                                          $query->where('type', 'pickup')
                                                                ->orWhere('type', 'both');
                                                      })
                                                      ->where(function($query)use ($m_t) {
                                                          $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                      })
                                                      ->where(function($query)use ($p_t) {
                                                          $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                      })
                                                      ->where('courier_id','4')->where('active','1')->where('company_id',$current_company_id)
                                                      ->first(); 
                                                if($chkserpickup && $chkserdrop){
                                                    $found = false;
                                                    foreach ($availableCouriers as $courier) {
                                                        if ($courier['courier_id'] == 4 && $courier['transport'] == $m_t) {
                                                            $found = true;
                                                            break;  
                                                        }
                                                    }
                                                    if ($found) {
                                                        $cour_id = 4;
                                                        $transport_used = $c_priority_arry[1];
                                                        break;
                                                    }
                                                }
                                          }
                                          if($c_priority_arry[0] == '5'){
                                              if($payment == 'cod'){
                                                    $p_t = 'cod';
                                                }else{
                                                    $p_t = 'ppd';
                                                }

                                                if($c_priority_arry[1] =='A'){
                                                    $m_t = 'air';
                                                }else{
                                                    $m_t = 'surface';
                                                }
                                                $chkserdrop = Servicable_pincode::
                                                      where('pincode',$drop)
                                                      ->where(function($query) {
                                                          $query->where('type', 'drop')
                                                                ->orWhere('type', 'both');
                                                      })
                                                      ->where(function($query)use ($m_t) {
                                                          $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                      })
                                                      ->where(function($query)use ($p_t) {
                                                          $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                      })
                                                      ->where('courier_id','5')->where('active','1')->where('company_id',$current_company_id)
                                                      ->first(); 
                                                $chkserpickup = Servicable_pincode::
                                                      where('pincode',$pickup)
                                                      ->where(function($query) {
                                                          $query->where('type', 'pickup')
                                                                ->orWhere('type', 'both');
                                                      })
                                                      ->where(function($query)use ($m_t) {
                                                          $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                      })
                                                      ->where(function($query)use ($p_t) {
                                                          $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                      })
                                                      ->where('courier_id','5')->where('active','1')->where('company_id',$current_company_id)
                                                      ->first(); 
                                                if($chkserpickup && $chkserdrop){
                                                    $found = false;
                                                    foreach ($availableCouriers as $courier) {
                                                        if ($courier['courier_id'] == 5 && $courier['transport'] == $m_t) {
                                                            $found = true;
                                                            break;  
                                                        }
                                                    }
                                                    if ($found) {
                                                        $cour_id = 5;
                                                        $transport_used = $c_priority_arry[1];
                                                        break;
                                                    }
                                                }
                                          }
                                           if ($c_priority_arry[0] == '4') {
                                                    if ($payment == 'cod') {
                                                        $p_t = 'cod';
                                                    } else {
                                                        $p_t = 'ppd';
                                                    }

                                                    if ($c_priority_arry[1] == 'A') {
                                                        $m_t = 'air';
                                                    } else {
                                                        $m_t = 'surface';
                                                    }
                                                    $chkserdrop = Servicable_pincode::
                                                        where('pincode', $drop)
                                                        ->where(function ($query) {
                                                            $query->where('type', 'drop')
                                                                ->orWhere('type', 'both');
                                                        })
                                                        ->where(function ($query) use ($m_t) {
                                                            $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                        })
                                                        ->where(function ($query) use ($p_t) {
                                                            $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                        })
                                                        ->where('courier_id', '4')->where('active', '1')->where('company_id', $current_company_id)
                                                        ->first();
                                                    $chkserpickup = Servicable_pincode::
                                                        where('pincode', $pickup)
                                                        ->where(function ($query) {
                                                            $query->where('type', 'pickup')
                                                                ->orWhere('type', 'both');
                                                        })
                                                        ->where(function ($query) use ($m_t) {
                                                            $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                        })
                                                        ->where(function ($query) use ($p_t) {
                                                            $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                        })
                                                        ->where('courier_id', '4')->where('active', '1')->where('company_id', $current_company_id)
                                                        ->first();
                                                    if ($chkserpickup && $chkserdrop) {
                                                        $found = false;
                                                        foreach ($availableCouriers as $courier) {
                                                            if ($courier['courier_id'] == 4 && $courier['transport'] == $m_t) {
                                                                $found = true;
                                                                break;
                                                            }
                                                        }
                                                        if ($found) {
                                                            $cour_id = 4;
                                                            $transport_used = $c_priority_arry[1];
                                                            break;
                                                        }
                                                    }
                                                }
                                          if($c_priority_arry[0] == '6'){
                                              if($payment == 'cod'){
                                                    $p_t = 'cod';
                                                }else{
                                                    $p_t = 'ppd';
                                                }

                                                if($c_priority_arry[1] =='A'){
                                                    $m_t = 'air';
                                                }else{
                                                    $m_t = 'surface';
                                                }
                                                $chkserdrop = Servicable_pincode::
                                                      where('pincode',$drop)
                                                      ->where(function($query) {
                                                          $query->where('type', 'drop')
                                                                ->orWhere('type', 'both');
                                                      })
                                                      ->where(function($query)use ($m_t) {
                                                          $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                      })
                                                      ->where(function($query)use ($p_t) {
                                                          $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                      })
                                                      ->where('courier_id','6')->where('active','1')->where('company_id',$current_company_id)
                                                      ->first(); 
                                                $chkserpickup = Servicable_pincode::
                                                      where('pincode',$pickup)
                                                      ->where(function($query) {
                                                          $query->where('type', 'pickup')
                                                                ->orWhere('type', 'both');
                                                      })
                                                      ->where(function($query)use ($m_t) {
                                                          $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                      })
                                                      ->where(function($query)use ($p_t) {
                                                          $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                      })
                                                      ->where('courier_id','6')->where('active','1')->where('company_id',$current_company_id)
                                                      ->first(); 
                                                if($chkserpickup && $chkserdrop){
                                                    $found = false;
                                                    foreach ($availableCouriers as $courier) {
                                                        if ($courier['courier_id'] == 6 && $courier['transport'] == $m_t) {
                                                            $found = true;
                                                            break;  
                                                        }
                                                    }
                                                    if ($found) {
                                                        $cour_id = 6;
                                                        $transport_used = $c_priority_arry[1];
                                                        break;
                                                    }
                                                }
                                          }
                                          if($c_priority_arry[0] == '7'){
                                                if($payment == 'cod'){
                                                    $p_t = 'cod';
                                                }else{
                                                    $p_t = 'ppd';
                                                }

                                                if($c_priority_arry[1] =='A'){
                                                    $m_t = 'air';
                                                }else{
                                                    $m_t = 'surface';
                                                }
                                                $chkserdrop = Servicable_pincode::
                                                      where('pincode',$drop)
                                                      ->where(function($query) {
                                                          $query->where('type', 'drop')
                                                                ->orWhere('type', 'both');
                                                      })
                                                      ->where(function($query)use ($m_t) {
                                                          $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                      })
                                                      ->where(function($query)use ($p_t) {
                                                          $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                      })
                                                      ->where('courier_id','7')->where('active','1')->where('company_id',$current_company_id)
                                                      ->first(); 
                                                $chkserpickup = Servicable_pincode::
                                                      where('pincode',$pickup)
                                                      ->where(function($query) {
                                                          $query->where('type', 'pickup')
                                                                ->orWhere('type', 'both');
                                                      })
                                                      ->where(function($query)use ($m_t) {
                                                          $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                      })
                                                      ->where(function($query)use ($p_t) {
                                                          $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                      })
                                                      ->where('courier_id','7')->where('active','1')->where('company_id',$current_company_id)
                                                      ->first(); 
                                                if($chkserpickup && $chkserdrop){
                                                    $found = false;
                                                    foreach ($availableCouriers as $courier) {
                                                        if ($courier['courier_id'] == 7 && $courier['transport'] == $m_t) {
                                                            $found = true;
                                                            break;  
                                                        }
                                                    }
                                                    if ($found) {
                                                        $cour_id = 7;
                                                        $transport_used = $c_priority_arry[1];
                                                        break;
                                                    }
                                                }


                                          }
                                          if($c_priority_arry[0] == '8'){
                                              if($payment == 'cod'){
                                                    $p_t = 'cod';
                                                }else{
                                                    $p_t = 'ppd';
                                                }

                                                if($c_priority_arry[1] =='A'){
                                                    $m_t = 'air';
                                                }else{
                                                    $m_t = 'surface';
                                                }
                                                $chkserdrop = Servicable_pincode::
                                                      where('pincode',$drop)
                                                      ->where(function($query) {
                                                          $query->where('type', 'drop')
                                                                ->orWhere('type', 'both');
                                                      })
                                                      ->where(function($query)use ($m_t) {
                                                          $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                      })
                                                      ->where(function($query)use ($p_t) {
                                                          $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                      })
                                                      ->where('courier_id','8')->where('active','1')->where('company_id',$current_company_id)
                                                      ->first(); 
                                                $chkserpickup = Servicable_pincode::
                                                      where('pincode',$pickup)
                                                      ->where(function($query) {
                                                          $query->where('type', 'pickup')
                                                                ->orWhere('type', 'both');
                                                      })
                                                      ->where(function($query)use ($m_t) {
                                                          $query->where('mode', $m_t)
                                                                ->orWhere('mode', 'both');
                                                      })
                                                      ->where(function($query)use ($p_t) {
                                                          $query->where('payment', $p_t)
                                                                ->orWhere('payment', 'both');
                                                      })
                                                      ->where('courier_id','8')->where('active','1')->where('company_id',$current_company_id)
                                                      ->first(); 
                                                if($chkserpickup && $chkserdrop){
                                                    $found = false;
                                                    foreach ($availableCouriers as $courier) {
                                                        if ($courier['courier_id'] == 8 && $courier['transport'] == $m_t) {
                                                            $found = true;
                                                            break;  
                                                        }
                                                    }
                                                    if ($found) {
                                                        $found = false;
                                                        foreach ($availableCouriers as $courier) {
                                                            if ($courier['courier_id'] == 9 && $courier['transport'] == $c_priority_arry[1]) {
                                                                $found = true;
                                                                break;  
                                                            }
                                                        }
                                                        if ($found) {
                                                            $cour_id = 9;
                                                            $transport_used = $c_priority_arry[1];
                                                            break;
                                                        }
                                                    }
                                                }

                                          }
                                          if($c_priority_arry[0] == '9'){

                                                $cour_id =9;$transport_used =$c_priority_arry[1];
                                                break;
                                          }
                                      }else{
                                          $error .= $id .': Courier Priority \n';
                                      }
                                  }
                                  if($cour_id !=''){
                                      $zone_id = Order::getzone($pickup,$drop);
                                      $pincodes[] = array(
                                          "courier_id" => $cour_id,  
                                          "zone" => $zone_id,
                                      );
                                      $get = array();
                                      $weight_array = ['0.5', '1', '1.5', '2', '3','3.5', '5','10','20','30','50'];
                                      foreach($pincodes as $row){
                                        if($row['zone'] !='0'){
                                          $zone = zone($row['zone']);
                                          $c_id = $row['courier_id'];
                                          // $count = intVal(($order->weight/1000) / 0.5) - 1; //to convert weight into kg
                                          foreach(['Air','Surface'] as $transport){
                                              foreach($weight_array as $c_weigt){
                                                  $totalparent = $codparent = $gstparent = $reverse_chregparent = $gst_codparent = $gst_freightparent = $freightparent = $rateparent = $rateaddparent = 0;
                                                  $rate = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $user_id,'weight' =>$c_weigt,'additional' =>0])->first();
                                                  $rateparent = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $parent_userid,'weight' =>$c_weigt,'additional' =>0])->first();
                                                  if($c_weigt=='0.5'){
                                                    $wadd = '0.5';
                                                    }else{
                                                        $wadd = '1';
                                                    }
                                                    $rateadd = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $user_id,'weight' =>$wadd,'additional' =>1])->first();
                                                    $rateaddparent = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $parent_userid,'weight' =>$wadd,'additional' =>1])->first();
                                                    // $add = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport, 'weight' => 'add','status' => 1,'user_id' => $user_id])->first();
                                                    $vol_weight = ($order['length']*$order['breadth']*$order['height'])/vol_weigh($c_id);
                                                    $weight_to_be_taken = $vol_weight > ($order['weight']/1000) ? $vol_weight : ($order['weight']/1000);
                                                    if($rate == null){
                                                        continue;
                                                    }
                                                    $percent = (($order->total * $rate->cod) / 100);
                                                    $cod = $payment == 'cod' ? $percent > $rate->cod_charges ? $percent : $rate->cod_charges : 0;
                                                    $remainging_weight = $weight_to_be_taken - $c_weigt;
                                                    $freight = $rate->$zone;

                                                    if($rateparent !=''){
                                                        $percentparent = (($order->total * $rateparent->cod) / 100); //4339
                                                        $codparent = $payment == 'cod' ? $percentparent > $rateparent->cod_charges ? $percentparent : $rateparent->cod_charges : 0; //0
                                                        $freightparent = $rateparent->$zone;
                                                    }

                                                    if($remainging_weight<0){
                                                        $remainging_weight =0;
                                                    }
                                                    $count =$remainging_charge=$remainging_chargeparent=0;
                                                    if($rateadd == null){
                                                        $add_rate =0;
                                                    }else{
                                                        $count = ceil($remainging_weight/$rateadd->weight);
                                                        $remainging_charge = $count * $rateadd->$zone;
                                                        $add_rate =$rateadd->$zone;
                                                    }
                                                    if($rateaddparent == null){
                                                        $add_rateparent =0;
                                                    }else{
                                                        $remainging_chargeparent = $count * $rateaddparent->$zone;
                                                        $add_rateparent = $rateaddparent->$zone;
                                                    }


                                                    $reverse_chreg =$reverse_chregparent =0;
                                                    
                                                    $freight = $freight + $remainging_charge;
                                                    $gst = (($freight + $cod) * 18) / 100;
                                                    $gst_cod = (($cod) * 18) / 100;
                                                    $gst_freight = (($freight) * 18) / 100;
                                                    if($payment == 'cod'){
                                                        $total = $gst + $freight + $cod;
                                                    } else {
                                                        $total = $gst + $freight;
                                                    }

                                                    $freightparent = $freightparent + $remainging_chargeparent;
                                                    $gstparent = (($freightparent + $codparent) * 18) / 100;
                                                    $gst_codparent = (($codparent) * 18) / 100;
                                                    $gst_freightparent = (($freightparent) * 18) / 100;
                                                    if($payment == 'cod'){
                                                        $totalparent = $gstparent + $freightparent + $codparent;
                                                    } else {
                                                        $totalparent = $gstparent + $freightparent;
                                                    }
                                                    // $total = ($total * 118)/100; // 18% gst
                                                    $get[] = [
                                                        'courier_id' => $rate->courier_id,
                                                        'name' => $couriers[$rate->courier_id]['name'],
                                                        'img' => asset('public/courier').'/'.$couriers[$rate->courier_id]['image'],
                                                        'mode' => (strtolower($transport) === 'air' || strtolower($transport) === 'express') ? 'fa-plane' : (strtolower($transport) === 'sdd' ? 'fa-motorcycle' : (strtolower($transport) === 'ndd' ? 'fa-bicycle' : (strtolower($transport) === 'quick' ? 'fa-bolt' : 'fa-truck'))),
                                                        'weight_used' => $c_weigt,
                                                        'weight' => round($weight_to_be_taken,2).' kg',
                                                        'zone' => $zone,

                                                        'price' => 'Rs.'.number_format($total,2),
                                                        'cod' => round($cod,2),
                                                        'reverse_charge' => round($reverse_chreg,2),
                                                        'gst' => round($gst,2),
                                                        'gst_cod' => round($gst_cod,2),
                                                        'gst_freight' => round($gst_freight,2),
                                                        'freight' => round($freight,2),
                                                        'rate' =>round($rate->$zone,2),
                                                        'rateadd' =>round($add_rate,2),

                                                        'priceparent' => 'Rs.'.number_format($totalparent,2),
                                                        'codparent' => round($codparent,2),
                                                        'reverse_chargeparent' => round($reverse_chregparent,2),
                                                        'gstparent' => round($gstparent,2),
                                                        'gst_codparent' => round($gst_codparent,2),
                                                        'gst_freightparent' => round($gst_freightparent,2),
                                                        'freightparent' => round($freightparent,2),
                                                        'rateparent' =>round(@$rateparent->$zone,2),
                                                        'rateaddparent' =>round($add_rateparent,2),
                                                                              
                                                    ];

                                              }
                                          }
                                        }  
                                      }
                                      
                                      if(count($get) == 0){
                                        $error .= $id .': There is some issue in ratecard OR Pincode not servicable \n';
                                      }else{
                                          usort($get, function($a, $b) {
                                              $priceA = (float) str_replace('Rs.', '', $a['price']);
                                              $priceB = (float) str_replace('Rs.', '', $b['price']);
                                              if ($priceA == $priceB) { return 0; }
                                              return ($priceA < $priceB) ? -1 : 1;
                                          });
                                          $price =0;
                                          if($transport_used =='S'){
                                              $mode_required = 'fa-truck';
                                          }else{
                                              $mode_required = 'fa-plane';
                                          }
                                          foreach($get as $c_array){

                                              if($c_array['mode'] == $mode_required && $zone == $c_array['zone'] && $cour_id == $c_array['courier_id']){ 
                                                  $price =  $c_array['price'];
                                                  $required_corr_data =$c_array;
                                                  break;
                                              }
                                          }
                                          $number = str_replace('Rs.', '', $required_corr_data['price']);
                                          $cleanedString = str_replace(',', '', $number);
                                          $ship_courier_cost = (float) $cleanedString;

                                          $numberparent = str_replace('Rs.', '', $required_corr_data['priceparent']);
                                          $cleanedStringparent = str_replace(',', '', $numberparent);
                                          $ship_courier_costparent = (float) $cleanedStringparent;
                                          if((Admin::find($orde_user_id)->wallet_blc + Admin::find($orde_user_id)->limit_loan) <= $ship_courier_cost){
                                            $error .= $id .': Minimum money required '.$ship_courier_cost.' \n';
                                          }else{
                                              $order_with_detail_s = Order::with('detail')->where('id',$id)->first();
                                              $warehouse = Warehouse::find($ware);
                                              $warehousereturn = Warehouse::find($return_warehouse_id);
                                              $awb_no ='';
                                              $shipment_id = null;
                                              
                                              
                                              if($required_corr_data['courier_id'] =='1'){
                                                  $pmode ='';
                                                  $coll_value =0;
                                                  if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                      $pmode = 'cod';
                                                      $coll_value = $order_with_detail_s['total'];
                                                  }elseif(strip_tags($order_with_detail_s['payment_mode']) =='Pre-Paid'){
                                                      $pmode = 'ppd';
                                                  }
                                                  $awb = Integration::get_awb_number($pmode);
                                                  if($awb !=''){
                                                      $response = json_decode($awb,true);
                                                      if(isset($response['awb']) && count($response['awb']) >0){
                                                          $awb_no = $response['awb'][0];
                                                          $manifest_d =
                                                              array(
                                                                  'AWB_NUMBER'=>$awb_no,
                                                                  'order_NUMBER'=>$order_with_detail_s['order_id'],
                                                                  'PRODUCT'=>$pmode,
                                                                  'CONSIGNEE'=>$order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                                  'CONSIGNEE_ADDRESS1'=>$order_with_detail_s['ship_address'],
                                                                  'CONSIGNEE_ADDRESS2'=>$order_with_detail_s['ship_address_2'],
                                                                  'CONSIGNEE_ADDRESS3'=>'',
                                                                  'DESTINATION_CITY'=>$order_with_detail_s['ship_city'],
                                                                  'PINCODE'=>$order_with_detail_s['ship_pincode'],
                                                                  'STATE'=>$order_with_detail_s['ship_state'],
                                                                  'MOBILE'=>$order_with_detail_s['ship_phone'],
                                                                  'TELEPHONE'=>'',
                                                                  'ITEM_DESCRIPTION'=>'Multiple items',
                                                                  'PIECES'=>count($order_with_detail_s['detail']),
                                                                  'COLLECTABLE_VALUE'=>$coll_value,
                                                                  'DECLARED_VALUE'=>$order_with_detail_s['total'],
                                                                  'ACTUAL_WEIGHT'=>$order_with_detail_s['weight']/1000,
                                                                  'VOLUMETRIC_WEIGHT'=>($order_with_detail_s['length']*$order_with_detail_s['breadth']*$order_with_detail_s['height'])/5000,
                                                                  'LENGTH'=>$order_with_detail_s['length'],
                                                                  'BREADTH'=>$order_with_detail_s['breadth'],
                                                                  'HEIGHT'=>$order_with_detail_s['height'],
                                                                  'PICKUP_NAME'=>$warehouse['contact_name'],
                                                                  'PICKUP_ADDRESS_LINE1'=>$warehouse['address'],
                                                                  'PICKUP_ADDRESS_LINE2'=>$warehouse['address_2'],
                                                                  'PICKUP_PINCODE'=>$warehouse['pincode'],
                                                                  'PICKUP_PHONE'=>$warehouse['phone'],
                                                                  'PICKUP_MOBILE'=>$warehouse['phone'],
                                                                  'RETURN_NAME'=>$warehousereturn['contact_name'],
                                                                  'RETURN_ADDRESS_LINE1'=>$warehousereturn['address'],
                                                                  'RETURN_ADDRESS_LINE2'=>$warehousereturn['address_2'],
                                                                  'RETURN_PINCODE'=>$warehousereturn['pincode'],
                                                                  'RETURN_PHONE'=>$warehousereturn['phone'],
                                                                  'RETURN_MOBILE'=>$warehousereturn['phone'],
                                                                  'DG_SHIPMENT'=>false
                                                                  //Pass ""true"" in case shipment package contains any item that is restricted for air travel as per DGCA guidelines. Otherwise pass ""false"

                                                              );
                                                              $manifest = Integration::shipment_ecom(json_encode($manifest_d,true));
                                                             
                                                          //checking api logs
                                                          api_logs(json_encode($manifest_d,true),$manifest,$order_with_detail_s['id'],'1');
                                                              $manifest = json_decode($manifest,true);
                                                              if($manifest['shipments'][0]['success']){ }else{$awb_no ='';
                                                                $error .= $id .' '.$manifest['shipments'][0]['reason'].'  \n';
                                                                }
                                                      }else{
                                                        $error .= $id .': AWB not fetched  \n';
                                                      }
                                                  }else{
                                                    $error .= $id .': Payment mode not found  \n';
                                                  }
                                              }
                                              else if($required_corr_data['courier_id'] =='2'){
                                                  $coll_value =0;
                                                  if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                      $pmode = 'COD';
                                                      $coll_value = $order_with_detail_s['total'];
                                                  }elseif(strip_tags($order_with_detail_s['payment_mode']) =='Pre-Paid'){
                                                      $pmode = 'Prepaid';
                                                  }

                                                  if($required_corr_data['mode'] =='fa-plane'){
                                                      $trsfr_type = 'Express';
                                                      $ttype = 'a';
                                                  }else{
                                                      $trsfr_type = 'Surface';
                                                      $ttype = 's';
                                                  }
                                                  $awb = Integration::get_awb_number_delhivary($pmode,$ttype);
                                                  $awb = json_decode($awb,true);
                                                  $awb_no ='';
                                                  if((int)($awb) == $awb){
                                                      $awb_no = $awb;
                                                  }
                                                  $s_Aaress = $order_with_detail_s['ship_address'].' '.$order_with_detail_s['ship_address_2'];
                                                  $w_Add = $warehouse['address'].' '.$warehouse['address_2'];
                                                  $delhivery_data = array(
                                                      'shipments'=> array(array(
                                                          'add' => str_replace('&','and',$s_Aaress),
                                                          'phone' => $order_with_detail_s['ship_phone'],
                                                          'payment_mode' => $pmode,
                                                          'name' => $order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                          'pin' => $order_with_detail_s['ship_pincode'],
                                                          'order' => $order_with_detail_s['order_id'],
                                                          'shipping_mode' => $trsfr_type,
                                                          'shipment_height' => $order_with_detail_s['height'],
                                                          'shipment_width' => $order_with_detail_s['breadth'],
                                                          'shipment_length' => $order_with_detail_s['length'],
                                                          'cod_amount' => $coll_value,
                                                          'waybill' => $awb_no,
                                                          'total_amount' => $order_with_detail_s['total'],
                                                          'quantity' => count($order_with_detail_s['detail']),
                                                          'weight' => $order_with_detail_s['weight'],
                                                          'products_desc' => 'Multiple items',
                                                      )),
                                                      'pickup_location' => array(
                                                          'name' =>$warehouse['company'].$ttype,
                                                          'city' => $warehouse['city'],
                                                          'pin' => $warehouse['pincode'],
                                                          'phone' => $warehouse['phone'],
                                                          'add' => str_replace('&','and',$w_Add),
                                                      )
                                                  );
                                                  try {
                                                      $ship_delhivary = Integration::shipment_delhivary('format=json&data='.urlencode(json_encode($delhivery_data)),$ttype);
                                                      //checking api logs
                                                      api_logs(json_encode($delhivery_data),$ship_delhivary,$order_with_detail_s['id'],'2');
                                                      $d_data = json_decode($ship_delhivary,true);
                                                      if (!is_array($d_data)) {
                                                          throw new \Exception('Invalid or empty response from Delhivery API');
                                                      }
                                                  } catch (\Throwable $e) {
                                                      $d_data = ['success' => false, 'rmk' => 'Delhivery API error: ' . $e->getMessage()];
                                                  }
                                                  if(!$d_data['success']){
                                                      $rmkk ='';$awb_no ='';
                                                      if(isset($d_data['packages'][0]) && isset($d_data['packages'][0]['remarks'])){
                                                          $rmk =$d_data['packages'][0]['remarks'];
                                                          for($k=0;$k<count($rmk);$k++){
                                                              $rmkk .=$rmk[$k].',';
                                                          }
                                                          $rmkk = rtrim($rmkk,',');
                                                      }
                                                       if (stripos($rmkk, 'ClientWarehouse matching query does not exist') !== false) {
                                                           $this->autoRegisterDelhiveryWarehouse($warehouse, $ttype);
                                                           try {
                                                               $ship_delhivary = Integration::shipment_delhivary('format=json&data='.urlencode(json_encode($delhivery_data)),$ttype);
                                                               api_logs(json_encode($delhivery_data),$ship_delhivary,$order_with_detail_s['id'],'2');
                                                               $d_data = json_decode($ship_delhivary,true);
                                                               if (is_array($d_data)) {
                                                                   $rmkk ='';
                                                                   if(isset($d_data['packages'][0]) && isset($d_data['packages'][0]['remarks'])){
                                                                       $rmk =$d_data['packages'][0]['remarks'];
                                                                       for($k=0;$k<count($rmk);$k++){
                                                                           $rmkk .=$rmk[$k].',';
                                                                       }
                                                                       $rmkk = rtrim($rmkk,',');
                                                                   }
                                                               }
                                                           } catch (\Throwable $e) {
                                                               $d_data = ['success' => false, 'rmk' => 'Delhivery API error: ' . $e->getMessage()];
                                                           }
                                                       }
                                                      if($rmkk !=''){
                                                        $error .= $id .': '. $rmkk.' \n';
                                                      }else{
                                                          $error .= $id .': '.$d_data['rmk'].'  \n';
                                                      }
                                                  }

                                              }
                                              else if($required_corr_data['courier_id'] =='3'){
                                                $w_id = $rw_id = '';
                                                if(isset($warehouse->bd_id) && $warehouse->bd_id !=0 && $warehouse->bd_id !=''){
                                                    $w_id = $warehouse->bd_id;
                                                }else{
                                                    $war_array = array(
                                                        'address_title'=>$warehouse->id.' '.$warehouse->name,
                                                        'sender_name'=>$warehouse->contact_name,
                                                        'full_address'=>$warehouse->address.' '.$warehouse->address_2,
                                                        'phone'=>$warehouse->phone,
                                                        'pincode'=>$warehouse->pincode,
                                                    );
                                                    $getWarehouse =  Integration_more::warehouse_bludart(json_encode($war_array,true));
                                                    $warehusedetail = json_decode($getWarehouse,true);

                                                    if($warehusedetail['status'] && isset($warehusedetail['data']['pick_address_id'])){
                                                        $w_id = $warehusedetail['data']['pick_address_id'];
                                                        $warehouse->bd_id = $w_id;
                                                        $warehouse->save();
                                                    }else{
                                                        $msg = $warehusedetail['responsemsg'][0];
                                                        $error .= $id .':'. $msg  .'\n';
                                                    }

                                                }

                                                if($w_id !=''){
                                                    if($warehousereturn->id == $warehouse->id){
                                                        $rw_id = $w_id;
                                                    }else{
                                                        if(isset($warehousereturn->bd_id) && $warehousereturn->bd_id !=0 && $warehousereturn->bd_id !=''){
                                                            $rw_id = $warehousereturn->bd_id;
                                                        }else{
                                                            $warret_array = array(
                                                                'address_title'=>$warehousereturn->id.' '.$warehousereturn->name,
                                                                'sender_name'=>$warehousereturn->contact_name,
                                                                'full_address'=>$warehousereturn->address.' '.$warehousereturn->address_2,
                                                                'phone'=>$warehousereturn->phone,
                                                                'pincode'=>$warehousereturn->pincode,
                                                            );
                                                            $getWarereturnhouse =  Integration_more::warehouse_bludart(json_encode($warret_array,true));
                                                            $warehusedetailretrun = json_decode($getWarereturnhouse,true);
                                                            if($warehusedetailretrun['status'] && isset($warehusedetailretrun['data']['pick_address_id'])){
                                                                $rw_id = $warehusedetailretrun['data']['pick_address_id'];
                                                                $warehousereturn->bd_id = $rw_id;
                                                                $warehousereturn->save();
                                                            }else{
                                                                $msg = $warehusedetailretrun['responsemsg'];
                                                                $error .= $id .':'. $msg  .'for return warehouse \n';
                                                            }
                                                        }
                                                    }
                                                    if($rw_id !=''){    
                                                        if($required_corr_data['mode'] =='fa-plane'){
                                                            $p_type = 'air';
                                                        }else{
                                                            $p_type = 'surface';
                                                        }
                                                        $coll_value =0;
                                                        $awb_no ='';
                                                        if($order_with_detail_s['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">C.O.D</span>'){
                                                            $pmode = 'Cod';
                                                            $coll_value = $order_with_detail_s['total'];
                                                        }elseif($order_with_detail_s['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">Pre-Paid</span>'){
                                                            $pmode = 'Prepaid';
                                                        }  
                                                        $o_details =$order_with_detail_s['detail'];
                                                        $order_items = array();
                                                        for($i=0;$i<count($o_details);$i++){
                                                            $order_items[] = array(
                                                                'product_sku'=> $o_details[$i]['code'],
                                                                'product_name'=> $o_details[$i]['name'],
                                                                'product_value'=> $o_details[$i]['price'],
                                                                'product_quantity'=>$o_details[$i]['qty'],
                                                            );
                                                        }         
                                                        $bd_data = array(
                                                            'client_order_id'=>$order_with_detail_s['order_id'],
                                                            'consignee_emailid'=>$order_with_detail_s['ship_email'],
                                                            'consignee_pincode'=>$order_with_detail_s['ship_pincode'],
                                                            'consignee_mobile'=>$order_with_detail_s['ship_phone'],
                                                            'consignee_phone'=>'',
                                                            'consignee_address1'=>$order_with_detail_s['ship_address'],
                                                            'consignee_address2'=>$order_with_detail_s['ship_address_2'],
                                                            'consignee_name'=>$order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                            'express_type'=>$p_type,
                                                            'pick_address_id'=>$w_id,
                                                            'return_address_id'=>$rw_id,
                                                            'cod_amount'=>$coll_value,
                                                            'tax_amount'=>0,
                                                            'mps'=>'0',
                                                            'courier_type'=>1,
                                                            'courier_code'=>'PXBDE01',
                                                            'products'=>$order_items,
                                                            'payment_mode'=>$pmode,
                                                            'order_amount'=>$order_with_detail_s['total'],
                                                            'shipment_width'=>array(
                                                                $order_with_detail_s['breadth']
                                                            ),
                                                            'shipment_height'=>array(
                                                                $order_with_detail_s['height']
                                                            ),
                                                            'shipment_length'=>array(
                                                                $order_with_detail_s['length']
                                                            ),
                                                            'shipment_weight'=>array(
                                                                round(($order_with_detail_s['weight']/1000),2)
                                                            ),
                                                        );
                                                        // echo json_encode($bd_data,true);die;
                                                        $shimedata = Integration_more::shipment_bludart(json_encode($bd_data,true));
                                                        //checking api logs
                                                          api_logs(json_encode($bd_data,true),$shimedata,$order_with_detail_s['id'],'3');
                                                        $shimedata = json_decode($shimedata,true);
                                                        if($shimedata['status'] && isset($shimedata['data']['awb_number']) && $shimedata['data']['awb_number'] !=''){
                                                            $awb_no = $shimedata['data']['awb_number'];
                                                        }else{
                                                            $msg = $shimedata['responsemsg'];
                                                            if(gettype($msg) =='array' && count($msg)>0){
                                                                $error .= $id .':'. $msg[0]  .'\n';
                                                            }else{
                                                                $error .= $id .':'. $msg  .'\n';
                                                            }
                                                        }
                                                    }else{
                                                        $error .= $id .': Return Warehouse id not found  \n';
                                                    }
                                                }else{

                                                    $error .= $id .': Warehouse id not found  \n';
                                                }


                                              }
                                                     else if($required_corr_data['courier_id'] =='4'){
                                                $coll_value = 0;
                                                $pmode = 'Prepaid';
                                                if (strip_tags($order_with_detail_s['payment_mode']) == 'C.O.D') {
                                                    $pmode = 'COD';
                                                    $coll_value = $order_with_detail_s['total'];
                                                }
                                                if($required_corr_data['mode'] == 'fa-bicycle'){
                                                    $p_type = 'NDD';
                                                }elseif($required_corr_data['mode'] == 'fa-motorcycle'){
                                                    $p_type = 'SDD';
                                                }else{
                                                    $p_type = 'SD'; // fa-truck = Surface default
                                                }
                                                 // Extraction of credentials
                                                 $xb_integration = Integration::where('user_id', $order_with_detail_s['user_id'])->where('courier_id', 4)->first();
                                                 $xb_user = ($xb_integration && $xb_integration->xusername) ? $xb_integration->xusername : env('XBEES_USERNAME', 'admin@Hyloship.com');
                                                 $xb_pass = ($xb_integration && $xb_integration->xpassword) ? $xb_integration->xpassword : env('XBEES_PASSWORD', 'Xpress@1234567');
                                                 $xb_secret = ($xb_integration && $xb_integration->secret_key) ? $xb_integration->secret_key : env('XBEES_SECRETKEY', '5babb4d7a6c80b45ade918fb4e429068c8480e6125925c474d8d67a27f8190db');
                                                 $xb_biz_name = ($xb_integration && $xb_integration->b_account_name) ? $xb_integration->b_account_name : env('XBEES_BUSINESS_ACCOUNT', 'Hyloship');
                                                 $xb_key = ($xb_integration && $xb_integration->xxb_key) ? $xb_integration->xxb_key : env('XBEES_XB_KEY', 'Plmng39338VdtHa');

                                                  // Secure selection to prevent recycling of used/cancelled AWBs
                                                  $availbelawbdata = null;
                                                  $avlawb = null;
                                                  $awb_candidates = Xb_pincode::where('used', '0')
                                                      ->where('company_id', $current_company_id)
                                                      ->orderBy('id', 'asc')
                                                      ->limit(20)
                                                      ->get();

                                                  foreach ($awb_candidates as $candidate) {
                                                      $isUsedInOrders = DB::table('orders')->where('tracking_info', $candidate->awb)->exists();
                                                      $isUsedInTransactions = DB::table('transactions')->where('awb', $candidate->awb)->exists();

                                                      if (!$isUsedInOrders && !$isUsedInTransactions) {
                                                          $availbelawbdata = $candidate;
                                                          $avlawb = $candidate->awb;
                                                          $candidate->used = '1';
                                                          $candidate->save();
                                                          break;
                                                      } else {
                                                          // Housekeeping: Mark as used if found in order history to prevent future selection
                                                          $candidate->used = '1';
                                                          $candidate->save();
                                                      }
                                                  }

                                                  if($availbelawbdata){
                                                     $vol_weight    = round(($order_with_detail_s['length'] * $order_with_detail_s['breadth'] * $order_with_detail_s['height']) / 5000, 2);
                                                     $phy_weight    = round($order_with_detail_s['weight'] / 1000, 2);
                                                     $bill_weight   = max($phy_weight, $vol_weight);
                                                     $manifest_id   = date('YmdHi') . $order_with_detail_s['order_id'];

                                                     $expess_data = [
                                                         'BusinessAccountName' => $xb_biz_name,
                                                         'OrderNo'             => (string)$order_with_detail_s['order_id'],
                                                         'AirWayBillNO'        => (string)$avlawb,
                                                         'OrderType'           => $pmode,
                                                         'CollectibleAmount'   => (float)$coll_value,
                                                         'DeclaredValue'       => (float)$order_with_detail_s['total'],
                                                         'Quantity'            => count($order_with_detail_s['detail']),
                                                         'PickupType'          => 'Warehouse',
                                                         'ServiceType'         => 'SD',
                                                         'DropDetails' => [
                                                             'Addresses' => [[
                                                                 'Name'    => $order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                                 'Address' => $order_with_detail_s['ship_address'].' '.$order_with_detail_s['ship_address_2'],
                                                                 'City'    => $order_with_detail_s['ship_city'],
                                                                 'PinCode' => (string)$order_with_detail_s['ship_pincode'],
                                                                 'State'   => $order_with_detail_s['ship_state'],
                                                                 'Type'    => 'Primary',
                                                             ]],
                                                             'ContactDetails' => [[
                                                                 'PhoneNo' => (string)$order_with_detail_s['ship_phone'],
                                                                 'Type'    => 'Primary',
                                                             ]],
                                                         ],
                                                         'PickupDetails' => [
                                                             'Addresses' => [[
                                                                 'Name'    => $warehouse['name'],
                                                                 'Address' => $warehouse['address'].' '.$warehouse['address_2'],
                                                                 'City'    => $warehouse['city'],
                                                                 'PinCode' => (string)$warehouse['pincode'],
                                                                 'State'   => $warehouse['state'],
                                                                 'Type'    => 'Primary',
                                                             ]],
                                                             'ContactDetails' => [[
                                                                 'PhoneNo' => (string)$warehouse['phone'],
                                                                 'Type'    => 'Primary',
                                                             ]],
                                                             'PickupVendorCode' => '1',
                                                         ],
                                                         'RTODetails' => [
                                                             'Addresses' => [[
                                                                 'Name'    => $warehousereturn['name'],
                                                                 'Address' => $warehousereturn['address'].' '.$warehousereturn['address_2'],
                                                                 'City'    => $warehousereturn['city'],
                                                                 'PinCode' => (string)$warehousereturn['pincode'],
                                                                 'State'   => $warehousereturn['state'],
                                                                 'Type'    => 'Primary',
                                                             ]],
                                                             'ContactDetails' => [[
                                                                 'PhoneNo' => (string)$warehousereturn['phone'],
                                                                 'Type'    => 'Primary',
                                                             ]],
                                                         ],
                                                         'PackageDetails' => [
                                                             'Dimensions' => [
                                                                 'Height' => (float)$order_with_detail_s['height'],
                                                                 'Length' => (float)$order_with_detail_s['length'],
                                                                 'Width'  => (float)$order_with_detail_s['breadth'],
                                                             ],
                                                             'Weight' => [
                                                                 'BillableWeight' => (float)$bill_weight,
                                                                 'PhyWeight'      => (float)$phy_weight,
                                                                 'VolWeight'      => (float)$vol_weight,
                                                             ],
                                                         ],
                                                         'ManifestID' => $manifest_id,
                                                     ];
                                                     $expess_data['ServiceType'] = $p_type; // Use dynamic service type

                                                     $manifestRes = Integration::shipment_express(json_encode($expess_data, true), $xb_user, $xb_pass, $xb_secret, $xb_key);
                                                     api_logs(json_encode($expess_data, true), $manifestRes, $order_with_detail_s['id'], '4');
                                                     $manifest = json_decode($manifestRes, true);

                                                     $isSuccess = false;
                                                     if (isset($manifest['ReturnCode']) && $manifest['ReturnCode'] == '100') {
                                                         $isSuccess = true;
                                                     } elseif (isset($manifest['message']) && stripos($manifest['message'], 'success') !== false) {
                                                         $isSuccess = true;
                                                     } elseif (isset($manifest['ReturnMessage']) && stripos($manifest['ReturnMessage'], 'success') !== false) {
                                                         $isSuccess = true;
                                                     }

                                                     if ($isSuccess) {
                                                         if (isset($manifest['AWBNo']) && $manifest['AWBNo'] != '') {
                                                             $awb_no = $manifest['AWBNo'];
                                                         } elseif (isset($manifest['data'][0]['AWBNo']) && $manifest['data'][0]['AWBNo'] != '') {
                                                             $awb_no = $manifest['data'][0]['AWBNo'];
                                                         } else {
                                                             $awb_no = $avlawb;
                                                         }
                                                     } else {
                                                         $msg = $manifest['ReturnMessage'] ?? $manifest['message'] ?? 'Manifest Error';
                                                         $error .= $id . ': ' . $msg . '  \n';
                                                         // Release AWB back into pool since manifest failed
                                                         $availbelawbdata->used = '0';
                                                         $availbelawbdata->save();
                                                     }
                                                } else {
                                                    $error .= $id . ': Available AWBs exhausted for XpressBees. Please contact site admin.  \n';
                                                }
                                              }
                                              else if($required_corr_data['courier_id'] =='13'){
                                                   // Parcel Uncle bulk manifesting
                                                   $awb_no ='';
                                                   $shipment_id = null;

                                                   // Fetch user-specific Parcel Uncle credentials
                                                   $pu_integration = Integration::where('user_id', $order_with_detail_s['user_id'])->where('courier_id', 13)->first();
                                                   if (!$pu_integration) {
                                                       $pu_integration = new Integration();
                                                   }
                                                   if (empty($pu_integration->pu_api_key)) {
                                                       $pu_integration->pu_api_key = env('PARCEL_UNCLE_API_KEY');
                                                       $pu_integration->pu_mode = env('PARCEL_UNCLE_MODE', 'Test');
                                                       $pu_integration->pu_carrier_title = env('PARCEL_UNCLE_CARRIER_TITLE', 'Parcel Uncle Sandbox');
                                                   }
                                                   if (empty($pu_integration->pu_api_key)) {
                                                       $error .= $id . ': Parcel Uncle credentials are not configured for this user. \n';
                                                   } else {
                                                       if (!$warehouse) {
                                                           $error .= $id . ': Shipper warehouse address is missing. \n';
                                                       } else {
                                                           // Determine payment mode and cod amount
                                                           $is_cod = (strip_tags($order_with_detail_s['payment_mode']) == 'C.O.D');
                                                           $cod_amount = 0.0;
                                                           if ($is_cod) {
                                                               $cod_amount = (float)($order_with_detail_s['cod_amount'] ?: ($order_with_detail_s['custom_total'] ?: $order_with_detail_s['total']));
                                                           }
                                                           
                                                           // Map service type per Parcel Uncle API (accepted values: SAME_DAY, NEXT_DAY, EXPRESS_4H)
                                                           // SDD/fa-motorcycle -> SAME_DAY | NDD/fa-bicycle -> NEXT_DAY | Quick/fa-bolt -> EXPRESS_4H
                                                           $service_type = 'SAME_DAY';
                                                           if (isset($required_corr_data['mode'])) {
                                                               $mode_lower = strtolower($required_corr_data['mode']);
                                                               if ($mode_lower === 'fa-bicycle' || str_contains($mode_lower, 'ndd')) {
                                                                   $service_type = 'NEXT_DAY';
                                                               } elseif ($mode_lower === 'fa-bolt' || str_contains($mode_lower, 'quick')) {
                                                                   $service_type = 'EXPRESS_4H';
                                                               } else {
                                                                   $service_type = 'SAME_DAY'; // fa-motorcycle / SDD / default
                                                               }
                                                           }
                                                            // Fetch company name
                                                            $user = Admin::find($order_with_detail_s['user_id']);
                                                            $sender_name = $user ? trim($user->company_name) : '';
                                                            if (empty($sender_name)) {
                                                                $sender_name = trim($warehouse->contact_name ?: $warehouse->name);
                                                            }

                                                            $sender_phone = preg_replace('/[^0-9]/', '', $warehouse->phone);
                                                            if (strlen($sender_phone) >= 10) {
                                                                $sender_phone = substr($sender_phone, -10);
                                                            }

                                                            $recipient_name = trim(($order_with_detail_s['ship_fname'] ?? '') . ' ' . ($order_with_detail_s['ship_lname'] ?? ''));

                                                            $raw_recipient_phone = !empty($order_with_detail_s['ship_mobile']) ? $order_with_detail_s['ship_mobile'] : $order_with_detail_s['ship_phone'];
                                                            $recipient_phone = preg_replace('/[^0-9]/', '', $raw_recipient_phone);
                                                            if (strlen($recipient_phone) >= 10) {
                                                                $recipient_phone = substr($recipient_phone, -10);
                                                            }

                                                            // Validate details before calling API
                                                            $v_errors = [];
                                                            $pu_payload = [
                                                                'service_type'     => $service_type,
                                                                'payment_method'   => 'WALLET',
                                                                'payment_mode'     => $is_cod ? 'COD' : 'Prepaid',
                                                                'order_number'     => (string)$order_with_detail_s['order_id'],
                                                                'total_amount'     => (float)$order_with_detail_s['total'],
                                                                'cod_amount'       => (float)$cod_amount,
                                                                'pickup_address'   => $warehouse->address . ' ' . ($warehouse->address_2 ?? ''),
                                                                'pickup_city'      => $warehouse->city,
                                                                'pickup_state'     => $warehouse->state,
                                                                'pickup_pincode'   => (string)$warehouse->pincode,
                                                                'delivery_address' => $order_with_detail_s['ship_address'] . ' ' . ($order_with_detail_s['ship_address_2'] ?? ''),
                                                                'delivery_city'    => $order_with_detail_s['ship_city'],
                                                                'delivery_state'   => $order_with_detail_s['ship_state'],
                                                                'delivery_pincode' => (string)$order_with_detail_s['ship_pincode'],
                                                                'sender_name'      => $sender_name,
                                                                'sender_phone'     => $sender_phone,
                                                                'recipient_name'   => $recipient_name,
                                                                'recipient_phone'  => $recipient_phone,
                                                                'weight_kg'        => (float)round($order_with_detail_s['weight'] / 1000, 2),
                                                                'parcel_type'      => 'PACKAGE',
                                                            ];





                                                            // Validate details before calling API
                                                            $v_errors = [];
                                                            if (strcasecmp($sender_name, $recipient_name) === 0) {
                                                                $v_errors[] = 'Sender name and Recipient name cannot be the same. Please ensure sender is your company name.';
                                                            }
                                                            if (!preg_match('/^[6-9]\d{9}$/', $sender_phone)) {
                                                                $v_errors[] = 'Invalid sender phone number. Must be a valid 10-digit Indian mobile number.';
                                                            }
                                                            if (!preg_match('/^[6-9]\d{9}$/', $recipient_phone)) {
                                                                $v_errors[] = 'Invalid customer/recipient phone number. Must be a valid 10-digit Indian mobile number (no leading zero).';
                                                            }
                                                            if ($sender_phone === $recipient_phone) {
                                                                $v_errors[] = 'Sender phone number and recipient phone number cannot be the same.';
                                                            }

                                                            if (!empty($v_errors)) {
                                                                $error .= $id . ': Validation failed: ' . implode('; ', $v_errors) . ' \n';
                                                            } else {
                                                                try {
                                                                    $res_pu = Integration::shipment_parcel_uncle($pu_payload, $pu_integration);
                                                                    // Log API call
                                                                    api_logs(json_encode($pu_payload), $res_pu, $order_with_detail_s['id'], '13');
                                                                    $res_pu_arr = json_decode($res_pu, true);
                                                                } catch (\Throwable $e) {
                                                                    api_logs(json_encode($pu_payload), 'EXCEPTION: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine(), $order_with_detail_s['id'], '13');
                                                                    $res_pu_arr = ['success' => false, 'message' => 'Parcel Uncle API Connection Failed: ' . $e->getMessage()];
                                                                }
                                                                if (isset($res_pu_arr['success']) && $res_pu_arr['success'] && isset($res_pu_arr['data']['tracking_number'])) {
                                                                    $awb_no = $res_pu_arr['data']['tracking_number'];
                                                                    $shipment_id = $res_pu_arr['data']['tracking_number'];
                                                                } else {
                                                                    $err_msg = $res_pu_arr['message'] ?? 'Error from Parcel Uncle API';
                                                                    if (isset($res_pu_arr['errors']) && is_array($res_pu_arr['errors'])) {
                                                                        $err_msg .= ': ' . json_encode($res_pu_arr['errors']);
                                                                    }
                                                                    $error .= $id . ': ' . $err_msg . ' \n';
                                                                }
                                                            }
                                                       }
                                                   }
                                               }
                                              else if($required_corr_data['courier_id'] =='14'){
                                                    $awb_no ='';
                                                    $shipment_id = null;

                                                    $ip_integration = Integration::where('user_id', $order_with_detail_s['user_id'])->where('courier_id', 14)->first();
                                                    $ip_cust_id = ($ip_integration && $ip_integration->ip_customer_id) ? $ip_integration->ip_customer_id : env('INDIAPOST_CUSTOMER_ID', '1843579100');
                                                    $ip_contract_id = ($ip_integration && $ip_integration->ip_contract_id) ? $ip_integration->ip_contract_id : env('INDIAPOST_CONTRACT_ID', '41441234');

                                                    $ip_pincode = DB::table('ip_pincodes')
                                                        ->where('company_id', $current_company_id)
                                                        ->where('used', '0')
                                                        ->orderBy('id', 'asc')
                                                        ->first();

                                                    if (!$ip_pincode) {
                                                        $error .= $id . ': No unused India Post barcodes found in the pool. \n';
                                                    } else {
                                                        if (!$warehouse) {
                                                            $error .= $id . ': Shipper warehouse address is missing. \n';
                                                        } else {
                                                            $barcode = $ip_pincode->awb;
                                                            $is_cod = (strip_tags($order_with_detail_s['payment_mode']) == 'C.O.D');
                                                            $cod_amount = 0.0;
                                                            if ($is_cod) {
                                                                $cod_amount = (float)($order_with_detail_s['cod_amount'] ?: ($order_with_detail_s['custom_total'] ?: $order_with_detail_s['total']));
                                                            }

                                                            $service_type = ($ip_integration && $ip_integration->ip_service_type) ? $ip_integration->ip_service_type : env('INDIAPOST_SERVICE_TYPE', 'BP');
                                                            if ($service_type === 'BP' && $ip_integration && (str_contains(strtolower($ip_integration->ip_carrier_title), 'speed') || str_contains(strtolower($ip_integration->ip_carrier_title), 'sp'))) {
                                                                $service_type = 'SP';
                                                            }

                                                            // article_type long-form values per sample input in docs
                                                            // service_type (BP/SP) is required — confirmed by API error
                                                            $weight_g = (float)$order_with_detail_s['weight'];
                                                            if ($service_type === 'SP') {
                                                                $article_type = ($weight_g <= 500) ? 'SP_INLAND_DOC' : 'SP_INLAND_PARCEL';
                                                                $shape_of_article = ($weight_g <= 500) ? 'DOC' : 'NROL';
                                                            } else {
                                                                $article_type = 'BUSINESS_PARCEL';
                                                                $shape_of_article = 'NROL';
                                                            }

                                                            $ip_payload = [
                                                                'articles' => [
                                                                    [
                                                                        'bulk_customer_id' => (string)$ip_cust_id,
                                                                        'contract_id' => (string)$ip_contract_id,
                                                                        'barcode_no' => (string)$barcode,
                                                                        'pickup_or_dropoff' => 'DROPOFF',
                                                                        'pickup_dropoff_office_id' => 21260024,
                                                                        'article_type' => $article_type,
                                                                        'service_type' => $service_type,
                                                                        'physical_weight' => (int)round($order_with_detail_s['weight']),
                                                                        'shape_of_article' => $shape_of_article,
                                                                        'length' => (float)($order_with_detail_s['length'] ?: 0),
                                                                        'breadth_diameter' => (float)($order_with_detail_s['breadth'] ?: 0),
                                                                        'height' => (float)($order_with_detail_s['height'] ?: 0),
                                                                        'priority_flag' => 'FALSE',
                                                                        'delivery_instruction' => '',
                                                                        'delivery_slot' => '',
                                                                        'instruction_rts' => 'RTS',
                                                                        'sender_name' => substr($warehouse->contact_name ?: $warehouse->name, 0, 50),
                                                                        'sender_company' => substr($warehouse->name, 0, 50),
                                                                        'sender_add_line_1' => substr($warehouse->address, 0, 50),
                                                                        'sender_add_line_2' => substr($warehouse->address_2 ?? '', 0, 50),
                                                                        'sender_city' => substr($warehouse->city, 0, 30),
                                                                        'sender_state' => substr($warehouse->state, 0, 30),
                                                                        'sender_pincode' => (string)$warehouse->pincode,
                                                                        'sender_emailid' => '',
                                                                        'sender_alt_contact' => '',
                                                                        'sender_kyc' => '',
                                                                        'sender_tax_reference' => '',
                                                                        'receiver_name' => substr($order_with_detail_s['ship_fname'] . ' ' . $order_with_detail_s['ship_lname'], 0, 50),
                                                                        'receiver_company' => '',
                                                                        'receiver_add_line_1' => substr($order_with_detail_s['ship_address'], 0, 50),
                                                                        'receiver_add_line_2' => substr($order_with_detail_s['ship_address_2'] ?? '', 0, 50),
                                                                        'receiver_city' => substr($order_with_detail_s['ship_city'], 0, 30),
                                                                        'receiver_state' => substr($order_with_detail_s['ship_state'], 0, 30),
                                                                        'receiver_pincode' => (string)$order_with_detail_s['ship_pincode'],
                                                                        'receiver_emailid' => '',
                                                                        'receiver_alt_contact' => '',
                                                                        'receiver_kyc' => '',
                                                                        'receiver_tax_reference' => '',
                                                                        'alt_address_flag' => 'FALSE',
                                                                        'pickup_address_flag' => 'FALSE',
                                                                        'drop_off_pincode' => (string)$warehouse->pincode,
                                                                        'sender_mobile_no' => (string)(preg_replace('/[^0-9]/', '', $warehouse->phone) ?: '9999999999'),
                                                                        'receiver_mobile_no' => (string)(preg_replace('/[^0-9]/', '', $order_with_detail_s['ship_mobile'] ?: $order_with_detail_s['ship_phone']) ?: '9999999999'),
                                                                        'prepayment_code' => '',
                                                                        'value_of_prepayment' => 0,
                                                                        'codr_cod' => $is_cod ? 'COD' : '',
                                                                        'value_for_codr_cod' => (float)$cod_amount,
                                                                        'insurance_type' => '',
                                                                        'value_of_insurance' => 0,
                                                                        'ack' => 'FALSE',
                                                                        'reg' => 'FALSE',
                                                                        'otp' => 'FALSE',
                                                                        'bulk_reference' => ''
                                                                    ]
                                                                ]
                                                            ];

                                                            try {
                                                                $res_ip = Integration::shipment_indiapost($ip_payload, $ip_integration);
                                                                // Log API call
                                                                api_logs(json_encode($ip_payload), $res_ip, $order_with_detail_s['id'], '14');
                                                                $res_ip_arr = json_decode($res_ip, true);
                                                            } catch (\Throwable $e) {
                                                                api_logs(json_encode($ip_payload), 'EXCEPTION: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine(), $order_with_detail_s['id'], '14');
                                                                $res_ip_arr = ['success' => false, 'message' => 'India Post API Connection Failed: ' . $e->getMessage()];
                                                            }
                                                            if (isset($res_ip_arr['success']) && $res_ip_arr['success']) {
                                                                DB::table('ip_pincodes')->where('id', $ip_pincode->id)->update(['used' => '1', 'updated_at' => now()]);
                                                                $awb_no = $barcode;
                                                                $shipment_id = $barcode;
                                                            } else {
                                                                $err_msg = $res_ip_arr['message'] ?? 'Error from India Post API';
                                                                if (isset($res_ip_arr['errors']) && is_array($res_ip_arr['errors'])) {
                                                                    $err_msg .= ': ' . json_encode($res_ip_arr['errors']);
                                                                }
                                                                $error .= $id . ': ' . $err_msg . ' \n';
                                                            }
                                                        }
                                                    }
                                                }
                                              else if($required_corr_data['courier_id'] =='5'){
                                                  $coll_value =0;
                                                  $awb_no ='';
                                                  $coll_mode = '';
                                                  if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                      $pmode = 'cod';
                                                      $coll_value = $order_with_detail_s['total'];
                                                      $coll_mode = 'cash';
                                                  }elseif(strip_tags($order_with_detail_s['payment_mode']) =='Pre-Paid'){
                                                      $pmode = 'prepaid';
                                                  }
                                                  if($required_corr_data['mode'] =='fa-plane'){
                                                      $trsfr_type = 'B2C PRIORITY';
                                                  }else{
                                                      $trsfr_type = 'B2C SMART EXPRESS';
                                                  }
                                                  $dtdc = array(
                                                      'consignments' =>array( array(
                                                          'customer_code' =>'GL7569',
                                                          'service_type_id' =>$trsfr_type,
                                                          'load_type' =>'NON-DOCUMENT',
                                                          'consignment_type' =>'Forward',
                                                          'dimension_unit' =>'cm',
                                                          'length' =>$order_with_detail_s['length'],
                                                          'width' =>$order_with_detail_s['breadth'],
                                                          'height' =>$order_with_detail_s['height'],
                                                          'num_pieces' =>count($order_with_detail_s['detail']),
                                                          'weight_unit' =>'kg',
                                                          'weight' =>$order_with_detail_s['weight']/1000,
                                                          'customer_reference_number' =>$order_with_detail_s['order_id'],
                                                          'commodity_id' =>'7',
                                                          'reference_number' =>'',
                                                          'declared_value' =>$order_with_detail_s['total'],
                                                          'cod_amount' =>$coll_value,
                                                          'cod_collection_mode'=> $coll_mode,
                                                          'origin_details' => array(
                                                              'name' =>$warehouse['name'],
                                                              'phone' =>$warehouse['phone'],
                                                              'alternate_phone' =>'8851678080',
                                                              'address_line_1' =>$warehouse['address'],
                                                              'address_line_2' =>$warehouse['address_2'],
                                                              'pincode' =>$warehouse['pincode'],
                                                              'city' =>$warehouse['city'],
                                                              'state' =>$warehouse['state'],
                                                          ),
                                                          'destination_details'=> array(
                                                              'name' =>$order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                              'phone' =>$order_with_detail_s['ship_phone'],
                                                              'alternate_phone' =>'9958523480',
                                                              'address_line_1' =>$order_with_detail_s['ship_address'],
                                                              'address_line_2' =>$order_with_detail_s['ship_address_2'],
                                                              'pincode' =>$order_with_detail_s['ship_pincode'],
                                                              'city' =>$order_with_detail_s['ship_city'],
                                                              'state' =>$order_with_detail_s['ship_state'],
                                                          ),
                                                          'return_details'=> array(
                                                              'name' =>$warehousereturn['name'],
                                                              'phone' =>$warehousereturn['phone'],
                                                              'alternate_phone' =>'8851678080',
                                                              'address_line_1' =>$warehousereturn['address'],
                                                              'address_line_2' =>$warehousereturn['address_2'],
                                                              'pincode' =>$warehousereturn['pincode'],
                                                              'city' =>$warehousereturn['city'],
                                                              'state' =>$warehousereturn['state'],
                                                           ),
                                                      )
                                                  ));
                                                  $res_dtdc = Integration::shipment_dtdc(json_encode($dtdc));
                                                  //checking api logs
                                                  api_logs(json_encode($dtdc),$res_dtdc,$order_with_detail_s['id'],'5');
                                                  $res_dtdc = json_decode($res_dtdc,true);
                                                  if(isset($res_dtdc['data']) && isset($res_dtdc['data'][0])){
                                                      if($res_dtdc['data'][0]['success']){
                                                          $awb_no = $res_dtdc['data'][0]['reference_number'];
                                                      }else{
                                                        $error .= $id .': '. $res_dtdc['data'][0]['message'].'  \n';
                                                      }
                                                  }
                                              }
                                              else if($required_corr_data['courier_id'] =='6'){
                                                  $coll_value =0;
                                                  $awb_no ='';
                                                  $p_type='';
                                                  if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                      $coll_value = $order_with_detail_s['total'];
                                                      if($required_corr_data['mode'] =='fa-plane'){
                                                          $p_type = 'ACC';
                                                          $is_surface = false;
                                                      }else{
                                                          $p_type = 'WKO';
                                                          $is_surface = true;
                                                      }
                                                  }elseif(strip_tags($order_with_detail_s['payment_mode']) =='Pre-Paid'){
                                                      if($required_corr_data['mode'] =='fa-plane'){
                                                          $p_type = 'ACP';
                                                          $is_surface = false;
                                                      }else{
                                                          $p_type = 'WKO';
                                                          $is_surface = true;
                                                      }
                                                  }

                                                  $smartr = array( array(
                                                      'packageDetails' =>array(
                                                          'awbNumber' =>'',
                                                          'orderNumber' =>$order_with_detail_s['order_id'],
                                                          'productType' =>$p_type,
                                                          'collectableValue' =>(string)($coll_value),
                                                          'declaredValue' =>(string)($order_with_detail_s['total']),
                                                          'itemDesc' =>"Multiple Items",
                                                          'dimensions' =>$order_with_detail_s['length'].'~'.$order_with_detail_s['breadth'].'~'.$order_with_detail_s['height'].'~'.count($order_with_detail_s['detail']).'~'.($order_with_detail_s['weight']/1000).'~0',
                                                          'pieces' =>(string)count($order_with_detail_s['detail']),
                                                          'weight' =>(string)$order_with_detail_s['weight']/1000,
                                                          'invoiceNumber' =>'',
                                                      ),
                                                      'deliveryDetails' =>array(
                                                          'toName'=> $order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                          'toAdd'=> $order_with_detail_s['ship_address'].' '.$order_with_detail_s['ship_address_2'],
                                                          'toCity'=> $order_with_detail_s['ship_city'],
                                                          'toState'=> $order_with_detail_s['ship_state'],
                                                          'toPin'=> $order_with_detail_s['ship_pincode'],
                                                          'toMobile'=> $order_with_detail_s['ship_phone'],
                                                          'toAddType'=> 'Home',
                                                          'toLat'=> '',
                                                          'toLng'=> '',
                                                          'toEmail'=>  $order_with_detail_s['ship_email']
                                                      ),
                                                      'pickupDetails' =>array(
                                                          'fromName'=> $warehouse['name'],
                                                          'fromAdd'=> $warehouse['address'].' '.$warehouse['address_2'],
                                                          'fromCity'=> $warehouse['city'],
                                                          'fromState'=> $warehouse['state'],
                                                          'fromPin'=> $warehouse['pincode'],
                                                          'fromMobile'=> $warehouse['phone'],
                                                          'fromAddType'=> 'Seller',
                                                          'fromLat'=> '',
                                                          'fromLng'=> '',
                                                          'fromEmail'=> $warehouse['email']
                                                      ),
                                                      'returnDetails' =>array(
                                                          'rtoName'=> $warehousereturn['name'],
                                                          'rtoAdd'=> $warehousereturn['address'].' '.$warehousereturn['address_2'],
                                                          'rtoCity'=> $warehousereturn['city'],
                                                          'rtoState'=> $warehousereturn['state'],
                                                          'rtoPin'=> $warehousereturn['pincode'],
                                                          'rtoMobile'=> $warehousereturn['phone'],
                                                          'rtoAddType'=> 'Seller',
                                                          'rtoLat'=> '',
                                                          'rtoLng'=> '',
                                                          'rtoEmail'=> $warehousereturn['email']
                                                      ),
                                                      'additionalInformation'=> array(
                                                          'customerCode'=> '',
                                                          'essentialFlag'=> '',
                                                          'otpFlag'=> '',
                                                          'dgFlag'=> '',
                                                          'isSurface'=> $is_surface,
                                                          'isReverse'=> 'false',
                                                          'sellerGSTIN'=> $warehouse['gst_no'],
                                                          'sellerERN'=> $order_with_detail_s['e_bill_no']
                                                      )
                                                  ));
                                                  $res_smartr= Integration::shipment_smartr(json_encode($smartr));
                                                  //checking api logs
                                                  api_logs(json_encode($smartr),$res_smartr,$order_with_detail_s['id'],'6');
                                                  $res_smartr = json_decode($res_smartr,true);
                                                  if(isset($res_smartr['success']) && !empty($res_smartr['total_success'])){
                                                      $awb_no = $res_smartr['total_success'][0]['awbNumber'];
                                                  }else{
                                                      if(isset($res_smartr['total_failure'][0])){
                                                        $error .= $res_smartr['total_failure'][0]['error'];
                                                      }
                                                  }
                                              }
                                              else if($required_corr_data['courier_id'] =='7'){
                                                  $coll_value =0;
                                                  $awb_no ='';
                                                  $p_type='';
                                                  $service_code = 'ECONOMY';
                                                  if($required_corr_data['mode'] =='fa-plane'){
                                                      $service_code = 'REGULAR';
                                                  }
                                                  if($order_with_detail_s['cancel_date'] !='' || $order_with_detail_s['cancel_date'] != null){
                                                      $time = explode(' ',$order_with_detail_s['cancel_date'])[1];
                                                      $time = str_replace(':','',$time);
                                                      $date = explode(' ',$order_with_detail_s['cancel_date'])[0];
                                                      $day = explode('-',$date)[2];
                                                      $o_id =$time.$order_with_detail_s['order_id'].$day;
                                                  }else{
                                                      $o_id = $order_with_detail_s['order_id'];
                                                  }
                                                  if(strlen($o_id)>10){
                                                      $o_id = substr($o_id,0,10);
                                                  }
                                                  if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                      $coll_value = $order['total'];
                                                      $t_id = 'XYZC'.sprintf('%010d', $o_id);
                                                  }elseif(strip_tags($order_with_detail_s['payment_mode']) =='Pre-Paid'){
                                                      $t_id = 'XYZP'.sprintf('%010d', $o_id);
                                                  }

                                                  $shipment_items = array();
                                                  foreach($order_with_detail_s['detail'] as $detail){
                                                      $shipment_items[] =array(
                                                          'product_id'=>$detail['code'],
                                                          'category'=>'miscellaneous',
                                                          'product_title'=>$detail['name'],
                                                          'quantity'=>$detail['qty'],
                                                          'cost'=>array(
                                                              'totalSaleValue' =>$detail['price']*$detail['qty'],
                                                              'totalTaxValue'=>$detail['tax_amount'],
                                                              'tax_breakup'=>array(
                                                                  "cgst"=>'0.00',
                                                                  "sgst"=>'0.00',
                                                                  "igst"=>'0.00',
                                                              )
                                                          ),
                                                          'seller_details' =>array(
                                                              'seller_reg_name'=>'Hyloship',
                                                              'vat_id'=>'',
                                                              'cst_id'=>'',
                                                              'gstin_id'=>'',
                                                          ),
                                                          'hsn'=>'',
                                                          'item_attributes'=>array(
                                                              array(
                                                                  'name'=>'order_id',
                                                                  'value'=>(string)($detail['order_id']),
                                                              ),
                                                              array(
                                                                  'name'=>'invoice_id',
                                                                  'value'=>'invmy_'.date('my').$detail['user_id'],
                                                              ),
                                                          ),
                                                          'handling_attributes'=>array(),
                                                      );
                                                  }
                                                  // echo '<pre>';print_r($shipment_items);die;
                                                  $ekart = array(
                                                      'request_Id' =>'',
                                                      'client_name' =>'XYZ',
                                                      'services' =>array(array(
                                                          'service_code' =>$service_code,
                                                          'service_details' =>array(array(
                                                              'service_leg' =>'FORWARD',
                                                              'service_data' =>array(
                                                                  'vendor_name' =>$order_with_detail_s['channel'],
                                                                  'amount_to_collect' =>$coll_value,
                                                                  'dispatch_date' =>'',
                                                                  'source' =>array(
                                                                      'address'=>array(
                                                                          'first_name'=>$warehouse['name'],
                                                                          'address_line1'=>$warehouse['address'].' '.$warehouse['address_2'],
                                                                          'pincode'=>$warehouse['pincode'],
                                                                          'city'=>$warehouse['city'],
                                                                          'state'=>$warehouse['state'],
                                                                          'primary_contact_number'=>$warehouse['phone'],
                                                                      )
                                                                  ),
                                                                  'destination'=>array(
                                                                      'address'=>array(
                                                                          'first_name'=>$order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                                          'address_line1'=>$order_with_detail_s['ship_address'].' '.$order_with_detail_s['ship_address_2'],
                                                                          'pincode'=>$order_with_detail_s['ship_pincode'],
                                                                          'city'=>$order_with_detail_s['ship_city'],
                                                                          'state'=>$order_with_detail_s['ship_state'],
                                                                          'primary_contact_number'=>$order_with_detail_s['ship_phone'],
                                                                      )
                                                                  ),
                                                                  'return_location'=>array(
                                                                      'address'=>array(
                                                                          'first_name'=>$warehousereturn['name'],
                                                                          'address_line1'=>$warehousereturn['address'].' '.$warehousereturn['address_2'],
                                                                          'pincode'=>$warehousereturn['pincode'],
                                                                          'city'=>$warehousereturn['city'],
                                                                          'state'=>$warehousereturn['state'],
                                                                          'primary_contact_number'=>$warehousereturn['phone'],
                                                                      )
                                                                  ),
                                                              ),
                                                              'shipment'=>array(
                                                                  'tracking_id' =>$t_id,
                                                                  'shipment_value' =>$order_with_detail_s['total'],
                                                                  'shipment_dimensions' =>array(
                                                                      'length'=>array(
                                                                          'value'=>$order_with_detail_s['length']
                                                                      ),
                                                                      'breadth'=>array(
                                                                          'value'=>$order_with_detail_s['breadth']
                                                                      ),
                                                                      'height'=>array(
                                                                          'value'=>$order_with_detail_s['height']
                                                                      ),
                                                                      'weight'=>array(
                                                                          'value'=>$order_with_detail_s['weight']/1000
                                                                      ),
                                                                  ),
                                                                  'shipment_items'=>$shipment_items,
                                                              ),


                                                          ),),
                                                      ),),
                                                  );
                                                  $res_ekart= Integration_more::shipment_smartr(json_encode($ekart));
                                                  //checking api logs
                                                  api_logs(json_encode($ekart),$res_ekart,$order_with_detail_s['id'],'7');
                                                  $res_ekart = json_decode($res_ekart,true);
                                                  if(isset($res_ekart['response'][0]) && $res_ekart['response'][0]['status'] =='REQUEST_RECEIVED'){
                                                      $awb_no = $res_ekart['response'][0]['tracking_id'];
                                                  }else{
                                                      if(isset($res_ekart['response'][0]) && $res_ekart['response'][0]['status'] =='REQUEST_REJECTED'){
                                                        $error .= $id .':'. $res_ekart['response'][0]['message'][0]. '\n';
                                                      }else{
                                                        $error .= $id .': Ekart is not responding  \n';
                                                      }
                                                  }

                                              }
                                              else if($required_corr_data['courier_id'] =='8'){
                                                $coll_value =0;
                                                $awb_no = $awb ='';
                                                $p_type='';
                                                $arrayawb = array(
                                                    'count' =>1
                                                );
                                                $awb_shadowfax = Integration_more::get_awb_number_shadowfax('forward',json_encode($arrayawb,true));
                                                // $awb_shadowfax ='{"message":"success","awb_numbers":["SF10000006KIM"]}';
                                                $awb_shadowfax =  json_decode($awb_shadowfax,true);
                                                if(isset($awb_shadowfax['message']) && $awb_shadowfax['message'] =='success'){
                                                    $awb = $awb_shadowfax['awb_numbers'][0];
                                                }
                                                if($awb !=''){
                                                    if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                        $pmode = 'COD';
                                                        $coll_value = $order_with_detail_s['total'];
                                                    }elseif(strip_tags($order_with_detail_s['payment_mode']) =='Pre-Paid'){
                                                        $pmode = 'Prepaid';
                                                    }
                                                    if($required_corr_data['mode'] =='fa-plane'){
                                                        $trsfr_type = 'ndd';
                                                    }else{
                                                        $trsfr_type = 'regular';
                                                    }

                                                    $shipment_items = array();
                                                    foreach($order_with_detail_s['detail'] as $detail){
                                                        $shipment_items[] =array(
                                                            'sku_name'=>$detail['code'],
                                                            'hsn_code'=>'',
                                                            'invoice_no'=>'invmy_'.date('my').$detail['user_id'],
                                                            'category'=>'miscellaneous',
                                                            'price'=>$detail['price'],
                                                            'seller_details' =>array(
                                                                'seller_name'=>'Hyloship',
                                                                'seller_address'=>'',
                                                                'seller_state'=>'',
                                                                'gstin_number'=>'',
                                                            ),
                                                            'taxes' =>array(
                                                                "cgst"=>'0.00',
                                                                "sgst"=>'0.00',
                                                                "igst"=>'0.00',
                                                                "total_tax"=>$detail['tax_amount'],
                                                            ), 
                                                            'additional_details'=>array(
                                                                'quantity'=>$detail['qty'],
                                                            )  
                                                        );
                                                    }

                                                    $shadowfax_data = array(
                                                        'order_type' =>'marketplace',
                                                        'order_details'=>array(
                                                            'client_order_id'=>$order_with_detail_s['order_id'],
                                                            'awb_number'=>$awb,
                                                            'actual_weight'=>$order_with_detail_s['weight'],
                                                            'volumetric_weight'=>($order_with_detail_s['length']*$order_with_detail_s['breadth']*$order_with_detail_s['height'])/5,
                                                            'product_value'=>$order_with_detail_s['total'],
                                                            'payment_mode'=>$pmode,
                                                            'cod_amount'=>$coll_value,
                                                            'total_amount'=>$order_with_detail_s['total'],
                                                            'eway_bill'=>$order_with_detail_s['e_bill_no'],
                                                            'gstin_number'=>$order_with_detail_s['ship_gstin'],
                                                            'order_service'=>$trsfr_type,
                                                        ),
                                                        'customer_details'=>array(
                                                            'name'=>$order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                            'contact'=>$order_with_detail_s['ship_phone'],
                                                            'address_line_1'=>$order_with_detail_s['ship_address'],
                                                            'address_line_2'=>$order_with_detail_s['ship_address_2'],
                                                            'city'=>$order_with_detail_s['ship_city'],
                                                            'state'=>$order_with_detail_s['ship_state'],
                                                            'pincode'=>$order_with_detail_s['ship_pincode'],
                                                            'latitude'=>$order_with_detail_s['ship_latitude'],
                                                            'longitude'=>$order_with_detail_s['ship_longitude'],
                                                        ),
                                                        'pickup_details'=>array(
                                                            'name'=>$warehouse['company'],
                                                            'contact'=>$warehouse['phone'],
                                                            'address_line_1'=>$warehouse['address'],
                                                            'address_line_2'=>$warehouse['address_2'],
                                                            'city'=>$warehouse['city'],
                                                            'state'=>$warehouse['state'],
                                                            'pincode'=>$warehouse['pincode'],
                                                            'latitude'=>$warehouse['latitude'],
                                                            'longitude'=>$warehouse['longitude'],
                                                        ),
                                                        'rts_details'=>array(
                                                            'name'=>$warehousereturn['company'],
                                                            'contact'=>$warehousereturn['phone'],
                                                            'address_line_1'=>$warehousereturn['address'],
                                                            'address_line_2'=>$warehousereturn['address_2'],
                                                            'city'=>$warehousereturn['city'],
                                                            'state'=>$warehousereturn['state'],
                                                            'pincode'=>$warehousereturn['pincode'],
                                                        ),
                                                        'product_details'=>$shipment_items,
                                                    );
                                                    $res_shadowfax= Integration_more::shipment_shadowfax('forward',json_encode($shadowfax_data));
                                                    //checking api logs
                                                    api_logs(json_encode($shadowfax_data),$res_shadowfax,$order_with_detail_s['id'],'8');
                                                    $res_shadowfax = json_decode($res_shadowfax,true);
                                                    if(isset($res_shadowfax['message']) && $res_shadowfax['message'] =='Success'){
                                                        $awb_no = $res_shadowfax['data']['awb_number'];
                                                    }else{
                                                        $e_msg ='Issue in data';
                                                        if(isset($res_shadowfax['errors'])){
                                                            $e_msg = is_array($res_shadowfax['errors']) ? json_encode($res_shadowfax['errors']) : $res_shadowfax['errors'];
                                                        }
                                                        else if(isset($res_shadowfax['errorCode'])){
                                                            $e_msg =$res_shadowfax['errorCode'];
                                                        }
                                                        else if(isset($res_shadowfax['message'])){
                                                            $e_msg =$res_shadowfax['message'];
                                                        }
                                                        $error .= $id .': '. $e_msg.'  \n';
                                                    }
                                                }else{
                                                    $error .= $id .': Issue in generating awb no. for shadowfax  \n';
                                                }
                                              }
                                              else if($required_corr_data['courier_id'] =='9'){
                                                    $coll_value =0;
                                                    $awb_no = $awb ='';
                                                    $p_type='';

                                                    if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                        $pmode = 'COD';
                                                        $coll_value = $order_with_detail_s['total'];
                                                            $cod_Array = array(array(
                                                            'id'=>'CollectOnDelivery',
                                                            'amount'=>array(
                                                                'unit'=>'INR',
                                                                'value'=>$order_with_detail_s['total'],
                                                            )

                                                           ));
                                                    }elseif(strip_tags($order_with_detail_s['payment_mode']) =='Pre-Paid'){
                                                        $pmode = 'Prepaid';
                                                            $cod_Array =array();
                                                    }
                                                    if($required_corr_data['mode'] =='fa-plane'){
                                                        $trsfr_type = 'SWA-IN-OA';
                                                    }else{
                                                        $trsfr_type = 'SWA-IN-OA';
                                                    }

                                                    $shipment_items = array();
                                                    foreach($order_with_detail_s['detail'] as $detail){
                                                        $shipment_items[] =array(
                                                            'itemValue'=>array(
                                                                'value'=>$detail['price'],
                                                                'unit'=>'INR',
                                                            ),
                                                            'weight'=>array(
                                                                'unit'=>'GRAM',
                                                                'value'=>50.0,
                                                            ),
                                                            'description'=>$detail['name'],
                                                            'itemIdentifier'=>'"'.$detail['id'].'"',
                                                            'quantity'=>$detail['qty'],

                                                        );
                                                    }
                                                    if(strlen($order_with_detail_s['ship_address']) >55){
                                                        $order_with_detail_s['ship_address'] = trim(preg_replace('/\s+/', ' ', substr($order_with_detail_s['ship_address'],0,55)));
                                                        $order_with_detail_s['ship_address_2'] = trim(preg_replace('/\s+/', ' ', substr($order_with_detail_s['ship_address'],55,strlen($order_with_detail_s['ship_address'])).' '.$order_with_detail_s['ship_address_2']));
                                                    }
                                                    if(strlen($warehouse['address']) >55){
                                                        $warehouse['address'] = trim(preg_replace('/\s+/', ' ', substr($warehouse['address'],0,55)));
                                                        $warehouse['address_2'] = trim(preg_replace('/\s+/', ' ', substr($warehouse['address'],55,strlen($warehouse['address'])).' '.$warehouse['address_2']));
                                                    }
                                                    if(strlen($warehousereturn['ship_address']) >55){
                                                        $warehousereturn['address'] = trim(preg_replace('/\s+/', ' ', substr($warehousereturn['address'],0,55)));
                                                        $warehousereturn['address_2'] = trim(preg_replace('/\s+/', ' ', substr($warehousereturn['address'],55,strlen($warehousereturn['address'])).' '.$warehousereturn['address_2']));
                                                    }
                                                    $ats_data = array(
                                                        'shipTo'=>array(
                                                            'name'=>$order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                            'addressLine1'=>$order_with_detail_s['ship_address'],
                                                            'addressLine2'=>$order_with_detail_s['ship_address_2'],
                                                            // 'companyName'=>$order_with_detail_s['ship_address_2'],
                                                            'stateOrRegion'=>$order_with_detail_s['ship_state'],
                                                            'city'=>$order_with_detail_s['ship_city'],
                                                            'countryCode'=>'IN',
                                                            'postalCode'=>$order_with_detail_s['ship_pincode'],
                                                            'email'=>$order_with_detail_s['ship_email'],
                                                            'phoneNumber'=>$order_with_detail_s['ship_phone'],
                                                        ),
                                                        'shipFrom'=>array(
                                                            'name'=>$warehouse['name'],
                                                            'addressLine1'=>$warehouse['address'],
                                                            'addressLine2'=>$warehouse['address_2'],
                                                            'companyName'=>$warehouse['company'],
                                                            'stateOrRegion'=>$warehouse['state'],
                                                            'city'=>$warehouse['city'],
                                                            'countryCode'=>'IN',
                                                            'postalCode'=>$warehouse['pincode'],
                                                            'email'=>$warehouse['email'],
                                                            'phoneNumber'=>$warehouse['phone'],
                                                        ),
                                                        'returnTo'=>array(
                                                            'name'=>$warehousereturn['name'],
                                                            'addressLine1'=>$warehousereturn['address'],
                                                            'addressLine2'=>$warehousereturn['address_2'],
                                                            'companyName'=>$warehousereturn['company'],
                                                            'stateOrRegion'=>$warehousereturn['state'],
                                                            'city'=>$warehousereturn['city'],
                                                            'countryCode'=>'IN',
                                                            'postalCode'=>$warehousereturn['pincode'],
                                                            'email'=>$warehousereturn['email'],
                                                            'phoneNumber'=>$warehousereturn['phone'],
                                                        ),
                                                        'packages'=>array(
                                                            array(
                                                                'dimensions' =>array(
                                                                    'length'=>$order_with_detail_s['length'],
                                                                    'width'=>$order_with_detail_s['breadth'],
                                                                    'height'=>$order_with_detail_s['height'],
                                                                    'unit'=>'CENTIMETER',
                                                                ),
                                                                'weight' =>array(
                                                                    'unit'=>'GRAM',
                                                                    'value'=>$order_with_detail_s['weight'],
                                                                ),
                                                                'insuredValue' =>array(
                                                                    'unit'=>'INR',
                                                                    'value'=>$order_with_detail_s['total'],
                                                                ),
                                                                'isHazmat'=>false,
                                                                'sellerDisplayName'=>'',
                                                                'packageClientReferenceId'=>$order_with_detail_s['order_id'],
                                                                'items'=>$shipment_items,

                                                            ),
                                                        ),
                                                            'valueAddedServiceDetails'=>$cod_Array,
                                                        'taxDetails'=>array(
                                                            array(
                                                                'taxType'=>'GST',
                                                                'taxRegistrationNumber'=>'"'.$order_with_detail_s['ship_gstin'].'"',
                                                            ),
                                                        ),
                                                        'channelDetails'=>array(
                                                            'channelType'=>'EXTERNAL',
                                                        ),
                                                        'serviceSelection'=>array(
                                                            'serviceId'=>array(
                                                                $trsfr_type
                                                            ),
                                                        ),
                                                        'labelSpecifications'=>array(
                                                            'format'=>'PDF',
                                                            'size'=>array(
                                                                'width'=>4,
                                                                'length'=>6,
                                                                'unit'=>'INCH',
                                                            ),
                                                            'dpi'=>203,
                                                            'pageLayout'=>'DEFAULT',
                                                            'needFileJoining'=>false,
                                                            'requestedDocumentTypes'=>array(
                                                                'LABEL'
                                                            ),
                                                        ),
                                                    );
                                                    $res_ats= Integration_more::shipment_ats(json_encode($ats_data));
                                                  //checking api logs
                                                  api_logs(json_encode($ats_data),$res_ats,$order_with_detail_s['id'],'9');
                                                    $re_array = json_decode($res_ats,true);
                                                    if(isset($re_array['payload']) && isset($re_array['payload']['packageDocumentDetails']) && isset($re_array['payload']['packageDocumentDetails'][0]) && isset($re_array['payload']['packageDocumentDetails'][0]['trackingId'])){
                                                        $awb_no =$re_array['payload']['packageDocumentDetails'][0]['trackingId'];
                                                        if(isset($re_array['payload']['shipmentId'])){
                                                            $shipment_id = $re_array['payload']['shipmentId'];
                                                        }
                                                    }else{
                                                        $e_msg ='Issue in data';
                                                        if(isset($re_array['errors'])){
                                                            if(isset($re_array['errors'][0]['details'])){
                                                                $error_d = explode('detected:',$re_array['errors'][0]['details']);
                                                                if(count($error_d) >1){
                                                                    $e_msg =$error_d[1];
                                                                }else{
                                                                    $e_msg =$re_array['errors'][0]['details'];
                                                                }

                                                            }
                                                        }
                                                        $error .= $id .': '. $e_msg.'  \n';
                                                    }
                                              }
                                              $ochktrck = Order::where('id',$id)->first();
                                              if($awb_no !='' && $ochktrck->tracking_info ==''){
                                                $manifest_order_array[$required_corr_data['courier_id']][] = $id;

                                                $order->tracking_info = $awb_no;
                                                $order->shipped_date = now();
                                                $order->shipment_id = $shipment_id;
                                                $order->ship_courier_id = $required_corr_data['courier_id'];

                                                $order->shipping_courier_cost = $ship_courier_cost;
                                                $order->shipping_courier_type = $required_corr_data['mode'];
                                                $order->shipping_courier_weight_used = $required_corr_data['weight_used'];
                                                $order->shipping_courier_weight = $required_corr_data['weight'];
                                                $order->cod = $required_corr_data['cod'];
                                                $order->gst = $required_corr_data['gst'];
                                                $order->gst_freight = $required_corr_data['gst_freight'];
                                                $order->gst_cod = $required_corr_data['gst_cod'];
                                                $order->freight = $required_corr_data['freight'];
                                                $order->reverse_charge = $required_corr_data['reverse_charge'];
                                                $order->rate = $required_corr_data['rate'];
                                                $order->rateadd = $required_corr_data['rateadd'];
                                                
                                                $order->shipping_courier_costparent = $ship_courier_costparent;
                                                $order->codparent = $required_corr_data['codparent'];
                                                $order->gstparent = $required_corr_data['gstparent'];
                                                $order->gst_freightparent = $required_corr_data['gst_freightparent'];
                                                $order->gst_codparent = $required_corr_data['gst_codparent'];
                                                $order->freightparent = $required_corr_data['freightparent'];
                                                $order->reverse_chargeparent = $required_corr_data['reverse_chargeparent'];
                                                $order->rateparent = $required_corr_data['rateparent'];
                                                $order->rateaddparent = $required_corr_data['rateaddparent'];
                                                
                                                $order->zone = $zone;
                                                $order->status = 2;
                                                $order->warehouse_id = $ware;
                                                $order->return_warehouse_id = $return_warehouse_id;
                                                $order->save();

                                                Status::orderstatuslog($id,$current_company_id,$old_status,'Shipped',now());
                                                 $dublicartras = Transaction::where('user_id',$orde_user_id)->where('order_id',$order->id)->where('awb',$awb_no)->where('remarks','freight charge')->where('parent_data','0')->first();
            
                                                if($dublicartras ==''){
                                                    $balance = Admin::find($orde_user_id);
                                                    $before_blnc = $balance->wallet_blc;
                                                    $balance->wallet_blc = $balance->wallet_blc - $ship_courier_cost;
                                                    $balance->save();

                                                    // $transaction = new Transaction();
                                                    // $transaction->order_id = $order->id;
                                                    // $transaction->user_id = $order->user_id;
                                                    // $transaction->awb = $awb_no;
                                                    // $transaction->tracking_info = $awb_no ."<br>". $required_corr_data['name'];
                                                    // $transaction->credit = "0.00";
                                                    // $transaction->debit = $ship_courier_cost;
                                                    // $transaction->remarks = "Amount Debited for assigning";
                                                    // $transaction->show_data = '0';
                                                    // $transaction->save();

                                                    $transaction = new Transaction();
                                                    $transaction->order_id = $order->id;
                                                    $transaction->user_id = $orde_user_id;
                                                    $transaction->company_id = $current_company_id;
                                                    $transaction->awb = $awb_no;
                                                    $transaction->tracking_info = $awb_no ."<br>". $required_corr_data['name'];
                                                    $transaction->credit = "0.00";
                                                    $transaction->debit = $required_corr_data['freight'] + $required_corr_data['gst_freight'];
                                                    $transaction->closing_blc = $before_blnc-$transaction->debit;
                                                    if($order->reverse_order =='0'){
                                                        $transaction->remarks = "freight charge";
                                                    }else{
                                                        $transaction->remarks = "freight & reverse charge";
                                                    }
                                                    $transaction->show_data = '1';
                                                    $transaction->save();
                                                    $before_blnc =$transaction->closing_blc;

                                                    $transactionparent = new Transaction();
                                                    $transactionparent->order_id = $order->id;
                                                    $transactionparent->user_id = $parent_userid;
                                                    $transactionparent->company_id = $current_company_id;
                                                    $transactionparent->awb = $awb_no;
                                                    $transactionparent->tracking_info = $awb_no ."<br>". $required_corr_data['name'];
                                                    $transactionparent->credit = "0.00";
                                                    $transactionparent->debit = $required_corr_data['freightparent'] + $required_corr_data['gst_freightparent'];
                                                    if($order->reverse_order =='0'){
                                                        $transactionparent->remarks = "freight charge";
                                                    }else{
                                                        $transactionparent->remarks = "freight & reverse charge";
                                                    }
                                                    $transactionparent->show_data = '1';
                                                    $transactionparent->parent_data = '1';
                                                    $transactionparent->save();

                                                    if($required_corr_data['cod'] !=0){
                                                        $transaction = new Transaction();
                                                        $transaction->order_id = $order->id;
                                                        $transaction->user_id = $orde_user_id;
                                                        $transaction->awb = $awb_no;
                                                        $transaction->tracking_info = $awb_no ."<br>". $required_corr_data['name'];
                                                        $transaction->credit = "0.00";
                                                        $transaction->debit = $required_corr_data['cod'] + $required_corr_data['gst_cod'];
                                                        $transaction->closing_blc = $before_blnc-$transaction->debit;
                                                        $transaction->remarks = "cod charge";
                                                        $transaction->show_data = '1';
                                                        $transaction->save();

                                                        $transactionparent = new Transaction();
                                                        $transactionparent->order_id = $order->id;
                                                        $transactionparent->user_id = $parent_userid;
                                                        $transactionparent->company_id = $company_id;
                                                        $transactionparent->awb = $awb_no;
                                                        $transactionparent->tracking_info = $awb_no ."<br>". $required_corr_data['name'];
                                                        $transactionparent->credit = "0.00";
                                                        $transactionparent->debit = $required_corr_data['codparent'] + $required_corr_data['gst_codparent'];
                                                        $transactionparent->remarks = "cod charge";
                                                        $transactionparent->show_data = '1';
                                                        $transactionparent->parent_data = '1';
                                                        $transactionparent->save();
                                                    }
                                                }
                                                createlogs('awb', 'order', $order->id);
                                                
                                            }
                                          }

                                      }
                                  }else{
                                    $error .= $id .': Not Servicable  \n';
                                  }
                              }
                            }else{
                                $error .= $id .': Some required details are missing'. '\n';
                            }
                        }else{
                            $error .= $id .': AWB already assigned'. '\n';
                        }
                        }
                        DB::commit();
                        } catch (\Throwable $e) {
                            DB::rollBack();
                            api_logs(json_encode(['action' => 'bulk_awb_assign_exception', 'order_id' => $id]), 'EXCEPTION: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine(), $id, $cour_id ?: '0');
                            $error .= $id . ': Exception: ' . $e->getMessage() . ' \n';
                        }
                    }
                    if($error ==''){
                        return Redirect()->back()->with('success', 'AWB Assigned Successfully!');
                    }
                    return Redirect()->back()->with('error', ($error));
                }else{
                    return back()->with('error',"No Warehouse selected"); 
                }
            }else{
                return back()->with('error',"No Warehouse selected"); 
            }
            
        }
        if(request()->status == 'delivered')
        {
            foreach(request()->id as $id)
                {
                    $order = Order::where('id', $id)->first();
                    $old_status = strip_tags($order->status);
                    $order->status = '3';
                    $order->delivered_date = now();
                    $order->save();
                    Status::orderstatuslog($id,$order->company_id,$old_status,'Delivered',now());
                }
                return redirect()->route('admin.order.all')->with('success', 'Orders Delivered Successfully!');    
        }
        if(request()->status == 'clone'){
            $current_company_id = $u_data->company_id;
            foreach(request()->id as $id)
            {
                $oldorder = Order::with('detail')->findOrFail($id);
                $lastOrder = Order::latest('id')->first();
                $orderId = $lastOrder ? sprintf('%03d', $lastOrder->id + 1) : '001';
                $order = new Order();
                $order->user_id = $user_id;
                $order->company_id = $current_company_id;
                $order->order_id = $orderId;
                $order->ship_fname = $oldorder->ship_fname;
                $order->ship_lname = $oldorder->ship_lname;
                $order->ship_email = $oldorder->ship_email;
                $order->ship_company = $oldorder->ship_company;
                $order->ship_phone = $oldorder->ship_phone;
                $order->ship_address = $oldorder->ship_address;
                $order->ship_address_2 = $oldorder->ship_address_2;
                $order->ship_country = $oldorder->ship_country;
                $order->ship_pincode = $oldorder->ship_pincode;
                $order->ship_city = $oldorder->ship_city;
                $order->ship_state = $oldorder->ship_state;
                $order->ship_latitude = $oldorder->ship_latitude;
                $order->ship_longitude = $oldorder->ship_longitude;
                $order->ship_gstin = $oldorder->ship_gstin;
                $order->bill_fname = $oldorder->bill_fname;
                $order->bill_lname = $oldorder->bill_lname;
                $order->bill_company = $oldorder->bill_company;
                $order->bill_phone = $oldorder->bill_phone;
                $order->bill_address = $oldorder->bill_address;
                $order->bill_address_2 = $oldorder->bill_address_2;
                $order->bill_country = $oldorder->bill_country;
                $order->bill_pincode = $oldorder->bill_pincode;
                $order->bill_city = $oldorder->bill_city;
                $order->bill_state = $oldorder->bill_state;
                $order->bill_latitude = $oldorder->bill_latitude;
                $order->bill_longitude = $oldorder->bill_longitude;
                $order->bill_gstin = $oldorder->bill_gstin;
                $order->e_bill_no = $oldorder->e_bill_no;
                $order->same_add = $oldorder->same_add;
                $order->discount = $oldorder->discount;
                $order->shipping_cost = $oldorder->shipping_cost;
                $order->total = $oldorder->total;
                $order->custom_total = $oldorder->custom_total;
                $order->payment_mode = strip_tags($oldorder->payment_mode) == 'C.O.D' ? '6':'12';
                $order->vendor_order_id = $oldorder->vendor_order_id;
                $order->channel = $oldorder->channel;
                $order->weight = $oldorder->weight;
                $order->length = $oldorder->length;
                $order->breadth = $oldorder->breadth;
                $order->height = $oldorder->height;
                $order->note = $oldorder->note;
                $order->save();
                $oid = $order->id;
                $order->order_id = $oid;
                $order->save();
                foreach($oldorder['detail'] as $olddetail)
                {
                    $detail = new OrderDetail();
                    $detail->user_id = $user_id;
                    $detail->company_id = $current_company_id;
                    $detail->order_id = $oid;
                    $detail->name = $olddetail->name;
                    $detail->code = $olddetail->code;
                    $detail->price = $olddetail->price;
                    $detail->discount = $olddetail->discount;
                    $detail->qty = $olddetail->qty ;
                    $detail->discount_type = $olddetail->discount_type;
                    $detail->tax_percent = $olddetail->tax_percent;
                    $detail->tax_amount = $olddetail->tax_amount ;
                    $detail->total_price = $olddetail->total_price;
                    $detail->save();
                }
                //create logs
                createlogs('created', 'order', $oid);
                return redirect()->back()->with('success', 'Orders Delivered Successfully!');  
            }
        }
        
    }

    public function bulkordercreate(Request $request)
    {
        return view('admin.order.bulkcreate');
       
    }


    
   public function bulkorderstore1(Request $request)
   {
        try {
            $collections = importExcelOrCsv($request->file('excel'));
        } catch (\Exception $exception) {
            return back()->with('error','You have uploaded a wrong format file, please upload the right file.');
        }
        $requiredFields = ['Order_ID*', 'First_Name*', 'Last_Name*', 'Phone_No*', 'Address_1*', 'Country*', 'City*', 'State*', 'Pincode*', 'Billing address is same as Shipping address(Y/N)*', 'weight_(gms)*','length_(cms)*','breadth_(cms)*','height_(cms)*','order_total_amount*','payment_mode(cod/pre-paid)*','Product_Name*','SKU*','Unit_Price*','qty*'];
        $c=1;
        if(array_key_first($collections[0]) !='Order_ID*'){
        return back()->with('error', 'You have uploaded a wrong format file, please upload the right file.');
        }
        foreach ($collections as $row) {
            foreach ($requiredFields as $field) {
                if (!isset($row[$field]) || rtrim(ltrim($row[$field])) =='') {
                    return back()->with('error', 'The ' . rtrim($field,'*') . ' field is missing in the '. $c.' row Please make sure all required fields are present.');
                }
            }
            $c++;
        }
        
        foreach($collections as $row){
            if(count(explode(';',$row['Product_Name*'])) !=0 && count(explode(';',$row['SKU*'])) !=0 && count(explode(';',$row['Unit_Price*'])) !=0  && count(explode(';',$row['qty*'])) !=0)
            {
                $count = count(explode(';',$row['Product_Name*'])); 
                $code = count(explode(';',$row['SKU*'])); 
                // echo $row['discount_type(f->flat/p->percentage)'];echo  '<br>'.count(explode(';',$row['discount_type(f->flat/p->percentage)'])).'<br>'.$code;die;
                $price = count(explode(';',$row['Unit_Price*'])); 
                // $type = count(json_decode($row['discount_type(f->flat/p->percentage)'])); 
                // $discount = count(json_decode($row['product_discount'])); 
                $qty = count(explode(';',$row['qty*'])); 
                // $tax = count(json_decode($row['tax_percent'])); 
                // $tax_amt = count(json_decode($row['tax_amount'])); 
                // $total = count(json_decode($row['total_Amount'])); 
                if(($code != $count) || ($price != $count) || ($qty != $count) ){
                        return back()->with('error','Please fill all the required fields, issue in product data');
                }
                if($row['discount_type(f->flat/p->percentage)'] !='' && $code != count(explode(';',$row['discount_type(f->flat/p->percentage)']))){
                        return back()->with('error','Please fill all the required fields, issue in discount_type');
                }
                if($row['product_discount'] !='' && $code != count(explode(';',$row['product_discount']))){
                        return back()->with('error','Please fill all the required fields, issue in product_discount');
                }
                if($row['tax_percent'] !='' && $code != count(explode(';',$row['tax_percent']))){
                        return back()->with('error','Please fill all the required fields, issue in tax_percent');
                }
                if($row['tax_amount'] !='' && $code != count(explode(';',$row['tax_amount']))){
                        return back()->with('error','Please fill all the required fields, issue in tax_amount');
                }
                if($row['total_Amount'] !='' && $code != count(explode(';',$row['total_Amount']))){
                        return back()->with('error','Please fill all the required fields, issue in total_Amount');
                }
            }else{
                // echo count(json_decode($row['name']));
                $e_msg ='';
                if(count(explode(';',$row['Product_Name*'])) ==0){
                    $e_msg .='Product_Name, ';
                }
                if( count(explode(';',$row['SKU*'])) ==0){
                    $e_msg .='SKU, ';
                }
                if(count(explode(';',$row['Unit_Price*'])) ==0){
                    $e_msg .='Unit_Price, ';
                }
                if(count(explode(';',$row['qty*'])) ==0){
                    $e_msg .='qty, ';
                }
                
                $e_msg = rtrim($e_msg,', ');
                return back()->with('error','Please fill the fields('.$e_msg.') in the correct format');
            }
        }
        
        foreach ($collections as $key => $value) {
            if($request->action_id =='update'){
                $order = Order::find($value['id']);
                if($order ==''){
                   
                }else{
                    if(strip_tags($order->status) =='New' || strip_tags($order->status) =='Canceled'){
                        $getuser = DB::table('orders')
                                ->select('user_id as user')
                                ->where('id','=',$value['id'])
                                ->first();
                        $orde_user_id  = $getuser->user;
                        $order->ship_fname = $value['First_Name*'];
                        $order->ship_lname = $value['Last_Name*'] ?? null;
                        $order->ship_email = $value['Email_id'] ?? null;
                        $order->ship_company = $value['Company_Name'] ?? null;
                        $order->ship_phone = $value['Phone_No*'] ?? null;
                        $order->ship_address = $value['Address_1*'] ?? null;
                        $order->ship_address_2 = $value['Address_2'] ?? null;
                        if($value['Country*'] !=''){
                            $c_id = Country::where('name',$value['Country*'])->first('id');
                            if($c_id == null){
                                $order->ship_country ='101';
                            }else{
                                $order->ship_country =$c_id['id'];
                            }
                        }else{
                            $order->ship_country ='101';
                        }
                        $order->ship_pincode = $value['Pincode*'] ?? null;
                        $order->ship_city = $value['City*'] ?? null;
                        $order->ship_state = $value['State*'] ?? null;
                        $order->ship_latitude = $value['latitude'] ?? null;
                        $order->ship_longitude = $value['longitude'] ?? null;
                        $order->ship_gstin = $value['Gstin'] ?? null;
                        $order->bill_fname = $value['Billing_First_Name'] ?? null;
                        $order->bill_lname = $value['Billing_Last_Name'] ?? null;
                        $order->bill_company = $value['Billing_Company_Name'] ?? null;
                        $order->bill_phone = $value['Billing_Phone'] ?? null;
                        $order->bill_address = $value['Billing_Address_1'] ?? null;
                        $order->bill_address_2 = $value['Billing_Address_2'] ?? null;
                        if($value['Billing_Country'] !=''){
                            $b_id = Country::where('name',$value['Billing_Country'])->first('id');
                            if($b_id == null){
                                $order->bill_country ='101';
                            }else{
                                $order->bill_country =$b_id['id'];
                            }
                        }else{
                            $order->bill_country ='101';
                        }
                        
                        $order->bill_pincode = $value['Billing_Pincode'] ?? null;
                        $order->bill_city = $value['Billing_City'] ?? null;
                        $order->bill_state = $value['Billing_State'] ?? null;
                        $order->bill_latitude = $value['Billing_latitude'] ?? null;
                        $order->bill_longitude = $value['Billing_longitude'] ?? null;
                        $order->bill_gstin = $value['Billing_Gstin'] ?? null;
                        $order->e_bill_no = $value['e_bill_no'] ?? null;
                        if($value['Billing address is same as Shipping address(Y/N)*'] =='Y' || $value['Billing address is same as Shipping address(Y/N)*'] =='Yes' || $value['Billing address is same as Shipping address(Y/N)*'] =='1')
                        {
                            $order->same_add ='1';
                        }else{
                            $order->same_add ='0';
                        }
                        if($value['discount'] ==''){
                            $order->discount = 0;
                        }else{
                            $order->discount = $value['discount'];
                        }
                        if($value['shipping_cost'] ==''){
                            $order->shipping_cost = 0;
                        }else{
                            $order->shipping_cost = $value['shipping_cost'];
                        }
                        $order->total = $value['order_total_amount*'] ?? 0;
                        $order->custom_total = $value['order_total_amount*'] ?? 0;
                        
                        if($value['payment_mode(cod/pre-paid)*']=='cod' || $value['payment_mode(cod/pre-paid)*']=='c.o.d' || $value['payment_mode(cod/pre-paid)*']=='COD'){
                            $order->payment_mode = '6';
                        }else{
                            $order->payment_mode = '12';
                        }
                        
                        $order->vendor_order_id = $value['Order_ID*'];
                        $order->channel = $value['channel(Hyloship/amazon/shopify)'] ?? 'Hyloship';
                        $order->weight = $value['weight_(gms)*'];
                        $order->length = $value['length_(cms)*'];
                        $order->breadth = $value['breadth_(cms)*'];
                        $order->height = $value['height_(cms)*'];
                        $order->note = $value['note'] ?? null;
//                        echo $order;die;
                        $order->save();
                    
                        $total_cost_order =0;
                        $deatildelete =OrderDetail::where('order_id', $value['id'])->delete();
                        for($j=0;$j<count(explode(';',$value['Product_Name*']));$j++){
                            $detail = new OrderDetail();
                            $detail->order_id = $order->id;
                            $user_id = $orde_user_id;
                            $detail->user_id = $user_id;
                            $detail->name = explode(';',$value['Product_Name*'])[$j];
                            $detail->code = explode(';',$value['SKU*'])[$j];
                            $detail->price = explode(';',$value['Unit_Price*'])[$j];
                            $detail->qty = explode(';',$value['qty*'])[$j];
                            if(count(explode(';',$value['discount_type(f->flat/p->percentage)'])) ==0 || $value['discount_type(f->flat/p->percentage)'] ==''){
                                $detail->discount_type ='f';
                                $detail->discount =0;
                            }else{
                                $detail->discount_type =explode(';',$value['discount_type(f->flat/p->percentage)'])[$j];
                                $detail->discount =explode(';',$value['product_discount'])[$j];
                            }
                            if(count(explode(';',$value['tax_percent'])) ==0 || $value['tax_percent'] ==''){
                                $detail->tax_percent =  0;
                                $detail->tax_amount =  0;
                            }else{
                                $detail->tax_percent = explode(';',$value['tax_percent'])[$j];
                                $detail->tax_amount = ($detail->price*$detail->qty*intval($detail->tax_percent))/100;
                            }
                            if($detail->discount_type == 'f'){
                                $detail->total_price = ($detail->tax_amount + ($detail->price*$detail->qty)) - intval($detail->discount);
                            }else{
                                $detail->total_price = ($detail->tax_amount + ($detail->price*$detail->qty)) - (($detail->price*$detail->qty*intval($detail->discount))/100);
                            }
                            $total_cost_order += $detail->total_price;
                            $detail->save();
                        }
                        $order->total = $total_cost_order - $order->discount + $order->shipping_cost;
                        $order_data = Order::where('id', $order->id)->first();
                        $order_data->total = $total_cost_order - $order_data->discount + $order_data->shipping_cost;
                        $order_data->custom_total = $total_cost_order - $order_data->discount + $order_data->shipping_cost;
                        $order_data->save();
                    }    
                }
            }else{
//            $order = Order::all()->last();
            $order = Order::latest()->first();
            if ($order) {
                $lastOrderId = $order->id;
                $order_id = sprintf('%03d', intval($lastOrderId) + 1);
            } else {
                $order_id = '001';
            }
            $order = new Order();
            $order->order_id = $order_id;
            $user_id = Auth::guard('admin')->user()->id;
            $order->user_id = $user_id;
            $order->ship_fname = $value['First_Name*'];
            $order->ship_lname = $value['Last_Name*'] ?? null;
            $order->ship_email = $value['Email_id'] ?? null;
            $order->ship_company = $value['Company_Name'] ?? null;
            $order->ship_phone = $value['Phone_No*'] ?? null;
            $order->ship_address = $value['Address_1*'] ?? null;
            $order->ship_address_2 = $value['Address_2'] ?? null;
            if($value['Country*'] !=''){
                $c_id = Country::where('name',$value['Country*'])->first('id');
                if($c_id == null){
                    $order->ship_country ='101';
                }else{
                    $order->ship_country =$c_id['id'];
                }
            }else{
                $order->ship_country ='101';
            }
            $order->ship_pincode = $value['Pincode*'] ?? null;
            $order->ship_city = $value['City*'] ?? null;
            $order->ship_state = $value['State*'] ?? null;
            $order->ship_latitude = $value['latitude'] ?? null;
            $order->ship_longitude = $value['longitude'] ?? null;
            $order->ship_gstin = $value['Gstin'] ?? null;
            $order->bill_fname = $value['Billing_First_Name'] ?? null;
            $order->bill_lname = $value['Billing_Last_Name'] ?? null;
            $order->bill_company = $value['Billing_Company_Name'] ?? null;
            $order->bill_phone = $value['Billing_Phone'] ?? null;
            $order->bill_address = $value['Billing_Address_1'] ?? null;
            $order->bill_address_2 = $value['Billing_Address_2'] ?? null;
            if($value['Billing_Country'] !=''){
                $b_id = Country::where('name',$value['Billing_Country'])->first('id');
                if($b_id == null){
                    $order->bill_country ='101';
                }else{
                    $order->bill_country =$b_id['id'];
                }
            }else{
                $order->bill_country ='101';
            }
            
            $order->bill_pincode = $value['Billing_Pincode'] ?? null;
            $order->bill_city = $value['Billing_City'] ?? null;
            $order->bill_state = $value['Billing_State'] ?? null;
            $order->bill_latitude = $value['Billing_latitude'] ?? null;
            $order->bill_longitude = $value['Billing_longitude'] ?? null;
            $order->bill_gstin = $value['Billing_Gstin'] ?? null;
            $order->e_bill_no = $value['e_bill_no'] ?? null;
            if($value['Billing address is same as Shipping address(Y/N)*'] =='Y' || $value['Billing address is same as Shipping address(Y/N)*'] =='Yes' || $value['Billing address is same as Shipping address(Y/N)*'] =='1')
            {
                $order->same_add ='1';
            }else{
                $order->same_add ='0';
            }
            if($value['discount'] ==''){
                $order->discount = 0;
            }else{
                $order->discount = $value['discount'];
            }
            if($value['shipping_cost'] ==''){
                $order->shipping_cost = 0;
            }else{
                $order->shipping_cost = $value['shipping_cost'];
            }
            $order->total = $value['order_total_amount*'] ?? 0;
            $order->custom_total = $value['order_total_amount*'] ?? 0;
//            echo $value['payment_mode(cod/pre-paid)*'].'<br>';
            if($value['payment_mode(cod/pre-paid)*']=='cod' || $value['payment_mode(cod/pre-paid)*']=='c.o.d' || $value['payment_mode(cod/pre-paid)*']=='COD'){
                $order->payment_mode = '6';
//                echo 'cod-->'.'<br>';
            }else{
//                echo 'ppd'.'<br>';
                $order->payment_mode = '12';
            }
//            echo $order;die; 
            $order->vendor_order_id = $value['Order_ID*'];
            $order->channel = $value['channel(Hyloship/amazon/shopify)'] ?? 'Hyloship';
            $order->weight = $value['weight_(gms)*'];
            $order->length = $value['length_(cms)*'];
            $order->breadth = $value['breadth_(cms)*'];
            $order->height = $value['height_(cms)*'];
            $order->note = $value['note'] ?? null;
            $order->save();
            
            $order->order_id = $order->id;
            $order->save();
            $total_cost_order =0;
            for($j=0;$j<count(explode(';',$value['Product_Name*']));$j++){
                $detail = new OrderDetail();
                $detail->order_id = $order->id;
                $user_id = Auth::guard('admin')->user()->id;
                $detail->user_id = $user_id;
                $detail->name = explode(';',$value['Product_Name*'])[$j];
                $detail->code = explode(';',$value['SKU*'])[$j];
                $detail->price = explode(';',$value['Unit_Price*'])[$j];
                $detail->qty = explode(';',$value['qty*'])[$j];
                if(count(explode(';',$value['discount_type(f->flat/p->percentage)'])) ==0 || $value['discount_type(f->flat/p->percentage)'] ==''){
                    $detail->discount_type ='f';
                    $detail->discount =0;
                }else{
                    $detail->discount_type =explode(';',$value['discount_type(f->flat/p->percentage)'])[$j];
                    $detail->discount =explode(';',$value['product_discount'])[$j];
                }
                if(count(explode(';',$value['tax_percent'])) ==0 || $value['tax_percent'] ==''){
                    $detail->tax_percent =  0;
                    $detail->tax_amount =  0;
                }else{
                    $detail->tax_percent = explode(';',$value['tax_percent'])[$j];
                    $detail->tax_amount = ($detail->price*$detail->qty*intval($detail->tax_percent))/100;
                }
                if($detail->discount_type == 'f'){
                    $detail->total_price = ($detail->tax_amount + ($detail->price*$detail->qty)) - intval($detail->discount);
                }else{
                    $detail->total_price = ($detail->tax_amount + ($detail->price*$detail->qty)) - (($detail->price*$detail->qty*intval($detail->discount))/100);
                }
                $total_cost_order += $detail->total_price;
                $detail->save();
            }
            $order->total = $total_cost_order - $order->discount + $order->shipping_cost;
            $order_data = Order::where('id', $order->id)->first();
            $order_data->total = $total_cost_order - $order_data->discount + $order_data->shipping_cost;
            $order_data->custom_total = $total_cost_order - $order_data->discount + $order_data->shipping_cost;
            $order_data->save();
            }
        }
        //  echo 'done';die;
    return redirect()->route('admin.order.index')->with('success','Excel Uploaded Successfully');
   }


    public function bulkorderstore(Request $request)
    {
        try {
            $collections = importExcelOrCsv($request->file('excel'));
        } catch (\Exception $exception) {
            return back()->with('error', 'You have uploaded a wrong format file, please upload the right file.');
        }
        
        $requiredFields = [
            'Order_ID*', 'First_Name*', 'Last_Name*', 'Phone_No*', 'Address_1*', 'Country*', 'City*', 'State*', 'Pincode*',
            'Billing address is same as Shipping address(Y/N)*', 'weight_(gms)*', 'length_(cms)*', 'breadth_(cms)*', 'height_(cms)*',
            'order_total_amount*', 'payment_mode(cod/pre-paid)*', 'Product_Name*', 'SKU*', 'Unit_Price*', 'qty*'
        ];
        
        if ($request->action_id == 'update') {
            if(array_key_first($collections[0]) !='id'){
                return back()->with('error', 'You have uploaded a wrong format file, please upload the right file.');
            }
        }else{
            if(array_key_first($collections[0]) !='Order_ID*'){
                return back()->with('error', 'You have uploaded a wrong format file, please upload the right file.');
            }
        }
        if(count($collections)>230){
            return back()->with('error', 'You can upload max 220 orders at one time');
        }
        foreach ($collections as $index => $row) {

            foreach ($requiredFields as $field) {
                if (empty(trim($row[$field] ?? ''))) {
                    return back()->with('error', 'The ' . rtrim($field, '*') . ' field is missing in row ' . ($index + 1) . '. Please make sure all required fields are present.');
                }
            }

            // Check for product-related fields
            $this->validateProductFields($row);
        }

        foreach ($collections as $row) {
            if ($request->action_id == 'update') {
                $this->updateOrder($row);
            } else {
                $this->createOrder($row);
            }
        }

        return redirect()->route('admin.order.index')->with('success', 'Excel Uploaded Successfully');
    }

    private function validateProductFields($row)
    {
        $fieldsToCheck = [
            'Product_Name*' => 'Product_Name',
            'SKU*' => 'SKU',
            'Unit_Price*' => 'Unit_Price',
            'qty*' => 'qty',
        ];

        foreach ($fieldsToCheck as $field => $name) {
            if (count(explode(';', $row[$field])) == 0) {
                throw new \Exception('Please fill the ' . $name . ' field in the correct format');
            }
        }

        foreach (['discount_type(f->flat/p->percentage)', 'product_discount', 'tax_percent', 'tax_amount', 'total_Amount'] as $field) {
            if ($row[$field] != '' && count(explode(';', $row[$field])) != count(explode(';', $row['Product_Name*']))) {
                throw new \Exception('Please fill all the required fields, issue in ' . $field);
            }
        }
    }

    private function updateOrder($row)
    {   
        $orderorigial = Order::find($row['id'])->toArray();
        $order = Order::find($row['id']);
        if (!$order || !in_array(strip_tags($order->status), ['New', 'Canceled'])) {
            return;
        }

        $this->fillOrderData($order, $row);
        $order->save();
        $newdata = Order::find($row['id'])->toArray();
        
        OrderDetail::where('order_id', $row['id'])->delete();

        $totalCostOrder = $this->saveOrderDetails($order->id, $row);
        if($order->discount ==''){
            $order->discount =0;
        }
        if($order->shipping_cost ==''){
            $order->shipping_cost =0;
        }
        $order->total = $totalCostOrder - $order->discount + $order->shipping_cost;
        $order->custom_total = $order->total;
        $order->save();
        $changedFields = array_diff_assoc($newdata,$orderorigial);
        unset($changedFields['channel'],$changedFields['user_id'],$changedFields['payment_mode'], $changedFields['updated_at'], $changedFields['extra_weight_status'],$changedFields['status']);
        $oldValues = [];
        foreach ($changedFields as $key => $newValue) {
            $oldValues[$key] = $orderorigial[$key] ?? 'N/A';
        }

        createlogs('updated','order', $order->id,$changedFields,$oldValues);
    }

    private function createOrder($row)
    {
//        echo 'hhi';die;
        $lastOrder = Order::latest('id')->first();
        $orderId = $lastOrder ? sprintf('%03d', $lastOrder->id + 1) : '001';

        $order = new Order();
        $order->order_id = $orderId;
        $order->user_id = Auth::guard('admin')->user()->id;
        $this->fillOrderData($order, $row);
        $order->save();

        $totalCostOrder = $this->saveOrderDetails($order->id, $row);
        if($order->discount ==''){
            $order->discount =0;
        }
        if($order->shipping_cost ==''){
            $order->shipping_cost =0;
        }
        $order->total = $totalCostOrder - $order->discount + $order->shipping_cost;
        $order->custom_total = $order->total;
        $order->save();
        createlogs('created','order',$orderId);
    }

    private function fillOrderData(Order $order, $row)
    {
        $current_company = Auth::guard('admin')->user()->company_id;
        $fields = [
            'ship_fname' => 'First_Name*',
            'ship_lname' => 'Last_Name*',
            'ship_email' => 'Email_id',
            'ship_company' => 'Company_Name',
            'ship_phone' => 'Phone_No*',
            'ship_address' => 'Address_1*',
            'ship_address_2' => 'Address_2',
            'ship_pincode' => 'Pincode*',
            'ship_city' => 'City*',
            'ship_state' => 'State*',
            'ship_latitude' => 'latitude',
            'ship_longitude' => 'longitude',
            'ship_gstin' => 'Gstin',
            'bill_fname' => 'Billing_First_Name',
            'bill_lname' => 'Billing_Last_Name',
            'bill_company' => 'Billing_Company_Name',
            'bill_phone' => 'Billing_Phone',
            'bill_address' => 'Billing_Address_1',
            'bill_address_2' => 'Billing_Address_2',
            'bill_pincode' => 'Billing_Pincode',
            'bill_city' => 'Billing_City',
            'bill_state' => 'Billing_State',
            'bill_latitude' => 'Billing_latitude',
            'bill_longitude' => 'Billing_longitude',
            'bill_gstin' => 'Billing_Gstin',
            'e_bill_no' => 'e_bill_no'
        ];

        foreach ($fields as $attribute => $field) {
            if($field =='Pincode*'){
                $row['Pincode*'] = (int)ltrim(rtrim($row['Pincode*']));
                if($row['Pincode*'] ==0){
                    $row['Pincode*'] = null;
                }
            }
            $order->{$attribute} = $row[$field] ?? null;
        }

        $order->ship_country = $this->getCountryId($row['Country*']);
        $order->bill_country = $this->getCountryId($row['Billing_Country']);
        $order->same_add = $this->isSameAddress($row['Billing address is same as Shipping address(Y/N)*']);
        $order->discount = $row['discount'] ?? 0;
        $order->shipping_cost = $row['shipping_cost'] ?? 0;
        $order->total = $row['order_total_amount*'] ?? 0;
        $order->custom_total = $row['order_total_amount*'] ?? 0;
        $order->payment_mode = $this->getPaymentMode($row['payment_mode(cod/pre-paid)*']);
        $order->vendor_order_id = $row['Order_ID*'];
        $order->channel = $row['channel(Hyloship/amazon/shopify)'] ?? 'Hyloship';
        $order->weight = $row['weight_(gms)*'];
        $order->length = $row['length_(cms)*'];
        $order->breadth = $row['breadth_(cms)*'];
        $order->height = $row['height_(cms)*'];
        $order->note = $row['note'] ?? null;
        $order->company_id = $current_company;
    }

    private function getCountryId($countryName)
    {
        $country = Country::where('name', $countryName)->first();
        return $country ? $country->id : '101';
    }

    private function isSameAddress($value)
    {
        return in_array(strtolower($value), ['y', 'yes', '1']) ? '1' : '0';
    }

    private function getPaymentMode($value)
    {
        return in_array(strtolower($value), ['cod', 'c.o.d', 'c.o.d']) ? '6' : '12';
    }

    private function saveOrderDetails($orderId, $row)
    {
        $totalCostOrder = 0;
        $products = count(explode(';', $row['Product_Name*']));
        $current_company = Auth::guard('admin')->user()->company_id;
        for ($i = 0; $i < $products; $i++) {
            $detail = new OrderDetail();
            $detail->order_id = $orderId;
            $detail->user_id = Auth::guard('admin')->user()->id;
            $detail->name = explode(';', $row['Product_Name*'])[$i];
            $detail->code = explode(';', $row['SKU*'])[$i];
            $detail->price = explode(';', $row['Unit_Price*'])[$i];
            $detail->qty = explode(';', $row['qty*'])[$i];
            $detail->company_id = $current_company;

            $detail->discount_type = $this->getDetailDiscountType($row, $i);
            $detail->discount = $this->getDetailDiscount($row, $i);
            $detail->tax_percent = $this->getDetailTaxPercent($row, $i);
            $detail->tax_amount = ($detail->price * $detail->qty * $detail->tax_percent) / 100;
            $detail->total_price = $this->calculateTotalPrice($detail);

            $totalCostOrder += $detail->total_price;
            $detail->save();
        }

        return $totalCostOrder;
    }

    private function getDetailDiscountType($row, $index)
    {
        return empty($row['discount_type(f->flat/p->percentage)']) ? 'f' : explode(';', $row['discount_type(f->flat/p->percentage)'])[$index];
    }

    private function getDetailDiscount($row, $index)
    {
        return empty($row['product_discount']) ? 0 : explode(';', $row['product_discount'])[$index];
    }

    private function getDetailTaxPercent($row, $index)
    {
        return empty($row['tax_percent']) ? 0 : explode(';', $row['tax_percent'])[$index];
    }

    private function calculateTotalPrice($detail)
    {
        $discountAmount = $detail->discount_type == 'f' ? $detail->discount : ($detail->price * $detail->qty * $detail->discount) / 100;
        return ($detail->price * $detail->qty) - $discountAmount + $detail->tax_amount;
    }




//order shipment checking...............................
    public function ship(Request $request)
    {
        if (request()->has('debug_ship')) {
            dd($request);
        }
        $id = $request->trackid;
        $carrier = $request->carrier;
        $user_id = Auth::guard('admin')->user()->id;
        $order = Order::with('detail')->findOrFail($id);
        $warehouse = Warehouse::where('user_id',$user_id)->first();
        foreach($order->detail as $key => $row)
            {
            $name = $row->name;
            $code = $row->code;
            $price = $row->price ?? 0;
            $discount = $row->discount ?? 0;
            $qty = $row->qty ?? 0;
            $discount_type = $row->discount_type ?? 0;
            $tax_percent = $row->tax_percent ?? 0;
            $tax_amount = $row->tax_amount ?? 0;
            $total_price = $row->total_price ?? 0;
            }

        if($carrier == 3){
            //token generation 
        $apiEndpoint = 'https://shipment.xpressbees.com/api/users/login'; 
        $headers = [
            'Accept' => 'application/json',
        ];
        $requestData = [
            'email' => 'Jaidebox01@gmail.com',
            'password' => 'Jaidebox@123'
        ];

        $response = Http::withHeaders($headers)->post($apiEndpoint, $requestData);

        $apiKey = json_decode($response->body())->data;
        // dd($apiKey);
        //order shipment
        $apiEndpoint = 'https://shipment.xpressbees.com/api/shipments2'; 
        $headers = [
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json',
        ];

        $requestData = [
                "order_number"=>$order->id,
                "shipping_charges"=> $order->shipping_cost,
                "discount"=> $order->discount,
                "cod_charges"=> 35,
                "payment_type"=>"cod",
                "order_amount"=> $price,
                "package_weight"=> $order->weight,
                "package_length"=> $order->length,
                "package_breadth"=> $order->breadth,
                "package_height"=> $order->height,
                "request_auto_pickup" =>"yes",
                "consignee"=> [
                "name"=>$order->ship_fname,
                "address"=>$order->ship_address,
                "address_2"=>$order->ship_address_2,
                "city"=>$order->ship_city,
                "state"=>$order->ship_state,
                "pincode"=>$order->ship_pincode,
                "phone"=>$order->ship_phone
        ],
                "pickup"=> [
                "warehouse_name"=>$warehouse->name,
                "name" =>$order->ship_fname,
                "address"=>$order->ship_address,
                "address_2"=>$order->ship_address_2,
                "city"=>$order->ship_city,
                "state"=>$order->ship_state,
                "pincode"=>$order->ship_pincode,
                "phone"=>$order->ship_phone
        ],
                "order_items"=> [
                [
                "name"=>$name,
                "qty"=>$qty,
                "price"=>$price,
                "sku"=>$order->vendor_order_id
                ]
                ],
                "courier_id" =>$carrier,
                "collectable_amount"=>$price
            ];
                
        // dd($requestData);
        $response = Http::withHeaders($headers)->post($apiEndpoint, $requestData);
        if (request()->has('debug_ship')) {
            dd(json_decode($response->body()));
        }

        if ($response->successful()) {
            if (request()->has('debug_ship')) {
                dd($response);
            }
            $responseData = json_decode($response->body())->data;
            return $responseData;
        }
    }
        if($carrier == 4){

        //token generation 
        $apiEndpoint = 'https://qaapis.delcaper.com/auth/login'; 
        $headers = [
            'Accept' => 'application/json',
        ];
        $requestData = [
            'email' => 'jaidebox01@gmail.com',
            "password" => "Delcaper@123",
            "vendorType" => "SELLER"
        ];
        $response = Http::withHeaders($headers)->post($apiEndpoint, $requestData);
        $apiKey = (json_decode($response->body())->data)->accessToken;
        // dd($apiKey);
        //order shipment
        $apiEndpoint = 'https://qaapis.delcaper.com/fulfillment/public/seller/order/push-order'; 
        $headers = [
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json',
        ];

        $requestData = [
                "orderId"=> $order->order_id,
                "orderNumber"=> $order->id,
                "orderCreatedAt"=> $order->created_at,
                "currency"=> "INR",
                "amount"=> $order->total,
                "weight"=> $order->weight,
                "lineItems"=> [
                [
                "name"=>$name,
                "price"=>$total_price,
                "weight"=>$order->weight,
                "quantity"=>$qty,
                "sku"=>$order->vendor_order_id,
                "unitPrice"=>$price
                ],
                [
                "name"=>$name,
                "price"=>$total_price,
                "weight"=>$order->weight,
                "quantity"=>$qty,
                "sku"=>$order->vendor_order_id,
                "unitPrice"=>$price
                ]
                ],
                "paymentType"=> "COD",
                "paymentStatus"=> "PENDING",
                "remarks"=> "handle with care",
                "shippingAddress"=>[
                "name"=> $order->ship_fname,
                "email"=> $order->ship_email,
                "phone"=> $order->ship_phone,
                "address1"=> $order->ship_address, 
                "address2"=> $order->ship_address_2,
                "city"=> $order->ship_city,
                "state"=> $order->ship_state,
                "country"=> $order->ship_country,
                "zip"=> $order->ship_pincode,
                "latitude"=> $order->ship_latitude,
                "longitude"=> $order->ship_longitude
                ],
                "pickupAddress"=>[
                "name"=> $order->bill_fname,
                "email"=> $order->bill_email,
                "phone"=> $order->bill_phone,
                "address1"=> $order->bill_address,
                "address2"=> $order->bill_address_2,
                "city"=> $order->bill_city,
                "state"=> $order->bill_state,
                "country"=> $order->bill_country,
                "zip"=> $order->bill_pincode,
                "latitude"=> $order->bill_latitude,
                "longitude"=> $order->bill_longitude
                ],
                "deliveryPromise"=>"90_MIN_DELIVERY" ,
                "returnableOrder"=> true,
                "channelCode"=> "mynewstore",
                "type"=> "HYPERLOCAL",
                "length"=> $order->length,
                "height"=> $order->height,
                "width"=> $order->breadth,
                "orderSubtype"=>"FORWARD"
            ];
                
        
        $response = Http::withHeaders($headers)->post($apiEndpoint, $requestData);
        if (request()->has('debug_ship')) {
            dd(json_decode($response->body()));
        }

        if ($response->successful()) {
            if (request()->has('debug_ship')) {
                dd($response);
            }
            $responseData = json_decode($response->body())->data;
            return $responseData;
        }
    }

    }



    public function cod()
    {
        $user = Auth::guard('admin')->user();
        $reductionfee = $reduction = $total = $paid =$pending =$next= 0;
        
        $selected_user_id = null;
        if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] !== '') {
            $selected_user_id = $_REQUEST['user_id'];
        }

        if($user->role_id =='1'){
            $order_q = Order::where('orders.cod_status', 'success')
                ->whereNotNull('orders.remittance_id')
                ->where('orders.remittance_id', '>', 0);
            if($selected_user_id !== null){
                $order_q->where('orders.user_id', $selected_user_id);
            }
            $order_q = $order_q
                ->join('admins', 'orders.user_id', '=', 'admins.id')
                ->join('remittances', 'remittances.id', '=', 'orders.remittance_id');
            $order = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id', 'remittances.id as rem_id')->get();
    
            $allusers = Admin::where('delete_status',0)->get();  
        }elseif($user->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user->id);
            $order_q = Order::where('orders.cod_status', 'success')
                ->whereNotNull('orders.remittance_id')
                ->where('orders.remittance_id', '>', 0)
                ->whereIn('orders.user_id',$sub_user_id);
            if($selected_user_id !== null){
                $order_q->where('orders.user_id', $selected_user_id);
            }
            $order_q = $order_q
                ->join('admins', 'orders.user_id', '=', 'admins.id')
                ->join('remittances', 'remittances.id', '=', 'orders.remittance_id');
            $order = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id', 'remittances.id as rem_id')->get();
    
            $allusers = Admin::where('delete_status',0)
            ->whereIn('id',$sub_user_id)
            ->get(); 
        }else{
            $selected_user_id = $user->id;
            $order_q = Order::where('orders.cod_status', 'success')
                ->whereNotNull('orders.remittance_id')
                ->where('orders.remittance_id', '>', 0)
                ->where('orders.user_id',$user->id);
            $order_q = $order_q
                ->join('admins', 'orders.user_id', '=', 'admins.id')
                ->join('remittances', 'remittances.id', '=', 'orders.remittance_id');
            $order = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id', 'remittances.id as rem_id')->get();
    
            $allusers = Admin::where('delete_status',0)
            ->where('id',$user->id)
            ->get();
        }
        foreach($order as $o):
            $total =$total + $o->total;
            if($o->cod_status ==  'success'){
                $paid = $paid + $o->total; 
            }
            if($o->cod_status ==  'pending'){
                $pending = $pending + $o->total;
                if(date('Y-m-d')>=date('Y-m-d', strtotime($o->delivered_date . ' +15 days'))){
                    $next += $o->total;
                }
            }
        endforeach;  
    $currentdate = \Carbon\Carbon::today()->format('Y-m-d h:m:s');
    $re_data = $_REQUEST;
    if (!isset($re_data['user_id'])) {
        $re_data['user_id'] = $selected_user_id;
    }
    $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
    
    $lastremitance_q = DB::table('admins')
        ->select(DB::raw("sum(last_remit_amount) as lastlemamount"));
    if($selected_user_id !== null){
        $lastremitance_q->where('id', $selected_user_id);
    } elseif ($user->role_id == '2') {
        $lastremitance_q->whereIn('id', $sub_user_id);
    }
    $lastremitance = $lastremitance_q->get();
    
    return view('admin.order.remlist',compact('order','user','couriers','pending','paid','total','next','allusers','re_data','lastremitance'));
    }
    
    public function codrem()
    {
        $ts = strtotime(date('Y-m-d'));
        $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
        $ordextra = array();
        $weekarray = array(date('Y-m-d', $start),
                 date('Y-m-d', strtotime('next saturday', $start)));
        $user = Auth::guard('admin')->user();
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $reductionfee = $reduction = $total = $paid =$pending =$next=$shipping=$rto=$extra_weight_rto=$extra_weight=0;
        if($user->role_id =='1'){
            $order_q = Order::where('status',3)->where('payment_mode',6);
            if(isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id'])){
            
            }else{
                $_REQUEST['user_id'] =array('1');
            }
            $order_q->whereIN('user_id', $_REQUEST['user_id']);

            if(isset($_REQUEST['start_date']) && !empty($_REQUEST['start_date'])){

            }else{
                $_REQUEST['start_date'] = $weekarray[0];
                $_REQUEST['end_date'] = $weekarray[1];
            }
            $order_q->where('delivered_date','>=', $_REQUEST['start_date'].'  00:00:01');
            $order_q->where('delivered_date','<=', $_REQUEST['end_date'].'  23:59:59');

            if(isset($_REQUEST['codstatus']) && $_REQUEST['codstatus'] !='all'){
                $order_q->where('cod_status', $_REQUEST['codstatus']);
            }else{
                $_REQUEST['codstatus'] ='all';
            }
            $order_q = $order_q->leftJoin('admins', 'orders.user_id', '=', 'admins.id');
            $order = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id')->get();
    
            
            $allusers = Admin::where('delete_status',0)->get();  

            //for rto and extraweight and extraweightRemitance

            // $ordextra_q = Order::join('admins', 'orders.user_id', '=', 'admins.id')
            // ->join('transactions','transactions.order_id','=','orders.id')
            // ->whereIn('transactions.remarks',array('Amount Debit for RTO','Amount Debit for extra weight','Amount Debit for extra weight - RTO'))
            // ->where('transactions.created_at','>=', $_REQUEST['start_date'].'  00:00:01')
            // ->where('transactions.created_at','<=', $_REQUEST['end_date'].'  23:59:59')
            // ->whereIN('transactions.user_id', $_REQUEST['user_id']);
            // $ordextra = $ordextra_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id','transactions.remarks','transactions.debit')->get();
        }elseif($user->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user->id);
            $order_q = Order::where('status',3)->where('payment_mode',6)->whereIn('user_id',$sub_user_id);
            if(isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id'])){
            
            }else{
                $_REQUEST['user_id'] =array($user->id);
            }
            $order_q->where('user_id', $_REQUEST['user_id']);

            if(isset($_REQUEST['start_date']) && !empty($_REQUEST['start_date'])){

            }else{
                $_REQUEST['start_date'] = $weekarray[0];
                $_REQUEST['end_date'] = $weekarray[1];
            }
            $order_q->where('delivered_date','>=', $_REQUEST['start_date'].'  00:00:01');
            $order_q->where('delivered_date','<=', $_REQUEST['end_date'].'  23:59:59');

            if(isset($_REQUEST['codstatus']) && $_REQUEST['codstatus'] !='all'){
                $order_q->where('cod_status', $_REQUEST['codstatus']);
            }else{
                $_REQUEST['codstatus'] ='all';
            }
            $order_q = $order_q->leftJoin('admins', 'orders.user_id', '=', 'admins.id');
            $order = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id')->get();
    
            // $order = $order_q->get();
           
            $allusers = Admin::where('delete_status',0)
            ->whereIn('id',$sub_user_id)
            ->get(); 

            
            //for rto and extraweight and extraweightRemitance

            // $ordextra_q = Order::join('admins', 'orders.user_id', '=', 'admins.id')
            // ->join('transactions','transactions.order_id','=','orders.id')
            // ->whereIn('transactions.remarks',array('Amount Debit for RTO','Amount Debit for extra weight','Amount Debit for extra weight - RTO'))
            // ->where('transactions.created_at','>=', $_REQUEST['start_date'].'  00:00:01')
            // ->where('transactions.created_at','<=', $_REQUEST['end_date'].'  23:59:59')
            // ->whereIN('transactions.user_id', $_REQUEST['user_id']);
            // $ordextra = $ordextra_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id','transactions.remarks','transactions.debit')->get();
        }else{
            $order_q = Order::where('status',3)->where('payment_mode',6)->where('user_id',$user->id);
            

            if(isset($_REQUEST['start_date']) && !empty($_REQUEST['start_date'])){

            }else{
                $_REQUEST['start_date'] = $weekarray[0];
                $_REQUEST['end_date'] = $weekarray[1];
            }
            $order_q->where('delivered_date','>=', $_REQUEST['start_date'].'  00:00:01');
            $order_q->where('delivered_date','<=', $_REQUEST['end_date'].'  23:59:59');
            
            
            $order_q = $order_q->leftJoin('admins', 'orders.user_id', '=', 'admins.id');
            $order = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id')->get();
    
            
            $_REQUEST['user_id'] =array($user->id);
            $allusers = Admin::where('delete_status',0)
            ->where('id',$user->id)
            ->get();

            
            
            //for rto and extraweight and extraweightRemitance

            // $ordextra_q = Order::join('admins', 'orders.user_id', '=', 'admins.id')
            // ->join('transactions','transactions.order_id','=','orders.id')
            // ->whereIn('transactions.remarks',array('Amount Debit for RTO','Amount Debit for extra weight','Amount Debit for extra weight - RTO'))
            // ->where('transactions.created_at','>=', $_REQUEST['start_date'].'  00:00:01')
            // ->where('transactions.created_at','<=', $_REQUEST['end_date'].'  23:59:59')
            // ->where('transactions.user_id', $user->id);
            // $ordextra = $ordextra_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id','transactions.remarks','transactions.debit')->get();
        }
        $row = array();
        foreach($order as $o):
            $total =$total + $o->total;
            // $shipping = $shipping + $o->freight +$o->gst_freight;
            $row[$o->tracking_info]['order_id'] = $o->vendor_order_id;
            $row[$o->tracking_info]['od_id'] = $o->id;
            $row[$o->tracking_info]['sellerid'] = $o->use_id;
            $row[$o->tracking_info]['seller'] = $o->name;
            $row[$o->tracking_info]['courier'] = $couriers[$o->ship_courier_id]['name'];
            $row[$o->tracking_info]['d_date'] = \Carbon\Carbon::parse($o->delivered_date)->format('d/m/Y');
            $row[$o->tracking_info]['total'] = $o->total;
            $row[$o->tracking_info]['shipping'] = 0;
            // $row[$o->tracking_info]['shipping'] = $o->freight +$o->gst_freight;
            $row[$o->tracking_info]['rto'] = 0;
            $row[$o->tracking_info]['extra_weight'] = 0;
            $row[$o->tracking_info]['extra_weight_rto'] = 0;
            $row[$o->tracking_info]['status'] = $o->status;
            $row[$o->tracking_info]['start_date'] = $_REQUEST['start_date'];
            $row[$o->tracking_info]['end_date'] = $_REQUEST['end_date'];
            if($o->cod_status != 'success'){
                $row[$o->tracking_info]['payment_status'] = 'Pending';
            }else{
                $row[$o->tracking_info]['payment_status'] = 'Paid';
            }
            
        endforeach;  
        foreach ($ordextra as $o){
            if (!is_object($o)) continue;
            if($o->remarks =='Amount Debit for RTO'){
                $rto = $rto+ $o->debit;
            }
            if($o->remarks =='Amount Debit for extra weight'){
                $extra_weight = $extra_weight + $o->debit;
            }
            if($o->remarks =='Amount Debit for extra weight - RTO'){
                $extra_weight_rto = $extra_weight_rto + $o->debit;
            }
            $row[$o->tracking_info]['order_id'] = $o->vendor_order_id;
            $row[$o->tracking_info]['od_id'] = $o->id;
            $row[$o->tracking_info]['sellerid'] = $o->use_id;
            $row[$o->tracking_info]['seller'] = $o->name;
            $row[$o->tracking_info]['courier'] = $couriers[$o->ship_courier_id]['name'];
            $row[$o->tracking_info]['d_date'] = \Carbon\Carbon::parse($o->delivered_date)->format('d/m/Y');
            if(!isset($row[$o->tracking_info]['total']) || $row[$o->tracking_info]['total'] == 0){
                $row[$o->tracking_info]['total'] = 0;
            }
            if(!isset($row[$o->tracking_info]['shipping']) || $row[$o->tracking_info]['shipping'] == 0){
                $row[$o->tracking_info]['shipping'] = 0;
            }
            if(!isset($row[$o->tracking_info]['rto'])){ $row[$o->tracking_info]['rto']= 0;}
            if($o->remarks =='Amount Debit for RTO'){
                $row[$o->tracking_info]['rto'] = $o->debit;
            }
            if(!isset($row[$o->tracking_info]['extra_weight'])){ $row[$o->tracking_info]['extra_weight']= 0;}
            if($o->remarks =='Amount Debit for extra weight'){
                $row[$o->tracking_info]['extra_weight'] = $o->debit;
            }
            if(!isset($row[$o->tracking_info]['extra_weight_rto'])){ $row[$o->tracking_info]['extra_weight_rto']= 0;}
            if($o->remarks =='Amount Debit for extra weight - RTO'){
                $row[$o->tracking_info]['extra_weight_rto'] = $o->debit;
            }
            $row[$o->tracking_info]['status'] = $o->status;
            $row[$o->tracking_info]['start_date'] = $_REQUEST['start_date'];
            $row[$o->tracking_info]['end_date'] = $_REQUEST['end_date'];
            if(!isset($row[$o->tracking_info]['payment_status'])){$row[$o->tracking_info]['payment_status'] = '';}
                
        }
    $re_data = $_REQUEST;
    return view('admin.order.codrem',compact('order','user','couriers','pending','paid','total','next','allusers','re_data','ordextra','shipping','rto','extra_weight','extra_weight_rto','row'));
    }
    
    public function codsum(){
        $ts = strtotime(date('Y-m-d'));
        $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
        $weekarray = array(date('Y-m-d', $start),
                 date('Y-m-d', strtotime('next saturday', $start)));
        $user = Auth::guard('admin')->user();
        $row = $ordextra = array();
        if(isset($_REQUEST['start_date']) && !empty($_REQUEST['start_date'])){}
        else{
                $_REQUEST['start_date'] = $weekarray[0];
                $_REQUEST['end_date'] = date('Y-m-d');
            }    
        if($user->role_id =='1'){
            $order_q = Order::where('status',3)->where('payment_mode',6);
            if(isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id'])){
            
            }else{
                $_REQUEST['user_id'] =array('1');
            }
            $order_q->whereIN('user_id', $_REQUEST['user_id']);

            
            $order_q->where('delivered_date','>=', $_REQUEST['start_date'].' 00:00:01');
            $order_q->where('delivered_date','<=', $_REQUEST['end_date'].' 23:59:59');

            if(isset($_REQUEST['codstatus']) && $_REQUEST['codstatus'] !='all'){
                $order_q->where('cod_status', $_REQUEST['codstatus']);
            }else{
                $_REQUEST['codstatus'] ='all';
            }
            $order_q = $order_q->leftJoin('admins', 'orders.user_id', '=', 'admins.id');
            $order = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id', 'admins.wallet_blc', 'admins.userpayment_type')->get();
    
            
            $allusers = Admin::where('delete_status',0)->get();  

            //for rto and extraweight and extraweightRemitance

            // $ordextra_q = Order::join('admins', 'orders.user_id', '=', 'admins.id')
            // ->join('transactions','transactions.order_id','=','orders.id')
            // ->whereIn('transactions.remarks',array('Amount Debit for RTO','Amount Debit for extra weight','Amount Debit for extra weight - RTO'))
            // ->where('transactions.created_at','>=', $_REQUEST['start_date'].'  00:00:01')
            // ->where('transactions.created_at','<=', $_REQUEST['end_date'].'  23:59:59')
            // ->whereIN('transactions.user_id', $_REQUEST['user_id']);
            // $ordextra = $ordextra_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id','transactions.remarks','transactions.debit')->get();
            
        }elseif($user->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user->id);
            $order_q = Order::where('status',3)->where('payment_mode',6)->whereIn('user_id',$sub_user_id);
            if(isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id'])){
            
            }else{
                $_REQUEST['user_id'] =array($user->id);
            }
            $order_q->where('user_id', $_REQUEST['user_id']);

            if(isset($_REQUEST['start_date']) && !empty($_REQUEST['start_date'])){

            }else{
                $_REQUEST['start_date'] = $weekarray[0];
                $_REQUEST['end_date'] = $weekarray[1];
            }
            $order_q->where('delivered_date','>=', $_REQUEST['start_date'].'  00:00:01');
            $order_q->where('delivered_date','<=', $_REQUEST['end_date'].'  23:59:59');

            if(isset($_REQUEST['codstatus']) && $_REQUEST['codstatus'] !='all'){
                $order_q->where('cod_status', $_REQUEST['codstatus']);
            }else{
                $_REQUEST['codstatus'] ='all';
            }
            $order_q = $order_q->leftJoin('admins', 'orders.user_id', '=', 'admins.id');
            $order = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id', 'admins.wallet_blc', 'admins.userpayment_type')->get();
    
            // $order = $order_q->get();
           
            $allusers = Admin::where('delete_status',0)
            ->whereIn('id',$sub_user_id)
            ->get(); 

            
            //for rto and extraweight and extraweightRemitance

            // $ordextra_q = Order::join('admins', 'orders.user_id', '=', 'admins.id')
            // ->join('transactions','transactions.order_id','=','orders.id')
            // ->whereIn('transactions.remarks',array('Amount Debit for RTO','Amount Debit for extra weight','Amount Debit for extra weight - RTO'))
            // ->where('transactions.created_at','>=', $_REQUEST['start_date'].'  00:00:01')
            // ->where('transactions.created_at','<=', $_REQUEST['end_date'].'  23:59:59')
            // ->whereIN('transactions.user_id', $_REQUEST['user_id']);
            // $ordextra = $ordextra_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id','transactions.remarks','transactions.debit', 'admins.userpayment_type')->get();
        }else{
            $order_q = Order::where('status',3)->where('payment_mode',6)->where('user_id',$user->id);
            

            if(isset($_REQUEST['start_date']) && !empty($_REQUEST['start_date'])){

            }else{
                $_REQUEST['start_date'] = $weekarray[0];
                $_REQUEST['end_date'] = $weekarray[1];
            }
            $order_q->where('delivered_date','>=', $_REQUEST['start_date'].'  00:00:01');
            $order_q->where('delivered_date','<=', $_REQUEST['end_date'].'  23:59:59');
            
            
            $order_q = $order_q->leftJoin('admins', 'orders.user_id', '=', 'admins.id');
            $order = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id', 'admins.wallet_blc', 'admins.userpayment_type')->get();
    
            
            $_REQUEST['user_id'] =array($user->id);
            $allusers = Admin::where('delete_status',0)
            ->where('id',$user->id)
            ->get();

            
            
            //for rto and extraweight and extraweightRemitance

            // $ordextra_q = Order::join('admins', 'orders.user_id', '=', 'admins.id')
            // ->join('transactions','transactions.order_id','=','orders.id')
            // ->whereIn('transactions.remarks',array('Amount Debit for RTO','Amount Debit for extra weight','Amount Debit for extra weight - RTO'))
            // ->where('transactions.created_at','>=', $_REQUEST['start_date'].'  00:00:01')
            // ->where('transactions.created_at','<=', $_REQUEST['end_date'].'  23:59:59')
            // ->where('transactions.user_id', $user->id);
            // $ordextra = $ordextra_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id','transactions.remarks','transactions.debit', 'admins.userpayment_type')->get();
        }
        foreach ($order as $or){
                
                $row[$or->use_id]['seller'] = $or->user_id;
                $row[$or->use_id]['sellertype'] = $or->userpayment_type;
                $row[$or->use_id]['start_date'] = $_REQUEST['start_date'];
                $row[$or->use_id]['end_date'] = $_REQUEST['end_date'];
                if(isset($row[$or->use_id]['total_re'])){
                    $row[$or->use_id]['total_re'] = $row[$or->use_id]['total_re'] + $or->total;
                }else{
                    $row[$or->use_id]['total_re'] = $or->total;
                }
                if(isset($row[$or->use_id]['shipping'])){
                    $row[$or->use_id]['shipping'] = $row[$or->use_id]['shipping'] + $or->freight +$or->gst_freight;
                }else{
                    $row[$or->use_id]['shipping'] = $or->freight +$or->gst_freight;
                }
                $row[$or->use_id]['rto'] = 0;
                $row[$or->use_id]['extra_weight'] = 0;
                $row[$or->use_id]['extra_weight_rto'] = 0;
                $row[$or->use_id]['wallet_blc'] = $or->wallet_blc;
                $row[$or->use_id]['datasum'] = $or->use_id.'_'.$row[$or->use_id]['end_date'].'_'.$row[$or->use_id]['start_date'];
            }
        foreach ($ordextra as $or){
                if (!is_object($or)) continue;
                $row[$or->use_id]['seller'] = $or->user_id;
                $row[$or->use_id]['start_date'] = $_REQUEST['start_date'];
                $row[$or->use_id]['end_date'] = $_REQUEST['end_date'];
                if(!isset($row[$or->use_id]['total_re'])){$row[$or->use_id]['total_re'] = 0;}
                if(!isset($row[$or->use_id]['shipping'])){$row[$or->use_id]['shipping'] = 0;}
                if(isset($row[$or->use_id]['rto'])){
                    if($or->remarks =='Amount Debit for RTO'){
                        $row[$or->use_id]['rto'] = $row[$or->use_id]['rto'] + $or->debit;
                    }
                }else{
                    if($or->remarks =='Amount Debit for RTO'){
                        $row[$or->use_id]['rto'] = $or->debit;
                    }else{
                        $row[$or->use_id]['rto'] = 0;
                    }
                }
                if(isset($row[$or->use_id]['extra_weight'])){
                    if($or->remarks =='Amount Debit for extra weight'){
                        $row[$or->use_id]['extra_weight'] = $row[$or->use_id]['extra_weight'] + $or->debit;
                    }
                }else{
                    if($or->remarks =='Amount Debit for extra weight'){
                        $row[$or->use_id]['extra_weight'] = $or->debit;
                    }else{
                        $row[$or->use_id]['extra_weight'] = 0;
                    }
                }
                if(isset($row[$or->use_id]['extra_weight_rto'])){
                    if($or->remarks =='Amount Debit for extra weight - RTO'){
                        $row[$or->use_id]['extra_weight_rto'] = $row[$or->use_id]['extra_weight_rto'] + $or->debit;
                    }
                }else{
                    if($or->remarks =='Amount Debit for extra weight - RTO'){
                        $row[$or->use_id]['extra_weight_rto'] = $or->debit;
                    }else{
                        $row[$or->use_id]['extra_weight_rto'] = 0;
                    }
                }
                $row[$or->use_id]['datasum'] = $or->use_id.'_'.$row[$or->use_id]['end_date'].'_'.$row[$or->use_id]['start_date'];
            }
        $re_data = $_REQUEST;
        return view('admin.order.codsum',compact('row','re_data','user','allusers'));
    }
    
    public function codawb(){
//        echo '<pre>';print_R($_REQUEST);die;
        $ordextra = array();
        $order_q = Order::where('status',3)->where('payment_mode',6);
            
            $order_q->where('tracking_info', $_REQUEST['awb']);
            $order_q->where('delivered_date','>=', $_REQUEST['start_date'].' 00:00:01');
            $order_q->where('delivered_date','<=', $_REQUEST['end_date'].' 23:59:59');

            if(isset($_REQUEST['codstatus']) && $_REQUEST['codstatus'] !='all'){
                $order_q->where('cod_status', $_REQUEST['codstatus']);
            }else{
                $_REQUEST['codstatus'] ='all';
            }
            $order_q = $order_q->leftJoin('admins', 'orders.user_id', '=', 'admins.id');
            $order = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id')->get();
    
            // $ordextra_q = Order::join('admins', 'orders.user_id', '=', 'admins.id')
            // ->join('transactions','transactions.order_id','=','orders.id')
            // ->whereIn('transactions.remarks',array('Amount Debit for RTO','Amount Debit for extra weight','Amount Debit for extra weight - RTO'))
            // ->where('transactions.created_at','>=', $_REQUEST['start_date'].'  00:00:01')
            // ->where('transactions.created_at','<=', $_REQUEST['end_date'].'  23:59:59')
            // ->where('transactions.awb', $_REQUEST['awb']);
            // $ordextra = $ordextra_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id','transactions.remarks','transactions.debit','transactions.created_at as t_date')->get();
        return view('admin.order.codawb',compact('order','ordextra'));
    }

    public function remlist(){
        return $this->cod();
    }
    
    public function remview($rem_id) {
        $user = Auth::guard('admin')->user();
        $current_company = $user->company_id;
        $orders = order::where('status',3)->where('payment_mode',6)->where('remittance_id',$rem_id)->where('company_id',$current_company)->get();
        $total =$orders->sum('cod_amount');
        $countorder =$orders->count();
        return view('admin.order.remview',compact('orders','total','countorder'));
    }
    
    public function saverem(Request $request, $id = 0){
        $recharge = floatval($request->recharge);
        $paid = floatval($request->paid);
        $cod_amount = floatval($request->cod_amount);
        $shipping_amount = floatval($request->shipping_amount);
//       echo gettype(floatval($paid+$recharge)).'=>'.floatval($paid+$recharge).'<br>'.gettype(floatval($cod_amount-$shipping_amount)).'->'.floatval($cod_amount-$shipping_amount);die;
        if(floatval($paid+$recharge) > floatval($cod_amount-$shipping_amount)){
            return Redirect()->back()->with('error', 'Amount not correct');
        }else{
           $orders = order::where('remittance_id',$id)->where('cod_status','!=','success')->get();
           foreach($orders as $order){
                $order->cod_status = 'success';
                $order->cod_date = now();
                $order->save();
           }
           $remitance = Remittance::find($id);
           $user_id = $remitance->user_id;
           $current_company = $remitance->company_id;
           $remitance->paid = $paid;
           $remitance->recharge = $recharge;
           $remitance->utr = $request->utr;
           $remitance->status = 'success';
           $remitance->save();

           $useddata = admin::find($user_id);
           $update_blc =$useddata->wallet_blc + $recharge;
           

            $transaction = new Transaction();
            $transaction->order_id = 0;
            $transaction->user_id = $user_id;
            $transaction->company_id = $current_company;
            $transaction->awb = null;
            $transaction->tracking_info = '';
            $transaction->credit = $recharge;
            $transaction->closing_blc = $update_blc;
            $transaction->debit = '0.00';
            $transaction->remarks = "Amount Credit against Remitance id: ".$id;
            $transaction->save();

            $useddata->wallet_blc = $update_blc;
            $useddata->last_remit_amount = $request->paid + $request->recharge;
            $useddata->save();

            return Redirect()->back()->with('success', 'Data updated');
        }
        // echo '<pre>';print_R($request);die;
    }
    
    public function codweekly(){
         $user = Auth::guard('admin')->user();
//         echo $user;die;
//         $strtweek = get_currentweekno($user->created_at);
         $currentweeknu = get_currentweekno(date('Y-m-d'));
         $ordextra = array();
         for($j=$currentweeknu;$j>0;$j--){
             $row[$j]['total']=0;
             $row[$j]['shipping']=0;
             $row[$j]['rto']=0;
             $row[$j]['extra_weight']=0;
             $row[$j]['extra_weight_rto']=0;
             
            $strtenddate = getstartenddateofweek(($j),date('Y'));
            $_REQUEST['start_date'] = $strtenddate['week_start'];
            $_REQUEST['end_date'] = $strtenddate['week_end'];
            $row[$j]['start_date']=$strtenddate['week_start'];
            $row[$j]['end_date']=$strtenddate['week_end'];
            $order_q = Order::where('status',3)->where('payment_mode',6)->where('user_id',$user->id);
            
            $order_q->where('delivered_date','>=', $_REQUEST['start_date'].'  00:00:01');
            $order_q->where('delivered_date','<=', $_REQUEST['end_date'].'  23:59:59');
            
            
            $order_q = $order_q->join('admins', 'orders.user_id', '=', 'admins.id');
            $order = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id')->get();
            $row[$j]['total'] = $order->sum('total');
            $row[$j]['shipping'] =$order->sum('freight') +$order->sum('gst_freight');
            
            $_REQUEST['user_id'] =array($user->id);
            $allusers = Admin::where('delete_status',0)
            ->where('id',$user->id)
            ->get();

            
            
            //for rto and extraweight and extraweightRemitance

            // $ordextra_q = Order::join('admins', 'orders.user_id', '=', 'admins.id')
            // ->join('transactions','transactions.order_id','=','orders.id')
            // ->whereIn('transactions.remarks',array('Amount Debit for RTO','Amount Debit for extra weight','Amount Debit for extra weight - RTO'))
            // ->where('transactions.created_at','>=', $_REQUEST['start_date'].'  00:00:01')
            // ->where('transactions.created_at','<=', $_REQUEST['end_date'].'  23:59:59')
            // ->where('transactions.user_id', $user->id);
            // $ordextra = $ordextra_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id','transactions.remarks','transactions.debit')->get();
            foreach ($ordextra as $or){
                if (!is_object($or)) continue;
                if($or->remarks =='Amount Debit for RTO'){
                    $row[$j]['rto'] = $row[$j]['rto'] + $or->debit;
                }

                if($or->remarks =='Amount Debit for extra weight'){
                    $row[$j]['extra_weight'] = $row[$j]['extra_weight'] + $or->debit;
                }

                if($or->remarks =='Amount Debit for extra weight - RTO'){
                    $row[$j]['extra_weight_rto'] = $row[$j]['extra_weight_rto'] + $or->debit;
                }

            }
//            echo $row[$j]['total'].'-->'.$row[$j]['shipping'].'-->'.$row[$j]['rto'].'-->'.$row[$j]['extra_weight'].'--->'.$j.'<br>'; 
         
        }
        return view('admin.order.codweekly',compact('row','user'));
    }
    public function markpaid($id){
//        $order = Order::where('id', $id)->first();
//        
//        $rem_amount =($order->total- ($order->freight + $order->gst_freight));
//        $remittance = new Remittance();
//        $remittance->order_id = $id; //for multiple orderid use ', ' in between order_Id
//        $remittance->user_id = $order->user_id;
//        $remittance->amount = $rem_amount;
//        $remittance->updated_at = now();
//        $remittance->created_at = now();
//        $remittance->save();
//        $remittance_id = $remittance->id;
//
//        $order->cod_amount = $rem_amount;
//        $order->cod_status = 'success';
//        $order->cod_date = now();
//        $order->remittance_id = $remittance_id;
//        $order->save();
//        return redirect()->route('cod')->with('success', 'Marked Paid Successfully!');
    }

    public function paid(){
        
        if(request()->status == 'paid'){
            $rem_order_array = array();
            foreach(request()->id as $id){
                $getuser = DB::table('orders')
                ->select('user_id as user','cod_status')
                ->where('id','=',$id)
                ->first();
                if($getuser->cod_status == 'success'){}else{
                    $rem_order_array[$getuser->user]['user'] = $getuser->user;
                    $rem_order_array[$getuser->user]['order'][] = $id;
                }
            }
            if(!empty($rem_order_array)){
                foreach($rem_order_array as $or){
                    
                    $remittance = new Remittance();
                    $remittance->order_id = implode(', ',$or['order']); //for multiple orderid use ', ' in between order_Id
                    $remittance->user_id = $or['user'];
                    // $remittance->amount = $rem_amount;
                    $remittance->updated_at = now();
                    $remittance->created_at = now();
                    $remittance->company_id = Admin::find($or['user'])->company_id ?? 1;
                    $remittance->save();
                    
                    $remittance_id = $remittance->id;
                    $totalrem =0;
                    foreach($or['order'] as $eachord){
                        $order = Order::where('id', $eachord)->first();
                        $rem_amount =($order->total);
                        $totalrem = $totalrem +$rem_amount;
                        $order->cod_amount = $rem_amount;
                        $order->cod_status = 'success';
                        $order->cod_date = now();
                        $order->remittance_id = $remittance_id;
                        $order->save();
                    }
                    $userd = Admin::where('id', $or['user'])->first();
                    $userd->last_remit_amount = $totalrem;
                    $userd->save();
                }
            }
            

        }
        return redirect('admin/cod');
    }

    public function updateremittance(Request $request)
    {
        $user = Auth::guard('admin')->user();
        if ($user->role_id != '1') {
            return back()->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'cod_paid_amount' => 'required|numeric',
        ]);

        $order = Order::findOrFail($request->order_id);
        $order->cod_paid_amount = $request->cod_paid_amount;
        
        if ($request->filled('cod_transaction_date')) {
            $order->cod_transaction_date = \Carbon\Carbon::parse($request->cod_transaction_date)->toDateTimeString();
        } else {
            $order->cod_transaction_date = null;
        }
        
        $order->cod_utr = $request->cod_utr;
        $order->cod_remark = $request->cod_remark;

        if ($request->filled('cod_status')) {
            $order->cod_status = $request->cod_status;
            if ($order->remittance_id && ($remittance = Remittance::find($order->remittance_id))) {
                $remittance->status = $request->cod_status;
                $remittance->paid = $request->cod_paid_amount;
                $remittance->utr = $request->cod_utr;
                $remittance->save();
            }
        }
        $order->save();

        return back()->with('success', 'Remittance updated successfully.');
    }

    function codcreate(){
        return view('admin.order.codcreate');
    }

    public function downloadCodPaidFormat(){
        $file = public_path('cod_paid.csv');
        if (!file_exists($file)) {
            $file = base_path('cod_paid.csv');
        }
        if (file_exists($file)) {
            return response()->download($file, 'cod_paid.csv', [
                'Content-Type' => 'text/csv',
            ]);
        }
        return back()->with('error', 'Sample file not found.');
    }
    
    public function storecod(Request $request){
        try {
            $collections = importExcelOrCsv($request->file('excel'));
        } catch (\Exception $exception) {
            return back()->with('error', 'You have uploaded a wrong format file, please upload the right file.');
        }
        $rem_order_array = array();
        $row_by_awb = array();
        $processed_awbs = array();
        foreach ($collections as $row) {
            $awbKey = null;
            $paidAmountKey = null;
            $transactionDateKey = null;
            $utrKey = null;
            $remarkKey = null;
            $statusKey = null;
            
            foreach ($row as $key => $value) {
                $lowerKey = strtolower(trim($key));
                if ($lowerKey === 'awb') {
                    $awbKey = $key;
                } elseif ($lowerKey === 'paid amount' || $lowerKey === 'paid_amount' || $lowerKey === 'paidamount') {
                    $paidAmountKey = $key;
                } elseif (in_array($lowerKey, ['trangection date', 'trangection_date', 'transaction date', 'transaction_date', 'date'])) {
                    $transactionDateKey = $key;
                } elseif ($lowerKey === 'utr') {
                    $utrKey = $key;
                } elseif ($lowerKey === 'remark' || $lowerKey === 'remarks') {
                    $remarkKey = $key;
                } elseif (in_array($lowerKey, ['status', 'cod_status', 'cod status', 'payment_status', 'payment status'])) {
                    $statusKey = $key;
                }
            }
            
            if (!$awbKey || empty($row[$awbKey])) {
                continue;
            }
            
            $awb = ltrim(rtrim($row[$awbKey]));
            if (in_array($awb, $processed_awbs)) {
                continue;
            }
            $processed_awbs[] = $awb;
            
            $getuser = DB::table('orders')
            ->select('user_id as user','cod_status','id','remittance_id')
            ->where('tracking_info','=',$awb)
            ->first();
            
            if ($getuser) {
                $rem_order_array[$getuser->user]['user'] = $getuser->user;
                $rem_order_array[$getuser->user]['order'][] = $getuser->id;
                
                $row_by_awb[$getuser->id] = [
                    'paid_amount' => $paidAmountKey ? trim($row[$paidAmountKey]) : null,
                    'transaction_date' => $transactionDateKey ? trim($row[$transactionDateKey]) : null,
                    'utr' => $utrKey ? trim($row[$utrKey]) : null,
                    'remark' => $remarkKey ? trim($row[$remarkKey]) : null,
                    'status' => $statusKey ? trim($row[$statusKey]) : null,
                ];
            }
        }
        if(!empty($rem_order_array)){
            foreach($rem_order_array as $or){
                $total_paid_in_group = 0;
                
                foreach($or['order'] as $eachord){
                    $order = Order::where('id', $eachord)->first();
                    $rem_amount = ($order->total);
                    
                    $rawPaidVal = isset($row_by_awb[$eachord]['paid_amount']) ? trim($row_by_awb[$eachord]['paid_amount']) : '';
                    if (is_numeric($rawPaidVal)) {
                        $paidVal = floatval($rawPaidVal);
                    } else {
                        $paidVal = $rem_amount;
                    }
                    
                    $utrVal = isset($row_by_awb[$eachord]['utr']) ? trim($row_by_awb[$eachord]['utr']) : null;
                    if ($utrVal === '-') {
                        $utrVal = null;
                    }
                    
                    $rawStatus = isset($row_by_awb[$eachord]['status']) ? strtolower(trim($row_by_awb[$eachord]['status'])) : '';
                    if (in_array($rawStatus, ['pending', 'unpaid', 'hold', 'process', 'processing'])) {
                        $finalStatus = 'pending';
                    } else {
                        $finalStatus = 'success';
                    }
                    
                    if ($order->remittance_id && ($remittance = Remittance::find($order->remittance_id))) {
                        $remittance->paid = $paidVal;
                        $remittance->cod_amount = $rem_amount;
                        $remittance->amount = $rem_amount;
                        $remittance->status = $finalStatus;
                        $remittance->utr = $utrVal;
                        $remittance->updated_at = now();
                        $remittance->save();
                        $remittance_id = $remittance->id;
                    } else {
                        $remittance = new Remittance();
                        $remittance->order_id = $eachord;
                        $remittance->user_id = $or['user'];
                        $remittance->updated_at = now();
                        $remittance->created_at = now();
                        $remittance->company_id = Admin::find($or['user'])->company_id ?? 1;
                        $remittance->paid = $paidVal;
                        $remittance->recharge = 0;
                        $remittance->cod_amount = $rem_amount;
                        $remittance->amount = $rem_amount;
                        $remittance->status = $finalStatus;
                        $remittance->utr = $utrVal;
                        $remittance->save();
                        $remittance_id = $remittance->id;
                    }
                    
                    $order->cod_amount = $rem_amount;
                    $order->cod_status = $finalStatus;
                    $order->cod_date = now();
                    $order->remittance_id = $remittance_id;
                    if (Schema::hasColumn('orders', 'cod_paid_amount')) {
                        $order->cod_paid_amount = $paidVal;
                    }
                    
                    $rawDate = isset($row_by_awb[$eachord]['transaction_date']) ? trim($row_by_awb[$eachord]['transaction_date']) : '';
                    $parsedDate = null;
                    if (!empty($rawDate) && $rawDate !== '-') {
                        try {
                            if ($rawDate instanceof \DateTimeInterface) {
                                $parsedDate = \Carbon\Carbon::instance($rawDate);
                            } else {
                                $parsedDate = \Carbon\Carbon::parse($rawDate);
                            }
                        } catch (\Exception $e) {
                            $parsedDate = null;
                        }
                    }
                    if (Schema::hasColumn('orders', 'cod_transaction_date')) {
                        $order->cod_transaction_date = $parsedDate ? $parsedDate->toDateTimeString() : now();
                    }
                    if (Schema::hasColumn('orders', 'cod_utr')) {
                        $order->cod_utr = $utrVal;
                    }
                    
                    $remarkVal = isset($row_by_awb[$eachord]['remark']) ? trim($row_by_awb[$eachord]['remark']) : null;
                    if ($remarkVal === '-') {
                        $remarkVal = null;
                    }
                    if (Schema::hasColumn('orders', 'cod_remark')) {
                        $order->cod_remark = $remarkVal;
                    }
                    $order->save();
                    
                    $total_paid_in_group += $paidVal;
                }
                
                $userd = Admin::where('id', $or['user'])->first();
                $userd->last_remit_amount = $total_paid_in_group;
                $userd->save();
            }
        }
        return redirect()->route('admin.order.remlist')->with('success',"Updated Successfully");
    }

    function coddownload($rem_id){
        $remittance = Remittance::where('id', $rem_id)->first();
        $user_id = Auth::guard('admin')->user()->id;
        $users = Admin::all(); // Assuming you have a User model
        if($remittance->user_id == $user_id){
            $orders = Order::where('remittance_id', $rem_id)->get();
            $data = [
                'title' => 'Sample PDF Title',
                'content' => 'Sample PDF Content',
                'users' => $users, // Pass the users data to the template
            ];
    
            $general_setting = DB::table('general_settings')->where('id','1')->first();
            $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
            // Generate the PDF using the template
            /** @var \Barryvdh\DomPDF\PDF $pdf */
            $pdf = PDF::loadView('admin.pdf.codremitane',compact('data','general_setting','remittance','orders','couriers'));
    
            // Optional: You can save the PDF to a file if needed
            // $pdf->save('path/to/save/pdf.pdf');
    
            // Return the PDF as a download response
            $name ='rem_'.$rem_id;
            return $pdf->download($name.'.pdf');
        }else{
            return redirect()->route('cod')->with('error', 'No Access'); 
        }
    }
    
public function onhold()
    {
        if(request()->status == 'on_hold')
        {
//            foreach(request()->id as $id)
//            {
//                $order = Order::where('id', $id)->first();
//                $order->status = '7';
//                $order->on_hold_date = now();
//                $order->save();
//            }

          return redirect()->route('admin.order.index')->with('success', 'Orders Onhold Successfully!');
        }
    }

    public function onholdpage()
    {
        $order = Order::where('status','7')->get();
        // dd($order);
        return view('admin.order.onhold',compact('order'));
    }

    

    public function cancel()
    {   
        if(request()->status == 'cancel')
            {
                foreach(request()->id as $id)
                {
                    $order = Order::with('detail')->where('id', $id)->first();
                    $getuser = DB::table('orders')
                            ->select('user_id as user')
                            ->where('id','=',$id)
                            ->first();
                    $user_id = $orde_user_id  = $getuser->user;
                    $order_userdata = Admin::find($user_id);
                    $parent_userid = $order_userdata->parent_id;
                    $current_company_id = $order_userdata->company_id;
                    $warehouse = Warehouse::find($order['warehouse_id']);
                    $old_status =strip_tags($order['status']);
                    $coll_value=0;$money_reversal=0;
//                    echo $old_status;die;
                    if($old_status !='Delivered' && in_array($old_status,array('New','Shipped','Pickup Pending'))){
                        if($order['tracking_info'] !=''){
                            if($order['ship_courier_id'] =='1'){
                                $cancelstatus =  Integration::cancelshipment($order['tracking_info']);
                                //checking api logs
                                api_logs($order['tracking_info'],$cancelstatus,$id,'1','cancelled');
                                $cancelstatus = json_decode($cancelstatus,true);
                                if(isset($cancelstatus[0]) ){
                                    $orderd = Order::where('id', $id)->first();
                                    $money_reversal = $orderd->shipping_courier_cost;
                                    $money_reversalparent = $orderd->shipping_courier_costparent;
                                    $orderd->status = '4';
                                    $orderd->order_id = $orderd->order_id.'c';
                                    $orderd->warehouse_id = null;
                                    $orderd->picked_date = null;
                                    $orderd->rto_date = null;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->ship_courier_id = 0;
                                    $orderd->shipping_courier_cost = 0;
                                    $orderd->gst = 0;
                                    $orderd->gst_freight = 0;
                                    $orderd->gst_cod = 0;
                                    $orderd->freight = 0;
                                    $orderd->cod = 0;

                                    $orderd->shipping_courier_costparent = 0;
                                    $orderd->gstparent = 0;
                                    $orderd->gst_freightparent = 0;
                                    $orderd->gst_codparent = 0;
                                    $orderd->freightparent = 0;
                                    $orderd->codparent = 0;

                                    $orderd->shipping_courier_type = null;
                                    $orderd->shipping_courier_weight_used = null;
                                    $orderd->shipping_courier_weight = null;
                                    $orderd->zone = null;
                                    $orderd->manifest_id = 0;
                                    $orderd->tracking_info = null;
                                    $orderd->cancel_date = now();
                                    
                                    $balance = Admin::find($orde_user_id);

                                    $transaction = new Transaction();
                                    $transaction->order_id = $id;
                                    $transaction->user_id = $orde_user_id;
                                    $transaction->company_id = $current_company_id;
                                    $transaction->awb = $order['tracking_info'];
                                    $transaction->tracking_info = $order['tracking_info'];
                                    $transaction->credit = $money_reversal;
                                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                                    $transaction->debit = '0.00';
                                    $transaction->show_data = '0';
                                    $transaction->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transaction->save();

                                    $transactionparent = new Transaction();
                                    $transactionparent->order_id = $id;
                                    $transactionparent->user_id = $parent_userid;
                                    $transactionparent->company_id = $current_company_id;
                                    $transactionparent->awb = $order['tracking_info'];
                                    $transactionparent->tracking_info = $order['tracking_info'];
                                    $transactionparent->credit = $money_reversalparent;
                                    $transactionparent->debit = '0.00';
                                    $transactionparent->show_data = '0';
                                    $transactionparent->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transactionparent->parent_data = '1';
                                    $transactionparent->save();
                                    
                                    // $balance = Auth::guard('admin')->user();
                                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                                    $balance->save();
                                    
                                    $orderd->save();

                                    Status::orderstatuslog($id,$current_company_id,$old_status,'Canceled',now());
                                    
                                }
                            }
                            else if($order['ship_courier_id'] =='2'){
                                $awb_array = json_encode(
                                    array(
                                        'waybill'=>$order['tracking_info'],
                                        'cancellation'=> true
                                    ),true
                                );
                                if($order['shipping_courier_type'] =='fa-plane'){
                                    $ttype ='a';
                                }else{
                                    $ttype ='s';
                                }
                                $cancelstatus =  Integration::cancelshipment_delivary($awb_array,$ttype);
                                //checking api logs
                                api_logs($awb_array,$cancelstatus,$id,'2','cancelled');
                                $cancelstatus = json_decode($cancelstatus,true);
                                // echo '<pre>';print_R($cancelstatus);die;
                                if($cancelstatus['status']){
                                    $orderd = Order::where('id', $id)->first();
                                    $money_reversal = $orderd->shipping_courier_cost;
                                    $money_reversalparent = $orderd->shipping_courier_costparent;
                                    $orderd->status = '4';
                                    $orderd->order_id = $orderd->order_id.'c';
                                    $orderd->warehouse_id = null;
                                    $orderd->picked_date = null;
                                    $orderd->rto_date = null;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->ship_courier_id = 0;
                                    $orderd->shipping_courier_cost = 0;
                                    $orderd->gst = 0;
                                    $orderd->gst_freight = 0;
                                    $orderd->gst_cod = 0;
                                    $orderd->freight = 0;
                                    $orderd->cod = 0;
                                    $orderd->shipping_courier_costparent = 0;
                                    $orderd->gstparent = 0;
                                    $orderd->gst_freightparent = 0;
                                    $orderd->gst_codparent = 0;
                                    $orderd->freightparent = 0;
                                    $orderd->codparent = 0;
                                    $orderd->shipping_courier_type = null;
                                    $orderd->shipping_courier_weight_used = null;
                                    
                                    $orderd->zone = null;
                                    $orderd->manifest_id = 0;
                                    $orderd->tracking_info = null;
                                    $orderd->cancel_date = now();
                                    
                                    $balance = Admin::find($orde_user_id);

                                    $transaction = new Transaction();
                                    $transaction->order_id = $id;
                                    $transaction->user_id = $orde_user_id;
                                    $transaction->company_id = $current_company_id;
                                    $transaction->awb = $order['tracking_info'];
                                    $transaction->tracking_info = $order['tracking_info'];
                                    $transaction->credit = $money_reversal;
                                    $transaction->closing_blc =  $balance->wallet_blc + $money_reversal;
                                    $transaction->debit = '0.00';
                                    $transaction->show_data = '0';
                                    $transaction->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transaction->save();
                                    
                                    $transactionparent = new Transaction();
                                    $transactionparent->order_id = $id;
                                    $transactionparent->user_id = $parent_userid;
                                    $transactionparent->company_id = $current_company_id;
                                    $transactionparent->awb = $order['tracking_info'];
                                    $transactionparent->tracking_info = $order['tracking_info'];
                                    $transactionparent->credit = $money_reversalparent;
                                    $transactionparent->debit = '0.00';
                                    $transactionparent->show_data = '0';
                                    $transactionparent->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transactionparent->parent_data = '1';
                                    $transactionparent->save();
                                    
                                    // $balance = Auth::guard('admin')->user();
                                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                                    $balance->save();
                                    
                                    $orderd->save();
                                }
                            }
                            else if($order['ship_courier_id'] =='3'){
                                $canceldata = array(
                                    'awb'=>$order['tracking_info']
                                );
                                $cancelstatus =  Integration_more::cancel_bluedart(json_encode($canceldata,true));
                                //checking api logs
                                api_logs(json_encode($canceldata,true),$cancelstatus,$id,'3','cancelled');
                                
                                $cancelstatus = json_decode($cancelstatus,true);
                                
                                $canCancelLocally = false;
                                if (isset($cancelstatus['status']) && $cancelstatus['status']) {
                                    $canCancelLocally = true;
                                } else {
                                    $msg = is_array($cancelstatus['responsemsg'] ?? '') ? implode(' ', $cancelstatus['responsemsg']) : ($cancelstatus['responsemsg'] ?? '');
                                    if (stripos($msg, 'already') !== false || stripos($msg, 'cancel') !== false || stripos($msg, 'not found') !== false) {
                                        $canCancelLocally = true;
                                    }
                                }

                                if($canCancelLocally){
                                    $orderd = Order::where('id', $id)->first();
                                    $money_reversal = $orderd->shipping_courier_cost;
                                    $money_reversalparent = $orderd->shipping_courier_costparent;
                                    $orderd->status = '4';
                                    $orderd->order_id = $orderd->order_id.'c';
                                    $orderd->warehouse_id = null;
                                    $orderd->picked_date = null;
                                    $orderd->rto_date = null;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->ship_courier_id = 0;
                                    $orderd->shipping_courier_cost = 0;
                                    $orderd->gst = 0;
                                    $orderd->gst_freight = 0;
                                    $orderd->gst_cod = 0;
                                    $orderd->freight = 0;
                                    $orderd->cod = 0;
                                    $orderd->shipping_courier_costparent = 0;
                                    $orderd->gstparent = 0;
                                    $orderd->gst_freightparent = 0;
                                    $orderd->gst_codparent = 0;
                                    $orderd->freightparent = 0;
                                    $orderd->codparent = 0;
                                    $orderd->shipping_courier_type = null;
                                    $orderd->shipping_courier_weight_used = null;
                                    
                                    $orderd->zone = null;
                                    $orderd->manifest_id = 0;
                                    $orderd->tracking_info = null;
                                    $orderd->cancel_date = now();
                                    
                                    $balance = Admin::find($orde_user_id);

                                    $transaction = new Transaction();
                                    $transaction->order_id = $id;
                                    $transaction->user_id = $orde_user_id;
                                    $transaction->company_id = $current_company_id;
                                    $transaction->awb = $order['tracking_info'];
                                    $transaction->tracking_info = $order['tracking_info'];
                                    $transaction->credit = $money_reversal;
                                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                                    $transaction->debit = '0.00';
                                    $transaction->show_data = '0';
                                    $transaction->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transaction->save();
                                    
                                    $transactionparent = new Transaction();
                                    $transactionparent->order_id = $id;
                                    $transactionparent->user_id = $parent_userid;
                                    $transactionparent->company_id = $current_company_id;
                                    $transactionparent->awb = $order['tracking_info'];
                                    $transactionparent->tracking_info = $order['tracking_info'];
                                    $transactionparent->credit = $money_reversalparent;
                                    $transactionparent->debit = '0.00';
                                    $transactionparent->show_data = '0';
                                    $transactionparent->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transactionparent->parent_data = '1';
                                    $transactionparent->save();

                                    // $balance = Auth::guard('admin')->user();
                                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                                    $balance->save();
                                    
                                    $orderd->save();
                                }else{
                                    $msg = is_array($cancelstatus['responsemsg'] ?? '') ? implode(' ', $cancelstatus['responsemsg']) : ($cancelstatus['responsemsg'] ?? 'Unknown Error');
                                    return [
                                        'status' => 0,
                                        'message' => $msg,
                                    ];
                                }
                            }
                            else if($order['ship_courier_id'] =='4'){
                                $awb_array = json_encode(
                                    array(
                                        'ShippingID'=>$order['tracking_info'],
                                        'CancellationReason'=>'Cancel order'
                                    ),true
                                );
                                $cancelstatus =  Integration::cancelshipment_xbess($awb_array);
                                //checking api logs
                                api_logs($awb_array,$cancelstatus,$id,'4','cancelled');
                                $cancelstatus = json_decode($cancelstatus,true);
                                
                                $canCancelLocally = false;
                                if(isset($cancelstatus['ReturnCode']) && $cancelstatus['ReturnCode'] =='100'){
                                    $canCancelLocally = true;
                                } elseif (isset($cancelstatus['ReturnMessage']) && (stripos($cancelstatus['ReturnMessage'], 'notified') !== false || stripos($cancelstatus['ReturnMessage'], 'already') !== false)) {
                                    // Treat "Already notified" or "Already cancelled" as success for local state cleanup
                                    $canCancelLocally = true;
                                }

                                if($canCancelLocally){
                                    $orderd = Order::where('id', $id)->first();
                                    $money_reversal = $orderd->shipping_courier_cost;
                                    $money_reversalparent = $orderd->shipping_courier_costparent;
                                    $orderd->status = '4';
                                    $orderd->order_id = $orderd->order_id.'c';
                                    $orderd->warehouse_id = null;
                                    $orderd->picked_date = null;
                                    $orderd->rto_date = null;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->ship_courier_id = 0;
                                    $orderd->shipping_courier_cost = 0;
                                    $orderd->gst = 0;
                                    $orderd->gst_freight = 0;
                                    $orderd->gst_cod = 0;
                                    $orderd->freight = 0;
                                    $orderd->cod = 0;
                                    $orderd->shipping_courier_costparent = 0;
                                    $orderd->gstparent = 0;
                                    $orderd->gst_freightparent = 0;
                                    $orderd->gst_codparent = 0;
                                    $orderd->freightparent = 0;
                                    $orderd->codparent = 0;
                                    $orderd->shipping_courier_type = null;
                                    $orderd->shipping_courier_weight_used = null;
                                    
                                    $orderd->zone = null;
                                    $orderd->manifest_id = 0;
                                    $orderd->tracking_info = null;
                                    $orderd->cancel_date = now();
                                    
                                    $balance = Admin::find($orde_user_id);

                                    $transaction = new Transaction();
                                    $transaction->order_id = $id;
                                    $transaction->user_id = $orde_user_id;
                                    $transaction->company_id = $current_company_id;
                                    $transaction->awb = $order['tracking_info'];
                                    $transaction->tracking_info = $order['tracking_info'];
                                    $transaction->credit = $money_reversal;
                                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                                    $transaction->debit = '0.00';
                                    $transaction->show_data = '0';
                                    $transaction->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transaction->save();
                                    
                                    $transactionparent = new Transaction();
                                    $transactionparent->order_id = $id;
                                    $transactionparent->user_id = $parent_userid;
                                    $transactionparent->company_id = $current_company_id;
                                    $transactionparent->awb = $order['tracking_info'];
                                    $transactionparent->tracking_info = $order['tracking_info'];
                                    $transactionparent->credit = $money_reversalparent;
                                    $transactionparent->debit = '0.00';
                                    $transactionparent->show_data = '0';
                                    $transactionparent->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transactionparent->parent_data = '1';
                                    $transactionparent->save();
                                    
                                    // $balance = Auth::guard('admin')->user();
                                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                                    $balance->save();
                                    
                                    $orderd->save();
                                }else{
                                    return redirect()->back()->with('error', $cancelstatus['ReturnMessage']);
                                }
                            }
                            else if($order['ship_courier_id'] =='5'){
                                $awb_array = json_encode(
                                    array(
                                        'AWBNo'=>array(
                                            $order['tracking_info']
                                        ),
                                        'customerCode'=> 'GL7569'
                                    ),true
                                );
                                $canceldtdc =  Integration::cancelshipment_dtdc($awb_array);
                                //checking api logs
                                api_logs($awb_array,$canceldtdc,$id,'5','cancelled');
                                $canceldtdc = json_decode($canceldtdc,true);
                                if($canceldtdc['success']){
                                    $orderd = Order::where('id', $id)->first();
                                    $money_reversal = $orderd->shipping_courier_cost;
                                    $money_reversalparent = $orderd->shipping_courier_costparent;
                                    $orderd->status = '4';
                                    $orderd->order_id = $orderd->order_id.'c';
                                    $orderd->warehouse_id = null;
                                    $orderd->picked_date = null;
                                    $orderd->rto_date = null;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->ship_courier_id = 0;
                                    $orderd->shipping_courier_cost = 0;
                                    $orderd->gst = 0;
                                    $orderd->gst_freight = 0;
                                    $orderd->gst_cod = 0;
                                    $orderd->freight = 0;
                                    $orderd->cod = 0;
                                    $orderd->shipping_courier_costparent = 0;
                                    $orderd->gstparent = 0;
                                    $orderd->gst_freightparent = 0;
                                    $orderd->gst_codparent = 0;
                                    $orderd->freightparent = 0;
                                    $orderd->codparent = 0;
                                    $orderd->shipping_courier_type = null;
                                    $orderd->shipping_courier_weight_used = null;
                                    
                                    $orderd->zone = null;
                                    $orderd->manifest_id = 0;
                                    $orderd->tracking_info = null;
                                    $orderd->cancel_date = now();
                                    
                                    $balance = Admin::find($orde_user_id);

                                    $transaction = new Transaction();
                                    $transaction->order_id = $id;
                                    $transaction->user_id = $orde_user_id;
                                    $transaction->company_id = $current_company_id;
                                    $transaction->awb = $order['tracking_info'];
                                    $transaction->tracking_info = $order['tracking_info'];
                                    $transaction->credit = $money_reversal;
                                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                                    $transaction->debit = '0.00';
                                    $transaction->show_data = '0';
                                    $transaction->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transaction->save();
                                    
                                    $transactionparent = new Transaction();
                                    $transactionparent->order_id = $id;
                                    $transactionparent->user_id = $parent_userid;
                                    $transactionparent->company_id = $current_company_id;
                                    $transactionparent->awb = $order['tracking_info'];
                                    $transactionparent->tracking_info = $order['tracking_info'];
                                    $transactionparent->credit = $money_reversalparent;
                                    $transactionparent->debit = '0.00';
                                    $transactionparent->show_data = '0';
                                    $transactionparent->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transactionparent->parent_data = '1';
                                    $transactionparent->save();
                                    
                                    // $balance = Auth::guard('admin')->user();
                                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                                    $balance->save();
                                    
                                    $orderd->save();
                                }else{
                                    if(isset($canceldtdc['failures'][0])){
                                            return redirect()->route('admin.order.all')->with('error', $canceldtdc['failures'][0]['message']);
                                        }
                                }
                            }
                            else if($order['ship_courier_id'] =='6'){
                                if(strip_tags($order['shipping_courier_type']) =='fa-truck'){
                                    $awb_array = json_encode(
                                        array(
                                            array('waybillNumber'=>array(
                                                $order['tracking_info']
                                            )),
                                        ),true
                                    );
                                    $type='cod';
                                }else{
                                    $awb_array = json_encode(
                                        array(
                                            'awbs'=>array(
                                                $order['tracking_info']
                                            ),
                                        ),true
                                    );
                                    $type='ppd';
                                }
                                $cancelsmartr =  Integration::cancelshipment_smartr($awb_array,$type);
                                //checking api logs
                                api_logs($awb_array,$cancelsmartr,$id,'6','cancelled');
                                $cancelsmartr = json_decode($cancelsmartr,true);
                                if($type=='ppd'){ // for air
                                    if($cancelsmartr['data'] && isset($cancelsmartr['data'][0]) && $cancelsmartr['data'][0]['success']){
                                        $orderd = Order::where('id', $id)->first();
                                        $money_reversal = $orderd->shipping_courier_cost;
                                        $money_reversalparent = $orderd->shipping_courier_costparent;
                                        $orderd->status = '4';
                                        $orderd->order_id = $orderd->order_id.'c';
                                        $orderd->warehouse_id = null;
                                        $orderd->picked_date = null;
                                        $orderd->rto_date = null;
                                        $orderd->return_warehouse_id = 0;
                                        $orderd->return_warehouse_id = 0;
                                        $orderd->ship_courier_id = 0;
                                        $orderd->shipping_courier_cost = 0;
                                        $orderd->gst = 0;
                                        $orderd->gst_freight = 0;
                                        $orderd->gst_cod = 0;
                                        $orderd->freight = 0;
                                        $orderd->cod = 0;
                                        $orderd->shipping_courier_costparent = 0;
                                        $orderd->gstparent = 0;
                                        $orderd->gst_freightparent = 0;
                                        $orderd->gst_codparent = 0;
                                        $orderd->freightparent = 0;
                                        $orderd->codparent = 0;
                                        $orderd->shipping_courier_type = null;
                                        $orderd->shipping_courier_weight_used = null;
                                        
                                        $orderd->zone = null;
                                        $orderd->manifest_id = 0;
                                        $orderd->tracking_info = null;
                                        $orderd->cancel_date = now();

                                        $balance = Admin::find($orde_user_id);
                                        
                                        $transaction = new Transaction();
                                        $transaction->order_id = $id;
                                        $transaction->user_id = $orde_user_id;
                                        $transaction->company_id = $current_company_id;
                                        $transaction->awb = $order['tracking_info'];
                                        $transaction->tracking_info = $order['tracking_info'];
                                        $transaction->credit = $money_reversal;
                                        $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                                        $transaction->debit = '0.00';
                                        $transaction->show_data = '0';
                                    $transaction->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                        $transaction->save();
                                        
                                        $transactionparent = new Transaction();
                                        $transactionparent->order_id = $id;
                                        $transactionparent->user_id = $parent_userid;
                                        $transactionparent->company_id = $current_company_id;
                                        $transactionparent->awb = $order['tracking_info'];
                                        $transactionparent->tracking_info = $order['tracking_info'];
                                        $transactionparent->credit = $money_reversalparent;
                                        $transactionparent->debit = '0.00';
                                        $transactionparent->show_data = '0';
                                    $transactionparent->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                        $transactionparent->parent_data = '1';
                                        $transactionparent->save();
                                        
                                        // $balance = Auth::guard('admin')->user();
                                        $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                                        $balance->save();
                                        
                                        $orderd->save();
                                    }else{
                                        if(isset($cancelsmartr['data'][0])){
                                            return redirect()->route('admin.order.all')->with('error', $cancelsmartr['data'][0]['message']);
                                        }
                                    }
                                }else{ // for surface
                                    if(isset($cancelsmartr[0]) && $cancelsmartr[0]['success']){
                                        $orderd = Order::where('id', $id)->first();
                                        $money_reversal = $orderd->shipping_courier_cost;
                                        $money_reversalparent = $orderd->shipping_courier_costparent;
                                        $orderd->status = '4';
                                        $orderd->order_id = $orderd->order_id.'c';
                                        $orderd->warehouse_id = null;
                                        $orderd->picked_date = null;
                                        $orderd->rto_date = null;
                                        $orderd->return_warehouse_id = 0;
                                        $orderd->return_warehouse_id = 0;
                                        $orderd->ship_courier_id = 0;
                                        $orderd->shipping_courier_cost = 0;
                                        $orderd->gst = 0;
                                        $orderd->gst_freight = 0;
                                        $orderd->gst_cod = 0;
                                        $orderd->freight = 0;
                                        $orderd->cod = 0;
                                        $orderd->shipping_courier_costparent = 0;
                                        $orderd->gstparent = 0;
                                        $orderd->gst_freightparent = 0;
                                        $orderd->gst_codparent = 0;
                                        $orderd->freightparent = 0;
                                        $orderd->codparent = 0;
                                        $orderd->shipping_courier_type = null;
                                        $orderd->shipping_courier_weight_used = null;
                                        
                                        $orderd->zone = null;
                                        $orderd->manifest_id = 0;
                                        $orderd->tracking_info = null;
                                        $orderd->cancel_date = now();

                                        $balance = Admin::find($orde_user_id);
                                        
                                        $transaction = new Transaction();
                                        $transaction->order_id = $id;
                                        $transaction->user_id = $orde_user_id;
                                        $transaction->company_id = $current_company_id;
                                        $transaction->awb = $order['tracking_info'];
                                        $transaction->tracking_info = $order['tracking_info'];
                                        $transaction->credit = $money_reversal;
                                        $transaction->debit = '0.00';
                                        $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                                        $transaction->show_data = '0';
                                    $transaction->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                        $transaction->save();
                                        
                                        $transactionparent = new Transaction();
                                        $transactionparent->order_id = $id;
                                        $transactionparent->user_id = $parent_userid;
                                        $transactionparent->company_id = $current_company_id;
                                        $transactionparent->awb = $order['tracking_info'];
                                        $transactionparent->tracking_info = $order['tracking_info'];
                                        $transactionparent->credit = $money_reversalparent;
                                        $transactionparent->debit = '0.00';
                                        $transactionparent->show_data = '0';
                                    $transactionparent->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                        $transactionparent->parent_data = '1';
                                        $transactionparent->save();
                                        
                                        // $balance = Auth::guard('admin')->user();
                                        $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                                        $balance->save();
                                        
                                        $orderd->save();
                                    }else{
                                        return redirect()->route('admin.order.all')->with('error', $cancelsmartr[0]['message']);
                                    }
                                }    
                            }
                            else if($order['ship_courier_id'] =='7'){
                                
                                $cancelekart =  Integration_more::cancelshipment_ekart($order['tracking_info']);
                                //checking api logs
                                api_logs('',$cancelekart,$id,'7','cancelled');
                                $cancelekart = json_decode($cancelekart,true);
//                                echo '<pre>';print_R($cancelekart);die;
                                if(isset($cancelekart['status'])){
                                    $orderd = Order::where('id', $id)->first();
                                    $money_reversal = $orderd->shipping_courier_cost;
                                    $money_reversalparent = $orderd->shipping_courier_costparent;
                                    $orderd->status = '4';
                                    $orderd->order_id = $orderd->order_id.'c';
                                    $orderd->warehouse_id = null;
                                    $orderd->picked_date = null;
                                    $orderd->rto_date = null;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->ship_courier_id = 0;
                                    $orderd->shipping_courier_cost = 0;
                                    $orderd->gst = 0;
                                    $orderd->gst_freight = 0;
                                    $orderd->gst_cod = 0;
                                    $orderd->freight = 0;
                                    $orderd->cod = 0;
                                    $orderd->shipping_courier_costparent = 0;
                                    $orderd->gstparent = 0;
                                    $orderd->gst_freightparent = 0;
                                    $orderd->gst_codparent = 0;
                                    $orderd->freightparent = 0;
                                    $orderd->codparent = 0;
                                    $orderd->shipping_courier_type = null;
                                    $orderd->shipping_courier_weight_used = null;
                                    
                                    $orderd->zone = null;
                                    $orderd->manifest_id = 0;
                                    $orderd->tracking_info = null;
                                    $orderd->cancel_date = now();

                                    $balance = Admin::find($orde_user_id);
                                    
                                    $transaction = new Transaction();
                                    $transaction->order_id = $id;
                                    $transaction->user_id = $orde_user_id;
                                    $transaction->company_id = $current_company_id;
                                    $transaction->awb = $order['tracking_info'];
                                    $transaction->tracking_info = $order['tracking_info'];
                                    $transaction->credit = $money_reversal;
                                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                                    $transaction->debit = '0.00';
                                    $transaction->show_data = '0';
                                    $transaction->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transaction->save();
                                    
                                    $transactionparent = new Transaction();
                                    $transactionparent->order_id = $id;
                                    $transactionparent->user_id = $parent_userid;
                                    $transactionparent->company_id = $current_company_id;
                                    $transactionparent->awb = $order['tracking_info'];
                                    $transactionparent->tracking_info = $order['tracking_info'];
                                    $transactionparent->credit = $money_reversalparent;
                                    $transactionparent->debit = '0.00';
                                    $transactionparent->show_data = '0';
                                    $transactionparent->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transactionparent->parent_data = '1';
                                    $transactionparent->save();
                                    
                                    // $balance = Auth::guard('admin')->user();
                                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                                    $balance->save();
                                    
                                    $orderd->save();
                                }else{
                                    if(isset($cancelekart['description']) ){
                                        return redirect()->back()->with('error', $cancelekart['description']);
                                    }else{
                                        return redirect()->back()->with('error', 'Ekart not responding in cancel');
                                    }
                                }
                                // echo '<pre>';print_R($cancelekart);die;
                                
                            }
                            else if($order['ship_courier_id'] =='8'){
                                if($order->reverse_order =='1'){
                                    $type ='backward';
                                }else{
                                    $type ='forward';
                                }
                                $ord_Array = array(
                                    'request_id'=>$order->tracking_info,
                                    'cancel_remarks'=>'Cancel the order',
                                );
                                $cancelshadowfax =  Integration_more::cancelshipment_shadowfax($type,json_encode($ord_Array));
                                //checking api logs
                                api_logs(json_encode($ord_Array),$cancelshadowfax,$id,'8','cancelled');
                                $cancelshadowfax = json_decode($cancelshadowfax,true);
                                if(isset($cancelshadowfax['responseCode']) && ($cancelshadowfax['responseCode'] =='200' || $cancelshadowfax['responseMsg'] =='Request is queued for cancellation')){
                                    $orderd = Order::where('id', $id)->first();
                                    $money_reversal = $orderd->shipping_courier_cost;
                                    $money_reversalparent = $orderd->shipping_courier_costparent;
                                    $orderd->status = '4';
                                    $orderd->order_id = $orderd->order_id.'c';
                                    $orderd->warehouse_id = null;
                                    $orderd->picked_date = null;
                                    $orderd->rto_date = null;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->ship_courier_id = 0;
                                    $orderd->shipping_courier_cost = 0;
                                    $orderd->gst = 0;
                                    $orderd->gst_freight = 0;
                                    $orderd->gst_cod = 0;
                                    $orderd->freight = 0;
                                    $orderd->cod = 0;
                                    $orderd->shipping_courier_costparent = 0;
                                    $orderd->gstparent = 0;
                                    $orderd->gst_freightparent = 0;
                                    $orderd->gst_codparent = 0;
                                    $orderd->freightparent = 0;
                                    $orderd->codparent = 0;
                                    $orderd->shipping_courier_type = null;
                                    $orderd->shipping_courier_weight_used = null;
                                    
                                    $orderd->zone = null;
                                    $orderd->manifest_id = 0;
                                    $orderd->tracking_info = null;
                                    $orderd->cancel_date = now();

                                    $balance = Admin::find($orde_user_id);
                                    
                                    $transaction = new Transaction();
                                    $transaction->order_id = $id;
                                    $transaction->user_id = $orde_user_id;
                                    $transaction->company_id = $current_company_id;
                                    $transaction->awb = $order['tracking_info'];
                                    $transaction->tracking_info = $order['tracking_info'];
                                    $transaction->credit = $money_reversal;
                                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                                    $transaction->debit = '0.00';
                                    $transaction->show_data = '0';
                                    $transaction->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transaction->save();
                                    
                                    $transactionparent = new Transaction();
                                    $transactionparent->order_id = $id;
                                    $transactionparent->user_id = $parent_userid;
                                    $transactionparent->company_id = $current_company_id;
                                    $transactionparent->awb = $order['tracking_info'];
                                    $transactionparent->tracking_info = $order['tracking_info'];
                                    $transactionparent->credit = $money_reversalparent;
                                    $transactionparent->debit = '0.00';
                                    $transactionparent->show_data = '0';
                                    $transactionparent->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transactionparent->parent_data = '1';
                                    $transactionparent->save();
                                    
                                    // $balance = Auth::guard('admin')->user();
                                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                                    $balance->save();
                                    
                                    $orderd->save();
                                }
                                else{
                                    if(isset($cancelshadowfax['errorCode']) ){
                                        return redirect()->back()->with('error', $cancelshadowfax['errorCode']);
                                    }
                                    if(isset($cancelshadowfax['responseMsg']) ){
                                        return redirect()->back()->with('error', $cancelshadowfax['responseMsg']);
                                    }
                                    
                                }
                                // echo '<pre>';print_R($cancelekart);die;
                            }
                            else if($order['ship_courier_id'] =='9'){
                                $shipment_id = $order->shipment_id;
                                if($shipment_id =='' || $shipment_id == null){
                                    return redirect()->back()->with('error', 'Shipment id not found');
                                }else{
                                    $cancelats =  Integration_more::cancelshipment_ats($shipment_id);
                                    //checking api logs
                                    api_logs($shipment_id,$cancelats,$id,'9','cancelled');
                                    $cancelats = json_decode($cancelats,true);
                                    if(isset($cancelats['payload']) && empty($cancelats['payload'])){
                                        $orderd = Order::where('id', $id)->first();
                                        $money_reversal = $orderd->shipping_courier_cost;
                                        $money_reversalparent = $orderd->shipping_courier_costparent;
                                        $orderd->status = '4';
                                        $orderd->order_id = $orderd->order_id.'c';
                                        $orderd->warehouse_id = null;
                                        $orderd->picked_date = null;
                                        $orderd->rto_date = null;
                                        $orderd->return_warehouse_id = 0;
                                        $orderd->return_warehouse_id = 0;
                                        $orderd->ship_courier_id = 0;
                                        $orderd->shipping_courier_cost = 0;
                                        $orderd->gst = 0;
                                        $orderd->gst_freight = 0;
                                        $orderd->gst_cod = 0;
                                        $orderd->freight = 0;
                                        $orderd->cod = 0;
                                        $orderd->shipping_courier_costparent = 0;
                                        $orderd->gstparent = 0;
                                        $orderd->gst_freightparent = 0;
                                        $orderd->gst_codparent = 0;
                                        $orderd->freightparent = 0;
                                        $orderd->codparent = 0;
                                        $orderd->shipping_courier_type = null;
                                        $orderd->shipping_courier_weight_used = null;
                                        
                                        $orderd->shipment_id = null;
                                        $orderd->zone = null;
                                        $orderd->manifest_id = 0;
                                        $orderd->tracking_info = null;
                                        $orderd->cancel_date = now();

                                        $balance = Admin::find($orde_user_id);
                                        
                                        $transaction = new Transaction();
                                        $transaction->order_id = $id;
                                        $transaction->user_id = $orde_user_id;
                                        $transaction->company_id = $current_company_id;
                                        $transaction->awb = $order['tracking_info'];
                                        $transaction->tracking_info = $order['tracking_info'];
                                        $transaction->credit = $money_reversal;
                                        $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                                        $transaction->debit = '0.00';
                                        $transaction->show_data = '0';
                                    $transaction->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                        $transaction->save();
                                        
                                        $transactionparent = new Transaction();
                                        $transactionparent->order_id = $id;
                                        $transactionparent->user_id = $parent_userid;
                                        $transactionparent->company_id = $current_company_id;
                                        $transactionparent->awb = $order['tracking_info'];
                                        $transactionparent->tracking_info = $order['tracking_info'];
                                        $transactionparent->credit = $money_reversalparent;
                                        $transactionparent->debit = '0.00';
                                        $transactionparent->show_data = '0';
                                    $transactionparent->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                        $transactionparent->parent_data = '1';
                                        $transactionparent->save();
                                        
                                        // $balance = Auth::guard('admin')->user();
                                        $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                                        $balance->save();
                                        
                                        $orderd->save();
                                    }else{
                                        $erro = 'Something went wrong,please try after sometime';
                                        if(isset($cancelats['errors']) && isset($cancelats['errors'][0])){
                                            $erro = $cancelats['errors'][0]['details'];
                                        }
                                        return redirect()->back()->with('error', $erro);
                                        
                                    }
                                }
                                // echo '<pre>';print_R($cancelekart);die;
                            }
                            else if($order['ship_courier_id'] =='10'){
                                // $cancelstatus =  Integration_more::cancelblitz($order['tracking_info']);
                                $cancelstatus ='{"status": "success", "messages": "", "response": [{"suborder_id": 30339272, "success": true, "message": "Order Cancelled", "code": 42000}]}';
                                
                                //checking api logs
                                api_logs($order['tracking_info'],$cancelstatus,$id,'10','cancelled');
                                $cancelstatus = json_decode($cancelstatus,true);
                                // echo '<pre>';print_r($cancelstatus);die;
                                if(isset($cancelstatus['status']) && $cancelstatus['status'] =='success'){
                                    $orderd = Order::where('id', $id)->first();
                                    $money_reversal = $orderd->shipping_courier_cost;
                                    $money_reversalparent = $orderd->shipping_courier_costparent;
                                    $orderd->status = '4';
                                    $orderd->order_id = $orderd->order_id.'c';
                                    $orderd->warehouse_id = null;
                                    $orderd->picked_date = null;
                                    $orderd->rto_date = null;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->ship_courier_id = 0;
                                    $orderd->shipping_courier_cost = 0;
                                    $orderd->gst = 0;
                                    $orderd->gst_freight = 0;
                                    $orderd->gst_cod = 0;
                                    $orderd->freight = 0;
                                    $orderd->cod = 0;

                                    $orderd->shipping_courier_costparent = 0;
                                    $orderd->gstparent = 0;
                                    $orderd->gst_freightparent = 0;
                                    $orderd->gst_codparent = 0;
                                    $orderd->freightparent = 0;
                                    $orderd->codparent = 0;

                                    $orderd->shipping_courier_type = null;
                                    $orderd->shipping_courier_weight_used = null;
                                    $orderd->shipping_courier_weight = null;
                                    $orderd->zone = null;
                                    $orderd->manifest_id = 0;
                                    $orderd->tracking_info = null;
                                    $orderd->cancel_date = now();
                                    
                                    $balance = Admin::find($orde_user_id);

                                    $transaction = new Transaction();
                                    $transaction->order_id = $id;
                                    $transaction->user_id = $orde_user_id;
                                    $transaction->company_id = $current_company_id;
                                    $transaction->awb = $order['tracking_info'];
                                    $transaction->tracking_info = $order['tracking_info'];
                                    $transaction->credit = $money_reversal;
                                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                                    $transaction->debit = '0.00';
                                    $transaction->show_data = '0';
                                    $transaction->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transaction->save();

                                    $transactionparent = new Transaction();
                                    $transactionparent->order_id = $id;
                                    $transactionparent->user_id = $parent_userid;
                                    $transactionparent->company_id = $current_company_id;
                                    $transactionparent->awb = $order['tracking_info'];
                                    $transactionparent->tracking_info = $order['tracking_info'];
                                    $transactionparent->credit = $money_reversalparent;
                                    $transactionparent->debit = '0.00';
                                    $transactionparent->show_data = '0';
                                    $transactionparent->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transactionparent->parent_data = '1';
                                    $transactionparent->save();
                                    
                                    // $balance = Auth::guard('admin')->user();
                                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                                    $balance->save();
                                    
                                    $orderd->save();

                                    Status::orderstatuslog($id,$current_company_id,$old_status,'Canceled',now());
                                    
                                }
                            }
                            else if($order['ship_courier_id'] =='11'){
                                if($old_status !='Delivered' && in_array($old_status,array('New','Shipped'))){
                                    $ord_Array = array(
                                        'orderId'=>$order->order_id,
                                        'cancelReason'=>"Please cancel"
                                    );
                                    $cancelpickndel =  Integration_courier::cancelshreemaruti(json_encode($ord_Array));
//                                    $cancelpickndel =  '{"status":200,"data":"Order has been cancelled"}';
//                                    $cancelpickndel =  '{"status":400,"trace":{"name":"BadRequestException","error":{}},"message":"The order cannot be canceled as order status is CANCELLED."}';
                                    $cancelshadowfax = json_decode($cancelpickndel,true);
//                                    echo '<pre>';print_R($cancelshadowfax);die;
                                    api_logs(json_encode($ord_Array),$cancelpickndel,$id,'11','cancelled');
                                    if($cancelshadowfax['status']=='200'){
                                        $orderd = Order::where('id', $id)->first();
                                    $money_reversal = $orderd->shipping_courier_cost;
                                    $money_reversalparent = $orderd->shipping_courier_costparent;
                                    $orderd->status = '4';
                                    $orderd->order_id = $orderd->order_id.'c';
                                    $orderd->warehouse_id = null;
                                    $orderd->picked_date = null;
                                    $orderd->rto_date = null;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->ship_courier_id = 0;
                                    $orderd->shipping_courier_cost = 0;
                                    $orderd->gst = 0;
                                    $orderd->gst_freight = 0;
                                    $orderd->gst_cod = 0;
                                    $orderd->freight = 0;
                                    $orderd->cod = 0;
                                    $orderd->shipping_courier_costparent = 0;
                                    $orderd->gstparent = 0;
                                    $orderd->gst_freightparent = 0;
                                    $orderd->gst_codparent = 0;
                                    $orderd->freightparent = 0;
                                    $orderd->codparent = 0;
                                    $orderd->shipping_courier_type = null;
                                    $orderd->shipping_courier_weight_used = null;
                                    
                                    $orderd->zone = null;
                                    $orderd->manifest_id = 0;
                                    $orderd->tracking_info = null;
                                    $orderd->cancel_date = now();

                                    $balance = Admin::find($orde_user_id);
                                    
                                    $transaction = new Transaction();
                                    $transaction->order_id = $id;
                                    $transaction->user_id = $orde_user_id;
                                    $transaction->company_id = $current_company_id;
                                    $transaction->awb = $order['tracking_info'];
                                    $transaction->tracking_info = $order['tracking_info'];
                                    $transaction->credit = $money_reversal;
                                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                                    $transaction->debit = '0.00';
                                    $transaction->show_data = '0';
                                    $transaction->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transaction->save();
                                    
                                    $transactionparent = new Transaction();
                                    $transactionparent->order_id = $id;
                                    $transactionparent->user_id = $parent_userid;
                                    $transactionparent->company_id = $current_company_id;
                                    $transactionparent->awb = $order['tracking_info'];
                                    $transactionparent->tracking_info = $order['tracking_info'];
                                    $transactionparent->credit = $money_reversalparent;
                                    $transactionparent->debit = '0.00';
                                    $transactionparent->show_data = '0';
                                    $transactionparent->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transactionparent->parent_data = '1';
                                    $transactionparent->save();
                                    
                                    // $balance = Auth::guard('admin')->user();
                                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                                    $balance->save();
                                    
                                    $orderd->save();
                                    }else{
                                        $e_msg ='Issue in data';
                                        if(isset($cancelshadowfax['message'])){
                                            $e_msg =$cancelshadowfax['message'][0];
                                        }
                                         return redirect()->back()->with('error', $e_msg);
                                       
                                    }
                                }else{
                                       return redirect()->back()->with('error', 'Order already Manifested');
                                    
                                }
                            }
                            else if($order['ship_courier_id'] =='13'){
                                $pu_integration = DB::table('integrations')
                                    ->where('courier_id', 13)
                                    ->where('user_id', $order['user_id'])
                                    ->first();
                                if (!$pu_integration) {
                                    $pu_integration = DB::table('integrations')->where('courier_id', 13)->first();
                                }
                                
                                $cancel_res = Integration::cancelshipment_parcel_uncle($order['tracking_info'], 'Order cancelled by merchant', $pu_integration);
                                api_logs(json_encode(['awb' => $order['tracking_info']]), $cancel_res, $id, '13', 'cancelled');
                                
                                $cancel_arr = json_decode($cancel_res, true);
                                if(isset($cancel_arr['success']) && $cancel_arr['success']){
                                    $orderd = Order::where('id', $id)->first();
                                    $money_reversal = $orderd->shipping_courier_cost;
                                    $money_reversalparent = $orderd->shipping_courier_costparent;
                                    $orderd->status = '4';
                                    $orderd->order_id = $orderd->order_id.'c';
                                    $orderd->warehouse_id = null;
                                    $orderd->picked_date = null;
                                    $orderd->rto_date = null;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->ship_courier_id = 0;
                                    $orderd->shipping_courier_cost = 0;
                                    $orderd->gst = 0;
                                    $orderd->gst_freight = 0;
                                    $orderd->gst_cod = 0;
                                    $orderd->freight = 0;
                                    $orderd->cod = 0;
                                    $orderd->shipping_courier_costparent = 0;
                                    $orderd->gstparent = 0;
                                    $orderd->gst_freightparent = 0;
                                    $orderd->gst_codparent = 0;
                                    $orderd->freightparent = 0;
                                    $orderd->codparent = 0;
                                    $orderd->shipping_courier_type = null;
                                    $orderd->shipping_courier_weight_used = null;
                                    
                                    $orderd->zone = null;
                                    $orderd->manifest_id = 0;
                                    $orderd->tracking_info = null;
                                    $orderd->cancel_date = now();

                                    $balance = Admin::find($orde_user_id);
                                    
                                    $transaction = new Transaction();
                                    $transaction->order_id = $id;
                                    $transaction->user_id = $orde_user_id;
                                    $transaction->company_id = $current_company_id;
                                    $transaction->awb = $order['tracking_info'];
                                    $transaction->tracking_info = $order['tracking_info'];
                                    $transaction->credit = $money_reversal;
                                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                                    $transaction->debit = '0.00';
                                    $transaction->show_data = '0';
                                    $transaction->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transaction->save();
                                    
                                    $transactionparent = new Transaction();
                                    $transactionparent->order_id = $id;
                                    $transactionparent->user_id = $parent_userid;
                                    $transactionparent->company_id = $current_company_id;
                                    $transactionparent->awb = $order['tracking_info'];
                                    $transactionparent->tracking_info = $order['tracking_info'];
                                    $transactionparent->credit = $money_reversalparent;
                                    $transactionparent->debit = '0.00';
                                    $transactionparent->show_data = '0';
                                    $transactionparent->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transactionparent->parent_data = '1';
                                    $transactionparent->save();
                                    
                                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                                    $balance->save();
                                    
                                    $orderd->save();
                                } else {
                                    $err_msg = $cancel_arr['message'] ?? 'Error cancelling order on Parcel Uncle';
                                    return redirect()->back()->with('error', $err_msg);
                                }
                            }
                            else if($order['ship_courier_id'] =='12'){
                                $ord_Array = array(
                                    'Control'=>array(
                                        'RequestId'=>"029906ee-04df-424d-8255-cc0351694ff8",
                                        'Source'=>1,
                                        "RequestTime"=>time(),
                                        "Version"=>"3.0"
                                    ),
                                    'Data'=>array(
                                        "UserId"=>7174,
                                        "Orders"=>array(
                                            array(
                                                "ClientUniqueNo"=>Auth::guard('admin')->user()->id.time(),
                                                "AWBNo"=>[$order->tracking_info],
                                            )
                                        )
                                    )
                                );
//                                echo json_encode($ord_Array);die;
                                $cancelpickndel =  Integration_more::cancelshipment_pckndel(json_encode($ord_Array));
//                                $cancelpickndel = '{"Control":{"Status":1,"Message":"Order cancellation request processed successfully.","MessageCode":200,"TimeTaken":"0.97375082969666 Second"},"Data":[{"ClientUniqueNo":"11772793628","AWBNo":"PA0638302","Valid":true,"ErrorMessage":""}]}';
//                                echo $cancelpickndel;die;
                                //checking api logs
                                api_logs(json_encode($ord_Array),$cancelpickndel,$id,'12','cancelled');
                                $cancelshadowfax = json_decode($cancelpickndel,true);
                                if(isset($cancelshadowfax['Control']) && $cancelshadowfax['Control']['Status'] ==1){
                                    $orderd = Order::where('id', $id)->first();
                                    $money_reversal = $orderd->shipping_courier_cost;
                                    $money_reversalparent = $orderd->shipping_courier_costparent;
                                    $orderd->status = '4';
                                    $orderd->order_id = $orderd->order_id.'c';
                                    $orderd->warehouse_id = null;
                                    $orderd->picked_date = null;
                                    $orderd->rto_date = null;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->return_warehouse_id = 0;
                                    $orderd->ship_courier_id = 0;
                                    $orderd->shipping_courier_cost = 0;
                                    $orderd->gst = 0;
                                    $orderd->gst_freight = 0;
                                    $orderd->gst_cod = 0;
                                    $orderd->freight = 0;
                                    $orderd->cod = 0;
                                    $orderd->shipping_courier_costparent = 0;
                                    $orderd->gstparent = 0;
                                    $orderd->gst_freightparent = 0;
                                    $orderd->gst_codparent = 0;
                                    $orderd->freightparent = 0;
                                    $orderd->codparent = 0;
                                    $orderd->shipping_courier_type = null;
                                    $orderd->shipping_courier_weight_used = null;
                                    
                                    $orderd->zone = null;
                                    $orderd->manifest_id = 0;
                                    $orderd->tracking_info = null;
                                    $orderd->cancel_date = now();

                                    $balance = Admin::find($orde_user_id);
                                    
                                    $transaction = new Transaction();
                                    $transaction->order_id = $id;
                                    $transaction->user_id = $orde_user_id;
                                    $transaction->company_id = $current_company_id;
                                    $transaction->awb = $order['tracking_info'];
                                    $transaction->tracking_info = $order['tracking_info'];
                                    $transaction->credit = $money_reversal;
                                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                                    $transaction->debit = '0.00';
                                    $transaction->show_data = '0';
                                    $transaction->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transaction->save();
                                    
                                    $transactionparent = new Transaction();
                                    $transactionparent->order_id = $id;
                                    $transactionparent->user_id = $parent_userid;
                                    $transactionparent->company_id = $current_company_id;
                                    $transactionparent->awb = $order['tracking_info'];
                                    $transactionparent->tracking_info = $order['tracking_info'];
                                    $transactionparent->credit = $money_reversalparent;
                                    $transactionparent->debit = '0.00';
                                    $transactionparent->show_data = '0';
                                    $transactionparent->remarks = "Amount Credit for cancel by ".Auth::guard('admin')->user()->name;
                                    $transactionparent->parent_data = '1';
                                    $transactionparent->save();
                                    
                                    // $balance = Auth::guard('admin')->user();
                                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                                    $balance->save();
                                    
                                    $orderd->save();
                                }
                                else{
                                    if(isset($cancelshadowfax['errorCode']) ){
                                        return redirect()->back()->with('error', $cancelshadowfax['errorCode']);
                                    }
                                    if(isset($cancelshadowfax['responseMsg']) ){
                                        return redirect()->back()->with('error', $cancelshadowfax['responseMsg']);
                                    }
                                    
                                }
                                // echo '<pre>';print_R($cancelekart);die;
                            }
                            
                        
                        }else{
                            // Try to cancel globally for XpressBees even if tracking_info is empty locally
                            if ($order['ship_courier_id'] == '4') {
                                $recoveredLog = ApiLog::where('order_id', $id)
                                    ->where('courier_id', '4')
                                    ->where('response', 'like', '%"ReturnCode":"100"%')
                                    ->orderBy('id', 'desc')
                                    ->first();
                                
                                if ($recoveredLog) {
                                    $loggedManifest = json_decode($recoveredLog->response, true);
                                    $foundAwb = $loggedManifest['AWBNo'] ?? ($loggedManifest['data'][0]['AWBNo'] ?? null);
                                    if ($foundAwb) {
                                        $awb_array = json_encode([
                                            'ShippingID' => (string)$foundAwb,
                                            'CancellationReason' => 'Cancel order (Sync Recovery)'
                                        ], true);
                                        Integration::cancelshipment_xbess($awb_array);
                                        api_logs($awb_array, 'Sync Recovery: Global Cancel triggered during local cancel', $id, '4', 'cancelled');
                                    }
                                }
                            }

                            $orderd = Order::where('id', $id)->first();
                            $orderd->status = '4';
                            $orderd->order_id = $orderd->order_id.'c';
                            $orderd->warehouse_id = null;
                            $orderd->picked_date = null;
                            $orderd->rto_date = null;
                            $orderd->return_warehouse_id = 0;
                            $orderd->return_warehouse_id = 0;
                            $orderd->ship_courier_id = 0;
                            $orderd->shipping_courier_cost = 0;
                            $orderd->gst = 0;
                            $orderd->gst_freight = 0;
                            $orderd->gst_cod = 0;
                            $orderd->freight = 0;
                            $orderd->cod = 0;
                            $orderd->shipping_courier_costparent = 0;
                            $orderd->gstparent = 0;
                            $orderd->gst_freightparent = 0;
                            $orderd->gst_codparent = 0;
                            $orderd->freightparent = 0;
                            $orderd->codparent = 0;
                            $orderd->shipping_courier_type = null;
                            $orderd->shipping_courier_weight_used = null;
                            
                            $orderd->zone = null;
                            $orderd->manifest_id = 0;
                            $orderd->tracking_info = null;
                            $orderd->cancel_date = now();
                            $orderd->save();
                        }
                    Status::orderstatuslog($id,$current_company_id,$old_status,'Canceled',now());    
                    }
                 }

            return redirect()->back()->with('success', 'Orders Cancelled Successfully!');
        }
    }
    
    public function rto()
    {

        if(request()->status == 'rto')
        {
            foreach(request()->id as $id)
            {
                $order = Order::with('detail')->where('id', $id)->first();
                if(strip_tags($order->status)== 'Delivered'){
                    $order_last = Order::latest()->first();
                    if ($order_last) {
                        $lastOrderId = $order_last->id;
                        $order_id = sprintf('%03d', intval($lastOrderId) + 1);
                    } else {
                        $order_id = '001';
                    }
                    $order_rto = new Order();
                    $user_id = Auth::guard('admin')->user()->id;
                    $order_rto->user_id = $user_id;
                    $order_rto->reverse_order = '1';
                    $order_rto->order_id = $order_id;
                    $order_rto->vendor_order_id = $order->vendor_order_id.'-R';
                    $order_rto->ship_fname = $order->ship_fname;
                    $order_rto->ship_lname = $order->ship_lname;
                    $order_rto->ship_email = $order->ship_email;
                    $order_rto->ship_company = $order->ship_company;
                    $order_rto->ship_phone = $order->ship_phone;
                    $order_rto->ship_address = $order->ship_address;
                    $order_rto->ship_address_2 = $order->ship_address_2;
                    $order_rto->ship_country = $order->ship_country;
                    $order_rto->ship_city = $order->ship_city;
                    $order_rto->ship_state = $order->ship_state;
                    $order_rto->ship_pincode = $order->ship_pincode;
                    $order_rto->ship_latitude = $order->ship_latitude;
                    $order_rto->ship_longitude = $order->ship_longitude;
                    $order_rto->ship_gstin = $order->ship_gstin;
                    $order_rto->warehouse_id = $order->warehouse_id;
                    $order_rto->payment_mode = '16';
                    $order_rto->weight = $order->weight;
                    $order_rto->length = $order->length;
                    $order_rto->breadth = $order->breadth;
                    $order_rto->height = $order->height;
                    $order_rto->discount = $order->discount;
                    $order_rto->shipping_cost = $order->shipping_cost;
                    $order_rto->total = $order->total;
                    $order_rto->custom_total = $order->custom_total;
                    // echo '<pre>';print_R($order_rto);die;
                    $order_rto->save();
                    $or_id = $order_rto->id;

                    for($i=0;$i<count($order->detail);$i++){
                        
                        $detail = new OrderDetail();
                        $detail->user_id = $order->detail[$i]->user_id;
                        $detail->order_id = $or_id;
                        $detail->name = $order->detail[$i]->name;
                        $detail->code = $order->detail[$i]->code;
                        $detail->price = $order->detail[$i]->price;
                        $detail->discount =$order->detail[$i]->discount;
                        $detail->qty = $order->detail[$i]->qty;
                        $detail->discount_type = $order->detail[$i]->discount_type;
                        $detail->tax_percent = $order->detail[$i]->tax_percent;
                        $detail->tax_amount = $order->detail[$i]->tax_amount;
                        $detail->total_price = $order->detail[$i]->total_price;
                        $detail->save();
                    }
                }
            }

          return redirect()->route('admin.order.index')->with('success', 'Orders Rto Successfully!');
        }
    }


    public function ndr()
    {
        // dd(request());
        if(request()->status == 'ndr')
        {
            foreach(request()->id as $id)
            {
                $order = Order::where('id', $id)->first();
                $order->status = '10';
                $order->rto_date = now();
                $order->save();
            }
// dd( $order);
          return redirect()->route('admin.order.index')->with('success', 'Orders NDR Successfully!');
        }
    }

    public function rto_order(){
        $user_data = Auth::guard('admin')->user();
        if($user_data->role_id =='1'){
            $order = Order::whereIn('status',array('5','13'))->get();
            // dd($orders);
            $rto_receive = Order::where('status','6')->get();
        }elseif($user_data->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user_data->id);
            $order = Order::whereIn('status',array('5','13'))
            ->whereIn('user_id', $sub_user_id)
            ->get();
            $rto_receive = Order::where('status','6')
            ->whereIn('user_id', $sub_user_id)
            ->get();
        }else{
            $order = Order::whereIn('status',array('5','13'))
            ->where('user_id', $user_data->id)
            ->get();
            $rto_receive = Order::where('status','6')
            ->where('user_id', $user_data->id)
            ->get();
        }
        return view('admin.order.rto',compact('order','rto_receive'));
    }
    public function rto_received(){
        if(request()->status =='RTO Received'){
            foreach(request()->id as $id){
                $order = Order::where('id',$id)->first();
                $old_status = strip_tags($order->status);
                $order->status ='6';
                $order->rto_received_date = now();
                $order->save();
                Status::orderstatuslog($id,$order->company_id,$old_status,'RTO Received',now());
            }
        }
        // $order = Order::where('status','6')->get();
        return redirect()->route('admin.order.rto_order')->with('success', 'Orders Rto received Successfully!');
    }

    public function new()
    {
        if(request()->status == 'new')
        {
            foreach(request()->id as $id)
            {
                $order = Order::where('id', $id)->first();
                $order->status = '1';
                $order->save();
            }

          return redirect()->route('admin.order.onholdpage')->with('success', 'Orders Change to new Successfully!');
        }
    }

    public function refund()
    {
        if(request()->status == 'refund')
        {
            foreach(request()->id as $id)
            {
                $order = Order::where('id', $id)->first();
                $order->status = '9';
                $order->save();
            }

          return redirect()->route('admin.order.index')->with('success', 'Orders Refund Successfully!');
        }
    }

    public function refundpage()
    {
        $refund_order = Order::where('status', 9)->get();

        return view('admin.order.refund',compact('refund_order'));
    }

    public function unfulfilled()
    {  
        $user_data = Auth::guard('admin')->user();
        if($user_data->role_id =='1'){
            $order_unfulfill = Order::where('status',1)->get();
        }else{
            $order_unfulfill = Order::where('status',1)
            ->where('user_id', $user_data->id)
            ->get();
        }
        return view('admin.order.unfulfilled',compact('order_unfulfill'));
        
    }

    public function fulfilled()
    {
        // if(request()->status == 'fulfilled')
        // {
        //     foreach(request()->id as $id)
        //     {
        //         $order = Order::where('id', $id)->first();
        //         $order->status = '8';
        //         $order->save();
        //     }

        //   return redirect()->route('admin.order.unfulfilled')->with('success', 'Orders fulfilled Successfully!');
        // }
       return redirect()->route('admin.order.unfulfilled')->with('success', 'Orders fulfilled Successfully!');
    }
  

    public function sla_order(){
       
        $user_id = Auth::guard('admin')->user()->id;
        $warehouse = WareHouse::where('user_id',$user_id)->get();
        $order = Order::where('status','1')->paginate(10);
        return view('admin.order.sla',compact('order','warehouse'));
    }

    public function return()
    {   
        $user = Auth::guard('admin')->user();
        $user_id = $user->id;
        $current_company = $user->company_id;
        // $warehouse = WareHouse::where('user_id',$user_id)->get();
        // $order = Order::where('status','5')->orWhere('status','6')->paginate(10);
        if(Auth::guard('admin')->user()->role_id =='1' ){
            $order_q = Order::with('detail');
            $warehouse = WareHouse::get();
        }elseif(Auth::guard('admin')->user()->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user_id);
            $order_q = Order::with('detail')->whereIn('user_id' , $sub_user_id);
            $warehouse = WareHouse::whereIn('user_id',$sub_user_id)->get();
        }else{
            $order_q = Order::with('detail')->where(['user_id' => $user_id]);
            $warehouse = WareHouse::where('user_id',$user_id)->get();
        }
        $order_q->where('company_id',$current_company);
        $order = $order_q->whereIn('status',['5','6','13'])
                        ->paginate(10);
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        return view('admin.order.return', compact('order','warehouse','couriers'));
    }


    

    public function get_courier(){
        $trasport_arary = ['NDD','SDD','Air','Surface','Quick'];
        $order = Order::find(request('order_id'));
        if( $order->ship_pincode !='' && $order->ship_fname !='' && $order->ship_fname !=0){}else{
            return [
                'status' => 0,
                'message' => 'Some required details are missing,please check detail page'
            ];
        }
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $getuser = DB::table('orders')
        ->select('user_id as user')
        ->where('id','=',$order->id)
        ->first();
        $user_id = $orde_user_id  = $getuser->user;
        $parent_userid = Admin::find($user_id)->parent_id;
        $currentcompany_id = Admin::find($user_id)->company_id;
        // $user_id = auth()->guard('admin')->user()->id;

        $availableCouriers = [];  
        $courierss = DB::table('couriers')
          ->where('status', '=', '1')  
          ->where('company_id', '=', $currentcompany_id)
         ->get(['courier_id', 'mode']); 

        foreach ($courierss as $courier) {
        $availableCouriers[] = [
        'courier_id' => $courier->courier_id,
        'transport' => $courier->mode,  
          ];
        }   
        $courier = Integration::whereUserId($user_id)->pluck('courier_id')->toArray();
        $ware = request('warehouse_id');
        if($ware != 0)
        {
        $pickup = Warehouse::whereId($ware)->pluck('pincode')->first();
        $drop = $order->ship_pincode;
        $pincodepicup_sevice = Pincode::firstOrCreate(['pincode' => $pickup], ['company_id' => $currentcompany_id ?? 1]);
        $pincodedrop_sevice = Pincode::firstOrCreate(['pincode' => $drop], ['company_id' => $currentcompany_id ?? 1]);
        if(!$pincodepicup_sevice || !$pincodedrop_sevice){
            return [
                'status' => 0,
                'message' => 'Pincode is not Servicable! - Hyloship'
            ];
        }
        $dom = new DOMDocument();
        $dom->loadHTML($order->payment_mode);
        $spans = $dom->getElementsByTagName('span');
        $text = '';
        foreach ($spans as $span) {
            $text = $span->textContent;
        }
        $rateuser = Ratecard::where(['status' => 1,'user_id' => $user_id,'company_id'=>$currentcompany_id])->first();
        if($rateuser == null){
            return [
                'status' => 0,
                'message' => 'Ratecard not found'
            ];
        }
        
        if($text=='Reverse'){
            $payment = 'rev';
            $tep =$drop;
            $drop =$pickup;
            $pickup =$tep;
            $service_type = 'REVERSE';
        }else{
            $payment = $text == 'C.O.D' ? 'cod' : 'prepaid';
            $service_type = 'FORWARD';
        }
        
        //for ecom
//        $pincode_serviceable = Integration::chk_serviceable_pincode($drop);
//        $response = json_decode($pincode_serviceable,true);
//        if(isset($response[0]) && $response[0]['active']){
//            $cour_id[] = 1;
//        }


        // for xbess
        // $expess_data = array(
        //     'origin'=>$pickup,
        //     'destination'=>$pickup,
        //     'payment_type'=>$payment,
        //     'order_amount'=>$order['custom_total'],
        //     'weight'=>$order['weight'],
        //     'length'=>$order['length'],
        //     'breadth'=>$order['breadth'],
        //     'height'=>$order['height']
        //  );
        // $xbess_srvice = Integration::chk_serviceable_pincode_xbess(json_encode($expess_data));
        // $xbess_srvice_response = json_decode($xbess_srvice,true);
        // if($xbess_srvice_response['status']){
        //     $cour_id[] =4;
        // }

        // for Delhivary
//        $delhivary_serviceable = Integration::chk_serviceable_pincode_delhivary($drop);
//        $service = json_decode($delhivary_serviceable,true);
//        if(empty($service['delivery_codes'])){ }else{
//            $cour_id[] =2;
//        }

        // for DTDC
//        $dtdc_serviceable = Integration::chk_serviceable_pincode_dtdc($drop);
//        $service_dtdc = json_decode($dtdc_serviceable,true);
//        // echo '<pre>';print_R($service_dtdc);die;
//        if($service_dtdc['status']){
//            $cour_id[] =5;
//        }

        //for Smartr
//        $smatr_serviceable = Integration::chk_serviceable_pincode_smatr($drop);
//        $service_smatr = json_decode($smatr_serviceable,true);
//        if($service_smatr['status'] =='Success'){
//            $cour_id[] =6;
//            $trasport_arary = ['Air'];
//        }
        
        //for Ekart
//        $ekart_chk_data = array(
//            'request_id'=>$order->order_id,
//            'dispatch_date'=>"",
//            'customer_pincode'=>$drop,
//            'seller_pincode'=>$pickup,
//            'rto_pincode'=>"",
//            'length'=>$order->length,
//            'breadth'=>$order->breadth,
//            'height'=>$order->height,
//            'weight'=>$order->weight/1000,
//            'delivery_type'=>'Small',
//            'service_type'=>$service_type,
//            'is_dangerous'=>false,
//            'is_fragile'=>false,
//        );
//        $ekart_serviceable = Integration_more::chk_serviceable_pincode_ekart(json_encode($ekart_chk_data));
//        $service_ekart = json_decode($ekart_serviceable,true);
//        if(isset($service_ekart['serviceable'])){
//            $cour_id[] =7;
//            if(isset($service_ekart['connections'])){
//                if(isset($service_ekart['connections']['REGULAR']) && isset($service_ekart['connections']['ECONOMY'])){
//                    $trasport_arary = ['Air','Surface'];
//                }else{
//                    if(isset($service_ekart['connections']['REGULAR'])){
//                        $trasport_arary = ['Air'];
//                    }else{
//                        $trasport_arary = ['Surface'];
//                    }
//                }
//            }
//            
//        }
        
        // for Shadowfax
//        $shadowfax_serviceable = Integration_more::chk_serviceable_pincode_shadowfax($drop,$pickup);
//        $service_shadowfax = json_decode($shadowfax_serviceable,true);
//        if(isset($service_shadowfax['message']) && $service_shadowfax['Serviceability']){
//            $cour_id[] =8;
//        }
//        $cour_id[] =10;
        if($text=='Reverse'){
            $cour_id =array();
        }else{
            $cour_id =array(2,3,7,10,4,8,11,12,13,14); // Added 13 for Parcel Uncle, 14 for India Post
        }
        if($user_id =='1'){
            $cour_id =array(11);
        }
//        $check_servicability = Integration_more::checkservicebluedart($pickup,$drop,$order['length'],$order['breadth'],$order['height'],$order['weight']);
        // $check_servicability = '[{"id":4,"name":"Xpressbees","mode":"SFC","rate":85},{"id":7,"name":"Ecom Express (upto 5KG)","mode":"SFC","rate":99}]';
//        $check_servicability = json_decode($check_servicability,true);

//            echo '<pre>';print_R($transport);die;
        
        
        $get = array();
        if(!empty($cour_id)){
            $zone_id = Order::getzone($pickup,$drop);
//             echo $zone_id;die;
            for($i=0;$i<count($cour_id);$i++){
                $pincodes[] = array(
                    "courier_id" => $cour_id[$i],  
                    "zone" => $zone_id,
                ); 
            }
            foreach($pincodes as $row){
//                echo '<pre>';print_R($pincodes);die;
                if($row['zone'] !='0' && $row['zone'] !=''){
                $zone = zone($row['zone']);
                $c_id = $row['courier_id'];
                // $count = intVal(($order->weight/1000) / 0.5) - 1; //to convert weight into kg
                $trasport_arary = ['NDD','SDD','Air','Surface','Quick'];
                $vol_weight = ($order['length']*$order['breadth']*$order['height'])/vol_weigh($c_id);
                $weight_to_be_taken = $vol_weight > ($order['weight']/1000) ? $vol_weight : ($order['weight']/1000);
                $weight_array = ['0.5', '1', '1.5', '2', '3','3.5', '5','10','20','30','50'];
                $filtered_weights = [];

                foreach ($weight_array as $w) {
                    $filtered_weights[] = $w;

                    if ($w >= $weight_to_be_taken) {
                        break; // stop once we cross required weight
                    }
                }

                foreach($trasport_arary as $transport){

                    $isCourierAvailable = false;

                    foreach ($availableCouriers as $courier) {
                        if ($courier['courier_id'] == $row['courier_id'] && strtolower($courier['transport']) == strtolower($transport)) {
                            $isCourierAvailable = true;
                            break;
                        }
                    }
                  
                    if (!$isCourierAvailable) {
                        continue; 
                    }

                    // For Blue Dart (courier_id = 3)
                    $bd_serviceable = true;
                    if ($c_id == 3) {
                        $bd_integration = Integration_more::get_bluedart_integration($user_id);
                        if ($bd_integration && $bd_integration->bd_client_id != '') {
                            $bd_res = Integration::chk_serviceable_pincode_bluedart_apigee($drop, $bd_integration);
                            $bd_serviceable = false;
                            if ($bd_res) {
                                $bd_data = json_decode($bd_res, true);
                                if (isset($bd_data['GetServicesforPincodeResult']) && $bd_data['GetServicesforPincodeResult']['IsError'] == false) {
                                    $result = $bd_data['GetServicesforPincodeResult'];
                                    if (strtolower($transport) == 'air') {
                                        $air_in = strtoupper($result['ApexInbound'] ?? '');
                                        $etail_air = strtoupper($result['eTailPrePaidAirInbound'] ?? '');
                                        $etail_cod = strtoupper($result['eTailCODAirInbound'] ?? '');
                                        $express_air = strtoupper($result['eTailExpressPrePaidAirInbound'] ?? '');
                                        if ($air_in === 'YES' || $etail_air === 'YES' || $etail_cod === 'YES' || $express_air === 'YES') {
                                            $bd_serviceable = true;
                                        }
                                    } elseif (strtolower($transport) == 'surface') {
                                        $ground_in = strtoupper($result['GroundInbound'] ?? '');
                                        $bharat_pre = strtoupper($result['BharatDartPrePaidInbound'] ?? '');
                                        $bharat_cod = strtoupper($result['BharatDartCODInbound'] ?? '');
                                        $etail_grd = strtoupper($result['eTailPrePaidGroundInbound'] ?? '');
                                        if ($ground_in === 'YES' || $bharat_pre === 'YES' || $bharat_cod === 'YES' || $etail_grd === 'YES') {
                                            $bd_serviceable = true;
                                        }
                                    }
                                }
                            }
                        } else {
                            $bd_serviceable = false;
                        }
                    }

//                    echo $isCourierAvailable;die;
                    foreach($filtered_weights as $c_weigt){
                        $totalparent = $codparent = $gstparent = $reverse_chregparent = $gst_codparent = $gst_freightparent = $freightparent = $rateparent = $rateaddparent = 0;
                        $rate = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $user_id,'weight' =>$c_weigt,'additional' =>0])->first();
                        $rateparent = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $parent_userid,'weight' =>$c_weigt,'additional' =>0])->first();
//                        echo '<pre>';print_R($transport.'-->'.$user_id.'-->'.$c_weigt.'-->'.$c_id);die;
                        if($c_weigt=='0.5'){
                            $wadd = '0.5';
                        }else{
                            $wadd = '1';
                        }
                        $rateadd = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $user_id,'weight' =>$wadd,'additional' =>1])->first();
                        $rateaddparent = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $parent_userid,'weight' =>$wadd,'additional' =>1])->first();
                        
                        // $add = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport, 'weight' => 'add','status' => 1,'user_id' => $user_id])->first();
                        
                        if($rate == null){
                            //  return [
                            //     'status' => 0,
                            //     'message' => 'Rate card not found for this user'
                            // ];
                            continue;
                        }
                        $percent = (($order->total * $rate->cod) / 100); //4339
                        $cod = $payment == 'cod' ? $percent > $rate->cod_charges ? $percent : $rate->cod_charges : 0; //0
                        $remainging_weight = $weight_to_be_taken - $c_weigt;
                        $freight = $rate->$zone;
                        
                        if($rateparent !=''){
                            $percentparent = (($order->total * $rateparent->cod) / 100); //4339
                            $codparent = $payment == 'cod' ? $percentparent > $rateparent->cod_charges ? $percentparent : $rateparent->cod_charges : 0; //0
                            $freightparent = $rateparent->$zone;
                        }


                        if($remainging_weight<0){
                            $remainging_weight =0;
                        }
                        $count =$remainging_charge=$remainging_chargeparent=0;
                        if($rateadd == null){
                            $getrateadd =0;
                        }else{
                            $count = ceil($remainging_weight/$rateadd->weight);
                            $remainging_charge = $count * $rateadd->$zone;
                            $getrateadd = $rateadd->$zone;
                        }

                        if($rateaddparent == null){
                            $getrateaddparent =0;
                        }else{
                            $remainging_chargeparent = $count * $rateaddparent->$zone;
                            $getrateaddparent = $rateaddparent->$zone;
                        }
                        $freight = $freight + $remainging_charge;
                        $freightparent = $freightparent + $remainging_chargeparent;
                        $reverse_chreg =$reverse_chregparent =0;
                        if($text=='Reverse'){
                            $ratereverse = Ratecard::where(['courier_id' => $c_id, 'transport' => 'Reverse','status' => 1,'user_id' => $user_id])->first();
                            $freight = $freight + @$ratereverse->$zone;
                            if(isset($ratereverse->$zone)){
                                $reverse_chreg =$ratereverse->$zone;
                            }
                            $ratereverseparent = Ratecard::where(['courier_id' => $c_id, 'transport' => 'Reverse','status' => 1,'user_id' => $parent_userid])->first();
                            $freightparent = $freightparent + @$ratereverseparent->$zone;
                            if(isset($ratereverseparent->$zone)){
                                $reverse_chregparent =$ratereverseparent->$zone;
                            }
                        }
                        $gst = (($freight + $cod) * 18) / 100;
                        $gst_cod = (($cod) * 18) / 100;
                        $gst_freight = (($freight) * 18) / 100;
                        if($payment == 'cod'){
                            $total = $gst + $freight + $cod;
                        } else {
                            $total = $gst + $freight;
                        }

                        $gstparent = (($freightparent + $codparent) * 18) / 100;
                        $gst_codparent = (($codparent) * 18) / 100;
                        $gst_freightparent = (($freightparent) * 18) / 100;
                        if($payment == 'cod'){
                            $totalparent = $gstparent + $freightparent + $codparent;
                        } else {
                            $totalparent = $gstparent + $freightparent;
                        }

                        
                        
                        $edd = '';
                        if($rate->courier_id == 2){
                            if ($transport == 'Air') {
                                $mot = 'E';
                            } elseif ($transport == 'Surface') {
                                $mot = 'S';
                            } elseif ($transport == 'NDD' || $transport == 'SDD') {
                                $mot = 'N';
                            } else {
                                $mot = 'S';
                            }
                            $pickup_date = date('Y-m-d H:i');
                            $tat_response = Integration::get_expected_tat_delhivery($pickup, $drop, $mot, 'B2C', $pickup_date, $pickup_date);
                            $tat_data = json_decode($tat_response, true);
                            if(isset($tat_data['data']['expected_delivery_date'])){
                                $edd = date('d M, Y', strtotime($tat_data['data']['expected_delivery_date']));
                            } elseif(isset($tat_data[0]['expected_delivery_date'])){
                                $edd = date('d M, Y', strtotime($tat_data[0]['expected_delivery_date']));
                            }
                        }
                        

                         $get[] = [
                            'courier_id' => $rate->courier_id,
                            'name' => $couriers[$rate->courier_id]['name'],
                            'img' => asset('public/courier').'/'.$couriers[$rate->courier_id]['image'],
                            'mode' => $transport == 'Air' 
                            ? 'fa-plane' 
                            : ($transport == 'Surface' 
                                ? 'fa-truck' 
                                : ($transport == 'NDD' 
                                    ? 'fa-bicycle' 
                                    : ($transport == 'Quick'
                                        ? 'fa-bolt'
                                        : 'fa-motorcycle'
                                    )
                                )
                            ),
                            'weight_used' => $c_weigt,
                            'weight' => round($weight_to_be_taken,2).' kg',
                            'zone' => $zone,
                            'edd' => $edd,
                            'serviceable' => ($rate->courier_id == 3) ? $bd_serviceable : true,
                            'serviceable_msg' => ($rate->courier_id == 3 && !$bd_serviceable) ? 'Pincode is not serviceable' : '',

                            'price' => 'Rs.'.number_format($total,2),
                            'cod' => round($cod,2),
                            'reverse_charge' => round($reverse_chreg,2),
                            'gst' => round($gst,2),
                            'gst_cod' => round($gst_cod,2),
                            'gst_freight' => round($gst_freight,2),
                            'freight' => round($freight,2),
                            'rate' =>round($rate->$zone,2),
                            'rateadd' =>round($getrateadd,2),

                            'priceparent' => 'Rs.'.number_format($totalparent,2),
                            'codparent' => round($codparent,2),
                            'reverse_chargeparent' => round($reverse_chregparent,2),
                            'gstparent' => round($gstparent,2),
                            'gst_codparent' => round($gst_codparent,2),
                            'gst_freightparent' => round($gst_freightparent,2),
                            'freightparent' => round($freightparent,2),
                            'rateparent' =>round(@$rateparent->$zone,2),
                            'rateaddparent' =>round($getrateaddparent,2),

                            
                        ];
                    }    
                }
                }
            }
        }
//        if($user_id ==68){
////                echo $c_id.' -->';
//                 echo '<pre>';print_R($get).'<br>';
//                }
        if(count($get) == 0){
            return [
                'status' => 0,
                'message' => 'Pincode is not Servicable!'
            ];
        }

        usort($get, function($a, $b) {
            $cidA = $a['courier_id']; 
            $cidB = $a['courier_id']; 
            $priceA = (float) str_replace('Rs.', '', $a['price']);
            $priceB = (float) str_replace('Rs.', '', $b['price']);
            if ($cidA == $cidB) {
                return 0; 
                if ($priceA == $priceB) { return 0; }
                return ($priceA < $priceB) ? -1 : 1;
            }
            return ($cidA < $cidB) ? -1 : 1;
            
        });
        return [
            'status' => 1, 
            'message' => 'Success',
            'data' => $get,
        ];

        if($pincodes->isEmpty()){
            return [
                'status' => 0,
                'message' => 'Pincode Not Servicable!'
            ];
        }
        }else{
            return [
                'status' => 0,
                'message' => 'No Warehouse for pickup'
            ];
        }
    }
    
    
    public function assign(Request $request){
        try {
        $validate = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
            'warehouse_id' => 'required|numeric',
            'return_warehouse_id' => 'numeric',
            'courier_id' => 'required|numeric',
        ])->validate();
        $getuser = DB::table('orders')
                ->select('user_id as user')
                ->where('id','=',request('order_id'))
                ->first();
        $orde_user_id  = $getuser->user;
        $orderuserdata = Admin::find($orde_user_id);
        $parent_userid = $orderuserdata->parent_id;
        $company_id  = $orderuserdata->company_id;
        $get = $this->get_courier();
        $number = str_replace('Rs.', '', $get['data'][request('courier_id')]['price']);
        $cleanedString = str_replace(',', '', $number);
        $ship_courier_cost = (float) $cleanedString;
        $numberparent= str_replace('Rs.', '', $get['data'][request('courier_id')]['priceparent']);
        $cleanedStringparent = str_replace(',', '', $numberparent);
        $ship_courier_costparent = (float) $cleanedStringparent;
        if((Admin::find($orde_user_id)->wallet_blc + Admin::find($orde_user_id)->limit_loan)  <= $ship_courier_cost){
            return [
                'status' => 0,
                'message' => 'Insufficient Balance in wallet to process this order',
            ];
        }
        $order = Order::with('detail')->where('id', $request->order_id)->first();
        if($order->tracking_info ==''){
        $warehouse = Warehouse::find(request('warehouse_id'));
        $warehousereturn = Warehouse::find(request('return_warehouse_id'));
        if(in_array($get['data'][request('courier_id')]['courier_id'],array('2','10','7'))){
            $awb_no =date('Ymdhis');
        }else{
            $awb_no ='';
        }
        $shipment_id = null;
        // echo $get['data'][request('courier_id')]['courier_id'];die;
        if(in_array($get['data'][request('courier_id')]['courier_id'],array('1','2','3','4','5','6','7','8','9','10','11','12','13','14'))){
            if($order->reverse_order =='0'){
                if($get['data'][request('courier_id')]['courier_id'] =='1'){
                $pmode ='';
                $coll_value =0;
                if($order['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">C.O.D</span>'){
                    $pmode = 'cod';
                    $coll_value = $order['custom_total'];
                }elseif($order['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">Pre-Paid</span>'){
                    $pmode = 'ppd';
                }
                $awb = Integration::get_awb_number($pmode);
    //            echo '<pre>';print_r($awb);die;
                if($awb !=''){
                    $response = json_decode($awb,true);
                    if(isset($response['awb']) && count($response['awb']) >0){
                        $awb_no = $response['awb'][0];
                        $manifest_d =
                            array(
                                'AWB_NUMBER'=>$awb_no,
                                'ORDER_NUMBER'=>$order['order_id'],
                                'PRODUCT'=>$pmode,
                                'CONSIGNEE'=>$order['ship_fname'].' '.$order['ship_lname'],
                                'CONSIGNEE_ADDRESS1'=>$order['ship_address'],
                                'CONSIGNEE_ADDRESS2'=>$order['ship_address_2'],
                                'CONSIGNEE_ADDRESS3'=>'',
                                'DESTINATION_CITY'=>$order['ship_city'],
                                'PINCODE'=>$order['ship_pincode'],
                                'STATE'=>$order['ship_state'],
                                'MOBILE'=>$order['ship_phone'],
                                'TELEPHONE'=>'',
                                'ITEM_DESCRIPTION'=>'Multiple items',
                                'PIECES'=>count($order['detail']),
                                'COLLECTABLE_VALUE'=>$coll_value,
                                'DECLARED_VALUE'=>$order['total'],
                                'ACTUAL_WEIGHT'=>$order['weight']/1000,
                                'VOLUMETRIC_WEIGHT'=>($order['length']*$order['breadth']*$order['height'])/5000,
                                'LENGTH'=>$order['length'],
                                'BREADTH'=>$order['breadth'],
                                'HEIGHT'=>$order['height'],
                                'PICKUP_NAME'=>$warehouse['contact_name'],
                                'PICKUP_ADDRESS_LINE1'=>$warehouse['address'],
                                'PICKUP_ADDRESS_LINE2'=>$warehouse['address_2'],
                                'PICKUP_PINCODE'=>$warehouse['pincode'],
                                'PICKUP_PHONE'=>$warehouse['phone'],
                                'PICKUP_MOBILE'=>$warehouse['phone'],
                                'RETURN_NAME'=>$warehousereturn['contact_name'],
                                'RETURN_ADDRESS_LINE1'=>$warehousereturn['address'],
                                'RETURN_ADDRESS_LINE2'=>$warehousereturn['address_2'],
                                'RETURN_PINCODE'=>$warehousereturn['pincode'],
                                'RETURN_PHONE'=>$warehousereturn['phone'],
                                'RETURN_MOBILE'=>$warehousereturn['phone'],
                                'DG_SHIPMENT'=>false
                                //Pass ""true"" in case shipment package contains any item that is restricted for air travel as per DGCA guidelines. Otherwise pass ""false"

                            );
                            $manifest = Integration::shipment_ecom(json_encode($manifest_d,true));
                            //checking api logs
                            api_logs(json_encode($manifest_d,true),$manifest,$order['id'],'1');
                            $manifest = json_decode($manifest,true);
                            if($manifest['shipments'][0]['success']){ }else{$awb_no ='';
                             return [
                                'status' => 0,
                                'message' => @$manifest['shipments'][0]['reason'],
                            ];
                            }
                    }else{
                        return [
                            'status' => 0,
                            'message' => 'Awb not working, please try after sometime',
                        ];
                    }
                }else{
                    return [
                        'status' => 0,
                        'message' => 'Payment mode not found',
                    ];
                }
                }
                else if($get['data'][request('courier_id')]['courier_id'] =='2'){
                    $coll_value =0;
                    if($order['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">C.O.D</span>'){
                        $pmode = 'COD';
                        $coll_value = $order['custom_total'];
                    }elseif($order['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">Pre-Paid</span>'){
                        $pmode = 'Prepaid';
                    }
                    if($get['data'][request('courier_id')]['mode'] =='fa-plane'){
                        $trsfr_type = 'Express';
                        $ttype = 'a';
                    }else{
                        $trsfr_type = 'Surface';
                        $ttype = 's';
                    }
                    $awb = Integration::get_awb_number_delhivary($pmode,$ttype);
                    $awb = json_decode($awb,true);
                    $awb_no ='';
                    if((int)($awb) == $awb){
                        $awb_no = $awb;
                    }
    //                $awb_no = '28838110000302';
                    $delhivery_data = array(
                        'shipments'=> array(array(
                            'add' => str_replace('&','and', $order['ship_address'].' '.$order['ship_address_2']),
                            'phone' => $order['ship_phone'],
                            'payment_mode' => $pmode,
                            'name' => $order['ship_fname'].' '.$order['ship_lname'],
                            'pin' => $order['ship_pincode'],
                            'order' => $order['order_id'],
                            'shipping_mode' => $trsfr_type,
                            'shipment_height' => $order['height'],
                            'shipment_width' => $order['breadth'],
                            'shipment_length' => $order['length'],
                            'cod_amount' => $coll_value,
                            'waybill' => $awb_no,
                            'total_amount' => $order['total'],
                            'quantity' => count($order['detail']),
                            'weight' => $order['weight'],
                            'products_desc' => 'Multiple items',
                        )),
                        'pickup_location' => array(
                            'name' =>$warehouse['company'].$ttype,
                            'city' => $warehouse['city'],
                            'pin' => $warehouse['pincode'],
                            'phone' => $warehouse['phone'],
                            'add' => str_replace('&','and', $warehouse['address'].' '.$warehouse['address_2']),
                        )
                    );
    //                echo json_encode($delhivery_data);
                    try {
                        $ship_delhivary = Integration::shipment_delhivary('format=json&data='.urlencode(json_encode($delhivery_data)),$ttype);
                        //checking api logs
                        api_logs(json_encode($delhivery_data),$ship_delhivary ,$order['id'],'2');
                        $d_data = json_decode($ship_delhivary,true);
                        if (!is_array($d_data)) {
                            throw new \Exception('Invalid or empty response from Delhivery API');
                        }
                    } catch (\Throwable $e) {
                        $d_data = ['success' => false, 'rmk' => 'Delhivery API error: ' . $e->getMessage()];
                    }

                    if(!$d_data['success']){
                        $awb_no ='';
                        $rmkk ='';
                        if(isset($d_data['packages'][0]) && isset($d_data['packages'][0]['remarks'])){
                            $rmk =$d_data['packages'][0]['remarks'];
                            for($k=0;$k<count($rmk);$k++){
                                $rmkk .=$rmk[$k].',';
                            }
                            $rmkk = rtrim($rmkk,',');
                        }
                        if (stripos($rmkk, 'ClientWarehouse matching query does not exist') !== false) {
                            $this->autoRegisterDelhiveryWarehouse($warehouse, $ttype);
                            try {
                                $ship_delhivary = Integration::shipment_delhivary('format=json&data='.urlencode(json_encode($delhivery_data)),$ttype);
                                api_logs(json_encode($delhivery_data),$ship_delhivary ,$order['id'],'2');
                                $d_data = json_decode($ship_delhivary,true);
                                if (is_array($d_data)) {
                                    $rmkk ='';
                                    if(isset($d_data['packages'][0]) && isset($d_data['packages'][0]['remarks'])){
                                        $rmk =$d_data['packages'][0]['remarks'];
                                        for($k=0;$k<count($rmk);$k++){
                                            $rmkk .=$rmk[$k].',';
                                        }
                                        $rmkk = rtrim($rmkk,',');
                                    }
                                }
                            } catch (\Throwable $e) {
                                $d_data = ['success' => false, 'rmk' => 'Delhivery API error: ' . $e->getMessage()];
                            }
                        }
                        if($rmkk !=''){
                            return [
                                'status' => 0,
                                'message' => $rmkk,
                            ];
                        }else{
                            return [
                                'status' => 0,
                                'message' => $d_data['rmk'],
                            ];
                        }
                    }

                }
                else if($get['data'][request('courier_id')]['courier_id'] =='3'){
                    // Check BlueDart Pincode Serviceability
                    $bd_integration = Integration_more::get_bluedart_integration($orde_user_id);
                    if ($bd_integration && $bd_integration->bd_client_id != '') {
                        $bd_res = Integration::chk_serviceable_pincode_bluedart_apigee($order['ship_pincode'], $bd_integration);
                        $bd_serviceable = false;
                        if ($bd_res) {
                            $bd_data_chk = json_decode($bd_res, true);
                            if (isset($bd_data_chk['GetServicesforPincodeResult']) && $bd_data_chk['GetServicesforPincodeResult']['IsError'] == false) {
                                $result_chk = $bd_data_chk['GetServicesforPincodeResult'];
                                $req_mode = $get['data'][request('courier_id')]['mode'] ?? '';
                                if ($req_mode == 'fa-plane') {
                                    $air_in = strtoupper($result_chk['ApexInbound'] ?? '');
                                    $etail_air = strtoupper($result_chk['eTailPrePaidAirInbound'] ?? '');
                                    $etail_cod = strtoupper($result_chk['eTailCODAirInbound'] ?? '');
                                    $express_air = strtoupper($result_chk['eTailExpressPrePaidAirInbound'] ?? '');
                                    if ($air_in === 'YES' || $etail_air === 'YES' || $etail_cod === 'YES' || $express_air === 'YES') {
                                        $bd_serviceable = true;
                                    }
                                } else {
                                    $ground_in = strtoupper($result_chk['GroundInbound'] ?? '');
                                    $bharat_pre = strtoupper($result_chk['BharatDartPrePaidInbound'] ?? '');
                                    $bharat_cod = strtoupper($result_chk['BharatDartCODInbound'] ?? '');
                                    $etail_grd = strtoupper($result_chk['eTailPrePaidGroundInbound'] ?? '');
                                    if ($ground_in === 'YES' || $bharat_pre === 'YES' || $bharat_cod === 'YES' || $etail_grd === 'YES') {
                                        $bd_serviceable = true;
                                    }
                                }
                            }
                        }
                        if (!$bd_serviceable) {
                            return [
                                'status' => 0,
                                'message' => 'Pincode is not serviceable for BlueDart.',
                            ];
                        }
                    }
                    $w_id = $rw_id = '';
                    if(isset($warehouse->bd_id) && $warehouse->bd_id !=0 && $warehouse->bd_id !=''){
                        $w_id = $warehouse->bd_id;
                    }else{
                        $war_array = array(
                            'address_title'=>$warehouse->name.''.date('Ymdhis'),
                            'sender_name'=>$warehouse->contact_name,
                            'full_address'=>$warehouse->address.' '.$warehouse->address_2,
                            'phone'=>$warehouse->phone,
                            'pincode'=>$warehouse->pincode,
                        );
                        $getWarehouse =  Integration_more::warehouse_bludart(json_encode($war_array,true));
                        $warehusedetail = json_decode($getWarehouse,true);
    //                    echo json_encode($war_array,true);die;
    //                    echo '<pre>';print_R($warehusedetail);die;
                        if($warehusedetail['status'] && isset($warehusedetail['data']['pick_address_id'])){
                            $w_id = $warehusedetail['data']['pick_address_id'];
                            // Only save numeric warehouse IDs, not APIGEE bypass marker
                            if ($w_id !== 'APIGEE') {
                                $warehouse->bd_id = $w_id;
                                $warehouse->save();
                            }
                        }else{
                            $shipment_id = null;
                            $msg = $warehusedetail['responsemsg'];
                            return [
                                'status' => 0,
                                'message' => $msg,
                            ];
                        }

                    }

                    if($w_id !=''){
                        if($warehousereturn->id == $warehouse->id){
                            $rw_id = $w_id;
                        }else{
                            if(isset($warehousereturn->bd_id) && $warehousereturn->bd_id !=0 && $warehousereturn->bd_id !=''){
                                $rw_id = $warehousereturn->bd_id;
                            }else{
                                $warret_array = array(
                                    'address_title'=>$warehousereturn->id.' '.$warehousereturn->name,
                                    'sender_name'=>$warehousereturn->contact_name,
                                    'full_address'=>$warehousereturn->address.' '.$warehousereturn->address_2,
                                    'phone'=>$warehousereturn->phone,
                                    'pincode'=>$warehousereturn->pincode,
                                );
                                $getWarereturnhouse =  Integration_more::warehouse_bludart(json_encode($warret_array,true));
                                $warehusedetailretrun = json_decode($getWarereturnhouse,true);
                                if($warehusedetailretrun['status'] && isset($warehusedetailretrun['data']['pick_address_id'])){
                                    $rw_id = $warehusedetailretrun['data']['pick_address_id'];
                                    $warehousereturn->bd_id = $rw_id;
                                    $warehousereturn->save();
                                }else{
                                    $shipment_id = null;
                                    $msg = $warehusedetailretrun['responsemsg'];
                                    return [
                                        'status' => 0,
                                        'message' => $msg,
                                    ];
                                }
                            }
                        }
                        if($get['data'][request('courier_id')]['mode'] =='fa-plane'){
                            $p_type = 'air';
                        }else{
                            $p_type = 'surface';
                        }
                        $coll_value =0;
                        $awb_no ='';
                        if($order['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">C.O.D</span>'){
                            $pmode = 'Cod';
                            $coll_value = $order['custom_total'];
                        }elseif($order['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">Pre-Paid</span>'){
                            $pmode = 'Prepaid';
                        }  
                        $o_details =$order['detail'];
                        $order_items = array();
                        for($i=0;$i<count($o_details);$i++){
                            $order_items[] = array(
                                'product_sku'=> $o_details[$i]['code'],
                                'product_name'=> $o_details[$i]['name'],
                                'product_value'=> $o_details[$i]['price'],
                                'product_quantity'=>$o_details[$i]['qty'],
                            );
                        }         
                        $bd_data = array(
                            'client_order_id'=>$order['order_id'],
                            'consignee_emailid'=>$order['ship_email'],
                            'consignee_pincode'=>$order['ship_pincode'],
                            'consignee_mobile'=>$order['ship_phone'],
                            'consignee_phone'=>'',
                            'consignee_address1'=>$order['ship_address'],
                            'consignee_address2'=>$order['ship_address_2'],
                            'consignee_city'=>$order['ship_city'],
                            'consignee_name'=>$order['ship_fname'].' '.$order['ship_lname'],
                            'express_type'=>$p_type,
                            'pick_address_id'=>$w_id,
                            'shipper_warehouse_id'=>$warehouse->id,
                            'return_address_id'=>$rw_id,
                            'cod_amount'=>$coll_value,
                            'tax_amount'=>0,
                            'mps'=>'0',
                            'courier_type'=>1,
                            'courier_code'=>'PXBDE01',
                            'products'=>$order_items,
                            'payment_mode'=>$pmode,
                            'order_amount'=>$order['custom_total'],
                            'shipment_width'=>array(
                                $order['breadth']
                            ),
                            'shipment_height'=>array(
                                $order['height']
                            ),
                            'shipment_length'=>array(
                                $order['length']
                            ),
                            'shipment_weight'=>array(
                                round(($order['weight']/1000),2)
                            ),
                        );
    //                    echo json_encode($bd_data,true);die;
                        $shimedata = Integration_more::shipment_bludart(json_encode($bd_data,true));
                        //checking api logs
                         api_logs(json_encode($bd_data,true),$shimedata ,$order['id'],'3');

                        // $shimedata = '{"status":false,"responsemsg":"Insuffiecient Balance"}';
                        // $shimedata = '{"status":true,"responsemsg":"Order Placed Successfully.","data":{"awb_number":"77123731742","order_number":"7180608","job_id":null,"lrnum":"","waybills_num_json":null,"lable_data":null,"routing_code":"BRU\/BRU\/KDH","payment_mode":"PPD","client_order_id":"3787","partner_display_name":"Bluedart","courier_code":"PXBDE01","pickup_id":"","courier_name":"Bluedart"}}';
                        $shimedata = json_decode($shimedata, true);
                        // echo '<pre>';print_R($shimedata);die;
                        if(is_array($shimedata) && !empty($shimedata['status']) && isset($shimedata['data']['awb_number']) && $shimedata['data']['awb_number'] !=''){
                            $awb_no = $shimedata['data']['awb_number'];
                        }else{
                            $msg = $shimedata['responsemsg'] ?? ($shimedata['message'] ?? 'Blue Dart API failure — check api_logs for details');
                            if(is_array($msg) && count($msg) > 0){
                                $msg = $msg[0];
                            }
                            return [
                                'status' => 0,
                                'message' => $msg,
                            ];
                        }
                        // echo $shimedata;die;
                    }else{
                        return [
                            'status' => 0,
                            'message' => 'Warehouse id not found',
                        ];
                    }
                    }
                else if($get['data'][request('courier_id')]['courier_id'] == '4') {
                            $coll_value = 0;
                            if (strip_tags($order['payment_mode']) == 'C.O.D') {
                                $pmode = 'cod';
                                $coll_value = $order['total'];
                            } elseif (strip_tags($order['payment_mode']) == 'Pre-Paid') {
                                $pmode = 'pre';
                            }

                            $vol_weight = round((($order['length'] * $order['breadth'] * $order['height']) / 5000), 2);
                            $phy_weight = round($order['weight'] / 1000, 2);
                            $bill_weight = max($phy_weight, $vol_weight);

                            // Fetch user-specific XpressBees credentials
                            $xb_integration = Integration::where('user_id', $orde_user_id)->where('courier_id', 4)->first();
                            $xb_user = ($xb_integration && $xb_integration->xusername) ? $xb_integration->xusername : env('XBEES_USERNAME', 'admin@Hyloship.com');
                            $xb_pass = ($xb_integration && $xb_integration->xpassword) ? $xb_integration->xpassword : env('XBEES_PASSWORD', 'Xpress@1234567');
                            $xb_secret = ($xb_integration && $xb_integration->secret_key) ? $xb_integration->secret_key : env('XBEES_SECRETKEY', '5babb4d7a6c80b45ade918fb4e429068c8480e6125925c474d8d67a27f8190db');
                            $xb_biz_name = ($xb_integration && $xb_integration->b_account_name) ? $xb_integration->b_account_name : env('XBEES_BUSINESS_ACCOUNT', 'Hyloship');
                            $xb_key = ($xb_integration && $xb_integration->xxb_key) ? $xb_integration->xxb_key : env('XBEES_XB_KEY', 'Plmng39338VdtHa');

                            // Fetch vendor code from integration or default
                            $xb_vendor = ($xb_integration && $xb_integration->vendor_code) ? $xb_integration->vendor_code : 'VENDOR123';

                            // Map transport mode to XpressBees ServiceType (SD, SDD, NDD only)
                            // Surface/fa-truck -> SD | SDD/fa-motorcycle -> SDD | NDD/fa-bicycle -> NDD
                            $xb_mode = $get['data'][request('courier_id')]['mode'] ?? 'fa-motorcycle';
                            if ($xb_mode === 'fa-bicycle') {
                                $xb_service_type = 'NDD';
                            } elseif ($xb_mode === 'fa-motorcycle') {
                                $xb_service_type = 'SDD';
                            } else {
                                $xb_service_type = 'SD'; // fa-truck = Surface default
                            }

                            $expess_data = array(
                                'BusinessAccountName' => $xb_biz_name,
                                'OrderNo' => (string) $order['order_id'],
                                'SubOrderNo' => (string) $order['order_id'],
                                'OrderType' => ($pmode == 'cod') ? 'COD' : 'PrePaid',
                                'CollectibleAmount' => (string) $coll_value,
                                'DeclaredValue' => (string) $order['total'],
                                'Quantity' => (string) count($order['detail']),
                                'PickupType' => 'Vendor',
                                'ServiceType' => $xb_service_type,
                                'DropDetails' => array(
                                    'Addresses' => array(
                                        array(
                                            'Name' => $order['ship_fname'] . ' ' . $order['ship_lname'],
                                            'Address' => $order['ship_address'] . ' ' . $order['ship_address_2'],
                                            'City' => $order['ship_city'],
                                            'EmailID' => $order['ship_email'] ?? '',
                                            'PinCode' => (string) $order['ship_pincode'],
                                            'State' => $order['ship_state'],
                                            'Type' => 'Primary',
                                        )
                                    ),
                                    'ContactDetails' => array(
                                        array(
                                            'PhoneNo' => (string) $order['ship_phone'],
                                            'Type' => 'Primary',
                                        )
                                    )
                                ),
                                'PickupDetails' => array(
                                    'Addresses' => array(
                                        array(
                                            'Name' => $warehouse['name'],
                                            'Address' => $warehouse['address'] . ' ' . $warehouse['address_2'],
                                            'City' => $warehouse['city'],
                                            'EmailID' => $warehouse['email'] ?? '',
                                            'PinCode' => (string) $warehouse['pincode'],
                                            'State' => $warehouse['state'],
                                            'Type' => 'Primary',
                                        )
                                    ),
                                    'ContactDetails' => array(
                                        array(
                                            'PhoneNo' => (string) $warehouse['phone'],
                                            'Type' => 'Primary',
                                        )
                                    ),
                                    'PickupVendorCode' => (string) $xb_vendor,
                                ),
                                'RTODetails' => array(
                                    'Addresses' => array(
                                        array(
                                            'Name' => $warehousereturn['name'],
                                            'Address' => $warehousereturn['address'] . ' ' . $warehousereturn['address_2'],
                                            'City' => $warehousereturn['city'],
                                            'EmailID' => $warehousereturn['email'] ?? '',
                                            'PinCode' => (string) $warehousereturn['pincode'],
                                            'State' => $warehousereturn['state'],
                                            'Type' => 'Primary',
                                        )
                                    ),
                                    'ContactDetails' => array(
                                        array(
                                            'PhoneNo' => (string) $warehousereturn['phone'],
                                            'Type' => 'Primary',
                                        )
                                    )
                                ),
                                'ManifestID' => date('YmdHi') . $order['order_id'],
                            );

                            // Generate AWB first as it is mandatory for live manifest
                            $awb_gen_data = json_encode([
                                "BusinessUnit" => "ECOM",
                                "ServiceType" => "FORWARD",
                                "DeliveryType" => ($pmode == 'cod') ? 'COD' : 'PREPAID',
                                "Count" => 1
                            ]);
                            $awb_res = Integration::generate_awb_series_xbess($awb_gen_data, $xb_user, $xb_pass, $xb_secret, $xb_key);
                            $awb_data = json_decode($awb_res, true);
                            $expess_data['AirWayBillNO'] = null;

                            // Attempt 1: Get AWB from primary API response
                            if (isset($awb_data['ReturnCode']) && $awb_data['ReturnCode'] == '100') {
                                if (isset($awb_data['BatchID']) && $awb_data['BatchID']) {
                                    $get_awb_data = json_encode([
                                        "BusinessUnit" => "ECOM",
                                        "ServiceType" => "FORWARD",
                                        "BatchID" => $awb_data['BatchID']
                                    ]);
                                    $fetch_res = Integration::get_awb_series_xbess($get_awb_data, $xb_user, $xb_pass, $xb_secret, $xb_key);
                                    $fetch_data = json_decode($fetch_res, true);
                                    if (isset($fetch_data['AWBNoSeries'][0])) {
                                        $expess_data['AirWayBillNO'] = $fetch_data['AWBNoSeries'][0];
                                    }
                                } elseif (isset($awb_data['AWBNo']) && $awb_data['AWBNo']) {
                                    $expess_data['AirWayBillNO'] = $awb_data['AWBNo'];
                                }
                            }

                            // Attempt 2: Fallback to pre-allocated pool (Batch: Tvo2T) if primary fails
                            if (empty($expess_data['AirWayBillNO'])) {
                                $fallback_batch = 'Tvo2T'; 
                                $get_awb_data = json_encode([
                                    "BusinessUnit" => "ECOM",
                                    "ServiceType" => "FORWARD",
                                    "BatchID" => $fallback_batch
                                ]);
                                $fetch_res = Integration::get_awb_series_xbess($get_awb_data, $xb_user, $xb_pass, $xb_secret, $xb_key);
                                $fetch_data = json_decode($fetch_res, true);
                                
                                if (isset($fetch_data['AWBNoSeries']) && is_array($fetch_data['AWBNoSeries'])) {
                                    foreach ($fetch_data['AWBNoSeries'] as $potential_awb) {
                                        // Ensure AWB is not used in active orders OR recorded in transactions (prevents reuse of cancelled AWBs)
                                        $isUsedInOrders = DB::table('orders')->where('tracking_info', $potential_awb)->exists();
                                        $isUsedInTransactions = DB::table('transactions')->where('awb', $potential_awb)->exists();

                                        if (!$isUsedInOrders && !$isUsedInTransactions) {
                                            $expess_data['AirWayBillNO'] = $potential_awb;
                                            break;
                                        }
                                    }
                                }
                            }

                            // Final error if no AWB could be retrieved by any method
                            if (empty($expess_data['AirWayBillNO'])) {
                                $attempt1_msg = (isset($awb_data['ReturnMessage']) ? $awb_data['ReturnMessage'] : (isset($awb_data['message']) ? $awb_data['message'] : 'Unknown Error'));
                                $attempt2_msg = (isset($fetch_data['ReturnMessage']) ? $fetch_data['ReturnMessage'] : 'Fallback Pool Empty');
                                
                                return [
                                    'status' => 0,
                                    'message' => "AWB Error: Primary [$attempt1_msg] | Fallback [$attempt2_msg]. Please check XpressBees credentials and AWB pool.",
                                ];
                            }

                            $manifestRes = Integration::shipment_express(json_encode($expess_data, true), $xb_user, $xb_pass, $xb_secret, $xb_key);
                            api_logs(json_encode($expess_data, true), $manifestRes ?? 'NULL_RESPONSE', $order['id'], '4');
                            $manifest = json_decode($manifestRes, true);

                            // Sync Logic: If XpressBees says already notified, try to recover the AWB from logs
                            if (isset($manifest['ReturnMessage']) && stripos($manifest['ReturnMessage'], 'already notified') !== false) {
                                $previousSuccessLog = ApiLog::where('order_id', $order['id'])
                                    ->where('courier_id', '4')
                                    ->where('response', 'like', '%"ReturnCode":"100"%')
                                    ->orderBy('id', 'desc')
                                    ->first();
                                
                                if ($previousSuccessLog) {
                                    $loggedManifest = json_decode($previousSuccessLog->response, true);
                                    if ((isset($loggedManifest['AWBNo']) && $loggedManifest['AWBNo']) || (isset($loggedManifest['data'][0]['AWBNo']) && $loggedManifest['data'][0]['AWBNo'])) {
                                        $manifest = $loggedManifest; // Recover state
                                    }
                                }
                            }

                            // Guard against null/empty API response (curl failure, timeout, non-JSON)
                            if ($manifest === null || !is_array($manifest)) {
                                return [
                                    'status' => 0,
                                    'message' => 'XpressBees API did not respond. Raw: ' . substr((string)$manifestRes, 0, 200),
                                ];
                            }

                            $isSuccess = false;
                            if (isset($manifest['ReturnCode']) && $manifest['ReturnCode'] == '100') {
                                $isSuccess = true;
                            } elseif (isset($manifest['message']) && stripos($manifest['message'], 'success') !== false) {
                                $isSuccess = true;
                            } elseif (isset($manifest['ReturnMessage']) && stripos($manifest['ReturnMessage'], 'success') !== false) {
                                $isSuccess = true;
                            }

                            if ($isSuccess) {
                                if (isset($manifest['AWBNo']) && $manifest['AWBNo'] != '') {
                                    $awb_no = (string)$manifest['AWBNo'];
                                } elseif (isset($manifest['data'][0]['AWBNo']) && $manifest['data'][0]['AWBNo'] != '') {
                                    $awb_no = (string)$manifest['data'][0]['AWBNo'];
                                } else {
                                    $awb_no = $expess_data['AirWayBillNO'];
                                }
                            } else {
                                $msg = $manifest['ReturnMessage'] ?? $manifest['message'] ?? 'Manifest Error';
                                return [
                                    'status' => 0,
                                    'message' => $msg,
                                ];
                            }
                        }       
                else if($get['data'][request('courier_id')]['courier_id'] =='5'){
                    $coll_value =0;
                    $awb_no ='';
                    if($order['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">C.O.D</span>'){
                        $pmode = 'cash';
                        $coll_value = $order['custom_total'];
                    }elseif($order['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">Pre-Paid</span>'){
                        $pmode = '';

                    }
                    if($get['data'][request('courier_id')]['mode'] =='fa-plane'){
                        $trsfr_type = 'B2C PRIORITY';
                    }else{
                        $trsfr_type = 'B2C SMART EXPRESS';
                    }
                    $dtdc = array(
                        'consignments' =>array( array(
                            'customer_code' =>'GL7569',
                            'service_type_id' =>$trsfr_type,
                            'load_type' =>'NON-DOCUMENT',
                            'mode'=>$pmode,
                            'consignment_type' =>'Forward',
                            'dimension_unit' =>'cm',
                            'length' =>$order['length'],
                            'width' =>$order['breadth'],
                            'height' =>$order['height'],
                            'num_pieces' =>count($order['detail']),
                            'weight_unit' =>'kg',
                            'weight' =>$order['weight']/1000,
                            'customer_reference_number' =>$order['order_id'],
                            'commodity_id' =>'7',
                            'reference_number' =>'',
                            'declared_value' =>$order['total'],
                            'cod_amount' =>$coll_value,
                            'cod_collection_mode'=>$pmode,
                            'origin_details' => array(
                                'name' =>$warehouse['name'],
                                'phone' =>$warehouse['phone'],
                                'alternate_phone' =>'8851678080',
                                'address_line_1' =>$warehouse['address'],
                                'address_line_2' =>$warehouse['address_2'],
                                'pincode' =>$warehouse['pincode'],
                                'city' =>$warehouse['city'],
                                'state' =>$warehouse['state'],
                            ),
                            'destination_details'=> array(
                                'name' =>$order['ship_fname'].' '.$order['ship_lname'],
                                'phone' =>$order['ship_phone'],
                                'alternate_phone' =>'9958523480',
                                'address_line_1' =>$order['ship_address'],
                                'address_line_2' =>$order['ship_address_2'],
                                'pincode' =>$order['ship_pincode'],
                                'city' =>$order['ship_city'],
                                'state' =>$order['ship_state'],
                            )
                        )
                    ));
                    $res_dtdc = Integration::shipment_dtdc(json_encode($dtdc));
                    //checking api logs
                   api_logs(json_encode($dtdc),$res_dtdc,$order['id'],'5');
                    $res_dtdc = json_decode($res_dtdc,true);
                    if(isset($res_dtdc['data']) && isset($res_dtdc['data'][0])){
                        if($res_dtdc['data'][0]['success']){
                            $awb_no = $res_dtdc['data'][0]['reference_number'];
                        }else{
                            return [
                                'status' => 0,
                                'message' => $res_dtdc['data'][0]['message'],
                            ];
                        }
                    }
                }
                else if($get['data'][request('courier_id')]['courier_id'] =='6'){
                    $coll_value =0;
                    $awb_no ='';
                    $p_type='';
                    if(strip_tags($order['payment_mode']) =='C.O.D'){
                        $coll_value = $order['custom_total'];
                        if($get['data'][request('courier_id')]['mode'] =='fa-plane'){
                            $p_type = 'ACC';
                            $is_surface = false;
                        }else{
                            $p_type = 'WKO';
                            $is_surface = true;
                        }
                    }elseif(strip_tags($order['payment_mode']) =='Pre-Paid'){
                        $pmode = 'prepaid';
                        if($get['data'][request('courier_id')]['mode'] =='fa-plane'){
                            $p_type = 'ACP';
                            $is_surface = false;
                        }else{
                            $p_type = 'WKO';
                            $is_surface = true;
                        }
                    }
                    $smartr = array( array(
                        'packageDetails' =>array(
                            'awbNumber' =>'',
                            'orderNumber' =>$order['order_id'],
                            'productType' =>$p_type,
                            'collectableValue' =>(string)($coll_value),
                            'declaredValue' =>(string)($order['total']),
                            'itemDesc' =>"Multiple Items",
                            'dimensions' =>$order['length'].'~'.$order['breadth'].'~'.$order['height'].'~'.count($order['detail']).'~'.($order['weight']/1000).'~0',
                            'pieces' =>(string)count($order['detail']),
                            'weight' =>(string)$order['weight']/1000,
                            'invoiceNumber' =>'',
                        ),
                        'deliveryDetails' =>array(
                            'toName'=> $order['ship_fname'].' '.$order['ship_lname'],
                            'toAdd'=> $order['ship_address'].' '.$order['ship_address_2'],
                            'toCity'=> $order['ship_city'],
                            'toState'=> $order['ship_state'],
                            'toPin'=> $order['ship_pincode'],
                            'toMobile'=> $order['ship_phone'],
                            'toAddType'=> 'Home',
                            'toLat'=> '',
                            'toLng'=> '',
                            'toEmail'=>  $order['ship_email']
                        ),
                        'pickupDetails' =>array(
                            'fromName'=> $warehouse['name'],
                            'fromAdd'=> $warehouse['address'].' '.$warehouse['address_2'],
                            'fromCity'=> $warehouse['city'],
                            'fromState'=> $warehouse['state'],
                            'fromPin'=> $warehouse['pincode'],
                            'fromMobile'=> $warehouse['phone'],
                            'fromAddType'=> 'Seller',
                            'fromLat'=> '',
                            'fromLng'=> '',
                            'fromEmail'=> $warehouse['email']
                        ),
                        'returnDetails' =>array(
                            'rtoName'=> $warehousereturn['name'],
                            'rtoAdd'=> $warehousereturn['address'].' '.$warehousereturn['address_2'],
                            'rtoCity'=> $warehousereturn['city'],
                            'rtoState'=> $warehousereturn['state'],
                            'rtoPin'=> $warehousereturn['pincode'],
                            'rtoMobile'=> $warehousereturn['phone'],
                            'rtoAddType'=> 'Seller',
                            'rtoLat'=> '',
                            'rtoLng'=> '',
                            'rtoEmail'=> $warehousereturn['email']
                        ),
                        'additionalInformation'=> array(
                            'customerCode'=> '',
                            'essentialFlag'=> '',
                            'otpFlag'=> '',
                            'dgFlag'=> '',
                            'isSurface'=> $is_surface,
                            'isReverse'=> 'false',
                            'sellerGSTIN'=> $warehouse['gst_no'],
                            'sellerERN'=> $order['e_bill_no']
                        )
                    ));
                    $res_smartr= Integration::shipment_smartr(json_encode($smartr));
                    //checking api logs
                    api_logs(json_encode($smartr),$res_smartr ,$order['id'],'6');
                    $res_smartr = json_decode($res_smartr,true);
                    if(isset($res_smartr['success']) && !empty($res_smartr['total_success'])){
                        $awb_no = $res_smartr['total_success'][0]['awbNumber'];
                    }else{
                        if(isset($res_smartr['total_failure'][0]))
                        return [
                            'status' => 0,
                            'message' => $res_smartr['total_failure'][0]['error'],
                        ];
                    }
                }
                else if($get['data'][request('courier_id')]['courier_id'] =='7'){
                    $coll_value =0;
                    $awb_no ='';
                    $p_type='';
                    $service_code = 'ECONOMY';
                    if($get['data'][request('courier_id')]['mode'] =='fa-plane'){
                        $service_code = 'REGULAR';
                    }
                    if($order['cancel_date'] !='' || $order['cancel_date'] != null){
                        $time = explode(' ',$order['cancel_date'])[1];
                        $time = str_replace(':','',$time);
                        $date = explode(' ',$order['cancel_date'])[0];
                        $day = explode('-',$date)[2];
                        $o_id =$time.$order['order_id'].$day;
                    }else{
                        $o_id = $order['order_id'];
                    }
                    if(strlen($o_id)>10){
                        $o_id = substr($o_id,0,10);
                    }
                    if(strip_tags($order['payment_mode']) =='C.O.D'){
                        $coll_value = $order['custom_total'];
                        $t_id = 'TOSC'.sprintf('%010d', $o_id);
                        $p_type="COD";
                    }elseif(strip_tags($order['payment_mode']) =='Pre-Paid'){
                        $t_id = 'TOSP'.sprintf('%010d', $o_id);
                        $p_type="Prepaid";
                    }
                    // echo $t_id;die;
                    $shipment_items = array();
                    $t_qty=0;
                    foreach($order['detail'] as $detail){
                        $t_qty = $t_qty+$detail['qty'];
                        $shipment_items[] =array(
                            'product_name'=>$detail['name'],

                        );
                    }
    //                 echo '<pre>';print_r($shipment_items);die;
                    $ekart = array(
                        'seller_name'=>"AFRAMAX LOGISTICS INDIA PRIVATE LIMITED",
                        'seller_address'=>"Second Floor, SF 03, Eros Metro Mall, Plot No 08, Sector 14 Dwarka, New Delhi, South West Delhi, Delhi, 110075",
                        'seller_gst_tin'=>"07AARCA0673K1ZB",
                        'consignee_gst_amount'=>0,
                        'order_number'=>(string)($order['id'].time()),
                        'invoice_number'=>$order['order_id'],
                        'invoice_date'=>date('Y-m-d'),
                        'consignee_name'=>$order['ship_fname'].' '.$order['ship_lname'],
                        'consignee_alternate_phone' => null,
                        'products_desc' => !empty($order['detail']) ? $order['detail'][0]['name'] : 'None',
                        'payment_mode'=>$p_type,
                        'category_of_goods'=>'Others',
                        'total_amount'=>(int)($order['total'] < 1 ? 1 : $order['total']),
                        'tax_value'=>0,
                        'taxable_amount'=>(int)($order['total'] < 1 ? 1 : $order['total']),
                        'commodity_value'=>(new NumberFormatter("en", NumberFormatter::SPELLOUT))->format($order['total'] < 1 ? 1 : $order['total']),
                        'cod_amount'=>(int)$coll_value,
                        'quantity'=>$t_qty,
                        'weight'=>$order['weight'],
                        'length'=>$order['length'],
                        'height'=>$order['height'],
                        'width'=>$order['breadth'],
                        'drop_location'=>array(
                            'drop_location'=>'Home',
                            'address'=>$order['ship_address'].' '.$order['ship_address_2'],
                            'city'=>$order['ship_city'],
                            'state'=>$order['ship_state'],
                            'country'=>'India',
                            'name'=>$order['ship_fname'].' '.$order['ship_lname'],
                            'phone'=>(int)$order['ship_phone'],
                            'pin'=>(int)$order['ship_pincode'],
                        ),
                        'pickup_location'=>array(
                            'location_type'=>'Office',
                            'address'=>$warehouse['address'].' '.$warehouse['address_2'],
                            'city'=>$warehouse['city'],
                            'state'=>$warehouse['state'],
                            'country'=>'India',
                            'name'=>$warehouse['name'].$warehouse['id'],
                            'phone'=>(int)$warehouse['phone'],
                            'pin'=>(int)$warehouse['pincode'],
                        ),
                        'return_location'=>array(
                            'location_type'=>'Office',
                            'address'=>$warehousereturn['address'].' '.$warehousereturn['address_2'],
                            'city'=>$warehousereturn['city'],
                            'state'=>$warehousereturn['state'],
                            'country'=>'India',
                            'name'=>$warehousereturn['name'].$warehousereturn['id'],
                            'phone'=>(int)$warehousereturn['phone'],
                            'pin'=>(int)$warehousereturn['pincode'],
                        ),
                        'qc_details'=>array(
                            'qc_shipment'=>false,
                            'product_name'=>'noqc'
                        ),
                        'items'=>$shipment_items

                    );
                    $pklocn = array(
                        'alias'=>$warehouse['name'].$warehouse['id'],
                        'phone' => (int) substr(preg_replace('/\D/', '', $warehouse['phone']), -10),
                        'address_line1'=>$warehouse['address'],
                        'address_line2'=>$warehouse['address_2'],
                        'pincode'=>(int)$warehouse['pincode'],
                        'city'=>$warehouse['city'],
                        'state'=>$warehouse['state'],
                        'country'=>'India',
                    );
    //                 echo '<pre>';print_r($ekart);die;
                    $res_ekart= Integration_more::shipment_ekart(json_encode($ekart),json_encode($pklocn));

                    //checking api logs
                    api_logs(json_encode($ekart),$res_ekart ,$order['id'],'7');
                    $res_ekart = json_decode($res_ekart,true);
                    if(isset($res_ekart['tracking_id'])){
                        $awb_no = $res_ekart['tracking_id'];
                    }else{
                        if(isset($res_ekart['description'])){
                        return [
                            'status' => 0,
                            'message' => $res_ekart['description'],
                        ];
                        }else{
                            return [
                                'status' => 0,
                                'message' => 'Ekart is not responding',
                            ];
                        }

                    }

                }
                else if($get['data'][request('courier_id')]['courier_id'] =='8'){
                    $coll_value =0;
                    $awb_no = $awb ='';
                    $p_type='';
                    $arrayawb = array(
                        'count' =>1
                    );
                    $awb_shadowfax = Integration_more::get_awb_number_shadowfax('forward',json_encode($arrayawb,true));
                    $awb_shadowfax =  json_decode($awb_shadowfax,true);
                    if(isset($awb_shadowfax['message']) && $awb_shadowfax['message'] =='success'){
                        $awb = $awb_shadowfax['awb_numbers'][0];
                    }
                    if($awb !=''){
                        if(strip_tags($order['payment_mode']) =='C.O.D'){
                            $pmode = 'COD';
                            $coll_value = $order['custom_total'];
                        }elseif(strip_tags($order['payment_mode']) =='Pre-Paid'){
                            $pmode = 'Prepaid';
                        }
                        if($get['data'][request('courier_id')]['mode'] =='fa-plane'){
                            $trsfr_type = 'ndd';
                        }else{
                            $trsfr_type = 'regular';
                        }

                        $shipment_items = array();
                        foreach($order['detail'] as $detail){
                            $shipment_items[] =array(
                                'sku_name'=>$detail['code'],
                                'hsn_code'=>'',
                                'invoice_no'=>'invmy_'.date('my').$detail['user_id'],
                                'category'=>'miscellaneous',
                                'price'=>$detail['price'],
                                'seller_details' =>array(
                                    'seller_name'=>'Hyloship',
                                    'seller_address'=>'',
                                    'seller_state'=>'',
                                    'gstin_number'=>'',
                                ),
                                'taxes' =>array(
                                    "cgst"=>'0.00',
                                    "sgst"=>'0.00',
                                    "igst"=>'0.00',
                                    "total_tax"=>$detail['tax_amount'],
                                ), 
                                'additional_details'=>array(
                                    'quantity'=>$detail['qty'],
                                )  
                            );
                        }

                        $shadowfax_data = array(
                            'order_type' =>'marketplace',
                            'order_details'=>array(
                                'client_order_id'=>$order['order_id'],
                                'awb_number'=>$awb,
                                'actual_weight'=>$order['weight'],
                                'volumetric_weight'=>($order['length']*$order['breadth']*$order['height'])/5,
                                'product_value'=>$order['total'],
                                'payment_mode'=>$pmode,
                                'cod_amount'=>$coll_value,
                                'total_amount'=>$order['total'],
                                'eway_bill'=>$order['e_bill_no'],
                                'gstin_number'=>$order['ship_gstin'],
                                'order_service'=>'regular',
                            ),
                            'customer_details'=>array(
                                'name'=>$order['ship_fname'].' '.$order['ship_lname'],
                                'contact'=>$order['ship_phone'],
                                'address_line_1'=>$order['ship_address'],
                                'address_line_2'=>$order['ship_address_2'],
                                'city'=>$order['ship_city'],
                                'state'=>$order['ship_state'],
                                'pincode'=>$order['ship_pincode'],
                                'latitude'=>$order['ship_latitude'],
                                'longitude'=>$order['ship_longitude'],
                            ),
                            'pickup_details'=>array(
                                'name'=>$warehouse['company'],
                                'contact'=>$warehouse['phone'],
                                'address_line_1'=>$warehouse['address'],
                                'address_line_2'=>$warehouse['address_2'],
                                'city'=>$warehouse['city'],
                                'state'=>$warehouse['state'],
                                'pincode'=>$warehouse['pincode'],
                                'latitude'=>$warehouse['latitude'],
                                'longitude'=>$warehouse['longitude'],
                            ),
                            'rts_details'=>array(
                                'name'=>$warehousereturn['company'],
                                'contact'=>$warehousereturn['phone'],
                                'address_line_1'=>$warehousereturn['address'],
                                'address_line_2'=>$warehousereturn['address_2'],
                                'city'=>$warehousereturn['city'],
                                'state'=>$warehousereturn['state'],
                                'pincode'=>$warehousereturn['pincode'],
                            ),
                            'product_details'=>$shipment_items,
                        );
                        $res_shadowfax= Integration_more::shipment_shadowfax('forward',json_encode($shadowfax_data));
                        //checking api logs
                        api_logs(json_encode($shadowfax_data), $res_shadowfax ,$order['id'],'8');
                        $res_shadowfax = json_decode($res_shadowfax,true);
                        if(isset($res_shadowfax['message']) && $res_shadowfax['message'] =='Success'){
                            $awb_no = $res_shadowfax['data']['awb_number'];
                        }else{
                            $e_msg ='Issue in data';
                            if(isset($res_shadowfax['errors'])){
                                $e_msg = is_array($res_shadowfax['errors']) ? json_encode($res_shadowfax['errors']) : $res_shadowfax['errors'];
                            }
                            else if(isset($res_shadowfax['errorCode'])){
                                $e_msg =$res_shadowfax['errorCode'];
                            }
                            else if(isset($res_shadowfax['message'])){
                                $e_msg =$res_shadowfax['message'];
                            }
                            return [
                                'status' => 0,
                                'message' => $e_msg,
                            ];
                        }

                    }else{
                            return [
                                'status' => 0,
                                'message' => 'Issue in generating awb no. for shadowfax',
                            ];
                        }


                }
                else if($get['data'][request('courier_id')]['courier_id'] =='9'){
                    $coll_value =0;
                    $awb_no = $awb ='';
                    $p_type='';

                    if(strip_tags($order['payment_mode']) =='C.O.D'){
                        $pmode = 'COD';
                        $coll_value = $order['custom_total'];
                            $cod_Array = array(array(
                            'id'=>'CollectOnDelivery',
                            'amount'=>array(
                                'unit'=>'INR',
                                'value'=>$order['total'],
                            )

                           ));
                    }elseif(strip_tags($order['payment_mode']) =='Pre-Paid'){
                        $pmode = 'Prepaid';
                            $cod_Array =array();
                    }
                    if($get['data'][request('courier_id')]['mode'] =='fa-plane'){
                        $trsfr_type = 'SWA-IN-OA';
                    }else{
                        $trsfr_type = 'SWA-IN-OA';
                    }

                    $shipment_items = array();
                    foreach($order['detail'] as $detail){
                        $shipment_items[] =array(
                            'itemValue'=>array(
                                'value'=>$detail['price'],
                                'unit'=>'INR',
                            ),
                            'weight'=>array(
                                'unit'=>'GRAM',
                                'value'=>50.0,
                            ),
                            'description'=>$detail['name'],
                            'itemIdentifier'=>'"'.$detail['id'].'"',
                            'quantity'=>$detail['qty'],

                        );
                    }
                    if(strlen($order['ship_address']) >55){
                        $order['ship_address'] = trim(preg_replace('/\s+/', ' ', substr($order['ship_address'],0,55)));
                        $order['ship_address_2'] = trim(preg_replace('/\s+/', ' ', substr($order['ship_address'],55,strlen($order['ship_address'])).' '.$order['ship_address_2']));
                    }
                    if(strlen($warehouse['address']) >55){
                        $warehouse['address'] = trim(preg_replace('/\s+/', ' ', substr($warehouse['address'],0,55)));
                        $warehouse['address_2'] = trim(preg_replace('/\s+/', ' ', substr($warehouse['address'],55,strlen($warehouse['address'])).' '.$warehouse['address_2']));
                    }
                    if(strlen($warehousereturn['ship_address']) >55){
                        $warehousereturn['address'] = trim(preg_replace('/\s+/', ' ', substr($warehousereturn['address'],0,55)));
                        $warehousereturn['address_2'] = trim(preg_replace('/\s+/', ' ', substr($warehousereturn['address'],55,strlen($warehousereturn['address'])).' '.$warehousereturn['address_2']));
                    }
                    $ats_data = array(
                        'shipTo'=>array(
                            'name'=>$order['ship_fname'].' '.$order['ship_lname'],
                            'addressLine1'=>$order['ship_address'],
                            'addressLine2'=>$order['ship_address_2'],
                            // 'companyName'=>$order['ship_address_2'],
                            'stateOrRegion'=>$order['ship_state'],
                            'city'=>$order['ship_city'],
                            'countryCode'=>'IN',
                            'postalCode'=>$order['ship_pincode'],
                            'email'=>$order['ship_email'],
                            'phoneNumber'=>$order['ship_phone'],
                        ),
                        'shipFrom'=>array(
                            'name'=>$warehouse['name'],
                            'addressLine1'=>$warehouse['address'],
                            'addressLine2'=>$warehouse['address_2'],
                            'companyName'=>$warehouse['company'],
                            'stateOrRegion'=>$warehouse['state'],
                            'city'=>$warehouse['city'],
                            'countryCode'=>'IN',
                            'postalCode'=>$warehouse['pincode'],
                            'email'=>$warehouse['email'],
                            'phoneNumber'=>$warehouse['phone'],
                        ),
                        'returnTo'=>array(
                            'name'=>$warehousereturn['name'],
                            'addressLine1'=>$warehousereturn['address'],
                            'addressLine2'=>$warehousereturn['address_2'],
                            'companyName'=>$warehousereturn['company'],
                            'stateOrRegion'=>$warehousereturn['state'],
                            'city'=>$warehousereturn['city'],
                            'countryCode'=>'IN',
                            'postalCode'=>$warehousereturn['pincode'],
                            'email'=>$warehousereturn['email'],
                            'phoneNumber'=>$warehousereturn['phone'],
                        ),
                        'packages'=>array(
                            array(
                                'dimensions' =>array(
                                    'length'=>$order['length'],
                                    'width'=>$order['breadth'],
                                    'height'=>$order['height'],
                                    'unit'=>'CENTIMETER',
                                ),
                                'weight' =>array(
                                    'unit'=>'GRAM',
                                    'value'=>$order['weight'],
                                ),
                                'insuredValue' =>array(
                                    'unit'=>'INR',
                                    'value'=>$order['total'],
                                ),
                                'isHazmat'=>false,
                                'sellerDisplayName'=>'',
                                'packageClientReferenceId'=>$order['order_id'],
                                'items'=>$shipment_items,

                            ),
                        ),
                            'valueAddedServiceDetails'=>$cod_Array,
                        'taxDetails'=>array(
                            array(
                                'taxType'=>'GST',
                                'taxRegistrationNumber'=>'"'.$order['ship_gstin'].'"',
                            ),
                        ),
                        'channelDetails'=>array(
                            'channelType'=>'EXTERNAL',
                        ),
                        'serviceSelection'=>array(
                            'serviceId'=>array(
                                $trsfr_type
                            ),
                        ),
                        'labelSpecifications'=>array(
                            'format'=>'PDF',
                            'size'=>array(
                                'width'=>4,
                                'length'=>6,
                                'unit'=>'INCH',
                            ),
                            'dpi'=>203,
                            'pageLayout'=>'DEFAULT',
                            'needFileJoining'=>false,
                            'requestedDocumentTypes'=>array(
                                'LABEL'
                            ),
                        ),
                    );
    //                if($orde_user_id == '1'){
    //                    echo '<pre>';print_r(json_encode($ats_data));die;
    //                }
                    $res_ats= Integration_more::shipment_ats(json_encode($ats_data));
    //                
                    //checking api logs
                    api_logs(json_encode($ats_data), $res_ats,$order['id'],'9');
                    $re_array = json_decode($res_ats,true);
                    if(isset($re_array['payload']) && isset($re_array['payload']['packageDocumentDetails']) && isset($re_array['payload']['packageDocumentDetails'][0]) && isset($re_array['payload']['packageDocumentDetails'][0]['trackingId'])){
                        $awb_no =$re_array['payload']['packageDocumentDetails'][0]['trackingId'];
                        if(isset($re_array['payload']['shipmentId'])){
                            $shipment_id = $re_array['payload']['shipmentId'];
                        }
                    }else{
                        $e_msg ='Issue in data';
                        if(isset($re_array['errors'])){
                            if(isset($re_array['errors'][0]['details'])){
                                $error_d = explode('detected:',$re_array['errors'][0]['details']);
                                if(count($error_d) >1){
                                    $e_msg =$error_d[1];
                                }else{
                                    $e_msg =$re_array['errors'][0]['details'];
                                }

                            }
                        }
                        return [
                            'status' => 0,
                            'message' => $e_msg,
                        ];
                    }


                }
                else if($get['data'][request('courier_id')]['courier_id'] =='10'){
                    $pmode ='';
                    $coll_value =0;
                    if($order['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">C.O.D</span>'){
                        $pmode = 'COD';
                        $coll_value = $order['custom_total'];
                    }elseif($order['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">Pre-Paid</span>'){
                        $pmode = 'PREPAID';
                    }
                    if(strlen($order['ship_address']) >55){
                        $order['ship_address'] = trim(preg_replace('/\s+/', ' ', substr($order['ship_address'],0,55)));
                        $order['ship_address_2'] = trim(preg_replace('/\s+/', ' ', substr($order['ship_address'],55,strlen($order['ship_address'])).' '.$order['ship_address_2']));
                    }
                    if(strlen($warehouse['address']) >55){
                        $warehouse['address'] = trim(preg_replace('/\s+/', ' ', substr($warehouse['address'],0,55)));
                        $warehouse['address_2'] = trim(preg_replace('/\s+/', ' ', substr($warehouse['address'],55,strlen($warehouse['address'])).' '.$warehouse['address_2']));
                    }
                    if(strlen($warehousereturn['ship_address']) >55){
                        $warehousereturn['address'] = trim(preg_replace('/\s+/', ' ', substr($warehousereturn['address'],0,55)));
                        $warehousereturn['address_2'] = trim(preg_replace('/\s+/', ' ', substr($warehousereturn['address'],55,strlen($warehousereturn['address'])).' '.$warehousereturn['address_2']));
                    }
                    $shipment_items = array();
                    foreach($order['detail'] as $detail){
                        $shipment_items[] =array(
                            'name'=>$detail['name'],
                            'description'=>$detail['name'],
                            'skuCode'=>$detail['code'],
                            "item_value"=>$detail['price'],
                            'quantity'=>$detail['qty'],

                        );
                    }
                    $blitz_data = array(
                        'channelId' =>"Aframax",
                        'returnShipmentFlag' =>"false",
                        'Shipment'=>array(
                            'code'=>$order['order_id'].date('ymdHis'),
                            'orderCode'=>$order['order_id'].date('ymdHis').'21',
                            'weight'=>(string)$order['weight'],
                            'length'=>(string)($order['length']*10),
                            'height'=>(string)($order['height']*10),
                            'breadth'=>(string)($order['breadth']*10),
                            'items'=>$shipment_items
                        ),
                        'deliveryAddressDetails'=>array(
                            "name"=>$order['ship_fname'].' '.$order['ship_lname'],
                            "phone"=>$order['ship_phone'],
                            "address1"=>$order['ship_address'],
                            "address2"=>$order['ship_address_2'],
                            "pincode"=>$order['ship_pincode'],
                            "city"=>$order['ship_city'],
                            "state"=>$order['ship_state'],
                            "country"=>"India",
                            // "lat"=>$order['ship_latitude'],
                            // "lng"=>$order['ship_longitude'],
                        ),
                        'pickupAddressDetails'=>array(
                            "name"=>$warehouse['name'],
                            "phone"=>$warehouse['phone'],
                            "address1"=>$warehouse['address'],
                            "address2"=>$warehouse['address_2'],
                            "pincode"=>$warehouse['pincode'],
                            "city"=>$warehouse['city'],
                            "state"=>$warehouse['state'],
                            "country"=>"India",
                            // "lat"=>$warehouse['latitude'],
                            // "lng"=>$warehouse['longitude'],
                            // "seller_name"=>$warehouse['contact_name'],
                            // "brand_name"=>$warehouse['company']
                        ),
                        'paymentMode'=>$pmode,
                        'totalAmount'=>(string)$order['total'],
                        'collectableAmount'=>(string)$coll_value
                    );
                    // echo json_encode($blitz_data);
                    $res_ats= Integration_more::shipment_blitz(json_encode($blitz_data));
                    api_logs(json_encode($blitz_data), $res_ats,$order['id'],'10');
                    $re_array = json_decode($res_ats,true);
                    if(isset($re_array['status']) && $re_array['status'] == 'SUCCESS'){
                        $awb_no =$re_array['waybill'];
                        $label_print =$re_array['shippingLabel'];
                    }else{
                        $e_msg ='Issue in data';
                        if($re_array['status'] == 'FAILED'){
                            $e_msg =$re_array['message'];
                        }
                        return [
                            'status' => 0,
                            'message' => $e_msg,
                        ];
                    }
                }
                else if($get['data'][request('courier_id')]['courier_id'] =='11'){
                    $res = $this->assignShreeMaruti($order, $warehouse, $warehousereturn, $get);
                    if ($res['status'] == 0) {
                        return $res;
                    }
                    $awb_no = $res['awb_no'] ?? '';
                    $cawb_no = $res['cawb_no'] ?? null;
                    $label_print = $res['label_print'] ?? null;
                    $shipment_id = $res['shipment_id'] ?? null;
                }
                else if($get['data'][request('courier_id')]['courier_id'] =='12'){
                    $res = $this->assignPicknDel($order, $warehouse, $warehousereturn, $get);
                    if ($res['status'] == 0) {
                        return $res;
                    }
                    $awb_no = $res['awb_no'] ?? '';
                    $cawb_no = $res['cawb_no'] ?? null;
                    $label_print = $res['label_print'] ?? null;
                    $shipment_id = $res['shipment_id'] ?? null;
                }
                else if($get['data'][request('courier_id')]['courier_id'] =='13'){
                    $res = $this->assignParcelUncle($order, $warehouse, $get, $orde_user_id);
                    if ($res['status'] == 0) {
                        return $res;
                    }
                    $awb_no = $res['awb_no'] ?? '';
                    $cawb_no = $res['cawb_no'] ?? null;
                    $label_print = $res['label_print'] ?? null;
                    $shipment_id = $res['shipment_id'] ?? null;
                }
                else if($get['data'][request('courier_id')]['courier_id'] =='14'){
                    $res = $this->assignIndiaPost($order, $warehouse, $company_id);
                    if ($res['status'] == 0) {
                        return $res;
                    }
                    $awb_no = $res['awb_no'] ?? '';
                    $cawb_no = $res['cawb_no'] ?? null;
                    $label_print = $res['label_print'] ?? null;
                    $shipment_id = $res['shipment_id'] ?? null;
                }
            }
        }else{
            if($get['data'][request('courier_id')]['courier_id'] =='1'){
                $pmode ='';
                $coll_value =0;
                $pmode ='rev';
                $awb = Integration::get_awb_number($pmode);
              //echo $awb;die;
                if($awb !=''){
                    $response = json_decode($awb,true);
                    if(isset($response['awb']) && count($response['awb']) >0){
                        $awb_no = $response['awb'][0];
                        $manifest_d =array(
                            'ECOMEXPRESS-OBJECTS' =>array(
                                'SHIPMENT' => array(
                                'AWB_NUMBER'=>"$awb_no",
                                'ORDER_NUMBER'=>$order['order_id'].'-R',
                                'PRODUCT'=>'REV',
                                'REVPICKUP_NAME'=>$order['ship_fname'].' '.$order['ship_lname'],
                                'REVPICKUP_ADDRESS1'=>$order['ship_address'],
                                'REVPICKUP_ADDRESS2'=>$order['ship_address_2'],
                                'REVPICKUP_ADDRESS3'=>'',
                                'REVPICKUP_CITY'=>$order['ship_city'],
                                'REVPICKUP_PINCODE'=>$order['ship_pincode'],
                                'REVPICKUP_STATE'=>$order['ship_state'],
                                'REVPICKUP_MOBILE'=>$order['ship_phone'],
                                'REVPICKUP_TELEPHONE'=>'',
                                'PIECES'=>count($order['detail']),
                                'COLLECTABLE_VALUE'=>$order['total'],
                                'DECLARED_VALUE'=>$order['total'],
                                'ACTUAL_WEIGHT'=>$order['weight']/1000,
                                'VOLUMETRIC_WEIGHT'=>($order['length']*$order['breadth']*$order['height'])/5000,
                                'LENGTH'=>$order['length'],
                                'BREADTH'=>$order['breadth'],
                                'HEIGHT'=>$order['height'],
                                'VENDOR_ID'=>'',
                                'DROP_NAME'=>$warehouse['contact_name'],
                                'DROP_ADDRESS_LINE1'=>$warehouse['address'],
                                'DROP_ADDRESS_LINE2'=>$warehouse['address_2'],
                                'DROP_PINCODE'=>$warehouse['pincode'],
                                'DROP_MOBILE'=>$warehouse['phone'],
                                'DROP_PHONE'=>$warehouse['phone'],
                                'ITEM_DESCRIPTION'=>'Multiple items',
                                'DG_SHIPMENT'=>false
                                //Pass ""true"" in case shipment package contains any item that is restricted for air travel as per DGCA guidelines. Otherwise pass ""false"
                                
                            )));
                      	//echo json_encode($manifest_d,true);die;
                            $manifest = Integration::rev_manifest_awb(json_encode($manifest_d,true));
                            //checking api logs
                            api_logs(json_encode($manifest_d,true),$manifest,$order['id'],'1');
                            $manifest = json_decode($manifest,true);
                     // echo '<pre>';print_r($manifest);
                            if(isset($manifest['RESPONSE-OBJECTS']['AIRWAYBILL-OBJECTS']['AIRWAYBILL']) && $manifest['RESPONSE-OBJECTS']['AIRWAYBILL-OBJECTS']['AIRWAYBILL']['success']){ }else{$awb_no ='';
                                $msg = 'Wrong Data Passed';
                                if(isset($manifest['RESPONSE-OBJECTS']) && isset($manifest['RESPONSE-OBJECTS']['RESPONSE-COMMENT'])){
                                    $msg = $manifest['RESPONSE-OBJECTS']['RESPONSE-COMMENT'];
                                }
                                return [
                                    'status' => 0,
                                    'message' => $msg,
                                ];
                            }
                    }else{
                        return [
                            'status' => 0,
                            'message' => 'Awb not working, please try after sometime2',
                        ];
                    }
                }else{
                    return [
                        'status' => 0,
                        'message' => 'Payment mode not found',
                    ];
                }
            }
            else if($get['data'][request('courier_id')]['courier_id'] =='2'){
                // echo 'hi';die;
                $coll_value =0;
                $pmode ='Pickup';
                if($get['data'][request('courier_id')]['mode'] =='fa-plane'){
                    $trsfr_type = 'Express';
                    $ttype = 'a';
                }else{
                    $trsfr_type = 'Surface';
                    $ttype = 's';
                }
                $awb = Integration::get_awb_number_delhivary($pmode,$ttype);
                $awb = json_decode($awb,true);
                $awb_no ='';
                if((int)($awb) == $awb){
                    $awb_no = $awb;
                }
                
                $ware_country = Country::where('id',$warehouse['country_id'])->first('name');
                $delhivery_data = array(
                    'shipments'=> array(array(
                        'waybill' => $awb_no,
                        'name' => $order['ship_fname'].' '.$order['ship_lname'],
                        'order' => $order['order_id'],
                        'products_desc' => 'Multiple items',
                        // 'order_date' => $order['created_at'],
                        'payment_mode' => $pmode,
                        'total_amount' => $order['total'],
                        'cod_amount' => $coll_value,
                        'add' => str_replace('&','and', $order['ship_address'].' '.$order['ship_address_2']),
                        'city' => $order['ship_city'],
                        'state' => $order['ship_state'],
                        'phone' => $order['ship_phone'],
                        'pin' => $order['ship_pincode'],
                        'shipment_width' => $order['breadth'],
                        'shipment_height' => $order['height'],
                        'shipment_length' => $order['length'],
                        'quantity' => count($order['detail']),
                        'weight' => $order['weight'],

                    )),
                    'pickup_location' => array(
                        'add' =>str_replace('&','and', $warehouse['address'].' '.$warehouse['address_2']),
                        'city' =>$warehouse['city'],
                        'country' =>$ware_country['name'],
                        'name' =>$warehouse['company'].$ttype,
                        'phone' =>$warehouse['phone'],
                        'pin' =>$warehouse['pincode'],
                        
                    )
                );
                try {
                    $ship_delhivary = Integration::shipment_rev_delhivary('format=json&data='.urlencode(json_encode($delhivery_data)),$ttype);
                    //checking api logs
                    api_logs(json_encode($delhivery_data),$ship_delhivary ,$order['id'],'2');
                    $d_data = json_decode($ship_delhivary,true);
                    if (!is_array($d_data)) {
                        throw new \Exception('Invalid or empty response from Delhivery API');
                    }
                } catch (\Throwable $e) {
                    $d_data = ['success' => false, 'rmk' => 'Delhivery API error: ' . $e->getMessage()];
                }
                if(!$d_data['success']){
                    $rmkk ='';$awb_no ='';
                    if(isset($d_data['packages'][0]) && isset($d_data['packages'][0]['remarks'])){
                        $rmk =$d_data['packages'][0]['remarks'];
                        for($k=0;$k<count($rmk);$k++){
                            $rmkk .=$rmk[$k].',';
                        }
                        $rmkk = rtrim($rmkk,',');
                    }
                    if (stripos($rmkk, 'ClientWarehouse matching query does not exist') !== false) {
                        $this->autoRegisterDelhiveryWarehouse($warehouse, $ttype);
                        try {
                            $ship_delhivary = Integration::shipment_rev_delhivary('format=json&data='.urlencode(json_encode($delhivery_data)),$ttype);
                            api_logs(json_encode($delhivery_data),$ship_delhivary ,$order['id'],'2');
                            $d_data = json_decode($ship_delhivary,true);
                            if (is_array($d_data)) {
                                $rmkk ='';
                                if(isset($d_data['packages'][0]) && isset($d_data['packages'][0]['remarks'])){
                                    $rmk =$d_data['packages'][0]['remarks'];
                                    for($k=0;$k<count($rmk);$k++){
                                        $rmkk .=$rmk[$k].',';
                                    }
                                    $rmkk = rtrim($rmkk,',');
                                }
                            }
                        } catch (\Throwable $e) {
                            $d_data = ['success' => false, 'rmk' => 'Delhivery API error: ' . $e->getMessage()];
                        }
                    }
                    if($rmkk !=''){
                        return [
                            'status' => 0,
                            'message' => $rmkk,
                        ];
                    }else{
                        return [
                            'status' => 0,
                            'message' => $d_data['rmk'],
                        ];
                    }
                }
                
            }
            else if($get['data'][request('courier_id')]['courier_id'] =='5'){
                $coll_value =0;
                $awb_no ='';
                $pmode ='Reverse';
                if($get['data'][request('courier_id')]['mode'] =='fa-plane'){
                    $trsfr_type = 'B2C PRIORITY';
                }else{
                    $trsfr_type = 'B2C SMART EXPRESS';
                }
                $dtdc = array(
                    'consignments' =>array( array(
                        'customer_code' =>'GL7569',
                        'service_type_id' =>$trsfr_type,
                        'load_type' =>'NON-DOCUMENT',
                        'consignment_type' =>$pmode,
                        'dimension_unit' =>'cm',
                        'length' =>$order['length'],
                        'width' =>$order['breadth'],
                        'height' =>$order['height'],
                        'num_pieces' =>count($order['detail']),
                        'weight_unit' =>'kg',
                        'weight' =>$order['weight']/1000,
                        'customer_reference_number' =>$order['order_id'],
                        'commodity_id' =>'7',
                        'reference_number' =>'',
                        'declared_value' =>$order['total'],
                        'cod_amount' =>$coll_value,
                        'origin_details' => array(
                            
                            'name' =>$order['ship_fname'].' '.$order['ship_lname'],
                            'phone' =>$order['ship_phone'],
                            'alternate_phone' =>'9958523480',
                            'address_line_1' =>$order['ship_address'],
                            'address_line_2' =>$order['ship_address_2'],
                            'pincode' =>$order['ship_pincode'],
                            'city' =>$order['ship_city'],
                            'state' =>$order['ship_state'],
                        ),
                        'destination_details'=> array(
                            'name' =>$warehouse['name'],
                            'phone' =>$warehouse['phone'],
                            'alternate_phone' =>'8851678080',
                            'address_line_1' =>$warehouse['address'],
                            'address_line_2' =>$warehouse['address_2'],
                            'pincode' =>$warehouse['pincode'],
                            'city' =>$warehouse['city'],
                            'state' =>$warehouse['state'],
                        )
                    )
                ));
                $res_dtdc = Integration::shipment_rev_dtdc(json_encode($dtdc));
                //checking api logs
                api_logs(json_encode($dtdc), $res_dtdc ,$order['id'],'5');
                $res_dtdc = json_decode($res_dtdc,true);
               
                if(isset($res_dtdc['data']) && isset($res_dtdc['data'][0])){
                    if($res_dtdc['data'][0]['success']){
                        $awb_no = $res_dtdc['data'][0]['reference_number'];
                    }else{
                        return [
                            'status' => 0,
                            'message' => $res_dtdc['data'][0]['message'],
                        ];
                    }
                }
            }
            else if($get['data'][request('courier_id')]['courier_id'] =='6'){
                $coll_value =0;
                $awb_no ='';
                $p_type='';
                if($get['data'][request('courier_id')]['mode'] =='fa-plane'){
                    $p_type = 'ACR';
                    $is_surface = false;
                }else{
                    $p_type = 'WKO';
                    $is_surface = true;
                }
                $smartr = array( array(
                    'packageDetails' =>array(
                        'awbNumber' =>'',
                        'orderNumber' =>$order['order_id'],
                        'productType' =>$p_type,
                        'collectableValue' =>(string)($coll_value),
                        'declaredValue' =>(string)($order['total']),
                        'itemDesc' =>"Multiple Items",
                        'dimensions' =>$order['length'].'~'.$order['breadth'].'~'.$order['height'].'~'.count($order['detail']).'~'.($order['weight']/1000).'~0',
                        'pieces' =>(string)count($order['detail']),
                        'weight' =>(string)$order['weight']/1000,
                        'invoiceNumber' =>'',
                    ),
                    'deliveryDetails' =>array(
                        'toName'=> $warehouse['name'],
                        'toAdd'=> $warehouse['address'].' '.$warehouse['address_2'],
                        'toCity'=> $warehouse['city'],
                        'toState'=> $warehouse['state'],
                        'toPin'=> $warehouse['pincode'],
                        'toMobile'=> $warehouse['phone'],
                        'toAddType'=> 'Seller',
                        'toLat'=> '',
                        'toLng'=> '',
                        'toEmail'=> $warehouse['email']
                    ),
                    'pickupDetails' =>array(
                        'fromName'=> $order['ship_fname'].' '.$order['ship_lname'],
                        'fromAdd'=> $order['ship_address'].' '.$order['ship_address_2'],
                        'fromCity'=> $order['ship_city'],
                        'fromState'=> $order['ship_state'],
                        'fromPin'=> $order['ship_pincode'],
                        'fromMobile'=> $order['ship_phone'],
                        'fromAddType'=> 'Home',
                        'fromLat'=> '',
                        'fromLng'=> '',
                        'fromEmail'=>  $order['ship_email']
                    ),
                    'returnDetails' =>array(
                        'rtoName'=> $order['ship_fname'].' '.$order['ship_lname'],
                        'rtoAdd'=> $order['ship_address'].' '.$order['ship_address_2'],
                        'rtoCity'=> $order['ship_city'],
                        'rtoState'=> $order['ship_state'],
                        'rtoPin'=> $order['ship_pincode'],
                        'rtoMobile'=> $order['ship_phone'],
                        'rtoAddType'=> 'Home',
                        'rtoLat'=> '',
                        'rtoLng'=> '',
                        'rtoEmail'=>  $order['ship_email']
                    ),
                    'additionalInformation'=> array(
                        'customerCode'=> '',
                        'essentialFlag'=> '',
                        'otpFlag'=> '',
                        'dgFlag'=> '',
                        'isSurface'=> $is_surface,
                        'isReverse'=> 'false',
                        'sellerGSTIN'=> $warehouse['gst_no'],
                        'sellerERN'=> $order['e_bill_no']
                    )
                ));
                $res_smartr= Integration::shipment_smartr(json_encode($smartr));
                //checking api logs
                api_logs(json_encode($smartr),$res_smartr,$order['id'],'6');
                $res_smartr = json_decode($res_smartr,true);
                if(isset($res_smartr['success']) && !empty($res_smartr['total_success'])){
                    $awb_no = $res_smartr['total_success'][0]['awbNumber'];
                }else{
                    if(isset($res_smartr['total_failure'][0]))
                    return [
                        'status' => 0,
                        'message' => $res_smartr['total_failure'][0]['error'],
                    ];
                }
            }
            else if($get['data'][request('courier_id')]['courier_id'] =='7'){
                $coll_value =0;
                $awb_no ='';
                $p_type='';
                $service_code = 'RETURNS_BASIC_CHECK';
                
                if($order['cancel_date'] !='' || $order['cancel_date'] != null){
                    $time = explode(' ',$order['cancel_date'])[1];
                    $time = str_replace(':','',$time);
                    $date = explode(' ',$order['cancel_date'])[0];
                    $day = explode('-',$date)[2];
                    $o_id =$time.$order['order_id'].$day;
                }else{
                    $o_id = $order['order_id'];
                }
                if(strlen($o_id)>10){
                    $o_id = substr($o_id,0,10);
                }
                $t_id = 'TOSR'.sprintf('%010d', $o_id);
                if($get['data'][request('courier_id')]['mode'] =='fa-plane'){
                    $service_code = 'REGULAR';
                }
                $shipment_items = array();
                foreach($order['detail'] as $detail){
                    $shipment_items[] =array(
                        'product_id'=>$detail['code'],
                        'category'=>'miscellaneous',
                        'product_title'=>$detail['name'],
                        'quantity'=>$detail['qty'],
                        'cost'=>array(
                            'totalSaleValue' =>$detail['price']*$detail['qty'],
                            'totalTaxValue'=>$detail['tax_amount'],
                            'tax_breakup'=>array(
                                "cgst"=>'0.00',
                                "sgst"=>'0.00',
                                "igst"=>'0.00',
                            )
                        ),
                        'seller_details' =>array(
                            'seller_reg_name'=>'Hyloship',
                            'vat_id'=>'',
                            'cst_id'=>'',
                            'gstin_id'=>'',
                        ),
                        'hsn'=>'',
                        'item_attributes'=>array(
                            array(
                                'name'=>'order_id',
                                'value'=>(string)($detail['order_id']),
                            ),
                            array(
                                'name'=>'invoice_id',
                                'value'=>'invmy_'.date('my').$detail['user_id'],
                            ),
                        ),
                        'handling_attributes'=>array(),
                    );
                }
                $ekart = array(
                    'request_Id' =>'',
                    'client_name' =>'XYZ',
                    'services' =>array(array(
                        'service_code' =>'RETURNS_BASIC_CHECK',
                        'service_details' =>array(array(
                            'service_leg' =>'REVERSE',
                            'service_data' =>array(
                                'vendor_name' =>$order['channel'],
                                'amount_to_collect' =>$coll_value,
                                'delivery_type'=>'Small',
                                'source' =>array(
                                    'address'=>array(
                                        'first_name'=>$order['ship_fname'].' '.$order['ship_lname'],
                                        'address_line1'=>$order['ship_address'].' '.$order['ship_address_2'],
                                        'pincode'=>$order['ship_pincode'],
                                        'city'=>$order['ship_city'],
                                        'state'=>$order['ship_state'],
                                        'primary_contact_number'=>$order['ship_phone'],
                                    )
                                ),
                                'destination'=>array(
                                    'address'=>array(
                                        'first_name'=>$warehouse['name'],
                                        'address_line1'=>$warehouse['address'].' '.$warehouse['address_2'],
                                        'pincode'=>$warehouse['pincode'],
                                        'city'=>$warehouse['city'],
                                        'state'=>$warehouse['state'],
                                        'primary_contact_number'=>$warehouse['phone'],
                                    )
                                ),
                                
                            ),
                            'shipment'=>array(
                                'tracking_id' =>$t_id,
                                'shipment_value' =>$order['total'],
                                'shipment_dimensions' =>array(
                                    'length'=>array(
                                        'value'=>$order['length']
                                    ),
                                    'breadth'=>array(
                                        'value'=>$order['breadth']
                                    ),
                                    'height'=>array(
                                        'value'=>$order['height']
                                    ),
                                    'weight'=>array(
                                        'value'=>$order['weight']/1000
                                    ),
                                ),
                                'shipment_items'=>$shipment_items,
                            ),
                            
                            
                        ),),
                    ),),
                );
                // $res_ekart= '{"response":[{"tracking_id":"TOSR0000000049","status":"REQUEST_RECEIVED","status_code":200,"is_parked":"NOT_PARKED"}],"request_id":"ed86af0f-4c35-41db-acac-cad2079f0c02"}
                // ';
                $res_ekart= Integration_more::shipment_smartr(json_encode($ekart));
                //checking api logs
               api_logs(json_encode($ekart),$res_ekart,$order['id'],'7');
                // echo $res_ekart;die;
                $res_ekart = json_decode($res_ekart,true);
                if(isset($res_ekart['response'][0]) && $res_ekart['response'][0]['status'] =='REQUEST_RECEIVED'){
                    $awb_no = $res_ekart['response'][0]['tracking_id'];
                }else{
                    if(isset($res_ekart['response'][0]) && $res_ekart['response'][0]['status'] =='REQUEST_REJECTED'){
                    return [
                        'status' => 0,
                        'message' => $res_ekart['response'][0]['message'][0],
                    ];
                    }else{
                        return [
                            'status' => 0,
                            'message' => 'Ekart is not responding',
                        ];
                    }

                }
            }
            else if($get['data'][request('courier_id')]['courier_id'] =='8'){
                $coll_value =0;
                $awb_no = $awb ='';
                $p_type='';
                $arrayawb = array(
                    'count' =>1
                );
                $awb_shadowfax = Integration_more::get_awb_number_shadowfax('backward',json_encode($arrayawb,true));
                $awb_shadowfax =  json_decode($awb_shadowfax,true);
                if(isset($awb_shadowfax['message']) && $awb_shadowfax['message'] =='success'){
                    $awb = $awb_shadowfax['awb_numbers'][0];
                }
                if($awb !=''){
                    
                    $shipment_items = array();
                    foreach($order['detail'] as $detail){
                        $shipment_items[] =array(
                            'name'=>$detail['name'],
                            'sku_name'=>$detail['code'],
                            'price'=>$detail['price'],
                            'category'=>'miscellaneous',
                            'return_reason'=>'not required',
                            'qc_required'=>false,
                            'qc_rules'=>array(),
                            'seller_details' =>array(
                                'regd_name'=>'Hyloship',
                                'regd_address'=>'',
                                'state'=>'',
                                'gstin'=>'',
                            ),
                            'taxes' =>array(
                                "cgst_amount"=>'0.00',
                                "sgst_amount"=>'0.00',
                                "igst_amount"=>'0.00',
                                "total_tax_amount"=>$detail['tax_amount'],
                            ),
                            'hsn_code'=>'',
                            'invoice_no'=>'invmy_'.date('my').$detail['user_id'],
                            'additional_details'=>array(
                                'quantity_value'=>$detail['qty'],
                            )  
                        );
                    }
    
                    $shadowfax_data = array(
                        'client_order_number'=>$order['order_id'],
                        'client_request_id'=>$awb,
                        'total_amount'=>$order['total'],
                        'price'=>$order['total'],
                        'eway_bill'=>$order['e_bill_no'],
                        'address_attributes'=>array(
                            'name'=>$order['ship_fname'].' '.$order['ship_lname'],
                            'phone_number'=>$order['ship_phone'],
                            'address_line'=>$order['ship_address'].' '.$order['ship_address_2'],
                            'city'=>$order['ship_city'],
                            'state'=>$order['ship_state'],
                            'pincode'=>$order['ship_pincode'],
                            'latitude'=>$order['ship_latitude'],
                            'longitude'=>$order['ship_longitude'],
                        ),
                        'seller_attributes'=>array(
                            'name'=>$warehouse['company'],
                            'phone'=>$warehouse['phone'],
                            'address_line'=>$warehouse['address'].' '.$warehouse['address_2'],
                            'city'=>$warehouse['city'],
                            'email'=>$warehouse['email'],
                            'state'=>$warehouse['state'],
                            'pincode'=>$warehouse['pincode'],
                            'unique_code'=>'w'.$warehouse['id'],
                            
                        ),
                        'skus_attributes'=>$shipment_items

                    );
                    $res_shadowfax= Integration_more::shipment_shadowfax('backward',json_encode($shadowfax_data));
                    //checking api logs
                     api_logs(json_encode($shadowfax_data),$res_shadowfax,$order['id'],'8');
                    $res_shadowfax = json_decode($res_shadowfax,true);
                    if(isset($res_shadowfax['message']) && $res_shadowfax['message'] =='Failure'){
                        $e_msg ='Issue in data';
                        if(isset($res_shadowfax['errors'])){
                            if(gettype($res_shadowfax['errors']) =='string'){
                                $e_msg =$res_shadowfax['errors'];
                            }
                            if(gettype($res_shadowfax['errors']) =='array'){
                                $e_msg ='';
                                foreach($res_shadowfax['errors'] as $error):
                                    $e_msg .=$error;
                                endforeach;
                            }
                        }
                        if(isset($res_shadowfax['errorCode'])){
                            $e_msg =$res_shadowfax['errorCode'];
                        }
                        return [
                            'status' => 0,
                            'message' => $e_msg,
                        ];
                    }else if(isset($res_shadowfax['message']) && $res_shadowfax['message'] =='Success'){
                        $awb_no = $res_shadowfax['awb_number'];
                    }
                    
                    
                }else{
                        return [
                            'status' => 0,
                            'message' => 'Issue in generating awb no. for shadowfax',
                        ];
                    }
               
                
            }
        }
        
        if (request()->has('debug_assign')) {
            dd([
                'request_data'  => $request->all(),
                'awb_no'        => $awb_no,
                'courier_data'  => $get['data'][request('courier_id')] ?? null,
                'order_id'      => request('order_id'),
                'warehouse_id'  => request('warehouse_id'),
            ]);
        }

        if($awb_no !=''){
            DB::beginTransaction();
            try {
                $order = Order::find(request('order_id'));
                $order->tracking_info = $awb_no;
                if(isset($label_print)){
                    $order->label_print = $label_print;
                }
                if(isset($cawb_no)){
                    $order->cawb_no = $cawb_no;
                }
                $order->shipped_date = now();
                $order->shipment_id = $shipment_id;
                $order->ship_courier_id = $get['data'][request('courier_id')]['courier_id'];

                $order->shipping_courier_cost = $ship_courier_cost;
                $order->shipping_courier_type = $get['data'][request('courier_id')]['mode'];
                $order->shipping_courier_weight_used = $get['data'][request('courier_id')]['weight_used'];
                $order->shipping_courier_weight = $get['data'][request('courier_id')]['weight'];
                $order->cod = $get['data'][request('courier_id')]['cod'];
                $order->gst = $get['data'][request('courier_id')]['gst'];
                $order->gst_freight = $get['data'][request('courier_id')]['gst_freight'];
                $order->gst_cod = $get['data'][request('courier_id')]['gst_cod'];
                $order->freight = $get['data'][request('courier_id')]['freight'];
                $order->reverse_charge = $get['data'][request('courier_id')]['reverse_charge'];
                $order->rate = $get['data'][request('courier_id')]['rate'];
                $order->rateadd = $get['data'][request('courier_id')]['rateadd'];


                $order->shipping_courier_costparent = $ship_courier_costparent;
                $order->codparent = $get['data'][request('courier_id')]['codparent'];
                $order->gstparent = $get['data'][request('courier_id')]['gstparent'];
                $order->gst_freightparent = $get['data'][request('courier_id')]['gst_freightparent'];
                $order->gst_codparent = $get['data'][request('courier_id')]['gst_codparent'];
                $order->freightparent = $get['data'][request('courier_id')]['freightparent'];
                $order->reverse_chargeparent = $get['data'][request('courier_id')]['reverse_chargeparent'];
                $order->rateparent = $get['data'][request('courier_id')]['rateparent'];
                $order->rateaddparent = $get['data'][request('courier_id')]['rateaddparent'];


                $manifest_new = new Manifest();
                $manifest_new->created_by = Auth::guard('admin')->user()->id;
                $manifest_new->company_id = Auth::guard('admin')->user()->company_id;
                $manifest_new->courier_id = $get['data'][request('courier_id')]['courier_id'];
                $manifest_new->updated_at = now();
                $manifest_new->created_at = now();
                $manifest_new->save();
                $manf_id = $manifest_new->id;

                $order->status = 12;
                $order->manifest_id = $manf_id;
                $order->manifest_date = now();
                $order->zone = $get['data'][request('courier_id')]['zone'];
                $order->warehouse_id = request('warehouse_id');
                $order->return_warehouse_id = request('return_warehouse_id');
                if(isset($get['data'][request('courier_id')]['edd']) && $get['data'][request('courier_id')]['edd'] != ''){
                    $order->expected_delivery_date = date('Y-m-d', strtotime($get['data'][request('courier_id')]['edd']));
                }
                $order->save();
                Status::orderstatuslog($order->id, $order->company_id, 1, 'Pickup Pending', now());
                
                $dublicartras = Transaction::where('user_id',$orde_user_id)->where('order_id',$order->id)->where('awb',$awb_no)->where('remarks','freight charge')->where('parent_data','0')->first();
                
                if($dublicartras ==''){
                $balance = Admin::find($orde_user_id);
                $before_blnc = $balance->wallet_blc;
                $balance->wallet_blc = $balance->wallet_blc - $ship_courier_cost;
                $balance->save();

                // $transaction = new Transaction();
                // $transaction->order_id = $order->id;
                // $transaction->user_id = $order->user_id;
                // $transaction->awb = $awb_no;
                // $transaction->tracking_info = $awb_no ."<br>". $get['data'][request('courier_id')]['name'];
                // $transaction->credit = "0.00";
                // $transaction->debit = $ship_courier_cost;
                // $transaction->closing_blc = $before_blnc-$ship_courier_cost;
                // $transaction->remarks = "Amount Debited for assigning";
                // $transaction->show_data = '0';
                // $transaction->save();

                $transaction = new Transaction();
                $transaction->order_id = $order->id;
                $transaction->user_id = $orde_user_id;
                $transaction->company_id = $company_id;
                $transaction->awb = $awb_no;
                $transaction->tracking_info = $awb_no ."<br>". $get['data'][request('courier_id')]['name'];
                $transaction->credit = "0.00";
                $transaction->debit = $get['data'][request('courier_id')]['gst_freight']+$get['data'][request('courier_id')]['freight'];
                $transaction->closing_blc = $before_blnc-$transaction->debit;
                if($order->reverse_order =='0'){
                    $transaction->remarks = "freight charge";
                }else{
                    $transaction->remarks = "freight & reverse charge";
                }
                $transaction->show_data = '1';
                $transaction->save();
                $before_blnc =$transaction->closing_blc;

                $transactionparent = new Transaction();
                $transactionparent->order_id = $order->id;
                $transactionparent->user_id = $parent_userid;
                $transactionparent->company_id = $company_id;
                $transactionparent->awb = $awb_no;
                $transactionparent->tracking_info = $awb_no ."<br>". $get['data'][request('courier_id')]['name'];
                $transactionparent->credit = "0.00";
                $transactionparent->debit = $get['data'][request('courier_id')]['gst_freightparent']+$get['data'][request('courier_id')]['freightparent'];
                if($order->reverse_order =='0'){
                    $transactionparent->remarks = "freight charge";
                }else{
                    $transactionparent->remarks = "freight & reverse charge";
                }
                $transactionparent->show_data = '1';
                $transactionparent->parent_data = '1';
                $transactionparent->save();

                

                if($get['data'][request('courier_id')]['cod'] !=0){
                    $transaction = new Transaction();
                    $transaction->order_id = $order->id;
                    $transaction->user_id = $orde_user_id;
                    $transaction->company_id = $company_id;
                    $transaction->awb = $awb_no;
                    $transaction->tracking_info = $awb_no ."<br>". $get['data'][request('courier_id')]['name'];
                    $transaction->credit = "0.00";
                    $transaction->debit = $get['data'][request('courier_id')]['gst_cod'] + $get['data'][request('courier_id')]['cod'];
                    $transaction->closing_blc = $before_blnc-$transaction->debit;
                    $transaction->remarks = "cod charge";
                    $transaction->show_data = '1';
                    $transaction->save();

                    $transactionparent = new Transaction();
                    $transactionparent->order_id = $order->id;
                    $transactionparent->user_id = $parent_userid;
                    $transactionparent->company_id = $company_id;
                    $transactionparent->awb = $awb_no;
                    $transactionparent->tracking_info = $awb_no ."<br>". $get['data'][request('courier_id')]['name'];
                    $transactionparent->credit = "0.00";
                    $transactionparent->debit = $get['data'][request('courier_id')]['gst_codparent'] + $get['data'][request('courier_id')]['codparent'];
                    $transactionparent->remarks = "cod charge";
                    $transactionparent->show_data = '1';
                    $transactionparent->parent_data = '1';
                    $transactionparent->save();
                }
                
                }
                if(!in_array($get['data'][request('courier_id')]['courier_id'],array('2','10','7'))){
                    TempAssignOrder::create([
                        'order_id' => $order->id,
                        'money' => $ship_courier_cost,
                        'courier_name'=> $get['data'][request('courier_id')]['name'] ?? 'Unknown',
                        'username' => Auth::guard('admin')->user()->name ?? 'system'
                    ]);

                    $body = "A new order has been added by " . auth()->guard('admin')->user()->name;


                    Mail::raw($body, function ($message) {
                        $message->to('sales@hyloship.com')       // main admin
                                // ->bcc(['adityav3011@gmail.com'])           // CC recipients
                                ->subject("New Order Request");
                    });
                }
                createlogs('awb', 'order', $order->id);
                DB::commit();
            } catch (\Throwable $dbEx) {
                DB::rollBack();
                throw $dbEx;
            }

            try {
                $user = auth()->guard('admin')->user();
                if ($user) {
                    $order_count = Order::where('user_id', $user->id)->whereNotNull('awb_no')->count();
                    if ($order_count == 1) {
                        $first_order_body = "User " . $user->name . " (" . $user->email . ") has successfully assigned their first order on Hyloship.\n\n"
                            . "Order ID: " . $order->id . "\n"
                            . "AWB Number: " . $awb_no . "\n"
                            . "Courier: " . ($get['data'][request('courier_id')]['name'] ?? 'Unknown');
                        
                        // Email to Admin & Ops (with CC)
                        Mail::raw($first_order_body, function ($message) use ($user) {
                            $message->to('sales@hyloship.com')
                                    ->cc([
                                        'dinanath.kumar@aframaxlogistics.com',
                                        'operations@hyloship.com',
                                        'adityav3011@gmail.com'
                                    ])
                                    ->subject("First Order Assigned by: " . $user->name);
                        });
                    }
                }
            } catch (\Throwable $mailEx) {
                \Illuminate\Support\Facades\Log::error('Order Assign Admin Mail Error: ' . $mailEx->getMessage());
            }

            try {
                $user = auth()->guard('admin')->user();
                if ($user) {
                    $order_count = Order::where('user_id', $user->id)->whereNotNull('awb_no')->count();
                    if ($order_count == 1) {
                        // Email to the Client/Seller
                        $client_order_body = "👋 Hello " . $user->name . ",\n\n"
                            . "🎉 Congratulations!\n\n"
                            . "Your first order has been successfully assigned through HyloShip. 🚚📦\n\n"
                            . "We're excited to be a part of your shipping journey!\n\n"
                            . "📋 What You Need to Do Next:\n\n"
                            . "✅ Log in to your HyloShip Seller Panel.\n"
                            . "📦 Review the assigned order details.\n"
                            . "🚚 Prepare the shipment for pickup.\n"
                            . "📄 Ensure the package is packed securely and ready for dispatch.\n"
                            . "⚠️ Important:\n"
                            . "Please make sure your shipment is ready before the scheduled pickup to avoid any delays.\n\n"
                            . "💬 Need Assistance?\n"
                            . "If you have any questions regarding your order or shipment, feel free to contact us.\n\n"
                            . "📧 Email: sales@hyloship.com\n\n"
                            . "Thank you for choosing HyloShip. We're committed to making your shipping experience smooth, reliable, and hassle-free.\n\n"
                            . "🚀 Here's to many more successful shipments with HyloShip!\n\n"
                            . "Best Regards,\n\n"
                            . "🚚 Team HyloShip\n"
                            . "📧 sales@hyloship.com\n"
                            . "🌐 www.hyloship.com";

                        Mail::raw($client_order_body, function ($message) use ($user) {
                            $message->to($user->email)
                                    ->cc('sales@hyloship.com')
                                    ->subject("🎉 Your First Order Has Been Assigned!");
                        });
                    }
                }
            } catch (\Throwable $mailEx) {
                \Illuminate\Support\Facades\Log::error('Order Assign Client Mail Error: ' . $mailEx->getMessage());
            }

            try {
                // Trigger WhatsApp Notification
                $to = preg_replace('/[^0-9]/', '', $order->ship_phone ?: $order->ship_mobile);
                
                $customer_name = trim(($order->ship_fname ?? '') . ' ' . ($order->ship_lname ?? ''));
                if (empty($customer_name)) {
                    $customer_name = 'Customer';
                }

                $delivery_address_parts = array_filter([
                    $order->ship_address ?? '',
                    $order->ship_address_2 ?? '',
                    $order->ship_city ?? '',
                    $order->ship_state ?? '',
                    $order->ship_pincode ?? ''
                ], function($val) {
                    return trim($val) !== '';
                });
                $delivery_address = implode(', ', $delivery_address_parts);
                if (empty($delivery_address)) {
                    $delivery_address = 'Address not provided';
                }

                // Hit the helper
                $wa_response = Integration::send_whatsapp_notification($to, $customer_name, $awb_no, $delivery_address);

                // Construct payload manually to log it
                $endpoint = env('WHATSAPP_API_ENDPOINT', 'https://telephonycloud.co.in/api/v1/whatsapp-message?svc-id=123');
                $token = env('WHATSAPP_API_TOKEN', 'MTAyMjUzMDQ6QWdlbnRAMDA3');
                $template = env('WHATSAPP_TEMPLATE_NAME', 'body_testing_with_variable');
                $payload = [
                    'endpoint' => $endpoint,
                    'to' => $to,
                    'type' => 'template',
                    'template' => [
                        'name' => $template,
                        'language' => ['code' => 'en'],
                        'components' => [
                            [
                                'type' => 'body',
                                'parameters' => [
                                    ['type' => 'text', 'text' => $customer_name],
                                    ['type' => 'text', 'text' => $awb_no],
                                    ['type' => 'text', 'text' => $delivery_address]
                                ]
                            ]
                        ]
                    ]
                ];

                // Log the API request and response using the api_logs helper
                api_logs(json_encode($payload), $wa_response, $order->id, 'whatsapp', 'whatsapp_notification');
            } catch (\Throwable $waEx) {
                // If anything in WhatsApp notification fails, log it to prevent breaking the order assignment flow
                api_logs(json_encode(['error' => $waEx->getMessage(), 'file' => basename($waEx->getFile()), 'line' => $waEx->getLine()]), 'FAILED', $order->id ?? 0, 'whatsapp', 'whatsapp_notification_exception');
            }
            
            return [
                'status' => 1,
                'message' => 'Courier Assigned Successfully',
            ];
        }else{
            return [
                'status' => 0,
                'message' => 'AWB empty. courier_id=' . ($get['data'][request('courier_id')]['courier_id'] ?? 'NULL') . ', reverse_order=' . ($order->reverse_order ?? 'NULL') . ', name=' . ($get['data'][request('courier_id')]['name'] ?? 'NULL'),
            ];
        }
        }else{
            return [
                'status' => 1,
                'message' => 'Courier Already Assigned ',
            ];
        }
        } catch (\Throwable $e) {
            return [
                'status' => 0,
                'message' => 'EXCEPTION: ' . $e->getMessage() . ' | File: ' . basename($e->getFile()) . ':' . $e->getLine(),
            ];
        }
    }
    public function unassign(){
        
        // foreach(request('id') as $row){
        //     $order = Order::find($row);
        //     $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        //     if($order->status == '<span class="badge text-white bg-dark">Shipped</span>'){
        //         $balance = Auth::guard('admin')->user();
        //         $balance->wallet_blc = $balance->wallet_blc + ($order->shipping_courier_cost ?? 0);
        //         $balance->save();

        //         $order->shipping_courier_cost = 0;
        //         $order->status = 1;
        //         $order->save();

        //         $transaction = new Transaction();
        //         $transaction->order_id = $order->id;
        //         $transaction->user_id = $order->user_id;
        //         $transaction->tracking_info = $order->tracking_info ."<br>". $couriers[$order->ship_courier_id]['name'];
        //         $transaction->credit = $balance->wallet_blc + ($order->shipping_courier_cost ?? 0);
        //         $transaction->debit = "0.00";
        //         $transaction->remarks = "Amount Credited for unassigning";
        //         $transaction->save(); 
        //     }
        // }

        return redirect()->back();
    }

    public function shipped_order(){
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $user_data = Auth::guard('admin')->user();
        $user_id = $user_data->id;
        $company_id = $user_data->company_id;
//        $admin = Auth::guard('admin')->user()->id;
        $warehouse = WareHouse::where('user_id',$user_id)->where('company_id',$company_id)->get();
        $re_data =array();
        if(!empty($_REQUEST)){
            if(Auth::guard('admin')->user()->role_id =='1' ){
                $order_q = Order::with('detail')->orderBy('shipped_date', 'desc');
            }elseif(Auth::guard('admin')->user()->role_id =='2'){
                $sub_user_id = Admin::getsubuserid($user_id);
                $order_q = Order::with('detail')->orderBy('shipped_date', 'desc')->whereIn('user_id' , $sub_user_id);
            }else{
                $order_q = Order::with('detail')->orderBy('shipped_date', 'desc')->where(['user_id' => $user_id]);
            }
            $order_q = $order_q->whereIn('status',array('2','12'));
            if(isset($_REQUEST['ship_courier_id']) && $_REQUEST['ship_courier_id'] !=0){
                $order_q->where('ship_courier_id', $_REQUEST['ship_courier_id']);
            }
            if(isset($_REQUEST['start_date']) && $_REQUEST['start_date'] !=''){
                $order_q->where('shipped_date','>=', $_REQUEST['start_date'].' 00:00:01');
            }
            if(isset($_REQUEST['end_date']) && $_REQUEST['end_date'] !=''){
                $order_q->where('shipped_date','<=', $_REQUEST['end_date'].' 23:59:59');
            }
            if(isset($_REQUEST['seller_id']) && $_REQUEST['seller_id'] !=''){
                $order_q->where('user_id', $_REQUEST['seller_id']);
            }
            $re_data= $_REQUEST;
            $order_q->where('company_id',$company_id);
            $order = $order_q->paginate(100);
        }else{
            if(Auth::guard('admin')->user()->role_id =='1' ){
                $order_q = Order::with('detail')->orderBy('shipped_date', 'desc');
            }elseif(Auth::guard('admin')->user()->role_id =='2'){
                $sub_user_id = Admin::getsubuserid($user_id);
                $order_q = Order::with('detail')->orderBy('shipped_date', 'desc')->whereIn('user_id' , $sub_user_id);
            }else{
                $order_q = Order::with('detail')->orderBy('shipped_date', 'desc')->where(['user_id' => $user_id]);
            }
            $order_q->where('company_id',$company_id);
            $order = $order_q->whereIn('status',array('2','12'))
            ->paginate(100);
        }
        // echo $order;die;
        return view('admin.order.shipped', compact('order','warehouse','couriers','re_data'));
    }

    public function order_report(){
        
        $user_id = Auth::guard('admin')->user()->id;
        $warehouse = WareHouse::where('user_id',$user_id)->get();
        // $order = Order::with('detail')->COUNT('id')->groupBy('status')->get();
        // $order = Order::with('detail')->where('user_id',$user_id)->paginate(10);
        // $order->status()
        $order =Order::with('detail')
        ->select('id','status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->get();
        // dd(($order));

        return view('admin.order.order_report', compact('order','warehouse'));
    }


     public function order_report_detail($id){
        // dd($id);
        // $user_id = Auth::guard('admin')->user()->id;
        // $warehouse = WareHouse::where('user_id',$user_id)->get();
    //   $order = Order::with('detail')->where([['user_id',$user_id] ,['id',$id]])->first();
    $order = Order::find($id);
    
    
    if(strip_tags($order->status)== 'New'){
        $status ='1' ;
    }else if(strip_tags($order->status)== 'Shipped'){
        $status ='2' ;
    }else if(strip_tags($order->status)== 'Delivered'){
        $status ='3';
    }else if(strip_tags($order->status)== 'Canceled'){
        $status ='4' ;
    }else if(strip_tags($order->status)== 'RTO'){
        $status ='5' ;
    }else if(strip_tags($order->status)== 'RTO Recieved'){
        $status ='6' ;
    }else if(strip_tags($order->status)== 'On Hold'){
        $status ='7' ;
    }else if(strip_tags($order->status)== 'Fulfillment'){
        $status ='8' ;
    }else if(strip_tags($order->status)== 'Refund'){
        $status ='9' ;
    }else if(strip_tags($order->status)== 'NDR'){
        $status ='10' ;
    } else if(strip_tags($order->status)== 'Courier Assigned'){
        $status ='11' ;
    }else if(strip_tags($order->status)== 'Manifested'){
        $status ='12' ;
    }
    
    
    $order_list= Order::with('detail')->where('status',$status)->get();
        return view('admin.order.order_report_detail',compact('order_list'));
     }
   
     public function courier()
     {
         $user_id = Auth::guard('admin')->user()->id;
         $order = Order::with('detail')->where(['user_id' => $user_id,'status' => 11])->paginate(10);
         
         return view('admin.order.courier', compact('order'));
     } 

    public function manifest()
    { 
        $now = date('Y-m-d');
        $start_date =  date('Y-m-d', strtotime($now. ' - 36 days')).' 00:00:00';
        $re_data['end_date'] = date('Y-m-d').' 23:59:59';
        $user_id = Auth::guard('admin')->user()->id;
        $company_id = Auth::guard('admin')->user()->company_id;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        if(Auth::guard('admin')->user()->role_id =='1' ){
            $manifest_q = Manifest::orderBy('created_at', 'desc')->with('manifest_order')->where('created_at','>=',$start_date)->where('company_id',$company_id);
            $manifest = $manifest_q->get();
        }elseif(Auth::guard('admin')->user()->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user_id);
            $manifest_q = Manifest::orderBy('created_at', 'desc')->with('manifest_order')->whereIn('created_by' , $sub_user_id)->where('company_id',$company_id);
            $manifest = $manifest_q->get();
        }else{
            $manifest = Manifest::orderBy('created_at', 'desc')->with('manifest_order')->where('created_by',$user_id)->where('company_id',$company_id)->get();
        }
//        echo '<pre>';print_R($manifest);die;
        return view('admin.order.manifest', compact('manifest','couriers','user_id'));
    }
    
    public function manifestprint($id)
    { 
        $user_id = Auth::guard('admin')->user()->id;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $manifest = Manifest::with('manifest_order')->where('id',$id)->first();
        $sub_user_id = Admin::getsubusername($user_id);
        if($manifest->created_by == Auth::guard('admin')->user()->name || Auth::guard('admin')->user()->role_id =='1' || (Auth::guard('admin')->user()->role_id =='2' && in_array($manifest->created_by,$sub_user_id))){
            for($i=0;$i<count($manifest->manifest_order);$i++){
                $manifest->manifest_order[$i] = Order::with('detail')->where(['id' => $manifest->manifest_order[$i]->id])->get();
            }
            return view('admin.order.manifestprint', compact('manifest','couriers'));
        }else{
            return redirect()->route('admin.order.manifest')->with('error', 'No Access');
        }
        
    }
     public function printSelectedManifest(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $user_id = $user->id;
        
        if (!$request->has('id') || empty($request->id)) {
            return redirect()->back()->with('error', 'Select at least one Order');
        }
        
        $d = implode(',', $request->id);
        $ven = explode(',', $d);
        
        $order_q = Order::with('detail')->whereIn('id', $ven);
        
        if ($user->role_id != '1') {
            $order_q->where('user_id', $user_id);
        }
        
        $orders = $order_q->get();
        
        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'No valid orders found to print manifest.');
        }
        
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);
        
        return view('admin.order.printselectedmanifest', compact('orders', 'couriers', 'user'));
    }
    
    public function invoiceprint($id)
    { 
        $user_id = Auth::guard('admin')->user()->id;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $manifest = Manifest::with('manifest_order')->where('id',$id)->first();
        if (!$manifest) {
            $order = Order::find($id);
            if ($order) {
                return $this->printParticularInvoice($id);
            }
            return redirect()->route('admin.order.manifest')->with('error', 'Invoice not found');
        }
        $sub_user_id = Admin::getsubusername($user_id);
        if($manifest->created_by == Auth::guard('admin')->user()->name || Auth::guard('admin')->user()->role_id =='1' || (Auth::guard('admin')->user()->role_id =='2' && in_array($manifest->created_by,$sub_user_id))){
            for($i=0;$i<count($manifest->manifest_order);$i++){
                $manifest->manifest_order[$i] = Order::with('detail')->where(['id' => $manifest->manifest_order[$i]->id])->get();
            }
            return view('admin.order.invoiceprint', compact('manifest','couriers'));
        }else{
            return redirect()->route('admin.order.manifest')->with('error', 'No Access');
        }
        
    }
    
    public function shippingprint($id,$courier_id)
    {   
        $file = 'shipping_label/shipping_'.$id.'.pdf';
        $user = Auth::guard('admin')->user();
        if(file_exists($file)){
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
        }else{
            if($courier_id =='9'){
                $label_arra =array();$j=0;
                $name = 'shipping_';
                $manifest = Manifest::with('manifest_order')->where('id',$id)->first();
                for($i=0;$i<count($manifest->manifest_order);$i++){
                    $label =  Integration_more::generate_shiplabelawb($manifest->manifest_order[$i]->shipment_id,$manifest->manifest_order[$i]->order_id);
                    $lebel_d = json_decode($label,true);
                    if(isset($lebel_d['payload'])){
                        $label_arra[$j]['awb'] = $lebel_d['payload']['packageDocumentDetail']['packageDocuments'][0]['contents'];
                        $label_arra[$j]['format'] = $lebel_d['payload']['packageDocumentDetail']['packageDocuments'][0]['format'];
                        $j++;
                    }
                }
                // echo '<pre>';print_R($label_arra);die;
                return view('admin.order.shippingprint', compact('label_arra','courier_id','id','name','user'));
            }else if($courier_id =='4'){
                $manifest = Manifest::with('manifest_order')->where('id',$id)->first();
                $label_arra = $manifest ? $manifest->manifest_order : collect();
                $name = 'shipping_';
                return view('admin.order.shippingprint', compact('label_arra','courier_id','id','name','user'));
            }else{
                $label_arra = Order::with('detail')->where('manifest_id', $id)->get();
                $sub_user_id = Admin::getsubusername(Auth::guard('admin')->user()->id);
                if(!empty($label_arra) && isset($label_arra[0]) && ($label_arra[0]->user_id == Auth::guard('admin')->user()->name || Auth::guard('admin')->user()->role_id =='1' || (Auth::guard('admin')->user()->role_id =='2' && in_array($label_arra[0]->user_id,$sub_user_id)))){
                    foreach($label_arra as $order):
                        if($order->warehouse_id){
                            $order['warehouse'] = Warehouse::where('id',$order->warehouse_id)->first();
                        }else{
                            $order['warehouse'] = array();
                        }
                    endforeach;
                    $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
                    $orders = $label_arra;
                    return view('admin.order.courierprint', compact('orders','id','courier_id','couriers','user'));
                }else{
                    return redirect()->route('admin.order.manifest')->with('error', 'No Access');
                }
            }
        }
            
    }

    public function printParticularInvoice($id)
    {
        $user = Auth::guard('admin')->user();
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $label_arra = Order::with('detail')->where('id', $id)->get();
        
        $only_invoice = true;
        
        $labelSetting = LabelSetting::where('user_id', $user->id)->first();
        if ($labelSetting && $labelSetting->printer_type == 2) {
            // Standard A4 user -> use A4 layout
            $orders = $label_arra;
            return view('admin.order.A4_Label_and_invoice', compact('orders', 'couriers', 'user', 'labelSetting', 'only_invoice'));
        }
        
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $orders = $label_arra;
        return view('admin.order.courierprint', compact('orders','id','couriers','user', 'only_invoice'));
    }

    public function shippingprintparticular($order_id,$courier_id)
    {   
        $file = 'shipping_label/shipping_particular_'.$order_id.'.pdf';
        $user = Auth::guard('admin')->user();
        if(file_exists($file)){
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
        }else{
            if($courier_id =='9'){
                $label_arra =array();$j=0;
                $name = 'shipping_particular_';
                $id =$order_id;
                $manifest = Order::where('id',$order_id)->first();
                if($manifest !=''){
                    $label =  Integration_more::generate_shiplabelawb($manifest->shipment_id,$manifest->order_id);
                    $lebel_d = json_decode($label,true);
                    if(isset($lebel_d['payload'])){
                        $label_arra[$j]['awb'] = $lebel_d['payload']['packageDocumentDetail']['packageDocuments'][0]['contents'];
                        $label_arra[$j]['format'] = $lebel_d['payload']['packageDocumentDetail']['packageDocuments'][0]['format'];
                    }
                }
                return view('admin.order.shippingprint', compact('label_arra','courier_id','id','name'));
            }else if($courier_id =='14'){
                $order = Order::with('detail')->where('id', $order_id)->first();
                if ($order) {
                    $warehouse = Warehouse::where('id', $order->warehouse_id)->first();
                    if ($warehouse) {
                        $ip_integration = Integration::where('user_id', $order->user_id)->where('courier_id', 14)->first();
                        $ip_cust_id = ($ip_integration && $ip_integration->ip_customer_id) ? $ip_integration->ip_customer_id : env('INDIAPOST_CUSTOMER_ID', '1843579100');
                        $ip_contract_id = ($ip_integration && $ip_integration->ip_contract_id) ? $ip_integration->ip_contract_id : env('INDIAPOST_CONTRACT_ID', '41441234');

                        $service_type = ($ip_integration && $ip_integration->ip_service_type) ? $ip_integration->ip_service_type : 'BP';
                        if ($service_type === 'BP' && $ip_integration && (str_contains(strtolower($ip_integration->ip_carrier_title), 'speed') || str_contains(strtolower($ip_integration->ip_carrier_title), 'sp'))) {
                            $service_type = 'SP';
                        }

                        $is_cod = (strtolower(strip_tags($order->payment_mode)) == 'cod' || strtolower(strip_tags($order->payment_mode)) == 'c.o.d' || strtolower(strip_tags($order->payment_mode)) == 'c.o.d.');
                        $cod_amount = ($is_cod && $order->custom_total > 0) ? $order->custom_total : $order->total;

                        $label_payload = [
                            [
                                'customer_id' => (int)$ip_cust_id,
                                'delivery_office_name' => substr($order->ship_city, 0, 50),
                                'destination_pin' => (string)$order->ship_pincode,
                                'booking_datetime' => \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i:s'),
                                'channel_type' => 'E',
                                'user_type' => 'R',
                                'user_id' => (int)$ip_cust_id,
                                'barcode_no' => (string)$order->tracking_info,
                                'service_type' => $service_type,
                                'booking_type' => 'COMMERCIAL',
                                'article_length' => (string)($order->length ?: 0),
                                'article_breadth' => (string)($order->breadth ?: 0),
                                'article_height' => (string)($order->height ?: 0),
                                'charged_weight' => (float)round($order->weight / 1000, 3),
                                'physical_weight' => (float)round($order->weight / 1000, 3),
                                'volumetric_weight' => 0.0,
                                'insurance_flag' => false,
                                'insurance_value' => 0,
                                'recipient_name' => substr($order->ship_fname . ' ' . $order->ship_lname, 0, 50),
                                'recipient_mobile' => (string)(preg_replace('/[^0-9]/', '', $order->ship_phone) ?: '9999999999'),
                                'recipient_addressl1' => substr($order->ship_address, 0, 50),
                                'recipient_addressl2' => substr($order->ship_address_2 ?? '', 0, 50),
                                'recipient_addressl3' => '',
                                'recipient_city' => substr($order->ship_city, 0, 30),
                                'recipient_pin' => (string)$order->ship_pincode,
                                'recipient_state' => substr($order->ship_state, 0, 30),
                                'sender_name' => substr($warehouse->contact_name ?: $warehouse->name, 0, 50),
                                'sender_mobile' => (string)(preg_replace('/[^0-9]/', '', $warehouse->phone) ?: '9999999999'),
                                'sender_addressl1' => substr($warehouse->address, 0, 50),
                                'sender_addressl2' => substr($warehouse->address_2 ?? '', 0, 50),
                                'sender_addressl3' => '',
                                'sender_city' => substr($warehouse->city, 0, 30),
                                'sender_pin' => (string)$warehouse->pincode,
                                'sender_state' => substr($warehouse->state, 0, 30),
                                'transmission_mode' => ($service_type === 'SP') ? 'A' : 'S',
                                'payment_mode' => $is_cod ? 'CO' : 'PP',
                                'routing_data' => $warehouse->pincode . ' - ' . $order->ship_pincode,
                                'booking_office_name' => substr($warehouse->city, 0, 30),
                                'booking_office_pin' => (string)$warehouse->pincode,
                                'size' => 'A6',
                                'total_amount' => (float)$cod_amount,
                                'payment_status' => 'PC',
                                'value_added_services' => 'ND',
                                'identifier' => 'Domestic',
                                'bkg_ref_id' => (string)$order->order_id,
                                'priority' => false,
                                'registered_flag' => false
                            ]
                        ];

                        $res_pdf = Integration::generate_label_indiapost(json_encode($label_payload), $ip_integration);

                        if (substr(trim($res_pdf), 0, 4) === '%PDF') {
                            @file_put_contents($file, $res_pdf);
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename="'.basename($file).'"');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate');
                            header('Pragma: public');
                            header('Content-Length: ' . strlen($res_pdf));
                            echo $res_pdf;
                            exit;
                        } else {
                            \Log::warning("India Post label generation failed: " . $res_pdf);
                        }
                    }
                }

                $label_arra = Order::with('detail')->where('id', $order_id)->get();
                $id = $order_id;
                foreach($label_arra as $order):
                    if($order->warehouse_id){
                        $order['warehouse'] = Warehouse::where('id',$order->warehouse_id)->first();
                    }else{
                        $order['warehouse'] = array();
                    }
                endforeach;
                $orders = $label_arra;
                $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
                return view('admin.order.courierprint', compact('orders','id','courier_id','couriers','user'));
            }else if($courier_id =='4' || $courier_id =='2'){
                $order = Order::with('detail')->where('id',$order_id)->first();
                $label_arra = $order ? array($order) : array();
                $name = 'shipping_particular_';
                $id =$order_id;
                return view('admin.order.shippingprint', compact('label_arra','courier_id','id','name','user'));
            }else{
                $label_arra = Order::with('detail')->where('id', $order_id)->get();
                $sub_user_id = Admin::getsubusername(Auth::guard('admin')->user()->id);
                $id =$order_id;
                if(!empty($label_arra) && isset($label_arra[0]) && ($label_arra[0]->user_id == Auth::guard('admin')->user()->name || Auth::guard('admin')->user()->role_id =='1' || (Auth::guard('admin')->user()->role_id =='2' && in_array($label_arra[0]->user_id,$sub_user_id)))){
                    foreach($label_arra as $order):
                        if($order->warehouse_id){
                            $order['warehouse'] = Warehouse::where('id',$order->warehouse_id)->first();
                        }else{
                            $order['warehouse'] = array();
                        }
                    endforeach;
                    $orders = $label_arra;
                                        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
                    return view('admin.order.courierprint', compact('orders','id','courier_id','couriers','user'));
                }else{
                return redirect()->route('admin.order.manifest')->with('error', 'No Access');
                }
            }
        }
            
    }
    
    public function revOrder(){
        foreach(request()->id as $id)
        {   
            $awb = Integration::get_awb_number('rev');
            $rev_awb_no ='';
            if($awb !=''){
                $response = json_decode($awb,true);
                if(isset($response['awb']) && count($response['awb']) >0){
                    $rev_awb_no = $response['awb'][0];
                }
            }
            if($rev_awb_no !=''){
            $order = Order::with('detail')->where('id', $id)->first();
            $warehouse = Warehouse::find($order['warehouse_id']);
            $coll_value=0;
            if($order['tracking_info'] !='' && $warehouse['contact_name']){
            if($order['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">C.O.D</span>'){
                $pmode = 'cod';
                $coll_value = $order['total'];
            }elseif($order['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">Pre-Paid</span>'){
                $pmode = 'ppd';
                
            }
            $manifest_d =array(
            'ECOMEXPRESS-OBJECTS' => array(
            'SHIPMENT' => array(
                'AWB_NUMBER'=>"$rev_awb_no",
                'ORDER_NUMBER'=>$order['order_id'],
                'PRODUCT'=>'REV',
                'REVPICKUP_NAME'=>$order['ship_fname'].' '.$order['ship_lname'],
                'REVPICKUP_ADDRESS1'=>$order['ship_address'],
                'REVPICKUP_ADDRESS2'=>$order['ship_address_2'],
                'REVPICKUP_ADDRESS3'=>'',
                'REVPICKUP_CITY'=>$order['ship_city'],
                'REVPICKUP_PINCODE'=>$order['ship_pincode'],
                'REVPICKUP_STATE'=>$order['ship_state'],
                'REVPICKUP_MOBILE'=>$order['ship_phone'],
                'REVPICKUP_TELEPHONE'=>'',
                'PIECES'=>count($order['detail']),
                'COLLECTABLE_VALUE'=>$coll_value,
                'DECLARED_VALUE'=>$order['total'],
                'ACTUAL_WEIGHT'=>$order['weight']/1000,
                'VOLUMETRIC_WEIGHT'=>($order['length']*$order['breadth']*$order['height'])/5000,
                'LENGTH'=>$order['length'],
                'BREADTH'=>$order['breadth'],
                'HEIGHT'=>$order['height'],
                'VENDOR_ID'=>'',
                'DROP_NAME'=>$warehouse['contact_name'],
                'DROP_ADDRESS_LINE1'=>$warehouse['address'],
                'DROP_ADDRESS_LINE2'=>$warehouse['address_2'],
                'DROP_PINCODE'=>$warehouse['pincode'],
                'DROP_MOBILE'=>$warehouse['phone'],
                'ITEM_DESCRIPTION'=>'Multiple items',
                'DROP_PHONE'=>$warehouse['phone'],
                'EXTRA_INFORMATION'=>'',
                'DG_SHIPMENT'=>false
                //Pass ""true"" in case shipment package contains any item that is restricted for air travel as per DGCA guidelines. Otherwise pass ""false"
                
            )));
            $manifest = Integration::rev_manifest_awb(json_encode($manifest_d));
            $manifest = json_decode($manifest,true);
            
            if($manifest['RESPONSE-OBJECTS']['AIRWAYBILL-OBJECTS']['AIRWAYBILL']['success']){
                        $orderd = Order::where('id', $id)->first();
                        $orderd->status = '5';
                        $orderd->rev_tracking_info = $rev_awb_no;
                        $orderd->rto_date = now();
                        $orderd->save();
                }
                }
            }
        }
    }
    
    public function download(Request $request){
            $user_id = Auth::guard('admin')->user()->id;
            $order_q = Order::with('detail');
        
            $path = $request->get('path', 'all');
            
            // Check if it's a "Download All" request based on the route name or a flag
            $isDownloadAll = $request->has('download_all_flag') || ($request->route() && $request->route()->getName() == 'admin.order.download_all');
        
            if(!$isDownloadAll && $request->has('id') && !empty($request->id)){
                // Download selected
                $ids = is_array($request->id) ? $request->id : explode(',', $request->id);
                $order_q->whereIn('id', $ids);
            } else {
                // Download all filtered
                $order_q = $this->getFilteredOrdersQuery($request);
            }

            
            $orders = $order_q->get();
            $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
            $admins = \App\Models\Admin\Admin::get(['id', 'name', 'company_name', 'parent_id'])->keyBy('id');
            return view('admin.reports.download', compact('orders','couriers','path','admins'));
        }
        
          public function download_all(Request $request){

            $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);
            $admins   = \App\Models\Admin\Admin::get(['id', 'name', 'company_name', 'parent_id'])->keyBy('id');

            $order_q = $this->getFilteredOrdersQuery($request);
            // eager-load warehouse for pickup location
            $order_q->with('warehouse');
            $orders  = $order_q->get();

            $filename = 'order_report_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $callback = function () use ($orders, $couriers, $admins) {
                $handle = fopen('php://output', 'w');

                // BOM for UTF-8 so Excel opens it correctly
                fputs($handle, "\xEF\xBB\xBF");

                // Header row
                $headRow = [
                    'S.No.', 'Order ID', 'Order No.', 'Seller ID', 'Seller Name', 'Parent ID', 'Parent Name', 'Courier Name', 'Shipping Mode', 'Channel',
                    'Created', 'Status', 'Payment Mode', 'Total Amount', 'Custom Total',
                    'Shipping Date', 'AWB', 'Manifest ID', 'Shipment Status', 'Total Attempt Count',
                    'Customer Name', 'Customer Phone', 'Customer Email', 'Pincode', 'City', 'State',
                    'Dimension(CM)', 'Weight(kg)', 'Vol.Weight(kg)', 'Shipping Charge', 'Zone', 'Order Tag',
                    'Pickup Location Name', 'Pickup Contact', 'Pickup Phone', 'Pickup Address', 'Pickup City', 'Pickup State', 'Pickup Pincode',
                    'Product 1','SKU 1','QTY 1','Price 1','Discount Type 1','Discount Amount 1','Tax Amount 1','T. Amount 1',
                    'Product 2','SKU 2','QTY 2','Price 2','Discount Type 2','Discount Amount 2','Tax Amount 2','T. Amount 2',
                    'Product 3','SKU 3','QTY 3','Price 3','Discount Type 3','Discount Amount 3','Tax Amount 3','T. Amount 3',
                    'Product 4','SKU 4','QTY 4','Price 4','Discount Type 4','Discount Amount 4','Tax Amount 4','T. Amount 4',
                    'Product 5','SKU 5','QTY 5','Price 5','Discount Type 5','Discount Amount 5','Tax Amount 5','T. Amount 5',
                ];
                fputcsv($handle, $headRow);

                $i = 1;
                foreach ($orders as $order) {
                    // Status
                    $status = ($order->manifest_id && strip_tags($order->status) == 'Shipped')
                        ? 'Manifested'
                        : strip_tags($order->status);

                    // Courier name
                    $courierName = isset($couriers[$order->ship_courier_id])
                        ? $couriers[$order->ship_courier_id]['name']
                        : ($order->ship_courier_id ? 'Courier ID: ' . $order->ship_courier_id : 'N/A');

                    // Shipping mode
                    $rawMode = strtolower($order->shipping_courier_type ?? '');
                    if ($rawMode == 'fa-plane' || $rawMode == 'air') {
                        $shippingMode = 'Air';
                    } elseif ($rawMode == 'fa-truck' || $rawMode == 'surface') {
                        $shippingMode = 'Surface';
                    } elseif ($rawMode == 'fa-motorcycle' || $rawMode == 'sdd') {
                        $shippingMode = 'SDD';
                    } elseif ($rawMode == 'fa-bicycle' || $rawMode == 'ndd') {
                        $shippingMode = 'NDD';
                    } elseif ($rawMode == 'fa-bolt' || $rawMode == 'quick') {
                        $shippingMode = 'Quick';
                    } else {
                        $shippingMode = $order->shipping_courier_type ?? '';
                    }

                    // Seller & Parent (Onboarder) info
                    $sellerId   = $order->getRawOriginal('user_id');
                    $seller     = isset($admins[$sellerId]) ? $admins[$sellerId] : null;
                    $sellerName = $seller ? ($seller->name ?: $seller->company_name) : $order->user_id;

                    $parentId   = $seller ? ($seller->parent_id ?? '') : '';
                    $parent     = ($parentId && isset($admins[$parentId])) ? $admins[$parentId] : null;
                    $parentName = $parent ? ($parent->name ?: $parent->company_name) : '';

                    // Volumetric weight
                    if ($order->ship_courier_id == '2') {
                        $volWeight = ($order->length * $order->breadth * $order->height) / 4000;
                    } elseif ($order->ship_courier_id == '5') {
                        $volWeight = ($order->length * $order->breadth * $order->height) / 4750;
                    } else {
                        $volWeight = ($order->length * $order->breadth * $order->height) / 5000;
                    }
                    
                    // Pickup / warehouse location
                    $wh = $order->warehouse;
                    if (!$wh && $order->warehouse_id) {
                        $wh = \App\Models\Admin\Warehouse::find($order->warehouse_id);
                    }

                    $row = [
                        $i++,
                        $order->order_id,
                        $order->vendor_order_id,
                        $sellerId,
                        $sellerName,
                        $parentId,
                        $parentName,
                        $courierName,
                        $shippingMode,
                        $order->channel,
                        $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i:s') : '',
                        $status,
                        strip_tags($order->payment_mode),
                        $order->total . '.00',
                        ($order->custom_total ?? $order->total) . '.00',
                        $order->shipped_date ? \Carbon\Carbon::parse($order->shipped_date)->format('Y-m-d H:i:s') : '',
                        $order->tracking_info,
                        $order->manifest_id,
                        '', // Shipment Status (external)
                        $order->total_attempt ?? 0,  // Total Attempt Count
                        trim($order->ship_fname . ' ' . $order->ship_lname),
                        $order->ship_phone,
                        $order->ship_email,
                        $order->ship_pincode,
                        $order->ship_city,
                        $order->ship_state,
                        $order->length . '*' . $order->breadth . '*' . $order->height,
                        $order->weight / 1000,
                        $volWeight,
                        $order->shipping_courier_cost,
                        $order->zone,
                        $order->tags,
                        // Pickup location columns
                        $wh ? $wh->name          : '',
                        $wh ? $wh->contact_name  : '',
                        $wh ? $wh->phone         : '',
                        $wh ? trim(($wh->address ?? '') . ($wh->address_2 ? ', ' . $wh->address_2 : '')) : '',
                        $wh ? $wh->city          : '',
                        $wh ? $wh->state         : '',
                        $wh ? $wh->pincode       : '',
                    ];

                    // Up to 5 product details
                    for ($j = 0; $j < 5; $j++) {
                        if (isset($order->detail[$j])) {
                            $detail = $order->detail[$j];
                            $discountType = ($detail->discount_type == 'p') ? 'Percentage' : 'Flat';
                            $row[] = $detail->name;
                            $row[] = $detail->code;
                            $row[] = $detail->qty;
                            $row[] = $detail->price;
                            $row[] = $discountType;
                            $row[] = $detail->discount;
                            $row[] = $detail->tax_amount;
                            $row[] = $detail->total_price;
                        } else {
                            $row = array_merge($row, ['', '', '', '', '', '', '', '']);
                        }
                    }

                    fputcsv($handle, $row);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }
         private function getFilteredOrdersQuery(Request $request)
        {
            $user_id = Auth::guard('admin')->user()->id;
            $current_company = Auth::guard('admin')->user()->company_id;
            
            $order_q = Order::with('detail');
            
            if($request->has('seller_id') && $request->seller_id != '0' && $request->seller_id != ''){
                $order_q->where(['user_id' => $request->seller_id]);
            } else {
                if(Auth::guard('admin')->user()->role_id == '1'){
                    // Admin gets all for company
                } elseif(Auth::guard('admin')->user()->role_id == '2'){
                    $sub_user_id = Admin::getsubuserid($user_id);
                    $order_q->whereIn('user_id', $sub_user_id);
                } else {
                    $order_q->where(['user_id' => $user_id]);
                }
            }
            
            $order_q->where('company_id', $current_company);

            if($request->has('payment_mode') && $request->payment_mode != ''){
                $order_q->where('payment_mode', $request->payment_mode);
            }
            if($request->has('vendor_order_id') && $request->vendor_order_id != ''){
                $ven = explode(',', $request->vendor_order_id);
                $order_q->whereIn('vendor_order_id', $ven);
            }
            if($request->has('tracking_info') && $request->tracking_info != ''){
                $tracking_info_Array = explode(',', $request->tracking_info);
                $order_q->whereIn('tracking_info', $tracking_info_Array);
            }
            if($request->has('order_status') && $request->order_status != '0'){
                $order_q->where('status', $request->order_status);
            }
            if($request->has('buyer_name') && $request->buyer_name != ''){
                $buyer_name = $request->buyer_name;
                $order_q->whereRaw("CONCAT(ship_fname, ' ', ship_lname) = ?", [$buyer_name]);
            }
            if($request->has('ship_courier_id') && $request->ship_courier_id != '0'){
                $order_q->where('ship_courier_id', $request->ship_courier_id);
            }

            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            
            if($start_date){
                $order_q->where('created_at', '>=', $start_date . ' 00:00:00');
            }
            if($end_date){
                $order_q->where('created_at', '<=', $end_date . ' 23:59:59');
            }

            if($request->has('exceptnewcancel')){
                $order_q->whereNotIn('status', ['1', '4']);
            }
            if($request->has('delivered')){
                $order_q->where('status', '3');
            }
            if($request->has('rto')){
                $order_q->whereIn('status', ['5', '6', '13']);
            }
            if($request->has('intransit')){
                $order_q->whereIn('status', ['14', '16', '17', '15', '10']);
            }
            if($request->has('intrait')){
                $order_q->whereIn('status', ['14']);
            }
            if($request->has('shipped_today')){
                $order_q->whereBetween('shipped_date', [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59'])
                        ->whereNotNull('tracking_info');
            }
            if($request->has('manifest_today')){
                $order_q->whereBetween('manifest_date', [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59'])
                        ->whereNotNull('tracking_info')
                        ->where('status', '12');
            }
            if($request->has('manifested_before12')){
                $order_q->whereBetween('manifest_date', [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 11:59:59'])
                        ->whereNotNull('tracking_info')
                        ->where('status', '12');
            }
            if($request->has('delivered_today')){
                $order_q->whereBetween('delivered_date', [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59'])
                        ->whereNotNull('tracking_info')
                        ->where('status', '3');
            }
            if($request->has('ood')){
                $order_q->whereBetween('status_date', [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59'])
                        ->whereNotNull('tracking_info')
                        ->where('status', '15');
            }
            if($request->has('ndr')){
                $order_q->whereNotNull('shipped_date');
            }
            
            if($request->has('role_id')){
                $role_id = $request->role_id;
                $now = date('Y-m-d');
                if($role_id == 'tw'){
                    $order_q->where('created_at', '>=', date('Y-m-d', strtotime($now . ' - 6 days')) . ' 00:00:00')
                            ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59');
                }
                if($role_id == 'tod'){
                    $order_q->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')
                            ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59');
                }
                if($role_id == 'yes'){
                    $order_q->where('created_at', '>=', date('Y-m-d', strtotime($now . ' - 1 days')) . ' 00:00:00')
                            ->where('created_at', '<=', date('Y-m-d', strtotime($now . ' - 1 days')) . ' 23:59:59');
                }
                if($role_id == 'lt'){
                    $order_q->where('created_at', '>=', date('Y-m-d', strtotime($now . ' - 2 days')) . ' 00:00:00')
                            ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59');
                }
                if($role_id == 'lf'){
                    $order_q->where('created_at', '>=', date('Y-m-d', strtotime($now . ' - 14 days')) . ' 00:00:00')
                            ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59');
                }
                if($role_id == 'lth'){
                    $order_q->where('created_at', '>=', date('Y-m-d', strtotime($now . ' - 29 days')) . ' 00:00:00')
                            ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59');
                }
            }

            return $order_q;
        }
    public function downloadupdate(){
        $order_q = Order::with('detail');
        $d = implode(',',request('id'));
        $ven = explode(',',$d);
        $order_q->whereIn('id', $ven);
        $orders = $order_q->get();
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        return view('admin.reports.downloadupdate', compact('orders','couriers'));
    }
    
    public function markpicked($id){
        $user = Auth::guard('admin')->user();
        $order = Order::where('id', $id)->first();
        if($order->user_id ==$user->id){
            $order->picked_date = now();
            $order->save();
            return redirect()->route('admin.order.shipped_order')->with('success', 'Marked Picked Successfully!');
        }else{
            return redirect()->route('admin.order.shipped_order')->with('error', 'No Access');
        }
    }

    public function addextraweight($id){
        $user = Auth::guard('admin')->user();
        $order = Order::where('id', $id)->first();
        if($order->user_id ==$user->id){
            return view('admin.order.addextraweight',compact('order'));
        }else{
            return redirect()->route('admin.order.shipped_order')->with('error', 'No Access');
        }
    }

    // public function updateweight(Request $request,$id){
    //     $order = Order::findOrFail($id);
    //     $user = Auth::guard('admin')->user();
    //     if($order->user_id ==$user->id){
    //         $order->extra_weight = $request->extra_weight;
    //         $order->extra_weight_cost = $request->extra_weight_cost;
    //         $order->save();
    //         return redirect()->route('admin.order.shipped_order')->with('success', 'Added Successfully!');
    //     }else{
    //         return redirect()->route('admin.order.shipped_order')->with('error', 'No Access');
    //     }
       
    // }
    
    public function manifestorder(){
        $manifest_order_array = array();
        foreach(request()->id as $id){
            $order = Order::with('detail')->where('id', $id)->first();
            if($order->manifest_id && $order->manifest_id !=0){}else{
                $manifest_order_array[$order->ship_courier_id][] = $id;
            }
        }
//        echo '<pre>';print_R($manifest_order_array);die;
        if(!empty($manifest_order_array)){
            foreach($manifest_order_array as $m_key => $m_order){
                    $manifest_new = new Manifest();
                    $manifest_new->created_by = Auth::guard('admin')->user()->id;
                    $manifest_new->company_id = Auth::guard('admin')->user()->company_id;
                    $manifest_new->courier_id = $m_key;
                    $manifest_new->updated_at = now();
                    $manifest_new->created_at = now();
                    $manifest_new->save();
                    $manf_id = $manifest_new->id;
                for($i=0;$i<count($m_order);$i++){
                    $order_mani = Order::find($m_order[$i]);
                    if($m_key=='11'){
                        $manifarray = array(
                          "awbNumber"=> array($order_mani->tracking_info),
                          "cAwbNumber"=>  array($order_mani->cawb_no),
                        );
                        $cancelpickndel =  Integration_courier::addmanfest(json_encode($manifarray));
                        $cancelshadowfax = json_decode($cancelpickndel,true);
                        api_logs(json_encode($manifarray),$cancelpickndel,$id,'11','Manifest');
                        if($cancelshadowfax['status']=='200'){
                            
                        }else{
                            $e_msg ='Issue in Shreemaruti, please try after sometime';
                            if(isset($cancelshadowfax['message'])){
                                $e_msg =$cancelshadowfax['message'][0];
                            }
                            return redirect()->route('admin.order.shipped_order')->with('error',$e_msg);
                        }
                    }
                    $old_status = strip_tags($order_mani->status);
                    $order_mani->manifest_id = $manf_id;
                    $order_mani->status = '12';
                    $order_mani->manifest_date = now();
                    $order_mani->save();
                    Status::orderstatuslog($m_order[$i],$order_mani->company_id,$old_status,'Pickup Pending',now());
                }
            }
        }else{
            return redirect()->route('admin.order.shipped_order')->with('error', 'Already Manifested');
        }
        return redirect()->route('admin.order.manifest')->with('success', 'Manifested Successfully!');
    }
  
  	public function get_pincode(){
        $pincode = Pincode::where('pincode',request('pincode'))->first();
        if($pincode !=''){
            $get[] = [
                'state' => $pincode->state,
                'city' => $pincode->city,
                
            ];
            return [
                'status' => 1, 
                'message' => 'Success',
                'data' => $get,
            ];
        }else{
            return [
                'status' => 0
            ];
        }
    }
    
    public function manifestproductwise(){
        $manifest_order_array = array();
        foreach(request()->id as $id){
            $order = Order::with('detail')->where('id', $id)->first();
            if($order->detail){
                if(isset($order->detail[0]->name)){
                    if($order->manifestprod_id && $order->manifestprod_id !=0){}else{
                        if($order->ship_courier_id =='9'){
                            $manifest_order_array[$order->detail[0]->name.' - ats'][] = $id;
                        }else{
                            $manifest_order_array[$order->detail[0]->name][] = $id;
                        }
                    }
                }
            }
            
        }
        if(!empty($manifest_order_array)){
            foreach($manifest_order_array as $m_key => $m_order){
                    $manifest_new = new Manifest();
                    $manifest_new->created_by = Auth::guard('admin')->user()->id;
                    $manifest_new->product = $m_key;
                    $manifest_new->updated_at = now();
                    $manifest_new->created_at = now();
                    $manifest_new->save();
                    $manf_id = $manifest_new->id;
                for($i=0;$i<count($m_order);$i++){
                    $order_mani = Order::find($m_order[$i]);
                    $old_status = strip_tags($order_mani->status);
                    $order_mani->manifestprod_id = $manf_id;
                    $order_mani->status = '12';
                    $order_mani->manifest_date = now();
                    $order_mani->save();
                    Status::orderstatuslog($m_order[$i],$order_mani->company_id,$old_status,'Pickup Pending',now());
                }
            }
        }else{
            return redirect()->route('admin.order.shipped_order')->with('error', 'Already Manifested');
        }
        return redirect()->route('admin.order.manifestpview')->with('success', 'Manifested Successfully!');
    }
    
    public function invoiceprodprint($id)
    { 
        $user_id = Auth::guard('admin')->user()->id;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $manifest = Manifest::with('manifest_porder')->where('id',$id)->first();
        $sub_user_id = Admin::getsubusername($user_id);
        if($manifest->created_by == Auth::guard('admin')->user()->name || Auth::guard('admin')->user()->role_id =='1' || (Auth::guard('admin')->user()->role_id =='2' && in_array($manifest->created_by,$sub_user_id))){
            for($i=0;$i<count($manifest->manifest_porder);$i++){
                $manifest->manifest_order[$i] = Order::with('detail')->where(['id' => $manifest->manifest_porder[$i]->id])->get();
            }
            return view('admin.order.invoiceprint', compact('manifest','couriers'));
        }else{
            return redirect()->route('admin.order.manifest')->with('error', 'No Access');
        }
        
    }
    
    public function manifestprodprint($id)
    { 
        $user_id = Auth::guard('admin')->user()->id;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $manifest = Manifest::with('manifest_porder')->where('id',$id)->first();
        
        $sub_user_id = Admin::getsubusername($user_id);
        if($manifest->created_by == Auth::guard('admin')->user()->name || Auth::guard('admin')->user()->role_id =='1' || (Auth::guard('admin')->user()->role_id =='2' && in_array($manifest->created_by,$sub_user_id))){
            for($i=0;$i<count($manifest->manifest_porder);$i++){
                $manifest->manifest_order[$i] = Order::with('detail')->where(['id' => $manifest->manifest_porder[$i]->id])->get();
            }
            return view('admin.order.manifestprint', compact('manifest','couriers'));
        }else{
            return redirect()->route('admin.order.manifestpview')->with('error', 'No Access');
        }
        
    }
    
    public function manifestpview(){
        $user_id = Auth::guard('admin')->user()->id;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        if(Auth::guard('admin')->user()->role_id =='1' ){
            $manifest_q = Manifest::orderBy('created_at', 'desc')->with('manifest_porder')->where('courier_id','0')->where('product','!=',null);
            $manifest = $manifest_q->get();
        }elseif(Auth::guard('admin')->user()->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user_id);
            $manifest_q = Manifest::orderBy('created_at', 'desc')->with('manifest_porder')->whereIn('created_by' , $sub_user_id)->where('courier_id','0')->where('product','!=',null);
            $manifest = $manifest_q->get();
        }else{
            $manifest = Manifest::orderBy('created_at', 'desc')->with('manifest_porder')->where('created_by',$user_id)->where('courier_id','0')->where('product','!=',null)->get();
        }
        return view('admin.order.manifestpview', compact('manifest','couriers'));
    }
    
    public function shippingprodprint($id,$courier_id)
    {   
        $file = 'shipping_label/shipping_'.$id.'.pdf';
        $user = Auth::guard('admin')->user();
        if(file_exists($file)){
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
        }else{
            if($courier_id =='9'){
                $label_arra =array();$j=0;
                $name = 'shipping_';
                $manifest = Manifest::with('manifest_porder')->where('id',$id)->first();
                for($i=0;$i<count($manifest->manifest_porder);$i++){
                    $label =  Integration_more::generate_shiplabelawb($manifest->manifest_porder[$i]->shipment_id,$manifest->manifest_porder[$i]->order_id);
                    $lebel_d = json_decode($label,true);
                    if(isset($lebel_d['payload'])){
                        $label_arra[$j]['awb'] = $lebel_d['payload']['packageDocumentDetail']['packageDocuments'][0]['contents'];
                        $label_arra[$j]['format'] = $lebel_d['payload']['packageDocumentDetail']['packageDocuments'][0]['format'];
                        $j++;
                    }
                }
                // echo '<pre>';print_R($label_arra);die;
                return view('admin.order.shippingprint', compact('label_arra','courier_id','id','name','user'));
            }else if($courier_id =='4'){
                $manifest = Manifest::with('manifest_porder')->where('id',$id)->first();
                $label_arra = $manifest ? $manifest->manifest_porder : collect();
                $name = 'shipping_';
                return view('admin.order.shippingprint', compact('label_arra','courier_id','id','name','user'));
            }else{
                $label_arra = Order::with('detail')->where('manifestprod_id', $id)->get();
                // echo $label_arra;die;
                $sub_user_id = Admin::getsubusername(Auth::guard('admin')->user()->id);
                if(!empty($label_arra) && isset($label_arra[0]) && ($label_arra[0]->user_id == Auth::guard('admin')->user()->name || Auth::guard('admin')->user()->role_id =='1' || (Auth::guard('admin')->user()->role_id =='2' && in_array($label_arra[0]->user_id,$sub_user_id)))){
                    foreach($label_arra as $order):
                        if($order->warehouse_id){
                            $order['warehouse'] = Warehouse::where('id',$order->warehouse_id)->first();
                        }else{
                            $order['warehouse'] = array();
                        }
                    endforeach;
                    $orders = $label_arra;
                                        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
                    return view('admin.order.courierprint', compact('orders','id','courier_id','couriers','user'));
                }else{
                    return redirect()->route('admin.order.manifestpview')->with('error', 'No Access');
                }
            }
        }
            
    }
    
    public function manifestmultiple(){
        $user = Auth::guard('admin')->user();
        $user_id = $user->id;
        $order_q = Order::with('detail');
        $id = date('ymdhis');
        $courier_id ='mix';
        if($user_id !=1){
            $order_q->where(['user_id' => $user_id]);
        }
        $d = implode(',',request('id'));
        $ven = explode(',',$d);
        $order_q->whereIn('id', $ven);
        $order_q->where('ship_courier_id','!=', '9');
        $order_q->where(function ($query) {
        $query->where('manifest_id', '<>', 0)
              ->orWhere('manifestprod_id', '<>', 0);
            });
        $label_arra = $order_q->get();
        if(count($label_arra) >0){
            foreach($label_arra as $order):
                if($order->warehouse_id){
                    $order['warehouse'] = Warehouse::where('id',$order->warehouse_id)->first();
                }else{
                    $order['warehouse'] = array();
                }
            endforeach;
            $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
            $orders = $label_arra;
                                $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
                    return view('admin.order.courierprint', compact('orders','id','courier_id','couriers','user'));
        }else{
           return redirect()->back()->with('error',"You don't have permission to access this Or order is not yet manifested"); 
        }
        
    }
    
    // label setting for 
        public function manifestmultiple1(){
        $user = Auth::guard('admin')->user();
        $user_id = $user->id;
        $order_q = Order::with('detail');
        $id = date('ymdhis');
        $courier_id ='mix';
        if($user_id !=1){
            $order_q->where(['user_id' => $user_id]);
        }
        $d = implode(',',request('id'));
        $ven = explode(',',$d);
        $order_q->whereIn('id', $ven);
        $order_q->where('ship_courier_id','!=', '9');
        $order_q->where(function ($query) {
        $query->where('manifest_id', '<>', 0)
              ->orWhere('manifestprod_id', '<>', 0);
            });
        $orders = $order_q->get();
        if(count($orders) >0){
            foreach($orders as $order):
                if($order->warehouse_id){
                    $order['warehouse'] = Warehouse::where('id',$order->warehouse_id)->first();
                }else{
                    $order['warehouse'] = array();
                }
            endforeach;
            $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
            return view('admin.order.courierprint1', compact('orders','id','courier_id','couriers','user'));
        }else{
           return redirect()->back()->with('error',"You don't have permission to access this Or order is not yet manifested"); 
        }
        
    }
    
    public function updateweightto500(){
        $user = Auth::guard('admin')->user();
        $user_id = $user->id;
        $d = implode(',',request('id'));
        $ven = explode(',',$d);
        $order_q = Order::whereIn('status',array('1','4'));
        if($user_id !=1){
            $order_q->where(['user_id' => $user_id]);
        }
        $order_q->whereIn('id', $ven);
        $orders = $order_q->get();
        foreach($orders as $order):
            $order->weight='500';
            $order->save();
        endforeach;
        return redirect()->back()->with('success',"Weight updated successfully"); 
    }
    
    public function bulkremittance(){
         return view('admin.order.bulkremittance');
    }
    
    public function storeremittance(Request $request)
   {
        try {
            $collections = (new FastExcel)->import($request->file('excel'));
        } catch (\Exception $exception) {
            return back()->with('error','You have uploaded a wrong format file, please upload the right file.');
        }
        $error ='';
        foreach($collections as $row){
            $remittace = Remittance::where('id',$row['R-id'])->where('user_id',$row['seller-id'])->where('status','in-progress')->first();
            if($remittace){
                $row['wallet_recharge'] = floatval($row['wallet_recharge']);
                $row['paid'] = floatval($row['paid']);
                $row['cod_amount'] = floatval($remittace->cod_amount);
                $row['shipping_amount'] = floatval($remittace->shipping_amount);
                
                if(floatval($row['paid']+$row['wallet_recharge']) > floatval($row['cod_amount']-$row['shipping_amount'])){
                    $error .='Remittance id '.$row['R-id'].' Amount not correct \n';
                }else{
                   $orders = order::where('remittance_id',$row['R-id'])->where('cod_status','!=','success')->get();
                   foreach($orders as $order){
                        $order->cod_status = 'success';
                        $order->cod_date = now();
                        $order->save();
                   }
                   $remitance = Remittance::find($row['R-id']);
                   $user_id = $remitance->user_id;
                   $remitance->paid = $row['paid'];
                   $remitance->recharge = $row['wallet_recharge'];
                   $remitance->utr = $row['utr'];
                   $remitance->status = 'success';
                   $remitance->save();

                   $useddata = admin::find($user_id);
                   $update_blc =$useddata->wallet_blc + $row['wallet_recharge'];


                    $transaction = new Transaction();
                    $transaction->order_id = 0;
                    $transaction->user_id = $user_id;
                    $transaction->awb = null;
                    $transaction->tracking_info = '';
                    $transaction->credit = $row['wallet_recharge'];
                    $transaction->closing_blc = $update_blc;
                    $transaction->debit = '0.00';
                    $transaction->remarks = "Amount Credit against Remitance id: ".$row['R-id'];
                    $transaction->save();

                    $useddata->wallet_blc = $update_blc;
                    $useddata->last_remit_amount = $row['paid'] + $row['wallet_recharge'];
                    $useddata->save();

                }
            }else{
                $error .='Remittance id '.$row['R-id'].' is already paid or seller id mismatch \n';
            }
            
        }
        if($error !=''){
            return redirect()->route('admin.order.bulkremittance')->with('error', ($error));
        }else{
            return redirect()->route('admin.order.bulkremittance')->with('success', 'Remittance Updated');
        }
   }
   
   public function getOrderShopify(){
        $currentUserId = Auth::guard('admin')->user()->id;
           $admin_users = Admin::with('channel')
           ->where('id', $currentUserId) 
            ->get();
          
           foreach($admin_users as $user):
               $usechannel = $user->channel;
               foreach($usechannel as $chanl):
        //  echo $chanl;die;
                   $datetime = explode(' ',$chanl->created_at);
                   if(count($datetime)==2 && $chanl->status =='2'){
                       if($chanl->channel_id =='1'){
                           $user_order =  Channel::getordershopify($chanl->store_name,$chanl->store_access,$datetime[0],$chanl->last_id);
                           $user_order = json_decode($user_order,true);
                           
//                           echo '<pre>';print_R($user_order);die;
                           if(isset($user_order['orders'])){
                               $ordershopify = $user_order['orders'];
                              
                               foreach($ordershopify as $os):
                                    $shopifyOrderId = $os['id'];

                                    $existingOrder = Order::where('shopify_order_id', $shopifyOrderId)->first();

                                    if ($existingOrder) {

                                    }else{
                                       $orderlast = Order::latest()->first();
                                       if ($orderlast) {
                                           $lastOrderId = $orderlast->id;
                                           $lastorder_id = sprintf('%d', intval($lastOrderId) + 1);
                                       }
                                //  echo $orderlast.' '.$lastorder_id;die;
                                       $last_fetch = $os['id'];
                                       // echo $os['financial_status'];die;
                                       //id,
                                       $order = new Order();
                                       $order->user_id = $user->id;
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
                                       if($os['financial_status'] =='paid'){
                                           $order->payment_mode = '12';
                                       }else{
                                           $order->payment_mode = '6';
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
                                       $order->note = $os['note'] ?? null;
                                       $order->status = '1';
                                       $order->company_id = $user->company_id;
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
                                    }
                               endforeach;
                             
                           }else{
   //                            if(isset($user_order['errors']))
   //                            $user_lastid = Channel_integration::findOrFail($chanl->id);
   //                			$user_lastid->status='3';
   //                			$user_lastid->save();
                           }
                       
                       }if($chanl->channel_id =='2'){
                           $after =str_replace(" ",'T',$chanl->created_at);
                           if($chanl->last_id ==0 || $chanl->last_id == '' || $chanl->last_id == null){
                               $after =$chanl->created_at;
                           }else{
                               $after =$chanl->last_id;
                           }
                        
                           $user_order =  Channel::getorderwoocommerce(ltrim(rtrim($chanl->store_name)),ltrim(rtrim($chanl->store_access)),ltrim(rtrim($chanl->customer_key)),ltrim(rtrim($after)));
                           $user_order = json_decode($user_order,true);
                         //echo '<pre>';print_R($user_order);die;
                           if(isset($user_order[0]['id'])){
                               foreach($user_order as $os):
//                                   $orderlast = Order::all()->last();
                                     $orderlast = Order::latest()->first();
                                   if ($orderlast) {
                                       $lastOrderId = $orderlast->id;
                                       $lastorder_id = sprintf('%d', intval($lastOrderId) + 1);
                                   }
                                   $last_fetch = $os['date_created'];
                                   // echo $last_fetch;die;
                                   //id,
                                   $order = new Order();
                                   $order->user_id = $user->id;
                                   $order->order_id = $lastorder_id;
                                   if(isset($os['shipping']) && !empty($os['shipping'])){
                                       $order->ship_fname =  $os['shipping']['first_name'];
                                       $order->ship_lname = $os['shipping']['last_name'];
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
                                       $order->bill_fname =  $os['billing']['first_name'];
                                       $order->bill_lname = $os['billing']['last_name'];
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
                                   if($os['payment_method'] == 'alg_custom_gateway_1'){
                                       $order->payment_mode = '12';
                                   } else {
                                       $order->payment_mode = '6';
                                   }
                                   $order->vendor_order_id = $os['id'];
                                   $order->channel = 'Woocommerce';
                                     $order->channel_id = $chanl->id;
                                   $order->weight =  1000;
                                   $order->length = 10;
                                   $order->breadth = 10;
                                   $order->height = 10;
                                   $order->note = $os['customer_note'] ?? null;
                                   $order->status = '1';
                                   $order->company_id = $user->company_id;
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
                           }else{
                               echo 'bye';
                           }
                       }
                   }
               endforeach;
           endforeach;
          return redirect()->back()->with('success', 'All orders are synced.');

    }    

    public function getOrdercancel(){
        $currentUserId = Auth::guard('admin')->user()->id;
        $admin_users = Admin::with('channel')
        ->where('id', $currentUserId)
        ->get();
     
        foreach($admin_users as $user):
            $usechannel = $user->channel;
            foreach($usechannel as $chanl):
                $datetime = explode(' ',$chanl->created_at);
                if(count($datetime)==2 && $chanl->status =='2'){
                    if($chanl->channel_id =='1'){
                            $updated_at_min = explode(' ',$chanl->created_at)[0];
                        $cancelledorder =  Channel::getcanordershopify($chanl->store_name,$chanl->store_access,$updated_at_min);
                        $cancelledorder = json_decode($cancelledorder,true);
                        if(isset($cancelledorder['orders'])){
                            $cancelledorder = $cancelledorder['orders'];
//                            echo count($cancelledorder);die;
                            foreach($cancelledorder as $can):
                                echo '<pre>';print_r($can);
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
                                // die;
                            endforeach;
                        }
                    }
                }    
            endforeach;
        endforeach;
        return redirect()->back()->with('success', 'All orders are cancelled');
    }

    public function gettrackupdate(){
        $currentUserId = Auth::guard('admin')->user()->id;
        $role_id = Auth::guard('admin')->user()->role_id;
        $current_company = Auth::guard('admin')->user()->company_id;
        
        $orders_q = Order::whereNotIn('status', ['1', '3', '4', '6', '16', '17'])
        ->where('company_id', $current_company)
        ->where(function($query) {
            $query->where('chk_date', '<=', date('Y-m-d H:i:s'))
                  ->orWhereNull('chk_date');
        });

        if (request()->has('ids') && is_array(request('ids'))) {
            $orders_q->whereIn('id', request('ids'));
            $orders = $orders_q->orderBy('chk_date')
            ->take(50)
            ->get();
        } else {
            if ($role_id == '1') {
                // Super Admin can update all orders in the company
            } elseif ($role_id == '2') {
                // Sub Admin can update orders for sellers under them
                $sub_user_id = Admin::getsubuserid($currentUserId);
                $orders_q->whereIn('user_id', $sub_user_id);
            } else {
                // Regular seller can only update their own orders
                $orders_q->where('user_id', $currentUserId);
            }
            $orders = $orders_q->orderBy('chk_date')
            ->take(20)
            ->get();
        }
       
        $anyUpdated = false;
        $updatedOrders = [];
        foreach($orders as $order):
            $oldStatus = strip_tags($order->status);
            try {
                Status::updatestatusorder($order->id);
                $freshOrder = Order::find($order->id);
                $newStatus = strip_tags($freshOrder->status);
                if ($oldStatus != $newStatus) {
                    $anyUpdated = true;
                    $statusHtml = $freshOrder->status;
                    if ($freshOrder->manifest_id && $newStatus == 'Shipped') {
                        $statusHtml = '<span class="badge text-white bg-danger">Manifested</span>';
                    }
                    $updatedOrders[] = [
                        'id' => $freshOrder->id,
                        'status_html' => $statusHtml,
                    ];
                }
            } catch (\Exception $e) {
                // ignore
            }
        endforeach;

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'updated' => $anyUpdated,
                'orders' => $updatedOrders
            ]);
        }

        return redirect()->back()->with('success', 'All orders are updated');
    }
    
    // public function printMultipleLabels(Request $request)
    // {
    //     $ids = array_keys($request->id);

    //     $orders = Order::with('detail')
    //         ->whereIn('id',$ids)
    //             ->where('tracking_info','!=', null)
    //         ->get();

    //     $user = Auth::guard('admin')->user();

    //     $couriers = json_decode(
    //         file_get_contents(resource_path('views/admin/courier.json')),
    //         true
    //     );

    //     foreach($orders as $order){

    //         if($order->warehouse_id){
    //             $order['warehouse'] = Warehouse::find($order->warehouse_id);
    //         }

    //     }

    //     return view(
    //         'admin.order.multiple_label_print',
    //         compact('orders','couriers','user')
    //     );
    // }
        
    // public function printMultipleLabels1(Request $request)
    // {
    //     $ids = array_keys($request->id);

    //     $orders = Order::with('detail')
    //         ->whereIn('id', $ids)
    //         ->where('tracking_info', '!=', null)
    //         ->get();

    //     $user = Auth::guard('admin')->user();

    //     // Load label settings to determine printer type
    //     $labelSetting = LabelSetting::where('company_id', $user->company_id)->first();

    //     $couriers = json_decode(
    //         file_get_contents(resource_path('views/admin/courier.json')),
    //         true
    //     );

    //     foreach ($orders as $order) {
    //         if ($order->warehouse_id) {
    //             $order['warehouse'] = Warehouse::find($order->warehouse_id);
    //         }
    //     }
    //     // dd($labelSetting);

    //     // printer_type: 1 = Thermal 4x6, 2 = Standard A4 (4 labels per sheet)
    //     if ($labelSetting && $labelSetting->printer_type == 2) {
    //         // A4 Layout Option: 0 = Only Label (4 per page), 1 = Label + Invoice
    //         $view = ($labelSetting->a4_print_option == 1) 
    //             ? 'admin.order.A4_Label_and_invoice' 
    //             : 'admin.order.multiple_label_print1';
    //     } else {
    //         $view = 'admin.order.print4x61';
    //     }
    //     // dd($view);

    //     return view(
    //         $view,
    //         compact('orders', 'couriers', 'user', 'labelSetting')
    //     );
    // }

    private function assignShreeMaruti($order, $warehouse, $warehousereturn, $get)
    {
        $pmode ='';
        $coll_value =0;
        if($order['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">C.O.D</span>'){
            $pmode = 'COD';
            $coll_value = $order['custom_total'];
            $paymentStatus ="PENDING";
        }elseif($order['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">Pre-Paid</span>'){
            $pmode = 'ONLINE';
            $paymentStatus ="PAID";
        }
        if($get['data'][request('courier_id')]['mode'] =='fa-plane'){
            $trsfr_type = 'AIR';
        }else{
            $trsfr_type = 'SURFACE';
        }
        if(strlen($order['ship_address']) >55){
            $order['ship_address'] = trim(preg_replace('/\s+/', ' ', substr($order['ship_address'],0,55)));
            $order['ship_address_2'] = trim(preg_replace('/\s+/', ' ', substr($order['ship_address'],55,strlen($order['ship_address'])).' '.$order['ship_address_2']));
        }
        if(strlen($warehouse['address']) >55){
            $warehouse['address'] = trim(preg_replace('/\s+/', ' ', substr($warehouse['address'],0,55)));
            $warehouse['address_2'] = trim(preg_replace('/\s+/', ' ', substr($warehouse['address'],55,strlen($warehouse['address'])).' '.$warehouse['address_2']));
        }
        if(strlen($warehousereturn['ship_address']) >55){
            $warehousereturn['address'] = trim(preg_replace('/\s+/', ' ', substr($warehousereturn['address'],0,55)));
            $warehousereturn['address_2'] = trim(preg_replace('/\s+/', ' ', substr($warehousereturn['address'],55,strlen($warehousereturn['address'])).' '.$warehousereturn['address_2']));
        }
        $shipment_items = array();
        foreach($order['detail'] as $detail){
            $shipment_items[] =array(
                'name'=>$detail['name'],
                'description'=>$detail['name'],
                'sku'=>$detail['code'],
                "unitPrice"=>$detail['price'],
                'quantity'=>$detail['qty'],
                "price"=>$detail['qty']*$detail['price'],
                "weight"=>1
            );
        }
        $isSame = $order['same_add'] == '1';
        $shremarutidata = array(
            'orderId' =>$order['order_id'],
            "orderSubtype"=>"FORWARD",
            "orderCreatedAt"=>date('Y-m-d H:i:s'),
            "currency"=>"INR",
            "amount"=>$order['custom_total'],
            "weight"=>$order['weight'],
            "lineItems"=>$shipment_items,
            "paymentType"=>$pmode,
            "paymentStatus"=>$paymentStatus,
            'shippingAddress'=>array(
                "name"=>$order['ship_fname'].' '.$order['ship_lname'],
                "email"=>$order['ship_email'],
                "phone"=>$order['ship_phone'],
                "address1"=>$order['ship_address'],
                "address2"=>$order['ship_address_2'],
                "zip"=>$order['ship_pincode'],
                "city"=>$order['ship_city'],
                "state"=>$order['ship_state'],
                "country"=>"India"
            ),
            "billingAddress" => array(
                "name" => $isSame 
                    ? $order['ship_fname'].' '.$order['ship_lname'] 
                    : $order['bill_fname'].' '.$order['bill_lname'],

                "email" => $order['ship_email'],
                "phone" => $isSame ? $order['ship_phone'] : $order['bill_phone'],
                "address1" => $isSame ? $order['ship_address'] : $order['bill_address'],
                "address2" => $isSame ? $order['ship_address_2'] : $order['bill_address_2'],
                "zip" => $isSame ? $order['ship_pincode'] : $order['bill_pincode'],
                "city" => $isSame ? $order['ship_city'] : $order['bill_city'],
                "state" => $isSame ? $order['ship_state'] : $order['bill_state'],
                "country" => "India",
            ),
            'pickupAddress'=>array(
                "name"=>$warehouse['name'],
                "phone"=>$warehouse['phone'],
                "address1"=>$warehouse['address'],
                "address2"=>$warehouse['address_2'],
                "zip"=>$warehouse['pincode'],
                "city"=>$warehouse['city'],
                "state"=>$warehouse['state'],
                "country"=>"India"
            ),
            'returnAddress'=>array(
                "name"=>$warehousereturn['name'],
                "phone"=>$warehousereturn['phone'],
                "address1"=>$warehousereturn['address'],
                "address2"=>$warehousereturn['address_2'],
                "zip"=>$warehousereturn['pincode'],
                "city"=>$warehousereturn['city'],
                "state"=>$warehousereturn['state'],
                "country"=>"India"
            ),
            'deliveryPromise'=>$trsfr_type,
            "discountUnit"=> "RUPEES",
            "length"=>$order['length'],
            "height"=>$order['height'],
            "width"=>$order['breadth'],
        );
        $res_ats= Integration_courier::shipment_shreemaruti(json_encode($shremarutidata));
        api_logs(json_encode($shremarutidata), $res_ats,$order['id'],'11');
        $re_array = json_decode($res_ats,true);
        if(isset($re_array['status']) && $re_array['status'] == '200'){
            return [
                'status' => 1,
                'awb_no' => $re_array['data']['awbNumber'],
                'cawb_no' => $re_array['data']['cAwbNumber'],
                'label_print' => null,
                'shipment_id' => null,
            ];
        }else{
            $e_msg ='Issue in data';
            if(isset($re_array['message'])){
                $e_msg =$re_array['message'][0];
            }
            return [
                'status' => 0,
                'message' => $e_msg,
            ];
        }
    }

    private function assignPicknDel($order, $warehouse, $warehousereturn, $get)
    {
        $coll_value =0;
        $awb_no = $awb ='';
        $p_type='';
        $arrayawb = array(
            'count' =>1
        );
       
        if(strip_tags($order['payment_mode']) =='C.O.D'){
            $pmode = 'COD';
            $coll_value = $order['custom_total'];
        }elseif(strip_tags($order['payment_mode']) =='Pre-Paid'){
            $pmode = 'Prepaid';
        }
        if($get['data'][request('courier_id')]['mode'] =='fa-plane'){
            $trsfr_type = 'ndd';
        }else{
            $trsfr_type = 'regular';
        }

        $shipment_items = array();
        
        $shadowfax_data = array(
            'Control'=>array(
              "RequestId"=>"18ce9553-cdf1-48a2-8017-c3859e4f476e",
              "Source"=>3,
              "RequestTime"=>time(),
              "Version"=>"3.2"
            ),
            'Data'=>array(
                "UserId"=>7174,
                "OrderDetails"=>array(
                    array(
                        "ClientUniqueNo"=>Auth::guard('admin')->user()->id.time(),
                        "BrandName"=>'Hyloship',
                        "VehicleType"=>"Bike",
                        "Info"=>array(
                            array(
                                "Pickup"=>array(
                                    "UniqueNo"=>$warehouse['id'],
                                    "PersonName"=>$warehouse['contact_name'],
                                    "Mobile"=>$warehouse['phone'],
                                    "HouseNo"=>"",
                                    "Landmark"=>"",
                                    "Address"=>$warehouse['address'].' '.$warehouse['address_2'],
                                    "Lat"=>$warehouse['latitude'],
                                    "Lng"=>$warehouse['longitude'],
                                    "Pincode"=>$warehouse['pincode'],
                                    "CashPaid"=>0,
                                    "CashCollection"=>$coll_value,
                                    "Comment"=>"",
                                    "PickupDate"=>"",
                                    "PickupSlot"=>"",
                                    "RTOName"=>$warehousereturn['contact_name'],
                                    "RTOMobile"=>$warehousereturn['phone'],
                                    "RTOAddr"=>$warehousereturn['address'].' '.$warehousereturn['address_2'],
                                    "RTOPincode"=>$warehousereturn['pincode'],
                                ),
                                "Item"=>array(
                                    array(
                                        "Qty"=>count($order['detail']),
                                        "Type"=>"Goods",
                                        "IsFragile"=>"0",
                                        "IsLiquid"=>"0",
                                        "Name"=>null,
                                        "Cost"=>$coll_value,
                                        "Length"=>$order['length'],
                                        "Width"=>$order['breadth'],
                                        "Height"=>$order['height'],
                                        "ActualWeight"=>$order['weight'],
                                        "EWayBillNo"=>"",
                                    )
                                ),
                                "Delivery"=>array(
                                    "UniqueNo"=>$order['ship_phone'],
                                    "PersonName"=>$order['ship_fname'].' '.$order['ship_lname'],
                                    "Mobile"=>$order['ship_phone'],
                                    "AddressType"=>"",
                                    "HouseNo"=>"",
                                    "Landmark"=>"",
                                    "Address"=>$order['ship_address'].' '.$order['ship_address_2'],
                                    "Lat"=>$order['ship_latitude'],
                                    "Lng"=>$order['ship_longitude'],
                                    "Pincode"=>$order['ship_pincode'],
                                    "CashCollection"=>$coll_value,
                                    "Comment"=>"",
                                )
                            )
                        )
                    )
                )
            )
        );
        $res_shadowfax= Integration_more::shipment_pckndel(json_encode($shadowfax_data));
        api_logs(json_encode($shadowfax_data), $res_shadowfax ,$order['id'],'12');
        $res_shadowfax = json_decode($res_shadowfax,true);
        if(isset($res_shadowfax['Control']) && $res_shadowfax['Control']['Status'] ==1){
            if(isset($res_shadowfax['Data'][0])){
                return [
                    'status' => 1,
                    'awb_no' => $res_shadowfax['Data'][0]['Details'][0]['AWBNo'],
                    'label_print' => $res_shadowfax['Data'][0]['Details'][0]['Label4X3'],
                    'cawb_no' => null,
                    'shipment_id' => null,
                ];
            }
        }else{
            $e_msg ='Issue in data';
            if(isset($res_shadowfax['Control']['Message'])){
                $e_msg =$res_shadowfax['Control']['Message'];
            }
            return [
                'status' => 0,
                'message' => $e_msg,
            ];
        }
    }

    private function assignParcelUncle($order, $warehouse, $get, $orde_user_id)
    {
        $pu_integration = Integration::where('user_id', $orde_user_id)->where('courier_id', 13)->first();
        if (!$pu_integration) {
            $pu_integration = new Integration();
        }
        if (empty($pu_integration->pu_api_key)) {
            $pu_integration->pu_api_key = env('PARCEL_UNCLE_API_KEY');
            $pu_integration->pu_mode = env('PARCEL_UNCLE_MODE', 'Test');
            $pu_integration->pu_carrier_title = env('PARCEL_UNCLE_CARRIER_TITLE', 'Parcel Uncle Sandbox');
        }
        if (empty($pu_integration->pu_api_key)) {
            return [
                'status' => 0,
                'message' => 'Parcel Uncle credentials are not configured for this user.',
            ];
        }

        if (!$warehouse) {
            return [
                'status' => 0,
                'message' => 'Shipper warehouse address is missing.',
            ];
        }

        $is_cod = (strip_tags($order['payment_mode']) == 'C.O.D');
        $cod_amount = $is_cod ? (float)$order['custom_total'] : 0.0;
        
        $service_type = 'SAME_DAY';
        $mode = strtolower($get['data'][request('courier_id')]['mode'] ?? '');
        if ($mode === 'fa-bicycle' || str_contains($mode, 'ndd')) {
            $service_type = 'NEXT_DAY';
        } elseif ($mode === 'fa-bolt' || str_contains($mode, 'quick')) {
            $service_type = 'EXPRESS_4H';
        } else {
            $service_type = 'SAME_DAY';
        }

        // Fetch company name
        $user = Admin::find($orde_user_id);
        $sender_name = $user ? trim($user->company_name) : '';
        if (empty($sender_name)) {
            $sender_name = trim($warehouse->contact_name ?: $warehouse->name);
        }

        $sender_phone = preg_replace('/[^0-9]/', '', $warehouse->phone);
        if (strlen($sender_phone) >= 10) {
            $sender_phone = substr($sender_phone, -10);
        }

        $recipient_name = trim(($order['ship_fname'] ?? '') . ' ' . ($order['ship_lname'] ?? ''));

        $raw_recipient_phone = !empty($order['ship_mobile']) ? $order['ship_mobile'] : $order['ship_phone'];
        $recipient_phone = preg_replace('/[^0-9]/', '', $raw_recipient_phone);
        if (strlen($recipient_phone) >= 10) {
            $recipient_phone = substr($recipient_phone, -10);
        }

        $pu_payload = [
            'service_type' => $service_type,
            'payment_method' => 'WALLET',
            'payment_mode' => $is_cod ? 'COD' : 'Prepaid',
            'order_number' => (string)$order['order_id'],
            'total_amount' => (float)$order['total'],
            'cod_amount' => (float)$cod_amount,
            'pickup_address' => $warehouse->address . ' ' . ($warehouse->address_2 ?? ''),
            'pickup_city' => $warehouse->city,
            'pickup_state' => $warehouse->state,
            'pickup_pincode' => (string)$warehouse->pincode,
            'delivery_address' => $order['ship_address'] . ' ' . ($order['ship_address_2'] ?? ''),
            'delivery_city' => $order['ship_city'],
            'delivery_state' => $order['ship_state'],
            'delivery_pincode' => (string)$order['ship_pincode'],
            'sender_name' => $sender_name,
            'sender_phone' => $sender_phone,
            'recipient_name' => $recipient_name,
            'recipient_phone' => $recipient_phone,
            'weight_kg' => (float)round($order['weight'] / 1000, 2),
            'parcel_type' => 'PACKAGE'
        ];

        // Validate details before calling API
        if (strcasecmp($sender_name, $recipient_name) === 0) {
            return [
                'status' => 0,
                'message' => 'Validation Error: Sender name and Recipient name cannot be the same. Please ensure sender is your company name.',
            ];
        }
        if (!preg_match('/^[6-9]\d{9}$/', $sender_phone)) {
            return [
                'status' => 0,
                'message' => 'Validation Error: Invalid sender phone number. Must be a valid 10-digit Indian mobile number.',
            ];
        }
        if (!preg_match('/^[6-9]\d{9}$/', $recipient_phone)) {
            return [
                'status' => 0,
                'message' => 'Validation Error: Invalid customer/recipient phone number. Must be a valid 10-digit Indian mobile number (no leading zero).',
            ];
        }
        if ($sender_phone === $recipient_phone) {
            return [
                'status' => 0,
                'message' => 'Validation Error: Sender phone number and recipient phone number cannot be the same.',
            ];
        }

        try {
            $res_pu = Integration::shipment_parcel_uncle($pu_payload, $pu_integration);
            api_logs(json_encode($pu_payload), $res_pu, $order['id'], '13');
            $res_pu_arr = json_decode($res_pu, true);
        } catch (\Throwable $e) {
            api_logs(json_encode($pu_payload), 'EXCEPTION: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine(), $order['id'], '13');
            return [
                'status' => 0,
                'message' => 'Parcel Uncle API Connection Failed: ' . $e->getMessage(),
            ];
        }
        if (isset($res_pu_arr['success']) && $res_pu_arr['success'] && isset($res_pu_arr['data']['tracking_number'])) {
            return [
                'status' => 1,
                'awb_no' => $res_pu_arr['data']['tracking_number'],
                'shipment_id' => $res_pu_arr['data']['tracking_number'],
                'label_print' => null,
                'cawb_no' => null,
            ];
        } else {
            $err_msg = $res_pu_arr['message'] ?? 'Error from Parcel Uncle API';
            if (isset($res_pu_arr['errors']) && is_array($res_pu_arr['errors'])) {
                $err_msg .= ': ' . json_encode($res_pu_arr['errors']);
            }
            return [
                'status' => 0,
                'message' => $err_msg,
            ];
        }
    }

    private function assignIndiaPost($order, $warehouse, $company_id)
    {
        $ip_pincode = DB::table('ip_pincodes')
            ->where('company_id', $company_id)
            ->where('used', '0')
            ->orderBy('id', 'asc')
            ->first();
        if (!$ip_pincode) {
            return [
                'status' => 0,
                'message' => 'No unused India Post barcodes found in the pool. Please upload barcodes under Integrations.'
            ];
        }
        $barcode = $ip_pincode->awb;

        $ip_integration = Integration::where('user_id', auth()->guard('admin')->user()->id)->where('courier_id', 14)->first();
        $ip_cust_id = ($ip_integration && $ip_integration->ip_customer_id) ? $ip_integration->ip_customer_id : env('INDIAPOST_CUSTOMER_ID', '1843579100');
        $ip_contract_id = ($ip_integration && $ip_integration->ip_contract_id) ? $ip_integration->ip_contract_id : env('INDIAPOST_CONTRACT_ID', '41441234');

        $is_cod = false;
        $cod_amount = 0;
        if ($order['payment_mode'] == '<span class="badge text-white" style="background:#3a87ad">C.O.D</span>') {
            $is_cod = true;
            $cod_amount = (float)($order['custom_total'] ?: $order['total']);
        }

        $service_type = ($ip_integration && $ip_integration->ip_service_type) ? $ip_integration->ip_service_type : env('INDIAPOST_SERVICE_TYPE', 'BP');
        if ($service_type === 'BP' && $ip_integration && (str_contains(strtolower($ip_integration->ip_carrier_title), 'speed') || str_contains(strtolower($ip_integration->ip_carrier_title), 'sp'))) {
            $service_type = 'SP';
        }

        // article_type long-form values per sample input in docs
        // service_type (BP/SP) is required — confirmed by API error
        $weight_g = (float)$order['weight'];
        if ($service_type === 'SP') {
            $article_type = ($weight_g <= 500) ? 'SP_INLAND_DOC' : 'SP_INLAND_PARCEL';
            $shape_of_article = ($weight_g <= 500) ? 'DOC' : 'NROL';
        } else {
            $article_type = 'BUSINESS_PARCEL';
            $shape_of_article = 'NROL';
        }

        $ip_payload = [
            'articles' => [
                [
                    'bulk_customer_id' => (string)$ip_cust_id,
                    'contract_id' => (string)$ip_contract_id,
                    'barcode_no' => (string)$barcode,
                    'pickup_or_dropoff' => 'DROPOFF',
                    'pickup_dropoff_office_id' => 21260024,
                    'article_type' => $article_type,
                    'service_type' => $service_type,
                    'physical_weight' => (int)round($order['weight']),
                    'shape_of_article' => $shape_of_article,
                    'length' => (float)($order['length'] ?: 0),
                    'breadth_diameter' => (float)($order['breadth'] ?: 0),
                    'height' => (float)($order['height'] ?: 0),
                    'priority_flag' => 'FALSE',
                    'delivery_instruction' => '',
                    'delivery_slot' => '',
                    'instruction_rts' => 'RTS',
                    'sender_name' => substr($warehouse->contact_name ?: $warehouse->name, 0, 80),
                    'sender_company' => substr($warehouse->name, 0, 80),
                    'sender_add_line_1' => substr($warehouse->address, 0, 80),
                    'sender_add_line_2' => substr($warehouse->address_2 ?? '', 0, 80),
                    'sender_city' => substr($warehouse->city, 0, 80),
                    'sender_state' => substr($warehouse->state, 0, 80),
                    'sender_pincode' => (string)$warehouse->pincode,
                    'sender_emailid' => '',
                    'sender_alt_contact' => '',
                    'sender_kyc' => '',
                    'sender_tax_reference' => '',
                    'receiver_name' => substr($order['ship_fname'] . ' ' . $order['ship_lname'], 0, 80),
                    'receiver_company' => '',
                    'receiver_add_line_1' => substr($order['ship_address'], 0, 80),
                    'receiver_add_line_2' => substr($order['ship_address_2'] ?? '', 0, 80),
                    'receiver_city' => substr($order['ship_city'], 0, 80),
                    'receiver_state' => substr($order['ship_state'], 0, 80),
                    'receiver_pincode' => (string)$order['ship_pincode'],
                    'receiver_emailid' => '',
                    'receiver_alt_contact' => '',
                    'receiver_kyc' => '',
                    'receiver_tax_reference' => '',
                    'alt_address_flag' => 'FALSE',
                    'pickup_address_flag' => 'FALSE',
                    'drop_off_pincode' => (string)$warehouse->pincode,
                    'sender_mobile_no' => (string)(preg_replace('/[^0-9]/', '', $warehouse->phone) ?: '9999999999'),
                    'receiver_mobile_no' => (string)(preg_replace('/[^0-9]/', '', $order['ship_phone']) ?: '9999999999'),
                    'prepayment_code' => '',
                    'value_of_prepayment' => 0,
                    'codr_cod' => $is_cod ? 'COD' : '',
                    'value_for_codr_cod' => (float)$cod_amount,
                    'insurance_type' => '',
                    'value_of_insurance' => 0,
                    'ack' => 'FALSE',
                    'reg' => 'FALSE',
                    'otp' => 'FALSE',
                    'bulk_reference' => ''
                ]
            ]
        ];

        try {
            $res_ip = Integration::shipment_indiapost($ip_payload, $ip_integration);
            api_logs(json_encode($ip_payload), $res_ip, $order['id'], '14');
            $res_ip_arr = json_decode($res_ip, true);
        } catch (\Throwable $e) {
            api_logs(json_encode($ip_payload), 'EXCEPTION: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine(), $order['id'], '14');
            return [
                'status' => 0,
                'message' => 'India Post API Connection Failed: ' . $e->getMessage(),
            ];
        }
        if (isset($res_ip_arr['success']) && $res_ip_arr['success'] && empty($res_ip_arr['error_articles']) && !empty($res_ip_arr['valid_articles'])) {
            DB::table('ip_pincodes')->where('id', $ip_pincode->id)->update(['used' => '1', 'updated_at' => now()]);
            return [
                'status' => 1,
                'awb_no' => $barcode,
                'shipment_id' => $barcode,
                'label_print' => null,
                'cawb_no' => null,
            ];
        } else {
            $err_msg = 'Error from India Post API';
            if (!empty($res_ip_arr['error_articles'])) {
                $first_error_art = $res_ip_arr['error_articles'][0];
                if (!empty($first_error_art['errors'])) {
                    $err_msg = implode(', ', $first_error_art['errors']);
                }
            } elseif (isset($res_ip_arr['message'])) {
                $err_msg = $res_ip_arr['message'];
            }
            if (isset($res_ip_arr['errors']) && is_array($res_ip_arr['errors'])) {
                $err_msg .= ': ' . json_encode($res_ip_arr['errors']);
            }
            return [
                'status' => 0,
                'message' => $err_msg,
            ];
        }
    }

    private function autoRegisterDelhiveryWarehouse($warehouse, $ttype)
    {
        try {
            $wh_email = $warehouse['email'] ?? 'support@hyloship.com';
            $wh_name = $warehouse['company'].$ttype;
            $wh_address = $warehouse['address'].' '.$warehouse['address_2'];
            $wh_country_name = 'India';
            if (isset($warehouse['country_id'])) {
                $c = Country::find($warehouse['country_id']);
                if ($c) {
                    $wh_country_name = $c->name;
                }
            }
            $warehouse_array = json_encode(
                array(
                    'phone'=>$warehouse['phone'],
                    'city'=>$warehouse['city'],
                    'name'=>$wh_name,
                    'pin'=>$warehouse['pincode'],
                    'address'=>$wh_address,
                    'country'=>$wh_country_name,
                    'email'=>$wh_email,
                    'registered_name'=>$warehouse['company'],
                    'return_address'=>$wh_address,
                    'return_pin'=>$warehouse['pincode'],
                    'return_city'=>$warehouse['city'],
                    'return_state'=>$warehouse['state'] ?? $warehouse['city'],
                    'return_country'=>$wh_country_name,
                ),true
            );
            Integration::create_warehouse($warehouse_array, $ttype);
        } catch (\Throwable $e) {
            // Silently ignore
        }
    }

    /* ================================================
     * B2B ORDER METHODS
     * ================================================ */

    public function b2bCreate()
    {
        $counrtries = Country::orderBy('name')->get();
        $order_id   = 'B2B-' . time();
        return view('b2b.orders.create', compact('counrtries', 'order_id'));
    }

    public function b2bIndex()
    {
        $user       = Auth::guard('admin')->user();
        $user_id    = $user->id;
        $re_data    = request()->all();
        $sortField  = request('sortField', 'id');
        $sortDir    = request('sortDirection', 'desc');

        $q = Order::with('detail')->where('user_id', $user_id);

        if (\Schema::hasColumn('orders', 'order_type')) {
            $q->where('order_type', 'b2b');
        }

        $start = request('start_date', date('Y-01-01'));
        $end   = request('end_date',   date('Y-m-d'));
        $q->where('created_at', '>=', $start . ' 00:00:00')
          ->where('created_at', '<=', $end   . ' 23:59:59');

        if (request('vendor_order_id')) {
            $q->where('vendor_order_id', 'like', '%' . request('vendor_order_id') . '%');
        }
        if (request('tracking_info')) {
            $q->where('tracking_info', request('tracking_info'));
        }
        if (request('order_status') && request('order_status') != '0') {
            $q->where('status', request('order_status'));
        }

        $orders    = $q->orderBy($sortField, $sortDir)->paginate(50);
        $warehouse = Warehouse::where('user_id', $user_id)->where('deleted', '0')->get();
        return view('b2b.orders.index', compact('orders', 're_data', 'sortField', 'sortDir', 'warehouse'));
    }

    public function b2bStore(Request $request)
    {
        $request->validate([
            'ship_fname'    => 'required|string|max:100',
            'ship_phone'    => 'required',
            'ship_address'  => 'required',
            'ship_country'  => 'required',
            'ship_pincode'  => 'required',
            'ship_city'     => 'required',
            'ship_state'    => 'required',
            'vendor_order_id' => 'required',
            'invoice_no'    => 'required|array|min:1',
            'invoice_date'  => 'required|array|min:1',
            'invoice_amount'=> 'required|array|min:1',
            'box_name'      => 'required|array|min:1',
            'length'        => 'required|array|min:1',
            'breadth'       => 'required|array|min:1',
            'height'        => 'required|array|min:1',
            'weight_per_box'=> 'required|array|min:1',
        ]);

        $user        = Auth::guard('admin')->user();
        $user_id     = $user->id;
        $company_id  = $user->company_id;

        // Calculate total weight (sum of weight_per_box * no_of_boxes)
        $totalWeight = 0;
        foreach ($request->weight_per_box as $k => $wpb) {
            $qty = $request->no_of_boxes[$k] ?? 1;
            $totalWeight += ($wpb * $qty);
        }

        // Use first box dimensions as shipment dimensions
        $length  = $request->length[0]  ?? 10;
        $breadth = $request->breadth[0] ?? 10;
        $height  = $request->height[0]  ?? 10;

        // Build invoice_no — ensure uniqueness by appending suffix if duplicate
        $raw_invoice = implode(',', $request->invoice_no);
        $invoice_no  = $raw_invoice;
        if (Order::where('invoice_no', $invoice_no)->exists()) {
            $invoice_no = $raw_invoice . '-' . date('His');
        }

        // Build order
        $order = new Order();
        $order->user_id         = $user_id;
        $order->company_id      = $company_id;
        $order->vendor_order_id = $request->vendor_order_id;
        $order->order_type      = 'b2b';
        $order->payment_mode    = $request->payment_mode ?? 12;
        $order->weight          = $totalWeight;
        $order->length          = $length;
        $order->breadth         = $breadth;
        $order->height          = $height;
        $order->ship_fname      = $request->ship_fname;
        $order->ship_lname      = $request->ship_lname;
        $order->ship_company    = $request->ship_company;
        $order->ship_email      = 'info@hyloship.com';
        $order->ship_phone      = $request->ship_phone;
        $order->ship_address    = $request->ship_address;
        $order->ship_country    = $request->ship_country;
        $order->ship_pincode    = $request->ship_pincode;
        $order->ship_city       = $request->ship_city;
        $order->ship_state      = $request->ship_state;
        $order->ship_gstin      = $request->ship_gstin;
        $order->e_bill_no       = $request->e_bill_no;
        $order->invoice_no      = $invoice_no;
        $order->same_add        = $request->same_add ?? 1;
        $order->status          = 1;
        $order->total           = array_sum($request->invoice_amount);
        $order->custom_total    = array_sum($request->invoice_amount);

        // Billing address
        if ($request->same_add == '0') {
            $order->bill_fname   = $request->bill_fname;
            $order->bill_lname   = $request->bill_lname;
            $order->bill_company = $request->bill_company;
            $order->bill_phone   = $request->bill_phone;
            $order->bill_address = $request->bill_address;
            $order->bill_country = $request->bill_country;
            $order->bill_pincode = $request->bill_pincode;
            $order->bill_city    = $request->bill_city;
            $order->bill_state   = $request->bill_state;
            $order->bill_gstin   = $request->bill_gstin;
        }

        try {
            $order->save();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                // Still duplicate (race condition) — force unique with timestamp+random
                $order->invoice_no = $raw_invoice . '-' . date('His') . rand(10, 99);
                $order->save();
            } else {
                throw $e;
            }
        }

        // Save box/product details as order details
        foreach ($request->box_name as $k => $boxName) {
            $detail = new OrderDetail();
            $detail->order_id    = $order->id;
            $detail->name        = $boxName;
            $detail->sku         = $request->sku[$k] ?? '';
            $detail->hsn_code    = $request->hsn_code[$k] ?? '';
            $detail->qty         = $request->no_of_boxes[$k] ?? 1;
            $detail->price       = $request->price_per_box[$k] ?? 0;
            $detail->tax_percent = $request->tax_rate[$k] ?? 0;
            $detail->save();
        }

        return redirect()->route('admin.b2b.order.index')
                         ->with('success', 'B2B Order created successfully!');
    }


    /* ================================================
     * GET B2B COURIER RATES (AJAX)
     * ================================================ */
    public function getB2bCourier()
    {
        $order_id     = request('order_id');
        $warehouse_id = request('warehouse_id', 0);

        $order = Order::with('detail')->find($order_id);
        if (!$order) {
            return ['status' => 0, 'message' => 'Order not found'];
        }
        if (empty($order->ship_pincode) || empty($order->ship_fname)) {
            return ['status' => 0, 'message' => 'Shipping details missing — check the order detail page'];
        }

        // Resolve user
        $order_user = Admin::find($order->user_id);
        if (!$order_user) {
            return ['status' => 0, 'message' => 'Order user not found'];
        }
        $user_id    = $order_user->id;
        $company_id = $order_user->company_id;

        // Resolve warehouse pincode
        if ($warehouse_id == 0) {
            return ['status' => 0, 'message' => 'Please select a pickup warehouse'];
        }
        $warehouse = Warehouse::find($warehouse_id);
        if (!$warehouse) {
            return ['status' => 0, 'message' => 'Warehouse not found'];
        }
        $origin_pin = $warehouse->pincode;
        $dest_pin   = $order->ship_pincode;

        // Determine B2B zones
        $origin_zone = Order::getB2bZone($origin_pin);
        $dest_zone   = Order::getB2bZone($dest_pin);

        if (!$origin_zone || !$dest_zone) {
            // Diagnostic: check what state each pincode resolves to
            $originPinData = \App\Models\Admin\Pincode::where('pincode', $origin_pin)->first();
            $destPinData   = \App\Models\Admin\Pincode::where('pincode', $dest_pin)->first();
            $originState   = $originPinData ? $originPinData->state : 'NOT IN DB';
            $destState     = $destPinData   ? $destPinData->state   : 'NOT IN DB';
            return [
                'status'  => 0,
                'message' => 'Zone not found. '
                    . 'Warehouse pin: ' . $origin_pin . ' → state: "' . $originState . '" → zone: ' . ($origin_zone ?: 'NULL') . '. '
                    . 'Delivery pin: '  . $dest_pin   . ' → state: "' . $destState   . '" → zone: ' . ($dest_zone   ?: 'NULL') . '. '
                    . 'Add state to pincodes table or ensure the pincode exists.',
            ];
        }

        // Fetch all B2B ratecards for this user/company matching the zone pair
        $ratecards = \App\Models\Admin\B2bRatecard::where('user_id', $user_id)
            ->where('company_id', $company_id)
            ->where('status', 1)
            ->where('origin_zone', $origin_zone)
            ->where('destination_zone', $dest_zone)
            ->get();

        if ($ratecards->isEmpty()) {
            // Diagnostic: count total ratecards for this user (any zone)
            $totalForUser   = \App\Models\Admin\B2bRatecard::where('user_id', $user_id)->where('company_id', $company_id)->count();
            $availableZones = \App\Models\Admin\B2bRatecard::where('user_id', $user_id)->where('company_id', $company_id)
                ->where('status', 1)
                ->selectRaw('origin_zone, destination_zone, COUNT(*) as cnt')
                ->groupBy('origin_zone', 'destination_zone')
                ->get()
                ->map(fn($r) => $r->origin_zone . '→' . $r->destination_zone . '(' . $r->cnt . ')')
                ->implode(', ');
            return [
                'status'  => 0,
                'message' => "No B2B rate card found for zone {$origin_zone} → {$dest_zone}. "
                    . "Total ratecards for your account: {$totalForUser}. "
                    . ($availableZones ? "Available zone pairs: {$availableZones}." : "No B2B ratecards uploaded yet — go to Rates → B2B Rate to upload."),
            ];
        }

        $results = [];
        foreach ($ratecards as $rc) {
            // Volumetric weight (kg) using B2B divisor (grams L*B*H / divisor)
            $divisor     = $rc->divisor ?: 4500;
            $actual_wt_g = $order->weight; // stored in grams
            $vol_wt_kg   = ($order->length * $order->breadth * $order->height) / $divisor;
            $actual_wt_kg = $actual_wt_g / 1000;
            $chargeable_kg = max($vol_wt_kg, $actual_wt_kg, $rc->min_chg_wt);

            // Base freight
            $freight = $chargeable_kg * $rc->rate;

            // FSC
            $fsc = $freight * ($rc->fsc_percentage / 100);

            // ROV (carrier risk on declared value)
            $rov = max($rc->rov_carrier_min, ($order->total * $rc->rov_carrier_percentage / 100));

            // Handling (by weight)
            $handling = 0;
            if ($chargeable_kg >= 400) {
                $handling = $rc->handling_above_400;
            } elseif ($chargeable_kg >= 250) {
                $handling = $rc->handling_250_400;
            } elseif ($chargeable_kg >= 100) {
                $handling = $rc->handling_100_250;
            }

            // Green tax
            $green_tax = $chargeable_kg * $rc->green_tax_per_kg;

            // Processing / LR charge
            $processing = $rc->processing_charge;

            // Total (before GST)
            $subtotal = $freight + $fsc + $rov + $handling + $green_tax + $processing;
            $gst      = $subtotal * 0.18;
            $total    = $subtotal + $gst;

            // Apply min LR charge
            if ($total < $rc->min_lr_charge) {
                $total = $rc->min_lr_charge;
            }
            $total = round($total, 2);

            $results[] = [
                'ratecard_id'    => $rc->id,
                'courier_code'   => $rc->courier_code,
                'courier_name'   => $rc->courier_name ?: $rc->courier_code,
                'transport'      => $rc->transport,
                'origin_zone'    => $origin_zone,
                'dest_zone'      => $dest_zone,
                'divisor'        => $divisor,
                'chargeable_kg'  => round($chargeable_kg, 2),
                'actual_wt_kg'   => round($actual_wt_kg, 2),
                'vol_wt_kg'      => round($vol_wt_kg, 2),
                'rate_per_kg'    => $rc->rate,
                'freight'        => round($freight, 2),
                'fsc'            => round($fsc, 2),
                'rov'            => round($rov, 2),
                'handling'       => round($handling, 2),
                'green_tax'      => round($green_tax, 2),
                'processing'     => round($processing, 2),
                'gst'            => round($gst, 2),
                'total'          => $total,
                'price_display'  => 'Rs.' . number_format($total, 2),
            ];
        }

        if (empty($results)) {
            return ['status' => 0, 'message' => 'No matching B2B rates found'];
        }

        return ['status' => 1, 'data' => $results, 'origin_zone' => $origin_zone, 'dest_zone' => $dest_zone];
    }

    /* ================================================
     * ASSIGN B2B ORDER (AJAX) — wallet debit + Delhivery manifest
     * ================================================ */
    public function assignB2b(Request $request)
    {
        try {
            $request->validate([
                'order_id'     => 'required|numeric',
                'warehouse_id' => 'required|numeric',
                'ratecard_id'  => 'required|numeric',
            ]);

            $order = Order::with('detail')->find($request->order_id);
            if (!$order) {
                return ['status' => 0, 'message' => 'Order not found'];
            }
            if (!empty($order->tracking_info)) {
                return ['status' => 0, 'message' => 'This order is already assigned (AWB/LRN: ' . $order->tracking_info . ')'];
            }

            // Resolve user and wallet
            $order_user = Admin::find($order->user_id);
            if (!$order_user) {
                return ['status' => 0, 'message' => 'Order user not found'];
            }
            $user_id    = $order_user->id;
            $company_id = $order_user->company_id;
            $parent_id  = $order_user->parent_id;

            // Fetch ratecard
            $rc = \App\Models\Admin\B2bRatecard::where('id', $request->ratecard_id)
                ->where('user_id', $user_id)
                ->where('status', 1)
                ->first();
            if (!$rc) {
                return ['status' => 0, 'message' => 'Rate card not found or not authorised'];
            }

            // Re-calculate total cost (same logic as getB2bCourier)
            $divisor      = $rc->divisor ?: 4500;
            $actual_wt_kg = $order->weight / 1000;
            $vol_wt_kg    = ($order->length * $order->breadth * $order->height) / $divisor;
            $chargeable   = max($vol_wt_kg, $actual_wt_kg, $rc->min_chg_wt);
            $freight      = $chargeable * $rc->rate;
            $fsc          = $freight * ($rc->fsc_percentage / 100);
            $rov          = max($rc->rov_carrier_min, ($order->total * $rc->rov_carrier_percentage / 100));
            $handling     = 0;
            if ($chargeable >= 400)      $handling = $rc->handling_above_400;
            elseif ($chargeable >= 250)  $handling = $rc->handling_250_400;
            elseif ($chargeable >= 100)  $handling = $rc->handling_100_250;
            $green_tax    = $chargeable * $rc->green_tax_per_kg;
            $processing   = $rc->processing_charge;
            $subtotal     = $freight + $fsc + $rov + $handling + $green_tax + $processing;
            $gst          = round($subtotal * 0.18, 2);
            $total_cost   = round($subtotal + $gst, 2);
            if ($total_cost < $rc->min_lr_charge) {
                $total_cost = round($rc->min_lr_charge, 2);
            }

            // Wallet check
            $wallet_blc  = Admin::find($user_id)->wallet_blc;
            $limit_loan  = Admin::find($user_id)->limit_loan;
            if (($wallet_blc + $limit_loan) < $total_cost) {
                return ['status' => 0, 'message' => 'Insufficient wallet balance. Required: ₹' . $total_cost . ', Available: ₹' . ($wallet_blc + $limit_loan)];
            }

            // Resolve warehouse
            $warehouse = Warehouse::find($request->warehouse_id);
            if (!$warehouse) {
                return ['status' => 0, 'message' => 'Warehouse not found'];
            }

            // Build Delhivery B2B manifest payload (multipart/form-data array)
            $invoices = [];
            $shipment_details = [];
            foreach ($order->detail as $idx => $d) {
                $invoices[] = [
                    'inv_num'     => $order->invoice_no ?? ('INV-' . $order->id . '-' . ($idx + 1)),
                    'inv_amt'     => $d->price * $d->qty,
                    'ewaybill'    => $order->ewaybill_no ?? '',
                    'inv_qr_code' => '',
                ];
                $shipment_details[] = [
                    'order_id'    => $order->vendor_order_id . '-' . ($idx + 1),
                    'box_count'   => $d->qty,
                    'description' => $d->name . ($d->hsn_code ? ' HSN:' . $d->hsn_code : ''),
                    'weight'      => $order->weight, // grams
                ];
            }
            if (empty($invoices)) {
                $invoices[]         = ['inv_num' => 'INV-' . $order->id, 'inv_amt' => $order->total, 'ewaybill' => $order->ewaybill_no ?? '', 'inv_qr_code' => ''];
                $shipment_details[] = ['order_id' => $order->vendor_order_id, 'box_count' => 1, 'description' => 'Goods', 'weight' => $order->weight];
            }

            $payment_mode = ($order->payment_mode == 6) ? 'cod' : 'prepaid';
            $cod_amount   = ($order->payment_mode == 6) ? $order->custom_total : 0;

            $manifest_payload = [
                'pickup_location_name' => $warehouse->company ?: $warehouse->name,
                'payment_mode'         => $payment_mode,
                'cod_amount'           => $cod_amount,
                'weight'               => $order->weight,
                'dropoff_location'     => json_encode([
                    'consignee_name' => trim($order->ship_fname . ' ' . $order->ship_lname),
                    'address'        => $order->ship_address . ($order->ship_address_2 ? ', ' . $order->ship_address_2 : ''),
                    'city'           => $order->ship_city,
                    'state'          => $order->ship_state,
                    'zip'            => $order->ship_pincode,
                    'phone'          => $order->ship_phone,
                    'email'          => $order->ship_email ?? '',
                ]),
                'billing_address'     => json_encode([
                    'name'            => trim($order->ship_fname . ' ' . $order->ship_lname),
                    'company'         => $order->ship_company ?: trim($order->ship_fname . ' ' . $order->ship_lname),
                    'consignor'       => $warehouse->company ?: $warehouse->name,
                    'address'         => $order->ship_address . ($order->ship_address_2 ? ', ' . $order->ship_address_2 : ''),
                    'city'            => $order->ship_city,
                    'state'           => $order->ship_state,
                    'pin'             => $order->ship_pincode,
                    'phone'           => $order->ship_phone,
                    'pan_number' => $order->ship_pan ?? $order->pan ?? 'ABCDE1234F',
                    'gst_number' => $order->ship_gstin ?? $order->gstin ?? $warehouse->gstin ?? 'UR',
                ]),
                'rov_insurance'     => 'True',
                'invoices'          => json_encode($invoices),
                'shipment_details'  => json_encode($shipment_details),
                'fm_pickup'         => 'False',
                'freight_mode'      => 'fod',
            ];

            // Call Delhivery B2B manifest API
            $api_response_raw = Integration::shipment_delhivery_b2b($manifest_payload);
            api_logs(json_encode($manifest_payload), $api_response_raw, $order->id, 'B2B');

            $api_response = json_decode($api_response_raw, true);

            $job_id = $api_response['job_id'] ?? null;

            // Store job_id in order for polling (even if LRN not yet available)
            $placeholder_tracking = $job_id ? ('B2BJOB-' . $job_id) : ('B2B-' . date('YmdHis'));

            // ---- Debit wallet ----
            $closing_blc = round($wallet_blc - $total_cost, 2);
            Admin::where('id', $user_id)->update(['wallet_blc' => $closing_blc]);

            // Wallet log
            WalletLog::create([
                'user_id'     => $user_id,
                'amount'      => $total_cost,
                'type'        => 'debit',
                'closing_blc' => $closing_blc,
                'tracking_id' => $order->id,
                'awb'         => $placeholder_tracking,
                'remarks'     => 'B2B freight charge - ' . $rc->courier_code . ' [' . $rc->origin_zone . '→' . $rc->destination_zone . '] ' . round($chargeable, 2) . 'kg',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // Update order status
            $order->ship_courier_id    = 20; // B2B courier slot
            $order->tracking_info      = $placeholder_tracking;
            $order->ship_courier_cost  = $total_cost;
            $order->warehouse_id       = $request->warehouse_id;
            $order->status             = 12; // Pickup Pending
            $order->save();

            // Store job_id in order meta for background polling
            if ($job_id) {
                DB::table('order_logs')->insert([
                    'order_id'   => $order->id,
                    'user_id'    => $user_id,
                    'old_value'  => '',
                    'new_value'  => 'B2B_JOB_ID:' . $job_id,
                    'company_id' => $company_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            createlogs('assigned', 'b2b_order', $user_id);

            return [
                'status'  => 1,
                'message' => 'B2B Order assigned! Manifest submitted. LRN will update shortly via background polling. Tracking: ' . $placeholder_tracking,
                'job_id'  => $job_id,
            ];

        } catch (\Illuminate\Validation\ValidationException $ve) {
            return ['status' => 0, 'message' => implode(', ', \Illuminate\Support\Arr::flatten($ve->errors()))];
        } catch (\Exception $e) {
            \Log::error('assignB2b Error: ' . $e->getMessage());
            return ['status' => 0, 'message' => 'Server error: ' . $e->getMessage()];
        }
    }
}
 