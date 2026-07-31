<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use App\Models\Admin\Country;
use App\Models\Admin\Integration;
use App\Models\Admin\Ratecard;
use App\Models\Admin\State;
use App\Models\Admin\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\EmailVerify;
use App\Models\Admin\Warehouse;
use App\Models\Admin\Company;
use App\Models\Admin\Currency;
use App\FileManager;
use Hash;
use DB;

class LoginController extends Controller
{
    public function index()
    {

        if(Auth::guard('admin')->user()) {
            return redirect()->route('admin.dashboard');
        }
    	return view('admin.auth.login1');
    }
    public function login1()
    
    {
        if(Auth::guard('admin')->user()) {
            return redirect()->route('admin.dashboard');
        }
    	return view('admin.auth.login1');
    }

    public function store(Request $request)
    {
        $subject = 'Test Subject';
        $message = 'This is msg';
       

        // try{
        //     Mail::to('ritesha412@gmail.com')->send(new EmailVerify($subject,$message));
        //     $msg = "Verification code sent to email address: ritesha412@gmail.com";
        // } catch(MailException $e){
        //     $msg = "Mail can't send";
        // }
        // echo $msg;die;
        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required',
        // ]);
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            if(Auth::guard('admin')->user()->delete_status =='0'){
                $admin_d = Admin::where('id', Auth::guard('admin')->user()->id)->first();
                $is_first_login = empty($admin_d->last_login);
                $admin_d->last_login = now();
                $admin_d->save();

                if ($is_first_login) {
                    try {
                        $first_login_body = "User " . $admin_d->name . " (" . $admin_d->email . ") has logged into their Hyloship seller account for the first time.\n\n"
                            . "User Code: " . ($admin_d->user_code ?? 'N/A') . "\n"
                            . "Mobile: " . ($admin_d->mobile ?? 'N/A');

                        Mail::raw($first_login_body, function ($message) use ($admin_d) {
                            $message->to('sales@hyloship.com')
                                    ->cc([
                                        'dinanath.kumar@aframaxlogistics.com',
                                        'operations@hyloship.com',
                                        'adityav3011@gmail.com'
                                    ])
                                    ->subject("First Login by User: " . $admin_d->name);
                        });

                        $client_login_body = "Dear " . ($admin_d->name ?? 'User') . ",\n\n"
                            . "Welcome back! You have successfully logged into your Hyloship seller account for the first time.\n\n"
                            . "To start shipping, please make sure to complete your profile & KYC submission, and set up your warehouse address.\n\n"
                            . "If you need any assistance, feel free to contact us at operations@hyloship.com.\n\n"
                            . "Best Regards,\n"
                            . "Team Hyloship";

                        Mail::raw($client_login_body, function ($message) use ($admin_d) {
                            $message->to($admin_d->email)
                                    ->cc('sales@hyloship.com')
                                    ->subject("Welcome to Hyloship - Getting Started");
                        });
                    } catch (\Throwable $mailEx) {
                        // Ignore to prevent breaking login flow
                    }
                }

                createlogs('User-login','login',$admin_d->id);
                if(Auth::guard('admin')->user()->role_id == 1)
                {
                    
                    return redirect()->route('admin.dashboard');
                }
                else
                {
                    return redirect()->route('employee.dashboard');
                }
            }else{
                // deleted user, redirect back with error message
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
               return redirect('login')->with('error', 'User is inactive,please contact admin');
            }
        }
    
