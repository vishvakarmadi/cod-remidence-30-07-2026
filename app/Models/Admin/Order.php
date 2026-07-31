<?php

namespace App\Models\Admin;
use App\Models\Customer;
use App\Models\Admin\Transaction;
use App\Models\Admin\WalletLog;
use DB;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function detail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
    
    public function lostpayment()
    {
        return $this->hasOne(Order_lost_payment::class, 'order_id');
    }
    

    public function shipCountry()
    {
        return $this->belongsTo(Country::class, 'ship_country');
    }

    public function billCountry()
    {
        return $this->belongsTo(Country::class, 'bill_country');
    }

    public function getStatusAttribute($value)
    {//change this on ordr model
        $statuses = [
            '1' => '<span class="badge text-white bg-secondary">New</span>',
            '2' => '<span class="badge text-white bg-dark">Shipped</span>',
            '3' => '<span class="badge text-white bg-success">Delivered</span>',
            '4' => '<span class="badge text-white bg-danger">Canceled</span>',
            '5' => '<span class="badge text-white bg-primary">RTO</span>',
            '6' => '<span class="badge text-white bg-primary">RTO Delivered</span>',
            '7' => '<span class="badge text-white bg-primary">On Hold</span>',
            // '8' => '<span class="badge text-white bg-warning">Fulfillment</span>',

            // '9' => '<span class="badge text-white bg-warning">Refund</span>',
            '10' => '<span class="badge text-white bg-warning">NDR</span>',
            // '11' => '<span class="badge text-white bg-dark">Courier Assigned</span>',
            '12' => '<span class="badge text-white bg-danger">Pickup Pending</span>',//manifested
            '13' => '<span class="badge text-white bg-success">RTO In Transit</span>',
            '14' => '<span class="badge text-white bg-success">In Transit</span>',
            '15' => '<span class="badge text-white bg-success">Out for Delivery</span>',
            '16' => '<span class="badge text-white bg-danger">Lost</span>',
            '17' => '<span class="badge text-white bg-danger">Damaged</span>',
            '18' => '<span class="badge text-white bg-danger">Destroyed</span>',    
        ];
        return $statuses[$value] ?? '';
    }

    public function getTotalAttemptAttribute($value)
    {
        $val = (int) $value;
        $rawStatus = strip_tags($this->attributes['status'] ?? '');
        if ($val == 0 && (in_array($rawStatus, ['3', 'Delivered']) || !empty($this->attributes['delivered_date']))) {
            return 1;
        }
        return $val;
    }

    public function getPaymentModeAttribute($value)
    {
        $modes = [
            '1' =>  '<span class="badge text-white" style="background:#3a87ad">Credit card</span>',
            '2' =>  '<span class="badge text-white" style="background:#3a87ad">Phone ordering</span>',
            '3' =>  '<span class="badge text-white" style="background:#3a87ad">Check</span>',
            '4' =>  '<span class="badge text-white" style="background:#3a87ad">Fax Ordering</span>',
            '5' =>  '<span class="badge text-white" style="background:#3a87ad">Money Order</span>',
            '6' =>  '<span class="badge text-white" style="background:#3a87ad">C.O.D</span>',
            '7' =>  '<span class="badge text-white" style="background:#3a87ad">Purchase Order</span>',
            '8' =>  '<span class="badge text-white" style="background:#3a87ad">Personal Check</span>',
            '9' =>  '<span class="badge text-white" style="background:#3a87ad">Business Check</span>',
            '10' =>  '<span class="badge text-white" style="background:#3a87ad">Government Check</span>',
            '11' =>  '<span class="badge text-white" style="background:#3a87ad">Travellers Check</span>',
            '12' =>  '<span class="badge text-white" style="background:#3a87ad">Pre-Paid</span>',
            '13' =>  '<span class="badge text-white" style="background:#3a87ad">Bank Transfer</span>',
            '14' =>  '<span class="badge text-white" style="background:#3a87ad">Paytm</span>',
            '15' =>  '<span class="badge text-white" style="background:#3a87ad">Gpay</span>',
            '16' =>  '<span class="badge text-white" style="background:#3a87ad">Reverse</span>',
        ];
        return $modes[$value] ?? '';
    }
    
    public static function getzone($origin,$destination){
//         echo $origin,$destination;die;
        $origin = ltrim(rtrim($origin));
        $destination = ltrim(rtrim($destination));
        $origin_data = Pincode::where(['pincode' => $origin])->first();
        $destination_data = Pincode::where(['pincode' => $destination])->first();
        $distance_url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$origin."&destinations=".$destination."&key=AIzaSyCwiCtnKcqvwdyMKTVV5Q8_HIq2YBppXOc";
        $distance ='';
//         echo $origin_data;die;
//         echo $distance_url;die;
        if($origin_data !=null &&  $destination_data != null){
            if($origin_data->special =='1' || $origin_data->north_east =='1' || $destination_data->special =='1' || $destination_data->north_east =='1'){
                return 'Z5';
            }else if($origin_data->metro =='1' && $destination_data->metro =='1' && ($destination_data->city != $origin_data->city)){
                if(strtolower($destination_data->state) =='delhi' && strtolower($origin_data->state) =='delhi'){
                    return 'Z2';
                }if($destination_data->state == $origin_data->state){
                    return 'Z2';
                }
                else{ 
                    return 'Z3';
                }
            }else{
                //to find distance start
                $distance_Data = Integration::hitgetcurl_distance($origin,$destination);
//                echo $distance_Data;die;
                $dd = json_decode($distance_Data,true);
                if($dd['status'] =='OK'){
                    if(!empty($dd['rows']) && isset($dd['rows'][0])){
                        if(isset($dd['rows'][0]['elements']) && isset($dd['rows'][0]['elements'][0])){
                            if(isset($dd['rows'][0]['elements'][0]['distance'])){
                                $distance = $dd['rows'][0]['elements'][0]['distance']['value'];
                            }
                        }
                    }
                }
                //to find distance end
//                echo $distance.'-->'.$destination_data->state .'-->'.$origin_data->state;die;
                if($distance !=''){
                    if( ($destination_data->pincode == $origin_data->pincode || strtolower($destination_data->city) == strtolower($origin_data->city)) && $distance <= 50000){
                        return 'Z1';
                    }elseif( (($destination_data->pincode == $origin_data->pincode || strtolower($destination_data->city) == strtolower($origin_data->city) ) && $distance > 50000) 
                         || (strtolower($destination_data->state) == strtolower($origin_data->state) && $distance <= 700000)
                         ){
                        return 'Z2';
                    }
                }
            }
         return 'Z4';  
        }else{ return '0';}
        
    }

    /**
     * Get B2B Zone (N1, N2, E, NE, W1, W2, S1, S2, Central) for a pincode.
     * Used for Delhivery B2B LTL freight rate matrix lookup.
     */
    public static function getB2bZone($pincode)
    {
        $pincode = ltrim(rtrim($pincode));
        $pinData = Pincode::where('pincode', $pincode)->first();
        if (!$pinData) {
            return null;
        }

        $state = strtolower(trim($pinData->state ?? ''));

        // North-East states → NE
        $ne_states = [
            'arunachal pradesh', 'assam', 'manipur', 'meghalaya',
            'mizoram', 'nagaland', 'sikkim', 'tripura'
        ];
        // North 1 (core north): Delhi, Haryana, Punjab, HP, J&K, Ladakh, Chandigarh, Uttarakhand
        $n1_states = [
            'delhi', 'haryana', 'punjab', 'himachal pradesh',
            'jammu and kashmir', 'jammu & kashmir', 'ladakh',
            'chandigarh', 'uttarakhand', 'uttar pradesh'
        ];
        // North 2 (extended north): Rajasthan, Bihar, Jharkhand
        $n2_states = [
            'rajasthan', 'bihar', 'jharkhand'
        ];
        // East: West Bengal, Odisha
        $e_states = [
            'west bengal', 'odisha', 'orissa'
        ];
        // West 1 (core west): Maharashtra, Goa, Dadra and Nagar Haveli, Daman and Diu
        $w1_states = [
            'maharashtra', 'goa', 'dadra and nagar haveli', 'dadra & nagar haveli',
            'daman and diu', 'daman & diu'
        ];
        // West 2: Gujarat
        $w2_states = ['gujarat'];
        // South 1: Karnataka, Kerala, Lakshadweep, Puducherry, Andaman and Nicobar
        $s1_states = [
            'karnataka', 'kerala', 'lakshadweep', 'puducherry', 'pondicherry',
            'andaman and nicobar', 'andaman & nicobar islands'
        ];
        // South 2: Tamil Nadu, Andhra Pradesh, Telangana
        $s2_states = [
            'tamil nadu', 'andhra pradesh', 'telangana'
        ];
        // Central: MP, Chhattisgarh
        $central_states = [
            'madhya pradesh', 'chhattisgarh'
        ];

        if (in_array($state, $ne_states))      return 'NE';
        if (in_array($state, $n1_states))      return 'N1';
        if (in_array($state, $n2_states))      return 'N2';
        if (in_array($state, $e_states))       return 'E';
        if (in_array($state, $w1_states))      return 'W1';
        if (in_array($state, $w2_states))      return 'W2';
        if (in_array($state, $s1_states))      return 'S1';
        if (in_array($state, $s2_states))      return 'S2';
        if (in_array($state, $central_states)) return 'Central';

        // Fallback: try pincode zone column if set
        if (!empty($pinData->zone)) {
            return $pinData->zone;
        }

        return null; // unknown
    }

    public function getUseridAttribute($value)
    {
        $subuser = Admin::where('id', $value)->first();

            if ($subuser) {
                return $subuser->name;
            }

            return '';
        
    }
    public function getExtraWeightStatusAttribute($value)
    {
        $weights = [
            '0' =>  '',// no extra weight, sb sahi hai 
            '1' =>  '<span class="badge text-white bg-info">New</span>',//extra weight cost calculation is done, but paise katne baki hai
            '2' =>  '<span class="badge text-white bg-success">Closed in Seller favor</span>',//closed with money debit
            '3' =>  '<span class="badge text-white bg-danger">Auto Accepted</span>',//paise cut gye seller k after 7 days of waiting time
            '4' =>  '<span class="badge text-white bg-success">Closed in Client favor</span>',//closed without money debit
            '5' =>  '<span class="badge text-white bg-primary">Open</span>',//extra weight added but no cost calculation is done till now
        ];
        return $weights[$value] ?? '';
    }

    public function reconciliation()
    {
        return $this->hasMany(WeightReconciliation::class, 'order_id');
    }
     public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }


    public static function getorderidusingsku($sku){
        $orders =DB::table('orders')
        ->select('orders.id')
        ->join('order_details','order_details.order_id','=','orders.id')
        ->where('order_details.code','=','inds')
        ->get();
        $o_id = array();
        foreach($orders as $order){
            $o_id[]=$order->id;
        }
        return $o_id;
    }
    
    public function orderlog()
    {
        return $this->hasMany(OrderLog::class, 'order_id');
    }
    public static function getorderidusingndr($c_id){
        $ordersdetails =DB::table('order_logs') // Specifies the table to query
        ->select('order_id') // Selects the 'order_id' column
        ->where(function ($query) { // Starts a subquery with 'orWhere' clauses
            $query->where('order_logs.new_value','=','NDR') // Checks if 'new_value' equals 'NDR'
                ->orWhere('order_logs.old_value','=','NDR'); // Checks if 'old_value' equals 'NDR'
        })
        ->where('company_id',$c_id)
        ->get(); // Executes the query and retrieves the results
        $o_id = array();
        $orders = order::where('status', '10')->where('company_id',$c_id)->get();
        foreach($ordersdetails as $order){
            $o_id[]=$order->order_id;
        }
        foreach($orders as $order){
            $o_id[]=$order->id;
        }
        return array_values(array_unique($o_id));
    }
    
    public static function getorderidusingndrsuingcron(){
        $ordersdetails = DB::table('order_logs')
        ->select('order_logs.order_id')
        ->where(function ($query) {
            $query->where('order_logs.new_value','=','NDR')
                ->orWhere('order_logs.old_value','=','NDR');
        })
        ->join('orders', 'orders.id', '=', 'order_logs.order_id')
        ->whereNotIn('orders.status', ['3', '6', '4']) // Exclude Delivered, RTO Delivered, Canceled
        ->where(function ($query) {
            $query->where('orders.attempts_cron', '0')
                  ->orWhere('orders.status', '10');
        })
        ->get();
        $o_id = array();
        $orders = Order::where('status', '10')->whereNotIn('status', ['3', '6', '4'])->get();
        foreach($ordersdetails as $order){
                $o_id[]=$order->order_id;
        }
        foreach($orders as $order){
            $o_id[]=$order->id;
        }
        return array_values(array_unique($o_id));
    }
    
    public static function getrtodate($order_id){
        $tr = Transaction::whereIn('transactions.remarks',array('Amount Debit for RTO','Amount Debit for extra weight - RTO'))->where('order_id',$order_id)->first();
        if($tr){
            return \Carbon\Carbon::parse($tr->created_at)->format('d M, Y') ;
        }else{
            return '';
        }
    }
    
    public static function getwarehousedata($ware_id) {
        return Warehouse::find($ware_id);
    }
    
    public static function getintransitdetails($user_id){
        $intrsit['total'] =0;
        $intrsit['count'] =0;
        $ordersintrsit = order::where('status','14')->where('payment_mode',6)->where('user_id',$user_id)->get();
        if($ordersintrsit){
            $intrsit['total'] = $ordersintrsit->sum('total');
            $intrsit['count'] = $ordersintrsit->count();
        }
        $intrsit['current_wallet'] = admin::find($user_id)->wallet_blc;
        return $intrsit;
    }
    
    public static function chkdublictae($order_id){
        $ordermatch = order::where('id',$order_id)->select('user_id as idu','ship_fname','ship_phone','ship_lname','ship_address','total')->first();
        $name = $ordermatch->ship_fname.''.$ordermatch->ship_lname;
        $name = str_replace(" ","",$name);
        $orderdata = Order::where('id','!=', $order_id)
        ->where("ship_fname", $ordermatch->ship_fname)
        ->where("ship_lname", $ordermatch->ship_lname)
        ->where("ship_phone", $ordermatch->ship_phone)
        ->where("user_id", $ordermatch->idu)
        // ->where("ship_address", $ordermatch->ship_address)
        ->where("total", $ordermatch->total)
        ->whereIn("status", array('1'))
        ->first();
        if($orderdata){
            return 'yes';
        }else{
            return  'no';
        }
    }
    
    public static function getwalleton($date,$user_id){
        $wallet ='';
        $ordersintrsit = WalletLog::where('user_id',$user_id)->where('created_at','like',$date. ' 23:59%')->first();
        if($ordersintrsit){
            $wallet= $ordersintrsit->wallet;
        }
        return $wallet;
    }
    
    public static function getStatusdata($value)
    {//change this on ordr model
        $statuses = [
            '1' => '<span class="badge text-white bg-secondary">New</span>',
            '2' => '<span class="badge text-white bg-dark">Shipped</span>',
            '3' => '<span class="badge text-white bg-success">Delivered</span>',
            '4' => '<span class="badge text-white bg-danger">Canceled</span>',
            '5' => '<span class="badge text-white bg-primary">RTO</span>',
            '6' => '<span class="badge text-white bg-primary">RTO Delivered</span>',
            '7' => '<span class="badge text-white bg-primary">On Hold</span>',
            // '8' => '<span class="badge text-white bg-warning">Fulfillment</span>',

            // '9' => '<span class="badge text-white bg-warning">Refund</span>',
            '10' => '<span class="badge text-white bg-warning">NDR</span>',
            // '11' => '<span class="badge text-white bg-dark">Courier Assigned</span>',
            '12' => '<span class="badge text-white bg-danger">Pickup Pending</span>',//manifested
            '13' => '<span class="badge text-white bg-success">RTO In Transit</span>',
            '14' => '<span class="badge text-white bg-success">In Transit</span>',
            '15' => '<span class="badge text-white bg-success">Out for Delivery</span>',
            '16' => '<span class="badge text-white bg-danger">Lost</span>',
            '17' => '<span class="badge text-white bg-danger">Damaged</span>',
            '18' => '<span class="badge text-white bg-danger">Destroyed</span>',
        ];
        return $statuses[$value] ?? '';
    }
}
