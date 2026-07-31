@extends('admin.admin_layouts')
@section('admin_content')

<style>
/* ===================== PAGE LAYOUT ===================== */
.label-form-panel {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.10);
    overflow: hidden;
    height: 100%;
}
.label-preview-panel-inner {
    position: sticky;
    top: 70px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.10);
    padding: 16px;
}

/* ===================== TABS ===================== */
.label-tabs {
    display: flex;
    border-bottom: 2px solid #e0e0e0;
    background: #f8f9fa;
}
.label-tab {
    padding: 11px 22px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    color: #555;
    transition: all 0.2s;
}
.label-tab.active {
    border-bottom-color: #1a73e8;
    color: #1a73e8;
    background: #fff;
}

/* ===================== FORM SECTIONS ===================== */
.form-section {
    padding: 18px 22px 8px;
    border-bottom: 1px solid #f0f0f0;
}
.form-section:last-of-type { border-bottom: none; }
.section-title {
    font-size: 14px;
    font-weight: 700;
    color: #222;
    margin-bottom: 4px;
}
.section-subtitle {
    font-size: 11px;
    color: #888;
    margin-bottom: 14px;
}
.check-row {
    display: flex;
    align-items: flex-start;
    margin-bottom: 10px;
}
.check-row input[type="checkbox"] {
    margin-top: 2px;
    margin-right: 9px;
    accent-color: #1a73e8;
    width: 15px;
    height: 15px;
    flex-shrink: 0;
    cursor: pointer;
}
.check-label {
    font-size: 13px;
    font-weight: 500;
    color: #333;
    line-height: 1.3;
}
.check-desc {
    font-size: 11px;
    color: #888;
    margin-top: 1px;
}
.grid-checks {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 16px;
}

/* ===================== VERIFY INPUTS ===================== */
.verify-group {
    display: flex;
    gap: 14px;
    margin-bottom: 12px;
}
.verify-item { flex: 1; }
.verify-item label {
    font-size: 11px;
    font-weight: 600;
    color: #555;
    margin-bottom: 4px;
    display: block;
}
.verify-input-wrap {
    display: flex;
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
}
.verify-input-wrap input {
    flex: 1;
    border: none;
    padding: 7px 10px;
    font-size: 12px;
    outline: none;
    min-width: 0;
}
.verify-badge {
    background: #2ecc71;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 0 10px;
    display: flex;
    align-items: center;
    gap: 3px;
    white-space: nowrap;
}

/* ===================== LOGO UPLOAD ===================== */
.logo-upload-row {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 12px;
}
.logo-upload-row .file-wrap {
    flex: 1;
    display: flex;
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
}
.logo-upload-row .file-wrap input[type=file] {
    flex: 1;
    padding: 6px 8px;
    font-size: 12px;
    border: none;
    outline: none;
}
.logo-upload-row .file-wrap .browse-btn {
    background: #6c757d;
    color: #fff;
    border: none;
    padding: 0 14px;
    font-size: 12px;
    cursor: pointer;
}
#preview-logo-thumb {
    width: 70px;
    height: 36px;
    object-fit: contain;
    border: 1px solid #ddd;
    border-radius: 5px;
    background: #fafafa;
}

/* ===================== NUMBER INPUTS ===================== */
.number-input-row { margin-bottom: 14px; }
.number-input-row label {
    font-size: 13px;
    font-weight: 600;
    color: #333;
    display: block;
    margin-bottom: 2px;
}
.number-input-row .sub {
    font-size: 11px;
    color: #888;
    margin-bottom: 5px;
    display: block;
}
.number-input-row input[type=number] {
    width: 100%;
    max-width: 200px;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 7px 12px;
    font-size: 13px;
    outline: none;
    transition: border 0.2s;
}
.number-input-row input[type=number]:focus { border-color: #1a73e8; }

/* ===================== SAVE BTN ===================== */
.save-btn-wrap { padding: 16px 22px 20px; }
.btn-save-setting {
    background: #1a1a2e;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 11px 32px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-save-setting:hover { background: #16213e; }

.lbl-hidden {
    display: none !important;
}.alert-success-custom {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
    padding: 10px 16px;
    border-radius: 7px;
    margin-bottom: 14px;
    font-size: 13px;
}
.alert-error-custom {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
    padding: 10px 16px;
    border-radius: 7px;
    margin-bottom: 14px;
    font-size: 13px;
}
.field-error {
    color: #dc3545;
    font-size: 11px;
    margin-top: 3px;
    display: block;
}

/* ===================== LABEL PREVIEW ===================== */
.preview-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.10);
    padding: 16px;
}
.preview-title {
    font-size: 12px;
    font-weight: 700;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
}

