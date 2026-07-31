<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Remittance extends Model
{
    //
    public function remittance_order()
    {
        return $this->hasMany(Order::class, 'remittance_id');
    }
   
}
