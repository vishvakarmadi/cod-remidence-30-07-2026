<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Order;
use App\Models\Admin\OrderDetail;
use App\Models\Admin\Country;
use App\Models\Admin\Api;
use App\Models\Admin\Integration_more;
use App\Models\Admin\Status;
use Illuminate\Support\Facades\Validator;
use DB;
use DOMDocument;
use DOMXPath;

class ApiOrderController extends Controller
{
    public function createorder(Request $request)
    { 
        $token = request()->bearerToken();
        $admin = DB::table('admins')->where('bearer_token', $token)->first();

        if (!$admin) {
            $response=response()->json(['error' => true, 'message' => 'Unauthorized'], 401);
            //checking api activity logs
            api_activity_logs($request,$response);
            return $response;

        } elseif ($admin->expired_on <= now()) {
            $response=response()->json(['error' => true, 'message' =>  'Token has expired. Please refresh your token.'], 401);
            //checking api activity logs 
            api_activity_logs($request,$response,$admin->id);
            return $response;
        }
        
        $validator = Validator::make($request->all(), [
            'ship_fname' => 'required|string|max:255',
            'ship_lname' => 'nullable|string|max:255',
            'ship_email' => 'required|email|max:255',
            'ship_company' => 'nullable|string|max:255',
            'ship_phone' => 'required|digits:10',
            'ship_address' => 'required|string|max:500',
            'ship_address_2' => 'nullable|string|max:500',
            'ship_country' => 'required|exists:countries,id',
            'ship_pincode' => 'required|numeric',
            'ship_city' => 'required|string|max:255',
            'ship_state' => 'required|string|max:255',
            'ship_latitude' => 'nullable|numeric',
            'ship_longitude' => 'nullable|numeric',
            'ship_gstin' => 'nullable|string|max:15',
            'e_bill_no' => 'nullable|string|max:255',
            'same_add' => 'required|boolean',
            'name' => 'required|array',
            'name.*' => 'required|string|max:255',
            'code' => 'required|array',
            'code.*' => 'required|string|max:255',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric|min:0',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:0',
            'discount_type' => 'nullable|array',
            'discount_type.*' => 'nullable|in:f,p',
            'discount' => 'nullable|array',
            'discount.*' => 'nullable|numeric|min:0',
            'tax_percent' => 'nullable|array',
            'tax_percent.*' => 'nullable|in:5,12,18,28',
            'tax_amount' => 'nullable|array',
            'tax_amount.*' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|array',
            'total_price.*' => 'nullable|numeric|min:0',
            'order_discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'payment_mode' => 'required|in:6,12',
            'vendor_order_id' => 'required|string|max:255',
            'channel' => 'nullable|string|max:255',
            'weight' => 'required|numeric',
            'length' => 'required|numeric|min:1',
            'breadth' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'bill_fname' => 'nullable|string|max:255',
            'bill_lname' => 'nullable|string|max:255',
            'bill_company' => 'nullable|string|max:255',
            'bill_phone' => 'nullable|digits:10',
            'bill_address' => 'nullable|string|max:500',
            'bill_address_2' => 'nullable|string|max:500',
            'bill_country' => 'nullable|exists:countries,id',
            'bill_pincode' => 'nullable|numeric',
            'bill_city' => 'nullable|string|max:255',
            'bill_state' => 'nullable|string|max:255',
            'bill_latitude' => 'nullable|numeric',
            'bill_longitude' => 'nullable|numeric',
            'bill_gstin' => 'nullable|string|max:15'
        ]);
        $user_id = $admin->id;
        if ($validator->fails()) {
            $response=response()->json(['error' => true, 'message' =>  $validator->errors()], 422);
             //checking api activity logs 
             api_activity_logs($request,$response,$user_id);
             return $response;

        }
        
        if($request->total ==0 || $request->total ==''){
            $response=response()->json(['error' => true, 'message' => 'Order value can not be 0'], 400);
            //checking api logs
            api_activity_logs($request,$response,$user_id);
            return $response;
        }
        if(count($request->name) !=0 && count($request->name) == count($request->code) && count($request->name) == count($request->qty) && count($request->name) == count($request->price) && count($request->name) == count($request->discount_type) && count($request->name) == count($request->discount) && count($request->name) == count($request->tax_percent) && count($request->name) == count($request->tax_amount) && count($request->name) == count($request->total_price)){
        $request->shipping_cost = "0.00";
        $request->custom_total = $request->total;
        $request->note = null;
        
        $orderl = Order::latest()->first();

//        echo $orderl->id;die;
        if ($orderl) {
            $lastOrderId = $orderl->id;
            $request->order_id = sprintf('%03d', intval($lastOrderId) + 1);
        }
       
        $order = Api::ordercreate($request, $user_id);
      
        if($order){
            createlogs('created', 'order', $order->id);
            $response=response()->json(['success' => true, 'message' => 'Order Created Successfully!', 'order_id' => $order->id], 201);
            //checking api activity logs
            api_activity_logs($request,$response,$user_id);
            return $response;    
        }else{
            $response=response()->json(['error' => true, 'message' => 'Wrong Data'], 400);
             //checking api activity logs
            api_activity_logs($request,$response,$user_id);
            return $response; 
        }
        }else{
            $response= response()->json(['error' => true, 'message' => 'Product Data Mismatch'], 400);
             //checking api activity logs
            api_activity_logs($request,$response,$user_id);
            return $response; 
        }
        
    }
    
