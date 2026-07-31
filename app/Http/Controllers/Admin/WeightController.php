<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\Order;
use App\Models\Admin\WeightReconciliation;
use App\Models\Admin\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use App\FileManager;
use Hash;
use DB;


class WeightController extends Controller
{
	public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {   
        $user = Auth::guard('admin')->user();
        $re_data['extra_weight_status'] = 0;
        $re_data['tracking_info'] ='';
        $re_data['sku'] ='';
        $re_data['courier_id'] ='';
        $re_data['start_date'] ='2024-10-01 00:00:00';
        $re_data['end_date'] =now();
        if(!empty($_REQUEST)){
            if(isset($_REQUEST['start_date']) && $_REQUEST['start_date'] !=''){
                $re_data['start_date'] =$_REQUEST['start_date'].' 00:00:00';  
            }else{
                $re_data['start_date'] ='2024-07-01 00:00:00';
            }
            if(isset($_REQUEST['end_date']) && $_REQUEST['end_date'] !=''){
                $re_data['end_date'] =$_REQUEST['end_date'].' 23:59:59';  
            }else{
                $re_data['end_date'] =now();
            }
        }
        $re_data['user_id'] = array();
        if($user->role_id =='1'){
            $order_q = Order::with('reconciliation')->with('detail')->where('extra_weight_status','!=' ,'0')->where('extra_weight_added_on','>=', $re_data['start_date'])->where('extra_weight_added_on','<=', $re_data['end_date']);
            $users = Admin::where('delete_status','0')->where('company_id',$user->company_id)->get();    
        }elseif($user->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user->id);
            $order_q = Order::with('reconciliation')->with('detail')->where('extra_weight_status','!=' ,'0')->whereIn('orders.user_id' , $sub_user_id)->where('extra_weight_added_on','>=', $re_data['start_date'])->where('extra_weight_added_on','<=', $re_data['end_date']);
            $users = Admin::where('delete_status',0)
            ->whereIn('id',$sub_user_id)
            ->get();
        }else{
            $order_q = Order::with('reconciliation')->with('detail')->where('extra_weight_status','!=' ,'0')->where(['orders.user_id' => $user->id])->where('extra_weight_added_on','>=', $re_data['start_date'])->where('extra_weight_added_on','<=', $re_data['end_date']);
            $users = Admin::where('id',$user->id)->where('delete_status','0')->get();
        }
        if(!empty($_REQUEST)){
            if(isset($_REQUEST['extra_weight_status']) && $_REQUEST['extra_weight_status'] !=0){
                $re_data['extra_weight_status'] =  $_REQUEST['extra_weight_status'];
                if($_REQUEST['extra_weight_status'] =='1'){
                    $order_q->whereIn('extra_weight_status',array('1','5'));
                }else{
                    $order_q->where('extra_weight_status', $_REQUEST['extra_weight_status']);
                }
            }
            if(!empty($_REQUEST['user_id'])){
                $re_data['user_id'] = $_REQUEST['user_id'];
                $order_q->whereIn('orders.user_id',$re_data['user_id']);
            }
            if(isset($_REQUEST['tracking_info']) && $_REQUEST['tracking_info'] !=''){
                $re_data['tracking_info'] = $_REQUEST['tracking_info'];
                $order_q->where('tracking_info',ltrim(rtrim($_REQUEST['tracking_info'])));
            }
            if(!empty($_REQUEST['courier_id'])){
                $re_data['courier_id'] = $_REQUEST['courier_id'];
                $order_q->where('orders.ship_courier_id',$re_data['courier_id']);
            }
            if(isset($_REQUEST['sku']) && $_REQUEST['sku'] !=''){
                $re_data['sku'] = $_REQUEST['sku'];
                $order_ids = order::getorderidusingsku($_REQUEST['sku']);
                $order_q->whereIn('id',$order_ids);
            }
        } 
        $order = $order_q->get();
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        return view('admin.weight.index', compact('order','users','re_data','couriers'));
    }
    public function create()
    {
        
    	return view('admin.weight.importweight');
    }

    public function store(Request $request){
        try {
            $collections = (new FastExcel)->import($request->file('excel'));
        } catch (\Exception $exception) {
            return back()->with('error','You have uploaded a wrong format file, please upload the right file.');
        }
      $error = '';
        foreach ($collections as $row) {
            $order = Order::where('tracking_info',ltrim(rtrim($row['Tracking_info'])))->first();
            if($order !=''){
                $extra_eight_added = ltrim(rtrim($row['Weight(in grms)']));
                echo $extra_eight_added.'==';
                if($order->extra_weight_status == '' && floatval(($order->shipping_courier_weight)) < ($extra_eight_added/1000)){
                    echo $row['Tracking_info'].'<br>';
                    $order->extra_weight = $extra_eight_added;
                    $order->extra_weight_status = 5;
                    $order->extra_weight_added_by = auth()->guard('admin')->user()->id;
                    $order->extra_weight_added_on = now();
                    $order->save();
                }else{
                    $error .= $row['Tracking_info'].',';
                }
            }else{
                $error .= $row['Tracking_info'].' not found, ';
            }
        }
        if($error !=''){
          return redirect()->route('admin.weight')->with('error', $error.' not added,please check');
        }else{
          return redirect()->route('admin.weight')->with('success',"Updated Successfully");
        }
    }

    public function add_details($id){
        $user = Auth::guard('admin')->user();
        if($user->role_id =='1'){
            $order = Order::where('id',$id)->where('company_id',$user->company_id)->first();
        }elseif($user->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user->id);
            $order = Order::where('id',$id)->where('company_id',$user->company_id)->whereIn('user_id' , $sub_user_id)->first();
        }else{
            $order = Order::where('id',$id)->where('company_id',$user->company_id)->where('user_id' , $user->id)->first();
        }
        if($order !=''){
            return view('admin.weight.add_details',compact('order'));
        }else{
            return redirect()->route('admin.weight')->with('error', 'No Access');
        }
        
    }

    public function storedetails(Request $request){
        $weight = new WeightReconciliation();
        $weight->order_id = $request->order_id;
        $weight->added_by = Auth::guard('admin')->user()->id;
        $weight->description = $request->description;
        if($request->file('photo1')){
            $ext1 = $request->file('photo1')->extension();
            $final_name1 = date('Ymdhis').'1.'.$ext1;
            $request->file('photo1')->move(public_path('uploads/'), $final_name1);
            $weight->file_1 = $final_name1;
        }
        if($request->file('photo2')){
            $ext2 = $request->file('photo2')->extension();
            $final_name2 = date('Ymdhis').'2.'.$ext2;
            $request->file('photo2')->move(public_path('uploads/'), $final_name2);
            $weight->file_2 = $final_name2;
        }
        if($request->file('photo3')){
            $ext3 = $request->file('photo3')->extension();
            $final_name3 = date('Ymdhis').'3.'.$ext3;
            $request->file('photo3')->move(public_path('uploads/'), $final_name3);
            $weight->file_3 = $final_name3;
        }
        if($request->file('photo4')){
            $ext4 = $request->file('photo4')->extension();
            $final_name4 = date('Ymdhis').'4.'.$ext4;
            $request->file('photo4')->move(public_path('uploads/'), $final_name4);
            $weight->file_4 = $final_name4;
        }
        $weight->save();
        return redirect()->route('admin.weight')->with('success',"Updated Successfully");
    }

    public function view_details($id){
        $user = Auth::guard('admin')->user();
        if($user->role_id =='1'){
            $order = Order::with('reconciliation')->where('id',$id)->where('company_id',$user->company_id)->first();
        }elseif($user->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user->id);
            $order = Order::with('reconciliation')->where('id',$id)->where('company_id',$user->company_id)->whereIn('user_id' , $sub_user_id)->first();
        }else{
            $order = Order::with('reconciliation')->where('id',$id)->where('company_id',$user->company_id)->where('user_id' , $user->id)->first();
        }
        if($order !=''){
            // echo $order;die;
            return view('admin.weight.view_details',compact('order'));
        }else{
            return redirect()->route('admin.weight')->with('error', 'No Access');
        }
        
    }
    public function action()
    {   
        if(request()->status =='client' || request()->status =='seller'){
            foreach(request()->id as $id)
            {
                $order = Order::where('id',$id)->where('extra_weight_status','1')->first();
                if($order !=''){
                    $weight = new WeightReconciliation();
                    $weight->order_id = $id;
                    $weight->added_by = Auth::guard('admin')->user()->id;
                    $weight->description = request()->closing_description;
                    $weight->save();
                    if(request()->status =='client'){
                        $order->extra_weight_status =4;
                    }
                    if(request()->status =='seller'){
                        $getuser = DB::table('orders')
                        ->select('user_id as user')
                        ->where('id','=',$id)
                        ->first();
                        $balance = Admin::find($getuser->user);
                        $parent_userid = Admin::find($getuser->user)->parent_id;
                        $transaction = new Transaction();
                        $transaction->order_id = $id;
                        $transaction->user_id = $getuser->user;
                        $transaction->company_id = $balance->company_id;
                        $transaction->awb = $order->tracking_info;
                        $transaction->tracking_info = $order->tracking_info;
                        $transaction->credit = '0.00';
                        $transaction->debit = $order->extra_weight_cost;
                        $transaction->closing_blc = $balance->wallet_blc - $order->extra_weight_cost;
                        $transaction->remarks = "Amount Debit for extra weight";
                        $transaction->show_data = '1';
                        $transaction->save();

                        $transactionparent = new Transaction();
                        $transactionparent->order_id = $id;
                        $transactionparent->user_id = $parent_userid;
                        $transactionparent->company_id = $balance->company_id;
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

                        $order->extra_weight_status =2;

                        $balancerto = Admin::find($getuser->user);
                        if($order->rtocharge_applied =='1' && $order->extra_weght_rto_deduct =='0'){
                            $transaction = new Transaction();
                            $transaction->order_id = $order->id;
                            $transaction->user_id = $getuser->user;
                            $transaction->company_id = $balancerto->company_id;
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
                            $transactionparent->user_id = $parent_userid;
                            $transactionparent->company_id = $balancerto->company_id;
                            $transactionparent->awb = $order->tracking_info;
                            $transactionparent->tracking_info = $order->tracking_info;
                            $transactionparent->credit = '0.00';
                            $transactionparent->debit = $order->extra_weight_costparent;
                            $transactionparent->remarks = "Amount Debit for extra weight - RTO";
                            $transactionparent->parent_data = '1';
                            $transactionparent->show_data = '1';
                            $transactionparent->save();

                            $balancerto->wallet_blc = $balancerto->wallet_blc - $order->extra_weight_cost;
                            $balancerto->save();

                            $order->extra_weght_rto_deduct ='1';

                        }
                    }
                    $order->extra_weight_closed_on =now();
                    $order->save();
                }

            }
        }elseif(request()->status =='downloadfile' ){
//           foreach(request()->id as $id)
//            {   
//               $order = Order::where('id',$id)->first();
//               $files = WeightReconciliation::where('order_id',$id)->where('added_by','!=','1')->get();
////               echo $files;die;
//               
//               $zip = new ZipArchive();
//                $zipFilename = 'public/uploads/files.zip';
//                $zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE);
//
//                foreach($files as $file) {
//                    if ($file->file_1 != '') {
//                        $original_filename = 'public/uploads/' . $file->file_1;
//                        $zip->addFile($original_filename, $order->tracking_info . '_' . $file->id . '.jpg');
//                    }
//                    if ($file->file_2 != '') {
//                        $original_filename = 'public/uploads/' . $file->file_2;
//                        $zip->addFile($original_filename, $order->tracking_info . '_2' . $file->id . '.jpg');
//                    }
//                }
//
//                $zip->close();
//
//                // Send the zip file to the user
//                header('Content-Type: application/zip');
//                header('Content-Disposition: attachment; filename="files.zip"');
//                header('Content-Length: ' . filesize($zipFilename));
//
//                readfile($zipFilename);
//
//                // Optionally, delete the temporary zip file after download
//                unlink($zipFilename);
//
//               die;  
//            }
        }
        return redirect()->route('admin.weight')->with('success',"Updated Successfully");
    }

}