@extends('admin.admin_layouts')

@section('admin_content')
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        /* ── SCREEN: print button ───────────────────── */
        .no-print {
            padding: 12px;
        }

        /* ── PRINT PAGE SETTINGS ────────────────────── */
        @media print {

            .no-print {
                display: none !important;
            }

            body * {
                visibility: hidden;
            }

            .print-area,
            .print-area * {
                visibility: visible;
            }

            .print-area {
                position: absolute;
                left: 0;
                top: 0;
            }

            @page {
                size: 100mm 150mm;
                /* 4 × 6 inch thermal */
                margin: 3mm;
            }

        }

        /* ── LABEL WRAPPER ──────────────────────────── */
        .label-4x6 {
            width: 94mm;
            /* 100mm - 2×3mm margin */
            min-height: 140mm;
            border: 1px solid #000;
            padding: 2mm;
            box-sizing: border-box;
            page-break-after: always;
            position: relative;
            overflow: hidden;
        }

        /* ── BARCODE ROW ────────────────────────────── */
        .bc-row {
            text-align: center;
            margin-bottom: 1mm;
        }

        .bc-row img {
            width: 90%;
            height: 18mm;
        }

        .bc-row .awb-text {
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-top: 1px;
        }

        /* ── DIVIDER ─────────────────────────────────── */
        hr {
            border: none;
            border-top: 1px solid #000;
            margin: 1.5mm 0;
        }

        /* ── HEADER ROW: order info + COD ──────────── */
        .hdr-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5mm;
        }

        .hdr-left {
            font-size: 9px;
            line-height: 1.4;
        }

        .hdr-right {
            text-align: right;
            font-size: 10px;
            line-height: 1.5;
            white-space: nowrap;
        }

        .hdr-right .payment-mode {
            font-weight: bold;
            font-size: 12px;
            color: #000;
        }

        .hdr-right .amount {
            font-weight: bold;
            font-size: 14px;
        }

        /* ── SHIPPING ADDRESS ───────────────────────── */
        .address-box {
            border: 1px solid #000;
            padding: 2mm;
            margin-bottom: 1.5mm;
            font-size: 10px;
            line-height: 1.5;
        }

        .address-box .label-tag {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 0.5mm;
        }

        .address-box .name {
            font-size: 12px;
            font-weight: bold;
        }

        /* ── COURIER ROW ────────────────────────────── */
        .courier-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5mm;
        }

        .courier-name {
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
        }

        .weight-text {
            font-size: 9px;
            color: #333;
        }

        /* ── PRODUCT TABLE ──────────────────────────── */
        table.products {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-bottom: 1.5mm;
        }

        table.products th,
        table.products td {
            border: 1px solid #000;
            padding: 1mm 1.5mm;
        }

        table.products th {
            background: #e8e8e8;
            font-weight: bold;
            text-align: center;
        }

        /* ── RETURN ADDRESS ─────────────────────────── */
        .return-box {
            border: 1px solid #000;
            padding: 1.5mm;
            font-size: 8px;
            line-height: 1.4;
            margin-bottom: 1.5mm;
        }

        .return-box .label-tag {
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 0.5mm;
        }

        /* ── FOOTER ─────────────────────────────────── */
        .footer-note {
            font-size: 7.5px;
            color: #444;
            border-top: 1px dashed #888;
            padding-top: 1mm;
            text-align: center;
        }
    </style>


    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">
            🖨️ Print Labels (4×6)
        </button>
        <small class="ml-3 text-muted">
            Paper: 4×6 inch (100×150 mm) &nbsp;|&nbsp;
            Scale: 100% / Actual Size &nbsp;|&nbsp;
            Margins: None
        </small>
    </div>

    <div class="print-area">

        <?php
        $admin = auth()->guard('admin')->user();
        $general_setting = DB::table('general_settings')->where('company_id', $admin->company_id)->first();
        
        require base_path('vendor/autoload.php');
        require base_path('vendor/picqer/php-barcode-generator/src/BarcodeBar.php');
        require base_path('vendor/picqer/php-barcode-generator/src/Barcode.php');
        require base_path('vendor/picqer/php-barcode-generator/src/Exceptions/BarcodeException.php');
        require base_path('vendor/picqer/php-barcode-generator/src/Exceptions/UnknownTypeException.php');
        require base_path('vendor/picqer/php-barcode-generator/src/Types/TypeInterface.php');
        require base_path('vendor/picqer/php-barcode-generator/src/BarcodeGenerator.php');
        require base_path('vendor/picqer/php-barcode-generator/src/Types/TypeCode128.php');
        require base_path('vendor/picqer/php-barcode-generator/src/BarcodeGeneratorSVG.php');
        
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        ?>

        @foreach ($orders as $order)
            <div class="label-4x6">

                {{-- ── BARCODE ─────────────────────────────── --}}
                <div class="bc-row">
                    <div class="courier-name" style="font-size: 14px; margin-bottom: 2px;">
                        <b>{{ $couriers[$order->ship_courier_id]['name'] ?? 'N/A' }}</b>
                    </div>
                    <div class="awb-text" style="font-size: 11px; margin-bottom: 3px;">
                        {{ $order->tracking_info }}
                    </div>
                    <?php
                    echo $generator->getBarcode($order->tracking_info, $generator::TYPE_CODE_128, 2, 60);
                    ?>
                </div>

                <hr>

                {{-- ── ORDER INFO + COD ───────────────────── --}}
                <div class="hdr-row">
                    <div class="hdr-left">
                        <b>Order #:</b> {{ $order->vendor_order_id }}<br>
                        <b>Date:</b> {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}<br>
                        <b>Wt:</b> {{ number_format($order->weight / 1000, 3) }} kg
                    </div>
                    <div class="hdr-right">
                        <div class="payment-mode">{!! strip_tags($order->payment_mode) !!}</div>
                        <div class="amount">₹{{ number_format($order->total, 2) }}</div>
                    </div>
                </div>

                <hr>

                {{-- ── SHIPPING ADDRESS ───────────────────── --}}
                <div class="address-box">
                    <div class="label-tag">Shipping Address</div>
                    <div class="name">{{ $order->ship_fname }} {{ $order->ship_lname }}</div>
                    {{ $order->ship_address }}@if ($order->ship_address_2)
                        , {{ $order->ship_address_2 }}
                    @endif
                    <br>
                    {{ $order->ship_city }}, {{ $order->ship_state }}<br>
                    PIN: {{ $order->ship_pincode }}
                    @if ($order->ship_phone)
                        <br>📞 {{ $order->ship_phone }}
                    @endif
                </div>

                {{-- ── DIMENSIONS ──────────────────────────── --}}
                <div class="courier-row" style="justify-content: flex-end;">
                    <div class="weight-text">
                        Dim: {{ $order->length }}×{{ $order->breadth }}×{{ $order->height }} cm
                    </div>
                </div>

                @php
                    $subtotal = $order->detail->sum('total_price');
                    $total_gst = $order->detail->sum('tax_amount');
                @endphp
                <table class="products">
                    <tr>
                        <th style="width:60%">Product / SKU</th>
                        <th style="width:15%">Qty</th>
                        <th style="width:25%">Total</th>
                    </tr>
                    @foreach ($order->detail as $d)
                        <tr>
                            <td>{{ $d->code ?? $d->name }}</td>
                            <td style="text-align:center">{{ $d->qty }}</td>
                            <td style="text-align:right">₹{{ number_format($d->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                    
                    @if($shipping_cost = (float)$order->shipping_cost)
                        <tr>
                            <td colspan="2" style="text-align:right">Shipping</td>
                            <td style="text-align:right">₹{{ number_format($shipping_cost, 2) }}</td>
                        </tr>
                    @endif

                    @if($total_gst > 0)
                        <tr>
                            <td colspan="2" style="text-align:right">GST</td>
                            <td style="text-align:right">₹{{ number_format($total_gst, 2) }}</td>
                        </tr>
                    @endif

                    @if($discount = (float)$order->discount)
                        <tr>
                            <td colspan="2" style="text-align:right">Discount</td>
                            <td style="text-align:right">-₹{{ number_format($discount, 2) }}</td>
                        </tr>
                    @endif

                    <tr>
                        <td colspan="2" style="text-align:right"><b>Grand Total</b></td>
                        <td style="text-align:right"><b>₹{{ number_format($order->total, 2) }}</b></td>
                    </tr>
                </table>

                {{-- ── RETURN ADDRESS ──────────────────────── --}}
                @if (isset($order->warehouse) && !empty($order->warehouse))
                    <div class="return-box">
                        <div class="label-tag">Return Address</div>
                        {{ $order->warehouse->name ?? '' }}
                        @if (!empty($order->warehouse->address))
                            , {{ $order->warehouse->address }}
                        @endif
                        @if (!empty($order->warehouse->pincode))
                            – {{ $order->warehouse->pincode }}
                        @endif
                    </div>
                @endif

                {{-- ── FOOTER ──────────────────────────────── --}}
                <div class="footer-note">
                    Please unbox with video. No unboxing video = no liability for damage/missing items.
                    @if (isset($general_setting))
                        &nbsp;|&nbsp; Powered by <b>{{ $general_setting->name }}</b>
                    @endif
                </div>

            </div>
        @endforeach

    </div>
@endsection
