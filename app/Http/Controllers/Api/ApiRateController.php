<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Admin\Ratecard;
use App\Models\Admin\Pincode;
use App\Models\Admin\Order;
use App\Models\Admin\Api;
use Illuminate\Support\Facades\Validator;
use DB;

class ApiRateController extends Controller
{
    public function calculate(Request $request)
    {
        $token = $request->bearerToken();
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
    
        $validatedData = Validator::make($request->all(), [
            'pickup_pin' => 'required|numeric',
            'drop_pin' => 'required|numeric',
            'length' => 'required|numeric',
            'breadth' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'payment' => 'required',
            'value' => request('payment') == 'cod' ? 'required|numeric' : '',
            'shipment_type' => 'required',
        ])->validate();
    
        $user_id = $admin->id;

        $result= Api::calculateRates($request, $user_id);
    
        if (empty($result) || count($result) == 0) {
            $response=response()->json([
                'status' => 0,
                'message' => 'Pincode is not Servicable!',
            ]);

        //checking api activity logs
        api_activity_logs($request,$response,$user_id);
        return $response;
        }

        foreach ($result as &$res) {
            unset($res['img']);  
            if ($res['mode'] === 'fa-plane') {
            $res['mode'] = 'Air ';
        } elseif ($res['mode'] === 'fa-truck') {
            $res['mode'] = 'Surface';
        }
        }
    
        $response= response()->json([
            'status' => 1,
            'message' => 'Success',
            'data' => $result,
            'request' => $validatedData,
        ]);
       
        //checking api activity logs 
        api_actity_logs($request,$response.$user_id);
        return $response;

    }

    public function activecourier(request $request){
        $token = $request->bearerToken();
        $admin = DB::table('admins')->where('bearer_token', $token)->first();

        if (!$admin) {
            $response=response()->json(['error' => true, 'message' => 'Unauthorized'], 401);
            //checking api activity logs
            api_activity_logs($request,$response);
            return $response;
        } 
        if ($admin->expired_on <= now()) {
            $response=response()->json(['error' => true, 'message' =>  'Token has expired. Please refresh your token.'], 401);
            //checking api activity logs 
            api_activity_logs($request,$response,$admin->id);
            return $response;
        }
        
        $user_id=$admin->id;
        $rate = Ratecard::where('user_id', $user_id)
                ->where('transport', '!=', 'Reverse')
                ->orderBy('courier_id')
                ->orderBy('transport')
                ->orderBy('weight')
                ->orderBy('additional')
                ->get();
//echo $rate;die;
    $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);

    foreach ($rate as $r) {
        // dd($rate);
    $courierName = isset($couriers[$r->courier_id]) ? $couriers[$r->courier_id]['name'] : 'Unknown Courier';
    
    $rateData[] = [
        'courier_id' => $r->courier_id,
        'courier_name' => $courierName,
        'mode' => $r->transport,
        'weight' => $r->weight,
        'additional' => $r->additional,
    ];
    };

   $response=response()->json([
        'success' => true,
        'data' => $rateData,
        'message' => 'active courier fetched with weight they can deliver'
    ]);
    //checking api activity logs 
    api_activity_logs($request,$response,$user_id);
    return $response;
    }

}