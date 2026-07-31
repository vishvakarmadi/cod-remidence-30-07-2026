<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';  // Specify the table name if it's different

    protected $fillable = ['name', 'company_code'];  // Define fillable fields if needed
}
