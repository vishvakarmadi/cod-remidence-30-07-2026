<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\OrderLog;
use App\Models\Admin\Order_courier_data;
use DB;
use SimpleXMLElement;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerify;
use DOMDocument;
use DOMXPath;

class Status extends Model
{
    public static function gettrack($ship_courier_id,$tracking_info,$type='forward'){
        $status = Status::getcourierstatuslogs($ship_courier_id,$tracking_info,$type);
        return Status::gethyloshipstatus($status,$ship_courier_id,$tracking_info,$type);
    }

    public static function getcourierstatuslogs($ship_courier_id,$tracking_info,$type='forward'){
//        echo $ship_courier_id.','.$tracking_info;die;
        if($ship_courier_id =='1'){
            return Integration::track_ecom($tracking_info);
        }
        if($ship_courier_id =='2'){
            return Integration::track_delhivery($tracking_info);
        }
        if($ship_courier_id =='3'){
            return Integration_more::track_bluedart($tracking_info);
        }
        if($ship_courier_id =='4'){
            return  Integration::track_xbees($tracking_info);
        }
        if($ship_courier_id =='5'){
            $track_data = array(
                'trkType'=>"cnno",
                'strcnno'=>$tracking_info,
                'addtnlDtl'=>"Y",

            );
            return Integration::track_dtdc(json_encode($track_data));  
        }
        if($ship_courier_id =='6'){
            return Integration::track_smartr($tracking_info);
        }
        if($ship_courier_id =='7'){
//            $track_data = array(
//                'request_id'=>"string",
//                'tracking_ids'=>array($tracking_info),
//             );
             return Integration_more::track_Ekart($tracking_info);
        }
        if($ship_courier_id =='8'){
            return Integration_more::track_shadowfax($tracking_info,$type);
        }
        if($ship_courier_id =='9'){
            return Integration_more::track_ats($tracking_info);   
        }  
        if($ship_courier_id =='10'){
            return Integration_more::getsttausblitz($tracking_info);   
        }
        if($ship_courier_id =='11'){
            return Integration_courier::getsttaushreemaruti($tracking_info);
        }
        if($ship_courier_id =='12'){
            return Integration_more::getsttauspckndel($tracking_info);
        }
        if($ship_courier_id =='13'){
            $order_info = DB::table('orders')->where('tracking_info', $tracking_info)->first();
            $user_id = $order_info ? $order_info->user_id : null;
            
            $integration = DB::table('integrations')->where('courier_id', 13)->where('user_id', $user_id)->first();
            if (!$integration) {
                $integration = DB::table('integrations')->where('courier_id', 13)->first();
            }
            return Integration::track_parcel_uncle($tracking_info, $integration);
        }
        if($ship_courier_id =='14'){
            $order_info = DB::table('orders')->where('tracking_info', $tracking_info)->first();
            $user_id = $order_info ? $order_info->user_id : null;
            
            $integration = DB::table('integrations')->where('courier_id', 14)->where('user_id', $user_id)->first();
            if (!$integration) {
                $integration = DB::table('integrations')->where('courier_id', 14)->first();
            }
            return Integration::track_indiapost($tracking_info, $integration);
        }
    } 

