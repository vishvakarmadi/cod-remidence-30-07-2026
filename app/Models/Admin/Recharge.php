<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'wallet_recharge';

    // Define the fillable attributes
    protected $fillable = [
        'user_id',
        'payment_id',
        'payment_amount',
        'coupon_amount',
        'coupon_code',
        'company_id',
        'recharge_done_by',
    ];
}
