<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'requested_by',
        'action_by',
        'credit_amount',
        'credit_describe',
        'company_id',
        'status'
    ];
}