    public static function gethyloshipstatus($status,$ship_courier_id,$tracking_info,$type){
        if($ship_courier_id =='1'){
//            echo simplexml_load_string($status);die;
//            $xml = simplexml_load_string(str_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $status));
            $xml = simplexml_load_string(str_replace('&', '&amp;', $status));
            $json = json_encode($xml);
            $arrayd = json_decode($json,TRUE);
//            echo '<pre>';print_R($tracking_info);die;
           
            if(isset($arrayd['object']) && isset($arrayd['object']['field']) && (
                in_array($arrayd['object']['field'][14],array('33401','313','312','430')))){
                $datac[0]='5';//RTO
                $datac[1]=str_replace('T',' ',date('Y-m-d H:i:s',strtotime($arrayd['object']['field'][20])));
                if(gettype($arrayd['object']['field'][18]) =='string' && $arrayd['object']['field'][18] !=''){
                    $datac[3] = date('Y-m-d',strtotime($arrayd['object']['field'][18]));
                }
                return $datac;
            }
            if(isset($arrayd['object']) && isset($arrayd['object']['field']) && (
                in_array($arrayd['object']['field'][14],array('2405','210','231','1224','331','22705','22704','23405','23404','23403','22111','22109','23202','23201','22108','24204','24202','24201','242','241','12247','12246','12245','12244','12243','12242','12241','23402','23401','23103','23102','23101','22801','22702','22303','22301','22107','22106','22105','22104','22103','22102','22101','21701','21503','21502','21501','21004','21003','21002','21001','22701','423','402','218','219','222','223','224','227')))){
                $datac[0]='10';//NDR
                $datac[1]=str_replace('T',' ',date('Y-m-d H:i:s',strtotime($arrayd['object']['field'][20])));
                $datac[2] =$arrayd['object']['field'][14];
                if(gettype($arrayd['object']['field'][18]) =='string' && $arrayd['object']['field'][18] !=''){
                                        $datac[3] = date('Y-m-d',strtotime($arrayd['object']['field'][18]));
                }
//                echo '<pre>';print_R($datac);die;
                return $datac;
            }
            if(isset($arrayd['object']) && isset($arrayd['object']['field']) && (in_array($arrayd['object']['field'][14],array('34304','34303','426','425','424','405','407','408','409','411','414','417','418','419','420','310','230','211','001','013','1210','1220','1230','1310','1320','1340','1350','1360','1370','1380','1390','1400','1410','1420','1430','014')))){
                $datac[0]='12';//MAnifested
                $datac[1]=str_replace('T',' ',date('Y-m-d H:i:s',strtotime($arrayd['object']['field'][20])));
                if(gettype($arrayd['object']['field'][18]) =='string' && $arrayd['object']['field'][18] !=''){
                                        $datac[3] = date('Y-m-d',strtotime($arrayd['object']['field'][18]));
                }
                return $datac;
            }
//            echo $arrayd['object']['field'][14];die;
            if(isset($arrayd['object']) && isset($arrayd['object']['field']) && (in_array($arrayd['object']['field'][14],array('37215','24455','37214','37213','77','80','777')))){
                $datac[0]='13';//RTO in transit
                $datac[1]=str_replace('T',' ',date('Y-m-d H:i:s',strtotime($arrayd['object']['field'][20])));
                if(gettype($arrayd['object']['field'][18]) =='string' && $arrayd['object']['field'][18] !=''){
                                        $datac[3] = date('Y-m-d',strtotime($arrayd['object']['field'][18]));
                }
                return $datac;
            }
//            if(isset($arrayd['object']) && isset($arrayd['object']['field']) && (in_array($arrayd['object']['field'][14],array('777')))){
//                $datac[0]='6';//RTO Delivered
//                $datac[1]=str_replace('T',' ',date('Y-m-d H:i:s',strtotime($arrayd['object']['field'][20])));
//                if(gettype($arrayd['object']['field'][18]) =='string' && $arrayd['object']['field'][18] !=''){
//                                        $datac[3] = date('Y-m-d',strtotime($arrayd['object']['field'][18]));
//                }
//                return $datac;
//            }
            if(isset($arrayd['object']) && isset($arrayd['object']['field']) && (
                in_array($arrayd['object']['field'][14],array('207','34302','30414','30415','37212','37211','37210','37209','37208','37207','37206','37205','37204','37203','37202','37201','30413','33202','33201','347','34602','34601','32502','30101','22903','32104','32105','21603','21602','30505','21601','30412','30411','30410','30409','30408','30407','34501','34401','34301','34003','34002','34001','32501','32103','32101','32001','31701','31101','30503','30502','30501','24003','24002','24001','23801','23701','22901','20701','88803','88802','30406','30405','30404','30403','30402','30401','34003','34002','334','236','428','235','1225','450','316','332','400','416','1260','002','127','003','004','005','0011')))){
                $datac[0]='14';//in transit
                $datac[1]=str_replace('T',' ',date('Y-m-d H:i:s',strtotime($arrayd['object']['field'][20])));
                if(gettype($arrayd['object']['field'][18]) =='string' && $arrayd['object']['field'][18] !=''){
                                        $datac[3] = date('Y-m-d',strtotime($arrayd['object']['field'][18]));
                }
                return $datac;
            }
            if(isset($arrayd['object']) && isset($arrayd['object']['field']) && (
                in_array($arrayd['object']['field'][14],array('35006','35004','35003','35002','35001','350','20003','22703','31501','20002','20001','421','401','415','006','82','83')))){
                $datac[0]='15';//out for delivary
                $datac[1]=str_replace('T',' ',date('Y-m-d H:i:s',strtotime($arrayd['object']['field'][20])));
                if(gettype($arrayd['object']['field'][18]) =='string' && $arrayd['object']['field'][18] !=''){
                                        $datac[3] = date('Y-m-d',strtotime($arrayd['object']['field'][18]));
                }
                return $datac;
            }
            if(isset($arrayd['object']) && isset($arrayd['object']['field']) && (
                in_array($arrayd['object']['field'][14],array('34806','34805','34804','34803','34802','34801','33308','348','33305','33304','33303','33302','33301','333')))){
                $datac[0]='16';//Lost
                $datac[1]=str_replace('T',' ',date('Y-m-d H:i:s',strtotime($arrayd['object']['field'][20])));
                if(gettype($arrayd['object']['field'][18]) =='string' && $arrayd['object']['field'][18] !=''){
                                        $datac[3] = date('Y-m-d',strtotime($arrayd['object']['field'][18]));
                }
                return $datac;
            }
            if(isset($arrayd['object']) && isset($arrayd['object']['field']) && (
                in_array($arrayd['object']['field'][14],array('30204','30203','30202','30201','314')))){
                $datac[0]='17';//Damaged
                $datac[1]=str_replace('T',' ',date('Y-m-d H:i:s',strtotime($arrayd['object']['field'][20])));
                if(gettype($arrayd['object']['field'][18]) =='string' && $arrayd['object']['field'][18] !=''){
                                        $datac[3] = date('Y-m-d',strtotime($arrayd['object']['field'][18]));
                }
                return $datac;
            }
             if(isset($arrayd['object']) && isset($arrayd['object']['field']) && (
                in_array($arrayd['object']['field'][14],array('88807','88806','88805','888')))){
                $datac[0]='18';//Destroyed
                $datac[1]=str_replace('T',' ',date('Y-m-d H:i:s',strtotime($arrayd['object']['field'][20])));
                if(gettype($arrayd['object']['field'][18]) =='string' && $arrayd['object']['field'][18] !=''){
                                        $datac[3] = date('Y-m-d',strtotime($arrayd['object']['field'][18]));
                }
                return $datac;
            }
            if(isset($arrayd['object']) && isset($arrayd['object']['field']) && (in_array($arrayd['object']['field'][14],array('999','315')))){
                $datac[0]='DELIVERED';
                $datac[1]=str_replace('T',' ',date('Y-m-d H:i:s',strtotime($arrayd['object']['field'][20])));
                if(gettype($arrayd['object']['field'][18]) =='string' && $arrayd['object']['field'][18] !=''){
                                        $datac[3] = date('Y-m-d',strtotime($arrayd['object']['field'][18]));
                }
                return $datac;
        //                return 'DELIVERED';
            }
            if(isset($arrayd['object']) && isset($arrayd['object']['field']) && (in_array($arrayd['object']['field'][14],array('431','427','326','404','1330','011')))){
                $datac[0]='Canceled';
                $datac[1]=str_replace('T',' ',date('Y-m-d H:i:s',strtotime($arrayd['object']['field'][20])));
                if(gettype($arrayd['object']['field'][18]) =='string' && $arrayd['object']['field'][18] !=''){
                                        $datac[3] = date('Y-m-d',strtotime($arrayd['object']['field'][18]));
                }
                return $datac;
        //                return 'Canceled';
            }
        }
//         if($ship_courier_id =='2'){
//             $status = json_decode($status,true);
// //            echo '<pre>';print_R($status);die;
//             if(isset($status['ShipmentData']) && isset($status['ShipmentData'][0]) && $status['ShipmentData'][0]['Shipment'] && 
//             (in_array($status['ShipmentData'][0]['Shipment']['Status']['Status'],array('Delivered','DTO')) && in_array($status['ShipmentData'][0]['Shipment']['Status']['StatusType'],array('DL')) )){
// //               echo '<pre>';print_R($status['ShipmentData'][0]['Shipment']['Status']);die;
// //                $timestamp = explode('T',$status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']);
// //                $datetime_utc = new \DateTime($timestamp, new \DateTimeZone('UTC'));
// //                $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
// //                $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
// //                 echo $timestamp;die;
//                 $datac[0]='DELIVERED';
//                 $datac[1]=date('Y-m-d H:i:s', strtotime($status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']));
// //                echo '<pre>'; print_r($datac);die;
//                 return $datac;
//             }
//             if(isset($status['ShipmentData']) && isset($status['ShipmentData'][0]) && $status['ShipmentData'][0]['Shipment'] && (in_array($status['ShipmentData'][0]['Shipment']['Status']['Status'],array('Canceled','Closed')) && in_array($status['ShipmentData'][0]['Shipment']['Status']['StatusType'],array('CN')) )){
// //                $timestamp = explode('T',$status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']);
// //                $datetime_utc = new \DateTime($timestamp, new \DateTimeZone('UTC'));
// //                $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
// //                $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
// //                 echo $timestamp;die;
//                 $datac[0]='Canceled';
//                  $datac[1]=date('Y-m-d H:i:s', strtotime($status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']));
//                 return $datac;
//             }
//             if(isset($status['ShipmentData']) && isset($status['ShipmentData'][0]) && $status['ShipmentData'][0]['Shipment'] && 
//             (in_array($status['ShipmentData'][0]['Shipment']['Status']['Status'],array('In Transit')) && in_array($status['ShipmentData'][0]['Shipment']['Status']['StatusType'],array('RT')) )){
// //                $timestamp = explode('T',$status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']);
// //                $datetime_utc = new \DateTime($timestamp, new \DateTimeZone('UTC'));
// //                $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
// //                $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
// //                 echo $timestamp;die;
//                 $datac[0]='5';//RTO
//                  $datac[1]=date('Y-m-d H:i:s', strtotime($status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']));
//                 return $datac;
//             }
//             if(isset($status['ShipmentData']) && isset($status['ShipmentData'][0]) && $status['ShipmentData'][0]['Shipment'] && 
//             (in_array($status['ShipmentData'][0]['Shipment']['Status']['Status'],array('RTO')) && in_array($status['ShipmentData'][0]['Shipment']['Status']['StatusType'],array('DL')) )){
// //                $timestamp = explode('T',$status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']);
// //                $datetime_utc = new \DateTime($timestamp, new \DateTimeZone('UTC'));
// //                $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
// //                $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
// //                 echo $timestamp;die;
//                 $datac[0]='6';//RTO received
//                  $datac[1]=date('Y-m-d H:i:s', strtotime($status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']));
//                 return $datac;
//             }
//             if(isset($status['ShipmentData']) && isset($status['ShipmentData'][0]) && $status['ShipmentData'][0]['Shipment'] &&
//             ( 
//                 ($status['ShipmentData'][0]['Shipment']['Status']['Status'] =='Manifested' && $status['ShipmentData'][0]['Shipment']['Status']['StatusType'] =='UD') ||
//                 ($status['ShipmentData'][0]['Shipment']['Status']['Status'] =='Not Picked' && $status['ShipmentData'][0]['Shipment']['Status']['StatusType'] =='UD') ||
//                 ($status['ShipmentData'][0]['Shipment']['Status']['Status'] =='Open' && $status['ShipmentData'][0]['Shipment']['Status']['StatusType'] =='PP') ||
//                 ($status['ShipmentData'][0]['Shipment']['Status']['Status'] =='Scheduled' && $status['ShipmentData'][0]['Shipment']['Status']['StatusType'] =='PP')  
                
//                 ))
//             {
// //                $timestamp = explode('T',$status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']);
// //                $datetime_utc = new \DateTime($timestamp, new \DateTimeZone('UTC'));
// //                $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
// //                $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
// //                 echo $timestamp;die;
//                 $datac[0]='12';//Manifested
//                  $datac[1]=date('Y-m-d H:i:s', strtotime($status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']));
//                 return $datac;
//             }
//             if(isset($status['ShipmentData']) && isset($status['ShipmentData'][0]) && $status['ShipmentData'][0]['Shipment'] && 
//             (in_array($status['ShipmentData'][0]['Shipment']['Status']['Status'],array('Pending','Dispatched','In Transit')) && in_array($status['ShipmentData'][0]['Shipment']['Status']['StatusType'],array('RT')) )){
// //                $timestamp = explode('T',$status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']);
// //                $datetime_utc = new \DateTime($timestamp, new \DateTimeZone('UTC'));
// //                $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
// //                $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
// //                 echo $timestamp;die;
//                 $datac[0]='13';//RTO intransit
//                  $datac[1]=date('Y-m-d H:i:s', strtotime($status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']));
//                 return $datac;
//             }
//             if(isset($status['ShipmentData']) && isset($status['ShipmentData'][0]) && $status['ShipmentData'][0]['Shipment'] && (
//                 ($status['ShipmentData'][0]['Shipment']['Status']['Status'] =='In Transit' && $status['ShipmentData'][0]['Shipment']['Status']['StatusType'] =='UD') ||
//                 ($status['ShipmentData'][0]['Shipment']['Status']['Status'] =='Pending' && $status['ShipmentData'][0]['Shipment']['Status']['StatusType'] =='UD')  ||
//                 ($status['ShipmentData'][0]['Shipment']['Status']['Status'] =='In Transit' && $status['ShipmentData'][0]['Shipment']['Status']['StatusType'] =='PU')  ||
//                 ($status['ShipmentData'][0]['Shipment']['Status']['Status'] =='Pending' && $status['ShipmentData'][0]['Shipment']['Status']['StatusType'] =='PU')  
//                 )
//                 ){
// //               $timestamp = explode('T',$status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']);
// //                $datetime_utc = new \DateTime($timestamp, new \DateTimeZone('UTC'));
// //                $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
// //                $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
// //                 echo $timestamp;die;
//                 $datac[0]='14';//intrasit
//                  $datac[1]=date('Y-m-d H:i:s', strtotime($status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']));
// //                echo 'hi';die;
//                 return $datac;
//             }
//             if(isset($status['ShipmentData']) && isset($status['ShipmentData'][0]) && $status['ShipmentData'][0]['Shipment'] && (
//                 in_array($status['ShipmentData'][0]['Shipment']['Status']['Status'],array('Dispatched')) && 
//                 in_array($status['ShipmentData'][0]['Shipment']['Status']['StatusType'],array('UD','PU')) )){
// //                $timestamp = explode('T',$status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']);
// //                $datetime_utc = new \DateTime($timestamp, new \DateTimeZone('UTC'));
// //                $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
// //                $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
// //                 echo $timestamp;die;
//                 $datac[0]='15';//Out for Delivery
//                  $datac[1]=date('Y-m-d H:i:s', strtotime($status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']));
//                 return $datac;
//             }
//             if(isset($status['ShipmentData']) && isset($status['ShipmentData'][0]) && $status['ShipmentData'][0]['Shipment'] && (
//                 in_array($status['ShipmentData'][0]['Shipment']['Status']['Status'],array('LOST','Lost'))  )){
// //                $timestamp = explode('T',$status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']);
// //                $datetime_utc = new \DateTime($timestamp, new \DateTimeZone('UTC'));
// //                $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
// //                $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
// //                 echo $timestamp;die;
//                 $datac[0]='16';//LOST
//                  $datac[1]=date('Y-m-d H:i:s', strtotime($status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']));
//                 return $datac;
//             }
// //            echo 'hff';die;
// //            $timestamp = explode('T',$status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']);
// //                $datetime_utc = new \DateTime($timestamp, new \DateTimeZone('UTC'));
// //                $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
// //                $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
// //                 echo $timestamp;die;
// //            echo $ist_timestamp;die;
//             $datac[0]='other';
//              if(isset($status['ShipmentData'])){
//                 $datac[1]=date('Y-m-d H:i:s', strtotime($status['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']));
//              }else{
//                  $datac[1]=now();
//              }
//             return $datac;
//         }
  if($ship_courier_id =='2'){
            $status = json_decode($status,true);
            if(isset($status['ShipmentData']) && isset($status['ShipmentData'][0]) && isset($status['ShipmentData'][0]['Shipment'])){
                $track = $status['ShipmentData'][0]['Shipment'];
                $currentStatus = $track['Status']['Status'];
                $statusType = $track['Status']['StatusType'];
                $eventDate = date('Y-m-d H:i:s', strtotime($track['Status']['StatusDateTime']));

                // 1. Delivered / RTO Delivered
                if ($statusType == 'DL') {
                    if (str_contains(strtoupper($currentStatus), 'RTO') || str_contains(strtoupper($currentStatus), 'DTO')) {
                        $datac[0] = '6'; // RTO Received
                    } else {
                        $datac[0] = 'DELIVERED';
                    }
                    $datac[1] = $eventDate;
                    return $datac;
                }

                // 2. Canceled
                if ($statusType == 'CN' || in_array($currentStatus, array('Canceled', 'Closed'))) {
                    $datac[0] = 'Canceled';
                    $datac[1] = $eventDate;
                    return $datac;
                }

                // 3. RTO / Return
                if ($statusType == 'RT') {
                    if (in_array($currentStatus, array('In Transit', 'Pending', 'Dispatched'))) {
                        $datac[0] = '13'; // RTO In Transit
                    } else {
                        $datac[0] = '5'; // RTO
                    }
                    $datac[1] = $eventDate;
                    return $datac;
                }

                // 4. Out for Delivery
                if (str_contains(strtoupper($currentStatus), 'OUT FOR DELIVERY') || $currentStatus == 'Dispatched') {
                    $datac[0] = '15';
                    $datac[1] = $eventDate;
                    return $datac;
                }

                // 5. Pickup Pending / Manifested
                if ($statusType == 'PP' || in_array($currentStatus, array('Manifested', 'Not Picked', 'Open', 'Scheduled', 'Pickup Scheduled', 'Pickup Pending'))) {
                    $datac[0] = '12';
                    $datac[1] = $eventDate;
                    return $datac;
                }

                // 6. In Transit / Picked Up
                if ($statusType == 'PU' || $statusType == 'UD' || in_array($currentStatus, array('In Transit', 'Pending', 'Picked Up', 'Arrived at Hub', 'Departed from Hub', 'Inbound', 'Outbound'))) {
                    $datac[0] = '14';
                    $datac[1] = $eventDate;
                    return $datac;
                }

                // Fallback for Delhivery
                $datac[0] = 'other';
                $datac[1] = $eventDate;
                return $datac;
            }

            $datac[0] = 'other';
            $datac[1] = now();
            return $datac;
        }
         if($ship_courier_id =='3'){
            $status = json_decode($status,true);
            
            // Handle Native APIGEE ShipmentData Response
            if (isset($status['ShipmentData']['Shipment'][0])) {
                $track = $status['ShipmentData']['Shipment'][0];
                $edd_raw = $track['ExpectedDeliveryDate'] ?? $track['Expected_Delivery_Date'] ?? null;
                $currentStatus = strtoupper($track['Status'] ?? '');
                
                // Get date from StatusDate
                $eventDate = $track['StatusDate'] ?? date('Y-m-d H:i:s');
                if (isset($track['StatusTime'])) {
                    $eventDate = date('Y-m-d H:i:s', strtotime($eventDate . ' ' . $track['StatusTime']));
                }

                $statusType = strtoupper($track['StatusType'] ?? '');

                if ($statusType == 'DL' || str_contains($currentStatus, 'DELIVERED') && !str_contains($currentStatus, 'UNDELIVERED')) {
                    $datac[0] = 'DELIVERED';
                } elseif ($statusType == 'CN' || str_contains($currentStatus, 'CANCEL') || str_contains($currentStatus, 'SHIPMENT CANCELLED')) {
                    $datac[0] = 'Canceled';
                } elseif (str_contains($currentStatus, 'RTO DELIVERED') || str_contains($currentStatus, 'RTO RECEIVED')) {
                    $datac[0] = '6'; // RTO Delivered/Received
                } elseif ($statusType == 'RT' || str_contains($currentStatus, 'RTO') || str_contains($currentStatus, 'RETURN')) {
                    $datac[0] = '5'; // RTO
                } elseif (str_contains($currentStatus, 'UNDELIVERED') || str_contains($currentStatus, 'RE-ATTEMPT') || str_contains($currentStatus, 'FAILED') || str_contains($currentStatus, 'NOT DELIVERED')) {
                    $datac[0] = '10'; // NDR
                } elseif ($statusType == 'OD' || str_contains($currentStatus, 'OUT FOR DELIVERY')) {
                    $datac[0] = '15'; // Out for Delivery
                } elseif ($statusType == 'PU' || str_contains($currentStatus, 'BOOKED') || str_contains($currentStatus, 'PICKUP SCHEDULED')) {
                    $datac[0] = '12'; // Pickup Pending
                } elseif ($statusType == 'UD' || str_contains($currentStatus, 'IN TRANSIT') || str_contains($currentStatus, 'SHIPPED') || str_contains($currentStatus, 'PICKED UP')) {
                    $datac[0] = '14'; // In Transit
                } elseif ($statusType == 'NF') {
                    return null; // Waybill not found, skip update
                } else {
                    $datac[0] = '14'; // Default: In Transit (order is already shipped/moving)
                }
                $datac[1] = $eventDate;
                return $datac;
            }

            // Handle Native APIGEE TrackingDetails Response
            if (isset($status['TrackingDetails']) && is_array($status['TrackingDetails']) && count($status['TrackingDetails']) > 0) {
                $track = $status['TrackingDetails'][0];
                $currentStatus = strtoupper($track['Status'] ?? '');
                $eventDate = $track['StatusDate'] ?? date('Y-m-d H:i:s');

                if (str_contains($currentStatus, 'DELIVERED')) {
                    $datac[0] = 'DELIVERED';
                } elseif (str_contains($currentStatus, 'CANCEL')) {
                    $datac[0] = 'Canceled';
                } elseif (str_contains($currentStatus, 'RTO') || str_contains($currentStatus, 'RETURN')) {
                    $datac[0] = '5'; // RTO
                } elseif (str_contains($currentStatus, 'UNDELIVERED') || str_contains($currentStatus, 'RE-ATTEMPT') || str_contains($currentStatus, 'FAILED')) {
                    $datac[0] = '10'; // NDR
                } elseif (str_contains($currentStatus, 'OUT FOR DELIVERY')) {
                    $datac[0] = '15';
                } elseif (str_contains($currentStatus, 'IN TRANSIT')) {
                    $datac[0] = '14';
                } else {
                    $datac[0] = '12'; // Default to manifested/processing
                }
                $datac[1] = $eventDate;
                return $datac;
            }

            // Fallback to ParcelX Logic
            if(isset($status['status']) && $status['status'] && isset($status['current_status']) && gettype($status['current_status']) =='array'){
                if($status['current_status']['status_code'] =='227'){
                    $datac[0]='Canceled';
                    $datac[1]=$status['current_status']['event_date'];
                    return $datac;
                }
                if($status['current_status']['status_code'] =='226'){
                    $datac[0]='DELIVERED';
                    $datac[1]=$status['current_status']['event_date'];
                    return $datac;
                }
                if(in_array($status['current_status']['status_code'],array('225','235'))){
                    $datac[0]='5';//RTO
                    $datac[1]=$status['current_status']['event_date'];
                    return $datac;
                }
                if(in_array($status['current_status']['status_code'],array('224'))){
                    $datac[0]='6';//RTO Received
                    $datac[1]=$status['current_status']['event_date'];
                    return $datac;
                }
                if(in_array($status['current_status']['status_code'],array('220','221','222','231','232'))){
                    $datac[0]='12';//manifested
                    $datac[1]=$status['current_status']['event_date'];
                    return $datac;
                }
                if(in_array($status['current_status']['status_code'],array('223','230','531','532'))){
                    $datac[0]='14';//In Transit
                    $datac[1]=$status['current_status']['event_date'];
                    return $datac;
                }
                if($status['current_status']['status_code'] =='237'){
                    $datac[0]='16';//LOST
                    $datac[1]=$status['current_status']['event_date'];
                    return $datac;
                }
                if(in_array($status['current_status']['status_code'],array('236','234'))){
                    $datac[0]='13';//RTO In Transit
                    $datac[1]=$status['current_status']['event_date'];
                    return $datac;
                }
                if(in_array($status['current_status']['status_code'],array('228'))){
                    $datac[0]='15';//Out for Delivery
                    $datac[1]=$status['current_status']['event_date'];
                    return $datac;
                }
                if($status['current_status']['status_code'] =='233'){
                    $datac[0]='10';//NDR
                    $datac[1]=$status['current_status']['event_date'];
                    $datac[2]=$status['current_status']['status_description'];
                    return $datac;
                }
            }
        }
        if($ship_courier_id =='4'){
             $status = json_decode($status,true);
             if(isset($status['ReturnCode']) && $status['ReturnCode'] =='100'){
                if(isset($status['ShipmentLogDetails']) && count($status['ShipmentLogDetails'])>0){ 
                    $currentStatus = '';
                    if(isset($status['ShipmentLogDetails'][0]['ShipmentStatus'])){
                        $currentStatus = $status['ShipmentLogDetails'][0]['ShipmentStatus'];
                    }
                    if(in_array($currentStatus,array('RAD',"IT","PKD","PUD","STD"))){
                       $datac[0]='14';//In Transit
                       $datac[1]=date('Y-m-d h:i:s',strtotime($status['ShipmentLogDetails'][0]['ShipmentStatusDateTime']));
                       return $datac;
                    }
                    if(in_array($currentStatus,array('RTO-IT','RTO-OFD','RAO'))){
                        $datac[0]='13';//RTO In Transit
                        $datac[1]=date('Y-m-d h:i:s',strtotime($status['ShipmentLogDetails'][0]['ShipmentStatusDateTime']));
                        return $datac;
                    }
                    if(in_array($currentStatus,array('RTO','RTON'))){
                        $datac[0]='5';//RTO
                        $datac[1]=date('Y-m-d h:i:s',strtotime($status['ShipmentLogDetails'][0]['ShipmentStatusDateTime']));
                        return $datac;
                    }
                    if(in_array($currentStatus,array('RTD'))){
                        $datac[0]='6';//RTO Received
                        $datac[1]=date('Y-m-d h:i:s',strtotime($status['ShipmentLogDetails'][0]['ShipmentStatusDateTime']));
                        return $datac;
                    }
                    if(in_array($currentStatus,array('LOST'))){
                        $datac[0]='16';//Lost
                        $datac[1]=date('Y-m-d h:i:s',strtotime($status['ShipmentLogDetails'][0]['ShipmentStatusDateTime']));
                        return $datac;
                    }
                    if(in_array($currentStatus,array('OFD'))){
                       $datac[0]='15';//Out for Delivery
                       $datac[1]=date('Y-m-d h:i:s',strtotime($status['ShipmentLogDetails'][0]['ShipmentStatusDateTime']));
                       return $datac;
                    }
                    if(in_array($currentStatus,array('OFP',"DRC"))){
                        $datac[0]='12';//manifested
                        $datac[1]=date('Y-m-d h:i:s',strtotime($status['ShipmentLogDetails'][0]['ShipmentStatusDateTime']));
                        return $datac;
                    }
                    if(in_array($currentStatus,array("UD"))){
                        $datac[0]='10';//NDR
                        $datac[1]=date('Y-m-d h:i:s',strtotime($status['ShipmentLogDetails'][0]['ShipmentStatusDateTime']));
                        $datac[2]=$status['ShipmentLogDetails'][0]['Description'];
                        return $datac;
                    }
                    if(in_array($currentStatus,array('DLVD'))){
                       $datac[0]='DELIVERED';
                       $datac[1]=date('Y-m-d h:i:s',strtotime($status['ShipmentLogDetails'][0]['ShipmentStatusDateTime']));
                       return $datac;
                    }
                }
             
            }
        }
        if($ship_courier_id =='5'){
            $status = json_decode($status,true);
//            echo '<pre>';print_R(($status));die;
            if(count($status['trackDetails']) >0 ){
                $status['trackHeader']['strStatus'] = $status['trackDetails'][count($status['trackDetails'])-1]['strAction'];
                $status['trackHeader']['strStatusTransOn'] = $status['trackDetails'][count($status['trackDetails'])-1]['strActionDate'];
                $status['trackHeader']['strStatusTransTime'] = $status['trackDetails'][count($status['trackDetails'])-1]['strActionTime'];
            }
            if(isset($status['statusCode']) && $status['statusCode'] == '200' && $status['status'] =='SUCCESS' && isset($status['trackHeader']) &&  (strtolower($status['trackHeader']['strStatus'])=='delivered' || strtolower($status['trackHeader']['strStatus'])=='otp based delivered')){
                $x =$status['trackHeader']['strStatusTransOn'];
                $y =$status['trackHeader']['strStatusTransTime'];
                $datac[0]='DELIVERED';
                $datac[1]=substr($x, 4, 4).'-'.substr($x, 2, 2).'-'.substr($x, 0, 2).' '.substr($y, 0, 2).':'.substr($y, 2, 2).':00';
                return $datac;
//                return 'DELIVERED';
            }  
            if(isset($status['statusCode']) && $status['statusCode'] == '200' && $status['status'] =='SUCCESS' && isset($status['trackHeader']) &&  (strtolower($status['trackHeader']['strStatus']) =='canceled' || strtolower($status['trackHeader']['strStatus']) =='pickup cancelled')){
                $x =$status['trackHeader']['strStatusTransOn'];
                $y =$status['trackHeader']['strStatusTransTime'];
                $datac[0]='Canceled';
                $datac[1]=substr($x, 4, 4).'-'.substr($x, 2, 2).'-'.substr($x, 0, 2).' '.substr($y, 0, 2).':'.substr($y, 2, 2).':00';
                return $datac;
//                return 'Canceled';
            }
            if(isset($status['statusCode']) && $status['statusCode'] == '200' && $status['status'] =='SUCCESS' && isset($status['trackHeader']) &&  ((strtolower($status['trackHeader']['strStatus'])=='rto received') || (strtolower($status['trackHeader']['strStatus'])=='rto processed & forwarded') || (strtolower($status['trackHeader']['strStatus'])=='rto booked'))){
                $x =$status['trackHeader']['strStatusTransOn'];
                $y =$status['trackHeader']['strStatusTransTime'];
                $datac[0]='5';//RTO
                $datac[1]=substr($x, 4, 4).'-'.substr($x, 2, 2).'-'.substr($x, 0, 2).' '.substr($y, 0, 2).':'.substr($y, 2, 2).':00';
                return $datac;
            }
            if(isset($status['statusCode']) && $status['statusCode'] == '200' && $status['status'] =='SUCCESS' && isset($status['trackHeader']) &&  ((strtolower($status['trackHeader']['strStatus'])=='rto delivered'))){
                $x =$status['trackHeader']['strStatusTransOn'];
                $y =$status['trackHeader']['strStatusTransTime'];
                $datac[0]='6';//RTO Received
                $datac[1]=substr($x, 4, 4).'-'.substr($x, 2, 2).'-'.substr($x, 0, 2).' '.substr($y, 0, 2).':'.substr($y, 2, 2).':00';
                return $datac;
            }
            if(isset($status['statusCode']) && $status['statusCode'] == '200' && $status['status'] =='SUCCESS' && isset($status['trackHeader']) &&  ( (strtolower($status['trackHeader']['strStatus'])=='not delivered') || (strtolower($status['trackHeader']['strStatus'])=='wrong pincode'))){
                $x =$status['trackHeader']['strStatusTransOn'];
                $y =$status['trackHeader']['strStatusTransTime'];
                $datac[0]='10';//NDR
                $datac[1]=substr($x, 4, 4).'-'.substr($x, 2, 2).'-'.substr($x, 0, 2).' '.substr($y, 0, 2).':'.substr($y, 2, 2).':00';
                if(isset($status['trackDetails']) && count($status['trackDetails']) >0){
                    $rmk = explode('|',$status['trackDetails'][count($status['trackDetails'])-1]['sTrRemarks']);
                    $datac[2]=$rmk[0];
                }
                if(strtolower($status['trackHeader']['strStatus'])=='wrong pincode'){
                    $datac[2]=$status['trackHeader']['strStatus'];
                }
                return $datac;
            }
            if(isset($status['statusCode']) && $status['statusCode'] == '200' && $status['status'] =='SUCCESS' && isset($status['trackHeader']) &&  ((strtolower($status['trackHeader']['strStatus'])=='pickup scheduled') || (strtolower($status['trackHeader']['strStatus'])=='not picked') || (strtolower($status['trackHeader']['strStatus'])=='pickup awaited') || (strtolower($status['trackHeader']['strStatus'])=='archived') || (strtolower($status['trackHeader']['strStatus'])=='pickup reassigned'))){
                $x =$status['trackHeader']['strStatusTransOn'];
                $y =$status['trackHeader']['strStatusTransTime'];
                $datac[0]='12';//manifested
                $datac[1]=substr($x, 4, 4).'-'.substr($x, 2, 2).'-'.substr($x, 0, 2).' '.substr($y, 0, 2).':'.substr($y, 2, 2).':00';
                return $datac;
            }
            if(isset($status['statusCode']) && $status['statusCode'] == '200' ){
                if($status['status'] =='SUCCESS' && isset($status['trackHeader']) &&  (strtolower($status['trackHeader']['strStatus'])=='rto in transit') || (strtolower($status['trackHeader']['strStatus'])=='rto out for delivery') || (strtolower($status['trackHeader']['strStatus'])=='rto out for delivery') || (strtolower($status['trackHeader']['strStatus'])=='rto returned/rto out for delivery')){
                    $x =$status['trackHeader']['strStatusTransOn'];
                    $y =$status['trackHeader']['strStatusTransTime'];
                    $datac[0]='13';//RTO In Transit
                    $datac[1]=substr($x, 4, 4).'-'.substr($x, 2, 2).'-'.substr($x, 0, 2).' '.substr($y, 0, 2).':'.substr($y, 2, 2).':00';
                    return $datac;
                }
            
            }
             
            if(isset($status['statusCode']) && $status['statusCode'] == '200' && $status['status'] =='SUCCESS' && isset($status['trackHeader']) &&  ((strtolower($status['trackHeader']['strStatus'])=='picked up') || (strtolower($status['trackHeader']['strStatus'])=='in transit') || (strtolower($status['trackHeader']['strStatus'])=='arrived at airport') || (strtolower($status['trackHeader']['strStatus'])=='not received') || (strtolower($status['trackHeader']['strStatus'])=='customs cleared') || (strtolower($status['trackHeader']['strStatus'])=='customs heldup') || (strtolower($status['trackHeader']['strStatus'])=='held up') || (strtolower($status['trackHeader']['strStatus'])=='mis route') || (strtolower($status['trackHeader']['strStatus'])=='booked'))){
                $x =$status['trackHeader']['strStatusTransOn'];
                $y =$status['trackHeader']['strStatusTransTime'];
                $datac[0]='14';//In Transit
                $datac[1]=substr($x, 4, 4).'-'.substr($x, 2, 2).'-'.substr($x, 0, 2).' '.substr($y, 0, 2).':'.substr($y, 2, 2).':00';
                return $datac;
            } 
            if(isset($status['statusCode']) && $status['statusCode'] == '200' && $status['status'] =='SUCCESS' && isset($status['trackHeader']) &&  ((strtolower($status['trackHeader']['strStatus'])=='out for delivery')  || (strtolower($status['trackHeader']['strStatus'])=='fdm prepared'))){
                $x =$status['trackHeader']['strStatusTransOn'];
                $y =$status['trackHeader']['strStatusTransTime'];
                $datac[0]='15';//Out for Delivery
                $datac[1]=substr($x, 4, 4).'-'.substr($x, 2, 2).'-'.substr($x, 0, 2).' '.substr($y, 0, 2).':'.substr($y, 2, 2).':00';
                return $datac;
            }
            
            if(isset($status['statusCode']) && $status['statusCode'] == '200' && $status['status'] =='SUCCESS' && isset($status['trackHeader']) &&  ((strtolower($status['trackHeader']['strStatus'])=='damaged shipment received box opened from hub'))){
                $x =$status['trackHeader']['strStatusTransOn'];
                $y =$status['trackHeader']['strStatusTransTime'];
                $datac[0]='17';//Damaged
                $datac[1]=substr($x, 4, 4).'-'.substr($x, 2, 2).'-'.substr($x, 0, 2).' '.substr($y, 0, 2).':'.substr($y, 2, 2).':00';
                return $datac;
            }
           
           
        }
        if($ship_courier_id =='6'){
            $status = json_decode($status,true);
            if($status['success'] ==1 && isset($status['data']) && isset($status['data'][0]) && isset($status['data'][0]['currentStatusDescription']) && (in_array($status['data'][0]['currentStatusCode'],array('CAN'))))
            {
                $datac[0]='Canceled';
                $datac[1]=str_replace('T',' ',$status['data'][0]['shipmentStatus'][0]['eventDate']);
                return $datac;
//                return ('Canceled',);
            }
            if($status['success'] ==1 && isset($status['data']) && isset($status['data'][0]) && isset($status['data'][0]['currentStatusDescription']) && (in_array($status['data'][0]['currentStatusCode'],array('DDL'))))
            {
                $datac[0]='DELIVERED';
                $datac[1]=str_replace('T',' ',$status['data'][0]['shipmentStatus'][0]['eventDate']);
                return $datac;
            }
            if($status['success'] ==1 && isset($status['data']) && isset($status['data'][0]) && isset($status['data'][0]['currentStatusDescription']) && (in_array($status['data'][0]['currentStatusCode'],array('RTL'))))
            {
                $datac[0]='5';//RTO
                $datac[1]=str_replace('T',' ',$status['data'][0]['shipmentStatus'][0]['eventDate']);
                return $datac;
            }
            if($status['success'] ==1 && isset($status['data']) && isset($status['data'][0]) && isset($status['data'][0]['currentStatusDescription']) && (in_array($status['data'][0]['currentStatusCode'],array('SUD'))))
            {
                $datac[0]='10';//NDR
                $datac[1]=str_replace('T',' ',$status['data'][0]['shipmentStatus'][0]['eventDate']);
                $datac[2]=$status['data'][0]['shipmentStatus'][0]['reasonCode'];
                return $datac;
            }
            if($status['success'] ==1 && isset($status['data']) && isset($status['data'][0]) && isset($status['data'][0]['currentStatusDescription']) && (in_array($status['data'][0]['currentStatusCode'],array('MAN','OFP','PKF'))))
            {
                $datac[0]='12';//MAnifested
                $datac[1]=str_replace('T',' ',$status['data'][0]['shipmentStatus'][0]['eventDate']);
                return $datac;
            }
            if($status['success'] ==1 && isset($status['data']) && isset($status['data'][0]) && isset($status['data'][0]['currentStatusDescription']) && (in_array($status['data'][0]['currentStatusCode'],array('RTS'))))
            {
                $datac[0]='6';//RTO Delivered
                $datac[1]=str_replace('T',' ',$status['data'][0]['shipmentStatus'][0]['eventDate']);
                return $datac;
            }
            if($status['success'] ==1 && isset($status['data']) && isset($status['data'][0]) && isset($status['data'][0]['currentStatusDescription']) && (in_array($status['data'][0]['currentStatusCode'],array('PKD','IND','DPD','ARD','RDC'))))
            {
                $datac[0]='14';//In Transit
                $datac[1]=str_replace('T',' ',$status['data'][0]['shipmentStatus'][0]['eventDate']);
                return $datac;
            }
            if($status['success'] ==1 && isset($status['data']) && isset($status['data'][0]) && isset($status['data'][0]['currentStatusDescription']) && (in_array($status['data'][0]['currentStatusCode'],array('OFD'))))
            {
                $datac[0]='15';//out for Delivery
                $datac[1]=str_replace('T',' ',$status['data'][0]['shipmentStatus'][0]['eventDate']);
                return $datac;
            }
            if($status['success'] ==1 && isset($status['data']) && isset($status['data'][0]) && isset($status['data'][0]['currentStatusDescription']) && (in_array($status['data'][0]['currentStatusCode'],array('LST'))))
            {
                $datac[0]='16';//lost
                $datac[1]=str_replace('T',' ',$status['data'][0]['shipmentStatus'][0]['eventDate']);
                return $datac;
            }
            if($status['success'] ==1 && isset($status['data']) && isset($status['data'][0]) && isset($status['data'][0]['currentStatusDescription']) && (in_array($status['data'][0]['currentStatusCode'],array('DSD'))))
            {
                $datac[0]='17';//damged
                $datac[1]=str_replace('T',' ',$status['data'][0]['shipmentStatus'][0]['eventDate']);
                return $datac;
            }
            
        }
//         if($ship_courier_id =='7'){
//             $status = json_decode($status,true);
//             if(isset($status['track']['ctime'])){
//                 $ctime = @$status['track']['ctime'];
//                 $status = @$status['track']['status'];
//                 $formattedDate = date('Y-m-d H:i:s', @$ctime / 1000);
//                 $formattedonlydate = date('Y-m-d', @$ctime / 1000);
// //                echo '<pre>';print_R($status);die;
//                 if(isset($status) && (in_array($status,array('Order Placed','Pickup Pending','Pickup Scheduled','Not Picked','Out for Pickup','Not Picked - QC Failed'))))
//                 {
//                     $date = $formattedDate;
//                     $datac[0]='12';//manifested
//                     $datac[1]=$formattedonlydate;
//                     return $datac;
//                 }
//                 if(isset($status) && (in_array($status,array('In Transit','Picked Up','Shipment Delayed','Undelivered'))))
//                 {
//                     $date = $formattedDate;
//                     $datac[0]='14';//In trasit
//                     $datac[1]=$formattedonlydate;
//                     return $datac;
//                 }
//                 if(isset($status) && (in_array($status,array('Out for Delivery'))))
//                 {
//                     $date = $formattedDate;
//                     $datac[0]='15';//Out for Delivery
//                     $datac[1]=$formattedonlydate;
//                     return $datac;
//                 }
//                 if(isset($status) && (in_array($status,array('Delivered'))))
//                 {
//                     $date = $formattedDate;
//                     $datac[0]='DELIVERED';
//                     $datac[1]=$formattedonlydate;
//                     return $datac;
//                 }
//                 if(isset($status) && (in_array($status,array('Cancelled','Seller Cancelled'))))
//                 {
//                     $date = $formattedDate;
//                     $datac[0]='Canceled';
//                     $datac[1]=$formattedonlydate;
//                     return $datac;
//                 }
//                 if(isset($status) && (in_array($status,array('RTO Requested','Seller RTO Requested','RTO Failed'))))
//                 {
//                     $date = $formattedDate;
//                     $datac[0]='5';//RTO
//                     $datac[1]=$formattedonlydate;
//                     return $datac;
//                 }
//                 if(isset($status) && (in_array($status,array('RTO In Transit','RTO Out for Delivery'))))
//                 {
//                     $date = $formattedDate;
//                     $datac[0]='13';//RTO In Transit
//                     $datac[1]=$formattedonlydate;
//                     return $datac;
//                 }
//                 if(isset($status) && (in_array($status,array('RTO Delivered'))))
//                 {
//                     $date = $formattedDate;
//                     $datac[0]='6';//RTO completed
//                     $datac[1]=$formattedonlydate;
//                     return $datac;
//                 }
//                 if(isset($status) && (in_array($status,array('Lost'))))
//                 {
//                     $date = $formattedDate;
//                     $datac[0]='16';//LOST
//                     $datac[1]=$formattedonlydate;
//                     return $datac;
//                 }
//                 if(isset($status) && (in_array($status,array('Damaged'))))
//                 {
//                     $date = $formattedDate;
//                     $datac[0]='17';//damaged
//                     $datac[1]=$formattedonlydate;
//                     return $datac;
//                 }
                
                
//             }
//         }
 if($ship_courier_id =='7'){
            $res_ekart = json_decode($status,true);
            if(isset($res_ekart['track']['ctime'])){
                $ctime = @$res_ekart['track']['ctime'];
                $track_status = @$res_ekart['track']['status'];
                $formattedDate = date('Y-m-d H:i:s', @$ctime / 1000);
                $formattedonlydate = date('Y-m-d', @$ctime / 1000);
                
                if(isset($res_ekart['edd']) && $res_ekart['edd'] !=''){
                     $datac[3] = date('Y-m-d', $res_ekart['edd'] / 1000);
                }
//                echo '<pre>';print_R($status);die;
                if(isset($track_status) && (in_array($track_status,array('Order Placed','Pickup Pending','Pickup Scheduled','Not Picked','Out for Pickup','Not Picked - QC Failed'))))
                {
                    $date = $formattedDate;
                    $datac[0]='12';//manifested
                    $datac[1]=$formattedonlydate;
                    return $datac;
                }
                if(isset($track_status) && (in_array($track_status,array('In Transit','Picked Up','Shipment Delayed','Undelivered'))))
                {
                    $date = $formattedDate;
                    $datac[0]='14';//In trasit
                    $datac[1]=$formattedonlydate;
                    return $datac;
                }
                if(isset($track_status) && (in_array($track_status,array('Out for Delivery'))))
                {
                    $date = $formattedDate;
                    $datac[0]='15';//Out for Delivery
                    $datac[1]=$formattedonlydate;
                    return $datac;
                }
                if(isset($track_status) && (in_array($track_status,array('Delivered'))))
                {
                    $date = $formattedDate;
                    $datac[0]='DELIVERED';
                    $datac[1]=$formattedonlydate;
                    return $datac;
                }
                if(isset($track_status) && (in_array($track_status,array('Cancelled','Seller Cancelled'))))
                {
                    $date = $formattedDate;
                    $datac[0]='Canceled';
                    $datac[1]=$formattedonlydate;
                    return $datac;
                }
                if(isset($track_status) && (in_array($track_status,array('RTO Requested','Seller RTO Requested','RTO Failed'))))
                {
                    $date = $formattedDate;
                    $datac[0]='5';//RTO
                    $datac[1]=$formattedonlydate;
                    return $datac;
                }
                if(isset($track_status) && (in_array($track_status,array('RTO In Transit','RTO Out for Delivery'))))
                {
                    $date = $formattedDate;
                    $datac[0]='13';//RTO In Transit
                    $datac[1]=$formattedonlydate;
                    return $datac;
                }
                if(isset($track_status) && (in_array($track_status,array('RTO Delivered'))))
                {
                    $date = $formattedDate;
                    $datac[0]='6';//RTO completed
                    $datac[1]=$formattedonlydate;
                    return $datac;
                }
                if(isset($track_status) && (in_array($track_status,array('Lost'))))
                {
                    $date = $formattedDate;
                    $datac[0]='16';//LOST
                    $datac[1]=$formattedonlydate;
                    return $datac;
                }
                if(isset($track_status) && (in_array($track_status,array('Damaged'))))
                {
                    $date = $formattedDate;
                    $datac[0]='17';//damaged
                    $datac[1]=$formattedonlydate;
                    return $datac;
                }
                
                
            }
        }
        if($ship_courier_id =='8'){
            $status = json_decode($status,true);
            if($type =='forward'){
                if(isset($status['tracking_details']) && is_array($status['tracking_details']) && count($status['tracking_details']) > 0){
                    $indexc  = count($status['tracking_details'])-1;
                    $datetime_utc = new \DateTime($status['tracking_details'][$indexc]['created'], new \DateTimeZone('UTC'));
                    $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                    $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
                } else {
                    $ist_timestamp = date('Y-m-d H:i:s');
                }
                if(isset($status['order_details']) && isset($status['order_details']['status'])){
                    $sf_status = strtolower($status['order_details']['status']);
                    if(in_array($sf_status,array('cancelled_by_seller')))
                    {
                        $datac[0]='Canceled';
                        $datac[1]=$ist_timestamp;
                        return $datac;
                    }
                    if(in_array($sf_status,array('delivered')))
                    {
                        $datac[0]='DELIVERED';
                        $datac[1]=$ist_timestamp;
                        return $datac;
                    }
                    if(in_array($sf_status,array('cancelled_by_customer','rts')))
                    {
                        $datac[0]='5';//rto
                        $datac[1]=$ist_timestamp;
                        return $datac;
                    }
                    if(in_array($sf_status,array('rts_nd')))
                    {
                        $datac[0]='6';//rto Received
                        $datac[1]=$ist_timestamp;
                        return $datac;
                    }
                    if(in_array($sf_status,array('cid','seller_not_contactable','nc','pickup_not_attempted')))
                    {
                        $datac[0]='10';//NDR
                        $datac[1]=$ist_timestamp;
                        $datac[2]=$status['order_details']['status'];
                        return $datac;
                    }
                    if(in_array($sf_status,array('new','assigned_for_seller_pickup','ofp','seller_initiated_delay',)))
                    {
                        $datac[0]='12';//manifested
                        $datac[1]=$ist_timestamp;
                        return $datac;
                    }
                    if(in_array($sf_status,array('rts_d','in_transit_return','rts_in_process','rts_ofd','recd_at_dc_rts','received_at_rts_hub')))
                    {
                        $datac[0]='13';//RTO In Transit
                        $datac[1]=$ist_timestamp;
                        return $datac;
                    }
                    if(in_array($sf_status,array(
                        'picked','recd_at_rev_hub','item_manifested','recd_at_fwd_hub','recd_at_fwd_dc','assigned_for_delivery','na','on_hold','pickup_on_hold','bag_received','bag_received_at_via','bag_in_transit','pincode_updated','item_misrouted')))
                    {
                        $datac[0]='14';//in transit
                        $datac[1]=$ist_timestamp;
                        return $datac;
                    }
                    if(in_array($sf_status,array('ofd','reopen_ndr')))
                    {
                        $datac[0]='15';//Out for Delivery
                        $datac[1]=$ist_timestamp;
                        return $datac;
                    }
                    if(in_array($sf_status,array('lost')))
                    {
                        $datac[0]='16';//Lost
                        $datac[1]=$ist_timestamp;
                        return $datac;
                    }
                }
                
                // Fallback for unmapped statuses
                if(isset($status['tracking_details']) && is_array($status['tracking_details']) && count($status['tracking_details']) > 0){
                    $last_status = strtolower($status['tracking_details'][count($status['tracking_details'])-1]['status'] ?? '');
                    if ($last_status == 'delivered') return ['DELIVERED', $ist_timestamp];
                    if ($last_status == 'ofd' || $last_status == 'reopen_ndr') return ['15', $ist_timestamp];
                    if (in_array($last_status, ['bag_received','bag_in_transit'])) return ['14', $ist_timestamp];
                }
                
                
            }else{
                if(isset($status['status_last_updated_at'])){
                    $datetime_utc = new \DateTime($status['status_last_updated_at'], new \DateTimeZone('UTC'));
                    $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                    $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
                } else {
                    $ist_timestamp = date('Y-m-d H:i:s');
                }
                if(isset($status['status']) && in_array($status['status'],array('Cancelled')))
                {   
                    $datac[0]='Canceled';
                    $datac[1]=$ist_timestamp;
                    return $datac;
                }
                if(isset($status['status']) && in_array($status['status'],array('delivered')))
                {
                    $datac[0]='DELIVERED';
                    $datac[1]=$ist_timestamp;
                    return $datac;
                }
                if(isset($status['status']) && in_array($status['status'],array('QC Failed','Return to Seller initiated')))
                {
                    $datac[0]='5';//RTO
                    $datac[1]=$ist_timestamp;
                    return $datac;
                }
                if(isset($status['status']) && in_array($status['status'],array('Returned To Client')))
                {
                    $datac[0]='6';//RTO received
                    $datac[1]=$ist_timestamp;
                    return $datac;
                }
                if(isset($status['status']) && in_array($status['status'],array('New','Assigned','Out For Pickup','On Hold')))
                {
                    $datac[0]='12';//manifested
                    $datac[1]=$ist_timestamp;
                    return $datac;
                }
                if(isset($status['status']) && in_array($status['status'],array('Undelivered','Bag In Transit for Return')))
                {
                    $datac[0]='13';//RTO In Transit
                    $datac[1]=$ist_timestamp;
                    return $datac;
                }
                if(isset($status['status']) && in_array($status['status'],array('Received at RTS destination hub','Pincode Updated','Item Misrouted','Bag Received','Bag Received at Via','Bag In Transit','Item added to Bag','Picked','Received','Received at Return DC','Not Attempted','Returned To Client')))
                {
                    $datac[0]='14';//In Transit
                    $datac[1]=$ist_timestamp;
                    return $datac;
                }
                if(isset($status['status']) && in_array($status['status'],array('Cid','Not Contactable','Return Shipment Out for Delivery')))
                {
                    $datac[0]='15';//Out for Delivery
                    $datac[1]=$ist_timestamp;
                    return $datac;
                }
//                if(isset($status['status']) && in_array($status['status'],array('Undelivered')))
//                {
//                    $datac[0]='16';//Undelivered
//                    $datac[1]=$ist_timestamp;
//                    return $datac;
//                }
            }
        }
        if($ship_courier_id =='9'){
            $status = json_decode($status,true);
//            echo '<pre>';print_R($status);die;
            if(isset($status['payload']) && isset($status['payload']['eventHistory'])){
                $eventcount = count($status['payload']['eventHistory']) -1;
                if($eventcount >0){
                $datetime_utc = new \DateTime($status['payload']['eventHistory'][$eventcount]['eventTime'], new \DateTimeZone('UTC'));
                $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
                $type = $status['payload']['eventHistory'][$eventcount]['shipmentType'];
                if($status['payload']['summary']['status'] =='PickupCancelled'){
                    $datac[0]='Canceled';
                    $datac[1]=$ist_timestamp;
                    return $datac;
                }
                if($status['payload']['summary']['status'] =='PreTransit' || $status['payload']['summary']['status'] == 'ReadyForReceive'){
                    $datac[0]='12';//manifested
                    $datac[1]=$ist_timestamp;
                    return $datac;
                }
                if($status['payload']['summary']['status'] =='InTransit' || $status['payload']['summary']['status'] =='AwaitingCustomerPickup' || $status['payload']['summary']['status'] =='PickupDone' || $status['payload']['summary']['status'] =='ArrivedAtCarrierFacility' || $status['payload']['summary']['status'] =='Departed'){
                    
                    if($type == 'FORWARD'){
                        $datac[0]='14';//in transit
                    }
                    if($type == 'RETURNS'){
                         $datac[0]='13';//RTO In Transit
                    }
                    $datac[1]=$ist_timestamp;
                    return $datac;
                }
                if($status['payload']['summary']['status'] =='Delivered'){
                    if($type == 'FORWARD'){
                        $datac[0]='DELIVERED';
                    }
                    if($type == 'RETURNS'){
                         $datac[0]='6';//rto Received
                    }
                    $datac[1]=$ist_timestamp;
//                    echo $type.'<br>';
                    return $datac;
                }
                if($status['payload']['summary']['status'] =='Lost'){
                    $datac[0]='16';//Lost
                    $datac[1]=$ist_timestamp;
                    return $datac;
                }
                if($status['payload']['summary']['status'] =='ReturnInitiated'){
                    $datac[0]='5';//RTO
                    $datac[1]=$ist_timestamp;
                    return $datac;
                }
                if($status['payload']['summary']['status'] =='OutForDelivery'){
                    if($type =='FORWARD')
                    {
                        $datac[0]='15';//Out for Delivery
                    }else if($type =='RETURNS'){
                        $datac[0]='13';//RTO In Transit
                    }
                    $datac[1]=$ist_timestamp;
                    return $datac;
                }
                if($status['payload']['summary']['status'] =='Rejected' || $status['payload']['summary']['status'] =='Undeliverable' || $status['payload']['summary']['status'] =='DeliveryAttempted'){
                    $datac[0]='10';//NDR
                    $datac[1]=$ist_timestamp;
                    $datac[2]=$status['payload']['summary']['status'];
                    return $datac;
                }
                }
            }
            
            
        }
        if($ship_courier_id =='10'){
            $status = json_decode($status,true);
            if (
                isset($status['message']) &&
                $status['message'] === 'Success' &&
                !empty($status['result'][0]['tracking'][0]['shipmentStatus'])
            ) {
                $shipment = $status['result'][0]['tracking'][0];
                // echo '<pre>';print_R($shipment);die;
                if ($shipment['shipmentStatus'] === 'Delivered') {
                    return [
                        'DELIVERED',
                        $shipment['timestamp'] ?? null
                    ];
                }
                if ($shipment['shipmentStatus'] === 'Cancelled') {
                    return [
                        'Canceled',
                        $shipment['timestamp'] ?? null
                    ];
                }
                if (in_array($shipment['shipmentStatus'],array('Awb Generated','Out for Pickup','Pickup Failed','To be Picked','Packed'))) {
                    return [
                        '12',//manifested
                        $shipment['timestamp'] ?? null
                    ];
                }
                if ($shipment['shipmentStatus'] === 'Damaged') {
                    return [
                        '17',//damaged
                        $shipment['timestamp'] ?? null
                    ];
                }
                if (in_array($shipment['shipmentStatus'] ,array('In-Transit','Delivery Delay','Picked-up','Reached Destination City','Reached Nearest Hub','Picked from Rack'))) {
                    return [
                        '14',//in transit
                        $shipment['timestamp'] ?? null
                    ];
                }
                if (in_array($shipment['shipmentStatus'],array('Failed Delivered','NDR Initiated'))) {
                    return [
                        '10',//NDR
                        $shipment['timestamp'] ?? null,
                        $shipment['remarks'] ?? ''
                    ];
                }
                if ($shipment['shipmentStatus'] === 'Lost') {
                    return [
                        '16',//Lost
                        $shipment['timestamp'] ?? null,
                    ];
                }
                if ($shipment['shipmentStatus'] === 'Out for Delivery') {
                    return [
                        '15',//Out for Delivery
                        $shipment['timestamp'] ?? null,
                    ];
                }
                if (in_array($shipment['shipmentStatus'] ,array('RTO Delivered'))) {
                    return [
                        '6',//RTO Delivered
                        $shipment['timestamp'] ?? null
                    ];
                }
                if (in_array($shipment['shipmentStatus'] ,array('RTO Delivery Delay','RTO Failed Delivered','RTO Initiated','RTO In-Transit','RTO Out for Delivery'))) {
                    return [
                        '13',//RTO In Transit
                        $shipment['timestamp'] ?? null
                    ];
                }
            }
                


            
        }
        if($ship_courier_id =='11'){
            $status = json_decode($status,true);
           
            if (
                isset($status['status']) && $status['status'] ==200
            ) {
                $shipment = $status['data'];
                if(isset($shipment['orderStateInfo'])){
                    $cnt = count($shipment['orderStateInfo']);
                    $m_data =$shipment['orderStateInfo'][$cnt-1];
                    if ($m_data['state'] === 'DELIVERED') {
                        return [
                            'DELIVERED',
                            $m_data['createdAt'] ?? null
                        ];
                    }
                    if ($m_data['state'] === 'CANCELED') {
                        return [
                            'Canceled',
                            $m_data['createdAt'] ?? null
                        ];
                    }
                    if (in_array($m_data['state'],array('READY_FOR_DISPATCH','OUT_FOR_PICKUP','NOT_PICKED_UP'))) {
                        return [
                            '12',//manifest
                            $m_data['createdAt'] ?? null
                        ];
                    }
                    if (in_array($m_data['state'],array('PICKED_UP','IN_TRANSIT'))) {
                        return [
                            '14',//in trait
                            $m_data['createdAt'] ?? null
                        ];
                    }
                    if (in_array($m_data['state'],array('UNDELIVERED'))) {
                        return [
                            '10',//NDR
                            $m_data['createdAt'] ?? null
                        ];
                    }
                    if (in_array($m_data['state'],array('OUT_FOR_DELIVERY'))) {
                        return [
                             '15',//Out for Delivery
                            $m_data['createdAt'] ?? null
                        ];
                    }
                    if (in_array($m_data['state'],array('RTO_DELIVERED'))) {
                        return [
                             '6',//RTO Delivered
                            $m_data['createdAt'] ?? null
                        ];
                    }
                    if (in_array($m_data['state'],array('RTO_IN_TRANSIT','RTO_OUT_FOR_DELIVERY'))) {
                        return [
                             '13',//RTO In Transit
                            $m_data['createdAt'] ?? null
                        ];
                    }
                     if (in_array($m_data['state'],array('RTO'))) {
                    return [
                        '5',//RTO In Transit
                         $m_data['createdAt'] ?? null
                    ];
                }
                }
            }
                


            
        }
        if($ship_courier_id =='12'){
            $status = json_decode($status,true);
//            echo '<pre>';print_R($status);die;
            if (
                isset($status['Control']) && $status['Control']['Status'] ==1
            ) {
                $shipment = $status['Data'][0];
                if ($shipment['short_code'] === 'DLD') {
                    return [
                        'DELIVERED',
                        $shipment['reported_on'] ?? null
                    ];
                }
                if ($shipment['short_code'] === 'PCN') {
                    return [
                        'Canceled',
                        $shipment['reported_on'] ?? null
                    ];
                }
                if (in_array($shipment['short_code'],array('NEW','RAP','ARP','ARV','RAD','ARD','OFP'))) {
                    return [
                        '12',//manifested
                        $shipment['reported_on'] ?? null
                    ];
                }
                if ($shipment['shipmentStatus'] === 'Damaged') {
                    return [
                        '17',//damaged
                        $shipment['reported_on'] ?? null
                    ];
                }
                if (in_array($shipment['shipmentStatus'] ,array('PCK','DTH','RAH','ITR','RDC','MIS','RCH','CBD','MIS','ODA'))) {
                    return [
                        '14',//in transit
                        $shipment['timestamp'] ?? null
                    ];
                }
                if (in_array($shipment['shipmentStatus'],array('PEN','WRG'))) {
                    return [
                        '10',//NDR
                        $shipment['reported_on'] ?? null,
                        $shipment['reason'] ?? ''
                    ];
                }
                if (in_array($shipment['shipmentStatus'],array('CAN'))) {
                    return [
                        'Canceled',//canceled
                        $shipment['reported_on'] ?? null,
                        $shipment['reason'] ?? ''
                    ];
                }
                if ($shipment['shipmentStatus'] === 'Lost') {
                    return [
                        '16',//Lost
                        $shipment['reported_on'] ?? null,
                    ];
                }
                if ($shipment['short_code'] === 'OFD') {
                    return [
                        '15',//Out for Delivery
                        $shipment['reported_on'] ?? null,
                    ];
                }
                if (in_array($shipment['shipmentStatus'] ,array('RTO Delivered'))) {
                    return [
                        '6',//RTO Delivered
                        $shipment['reported_on'] ?? null
                    ];
                }
                if (in_array($shipment['shipmentStatus'] ,array('CDD','CBH','CIH','CRD','CFD','RTU'))) {
                    return [
                        '13',//RTO In Transit
                        $shipment['reported_on'] ?? null
                    ];
                }
                if (in_array($shipment['shipmentStatus'] ,array('RTO'))) {
                    return [
                        '5',//RTO In Transit
                        $shipment['timestamp'] ?? null
                    ];
                }
            }
                


            
        }
        if($ship_courier_id =='13'){
            $res = json_decode($status, true);
            if(isset($res['success']) && $res['success'] && isset($res['data'])){
                $track = $res['data'];
                $currentStatus = strtoupper($track['current_status'] ?? '');
                $description = $track['description'] ?? '';
                
                $eventDate = date('Y-m-d H:i:s');
                if (isset($track['timeline']) && is_array($track['timeline']) && count($track['timeline']) > 0) {
                    $latest_event = end($track['timeline']);
                    if (isset($latest_event['timestamp'])) {
                        $eventDate = date('Y-m-d H:i:s', strtotime($latest_event['timestamp']));
                    }
                }
                
                if ($currentStatus == 'DELIVERED') {
                    $datac[0] = 'DELIVERED';
                } elseif ($currentStatus == 'CANCELLED') {
                    $datac[0] = 'Canceled';
                } elseif ($currentStatus == 'RETURNED') {
                    $datac[0] = '6'; // RTO Received
                } elseif ($currentStatus == 'FAILED') {
                    $datac[0] = '10'; // NDR
                    $datac[2] = $description;
                } elseif ($currentStatus == 'OUT_FOR_DELIVERY') {
                    $datac[0] = '15'; // Out for Delivery
                } elseif (in_array($currentStatus, ['PICKED_UP', 'IN_TRANSIT'])) {
                    $datac[0] = '14'; // In Transit
                } else {
                    $datac[0] = '12'; // Manifested
                }
                $datac[1] = $eventDate;
                
                $order_info = DB::table('orders')->where('tracking_info', $tracking_info)->first();
                if ($order_info && $order_info->created_at) {
                    $datac[3] = date('Y-m-d', strtotime('+4 days', strtotime($order_info->created_at)));
                } else {
                    $datac[3] = date('Y-m-d', strtotime('+4 days'));
                }
                return $datac;
            }
        }
        if($ship_courier_id =='14'){
            $res = json_decode($status, true);
            if(isset($res['success']) && $res['success'] && isset($res['data'][0])){
                $track_data = $res['data'][0];
                $booking = $track_data['booking_details'] ?? [];
                $details = $track_data['tracking_details'] ?? [];
                
                $latest_event = isset($details[0]) ? $details[0] : [];
                $event_name = strtoupper($latest_event['event'] ?? '');
                $description = $latest_event['remarks'] ?? '';
                
                $eventDate = date('Y-m-d H:i:s');
                if (isset($latest_event['date'])) {
                    $eventDate = date('Y-m-d H:i:s', strtotime($latest_event['date']));
                }
                
                if (str_contains($event_name, 'DELIVERED') || str_contains($event_name, 'DELIVERY CONFIRMED') || !empty($booking['delivery_confirmed_on'])) {
                    $datac[0] = 'DELIVERED';
                    if (!empty($booking['delivery_confirmed_on'])) {
                        $eventDate = date('Y-m-d H:i:s', strtotime($booking['delivery_confirmed_on']));
                    }
                } elseif (str_contains($event_name, 'RETURN') || str_contains($event_name, 'RTS') || str_contains($event_name, 'RETURNED TO SENDER')) {
                    $datac[0] = '6'; // RTO Received
                } elseif (str_contains($event_name, 'UNDELIVERED') || str_contains($event_name, 'FAILED') || str_contains($event_name, 'ATTEMPT FAILED')) {
                    $datac[0] = '10'; // NDR
                    $datac[2] = $description ?: $latest_event['event'] ?? 'Delivery failed';
                } elseif (str_contains($event_name, 'TAKEN OUT FOR DELIVERY') || str_contains($event_name, 'OUT FOR DELIVERY') || str_contains($event_name, 'OUT_FOR_DELIVERY')) {
                    $datac[0] = '15'; // Out for Delivery
                } elseif (str_contains($event_name, 'DISPATCHED') || str_contains($event_name, 'RECEIVED') || str_contains($event_name, 'BAGGED') || str_contains($event_name, 'TRANSIT')) {
                    $datac[0] = '14'; // In Transit
                } else {
                    $datac[0] = '12'; // Manifested
                }
                $datac[1] = $eventDate;
                
                $order_info = DB::table('orders')->where('tracking_info', $tracking_info)->first();
                if ($order_info && $order_info->created_at) {
                    $datac[3] = date('Y-m-d', strtotime('+4 days', strtotime($order_info->created_at)));
                } else {
                    $datac[3] = date('Y-m-d', strtotime('+4 days'));
                }
                return $datac;
            }
        }
        $datac[0]='other';
        $datac[1]=date('Y-m-d');
        return $datac;
    }