/* ===================== LABEL PREVIEW — mirrors print4x6.blade.php exactly ===================== */
#shipping-label {
    font-family: Arial, sans-serif;
    font-size: 11px;
    color: #111;
    background: #fff;
    box-sizing: border-box;
    width: 100%;
    max-width: 390px;
    border: 1px solid #000;
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 0;
    -webkit-print-color-adjust: exact;
}
#shipping-label .bc-row { text-align: center; flex-shrink: 0; }
#shipping-label .bc-row .courier-name { font-size:16px; font-weight:bold; text-transform:uppercase; letter-spacing:1px; margin-bottom:1px; }
#shipping-label .bc-row .awb-text   { font-size:10px; font-weight:bold; letter-spacing:1px; margin-bottom:2px; color:#222; }
#shipping-label .bc-row svg         { width:100%; height:45px; display:block; }
#shipping-label hr { border:none; border-top:1px solid #000; margin:4px 0; flex-shrink:0; }
#shipping-label .hdr-row { display:flex; justify-content:space-between; align-items:flex-start; flex-shrink:0; }
#shipping-label .hdr-left { font-size:9px; line-height:1.6; }
#shipping-label .hdr-right { text-align:right; line-height:1.5; white-space:nowrap; }
#shipping-label .hdr-right .payment-mode { font-weight:bold; font-size:12px; }
#shipping-label .hdr-right .amount      { font-weight:bold; font-size:17px; }
#shipping-label .address-box { border:1.5px solid #000; padding:5px 8px; font-size:10.5px; line-height:1.55; flex-shrink:0; margin-top:4px; }
#shipping-label .address-box .label-tag { font-size:7.5px; font-weight:bold; text-transform:uppercase; color:#555; margin-bottom:2px; }
#shipping-label .address-box .cust-name{ font-size:13px; font-weight:bold; margin-bottom:2px; }
#shipping-label .dim-row { display:flex; justify-content:flex-end; flex-shrink:0; margin-top:3px; }
#shipping-label .weight-text { font-size:8.5px; color:#333; }
#shipping-label table.products { width:100%; border-collapse:collapse; font-size:9px; flex-shrink:0; margin-top:4px; }
#shipping-label table.products th,
#shipping-label table.products td { border:1px solid #000; padding:3px 5px; }
#shipping-label table.products th { background:#e8e8e8; font-weight:bold; text-align:center; }
#shipping-label table.products td { text-align:left; }
#shipping-label .return-box { border:1px solid #000; padding:4px 6px; font-size:8px; line-height:1.4; flex-shrink:0; }
#shipping-label .return-box .label-tag { font-size:7px; font-weight:bold; text-transform:uppercase; color:#555; margin-bottom:1px; }
#shipping-label .footer-note { font-size:7.5px; color:#444; border-top:1px dashed #888; padding-top:3px; text-align:center; flex-shrink:0; margin-top:4px; }
.lbl-hidden { display: none !important; }
</style>

@php
    $s = $setting ?? null;
    $admin = auth()->guard('admin')->user();
    // Fetch KYC GST for the current logged in user (primary source)
    $kyc = \DB::table('profiles')->where('user_id', $admin->id)->first();
    $kyc_gst = $kyc->gst ?? '';
