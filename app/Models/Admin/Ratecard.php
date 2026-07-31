<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Ratecard extends Model
{
    public function pincode(){
        return $this->hasMany(Pincode::class,'courier_id');
    }
}