        // Authentication failed, redirect back with error message
        return redirect('login')->with('error', 'Invalid username or password');
    }

    public function new()
    {
        if(Auth::guard('admin')->user()) {
            $admin_d = Admin::where('id', Auth::guard('admin')->user()->id)->first();
            $admin_d->last_login = now();
            $admin_d->save();
            return redirect()->route('admin.dashboard');
        }
    	return view('admin.auth.register1');
    }
    
    public function register1()
    {
        if(Auth::guard('admin')->user()) {
            $admin_d = Admin::where('id', Auth::guard('admin')->user()->id)->first();
            $admin_d->last_login = now();
            $admin_d->save();
            return redirect()->route('admin.dashboard');
        }
    	return view('admin.auth.register1');
    }

    //register function
    public function register($company_code)
    {
        $company = Company::where('company_code', $company_code)->first();
        if (!$company) {
            abort(404);
        }
        $company_id = $company->id;

        if(Auth::guard('admin')->user()) {
            $admin_d = Admin::where('id', Auth::guard('admin')->user()->id)->first();
            $admin_d->last_login = now();
            $admin_d->save();
            return redirect()->route('admin.dashboard');
        }
    	return view('admin.auth.register1', ['company_id' => $company_id,'company_code' => $company_code]);
    }

    public function register_store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6',
            're_password' => 'required|same:password',
            'mobile' => 'required|numeric|digits:10',
            'company_name' => 'required|string',
          	'volume' => 'required',
            'company_address' => 'required|string',
        ])->validate();

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->company_name = $request->company_name;
      	$admin->company_url = $request->company_url ?? null;
        $admin->company_address = $request->company_address;
        $admin->company_id = 1;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->mobile = $request->mobile;
        $admin->volume = $request->volume;
        $admin->role_id = $request->role_id;
        $admin->user_code = Admin::generateUniqueUserCode();
        $otp = mt_rand(1000, 9999);
        $admin->otp = $otp;
        $admin->save();

        try {
            $reg_body = "A new seller/user has registered on Hyloship.\n\nName: " . $admin->name . "\nEmail: " . $admin->email . "\nMobile: " . $admin->mobile . "\nCompany Name: " . $admin->company_name . "\nCompany Address: " . $admin->company_address;
            Mail::raw($reg_body, function ($message) use ($admin) {
                $message->to('sales@hyloship.com')
                        ->cc([
                            'dinanath.kumar@aframaxlogistics.com',
                            'operations@hyloship.com',
                            'adityav3011@gmail.com'
                        ])
                        ->subject("New User Registration: " . $admin->name);
            });
        } catch (\Throwable $mailEx) {
            \Illuminate\Support\Facades\Log::error('Registration Admin Mail Error: ' . $mailEx->getMessage());
        }

        try {
            // Fetch logo and login URL for the email
            $general_setting = DB::table('general_settings')->where('id', 1)->first();
            $logo = $general_setting->logo ?? null;
            $logo_url = $logo ? asset('public/uploads/' . $logo) : null;
            $login_url = route('admin.login');

            // Send professional HTML welcome email to the client/seller
            Mail::send('admin.emails.welcome_client', [
                'admin' => $admin,
                'logo_url' => $logo_url,
                'login_url' => $login_url
            ], function ($message) use ($admin) {
                $message->to($admin->email)
                        ->cc('sales@hyloship.com')
                        ->subject("Welcome to HyloShip - Seller Registration (KYC Action Required)");
            });
        } catch (\Throwable $mailEx) {
            \Illuminate\Support\Facades\Log::error('Registration Client Mail Error: ' . $mailEx->getMessage());
        }

