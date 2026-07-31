<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelSetting extends Model
{
    use HasFactory;

    protected $table = 'labalesetting';
    protected $guarded = [];

    protected $fillable = [
        'user_id',
        'company_id',
        'printer_type',
        'logo',
        'logo_hidden',
        'email',
        'mobile',
        'hide_pickup_address',
        'hide_pickup_mobile',
        'hide_rto_address',
        'hide_rto_mobile',
        'hide_gst_number',
        'hide_pickup_contact_name',
        'hide_rto_contact_name',
        'hide_pickup_name',
        'hide_rto_name',
        'hide_sku',
        'show_hsn',
        'hide_product',
        'hide_qty',
        'hide_total_amount',
        'hide_discount_amount',
        'show_gst',
        'hide_shipping_charges',
        'hide_order_amount',
        'hide_customer_mobile',
        'hide_customer_name',
        'hide_customer_address',
        'hide_order_barcode',
        'hide_invoice_number',
        'a4_print_option',
    ];

    protected $casts = [
        'logo_hidden'            => 'boolean',
        'hide_pickup_address'    => 'boolean',
        'hide_pickup_mobile'     => 'boolean',
        'hide_rto_address'       => 'boolean',
        'hide_rto_mobile'        => 'boolean',
        'hide_gst_number'        => 'boolean',
        'hide_pickup_contact_name' => 'boolean',
        'hide_rto_contact_name'  => 'boolean',
        'hide_pickup_name'       => 'boolean',
        'hide_rto_name'          => 'boolean',
        'hide_sku'               => 'boolean',
        'show_hsn'               => 'boolean',
        'hide_product'           => 'boolean',
        'hide_qty'               => 'boolean',
        'hide_total_amount'      => 'boolean',
        'hide_discount_amount'   => 'boolean',
        'show_gst'               => 'boolean',
        'hide_shipping_charges'  => 'boolean',
        'hide_order_amount'      => 'boolean',
        'hide_customer_mobile'   => 'boolean',
        'hide_customer_name'     => 'boolean',
        'hide_customer_address'  => 'boolean',
        'hide_order_barcode'     => 'boolean',
        'hide_invoice_number'    => 'boolean',
        'a4_print_option'        => 'integer',
    ];
}