@endphp
<div class="container-fluid pb-4">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert-success-custom">✔ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error-custom">✖ {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error-custom">
            <strong>✖ Please fix the following errors:</strong>
            <ul style="margin:6px 0 0 16px;padding:0;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tabs --}}
    <div class="label-tabs mb-3" style="background:transparent;border:none;gap:0">
        <div class="label-tab active" style="background:#fff;border-radius:8px 8px 0 0;border:1px solid #e0e0e0;border-bottom:2px solid #1a73e8;margin-right:4px">My Label Setting</div>
    </div>

    <div class="row">

        {{-- ========== LEFT COL: FORM ========== --}}
        <div class="col-md-6" style="padding-right:8px;">
            <div class="label-form-panel">
            <form method="POST"
                  action="{{ route('admin.settings.labalsetting.save') }}"
                  enctype="multipart/form-data"
                  id="label-settings-form">
                @csrf

                {{-- PRIORITY VISIBILITY SETTINGS (TAX & GST) --}}
                <div class="form-section" style="background: #f8faff; border: 1px solid #e1eaff; border-radius: 8px; margin: 15px 22px 5px;">
                    <div class="section-title" style="color: #1a73e8; display: flex; align-items: center; gap: 8px;">
                        <span>⭐</span> Priority Visibility Settings (Tax & GST)
                    </div>
                    <div class="section-subtitle">Manage how tax and GST details appear on your labels</div>
                    <div class="grid-checks">
                        <div class="check-row">
                            <input type="checkbox" id="chk_show_gst" name="show_gst" value="1"
                                   {{ ($s && $s->show_gst) ? 'checked' : '' }}>
                            <div class="check-label" style="font-weight: 700;">Show Tax / GST Column</div>
                            <div class="check-desc">(Product table tax amount)</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_gst" name="hide_gst_number" value="1"
                                   {{ ($s && $s->hide_gst_number) ? 'checked' : '' }}>
                            <div class="check-label" style="font-weight: 700;">Hide My GSTIN Number</div>
                            <div class="check-desc">(Your seller GST number)</div>
                        </div>
                    </div>
                </div>

                {{-- SELLER DETAILS (WAREHOUSE) --}}
                <div class="form-section">
                    <div class="section-title">Seller Details (Warehouse)</div>
                    <div class="section-subtitle">Select the box to hide confidential seller/warehouse details on labels</div>
                    <div class="grid-checks">
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_pickup_addr" name="hide_pickup_address" value="1"
                                   {{ ($s && $s->hide_pickup_address) ? 'checked' : '' }}>
                            <div class="check-label">Hide Pickup Address</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_pickup_mobile" name="hide_pickup_mobile" value="1"
                                   {{ ($s && $s->hide_pickup_mobile) ? 'checked' : '' }}>
                            <div class="check-label">Hide Pickup Mobile Number</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_rto_addr" name="hide_rto_address" value="1"
                                   {{ ($s && $s->hide_rto_address) ? 'checked' : '' }}>
                            <div class="check-label">Hide RTO Address</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_rto_mobile" name="hide_rto_mobile" value="1"
                                   {{ ($s && $s->hide_rto_mobile) ? 'checked' : '' }}>
                            <div class="check-label">Hide RTO Mobile Number</div>
                        </div>

                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_pickup_contact" name="hide_pickup_contact_name" value="1"
                                   {{ ($s && $s->hide_pickup_contact_name) ? 'checked' : '' }}>
                            <div class="check-label">Hide Pickup Contact Name</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_rto_contact" name="hide_rto_contact_name" value="1"
                                   {{ ($s && $s->hide_rto_contact_name) ? 'checked' : '' }}>
                            <div class="check-label">Hide RTO Contact Name</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_pickup_name" name="hide_pickup_name" value="1"
                                   {{ ($s && $s->hide_pickup_name) ? 'checked' : '' }}>
                            <div class="check-label">Hide Pickup Name</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_rto_name" name="hide_rto_name" value="1"
                                   {{ ($s && $s->hide_rto_name) ? 'checked' : '' }}>
                            <div class="check-label">Hide RTO Name</div>
                        </div>
                    </div>
                </div>

                {{-- CUSTOMER DETAILS --}}
                <div class="form-section">
                    <div class="section-title">Customer Details</div>
                    <div class="section-subtitle">Select the box to hide customer specific details on labels</div>
                    <div class="grid-checks">
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_cust_name" name="hide_customer_name" value="1"
                                   {{ ($s && $s->hide_customer_name) ? 'checked' : '' }}>
                            <div class="check-label">Hide Customer Name</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_cust_address" name="hide_customer_address" value="1"
                                   {{ ($s && $s->hide_customer_address) ? 'checked' : '' }}>
                            <div class="check-label">Hide Customer Address</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_cust_mobile" name="hide_customer_mobile" value="1"
                                   {{ ($s && $s->hide_customer_mobile) ? 'checked' : '' }}>
                            <div class="check-label">Hide Customer Mobile</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_order_barcode" name="hide_order_barcode" value="1"
                                   {{ ($s && $s->hide_order_barcode) ? 'checked' : '' }}>
                            <div class="check-label">Hide Order Barcode</div>
                        </div>
                    </div>
                </div>

                {{-- COMMON SETTING --}}
                <div class="form-section">
                    <div class="section-title">Common Setting</div>

                    {{-- Printer Type --}}
                    <div class="number-input-row mt-2">
                        <label style="font-size:13px;font-weight:600;color:#333;margin-bottom:6px;display:block;">
                            Printer Type
                        </label>
                        <div style="display:flex;gap:20px;">
                            <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="printer_type" value="1"
                                       id="pt_thermal"
                                       style="accent-color:#1a73e8;width:15px;height:15px;cursor:pointer;"
                                       {{ ($s && $s->printer_type == 2) ? '' : 'checked' }}>
                                <span>🖨️ Thermal 4x6</span>
                            </label>
                            <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="printer_type" value="2"
                                       id="pt_a4"
                                       style="accent-color:#1a73e8;width:15px;height:15px;cursor:pointer;"
                                       {{ ($s && $s->printer_type == 2) ? 'checked' : '' }}>
                                <span>📄 Standard A4</span>
                            </label>
                        </div>
                    </div>

                    {{-- A4 Print Layout Options (Visible only when A4 is selected) --}}
                    <div id="a4_layout_options" style="{{ ($s && $s->printer_type == 2) ? '' : 'display:none;' }} margin-top:15px; padding-left: 10px; border-left: 3px solid #1a73e8;">
                        <label style="font-size:12px; font-weight:600; color:#555; margin-bottom:8px; display:block;">
                            A4 Print Layout
                        </label>
                        <div style="display:flex; flex-direction:column; gap:8px;">
                            <label style="display:flex; align-items:center; gap:8px; font-size:13px; cursor:pointer;">
                                <input type="radio" name="a4_print_option" value="0"
                                       {{ ($s && $s->a4_print_option == 1) ? '' : 'checked' }}
                                       style="accent-color:#1a73e8;width:14px;height:14px;cursor:pointer;">
                                <span>🔲 Only Label (4 per page)</span>
                            </label>
                            <label style="display:flex; align-items:center; gap:8px; font-size:13px; cursor:pointer;">
                                <input type="radio" name="a4_print_option" value="1"
                                       {{ ($s && $s->a4_print_option == 1) ? 'checked' : '' }}
                                       style="accent-color:#1a73e8;width:14px;height:14px;cursor:pointer;">
                                <span>📄 Label + Invoice (Combined)</span>
                            </label>
                        </div>
                    </div>



                    <div class="check-row mt-2">
                        <input type="checkbox" id="chk_channel_logo" name="logo_hidden" value="1"
                               {{ ($s && $s->logo_hidden) ? 'checked' : '' }}>
                        <div class="check-label" style="font-weight: 700;">Show Logo on Label</div>
                    </div>

                    {{-- Logo Upload --}}
                    <div id="logo-upload-section" style="{{ ($s && !$s->logo_hidden) ? 'opacity:0.4;pointer-events:none;' : '' }} margin-top:10px;">
                        <div class="check-desc mb-1" style="font-weight:600;color:#555;">Upload Logo (Desktop)</div>
                        <div class="logo-upload-row">
                            <div class="file-wrap">
                                <input type="file" id="logo_file" name="logo" accept="image/*">
                                <button class="browse-btn" type="button" onclick="document.getElementById('logo_file').click()">Browse</button>
                            </div>
                            @if($s && $s->logo)
                                <img id="preview-logo-thumb"
                                     src=""{{ $s && $s->logo ? asset('public/uploads/' . $s->logo) : asset('uploads/default.png') }}""
                                     alt="logo preview"
                                     style="display:block;width:70px;height:36px;object-fit:contain;border:1px solid #ddd;border-radius:5px;">
                            @else
                                <img id="preview-logo-thumb" src="" alt="logo preview" style="display:none;width:70px;height:36px;object-fit:contain;border:1px solid #ddd;border-radius:5px;">
                            @endif
                        </div>
                        @error('logo')
                            <span class="field-error">⚠ {{ $message }}</span>
                        @enderror
                    </div>

                    <div class="verify-group mt-3">
                        <div class="verify-item">
                            <label>Enter Email Id</label>
                            <div class="verify-input-wrap">
                                <input type="email" id="inp_email" name="email"
                                       value="{{ old('email', $s->email ?? '') }}">
                                <div class="verify-badge">✔ Verified</div>
                            </div>
                            @error('email')
                                <span class="field-error">⚠ {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="verify-item">
                            <label>Enter Mobile No</label>
                            <div class="verify-input-wrap">
                                <input type="text" id="inp_mobile" name="mobile"
                                       value="{{ old('mobile', $s->mobile ?? '') }}">
                                <div class="verify-badge">✔ Verified</div>
                            </div>
                            @error('mobile')
                                <span class="field-error">⚠ {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>



                {{-- HIDE/SHOW PRODUCT DETAILS --}}
                <div class="form-section">
                    <div class="section-title">Hide/Show Product Details</div>
                    <div class="section-subtitle">Select the box to hide product details on labels</div>
                    <div class="grid-checks">
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_sku" name="hide_sku" value="1"
                                   {{ ($s && $s->hide_sku) ? 'checked' : '' }}>
                            <div class="check-label">Hide SKU</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_show_hsn" name="show_hsn" value="1"
                                   {{ ($s && $s->show_hsn) ? 'checked' : '' }}>
                            <div class="check-label">Show HSN</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_product" name="hide_product" value="1"
                                   {{ ($s && $s->hide_product) ? 'checked' : '' }}>
                            <div class="check-label">Hide Product</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_qty" name="hide_qty" value="1"
                                   {{ ($s && $s->hide_qty) ? 'checked' : '' }}>
                            <div class="check-label">Hide QTY</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_total_amt" name="hide_total_amount" value="1"
                                   {{ ($s && $s->hide_total_amount) ? 'checked' : '' }}>
                            <div class="check-label">Hide Total Amount</div>
                        </div>
                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_discount" name="hide_discount_amount" value="1"
                                   {{ ($s && $s->hide_discount_amount) ? 'checked' : '' }}>
                            <div class="check-label">Hide Discount Amount</div>
                        </div>

                        <div class="check-row">
                            <input type="checkbox" id="chk_hide_shipping_charges" name="hide_shipping_charges" value="1"
                                   {{ ($s && $s->hide_shipping_charges) ? 'checked' : '' }}>
                            <div class="check-label">Hide Shipping Charges</div>
                        </div>
                    </div>

                    <div class="check-row mt-1">
                        <input type="checkbox" id="chk_hide_cod_amount" name="hide_order_amount" value="1"
                               {{ ($s && $s->hide_order_amount) ? 'checked' : '' }}>
                        <div>
                            <div class="check-label">Hide Order Amount/Collectable Amount</div>
                            <div class="check-desc">Select the box to hide the order (COD/Prepaid) value on labels</div>
                        </div>
                    </div>
                    
                    <div class="check-row mt-1">
                        <input type="checkbox" id="chk_hide_invoice_number" name="hide_invoice_number" value="1"
                               {{ ($s && $s->hide_invoice_number) ? 'checked' : '' }}>
                        <div>
                            <div class="check-label">Hide Invoice Number</div>
                            <div class="check-desc">Select the box to hide the order invoice number on labels</div>
                        </div>
                    </div>
                </div>

                {{-- SAVE --}}
                <div class="save-btn-wrap">
                    <button class="btn-save-setting" type="submit">Save Setting</button>
                </div>

            </form>{{-- end form --}}
            </div>{{-- .label-form-panel --}}
        </div>{{-- col-md-6 left --}}

        {{-- ========== RIGHT COL: LIVE PREVIEW ========== --}}
        <div class="col-md-6" style="padding-left:8px;">
            <div class="label-preview-panel-inner">
                <div class="preview-title">📦 Live Label Preview <small style="font-weight:400;color:#aaa;font-size:10px;">(exact replica of printed label)</small></div>

                <div id="shipping-label">

                    {{-- COURIER NAME + LOGO HEADER --}}
                    <div class="bc-row" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">
                        <div style="text-align:left;flex:1;">
                            <div class="courier-name">COURIER NAME</div>
                            <div class="awb-text">AWB1234567890</div>
                        </div>
                        <div id="lbl-logo-img-wrap" class="{{ ($s && $s->logo && $s->logo_hidden) ? '' : 'lbl-hidden' }}" style="flex:0 0 auto;text-align:right;">
                            <img id="lbl-logo-img"
                                 src="{{ $s && $s->logo ? asset('uploads/' . $s->logo) : asset('uploads/default.png') }}"
                                 alt="Logo"
                                 style="max-height:40px;max-width:120px;object-fit:contain;display:block;">
                        </div>
                    </div>

                    {{-- AWB BARCODE --}}
                    <div class="bc-row">
                        <svg width="100%" height="45" viewBox="0 0 200 45">
                            <rect x="0"  y="0" width="3" height="45" fill="#111"/><rect x="5"  y="0" width="2" height="45" fill="#111"/>
                            <rect x="10" y="0" width="4" height="45" fill="#111"/><rect x="16" y="0" width="1" height="45" fill="#111"/>
                            <rect x="20" y="0" width="3" height="45" fill="#111"/><rect x="25" y="0" width="2" height="45" fill="#111"/>
                            <rect x="30" y="0" width="1" height="45" fill="#111"/><rect x="34" y="0" width="4" height="45" fill="#111"/>
                            <rect x="40" y="0" width="2" height="45" fill="#111"/><rect x="45" y="0" width="1" height="45" fill="#111"/>
                            <rect x="48" y="0" width="3" height="45" fill="#111"/><rect x="53" y="0" width="2" height="45" fill="#111"/>
                            <rect x="57" y="0" width="1" height="45" fill="#111"/><rect x="61" y="0" width="4" height="45" fill="#111"/>
                            <rect x="67" y="0" width="1" height="45" fill="#111"/><rect x="71" y="0" width="3" height="45" fill="#111"/>
                            <rect x="76" y="0" width="2" height="45" fill="#111"/><rect x="80" y="0" width="1" height="45" fill="#111"/>
                            <rect x="83" y="0" width="4" height="45" fill="#111"/><rect x="89" y="0" width="2" height="45" fill="#111"/>
                            <rect x="94" y="0" width="3" height="45" fill="#111"/><rect x="99" y="0" width="1" height="45" fill="#111"/>
                            <rect x="103" y="0" width="3" height="45" fill="#111"/><rect x="108" y="0" width="2" height="45" fill="#111"/>
                            <rect x="113" y="0" width="4" height="45" fill="#111"/><rect x="119" y="0" width="1" height="45" fill="#111"/>
                            <rect x="123" y="0" width="2" height="45" fill="#111"/><rect x="127" y="0" width="3" height="45" fill="#111"/>
                            <rect x="132" y="0" width="1" height="45" fill="#111"/><rect x="136" y="0" width="4" height="45" fill="#111"/>
                            <rect x="142" y="0" width="2" height="45" fill="#111"/><rect x="147" y="0" width="1" height="45" fill="#111"/>
                            <rect x="151" y="0" width="3" height="45" fill="#111"/><rect x="156" y="0" width="2" height="45" fill="#111"/>
                            <rect x="161" y="0" width="4" height="45" fill="#111"/><rect x="167" y="0" width="1" height="45" fill="#111"/>
                            <rect x="171" y="0" width="2" height="45" fill="#111"/><rect x="175" y="0" width="3" height="45" fill="#111"/>
                            <rect x="180" y="0" width="1" height="45" fill="#111"/><rect x="184" y="0" width="2" height="45" fill="#111"/>
                            <rect x="188" y="0" width="4" height="45" fill="#111"/><rect x="194" y="0" width="2" height="45" fill="#111"/>
                            <rect x="198" y="0" width="2" height="45" fill="#111"/>
                        </svg>
                    </div>

                    <hr>

                    {{-- PICKUP & RTO ADDRESSES (Moved to Top Section in Label) --}}
                    <div id="lbl-pickup-rto-section" style="display:flex;gap:4px;margin-bottom:5px;">
                        <div id="lbl-pickup-section" class="preview-address-box return-box" style="flex:1;">
                            <div class="label-tag">Pickup Address</div>
                            <div id="lbl-pickup-name" style="font-weight:bold;">Pickup Warehouse Name</div>
                            <div id="lbl-pickup-contact">Attn: Warehouse Manager</div>
                            Test 234 45678 gurugram, Haryana, India - 122001
                            <div id="lbl-pickup-mobile">Ph: 9999999999</div>
                            <div id="lbl-pickup-gst-section" class="{{ ($s && $s->hide_gst_number) ? 'lbl-hidden' : '' }}">
                                <b>GSTIN:</b> <span class="preview-gst-val">{{ $kyc_gst }}</span>
                            </div>
                        </div>
                        <div id="lbl-rto-section" class="preview-address-box return-box" style="flex:1;">
                            <div class="label-tag">RTO Address</div>
                            <div id="lbl-rto-name" style="font-weight:bold;">Return Warehouse Name</div>
                            <div id="lbl-rto-contact">Attn: RTO Manager</div>
                            Test 234 45678 gurugram, Haryana, India - 122001
                            <div id="lbl-rto-mobile">Ph: 9999999999</div>
                        </div>
                    </div>

                    <hr>

                    {{-- ORDER INFO + COD AMOUNT (mirrors hdr-row in print4x6) --}}
                    <div class="hdr-row">
                        <div class="hdr-left">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div>
                                    <b>Order #:</b> ORDER_ID<br>
                                    <div id="lbl-invoice-number"><b>Invoice:</b> INVOICE_NO</div>
                                    <b>Date:</b> DD MMM YYYY<br>
                                    <b>Wt:</b> 0.000 kg
                                </div>
                                <div id="lbl-order-barcode">
                                    <svg width="70" height="18" viewBox="0 0 70 18">
                                        <rect x="0" y="0" width="2" height="18" fill="#111"/><rect x="4" y="0" width="1" height="18" fill="#111"/>
                                        <rect x="7" y="0" width="3" height="18" fill="#111"/><rect x="12" y="0" width="2" height="18" fill="#111"/>
                                        <rect x="17" y="0" width="1" height="18" fill="#111"/><rect x="21" y="0" width="3" height="18" fill="#111"/>
                                        <rect x="26" y="0" width="2" height="18" fill="#111"/><rect x="30" y="0" width="1" height="18" fill="#111"/>
                                        <rect x="34" y="0" width="3" height="18" fill="#111"/><rect x="39" y="0" width="2" height="18" fill="#111"/>
                                        <rect x="43" y="0" width="1" height="18" fill="#111"/><rect x="47" y="0" width="3" height="18" fill="#111"/>
                                        <rect x="52" y="0" width="2" height="18" fill="#111"/><rect x="56" y="0" width="1" height="18" fill="#111"/>
                                        <rect x="60" y="0" width="3" height="18" fill="#111"/><rect x="65" y="0" width="2" height="18" fill="#111"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="hdr-right">
                            <div class="payment-mode">COD</div>
                            <div class="amount" id="lbl-cod-amount">₹1,500.00</div>
                            <div id="lbl-gst-number-section" class="{{ ($s && $s->hide_gst_number) ? 'lbl-hidden' : '' }}">
                                <div style="font-size: 8px; margin-top: 2px;">GSTIN: <span id="lbl-gst-val">{{ $kyc_gst }}</span></div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- SHIPPING ADDRESS (mirrors address-box in print4x6) --}}
                    <div class="address-box">
                        <div class="label-tag">Shipping Address</div>
                        <div id="lbl-cust-name" class="cust-name">CUSTOMER NAME</div>
                        <div id="lbl-cust-address">
                            Customer Street Address, Building/Flat No,<br>
                            City, State, Country<br>
                            PIN: 000000
                        </div>
                        <span id="lbl-cust-mobile"><br>📞 98XXXXXXXX</span>
                    </div>

                    {{-- DIMENSIONS --}}
                    <div class="dim-row">
                        <span class="weight-text">Dim: 10×10×10 cm</span>
                    </div>

                    {{-- PRODUCTS TABLE (mirrors table.products in print4x6) --}}
                    <div id="lbl-products-section">
                        <table class="products" id="lbl-prod-table">
                            <thead>
                                <tr>
                                    <th style="width:auto" id="lbl-th-product">Product / SKU</th>
                                    <th style="width:40px" id="lbl-th-qty">Qty</th>
                                    <th style="width:50px" id="lbl-th-hsn" class="lbl-hidden">HSN</th>
                                    <th style="width:50px" id="lbl-th-gst" class="lbl-hidden">Tax</th>
                                    <th style="width:70px" id="lbl-th-total">Total</th>
                                </tr>
                            </thead>
                            <tbody id="lbl-prod-body"></tbody>
                        </table>
                        {{-- summary rows match print4x6 grand-total section --}}
                        <div id="lbl-summary-wrap" style="margin-top:3px;">
                            <table style="width:100%;border-collapse:collapse;font-size:9px;">
                                <tr id="lbl-shipping-row">
                                    <td style="text-align:right;">Shipping</td>
                                    <td style="text-align:right;border:1px solid #000;padding:2px 5px;">₹50.00</td>
                                </tr>
                                <tr id="lbl-discount-row">
                                    <td style="text-align:right;">Discount</td>
                                    <td style="text-align:right;border:1px solid #000;padding:2px 5px;">-₹100.00</td>
                                </tr>
                                <tr id="lbl-grand-total-row">
                                    <td style="text-align:right;"><b>Grand Total</b></td>
                                    <td style="text-align:right;border:1px solid #000;padding:2px 5px;"><b>₹1,500.00</b></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Summary Rows --}}
                    <hr>

                    {{-- FOOTER (mirrors footer-note in print4x6) --}}
                    <div class="footer-note" id="lbl-footer">
                        <span id="lbl-footer-contact"><b>Contact:</b> <span id="lbl-mobile-val">{{ $s->mobile ?? 'N/A' }}</span> | <span id="lbl-email-val">{{ $s->email ?? 'N/A' }}</span> |</span>
                        Please unbox with video. No unboxing video = no liability for damage/missing items.
                    </div>

                </div>{{-- #shipping-label --}}
            </div>{{-- .label-preview-panel-inner --}}
        </div>{{-- col-md-6 right --}}


    </div>{{-- .row --}}
</div>


<script>
"use strict";
(function () {

    // ─── SAMPLE PRODUCTS ───────────────────────────────────────
    var fullProducts = [
        { sku: 'MOBILE01',  name: 'Mobile',  qty: 1, total: 10000, hsn: '6201', gst: '18%' },
        { sku: 'LAPTOP02',  name: 'Laptop',  qty: 1, total: 30000, hsn: '8471', gst: '18%' },
        { sku: 'TABLET03',  name: 'Tablet',  qty: 2, total: 8000,  hsn: '8471', gst: '12%' },
        { sku: 'CHARGER04', name: 'Charger', qty: 1, total: 500,   hsn: '8504', gst: '18%' },
        { sku: 'CASE05',    name: 'Case',    qty: 3, total: 1200,  hsn: '4202', gst: '12%' },
    ];

    function trimText(str, len) {
        if (!len || len <= 0) return str;
        return str.length > len ? str.substring(0, len) + '...' : str;
    }
        function show(el) { el && el.classList.remove('lbl-hidden'); }
        function hide(el) { el && el.classList.add('lbl-hidden'); }
        function chk(id)  { var el = document.getElementById(id); return el ? el.checked : false; }

        // ─── MAIN UPDATE FUNCTION ────────────────────────────────────
        function updatePreview() {
            // --- PRINTER TYPE ---
            var ptThermal = document.getElementById('pt_thermal');
            var isThermal = ptThermal && ptThermal.checked;
            var label = document.getElementById('shipping-label');
            // Thermal = narrow (4x6), A4 = wider
            if (isThermal) {
                label.style.maxWidth = '390px';
                label.style.fontSize = '11px';
            } else {
                label.style.maxWidth = '100%';
                label.style.fontSize = '11px';
            }

            // --- GST ---
            var hideGst = chk('chk_hide_gst');
            var gstSection = document.getElementById('lbl-gst-number-section');
            var pickupGstSection = document.getElementById('lbl-pickup-gst-section');
            if (hideGst) {
                hide(gstSection);
                hide(pickupGstSection);
            } else {
                show(gstSection);
                show(pickupGstSection);
            }

        // showLogo variable initialization is handled below using chk_channel_logo
        var hideCustName   = chk('chk_hide_cust_name');
        var hideCustAddr   = chk('chk_hide_cust_address');
        var hideCustMob    = chk('chk_hide_cust_mobile');
        var hideBarcode    = chk('chk_hide_order_barcode');
        var hideCod        = chk('chk_hide_cod_amount');
        var hideInvoice    = chk('chk_hide_invoice_number');
        var hidePickupAddr = chk('chk_hide_pickup_addr');
        var hidePickupMob  = chk('chk_hide_pickup_mobile');
        var hidePickupContact = chk('chk_hide_pickup_contact');
        var hidePickupName = chk('chk_hide_pickup_name');
        // var hideGst        = chk('chk_hide_gst'); // already handled above
        var hideRtoAddr    = chk('chk_hide_rto_addr');
        var hideRtoMob     = chk('chk_hide_rto_mobile');
        var hideRtoContact = chk('chk_hide_rto_contact');
        var hideRtoName    = chk('chk_hide_rto_name');
        var hideSku        = chk('chk_hide_sku');
        var showHsn        = chk('chk_show_hsn');
        var hideProduct    = chk('chk_hide_product');
        var hideQty        = chk('chk_hide_qty');
        var hideTotalAmt   = chk('chk_hide_total_amt');
        var hideDiscount   = chk('chk_hide_discount');
        var showGst        = chk('chk_show_gst');
        var hideShipping   = chk('chk_hide_shipping_charges');
        var email  = document.getElementById('inp_email').value;
        var mobile = document.getElementById('inp_mobile').value;

        // --- LOGO ---
        var showLogo = chk('chk_channel_logo');
        var lblLogoImgWrap = document.getElementById('lbl-logo-img-wrap');
        var logoUploadSection = document.getElementById('logo-upload-section');
        
        if (showLogo) {
            show(lblLogoImgWrap);
            logoUploadSection.style.opacity = '1';
            logoUploadSection.style.pointerEvents = 'auto';
        } else {
            hide(lblLogoImgWrap);
            logoUploadSection.style.opacity = '0.4';
            logoUploadSection.style.pointerEvents = 'none';
        }

        // --- A4 PRINT OPTION VISIBILITY ---
        var isA4 = chk('pt_a4');
        var a4Options = document.getElementById('a4_layout_options');
        if (isA4) {
            show(a4Options);
        } else {
            hide(a4Options);
        }

        // --- CUSTOMER DETAILS ---
        hideCustName ? hide(document.getElementById('lbl-cust-name'))
                     : show(document.getElementById('lbl-cust-name'));
        hideCustAddr ? hide(document.getElementById('lbl-cust-address'))
                     : show(document.getElementById('lbl-cust-address'));
        hideCustMob ? hide(document.getElementById('lbl-cust-mobile'))
                    : show(document.getElementById('lbl-cust-mobile'));

        // --- ORDER BARCODE (small barcode beside order info) ---
        hideBarcode ? hide(document.getElementById('lbl-order-barcode'))
                    : show(document.getElementById('lbl-order-barcode'));

        // --- COD / ORDER AMOUNT ---
        hideCod ? hide(document.getElementById('lbl-cod-amount'))
                : show(document.getElementById('lbl-cod-amount'));

        // --- INVOICE NUMBER ---
        hideInvoice ? hide(document.getElementById('lbl-invoice-number'))
                    : show(document.getElementById('lbl-invoice-number'));

        // --- GST NUMBER ---
        hideGst ? hide(document.getElementById('lbl-gst-number'))
                : show(document.getElementById('lbl-gst-number'));

        // --- PICKUP ADDRESS BLOCK ---
        var pickupSection = document.getElementById('lbl-pickup-section');
        if (hidePickupAddr) {
            hide(pickupSection);
        } else {
            show(pickupSection);
            hidePickupName    ? hide(document.getElementById('lbl-pickup-name'))    : show(document.getElementById('lbl-pickup-name'));
            hidePickupContact ? hide(document.getElementById('lbl-pickup-contact')) : show(document.getElementById('lbl-pickup-contact'));
            hidePickupMob     ? hide(document.getElementById('lbl-pickup-mobile'))  : show(document.getElementById('lbl-pickup-mobile'));
        }

        // --- RTO ADDRESS BLOCK ---
        var rtoSection = document.getElementById('lbl-rto-section');
        if (hideRtoAddr) {
            hide(rtoSection);
        } else {
            show(rtoSection);
            hideRtoName    ? hide(document.getElementById('lbl-rto-name'))    : show(document.getElementById('lbl-rto-name'));
            hideRtoContact ? hide(document.getElementById('lbl-rto-contact')) : show(document.getElementById('lbl-rto-contact'));
            hideRtoMob     ? hide(document.getElementById('lbl-rto-mobile'))  : show(document.getElementById('lbl-rto-mobile'));
        }

        // --- PRODUCTS TABLE (same columns as print4x6) ---
        // header columns
        hideProduct  ? hide(document.getElementById('lbl-th-product')) : show(document.getElementById('lbl-th-product'));
        hideQty      ? hide(document.getElementById('lbl-th-qty'))     : show(document.getElementById('lbl-th-qty'));
        hideTotalAmt ? hide(document.getElementById('lbl-th-total'))   : show(document.getElementById('lbl-th-total'));
        showHsn      ? show(document.getElementById('lbl-th-hsn'))     : hide(document.getElementById('lbl-th-hsn'));
        showGst      ? show(document.getElementById('lbl-th-gst'))     : hide(document.getElementById('lbl-th-gst'));

        // product rows
        var tbody = document.getElementById('lbl-prod-body');
        tbody.innerHTML = '';

        // hide entire products section if hide_product is checked
        hideProduct ? hide(document.getElementById('lbl-products-section'))
                    : show(document.getElementById('lbl-products-section'));

        if (!hideProduct) {
            var visible   = fullProducts.slice(0, 2);
            var moreCount = fullProducts.length - 2;

            // helper: format number like PHP number_format(x, 2)
            function fmt(n) {
                return '₹' + Number(n).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});
            }

            visible.forEach(function(p) {
                var tr = document.createElement('tr');
                var addTd = function(text, align) {
                    var td = document.createElement('td');
                    td.textContent = text;
                    if (align) td.style.textAlign = align;
                    tr.appendChild(td);
                };
                // Product / SKU column (name + optional SKU like print4x6)
                if (!hideProduct) {
                    var td = document.createElement('td');
                    td.textContent = p.name;
                    if (!hideSku) {
                        var small = document.createElement('small');
                        small.textContent = '[' + p.sku + ']';
                        small.style.cssText = 'color:#666;display:block;';
                        td.appendChild(small);
                    }
                    tr.appendChild(td);
                }
                if (!hideQty)     addTd(p.qty, 'center');
                if (showHsn)      addTd(p.hsn, 'center');
                if (showGst)      addTd(p.gst, 'right');
                if (!hideTotalAmt) addTd(fmt(p.total), 'right');
                tbody.appendChild(tr);
            });

            if (moreCount > 0) {
                var colCount = (!hideProduct?1:0)+(!hideQty?1:0)+(showHsn?1:0)+(showGst?1:0)+(!hideTotalAmt?1:0);
                var tr2 = document.createElement('tr');
                var td2 = document.createElement('td');
                td2.setAttribute('colspan', Math.max(colCount, 1));
                td2.style.cssText = 'text-align:center;color:#555;';
                td2.textContent = '+' + moreCount + ' More Products';
                tr2.appendChild(td2); tbody.appendChild(tr2);
            }
        }

        // --- SUMMARY ROWS (shipping / discount / grand total) ---
        var summaryWrap = document.getElementById('lbl-summary-wrap');
        
        // Always show the wrap if any of them are visible
        show(summaryWrap);

        // Shipping Row Condition
        hideShipping ? hide(document.getElementById('lbl-shipping-row'))
                     : show(document.getElementById('lbl-shipping-row'));
        
        // Discount Row Condition
        hideDiscount ? hide(document.getElementById('lbl-discount-row'))
                     : show(document.getElementById('lbl-discount-row'));

        // Grand Total Row Condition (Hides based on hideTotalAmt)
        hideTotalAmt ? hide(document.getElementById('lbl-grand-total-row'))
                     : show(document.getElementById('lbl-grand-total-row'));
        
        // If all three are hidden, hide the whole wrap
        if (hideShipping && hideDiscount && hideTotalAmt) {
            hide(summaryWrap);
        }

        // --- FOOTER ---
        document.getElementById('lbl-email-val').textContent  = email  || 'N/A';
        document.getElementById('lbl-mobile-val').textContent = mobile || 'N/A';
        // hide footer contact span if both empty
        var footContact = document.getElementById('lbl-footer-contact');
        (!mobile && !email) ? hide(footContact) : show(footContact);
    }

    // ─── LOGO FILE UPLOAD ────────────────────────────────────────
    document.getElementById('logo_file').addEventListener('change', function () {
        var file = this.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            var thumb = document.getElementById('preview-logo-thumb');
            thumb.src = e.target.result;
            thumb.style.display = 'block';
            document.getElementById('lbl-logo-img').src = e.target.result;
            updatePreview();
        };
        reader.readAsDataURL(file);
    });


    // ─── ATTACH LISTENERS ────────────────────────────────────────
    [
        'chk_hide_cust_name','chk_hide_cust_address','chk_hide_cust_mobile','chk_hide_order_barcode',
        'chk_hide_cod_amount','chk_hide_invoice_number',
        'chk_channel_logo',
        'chk_hide_pickup_addr','chk_hide_pickup_mobile','chk_hide_pickup_contact','chk_hide_pickup_name',
        'chk_hide_rto_addr','chk_hide_rto_mobile','chk_hide_rto_contact','chk_hide_rto_name',
        'chk_hide_gst',
        'chk_hide_sku','chk_show_hsn','chk_hide_product','chk_hide_qty',
        'chk_hide_total_amt','chk_hide_discount','chk_show_gst','chk_hide_shipping_charges',
        'inp_email','inp_mobile'
    ].forEach(function(id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('change', updatePreview);
        el.addEventListener('input',  updatePreview);
    });

    // ─── PRINTER TYPE LISTENERS ───────────────────────────────────
    ['pt_thermal', 'pt_a4'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('change', updatePreview);
    });

    // ─── INIT on page load ───────────────────────────────────────
    updatePreview();

})();
</script>

@endsection
