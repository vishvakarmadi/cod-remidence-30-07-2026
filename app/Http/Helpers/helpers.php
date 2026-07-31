<?php 
use App\Models\Admin\ActivityLog;
use App\Models\Admin\ApiLog;
use App\Models\Admin\Admin;
use App\Models\Admin\ApiactivityLog;


function menuActive($routeName, $type = null, $param = null)
{
    if ($type == 3) $class = 'side-menu--open';
    elseif ($type == 2) $class = 'sidebar-submenu__open';
    else $class = 'active';

    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) return $class;
        }
    } elseif (request()->routeIs($routeName)) {
        if ($param) {
            $routeParam = array_values(@request()->route()->parameters ?? []);
            if (strtolower(@$routeParam[0]) == strtolower($param)) return $class;
            else return;
        }
        return $class;
    }
}

function zone($get){
    return [
        '0'  => 'Not servicable',
        'Z1' => 'within_city',
        'Z2' => 'within_state',
        'Z3' => 'metro_to_metro',
        'Z4' => 'rest_of_india',
        'Z5' => 'north_east',
    ][$get];

}

function vol_weigh($c_id){
//    if($c_id =='2' || $c_id =='5'){
//        if($c_id =='2'){
//            return '4000';
//        }else{
//            return '4750';
//        }
//    }else{
//        return '5000';
//    }
    return '5000';
}

function get_fin_year(){
    return date("m") >= 4 ? date("Y").'-04-01 00:00:01'. '&&' . (date("Y")+1).'-03-31 23:59:59' : (date("Y") - 1).'-04-01 00:00:01'. '&&' . date("Y").'-03-31 23:59:59';
}

function get_currentweekno($date){
    return date("W", strtotime($date));
}

function getstartenddateofweek($week, $year){
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['week_start'] = $dto->format('Y-m-d');
    $dto->modify('-1 days');
    $ret['week_start'] = $dto->format('Y-m-d').' 00:00:01';
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('Y-m-d').' 23:59:59';
    return $ret;
}



function createlogs($action, $action_type, $action_id, $changedFields = [], $oldValues = [])
{  
    $startTime = microtime(true);
    $description = '';
    $user = Auth::guard('admin')->user();
    if(!$user){
    $token = request()->bearerToken();
    $user=Admin::where('bearer_token',$token)->first();
    }
    
    if ($action_type === 'order') {
        switch ($action) {
            case 'created':
                $description = 'Order with ID ' . $action_id . ' was created.';
                break;
            case 'updated':
                $description = 'Order with ID ' . $action_id . ' was updated.';
                break;
            case 'deleted':
                $description = 'Order with ID ' . $action_id . ' was deleted.';
                break;
            case 'delivered':
                 $description = 'Order with ID ' . $action_id . ' was delivered.';
                break;
            case 'awb':
                $description='Awb for '. $action_id . 'was generated.';
            default:
                $description = 'Order with ID ' . $action_id . ' was ' . $action . '.';
                break;
        }
    }elseif ($action_type === 'rate') {
        switch ($action) {
            case 'updated':
                $description = 'Rate card for user id ' . $action_id . ' was updated.';
                break;
            
        }

    }elseif ($action_type === 'warehouse') {
        switch ($action) {
            case 'created':
                $description = 'Warehouse with ID ' . $action_id . ' was created.';
                break;
            case 'deleted':
                $description = 'Warehouse with ID ' . $action_id . ' was deleted.';
                break;
            default:
                $description = 'Warehouse with ID ' . $action_id . ' was ' . $action . '.';
                break;
        }
    }elseif ($action_type === 'login'){
        switch ($action) {
            case 'loginAs':
                $description = 'User id ' . $user . ' was logged in as '. $action_id ;
                break;
            case 'User-login':
                $description = 'User id ' .  $action_id  . ' was logged in ';
        }
    }elseif($action_type ==='courier'){

        switch($action){
           case 'changed':
               $description = 'Status of Courier id '. $action_id; 
               break;
           case'all_changed':
               $description =  'Status of All Courier has been changed';
               break;
        }
    }else {
        
        $description = $action . ' with ID ' . $action_id;
    }
    
    if (!empty($changedFields)) {
        $descriptionParts = [];
        foreach ($changedFields as $field => $newValue) {
            $oldValue = $oldValues[$field] ?? 'N/A';
            $descriptionParts[] = "$field: '$oldValue' to '$newValue'";
        }
        $description .= ' | Changes: ' . implode(', ', $descriptionParts);
    }

    $ipAddress = request()->ip();
    $geoLocation = getGeoLocation($ipAddress);
    $responseTime = microtime(true) - $startTime;
    $responseTimeMs = number_format($responseTime * 1000, 2);


    ActivityLog::create([
        'user_id' => $user->id,
        'company_id'=>$user->company_id,
        'action_id' => $action_id,
        'action' => $action,
        'action_type' => $action_type,
        'ip_address' => request()->ip(),
        'link_requested' => request()->fullUrl(),
        'requested_data' => json_encode(request()->all()),
        'user_agent' => request()->header('User-Agent'),
        'geo_location' => $geoLocation,
        'response_time'=>$responseTimeMs,
        'request_method' => request()->method(),
        'action_description' => $description,
        'created_at' => now(),
    ]);
       return 1;

}

function getGeoLocation($ipAddress)
{
    try {
        $response = Http::get("https://ipinfo.io/{$ipAddress}/json");
        $data = $response->json();

        // Log the raw response for debugging
       // \Log::info('GeoLocation Response:', $data);

        // Check if 'city' and 'country' are present
        $city = $data['city'] ?? 'Unknown';
        $country = $data['country'] ?? 'Unknown';

        return "$city, $country";
    } catch (\Exception $e) {
        // Log the exception for debugging
//        \Log::error('GeoLocation Error:', ['exception' => $e->getMessage()]);

        return 'Unknown';
    }
}

function api_logs($request,$response,$order_id,$courier_id,$logtype ='awb_assign')
{
    $user = Auth::guard('admin')->user();
    if(!$user){
        $token = request()->bearerToken();
        $user=Admin::where('bearer_token',$token)->first();
    }
    ApiLog::create([
            'user_id'=>$user->id,
            'company_id'=>$user->company_id,
            'log_type'=>$logtype,
            'request'=>$request,
            'response'=>$response,
            'order_id'=>$order_id,
            'courier_id'=>$courier_id,
    ]);
    return 1;
}

function api_activity_logs($requested_data,$response_sent,$user_id=0){
    if (is_object($requested_data) && method_exists($requested_data, 'all')) {
        $requested_data = $requested_data->all();
    } elseif (!is_array($requested_data)) {
        $requested_data = ['requested_data' => $requested_data];
    }
    ApiactivityLog::create([
        'user_id'        => $user_id,
        'requested_data' => json_encode($requested_data),  
        'response_sent'  => json_encode($response_sent), 
        'url'            => request()->fullUrl(),
        'request_type'   => request()->method(),
    ]);
}