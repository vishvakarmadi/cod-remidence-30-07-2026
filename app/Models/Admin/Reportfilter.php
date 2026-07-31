<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Reportfilter extends Model
{
    //
    public function getUseridAttribute($value)
    {
        $subuser = Admin::where('id',$value)->get();
        if(!empty($subuser)){
            return $subuser[0]->name;
        }else{
            return '';
        }
        
    }
}
