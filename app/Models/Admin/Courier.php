<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    //
    public function ratecards()
    {
        return $this->hasMany(Ratecard::class,'courier_id');
    }
}
