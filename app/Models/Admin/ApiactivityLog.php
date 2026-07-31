<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiactivityLog extends Model
{
    use HasFactory;

    protected $table = 'api_activity_logs'; 
    protected $fillable = [
        'user_id',
        'requested_data',
        'response_sent',
        'url',
        'request_type',
    ];
}
