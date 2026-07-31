<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'description',
        'resolved_description',
        'created_by',
        'resolved_by','status','category','order_id','awb','courier'
    ];
    
    public function creator()
{
    return $this->belongsTo(Admin::class, 'created_by');
}
public function resolver()
{
    return $this->belongsTo(Admin::class, 'resolved_by');
}
}
