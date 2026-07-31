<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'bank_name',
        'beneficiary_name',
        'account_no',
        'ifsc_code',
        'account_type',
        'country',
        'state',
        'city',
        'address',
        'billing_address',
        'billing_same_personal_address',
        'company_type',
        'company_type_name',
        'zip_code',
        'aadhaar_no',
        'aadhaar',
        'doc_type',
        'doc_proof',
        'pan_no',
        'pan',
        'gst',
        'bank_name_other',
        'cheque',
    ];
}
