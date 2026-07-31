<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Warehouse;
use App\Models\Admin\Country;
use App\Models\Admin\Integration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function warehouse_index()
    {
        $user = Auth::guard('admin')->user();
        $current_company = $user->company_id;
        $user_id = $user->id;
        if($user_id == 1){
            $warehouse = Warehouse::where('deleted','0')->where('company_id',$current_company)->get();
            $country = Country::where('id','101')->get();
            return view('admin.warehouse.index', compact('warehouse','country'));
        }else{
            $warehouse = Warehouse::where('user_id',$user_id)->where('company_id',$current_company)->where('deleted','0')->get();
            // dd($warehouse);
            $country = Country::where('id','101')->get();
            return view('admin.warehouse.index', compact('warehouse','country'));
        }
    }




    public function warehouse_delete($id)
    {
        $user = Auth::guard('admin')->user();
        $current_company = $user->company_id;
        $user_id = $user->id;
        $warehouse = Warehouse::where('user_id',$user_id)->where('company_id',$current_company)->where('id',$id)->first();
        if($warehouse){
            $warehouse->deleted = '1';
            $warehouse->save();
            $message = "Warehouse Deleted Successfully...! ";
            createlogs('deleted','warehouse',$id);
            return redirect()->route('admin.warehouse.list')->with('success', $message);
        }
        else{
            return redirect()->back()->with('error',"You don't have permission to delete this");
        }
       
    }



    public function warehouse_save(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required',
            'contact_name' => 'required',
            'company' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'address_2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country_id' => 'required',
            'pincode' => 'required',
            // 'latitude' => 'required',
            // 'longitude' => 'required',
            'gst_no' => 'required',
            // 'fssai_licence' => 'required',
            // 'note' => 'required',

        ]);
        $user = Auth::guard('admin')->user();
        $current_company = $user->company_id;
        $user_id = $user->id;

        if ($id) {
                $warehouse = Warehouse::findOrFail($id);
                $orginalwarehouse = $warehouse->getOriginal();
                $message  = "Warehouse updated successfully";
            
        } else {
            $orginalwarehouse = array();
            $warehouse = new Warehouse();
            $warehouse->user_id = $user_id;
            $message  = "Warehouse added successfully";
        }
        $country = Country::find($request->country_id);
        $warehouse_array_air = json_encode(
            array(
                'phone'=>$request->phone,
                'city'=>$request->city,
                'name'=>$request->company.'a',
                'pin'=>$request->pincode,
                'address'=>$request->address.' '.$request->address_2,
                'country'=>$country['name'],
                'email'=>$request->email,
                'registered_name'=>$request->company,
                'return_address'=>$request->address.' '.$request->address_2,
                'return_pin'=>$request->pincode,
                'return_city'=>$request->city,
                'return_state'=>$request->state,
                'return_country'=>$country['name'],

            ),true
        );
        $warehouse_d =  Integration::create_warehouse($warehouse_array_air,'a');
        //$warehouse_d = '{"error_code": [2000], "data": {"business_hours": {"WED": {"start_time": "09:30", "close_time": "18:30"}, "THU": {"start_time": "09:30", "close_time": "18:30"}, "TUE": {"start_time": "09:30", "close_time": "18:30"}, "MON": {"start_time": "09:30", "close_time": "18:30"}, "FRI": {"start_time": "09:30", "close_time": "18:30"}, "SAT": {"start_time": "09:30", "close_time": "18:30"}}, "name": "HandloomWear", "pincode": "508114", "phone": "9505575909", "address": "8-105, vellanky, ramannapeta, bhongir 508113 8-105, vellanky, ramannapeta, bhongir 508113", "secondary_phone": null, "message": "some error while creating/updating warehouse", "other_phone": null, "business_days": ["MON", "TUE", "WED", "THU", "FRI", "SAT"]}, "success": false, "error": ["Transaction Failed: client-warehouse of client: cms::client::a24789e7-e50c-11ee-b193-022043a757bf with name: HandloomWear already exists CLIENT_STORES_CREATE"]}';
        //echo $warehouse_d;die;
        $dat_creted =json_decode($warehouse_d,true);
