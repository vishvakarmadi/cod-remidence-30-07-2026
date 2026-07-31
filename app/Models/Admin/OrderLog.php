<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    //

    public function getOrdercodeAttribute($value)
    {
        $subuser = Ordercode::where('code',$value)->first();
        if($subuser !=''){
            return '<span class="ordercodeclss text-info">'.$subuser->name.'</span>';
        }else{
            return '<span class="ordercodeclss text-info">'.$value.'</span>';
        }
        
    }
}
