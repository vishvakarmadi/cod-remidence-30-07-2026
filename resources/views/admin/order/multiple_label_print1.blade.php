<?php
    require_once base_path('vendor/autoload.php');
    require_once base_path('vendor/picqer/php-barcode-generator/src/BarcodeBar.php');
    require_once base_path('vendor/picqer/php-barcode-generator/src/Barcode.php');
    require_once base_path('vendor/picqer/php-barcode-generator/src/Exceptions/BarcodeException.php');
    require_once base_path('vendor/picqer/php-barcode-generator/src/Exceptions/UnknownTypeException.php');
    require_once base_path('vendor/picqer/php-barcode-generator/src/Types/TypeInterface.php');
    require_once base_path('vendor/picqer/php-barcode-generator/src/BarcodeGenerator.php');
    require_once base_path('vendor/picqer/php-barcode-generator/src/Types/TypeCode128.php');
    require_once base_path('vendor/picqer/php-barcode-generator/src/BarcodeGeneratorSVG.php');

    $admin = auth()->guard('admin')->user();
        $ls = \App\Models\LabelSetting::where('user_id', $admin->id)->first();

    $general_setting = \DB::table('general_settings')->where('company_id', $admin->company_id)->first();
    $generator = new \Picqer\Barcode\BarcodeGeneratorSVG();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Labels - A4 Grid (2x2)</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        html, body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            background: #fff;
        }

        @media print {
            html, body { margin: 0; padding: 0; }
            @page { size: A4; margin: 0; }
            .no-print { display: none !important; }
        }

        /* ── SCREEN BUTTON ─────────────────────────────── */
        .no-print {
            padding: 10px;
            background: #f4f4f4;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        .no-print button {
            background: #3490dc;
            color: #fff;
            border: none;
            padding: 8px 18px;
            font-size: 13px;
            border-radius: 4px;
            cursor: pointer;
        }

        /* ── A4 PAGE: 2×2 GRID ─────────────────────────── */
        .a4-page {
            width: 210mm;
            height: 297mm;
            padding: 8mm;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 6mm;
            page-break-after: always;
            box-sizing: border-box;
        }

        /* ── SINGLE LABEL (mirrors print4x6 .label-4x6) ── */
        .label-item {
            border: 1px solid #000;
            padding: 6px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            background: #fff;
            box-sizing: border-box;
        }

        /* ── SECTIONS — same names as print4x6 ────────── */
        .bc-row { text-align: center; flex-shrink: 0; }
        .bc-row .courier-name { font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1px; }
        .bc-row .awb-text     { font-size: 9px;  font-weight: bold; letter-spacing: 1px; margin-bottom: 2px; color: #222; }
        .bc-row svg           { width: 100%; height: 36px; display: block; }

        hr { border: none; border-top: 1px solid #000; margin: 3px 0; flex-shrink: 0; }

        .hdr-row { display: flex; justify-content: space-between; align-items: flex-start; flex-shrink: 0; }
        .hdr-left { font-size: 8px; line-height: 1.5; }
        .hdr-right { text-align: right; line-height: 1.4; white-space: nowrap; }
        .hdr-right .payment-mode { font-weight: bold; font-size: 10px; }
        .hdr-right .amount       { font-weight: bold; font-size: 14px; }

        .address-box { border: 1.5px solid #000; padding: 4px 6px; font-size: 9px; line-height: 1.5; flex-shrink: 0; margin-top: 3px; }
        .label-tag   { font-size: 6.5px; font-weight: bold; text-transform: uppercase; color: #555; margin-bottom: 2px; }
        .name        { font-size: 11px; font-weight: bold; margin-bottom: 2px; }

        .dim-row     { display: flex; justify-content: flex-end; flex-shrink: 0; margin-top: 2px; }
        .weight-text { font-size: 7.5px; color: #333; }

        table.products { width: 100%; border-collapse: collapse; font-size: 8px; flex-shrink: 0; margin-top: 3px; }
        table.products th, table.products td { border: 1px solid #000; padding: 2px 4px; }
        table.products th { background: #e8e8e8; font-weight: bold; text-align: center; }

        .return-box          { border: 1px solid #000; padding: 3px 5px; font-size: 7px; line-height: 1.4; flex-shrink: 0; }
        .return-box .label-tag { font-size: 6px; }

        .footer-note { font-size: 7px; color: #444; border-top: 1px dashed #888; padding-top: 3px; text-align: center; flex-shrink: 0; margin-top: 3px; }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()">🖨️ Print Labels (A4 — 4 per page)</button>
        <span style="font-size:11px;color:#666;margin-left:10px;">Format: A4 · 4 Labels Per Page (2×2)</span>
    </div>

    @php $orderChunks = $orders->chunk(4); @endphp

    @foreach ($orderChunks as $chunk)
        <div class="a4-page">
            @foreach ($chunk as $order)
                <div class="label-item">

                    {{-- ── COURIER NAME + LOGO (same as print4x6) ──────── --}}
                    <div class="bc-row" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                        <div style="text-align:left;flex:1;">
                            <div class="courier-name">{{ $couriers[$order->ship_courier_id]['name'] ?? 'N/A' }}</div>
                            @if($order->tracking_info)
                                <div class="awb-text">{{ $order->tracking_info }}</div>
                            @endif
                        </div>

                       @php
    $logo_path = ($ls && $ls->logo) ? $ls->logo : (isset($general_setting->logo) ? $general_setting->logo : null);
    $show_logo = ($ls && $ls->logo_hidden == 0) && $logo_path;
@endphp

<div style="flex:0 0 auto;text-align:right;">
    @if($show_logo)
        <img src="{{ asset('public/uploads/' . $logo_path) }}" style="max-height:51px;max-width:80px;object-fit:contain;">
    @endif
</div>
                    </div>

                    {{-- ── AWB BARCODE ──────────────────────────────────── --}}
                    @if($order->tracking_info)
                        <div class="bc-row">
                            <?php echo $generator->getBarcode($order->tracking_info, $generator::TYPE_CODE_128, 1, 36); ?>
                        </div>
                    @endif

                    <hr>

                    {{-- ── PICKUP & RTO (Moved to Top Section) ────────────────────── --}}
                    <div style="display:flex;gap:4px;margin-bottom:4px;">
                        @if(!($ls && $ls->hide_pickup_address) && isset($order->warehouse) && !empty($order->warehouse))
                            @php $w = $order->warehouse; @endphp
                            <div class="return-box" style="flex:1; border: 1.5px solid #000; padding: 3px 5px; font-size: 7px;">
                                <div class="label-tag">Pickup Address</div>
                                @if(!($ls && $ls->hide_pickup_name))
                                    <b>{{ $w->name }}</b><br>
                                @endif
                                @if(!($ls && $ls->hide_pickup_contact_name) && isset($w->contact_person))
                                    <span>Attn: {{ $w->contact_person }}</span><br>
                                @endif
                                {{ $w->address }}
                                @if(!empty($w->city)), {{ $w->city }}@endif
                                @if(!empty($w->pincode)) – {{ $w->pincode }}@endif
                                @if($w->mobile && !($ls && $ls->hide_pickup_mobile))
                                    <br>Ph: {{ $w->mobile }}
                                @endif
                                @if(!($ls && $ls->hide_gst_number))
                                    @if(!empty($w->gst_no))
                                        <br><b>GSTIN:</b> {{ $w->gst_no }}
                                    @elseif($kyc_gst)
                                        <br><b>GSTIN:</b> {{ $kyc_gst }}
                                    @endif
                                @endif
                            </div>
                        @endif

                       @if(!($ls && $ls->hide_rto_address) && (isset($order->rto_warehouse) || isset($order->warehouse)))
                            @php $w = ($order->return_warehouse_id ? \App\Models\Admin\Warehouse::find($order->return_warehouse_id) : null) ?? $order->warehouse; @endphp
                            <div class="return-box" style="flex:1; border: 1.5px solid #000; padding: 3px 5px; font-size: 7px;">
                                <div class="label-tag">RTO Address</div>
                                @if(!($ls && $ls->hide_rto_name))
                                    <b>{{ $w->name }}</b><br>
                                @endif
                                @if(!($ls && $ls->hide_rto_contact_name) && isset($w->contact_person))
                                    <span>Attn: {{ $w->contact_person }}</span><br>
                                @endif
                                {{ $w->address }}
                                @if(!empty($w->city)), {{ $w->city }}@endif
                                @if(!empty($w->pincode)) – {{ $w->pincode }}@endif
                                @if($w->mobile && !($ls && $ls->hide_rto_mobile))
                                    <br>Ph: {{ $w->mobile }}
                                @endif
                            </div>
                        @endif
                    </div>

                    <hr>

                    {{-- ── ORDER INFO + COD AMOUNT (same as print4x6) ───── --}}
                    <div class="hdr-row">
                        <div class="hdr-left">
                            <div style="display:flex;align-items:center;gap:6px;">
                                <div>
                                    <b>Order #:</b> {{ $order->vendor_order_id }}<br>
                                    @if(!($ls && $ls->hide_invoice_number))
                                        <b>Invoice #:</b> {{ $order->invoice_no ?? $order->order_id }}<br>
                                    @endif
                                    <b>Date:</b> {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}<br>
                                    <b>Wt:</b> {{ number_format($order->weight / 1000, 3) }} kg
                                </div>
                                @if(!($ls && $ls->hide_order_barcode) && $order->vendor_order_id)
                                    <div style="margin-top:2px;">
                                        <?php echo $generator->getBarcode($order->vendor_order_id, $generator::TYPE_CODE_128, 1, 14); ?>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="hdr-right">
                            <div class="payment-mode">{!! strip_tags($order->payment_mode) !!}</div>
                           @if(!($ls && $ls->hide_order_amount))
                                @php
                                    $is_cod = (strtolower(strip_tags($order->payment_mode)) == 'cod' || strtolower(strip_tags($order->payment_mode)) == 'c.o.d' || strtolower(strip_tags($order->payment_mode)) == 'c.o.d.');
                                    $display_amount = ($is_cod && $order->custom_total > 0) ? $order->custom_total : $order->total;
                                @endphp
                                <div class="amount">
                                   @if($is_cod)
                                        <div style="font-size: 9px; font-weight: normal; color: #555; letter-spacing: 0.5px;">Collectable Amount:</div>
                                    @endif
                                    ₹{{ number_format($display_amount, 2) }}
                                </div>
                            @endif
                            @php
                                $kyc = \DB::table('profiles')->where('user_id', $order->getRawOriginal('user_id'))->first();
                                $kyc_gst = $kyc->gst ?? null;
                            @endphp
                            @if(!($ls && $ls->hide_gst_number) && $kyc_gst)
                                <div style="font-size:7px;margin-top:2px;">GSTIN: {{ $kyc_gst }}</div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    {{-- ── SHIPPING ADDRESS (same as print4x6) ─────────── --}}
                    <div class="address-box">
                        <div class="label-tag">Shipping Address</div>
                        @if(!($ls && $ls->hide_customer_name))
                            <div class="name">{{ $order->ship_fname }} {{ $order->ship_lname }}</div>
                        @endif

                        @if(!($ls && $ls->hide_customer_address))
                            {{ $order->ship_address }}
                            @if($order->ship_address_2)
                                , {{ $order->ship_address_2 }}
                            @endif
                            <br>
                            {{ $order->ship_city }}, {{ $order->ship_state }}<br>
                            PIN: {{ $order->ship_pincode }}
                        @endif

                        @if(!($ls && $ls->hide_customer_mobile) && $order->ship_phone)
                            <br>📞 {{ $order->ship_phone }}
                        @endif
                    </div>

                    {{-- ── DIMENSIONS (same as print4x6) ──────────────── --}}
                    <div class="dim-row">
                        <span class="weight-text">Dim: {{ $order->length }}×{{ $order->breadth }}×{{ $order->height }} cm</span>
                    </div>

                    {{-- ── PRODUCT TABLE (same structure as print4x6) ───── --}}
                    @if(!($ls && $ls->hide_product))
                        @php
                            $subtotal    = $order->detail->sum('total_price');
                            $total_gst   = $order->detail->sum('tax_amount');
                            $total_item_disc = $order->detail->sum(function($d) {
                                $d_type = trim(strtolower((string)$d->discount_type));
                                $val    = (float)$d->discount;
                                return ($d_type === 'p') ? ((float)$d->price * (float)$d->qty * $val) / 100 : $val;
                            });
                        @endphp
                        <table class="products">
                            <thead>
                                <tr>
                                    <th style="width:auto">Product / SKU</th>
                                    @if(!($ls && $ls->hide_qty))         <th style="width:28px">Qty</th>    @endif
                                    @if(!($ls && $ls->hide_discount_amount)) <th style="width:36px">Disc</th>    @endif
                                    @if($ls && $ls->show_hsn)            <th style="width:36px">HSN</th>    @endif
                                    @if($ls && $ls->show_gst)            <th style="width:36px">Tax</th>    @endif
                                    @if(!($ls && $ls->hide_total_amount)) <th style="width:50px">Total</th>  @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->detail as $d)
                                    @php
                                        $d_type = trim(strtolower((string)$d->discount_type));
                                        $d_val  = (float)$d->discount;
                                        $row_discount = ($d_type === 'p') ? ((float)$d->price * (float)$d->qty * $d_val) / 100 : $d_val;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $d->name }}
                                            @if(!($ls && $ls->hide_sku) && $d->code)
                                                <br><small style="color:#666">[{{ $d->code }}]</small>
                                            @endif
                                        </td>
                                        @if(!($ls && $ls->hide_qty))          <td style="text-align:center">{{ $d->qty }}</td>                                 @endif
                                        @if(!($ls && $ls->hide_discount_amount)) <td style="text-align:right">₹{{ number_format($row_discount, 2) }}</td> @endif
                                        @if($ls && $ls->show_hsn)             <td style="text-align:center">{{ $d->hsn ?? '-' }}</td>                         @endif
                                        @if($ls && $ls->show_gst)             <td style="text-align:right">₹{{ number_format($d->tax_amount, 2) }}</td>       @endif
                                        @if(!($ls && $ls->hide_total_amount)) <td style="text-align:right">₹{{ number_format($d->total_price, 2) }}</td>      @endif
                                    </tr>
                                @endforeach

                                {{-- ── Shipping / Discount / Grand Total rows (same as print4x6) --}}
                                @if(!($ls && $ls->hide_total_amount))
                                    @if(!($ls && $ls->hide_shipping_charges) && ($shipping_cost = (float) $order->shipping_cost))
                                        <tr>
                                            <td colspan="{{ 1 + (!($ls && $ls->hide_qty)?1:0) + (!($ls && $ls->hide_discount_amount)?1:0) + ($ls && $ls->show_hsn?1:0) + ($ls && $ls->show_gst?1:0) }}" style="text-align:right">Shipping</td>
                                            <td style="text-align:right">₹{{ number_format($shipping_cost, 2) }}</td>
                                        </tr>
                                    @endif

                                    @php
                                        $discount_final = (float)$order->discount + $total_item_disc;
                                    @endphp
                                    @if(!($ls && $ls->hide_discount_amount) && $discount_final > 0)
                                        <tr>
                                            <td colspan="{{ 1 + (!($ls && $ls->hide_qty)?1:0) + (!($ls && $ls->hide_discount_amount)?1:0) + ($ls && $ls->show_hsn?1:0) + ($ls && $ls->show_gst?1:0) }}" style="text-align:right">Total Discount</td>
                                            <td style="text-align:right">-₹{{ number_format($discount_final, 2) }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td colspan="{{ 1 + (!($ls && $ls->hide_qty)?1:0) + (!($ls && $ls->hide_discount_amount)?1:0) + ($ls && $ls->show_hsn?1:0) + ($ls && $ls->show_gst?1:0) }}" style="text-align:right"><b>Grand Total</b></td>
                                        <td style="text-align:right"><b>₹{{ number_format($order->total, 2) }}</b></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif

                    {{-- ── FOOTER (same as print4x6) ───────────────────── --}}
                    <div class="footer-note">
                        @php
                            $f_mobile = ($ls && $ls->mobile) ? $ls->mobile : ($general_setting->phone ?? null);
                            $f_email  = ($ls && $ls->email)  ? $ls->email  : ($general_setting->email ?? null);
                        @endphp
                        @if($f_mobile && !($ls && $ls->hide_pickup_mobile))
                            <b>Contact:</b> {{ $f_mobile }}
                            @if($f_email) | {{ $f_email }}@endif
                            <br>
                        @endif
                        Please unbox with video. No unboxing video = no liability for damage/missing items.
                        @if(isset($general_setting))
                            &nbsp;|&nbsp; Powered by <b>{{ $general_setting->name }}</b>
                        @endif
                    </div>

                </div>{{-- .label-item --}}
            @endforeach
        </div>{{-- .a4-page --}}
    @endforeach

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() { window.print(); }, 600);
        });
    </script>

</body>
</html>