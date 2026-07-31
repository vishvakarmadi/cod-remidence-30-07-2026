<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Available_invoice extends Model
{
      
    public static function getavailableid($invoice_type){
      $avinvoice =   Available_invoice::where('invoice_type',$invoice_type)->where('available','1')->first();
      if($avinvoice  !=''){
        $inv_id = $avinvoice->invoice_id;
        $avinvoice->available = '0';
        $avinvoice->save();
        return $inv_id;
      }else{
        return '0';
      }
    }
}