    public static function orderstatuslog($order_id,$company_id,$old_status,$new_status,$chnge_time,$ordercode=null){
        if($old_status != $new_status || $new_status =='NDR'){
            $orderlog = new OrderLog();
            $orderlog->order_id = $order_id;
            $orderlog->old_value = $old_status;
            $orderlog->new_value = $new_status;
            $orderlog->value_type = 'status';
            $orderlog->ordercode = $ordercode;
            $orderlog->created_at = $chnge_time;
            $orderlog->company_id = $company_id;
            $orderlog->save();
        }
    }

    public static function getStatusname($value)
    { //change this on ordr model
        return [
        '1' => '<span class="badge text-white bg-secondary">New</span>',
        '2' => '<span class="badge text-white bg-dark">Shipped</span>',
        '3' => '<span class="badge text-white bg-success">Delivered</span>',
        '4' => '<span class="badge text-white bg-danger">Canceled</span>',
        '5' => '<span class="badge text-white bg-primary">RTO</span>',
        '6' => '<span class="badge text-white bg-primary">RTO Received</span>',
        // '7' => '<span class="badge text-white bg-primary">On Hold</span>',
        // '8' => '<span class="badge text-white bg-warning">Fulfillment</span>',
        // '9' => '<span class="badge text-white bg-warning">Refund</span>',
        '10' => '<span class="badge text-white bg-warning">NDR</span>',
        // '11' => '<span class="badge text-white bg-dark">Courier Assigned</span>',
        '12' => '<span class="badge text-white bg-danger">Pickup Pending</span>',//manifested
        '13' => '<span class="badge text-white bg-success">RTO In Transit</span>',
        '14' => '<span class="badge text-white bg-success">In Transit</span>',
        '15' => '<span class="badge text-white bg-success">Out for Delivery</span>',
        '16' => '<span class="badge text-white bg-danger">Lost</span>',
        '17' => '<span class="badge text-white bg-danger">Damaged</span>',

        ][$value];
    }
    
