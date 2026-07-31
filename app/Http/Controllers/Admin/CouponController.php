<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Coupon;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use App\Models\Admin\Recharge;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use DB;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $user = Auth::guard('admin')->user();
        if($user->role_id =='1'){
            $coupon = Coupon::where(['active' => '1'])->get();
        }else{
            $coupon = Coupon::where(['created_by' => $user->id,'active' => '1'])->get();
        }
        return view('admin.coupon.index', compact('coupon'));
    }

    public function create()
    {
        $user = Auth::guard('admin')->user();
        if($user->role_id =='1'){
            $users = Admin::query()->where('delete_status', '=', 0)->get();
        }else{
            $users = Admin::query()->where('id', '=', $user->id)->get();
        }
        return view('admin.coupon.create', compact('users'));
    }

    public function store(Request $request)
    {
        $coupon = new Coupon();
        // $data = $request->only($coupon->getFillable());

        $request->validate([
            'coupon_code' => 'required|unique:coupons',
            'coupon_type' => 'required',
            'coupon_discount' => 'required',
            'coupon_start_date' => 'required',
            'coupon_end_date' => 'required',
            'coupon_status' => 'required',
            'created_by' => 'required'
        ]);
        $coupon->coupon_code = $request->coupon_code;
        $coupon->coupon_type = $request->coupon_type;
        $coupon->created_by = $request->created_by;
        $coupon->coupon_discount = $request->coupon_discount;
        $coupon->coupon_start_date = $request->coupon_start_date;
        $coupon->coupon_end_date = $request->coupon_end_date;
        $coupon->coupon_status = $request->coupon_status;
        $coupon->updated_at = now();
        $coupon->created_at = now();
        $coupon->save();

        // $coupon->fill($data)->save();
        return redirect()->route('admin.coupon.index')->with('success', 'Coupon is added successfully!');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $user = Auth::guard('admin')->user();
        if($user->role_id =='1'){
            $users = Admin::query()->where('delete_status', '=', 0)->get();
        }else{
            $users = Admin::query()->where('id', '=', $user->id)->get();
            if($coupon->created_by !=$user->id){
                return redirect()->route('admin.coupon.index')->with('error', "You  don't have permission to edit this");
            }
            
        }
        return view('admin.coupon.edit', compact('coupon','users'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        // $data = $request->only($coupon->getFillable());

        $request->validate([
            'coupon_code' => [
                'required',
                Rule::unique('coupons')->ignore($id),
            ],
            'coupon_type' => 'required',
            'coupon_discount' => 'required',
            'coupon_start_date' => 'required',
            'coupon_end_date' => 'required',
            'coupon_status' => 'required',
            'created_by' => 'required'
        ]);
        $coupon->coupon_code = $request->coupon_code;
        $coupon->coupon_type = $request->coupon_type;
        $coupon->created_by = $request->created_by;
        $coupon->coupon_discount = $request->coupon_discount;
        $coupon->coupon_start_date = $request->coupon_start_date;
        $coupon->coupon_end_date = $request->coupon_end_date;
        $coupon->coupon_status = $request->coupon_status;
        $coupon->updated_at = now();
        $coupon->save();
        // $coupon->fill($data)->save();
        return redirect()->route('admin.coupon.index')->with('success', 'Coupon is updated successfully!');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $user = Auth::guard('admin')->user();
        if($user->role_id =='1'){
            $coupon->delete();
        }else{
            if($coupon->created_by !=$user->id){
                return redirect()->route('admin.coupon.index')->with('error', "You  don't have permission to delete this");
            }
        }
        return Redirect()->back()->with('success', 'Coupon is deleted successfully!');
    }

    public function validateCoupon(Request $request)
    {
        $userId = Auth::guard('admin')->user()->id;
        $userEmail = trim(Auth::guard('admin')->user()->email ?? '');
        $companyId = Auth::guard('admin')->user()->company_id;
        
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
            if (\App\Models\Admin\Payment::where('user_email', $userEmail)->where('coupon_amount', '>', 0)->exists()) {
                $hasUsedCoupon = true;
            }
        }

        // Check in Payments table by company_id (if valid and > 1)
        if (!$hasUsedCoupon && $companyId !== null && $companyId !== '' && (int)$companyId > 1) {
            if (\App\Models\Admin\Payment::where('company_id', $companyId)->where('coupon_amount', '>', 0)->exists()) {
                $hasUsedCoupon = true;
            }
        }

        if ($hasUsedCoupon) {
            return response()->json([
                'success' => false,
                'message' => 'You have already used a coupon before. Only one coupon is allowed per user.'
            ]);
        }

        $inputCoupon = trim($request->coupon_code);
        $coupon = Coupon::query()->where([
            ['coupon_code', '=', $inputCoupon],
            ['coupon_start_date', '<=', now()],
            ['coupon_end_date', '>=', now()],
            ['coupon_status', '=', 'Show']
        ])->first();

        if ($coupon != null) {
            $is_welcome = (strcasecmp(trim($coupon->coupon_code), 'WELCOME') === 0);
            $is_hylo200 = (strcasecmp(trim($coupon->coupon_code), 'HYLO200') === 0);
            $is_hylo500 = (strcasecmp(trim($coupon->coupon_code), 'HYLO500') === 0);
            
            if ($is_welcome || $is_hylo200 || $is_hylo500) {
                // Double check if this is the user's first recharge or if they forgot to use a coupon on previous recharges
                $hasUsedCoupon = Recharge::where('user_id', $userId)->where('coupon_amount', '>', 0)->exists();
                
                if ($hasUsedCoupon) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Coupon ' . $coupon->coupon_code . ' is only valid if you have never used a coupon before.'
                    ]);
                }

                if ($is_welcome && $request->amount < 5000) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Coupon WELCOME requires a minimum recharge of ₹5000.'
                    ]);
                }

                if ($is_hylo200 && $request->amount < 1000) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Coupon HYLO200 requires a minimum recharge of ₹1000.'
                    ]);
                }

                if ($is_hylo500 && $request->amount < 2000) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Coupon HYLO500 requires a minimum recharge of ₹2000.'
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'is_cashback' => true,
                    'cashback_amount' => $coupon->coupon_discount,
                    'amount' => $request->amount,
                ]);
            }

            if ($coupon->coupon_type == 'Percentage') {
                $discount = $request->amount * $coupon->coupon_discount / 100; 
                $amount = $request->amount - $discount;
                return response()->json([
                    'success' => true,
                    'amount' => $amount,
                ]);
            }

            if ($coupon->coupon_type == 'Amount') {
                $amount = $request->amount - $coupon->coupon_discount; 
                return response()->json([
                    'success' => true,
                    'amount' => $amount,
                ]);
            }
        } else {
            return response()->json(['success' => false]);
        } 
    }
}