//        echo '<pre>';print_r($dat_creted);die;
        if(isset($dat_creted['success']) && $dat_creted['success']){
            
        }else{
            if(!isset($dat_creted['error'])){
                $dat_creted['error'] = array();
            }
            for($i=0;$i<count($dat_creted['error']);$i++){
                if(str_contains($dat_creted['error'][$i],'already exists')){
                    $warehouse_edit =  Integration::edit_warehouse($warehouse_array_air,'a');
                    $dat_edited =json_decode($warehouse_edit,true);
                }
            }
        }
        $warehouse_array_surface = json_encode(
            array(
                'phone'=>$request->phone,
                'city'=>$request->city,
                 'name'=>$request->company.'s',
                'pin'=>$request->pincode,
                'address'=>$request->address.' '.$request->address_2,
                'country'=>$country['name'],
                'email'=>$request->email,
                'registered_name'=>$request->company,
                'return_address'=>$request->address.' '.$request->address_2,
                'return_pin'=>$request->pincode,
                'return_city'=>$request->city,
                'return_state'=>$request->state,
                'return_country'=>$country['name'],

            ),true
        );
        $warehouse_d =  Integration::create_warehouse($warehouse_array_surface,'s');
        //$warehouse_d = '{"error_code": [2000], "data": {"business_hours": {"WED": {"start_time": "09:30", "close_time": "18:30"}, "THU": {"start_time": "09:30", "close_time": "18:30"}, "TUE": {"start_time": "09:30", "close_time": "18:30"}, "MON": {"start_time": "09:30", "close_time": "18:30"}, "FRI": {"start_time": "09:30", "close_time": "18:30"}, "SAT": {"start_time": "09:30", "close_time": "18:30"}}, "name": "HandloomWear", "pincode": "508114", "phone": "9505575909", "address": "8-105, vellanky, ramannapeta, bhongir 508113 8-105, vellanky, ramannapeta, bhongir 508113", "secondary_phone": null, "message": "some error while creating/updating warehouse", "other_phone": null, "business_days": ["MON", "TUE", "WED", "THU", "FRI", "SAT"]}, "success": false, "error": ["Transaction Failed: client-warehouse of client: cms::client::a24789e7-e50c-11ee-b193-022043a757bf with name: HandloomWear already exists CLIENT_STORES_CREATE"]}';
        //echo $warehouse_d;die;
        $dat_creted =json_decode($warehouse_d,true);
        
        if(isset($dat_creted['success']) && $dat_creted['success']){
            
        }else{
            if(!isset($dat_creted['error'])){
                $dat_creted['error'] = array();
            }
            for($i=0;$i<count($dat_creted['error']);$i++){
                if(str_contains($dat_creted['error'][$i],'already exists')){
                    $warehouse_edit =  Integration::edit_warehouse($warehouse_array_surface,'s');
                    $dat_edited =json_decode($warehouse_edit,true);
                }
            }
        }
        if($request->default == 1)
        {
            $default = 1;
        }
        else{
            $default = 0;
        }
        
        $warehouse->name = $request->name;
        $warehouse->contact_name = $request->contact_name;
        $warehouse->company = $request->company;
        $warehouse->email = $request->email;
        $warehouse->phone = $request->phone;
        $warehouse->address = $request->address;
        $warehouse->address_2 = $request->address_2;
        $warehouse->city = $request->city;
        $warehouse->state = $request->state;
        $warehouse->country_id = $request->country_id;
        $warehouse->pincode = $request->pincode;
        $warehouse->latitude = $request->latitude;
        $warehouse->longitude = $request->longitude;
        $warehouse->gst_no = $request->gst_no;
        $warehouse->fssai_licence = $request->fssai_licence;
        $warehouse->note = $request->note;
        $warehouse->default = $default;
        $warehouse->created_at = now();
        $warehouse->updated_at = now();
        $warehouse->company_id = $current_company;
        $warehouse->save();
        
         if ($id) {
                $changedFields = array_diff_assoc($warehouse->getAttributes(),$orginalwarehouse);
                unset($changedFields['created_at'], $changedFields['updated_at'], $changedFields['bd_id']);
                $oldValues = [];
                foreach ($changedFields as $key => $newValue) {
                    $oldValues[$key] = $orginalwarehouse[$key] ?? 'N/A';
                }

               createlogs('updated', 'warehouse', $id, $changedFields,$oldValues);
         }
        return redirect()->route('admin.warehouse.list')->with('success', $message);

    }


    public function location(Request $request, $id = 0){
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->latitude = $request->latitude;
        $warehouse->longitude = $request->longitude;
        $warehouse->save();
        return redirect()->route('admin.warehouse.list')->with('success', 'Location Updated Successfully!');
    }

}
