<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;


class Invoice extends Model
{
    
    public function detail()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }
    
    
   public function getUseridAttribute($value)
    {
        $subuser = Admin::where('id',$value)->get();
        if(!empty($subuser)){
            return @$subuser[0]->name;
        }else{
            return '';
        }
        
    }
}
