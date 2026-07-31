<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'contact_name',
        'company',
        'email',
        'phone',
        'address',
        'address_2',
        'city',
        'state',
        'country_id',
        'pincode',
        'latitude',
        'longitude',
        'gst_no',
        'fssai_licence',
        'note',
        'default'


    ];


    public function country()
    {
        return $this->belongsTo(country::class, 'country_id', 'id');
    }

}
