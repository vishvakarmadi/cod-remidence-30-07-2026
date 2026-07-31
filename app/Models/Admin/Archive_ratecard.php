<?php

namespace App\Http\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive_ratecard extends Model
{
    use HasFactory;


    protected $fillable = [
        'courier_id', 'transport', 'weight', 'additional', 'within_city', 'within_state', 
        'metro_to_metro', 'rest_of_india', 'north_east', 'cod_charges', 'cod', 
        'user_id', 'uploaded_by'
    ];
}
