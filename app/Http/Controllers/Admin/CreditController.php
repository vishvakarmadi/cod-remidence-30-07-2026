<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Mail\EmailVerify;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Order;
use Illuminate\Http\Request;
use App\Models\Admin\CreditRequest;
use App\Models\Admin\Admin;
use App\Models\Admin\Transaction;


class CreditController extends Controller
{
    public function requestForm(Request $request)
    {
           $userId = $request->query('user_id');//user id in the url from the users 
           return view('admin.credit.request', ['userId' => $userId]); // Ensure the view file exists at this path
        
    }

    public function requestCredit(Request $request)
    {   

    $credit_amount = $request->input('credit_amount');//credit amount
    $credit_describe = $request->input('credit_describe');//credit description
    $userId = $request->input('user_id');//user id from the url
    $req_user= Auth::guard('admin')->user();
    
    //validation as validator is not working 
    if (!is_numeric($credit_amount) || empty($credit_describe) ) {
        return redirect()->back()
                         ->withErrors(['error' => 'Invalid input data.'])
                         ->withInput();
    }

   //credit request table updation
    $creditRequest = CreditRequest::create([
        'user_id' => $userId,
        'requested_by'=>$req_user->id,
        'company_id'=>$req_user->company_id,
        'credit_amount' => $credit_amount,
        'credit_describe' => $credit_describe,
        'status' => 'pending',
    ]);

   //mail to 
    $recipientEmail = 'ritesha412@gmail.com';
    $ccEmail1 = 'ritesha412@gmail.com';
    $ccEmail2 = 'ritesha412@gmail.com';
    $subject = 'Approve or Not';  
    $message = '<p>User ID: ' . $userId . '</p>' .
               '<p>Credit Amount: ' . $credit_amount . '</p>' .
               '<p>Credit Description: ' . $credit_describe . '</p>' .
               '<p>Click <a href="' . url("/verify-request?user_id={$userId}") . '">here</a> to verify.</p>';  

    Mail::to($recipientEmail)
        ->cc([$ccEmail1, $ccEmail2])
        ->send(new EmailVerify($subject, $message));
        //success
    return redirect()->back()->with('success', 'Please check your email to verify');
    }
        
    public function showVerificationPage(Request $request)
    {
        //userid of the user from the url basically 
        $userId = $request->query('user_id');

             //checking if its authorised person to check the verification
        if ( Auth::guard('admin')->user()->id == 1 || Auth::guard('admin')->user()->id == 2 || Auth::guard('admin')->user()->id == 195) {
            $creditRequests = CreditRequest::where('user_id', $userId)->get();   
            return view('admin.credit.verification_page', [
                'userId' => $userId,
                'creditRequests' => $creditRequests
            ]);
        }

        
        return redirect('/admin/dashboard')->with('error', 'Unauthorized access.');
    }

