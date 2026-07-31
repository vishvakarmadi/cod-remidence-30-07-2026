<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\State;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use DB;

class ProfileChangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $admin_data = Admin::where('id',Auth::guard('admin')->user()->id)->first();
        $states = State::where('country_id','101')->where('state_code','!=','0')->get();
        return view('admin.auth.profile_change', compact('admin_data','states'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'pin_code' => 'required',
            'state' => 'required',
            'mobile' => 'required',
            'company_address' => 'required',
            
            
            
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['mobile'] = $request->mobile;
        $data['company_name'] = $request->company_name;
        $data['company_address'] = $request->company_address;
        $data['place_supply'] = $request->place_supply;
         $data['pin_code'] = $request->pin_code;
        $data['state'] = $request->state;
        Admin::where('id',Auth::guard('admin')->user()->id)->update($data);

        return Redirect()->back()->with('success', 'Profile Information is updated successfully!');

    }

}
