<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Admin\Warehouse;
use App\Models\Admin\Country;
use App\Models\Admin\Integration;
use App\Models\Admin\Integration_more;
use App\Models\Admin\ActivityLog;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Admin\Api;
use App\Models\Admin\Admin;
use DB;

class ApiWarehouseController extends Controller
{
    public function warehouse_save(Request $request){
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
            'name' => 'required|string|max:255|min:3',
            'contact_name' => 'required|string|max:255|min:3',
            'company' => 'required|string',
            'email' => 'nullable|email|max:50',
            'phone' => 'required|digits:10',
            'address' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'city' => 'required|string',
            'state' => 'required|string',
            'country_id' => 'required|numeric',
            'pincode' => 'required|numeric',
            'gst_no' => 'nullable|string',
          
        ]);
        if ($validator->fails()) {
            $response=response()->json(['error' => true,'message' => $validator->errors()], 422);
            //checking api activity logs]
            api_activity_logs($request,$response,$admin->id);
            return $response;
        }
        $user_id=$admin->id;

        $response = Api::warehouse($request, $user_id, $id=0);
       if( $response){
       $res=response()->json(['success' => true, 'message' => $response[1], 'warehouse_id' =>$response[0]], 200);
    //checking api activity logs
    api_activity_logs($request,$res,$user_id);
    return $res;
    }
       else{
           $response= response()->json(['error' => true, 'message' => 'Please contact admin'], 401); 
           //checking api activity logs
           api_activity_logs($request,$response,$user_id);
            return $response;
       }
    }




        public function deleteWarehouse($id)
        {
            $token = request()->bearerToken();
            $admin = DB::table('admins')->where('bearer_token', $token)->first();
            if (!$admin) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }else{
                if($admin->expired_on <= now()){
                 return response()->json(['error' => 'Token has expired. Please refresh your token.'], 401);   
                }
            }

            $user_id = $admin->id;
            $response = Api::deleteware($id,$user_id);
            $res=response()->json(['message' => $response], 200);
         //checking api activity logs
         api_activity_logs($id,$res,$user_id);
         return $res;           
        }

}