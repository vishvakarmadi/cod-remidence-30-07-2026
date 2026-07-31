<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Profile;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\FileManager;
use Hash;


class KycController extends Controller
{
	public function __construct()
    {
    	$this->middleware(function ($request, $next) {
			if($request->session()->has('admin')) {
				return redirect()->route('admin.dashboard');
			}
			return $next($request);
		});
    }

    public function kyc_create()
    {
        $user = Admin::whereId(Auth::guard('admin')->user()->id)->first();
        $kyc = Profile::where('user_id',Auth::guard('admin')->user()->id)->first();
    	return view('admin.company.kyc',compact('kyc','user'));
    }


    public function kyc_store(Request $request)
    {
        $check = Profile::where('user_id',Auth::guard('admin')->user()->id)->exists();
        if($check){
            $profile = Profile::where('user_id',Auth::guard('admin')->user()->id)->first();
//            echo $request->file('doc_proof');die;
            if($request->file('doc_proof')){
                if($profile->doc_proof){
                    $doc_proofFilename = FileManager::update($request->file('doc_proof'),'/uploads/',$profile->doc_proof);
                    $profile->doc_proof = $doc_proofFilename;
                }else{
                   $doc_proofFilename = FileManager::upload($request->file('doc_proof'),'/uploads/'); 
                   $profile->doc_proof = $doc_proofFilename;
                }
            }
            if($request->file('pan')){
                $panFilename = FileManager::update($request->file('pan'),'/uploads/',$profile->pan);
                $profile->pan = $panFilename;
            }
            if($request->file('cheque')){
                $chequeFilename = FileManager::update($request->file('cheque'),'/uploads/',$profile->cheque);
                $profile->cheque = $chequeFilename;
            }
            if($request->file('aadhaar')){
                $aadhaarFilename = FileManager::upload($request->file('aadhaar'),'/uploads/');
                $profile->aadhaar = $aadhaarFilename;
            }
        } else {
            if($request->file('doc_proof') != null){
                $doc_proofFilename = FileManager::upload($request->file('doc_proof'),'/uploads/');
            }else{
                $doc_proofFilename = null;
            }
            $panFilename = FileManager::upload($request->file('pan'),'/uploads/');
            $chequeFilename = FileManager::upload($request->file('cheque'),'/uploads/');
            $aadhaarFilename = FileManager::upload($request->file('aadhaar'),'/uploads/');
    
            $profile = new Profile();
            $profile->doc_proof = $doc_proofFilename;//GST Doc
            $profile->pan = $panFilename;
            $profile->cheque = $chequeFilename;
            $profile->aadhaar = $aadhaarFilename;
        }

        $id = Auth::guard('admin')->user()->id;
        $profile->user_id = $id;
        $profile->company_type = $request->company_type;
        $profile->company_type_name = $request->company_type_name;
        $profile->aadhaar_no = $request->aadhaar_no;
        $profile->doc_type = 'Gst';
        $profile->pan_no = $request->pan_no;
        $profile->gst = $request->gst;
        $profile->bank_name = $request->bank_name;
        $profile->bank_name_other = $request->bank_name_other;
        $profile->account_type = $request->account_type;
        $profile->beneficiary_name = $request->beneficiary_name;
        $profile->account_no = $request->account_no;
        $profile->ifsc_code = $request->ifsc_code;
        $profile->save();

        Admin::whereId($id)->update(['kyc_status' => 1]);

        try {
            $user = Auth::guard('admin')->user();
            $kyc_body = "A user has submitted/updated their eKYC details on Hyloship.\n\n"
                . "User Name: " . ($user->name ?? 'N/A') . "\n"
                . "Email: " . ($user->email ?? 'N/A') . "\n"
                . "Company Name: " . ($request->company_type_name ?? 'N/A') . "\n"
                . "Pan No: " . ($request->pan_no ?? 'N/A') . "\n"
                . "Aadhaar No: " . ($request->aadhaar_no ?? 'N/A') . "\n"
                . "GST No: " . ($request->gst ?? 'N/A');
            
            \Illuminate\Support\Facades\Mail::raw($kyc_body, function ($message) use ($user) {
                $message->to('sales@hyloship.com')
                        ->cc([
                            'dinanath.kumar@aframaxlogistics.com',
                            'operations@hyloship.com',
                            'adityav3011@gmail.com'
                        ])
                        ->subject("KYC Submitted: " . ($user->name ?? 'User'));
            });
        } catch (\Throwable $mailEx) {
            \Illuminate\Support\Facades\Log::error('Kyc Store Admin Mail Error: ' . $mailEx->getMessage());
        }

        try {
            $user = Auth::guard('admin')->user();
            // Fetch logo and login URL for the email
            $general_setting = \DB::table('general_settings')->where('id', 1)->first();
            $logo = $general_setting->logo ?? null;
            $logo_url = $logo ? asset('public/uploads/' . $logo) : null;
            $login_url = route('admin.login');

            // Client confirmation email
            \Illuminate\Support\Facades\Mail::send('admin.emails.kyc_received', [
                'user' => $user,
                'logo_url' => $logo_url,
                'login_url' => $login_url
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->cc('sales@hyloship.com')
                        ->subject("KYC Documents Received - Hyloship");
            });
        } catch (\Throwable $mailEx) {
            \Illuminate\Support\Facades\Log::error('Kyc Store Client Mail Error: ' . $mailEx->getMessage());
        }

        return redirect()->back()->with('success', 'Kyc Updated successfully!');
    }

    public function kyc_show($id)
    {
        $user = Admin::whereId($id)->first();
        $kyc = Profile::where('user_id',$id)->first();
        if($kyc){
            $parents = Admin::where('delete_status', 0)
                ->where('id', '!=', $id)
                ->where(function($query) {
                    $query->whereIn('role_id', [1, 2])
                          ->orWhere(function($q) {
                              $q->where('role_id', 3)->where('role_action', '1');
                          });
                })
                ->orderBy('name', 'asc')
                ->get();
            return view('admin.company.kycview',compact('kyc','user','parents'));
        }else{
            return redirect()->back()->with('error', 'No data found');
        }
    }

    public function approve($id, Request $request)
    {
        $kycs = Admin::with('profile')->where('id',$id)->first();

        if ($request->has('parent_id') && $request->parent_id != '') {
            $kycs->parent_id = $request->parent_id;
        }

        if ($request->has('approve') && $request->approve != '') {
            $kycs->kyc_status = $request->approve;
        }

        $kycs->save();

        if ($request->approve == 2) {
            try {
                // Fetch logo and login URL
                $general_setting = \DB::table('general_settings')->where('id', 1)->first();
                $logo = $general_setting->logo ?? null;
                $logo_url = $logo ? asset('public/uploads/' . $logo) : null;
                $login_url = route('admin.login');

                \Illuminate\Support\Facades\Mail::send('admin.emails.kyc_approved', [
                    'kycs' => $kycs,
                    'logo_url' => $logo_url,
                    'login_url' => $login_url
                ], function ($message) use ($kycs) {
                    $message->to($kycs->email)
                            ->cc('sales@hyloship.com')
                            ->subject("Your KYC Has Been Approved – Welcome to HyloShip!");
                });
            } catch (\Throwable $mailEx) {
                // Ignore to prevent breaking the flow
            }
        }

        return redirect()->route('admin.role.user')->with('success', 'KYC and Parent ID updated successfully!');
    }
}