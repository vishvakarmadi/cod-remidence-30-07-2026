<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\FileManager;
use DB;

class PhotoChangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $admin_data = Admin::where('id',Auth::guard('admin')->user()->id)->first();
        return view('admin.auth.photo_change', compact('admin_data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data['photo'] = FileManager::update($request->file('photo'),'/uploads/',$request->current_photo ?? 'default.png');
        Admin::where('id',Auth::guard('admin')->user()->id)->update($data);

        return Redirect()->back()->with('success', 'Photo is updated successfully!');
    }

}
