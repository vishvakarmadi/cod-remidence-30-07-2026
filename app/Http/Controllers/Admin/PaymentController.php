<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\PageProjectItem;
use App\Models\Admin\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Admin\Coupon;
use App\Models\Admin\Admin;
use App\Models\Admin\Order;
use App\Models\Admin\Country;
use App\Models\Admin\Courier;
use App\Models\Admin\OrderDetail;
use App\Models\Admin\InvoiceDetail;
use App\Models\Admin\Order_lost_payment;
use App\Models\Admin\Warehouse;
use App\Models\Admin\Transaction;
use App\Models\Admin\Invoice;
use App\Models\Admin\Recharge;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;


use App\Models\User;

use Razorpay\Api\Api;
use Illuminate\Support\Facades\Schema;
use DB;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use PDF;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->razorpayKey = env('RAZORPAY_KEY'); 
        $this->razorpaySecret = env('RAZORPAY_SECRET'); 
    }
    public function create_coupon(){
        return view('admin.coupon.create');
    }

    public function add_wallet()
    {
        $user = auth()->guard('admin')->user();
        $userId = $user->id;
        $userEmail = trim($user->email ?? '');
        $companyId = $user->company_id;

        $hasUsedCoupon = false;

        // Check in Recharge table by user_id
        if (Recharge::where('user_id', $userId)->where('coupon_amount', '>', 0)->exists()) {
            $hasUsedCoupon = true;
        }

        // Check in Recharge table by company_id (if valid and > 1)
        if (!$hasUsedCoupon && $companyId !== null && $companyId !== '' && (int)$companyId > 1) {
            if (Recharge::where('company_id', $companyId)->where('coupon_amount', '>', 0)->exists()) {
                $hasUsedCoupon = true;
            }
        }

        // Check in Payments table by email (if email is not empty and is valid)
        if (!$hasUsedCoupon && !empty($userEmail) && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            if (Payment::where('user_email', $userEmail)->where('coupon_amount', '>', 0)->exists()) {
                $hasUsedCoupon = true;
            }
        }

        // Check in Payments table by company_id (if valid and > 1)
        if (!$hasUsedCoupon && $companyId !== null && $companyId !== '' && (int)$companyId > 1) {
            if (Payment::where('company_id', $companyId)->where('coupon_amount', '>', 0)->exists()) {
                $hasUsedCoupon = true;
            }
        }

        if ($hasUsedCoupon) {
            $coupons = collect();
        } else {
            $coupons = Coupon::query()->where([
                ['coupon_status', '=', 'Show'],
                ['coupon_start_date', '<=', now()],
                ['coupon_end_date', '>=', now()]
            ])->get();
        }

        return view('admin.payment.add', compact('coupons'))->with('success', 'Project Page Content is updated successfully!');
    }
    public function add_money(Request $request)
    {   
        $razorpayResponse = json_decode($request->input('razorpay_response'), true);
        $input = $request->all();
        $api = new Api (env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $payment = $api->payment->fetch($razorpayResponse['razorpay_payment_id']);

        if(count($input) && !empty($razorpayResponse['razorpay_payment_id'])) {
            try {
                // Check if payment is already processed in our system to prevent double crediting
                if (Payment::where('r_payment_id', $razorpayResponse['razorpay_payment_id'])->exists()) {
                    if ($request->ajax()) {
                        session()->flash('success', 'Payment Successful.');
                        return response()->json(['status' => 'success', 'message' => 'Payment Successful.']);
                    }
                    return redirect()->back()->with('success', 'Payment Successful.');
                }

                $payment = $api->payment->fetch($razorpayResponse['razorpay_payment_id']);
                
                if ($payment['status'] === 'captured') {
                    $response = $payment;
                } else {
                    try {
                        $response = $payment->capture(array('amount' => $payment['amount'], 'currency' => $payment['currency'] ?? 'INR'));
                    } catch (\Exception $captureException) {
                        if (str_contains(strtolower($captureException->getMessage()), 'already been captured')) {
                            // If auto-captured by Razorpay during race condition, fetch and proceed
                            $response = $api->payment->fetch($razorpayResponse['razorpay_payment_id']);
                        } else {
                            throw $captureException;
                        }
                    }
                }
                
                if($response['email'] ==''){
                    $response['email'] = 'test'.Auth::guard('admin')->user()->id;
                }
                
                $capturedAmount = $response['amount']/100;
                $coupon_amt = 0;
                $id = Auth::guard('admin')->user()->id;

                // Handle coupon cashbacks for online payment (WELCOME, HYLO200, HYLO500)
                if ($request->filled('coupon_code')) {
                    $couponCodeUpper = strtoupper(trim($request->coupon_code));
                    
                    $userEmail = trim(Auth::guard('admin')->user()->email ?? '');
                    $comp = Auth::guard('admin')->user()->company_id;
                    $hasUsedCoupon = false;

                    // Check in Recharge table by user_id
                    if (Recharge::where('user_id', $id)->where('coupon_amount', '>', 0)->exists()) {
                        $hasUsedCoupon = true;
                    }

                    // Check in Recharge table by company_id (if valid and > 1)
                    if (!$hasUsedCoupon && $comp !== null && $comp !== '' && (int)$comp > 1) {
                        if (Recharge::where('company_id', $comp)->where('coupon_amount', '>', 0)->exists()) {
                            $hasUsedCoupon = true;
                        }
                    }

                    // Check in Payments table by email (if email is not empty and is valid)
                    if (!$hasUsedCoupon && !empty($userEmail) && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                        if (Payment::where('user_email', $userEmail)->where('coupon_amount', '>', 0)->exists()) {
                            $hasUsedCoupon = true;
                        }
                    }

                    // Check in Payments table by company_id (if valid and > 1)
                    if (!$hasUsedCoupon && $comp !== null && $comp !== '' && (int)$comp > 1) {
                        if (Payment::where('company_id', $comp)->where('coupon_amount', '>', 0)->exists()) {
                            $hasUsedCoupon = true;
                        }
                    }
                    
                    if (!$hasUsedCoupon) {
                        if ($couponCodeUpper === 'WELCOME') {
                            if ($capturedAmount >= 5000) {
                                $coupon_amt = 1000;
                            }
                        } elseif ($couponCodeUpper === 'HYLO200') {
                            if ($capturedAmount >= 1000) {
                                $coupon_amt = 200;
                            }
                        } elseif ($couponCodeUpper === 'HYLO500') {
                            if ($capturedAmount >= 2000) {
                                $coupon_amt = 500;
                            }
                        }
                    }
                }

                $paymentRecord = Payment::create([
                    'r_payment_id' => $response['id'],
                    'method' => $response['method'],
                    'currency' => $response['currency'],
                    'user_email' => $response['email'],
                    'amount' => $capturedAmount,
                    'coupon_amount' => $coupon_amt,
                    'company_id' => Auth::guard('admin')->user()->company_id,
                    'json_response' => json_encode((array)$response)
                ]);

                // Log the record in wallet_recharge table to track for future first-recharge checks
                Recharge::create([
                    'user_id' => $id,
                    'payment_id' => $response['id'],
                    'payment_amount' => $capturedAmount,
                    'coupon_amount' => $coupon_amt,
                    'coupon_code' => $request->filled('coupon_code') ? strtoupper(trim($request->coupon_code)) : null,
                    'company_id' => Auth::guard('admin')->user()->company_id,
                    'recharge_done_by' => $id
                ]);

                $wallet = (Auth::guard('admin')->user()->wallet_blc) + $capturedAmount + $coupon_amt;
                
                $transaction = new Transaction();
                $transaction->order_id = 0;
                $transaction->user_id = $id;
                $transaction->tracking_info = 'Wallet Recharge with razorpay id '.$razorpayResponse['razorpay_payment_id'];
                $transaction->credit = $capturedAmount + $coupon_amt;
                if (Schema::hasColumn('transactions', 'coupon_amount')) {
                    $transaction->coupon_amount = $coupon_amt;
                }
                if (Schema::hasColumn('transactions', 'company_id')) {
                    $transaction->company_id = Auth::guard('admin')->user()->company_id;
                }
                $transaction->debit = "0.00";
                $transaction->closing_blc = $wallet;
                $transaction->remarks = json_encode((array)$response);
                $transaction->save();

                $admin = Admin::where('id',$id)->first();
                $admin->wallet_blc = $wallet;
                $admin->save();

                $msg = 'Payment Successful.' . ($coupon_amt > 0 ? " Cashback of ₹{$coupon_amt} applied!" : "");
                if ($request->ajax()) {
                    session()->flash('success', $msg);
                    return response()->json(['status' => 'success', 'message' => $msg]);
                }
                return redirect()->back()->with('success', $msg); 
            } catch(\Exception $e) {
                \Log::error('Razorpay Capture Error: ' . $e->getMessage());
                if ($request->ajax()) {
                    session()->flash('error', $e->getMessage());
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
                }
                Session::put('error', $e->getMessage());
                return redirect()->back();
            }
        }
    }

    public function razorpayWebhook(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        \Log::info('Razorpay Webhook Received', ['event' => $data['event'] ?? 'none', 'payload' => $data]);

        $webhookSecret = env('RAZORPAY_WEBHOOK_SECRET');
        if (!empty($webhookSecret)) {
            $signature = $request->header('X-Razorpay-Signature');
            try {
                $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
                $api->utility->verifyWebhookSignature($request->getContent(), $signature, $webhookSecret);
            } catch (\Exception $e) {
                \Log::error('Razorpay Webhook Signature Verification Failed: ' . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => 'Invalid Signature'], 400);
            }
        }

        if (isset($data['event']) && $data['event'] === 'payment.captured') {
            $paymentEntity = $data['payload']['payment']['entity'];
            $paymentId = $paymentEntity['id'];

            // Check if we already processed this payment
            if (Payment::where('r_payment_id', $paymentId)->exists()) {
                return response()->json(['status' => 'success', 'message' => 'Payment already processed']);
            }

            $capturedAmount = $paymentEntity['amount'] / 100;
            $email = $paymentEntity['email'] ?? '';
            $notes = $paymentEntity['notes'] ?? [];

            $userId = isset($notes['user_id']) ? (int)$notes['user_id'] : null;
            $couponCode = isset($notes['coupon_code']) ? trim($notes['coupon_code']) : '';

            // Fallback: look up user by email
            if (!$userId && !empty($email)) {
                $user = Admin::where('email', $email)->first();
                if ($user) {
                    $userId = $user->id;
                }
            }

            if (!$userId) {
                \Log::error("Razorpay Webhook Error: User not found for payment ID $paymentId");
                return response()->json(['status' => 'error', 'message' => 'User not found'], 400);
            }

            $user = Admin::find($userId);
            if (!$user) {
                \Log::error("Razorpay Webhook Error: User ID $userId not found in database.");
                return response()->json(['status' => 'error', 'message' => 'User not found'], 400);
            }

            $coupon_amt = 0;
            if (!empty($couponCode)) {
                $couponCodeUpper = strtoupper(trim($couponCode));
                $comp = $user->company_id;
                $hasUsedCoupon = false;

                // Check in Recharge table by user_id
                if (Recharge::where('user_id', $userId)->where('coupon_amount', '>', 0)->exists()) {
                    $hasUsedCoupon = true;
                }

                // Check in Recharge table by company_id
                if (!$hasUsedCoupon && $comp !== null && $comp !== '' && (int)$comp > 1) {
                    if (Recharge::where('company_id', $comp)->where('coupon_amount', '>', 0)->exists()) {
                        $hasUsedCoupon = true;
                    }
                }

                // Check in Payments table by email
                if (!$hasUsedCoupon && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    if (Payment::where('user_email', $email)->where('coupon_amount', '>', 0)->exists()) {
                        $hasUsedCoupon = true;
                    }
                }

                // Check in Payments table by company_id
                if (!$hasUsedCoupon && $comp !== null && $comp !== '' && (int)$comp > 1) {
                    if (Payment::where('company_id', $comp)->where('coupon_amount', '>', 0)->exists()) {
                        $hasUsedCoupon = true;
                    }
                }

                if (!$hasUsedCoupon) {
                    if ($couponCodeUpper === 'WELCOME') {
                        if ($capturedAmount >= 5000) {
                            $coupon_amt = 1000;
                        }
                    } elseif ($couponCodeUpper === 'HYLO200') {
                        if ($capturedAmount >= 1000) {
                            $coupon_amt = 200;
                        }
                    } elseif ($couponCodeUpper === 'HYLO500') {
                        if ($capturedAmount >= 2000) {
                            $coupon_amt = 500;
                        }
                    }
                }
            }

            \DB::beginTransaction();
            try {
                // Create payment record
                Payment::create([
                    'r_payment_id' => $paymentId,
                    'method' => $paymentEntity['method'] ?? 'unknown',
                    'currency' => $paymentEntity['currency'] ?? 'INR',
                    'user_email' => $email,
                    'amount' => $capturedAmount,
                    'coupon_amount' => $coupon_amt,
                    'company_id' => $user->company_id,
                    'json_response' => json_encode((array)$paymentEntity)
                ]);

                // Create recharge record
                Recharge::create([
                    'user_id' => $userId,
                    'payment_id' => $paymentId,
                    'payment_amount' => $capturedAmount,
                    'coupon_amount' => $coupon_amt,
                    'coupon_code' => !empty($couponCode) ? strtoupper(trim($couponCode)) : null,
                    'company_id' => $user->company_id,
                    'recharge_done_by' => $userId
                ]);

                $wallet = ($user->wallet_blc) + $capturedAmount + $coupon_amt;

                $transaction = new Transaction();
                $transaction->order_id = 0;
                $transaction->user_id = $userId;
                $transaction->tracking_info = 'Wallet Recharge (Webhook) with razorpay id ' . $paymentId;
                $transaction->credit = $capturedAmount + $coupon_amt;
                if (Schema::hasColumn('transactions', 'coupon_amount')) {
                    $transaction->coupon_amount = $coupon_amt;
                }
                if (Schema::hasColumn('transactions', 'company_id')) {
                    $transaction->company_id = $user->company_id;
                }
                $transaction->debit = "0.00";
                $transaction->closing_blc = $wallet;
                $transaction->remarks = json_encode((array)$paymentEntity);
                $transaction->save();

                $user->wallet_blc = $wallet;
                $user->save();

                \DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Wallet updated successfully']);
            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error("Razorpay Webhook DB Transaction Error: " . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => 'Database transaction failed'], 500);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Event ignored']);
    }

    public function cod(Request $request){
        $lastremit = Auth::guard('admin')->user()->last_remit_amount;
        if($request->paymentMethod == 2){
            if(($request->amount) > $lastremit){
                $cod = $request->amount - $lastremit;
                $wallet = (Auth::guard('admin')->user()->wallet_blc)  + ($cod);

                $id = Auth::guard('admin')->user()->id;
                
                $transaction = new Transaction();
                $transaction->order_id = 0;//for no order detail
                $transaction->user_id = $id;
                $transaction->tracking_info = 'Wallet Recharge with cod remittance';
                $transaction->credit = $cod;
                $transaction->debit = "0.00";
                $transaction->closing_blc = $wallet;
                $transaction->remarks = 'Wallet Recharge with cod remittance';
                $transaction->save();
                
                $admin = Admin::where('id',$id)->first();
                $admin->wallet_blc = $wallet;
                $admin->save();
                // dd($admin);
                return response()->json([
                    'success' => true,
                    'amount' => $cod,
                    'message'=>"PAyment Successful"
                ]);
                // return redirect()->back()->with('Payment Successful');
            }else{
                return back()->with('Entered Amount must be greater than last remit');
            }
        }elseif($request->payment == 1){
            return redirect()->route('admin.payment.add_money');
        }
        
    }

    public function billing_info()
    {   
        $user_data = Auth::guard('admin')->user();
        $user_id = $user_data->id;
        $role_id = $user_data->role_id;
        
        $last_dat =  date('Y-m-d', strtotime('last day of previous month')).' 23:59:59';
        $start_dat =  date('Y-m-d', strtotime('first day of previous month')).' 00:00:00';
        $re_data['start_date'] = '';
        $re_data['end_date'] = '';

        // Invoice/Orders of previous month
        $invoice_q = Order::where('shipped_date', '>=', $start_dat)->where('shipped_date', '<=', $last_dat);
        if ($role_id == '1') {
            if ($user_id != '1') {
                $invoice_q = $invoice_q->where('company_id', $user_data->company_id);
            }
        } elseif ($role_id == '2') {
            $sub_user_id = Admin::getsubuserid($user_id);
            $invoice_q = $invoice_q->whereIn('user_id', $sub_user_id);
        } else {
            $invoice_q = $invoice_q->where('user_id', $user_id);
        }
        $invoice = $invoice_q->get();

        // Transactions list
        $transactionq = Transaction::with(['admin', 'order'])->orderBy('id', 'desc')->where('show_data', '1');
        if ($role_id == '1') {
            if ($user_id == '1') {
                $transactionq = $transactionq->where('parent_data', '0');
            } else {
                $transactionq = $transactionq->where(function ($q) use ($user_data) {
                    $q->where('company_id', $user_data->company_id)
                      ->orWhereHas('admin', function ($a) use ($user_data) {
                          $a->where('company_id', $user_data->company_id);
                      });
                });
            }
        } elseif ($role_id == '2') {
            $sub_user_id = Admin::getsubuserid($user_id);
            $transactionq = $transactionq->whereIn('user_id', $sub_user_id);
        } else {
            $transactionq = $transactionq->where('user_id', $user_id);
        }

        if (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '') {
            $re_data['start_date'] = $_REQUEST['start_date'].' 00:00:00';
            $re_data['end_date'] = $_REQUEST['end_date'].' 23:59:59';
            $transactionq = $transactionq->where('created_at', '>=', $re_data['start_date'])->where('created_at', '<=', $re_data['end_date']);
        }
        $transaction = $transactionq->get();

        // Fetch pending weight reconciliation orders to merge as virtual transaction rows
        $pending_orders_q = Order::whereIn('extra_weight_status', [1, 5])->where('extra_weight_cost', '>', 0);
        
        if ($role_id == '1') {
            if ($user_id != '1') {
                $pending_orders_q = $pending_orders_q->where('company_id', $user_data->company_id);
            }
        } elseif ($role_id == '2') {
            $sub_user_id = Admin::getsubuserid($user_id);
            $pending_orders_q = $pending_orders_q->whereIn('user_id', $sub_user_id);
        } else {
            $pending_orders_q = $pending_orders_q->where('user_id', $user_id);
        }

        if (isset($re_data['start_date']) && $re_data['start_date'] != '') {
            $pending_orders_q = $pending_orders_q->where('updated_at', '>=', $re_data['start_date'])
                                                 ->where('updated_at', '<=', $re_data['end_date']);
        }
        
        $pending_orders = $pending_orders_q->get();
        $virtual_txs = collect();
        foreach ($pending_orders as $order) {
            $mockTx = new Transaction();
            $mockTx->id = 'Pending';
            $mockTx->order_id = $order->id;
            $mockTx->user_id = $order->getRawOriginal('user_id');
            $mockTx->company_id = $order->company_id;
            $mockTx->awb = $order->tracking_info;
            $mockTx->credit = 0.00;
            $mockTx->debit = $order->extra_weight_cost;
            $mockTx->closing_blc = '-';
            $mockTx->remarks = 'Weight reconciliation (Pending)';
            $mockTx->created_at = $order->updated_at;
            $mockTx->updated_at = $order->updated_at;
            
            $mockTx->setRelation('order', $order);
            $admin = Admin::find($order->getRawOriginal('user_id'));
            $mockTx->setRelation('admin', $admin);
            
            $virtual_txs->push($mockTx);
        }
        
        $transaction = $transaction->concat($virtual_txs)->sortByDesc('created_at');

        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);

        // Warehouses list
        $warehouse_q = WareHouse::query();
        if ($role_id == '1') {
            if ($user_id != '1') {
                $warehouse_q = $warehouse_q->where('company_id', $user_data->company_id);
            }
        } elseif ($role_id == '2') {
            $sub_user_id = Admin::getsubuserid($user_id);
            $warehouse_q = $warehouse_q->whereIn('user_id', $sub_user_id);
        } else {
            $warehouse_q = $warehouse_q->where('user_id', $user_id);
        }
        $warehouse = $warehouse_q->get();

        // Forward and backward orders
        $order_fwd_q = Order::with('detail')->where('shipping_courier_cost', '!=', '0')->where('reverse_order', '=', '0');
        $order_bwd_q = Order::with('detail')->where('shipping_courier_cost', '!=', '0')->where('reverse_order', '=', '1');

        if ($role_id == '1') {
            if ($user_id != '1') {
                $order_fwd_q = $order_fwd_q->where('company_id', $user_data->company_id);
                $order_bwd_q = $order_bwd_q->where('company_id', $user_data->company_id);
            }
        } elseif ($role_id == '2') {
            $sub_user_id = Admin::getsubuserid($user_id);
            $order_fwd_q = $order_fwd_q->whereIn('user_id', $sub_user_id);
            $order_bwd_q = $order_bwd_q->whereIn('user_id', $sub_user_id);
        } else {
            $order_fwd_q = $order_fwd_q->where('user_id', $user_id);
            $order_bwd_q = $order_bwd_q->where('user_id', $user_id);
        }

        $order_fwd = $order_fwd_q->get();
        $order_bwd = $order_bwd_q->get();

        if (Auth::guard('admin')->user()->role_id == '1') {
            $invoices = Invoice::with('detail')->where('show_data', '1')
                    ->join('admins', 'admins.id', '=', 'invoices.user_id')
                    ->select('invoices.*', 'admins.id as us_id', 'admins.company_name')->get();
        } elseif (Auth::guard('admin')->user()->role_id == '2') {
            $sub_user_id = Admin::getsubuserid($user_id);
            $invoices = Invoice::with('detail')->where('show_data', '1')->whereIn('user_id', $sub_user_id)->join('admins', 'admins.id', '=', 'invoices.user_id')
                    ->select('invoices.*', 'admins.id as us_id', 'admins.company_name')->get();
        } else {
            $invoices = Invoice::with('detail')->where('show_data', '1')->where('user_id', $user_id)->join('admins', 'admins.id', '=', 'invoices.user_id')
                    ->select('invoices.*', 'admins.id as us_id', 'admins.company_name')->get();
        }

        return view('admin.billing.shipping_charges', compact('warehouse', 'order_fwd', 'couriers', 'transaction', 'order_bwd', 'invoice', 'user_id', 'invoices', 're_data', 'role_id'));
    }



    public function invoice_generate($in_id)
    {
        // Fetch additional data to pass to the PDF template
        $users = User::all(); // Assuming you have a User model
        $data = [
            'title' => 'Sample PDF Title',
            'content' => 'Sample PDF Content',
            'users' => $users, // Pass the users data to the template
        ];
        $invoice = Invoice::where('invoices.id' , $in_id)
        ->Join('admins', 'admins.id', '=', 'invoices.user_id')
        ->leftJoin('profiles', 'profiles.user_id', '=', 'invoices.user_id')
        ->leftJoin('states', 'states.id', '=', 'profiles.state')        
        ->select('invoices.*', 'states.state_code', 'admins.place_supply','admins.company_name','admins.company_address as billing_address','profiles.address', 'profiles.state', 'profiles.city','profiles.bank_name','profiles.beneficiary_name','account_no','ifsc_code','profiles.gst','profiles.zip_code')
        ->first();
        $general_setting = DB::table('general_settings')->where('id','1')->first();
//         echo '<pre>';print_R($invoice);die;
//         return view('admin.pdf.template',compact('data','general_setting','invoice'));

        // Generate the PDF using the template
        $pdf = PDF::loadView('admin.pdf.template',compact('data','general_setting','invoice'));
        // die;
        // Optional: You can save the PDF to a file if needed
        // $pdf->save('path/to/save/pdf.pdf');

        // Return the PDF as a download response
        if($invoice->invoice_type =='c'){
            $name = 'Credit note -'.$invoice->company_name.'.pdf';
        }elseif($invoice->invoice_type =='n'){        
            $name = 'Invoice -'.$invoice->company_name.'.pdf';
        }
        return $pdf->download($name);
    }




