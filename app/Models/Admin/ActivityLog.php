<?php
// app/Models/Admin/ActivityLog.php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'link_requested',
         'action',
         'action_type',
        'ip_address',
        'mac_address',
        'requested_data',
        'role_id',
        'action_description',
        'action_id',
        'geo_location',
        'user_agent',
        'request_method',
        'response_code',
        'response_time',
        'company_id',
       ];
}