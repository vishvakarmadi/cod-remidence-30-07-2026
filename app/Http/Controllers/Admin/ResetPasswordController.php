<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\EmailVerify;
use Illuminate\Validation\Rule;
use DB;
use Hash;

class ResetPasswordController extends Controller
{
    public function index()
    {
        $id = substr(strrchr(url()->current(),'/'),1);
        $aa = Admin::find($id);
        $expected_url = url('reset-password/'.$aa->token.'/'.$aa->id);
        $current_url = url()->current();
        $email = $aa->email;
        $id = $aa->id;
        
        if($expected_url != $current_url) {
            return redirect()->route('admin.login');
        }
        return view('admin.auth.reset_password',compact('email','id'));
    }

    public function update(Request $request)
    {
        if($request->new_password != $request->retype_password){
            return Redirect()->back()->with('error', 'Passwords are not matching!');
        }
        $data['password'] = Hash::make($request->new_password);
        $data['token'] = '';
        Admin::where('email',$request->email)->update($data);
        
        
        
        return redirect()->route('admin.login')->with('success', 'Password is reset successfully!');
    }
}