//        $randotp = $admin->otp;
//        $email_template_data = DB::table('email_templates')->where('id', 9)->first();
//        $subject = $email_template_data->et_subject;
//        $message = $email_template_data->et_content;
//        $message = str_replace('[[name]]', $request->name, $message);
//        $message = str_replace('[[code]]', $randotp, $message);
//        $message = str_replace('[[website or app]]', 'Hyloship panel', $message);
//        $message = str_replace('[[customer portal]]', 'seller', $message);
//
//        try{
//            Mail::to($request->email)->send(new EmailVerify($subject,$message));
//            $msg = "Verification code sent to email address: " . $request->email;
//        } catch(MailException $e){
//            return $msg = "Mail can't send";
//        }

        $id = $admin->id;
        $get = Admin::where('id',$id)->first();
        $role = Role::where('id', request()->role_id)->first();
        $get->assignRole($role->name);
        $permissions = $get->getAllPermissions();
        
        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
             Auth::guard('admin')->logout();
             $request->session()->invalidate();
             return redirect('login')->with('success', 'Register Successfully');
        }
    
        return redirect()->back()->with('Invalid credentials');
    }

    public function otp_form(){
        $admin = auth()->guard('admin')->user();
        return view('admin.auth.otp',compact('admin'));
    }

    
    public function otpverify(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $id = $user->id;
        $admin = Admin::where('id', $id)->first();
      	$request->otp = $request->otp1.''.$request->otp2.''.$request->otp3.''.$request->otp4;
        if($admin->otp == $request->otp || $request->otp == '1515')
        {   
            if($admin->welcome_mail ==0 ){
                
                 $ratecards = Ratecard::where(['status' => 1,'user_id' => 0,'company_id'=>$user->company_id])->get();
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
                    
                $email_template_data = DB::table('email_templates')->where('id', 11)->where('company_id',$user->company_id)->first();
                $subject = $email_template_data->et_subject;
                $message = $email_template_data->et_content;
                $message = str_replace('[[name]]', $admin->name, $message);
                try{
                    Mail::to($request->email)->send(new EmailVerify($subject,$message));
                    $msg = "Verification code sent to email address: " . $request->email;
                } catch(\Exception $e){
                    return $msg = "Mail can't send";
                }
            }
            $admin->otp_verified = 1;
            $admin->welcome_mail = 1;
            $admin->last_login = now();
            $admin->save();
            
            return redirect()->route('admin.profile')->with('success', 'Email verified successfully and User is registered successfully!');
        }
            return Redirect()->route('admin.otp')->with('error', 'You Entered Code is wrong. Check your code and try again.');
    }
    public function otp()
    {
        $id = 1;
        // $id = Auth::guard('admin')->user()->id;
        $admin = Admin::where('id', $id)->first();
        return view('admin.auth.otp',compact('admin'));
    }
    
    public function profile()
    {
        $check = Profile::where('user_id', Auth::guard('admin')->user()->id)->exists();
        if($check){
            return redirect()->route('admin.kyc');
        }
        $countries = Country::get();
    	return view('admin.company.profile',compact('countries'));
    }

    public function states(Request $request)
    {
        $states = State::where("country_id", $request->country_id)->get();
    	return $states;
    }

    public function profile_store(Request $request)
    {
        
        if($request->file('doc_proof') != null){
        $doc_proofFilename = FileManager::upload($request->file('doc_proof'),'/uploads/');
        }else{
            $doc_proofFilename = null;
        }
        $panFilename = FileManager::upload($request->file('pan'),'/uploads/');
        $aadhaarFilename = FileManager::upload($request->file('aadhaar'),'/uploads/');
        $chequeFilename = FileManager::upload($request->file('cheque'),'/uploads/');
        $id = Auth::guard('admin')->user()->id;
        $chkprofile = Profile::where('user_id',$id)->first();
        if($chkprofile ==''){
            $profile = new Profile();

            $profile->user_id = $id;
            $profile->country = $request->country;
            $profile->state = $request->state;
            $profile->city = $request->city;
            $profile->address = $request->address;
            //
            if(isset($request->billing_same_personal_address)){
                $profile->billing_address = $request->address;
                $profile->billing_same_personal_address = '1';
            }else{
                $profile->billing_address = $request->billing_address;
                $profile->billing_same_personal_address = '0';
            }
            $profile->company_type_name = $request->company_type_name;

            //


            $profile->zip_code = $request->zip_code;
            $profile->company_type = $request->company_type;

            $profile->company_type_name = $request->company_type_name;
            $profile->aadhaar_no = $request->aadhaar_no;
            $profile->aadhaar = $aadhaarFilename;
            $profile->doc_type = 'Gst';
            $profile->doc_proof = $doc_proofFilename;//GST Doc

            $profile->pan_no = $request->pan_no;
            $profile->pan = $panFilename;
            $profile->gst = $request->gst;
            $profile->bank_name = $request->bank_name;
            $profile->beneficiary_name = $request->beneficiary_name;
            $profile->account_no = $request->account_no;
            $profile->ifsc_code = $request->ifsc_code;
            $profile->cheque = $chequeFilename;
            $profile->account_type = $request->account_type;
            $profile->save();

            $admin_kyc = Admin::where('id', $id)->first();
            $admin_kyc->kyc_status = '1';
            $admin_kyc->save();

            try {
                $user = Auth::guard('admin')->user();
                $kyc_body = "A user has completed their profile and KYC on Hyloship.\n\n"
                    . "User Name: " . ($user->name ?? 'N/A') . "\n"
                    . "Email: " . ($user->email ?? 'N/A') . "\n"
                    . "Company Type: " . ($request->company_type_name ?? 'N/A') . "\n"
                    . "Pan No: " . ($request->pan_no ?? 'N/A') . "\n"
                    . "Aadhaar No: " . ($request->aadhaar_no ?? 'N/A') . "\n"
                    . "GST No: " . ($request->gst ?? 'N/A');
                
                Mail::raw($kyc_body, function ($message) use ($user) {
                    $message->to('sales@hyloship.com')
                            ->cc([
                                'dinanath.kumar@aframaxlogistics.com',
                                'operations@hyloship.com',
                                'adityav3011@gmail.com'
                            ])
                            ->subject("KYC Submitted (Profile Setup): " . ($user->name ?? 'User'));
                });
            } catch (\Throwable $mailEx) {
                \Illuminate\Support\Facades\Log::error('Profile Store Admin Mail Error: ' . $mailEx->getMessage());
            }

            try {
                $user = Auth::guard('admin')->user();
                // Client confirmation email
                $client_kyc_body = "Dear " . ($user->name ?? 'User') . ",\n\n"
                    . "Thank you for completing your profile and submitting your KYC details on Hyloship.\n\n"
                    . "Our team is currently reviewing your documents (Aadhaar, PAN, GST, etc.). We will notify you via email as soon as your KYC verification is approved.\n\n"
                    . "Best Regards,\n"
                    . "Team Hyloship";

                Mail::raw($client_kyc_body, function ($message) use ($user) {
                    $message->to($user->email)
                            ->cc('sales@hyloship.com')
                            ->subject("KYC Documents Received - Hyloship");
                });
            } catch (\Throwable $mailEx) {
                \Illuminate\Support\Facades\Log::error('Profile Store Client Mail Error: ' . $mailEx->getMessage());
            }
            
            
            $warehouse = new Warehouse();
            $warehouse->user_id = $id;
            $warehouse->name = $request->ware_name;
            $warehouse->contact_name = $request->ware_contact_name;
            $warehouse->company = $request->ware_company;
            $warehouse->email = $request->ware_email;
            $warehouse->phone = $request->ware_phone;
            $warehouse->address = $request->ware_address;
            $warehouse->address_2 = $request->ware_address_2;
            $warehouse->city = $request->ware_city;
            $warehouse->state = $request->ware_state;
            $warehouse->country_id = $request->ware_country;
            $warehouse->pincode = $request->ware_pincode;
            $warehouse->latitude = $request->ware_latitude;
            $warehouse->longitude = $request->ware_longitude;
            $warehouse->gst_no = $request->ware_gst_no;
            $warehouse->fssai_licence = $request->ware_fssai_licence;
            $warehouse->note = $request->ware_note;
            $warehouse->default = $request->ware_default ?? 0;
            $warehouse->created_at = now();
            $warehouse->updated_at = now();
            $warehouse->save();

            $country = Country::find($request->ware_country);
            $warehouse_array = json_encode(
                array(
                    'phone'=>$request->ware_phone,
                    'city'=>$request->ware_city,
                    'name'=>$request->ware_company,
                    'pin'=>$request->ware_pincode,
                    'address'=>$request->ware_address.' '.$request->ware_address_2,
                    'country'=>$country['name'],
                    'email'=>$request->ware_email,
                    'registered_name'=>$request->ware_company,
                    'return_address'=>$request->ware_address.' '.$request->ware_address_2,
                    'return_pin'=>$request->ware_pincode,
                    'return_city'=>$request->ware_city,
                    'return_state'=>$request->ware_state,
                    'return_country'=>$country['name'],

                ),true
            );
            $warehouse_d =  Integration::create_warehouse($warehouse_array,'s');
           // $dat_creted =json_decode($warehouse_d,true);
        }
        if(Auth::guard('admin')->user()){
            $admin_d = Admin::where('id', Auth::guard('admin')->user()->id)->first();
            $admin_d->last_login = now();
            $admin_d->save();
        }
        return redirect()->route('admin.dashboard')->with('success', 'Registered successfully!');
    }

    public function manage_profile($id)
    {
        $countries = Country::get();
        $states = State::get();
        $currency = array();
        $admin = Admin::where('id',$id)->first();
        $profile = Profile::where('user_id',$id)->first();

    	return view('admin.company.manage_profile',compact('countries','currency','profile','admin','states'));
    }

    public function manage_profile_update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->business_name = $request->business_name;
        $admin->trade_name = $request->trade_name;
        $admin->license_key = $request->license_key;
        $admin->tin_number = $request->tin_number;
        $admin->vat_number = $request->vat_number;
        $admin->gst_number = $request->gst_number;
        $admin->description = $request->description;
        $admin->contact_email = $request->contact_email;
        $admin->enable_email = $request->enable_email;
        $admin->notification_email = $request->notification_email;
        $admin->contact_phone = $request->contact_phone;
        $admin->url = $request->url;
        $admin->currency = $request->currency;
        $admin->sla_time = $request->sla_time;
        $admin->mps_status = $request->mps_status;
        $admin->save();

        $check = Profile::where('user_id', $id)->first();
        if($check){
            $profile = Profile::where('user_id', $id)->first();
        } else {
            $profile = new Profile();
        }
        $profile->user_id = $id;
        $profile->country = $request->country;
        $profile->state = $request->state;
        $profile->city = $request->city;
        $profile->address = $request->address;
        $profile->zip_code = $request->zip_code;
        $profile->save();

        return redirect()->route('admin.dashboard')->with('success', 'User Profile edited successfully!');
    }
}