    public function orderawb(Request $request){
        $token = request()->bearerToken();
        $admin = DB::table('admins')->where('bearer_token', $token)->first();

        if (!$admin) {
            $response=response()->json(['error' => true, 'message' => 'Unauthorized'], 401);
            //checking api activity logs
            api_activity_logs($request,$response);
            return $response;

        } elseif ($admin->expired_on <= now()) {
            $response=response()->json(['error' => true, 'message' =>  'Token has expired. Please refresh your token.'], 401);
            //checking api activity logs 
            api_activity_logs($request,$response,$admin->id);
            return $response;
        }
        $user_id = $admin->id;
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
            'courier_id' => 'required|numeric',
            'mode'=>'required|string|in:Air,Surface',
            'weight' =>'required|numeric'
        ]);
            
        if ($validator->fails()) {
            $response= response()->json(['errors' => $validator->errors()], 422);
           //checking api activity logs 
           api_activity_logs($request,$response,$user_id);
           return $response;
        }
        if(isset($request->warehouse_id) && isset($request->return_warehouse_id)){
        if($request->warehouse_id ==0 || $request->return_warehouse_id ==0){
           $response=response()->json(['error' => true, 'message' =>  'Warehouse id cannot be 0'], 422);
             //checking api activity logs 
             api_activity_logs($request,$response,$user_id);
             return $response;      
        }
        }else{
            $response= response()->json(['error' => true, 'message' =>  'Warehouse id not found'], 422);
          //checking api activity logs 
          api_activity_logs($request,$response,$user_id);
          return $response;
        }
        if(isset($request->fragile)){
            $dg_goods = $request->fragile;
        }else{
            $dg_goods = false;
        }
        $orderawb = Api::ordercreateawb($request, $user_id,$dg_goods);
        $response=response()->json([$orderawb[0] => true, 'message' => $orderawb[1]], 200);
        //checking api activity logs 
        api_activity_logs($request,$response,$user_id);
        return $response;
    }

    public function updateorder(Request $request, $id){
        $token = request()->bearerToken();
        $admin = DB::table('admins')->where('bearer_token', $token)->first();
        if (!$admin) {
            $response=response()->json(['error' => true, 'message' => 'Unauthorized'], 401);
            //checking api activity logs
            api_activity_logs($request,$response);
            return $response;
        } elseif ($admin->expired_on <= now()) {
            $response=response()->json(['error' => true, 'message' =>  'Token has expired. Please refresh your token.'], 401);
            //checking api activity logs 
            api_activity_logs($request,$response,$admin->id);
            return $response;
        }
        $checkorder=Order::find($id);
        if(!$checkorder){
            $response=response()->json(['error' => true, 'message' => 'order not found'], 400);
            //checking 
            api_activity_logs($request,$response,$admin->id);
            return $response;
        }
        $getuser = Order::select('user_id as user', 'status')
                ->where('id', $id)
                ->first();
    
        if($getuser->user != $admin->id){
            $response=response()->json(['error' => true, 'message' => 'No Access for this order'], 400);
            //checking api activity logs
            api_activity_logs($request,$response,$admin->id);
            return $response;
        }else{
       $response = Api::orderupdate($request,$getuser->user,$id);
       //checking api activity logs
       api_activity_logs($request,$response,$getuser->user);
       return $response;
        }
    }

    public function deleteorder(Request $request,$id)
    {
    $token = request()->bearerToken();
    $admin = DB::table('admins')->where('bearer_token', $token)->first();

    if (!$admin) {
        $response=response()->json(['error' => true, 'message' => 'Unauthorized'], 401);
        //checking api activity logs
        api_activity_logs($request,$response);
        return $response;

    } elseif ($admin->expired_on <= now()) {
        $response=response()->json(['error' => true, 'message' =>  'Token has expired. Please refresh your token.'], 401);
        //checking api activity logs 
        api_activity_logs($request,$response,$admin->id);
        return $response;
    }
    
        // $user_id=$admin->id;
        $getuser = DB::table('orders')
        ->select('user_id as user','status')
        ->where('id','=',$id)
        ->first();
        if (!is_array($id)) {
            $id = explode(',', $id);
        }
        if($getuser->user != $admin->id){
            $response=response()->json(['error' => true, 'message' => 'No Access for this order'], 400);
            //checking api activity logs
            api_activity_logs($id,$response,$admin->id);
            return $response;
        }
        $response=Api::orderdelete($id,$getuser->user);
        if (is_array($response) && isset($response['success'])) {
            if ($response['success']) {
                $response=response()->json(['success' => true, 'message' => $response['message']], 200);
                 //checking api activity logs
                api_activity_logs($id,$response,$getuser->user);
                return $response;
            } else {
                $response= response()->json(['error' => true, 'message' => $response['message']], 400);
                //checking api activity logs
                api_activity_logs($id,$response,$getuser->user);
                return $response;
            }
        } else {
            $response= response()->json(['error' => true, 'message' => $response['message']], 200);
            //checking api activity logs
            api_activity_logs($id,$response,$getuser->user);
            return $response;
        }
    
    }

    public function cancelorder($id){
        
        $token = request()->bearerToken();
        $admin = DB::table('admins')->where('bearer_token', $token)->first();

        if (!$admin) {
            $response=response()->json(['error' => true, 'message' => 'Unauthorized'], 401);
            //checking api activity logs
            api_activity_logs($id,$response);
            return $response;

        } elseif ($admin->expired_on <= now()) {
            $response=response()->json(['error' => true, 'message' =>  'Token has expired. Please refresh your token.'], 401);
            //checking api activity logs 
            api_activity_logs($id,$response,$admin->id);
            return $response;
        }else{
            $getuser = DB::table('orders')
            ->select('user_id as user','status')
            ->where('id','=',$id)
            ->first();
            if($getuser->user != $admin->id){
                $response=response()->json(['error' => true, 'message' => 'No Access for this order'], 400);
                //checking api activity logs
                api_activity_logs($id,$response,$admin->id);
                return $response;
            }
            if($getuser->status =='4'){
                $response= response()->json(['error' => true, 'message' => 'Already cancelled'], 400);
              //checking api activity logs
              api_activity_logs($id,$response,$getuser->user);
              return $response;
            }
            if(!in_array($getuser->status, array('1','2','12'))){
                $response= response()->json(['error' => true, 'message' => 'Cannot cancel processed order,please contact admin'], 400);
                //checking api activity logs
                api_activity_logs($id,$response,$getuser->user);
                return $response;
            }
            $response = Api::ordercancel($id);
            $res=response()->json([$response[0] => true, 'message' => $response[1]], 200);
            //checking api activity logs
             api_activity_logs($id,$res,$getuser->user);
            return $res;
        }
    }
    
    
    public function trackorder($id){
        $token = request()->bearerToken();
        $admin = DB::table('admins')->where('bearer_token', $token)->first();

        if (!$admin) {
            $response=response()->json(['error' => true, 'message' => 'Unauthorized'], 401);
            //checking api activity logs
            api_activity_logs($id,$response);
            return $response;
        } elseif ($admin->expired_on <= now()) {
            $response=response()->json(['error' => true, 'message' =>  'Token has expired. Please refresh your token.'], 401);
            //checking api activity logs 
            api_activity_logs($id,$response,$admin->id);
            return $response;
        }else{
            $user_id=$admin->id;
            $order = Order::where('id',$id)->where('user_id',$user_id)->first();
            if($order){
               
                $courierId =$courierId = $order->ship_courier_id;
                $tracking_info= $order->tracking_info;
                if(!$tracking_info){
                  
                    $response=response()->json(['error' => true, 'message' =>  'Awb not generated'], 400);
                     //checking api activity logs 
                      api_activity_logs($id,$response,$user_id);
                      return $response;
                }
                $type_info= $order->reverse_order;

                if( $type_info=="1"){
                    $type ='backward';
                }else{
                    $type ='forward';
                }
                $get_statusprogress=Status::getcourierstatuslogs($courierId,$tracking_info,$type);
                $progress = array();$i=0;
//                return($get_statusprogress);
                if($order->ship_courier_id =='1'){

                    // Load XML from a file or a string
                    $xmlString = <<<XML
                    $get_statusprogress
                    XML;
                     $xmlString = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $xmlString);
                    // Create a new DOMDocument and load the XML
                    $dom = new DOMDocument();
                    $dom->loadXML($xmlString);

                    // Find all scan_stages objects
                    $scanStagesArray = [];
                    $xpath = new DOMXPath($dom);
                    $scanStages = $xpath->query('//object[@model="scan_stages"]');

                    foreach ($scanStages as $stage) {
                        $stageArray = [];
                        foreach ($stage->getElementsByTagName('field') as $field) {
                            $name = $field->getAttribute('name');
                            $stageArray[$name] = $field->nodeValue;
                        }
                        $progress[$i]['action'] = $stageArray['status'];
                        $progress[$i]['place'] = $stageArray['location_city'];
                        $progress[$i]['remarks'] = $stageArray['scan_status'];
                        $progress[$i]['kimi_status'] = API::getstatusvalue($stageArray,$order->ship_courier_id);
                        $dateString = rtrim($stageArray['updated_on']);
                        $date = \DateTime::createFromFormat('d M, Y, H:i', $dateString);
                        if ($date) {
                            $progress[$i]['date'] = $date->format('Y-m-d H:i:s');
                        }else{
                            $progress[$i]['date'] ='';
                        }
                        
                        $i++;
                    }

//                    $progress = array_reverse($progress);

                }
                if($order->ship_courier_id =='2'){
                    $status_progressarray = json_decode($get_statusprogress,true);
                    if(isset($status_progressarray['ShipmentData']) && isset($status_progressarray['ShipmentData'][0]) && $status_progressarray['ShipmentData'][0]['Shipment'] && $status_progressarray['ShipmentData'][0]['Shipment']['Scans'])
                    foreach($status_progressarray['ShipmentData'][0]['Shipment']['Scans'] as $history){
                        if(isset($history['ScanDetail'])){
                            $hs = $history['ScanDetail'];
                            $timestamp = $hs['StatusDateTime'];
                            $progress[$i]['action'] = $hs['Scan'].'-'.$hs['ScanType'];;
                            $progress[$i]['place'] = $hs['ScannedLocation'];
                            $progress[$i]['remarks'] = $hs['Instructions'];
                            $progress[$i]['kimi_status'] = API::getstatusvalue($hs,$order->ship_courier_id);
                            $progress[$i]['date'] = date('Y-m-d H:i:s', strtotime($timestamp));
                            $i++;
                        }
                    }
                    $progress = array_reverse($progress);
                }
                if($order->ship_courier_id =='3'){
                    $status_progressarray = json_decode($get_statusprogress,true);
                    if($status_progressarray['status'] && isset($status_progressarray['current_status'])){
                        if(isset($status_progressarray['data'])){
                            foreach($status_progressarray['data'] as $history){
                                $progress[$i]['action'] = $history['status_title'];
                                $progress[$i]['place'] = $history['status_location'];
                                $progress[$i]['remarks'] =$history['status_description'];
                                $progress[$i]['kimi_status'] = API::getstatusvalue($history,$order->ship_courier_id);
                                $progress[$i]['date'] = $history['event_date'];
                                $i++;
                            }
                        }
                    }
                    $progress = array_reverse($progress);
                }
                if($order->ship_courier_id =='4'){
                    $status_progressarray = json_decode($get_statusprogress,true);
                    if(isset($status_progressarray['ShipmentLogDetails']) && count($status_progressarray['ShipmentLogDetails'])>0){
                        $history =$status_progressarray['ShipmentLogDetails'];
                        for($j=count($history)-1;$j>=0;$j--){
                            $progress[$i]['action'] = $history[$j]['Process'];
                            $progress[$i]['place'] = $history[$j]['City'];
                            $progress[$i]['remarks'] = $history[$j]['Description'];
                            $progress[$i]['kimi_status'] = API::getstatusvalue($history[$j],$order->ship_courier_id);
                            $progress[$i]['date'] = date('Y-m-d H:i:s',strtotime($history[$j]['ShipmentStatusDateTime']));
                            $i++;
                        }
                        
                    }
                    $progress = array_reverse($progress);
                }
                if($order->ship_courier_id =='5'){
                    $status_progressarray = json_decode($get_statusprogress,true);
                    if(isset($status_progressarray['statusCode']) && $status_progressarray['statusCode'] == '200'){
                        if(isset($status_progressarray['trackDetails']) && count($status_progressarray['trackDetails']) >0){
                            foreach($status_progressarray['trackDetails'] as $history){
                                $x =$history['strActionDate'];
                                $y =$history['strActionTime'];
                                $progress[$i]['action'] = $history['strAction'];
                                $progress[$i]['place'] = $history['strOrigin'].'-'.$history['strDestination'];
                                $progress[$i]['remarks'] = $history['sTrRemarks'];
                                $progress[$i]['kimi_status'] = API::getstatusvalue($history,$order->ship_courier_id);
                                $progress[$i]['date'] = substr($x, 4, 4).'-'.substr($x, 2, 2).'-'.substr($x, 0, 2).' '.substr($y, 0, 2).':'.substr($y, 2, 2).':00';
                                $i++;
                            }
                        }
                    }
                    $progress = array_reverse($progress);
                }
                if($order->ship_courier_id =='6'){
                    $status_progressarray = json_decode($get_statusprogress,true);
                    if($status_progressarray['success'] ==1 && isset($status_progressarray['data']) && isset($status_progressarray['data'][0]) && isset($status_progressarray['data'][0]['shipmentStatus'])){
                        $history =$status_progressarray['data'][0]['shipmentStatus'];
                        for($j=count($history)-1;$j>=0;$j--){
                                $progress[$i]['action'] = $history[$j]['statusDescription'];
                                $progress[$i]['place'] = $history[$j]['city'].'-'.$history[$j]['state'];
                                $progress[$i]['remarks'] = $history[$j]['remarks'];
                                $progress[$i]['kimi_status'] = API::getstatusvalue($history[$j],$order->ship_courier_id);
                                $progress[$i]['date'] = str_replace('T',' ',$history[$j]['eventDate']);
                                $i++;
                        }
                    }
                    $progress = array_reverse($progress);
                }
                if($order->ship_courier_id =='7'){
                    $status_progressarray = json_decode($get_statusprogress,true);
                    if(isset($status_progressarray[$tracking_info]) && isset($status_progressarray[$tracking_info]['history'])){
                        $history =$status_progressarray[$tracking_info]['history'];
                        for($j=count($history)-1;$j>=0;$j--){
                                $date = explode('+',$history[$j]['event_date']);
                                $progress[$i]['action'] = $history[$j]['public_description'];
                                $progress[$i]['place'] = $history[$j]['city'];
                                $progress[$i]['remarks'] = $history[$j]['status'];
                                $progress[$i]['kimi_status'] = API::getstatusvalue($history[$j],$order->ship_courier_id);
                                $progress[$i]['date'] = str_replace('T',' ',$date[0]);
                                $i++;
                        }
                    }
                    $progress = array_reverse($progress);
                }
                if($order->ship_courier_id =='8'){
                    $status_progressarray = json_decode($get_statusprogress,true);
                    if($type =='forward'){
                        if(isset($status_progressarray['tracking_details'])){
                            foreach($status_progressarray['tracking_details'] as $history){
                                $datetime_utc = new \DateTime($history['created'], new \DateTimeZone('UTC'));
                                $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                                $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
                                $progress[$i]['action'] = $history['status'];
                                $progress[$i]['place'] = $history['location'];
                                $progress[$i]['remarks'] = $history['remarks'];
                                $progress[$i]['kimi_status'] = API::getstatusvalue($history,$order->ship_courier_id);
                                $progress[$i]['date'] = $ist_timestamp;
                                $i++;
                            }
                        }
                    }else{
                        if(isset($status_progressarray['pickup_request_state_histories'])){
                            foreach($status_progressarray['pickup_request_state_histories'] as $history){
                                $datetime_utc = new \DateTime($history['created_at'], new \DateTimeZone('UTC'));
                                $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                                $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
                                $progress[$i]['action'] = $history['state'];
                                $progress[$i]['place'] = $history['current_location'];
                                $progress[$i]['remarks'] = $history['comment'];
                                $progress[$i]['kimi_status'] = API::getstatusvalue($history,$order->ship_courier_id,'backward');
                                $progress[$i]['date'] = $ist_timestamp;
                                $i++;
                            }
                        }
                    }
                    $progress = array_reverse($progress);
                }
                if($order->ship_courier_id =='9'){
                    $status_progressarray = json_decode($get_statusprogress,true);
//                    echo '<pre>';print_R($status_progressarray);die;
                    if(isset($status_progressarray['payload']) && isset($status_progressarray['payload']['eventHistory'])){
                        foreach($status_progressarray['payload']['eventHistory'] as $history){
                            $datetime_utc = new \DateTime($history['eventTime'], new \DateTimeZone('UTC'));
                            $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                            $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
                            $progress[$i]['action'] = $history['eventCode'].'-'.$history['shipmentType'];
                            $progress[$i]['place'] = @$history['location']['city'];
                            $progress[$i]['remarks'] = '';
                            $progress[$i]['kimi_status'] = API::getstatusvalue($history,$order->ship_courier_id);
                            $progress[$i]['date'] = $ist_timestamp;
                            $i++;
                        }
                    }
                    $progress = array_reverse($progress);
                }
                $response= response()->json(['success' => true, 'shipment_history' => $progress], 200);
                 //checking api activity logs 
                 api_activity_logs($id,$response,$user_id);
                 return $response;

            }else{
               $response= response()->json(['error' => true, 'message' => 'No Access for this order'], 400);
               //checking api activity logs 
               api_activity_logs($id,$response,$user_id);
               return $response;
            }
        }
    }    

    public function manifestorder(Request $request){
         $token = request()->bearerToken();
        $admin = DB::table('admins')->where('bearer_token', $token)->first();

        if (!$admin) {
            $response=response()->json(['error' => true, 'message' => 'Unauthorized'], 401);
            //checking api activity logs
            api_activity_logs($request,$response);
            return $response;
        } elseif ($admin->expired_on <= now()) {
            $response=response()->json(['error' => true, 'message' =>  'Token has expired. Please refresh your token.'], 401);
            //checking api activity logs 
            api_activity_logs($request,$response,$admin->id);
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|string',
        ]);

        if ($validator->fails()) {
           $response= response()->json(['error' => true,'message' => $validator->errors()]);
           //checking api logs 
           api_activity_logs($request,$response,$admin->id);
           return $response;
        }
        $user_id = $admin->id;  
        $response = Api::ordermanifest($request->order_id,$user_id);
        $res=response()->json([$response[0] => true, 'message' => $response[1]], 200);
        //checking api logs
        api_activity_logs($request,$res,$user_id);
        return $res;
    }


    public function view_order(Request $request) {
        $token = request()->bearerToken();
        $admin = DB::table('admins')->where('bearer_token', $token)->first();

        if (!$admin) {
            $response=response()->json(['error' => true, 'message' => 'Unauthorized'], 401);
            //checking api activity logs
            api_activity_logs($request,$response);
            return $response;
        } elseif ($admin->expired_on <= now()) {
            $response=response()->json(['error' => true, 'message' =>  'Token has expired. Please refresh your token.'], 401);
            //checking api activity logs 
            api_activity_logs($request,$response,$admin->id);
            return $response;
        }
    
        // Validate request parameters
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'status' => 'nullable|string',
            'id' => 'nullable|string',
            'page' => 'nullable|integer|min:1',
        ]);
    
        if ($validator->fails()) {
            $response=response()->json([
                'error' => true,
                'message' => $validator->errors()->first(),
            ], 400);
            //checking api activity logs 
            api_activity_logs($request,$response,$admin->id);
            return $response;
        }
    
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $status = $request->status;
        $id = $request->id;
        $page = $request->page ?? 1;
    
        // Build query
        $query = Order::select(
            'orders.id',
            'orders.user_id',
            'orders.created_at',
            'orders.tracking_info',
            'orders.ship_fname',
            'orders.ship_lname',
            'orders.ship_email',
            'orders.ship_phone',
            'orders.ship_gstin',
            'orders.ship_address',
            'orders.ship_city',
            'orders.ship_state',
            'orders.ship_pincode',
            'orders.bill_fname',
            'orders.bill_address',
            'orders.bill_state',
            'orders.bill_pincode',
            'orders.bill_phone',
            'orders.payment_mode',
            'orders.status',
            'orders.zone',
            'orders.weight',
            'orders.length',
            'orders.height',
            'orders.breadth',
            'warehouses.name as warehouse_name',
            'warehouses.address as warehouse_address1',
            'warehouses.address_2 as warehouse_address2',
            'warehouses.pincode as warehouse_pincode',
            'warehouses.contact_name as warehouse_contact',
            'warehouses.phone as warehouse_phone'
        )
        ->leftJoin('warehouses', 'orders.warehouse_id', '=', 'warehouses.id') 
        ->with('detail') // Include details
        ->where('orders.user_id', $admin->id)
        ->orderBy('orders.created_at', 'desc');
        // Date filtering
        if (!empty($startDate)) {
            $startDateWithTime = $startDate . ' 00:00:00';
            $query->where('orders.created_at', '>=',$startDateWithTime);
        }
        if (!empty($endDate)) {
            $endDateWithTime = $endDate . ' 23:59:59';
            $query->where('orders.created_at', '<=',$endDateWithTime);
        }
    
        if (!empty($status)) {
            $statusArray = array_map('trim', explode(',', $status));
            $query->whereIn('orders.status', $statusArray);
        }
        if(!empty($id)){
           $idarray =  array_map('trim', explode(',', $id));
           $query->whereIn('orders.id', $idarray);
        }
    
        // Pagination
        $orders = $query->paginate(100, ['*'], 'page', $page);
    
        // Format the response
        $formattedOrders = $orders->map(function ($order) {
            $vol = ($order->height * $order->breadth * $order->length) / 5000;
            $statusText = strip_tags($order->status);
            $paymentmode=strip_tags($order->payment_mode);

            return [
                'order' => [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'created_at' => $order->created_at,
                    'tracking_info' => $order->tracking_info,
                    'ship_fname' => $order->ship_fname,
                    'ship_lname' => $order->ship_lname,
                    'ship_email' => $order->ship_email,
                    'ship_phone' => $order->ship_phone,
                    'ship_gstin' => $order->ship_gstin,
                    'ship_address' => $order->ship_address,
                    'ship_city' => $order->ship_city,
                    'ship_state' => $order->ship_state,
                    'ship_pincode' => $order->ship_pincode,
                    'bill_fname' => $order->bill_fname,
                    'bill_address' => $order->bill_address,
                    'bill_state' => $order->bill_state,
                    'bill_pincode' => $order->bill_pincode,
                    'bill_phone' => $order->bill_phone,
                    'payment_mode' => $paymentmode,
                    'status' => $statusText,
                    'zone' => $order->zone,
                    'weight' => $order->weight,
                    'length' => $order->length,
                    'breadth' => $order->breadth,
                    'vol_weight' => $vol,
                    'warehouse' => [
                        'name' => $order->warehouse_name,
                        'address1' => $order->warehouse_address1,
                        'address2' => $order->warehouse_address2,
                        'pincode' => $order->warehouse_pincode,
                        'contact_name' => $order->warehouse_contact,
                        'phone' => $order->warehouse_phone,
                    ],
                    'details' => $order->detail->map(function ($detail) {
                        return [
                            'id' => $detail->id,
                            'user_id' => $detail->user_id,
                            'order_id' => $detail->order_id,
                            'name' => $detail->name,
                            'code' => $detail->code,
                            'price' => $detail->price,
                            'discount_type' => $detail->discount_type,
                            'discount' => $detail->discount,
                            'qty' => $detail->qty,
                            'tax_percent' => $detail->tax_percent,
                            'tax_amount' => $detail->tax_amount,
                            'total_price' => $detail->total_price,
                            'created_at' => $detail->created_at,
                            'updated_at' => $detail->updated_at,
                        ];
                    }),
                ],
            ];
        });
    
    
       $response=response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully',
            'data' => [
                'current_page' => $orders->currentPage(),
                'total' => $orders->total(),
                'data' => $formattedOrders,
            ],
        ], 200);

        //checking api logs
        api_activity_logs($request,$response,$admin->id);
        return $response;
    }

    public function generatelabel($id){
        $token = request()->bearerToken();
        $admin = DB::table('admins')->where('bearer_token', $token)->first();

        if (!$admin) {
            $response=response()->json(['error' => true, 'message' => 'Unauthorized'], 401);
            //checking api activity logs
            api_activity_logs($id,$response);
            return $response;
        } elseif ($admin->expired_on <= now()) {
            $response=response()->json(['error' => true, 'message' =>  'Token has expired. Please refresh your token.'], 401);
            //checking api activity logs 
            api_activity_logs($id,$response,$admin->id);
            return $response;
        }
        $user_id = $admin->id;
        $chkorder = order::where('id',$id)->where('user_id',$user_id)->first();
        if($chkorder ){
            if($chkorder->ship_courier_id !='9'){
                $response=response()->json(['error' => true, 'message' => 'Only Ats orders are allowed'], 400);
                //checking api activity logs
                api_activity_logs($id,$response,$admin->id);
                return $response;
            }else{
                $label =  Integration_more::generate_shiplabelawb($chkorder->shipment_id,$chkorder->order_id);
                $lebel_d = json_decode($label,true);
                $response=response()->json([
                        'success' => true,
                        'data' => [
                            'data' => $lebel_d,
                        ],
                    ], 200);
                api_activity_logs($id,$response,$admin->id);
                return $response;
            }
        }else{
                $response=response()->json(['error' => true, 'message' => 'No Access for this order'], 400);
                //checking api activity logs
                api_activity_logs($id,$response,$admin->id);
                return $response;
        }
    }
    
}