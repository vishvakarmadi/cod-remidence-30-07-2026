<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;

class GreetingController extends Controller
{
    public function showGreeting()
    {
        $token = request()->bearerToken();
        $admin = Admin::where('bearer_token',$token)->first(); 
        if (!$admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }else{
            if($admin->expired_on <= now()){
             return response()->json(['error' => 'Token has expired. Please refresh your token.'], 401);   
            }
        }

        return response()->json([
            'message' => 'Hi, ' . $admin->name . '!',
        ]);
        
    }
    

}