<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use App\Models\Admin\OrderLog;
use Illuminate\Http\Request;
use App\Models\Admin\Status;
use DOMDocument;
use DOMXPath;


class TrackController extends Controller
{
    public function trackorder(){
        return view('admin.track.trackorder');
    }

    public function track(Request $request){
        $awb = $request->awb;
        if($awb !=''){
            $order = order::where('tracking_info',$awb)->first();
            if($order){
                return redirect()->route('admin.tracking', ['awb' => $awb]);
            }else{
                return redirect()->back()->with('error', 'AWB not found!');
            }
        }else{
            return redirect()->back()->with('error', 'Please enter AWB!');
        }
    }
    
    // fetch details from courier and show them on our portal
    public function checkAwb(Request $request)
    {
        $awb = $request->awb;
        $order = order::where('tracking_info', $awb)->first();
        if ($order) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function tracking($awb){
            $order = order::where('tracking_info',$awb)->first();
            if(!$order){
                return view('errors.404');
            }
            if($order){
                $progress = array(); $i=0;
                if($order->reverse_order =='1'){
                    $type ='backward';
                }else{
                    $type ='forward';
                }
                $tracking_info = $order->tracking_info;
                $get_statusprogress = Status::getcourierstatuslogs($order->ship_courier_id,$order->tracking_info,$type);
                $hyloshipstatus = Status::gethyloshipstatus($get_statusprogress,$order->ship_courier_id,$order->tracking_info,$type);
                if(!in_Array(strip_tags($order->status),array('Canceled','Delivered','Damaged','Lost','RTO Delivered'))){
                    Status::updatestausindb($order->id,$hyloshipstatus);
                }
//                if($order->ship_courier_id =='4' && in_Array(strip_tags($order->status),array('Delivered'))){
//                    Status::updatestausindb($order->id,$hyloshipstatus);
//                }

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
                        $progress[$i]['action'] = $stageArray['status'].' '.$stageArray['reason_code_number'];
                        $progress[$i]['place'] = $stageArray['location_city'];
                        $progress[$i]['remarks'] = $stageArray['scan_status'];
                        $progress[$i]['date'] = (rtrim($stageArray['updated_on']));
                        $i++;
                    }

                    $progress = array_reverse($progress);

                }
                if($order->ship_courier_id =='2'){
                    $status_progressarray = json_decode($get_statusprogress,true);
                if(isset($status_progressarray['ShipmentData'][0]['Shipment']['Scans']))
                    foreach($status_progressarray['ShipmentData'][0]['Shipment']['Scans'] as $history){
                        if(isset($history['ScanDetail'])){
                            $hs = $history['ScanDetail'];
                            $timestamp = $hs['StatusDateTime'];
//                            $datetime_utc = new \DateTime($timestamp, new \DateTimeZone('UTC'));
//                            $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
//                            $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
                            $progress[$i]['action'] = $hs['Scan'].'-'.$hs['ScanType'];;
                            $progress[$i]['place'] = $hs['ScannedLocation'];
                            $progress[$i]['remarks'] = $hs['Instructions'];
                            $progress[$i]['date'] = date('Y-m-d H:i:s', strtotime($timestamp));
                            $i++;
                        }
                    }
                }
                if($order->ship_courier_id =='3'){
                    $status_progressarray = json_decode($get_statusprogress, true);
                    if (!is_array($status_progressarray)) {
                        // No valid response — skip
                    } elseif (isset($status_progressarray['ShipmentData']['Shipment'][0]['Scans'])) {
                        // Native APIGEE tracking response
                        foreach($status_progressarray['ShipmentData']['Shipment'][0]['Scans'] as $history) {
                            if(isset($history['ScanDetail'])) {
                                $hs = $history['ScanDetail'];
                                $progress[$i]['action']  = ($hs['Scan'] ?? '') . '-' . ($hs['ScanType'] ?? '');
                                $progress[$i]['place']   = $hs['ScannedLocation'] ?? '';
                                $progress[$i]['remarks'] = $hs['Instructions'] ?? '';
                                
                                // Parse date from ScanDate and ScanTime
                                $dateTimeStr = ($hs['ScanDate'] ?? '') . ' ' . ($hs['ScanTime'] ?? '');
                                $progress[$i]['date'] = !empty(trim($dateTimeStr)) ? date('Y-m-d H:i:s', strtotime($dateTimeStr)) : '';
                                
                                $i++;
                            }
                        }
                    } elseif (isset($status_progressarray['ShipmentData'][0]['Shipment']['Scans'])) {
                        // Fallback for alternative depth
                        foreach($status_progressarray['ShipmentData'][0]['Shipment']['Scans'] as $history) {
                            if(isset($history['ScanDetail'])) {
                                $hs = $history['ScanDetail'];
                                $progress[$i]['action']  = ($hs['Scan'] ?? '') . '-' . ($hs['ScanType'] ?? '');
                                $progress[$i]['place']   = $hs['ScannedLocation'] ?? '';
                                $progress[$i]['remarks'] = $hs['Instructions'] ?? '';
                                $progress[$i]['date']    = isset($hs['StatusDateTime']) ? date('Y-m-d H:i:s', strtotime($hs['StatusDateTime'])) : '';
                                $i++;
                            }
                        }
                    } elseif (!empty($status_progressarray['status']) && isset($status_progressarray['data'])) {
                        // Legacy ParcelX format fallback
                        foreach($status_progressarray['data'] as $history) {
                            $progress[$i]['action']  = $history['status_title'] ?? '';
                            $progress[$i]['place']   = $history['status_location'] ?? '';
                            $progress[$i]['remarks'] = $history['status_description'] ?? '';
                            $progress[$i]['date']    = $history['event_date'] ?? '';
                            $i++;
                        }
                    }
                }
                if($order->ship_courier_id =='4'){
                    $status_progressarray = json_decode($get_statusprogress,true);
                    if(isset($status_progressarray['ShipmentLogDetails']) && count($status_progressarray['ShipmentLogDetails'])>0){
                        $history =$status_progressarray['ShipmentLogDetails'];
                        for($j=count($history)-1;$j>=0;$j--){
                            $progress[$i]['action'] = $history[$j]['Process'];
                            $progress[$i]['place'] = $history[$j]['City'];
                            $progress[$i]['remarks'] = $history[$j]['Description'];
                            $progress[$i]['date'] = $history[$j]['ShipmentStatusDateTime'];
                            $i++;
                        }
                        
                    }
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
                                $progress[$i]['date'] = substr($x, 4, 4).'-'.substr($x, 2, 2).'-'.substr($x, 0, 2).' '.substr($y, 0, 2).':'.substr($y, 2, 2).':00';
                                $i++;
                            }
                        }
                    }
                }
                if($order->ship_courier_id =='6'){
                    $status_progressarray = json_decode($get_statusprogress,true);
                    if($status_progressarray['success'] ==1 && isset($status_progressarray['data']) && isset($status_progressarray['data'][0]) && isset($status_progressarray['data'][0]['shipmentStatus'])){
                        $history =$status_progressarray['data'][0]['shipmentStatus'];
                        for($j=count($history)-1;$j>=0;$j--){
                                $progress[$i]['action'] = $history[$j]['statusDescription'];
                                $progress[$i]['place'] = $history[$j]['city'].'-'.$history[$j]['state'];
                                $progress[$i]['remarks'] = $history[$j]['remarks'];
                                $progress[$i]['date'] = str_replace('T',' ',$history[$j]['eventDate']);
                                $i++;
                        }
                    }
                }
                if($order->ship_courier_id =='7'){
                    $status_progressarray = json_decode($get_statusprogress,true);
//                    echo '<pre>';print_R($status_progressarray['track']);die;
                    if(isset($status_progressarray['track']) && isset($status_progressarray['track']['details'])){
                        $history =$status_progressarray['track']['details'];
                        for($j=count($history)-1;$j>=0;$j--){
                                $date = explode(' ',date('Y-m-d H:i:s', $history[$j]['ctime'] / 1000));
                                $progress[$i]['action'] = isset($history[$j]['status']) ? $history[$j]['status'] : '';
                                $progress[$i]['place'] = isset($history[$j]['location']) ? $history[$j]['location'] : '';
                                $progress[$i]['remarks'] = "";
                                $progress[$i]['date'] = str_replace('T',' ',$date[0]);
                                $i++;
                        }
                    }
                }
                if($order->ship_courier_id =='8'){
                    $status_progressarray = json_decode($get_statusprogress,true);
                    if($type =='forward'){
                        if(isset($status_progressarray['tracking_details'])){
                            foreach($status_progressarray['tracking_details'] as $history){
                                $datetime_utc = new \DateTime($history['created'], new \DateTimeZone('UTC'));
                                $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                                $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
                                $progress[$i]['action'] = isset($history['status']) ? $history['status'] : '';
                                $progress[$i]['place'] = isset($history['location']) ? $history['location'] : '';
                                $progress[$i]['remarks'] = isset($history['remarks']) ? $history['remarks'] : '';
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
                                $progress[$i]['action'] = isset($history['state']) ? $history['state'] : '';
                                $progress[$i]['place'] = isset($history['current_location']) ? $history['current_location'] : '';
                                $progress[$i]['remarks'] = isset($history['comment']) ? $history['comment'] : '';
                                $progress[$i]['date'] = $ist_timestamp;
                                $i++;
                            }
                        }
                    }
                }
                if($order->ship_courier_id =='9'){
                    $status_progressarray = json_decode($get_statusprogress,true);
                    if(isset($status_progressarray['payload']) && isset($status_progressarray['payload']['eventHistory'])){
                        foreach($status_progressarray['payload']['eventHistory'] as $history){
                            $datetime_utc = new \DateTime($history['eventTime'], new \DateTimeZone('UTC'));
                            $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                            $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
                            $progress[$i]['action'] = $history['eventCode'].'-'.$history['shipmentType'];
                            $progress[$i]['place'] = $history['location']['city'] ?? '';
                            $progress[$i]['remarks'] = '';
                            $progress[$i]['date'] = $ist_timestamp;
                            $i++;
                        }
                    }
                }
                if($order->ship_courier_id =='10'){
                    $status_progressarray = json_decode($get_statusprogress,true);
                     $shipment = $status_progressarray['result'][0]['tracking'];
//                     echo '<pre>';print_r($shipment);die;
                    
                    if(isset($shipment)){
                        foreach($shipment as $history){
                            $progress[$i]['action'] = $history['shipmentStatus'];
                            $progress[$i]['place'] = @$history['location'];
                            $progress[$i]['remarks'] = $history['remarks'];
                            $progress[$i]['date'] = $history['timestamp'];
                            $i++;
                        }
                    }
                }
                if($order->ship_courier_id =='11'){
                    $status_progressarray = json_decode($get_statusprogress,true);
                    if(isset($status_progressarray['status']) && $status_progressarray['status'] ==200){
                        $shipment = $status_progressarray['data']['orderStateInfo'];
//                        echo '<pre>';print_r($shipment);die;

                       if(isset($shipment)){
                           foreach($shipment as $history){
                               $progress[$i]['action'] = $history['state'];
                               $progress[$i]['place'] = @$history['location'];
                               $progress[$i]['remarks'] = @$history['remarks'];
                               $progress[$i]['date'] = $history['createdAt'];
                               $i++;
                           }
                       }
                    }   
                }
            }
            $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
            $ordernew = order::where('tracking_info',$awb)->first();

            // Filter out tracking progress events that happened before the order was created (recycled AWB check)
            if ($ordernew && isset($progress) && is_array($progress)) {
                $orderCreatedTimestamp = strtotime($ordernew->created_at);
                $filteredProgress = [];
                foreach ($progress as $item) {
                    if (isset($item['date']) && !empty($item['date'])) {
                        $itemTimestamp = strtotime($item['date']);
                        if ($itemTimestamp && $orderCreatedTimestamp && $itemTimestamp < $orderCreatedTimestamp - 86400) {
                            continue; // Skip recycled AWB scans
                        }
                    }
                    $filteredProgress[] = $item;
                }
                $progress = $filteredProgress;
            }

           if($ordernew){
            $orderlogs = OrderLog::orderBy('created_at', 'asc')->where('order_id',$ordernew->id)->get();
            
            $numLogs = count($orderlogs); // Get the number of logs
            $filteredLogs = []; // Initialize an empty array to store filtered logs

            for ($i = 0; $i < $numLogs; $i++) {
                $currentLog = $orderlogs[$i];

                // Access current log's properties
                $new_value = $currentLog->new_value;

                // Check if there is a next log and if its new_value is different
                if ($i === 0 || $new_value !== $orderlogs[$i - 1]->new_value) {
                    // Add the current log to filtered logs
                    $filteredLogs[] = $currentLog;
                }
            }


            $orderlogs = $filteredLogs;
           }
//             echo '<pre>';print_R($orderlogs);die;
            return view('admin.track.tracking',compact('ordernew','progress','couriers','orderlogs'));
           
        
    }
    
    
    
}
