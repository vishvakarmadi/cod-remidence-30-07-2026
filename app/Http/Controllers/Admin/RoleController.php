<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Profile;
use App\Models\Admin\Admin;
use App\Models\Admin\Role_permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin\CustomerQuery;
use App\Models\Admin\VisitedCustomer;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Transaction;
use DB;
use Hash;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function user()
    {
        $c_user = auth()->guard('admin')->user();
        if($c_user->role_id =='1' || $c_user->role_id =='2')
        // if(auth()->guard('admin')->user()->hasPermissionTo('list user'))
        {
            if(auth()->guard('admin')->user()->role_id =='1'){
            $admin_users = Admin::with('profile')
            ->where('delete_status', 0) 
            ->where('id' ,'!=', $c_user->id)
            ->where('company_id','=',$c_user->company_id)
            ->get();
            }else{
                $sub_user_id = Admin::getsubuserid($c_user->id);
                $admin_users = Admin::with('profile')
                ->where('delete_status', 0) 
                ->whereIn('id',$sub_user_id)
                ->where('id' ,'!=', auth()->guard('admin')->user()->id)
                ->where('company_id','=',$c_user->company_id)
                ->get();
            }
//            echo $admin_users;die;
            return view('admin.role.user', compact('admin_users'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }
    
    public function user_query()
    {
        $c_user = auth()->guard('admin')->user();
        if($c_user->role_id =='1' || $c_user->role_id =='2')
        // if(auth()->guard('admin')->user()->hasPermissionTo('list user'))
        {
            $user_query = CustomerQuery::orderBy('id', 'desc')->where('resolved','0')->get();
//            echo '<pre>';print_R($user_query);die;
            return view('admin.role.user_query', compact('user_query'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }
    public function user_view()
    {
        $c_user = auth()->guard('admin')->user();
        if($c_user->role_id =='1' || $c_user->role_id =='2')
        // if(auth()->guard('admin')->user()->hasPermissionTo('list user'))
        {
            $user_query = VisitedCustomer::orderBy('id', 'desc')->get();
//            echo '<pre>';print_R($user_query);die;
            return view('admin.role.user_view', compact('user_query'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }

    public function user_create()
    {
        $c_user = auth()->guard('admin')->user();
        if($c_user->role_id =='1' || $c_user->role_id =='2')
        // if(auth()->guard('admin')->user()->hasPermissionTo('create user'))
        {
            if($c_user->role_id ==1){
                $roles = Role::where('id','!=','3')->get();
            }elseif($c_user->role_id ==2){
                $roles = Role::orderBy('id', 'desc')->where('id','!=','1')->where('id','!=','2')->get();
            }
            return view('admin.role.user_create', compact('roles'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }

    public function user_store(Request $request)
    {
        $admin = new Admin();
        $data = $request->only($admin->getFillable());

        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|unique:admins',
                'password' => 'required',
                're_password' => 'required|same:password'
            ],
            [],
            [
                'name' => 'Name',
                'email' => 'Email',
                'password' => 'Password',
                're_password' => 'Retype Password'
            ]
        );
        
        $statement = DB::select("SHOW TABLE STATUS LIKE 'admins'");
        $ai_id = $statement[0]->Auto_increment;
        if($request->file('photo')){
            $ext = $request->file('photo')->extension();
            $final_name = 'user-'.$ai_id.'.'.$ext;
            $request->file('photo')->move(public_path('uploads/'), $final_name);
            $data['photo'] = $final_name;
        }
        $data['password'] = Hash::make($request->password);
        $data['token'] = '';
        $data['company_id'] = $request->current_id;
        $admin->fill($data)->save();

        $id = $admin->id;
        $get = Admin::where('id',$id)->first();
        $role = Role::where('id', request()->role_id)->first();
        $get->assignRole($role->name);
        $permissions = $get->getAllPermissions();
        
        $ad = Admin::findOrFail($id);
        $ad->parent_id = $request->parent_id;
        if($request->role_id =='2'){
            $ad->role_action = '1';
            $ad->role_action_by = auth()->guard('admin')->user()->id;
            $ad->role_action_on = now();
        }
        $ad->save();
        if($request->role_id =='3'){
            return redirect()->route('admin.role.user')->with('error', 'Required admin permission to make super-admin,please contact admin');
        }else{
            return redirect()->route('admin.role.user')->with('success', 'Admin User is added successfully!');
        }
        
    }


    public function user_edit($id)
    {
        $sub_user_id = Admin::getsubuserid(auth()->guard('admin')->user()->id);
        if(auth()->guard('admin')->user()->id == $id || auth()->guard('admin')->user()->role_id ==1 || (auth()->guard('admin')->user()->role_id ==2 && in_array($id,$sub_user_id)))
        {
            if(auth()->guard('admin')->user()->role_id ==1){
                $roles = Role::where('id','!=','3')->get();
            }elseif(auth()->guard('admin')->user()->role_id ==2){
                $roles = Role::where('id','!=','1')->where('id','!=','2')->get();
            }
            $admin_user = Admin::findOrFail($id);
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
            return view('admin.role.user_edit', compact('admin_user','roles','parents'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }

    public function user_update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $data = $request->only($admin->getFillable());
        $rol_permission = $admin->role_action;
        $rol_previous = $admin->role_id;
        if($request->hasFile('photo')) {
            $request->validate([
                'name'   =>  [
                    'required'
                ],
                'email'   =>  [
                    Rule::unique('admins')->ignore($id),
                ],
                'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            unlink(public_path('uploads/'.$admin->photo));
            $ext = $request->file('photo')->extension();
            $final_name = 'user-'.$id.'.'.$ext;
            $request->file('photo')->move(public_path('uploads/'), $final_name);
            $data['photo'] = $final_name;
        } else {
            $request->validate([
                'name'   =>  [
                    'required'
                ],
                'email'   =>  [
                    Rule::unique('admins')->ignore($id),
                ]
            ]);
            $data['photo'] = $admin->photo;
        }
//        $data['kam'] = $request->kam;
//        echo '<pre>';print_R($data);die;
        $admin->fill($data)->save();

        $role = Role::where('id', request()->role_id)->first();
        $admin->assignRole($role->name);
        $permissions = $admin->getAllPermissions();
        if($request->role_id =='3'){
            if($rol_permission =='1'){
                $ad = Admin::findOrFail($id);
                $ad->role_id = '2';
                $ad->save();
            }
        }
            $userkm = Admin::findOrFail($id);
            $userkm->sm = $request->sm ?? null;
            $userkm->userpayment_type = $request->userpayment_type ?? null;
            if ($request->has('parent_id')) {
                $userkm->parent_id = $request->parent_id;
            }
            $userkm->save();
        
        if($rol_previous != $request->role_id && $request->role_id =='2'){
            $user = Admin::findOrFail($id);
            $user->role_action = '1';
            $user->role_action_by = auth()->guard('admin')->user()->id;
            $user->role_action_on = now();
            $user->save();
        }
        // Update Bank Details in Profile table if provided
        if ($request->filled('bank_name')) {
            $profile_data = [
                'bank_name' => $request->bank_name,
                'beneficiary_name' => $request->beneficiary_name,
                'account_no' => $request->account_no,
                'ifsc_code' => $request->ifsc_code,
                'account_type' => $request->account_type,
            ];

            Profile::updateOrCreate(
                ['user_id' => $id],
                $profile_data
            );
        }
        // dd($permissions);
        return redirect()->route('admin.role.user')->with('success', 'Admin User is updated successfully!');
    }

    public function user_edit_password($id)
    {
        if(auth()->guard('admin')->user()->hasPermissionTo('change user'))
        {
            $admin_user = Admin::findOrFail($id);
            return view('admin.role.user_edit_password', compact('admin_user'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }

    public function user_update_password(Request $request, $id)
    {
        $request->validate([
            'password' => 'required',
            're_password' => 'required|same:password',
        ]);

        $data['password'] = Hash::make($request->password);
        Admin::where('id',$id)->update($data);

        return redirect()->route('admin.role.user')->with('success', 'Admin User Password is updated successfully!');
    }

    public function user_destroy($id)
    {
        if(auth()->guard('admin')->user()->hasPermissionTo('delete user'))
        {
            $admin = Admin::findOrFail($id);
            Profile::where('user_id',$id)->delete();
            $admin->delete_status = 1;
          	$admin->email = $admin->email.'-deleted';
            $admin->save();
    
            return redirect()->route('admin.role.user')->with('success', 'Admin User Deleted successfully!');
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }
    public function addloan($id)
    {
        return view('admin.role.addloan', compact('id'));
    }

    public function index()
    {
        if(auth()->guard('admin')->user()->hasPermissionTo('list role'))
        {
            $roles = Role::all();
            $edit = '';
            return view('admin.role.index', compact('roles','edit'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }

    public function create()
    {
        if(auth()->guard('admin')->user()->hasPermissionTo('create role'))
        {
            $pageTitle = "Create Role";
            $prefix = DB::table('prefix')->pluck('prefix', 'id')->toArray();
            $permissions = DB::table('permissions')->pluck('name', 'id')->toArray();
    
            return view('admin.role.create', compact('pageTitle', 'permissions','prefix'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:roles',
            'permissions' => 'required',
        ]);
        
        $auth = auth()->guard('admin')->user();
        $role = Role::create(['name' => request()->name,'guard_name' => 'web']);
        $permissions = $request['permissions'];

        foreach($permissions as $permission)
        {
            $role->givePermissionTo([$permission]);
        }

        return redirect()->route('admin.role.index')->with('success', 'Role is added successfully!');
    }

    public function edit($id)
    {
        if(auth()->guard('admin')->user()->hasPermissionTo('edit role'))
        {
            $pageTitle = "Edit Role";
            $role = Role::findOrFail($id);
            $prefix = DB::table('prefix')->pluck('prefix', 'id')->toArray();
            $permissions = DB::table('permissions')->pluck('name', 'id')->toArray();
            $permit = $role->getAllPermissions()->pluck('id');
    
            return view('admin.role.edit', compact('pageTitle', 'permissions','prefix','role','permit'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => [
                'required',
                'max:100',
                Rule::unique('roles')->ignore($id),
            ],
            'permissions' => 'required',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();

        $role->syncPermissions([]);

        $permissions = $request['permissions'];
        foreach($permissions as $permission)
        {
            $role->givePermissionTo([$permission]);
        }
        return redirect()->route('admin.role.index')->with('success', 'Role is updated successfully!');
    }

    public function view($id)
    {
        if(auth()->guard('admin')->user()->hasPermissionTo('show role'))
        {
            $role = Role::findOrFail($id);
            $pageTitle = "View Role";
            $prefix = DB::table('prefix')->pluck('prefix', 'id')->toArray();
            $permissions = DB::table('permissions')->pluck('name', 'id')->toArray();
            $permit = $role->getAllPermissions()->pluck('id');
    
            return view('admin.role.view', compact('pageTitle', 'permissions','prefix','role','permit'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }


    public function destroy($id)
    {
        if(auth()->guard('admin')->user()->hasPermissionTo('delete role'))
        {
            $check = DB::table('admins')->where('role_id',$id)->where('delete_status',0)->get();
            if(count($check) != 0)
            {
                $notify[] = ['error', 'This Role Assigned For Someone'];
                return Redirect()->back()->withNotify($notify);
            }
    
            $role = Role::findOrFail($id);
            $role->syncPermissions([]);
            $role->delete();
            return redirect()->back()->with('success', 'Role is deleted successfully!');
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }

    public function per_create()
    {
        $pageTitle = "Create Permission";
        return view('admin.role.per_create', compact('pageTitle'));
    }

    public function per_store(Request $request)
    {
        $request->validate([
            'prefix' => 'required',
            'permission' => 'required',
        ]);

        $check = DB::table('prefix')->where('prefix',request()->prefix)->first();
        if($check)
        {
            $pageTitle = "Edit Permission";
            $module = request()->prefix;
            $permissions = DB::table('permissions')->pluck('name', 'id')->toArray();
            $matchedPermissions = array_filter($permissions, function($permission) use ($module) {
                return strpos($permission, $module) !== false;
            });
            foreach ($matchedPermissions as $item) {
                $words = explode(' ', $item);
                $array[] = $words[0];
            }
            // dd($array);
            return view('admin.role.per_edit', compact('pageTitle','check','matchedPermissions','array'));
        }
        else
        {
            DB::table('prefix')->insert(['prefix' => $request->prefix]);
        }
        
        foreach(request()->permission as $row)
        {
            DB::table('permissions')->insert(['name' => $row.' '.request()->prefix, 'guard_name' => 'web']);
        }

        $adminRole = Role::findByName('admin', 'web');
        $allPermissions = Permission::all();
        $adminRole->syncPermissions($allPermissions);
        
        $admin = Admin::where('id',1)->first();
        $admin->assignRole($adminRole->name);
        $permissions = $admin->getAllPermissions();

        $notify[] = ['success', 'Permissions successfully'];
        return Redirect()->back()->withNotify($notify);
    }


    public function per_update(Request $request,$id)
    {
        $request->validate([
            'prefix' => 'required',
            'permission' => 'required',
        ]);


        $module = request()->prefix;
        $get = DB::table('permissions')->pluck('name', 'id')->toArray();
        $permissions = array_filter($get, function($get) use ($module) {
            return strpos($get, $module) !== false;
        });


        $permissionIds = array_keys($permissions);
        Permission::whereIn('id', $permissionIds)->delete();
        $roles = Role::all();
        foreach ($roles as $role) {
            $role->revokePermissionTo($permissionIds);
        }
        foreach ($permissionIds as $permissionId) {
            unset($permissions[$permissionId]);
        }

        foreach(request()->permission as $row)
        {
            DB::table('permissions')->insert(['name' => $row.' '.request()->prefix, 'guard_name' => 'web']);
        }

        $adminRole = Role::findByName('admin', 'web');
        $allPermissions = Permission::all();
        $adminRole->syncPermissions($allPermissions);

        $notify[] = ['success', 'Permissions successfully'];
        return redirect()->route('admin.role.per_create')->withNotify($notify);
    }

    public function loginAsUser($id){
        $sub_user_id = Admin::getsubuserid(auth()->guard('admin')->user()->id);
        if(auth()->guard('admin')->user()->id == $id || auth()->guard('admin')->user()->role_id ==1 || (auth()->guard('admin')->user()->role_id ==2 && in_array($id,$sub_user_id)))
        {
            
            $user = Admin::where(['delete_status' => 0])->find($id); 
            if (!$user) {
                abort(404);
            }
            createlogs('loginAs','login',$id);
            // dd(Auth::guard('admin')->login($user));
            Auth::guard('admin')->login($user);
            $admin_d = Admin::where('id', $id)->first();
            $admin_d->last_login = now();
            $admin_d->save();
            return redirect()->route('employee.dashboard');
        }else{
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }

    public function user_active($id)
    {
        $sub_user_id = Admin::getsubuserid(auth()->guard('admin')->user()->id);
        if(auth()->guard('admin')->user()->id == $id || auth()->guard('admin')->user()->role_id ==1 || (auth()->guard('admin')->user()->role_id ==2 && in_array($id,$sub_user_id)))
            {
            $user = Admin::find($id);
            if($user !=''){
                if($user->active ==0){
                    $user->active = '1';
                }else{
                    $user->active = '0';
                }
                $user->save();
            }else{
                return Redirect()->route('admin.role.user')->with('error', 'No Permission To Access');
            }
            
            return redirect()->route('admin.role.user')->with('success', 'Status changed successfully!');
        }else{
            return Redirect()->route('admin.role.user')->with('error', 'No Permission To Access');
        }
    }

    public function user_supseller($id){
        if(auth()->guard('admin')->user()->role_id ==1){
            $user = Admin::find($id);
            if($user !=''){
                
                $user->role_id = '2';
                $user->role_action = '1';
                $user->role_action_by = auth()->guard('admin')->user()->id;
                $user->role_action_on = now();
                $user->save();
            }else{
                return Redirect()->route('admin.role.user')->with('error', 'No Permission To Access');
            }
            
            return redirect()->route('admin.role.user')->with('success', 'Status changed successfully!');
        }else{
            return Redirect()->route('admin.role.user')->with('error', 'No Permission To Access');
        }
    }
    
    public function updateloan(){
        
        $user = admin::find($_REQUEST['u_id']);
        if($user){
           if($_REQUEST['amount'] >0){
                $newwallet = $user->wallet_blc + $_REQUEST['amount'];
                $newloan = $user->loan_amount + $_REQUEST['amount'];
                
                $transaction = new Transaction();
                $transaction->order_id = 0;
                $transaction->user_id = $_REQUEST['u_id'];
                $transaction->awb = '';
                $transaction->tracking_info = '';
                $transaction->credit = $_REQUEST['amount'];
                $transaction->closing_blc = $newwallet;
                $transaction->debit = '0.00';
                $transaction->remarks = "Wallet Recharge as loan";
                $transaction->save();
                
                $user->wallet_blc = $newwallet;
                $user->loan_amount = $newloan;
                $user->loan_date = now();
                $user->save();
           }else{
               return Redirect()->route('admin.role.user')->with('error', 'Wrong Amount');
           }
        }else{
            return Redirect()->route('admin.role.user')->with('error', 'No User found');
        }
        return redirect()->route('admin.role.user')->with('success', 'Status changed successfully!');
        
        
    }






}