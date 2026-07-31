<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    // Add all columns you want to be mass assignable
    protected $fillable = [
        'user_id',
        'order_id',
        'name',
        'code',
        'price',
        'discount',
        'qty',
        'discount_type',
        'tax_percent',
        'tax_amount',
        'total_price',
        'msds_file', // if you added MSDS handling
        'company_id'
    ];
}
