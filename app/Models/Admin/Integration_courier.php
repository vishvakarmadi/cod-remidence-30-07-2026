<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Integration_courier extends Model
{
    protected static function getBaseUrl() {
        return 'https://apis.delcaper.com/';
    }

    protected static function getToken() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apis.delcaper.com/auth/login',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "email": "kapil@hyloship.com",
            "password": "Aframax@123",
            "vendorType": "SELLER"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: AWSALB=aHRuFH+a2oyA/48Uamf3cHY8pMR4BSaXiTIhBTPQ+h2UYa6zYKjkdDRtY2b55i8Zj+0utVMe6QYb9txFyyj1YvMJpdZH8WvlDrNdzZvyNmmkxkA0FSyaFj7A+bvX; AWSALBCORS=aHRuFH+a2oyA/48Uamf3cHY8pMR4BSaXiTIhBTPQ+h2UYa6zYKjkdDRtY2b55i8Zj+0utVMe6QYb9txFyyj1YvMJpdZH8WvlDrNdzZvyNmmkxkA0FSyaFj7A+bvX'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $res = json_decode($response, true);
        if(isset($res['data']) && $res['data']['accessToken']){
            return $res['data']['accessToken'];
        }else{
           return 'no token'; 
        }
//        echo '<pre>';print_R($res['data']['refreshToken']);die;
//        echo $response;

    }
    
    public static function hitpostcurl($url,$input){
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
          CURLOPT_POSTFIELDS =>$input,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.self::getToken(),
              'Cookie: AWSALB=aHRuFH+a2oyA/48Uamf3cHY8pMR4BSaXiTIhBTPQ+h2UYa6zYKjkdDRtY2b55i8Zj+0utVMe6QYb9txFyyj1YvMJpdZH8WvlDrNdzZvyNmmkxkA0FSyaFj7A+bvX; AWSALBCORS=aHRuFH+a2oyA/48Uamf3cHY8pMR4BSaXiTIhBTPQ+h2UYa6zYKjkdDRtY2b55i8Zj+0utVMe6QYb9txFyyj1YvMJpdZH8WvlDrNdzZvyNmmkxkA0FSyaFj7A+bvX'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    
    public static function hitputcurl($url,$input){
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
          CURLOPT_POSTFIELDS =>$input,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.self::getToken(),
            'Cookie: AWSALB=2c1W15KVKb+LCADzaJyTav+4GVnTnRHimQ9/x/6UlCexn9sAsisUagpbpSEUbUmtbaI6mlc96Mxpb1V0CiYy2P8uN43FU/pVKMvlAMc1J23KGYgrzSHky6GL0XZs; AWSALBCORS=2c1W15KVKb+LCADzaJyTav+4GVnTnRHimQ9/x/6UlCexn9sAsisUagpbpSEUbUmtbaI6mlc96Mxpb1V0CiYy2P8uN43FU/pVKMvlAMc1J23KGYgrzSHky6GL0XZs'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;

    }
    
    public static function hitgetcurl($url){
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
            'Cookie: AWSALB=XBuGdluty/NohIBsLEuaXrrCh1ni4WwawezM0bgl1E3aWMeqXs5YYmqV2laojOTxvIRYb3ugX79LdVcZ4tAlGxpBO1Wqek4GUGUc3wU6K5L6OlRBSJoFXoYQI8mT; AWSALBCORS=XBuGdluty/NohIBsLEuaXrrCh1ni4WwawezM0bgl1E3aWMeqXs5YYmqV2laojOTxvIRYb3ugX79LdVcZ4tAlGxpBO1Wqek4GUGUc3wU6K5L6OlRBSJoFXoYQI8mT'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    
    public static function shipment_shreemaruti($json_input){
        if($json_input !=''){
            $url = self::getBaseUrl().'fulfillment/public/seller/order/ecomm/push-order';
            $response = Integration_courier::hitpostcurl($url,$json_input);
            return $response;
        }else{
            return ''; 
        }
    }
    
    public static function cancelshreemaruti($json_input){
        if($json_input !=''){
            $url = self::getBaseUrl().'fulfillment/public/seller/order/cancel-order';
            $response = Integration_courier::hitputcurl($url,$json_input);
            return $response;
        }else{
            return ''; 
        }
    }
    
    public static function addmanfest($json_input){
        if($json_input !=''){
            $url = self::getBaseUrl().'fulfillment/public/seller/order/create-manifest';
            $response = Integration_courier::hitpostcurl($url,$json_input);
            return $response;
        }else{
            return ''; 
        }
    }
    
    public static function getsttaushreemaruti($awb){
        if($awb !=''){
            $url = self::getBaseUrl().'fulfillment/public/seller/order/order-tracking/'.$awb;
            $response = Integration_courier::hitgetcurl($url);
            return $response;
        }else{
            return ''; 
        }
    }

}
