<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerQuery extends Model
{
    use HasFactory;

    protected $table = 'customer_queries';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'query',
    ];
}
