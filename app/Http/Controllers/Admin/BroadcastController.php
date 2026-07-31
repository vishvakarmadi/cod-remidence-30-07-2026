<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Broadcast;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use App\FileManager;
use Hash;
use DB;
use Illuminate\Support\Facades\Mail;


class BroadcastController extends Controller
{
	public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {   
        $user = Auth::guard('admin')->user();
        $current_company = $user->company_id;
        $broadcasts = Broadcast::where('active','1')->where('to_date','>=',date('Y-m-d'))->where('company_id',$current_company)->get();
        return view('admin.broadcast.index', compact('broadcasts'));
    }
    public function create()
    {
        $user = Auth::guard('admin')->user();
        $current_company = $user->company_id;
        $users = admin::where('active','1')->where('delete_status','0')->where('company_id',$current_company)->get();
    	return view('admin.broadcast.create', compact('users'));
    }
    
    public function store(Request $request)
    {
        if(isset($request->b_id)){
            $broadcast = Broadcast::find($request->b_id);
            $msg = 'Broadcast Updated Successfully!';
        }else{
            $broadcast = new Broadcast();
            $msg = 'Broadcast Created Successfully!';
        }
         $broadcast->message = $request->message;
         $broadcast->user_id = implode(',',$request->user_id);
         $broadcast->from_date = $request->from_date;
         $broadcast->to_date = $request->to_date;
         $broadcast->company_id = $request->company_id;
         $broadcast->save();

         // Send email to selected users
         if (!empty($request->user_id)) {
             $selectedUsers = Admin::whereIn('id', $request->user_id)->get();
             foreach ($selectedUsers as $u) {
                 if (!empty($u->email)) {
                     $actionText = isset($request->b_id) ? 'Updated' : 'New';
                     $subject = 'Important Announcement: ' . $actionText . ' Broadcast Message';
                     
                     $fromDate = date('d M Y', strtotime($request->from_date));
                     $toDate = date('d M Y', strtotime($request->to_date));
                     $issueTime = date('d M Y, h:i A');
                     $logoUrl = url('public/hyloshiplogo.jpeg');

                     $body = "
                          <div style='background-color: #f3f4f6; padding: 40px 15px; font-family: \"Segoe UI\", Tahoma, Geneva, Verdana, sans-serif; color: #1f2937;'>
                              <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);'>
                                  <!-- Header Gradient Banner -->
                                  <div style='background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); padding: 35px 30px; text-align: center;'>
                                      <img src='{$logoUrl}' alt='Hyloship Logo' style='height: 50px; margin-bottom: 12px; border-radius: 8px; background-color: #ffffff; padding: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);'>
                                      <br>
                                      <span style='display: inline-block; background-color: rgba(255, 255, 255, 0.2); color: #ffffff; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; padding: 6px 16px; border-radius: 50px; margin-bottom: 12px;'>
                                          {$actionText} Announcement
                                      </span>
                                      <h1 style='color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px; line-height: 1.3;'>
                                          Important Notice from Management
                                      </h1>
                                  </div>
                                  
                                  <!-- Main Content Body -->
                                  <div style='padding: 40px 30px;'>
                                      <p style='font-size: 16px; font-weight: 600; color: #111827; margin-top: 0; margin-bottom: 20px;'>
                                          Dear {$u->name},
                                      </p>
                                      <p style='font-size: 15px; color: #4b5563; line-height: 1.6; margin-bottom: 25px;'>
                                          An important announcement has been broadcast for your attention. Please find the details below:
                                      </p>
                                      
                                      <!-- Message Card Section -->
                                      <div style='background-color: #eef2ff; border-left: 5px solid #4f46e5; border-radius: 0 12px 12px 0; padding: 25px 20px; margin-bottom: 30px;'>
                                          <span style='display: block; font-size: 11px; font-weight: 800; color: #4f46e5; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;'>
                                              Announcement Message
                                          </span>
                                          <div style='font-size: 16px; color: #1e1b4b; line-height: 1.7; font-weight: 500;'>
                                              " . nl2br(e($request->message)) . "
                                          </div>
                                      </div>
                                      
                                      <!-- Timing & Validity Details Table -->
                                      <div style='background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 30px;'>
                                          <table style='width: 100%; border-collapse: collapse; font-size: 14px;'>
                                              <tr>
                                                  <td style='padding: 6px 0; color: #6b7280; font-weight: 500; width: 40%;'>Validity Period:</td>
                                                  <td style='padding: 6px 0; color: #111827; font-weight: 600; text-align: right;'>{$fromDate} to {$toDate}</td>
                                              </tr>
                                              <tr>
                                                  <td style='padding: 6px 0; color: #6b7280; font-weight: 500;'>Issued Date/Time:</td>
                                                  <td style='padding: 6px 0; color: #111827; font-weight: 600; text-align: right;'>{$issueTime}</td>
                                              </tr>
                                          </table>
                                      </div>

                                      <!-- Dashboard Callout -->
                                      <div style='text-align: center; margin-bottom: 10px;'>
                                          <p style='font-size: 13px; color: #9ca3af; margin: 0;'>
                                              You can also view and dismiss this announcement anytime directly on your dashboard.
                                          </p>
                                      </div>
                                  </div>
                                  
                                  <!-- Professional Footer -->
                                  <div style='background-color: #f9fafb; padding: 25px 30px; text-align: center; border-top: 1px solid #f3f4f6;'>
                                      <p style='font-size: 14px; font-weight: 600; color: #4b5563; margin: 0 0 5px 0;'>
                                          Best Regards,
                                      </p>
                                      <p style='font-size: 15px; font-weight: 700; color: #312e81; margin: 0 0 15px 0;'>
                                          Hyloship Administration
                                      </p>
                                      <p style='font-size: 11px; color: #9ca3af; margin: 0; line-height: 1.5;'>
                                          This is an automated system notification. Please do not reply directly to this email.
                                      </p>
                                  </div>
                              </div>
                          </div>
                      ";

                     Mail::html($body, function ($message) use ($u, $subject) {
                          $message->to($u->email)->subject($subject);
                     });
                 }
             }
         }

         return redirect()->route('admin.broadcast')->with('success', $msg);
    }
    
    public function destroy($id){
        Broadcast::where('id', $id)->delete();
        return Redirect()->back()->with('success', 'Broadcast is deleted successfully!');
    }
    
    public function edit($id){
        $broadcast = Broadcast::find($id);
        $userd = Auth::guard('admin')->user();
        $current_company = $userd->company_id;
        $users = admin::where('active','1')->where('delete_status','0')->where('company_id',$current_company)->get();
    	return view('admin.broadcast.create', compact('users','broadcast'));
    }
    
    public function hideuser(){
        $user_id = Auth::guard('admin')->user()->id;
        $broadcast = Broadcast::find(request('b_id'));
        $userIdsArray = explode(',', $broadcast->user_id);
        $userIdsArray = array_diff($userIdsArray, [$user_id]);
        $newUserIds = implode(',', $userIdsArray);
        $broadcast->user_id =$newUserIds;
        $broadcast->save();
//        echo ;die;
    }

    

}