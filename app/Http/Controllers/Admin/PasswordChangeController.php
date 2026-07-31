<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use DB;
use Hash;

class PasswordChangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $admin_data = Admin::where('id',Auth::guard('admin')->user()->id)->first();
        return view('admin.auth.password_change', compact('admin_data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => 'required',
            're_password' => 'required|same:password',
        ]);

        $data['password'] = Hash::make($request->password);
        Admin::where('id',Auth::guard('admin')->user()->id)->update($data);

        return Redirect()->back()->with('success', 'Password is updated successfully!');

    }

}
