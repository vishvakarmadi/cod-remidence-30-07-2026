<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    
    public static function cleanStoreName($store_name) {
        $store_name = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $store_name);
        $store_name = str_replace('.myshopify.com', '', $store_name);
        return trim($store_name, '/');
    }

    public static function posthitshopify($url,$token){


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
            'X-Shopify-Access-Token: '.$token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public static function getordershopify($store_name,$store_access,$strt_date,$last_id_fetched){
//        echo 'hi';die;
        $store_name = self::cleanStoreName($store_name);
        $url = "https://".$store_name.".myshopify.com/admin/api/2024-04/orders.json?created_at_min=".$strt_date."&limit=100&since_id=".$last_id_fetched."&order=id%20asc";
//         echo $url.' '.$store_access;die;
        return Channel::posthitshopify($url,$store_access);
    }
  
  	public static function gethitwoocommerce($url,$consumer_secret,$customer_key){
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
            "Authorization: Basic " . base64_encode("$customer_key:$consumer_secret"),
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
    }

    public static function getorderwoocommerce($store_url,$consumer_secret,$customer_key,$last_time_fetched){
        $last_time_fetched = str_replace(" ",'T',$last_time_fetched);
      	//$l_array = explode('T',$last_time_fetched);
      	//$last_time_fetched = $l_array[0].'T00:00:00';
      //$last_time_fetched = '2024-05-14T14:36:15';
      	$store_url = rtrim($store_url,"/");
        $url=$store_url."/wp-json/wc/v3/orders?after=".$last_time_fetched."&orderby=id&order=asc&per_page=20&status=pending,processing,on-hold";//max 100
      //echo $url;die;  
      return Channel::gethitwoocommerce($url,$consumer_secret,$customer_key);
    }
  
    public static function getfulfillmentid($store_name,$store_access,$shopify_order_id){
        $store_name = self::cleanStoreName($store_name);
        $url = "https://".$store_name.".myshopify.com/admin/api/2024-04/orders/".$shopify_order_id."/fulfillments.json";
        // echo $url;die;
        return Channel::posthitshopify($url,$store_access);
    }
    public static function getfulfillmentid_other($store_name,$store_access,$shopify_order_id){
        $store_name = self::cleanStoreName($store_name);
        $url = "https://".$store_name.".myshopify.com/admin/api/2024-01/orders/".$shopify_order_id."/fulfillment_orders.json";
        // echo $url;die;
        return Channel::posthitshopify($url,$store_access);
    }

    public static function shopifyposthit($url,$token,$data){
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
            'X-Shopify-Access-Token: '.$token,
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function addfullfillment($store_name,$store_access,$fullfill_data){
        $store_name = self::cleanStoreName($store_name);
        $url = "https://".$store_name.".myshopify.com/admin/api/2024-04/fulfillments.json";
        return Channel::shopifyposthit($url,$store_access,$fullfill_data);
    }
    
    public static function getcanordershopify($store_name,$store_access,$updated_at_min){
      $store_name = self::cleanStoreName($store_name);
      $url = "https://".$store_name.".myshopify.com/admin/api/2024-07/orders.json?status=cancelled&updated_at_min=".$updated_at_min."&limit=100&fields=id,updated_at,cancelled_at&order=updated_at%20asc";
//      echo $url;die;
      return Channel::posthitshopify($url,$store_access);
  }
}
