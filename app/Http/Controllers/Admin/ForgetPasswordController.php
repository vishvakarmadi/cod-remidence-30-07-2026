<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMessageToAdmin;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Hash;
use DB;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordController extends Controller
{
    public function index()
    {
    	return view('admin.auth.forget_password1');
    }
    public function forget_password1()
    {
    	return view('admin.auth.forget_password1');
    }
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $check_email = Admin::where('email',$request->email)
        ->where('delete_status', 0)
        ->first();
        if(!$check_email)
        {
        	return Redirect()->back()->with('error', 'Email address not found');
        }
        else
        {
//            $email_template_data = DB::table('email_templates')->where('id', 5)->first();
//            $subject = $email_template_data->et_subject;
//            $message = $email_template_data->et_content;
//
//            $token = hash('sha256',time());
//            $reset_link = url('admin/reset-password/'.$token.'/'.$check_email->id);
//            $message = str_replace('[[reset_link]]', $reset_link, $message);
            $subject = "Forgot Password";
            $token = hash('sha256', time());

            $reset_link = url('reset-password/' . $token . '/' . $check_email->id);

            $body = '
                <p>To reset your password, please click on the following link:</p>
                <p>
                    <a href="' . $reset_link . '" target="_blank">
                        Reset Password Link
                    </a>
                </p>
            ';

            Mail::html($body, function ($message) use ($request, $subject) {
                $message->to($request->email)
                        ->subject($subject);
            }); 

            $data['token'] = $token;
            Admin::where('email',$request->email)->update($data);

//            Mail::to($request->email)->send(new ResetPasswordMessageToAdmin($subject,$message));
        }

        return Redirect()->back()->with('success', 'Please check your email for reset instruction');
    }
    
    public function terms_condition()
    {
    	return view('admin.auth.terms_condition');
    }
    public function privacy()
    {
    	return view('admin.auth.privacy');
    }
    public function refund_cancellation()
    {
    	return view('admin.auth.refund_cancellation');
    }
    public function pricing_product()
    {
    	return view('admin.auth.pricing_product');
    }
    public function contact_us()
    {
    	return view('admin.auth.contact_us');
    }

}
