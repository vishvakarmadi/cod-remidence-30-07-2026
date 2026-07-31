<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Admin\Admin;
use App\Models\Admin\Ratecard;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Role;
use Hash;

require_once('vendor/laravel/socialite/src/Contracts/Factory.php');
require_once('vendor/laravel/socialite/src/Facades/Socialite.php');
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController  extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle(){
        try {
            
            $google_user = Socialite::driver('google')->user();
            $user = Admin::where('email',$google_user->getEmail())->first();
            if(isset($user->id)){
                $user->google_id = $google_user->getId();
                $user->google_pic = $google_user->getAvatar();
                $user->last_login = now();
                $user->save();
                Auth::guard('admin')->loginUsingId($user->id);
            }else{
                $admin = new Admin();
                $admin->name = $google_user->getName();
                $admin->email = $google_user->getEmail();
                $admin->password = Hash::make($google_user->getId());
                $admin->role_id = '4';
                $admin->google_id = $google_user->getId();
                $admin->google_pic = $google_user->getAvatar();
                $admin->otp_verified = 1;
                $admin->terms_condition_accept = '1';
                $admin->tc_accepted_at = now();
                $admin->last_login = now();
                $admin->save();
                $id = $admin->id;
                $get = Admin::where('id',$id)->first();
                $role = Role::where('id', '4')->first();
                $get->assignRole($role->name);
                $permissions = $get->getAllPermissions();

                $ratecards = Ratecard::where(['status' => 1,'user_id' => 0])->get();
                    foreach($ratecards as $ratecard){
                        $rc = new Ratecard();
                        $rc->courier_id = $ratecard->courier_id;
                        $rc->transport = $ratecard->transport;
                        $rc->weight = $ratecard->weight;
                        $rc->within_city = $ratecard->within_city;
                        $rc->within_state = $ratecard->within_state;
                        $rc->metro_to_metro = $ratecard->metro_to_metro;
                        $rc->rest_of_india = $ratecard->rest_of_india;
                        $rc->north_east = $ratecard->north_east;
                        $rc->cod_charges = $ratecard->cod_charges;
                        $rc->cod = $ratecard->cod;
                        $rc->user_id = $id;
                        $rc->save();
                    }
                Auth::guard('admin')->loginUsingId($id);       
            }
            return redirect()->route('admin.dashboard');

          } catch (\Exception $e) {
           echo  $e->getMessage();die;
          }
    }
}
