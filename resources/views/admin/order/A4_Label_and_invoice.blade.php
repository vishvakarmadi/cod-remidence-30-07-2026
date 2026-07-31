@extends('admin.admin_layouts')

@section('admin_content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            color: #333;
        }

        @media print {
            body * { visibility: hidden; }
            .print-area, .print-area * { visibility: visible; }
            .print-area { position: absolute; left: 0; top: 0; width: 100%; }
            @page { size: A4 portrait; margin: 4mm; }
            .page { page-break-after: always; width: 100%; height: 284mm; box-sizing: border-box; }
            .no-print { display: none !important; }
            .grid { height: 100%; }
            .label-block { height: 138mm; max-height: 138mm; overflow: hidden; }
            .btn { display: none !important; }
        }

        .page { margin-bottom: 10px; }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 4px;
            width: 100%;
            height: 100%;
        }

        .label-block {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0;
            overflow: hidden;
            background: #fff;
            display: flex;
            flex-direction: column;
            font-size: 9px;
            line-height: 1.3;
            color: #222;
        }

        .badge-cod { background: #e53e3e; color: white; padding: 2px 6px; border-radius: 4px; font-weight: 600; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px;}
        .badge-paid { background: #38a169; color: white; padding: 2px 6px; border-radius: 4px; font-weight: 600; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px;}
    </style>

    <button onclick="window.print()" class="btn btn-primary no-print" style="margin: 10px; background: #2b6cb0; border-color: #2b6cb0; font-family: 'Inter', sans-serif; font-weight: 500;">
        <i class="fa fa-print"></i> Print Labels + Invoice (4 per page)
    </button>
    <div class="print-area">
        @php
            $admin = auth()->guard('admin')->user();
            $general_setting = DB::table('general_settings')->where('company_id', $admin->company_id)->first();
            require base_path('vendor/autoload.php');
            require base_path('vendor/picqer/php-barcode-generator/src/Exceptions/BarcodeException.php');
            require base_path('vendor/picqer/php-barcode-generator/src/BarcodeBar.php');
            require base_path('vendor/picqer/php-barcode-generator/src/Barcode.php');
            require base_path('vendor/picqer/php-barcode-generator/src/Types/TypeInterface.php');
            require base_path('vendor/picqer/php-barcode-generator/src/BarcodeGenerator.php');
            require base_path('vendor/picqer/php-barcode-generator/src/Types/TypeCode128.php');
            require base_path('vendor/picqer/php-barcode-generator/src/BarcodeGeneratorSVG.php');
            $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
            $chunks = $orders->chunk(4);
        @endphp

        @foreach ($chunks as $page)
            <div class="page">
                <div class="grid">
                    @foreach ($page as $order)
                        @php
                           $ls = \App\Models\LabelSetting::where('user_id', $admin->id)->first();
                            $kyc = \DB::table('profiles')->where('user_id', $order->user_id)->first();
                            $kyc_gst = $kyc->gst ?? null;

                            $warehouse = $order->warehouse ?? \App\Models\Admin\Warehouse::find($order->warehouse_id) ?? (object)[
                                'name'      => $general_setting->footer_column1_heading ?? 'Warehouse',
                                'address'   => $general_setting->footer_address ?? 'N/A',
                                'city'      => '',
                                'gst_no'    => 'N/A',
                                'phone'     => $general_setting->footer_phone ?? '',
                                'state'     => '',
                                'pincode'   => ''
                            ];
                            $rto_warehouse = $order->rto_warehouse ?? ($order->return_warehouse_id ? \App\Models\Admin\Warehouse::find($order->return_warehouse_id) : null) ?? $warehouse;
                            $seller_state   = strtolower(trim($warehouse->state ?? ''));
                            $buyer_state    = strtolower(trim($order->ship_state ?? ''));
                            $is_inter_state = ($seller_state != $buyer_state);
                            $pmode          = strip_tags($order->payment_mode);
                            $is_cod         = ($pmode == '6' || $pmode == 'C.O.D');
                        @endphp

                        <div class="label-block">

                            {{-- ===== PART 1: SHIPPING LABEL ===== --}}
                            <div style="border-bottom: 2px dashed #cbd5e0; padding: 6px 10px;">

                                {{-- Compact header: Warehouse name + Payment type --}}
                                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #edf2f7; padding-bottom: 3px; margin-bottom: 4px;">
                                    <div style="font-size: 8px;">
                                        @if(!($ls && $ls->hide_pickup_name))
                                            <b style="font-size: 9px; color: #1a202c;">{{ $warehouse->name }}</b>
                                        @endif
                                        @if(!($ls && $ls->hide_pickup_address))
                                            | <span style="color: #4a5568;">{{ $warehouse->city ?? '' }}, {{ $warehouse->state ?? '' }}</span>
                                        @endif
                                    </div>
                                    @if(!($ls && $ls->hide_gst_number))
                                        <div style="font-size: 7px; color: #718096; font-weight: 500;">
                                            GSTIN: {{ ($warehouse->gst_no && $warehouse->gst_no != 'N/A') ? $warehouse->gst_no : ($kyc_gst ?? 'N/A') }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Address Row: LEFT=address, RIGHT=circular logo + barcode --}}
                                <div style="display: flex; justify-content: space-between;">
                                    {{-- Ship To --}}
                                    <div style="width: 52%; font-size: 8px; padding-right: 4px;">
                                        <div style="font-size: 7px; color: #718096; font-weight: 600; text-transform: uppercase; margin-bottom: 1px;">Ship To</div>
                                        @if(!($ls && $ls->hide_customer_name))
                                            <b style="font-size: 11px; color: #1a202c; display: block; margin-bottom: 2px;">{{ $order->ship_fname }} {{ $order->ship_lname }}</b>
                                        @endif
                                        @if(!($ls && $ls->hide_customer_address))
                                            <div style="color: #2d3748; line-height: 1.2;">
                                                {{ $order->ship_address }}<br>
                                                {{ $order->ship_city }}, {{ $order->ship_state }} - {{ $order->ship_pincode }}
                                            </div>
                                        @endif
                                        @if(!($ls && $ls->hide_customer_mobile) && $order->ship_phone)
                                            <div style="margin-top: 2px;">Ph: <b style="color: #1a202c;">{{ $order->ship_phone }}</b></div>
                                        @endif
                                        
                                       @if(!($ls && $ls->hide_rto_address))
                                        <div style="border-top: 1px solid #edf2f7; background: #f7fafc; border-radius: 3px; margin-top: 4px; padding: 3px; font-size: 7px; color: #4a5568;">
                                            <b style="color: #2d3748;">Return:</b> 
                                            @if(!($ls && $ls->hide_rto_name)){{ $rto_warehouse->name }}, @endif
                                            {{ $rto_warehouse->city ?? '' }}, {{ $rto_warehouse->state ?? '' }}
                                            @if(!($ls && $ls->hide_rto_mobile) && ($rto_warehouse->phone || $rto_warehouse->mobile || $general_setting->footer_phone))
                                                | <b style="color: #2d3748;">Ph:</b> {{ $rto_warehouse->phone ?? ($rto_warehouse->mobile ?? ($general_setting->footer_phone ?? 'N/A')) }}
                                            @endif
                                        </div>
                                        @endif
                                    </div>

                                    {{-- RIGHT: Logo + Order Date + Payment + Barcode --}}
                                    <div style="width: 46%; text-align: center; display: flex; flex-direction: column; align-items: center;">
                                        {{-- Logo Container --}}
                                        @if($ls && $ls->logo_hidden)
                                        <div style="max-width: 80px; height: 35px; border: 1px solid #e2e8f0; border-radius: 4px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #fff; margin-bottom: 3px; padding: 2px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                            @if ($ls->logo)
                                                <img src="{{ asset('public/uploads/' . $ls->logo) }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                            @elseif (isset($general_setting) && $general_setting->logo)
                                                <img src="{{ asset('public/uploads/' . $general_setting->logo) }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                            @else
                                                <b style="font-size: 8px; text-align: center; color: #4a5568;">{{ $general_setting->name ?? '' }}</b>
                                            @endif
                                        </div>
                                        @endif

                                        {{-- Order Date --}}
                                        <div style="font-size: 7px; margin-bottom: 3px; text-align: center; color: #4a5568;">
                                            <b style="color: #1a202c; font-size: 8px;">Order #: {{ $order->vendor_order_id ?? $order->id }}</b><br>
                                            <b style="color: #2d3748;">Date: {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</b> 
                                            @if(!($ls && $ls->hide_invoice_number))
                                                | Invoice #: {{ $order->order_id ?? $order->id }}
                                            @endif
                                        </div>

                                        {{-- Payment Badge --}}
                                        @if(!($ls && $ls->hide_order_amount))
                                        <div style="width: 100%; text-align: center; margin-bottom: 3px;">
                                            <span class="{{ $is_cod ? 'badge-cod' : 'badge-paid' }}" style="display: block;">
                                                {{ $is_cod ? 'COD Collectable: ₹'.($order->custom_total > 0 ? $order->custom_total : $order->total) : 'PRE-PAID' }}
                                            </span>
                                        </div>
                                        @endif

                                        {{-- Courier + Barcode --}}
                                        <div style="font-size: 9px; margin-bottom: 3px; color: #1a202c; font-weight: 700; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                            <span>{{ $couriers[$order->ship_courier_id]['name'] ?? 'Courier' }}</span>
                                            <span style="font-size: 9px; letter-spacing: 0.5px;">{{ $order->tracking_info }}</span>
                                        </div>
                                        @if($order->tracking_info)
                                        <div style="background: #fff; padding: 1px; border: 1px solid #edf2f7; border-radius: 2px; width: 100%; box-sizing: border-box;">
                                            <img src="data:image/svg+xml;base64,{{ base64_encode($generator->getBarcode($order->tracking_info, $generator::TYPE_CODE_128, 2, 70)) }}" style="width: 100%; height: 60px;">
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Product Summary --}}
                                @if(!($ls && $ls->hide_product))
                                <div style="background: #f7fafc; padding: 3px 4px; border-radius: 3px; margin-top: 4px; font-size: 7px; border: 1px solid #edf2f7; color: #4a5568;">
                                    <b style="color: #2d3748;">Items:</b> 
                                    @foreach($order->detail as $item)
                                        {{ $item->name }} @if(!($ls && $ls->hide_qty))(x{{ $item->qty }})@endif @if(!($ls && $ls->hide_sku) && $item->code)[{{ $item->code }}]@endif &nbsp;
                                    @endforeach
                                    <span style="border-left: 1px solid #cbd5e0; padding-left: 4px; margin-left: 2px;"><b style="color: #2d3748;">Wt:</b> {{ $order->weight/1000 }}KG</span>
                                </div>
                                @endif
                            </div>

                            {{-- ===== PART 2: TAX INVOICE ===== --}}
                            <div style="padding: 8px 10px; flex-grow: 1; display: flex; flex-direction: column;">

                                {{-- Invoice Header --}}
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; margin-bottom: 4px;">
                                    <div>
                                        <b style="font-size: 11px; color: #1a202c; letter-spacing: 0.5px;">TAX INVOICE</b><br>
                                        <span style="font-size: 7px; color: #4a5568; line-height: 1.2; display: inline-block; margin-top: 2px;">
                                            @if(!($ls && $ls->hide_pickup_name))<b style="color: #2d3748;">{{ $warehouse->name }}</b>@endif 
                                            @if(!($ls && $ls->hide_gst_number))| GSTIN: {{ $warehouse->gst_no != 'N/A' ? $warehouse->gst_no : ($kyc_gst ?? 'N/A') }}@endif<br>
                                            @if(!($ls && $ls->hide_pickup_address))
                                                {{ $warehouse->address }}, {{ $warehouse->city ?? '' }}, {{ $warehouse->state ?? '' }}
                                            @endif
                                        </span>
                                    </div>
                                    <div style="text-align: right; font-size: 7px; color: #4a5568; line-height: 1.2;">
                                        <b style="font-size: 8px; color: #1a202c;">Order #: {{ $order->vendor_order_id ?? $order->id }}</b><br>
                                        @if(!($ls && $ls->hide_invoice_number))
                                            <b style="color: #2d3748;">Invoice #: {{ $order->order_id ?? $order->id }}</b><br>
                                        @endif
                                        Invoice Date: {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}<br>
                                        <span style="font-style: italic; color: #718096; background: #f7fafc; padding: 1px 3px; border-radius: 2px; margin-top: 2px; display: inline-block; border: 1px solid #edf2f7;">Original For Recipient</span>
                                    </div>
                                </div>

                                {{-- Bill To + Order Info --}}
                                <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 7px;">
                                    <div style="width: 55%; border: 1px solid #e2e8f0; border-radius: 3px; padding: 3px 4px; background: #fffcf9;">
                                        <div style="font-size: 6.5px; color: #718096; font-weight: 600; margin-bottom: 1px;">BILL TO / SHIP TO</div>
                                        @if(!($ls && $ls->hide_customer_name))
                                            <b style="font-size: 8px; color: #1a202c;">{{ $order->ship_fname }} {{ $order->ship_lname }}</b><br>
                                        @endif
                                        @if(!($ls && $ls->hide_customer_address))
                                            <span style="color: #4a5568;">{{ $order->ship_address }}, {{ $order->ship_city }}, {{ $order->ship_state }} - {{ $order->ship_pincode }}</span><br>
                                            <span style="color: #718096; display: inline-block; margin-top: 1px;">Place of Supply: <b style="color: #4a5568;">{{ $order->ship_state }}</b></span>
                                        @endif
                                    </div>
                                    <div style="width: 42%; border: 1px solid #e2e8f0; border-radius: 3px; padding: 3px 4px; font-size: 7px; color: #4a5568; background: #fbfbfb;">
                                        <span style="color: #718096;">Order #:</span> <b style="color: #1a202c;">{{ $order->vendor_order_id ?? $order->id }}</b><br>
                                        <div style="margin-top: 3px;">
                                            @if($is_cod)
                                                @if(!($ls && $ls->hide_order_amount))
                                                    <b style="color: #c53030; background: #fff5f5; padding: 1px 3px; border-radius: 2px;">C.O.D: ₹{{ $order->custom_total }}</b>
                                                @endif
                                            @else
                                                <b style="color: #2f855a; background: #f0fff4; padding: 1px 3px; border-radius: 2px;">Pre-Paid</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Product Table --}}
                                @if(!($ls && $ls->hide_product))
                                <table style="width: 100%; border-collapse: collapse; font-size: 7px; border: 1px solid #e2e8f0; border-radius: 3px; overflow: hidden;">
                                    <thead>
                                        <tr style="background: #f1f5f9; color: #334155; text-align: left;">
                                            <th style="padding: 3px; border-bottom: 1px solid #cbd5e1; font-weight: 600; text-align: left;">Item / SKU</th>
                                            @if(!($ls && $ls->hide_qty)) <th style="padding: 3px; border-bottom: 1px solid #cbd5e1; font-weight: 600; text-align: center; width: 14px;">Qty</th> @endif
                                            <th style="padding: 3px; border-bottom: 1px solid #cbd5e1; font-weight: 600; text-align: right; width: 35px;">Gross</th>
                                            @if(!($ls && $ls->hide_discount_amount)) <th style="padding: 3px; border-bottom: 1px solid #cbd5e1; font-weight: 600; text-align: right; width: 40px;">Disc</th> @endif
                                            @if($ls && $ls->show_gst) <th style="padding: 3px; border-bottom: 1px solid #cbd5e1; font-weight: 600; text-align: right; width: 45px;">Tax</th> @endif
                                            @if(!($ls && $ls->hide_total_amount)) <th style="padding: 3px; border-bottom: 1px solid #cbd5e1; font-weight: 600; text-align: right; width: 40px;">Total</th> @endif
                                        </tr>
                                    </thead>
                                    <tbody style="color: #475569;">
                                        @php $total_tax = 0; $total_item_disc = 0; @endphp
                                        @foreach($order->detail as $item)
                                            @php
                                                $tax      = floatval($item->tax_amount);
                                                $gross    = $item->price * $item->qty;
                                                $disc_amt = ($item->discount_type == 'p') ? ($item->price * $item->qty * $item->discount) / 100 : (float)($item->discount ?? 0);
                                                $total_tax += $tax;
                                                $total_item_disc += $disc_amt;
                                            @endphp
                                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                                <td style="padding: 3px; border-right: 1px dotted #e2e8f0;">
                                                    <b style="color: #1e293b;">{{ $item->name }}</b>
                                                    @if(!($ls && $ls->hide_sku) && $item->code)
                                                        <br><span style="color: #64748b; font-size: 6px;">SKU: {{ $item->code }}</span>
                                                    @endif
                                                    @if($ls && $ls->show_hsn && $item->hsn)
                                                        | <span style="color: #64748b; font-size: 6px;">HSN: {{ $item->hsn }}</span>
                                                    @endif
                                                </td>
                                                @if(!($ls && $ls->hide_qty)) <td style="padding: 3px; text-align: center; border-right: 1px dotted #e2e8f0;">{{ $item->qty }}</td> @endif
                                                <td style="padding: 3px; text-align: right; border-right: 1px dotted #e2e8f0;">₹{{ number_format($gross, 2) }}</td>
                                                @if(!($ls && $ls->hide_discount_amount)) <td style="padding: 3px; text-align: right; border-right: 1px dotted #e2e8f0;">₹{{ number_format($disc_amt, 2) }}</td> @endif
                                                @if($ls && $ls->show_gst)
                                                <td style="padding: 3px; text-align: right; font-size: 6px; border-right: 1px dotted #e2e8f0; line-height: 1.1;">
                                                    @if($is_inter_state)
                                                        IGST {{ $item->tax_percent }}%<br><b style="color:#334155;">₹{{ number_format($tax, 2) }}</b>
                                                    @else
                                                        C+S {{ $item->tax_percent }}%<br><b style="color:#334155;">₹{{ number_format($tax, 2) }}</b>
                                                    @endif
                                                </td>
                                                @endif
                                                @if(!($ls && $ls->hide_total_amount)) <td style="padding: 3px; text-align: right; color: #0f172a; font-weight: 500;">₹{{ number_format($item->total_price, 2) }}</td> @endif
                                            </tr>
                                        @endforeach

                                        @if(!($ls && $ls->hide_shipping_charges) && isset($order->shipping_cost) && $order->shipping_cost > 0)
                                        <tr style="border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                                            <td style="padding: 3px; border-right: 1px dotted #e2e8f0;">Shipping / Fees</td>
                                            @if(!($ls && $ls->hide_qty)) <td style="padding: 3px; text-align:center; border-right: 1px dotted #e2e8f0;">-</td> @endif
                                            <td style="padding: 3px; text-align: right; border-right: 1px dotted #e2e8f0;">₹{{ number_format($order->shipping_cost, 2) }}</td>
                                            @if(!($ls && $ls->hide_discount_amount)) <td style="padding: 3px; text-align: right; border-right: 1px dotted #e2e8f0;">₹0</td> @endif
                                            @if($ls && $ls->show_gst) <td style="padding: 3px; border-right: 1px dotted #e2e8f0;"></td> @endif
                                            @if(!($ls && $ls->hide_total_amount)) <td style="padding: 3px; text-align: right; color: #0f172a;">₹{{ number_format($order->shipping_cost, 2) }}</td> @endif
                                        </tr>
                                        @endif

                                        @if(!($ls && $ls->hide_total_amount))
                                        @php
                                            $total_discount = (float)$order->discount + $total_item_disc;
                                        @endphp
                                        @if($total_discount > 0 && !($ls && $ls->hide_discount_amount))
                                        <tr style="border-bottom: 1px solid #e2e8f0; background: #fffcf9;">
                                            <td style="padding: 3px; border-right: 1px dotted #e2e8f0;">Total Discount</td>
                                            @if(!($ls && $ls->hide_qty)) <td style="text-align:center; border-right: 1px dotted #e2e8f0;">-</td> @endif
                                            <td style="padding: 3px; text-align: right; border-right: 1px dotted #e2e8f0;"></td>
                                            <td style="padding: 3px; text-align: right; border-right: 1px dotted #e2e8f0; font-weight: 600; color: #c53030;">-₹{{ number_format($total_discount, 2) }}</td>
                                            @if($ls && $ls->show_gst) <td style="padding: 3px; border-right: 1px dotted #e2e8f0;"></td> @endif
                                            @if(!($ls && $ls->hide_total_amount)) <td style="padding: 3px; text-align: right; color: #c53030;">-₹{{ number_format($total_discount, 2) }}</td> @endif
                                        </tr>
                                        @endif
                                        <tr style="font-weight: 600; background: #e2e8f0; color: #0f172a;">
                                            <td colspan="{{ 1 + (!($ls && $ls->hide_qty)?1:0) + (!($ls && $ls->hide_discount_amount)?1:0) }}" style="padding: 4px 6px; text-align: right; border-right: 1px solid #cbd5e1;">GRAND TOTAL</td>
                                            <td style="padding: 4px 3px; text-align: right; border-right: 1px solid #cbd5e1;">@if($ls && $ls->show_gst)₹{{ number_format($total_tax, 2) }}@endif</td>
                                            <td style="padding: 4px 3px; text-align: right; font-size: 8.5px;">
                                                ₹{{ number_format((float)($order->custom_total > 0 ? $order->custom_total : $order->total), 2) }}
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                @endif

                                {{-- Footer / Terms --}}
                                <div style="display: flex; justify-content: space-between; margin-top: auto; font-size: 6px; border-top: 1px solid #e2e8f0; padding-top: 3px; color: #64748b;">
                                    <div style="width: 60%; line-height: 1.2;">
                                        <b style="color: #475569;">Terms and conditions:</b> No liability for lost shipment. No signature = No liability. Note: Goods once sold will only be taken back or exchanged as per store return policy.<br>
                                        <span style="font-style: italic; margin-top: 2px; display: inline-block;">Powered by Hyloship. This is an auto-generated label and does not need signature.</span>
                                    </div>
                                    <div style="width: 38%; text-align: center; border: 1px solid #e2e8f0; border-radius: 2px; padding: 2px; background: #f8fafc;">
                                        <b style="color: #334155;">For @if(!($ls && $ls->hide_pickup_name)){{ $warehouse->name }}@endif</b><br>
                                        <div style="height: 10px;"></div>
                                        Authorized Signatory
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
