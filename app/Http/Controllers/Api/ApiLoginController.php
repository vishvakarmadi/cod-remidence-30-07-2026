<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin\Admin;

class ApiLoginController extends Controller
{
    public function shoplinelogin(Request $request)
    {
       
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Get credentials from the request
        $credentials = $request->only('email', 'password');

        // Check if credentials match an admin
        $admin = Admin::where('email', $credentials['email'])->first();
       
        if ($admin && Hash::check($credentials['password'], $admin->password)) {
            $expired_on =  date('Y-m-d H:i:s', strtotime('1 hour'));
            $passcodeen = hash('sha512',(now().''.$admin->id));
            $admin->bearer_token =$passcodeen; 
            $admin->expired_on =$expired_on; 
            $admin->save();
            $response=Response::json([
                'Bearer Token' => $passcodeen,
                'Expired on' => $expired_on,
            ], 200);
            //checking api activity logs 
            api_activity_logs($request,$response,$admin->id);
            return $response;
        } else {
            // Authentication failed
           $response=Response::json([
                'error' => 'Invalid credentials'
            ], 401);
            //checking api activity logs 
            api_activity_logs($request,$response);   
            return $response;
        }
   
    }


}