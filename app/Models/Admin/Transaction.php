<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;


class Transaction extends Model
{
   //
    
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
