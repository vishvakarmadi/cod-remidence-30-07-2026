<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $fillable = [
        'Pincode','City','State','COD_Delivery','Prepaid_Delivery','Pickup','Zone','company_id'
    ];

}
