<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Shipping;
use App\Models\Admin\Courier;
use App\Models\Admin\Order;
use App\Models\Admin\Admin;
use App\Models\Admin\Pincode;
use App\Models\Admin\Ratecard;
use App\Models\Admin\Integration;
use App\Models\Admin\TermCondition;
use App\Models\Admin\WareHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Admin\Archive_ratecard;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use DOMDocument;
use DB;

class RateController extends Controller
{
    public function rate(Request $request)
    {
        $user = auth()->guard('admin')->user();
        $rate = Ratecard::where('user_id',$user->id)
                    ->where('company_id',$user->company_id)
                    ->where('courier_id','!=','1')
                    ->where('courier_id','!=','6')
                    ->where('transport','!=','Reverse')
                     ->where('status',1)
                    ->orderBy('courier_id')
                    ->orderBy('transport')
                    ->orderBy('weight')
                    ->orderBy('additional')
                    ->get();
                    // echo '<pre>';print_R(($rate));die;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $terms = TermCondition::where('company_id',$user->company_id)->get();
        if($user->role_id =='1'){
            $users = Admin::where('delete_status',0)->where('company_id',$user->company_id)->get();
        }elseif($user->role_id =='2'){
            $sub_user_id = Admin::getsubuserid($user->id);
            $users = Admin::where('delete_status',0)
            ->whereIn('id',$sub_user_id)
            ->get(); 
        }else{
            $users = Admin::where('delete_status',0)
            ->where('id',$user->id)
            ->get(); 
        }
        return view('admin.rate.rate',compact('rate','couriers','terms','users'));
    }





    public function calculate(Request $request){
        // echo 'hi';die;
        $validate = Validator::make($request->all(), [
            'pickup_pin' => 'required|numeric',
            'drop_pin' => 'required|numeric',
            'length' => 'required|numeric',
            'breadth' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'payment' => 'required',
            'value' =>  request('payment') == 'cod' ? 'required|numeric' : '',
            'shipment_type' => 'required',
        ])->validate();
        $user_id = auth()->guard('admin')->user()->id;
        $rateuser = Ratecard::where(['status' => 1,'user_id' => auth()->guard('admin')->user()->id])->first();
        if($rateuser == null){
            return [
                'status' => 0,
                'message' => 'Ratecard not found'
            ];
        }
        
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
       
        $cour_id =array(2,7,10,4,8,11,12);
        $zone_id = Order::getzone(request('pickup_pin'),request('drop_pin'));
        // echo $zone_id;die;
        for($i=0;$i<count($cour_id);$i++){
            $pincodes[] = array(
                "courier_id" => $cour_id[$i],  
                "zone" => $zone_id,
            ); 
        }

        $get = [];
        $payment = request('payment');
        foreach($pincodes as $row){
            $zone = zone($row['zone']);
            $c_id = $row['courier_id'];
            // $count = intVal(($order->weight/1000) / 0.5) - 1; //to convert weight into kg
            foreach(['Air','Surface'] as $transport){
                foreach(['0.5','1','1.5','2','3','3.5','5','10','20','30','50'] as $c_weigt){
                    // echo $c_id.'-->'.$transport.'-->'.$user_id.'-->'.$c_weigt;die;
                    $rate = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $user_id,'weight' =>$c_weigt,'additional' =>0])->first();
                    if($c_weigt=='0.5'){
                        $wadd = '0.5';
                    }else{
                        $wadd = '1';
                    }
                    $rateadd = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $user_id,'weight' =>$wadd,'additional' =>1])->first();
                    
                    // $add = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport, 'weight' => 'add','status' => 1,'user_id' => $user_id])->first();
                    
                    $vol_weight = (request('length')*request('breadth')*request('height'))/vol_weigh($c_id);
                    $weight_to_be_taken = $vol_weight > (request('weight')) ? $vol_weight : (request('weight'));
//                     echo $vol_weight.'-->'.vol_weigh($c_id);die;
                    if($rate == null){
                        //  return [
                        //     'status' => 0,
                        //     'message' => 'Rate card not found for this user'
                        // ];
                        continue;
                    }
                    $percent = ((request('value') * $rate->cod) / 100); //4339
                    $cod = $payment == 'cod' ? $percent > $rate->cod_charges ? $percent : $rate->cod_charges : 0; //0
                    $remainging_weight = $weight_to_be_taken - $c_weigt;
                    $freight = $rate->$zone;
                    
                    if($remainging_weight<0){
                        $remainging_weight =0;
                    }
                    $count =$remainging_charge=0;
                    if($rateadd == null){

                    }else{
                        $count = ceil($remainging_weight/$rateadd->weight);
                        $remainging_charge = $count * $rateadd->$zone;
                    }
                    
