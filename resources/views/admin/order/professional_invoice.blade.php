@extends('admin.admin_layouts')
@section('admin_content')
@php
    $admin = auth()->guard('admin')->user();
    $g_setting = DB::table('general_settings')->where('company_id', $admin->company_id)->first();
    $themeColor = $g_setting->theme_color ?? '#744245';

    // Fetch Client/Seller Specific Label Settings
    $ls = \App\Models\LabelSetting::where('user_id', $admin->id)->first();
    $logo_path = ($ls && $ls->logo && !$ls->logo_hidden) ? $ls->logo : (isset($g_setting->logo) ? $g_setting->logo : null);

    // Barcode Generator setup
    require_once base_path('vendor/picqer/php-barcode-generator/src/BarcodeGenerator.php');
    require_once base_path('vendor/picqer/php-barcode-generator/src/BarcodeGeneratorSVG.php');
    require_once base_path('vendor/picqer/php-barcode-generator/src/Types/TypeInterface.php');
    require_once base_path('vendor/picqer/php-barcode-generator/src/Types/TypeCode128.php');
    require_once base_path('vendor/picqer/php-barcode-generator/src/Barcode.php');
    require_once base_path('vendor/picqer/php-barcode-generator/src/BarcodeBar.php');
    $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --primary-color: {{ $themeColor }};
        --primary-light: {{ $themeColor }}15;
        --secondary-color: #64748b;
        --dark-color: #0f172a;
        --light-bg: #f8fafc;
        --border-color: #e2e8f0;
        --success-color: #10b981;
        --danger-color: #ef4444;
    }

    body {
        background-color: #f1f5f9;
        font-family: 'Inter', sans-serif;
        color: var(--dark-color);
        margin: 0;
        padding: 0;
    }
