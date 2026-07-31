<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\GeneralSetting;
use App\FileManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Admin\Courier;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\PhpProcess;
use Illuminate\Support\Facades\Config;
use DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Ticket;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerify;
use App\Models\Admin\Order;
use App\Models\Admin\TempAssignOrder;
use App\Models\LabelSetting;

class GeneralSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }


    public function settings(){
        $admin = auth()->guard('admin')->user();
        return view('admin.settings.settings',compact('admin'));
    }
       public function labalsetting(){
        $admin = auth()->guard('admin')->user();
        // Fetch setting specific to the logged-in user
        $setting = LabelSetting::where('user_id', $admin->id)->first();
        return view('admin.settings.labalsetting', compact('setting'));
    }
public function labalsettingSave(Request $request){
        $admin = auth()->guard('admin')->user();

        // ── VALIDATION (runs before try/catch so ValidationException is handled natively) ──
        $request->validate([
            'email'  => 'nullable|email|max:255',
            'mobile' => 'nullable|digits_between:7,15',
            'logo'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'email.email'            => 'Please enter a valid email address.',
            'mobile.digits_between'  => 'Mobile number must be between 7 and 15 digits.',
            'logo.image'             => 'Logo must be an image file.',
            'logo.mimes'             => 'Logo must be a jpeg, png, jpg, gif, or svg file.',
            'logo.max'               => 'Logo file size must not exceed 2MB.',
        ]);

        try {
            $data = [
                'user_id'                  => $admin->id,
                'company_id'               => $admin->company_id,
                'printer_type'             => $request->input('printer_type', 1),
                'email'                    => $request->input('email'),
                'mobile'                   => $request->input('mobile'),
                'logo_hidden'              => $request->has('logo_hidden')              ? 1 : 0,
                'hide_pickup_address'      => $request->has('hide_pickup_address')      ? 1 : 0,
                'hide_pickup_mobile'       => $request->has('hide_pickup_mobile')       ? 1 : 0,
                'hide_rto_address'         => $request->has('hide_rto_address')         ? 1 : 0,
                'hide_rto_mobile'          => $request->has('hide_rto_mobile')          ? 1 : 0,
                'hide_gst_number'          => $request->has('hide_gst_number')          ? 1 : 0,
                'hide_pickup_contact_name' => $request->has('hide_pickup_contact_name') ? 1 : 0,
                'hide_rto_contact_name'    => $request->has('hide_rto_contact_name')    ? 1 : 0,
                'hide_pickup_name'         => $request->has('hide_pickup_name')         ? 1 : 0,
                'hide_rto_name'            => $request->has('hide_rto_name')            ? 1 : 0,
                'hide_sku'                 => $request->has('hide_sku')                 ? 1 : 0,
                'show_hsn'                 => $request->has('show_hsn')                 ? 1 : 0,
                'hide_product'             => $request->has('hide_product')             ? 1 : 0,
                'hide_qty'                 => $request->has('hide_qty')                 ? 1 : 0,
                'hide_total_amount'        => $request->has('hide_total_amount')        ? 1 : 0,
                'hide_discount_amount'     => $request->has('hide_discount_amount')     ? 1 : 0,
                'show_gst'                 => $request->has('show_gst')                 ? 1 : 0,
                'hide_shipping_charges'    => $request->has('hide_shipping_charges')    ? 1 : 0,
                'hide_order_amount'        => $request->has('hide_order_amount')        ? 1 : 0,
                'hide_customer_mobile'     => $request->has('hide_customer_mobile')     ? 1 : 0,
                'hide_customer_name'       => $request->has('hide_customer_name')       ? 1 : 0,
                'hide_customer_address'    => $request->has('hide_customer_address')    ? 1 : 0,
                'hide_order_barcode'       => $request->has('hide_order_barcode')       ? 1 : 0,
                'hide_invoice_number'      => $request->has('hide_invoice_number')      ? 1 : 0,
                'a4_print_option'          => $request->input('a4_print_option', 0),
            ];

            // Handle logo upload — validation already passed above
            if ($request->hasFile('logo')) {
                $ext      = $request->file('logo')->extension();
                $filename = date('YmdHis') . '_label_logo.' . $ext;
                $request->file('logo')->move(public_path('uploads'), $filename);
                $data['logo'] = $filename;
            }

            // Find existing record by user_id
            $existing = LabelSetting::where('user_id', $admin->id)->first();

            if ($existing) {
                // Record exists → UPDATE it (preserve old logo if no new file uploaded)
                if (!isset($data['logo'])) {
                    unset($data['logo']); // keep old logo untouched
                }
                $existing->update($data);
            } else {
                // No record yet → CREATE new one
                LabelSetting::create($data);
            }

            return redirect()->back()->with('success', 'Label settings saved successfully!');

        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Database error while saving label settings. Please try again.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function cache(){
        return view('admin.general_setting.cache');
    }
    public function cache_clear(){
        Artisan::call('optimize:clear');
        return back()->with('success', 'Cache cleared successfully');
    }

    public function color_change()
    {
        ob_start();
        include public_path('/admin/assets/css/color_skins.css');
        $css = ob_get_clean();
    
        $get = DB::table('general_settings')->where('id','1')->first();
        $colorCode = $get->theme_color;
        $css = str_replace('{{color_code}}', $colorCode, $css);
        return response($css, 200, ['Content-Type' => 'text/css']);
    }
	
	


    

    public function logo_edit()
    {
        if(auth()->guard('admin')->user()->hasPermissionTo('edit component'))
        {
            $general_setting = GeneralSetting::where('id',1)->first();
            
            return view('admin.general_setting.logo', compact('general_setting'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }

    public function logo_update(Request $request)
    {
        $request->validate([
            'logo' => 'image|mimes:jpeg,png,svg,jpg,gif|max:2048',
            'white_logo' => 'image|mimes:jpeg,svg,png,jpg,gif|max:2048'
        ]);

        if($request->hasFile('logo'))
        {
            unlink(public_path('uploads/'.$request->current_photo));

            $ext = $request->file('logo')->extension();
            $currentime = date('Ymdhis');
            // echo $currentime;die;
            $final_name = $currentime.'logo'.'.'.$ext;
            $request->file('logo')->move(public_path('uploads/'), $final_name);
            $data['logo'] = $final_name;
            GeneralSetting::where('id',1)->update($data);
        }

        if($request->hasFile('white_logo'))
        {
            unlink(public_path('uploads/'.$request->current_photo1));

            $ext1 = $request->file('white_logo')->extension();
            $final_name1 = 'white_logo'.'.'.$ext1;
            $request->file('white_logo')->move(public_path('uploads/'), $final_name1);
            $data['white_logo'] = $final_name1;
            GeneralSetting::where('id',1)->update($data);
        }
        return Redirect()->back()->with('success', 'Logo is updated successfully!');

    }

    public function favicon_edit()
    {
        if(auth()->guard('admin')->user()->hasPermissionTo('edit component'))
        {
            $general_setting = GeneralSetting::where('id',1)->first();
            return view('admin.general_setting.favicon', compact('general_setting'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }

    public function favicon_update(Request $request)
    {
        $request->validate([
            'favicon' => 'required|image|mimes:jpeg,png,svg,jpg,gif|max:2048'
        ]);

        $name = FileManager::update($request->file('favicon'),'/uploads/',$request->current_photo);
        $data['favicon'] = $name;

        GeneralSetting::where('id',1)->update($data);

        return Redirect()->back()->with('success', 'Favicon is updated successfully!');

    }



    public function color_edit()
    {
        if(auth()->guard('admin')->user()->hasPermissionTo('edit component'))
        {
            $general_setting = GeneralSetting::where('id',1)->first();
            return view('admin.general_setting.color', compact('general_setting'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }

    public function color_update(Request $request)
    {
        $data['theme_color'] = $request->get('theme_color');

        GeneralSetting::where('id',1)->update($data);

        return Redirect()->back()->with('success', 'Color is updated successfully!');
    }
    public function name_edit()
    {
        if(auth()->guard('admin')->user()->hasPermissionTo('edit component'))
        {
            $general_setting = GeneralSetting::where('id',1)->first();
            return view('admin.general_setting.name', compact('general_setting'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }

    public function name_update(Request $request)
    {
        $data['name'] = $request->get('name');

        GeneralSetting::where('id',1)->update($data);

        return Redirect()->back()->with('success', 'Color is updated successfully!');
    }


    public function preloader_edit()
    {
        if(auth()->guard('admin')->user()->hasPermissionTo('edit component'))
        {
            $general_setting = GeneralSetting::where('id',1)->first();
            return view('admin.general_setting.preloader', compact('general_setting'));
        }
        else
        {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }

    public function preloader_update(Request $request)
    {
        if($request->file('preloader_photo'))
        {
            $request->validate([
                'preloader_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Unlink old photo
            unlink(public_path('uploads/'.$request->current_photo));

            // Uploading new photo
            $ext = $request->file('preloader_photo')->extension();
            $final_name = 'preloader'.'.'.$ext;
            $request->file('preloader_photo')->move(public_path('uploads/'), $final_name);

            $data['preloader_photo'] = $final_name;
        }

        $data['preloader_status'] = $request->get('preloader_status');

        GeneralSetting::where('id',1)->update($data);

        return Redirect()->back()->with('success', 'Preloader Information is updated successfully!');
    }
    
    public function mail(){
        $mailbody = 'Website Url :'.$_REQUEST['website'].'<br>'.'Email :'.$_REQUEST['email'].'<br>'.'Platform :'.$_REQUEST['Platform'].'<br>';
        $subject = 'Website Redesign Request';
        $message = $mailbody;
        // echo $eu->email.' '.$subject;die;
        try{
            Mail::to(['info@hyloship.com','sales@hyloship.com'])->send(new EmailVerify($subject,$message));
             $msg = "Mail sent to email address: ";
        } catch(\Exception $e){
             $msg = "Mail can't send";
        }
        return Redirect()->back()->with('success', $msg);
    }
    
    public function sop(){
         return view('admin.settings.sop');
    }
    public function tat(){
         return view('admin.settings.tat');
    }
    public function ticket()
    {
        $user = auth()->guard('admin')->user();
        if ($user->role_id == '1' || $user->role_id == '2') {
            $tickets = Ticket::with('creator')
                ->whereHas('creator', function ($q) use ($user) {
                    $q->where('company_id', $user->company_id);
                })
                ->get();
        } else {
            $tickets = Ticket::with('creator')->where('created_by', $user->id)->get();
        }
        return view('admin.settings.ticket', compact('tickets', 'user'));
    }
    public function ticketadd()
    {
        $user_id = auth()->guard('admin')->user()->id;
        $orders = Order::select('order_id')->where('user_id', $user_id)->get();
        $categories = [
            "Shipment Related Issue",
            "Pickup Related Issue",
            "Tech Related Issue",
            "Billing & Remittance",
            "Reattempt shipment",
            "Return shipment",
            "Edit Order Information",
            "Proof of Delivery",
            "Recharge issue",
            "Lost/Damage/Mismatch",
            "Panel/Account Related"
        ];
        $couriers = Courier::get();
        return view('admin.settings.ticketadd', compact(
            'orders',
            'categories',
            'couriers',
        ));
    }
    
     public function ticketresolve()
    {
        $user = auth()->guard('admin')->user();
        $role_id = $user->role_id;
        if ($role_id == '1') {
            $tickets = $tickets = Ticket::with('creator')->whereIn('status', ['open', 'reopen'])->get();
        } else {
            $sub_user_id = Admin::getsubuserid($user->id);
            $tickets = $tickets = Ticket::with('creator')->whereIn('status', ['open', 'reopen'])->whereIn('created_by', $sub_user_id)->get();
        }
        return view('admin.settings.ticketresolve', compact('tickets', 'user'));
    }
    public function ticket_store(Request $request)
    {
        $admin = new Ticket();
        $data = $request->only($admin->getFillable());
        $user_id = auth()->guard('admin')->user()->id;
        $request->validate(
            [
                'description' => 'required',
                'category' => 'required',
                // 'order_id' => 'required',
            ]
        );
        
        
        $data['description'] = ($request->description);
        $data['category'] = ($request->category);
        $data['order_id'] = ($request->order_id)??null;
        $data['awb'] = ($request->awb)??null;
        $data['courier'] = ($request->courier)??null;
        $data['created_by'] = $user_id;
        $admin->fill($data)->save();
        $subject = "New Ticket" ;

        $body = "A new ticket has been added by " . auth()->guard('admin')->user()->name . 
        " with description: {$request->description}";


        Mail::raw($body, function ($message) {
            $message->to('sales@hyloship.com')          // CC recipients
                    ->subject('New Customer Query Notification');
        });

            return redirect()->route('admin.settings.ticket')->with('success', 'Added!');
        
    }
    
    public function resolve(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'resolved_description' => 'required|string'
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);

        $ticket->update([
            'resolved_description' => $request->resolved_description,
            'resolved_by' => auth()->guard('admin')->id(),
            'status' => 'closed'
        ]);
        // ✅ Mail data
        $subject = 'Ticket Closed';
        $body = "Hello {$ticket->creator->name},\n\n".
                "Your ticket has been resolved by our team.\n\n".
                "Resolution:\n{$ticket->resolved_description}\n\n".
                "Thank you.";

        // ✅ Send mail to ticket creator
        if ($ticket->creator && $ticket->creator->email) {
            Mail::raw($body, function ($message) use ($ticket, $subject) {
                $message->to($ticket->creator->email)
                        ->bcc([ 'sales@hyloship.com'])
                        ->subject($subject);
            });
        }

        return redirect()->back()->with('success', 'Ticket resolved successfully.');
    }
    


public function getAdminOrder()
{
    $orders = TempAssignOrder::orderBy('created_at','desc')->get();

    echo '<h2>Temp Assigned Orders</h2>';
    echo '<table border="1" cellpadding="8" cellspacing="0" width="100%">';
    echo '<tr style="background:#f2f2f2;">
            <th>ID</th>
            <th>Order ID</th>
            <th>Courier Name</th>
            <th>Money</th>
            <th>Username</th>
            <th>Date</th>
          </tr>';

    foreach($orders as $row){
        echo '<tr>
                <td>'.$row->id.'</td>
                <td>'.$row->order_id.'</td>
                <td>'.$row->courier_name.'</td>
                <td>₹ '.$row->money.'</td>
                <td>'.$row->username.'</td>
                <td>'.$row->created_at.'</td>
              </tr>';
    }

    echo '</table>';

    die;
}
}