//NDR STARTS

    public function ndr()
    {
        $activepage = session('page');
        if($activepage =='' || $activepage ==null){
            $activepage = 'dashboard';
        }
        $user = Auth::guard('admin')->user();
        $currentDate = now()->toDateString();
        $startOfWeek = now()->startOfWeek()->toDateString();
        $endOfWeek = now()->endOfWeek()->toDateString();
        $ndrordeR_id = Order::getorderidusingndr($user->company_id);
        $lastthree = date('Y-m-d', strtotime('-3 days', strtotime(now()))).' 00:00:01';
        $nowdate = now();
        if(isset($_REQUEST['start_date'])){
            $lastthree = $_REQUEST['start_date'].' 00:00:01';
        }
        if(isset($_REQUEST['end_date'])){
            $nowdate = $_REQUEST['end_date'].' 23:59:59';
        }
        $re_data['start_date'] = $lastthree;
        $re_data['end_date'] = $nowdate;
        if($user->role_id =='1'){
            $ndr_order = order::with('orderlog')->with('detail')->where('company_id',$user->company_id)->whereIn('id', $ndrordeR_id)
            ->get();
            $order_q = Order::with('detail')->where('shipped_date' ,'!=', null)->where('company_id',$user->company_id)->count();
            $ndrCounts = DB::table('order_logs')
            ->select(DB::raw('DATE(created_at) as date'), 'new_value', DB::raw('COUNT(*) as ndr_count'))
            ->where('new_value', 'NDR')
            ->where('company_id',$user->company_id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy('date', 'new_value')
            ->get();
        }elseif($user->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user->id);
            $ndr_order = order::with('orderlog')->with('detail')->where('company_id',$user->company_id)->whereIn('id', $ndrordeR_id)
            ->whereIn('orders.user_id',$sub_user_id)
            ->get();
            
            $order_q = Order::with('detail')->whereIn('user_id' , $sub_user_id)->where('company_id',$user->company_id)->where('shipped_date' ,'!=', null)->count();
            $ndrCounts = DB::table('order_logs')
            ->join('orders', 'orders.id', '=', 'order_logs.order_id')
            ->select(DB::raw('DATE(order_logs.created_at) as date'), 'order_logs.new_value', DB::raw('COUNT(*) as ndr_count'))
            ->where('order_logs.new_value', 'NDR')
            ->where('company_id',$user->company_id)
            ->whereBetween('order_logs.created_at', [$startOfWeek, $endOfWeek])
            ->whereIn('orders.user_id', $sub_user_id) // Add condition with user_id
            ->groupBy('date', 'order_logs.new_value')
            ->get();
        }else{
            $ndr_order = order::with('orderlog')->with('detail')->whereIn('id', $ndrordeR_id)
            ->where('orders.user_id',$user->id)
            ->get();
           
            $order_q = Order::with('detail')->where(['user_id' => $user->id])->where('shipped_date' ,'!=', null)->count();
            $ndrCounts = DB::table('order_logs')
            ->join('orders', 'orders.id', '=', 'order_logs.order_id')
            ->select(DB::raw('DATE(order_logs.created_at) as date'), 'order_logs.new_value', DB::raw('COUNT(*) as ndr_count'))
            ->where('order_logs.new_value', 'NDR')
            ->whereBetween('order_logs.created_at', [$startOfWeek, $endOfWeek])
            ->where('orders.user_id', $user->id) // Add condition with user_id
            ->groupBy('date', 'order_logs.new_value')
            ->get();
        }
        $rto_order = $delivered = $action_pending = $action_taken =array();
        // Now, we need to construct an array for each day of the week
        $weekDays = $weekRTO = $weekDelivrd = [];
        $currentDate = $currentDated =$startOfWeek;
        while ($currentDate <= $endOfWeek) {

            $weekDays[date('D',strtotime($currentDate))] = 0; // Initialize count to 0 for each day
            $weekRTO[date('D',strtotime($currentDate))] = 0; // Initialize count to 0 for each day
            $weekDelivrd[date('D',strtotime($currentDate))] = 0; // Initialize count to 0 for each day
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }
        // echo '<pre>';print_r($weekDays);die;
        foreach ($ndrCounts as $count) {
            $weekDays[date('D',strtotime($count->date))] += $count->ndr_count;
        }
    
        foreach($ndr_order as $order){
            if(strip_tags($order->status) =='Delivered'){
                $delivered[] = $order;
                $currentDated =$startOfWeek;
                while ($currentDated <= $endOfWeek) {
                    if($order->delivered_date >= $currentDated.' 00:00:01' && $order->delivered_date <= $currentDated.' 23:59:59'){
                        $weekDelivrd[date('D',strtotime($currentDated))] += 1;
                    }
                    $currentDated = date('Y-m-d', strtotime($currentDated . ' +1 day'));
                }
            }elseif(in_array(strip_tags($order->status),array('RTO','RTO Received','RTO In Transit'))){
                $rto_order[] = $order; 
                $currentDated =$startOfWeek;
                while ($currentDated <= $endOfWeek) {
                    if($order->status_date >= $currentDated.' 00:00:01' && $order->status_date <= $currentDated.' 23:59:59'){
                        $weekRTO[date('D',strtotime($currentDated))] += 1;
                    }
                    $currentDated = date('Y-m-d', strtotime($currentDated . ' +1 day'));
                }
            }else{
                if($order->ndr_action =='' || $order->ndr_action ==null){
                    $action_pending[] =  $order;
                }else{
                    if($order->status_date >= $lastthree && $order->status_date <= $nowdate){
                        $action_taken[] =  $order;
                    }
                }
            }
            
        }
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $user_id = $user->id;
        return view('admin.ndr.ndr',compact('re_data','user_id','ndr_order','couriers','rto_order','delivered','order_q','weekDays','weekRTO','weekDelivrd','action_pending','action_taken','activepage'));

    }

    public function action()
    {   
        foreach(request()->id as $id)
        {
            $order = Order::where('id',$id)->first();
            if($order !=''){
                
                $order->ndr_action =request()->closing_description;
                $order->ndr_action_date =now();
                $order->save();
            }
            
        }
        return redirect()->route('admin.ndr.ndr')->with('success',"Updated Successfully")->with('page', 'pending');
    }

    public function addndr($order_id){
        $user = Auth::guard('admin')->user();
        if($user->role_id =='1'){
            $order = Order::where('id',$order_id)->where('company_id',$user->company_id)->first();
        }else if($user->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user->id);
            $order = Order::where('id',$order_id)
            ->where('company_id',$user->company_id)
            ->whereIn('orders.user_id',$sub_user_id)
            ->first();
        }else{
            $order = Order::where('id',$order_id)
            ->where('company_id',$user->company_id)
            ->where('orders.user_id',$user->id)
            ->first();
        }
        
        if($order ==null || $order ==''){
            return redirect()->route('admin.ndr.ndr')->with('error', 'No Access')->with('page', 'pending');;
        }
        return view('admin.ndr.addndr',compact('order'));
    }

    public function update(Request $request)
    {
        $order = Order::where('id',$request->order_id)->first();
        if($order !=''){
            $order->ndr_action =$request->closing_description;
            $order->ndr_action_date =now();
            $order->save();

            // Check if courier is Parcel Uncle (13) and has NDR action
            if($order->ship_courier_id == 13 && $request->has('pu_ndr_action')) {
                $pu_integration = DB::table('integrations')
                    ->where('courier_id', 13)
                    ->where('user_id', $order->user_id)
                    ->first();
                if (!$pu_integration) {
                    $pu_integration = DB::table('integrations')->where('courier_id', 13)->first();
                }
                
                if ($pu_integration) {
                    $action = $request->input('pu_ndr_action');
                    $action_data = ['action' => $action];
                    
                    if ($action == 'REATTEMPT') {
                        $action_data['reattempt_date'] = $request->input('pu_reattempt_date') ?: date('Y-m-d', strtotime('+1 day'));
                        if ($request->filled('pu_reattempt_slot')) {
                            $action_data['reattempt_slot'] = $request->input('pu_reattempt_slot');
                        }
                    } elseif ($action == 'ADDRESS_UPDATE') {
                        $action_data['updated_address'] = $request->input('pu_updated_address') ?: $order->ship_address;
                        if ($request->filled('pu_updated_phone')) {
                            $action_data['updated_phone'] = $request->input('pu_updated_phone');
                        }
                    } elseif ($action == 'RTO') {
                        $action_data['remarks'] = $request->input('pu_rto_remarks') ?: $request->closing_description;
                    }
                    
                    $res = \App\Models\Admin\Integration::ndr_action_parcel_uncle($order->tracking_info, $action_data, $pu_integration);
                    api_logs(json_encode($action_data), $res, $order->id, '13', 'ndr_action');
                    
                    $res_arr = json_decode($res, true);
                    if (!isset($res_arr['success']) || !$res_arr['success']) {
                        $msg = $res_arr['message'] ?? 'Error updating NDR on Parcel Uncle';
                        return redirect()->route('admin.ndr.ndr')->with('error', $msg)->with('page', 'pending');
                    }
                }
            }

            return redirect()->route('admin.ndr.ndr')->with('success',"Updated Successfully")->with('page', 'pending');
        }else{
            return redirect()->route('admin.ndr.ndr')->with('error',"No order found")->with('page', 'pending');
        }

    }
    
    public function getinvoice(){
        $user_current = Auth::guard('admin')->user()->id;
        $start_date = '2024-04-30 23:59:59';
        $order_q = Order::with('detail')
        ->join('transactions', 'transactions.order_id', '=', 'orders.id')
        ->leftjoin('admins', 'admins.id', '=', 'transactions.user_id')
        ->leftJoin('profiles', 'transactions.user_id', '=', 'profiles.user_id')    
        ->leftJoin('states', 'states.id', '=', 'profiles.state')    
        ->where('transactions.created_at','<=',$start_date)
        ->where('transactions.user_id','!=','199')->where('transactions.user_id','!=','19999');
        // ->where('orders.user_id',$user_current);
        $orders = $order_q->select('orders.*', 'transactions.user_id as u_id','admins.name as u_name','states.name as pstate')
        ->distinct('orders.*')
        ->get();
        $in_data = array();$i=0;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        foreach($orders as $order){
            $trans = Transaction::where('order_id',$order->id)->where('transactions.created_at','<=',$start_date)->select('*')->distinct('awb')->get();
            $awb = array();
            foreach($trans as $t){
                if($t->remarks =='Amount Credit for cancel'){
                    $awb[$t->awb] = $t->credit;
                } 
                if(isset($awb[$t->awb])){
                    continue;
                } 
                $awb[$t->awb] =  $t->debit;
               
            }
            
            foreach($awb as $key=>$value){
                $in_data[$i]['Bill Type'] = ($order->reverse_order=='0')?'Forward':'Reverse';
                $in_data[$i]['AWB Number'] = $key;
                $in_data[$i]['Carrier'] = 'N/a';
                $in_data[$i]['Shipment Status'] = 'Canceled';
                $in_data[$i]['Seller id'] = $order->u_id;
                $in_data[$i]['Seller Name'] = $order->u_name;
                $in_data[$i]['Order ID'] = $order->id;
                $in_data[$i]['Payment Type'] = strip_tags($order->payment_mode);
                $in_data[$i]['Pincode'] = $order->ship_pincode;
                $in_data[$i]['City'] = $order->ship_city;
                $in_data[$i]['Charged Weight'] = $order->shipping_courier_weight;
                $in_data[$i]['Foward Freight'] = '0';
                $in_data[$i]['RTO Freight'] = '0';
                $in_data[$i]['Extra Wgt Charges'] = '0';
                $in_data[$i]['RTO Extra Wgt Charges'] = '0';
                $in_data[$i]['COD Charges'] = '0';
                $in_data[$i]['Sub Total'] = 0;
                $in_data[$i]['gst'] = 0;
                $in_data[$i]['pstate'] = $order->pstate;
                $in_data[$i]['Grand Total'] = 0;
                $in_data[$i]['Order Amount'] = $order->total;
                $in_data[$i]['Warehouse City'] = '';
                $in_data[$i]['Warehouse State'] = '';
                $in_data[$i]['Warehouse Pin Code'] = '';
                for($j=1;$j<=10;$j++){
                    $in_data[$i]['SKU('.$j.')'] = '';
                    $in_data[$i]['Product('.$j.')'] = '';
                    $in_data[$i]['Quantity('.$j.')'] = '';
                }
                
                if($key == $order->tracking_info ){
                    $in_data[$i]['Carrier'] = @$couriers[$order->ship_courier_id]['name'];
                    $in_data[$i]['Shipment Status'] = strip_tags($order->status);
                    $in_data[$i]['Foward Freight'] = $order->freight;
                    $in_data[$i]['RTO Freight'] = $order->rto_charge_witoutgst;
                    if(in_array(strip_tags($order->extra_weight_status),array('Closed in Seller favor','Auto Accepted'))){
                        $in_data[$i]['Extra Wgt Charges'] = $order->extracostwithoutgst;
                        if($order->rtocharge_applied =='1'){
                            $in_data[$i]['RTO Extra Wgt Charges'] = $order->extracostwithoutgst;
                        }else{
                            $in_data[$i]['RTO Extra Wgt Charges'] = 0;
                        }
                    }else{
                        $in_data[$i]['Extra Wgt Charges'] = 0;
                        $in_data[$i]['RTO Extra Wgt Charges'] = 0;
                    }
                    $in_data[$i]['COD Charges'] =  $order->cod;
                    $in_data[$i]['Sub Total'] = $in_data[$i]['Foward Freight']+$in_data[$i]['RTO Freight']+ $in_data[$i]['Extra Wgt Charges']+$in_data[$i]['RTO Extra Wgt Charges']+$in_data[$i]['COD Charges'];
                    if($in_data[$i]['Sub Total'] ==0 && $order->shipping_courier_cost !=0){
                        $in_data[$i]['Sub Total'] = $order->shipping_courier_cost/1.18;
                    }
                    $gst =0;
                    $gst = $order->gst_freight + $order->gst_cod;
                    if($order->rtocharge_applied =='1'){
                        $gst +=  $order->rto_charge_gst; 
                    }
                    if(in_array(strip_tags($order->extra_weight_status),array('Closed in Seller favor','Auto Accepted'))){
                        $gst +=  $order->extracostgst;  
                        if($order->rtocharge_applied =='1'){
                            $gst +=  $order->extracostgst; 
                        }
                    }
                    if($gst ==0 &&  $order->shipping_courier_cost !=0){
                        $gst =  (($order->shipping_courier_cost/1.18)*0.18);
                    }
                    $in_data[$i]['gst'] = $gst;
                    $in_data[$i]['Grand Total'] = $gst + $in_data[$i]['Sub Total'];
                }

                if($order->warehouse_id !='' && $order->warehouse_id != null){
                    $warehouse = Warehouse::where('id',$order->warehouse_id)->first();
                    if($warehouse){
                        $in_data[$i]['Warehouse City'] = $warehouse->city;
                        $in_data[$i]['Warehouse State'] = $warehouse->state;
                        $in_data[$i]['Warehouse Pin Code'] = $warehouse->pincode;
                    }
                }
                $k=1;
                foreach($order->detail  as $row){
                    $in_data[$i]['SKU('.$k.')'] = $row->code;
                    $in_data[$i]['Product('.$k.')'] = $row->name;
                    $in_data[$i]['Quantity('.$k.')'] = $row->qty;
                    $k++;
                }
                $i++;   
            }
           
        }
        return view('admin.billing.getinvoice',compact('in_data'));

        
    }
    

    public function invoicedata($invoice_id){
        $invdata = Invoice::where('id',$invoice_id)->select('user_id as u_id','start_date','end_date','invoice_id')->first();
        $userchkid = $invdata->u_id;
        $start_date = $invdata->start_date;
        $end_date = $invdata->end_date;
         $invoice_id = $invdata->invoice_id;
        $remar_Array = array('Amount Credit for cancel','freight charge','cod charge','COD Charge Refunded','Amount Debit for RTO','freight & reverse charge','Amount Debit for extra weight','Amount Debit for extra weight - RTO','RTO Charge Refunded','Extra weight Refunded','Freight Charge Refunded','zone amount difference');
        $trans = Transaction::
        join('admins', 'transactions.user_id', '=', 'admins.id')
        ->join('orders', 'orders.id', '=', 'transactions.order_id')
        ->leftJoin('profiles', function($join) {
            $join->on('admins.id', '=', 'profiles.user_id')
                 ->whereRaw('profiles.id = (SELECT id FROM profiles WHERE profiles.user_id = orders.user_id LIMIT 1)');
        })
        ->whereIn('transactions.remarks',$remar_Array)->where('show_data','1')->where('transactions.user_id',$userchkid)->where('transactions.created_at','>=',$start_date)->where('transactions.created_at','<=',$end_date)->select('transactions.*','admins.id as usr_id','admins.name as usr_name','orders.reverse_order','orders.vendor_order_id','orders.order_id','orders.status','orders.ship_pincode','orders.ship_city','orders.tracking_info','orders.ship_courier_id'
                ,'profiles.state as pstate')->get();
        $in_data = array();$i=0;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        foreach($trans as $t){
            if($t->user_id ==$userchkid){
                $in_data[$t->awb]['awb'] =$t->awb;
                $in_data[$t->awb]['invoice_id'] =$invoice_id;
                $in_data[$t->awb]['bill_type'] =($t->reverse_order=='0')?'Forward':'Reverse';
                $in_data[$t->awb]['seller_id'] =$t->usr_id;
                $in_data[$t->awb]['user_id'] =$t->user_id;
                $in_data[$t->awb]['seller_name'] =$t->usr_name;
                $in_data[$t->awb]['order_id'] =$t->order_id;
                $in_data[$t->awb]['pincode'] =$t->ship_pincode;
                $in_data[$t->awb]['city'] =$t->ship_city;
                $in_data[$t->awb]['pstate'] =$t->pstate;

                if($t->tracking_info == $t->awb){
                    $in_data[$t->awb]['status'] =strip_tags(order::getStatusdata($t->status));
                    $in_data[$t->awb]['courier'] =$couriers[$t->ship_courier_id]['name'];
                }elseif($t->tracking_info ==''){
                    $in_data[$t->awb]['status'] ='Canceled';
                    $in_data[$t->awb]['courier'] ='';
                }else{
                    $in_data[$t->awb]['status'] ='na';
                    $in_data[$t->awb]['courier'] ='';
                }

                if(isset($in_data[$t->awb]['cancel_amount'])){}else{$in_data[$t->awb]['cancel_amount'] =0;}
                if($t->remarks =='Amount Credit for cancel'){
                    $in_data[$t->awb]['cancel_amount'] += ($t->credit *-1);
                }

                if(isset($in_data[$t->awb]['freight'])){}else{$in_data[$t->awb]['freight'] =0;}
                if($t->remarks =='freight charge' || $t->remarks =='freight & reverse charge'){
                    $in_data[$t->awb]['freight'] += $t->debit;
                }

                if(isset($in_data[$t->awb]['cod'])){}else{$in_data[$t->awb]['cod'] =0;}
                if($t->remarks =='cod charge'){
                    $in_data[$t->awb]['cod'] += $t->debit;
                }

                if(isset($in_data[$t->awb]['cod_refunded'])){}else{$in_data[$t->awb]['cod_refunded'] =0;}
                if($t->remarks =='COD Charge Refunded'){
                    $in_data[$t->awb]['cod_refunded'] +=($t->credit *-1);
                }

                if(isset($in_data[$t->awb]['rto'])){}else{$in_data[$t->awb]['rto'] =0;}
                if($t->remarks =='Amount Debit for RTO'){
                    $in_data[$t->awb]['rto'] += $t->debit;
                }

                if(isset($in_data[$t->awb]['extra_weight'])){}else{$in_data[$t->awb]['extra_weight'] =0;}
                if($t->remarks =='Amount Debit for extra weight'){
                    $in_data[$t->awb]['extra_weight'] += $t->debit;
                }

                if(isset($in_data[$t->awb]['extra_weight_rto'])){}else{$in_data[$t->awb]['extra_weight_rto'] =0;}
                if($t->remarks =='Amount Debit for extra weight - RTO'){
                    $in_data[$t->awb]['extra_weight_rto'] += $t->debit;
                }

                if(isset($in_data[$t->awb]['rto_refunded'])){}else{$in_data[$t->awb]['rto_refunded'] =0;}
                if($t->remarks =='RTO Charge Refunded'){
                    $in_data[$t->awb]['rto_refunded'] +=($t->credit *-1);
                }
                if(isset($in_data[$t->awb]['extraweight_refunded'])){}else{$in_data[$t->awb]['extraweight_refunded'] =0;}
                if($t->remarks =='Extra weight Refunded'){
                    $in_data[$t->awb]['extraweight_refunded'] +=($t->credit *-1);
                }
                
                if(isset($in_data[$t->awb]['freight_refunded'])){}else{$in_data[$t->awb]['freight_refunded'] =0;}
                if($t->remarks =='Freight Charge Refunded'){
                    $in_data[$t->awb]['freight_refunded'] +=($t->credit *-1);
                }
                if(isset($in_data[$t->awb]['zone_amount_difference'])){}else{$in_data[$t->awb]['zone_amount_difference'] =0;}
                if($t->remarks =='zone amount difference'){
                    $in_data[$t->awb]['zone_amount_difference'] +=($t->credit *-1);
                    $in_data[$t->awb]['zone_amount_difference'] += $t->debit;
                }
            }
        }
        return view('admin.billing.getinvoiceweekly',compact('in_data'));
    }

    public function downloadinvoice()
    {   
//        echo '<pre>';print_R(request()->id);die;
        $in_data = array();
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
                    
        foreach(request()->id as $id)
        {   
            if(($id>=116 && $id<=122) || $id =='306' || $id =='307'){
                
            }else{
                    $invdata = Invoice::where('id',$id)->select('user_id as u_id','start_date','end_date','invoice_id')->first();
                    $userchkid = $invdata->u_id;
                    $start_date = $invdata->start_date;
                    $end_date = $invdata->end_date;
                    $invoice_id = $invdata->invoice_id;
                    $remar_Array = array('Amount Credit for cancel','freight charge','cod charge','COD Charge Refunded','Amount Debit for RTO','freight & reverse charge','Amount Debit for extra weight','Amount Debit for extra weight - RTO','RTO Charge Refunded','Extra weight Refunded','Freight Charge Refunded');
                    $trans = Transaction::
                    join('admins', 'transactions.user_id', '=', 'admins.id')
                    ->join('orders', 'orders.id', '=', 'transactions.order_id')
                    ->leftJoin('profiles', function($join) {
                        $join->on('admins.id', '=', 'profiles.user_id')
                             ->whereRaw('profiles.id = (SELECT id FROM profiles WHERE profiles.user_id = orders.user_id LIMIT 1)');
                    })
                    ->whereIn('transactions.remarks',$remar_Array)->where('show_data','1')->where('transactions.user_id',$userchkid)->where('transactions.created_at','>=',$start_date)->where('transactions.created_at','<=',$end_date)->select('transactions.*','admins.id as usr_id','admins.name as usr_name','orders.reverse_order','orders.vendor_order_id','orders.order_id','orders.status','orders.ship_pincode','orders.ship_city','orders.tracking_info','orders.ship_courier_id'
                            ,'profiles.state as pstate')->get();
                    foreach($trans as $t){
                        if($t->user_id ==$userchkid){
                            $in_data[$invoice_id][$t->awb]['awb'] =$t->awb;
                            $in_data[$invoice_id][$t->awb]['invoice_id'] =$invoice_id;
                            $in_data[$invoice_id][$t->awb]['bill_type'] =($t->reverse_order=='0')?'Forward':'Reverse';
                            $in_data[$invoice_id][$t->awb]['seller_id'] =$t->usr_id;
                            $in_data[$invoice_id][$t->awb]['user_id'] =$t->user_id;
                            $in_data[$invoice_id][$t->awb]['seller_name'] =$t->usr_name;
                            $in_data[$invoice_id][$t->awb]['order_id'] =$t->order_id;
                            $in_data[$invoice_id][$t->awb]['pincode'] =$t->ship_pincode;
                            $in_data[$invoice_id][$t->awb]['city'] =$t->ship_city;
                            $in_data[$invoice_id][$t->awb]['pstate'] =$t->pstate;

                            if($t->tracking_info == $t->awb){
                                $in_data[$invoice_id][$t->awb]['status'] =strip_tags(order::getStatusdata($t->status));
                                $in_data[$invoice_id][$t->awb]['courier'] =$couriers[$t->ship_courier_id]['name'];
                            }elseif($t->tracking_info ==''){
                                $in_data[$invoice_id][$t->awb]['status'] ='Canceled';
                                $in_data[$invoice_id][$t->awb]['courier'] ='';
                            }else{
                                $in_data[$invoice_id][$t->awb]['status'] ='na';
                                $in_data[$invoice_id][$t->awb]['courier'] ='';
                            }

                            if(isset($in_data[$invoice_id][$t->awb]['cancel_amount'])){}else{$in_data[$invoice_id][$t->awb]['cancel_amount'] =0;}
                            if($t->remarks =='Amount Credit for cancel'){
                                $in_data[$invoice_id][$t->awb]['cancel_amount'] += ($t->credit *-1);
                            }

                            if(isset($in_data[$invoice_id][$t->awb]['freight'])){}else{$in_data[$invoice_id][$t->awb]['freight'] =0;}
                            if($t->remarks =='freight charge' || $t->remarks =='freight & reverse charge'){
                                $in_data[$invoice_id][$t->awb]['freight'] += $t->debit;
                            }

                            if(isset($in_data[$invoice_id][$t->awb]['cod'])){}else{$in_data[$invoice_id][$t->awb]['cod'] =0;}
                            if($t->remarks =='cod charge'){
                                $in_data[$invoice_id][$t->awb]['cod'] += $t->debit;
                            }

                            if(isset($in_data[$invoice_id][$t->awb]['cod_refunded'])){}else{$in_data[$invoice_id][$t->awb]['cod_refunded'] =0;}
                            if($t->remarks =='COD Charge Refunded'){
                                $in_data[$invoice_id][$t->awb]['cod_refunded'] +=($t->credit *-1);
                            }

                            if(isset($in_data[$invoice_id][$t->awb]['rto'])){}else{$in_data[$invoice_id][$t->awb]['rto'] =0;}
                            if($t->remarks =='Amount Debit for RTO'){
                                $in_data[$invoice_id][$t->awb]['rto'] += $t->debit;
                            }

                            if(isset($in_data[$invoice_id][$t->awb]['extra_weight'])){}else{$in_data[$invoice_id][$t->awb]['extra_weight'] =0;}
                            if($t->remarks =='Amount Debit for extra weight'){
                                $in_data[$invoice_id][$t->awb]['extra_weight'] += $t->debit;
                            }

                            if(isset($in_data[$invoice_id][$t->awb]['extra_weight_rto'])){}else{$in_data[$invoice_id][$t->awb]['extra_weight_rto'] =0;}
                            if($t->remarks =='Amount Debit for extra weight - RTO'){
                                $in_data[$invoice_id][$t->awb]['extra_weight_rto'] += $t->debit;
                            }

                            if(isset($in_data[$invoice_id][$t->awb]['rto_refunded'])){}else{$in_data[$invoice_id][$t->awb]['rto_refunded'] =0;}
                            if($t->remarks =='RTO Charge Refunded'){
                                $in_data[$invoice_id][$t->awb]['rto_refunded'] +=($t->credit *-1);
                            }
                            if(isset($in_data[$invoice_id][$t->awb]['extraweight_refunded'])){}else{$in_data[$invoice_id][$t->awb]['extraweight_refunded'] =0;}
                            if($t->remarks =='Extra weight Refunded'){
                                $in_data[$invoice_id][$t->awb]['extraweight_refunded'] +=($t->credit *-1);
                            }

                            if(isset($in_data[$invoice_id][$t->awb]['freight_refunded'])){}else{$in_data[$invoice_id][$t->awb]['freight_refunded'] =0;}
                            if($t->remarks =='Freight Charge Refunded'){
                                $in_data[$invoice_id][$t->awb]['freight_refunded'] +=($t->credit *-1);
                            }
                        }
                    }
            }
            
        }
//        echo '<pre>';print_R($in_data);die;
        return view('admin.billing.downloadinvoice',compact('in_data'));
        
    }
    
    // public function walletreport()
    // {
    //     $user = Auth::guard('admin')->user();

    //     $query = Transaction::with('admin')
    //         ->where(function ($q) {
    //             $q->where('remarks', 'like', '%u000%')
    //               ->orWhere('remarks', 'like', '%recharge%')
    //               ->orWhere('remarks', 'like', '%hosting%');
    //         });

    //     if ($user->role_id == '1') {

    //         $query->where(function ($q) use ($user) {
    //             $q->where('company_id', $user->company_id)
    //               ->orWhereHas('admin', function ($a) use ($user) {
    //                   $a->where('company_id', $user->company_id);
    //               });
    //         });

    //     } elseif ($user->role_id == '2') {

    //         $sub_user_id = Admin::getsubuserid($user->id);
    //         $query->whereIn('user_id', $sub_user_id);

    //     } else {

    //         $query->where('user_id', $user->id);

    //     }

    //     $transactions = $query->get();

    //     return view('admin.payment.walletreport', compact('transactions'));
    // }
    //   public function walletreport()
    // {
    //     $user = Auth::guard('admin')->user();

    //     $query = \App\Models\Admin\Transaction::with('admin')
    //         ->where(function ($q) {
    //             $q->where('remarks', 'like', '%u000%')
    //               ->orWhere('remarks', 'like', '%recharge%')
    //               ->orWhere('remarks', 'like', '%hosting%');
    //         })
    //         ->orderBy('id', 'desc');

    //     if ($user->role_id == '1') {
    //         $query->where(function ($q) use ($user) {
    //             $q->where('company_id', $user->company_id)
    //               ->orWhereHas('admin', function ($a) use ($user) {
    //                   $a->where('company_id', $user->company_id);
    //               });
    //         });
    //     } elseif ($user->role_id == '2') {
    //         $sub_user_id = \App\Models\Admin\Admin::getsubuserid($user->id);
    //         $query->whereIn('user_id', $sub_user_id);
    //     } else {
    //         $query->where('user_id', $user->id);
    //     }

    //     $transactions = $query->get();

    //     return view('admin.payment.walletreport', compact('transactions'));
    // }
    
    //  public function walletreport()
    // {
    //     $user = Auth::guard('admin')->user();

    //     $query = \App\Models\Admin\Transaction::with('admin')
    //         ->where(function ($q) {
    //             $q->where('tracking_info', 'like', '%Wallet Recharge%')
    //               ->orWhere('tracking_info', 'like', '%razorpay%')
    //               ->orWhere('remarks', 'like', '%pay_%');
    //         })
    //         ->where('order_id', 0) // Only wallet recharge entries (not order transactions)
    //         ->orderBy('id', 'desc');

    //     if ($user->role_id == '1') {
    //         $query->where(function ($q) use ($user) {
    //             $q->where('company_id', $user->company_id)
    //               ->orWhereHas('admin', function ($a) use ($user) {
    //                   $a->where('company_id', $user->company_id);
    //               });
    //         });
    //     } elseif ($user->role_id == '2') {
    //         $sub_user_id = \App\Models\Admin\Admin::getsubuserid($user->id);
    //         $query->whereIn('user_id', $sub_user_id);
    //     } else {
    //         $query->where('user_id', $user->id);
    //     }

    //     $transactions = $query->get();

    //     return view('admin.payment.walletreport', compact('transactions'));
    // }
    public function walletreport(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $query = Transaction::with('admin')
            ->where(function ($q) {
                $q->where('remarks', 'like', '%u000%')
                  ->orWhere('remarks', 'like', '%recharge%')
                  ->orWhere('remarks', 'like', '%hosting%');
            })
            ->orderBy('id', 'desc');

        // Date Range Filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // User Filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Status Filter (Checking remarks JSON)
        if ($request->filled('status')) {
            $query->where('remarks', 'like', '%' . $request->status . '%');
        }

        if ($user->role_id == '1') {
            $query->where(function ($q) use ($user) {
                $q->where('company_id', $user->company_id)
                  ->orWhereHas('admin', function ($a) use ($user) {
                      $a->where('company_id', $user->company_id);
                  });
            });
        } elseif ($user->role_id == '2') {
            $sub_user_id = \App\Models\Admin\Admin::getsubuserid($user->id);
            $query->whereIn('user_id', $sub_user_id);
        } else {
            $query->where('user_id', $user->id);
        }

        $transactions = $query->get();
        
        // Fetch users for the filter dropdown
        $users_q = Admin::where('delete_status', '0')->orderBy('name', 'asc');
        if ($user->role_id == '1') {
             $users_q->where('company_id', $user->company_id);
        } elseif ($user->role_id == '2') {
             $sub_user_id = Admin::getsubuserid($user->id);
             $users_q->whereIn('id', $sub_user_id);
        } else {
             $users_q->where('id', $user->id);
        }
        $users = $users_q->get();

        return view('admin.payment.walletreport', compact('transactions', 'users'));
    }

    /*
    public function updateWalletRecharge(Request $request)
    {
        $user = Auth::guard('admin')->user();
        if ($user->role_id != '1') {
            return redirect()->back()->with('error', 'Unauthorized access! Only admins can edit recharge details.');
        }

        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'amount'         => 'required|numeric|min:0',
            'coupon_amount'  => 'nullable|numeric|min:0',
            'remarks'        => 'nullable|string',
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);

        $newAmount = (float)$request->amount;
        $newCouponAmount = (float)($request->coupon_amount ?? 0);
        $newTotalCredit = $newAmount + $newCouponAmount;

        $oldTotalCredit = (float)$transaction->credit;
        $creditDiff = $newTotalCredit - $oldTotalCredit;

        // Update transaction fields
        $transaction->credit = $newTotalCredit;
        if (Schema::hasColumn('transactions', 'coupon_amount')) {
            $transaction->coupon_amount = $newCouponAmount;
        }

        if ($request->filled('remarks')) {
            $decoded = json_decode($transaction->remarks, true);
            if (is_array($decoded)) {
                $decoded['admin_edit_remark'] = $request->remarks;
                $transaction->remarks = json_encode($decoded);
            } else {
                $transaction->remarks = $request->remarks;
            }
        }
        $transaction->save();

        // Adjust User Wallet Balance
        $targetUser = Admin::find($transaction->user_id);
        if ($targetUser) {
            $targetUser->wallet_blc = ($targetUser->wallet_blc ?? 0) + $creditDiff;
            $targetUser->save();
        }

        return redirect()->back()->with('success', 'Wallet recharge transaction updated successfully! User wallet balance updated accordingly.');
    }
    */

    
    
       public function refundCheck($payment_id)
    {
        try {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $payment = $api->payment->fetch($payment_id);
            $amountRefunded = $payment->amount_refunded ?? 0;
            $totalAmount    = $payment->amount ?? 0;
            $refundStatus   = $payment->refund_status ?? null; // null, partial, full
            $status         = $payment->status ?? 'unknown';

            // Status message mapping
            if ($status === "captured" || $status === "authorized") {
                $message = "Payment Successful";
            } else if ($status === "refunded") {
                $message = "Refund Processed";
            } else if ($status === "failed") {
                $message = "Payment Failed";
            } else {
                $message = "Payment Processing";
            }
            
            // AUTO UPDATE TRANSACTION AND PAYMENT STATUS
            $paymentRow = \App\Models\Admin\Payment::where('r_payment_id', $payment_id)->first();
            if ($paymentRow) {
                $jsonResponse = json_decode($paymentRow->json_response, true) ?? [];
                $jsonResponse['status'] = $status;
                $paymentRow->json_response = json_encode($jsonResponse);
                $paymentRow->save();
            }

            $transaction = \App\Models\Admin\Transaction::where('remarks', 'like', "%{$payment_id}%")->first();
            if ($transaction) {
                $remarks = json_decode($transaction->remarks, true);
                if (is_array($remarks)) {
                    $attrKey = "\0*\0attributes";
                    if (isset($remarks[$attrKey])) {
                        $remarks[$attrKey]['status'] = $status;
                    } else {
                        $remarks['status'] = $status;
                    }
                    $transaction->remarks = json_encode($remarks);
                    $transaction->save();
                }
            }

            if ($amountRefunded == 0) {
                $refundLabel = 'No Refund';
            } elseif ($amountRefunded == $totalAmount) {
                $refundLabel = 'Full Refund';
            } else {
                $refundLabel = 'Partial Refund';
            }

            return response()->json([
                'success'        => true,
                'payment_id'     => $payment_id,
                'status'         => $status,
                'message'        => $message,
                'amount'         => $totalAmount / 100,         // Razorpay stores in paise
                'amount_refunded'=> $amountRefunded / 100,
                'refund_status'  => $refundStatus,
                'refund_label'   => $refundLabel,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    
    public function lostshipments(){
        $user = Auth::guard('admin')->user();
        $user_id = $user->id;
        $re_data['user_id'][] =$user_id;
        $re_data['ship_courier_id'] =0;
        $lostorderquery = Order::with('lostpayment')->where('status','16')->where('company_id',$user->company_id);
        if(isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id'])){
            $re_data['user_id'] = $_REQUEST['user_id'];
        }
        $lostorderquery->whereIN('user_id', $re_data['user_id']);
        if(isset($_REQUEST['ship_courier_id']) && $_REQUEST['ship_courier_id'] !=0){
             $re_data['ship_courier_id'] =$_REQUEST['ship_courier_id'];
             $lostorderquery->where('ship_courier_id', $_REQUEST['ship_courier_id']);
        }
        $lostorder = $lostorderquery->get();
        $users = Admin::where('delete_status','0')->where('company_id',$user->company_id)->orderBy('name', 'asc')->get();  
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        return view('admin.payment.lostshipments',compact('lostorder','users','re_data','couriers'));
    }
    
    public function addlostpay(Request $request, $id = 0){
        $order_q = order::where('orders.id',$id)->join('admins', 'orders.user_id', '=', 'admins.id');
        $orders = $order_q->select('orders.*', 'admins.name', 'admins.email', 'admins.mobile','admins.company_name','admins.sm', 'admins.id as use_id', 'admins.company_id as com_id')->get();
        $order = $orders[0];
        if($order->total >= $request->paid){
            $data = new Order_lost_payment();
            $data->order_id = $id;
            $data->lost_payment_amountpaid = $request->paid;
            $data->lost_payment_status = 'success';
            $data->lost_payment_time = now();
            $data->lost_payment_utr = $request->utr;
//            $data->updated_at = now();
            
            $transaction = new Transaction();
            $transaction->order_id = 0;
            $transaction->user_id = $order->use_id;
            $transaction->company_id = $order->com_id;
            $transaction->awb = $order->tracking_info;
            $transaction->tracking_info = $order->tracking_info;
            $transaction->credit = '0.00';
            $transaction->closing_blc = '';
            $transaction->debit = '0.00';
            $transaction->remarks = "Amount Paid for shipment lost with UTR:".$request->utr;
            $transaction->save();
            
            $data->save();
            return Redirect()->back()->with('success', 'Updated');
        }else{
            return Redirect()->back()->with('error', 'Amount not correct');
        }
        
        
    }
    
    public function rechargeForm(Request $request)
    {
        $userId = $request->query('user_id');//user id in the url from the users 
        return view('admin.payment.rechargeWallet', ['userId' => $userId]); //returning the view
    }

    public function rechargeprocess(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'user_id' => 'required', 
            'payment_id' => 'required|string',
            'payment_amount' => 'required|numeric|min:0',
            'coupon_code' => 'nullable|string',
            'coupon_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }
    
        $validatedData = $validator->validated();
        $validatedData['recharge_done_by'] = Auth::guard('admin')->user()->id;
        $validatedData['company_id'] = Auth::guard('admin')->user()->company_id;
        
        $coupon_amt = $validatedData['coupon_amount'] ?? 0;
        
        // Custom logic for WELCOME and HYLO200 / HYLO500 coupons
        if (isset($validatedData['coupon_code'])) {
            $couponCodeUpper = strtoupper(trim($validatedData['coupon_code']));
            if ($couponCodeUpper == 'WELCOME') {
                if ($validatedData['payment_amount'] >= 5000) {
                    // Check if it's the first recharge for this user
                    $previousRecharges = Recharge::where('user_id', $validatedData['user_id'])->count();
                    if ($previousRecharges == 0) {
                        $coupon_amt = 1000;
                    }
                }
            } elseif ($couponCodeUpper == 'HYLO200') {
                if ($validatedData['payment_amount'] >= 1000) {
                    // Check if it's the first recharge for this user
                    $previousRecharges = Recharge::where('user_id', $validatedData['user_id'])->count();
                    if ($previousRecharges == 0) {
                        $coupon_amt = 200;
                    }
                }
            } elseif ($couponCodeUpper == 'HYLO500') {
                if ($validatedData['payment_amount'] >= 2000) {
                    // Check if it's the first recharge for this user
                    $previousRecharges = Recharge::where('user_id', $validatedData['user_id'])->count();
                    if ($previousRecharges == 0) {
                        $coupon_amt = 500;
                    }
                }
            }
        }

        if (Schema::hasColumn('wallet_recharge', 'coupon_amount')) {
            $validatedData['coupon_amount'] = $coupon_amt;
        }
        Recharge::create($validatedData);

        $admin = Admin::where('id', $validatedData['user_id'])->first();
        if ($admin){ 
            $admin->wallet_blc += ($validatedData['payment_amount'] + $coupon_amt); 
            $admin->save(); 
        }

        $transaction = new Transaction();
        $transaction->order_id = 0;
        $transaction->user_id = $validatedData['user_id'];
        $transaction->company_id = $validatedData['company_id'];
        $transaction->tracking_info = "Manual Wallet Recharge - Transaction ID: {$validatedData['payment_id']}";
        $transaction->credit = $validatedData['payment_amount'] + $coupon_amt;
        if (Schema::hasColumn('transactions', 'coupon_amount')) {
            $transaction->coupon_amount = $coupon_amt;
        }
        $transaction->debit = 0.00;
        $transaction->closing_blc = $admin->wallet_blc ?? 0;
        $transaction->remarks = '{"\u0000*\u0000attributes":{"id":"'.$validatedData["payment_id"].'"}}';
        $transaction->save();

        return redirect()->back()->with('success', 'Wallet recharged successfully.' . ($coupon_amt > 0 ? " Cashback of ₹{$coupon_amt} applied!" : ""));
    }
    
    public function saveinvdetails(Request $request, $id = 0){
        $inv_detail = new InvoiceDetail();
        $inv_detail->invoice_id = $id;
        $inv_detail->amount = $request->amount;
        $inv_detail->utr = $request->utr;
        $inv_detail->added_by = Auth::guard('admin')->user()->id;
        $inv_detail->company_id = Auth::guard('admin')->user()->company_id;
        $inv_detail->save();
        return redirect()->back()->with('success', 'Added successfully.');
    }
    
    public function shipping_charges() {
        $user_data = Auth::guard('admin')->user();
        $user_id = $user_data->id;
        $role_id = $user_data->role_id;
        
        $order_fwd_q = Order::with('detail')->where('shipping_courier_cost', '!=', '0')->where('reverse_order', '=', '0');
        $order_bwd_q = Order::with('detail')->where('shipping_courier_cost', '!=', '0')->where('reverse_order', '=', '1');

        if ($role_id == '1') {
            if ($user_id != '1') {
                $order_fwd_q = $order_fwd_q->where('company_id', $user_data->company_id);
                $order_bwd_q = $order_bwd_q->where('company_id', $user_data->company_id);
            }
        } elseif ($role_id == '2') {
            $sub_user_id = Admin::getsubuserid($user_id);
            $order_fwd_q = $order_fwd_q->whereIn('user_id', $sub_user_id);
            $order_bwd_q = $order_bwd_q->whereIn('user_id', $sub_user_id);
        } else {
            $order_fwd_q = $order_fwd_q->where('user_id', $user_id);
            $order_bwd_q = $order_bwd_q->where('user_id', $user_id);
        }

        $order_fwd = $order_fwd_q->get();
        $order_bwd = $order_bwd_q->get();

        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);
       
        return view('admin.billing.shipping_charges2', compact('order_fwd', 'order_bwd', 'user_id', 'couriers'));
    }
    
    public function creditNotes() {
        $user_data =Auth::guard('admin')->user();
        $user_id = $user_data->id;
       
        if($user_data->role_id =='1'){
            $invoices = Invoice::with('detail')->where('show_data','1')->where('admins.company_id',$user_data->company_id)
                    ->join('admins', 'admins.id', '=', 'invoices.user_id')
                    ->select('invoices.*','admins.id as us_id','admins.company_name')->get();
        }elseif($user_data->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user_id);
            $invoices = Invoice::with('detail')->where('show_data','1')->where('admins.company_id',$user_data->company_id)
            ->whereIn('user_id' , $sub_user_id)->join('admins', 'admins.id', '=', 'invoices.user_id')
                    ->select('invoices.*','admins.id as us_id','admins.company_name')->get();
        }else{
            $invoices = Invoice::with('detail')->where('show_data','1')->where('user_id' , $user_id)->join('admins', 'admins.id', '=', 'invoices.user_id')
                    ->select('invoices.*','admins.id as us_id','admins.company_name')->get();
        }

        return view('admin.billing.credit_notes',compact('invoices','user_id'));
    }
    
    public function walletTransaction() {
        $user_data = Auth::guard('admin')->user();
        $user_id = $user_data->id;
        $role_id = $user_data->role_id;
        
        $transactionq = Transaction::with(['admin', 'order'])->orderBy('id', 'desc')->where('show_data', '1');
        
        if ($role_id == '1') {
            if ($user_id == '1') {
                $transactionq = $transactionq->where('parent_data', '0');
            } else {
                $transactionq = $transactionq->where(function ($q) use ($user_data) {
                    $q->where('company_id', $user_data->company_id)
                      ->orWhereHas('admin', function ($a) use ($user_data) {
                          $a->where('company_id', $user_data->company_id);
                      });
                });
            }
        } elseif ($role_id == '2') {
            $sub_user_id = Admin::getsubuserid($user_id);
            $transactionq = $transactionq->whereIn('user_id', $sub_user_id);
        } else {
            $transactionq = $transactionq->where('user_id', $user_id);
        }
       
        $re_data['start_date'] = '';
        $re_data['end_date'] = '';
        $re_data['search'] = '';

        if(isset($_REQUEST['search']) && $_REQUEST['search'] !=''){
            $search = trim($_REQUEST['search']);
            $re_data['search'] = $search;
            $transactionq = $transactionq->where(function ($q) use ($search) {
                $q->where('remarks', 'like', "%{$search}%")
                  ->orWhere('awb', 'like', "%{$search}%");
            });
        }

        if(isset($_REQUEST['start_date']) && $_REQUEST['start_date'] !=''){
            $re_data['start_date'] = $_REQUEST['start_date'].' 00:00:00';
            $re_data['end_date'] = $_REQUEST['end_date'].' 23:59:59';
            $transactionq = $transactionq->where('created_at','>=',$re_data['start_date'])->where('created_at','<=',$re_data['end_date']);
        }
        else {
            if (empty($re_data['search'])) {
                $re_data['start_date'] = now()->startOfMonth();
                $re_data['end_date'] = now()->endOfMonth(); 
                $transactionq = $transactionq->where('created_at', '>=', $re_data['start_date'])
                    ->where('created_at', '<=', $re_data['end_date']);
            } else {
                $re_data['start_date'] = '';
                $re_data['end_date'] = '';
            }
        }
        $transaction =  $transactionq->get();

        // Fetch pending weight reconciliation orders to merge as virtual transaction rows
        $pending_orders_q = Order::whereIn('extra_weight_status', [1, 5])->where('extra_weight_cost', '>', 0);
        
        if ($role_id == '1') {
            if ($user_id != '1') {
                $pending_orders_q = $pending_orders_q->where('company_id', $user_data->company_id);
            }
        } elseif ($role_id == '2') {
            $sub_user_id = Admin::getsubuserid($user_id);
            $pending_orders_q = $pending_orders_q->whereIn('user_id', $sub_user_id);
        } else {
            $pending_orders_q = $pending_orders_q->where('user_id', $user_id);
        }

        if (!empty($re_data['search'])) {
            $search = $re_data['search'];
            if (str_contains(strtolower('Weight reconciliation (Pending)'), strtolower($search))) {
                if (!empty($re_data['start_date']) && !empty($re_data['end_date'])) {
                    $pending_orders_q = $pending_orders_q->where('updated_at', '>=', $re_data['start_date'])
                                                         ->where('updated_at', '<=', $re_data['end_date']);
                }
            } else {
                $pending_orders_q = $pending_orders_q->where(function ($q) use ($search) {
                    $q->where('tracking_info', 'like', "%{$search}%")
                      ->orWhere('order_id', 'like', "%{$search}%");
                });
                if (!empty($re_data['start_date']) && !empty($re_data['end_date'])) {
                    $pending_orders_q = $pending_orders_q->where('updated_at', '>=', $re_data['start_date'])
                                                         ->where('updated_at', '<=', $re_data['end_date']);
                }
            }
        } else {
            if (!empty($re_data['start_date']) && !empty($re_data['end_date'])) {
                $pending_orders_q = $pending_orders_q->where('updated_at', '>=', $re_data['start_date'])
                                                     ->where('updated_at', '<=', $re_data['end_date']);
            }
        }
        
        $pending_orders = $pending_orders_q->get();
        $virtual_txs = collect();
        foreach ($pending_orders as $order) {
            $mockTx = new Transaction();
            $mockTx->id = 'Pending';
            $mockTx->order_id = $order->id;
            $mockTx->user_id = $order->getRawOriginal('user_id');
            $mockTx->company_id = $order->company_id;
            $mockTx->awb = $order->tracking_info;
            $mockTx->credit = 0.00;
            $mockTx->debit = $order->extra_weight_cost;
            $mockTx->closing_blc = '-';
            $mockTx->remarks = 'Weight reconciliation (Pending)';
            $mockTx->created_at = $order->updated_at;
            $mockTx->updated_at = $order->updated_at;
            
            $mockTx->setRelation('order', $order);
            $admin = Admin::find($order->getRawOriginal('user_id'));
            $mockTx->setRelation('admin', $admin);
            
            $virtual_txs->push($mockTx);
        }
        
        $transaction = $transaction->concat($virtual_txs)->sortByDesc('created_at');

        return view('admin.billing.wallet_transaction',compact('re_data','transaction'));
    }    public function exportWalletTransactions(Request $request) {
        $user_data = Auth::guard('admin')->user();
        $user_id = $user_data->id;
        $role_id = $user_data->role_id;
        
        $transactionq = Transaction::with(['admin', 'order'])->orderBy('id', 'desc')->where('show_data', '1');
        
        if ($role_id == '1') {
            if ($user_id == '1') {
                $transactionq = $transactionq->where('parent_data', '0');
            } else {
                $transactionq = $transactionq->where(function ($q) use ($user_data) {
                    $q->where('company_id', $user_data->company_id)
                      ->orWhereHas('admin', function ($a) use ($user_data) {
                          $a->where('company_id', $user_data->company_id);
                      });
                });
            }
        } elseif ($role_id == '2') {
            $sub_user_id = Admin::getsubuserid($user_id);
            $transactionq = $transactionq->whereIn('user_id', $sub_user_id);
        } else {
            $transactionq = $transactionq->where('user_id', $user_id);
        }
        
        if ($request->filled('search')) {
            $search = trim($request->search);
            $transactionq = $transactionq->where(function ($q) use ($search) {
                $q->where('remarks', 'like', "%{$search}%")
                  ->orWhere('awb', 'like', "%{$search}%");
            });
        }

        if ($request->filled('start_date')) {
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';
            $transactionq = $transactionq->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date);
        } else {
            if (!$request->filled('search')) {
                $start_date = now()->startOfMonth();
                $end_date = now()->endOfMonth();
                $transactionq = $transactionq->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date);
            }
        }
        
        $transactions = $transactionq->get();

        // Fetch pending weight reconciliation orders to merge as virtual transaction rows
        $pending_orders_q = Order::whereIn('extra_weight_status', [1, 5])->where('extra_weight_cost', '>', 0);
        
        if ($role_id == '1') {
            if ($user_id != '1') {
                $pending_orders_q = $pending_orders_q->where('company_id', $user_data->company_id);
            }
        } elseif ($role_id == '2') {
            $sub_user_id = Admin::getsubuserid($user_id);
            $pending_orders_q = $pending_orders_q->whereIn('user_id', $sub_user_id);
        } else {
            $pending_orders_q = $pending_orders_q->where('user_id', $user_id);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            if (str_contains(strtolower('Weight reconciliation (Pending)'), strtolower($search))) {
                if ($request->filled('start_date')) {
                    $start_date = $request->start_date . ' 00:00:00';
                    $end_date = $request->end_date . ' 23:59:59';
                    $pending_orders_q = $pending_orders_q->where('updated_at', '>=', $start_date)->where('updated_at', '<=', $end_date);
                }
            } else {
                $pending_orders_q = $pending_orders_q->where(function ($q) use ($search) {
                    $q->where('tracking_info', 'like', "%{$search}%")
                      ->orWhere('order_id', 'like', "%{$search}%");
                });
                if ($request->filled('start_date')) {
                    $start_date = $request->start_date . ' 00:00:00';
                    $end_date = $request->end_date . ' 23:59:59';
                    $pending_orders_q = $pending_orders_q->where('updated_at', '>=', $start_date)->where('updated_at', '<=', $end_date);
                }
            }
        } else {
            if ($request->filled('start_date')) {
                $start_date = $request->start_date . ' 00:00:00';
                $end_date = $request->end_date . ' 23:59:59';
                $pending_orders_q = $pending_orders_q->where('updated_at', '>=', $start_date)->where('updated_at', '<=', $end_date);
            } else {
                $start_date = now()->startOfMonth();
                $end_date = now()->endOfMonth();
                $pending_orders_q = $pending_orders_q->where('updated_at', '>=', $start_date)->where('updated_at', '<=', $end_date);
            }
        }
        
        $pending_orders = $pending_orders_q->get();
        $virtual_txs = collect();
        foreach ($pending_orders as $order) {
            $mockTx = new Transaction();
            $mockTx->id = 'Pending';
            $mockTx->order_id = $order->id;
            $mockTx->user_id = $order->getRawOriginal('user_id');
            $mockTx->company_id = $order->company_id;
            $mockTx->awb = $order->tracking_info;
            $mockTx->credit = 0.00;
            $mockTx->debit = $order->extra_weight_cost;
            $mockTx->closing_blc = '-';
            $mockTx->remarks = 'Weight reconciliation (Pending)';
            $mockTx->created_at = $order->updated_at;
            $mockTx->updated_at = $order->updated_at;
            
            $mockTx->setRelation('order', $order);
            $admin = Admin::find($order->getRawOriginal('user_id'));
            $mockTx->setRelation('admin', $admin);
            
            $virtual_txs->push($mockTx);
        }
        
        $transactions = $transactions->concat($virtual_txs)->sortByDesc('created_at');
 
        return (new FastExcel($transactions))->download('wallet_transactions.xlsx', function ($row) use ($role_id) {
            $data = [];
            if ($role_id == '1' || $role_id == '2') {
                $data['User'] = $row->admin->name ?? '';
                $data['Company'] = $row->admin->company_name ?? '';
            }
            $data['Date & Time'] = \Carbon\Carbon::parse($row->updated_at)->format('d M, Y H:i');
            $data['Tracking ID'] = $row->id;
            $data['AWB'] = $row->awb ?? '';
            $data['Credit'] = $row->credit;
            $data['Debit'] = $row->debit != 0 ? '-' . $row->debit : '0.00';
            $data['Closing Blc'] = $row->closing_blc;
            $data['Remarks'] = $row->remarks;
            
            $data['Entered Weight (kg)'] = '';
            $data['Applied Weight (kg)'] = '';
            $data['Weight Difference (kg)'] = '';
            $data['Extra Weight Cost (INR)'] = '';
            if ($row->order_id != 0 && $row->order && $row->order->extra_weight > 0) {
                $entered = floatval($row->order->shipping_courier_weight);
                $applied = floatval($row->order->extra_weight) / 1000;
                $data['Entered Weight (kg)'] = $entered;
                $data['Applied Weight (kg)'] = $applied;
                $data['Weight Difference (kg)'] = $applied - $entered;
                $data['Extra Weight Cost (INR)'] = floatval($row->order->extra_weight_cost);
            }
            
            return $data;
        });
    }

    public function invoices()
    {
        $user_data = Auth::guard('admin')->user();
        $user_id = $user_data->id;
        $role_id = $user_data->role_id;
        
        if ($role_id == '1') {
            $invoices = Invoice::with('detail')->where('show_data','1')->where('admins.company_id',$user_data->company_id)
                    ->join('admins', 'admins.id', '=', 'invoices.user_id')
                    ->select('invoices.*','admins.id as us_id','admins.company_name')->get();
        } elseif ($role_id == '2') {
            $sub_user_id = Admin::getsubuserid($user_id);
            $invoices = Invoice::with('detail')->where('show_data','1')->where('admins.company_id',$user_data->company_id)
            ->whereIn('user_id' , $sub_user_id)->join('admins', 'admins.id', '=', 'invoices.user_id')
            ->select('invoices.*','admins.id as us_id','admins.company_name')->get();
        } else {
            $invoices = Invoice::with('detail')->where('show_data','1')
            ->where('user_id' , $user_id)
            ->join('admins', 'admins.id', '=', 'invoices.user_id')
            ->select('invoices.*','admins.id as us_id','admins.company_name')->get();
        }
        
        return view('admin.billing.invoices', compact('invoices', 'user_id', 'role_id'));
     }
}