    public function approveCreditRequest($id)
    {   //id of the credit request as it is updated
        $creditRequest = CreditRequest::find($id);
        $action_by=Auth::guard('admin')->user()->id;
        if ($creditRequest && $creditRequest->status != 'approved') {
            $creditRequest->status = 'approved';
            $creditRequest->action_by=$action_by;
            $creditRequest->save();
            //id of the user 
            $userId = $creditRequest->user_id;
            $credit_amount=$creditRequest->credit_amount;
            $credit_describe=$creditRequest->credit_describe;
            $admin = Admin::find($userId);
            //admin table updation
            if ($admin) {
                $old_limit = $admin->limit_loan;
                $admin->limit_loan = $old_limit + $credit_amount;
                $admin->loan_amount = $admin->loan_amount + $credit_amount;
                $admin->loan_date = now();
                $admin->save();
            }
            $recipientEmail=$admin->email;
            $ccEmail1='ritesha412@gmail.com';
            $ccEmail2='ritesha412@gmail.com';
            $ccEmail3='ritesha412@gmail.com';
            $subject = 'Request For Credit Limit';
            $message = '<p>Hello,</p>' .
           '<p>We are pleased to inform you that your credit request has been approved. Here are the details of your approved request:</p>' .
           '<p><strong>User ID:</strong> ' . $userId . '</p>' .
           '<p><strong>Name:</strong> ' . $admin->name . '</p>' .
           '<p><strong>Credit Amount:</strong> ₹' .  number_format((float)$credit_amount, 2). '</p>' .
           '<p><strong>Previous Limit:</strong> ₹' .  number_format((float)$old_limit, 2). '</p>' .
           '<p><strong>Revised Limit:</strong> ₹' .  number_format((float)$admin->limit_loan, 2). '</p>' .
           '<p><strong>Credit Description:</strong> ' . htmlspecialchars($credit_describe) . '</p>' .
           '<p>If you have any questions or need further assistance, please feel free to contact us @hello@hyloship.com.</p>' .
           '<p>Thank you for your patience.</p>' .
           '<p>Best regards,<br>Hyloship</p>';

           
            Mail::to($recipientEmail)
             ->cc([$ccEmail1, $ccEmail2, $ccEmail3])
            ->send(new EmailVerify($subject, $message));  
            //transaction table updated
           $transaction = new Transaction();
           $transaction->order_id = 0;
           $transaction->user_id = $userId;
           $transaction->awb;
           $transaction->tracking_info = " ";
           $transaction->credit = $credit_amount;
           $transaction->debit = 0.00;
           $transaction->closing_blc ;
           $transaction->remarks ="Limit changed from $old_limit to {$admin->limit_loan}" ;
           $transaction->save();
            return redirect()->to("/verify-request?user_id={$userId}")
            ->with('success', 'Credit request approved successfully.');
        }
        return redirect()->back()->with('error', 'Credit request not found.');
    }


    public function decline($id)
    {
        //similarly of the approve
        $action_by=Auth::guard('admin')->user()->id;
        $creditRequest = CreditRequest::findOrFail($id);
        $creditRequest->status = 'declined';
        $creditRequest->action_by=$action_by;
        $creditRequest->save();
        $credit_amount=$creditRequest->credit_amount;
        $credit_describe=$creditRequest->credit_describe;
        $userId = $creditRequest->user_id;
        $admin = Admin::find($userId);
        $recipientEmail=$admin->email;
        $ccEmail1='ritesha412@gmail.com';
        $ccEmail2='ritesha412@gmail.com';
        $ccEmail3='ritesha412@gmail.com';
        $subject = 'Request For Credit Limit';
        $message = '<p>Hello,</p>' .
       '<p>We are sorry to inform you that your credit request has been Declined. Here are the details of your Decline request:</p>' .
       '<p><strong>User ID:</strong> ' . $userId . '</p>' .
       '<p><strong>Credit Amount:</strong> ₹' .  number_format((float)$credit_amount, 2). '</p>' .
       '<p><strong>Credit Description:</strong> ' . htmlspecialchars($credit_describe) . '</p>' .
       '<p>If you have any questions or need further assistance, please feel free to contact us @hello@hyloship.com.</p>' .
       '<p>Thank you for your patience.</p>' .
       '<p>Best regards,<br>Hyloship</p>';

       Mail::to($recipientEmail)
       ->cc([$ccEmail1, $ccEmail2, $ccEmail3])
       ->send(new EmailVerify($subject, $message));                

        $transaction = new Transaction();
        $transaction->order_id = 0;
        $transaction->user_id = $userId;
        $transaction->awb;
        $transaction->tracking_info = " ";
        $transaction->credit = $credit_amount;
        $transaction->debit = 0.00;
        $transaction->closing_blc ;
        $transaction->remarks ="Limit Request Declined" ;
        $transaction->save();
    

        return redirect()->to("/verify-request?user_id={$userId}")
            ->with('success', 'Credit request declined successfully.');
    }
}
