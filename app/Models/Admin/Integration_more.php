<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Admin\Blitztoken;

class Integration_more extends Model
{
    
    
    public static function puthit_ekart($url,$array_data,$token,$type='PUT'){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.elite.ekartlogistics.in/api/v1/package/create',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $type,
          CURLOPT_POSTFIELDS =>$array_data,
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token,
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


           

            return $response;

    }
    public static function deletehit_ekart($url,$token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'DELETE',
          CURLOPT_HTTPHEADER => array(
             'Authorization: Bearer '.$token,
           ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function pushhit_ekart($url,$array_data,$token) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$array_data,
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token,
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function gethit_ekart($url) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function generatetoken_ekart(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://app.elite.ekartlogistics.in/integrations/v2/auth/token/EKART_699459b7dcc54747b4c5a640',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "username": "kapil@aframaxlogistics.com",
        "password": "Aframax@123"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response,true);
        if(isset($response['access_token'])){
            return $response['access_token'];
        }else{
            return '';
        }
    }

    public static function chk_serviceable_pincode_ekart($pincode){
        if($pincode !=''){
            $token = Integration_more::generatetoken_ekart();
            if($token !=''){
                $url = 'https://api.ekartlogistics.com/v1/offerings';
//                return Integration_more::posthit_ekart($url,$pincode,$token);
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
    
    public static function shipment_ekart($array_data,$pck_ln) {
        if($array_data !=''){
            $token = Integration_more::generatetoken_ekart();
//             print_R($array_data);die;
             if($token !=''){
                 $url ="https://app.elite.ekartlogistics.in/api/v1/package/create";
                 $res_ekart = Integration_more::puthit_ekart($url,$array_data,$token);
                 $res_ekartjon = json_decode($res_ekart,true);
//                 echo '<pre>';print_R($res_ekartjon);d
//                 echo '->>>';print_R(strpos($res_ekartjon['description'],'pickup_location does not exist or is dele'));
                if(isset($res_ekartjon['description']) && strpos($res_ekartjon['message'],'SWIFT_RESOURCE_NOT_FOUND_EXCEPTION') !== false){
//                    echo 'hi';
                        $add_res = Integration_more::pushhit_ekart('https://app.elite.ekartlogistics.in/api/v2/address',$pck_ln,$token);
                        $res_ekart = Integration_more::puthit_ekart($url,$array_data,$token);
//                        echo $add_res;die;
                        return $res_ekart;
                    }else{
                        return $res_ekart;
                    }
//                 $ekrart_res = 
             }else{
                  return '';
             }
        }else{
            return '';
        }
    }
        


    public static function shipment_smartr($array_data){
        if($array_data !=''){
            $token = Integration_more::generatetoken_ekart();
//            echo '<pre>';print_R($token);die;
            if($token !=''){
                $url = 'https://api.ekartlogistics.com/v2/shipments/create';
                        https://app.elite.ekartlogistics.in/api/v1/package/create
                return Integration_more::posthit_ekart($url,$array_data,$token);
//                echo '<pre>';print_R(Integration_more::posthit_ekart($url,$array_data,$token));die;
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
    

    public static function cancelshipment_ekart($awb){
        if($awb !=''){
            $token = Integration_more::generatetoken_ekart();
            if($token !=''){
                
                    $url ="https://app.elite.ekartlogistics.in/api/v1/package/cancel?tracking_id=".$awb;
                return Integration_more::deletehit_ekart($url,$token);
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
    
     public static function track_Ekart($array_data){
        if($array_data !=''){
//            echo '<pre>';print_R($array_data);die;
//            $token = Integration_more::generatetoken_ekart();
//            if($token !=''){
                $url = 'https://app.elite.ekartlogistics.in/api/v1/track/'.$array_data;
                return Integration_more::gethit_ekart($url);
//            }else{
//                return '';
//            }
        }else{
            return '';
        }
    }
    
    public static function gethit_shadowfax($url){
        $token = '07d98d4ba4a017fb8cc207703d450c1db77c6c37';

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Token '.$token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public static function posthit_shadowfax($url,$array_data){
        $token = '07d98d4ba4a017fb8cc207703d450c1db77c6c37';

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$array_data,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Token '.$token,
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;


    }

    public static function chk_serviceable_pincode_shadowfax($drop,$pickup){
        $url = "https://dale.shadowfax.in/api/v1/serviceability/?pickup_pincode=".$pickup."&delivery_pincode=$drop";
        return Integration_more::gethit_shadowfax($url);
    }

    public static function get_awb_number_shadowfax($type,$arraydata){
        if($type =='forward'){
            $url = "https://dale.shadowfax.in/api/v3/clients/generate_marketplace_awb/";
        }else{
            $url = "https://dale.shadowfax.in/api/v3/clients/orders/generate_awb/";
        }
        return Integration_more::posthit_shadowfax($url,$arraydata);
    }

    public static function shipment_shadowfax($type,$arraydata){
        if($type =='forward'){
            $url = "https://dale.shadowfax.in/api/v3/clients/orders/";
        }else{
            $url = "https://dale.shadowfax.in/api/v3/clients/requests";
        }
        return Integration_more::posthit_shadowfax($url,$arraydata);
    }
    public static function cancelshipment_shadowfax($type,$arraydata){
        if($type =='forward'){
            $url = "https://dale.shadowfax.in/api/v3/clients/orders/cancel/";
        }else{
            $url = "https://dale.shadowfax.in/api/v2/clients/requests/mark_cancel";
        }
        return Integration_more::posthit_shadowfax($url,$arraydata);
    }
    
    public static function track_shadowfax($tracking_info,$type){
        if($type =='forward'){
            $url = "https://dale.shadowfax.in/api/v4/clients/orders/".$tracking_info."/track/";
        }else{
            $url = "https://dale.shadowfax.in/api/v4/clients/requests/".$tracking_info;
        }
//        echo $url;die;
        return Integration_more::gethit_shadowfax($url);
    }
  
  	public static function generate_token_ats(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.amazon.com/auth/o2/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'grant_type=refresh_token&refresh_token=' . env('ATS_REFRESH_TOKEN', '') . '&client_id=' . env('ATS_CLIENT_ID', '') . '&client_secret=' . env('ATS_CLIENT_SECRET', ''),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public static function posthit_ats($at,$arraydata,$url){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$arraydata,
        CURLOPT_HTTPHEADER => array(
            'x-amz-access-token: '.$at,
            'x-amzn-shipping-business-id: AmazonShipping_IN',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public static function puthit_ats($at,$url){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_HTTPHEADER => array(
            'x-amz-access-token: '.$at,
            'x-amzn-shipping-business-id: AmazonShipping_IN'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function shipment_ats($arraydata){
        if($arraydata !=''){
            $token_array = Integration_more::generate_token_ats();
            // $token_array = '{"access_token":"Atza|IwEBINy4VU09mFuCnBGZKAK3zvJOtgkIIV2Xnb4xZtURulTCOinMnLc-zyGXVZYVMOm5cIfw2tLh0nuvk29g100eVXOEVjR48tYJ32vC96MDfmZwHsd0-o-RffZ4ftZwocdANKBE9P3K8pTYbge3xnldgj_rECW4q-F0r9QMvudqOOricTtH3ImPewiY7LeXw8g7WeQreHGu9d_3OXyTTjgvKUL3-828UUDDyr8z36uxsBqoEwR8D4uMgIQGX7usx-3ZWMJa6zteQHIht59Oq7bmUowzAB3HLD8gviUtTQ9mn6GD94lmnflr5y6RZ1NfArxs6LbCMgrfWDUWs6CeIImBH6Z8","refresh_token":"Atzr|IwEBIFORYs1R2t8gVf7AgYPjSK6k75Yj13R475X6ffkoUXzc4dvQLyJWWVYNzsgkX3MzpAXy46vGb5wzUmZPSu2OA1urTFugXw1Tlr8nLzvCAVolWpYuhN5QMy0rFWcxj7rMyrl1rUfhEv_Y-WTLj5qsPG0943o3DyMHdkh61L287MxOPA8nsr_m7RmMlC4CEOmcw6UCSptfFMKoJhw1ZnNl1BjcBwQxrh5lk8tSL3W-paSiuBHLoaFfgn1S3p612kYqPMrtM8jCWkxxuHTTca31M3FSseSXKA42MqtgpISRlLvYNwOSERf0-li3tNOVYU0lUSM","token_type":"bearer","expires_in":3600}';
            $token_data = json_decode($token_array,true);
            if(isset($token_data['access_token']) && $token_data['access_token'] !=''){
                $url = 'https://sellingpartnerapi-eu.amazon.com/shipping/v2/oneClickShipment';
                return Integration_more::posthit_ats($token_data['access_token'],$arraydata,$url);
            }else{
                return ''; 
            }
        }else{
            return '';
        }
    }
    public static function cancelshipment_ats($shipment_id){
        if($shipment_id !=''){
            $token_array = Integration_more::generate_token_ats();
            $token_data = json_decode($token_array,true);
            if(isset($token_data['access_token']) && $token_data['access_token'] !=''){
                $url = 'https://sellingpartnerapi-eu.amazon.com/shipping/v2/shipments/'.$shipment_id.'/cancel';
                return Integration_more::puthit_ats($token_data['access_token'],$url);
            }
        }else{
            return '';
        }
        
    }
  
  	public static function gethit_ats($at,$url){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'x-amz-access-token: '.$at,
            'x-amzn-shipping-business-id: AmazonShipping_IN'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function track_ats($tracking_info){
        if($tracking_info !=''){
            $token_array = Integration_more::generate_token_ats();
            $token_data = json_decode($token_array,true);
            if(isset($token_data['access_token']) && $token_data['access_token'] !=''){
                $url = 'https://sellingpartnerapi-eu.amazon.com/shipping/v2/tracking?trackingId='.$tracking_info.'&carrierId=ATS';
                return Integration_more::gethit_ats($token_data['access_token'],$url);
            }
        }else{
            return '';
        }
    }
  
  	public static function generate_shiplabelawb($shipingid,$orderid){
        if($shipingid !='' && $orderid !=''){
            $token_array = Integration_more::generate_token_ats();
            $token_data = json_decode($token_array,true);
            if(isset($token_data['access_token']) && $token_data['access_token'] !=''){
                $url = 'https://sellingpartnerapi-eu.amazon.com/shipping/v2/shipments/'.$shipingid.'/documents?packageClientReferenceId='.$orderid;
                return Integration_more::gethit_ats($token_data['access_token'],$url);
            }
        }else{
            return '';
        }
    }
    
    public static function shipment_token(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://core.optnship.com/api/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "mobile": "",
        "password": ""
        }',
        CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
	
  	public static function shipment_puthit($url,$token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS =>'{
        "update_type": "cancel"
        }',
        CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer '.$token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
  
    public static function posthit_optnship($url,$token,$data){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$data,
        CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer '.$token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public static function shipment_bludartold($ship_data){
        if($ship_data !=''){
            $token_array = Integration_more::shipment_token();
            $token_array = json_decode($token_array,true);
            if(isset($token_array['api_token']) && $token_array['api_token'] !=''){
                $url = 'https://core.optnship.com/api/booking';
                return Integration_more::posthit_optnship($url,$token_array['api_token'],$ship_data);
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
    public static function ware_bludart($ware_data){
        if($ware_data !=''){
            $token_array = Integration_more::shipment_token();
            $token_array = json_decode($token_array,true);
            if(isset($token_array['api_token']) && $token_array['api_token'] !=''){
                $url = 'https://core.optnship.com/api/assigncourier';
                return Integration_more::posthit_optnship($url,$token_array['api_token'],$ware_data);
            }else{
                return '';
            }
        }else{
            return '';
        }
    }

    public static function track_bluedartold($track_data){
        if($track_data !=''){
            $token_array = Integration_more::shipment_token();
            $token_array = json_decode($token_array,true);
            if(isset($token_array['api_token']) && $token_array['api_token'] !=''){
                $url = 'https://core.optnship.com/api/tracking/WEB';
                return Integration_more::posthit_optnship($url,$token_array['api_token'],$track_data);
            }else{
                return '';
            }
        }else{
            return '';
        }
    }

  public static function cancel_bluedartold($shipmeny_id){
        if($shipmeny_id =='' || $shipmeny_id == null || $shipmeny_id ==0){
            return '';
        }else{
            $token_array = Integration_more::shipment_token();
            $token_array = json_decode($token_array,true);
            if(isset($token_array['api_token']) && $token_array['api_token'] !=''){
                $url = 'https://core.optnship.com/api/orders/'.$shipmeny_id;
                return Integration_more::shipment_puthit($url,$token_array['api_token']);
            }else{
                return '';
            }
        }
    }
    
    public static function shipment_gethit($url,$token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'accept: */*',
            'Authorization: Bearer '.$token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function checkservicebluedart($pickup,$drop,$length,$breadth,$height,$weight){
            $token_array = Integration_more::shipment_token();
            $token_array = json_decode($token_array,true);
            if(isset($token_array['api_token']) && $token_array['api_token'] !=''){
                $url = 'https://core.optnship.com/api/servicablecouriers?pickup='.$pickup.'&destination='.$drop.'&length='.$length.'&breadth='.$breadth.'&height='.$height.'&weight='.$weight;
                return Integration_more::shipment_gethit($url,$token_array['api_token']);
            }else{
                return '';
            }
    }
// -----------------------------------------------------------------------------------------

    public static function posthit_bd($url,$data){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            'access-token: YTc5ZTc4OThiZWIxOGJkMWZiZWJmNzE2NTA3YTgzMWVkYmY2ZmMwNTIzNTBhODI1MjA1MTc2OjAwMmFlYTE1OWE0OTgyMTQzNDM0NmVlYTk0NTk3ZDgyYWZkMThhZTRjM2VhNDNmZDI5ODJmYmE0MmMxZjAxNDk3NzdmNGUyNWUzOTY2YjFiNDdmYzcyZjc2N2E1MTc=',
            'Content-Type: application/json',
            'Cookie: px_session=ara42oont7pfe3j9eqpjngsq5qub9d8q'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return  $response;

    }
    public static function gethit_bd($url){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'access-token: YTc5ZTc4OThiZWIxOGJkMWZiZWJmNzE2NTA3YTgzMWVkYmY2ZmMwNTIzNTBhODI1MjA1MTc2OjAwMmFlYTE1OWE0OTgyMTQzNDM0NmVlYTk0NTk3ZDgyYWZkMThhZTRjM2VhNDNmZDI5ODJmYmE0MmMxZjAxNDk3NzdmNGUyNWUzOTY2YjFiNDdmYzcyZjc2N2E1MTc=',
            'Content-Type: application/json',
            'Cookie: px_session=ara42oont7pfe3j9eqpjngsq5qub9d8q'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return  $response;

    }
    /**
     * Returns a Blue Dart integration object populated from .env (primary)
     * or from the DB record (optional override).
     */
    public static function get_bluedart_integration($user_id = null) {
        // Try DB first (user-specific or global)
        $integration = null;
        if ($user_id) {
            $integration = Integration::where('courier_id', 3)->where('user_id', $user_id)->first();
        }
        if (!$integration) {
            $integration = Integration::where('courier_id', 3)->first();
        }

        // Build a plain object from config with DB as override
        $obj = new \stdClass();
        $obj->bd_client_id     = ($integration && $integration->bd_client_id)     ? $integration->bd_client_id     : config('services.bluedart.client_id', '');
        $obj->bd_client_secret = ($integration && $integration->bd_client_secret) ? $integration->bd_client_secret : config('services.bluedart.client_secret', '');
        $obj->login_id         = ($integration && $integration->login_id)         ? $integration->login_id         : config('services.bluedart.login_id', '');
        $obj->licence_key      = ($integration && $integration->licence_key)      ? $integration->licence_key      : config('services.bluedart.licence_key', '');
        
        // Tracking often requires a dedicated licence key on Blue Dart
        $obj->tracking_licence_key = config('services.bluedart.tracking_licence_key') ?: $obj->licence_key;
        
        $obj->pre_paid         = ($integration && $integration->pre_paid)         ? $integration->pre_paid         : config('services.bluedart.customer_code', '');
        $obj->cod              = ($integration && $integration->cod)              ? $integration->cod              : config('services.bluedart.customer_code', '');
        $obj->origin_area      = ($integration && $integration->origin_area)      ? $integration->origin_area      : config('services.bluedart.origin_area', '');
        $obj->server           = ($integration && $integration->server)           ? $integration->server           : (str_contains(config('services.bluedart.api_base_url', ''), 'sandbox') ? '0' : '1');
        $obj->packtype         = ($integration && $integration->packtype)         ? $integration->packtype         : '';
        $obj->user_id          = $user_id ?? ($integration->user_id ?? 1);
        return $obj;
    }

    public static function warehouse_bludart($warehusedata){
        $integration = self::get_bluedart_integration();
        if ($integration->login_id != '') {
            // APIGEE: warehouse pre-registration not needed — return bypass token
            return json_encode(['status' => true, 'data' => ['pick_address_id' => 'APIGEE']]);
        }
        $url = 'https://app.parcelx.in/api/v1/create_warehouse';
        return Integration_more::posthit_bd($url,$warehusedata);
    }

    public static function shipment_bludart($order_data, $user_id = null){
        $data = json_decode($order_data, true);
        $integration = self::get_bluedart_integration($user_id);

        if ($integration->login_id != '') {
            if ($integration->bd_client_id == '') {
                return json_encode(['status' => false, 'responsemsg' => ['Blue Dart APIGEE Client ID is missing. Please add BLUEDART_CLIENT_ID to services.php / env file.']]);
            }
            $apigee_payload = self::prepare_bluedart_apigee_payload($data, $integration);
            $response = Integration::shipment_bluedart_apigee($apigee_payload, $integration);



            $res_data = json_decode($response, true);
            if (isset($res_data['GenerateWayBillResult']['AWBNo']) && $res_data['GenerateWayBillResult']['AWBNo'] != '') {
                $awb = $res_data['GenerateWayBillResult']['AWBNo'];
                return json_encode([
                    'status' => true,
                    'data' => [
                        'awb_number' => $awb,
                        'label_binary' => $res_data['GenerateWayBillResult']['AWBPrintContent'] ?? '',
                        'TokenNumber' => $res_data['GenerateWayBillResult']['TokenNumber'] ?? ''
                    ]
                ]);
            } else {
                $error = 'Unknown Blue Dart Error';
                if (isset($res_data['responsemsg'])) {
                    if (is_array($res_data['responsemsg'])) {
                        $error = implode(' | ', $res_data['responsemsg']);
                    } else {
                        $error = $res_data['responsemsg'];
                    }
                } elseif (isset($res_data['GenerateWayBillResult']['Status']) && is_array($res_data['GenerateWayBillResult']['Status'])) {
                    $status_bits = $res_data['GenerateWayBillResult']['Status'];
                    $error_parts = [];
                    foreach ($status_bits as $bit) {
                        if (isset($bit['StatusInformation']) && $bit['StatusInformation'] != '') {
                            $error_parts[] = $bit['StatusInformation'];
                        }
                    }
                    if (!empty($error_parts)) {
                        $error = implode(' | ', array_unique($error_parts));
                    }
                } elseif (isset($res_data['GenerateWayBillResult']['Status']['Description'])) {
                    $error = $res_data['GenerateWayBillResult']['Status']['Description'];
                } elseif (isset($res_data['error-response'][0]['Status'][0]['StatusInformation'])) {
                    $error = $res_data['error-response'][0]['Status'][0]['StatusInformation'];
                } elseif (isset($res_data['message'])) {
                    $error = $res_data['message'];
                } elseif (isset($res_data['fault']['faultstring'])) {
                    $error = $res_data['fault']['faultstring'];
                } elseif ($response === null) {
                    $error = 'No response from Blue Dart API (check Client ID/Secret)';
                } elseif (empty($res_data) && !empty($response)) {
                    $error = 'Invalid JSON response from Blue Dart API: ' . substr(strip_tags($response), 0, 200);
                }
                return json_encode(['status' => false, 'responsemsg' => [$error]]);
            }
        }

        $url = 'https://app.parcelx.in/api/v3/order/create_order';
        return Integration_more::posthit_bd($url,$order_data);
    }

    public static function cancel_bluedart($order_data, $user_id = null){
        // OrderController sends json_encode(['awb' => '...']), so we must decode it.
        $decoded = json_decode($order_data, true);
        $awb = is_array($decoded) && isset($decoded['awb']) ? $decoded['awb'] : $order_data;

        $integration = self::get_bluedart_integration($user_id);
        if ($integration->login_id != '') {
            $response = Integration::cancelshipment_bluedart_apigee($awb, $integration);
            $res_data = json_decode($response, true);
            if (isset($res_data['CancelWaybillResult']['Status'][0]['StatusCode']) && strtolower($res_data['CancelWaybillResult']['Status'][0]['StatusCode']) == 'valid') {
                return json_encode(['status' => true, 'responsemsg' => 'Success']);
            } else {
                $error = $res_data['CancelWaybillResult']['Status'][0]['StatusInformation'] ?? 'Unknown Error';
                return json_encode(['status' => false, 'responsemsg' => $error]);
            }
        }
        $url = 'https://app.parcelx.in/api/v1/order/cancel_order';
        return Integration_more::posthit_bd($url,$awb);
    }

    public static function track_bluedart($awb, $user_id = null){
        $integration = self::get_bluedart_integration($user_id);
        if ($integration->login_id != '') {
            return Integration::track_bluedart_apigee($awb, $integration);
        }
        $url = 'https://app.parcelx.in/api/v1/track_order?awb='.$awb;
        return Integration_more::gethit_bd($url);
    }

    public static function clean_utf8_string($str, $limit = 30) {
        if ($str === null || $str === '') {
            return '';
        }
        // Replace common non-ASCII punctuation/dashes
        $replacements = [
            "\xE2\x80\x93" => '-', // en-dash
            "\xE2\x80\x94" => '-', // em-dash
            "\xE2\x80\x98" => "'", // left single quote
            "\xE2\x80\x99" => "'", // right single quote
            "\xE2\x80\x9C" => '"', // left double quote
            "\xE2\x80\x9D" => '"', // right double quote
        ];
        $str = strtr($str, $replacements);
        
        // Remove HTML tags
        $str = strip_tags($str);
        
        // Ensure string is valid UTF-8
        $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
        
        return mb_substr($str, 0, $limit, 'UTF-8');
    }

    public static function split_address($address, $limit = 30) {
        $address = trim(preg_replace('/\s+/', ' ', $address));
        $len = mb_strlen($address, 'UTF-8');
        if ($len <= $limit) {
            return [$address, '', ''];
        }
        
        $chunks = [];
        $words = explode(' ', $address);
        $currentChunk = '';
        
        foreach ($words as $word) {
            if (mb_strlen($currentChunk . ' ' . $word, 'UTF-8') <= $limit) {
                $currentChunk = empty($currentChunk) ? $word : $currentChunk . ' ' . $word;
            } else {
                if (!empty($currentChunk)) {
                    $chunks[] = $currentChunk;
                }
                while (mb_strlen($word, 'UTF-8') > $limit) {
                    $chunks[] = mb_substr($word, 0, $limit, 'UTF-8');
                    $word = mb_substr($word, $limit, null, 'UTF-8');
                }
                $currentChunk = $word;
            }
        }
        if (!empty($currentChunk)) {
            $chunks[] = $currentChunk;
        }
        
        return [
            mb_substr($chunks[0] ?? '', 0, $limit, 'UTF-8'),
            mb_substr($chunks[1] ?? '', 0, $limit, 'UTF-8'),
            mb_substr($chunks[2] ?? '', 0, $limit, 'UTF-8')
        ];
    }

    private static function prepare_bluedart_apigee_payload($data, $integration) {
        // Map data to strict Blue Dart APIGEE structure
        $is_cod = (isset($data['payment_mode']) && strtolower(strip_tags($data['payment_mode'])) == 'cod');
        $product_code = 'A';
        
        // Look up warehouse by actual ID — prioritize internal shipper_warehouse_id
        $wh_id = $data['shipper_warehouse_id'] ?? ($data['pick_address_id'] !== 'APIGEE' ? $data['pick_address_id'] : null);

        if ($wh_id) {
            $wh = \DB::table('warehouses')->where('id', $wh_id)->first();
        } else {
            // Fallback: try to find any warehouse for this company/user
            $wh = \DB::table('warehouses')
                ->where('company_id', Auth::guard('admin')->user()->company_id ?? 0)
                ->first();
        }

        $shipper_pincode = $data['shipper_pincode'] 
            ?? ($data['pickup_pincode'] 
            ?? ($wh ? trim($wh->pincode) 
            : ($integration->pincode ?? '110075')));

        // Dynamic Origin Area resolution using Pincode Finder API
        $origin_area = '';
        if (!empty($shipper_pincode)) {
            try {
                $pincode_res = Integration::chk_serviceable_pincode_bluedart_apigee($shipper_pincode, $integration);
                if ($pincode_res) {
                    $p_data = json_decode($pincode_res, true);
                    if (isset($p_data['GetServicesforPincodeResult']['AreaCode']) && !empty($p_data['GetServicesforPincodeResult']['AreaCode'])) {
                        $origin_area = strtoupper(trim($p_data['GetServicesforPincodeResult']['AreaCode']));
                    }
                }
            } catch (\Exception $e) {
                // Ignore exception and fallback
            }
        }

        // Fallbacks if Pincode Finder API didn't return AreaCode
        if (empty($origin_area) && $wh && isset($wh->origin_area) && !empty($wh->origin_area)) {
            $origin_area = strtoupper(trim($wh->origin_area));
        } elseif (empty($origin_area) && $wh && isset($wh->bd_origin_area) && !empty($wh->bd_origin_area)) {
            $origin_area = strtoupper(trim($wh->bd_origin_area));
        } elseif (empty($origin_area) && isset($data['origin_area']) && !empty($data['origin_area'])) {
            $origin_area = strtoupper(trim($data['origin_area']));
        } elseif (empty($origin_area) && isset($data['shipper_origin_area']) && !empty($data['shipper_origin_area'])) {
            $origin_area = strtoupper(trim($data['shipper_origin_area']));
        }

        if (empty($origin_area) && $wh && !empty($wh->city)) {
            $city_clean = strtoupper(trim($wh->city));
            $city_map = [
                'DELHI' => 'DEL', 'NEW DELHI' => 'DEL', 'GURGAON' => 'GGN', 'GURUGRAM' => 'GGN', 'NOIDA' => 'NOD', 'FARIDABAD' => 'FBD', 'GHAZIABAD' => 'GZB',
                'MUMBAI' => 'BOM', 'THANE' => 'THA', 'NAVI MUMBAI' => 'BOM', 'PUNE' => 'PNQ',
                'BANGALORE' => 'BLR', 'BENGALURU' => 'BLR',
                'CHENNAI' => 'MAA',
                'KOLKATA' => 'CCU',
                'HYDERABAD' => 'HYD',
                'AHMEDABAD' => 'AMD',
                'JAIPUR' => 'JAI',
                'SURAT' => 'STV',
                'LUCKNOW' => 'LKO',
                'CHANDIGARH' => 'IXC',
                'INDORE' => 'IDR'
            ];
            if (isset($city_map[$city_clean])) {
                $origin_area = $city_map[$city_clean];
            } elseif (strlen($city_clean) == 3) {
                $origin_area = $city_clean;
            }
        }

        if (empty($origin_area)) {
            $origin_area = ($integration && !empty($integration->origin_area)) ? strtoupper(trim($integration->origin_area)) : '';
        }

        if (empty($origin_area)) {
            $origin_area = 'DEL';
        }

        // Fetch warehouse details
        $sender = [
            "CustomerCode" => $is_cod ? ($integration->cod ?: '') : ($integration->pre_paid ?: ''),
            "OriginArea" => $origin_area,
            "Sender" => "",
            "CustomerName" => "",
            "CustomerAddress1" => "",
            "CustomerAddress2" => "",
            "CustomerAddress3" => "",
            "CustomerPincode" => $shipper_pincode, 
            "CustomerMobile" => "",
            "IsToPayCustomer" => false
        ];

        if ($wh) {
                $sender["Sender"]           = self::clean_utf8_string($wh->name, 30);
                $sender["CustomerName"]     = self::clean_utf8_string($wh->contact_name ?: $wh->name, 30);
                
                // Split warehouse address dynamically
                $sender_addr_full = implode(', ', array_filter([$wh->address ?? '', $wh->address_2 ?? '']));
                $sender_chunks = self::split_address($sender_addr_full, 30);
                
                $sender["CustomerAddress1"] = $sender_chunks[0];
                $sender["CustomerAddress2"] = $sender_chunks[1];
                $sender["CustomerAddress3"] = self::clean_utf8_string(($sender_chunks[2] !== '' ? $sender_chunks[2] . ', ' : '') . ($wh->city ?? ''), 30);
                
                $sender["CustomerMobile"]   = $wh->phone;
        }

        // dd([
        //     'wh_id_used'        => $wh_id,
        //     'warehouse_found'   => $wh ? (array)$wh : null,
        //     'origin_area'       => $origin_area,
        //     'shipper_pincode'   => $shipper_pincode,
        //     'sender_payload'    => $sender,
        // ]);

        $services = [
            "ActualWeight"       => (float)($data['shipment_weight'][0] ?? 0.5),
            "CollectableAmount"  => $is_cod ? (float)($data['cod_amount'] ?? 0) : 0,
            "TotalCashPaytoCustomer" => $is_cod ? (string)(float)($data['cod_amount'] ?? 0) : "0",
            "Commodity" => [
                "CommodityDetail1" => self::clean_utf8_string($data['products'][0]['product_name'] ?? "Items", 30),
                "CommodityDetail2" => "",
                "CommodityDetail3" => ""
            ],
            "CreditReferenceNo" => (string)($data['client_order_id'] ?? time()),
            "DeclaredValue"     => (float)($data['order_amount'] ?? 0),
            "Dimensions" => [
                [
                    "Breadth" => (int)($data['shipment_width'][0] ?? 10),
                    "Count"   => 1,
                    "Height"  => (int)($data['shipment_height'][0] ?? 10),
                    "Length"  => (int)($data['shipment_length'][0] ?? 10)
                ]
            ],
            "InvoiceNo"          => (string)($data['client_order_id'] ?? time()),
            "ItemCount"          => max(1, count($data['products'] ?? [])),
            "PieceCount"         => max(1, count($data['products'] ?? [])),
            "Pieces"             => max(1, count($data['products'] ?? [])),
            "PickupDate"         => "/Date(" . (time() * 1000) . ")/",
            "PickupTime"         => "1600",
            "ProductCode"        => $product_code,
            "ProductType"        => 1,
            "SubProductCode"     => $is_cod ? "C" : "P",
            "RegisterPickup"     => true,
            "PDFOutputNotRequired" => false,
        ];

        if (isset($data['express_type']) && strtolower($data['express_type']) == 'surface') {
            $services['PackType'] = $integration->packtype ?: 'L';
        }

        // Split consignee address dynamically
        $consignee_addr_full = implode(', ', array_filter([$data['consignee_address1'] ?? '', $data['consignee_address2'] ?? '']));
        $consignee_chunks = self::split_address($consignee_addr_full, 30);
        
        $consignee_address_1 = $consignee_chunks[0];
        $consignee_address_2 = $consignee_chunks[1];
        $consignee_address_3 = self::clean_utf8_string($consignee_chunks[2] ?? '', 30);

        $payload = [
            "Request" => [
                "Consignee" => [
                    "ConsigneeName" => self::clean_utf8_string($data['consignee_name'] ?? 'Consignee', 30),
                    "ConsigneeAddress1" => $consignee_address_1,
                    "ConsigneeAddress2" => $consignee_address_2,
                    "ConsigneeAddress3" => $consignee_address_3,
                    "ConsigneeCity" => strtoupper(self::clean_utf8_string($data['consignee_city'] ?? '', 30)),
                    "ConsigneeAddressType" => "R",
                    "ConsigneeAttention" => self::clean_utf8_string($data['consignee_name'] ?? 'Consignee', 30),
                    "ConsigneePincode" => $data['consignee_pincode'] ?? '',
                    "ConsigneeMobile" => $data['consignee_mobile'] ?? '',
                    "ConsigneeTelephone" => "",
                    "ConsigneeEmailID" => $data['consignee_emailid'] ?? ''
                ],
                "Services" => array_merge($services, [
                    "OTPBasedDelivery" => 0,
                    "OTPCode" => "",
                    "itemdtl" => [],
                    "noOfDCGiven" => 0
                ]),
                "Returnadds" => [
                    "ReturnAddress1"  => isset($sender["CustomerAddress1"]) ? $sender["CustomerAddress1"] : "",
                    "ReturnAddress2"  => isset($sender["CustomerAddress2"]) ? $sender["CustomerAddress2"] : "",
                    "ReturnAddress3"  => isset($sender["CustomerAddress3"]) ? $sender["CustomerAddress3"] : "",
                    "ReturnContact"   => $sender["CustomerName"] ?? "",
                    "ReturnMobile"    => $sender["CustomerMobile"] ?? "",
                    "ReturnPincode"   => $wh ? (string)$wh->pincode : ($sender["CustomerPincode"] ?? ""),
                    "ReturnEmailID"   => "",
                    "ReturnTelephone" => ""
                ],
                "Shipper" => array_merge($sender, [
                    "VendorCode" => ""
                ])
            ],
            "Profile" => [
                "Api_type"   => "S",
                "LicenceKey" => $integration->licence_key,
                "LoginID"    => $integration->login_id,
                "Version"    => "1.3"
            ]
        ];
        
        // dd($payload);

        $json_payload = json_encode($payload);
        \Log::info('Blue Dart APIGEE Outgoing Payload: ' . $json_payload);
        return $json_payload;
    }
    
    public static function posthit_blitz($url, $array_data, $token) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false, // for local testing only
            CURLOPT_SSL_VERIFYHOST => false, // for local testing only
            CURLOPT_POSTFIELDS => $array_data,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.$token,
                'Content-Type: application/json',
                'Accept: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        if ($response === false) {
            throw new \Exception('Curl error: ' . curl_error($curl));
        }

        curl_close($curl);
        return $response;
    }


    public static function getBlitzToken()
    {
        // Fetch latest token
        $tokenRow = Blitztoken::latest()->first();

        // If token exists and is less than 23 hours old → reuse
        if ($tokenRow && $tokenRow->created_at->gt(Carbon::now()->subHours(23))) {
            return $tokenRow->token;
        }

        // Otherwise generate new token
        $username = 'sGMugtYxG7Df';
        $password = '~Zp!FspWT5?Kr';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://oyvm2iv4xj.execute-api.ap-south-1.amazonaws.com/v1/auth',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POSTFIELDS =>'{
                "request_type": "authenticate",
                "payload": {
                    "username": "sGMugtYxG7Df",
                    "password": "~Zp!FspWT5?Kr"
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            ));

        $response = curl_exec($curl);
        curl_close($curl);
        // echo '<pre>';print_R($response);die;
        $response = json_decode($response, true);

        if (!isset($response['id_token'])) {
            throw new \Exception('Unable to generate Blitz token');
        }

        $idToken = $response['id_token'];

        // Delete old tokens
        Blitztoken::truncate();

        // Store new token
        Blitztoken::create([
            'token' => $idToken
        ]);

        return $idToken;
    }
    
    public static function shipment_blitz($order_data){
        $url = 'https://xv24xrhpxa.execute-api.ap-south-1.amazonaws.com/v1/waybill/';
        $token = Integration_more::getblitztoken();
        return Integration_more::posthit_blitz($url,$order_data,$token);
    }

    public static function cancelblitz($awb){
        $url ="https://oyvm2iv4xj.execute-api.ap-south-1.amazonaws.com/v1/orin/api/cancel/";
        $order_data_attay[] = array(
                    'field' =>"awb",
                    'value' =>$awb,
                    'cancel_reason' =>"",
                    'cancelled_by' =>"customer",
        );
        $token = Integration_more::getblitztoken();
        return Integration_more::posthit_blitz($url,json_encode($order_data_attay),$token);
    }
    
    public static function getsttausblitz($awb){
//        $awb='GS1227014821';
        $url ="https://oyvm2iv4xj.execute-api.ap-south-1.amazonaws.com/v1/tracking";
        $order_data_attay = array(
                    'field' =>"shipment",
                    'value' =>$awb
        );
//        echo json_encode($order_data_attay);die;
        $token = Integration_more::getblitztoken();
        return Integration_more::posthit_blitz($url,json_encode($order_data_attay),$token);
    }
    
    
//    =====================BLITZ END=========================================
    


//    =====================PCKnDEL=========================================
    
    
    public static function getpckndeltoken(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.pikndel.com/backoffice/api/account/login',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{"Control": {"RequestId": "baab37e9-49d1-4cb6-82c9-43a13f0532ce","Source": 3,"RequestTime": 1578469225,"Version": "1.0"},"Data": {"Username": "hyloship_ndd","Password": "Pikndel@123","GrantType":"password"}} ',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: ci_session=ifj286dc32irhbr865kjdvppanpqaphg'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return  $response;

    }
    
    public static function posthit_pckndel($url,$data,$token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$data,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$token,
            'Cookie: ci_session=ifj286dc32irhbr865kjdvppanpqaphg'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function shipment_pckndel($order_data) {
        
        $url = "https://api.pikndel.com/backoffice/api/pikndel/place_order";

        $token = Integration_more::getpckndeltoken();
        $token_array = json_decode($token, true);

        if (
            isset($token_array['Control']) &&
            isset($token_array['Control']['MessageCode']) &&
            $token_array['Control']['MessageCode'] == 200
        ) {
            return Integration_more::posthit_pckndel($url, $order_data, $token_array['Data']['Token']);
        } else {
            return $token;
        }
    }
    
    public static function cancelshipment_pckndel($cancel_date){
        $url = "https://api.pikndel.com/backoffice/api/pikndel/order/cancel";

        $token = Integration_more::getpckndeltoken();
        $token_array = json_decode($token, true);

        if (
            isset($token_array['Control']) &&
            isset($token_array['Control']['MessageCode']) &&
            $token_array['Control']['MessageCode'] == 200
        ) {
            return Integration_more::posthit_pckndel($url, $cancel_date, $token_array['Data']['Token']);
        } else {
            return $token;
        }
    }
     public static function getsttauspckndel($tracking_info){
        $url = "https://api.pikndel.com/backoffice/api/pikndel/order/get_status";

        $token = Integration_more::getpckndeltoken();
        $token_array = json_decode($token, true);

        if (
            isset($token_array['Control']) &&
            isset($token_array['Control']['MessageCode']) &&
            $token_array['Control']['MessageCode'] == 200
        ) { 
            $trackarray = array(
                "Control"=>array(
                    "RequestId"=>"10db2584-96ab-402b-91b5-2b0ebdd95ee8",
                    "RequestTime"=>time(),
                    "Source"=>"3",
                    "Version"=>"1.0"
                ),
                "Data"=>array(
                    "AWBNo"=>$tracking_info
                )
            );
            return Integration_more::posthit_pckndel($url, json_encode($trackarray), $token_array['Data']['Token']);
        } else {
            return $token;
        }
    }
}