<?php


namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempAssignOrder extends Model
{
    use HasFactory;

    protected $table = 'temp_assign_order';

    protected $fillable = [
        'order_id',
        'username',
        'courier_name',
        'money',
    ];

//    public $timestamps = false; // Because you only have created_at
}