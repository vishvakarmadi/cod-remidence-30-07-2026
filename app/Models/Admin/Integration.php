<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Integration extends Model
{
     // for ecom express start
    public static function hitpostcurl($url, $array_data)
    {
        if ($url != '' && !empty($array_data)) {
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
                CURLOPT_POSTFIELDS => $array_data,
                CURLOPT_HTTPHEADER => array(
                    'Cookie: AWSALB=G7BF4FlIo4ykNfok3Dk7mrwVNRQ/mebSumzKCDxlB2BgZd3+iZ0aSuxw1XX7qw/2XhhyOc/sHM4LUNzMEGqUMRQY3FM7oY9Dd2vkk9PVVkT5q3pj30WW0wiQ6r4T; AWSALBCORS=G7BF4FlIo4ykNfok3Dk7mrwVNRQ/mebSumzKCDxlB2BgZd3+iZ0aSuxw1XX7qw/2XhhyOc/sHM4LUNzMEGqUMRQY3FM7oY9Dd2vkk9PVVkT5q3pj30WW0wiQ6r4T'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
        }
    }

    public static function hitgetcurl($url)
    {
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
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public static function chk_serviceable_pincode($pincode)
    {
        if ($pincode != '') {
            $array_data = array('username' => '', 'password' => 'p63Cu1WHBN', 'pincode' => $pincode);
            $url = 'https://api.ecomexpress.in/apiv3/pincode/';
            $response = Integration::hitpostcurl($url, $array_data);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function get_awb_number($payment_mode)
    {
        if ($payment_mode != '') {
            $array_data = array('username' => '', 'password' => 'p63Cu1WHBN', 'count' => 1, 'type' => $payment_mode);
            $url = 'https://api.ecomexpress.in/apiv2/fetch_awb/';
            $response = Integration::hitpostcurl($url, $array_data);
            return $response;
        }
        else {
            return '';
        }
    }
    public static function shipment_ecom($json_input)
    {
        if ($json_input != '') {
            $json_input = '[' . $json_input . ']';
            $array_data = array('username' => '', 'password' => 'p63Cu1WHBN', 'json_input' => $json_input);
            $url = 'https://api.ecomexpress.in/apiv2/manifest_awb/';
            $response = Integration::hitpostcurl($url, $array_data);
            return $response;
        }
        else {
            return '';
        }
    }
    public static function rev_manifest_awb($json_input)
    {
        if ($json_input != '') {
            // $json_input = '['.$json_input.']';
            $array_data = array('username' => '', 'password' => 'p63Cu1WHBN', 'json_input' => $json_input);
            $url = 'https://api.ecomexpress.in/apiv2/manifest_awb_rev_v2/';
            $response = Integration::hitpostcurl($url, $array_data);
            return $response;
        }
        else {
            return '';
        }
    }
    public static function cancelshipment($awb_no)
    {
        if ($awb_no != '') {
            $array_data = array('username' => '', 'password' => 'p63Cu1WHBN', 'awbs' => $awb_no);
            $url = 'https://api.ecomexpress.in/apiv2/cancel_awb/';
            $response = Integration::hitpostcurl($url, $array_data);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function generate_shiplabel($awb_no)
    {
        if ($awb_no != '') {
            $array_data = array('username' => '', 'password' => 'p63Cu1WHBN', 'awb' => $awb_no);
            $url = 'https://shipment.ecomexpress.in/services/expp/shipping_label';
            $response = Integration::hitpostcurl($url, $array_data);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function track_ecom($tracking_info)
    {
        if ($tracking_info != '') {
            $url = 'https://plapi.ecomexpress.in/track_me/api/mawbd/?username=&password=p63Cu1WHBN&awb=' . $tracking_info;
            $response = Integration::hitgetcurl($url);
            return $response;
        }
        else {
            return '';
        }
    }

    // for ecom express end

    // for ecom xbess start
    public static function generatetoken_xbess($username = null, $password = null, $secretkey = null)
    {
        $username = $username ?: env('XBEES_USERNAME', 'admin@Hyloship.com');
        $password = $password ?: env('XBEES_PASSWORD', 'Xpress@1234567');
        $secretkey = $secretkey ?: env('XBEES_SECRETKEY', '5babb4d7a6c80b45ade918fb4e429068c8480e6125925c474d8d67a27f8190db');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://userauthapis.xbees.in/api/auth/generateToken',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30, // Added timeout
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                "username" => $username,
                "password" => $password,
                "secretkey" => $secretkey
            ]),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return json_encode(['code' => 500, 'message' => 'CURL Error: ' . $error_msg]);
        }
        curl_close($curl);
        return $response;
    }

    public static function hitpostcurl_xbess_svc($url, $data_string, $xb_key = null)
    {
        $xb_key = $xb_key ?: env('XBEES_XB_KEY', 'Plmng39338VdtHa');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_SSL_VERIFYPEER => false, // Bypass SSL for compatibility
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => array(
                'XBKey: ' . $xb_key,
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return json_encode(['ReturnCode' => '500', 'ReturnMessage' => 'CURL Error: ' . $error_msg]);
        }
        curl_close($curl);
        return $response;
    }

    public static function hitpostcurl_xbess($url, $data_string, $token, $xb_key = null)
    {
        $xb_key = $xb_key ?: env('XBEES_XB_KEY', 'Plmng39338VdtHa');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60, // Increased timeout for manifest
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => array(
                'token: ' . $token,
                'versionnumber: v1',
                'XBKey: ' . $xb_key,
                'xbAccessKey: ' . $xb_key,
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return json_encode(['ReturnCode' => '500', 'ReturnMessage' => 'CURL Error: ' . $error_msg]);
        }
        curl_close($curl);
        return $response;
    }

    public static function hitgetcurl_xbess($url, $token)
    {
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
                'token: ' . $token,
                'versionnumber: v1',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function chk_serviceable_pincode_xbess($array_data, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        $tdat = Integration::generatetoken_xbess($username, $password, $secretkey);
        $tdata = json_decode($tdat, true);
        if (isset($tdata['token'])) {
            if ($array_data != '') {
                $url = env('XBEES_SERVICEABILITY_URL', 'https://xbmasterapi.xbees.in/expose/get/serviceabilitypincode/details');
                return Integration::hitpostcurl_xbess($url, $array_data, $tdata['token'], $xb_key);
            }
            return '';
        }
        return $tdat;
    }

    public static function shipment_express($array_data, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        $tdat = Integration::generatetoken_xbess($username, $password, $secretkey);
        $tdata = json_decode($tdat, true);
        if (isset($tdata['token'])) {
            if ($array_data != '') {
                $url = env('XBEES_SHIPMENT_URL', 'https://apishipmentmanifestation.xbees.in/shipmentmanifestation/forward');
                return Integration::hitpostcurl_xbess($url, $array_data, $tdata['token'], $xb_key);
            }
            return '';
        }
        return $tdata;
    }

    public static function cancelshipment_xbess($array_data, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        $tdat = Integration::generatetoken_xbess($username, $password, $secretkey);
        $tdata = json_decode($tdat, true);
        if (isset($tdata['token'])) {
            if ($array_data != '') {
                $url = env('XBEES_CANCEL_URL', 'https://clientshipupdatesapi.xbees.in/forwardcancellation');
                return Integration::hitpostcurl_xbess($url, $array_data, $tdata['token'], $xb_key);
            }
            return '';
        }
        return $tdata;
    }

    public static function track_xbees($tracking_info, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        $tdat = Integration::generatetoken_xbess($username, $password, $secretkey);
        $tdata = json_decode($tdat, true);
        if (isset($tdata['token'])) {
            if ($tracking_info != '') {
                $array_data = json_encode(array('AWBNumber' => $tracking_info), true);
                $url = env('XBEES_TRACKING_URL', 'https://apishipmenttracking.xbees.in/GetShipmentAuditLog');
                return Integration::hitpostcurl_xbess($url, $array_data, $tdata['token'], $xb_key);
            }
            return '';
        }
        return $tdat;
    }

    public static function get_current_status_xbess($tracking_info, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        $tdat = Integration::generatetoken_xbess($username, $password, $secretkey);
        $tdata = json_decode($tdat, true);
        if (isset($tdata['token'])) {
            if ($tracking_info != '') {
                $array_data = json_encode(array('AWBNumber' => $tracking_info), true);
                $url = env('XBEES_CURRENT_STATUS_URL', 'https://apishipmenttracking.xbees.in/GetCurrentShipmentStatus');
                return Integration::hitpostcurl_xbess($url, $array_data, $tdata['token'], $xb_key);
            }
            return '';
        }
        return $tdat;
    }

    public static function generate_awb_series_xbess($array_data, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        if ($array_data != '') {
            $url = env('XBEES_AWB_URL', 'https://xbclientapi.xbees.in/POSTShipmentService.svc/AWBNumberSeriesGeneration');
            return Integration::hitpostcurl_xbess_svc($url, $array_data, $xb_key);
        }
        return '';
    }

    public static function get_awb_series_xbess($array_data, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        if ($array_data != '') {
            $url = env('XBEES_AWB_FETCH_URL', 'https://xbclientapi.xbees.in/TrackingService.svc/GetAWBNumberGeneratedSeries');
            return Integration::hitpostcurl_xbess_svc($url, $array_data, $xb_key);
        }
        return '';
    }

    public static function update_ndr_date_xbess($array_data, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        $tdat = Integration::generatetoken_xbess($username, $password, $secretkey);
        $tdata = json_decode($tdat, true);
        if (isset($tdata['token'])) {
            if ($array_data != '') {
                $url = 'https://clientshipupdatesapi.xbees.in/client/UpdateNDRDeferredDeliveryDate';
                return Integration::hitpostcurl_xbess($url, $array_data, $tdata['token'], $xb_key);
            }
            return '';
        }
        return $tdata;
    }

    public static function get_tracking_summary_xbess($array_data, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        $tdat = Integration::generatetoken_xbess($username, $password, $secretkey);
        $tdata = json_decode($tdat, true);
        if (isset($tdata['token'])) {
            if ($array_data != '') {
                $url = env('XBEES_TRACKING_SUMMARY_URL', 'https://apishipmenttracking.xbees.in/GetShipmentAuditLog');
                return Integration::hitpostcurl_xbess($url, $array_data, $tdata['token'], $xb_key);
            }
            return '';
        }
        return $tdat;
    }

    public static function send_whatsapp_notification($to, $customer_name, $awb_no, $delivery_address, $template_name = null)
    {
        if (empty($to)) {
            return json_encode(['status' => 'error', 'message' => 'Recipient phone number is empty.']);
        }

        $endpoint = env('WHATSAPP_API_ENDPOINT', 'https://telephonycloud.co.in/api/v1/whatsapp-message?svc-id=123');
        $token = env('WHATSAPP_API_TOKEN', 'MTAyMjUzMDQ6QWdlbnRAMDA3');
        $template = $template_name ?: env('WHATSAPP_TEMPLATE_NAME', 'body_testing_with_variable');

        $payload = [
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $template,
                'language' => [
                    'code' => 'en'
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $customer_name
                            ],
                            [
                                'type' => 'text',
                                'text' => $awb_no
                            ],
                            [
                                'type' => 'text',
                                'text' => $delivery_address
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic ' . $token
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return json_encode(['status' => 'error', 'message' => 'CURL Error: ' . $err, 'payload' => $payload]);
        }

        return $response;
    }

    // for ecom xbess end

    // for ecom delhivary start

    public static function hitgetcurl_delhivary($url)
    {
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

    public static function hitgetcurltoken_delhivary($url, $token)
    {

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
                'Authorization: Token ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);


        curl_close($curl);
        return $response;
    }

    public static function hitpostcurl_delhivary($url, $array_data, $token)
    {
        // Decode Delhivery form data (e.g., format=json&data=...) for easier debugging if needed
        $decoded_data = null;
        if (is_string($array_data)) {
            if (strpos($array_data, 'data=') !== false) {
                parse_str($array_data, $parsed);
                if (isset($parsed['data'])) {
                    $decoded_data = json_decode($parsed['data'], true);
                }
            } else if (strpos(trim($array_data), '{') === 0 || strpos(trim($array_data), '[') === 0) {
                $decoded_data = json_decode($array_data, true);
            }
        }

        \Log::info('Delhivery API Payload:', [
            'url' => $url,
            'headers' => [
                'Authorization: Token ' . $token,
                'Accept: application/json',
                (is_string($array_data) && (strpos(trim($array_data), '{') === 0 || strpos(trim($array_data), '[') === 0)) ? 'Content-Type: application/json' : null
            ],
            'post_fields_raw' => $array_data,
            'post_fields_decoded' => $decoded_data
        ]);

        if (request()->has('debug_delhivery')) {
            dd([
                'url' => $url,
                'headers' => [
                    'Authorization: Token ' . $token,
                    'Accept: application/json',
                    (is_string($array_data) && (strpos(trim($array_data), '{') === 0 || strpos(trim($array_data), '[') === 0)) ? 'Content-Type: application/json' : null
                ],
                'post_fields_raw' => $array_data,
                'post_fields_decoded' => $decoded_data
            ]);
        }

        $curl = curl_init();

        $headers = array(
            'Authorization: Token ' . $token,
            'Accept: application/json',
        );

        // Check if data is raw JSON or form-encoded string
        if (is_string($array_data) && (strpos(trim($array_data), '{') === 0 || strpos(trim($array_data), '[') === 0)) {
            $headers[] = 'Content-Type: application/json';
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $array_data,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    // public static function hitpostcurl_delhivary($url, $array_data, $token)
    // {
    //     // Decode Delhivery form data (e.g., format=json&data=...) for easier debugging if needed
    //     $decoded_data = null;
    //     if (is_string($array_data)) {
    //         if (strpos($array_data, 'data=') !== false) {
    //             parse_str($array_data, $parsed);
    //             if (isset($parsed['data'])) {
    //                 $decoded_data = json_decode($parsed['data'], true);
    //             }
    //         } else if (strpos(trim($array_data), '{') === 0 || strpos(trim($array_data), '[') === 0) {
    //             $decoded_data = json_decode($array_data, true);
    //         }
    //     }

    //     $headers = array(
    //         'Authorization: Token ' . $token,
    //         'Accept: application/json',
    //     );

    //     if (is_string($array_data) && (strpos(trim($array_data), '{') === 0 || strpos(trim($array_data), '[')) === 0) {
    //         $headers[] = 'Content-Type: application/json';
    //     }

    //     \Log::info('Delhivery API Payload:', [
    //         'url' => $url,
    //         'headers' => $headers,
    //         'post_fields_raw' => $array_data,
    //         'post_fields_decoded' => $decoded_data
    //     ]);

    //     // Die and Dump (dd) to inspect exact curl request data before hit
    //     dd([
    //         'CURL_URL' => $url,
    //         'CURL_HEADERS' => $headers,
    //         'RAW_POST_FIELDS' => $array_data,
    //         'DECODED_DATA_PAYLOAD' => $decoded_data ?? $array_data
    //     ]);

    //     $curl = curl_init();

    //     $headers = array(
    //         'Authorization: Token ' . $token,
    //         'Accept: application/json',
    //     );

    //     // Check if data is raw JSON or form-encoded string
    //     if (is_string($array_data) && (strpos(trim($array_data), '{') === 0 || strpos(trim($array_data), '[') === 0)) {
    //         $headers[] = 'Content-Type: application/json';
    //     }

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => $url,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS => $array_data,
    //         CURLOPT_HTTPHEADER => $headers,
    //     ));

    //     $response = curl_exec($curl);
    //     curl_close($curl);
    //     return $response;
    // }
    public static function chk_serviceable_pincode_delhivary($pincode, $type)
    {
        if ($pincode != '') {
            $type = strtolower($type);
            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
            $url = 'https://track.delhivery.com/c/api/pin-codes/json/?token=' . $token . '&filter_codes=' . $pincode;
            $response = Integration::hitgetcurl_delhivary($url);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function get_awb_number_delhivary($pincode, $type)
    {
        if ($pincode != '') {
            $type = strtolower($type);
            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
            $url = 'https://track.delhivery.com/waybill/api/bulk/json/?token=' . $token . '&count=1';
            $response = Integration::hitgetcurl_delhivary($url);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function create_warehouse($array_data, $type)
    {
        if ($array_data != '') {
            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
            $url = 'https://track.delhivery.com/api/backend/clientwarehouse/create/';
            $response = Integration::hitpostcurl_delhivary($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }
    public static function edit_warehouse($array_data, $type)
    {
        if ($array_data != '') {
            $type = strtolower($type);
            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
            $url = 'https://track.delhivery.com/api/backend/clientwarehouse/edit/';
            $response = Integration::hitpostcurl_delhivary($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function shipment_delhivary($array_data, $type)
    {
        if ($array_data != '') {
            $type = strtolower($type);
            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
            $url = 'https://track.delhivery.com/api/cmu/create.json';
            $response = Integration::hitpostcurl_delhivary($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }
    public static function cancelshipment_delivary($array_data, $type)
    {
        if ($array_data != '') {
            $type = strtolower($type);
            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
            $url = 'https://track.delhivery.com/api/p/edit';
            $response = Integration::hitpostcurl_delhivary($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }



    
//    public static function generate_shiplabel_delivary($awb){
//        if($awb !=''){
//            
////          $token ='f5ea26499f383776cc79180355c8b9b10deef3a5';
//              $url = 'https://track.delhivery.com/api/p/packing_slip?wbns='.$awb;
//             $response = Integration::hitgetcurltoken_delhivary($url,$token);
//             return $response;
//         }else{
//             return '';
//         }
//    }

    public static function shipment_rev_delhivary($array_data, $type)
    {
        if ($array_data != '') {
            $type = strtolower($type);
            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';

            // Inject Delhivery Parametric QC 3.0 fields dynamically
            if (is_string($array_data)) {
                parse_str($array_data, $parsed);
                if (isset($parsed['data'])) {
                    $data = json_decode($parsed['data'], true);
                    if (isset($data['shipments'])) {
                        foreach ($data['shipments'] as &$shipment) {
                            $shipment['qc_type'] = 'param';
                            
                            $custom_qc = [
                                'items' => []
                            ];
                            
                            $order_id = $shipment['order'] ?? null;
                            $order = null;
                            if ($order_id) {
                                $order = Order::with('detail')->where('order_id', $order_id)->first();
                                if (!$order) {
                                    $order = Order::with('detail')->where('id', $order_id)->first();
                                }
                            }
                            
                            if ($order && isset($order->detail) && count($order->detail) > 0) {
                                // Delhivery RVP supports maximum of 2 items and 6 questions per item
                                $details = $order->detail->take(2);
                                foreach ($details as $detail) {
                                    $qc_questions = [
                                        [
                                            'question' => 'Is the brand same?',
                                            'answer' => 'Yes',
                                            'answer_type' => 'binary'
                                        ],
                                        [
                                            'question' => 'Are the brand tags intact?',
                                            'answer' => 'Yes',
                                            'answer_type' => 'binary'
                                        ],
                                        [
                                            'question' => 'Is the product unused and in original condition?',
                                            'answer' => 'Yes',
                                            'answer_type' => 'binary'
                                        ],
                                        [
                                            'question' => 'Is the product same as described?',
                                            'answer' => 'Yes',
                                            'answer_type' => 'binary'
                                        ],
                                        [
                                            'question' => 'Is the size correct?',
                                            'answer' => 'Yes',
                                            'answer_type' => 'binary'
                                        ],
                                        [
                                            'question' => 'Is the color correct?',
                                            'answer' => 'Yes',
                                            'answer_type' => 'binary'
                                        ]
                                    ];
                                    
                                    // Compatibility with possible pre-existing internal QC questions structure
                                    if (isset($detail->qc_questions) && is_array($detail->qc_questions)) {
                                        $qc_questions = [];
                                        foreach (array_slice($detail->qc_questions, 0, 6) as $q) {
                                            $qc_questions[] = [
                                                'question' => $q['question'] ?? $q,
                                                'answer' => $q['answer'] ?? 'Yes',
                                                'answer_type' => $q['answer_type'] ?? 'binary'
                                            ];
                                        }
                                    }
                                    
                                    $custom_qc['items'][] = [
                                        'item_name' => $detail->name,
                                        'quantity' => (int)$detail->qty,
                                        'qc_questions' => $qc_questions
                                    ];
                                }
                            } else {
                                // Default/fallback item details if order/detail relation is not populated
                                $custom_qc['items'][] = [
                                    'item_name' => $shipment['products_desc'] ?? 'Product',
                                    'quantity' => (int)($shipment['quantity'] ?? 1),
                                    'qc_questions' => [
                                        [
                                            'question' => 'Is the brand same?',
                                            'answer' => 'Yes',
                                            'answer_type' => 'binary'
                                        ],
                                        [
                                            'question' => 'Are the brand tags intact?',
                                            'answer' => 'Yes',
                                            'answer_type' => 'binary'
                                        ],
                                        [
                                            'question' => 'Is the product unused and in original condition?',
                                            'answer' => 'Yes',
                                            'answer_type' => 'binary'
                                        ],
                                        [
                                            'question' => 'Is the product same as described?',
                                            'answer' => 'Yes',
                                            'answer_type' => 'binary'
                                        ],
                                        [
                                            'question' => 'Is the size correct?',
                                            'answer' => 'Yes',
                                            'answer_type' => 'binary'
                                        ],
                                        [
                                            'question' => 'Is the color correct?',
                                            'answer' => 'Yes',
                                            'answer_type' => 'binary'
                                        ]
                                    ]
                                ];
                            }
                            $shipment['custom_qc'] = $custom_qc;
                        }
                        unset($shipment);
                        $parsed['data'] = json_encode($data);
                        $array_data = http_build_query($parsed);
                    }
                }
            }

            $url = 'https://track.delhivery.com/api/cmu/create.json';
            $response = Integration::hitpostcurl_delhivary($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function track_delhivery($tracking_info)
    {
        if ($tracking_info != '') {


            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5'; //s
            $url = 'https://track.delhivery.com/api/v1/packages/json/?token=' . $token . '&waybill=' . $tracking_info;
            $response = Integration::hitgetcurl_delhivary($url); //            echo $response;die;
            if (str_contains($response, 'No such waybill or Order Id found')) {
                $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5'; //a
                $url = 'https://track.delhivery.com/api/v1/packages/json/?token=' . $token . '&waybill=' . $tracking_info;
                $response = Integration::hitgetcurl_delhivary($url);
                return $response;
            }
            else {
                return $response;
            }


        }
        else {
            return '';
        }
    }

    // for ecom delhivary end

    // for ecom dtdc start

    public static function hitpostcurl_dtdc($url, $array_data, $token, $api_key = true)
    {
        $curl = curl_init();
        if ($api_key) {
            $headerd = array(
                'Content-Type: application/json',
                'api-key: ' . $token
            );
        }
        else {
            $headerd = array(
                'Content-Type: application/json',
                'x-access-token: GL7569_trk_json:f6e2067f51c04474d1e3cf356dbbd639',
            );
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $array_data,
            CURLOPT_HTTPHEADER => $headerd,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;

    }
    public static function hitgetcurl_dtdc($url)
    {
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
                'Content-Type: application/json',
                'X-Access-Token: NF941_NL3024_bk:d83d61df2fd0ceefc2f9837641d0b73b',
                'Cookie: JSESSIONID=4E3A8203FD99A5D6E2C2E3453E431F80'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public static function chk_serviceable_pincode_dtdc($pincode)
    {
        if ($pincode != '') {
            $url = 'https://firstmileapi.dtdc.com/dtdc-api/api/custOrder/service/getServiceTypes/201301/' . $pincode;
            $response = Integration::hitgetcurl_dtdc($url);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function shipment_dtdc($array_data)
    {
        if ($array_data != '') {
            //for testing
            //  $token ='b01ed3562b088ab9c52822e3c18f9e';
            //  $url = 'https://demodashboardapi.shipsy.in/api/customer/integration/consignment/softdata';
            //for prod
            $token = 'd1d1f292ed2ad3921b56b5dcdbcef0';
            $url = 'https://dtdcapi.shipsy.io/api/customer/integration/consignment/softdata';
            $response = Integration::hitpostcurl_dtdc($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function cancelshipment_dtdc($array_data)
    {
        if ($array_data != '') {
            //for testing
            //  $token ='b01ed3562b088ab9c52822e3c18f9e';
            //  $url = 'https://demodashboardapi.shipsy.in/api/customer/integration/consignment/cancel';
            //for prod
            $token = 'd1d1f292ed2ad3921b56b5dcdbcef0';
            $url = 'http://dtdcapi.shipsy.io/api/customer/integration/consignment/cancel';
            $response = Integration::hitpostcurl_dtdc($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function generate_shiplabel_dtdc($array_data)
    {
        if ($array_data != '') {
            //for testing
            //  $token ='b01ed3562b088ab9c52822e3c18f9e';
            //  $url = 'https://demodashboardapi.shipsy.in/api/customer/integration/consignment/label/multipiece';
            //for prod
            $token = 'd1d1f292ed2ad3921b56b5dcdbcef0';
            $url = 'https://dtdcapi.shipsy.io/api/customer/integration/consignment/label/multipiece';
            $response = Integration::hitpostcurl_dtdc($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function shipment_rev_dtdc($array_data)
    {
        if ($array_data != '') {
            //for testing
            //  $token ='b01ed3562b088ab9c52822e3c18f9e';
            //  $url = 'https://demodashboardapi.shipsy.in/api/customer/integration/consignment/softdata';
            //for prod
            $token = 'd1d1f292ed2ad3921b56b5dcdbcef0';
            $url = 'https://dtdcapi.shipsy.io/api/customer/integration/consignment/softdata';
            $response = Integration::hitpostcurl_dtdc($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function track_dtdc($array_data)
    {
        if ($array_data != '') {
            //for prod
            $token = 'd1d1f292ed2ad3921b56b5dcdbcef0';
            $url = 'https://blktracksvc.dtdc.com/dtdc-api/rest/JSONCnTrk/getTrackDetails';
            $response = Integration::hitpostcurl_dtdc($url, $array_data, $token, false);
            return $response;
        }
        else {
            return '';
        }
    }
    // for ecom dtdc end

    // for smartr start

    public static function posthit_smartr($url, $array_data)
    {
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
            CURLOPT_POSTFIELDS => $array_data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public static function posthit_smartr_Token($url, $array_data, $token)
    {
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
            CURLOPT_POSTFIELDS => $array_data,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function hitget_smartr($url, $token)
    {
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
                'Authorization: Bearer ' . $token,
                'Cookie: csrftoken=nIkpEuSMRY5au3F17eMDuScH9jwxkXiU7De1xfixx8nY7U8lpVO98645vDfrxwL3; sessionid=x42vtp4lhbfaiwgu352krcnfdaiyq76m'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function generatetoken_smartr()
    {
        $url = 'https://api.smartr.in/api/v1/get-token/';
        $array_data = '{"username":"", "password":""}';
        $token_json = Integration::posthit_smartr($url, $array_data);
        $token_array = json_decode($token_json, true);
        if ($token_array['success'] && isset($token_array['data']['access_token'])) {
            return $token_array['data']['access_token'];
        }
        else {
            return '';
        }

    }

    public static function chk_serviceable_pincode_smatr($pin)
    {
        if ($pin) {
            $url = 'https://api.smartr.in/api/v1/pincode/?pincode=' . $pin;
            $token = Integration::generatetoken_smartr();
            if ($token != '') {
                return Integration::hitget_smartr($url, $token);
            }
            else {
                return '';
            }
        }
        else {
            return '';
        }

    }

    public static function shipment_smartr($array_data)
    {
        if ($array_data != '') {
            $url = 'https://api.smartr.in/api/v1/add-order/';
            $token = Integration::generatetoken_smartr();
            if ($token != '') {
                return Integration::posthit_smartr_Token($url, $array_data, $token);
            }
            else {
                return '';
            }
        }
        else {
            return '';
        }
    }

    public static function generate_shiplabel_smartr($array_data, $type)
    {
        if ($array_data != '') {
            if ($type == 'surface') {
                $url = 'https://api.smartr.in/api/v1/shippingLabel/?awbs=' . $array_data;
            }
            else {
                $url = 'https://api.smartr.in/api/v1/generateLabel/?awbs=' . $array_data;
            }
            $token = Integration::generatetoken_smartr();
            if ($token != '') {
                return Integration::hitget_smartr($url, $token);
            }
            else {
                return '';
            }

        }
        else {
            return '';
        }
    }

    public static function cancelshipment_smartr($array_data, $type)
    {
        if ($array_data != '') {
            if ($type == 'cod') {
                $url = 'https://api.smartr.in/api/v1/cancellation/';
            }
            else {
                $url = 'https://api.smartr.in/api/v1/updateCancel/';
            }
            $token = Integration::generatetoken_smartr();
            if ($token != '') {
                return Integration::posthit_smartr_Token($url, $array_data, $token);
            }
            else {
                return '';
            }
        }
        else {
            return '';
        }
    }

    public static function track_smartr($tracking_info)
    {
        if ($tracking_info) {
            $url = 'https://api.smartr.in/api/v1/tracking/?awb=' . $tracking_info;
            $token = Integration::generatetoken_smartr();
            if ($token != '') {
                return Integration::hitget_smartr($url, $token);
            }
            else {
                return '';
            }
        }
        else {
            return '';
        }

    }

    // for smartr end

    public static function hitgetcurl_distance($origin, $destination)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $origin . "&destinations=" . $destination . "&key=AIzaSyCwiCtnKcqvwdyMKTVV5Q8_HIq2YBppXOc";
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

    // Blue Dart APIGEE Start

    public static function get_bluedart_apigee_token($client_id, $client_secret, $server_mode = 1)
    {
        $baseUrl = ($server_mode == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        $cacheKey = 'bluedart_token_' . md5($client_id);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $baseUrl . '/in/transportation/token/v1/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'ClientID: ' . $client_id,
                'clientSecret: ' . $client_secret,
                'accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);

        if (isset($data['JWTToken'])) {
            Cache::put($cacheKey, $data['JWTToken'], 3500); // Valid for 1 hour, stash for 58 mins
            return $data['JWTToken'];
        }

        // Log failure so we can diagnose the real reason
        \Log::error('Blue Dart APIGEE Token Fetch Failed. Response: ' . $response . ' | client_id: ' . $client_id);
        return null;
    }

    public static function hitpostcurl_bluedart_apigee($url, $array_data, $token)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $array_data,
            CURLOPT_HTTPHEADER => array(
                'JWTToken: ' . $token,
                'Content-Type: application/json',
                'accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function hitgetcurl_bluedart_apigee($url, $token)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'JWTToken: ' . $token,
                'accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function chk_serviceable_pincode_bluedart_apigee($pincode, $integration)
    {
        $token = self::get_bluedart_apigee_token($integration->bd_client_id, $integration->bd_client_secret, $integration->server);
        if (!$token) return null;

        $baseUrl = ($integration->server == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        $url = $baseUrl . '/in/transportation/finder/v1/GetServicesforPincode';

        $data = json_encode([
            "pinCode" => $pincode,
            "profile" => [
                "LoginID" => $integration->login_id,
                "LicenceKey" => $integration->licence_key,
                "Api_type" => "S"
            ]
        ]);

        return self::hitpostcurl_bluedart_apigee($url, $data, $token);
    }

    public static function get_services_for_pincode_and_product($pincode, $product_code, $sub_product_code, $integration)
    {
        $token = self::get_bluedart_apigee_token($integration->bd_client_id, $integration->bd_client_secret, $integration->server);
        if (!$token) return null;

        $baseUrl = ($integration->server == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        $url = $baseUrl . '/in/transportation/finder/v1/GetServicesforPincodeAndProduct';

        $data = json_encode([
            "pinCode" => (string)$pincode,
            "ProductCode" => (string)$product_code,
            "SubProductCode" => (string)$sub_product_code,
            "PackType" => "L",
            "Feature" => "R",
            "profile" => [
                "LoginID" => $integration->login_id,
                "LicenceKey" => $integration->licence_key,
                "Api_type" => "T"
            ]
        ]);

        return self::hitpostcurl_bluedart_apigee($url, $data, $token);
    }

    public static function shipment_bluedart_apigee($data, $integration)
    {
        $token = self::get_bluedart_apigee_token($integration->bd_client_id, $integration->bd_client_secret, $integration->server);
        if (!$token) return null;

        $baseUrl = ($integration->server == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        $url = $baseUrl . '/in/transportation/waybill/v1/GenerateWayBill';

        // Dump exact cURL data sent to BlueDart API
        // dd([
        //     'bluedart_curl_url' => $url,
        //     'bluedart_headers'  => [
        //         'JWTToken: ' . $token,
        //         'Content-Type: application/json',
        //         'accept: application/json'
        //     ],
        //     'bluedart_payload'  => is_string($data) ? json_decode($data, true) : $data
        // ]);

        return self::hitpostcurl_bluedart_apigee($url, $data, $token);
    }

    public static function track_bluedart_apigee($tracking_info, $integration)
    {
        $token = self::get_bluedart_apigee_token($integration->bd_client_id, $integration->bd_client_secret, $integration->server);
        if (!$token) return null;

        $baseUrl = ($integration->server == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        
        // Updated URL to include required handler and action parameters to prevent License Mismatch
        $url = $baseUrl . "/in/transportation/tracking/v1/shipment?handler=tnt&loginid={$integration->login_id}&numbers={$tracking_info}&format=json&lickey={$integration->tracking_licence_key}&scan=1&action=custawbquery&verno=1&awb=awb";

        return self::hitgetcurl_bluedart_apigee($url, $token);
    }

    public static function cancelshipment_bluedart_apigee($tracking_info, $integration)
    {
        $token = self::get_bluedart_apigee_token($integration->bd_client_id, $integration->bd_client_secret, $integration->server);
        if (!$token) return null;

        $baseUrl = ($integration->server == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        $url = $baseUrl . '/in/transportation/waybill/v1/CancelWaybill';

        $data = json_encode([
            "Request" => [
                "AWBNo" => $tracking_info
            ],
            "Profile" => [
                "LoginID" => $integration->login_id,
                "LicenceKey" => $integration->licence_key,
                "Api_type" => "S"
            ]
        ]);

        return self::hitpostcurl_bluedart_apigee($url, $data, $token);
    }

    public static function update_ewaybill_apigee($data, $integration)
    {
        $token = self::get_bluedart_apigee_token($integration->bd_client_id, $integration->bd_client_secret, $integration->server);
        if (!$token) return null;

        $baseUrl = ($integration->server == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        $url = $baseUrl . '/in/transportation/waybill/v1/UpdateEwayBill';

        $payload = json_encode([
            "ERequest" => [
                "InvoiceDate"   => $data['InvoiceDate'] ?? '',
                "InvoiceNumber" => $data['InvoiceNumber'] ?? '',
                "SellerGSTNo"   => $data['SellerGSTNo'] ?? '',
                "Waybillnumber" => $data['Waybillnumber'] ?? '',
                "eWaybillDate"  => $data['eWaybillDate'] ?? '',
                "eWaybillNumber"=> $data['eWaybillNumber'] ?? ''
            ],
            "Profile" => [
                "LoginID"    => $integration->login_id,
                "LicenceKey" => $integration->licence_key,
                "Api_type"   => "S"
            ]
        ]);

        return self::hitpostcurl_bluedart_apigee($url, $payload, $token);
    }

    public static function import_data_apigee($data, $integration)
    {
        $token = self::get_bluedart_apigee_token($integration->bd_client_id, $integration->bd_client_secret, $integration->server);
        if (!$token) return null;

        $baseUrl = ($integration->server == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        $url = $baseUrl . '/in/transportation/waybill/v1/ImportData';

        // $data should be an array of order requests.
        $payload = json_encode([
            "Request" => $data,
            "Profile" => [
                "LoginID"    => $integration->login_id,
                "LicenceKey" => $integration->licence_key,
                "Api_type"   => "S"
            ]
        ]);

        return self::hitpostcurl_bluedart_apigee($url, $payload, $token);
    }

    // Blue Dart APIGEE End
    
    public static function get_expected_tat_delhivery($origin_pin, $destination_pin, $mot, $pdt, $expected_pickup_date, $expected_pd)
    {
        $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
        $url = "https://track.delhivery.com/api/dc/expected_tat?origin_pin=" . $origin_pin . "&destination_pin=" . $destination_pin . "&mot=" . $mot . "&pdt=" . $pdt . "&expected_pickup_date=" . urlencode($expected_pickup_date);
        
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
                'Authorization: Token ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    // 
    public static function hitcurl_parcel_uncle($url, $method = 'GET', $data = null, $apiKey = '')
    {
        $curl = curl_init();
        $headers = [
            "X-API-Key: " . $apiKey,
            "Accept: application/json"
        ];
        
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
        ];

        if ($data !== null) {
            $options[CURLOPT_POSTFIELDS] = is_array($data) ? json_encode($data) : $data;
            $headers[] = "Content-Type: application/json";
        }

        $options[CURLOPT_HTTPHEADER] = $headers;
        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        curl_close($curl);
        
        return $response;
    }

    public static function chk_serviceable_pincode_parcel_uncle($pincode, $integration)
    {
        $apiKey = ($integration && isset($integration->pu_api_key) && !empty($integration->pu_api_key)) ? $integration->pu_api_key : env('PARCEL_UNCLE_API_KEY', '');
        $url = "https://parceluncle.com/carrier/v1/merchant/serviceability/?pincode=" . urlencode($pincode);
        return self::hitcurl_parcel_uncle($url, 'GET', null, $apiKey);
    }

    public static function rate_quote_parcel_uncle($data, $integration)
    {
        $apiKey = ($integration && isset($integration->pu_api_key) && !empty($integration->pu_api_key)) ? $integration->pu_api_key : env('PARCEL_UNCLE_API_KEY', '');
        $url = "https://parceluncle.com/carrier/v1/merchant/rates/";
        return self::hitcurl_parcel_uncle($url, 'POST', $data, $apiKey);
    }

    public static function shipment_parcel_uncle($data, $integration)
    {
        $apiKey = ($integration && isset($integration->pu_api_key) && !empty($integration->pu_api_key)) ? $integration->pu_api_key : env('PARCEL_UNCLE_API_KEY', '');
        $url = "https://parceluncle.com/carrier/v1/merchant/shipments/";
        return self::hitcurl_parcel_uncle($url, 'POST', $data, $apiKey);
    }

    public static function cancelshipment_parcel_uncle($tracking_number, $reason, $integration)
    {
        $apiKey = ($integration && isset($integration->pu_api_key) && !empty($integration->pu_api_key)) ? $integration->pu_api_key : env('PARCEL_UNCLE_API_KEY', '');
        $url = "https://parceluncle.com/carrier/v1/merchant/shipments/" . urlencode($tracking_number) . "/cancel/";
        $data = ["reason" => $reason ?: "Order cancelled by buyer"];
        return self::hitcurl_parcel_uncle($url, 'POST', $data, $apiKey);
    }

    public static function track_parcel_uncle($tracking_number, $integration)
    {
        $apiKey = ($integration && isset($integration->pu_api_key) && !empty($integration->pu_api_key)) ? $integration->pu_api_key : env('PARCEL_UNCLE_API_KEY', '');
        $url = "https://parceluncle.com/carrier/v1/merchant/shipments/" . urlencode($tracking_number) . "/track/";
        return self::hitcurl_parcel_uncle($url, 'GET', null, $apiKey);
    }

    public static function generate_shiplabel_parcel_uncle($tracking_number, $integration)
    {
        $apiKey = ($integration && isset($integration->pu_api_key) && !empty($integration->pu_api_key)) ? $integration->pu_api_key : env('PARCEL_UNCLE_API_KEY', '');
        $url = "https://parceluncle.com/carrier/v1/merchant/shipments/" . urlencode($tracking_number) . "/label/";
        return self::hitcurl_parcel_uncle($url, 'GET', null, $apiKey);
    }

    public static function ndr_action_parcel_uncle($tracking_number, $action_data, $integration)
    {
        $apiKey = ($integration && isset($integration->pu_api_key) && !empty($integration->pu_api_key)) ? $integration->pu_api_key : env('PARCEL_UNCLE_API_KEY', '');
        $url = "https://parceluncle.com/carrier/v1/merchant/ndr/" . urlencode($tracking_number) . "/action/";
        return self::hitcurl_parcel_uncle($url, 'POST', $action_data, $apiKey);
    }

    // =========================================================================
    // INDIA POST INTEGRATION
    // =========================================================================

    public static function hitcurl_indiapost($url, $method = 'GET', $data = null, $token = null)
    {
        $curl = curl_init();
        $headers = [
            'Accept: application/json'
        ];
        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ];

        if ($data !== null) {
            $options[CURLOPT_POSTFIELDS] = is_array($data) ? json_encode($data) : $data;
            $headers[] = 'Content-Type: application/json';
        } else if ($method === 'POST') {
            $headers[] = 'Content-Type: application/json';
        }

        $options[CURLOPT_HTTPHEADER] = $headers;
        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return json_encode(['success' => false, 'message' => 'CURL Error: ' . $error_msg]);
        }

        curl_close($curl);
        return $response;
    }

    public static function generatetoken_indiapost($username = null, $password = null)
    {
        $username = $username ?: env('INDIAPOST_CUSTOMER_ID', '1843579100');
        $password = $password ?: env('INDIAPOST_PASSWORD', 'Dop@1234');

        $cacheKey = 'indiapost_token_' . $username;
        $cachedToken = Cache::get($cacheKey);
        if ($cachedToken) {
            return $cachedToken;
        }

        $baseUrl = env('INDIAPOST_BASE_URL', 'https://test.cept.gov.in');
        $url = $baseUrl . '/beextcustomer/v1/access/login';

        $payload = [
            'username' => (string)$username,
            'password' => (string)$password
        ];

        $response = self::hitcurl_indiapost($url, 'POST', $payload);
        $res = json_decode($response, true);

        if (isset($res['success']) && $res['success'] && isset($res['data']['access_token'])) {
            $token = $res['data']['access_token'];
            $expiresIn = isset($res['data']['expires_in']) ? (int)$res['data']['expires_in'] : 3600;
            $cacheDuration = max(60, $expiresIn - 300);
            Cache::put($cacheKey, $token, $cacheDuration);
            return $token;
        }

        return null;
    }

    public static function chk_serviceable_pincode_indiapost($pincode, $token = null)
    {
        if (empty($token)) {
            $token = self::generatetoken_indiapost();
        }
        if (empty($token)) {
            return json_encode(['success' => false, 'message' => 'Authentication failed']);
        }

        $baseUrl = env('INDIAPOST_BASE_URL', 'https://test.cept.gov.in');
        $url = $baseUrl . '/bemasterdata/v1/offices/limited-details?pincode=' . urlencode($pincode) . '&limit=50&office-type=post';

        return self::hitcurl_indiapost($url, 'GET', null, $token);
    }

    public static function get_tariff_indiapost($product_code = 'BP', $weight, $source_pin, $dest_pin, $length = 0, $width = 0, $height = 0, $ins = 0, $token = null)
    {
        if (empty($token)) {
            $token = self::generatetoken_indiapost();
        }
        if (empty($token)) {
            return json_encode(['success' => false, 'message' => 'Authentication failed']);
        }

        $weight = (int) $weight;
        $length = (int) $length;
        $width = (int) $width;
        $height = (int) $height;
        $ins = (int) $ins;

        $baseUrl = env('INDIAPOST_BASE_URL', 'https://test.cept.gov.in');
        if ($product_code == 'SP') {
            $url = $baseUrl . '/beextcustomer/v1/speed-post/tariffs?product-code=SP&weight=' . $weight . '&source-pincode=' . $source_pin . '&destination-pincode=' . $dest_pin . '&length=' . $length . '&width=' . $width . '&height=' . $height . '&INS=' . $ins . '&POD=NO';
        } else {
            $url = $baseUrl . '/beextcustomer/v1/business-parcel-tariff/calculate?product-code=' . $product_code . '&weight=' . $weight . '&source-pincode=' . $source_pin . '&destination-pincode=' . $dest_pin . '&length=' . $length . '&width=' . $width . '&height=' . $height . '&ins=' . $ins;
        }

        return self::hitcurl_indiapost($url, 'GET', null, $token);
    }

    public static function shipment_indiapost($payload, $integration = null, $token = null)
    {
        $username = ($integration && $integration->ip_customer_id) ? $integration->ip_customer_id : env('INDIAPOST_CUSTOMER_ID', '1843579100');
        $password = ($integration && $integration->ip_password) ? $integration->ip_password : env('INDIAPOST_PASSWORD', 'Dop@1234');
        $customId = $username;

        if (empty($token)) {
            $token = self::generatetoken_indiapost($username, $password);
        }
        if (empty($token)) {
            return json_encode(['success' => false, 'message' => 'Authentication failed']);
        }

        $baseUrl = env('INDIAPOST_BASE_URL', 'https://test.cept.gov.in');
        $url = $baseUrl . '/beextcustomer/process-articles/' . $customId;

        return self::hitcurl_indiapost($url, 'POST', $payload, $token);
    }

    public static function generate_label_indiapost($payload, $integration = null, $token = null)
    {
        $username = ($integration && $integration->ip_customer_id) ? $integration->ip_customer_id : env('INDIAPOST_CUSTOMER_ID', '1843579100');
        $password = ($integration && $integration->ip_password) ? $integration->ip_password : env('INDIAPOST_PASSWORD', 'Dop@1234');

        if (empty($token)) {
            $token = self::generatetoken_indiapost($username, $password);
        }
        if (empty($token)) {
            return json_encode(['success' => false, 'message' => 'Authentication failed']);
        }

        $baseUrl = env('INDIAPOST_LABEL_BASE_URL', 'https://test.cept.gov.in');
        $url = $baseUrl . '/beextcustomer/v1/label/create/domestic';

        return self::hitcurl_indiapost($url, 'POST', $payload, $token);
    }

    public static function track_indiapost($tracking_info, $integration = null, $token = null)
    {
        $username = ($integration && $integration->ip_customer_id) ? $integration->ip_customer_id : env('INDIAPOST_CUSTOMER_ID', '1843579100');
        $password = ($integration && $integration->ip_password) ? $integration->ip_password : env('INDIAPOST_PASSWORD', 'Dop@1234');

        if (empty($token)) {
            $token = self::generatetoken_indiapost($username, $password);
        }
        if (empty($token)) {
            return json_encode(['success' => false, 'message' => 'Authentication failed']);
        }

        $barcodes = is_array($tracking_info) ? $tracking_info : [$tracking_info];
        $payload = [
            'bulk' => $barcodes
        ];

        $baseUrl = env('INDIAPOST_BASE_URL', 'https://test.cept.gov.in');
        $url = $baseUrl . '/beextcustomer/v1/tracking/bulk';

        return self::hitcurl_indiapost($url, 'POST', $payload, $token);
    }

    public static function download_events_indiapost($event_code, $event_date, $integration = null, $token = null)
    {
        // Per DOP doc section 13.5: POST with JSON body {Cust_Id, Event_Code, Event_Date (DDMMYYYY)}
        $username = ($integration && $integration->ip_customer_id) ? $integration->ip_customer_id : env('INDIAPOST_CUSTOMER_ID', '1843579100');
        $password = ($integration && $integration->ip_password) ? $integration->ip_password : env('INDIAPOST_PASSWORD', 'Dop@1234');

        if (empty($token)) {
            $token = self::generatetoken_indiapost($username, $password);
        }
        if (empty($token)) {
            return json_encode(['success' => false, 'message' => 'Authentication failed']);
        }

        $baseUrl = env('INDIAPOST_BASE_URL', 'https://test.cept.gov.in');
        $url = $baseUrl . '/beextcustomer/v1/event/download';

        $payload = [
            'Cust_Id'    => (string)$username,
            'Event_Code' => (string)$event_code, // 'LE' or 'IB'
            'Event_Date' => (string)$event_date,  // DDMMYYYY format
        ];

        return self::hitcurl_indiapost($url, 'POST', $payload, $token);
    }

    public static function refresh_token_indiapost($refresh_token)
    {
        // Per DOP doc section 13.1.2: refresh_token sent as Bearer token, no body required
        $baseUrl = env('INDIAPOST_BASE_URL', 'https://test.cept.gov.in');
        $url = $baseUrl . '/beextcustomer/v1/access/TokenWithRtoken';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $refresh_token,
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    // for delhivery b2b start
    public static function generatetoken_delhivery_b2b($username = null, $password = null)
    {
        $username = $username ?: env('DELHIVERY_B2B_USERNAME', 'AFRAMAXLOGISTICSIND6B2BC-b2b');
        $password = $password ?: env('DELHIVERY_B2B_PASSWORD', 'Afrahylo@7070');
        
        $cache_key = 'delhivery_b2b_jwt_token_' . md5($username);
        $cached_token = Cache::get($cache_key);
        if ($cached_token) {
            return $cached_token;
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/ums/login';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'username' => $username,
                'password' => $password
            ]),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            \Log::error('Delhivery B2B Login Curl Error: ' . $err);
            return '';
        }

        $res = json_decode($response, true);
        if (isset($res['success']) && $res['success'] && isset($res['data']['jwt'])) {
            $token = $res['data']['jwt'];
            // Cache token for 23 hours (82800 seconds) since validity is 24 hours
            Cache::put($cache_key, $token, 82800);
            return $token;
        }

        \Log::error('Delhivery B2B Login Failed: ' . $response);
        return '';
    }

    public static function hitgetcurl_delhivery_b2b($url, $token)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            \Log::error('Delhivery B2B GET Curl Error: ' . $err . ' URL: ' . $url);
            return json_encode(['success' => false, 'message' => 'CURL Error: ' . $err]);
        }
        return $response;
    }

    public static function hitpostcurl_json_delhivery_b2b($url, $json_data, $token)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $json_data,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            \Log::error('Delhivery B2B POST JSON Curl Error: ' . $err . ' URL: ' . $url);
            return json_encode(['success' => false, 'message' => 'CURL Error: ' . $err]);
        }
        return $response;
    }

    public static function hitcustomcurl_delhivery_b2b($url, $method, $array_data, $token)
    {
        $curl = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        );

        if (!empty($array_data)) {
            $options[CURLOPT_POSTFIELDS] = is_string($array_data) ? $array_data : json_encode($array_data);
        }

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            \Log::error('Delhivery B2B ' . $method . ' Curl Error: ' . $err . ' URL: ' . $url);
            return json_encode(['success' => false, 'message' => 'CURL Error: ' . $err]);
        }
        return $response;
    }

    public static function hitmultipartcurl_delhivery_b2b($url, $array_data, $token, $method = 'POST')
    {
        $curl = curl_init();
        
        $headers = array(
            'Authorization: Bearer ' . $token
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $array_data,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            \Log::error('Delhivery B2B Multipart ' . $method . ' Curl Error: ' . $err . ' URL: ' . $url);
            return json_encode(['success' => false, 'message' => 'CURL Error: ' . $err]);
        }
        return $response;
    }

    public static function chk_serviceable_pincode_delhivery_b2b($pincode, $weight = 1, $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/pincode-service/' . $pincode . '?weight=' . $weight;

        return self::hitgetcurl_delhivery_b2b($url, $token);
    }

    public static function get_expected_tat_delhivery_b2b($origin_pin, $destination_pin, $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/tat/estimate?origin_pin=' . $origin_pin . '&destination_pin=' . $destination_pin;

        return self::hitgetcurl_delhivery_b2b($url, $token);
    }

    public static function create_warehouse_delhivery_b2b($array_data, $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/client-warehouse/create/';
        $json_data = is_string($array_data) ? $array_data : json_encode($array_data);

        return self::hitpostcurl_json_delhivery_b2b($url, $json_data, $token);
    }

    public static function update_warehouse_delhivery_b2b($array_data, $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/client-warehouses/update';
        $json_data = is_string($array_data) ? $array_data : json_encode($array_data);

        return self::hitcustomcurl_delhivery_b2b($url, 'PATCH', $json_data, $token);
    }

    public static function shipment_delhivery_b2b($array_data, $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/manifest';

        return self::hitmultipartcurl_delhivery_b2b($url, $array_data, $token, 'POST');
    }

    public static function get_manifest_status_delhivery_b2b($job_id, $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/manifest?job_id=' . $job_id;

        return self::hitgetcurl_delhivery_b2b($url, $token);
    }

    public static function update_shipment_delhivery_b2b($lrn, $array_data, $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/lrn/update/' . $lrn;

        return self::hitmultipartcurl_delhivery_b2b($url, $array_data, $token, 'PUT');
    }

    public static function update_shipment_status_delhivery_b2b($job_id, $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/lrn/update/status?job_id=' . $job_id;

        return self::hitgetcurl_delhivery_b2b($url, $token);
    }

    public static function cancel_shipment_delhivery_b2b($lrn, $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/lrn/cancel/' . $lrn;

        return self::hitcustomcurl_delhivery_b2b($url, 'DELETE', null, $token);
    }

    public static function track_delhivery_b2b($lrnum, $all_wbns = false, $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/lrn/track?lrnum=' . $lrnum . '&all_wbns=' . ($all_wbns ? 'true' : 'false');

        return self::hitgetcurl_delhivery_b2b($url, $token);
    }

    public static function pickup_request_delhivery_b2b($array_data, $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/pickup_requests';
        $json_data = is_string($array_data) ? $array_data : json_encode($array_data);

        return self::hitpostcurl_json_delhivery_b2b($url, $json_data, $token);
    }

    public static function cancel_pickup_delhivery_b2b($pickup_id, $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/pickup_requests/' . $pickup_id;

        return self::hitcustomcurl_delhivery_b2b($url, 'DELETE', null, $token);
    }

    public static function get_shipping_label_url_delhivery_b2b($lrn, $size = 'std', $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/label/get_urls/' . $size . '/' . $lrn;

        return self::hitgetcurl_delhivery_b2b($url, $token);
    }

    public static function generate_document_delhivery_b2b($doc_type, $array_data, $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/generate/' . $doc_type;
        $json_data = is_string($array_data) ? $array_data : json_encode($array_data);

        return self::hitpostcurl_json_delhivery_b2b($url, $json_data, $token);
    }

    public static function generate_document_status_delhivery_b2b($doc_type, $job_id, $username = null, $password = null)
    {
        $token = self::generatetoken_delhivery_b2b($username, $password);
        if (!$token) {
            return json_encode(['success' => false, 'message' => 'Failed to generate Delhivery B2B token']);
        }

        $baseUrl = env('DELHIVERY_B2B_BASE_URL', 'https://ltl-clients-api-dev.delhivery.com');
        $url = $baseUrl . '/generate/' . $doc_type . '/status/' . $job_id;

        return self::hitgetcurl_delhivery_b2b($url, $token);
    }
    // for delhivery b2b end
}
