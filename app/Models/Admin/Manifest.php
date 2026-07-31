<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Manifest extends Model
{
    //
    public function manifest_order()
    {
        return $this->hasMany(Order::class, 'manifest_id');
    }
    
    public function manifest_porder()
    {
        return $this->hasMany(Order::class, 'manifestprod_id');
    }
    
    public function getCreatedbyAttribute($value)
    {
        $subuser = Admin::where('id',$value)->get();
        if(!empty($subuser)){
            return $subuser[0]->name;
        }else{
            return '';
        }
        
    }
}