    public static function updatestatusorder($order_id){
        $order = Order::find($order_id);
//        echo $order;die;
        echo  $order->id.'<br>';
        if($order->reverse_order =='1'){
            $type ='backward';
        }else{
            $type ='forward';
        }
//        $order->tracking_info ='10383177935';
        echo $order->tracking_info.'<br>';
        $get_status = Status::gettrack($order->ship_courier_id,$order->tracking_info,$type);
//        $get_status[0]='Canceled';
       echo '<pre>';print_R($get_status);
        Status::updatestausindb($order_id,$get_status,'1');
    }

    public static function updatestausindb($order_id,$get_status,$print='0'){
        $order = Order::find($order_id);
        if (!$order || empty($get_status) || !is_array($get_status)) {
            return;
        }

        // Prevent recycled AWB updates: If the event date from the courier is before the order was created, ignore it.
        if (isset($get_status[1])) {
            $eventTimestamp = strtotime($get_status[1]);
            $orderCreatedTimestamp = strtotime($order->created_at);
            if ($eventTimestamp && $orderCreatedTimestamp && $eventTimestamp < $orderCreatedTimestamp - 86400) {
                // Update chk_date so we don't keep querying the API too frequently, but don't change the status
                $order->chk_date = date('Y-m-d H:i:s', strtotime(now() . ' +2 hours'));
                $order->save();
                echo "Skipping update: Courier event date ({$get_status[1]}) is before order creation date ({$order->created_at}) for Order #{$order_id} (Recycled AWB)<br>";
                return;
            }
        }

        $i=0;$progress=array();
        $old_status = strip_tags($order->status);
        if($order){
            if($order->reverse_order =='1'){
                $type ='backward';
            }else{
                $type ='forward';
            }
            $get_statusprogress = Status::getcourierstatuslogs($order->ship_courier_id,$order->tracking_info,$type);
            if($order->ship_courier_id =='1'){
//                echo $get_statusprogress;die;
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
                    $progress['action'] = $stageArray['status'];
                    $progress['place'] = $stageArray['location_city'];
                    $progress['remarks'] = $stageArray['scan_status'];
                    $dateString = rtrim($stageArray['updated_on']);
                    $date = \DateTime::createFromFormat('d M, Y, H:i', $dateString);
                    if ($date) {
                        $progress['date'] = $date->format('Y-m-d H:i:s');
                    }else{
                        $progress['date'] ='';
                    }
                    break;
                }
            }
            if($order->ship_courier_id =='2'){
                $status_progressarray = json_decode($get_statusprogress,true);
                if(isset($status_progressarray['ShipmentData']) && isset($status_progressarray['ShipmentData'][0]['Shipment']['Scans']))
                $scans = $status_progressarray['ShipmentData'][0]['Shipment']['Scans'];
                for ($j = count($scans) - 1; $j >= 0; $j--) {
                    $history = $scans[$j];
                    if (isset($history['ScanDetail'])) {
                        $hs = $history['ScanDetail'];
                        $timestamp = $hs['StatusDateTime'];
                        $progress['action'] = $hs['Scan'] . '-' . $hs['ScanType'];
                        $progress['place'] = $hs['ScannedLocation'];
                        $progress['remarks'] = $hs['Instructions'];
                        $progress['date'] = date('Y-m-d H:i:s', strtotime($timestamp));
                        $i++;
                        break;
                    }
                }
            } 
           if($order->ship_courier_id =='3'){
                $status_progressarray = json_decode($get_statusprogress, true);
                if (!is_array($status_progressarray)) {
                    // No valid response — skip
                } elseif (isset($status_progressarray['ShipmentData']['Shipment'][0]['Scans'])) {
                    // Native APIGEE tracking response
                    $scans = $status_progressarray['ShipmentData']['Shipment'][0]['Scans'];
                    for ($j = count($scans) - 1; $j >= 0; $j--) {
                        if (isset($scans[$j]['ScanDetail'])) {
                            $hs = $scans[$j]['ScanDetail'];
                            $progress['action']  = ($hs['Scan'] ?? '') . '-' . ($hs['ScanType'] ?? '');
                            $progress['place']   = $hs['ScannedLocation'] ?? '';
                            $progress['remarks'] = $hs['Instructions'] ?? '';
                            $progress['date']    = isset($hs['StatusDateTime']) ? date('Y-m-d H:i:s', strtotime($hs['StatusDateTime'])) : '';
                            break;
                        }
                    }
                } elseif (!empty($status_progressarray['status']) && isset($status_progressarray['data'])) {
                    // Legacy ParcelX format fallback
                    $scans = $status_progressarray['data'];
                    if (count($scans) > 0) {
                        $last = count($scans) - 1;
                        $progress['action']  = $scans[$last]['status_title'] ?? '';
                        $progress['place']   = $scans[$last]['status_location'] ?? '';
                        $progress['remarks'] = $scans[$last]['status_description'] ?? '';
                        $progress['date']    = $scans[$last]['event_date'] ?? '';
                    }
                }
            } 
            if($order->ship_courier_id =='4'){
                $status_progressarray = json_decode($get_statusprogress,true);
                if(isset($status_progressarray['ShipmentLogDetails']) && count($status_progressarray['ShipmentLogDetails'])>0){
                    $scans =$status_progressarray['ShipmentLogDetails'];
                    if(count($scans)>0){
                        $last =0;
                        $progress['action'] = $scans[$last]['Process'];
                        $progress['place'] = $scans[$last]['City'];
                        $progress['remarks'] = $scans[$last]['Description'];
                        $progress['date'] = date('Y-m-d H:i:s',strtotime($scans[$last]['ShipmentStatusDateTime']));
                    }
                }
            }
            if($order->ship_courier_id =='5'){
                $status_progressarray = json_decode($get_statusprogress,true);
                if(isset($status_progressarray['statusCode']) && $status_progressarray['statusCode'] == '200'){
                    if(isset($status_progressarray['trackDetails']) && count($status_progressarray['trackDetails']) >0){
                        $scans =$status_progressarray['trackDetails'];
                        if(count($scans)>0){
                            $last =count($scans)-1;
                            $x =$scans[$last]['strActionDate'];
                            $y =$scans[$last]['strActionTime'];
                            $progress['action'] = $scans[$last]['strAction'];
                            $progress['place'] = $scans[$last]['strOrigin'].'-'.$scans[$last]['strDestination'];
                            $progress['remarks'] = $scans[$last]['sTrRemarks'];
                            $progress['date'] = substr($x, 4, 4).'-'.substr($x, 2, 2).'-'.substr($x, 0, 2).' '.substr($y, 0, 2).':'.substr($y, 2, 2).':00';
                            $i++;
                        }
                    }
                }
            }
            if($order->ship_courier_id =='6'){
                $status_progressarray = json_decode($get_statusprogress,true);
                if($status_progressarray['success'] ==1 && isset($status_progressarray['data']) && isset($status_progressarray['data'][0]) && isset($status_progressarray['data'][0]['shipmentStatus'])){
                    $history =$status_progressarray['data'][0]['shipmentStatus'];
                    $j=0;
                    if(isset($history[$j])){
                        $progress['action'] = $history[$j]['statusDescription'];
                        $progress['place'] = $history[$j]['city'].'-'.$history[$j]['state'];
                        $progress['remarks'] = $history[$j]['remarks'];
                        $progress['date'] = str_replace('T',' ',$history[$j]['eventDate']);
                    }
                }
            }
            if($order->ship_courier_id =='7'){
                $status_progressarray = json_decode($get_statusprogress,true);
                $tracking_info= $order->tracking_info;
                if(isset($status_progressarray[$tracking_info]) && isset($status_progressarray[$tracking_info]['history'])){
                    $history =$status_progressarray[$tracking_info]['history'];
                    $j=0;
                    if(isset($history[$j])){
                        $date = explode('+',$history[$j]['event_date']);
                        $progress['action'] = $history[$j]['public_description'];
                        $progress['place'] = $history[$j]['city'];
                        $progress['remarks'] = $history[$j]['status'];
                        $progress['date'] = str_replace('T',' ',$date[0]);
                        $i++;
                    }
                }
            }
            if($order->ship_courier_id =='8'){
                $status_progressarray = json_decode($get_statusprogress,true);
                if($type =='forward'){
                    if(isset($status_progressarray['tracking_details'])){
                        $scans = $status_progressarray['tracking_details'];
                        for ($j = count($scans) - 1; $j >= 0; $j--) {
                            $history = $scans[$j];
                            $datetime_utc = new \DateTime($history['created'], new \DateTimeZone('UTC'));
                            $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                            $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
                            $progress['action'] = $history['status'];
                            $progress['place'] = $history['location'];
                            $progress['remarks'] = $history['remarks'];
                            $progress['date'] = $ist_timestamp;
                            $i++;
                            
                        }
                    }
                }else{
                    if(isset($status_progressarray['pickup_request_state_histories'])){
                        $scans = $status_progressarray['pickup_request_state_histories'];
                        for ($j = count($scans) - 1; $j >= 0; $j--) {
                            $history = $scans[$j];
                            $datetime_utc = new \DateTime($history['created_at'], new \DateTimeZone('UTC'));
                            $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                            $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
                            $progress['action'] = $history['state'];
                            $progress['place'] = $history['current_location'];
                            $progress['remarks'] = $history['comment'];
                            $progress['date'] = $ist_timestamp;
                            $i++;
                            break;
                        }
                    }
                }
            }  
            if($order->ship_courier_id =='9'){
                $status_progressarray = json_decode($get_statusprogress,true);
                if(isset($status_progressarray['payload']) && isset($status_progressarray['payload']['eventHistory'])){
                    $scans = $status_progressarray['payload']['eventHistory'];
                    for ($j = count($scans) - 1; $j >= 0; $j--) {
                        $history = $scans[$j];
                    
                        $datetime_utc = new \DateTime($history['eventTime'], new \DateTimeZone('UTC'));
                        $datetime_utc->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                        $ist_timestamp = $datetime_utc->format('Y-m-d H:i:s');
                        $progress['action'] = $history['eventCode'].'-'.$history['shipmentType'];
                        $progress['place'] = $history['location']['city'] ?? '';
                        $progress['remarks'] = '';
                        $progress['date'] = $ist_timestamp;
                        $i++;
                        break;
                    }
                }
            }
        }
        Status::getcourierdata($order_id,$progress);
        if($get_status[0] =='DELIVERED'){
            $orderd = Order::where('id', $order->id)->first();
            $orderd->status = '3';
//            $orderd->chk_ats = '1';
            $orderd->delivered_date = $get_status[1];
            if ((int)($orderd->getRawOriginal('total_attempt') ?? 0) < 1) {
                $orderd->total_attempt = 1;
            }
            $orderd->chk_date = date('Y-m-d H:i:s', strtotime(now() . ' +1 hours'));
            if($orderd->picked_date ==''){
                if($orderd->manifest_date !=''){
                    $orderd->picked_date = date('Y-m-d', strtotime('+1 day', strtotime($orderd->manifest_date)));
                }else{
                    if($orderd->shipped_date != ''){
                        $orderd->picked_date = date('Y-m-d', strtotime('+1 day', strtotime($orderd->shipped_date)));
                    }
                }
                if(date('Y-m-d', strtotime($orderd->picked_date)) >= date('Y-m-d', strtotime($orderd->delivered_date))){
                    $orderd->picked_date = date('Y-m-d', strtotime($orderd->delivered_date));
                }
            }
            if(isset($get_status[3]) && $get_status[3] !=''){
                $orderd->expected_delivery_date = $get_status[3];
            }
            $orderd->save();
            Status::orderstatuslog($order->id,$order->company_id,$old_status,'Delivered',$get_status[1]);
        }
        elseif($get_status[0] =='5' || $get_status[0] =='6' || $get_status[0] =='10' || $get_status[0] =='12' || $get_status[0] =='13'  || $get_status[0] =='14'  || $get_status[0] =='15'  || $get_status[0] =='16'  || $get_status[0] =='17'){
            $orderd = Order::where('id', $order->id)->first();
            $chk_intial_time = $orderd->status_date;
            $orderd->status = $get_status[0];
            $orderd->status_date = $get_status[1];
            $orderd->chk_date = date('Y-m-d H:i:s', strtotime(now() . ' +1 hours'));
            if($orderd->picked_date ==''){
                $orderd->picked_date = date('Y-m-d', strtotime($get_status[1]));
            }
            
            
            $getuser = DB::table('orders')
            ->select('orders.user_id as user','ratecard_type','wallet_blc')
            ->join('admins', 'admins.id', '=', 'orders.user_id')
            ->where('orders.id','=',$order->id)
            ->first();
            $orde_user_id  = $getuser->user;
            $parent_userid = Admin::find($orde_user_id)->parent_id;
            if($orderd->rtocharge_applied =='0' && in_array($get_status[0],array('5','6','13'))){
                $orderd->rtocharge_applied ='1';
                $orderd->rto_date =now();
                if($getuser->ratecard_type !='flat'){
                    $orderd->rto_charge =$orderd->freight+$orderd->gst_freight;//with gst
                    $orderd->rto_charge_gst =$orderd->gst_freight;//gst
                    $orderd->rto_charge_witoutgst =$orderd->freight;//without gst
                    
                    $orderd->rto_chargeparent =$orderd->freightparent+$orderd->gst_freightparent;//with gst
                    $orderd->rto_charge_gstparent =$orderd->gst_freightparent;//gst
                    $orderd->rto_charge_witoutgstparent =$orderd->freightparent;//without gst

                    $transaction = new Transaction();
                    $transaction->order_id = $orderd->id;
                    $transaction->user_id = $getuser->user;
                    $transaction->awb = $orderd->tracking_info;
                    $transaction->tracking_info = $orderd->tracking_info;
                    $transaction->credit = '0.00';
                    $transaction->debit = $orderd->freight+$orderd->gst_freight;
                    $transaction->closing_blc = $getuser->wallet_blc - ($orderd->freight+$orderd->gst_freight);
                    $transaction->remarks = "Amount Debit for RTO";
                    $transaction->save();

                    $transactionparent = new Transaction();
                    $transactionparent->order_id = $orderd->id;
                    $transactionparent->user_id = $parent_userid;
                    $transactionparent->awb = $orderd->tracking_info;
                    $transactionparent->tracking_info = $orderd->tracking_info;
                    $transactionparent->credit = '0.00';
                    $transactionparent->debit = $orderd->freightparent+$orderd->gst_freightparent;
                    $transactionparent->remarks = "Amount Debit for RTO";
                    $transactionparent->parent_data = '1';
                    $transactionparent->save();

                    $balance = Admin::where('id', $getuser->user) 
                    ->first();
                    $balance->wallet_blc = $balance->wallet_blc - ($orderd->freight+$orderd->gst_freight);
                    $balance->save();
                    
                    
                    if($orderd->cod !='0'){
                        $orderd->codrefunded ='1';
                        $balancenew = Admin::where('id', $getuser->user)->first();
                    
                        $transaction = new Transaction();
                        $transaction->order_id = $orderd->id;
                        $transaction->user_id = $getuser->user;
                        $transaction->awb = $orderd->tracking_info;
                        $transaction->tracking_info = $orderd->tracking_info;
                        $transaction->credit = ($orderd->cod + $orderd->gst_cod);
                        $transaction->debit = '0.00';
                        $transaction->closing_blc = $balancenew->wallet_blc + ($orderd->cod + $orderd->gst_cod);
                        $transaction->remarks = "COD Charge Refunded";
                        $transaction->save();
                        
                        $balancenew->wallet_blc = $balancenew->wallet_blc + ($orderd->cod + $orderd->gst_cod);
                        $balancenew->save();
                        
                        $transactionparent = new Transaction();
                        $transactionparent->order_id = $orderd->id;
                        $transactionparent->user_id = $parent_userid;
                        $transactionparent->awb = $orderd->tracking_info;
                        $transactionparent->tracking_info = $orderd->tracking_info;
                        $transactionparent->credit = ($orderd->codparent + $orderd->gst_codparent);
                        $transactionparent->debit = '0.00';
                        $transactionparent->remarks = "COD Charge Refunded";
                        $transactionparent->parent_data = '1';
                        $transactionparent->save();
                    }
                    
                }
            }
                if(in_array($get_status[0],array('5','6','13')) && in_array(strip_tags($orderd->extra_weight_status), array('Closed in Seller favor','Auto Accepted')) && $orderd->extra_weght_rto_deduct =='0'){
                    $transaction = new Transaction();
                    $transaction->order_id = $orderd->id;
                    $transaction->user_id = $getuser->user;
                    $transaction->awb = $orderd->tracking_info;
                    $transaction->tracking_info = $orderd->tracking_info;
                    $transaction->credit = '0.00';
                    $transaction->debit = $orderd->extra_weight_cost;
                    $transaction->closing_blc = $getuser->wallet_blc - $orderd->extra_weight_cost;
                    $transaction->remarks = "Amount Debit for extra weight - RTO";
                    $transaction->save();
                    
                    if($orderd->extra_weight_costparent ==''){
                        $orderd->extra_weight_costparent ='0.00';
                    }
                    $transactionparent = new Transaction();
                    $transactionparent->order_id = $orderd->id;
                    $transactionparent->user_id = $parent_userid;
                    $transactionparent->awb = $orderd->tracking_info;
                    $transactionparent->tracking_info = $orderd->tracking_info;
                    $transactionparent->credit = '0.00';
                    $transactionparent->debit = $orderd->extra_weight_costparent;
                    $transactionparent->remarks = "Amount Debit for extra weight - RTO";
                    $transactionparent->parent_data = '1';
                    $transactionparent->save();
                    
                    $balance = Admin::where('id', $getuser->user) 
                    ->first();
                    $balance->wallet_blc = $balance->wallet_blc - $orderd->extra_weight_cost;
                    $balance->save();

                    $orderd->extra_weght_rto_deduct ='1';
                    
                }
//                echo $chk_intial_time .'!='.$get_status[1].'===>'.$get_status[0].'========>'.$orde_user_id;die;
            if($get_status[0] == '15' && $chk_intial_time !=$get_status[1]){
                $orderd->total_attempt = ($orderd->total_attempt ?? 0) + 1;
//                echo $orde_user_id;die;
                if($orde_user_id =='203'){
                 $trackingInfo = $orderd->tracking_info;
                 $recipientEmail = Admin::find($orde_user_id)->email;
                 $s_name = Admin::find($orde_user_id)->name;
                    $subject = "Order Out for Delivery - AWB: {$trackingInfo}";
                    $cc='ritesha412@gmail.com';
                    $message = "
                        <p>Dear {$s_name},</p>
                        <p>Your order with Order ID: {$order->id} and AWB Number: {$trackingInfo} is now out for delivery.</p>
                        <p>If you need further assistance with your order,please contact sales team.</p>
                        <p>We hope to see you again!</p>
                        <p>Best regards,</p>
                    ";

                    Mail::to($recipientEmail)
                    ->cc([$cc])
                    ->send(new EmailVerify($subject, $message));

                }else{
//                    $trackingInfo = $orderd->tracking_info;
//                 $recipientEmail = 'ankushmadan48@gmail.com';
//                 $s_name = Admin::find($orde_user_id)->name;
//                    $subject = "Order Out for Delivery - AWB: {$trackingInfo}";
//                    $cc='ritesh';
//                    $message = "
//                        <p>Dear {$s_name},</p>
//                        <p>Your order with Order ID: {$order->id} and AWB Number: {$trackingInfo} is now out for delivery.</p>
//                        <p>If you need further assistance with your order,please contact sales team.</p>
//                        <p>We hope to see you again!</p>
//                        <p>Best regards,</p>
//                    ";
//
//                    Mail::to($recipientEmail)
//                    ->cc([$cc])
//                    ->send(new EmailVerify($subject, $message));
                }
            }
            if(isset($get_status[3]) && $get_status[3] !=''){
                $orderd->expected_delivery_date = $get_status[3];
            }
            if($get_status[0]=='6'){
                 $orderd->rto_received_date=$get_status[1];
            }
            $orderd->save();
            $new_status =strip_tags(Status::getStatusname($get_status[0]));
            if(isset($get_status[2])){
                $statuscode =$get_status[2];
            }else{
                $statuscode = null;
            }
            if($print =='1'){
                echo $chk_intial_time.'!='.$get_status[1].'<br>';
            }
            if($chk_intial_time !=$get_status[1]){
                Status::orderstatuslog($order->id,$order->company_id,$old_status,$new_status,$get_status[1],$statuscode);
            }
        }
        elseif($get_status[0] =='Canceled'){
            $orderd = Order::where('id', $order->id)->first();
            if ($orderd->status != '4') { // Only log if status is actually changing
                $orderd->status = '4';
                $orderd->cancel_date = $get_status[1] ?? now();
                Status::orderstatuslog($order->id, $order->company_id, $old_status, 'Canceled', $orderd->cancel_date);
                
                // --- Automatic Refund Logic ---
                $alreadyRefunded = \App\Models\Admin\Transaction::where('order_id', $orderd->id)
                    ->where('remarks', 'like', '%Amount Credit for cancel%')->exists();
                    
                if (!$alreadyRefunded && in_array($old_status, array('New', '1', 'Shipped', '2', 'Pickup Pending', '12'))) { 
                    $money_reversal = $orderd->shipping_courier_cost;
                    $money_reversalparent = $orderd->shipping_courier_costparent;
                    $orde_user_id = $orderd->user_id;
                    $parent_userid = \App\Models\Admin\Admin::find($orde_user_id)->parent_id;
                    $current_company_id = $orderd->company_id;
                    $balance = \App\Models\Admin\Admin::find($orde_user_id);
                    
                    $canceller_name = 'System (Courier API)';

                    if ($money_reversal > 0 && $balance) {
                        $transaction = new \App\Models\Admin\Transaction();
                        $transaction->order_id = $orderd->id;
                        $transaction->user_id = $orde_user_id;
                        $transaction->company_id = $current_company_id;
                        $transaction->awb = $orderd->tracking_info;
                        $transaction->tracking_info = $orderd->tracking_info;
                        $transaction->credit = $money_reversal;
                        $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                        $transaction->debit = '0.00';
                        $transaction->show_data = '0';
                        $transaction->remarks = "Amount Credit for cancel by " . $canceller_name;
                        $transaction->save();
                        
                        $transactionparent = new \App\Models\Admin\Transaction();
                        $transactionparent->order_id = $orderd->id;
                        $transactionparent->user_id = $parent_userid;
                        $transactionparent->company_id = $current_company_id;
                        $transactionparent->awb = $orderd->tracking_info;
                        $transactionparent->tracking_info = $orderd->tracking_info;
                        $transactionparent->credit = $money_reversalparent;
                        $transactionparent->debit = '0.00';
                        $transactionparent->show_data = '0';
                        $transactionparent->remarks = "Amount Credit for cancel by " . $canceller_name;
                        $transactionparent->parent_data = '1';
                        $transactionparent->save();
                        
                        $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                        $balance->save();
                    }
                }
                // ------------------------------
            }
            $orderd->chk_date = date('Y-m-d H:i:s', strtotime(now() . ' +15 minutes'));
            $orderd->save();
        }
        else{
            $orderd = Order::where('id', $order->id)->first();
            // $orderd->chk_date = date('Y-m-d H:i:s', strtotime(now() . '+1 hours'));
            $orderd->chk_date = date('Y-m-d H:i:s', strtotime(now() . '+15 minutes'));
            $orderd->save();
        }
    }
    
    public static function updateorderattempts($order_id){
        $order = Order::find($order_id);
        if (!$order) return;

        $total_attempts = 0;
        if($order->reverse_order == '1'){
            $type = 'backward';
        } else {
            $type = 'forward';
        }

        $raw_status = Status::getcourierstatuslogs($order->ship_courier_id, $order->tracking_info, $type);
        if (empty($raw_status)) {
            return;
        }

        if ($order->ship_courier_id == '1') {
            $xml = @simplexml_load_string(str_replace('&', '&amp;', $raw_status));
            if ($xml) {
                $json = json_encode($xml);
                $arrayd = json_decode($json, TRUE);
                if (isset($arrayd['object'])) {
                    $objects = isset($arrayd['object'][0]) ? $arrayd['object'] : [$arrayd['object']];
                    foreach ($objects as $obj) {
                        if (isset($obj['field']) && isset($obj['field'][14])) {
                            if (in_array($obj['field'][14], array('35006','35004','35003','35002','35001','350','20003','22703','31501','20002','20001','421','401','415','006','82','83'))) {
                                $total_attempts++;
                            }
                        }
                    }
                }
            }
        } else {
            $status = is_array($raw_status) ? $raw_status : json_decode($raw_status, true);

            if ($order->ship_courier_id == '2') {
                if (isset($status['ShipmentData']) && isset($status['ShipmentData'][0]) && isset($status['ShipmentData'][0]['Shipment']) && isset($status['ShipmentData'][0]['Shipment']['Scans'])) {
                    foreach ($status['ShipmentData'][0]['Shipment']['Scans'] as $history) {
                        if (isset($history['ScanDetail'])) {
                            $inst = $history['ScanDetail']['Instructions'] ?? '';
                            $scan = $history['ScanDetail']['Scan'] ?? '';
                            $scanType = $history['ScanDetail']['ScanType'] ?? '';
                            if (stripos($inst, 'Out for delivery') !== false || stripos($scan, 'Out for delivery') !== false || strcasecmp($scanType, 'OFD') == 0 || strcasecmp($inst, 'OFD') == 0) {
                                $total_attempts++;
                            }
                        }
                    }
                }
            } elseif ($order->ship_courier_id == '3') {
                if (isset($status[0]) && isset($status[0]['order_history'])) {
                    foreach ($status[0]['order_history'] as $history) {
                        $stVal = $history['status_value'] ?? '';
                        if (stripos($stVal, 'Out For Delivery') !== false || strcasecmp($stVal, 'OFD') == 0) {
                            $total_attempts++;
                        }
                    }
                }
            } elseif ($order->ship_courier_id == '5') {
                if (isset($status['trackDetails']) && count($status['trackDetails']) > 0) {
                    foreach ($status['trackDetails'] as $history) {
                        $strAct = $history['strAction'] ?? '';
                        $strCode = $history['strCode'] ?? '';
                        if (stripos($strAct, 'Out For Delivery') !== false || strcasecmp($strCode, 'OFD') == 0) {
                            $total_attempts++;
                        }
                    }
                }
            } elseif ($order->ship_courier_id == '6') {
                if (isset($status['success']) && $status['success'] == 1 && isset($status['data']) && isset($status['data'][0]) && isset($status['data'][0]['shipmentStatus'])) {
                    $history = $status['data'][0]['shipmentStatus'];
                    for ($j = count($history) - 1; $j >= 0; $j--) {
                        $stCode = $history[$j]['statusCode'] ?? '';
                        $stName = $history[$j]['statusName'] ?? '';
                        if (strcasecmp($stCode, 'OFD') == 0 || stripos($stName, 'Out for delivery') !== false) {
                            $total_attempts++;
                        }
                    }
                }
            } elseif ($order->ship_courier_id == '7') {
                if (isset($status[$order->tracking_info]) && isset($status[$order->tracking_info]['history'])) {
                    $history = $status[$order->tracking_info]['history'];
                    for ($j = count($history) - 1; $j >= 0; $j--) {
                        $st = $history[$j]['status'] ?? '';
                        if (stripos($st, 'out_for_delivery') !== false || stripos($st, 'out for delivery') !== false || strcasecmp($st, 'ofd') == 0) {
                            $total_attempts++;
                        }
                    }
                }
            } elseif ($order->ship_courier_id == '8') {
                if ($type == 'forward') {
                    if (isset($status['tracking_details'])) {
                        foreach ($status['tracking_details'] as $history) {
                            $stId = $history['status_id'] ?? '';
                            $stName = $history['status_name'] ?? '';
                            if (strcasecmp($stId, 'ofd') == 0 || stripos($stName, 'out for delivery') !== false) {
                                $total_attempts++;
                            }
                        }
                    }
                } else {
                    if (isset($status['pickup_request_state_histories'])) {
                        foreach ($status['pickup_request_state_histories'] as $history) {
                            $st = $history['state'] ?? '';
                            if (stripos($st, 'Out for Delivery') !== false) {
                                $total_attempts++;
                            }
                        }
                    }
                }
            } elseif ($order->ship_courier_id == '9') {
                if (isset($status['payload']) && isset($status['payload']['eventHistory'])) {
                    foreach ($status['payload']['eventHistory'] as $history) {
                        $evCode = $history['eventCode'] ?? '';
                        $evDesc = $history['eventDescription'] ?? '';
                        if (strcasecmp($evCode, 'OutForDelivery') == 0 || stripos($evDesc, 'Out for delivery') !== false) {
                            $total_attempts++;
                        }
                    }
                }
            } else {
                // Fallback attempt detection for other couriers
                if (is_array($status)) {
                    array_walk_recursive($status, function($val) use (&$total_attempts) {
                        if (is_string($val) && (stripos($val, 'out for delivery') !== false || stripos($val, 'out_for_delivery') !== false)) {
                            $total_attempts++;
                        }
                    });
                }
            }
        }

        $rawSt = strip_tags($order->status ?? '');
        if ($total_attempts == 0 && (in_array($rawSt, ['3', 'Delivered']) || !empty($order->delivered_date))) {
            $total_attempts = 1;
        }

        $order->total_attempt = max((int)($order->getRawOriginal('total_attempt') ?? 0), $total_attempts);
        if ($total_attempts > 0 || in_array($order->status, ['3', '6', '4'])) {
            $order->attempts_cron = '1';
        }
        $order->save();
    }
    
    public static function getcourierdata($order_id,$lastscanarray){
        if(!empty($lastscanarray)){
            $order = Order_courier_data::where('order_id',$order_id)->first();
            if($order){
               
            }else{
                $order = new Order_courier_data();
                $order->order_id = $order_id;
            }
                $order->c_action = $lastscanarray['action'];
                $order->c_place = $lastscanarray['place'];
                $order->c_remarks = $lastscanarray['remarks'];
                $order->c_date = $lastscanarray['date'];
                $order->save();
        }
    }
}
