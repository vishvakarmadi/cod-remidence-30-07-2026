<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitedCustomer extends Model
{
    use HasFactory;

    protected $table = 'visited_customers';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'ip_address',
    ];
}
