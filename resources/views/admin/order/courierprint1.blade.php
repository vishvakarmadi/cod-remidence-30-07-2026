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
    $ls = \App\Models\LabelSetting::where('company_id', $admin->company_id)->first();
    $general_setting = \DB::table('general_settings')->where('company_id', $admin->company_id)->first();
    $generator = new \Picqer\Barcode\BarcodeGeneratorSVG();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Label 4×6</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        html,
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            background: #fff;
            width: 4in;
            /* margin: 0;
            padding: 0; */
        }

        @media print {

            html,
            body {
                width: 4in;
                margin: 0;
                padding: 0;
            }

            @page {
                size: 4in 6in;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }
        }

        /* ── SCREEN: print button ─────────────────────── */
        .no-print {
            padding: 10px;
            background: #f4f4f4;
            border-bottom: 1px solid #ddd;
            font-family: Arial, sans-serif;
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

        .no-print small {
            font-size: 11px;
            color: #666;
            margin-left: 12px;
        }

        /* ── LABEL WRAPPER ────────────────────────────── */
        .label-4x6 {
            width: calc(4in - 50px);
            height: calc(6in - 50px);
            max-width: calc(4in - 50px);
            max-height: calc(6in - 50px);
            /* padding: 8px 5px; */
            border: 1px solid #000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            box-sizing: border-box;
            page-break-after: always;
            break-after: page;
            overflow: hidden;
            padding: 10px;
            display: flex;
            flex-direction: column;
            margin: 20px;
            justify-content: space-between;
        }

        /* ── BARCODE SECTION ──────────────────────────── */
        .bc-row {
            text-align: center;
            flex-shrink: 0;
        }

        .bc-row .courier-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1px;
        }

        .bc-row .awb-text {
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 2px;
            color: #222;
        }

        .bc-row svg {
            width: 100%;
            height: 48px;
            display: block;
        }

        /* ── DIVIDER ──────────────────────────────────── */
        hr {
            border: none;
            border-top: 1px solid #000;
            flex-shrink: 0;
        }

        /* ── ORDER INFO ROW ───────────────────────────── */
        .hdr-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-shrink: 0;
        }

        .hdr-left {
            font-size: 9px;
            line-height: 1.6;
        }

        .hdr-right {
            text-align: right;
            line-height: 1.5;
            white-space: nowrap;
        }

        .hdr-right .payment-mode {
            font-weight: bold;
            font-size: 12px;
        }

        .hdr-right .amount {
            font-weight: bold;
            font-size: 17px;
        }

        /* ── SHIPPING ADDRESS ─────────────────────────── */
        .address-box {
            border: 1.5px solid #000;
            padding: 5px 8px;
            font-size: 10.5px;
            line-height: 1.55;
            flex-shrink: 0;
            /* natural height — no empty gap */
        }

        .address-box .label-tag {
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 2px;
        }

        .address-box .name {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        /* ── DIMENSIONS ROW ───────────────────────────── */
        .dim-row {
            display: flex;
            justify-content: flex-end;
            flex-shrink: 0;
        }

        .weight-text {
            font-size: 8.5px;
            color: #333;
        }

        /* ── PRODUCT TABLE ────────────────────────────── */
        table.products {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            flex-shrink: 0;
        }

        table.products th,
        table.products td {
            border: 1px solid #000;
            padding: 3px 5px;
        }

        table.products th {
            background: #e8e8e8;
            font-weight: bold;
            text-align: center;
        }

        /* ── RETURN ADDRESS ───────────────────────────── */
        .return-box {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 8px;
            line-height: 1.4;
            flex-shrink: 0;
        }

        .return-box .label-tag {
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 1px;
        }

        /* ── FOOTER ───────────────────────────────────── */
        .footer-note {
            font-size: 7.5px;
            color: #444;
            border-top: 1px dashed #888;
            padding-top: 3px;
            text-align: center;
            flex-shrink: 0;
        }
    </style>
</head>

<body>

    <div class="no-print">
        <div class="hdr-left-bar">
            <a href="javascript:history.back()" class="back-btn">&#8592; Back</a>
            <span class="page-title">🛠️ Print Labels &mdash; 4&times;6 inch</span>
            <span class="hint">Paper: 4×6 &nbsp;| Scale: 100% &nbsp;| Margins: None</span>
        </div>
        <button class="print-btn" onclick="window.print()">🖨️ Print Now</button>
    </div>



    @foreach ($orders as $order)
        <div class="label-4x6">

            {{-- ── BARCODE & LOGO HEADER ────────────────── --}}
            <div class="bc-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                <div style="text-align: left; flex: 1;">
                    <div class="courier-name">{{ $couriers[$order->ship_courier_id]['name'] ?? 'N/A' }}</div>
                    @if($order->tracking_info)
                        <div class="awb-text">{{ $order->tracking_info }}</div>
                    @endif
                </div>
                
                @if(!($ls && $ls->logo_hidden))
                    @php
                        $logo_path = ($ls && $ls->logo) ? $ls->logo : (isset($general_setting->logo) ? $general_setting->logo : null);
                    @endphp
                    @if($logo_path)
                    <div style="flex: 0 0 auto; text-align: right;">
                        <img src="{{ asset('uploads/' . $logo_path) }}" style="max-height: 40px; max-width: 120px; object-fit: contain;">
                    </div>
                    @endif
                @endif
            </div>

            @if($order->tracking_info)
            <div class="bc-row">
                <?php echo $generator->getBarcode($order->tracking_info, $generator::TYPE_CODE_128, 2, 45); ?>
            </div>
            @endif

            <hr>

            {{-- ── ORDER INFO + COD ───────────────────────── --}}
            <div class="hdr-row">
                <div class="hdr-left">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div>
                            <b>Order #:</b> {{ $order->vendor_order_id }}<br>
                            <b>Date:</b> {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}<br>
                            <b>Wt:</b> {{ number_format($order->weight / 1000, 3) }} kg
                        </div>
                        @if(!($ls && $ls->hide_order_barcode) && $order->vendor_order_id)
                        <div style="margin-top: 2px;">
                            <?php echo $generator->getBarcode($order->vendor_order_id, $generator::TYPE_CODE_128, 1, 20); ?>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="hdr-right">
                    <div class="payment-mode">{!! strip_tags($order->payment_mode) !!}</div>
                    @if(!($ls && $ls->hide_order_amount))
                        <div class="amount">₹{{ number_format($order->total, 2) }}</div>
                    @endif
                    @if(!($ls && $ls->hide_gst_number) && isset($general_setting->gst_number))
                        <div style="font-size: 8px; margin-top: 2px;">GST: {{ $general_setting->gst_number }}</div>
                    @endif
                </div>
            </div>

            <hr>

            {{-- ── SHIPPING ADDRESS ───────────────────────── --}}
            <div class="address-box">
                <div class="label-tag">Shipping Address</div>
                <div class="name">{{ $order->ship_fname }} {{ $order->ship_lname }}</div>
                {{ $order->ship_address }}@if ($order->ship_address_2)
                    , {{ $order->ship_address_2 }}
                @endif
                <br>
                {{ $order->ship_city }}, {{ $order->ship_state }}<br>
                PIN: {{ $order->ship_pincode }}
                @if (!($ls && $ls->hide_customer_mobile) && $order->ship_phone)
                    <br>📞 {{ $order->ship_phone }}
                @endif
            </div>

            {{-- ── DIMENSIONS ─────────────────────────────── --}}
            <div class="dim-row">
                <span class="weight-text">Dim: {{ $order->length }}×{{ $order->breadth }}×{{ $order->height }}
                    cm</span>
            </div>

            @if(!($ls && $ls->hide_product))
            @php
                $subtotal = $order->detail->sum('total_price');
                $total_gst = $order->detail->sum('tax_amount');
            @endphp
            <table class="products">
                <thead>
                    <tr>
                        <th style="width: auto">Product / SKU</th>
                        @if(!($ls && $ls->hide_qty)) <th style="width:40px">Qty</th> @endif
                        @if($ls && $ls->show_hsn) <th style="width:50px">HSN</th> @endif
                        @if($ls && $ls->show_gst) <th style="width:50px">GST</th> @endif
                        @if(!($ls && $ls->hide_total_amount)) <th style="width:70px">Total</th> @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->detail as $d)
                        <tr>
                            <td>
                                {{ $d->name }}
                                @if(!($ls && $ls->hide_sku) && $d->code)
                                    <br><small style="color: #666">[{{ $d->code }}]</small>
                                @endif
                            </td>
                            @if(!($ls && $ls->hide_qty)) <td style="text-align:center">{{ $d->qty }}</td> @endif
                            @if($ls && $ls->show_hsn) <td style="text-align:center">{{ $d->hsn ?? '-' }}</td> @endif
                            @if($ls && $ls->show_gst) <td style="text-align:right">₹{{ number_format($d->tax_amount, 0) }}</td> @endif
                            @if(!($ls && $ls->hide_total_amount)) <td style="text-align:right">₹{{ number_format($d->total_price, 2) }}</td> @endif
                        </tr>
                    @endforeach

                    @if(!($ls && $ls->hide_total_amount))
                        @if (!($ls && $ls->hide_shipping_charges) && ($shipping_cost = (float) $order->shipping_cost))
                            <tr>
                                <td colspan="{{ 1 + (!($ls && $ls->hide_qty) ? 1 : 0) + ($ls && $ls->show_hsn ? 1 : 0) + ($ls && $ls->show_gst ? 1 : 0) }}" style="text-align:right">Shipping</td>
                                <td style="text-align:right">₹{{ number_format($shipping_cost, 2) }}</td>
                            </tr>
                        @endif

                        @if (!($ls && $ls->hide_discount_amount) && ($discount = (float) $order->discount))
                            <tr>
                                <td colspan="{{ 1 + (!($ls && $ls->hide_qty) ? 1 : 0) + ($ls && $ls->show_hsn ? 1 : 0) + ($ls && $ls->show_gst ? 1 : 0) }}" style="text-align:right">Discount</td>
                                <td style="text-align:right">-₹{{ number_format($discount, 2) }}</td>
                            </tr>
                        @endif

                        <tr>
                            <td colspan="{{ 1 + (!($ls && $ls->hide_qty) ? 1 : 0) + ($ls && $ls->show_hsn ? 1 : 0) + ($ls && $ls->show_gst ? 1 : 0) }}" style="text-align:right"><b>Grand Total</b></td>
                            <td style="text-align:right"><b>₹{{ number_format($order->total, 2) }}</b></td>
                        </tr>
                    @endif
                </tbody>
            </table>
            @endif

            {{-- ── RETURN ADDRESS ──────────────────────────── --}}
            <div style="display: flex; gap: 10px;">
                @if (!($ls && $ls->hide_pickup_address) && isset($order->warehouse) && !empty($order->warehouse))
                    @php
                        $w_name = $order->warehouse->name ?? null;
                        $w_addr = $order->warehouse->address ?? null;
                    @endphp
                    @if($w_name || $w_addr)
                    <div class="return-box" style="flex: 1;">
                        <div class="label-tag">Pickup/Return Address</div>
                        @if(!($ls && $ls->hide_pickup_name) && $w_name)<b>{{ $w_name }}</b><br>@endif
                        @if($w_addr){{ $w_addr }}@endif
                        @if (!empty($order->warehouse->city)), {{ $order->warehouse->city }}@endif
                        @if (!empty($order->warehouse->pincode)) – {{ $order->warehouse->pincode }}@endif
                        @if($order->warehouse->mobile && !($ls && $ls->hide_pickup_mobile))<br>Ph: {{ $order->warehouse->mobile }}@endif
                    </div>
                    @endif
                @endif
            </div>

            {{-- ── FOOTER ─────────────────────────────────── --}}
            <div class="footer-note">
                @php
                    $f_mobile = ($ls && $ls->mobile) ? $ls->mobile : ($general_setting->phone ?? null);
                    $f_email = ($ls && $ls->email) ? $ls->email : ($general_setting->email ?? null);
                @endphp
                @if($f_mobile || $f_email)
                    <b>Contact:</b> @if($f_mobile){{ $f_mobile }}@endif @if($f_email) | {{ $f_email }}@endif <br>
                @endif
                Please unbox with video. No unboxing video = no liability for damage/missing items.
                @if (isset($general_setting))
                    &nbsp;|&nbsp; Powered by <b>{{ $general_setting->name }}</b>
                @endif
            </div>

        </div>
    @endforeach

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 400);
        });
    </script>

</body>

</html>