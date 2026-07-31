<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    protected $table = 'api_logs'; 
    protected $fillable = [
        'user_id',
        'company_id',
        'log_type',
        'request',
        'response',
        'order_id',
        'courier_id',
    ];
}