.cutoff-marquee{
    display: none;
}
    .invoice-wrapper {
        padding: 40px 15px;
    }

    .invoice-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        padding: 45px;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border-color);
        max-width: 950px;
        margin: 0 auto 30px;
    }

    /* Status Stamp */
    .status-stamp {
        border: 3px double var(--status-color);
        border-radius: 4px;
        padding: 6px 15px;
        display: inline-block;
        font-weight: 800;
        font-size: 1rem;
        text-transform: uppercase;
        transform: rotate(-10deg);
        color: var(--status-color);
        margin-bottom: 20px;
        opacity: 0.85;
        letter-spacing: 1px;
    }
    .badge-paid { --status-color: var(--success-color); }
    .badge-cod { --status-color: var(--danger-color); }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 2px solid var(--light-bg);
    }

    .logo-container img {
        max-width: 160px;
        height: auto;
    }

    .invoice-meta {
        text-align: right;
    }

    .invoice-title {
        font-weight: 800;
        font-size: 2.2rem;
        color: var(--primary-color);
        margin: 0;
        line-height: 1.1;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
    }

    .info-box {
        padding: 20px;
        background: var(--light-bg);
        border-radius: 10px;
        border: 1px solid var(--border-color);
    }

    .section-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--secondary-color);
        letter-spacing: 1.2px;
        margin-bottom: 12px;
        display: block;
    }

    .main-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 30px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        overflow: hidden;
    }

    .main-table thead th {
        background: var(--primary-color);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        padding: 14px 12px;
        text-align: left;
    }

    .main-table tbody td {
        padding: 14px 12px;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.85rem;
    }

    .main-table tbody tr:nth-child(even) {
        background-color: var(--light-bg);
    }

    .main-table tbody tr:last-child td {
        border-bottom: none;
    }

    .totals-container {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 40px;
    }

    .tax-table {
        width: 100%;
        font-size: 0.75rem;
        border-collapse: collapse;
    }

    .tax-table th, .tax-table td {
        padding: 8px;
        border: 1px solid var(--border-color);
        text-align: right;
    }

    .tax-table th { background: var(--light-bg); text-align: center; }

    .summary-box {
        background: var(--dark-color);
        color: #fff;
        padding: 25px;
        border-radius: 12px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .summary-row.total {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid rgba(255,255,255,0.1);
        font-size: 1.4rem;
        font-weight: 700;
        opacity: 1;
        color: #fff;
    }

    .signature-section {
        margin-top: 60px;
        text-align: right;
    }

    .sign-line {
        width: 200px;
        border-top: 1px solid var(--dark-color);
        margin-left: auto;
        margin-bottom: 8px;
    }

    @media print {
        @page { size: A4; margin: 0; }
        body { background: #fff; }
        .no-print { display: none !important; }
        .invoice-wrapper { padding: 0; }
        .invoice-card {
            box-shadow: none;
            border: none;
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 15mm;
            border-radius: 0;
            page-break-after: always;
        }
        .status-badge { -webkit-print-color-adjust: exact; }
    }

    /* Hide Global Layout Elements on this page */
    .fixed-marquee, 
    .main-navbar, 
    .whatsapp-float, 
    .track-float, 
    #leftsidebar, 
    .page-loader-wrapper {
        display: none !important;
    }

    #wrapper {
        padding-top: 0 !important;
    }

    #main-content {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
</style>

<div class="container no-print mb-4 mt-4">
    <div class="row">
        <div class="col-12 text-right">
            <button onclick="window.print()" class="btn btn-primary shadow-sm">
                <i class="fa fa-print"></i> Print Professional Invoices
            </button>
        </div>
    </div>
</div>

<div class="invoice-wrapper" id="content_print">
    @if(isset($manifest) && is_object($manifest) && isset($manifest->manifest_order) && count($manifest->manifest_order) > 0)
        @foreach($manifest->manifest_order as $order_group)
            @php 
                $order = $order_group[0];
                $warehouse = \App\Models\Admin\Warehouse::find($order->warehouse_id);
                $originState = strtolower(trim($warehouse->state ?? ''));
                $destState = strtolower(trim($order->ship_state ?? ''));
                $isInterState = ($originState != $destState && $originState != '' && $destState != '');
                
                $sellerGstin = $warehouse->gst_no ?? 'N/A';
                $isPaid = strip_tags($order->payment_mode) != 'C.O.D';
            @endphp
            <div class="invoice-card">
                <!-- Header -->
                <div class="invoice-header">
                    <div class="logo-container">
                        @if($logo_path)
                            <img src="{{ asset('public/uploads/'.$logo_path) }}" alt="Logo">
                        @else
                            <h2 class="fw-bold text-dark mb-0 font-weight-bold">{{ $g_setting->name ?? 'Hyloship' }}</h2>
                        @endif
                        <p class="text-muted small mt-2 mb-0">Place of Supply: <strong>{{ strtoupper($destState) }}</strong></p>
                    </div>
                    <div class="invoice-meta">
                        <div class="status-stamp {{ $isPaid ? 'badge-paid' : 'badge-cod' }}">
                            {{ $isPaid ? 'PAID' : 'CASH ON DELIVERY' }}
                        </div>
                        <h1 class="invoice-title">TAX INVOICE</h1>
                        <!--<p class="text-muted mb-2">Invoice #{{ $order->order_id }}</p>-->
                        <p class="text-muted mb-2">Invoice #{{ $order->invoice_no ?? $order->order_id }}</p>
                        <div class="d-flex align-items-center justify-content-end gap-3 mt-3">
                            <div class="text-right">
                                <p class="mb-1 small"><strong>Date:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d-M-Y') }}</p>
                                <img src="data:image/svg+xml;base64,{{ base64_encode($generator->getBarcode($order->order_id, $generator::TYPE_CODE_128, 1.5, 25)) }}" style="height: 25px;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Addresses -->
                <div class="info-grid">
                    <div class="info-box">
                        <span class="section-label">Seller Information</span>
                        <h6 class="fw-bold mb-1 text-dark">{{ $warehouse->name ?? ($g_setting->name ?? 'Aframax Pvt Ltd') }}</h6>
                        <p class="text-muted small mb-1">{{ $warehouse->address ?? 'N/A' }}, {{ $warehouse->city ?? '' }}</p>
                        <p class="text-muted small mb-1">{{ $warehouse->state ?? '' }} - {{ $warehouse->pincode ?? '' }}</p>
                        <p class="text-muted small mb-0"><strong>GSTIN:</strong> {{ $sellerGstin }}</p>
                    </div>
                    <div class="info-box">
                        <span class="section-label">Shipping To</span>
                        <h6 class="fw-bold mb-1 text-dark">{{ ucfirst($order->ship_fname) }} {{ ucfirst($order->ship_lname) }}</h6>
                        <p class="text-muted small mb-1">{{ $order->ship_address }}</p>
                        <p class="text-muted small mb-1">{{ $order->ship_city }}, {{ $order->ship_state }} - {{ $order->ship_pincode }}</p>
                        <p class="text-muted small mb-0"><strong>Phone:</strong> {{ $order->ship_phone }}</p>
                    </div>
                </div>

                <!-- Product Table -->
                <div class="table-responsive">
                    <table class="main-table">
                        <thead>
                            <tr>
                                <th style="width: 40%">Item Description</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Rate</th>
                                <th class="text-right">Taxable</th>
                                <th class="text-right">GST</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $total_taxable = 0;
                                $total_discount = 0;
                                $total_gst = 0;
                                $tax_groups = [];
                            @endphp
                            @foreach($order->detail as $detail)
                                @php
                                    $taxable = $detail->price * $detail->qty;
                                    $tax = $detail->tax_amount;
                                    $disc_amt = $taxable + $tax - $detail->total_price;
                                    if(abs($disc_amt) < 0.01) $disc_amt = 0;
                                    
                                    $total_taxable += $taxable;
                                    $total_discount += $disc_amt;
                                    $total_gst += $tax;

                                    $rate = $detail->tax_percent;
                                    if(!isset($tax_groups[$rate])) $tax_groups[$rate] = ['taxable' => 0, 'tax' => 0];
                                    $tax_groups[$rate]['taxable'] += $taxable;
                                    $tax_groups[$rate]['tax'] += $tax;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $detail->name }}</div>
                                        <div class="text-muted small">SKU: {{ $detail->code }}</div>
                                    </td>
                                    <td class="text-center">{{ $detail->qty }}</td>
                                    <td class="text-right">₹{{ number_format($detail->price, 2) }}</td>
                                    <td class="text-right">₹{{ number_format($taxable, 2) }}</td>
                                    <td class="text-right">
                                        ₹{{ number_format($tax, 2) }}<br>
                                        <small class="text-muted">({{ $rate }}%)</small>
                                    </td>
                                    <td class="text-right font-weight-bold">₹{{ number_format($detail->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals & Tax Summary -->
                <div class="totals-container mt-2">
                    <div>
                        <span class="section-label">GST Breakdown</span>
                        <table class="tax-table">
                            <thead>
                                <tr>
                                    <th>Rate</th>
                                    <th>Taxable</th>
                                    @if($isInterState)
                                        <th>IGST</th>
                                    @else
                                        <th>CGST</th>
                                        <th>SGST</th>
                                    @endif
                                    <th>Total Tax</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tax_groups as $rate => $vals)
                                    <tr>
                                        <td class="text-center">{{ $rate }}%</td>
                                        <td>{{ number_format($vals['taxable'], 2) }}</td>
                                        @if($isInterState)
                                            <td>{{ number_format($vals['tax'], 2) }}</td>
                                        @else
                                            <td>{{ number_format($vals['tax']/2, 2) }}</td>
                                            <td>{{ number_format($vals['tax']/2, 2) }}</td>
                                        @endif
                                        <td class="font-weight-bold">{{ number_format($vals['tax'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <div class="mt-4 pt-2">
                            <span class="section-label">Shipping Details</span>
                            <p class="small mb-1"><strong>AWB:</strong> {{ $order->tracking_info }}</p>
                            <p class="small"><strong>Courier:</strong> {{ @$couriers[$order->ship_courier_id]['name'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div>
                        <div class="summary-box">
                            <div class="summary-row">
                                <span>Taxable Value</span>
                                <span>₹{{ number_format($total_taxable, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>GST Amount</span>
                                <span>₹{{ number_format($total_gst, 2) }}</span>
                            </div>
                            @php
                                $global_discount = isset($order->discount) ? (float)$order->discount : 0;
                                $overall_discount = $total_discount + $global_discount;
                                $shipping_cost = isset($order->shipping_cost) ? (float)$order->shipping_cost : 0;
                                $cod_charge = isset($order->cod_charge) ? (float)$order->cod_charge : 0;
                            @endphp
                            @if($overall_discount > 0)
                                <div class="summary-row text-danger">
                                    <span>Discount</span>
                                    <span>-₹{{ number_format($overall_discount, 2) }}</span>
                                </div>
                            @endif
                            @if($shipping_cost > 0)
                                <div class="summary-row">
                                    <span>Shipping</span>
                                    <span>₹{{ number_format($shipping_cost, 2) }}</span>
                                </div>
                            @endif
                            @if($cod_charge > 0)
                                <div class="summary-row">
                                    <span>COD Charges</span>
                                    <span>₹{{ number_format($cod_charge, 2) }}</span>
                                </div>
                            @endif
                            
                            @php
                                $db_grand_total = (float)($order->custom_total > 0 ? $order->custom_total : $order->total);
                            @endphp
                            
                            <div class="summary-row total">
                                <span>Grand Total</span>
                                <span>₹{{ number_format($db_grand_total, 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="signature-section">
                            <div class="sign-line"></div>
                            <span class="section-label" style="letter-spacing: 0;">Authorized Signatory</span>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-3 border-top text-muted small text-center" style="opacity: 0.6;">
                    This is a computer generated document and does not require a physical signature.
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center p-5">
            <h4 class="text-muted">No orders selected for printing.</h4>
        </div>
    @endif
</div>
@endsection