                    // echo $remainging_weight.'-->'.$count.'-->'.$remainging_charge.'--'.$rate->$zone.'--'.$zone.'--'.$freight;die;
                    // $freight = $rate->$zone * $count;
                    // $freight = ($rate->$zone) + (@$add->$zone * $count); // 45
                    $freight = $freight + $remainging_charge;
                    if(request('shipment_type') == 'reverse'){
                        $ratereverse = Ratecard::where(['courier_id' => $c_id, 'transport' => 'Reverse','status' => 1,'user_id' => $user_id])->first();
                        $freight = $freight + @$ratereverse->$zone;
                    }
                    $gst = (($freight + $cod) * 18) / 100;
                    if($payment == 'cod'){
                        $total = $gst + $freight + $cod;
                    } else {
                        $total = $gst + $freight;
                    }
                   
                    // echo $freight.'-->'.$cod.'-->'.$gst.'<br>';
                    // $total = ($total * 118)/100; // 18% gst
                    $withoutgsttotal = $total - $gst;
                    $get[] = [
                        'courier_id' => $rate->courier_id,
                        'name' => $couriers[$rate->courier_id]['name'],
                        'img' => url('public/courier').'/'.$couriers[$rate->courier_id]['image'],
                        'mode' => $transport == 'Air' ? 'fa-plane' : 'fa-truck',
                        'weight_used' => $c_weigt,
                        'weight' => round($weight_to_be_taken,2).' kg',
                        'price' => 'Rs.'.number_format($total,2),
                        'zone' => $zone,

                        'courier' => $couriers[$rate->courier_id]['name'] .' '. $rate->transport . ' ('.$c_weigt.' KG)',
                        'freight_charge' => 'Rs '.number_format($freight,2),
                        'cod' => 'Rs '.number_format($cod,2),
                        'gst' => 'Rs '.number_format($gst,2),
                        'total' => 'Rs '.number_format($total,2),
                        'withoutgsttotal' => 'Rs '.number_format($withoutgsttotal,2),
                    ];
                    
                }    
            }
        }
    //    die;
        if(count($get) == 0){
            return [
                'status' => 0,
                'message' => 'Pincode is not Servicable!'
            ];
        }
        return [
            'status' => 1, 
            'message' => 'Success',
            'data' => $get,
            'request' => $request->all(),
        ];
           
    }
    
    public function rateedit($id)
    {
        $rate = Ratecard::findOrFail($id);
        $couriers = Courier::get();

        return view('admin.rate.rateedit',compact('rate','couriers'));
    }

    public function rateupdate(Request $request,$id){
        // dd($request);
        $rate = Ratecard::findOrFail($id);
        $rate->courier_id = $request->courier_id;
        $rate->within_city = $request->within_city;
        $rate->within_state = $request->within_state;
        $rate->metro_to_metro = $request->metro_to_metro;
        $rate->rest_of_india = $request->rest_of_india;
        $rate->north_east = $request->north_east;
        $rate->cod_charges = $request->cod_charges;
        $rate->cod = $request->cod;
        $rate->save();
        return redirect()->route('admin.rate')->with('success',"Rate Updated Successfully");
    }

    public function ratetermedit($id)
    {
        $term = TermCondition::findOrFail($id);

        return view('admin.rate.ratetermedit',compact('term'));
    }
    
    public function ratetermupdate(Request $request,$id){
        $term = TermCondition::findOrFail($id);
        $term->terms = $request->terms;
        $term->conditions = $request->conditions;
        $term->save();
        return redirect()->route('admin.rate')->with('success',"Terms & Condition Updated Successfully");
    }
  
    

    public function bulkratestore(Request $request)
    {
        $user = auth()->guard('admin')->user();
        try {
            $collections = (new FastExcel)->import($request->file('excel'));
        } catch (\Exception $exception) {
            return back()->with('error','You have uploaded a wrong format file, please upload the right file.');
        }
        if($user->role_id ==1 || $user->role_id ==2){
         $existingRatecards = Ratecard::where('user_id', request()->user_id)->where('company_id',$user->company_id)->get();
            foreach ($existingRatecards as $ratecard) {
                try {
                    $ratecardArray = $ratecard->toArray(); // Convert to array
                    DB::table('archive_ratecards')->insert($ratecardArray);
                } catch (\Exception $e) {
                    \Log::error('Error saving to archive_ratecards: ' . $e->getMessage());
              }
            }    
        $rate = Ratecard::where('user_id', request()->user_id)->where('company_id',$user->company_id)->delete();
        $c_data['Ecom Express'] = 1;
        $c_data['Delhivery'] = 2;
        $c_data['Bluedart'] = 3;
        $c_data['XpressBees'] = 4;
        $c_data['DTDC'] = 5;
        $c_data['Smartr'] = 6;
        $c_data['Ekart'] = 7;
        $c_data['Shadowfax'] = 8;
        $c_data['ATS'] = 9;
        $c_data['Blitz'] = 10;
        $c_data['Shree Maruti'] = 11;
        $c_data['PicknDel'] = 12;
        $c_data['PiknDel'] = 12;
        $c_data['Parcel Uncle'] = 13;
        $c_data['India Post'] = 14;
        foreach ($collections as $row) {
            // echo '<pre>';print_R($row);
            if(isset($c_data[trim($row['courier_id'])])){
                
                $ratecard = new Ratecard();
                $ratecard->courier_id = $c_data[$row['courier_id']];
                if($row['transport'] ==''){
                    $ratecard->transport  = 'Surface';
                }else{
                    $ratecard->transport  = $row['transport'];
                }
                $ratecard->weight = $row['weight'];
                $ratecard->additional = $row['additional'];
                $ratecard->within_city = $row['within_city'];
                $ratecard->within_state = $row['within_state'];
                $ratecard->metro_to_metro = $row['metro_to_metro'];
                $ratecard->rest_of_india = $row['rest_of_india'];
                $ratecard->north_east = $row['north_east'];
                $ratecard->cod_charges = $row['cod_charges'] ?? 0;
                $ratecard->cod = $row['cod'] ?? 0;
                $ratecard->user_id = request()->user_id;
                $ratecard->company_id = $user->company_id;
                $ratecard->uploaded_by = $user->id;
//                 echo $ratecard;die;
                $ratecard->save();
            }
            // echo '<pre>';print_R($ratecard->id);die;

        }
// die('done');
        createlogs('updated', 'rate', $request->user_id);
            return redirect()->route('admin.rate')->with('success','Rate Imported Successfully');
        }else{
            return redirect()->back()->with('error',"You don't have permission to access this");
        }
    }
   
    public function pincreate(){
        $get = Integration::whereUserId(auth()->guard('admin')->user()->id)->get();
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);

        return view('admin.rate.pincode',compact('get','couriers'));
    }

    public function pinstore(Request $request)
    {
        try {
            $collections = (new FastExcel)->import($request->file('excel'));
        } catch (\Exception $exception) {
            return back()->with('error','You have uploaded a wrong format file, please upload the right file.');
        }

        $pincode = Pincode::where(['user_id' => auth()->guard('admin')->user()->id,'courier_id' => request('courier_id')])->delete();
        foreach ($collections as $row) {
            $pin = new Pincode();
            $pin->user_id = auth()->guard('admin')->user()->id;
            $pin->courier_id = request('courier_id');
            $pin->pincode = $row['PINCODE'];
            $pin->city = $row['CITY'];
            $pin->state = $row['STATE'];
            $pin->cod = $row['COD'];
            $pin->prepaid = $row['PREPAID'];
            $pin->pickup = $row['PICKUP'];
            $pin->zone = $row['ZONE'];
            $pin->updated_at = now();
            $pin->save();
        }

        return redirect()->route('admin.pin.create')->with('success','Pincode Uploaded Successfully');
    }

    public function bulkrateforsingleuser(Request $request)
    {
        try {
            $collections = (new FastExcel)->import($request->file('excel'));
        } catch (\Exception $exception) {
            return back()->with('error','You have uploaded a wrong format file, please upload the right file.');
        }
        if(auth()->guard('admin')->user()->id ==1){
        $rates = Ratecard::where('user_id',$request->id)->delete();
        $user_id = Auth::guard('admin')->user()->id;
        foreach ($collections as $row) {
            // $newrate = Ratecard::create([
            // 'user_id' => $request->id,
            // 'courier_id' => $row['courier_id'],
            // 'transport' => $row['transport'],
            // 'weight' => $row['weight'],
            // 'within_city' => $row['within_city'],
            // 'within_state' => $row['within_state'],
            // 'metro_to_metro' => $row['metro_to_metro'],
            // 'rest_of_india' => $row['rest_of_india'],
            // 'north_east' => $row['north_east'],
            // 'cod_charges' => $row['cod_charges'],
            // 'cod' => $row['cod'],
            //  ]);
            $ratecard = new Ratecard();
            $ratecard->courier_id = $row['courier_id'];
            $ratecard->transport = $row['transport'];
            $ratecard->weight = $row['weight'];
            $ratecard->within_city = $row['within_city'];
            $ratecard->within_state = $row['within_state'];
            $ratecard->metro_to_metro = $row['metro_to_metro'];
            $ratecard->rest_of_india = $row['rest_of_india'];
            $ratecard->north_east = $row['north_east'];
            $ratecard->cod_charges = $row['cod_charges'];
            $ratecard->cod = $row['cod'];
            $ratecard->user_id = $request->id;
            $ratecard->uploaded_by = auth()->guard('admin')->user()->id;
            $ratecard->save();
            // $ratecard->user_id
    }
        if(isset($ratecard) && $ratecard->user_id != 0){
            $admin = Admin::where('id',$request->id)->first();
            $admin->ratecard = 1;
            $admin->save();
        }
        return redirect()->route('admin.role.user')->with('success','Rate Imported Successfully');
        }else{
            return redirect()->back()->with('error',"You don't have permission to access this");
        }
    }
    
    public function getrate($user_id){
//        echo $user_id;die;
        $rate = Ratecard::where('user_id',$user_id)
        ->orderBy('courier_id')
        ->orderBy('transport')
        ->orderBy('weight')
        ->orderBy('additional')
        ->get();
        // echo '<pre>';print_R(($rate));die;
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        return view('admin.rate.getrate',compact('rate','couriers'));
    }

}
    