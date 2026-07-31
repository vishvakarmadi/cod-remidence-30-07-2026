@extends('admin.admin_layouts')
@section('admin_content')
    @php
        $session = Auth::guard('admin')->user();
    @endphp
    <style>
        ul.a {
            list-style-type: circle;
        }

        .container {
            max-width: 900px;
            width: 100%;
            background-color: #fff;
            margin: auto;
            padding: 15px;
            box-shadow: 0 2px 20px #0001, 0 1px 6px #0001;
            border-radius: 5px;
            overflow-x: auto;
        }

        ._table {
            width: 100%;
            border-collapse: collapse;
        }

        ._table :is(th, td) {
            border: 1px solid #0002;
            padding: 8px 10px;
        }

        /* form field design start */
        .form_control {
            border: 1px solid #0002;
            background-color: transparent;
            outline: none;
            padding: 8px 12px;
            font-family: 1.2rem;
            width: 100%;
            color: #333;
            font-family: Arial, Helvetica, sans-serif;
            transition: 0.3s ease-in-out;
        }

        .form_control::placeholder {
            color: inherit;
            opacity: 0.5;
        }

        .form_control:is(:focus, :hover) {
            box-shadow: inset 0 1px 6px #0002;
        }

        /* form field design end */


        .success {
            background-color: #24b96f !important;
        }

        .warning {
            background-color: #ebba33 !important;
        }

        .primary {
            background-color: #259dff !important;
        }

        .secondery {
            background-color: #00bcd4 !important;
        }

        .danger {
            background-color: #ff5722 !important;
        }

        .action_container {
            display: inline-flex;
        }

        .action_container>* {
            border: none;
            outline: none;
            color: #fff;
            text-decoration: none;
            display: inline-block;
            padding: 8px 14px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        .action_container>*+* {
            border-left: 1px solid #fff5;
        }

        .action_container>*:hover {
            filter: hue-rotate(-20deg) brightness(0.97);
            transform: scale(1.05);
            border-color: transparent;
            box-shadow: 0 2px 10px #0004;
            border-radius: 2px;
        }

        .action_container>*:active {
            transition: unset;
            transform: scale(.95);
        }

        @media only screen and (max-width: 600px) {
            .responsive-iframe {
                top: 0;
                left: 0;
                bottom: 0;
                right: 0;
                width: 100%;
                height: 200px;
                border: none;
            }

            .rounded-circle {
                width: 48px !important;
            }

            ul {
                line-height: 162%;
                width: 220px;
                font-size: 11px;
            }
        }
    </style>


    <div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-8 col-sm-12">
                <h2>Order Edit</h2>
            </div>
            <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                <ul class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <form id="form_submit" action="{{ route('admin.order.update',$order->id) }}" method="POST">
            @csrf
            <div class="col-12">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Customer Shipping Address</h5>
                         @if ($order->reverse_order =='1') 
                            <span style="color:red">
                                This is a Reverse order, shipping address will be treated as pickup address
                            </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4 ">
                                <label>First Name</label><span class="required"> *</span>
                                <input class="form-control" type="text" name="ship_fname" required value="{{ $order->ship_fname }}">
                                <input class="form-control" type="hidden" name="order_id" required value="{{ $order->order_id }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Last Name</label>
                                <!--<span class="required"> *</span>-->
                                <input class="form-control" type="text" name="ship_lname"  value="{{ $order->ship_lname }}">
                            </div>
<!--                            <div class="form-group col-md-4">
                                <label>Email </label>-->
                                <input class="d-none" type="email" name="ship_email" value="{{ $order->ship_email }}" >
                            <!--</div>-->
                            <div class="form-group col-md-4">
                                <label>Company </label>
                                <input class="form-control" type="text" name="ship_company" value="{{ $order->ship_company }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Phone</label><span class="required"> *</span>
                                <input class="form-control phone-number" type="text" name="ship_phone" required value="{{ $order->ship_phone }}" max="10">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Address</label><span class="required"> *</span>
                                <textarea class="form-control" name="ship_address" required>{{ $order->ship_address }}</textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Address</label>
                                <textarea class="form-control" name="ship_address_2">{{ $order->ship_address_2 }}</textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Country</label><span class="required"> *</span>
                                <select class="form-control" name="ship_country" required>
                                    <option value="">Select Country</option>
                                    @foreach ($counrtries as $country)
                                        <option value="{{ $country->id }}" @if($order->ship_country == $country->id) selected @endif>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Zip/postal code</label><span class="required"> *</span>
                                <input class="form-control" type="number" id="pncd" name="ship_pincode" required value="{{ rtrim($order->ship_pincode) }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>City </label><span class="required"> *</span>
                                <input class="form-control" type="text" id="cityid" name="ship_city" required value="{{ $order->ship_city }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>State/province </label><span class="required"> *</span>
                                <input class="form-control" type="text" id="stateid" name="ship_state" required value="{{ $order->ship_state }}">
                            </div>
<!--                            <div class="form-group col-md-4">
                                <label>Latitude </label>-->
                                <input class="form-control  d-none" type="text" name="ship_latitude" value="{{ $order->ship_latitude }}">
                            <!--</div>-->
<!--                            <div class="form-group col-md-4">
                                <label>Longitude </label>-->
                                <input class="form-control d-none" type="text" name="ship_longitude" value="{{ $order->ship_longitude }}">
                            <!--</div>-->
                            <div class="form-group col-md-4">
                                <label>GSTIN </label>
                                <input class="form-control maxlenght" type="text" name="ship_gstin" value="{{ $order->ship_gstin }}" max="15">
                            </div>
                            <div class="form-group col-md-4">
                                <label>e-Way Bill No </label>
                                <input class="form-control" type="text" name="e_bill_no" value="{{ $order->e_bill_no }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Billing address same as Shipping address</label>
                                <div style="display:flex;gap:8px;margin-top:6px;">
                                    <button type="button" id="btn_bill_yes" onclick="toggleBilling(1)" style="padding:6px 18px;border-radius:4px;border:2px solid {{ $order->same_add == 1 ? '#26a65b' : '#ccc' }};background:{{ $order->same_add == 1 ? '#26a65b' : '#fff' }};color:{{ $order->same_add == 1 ? '#fff' : '#333' }};cursor:pointer;font-size:14px;">&#10004; Yes</button>
                                    <button type="button" id="btn_bill_no" onclick="toggleBilling(0)" style="padding:6px 18px;border-radius:4px;border:2px solid {{ $order->same_add == 0 ? '#e74c3c' : '#ccc' }};background:{{ $order->same_add == 0 ? '#e74c3c' : '#fff' }};color:{{ $order->same_add == 0 ? '#fff' : '#333' }};cursor:pointer;font-size:14px;">&#10008; No</button>
                                </div>
                                <input type="hidden" name="same_add" id="same_add_val" value="{{ $order->same_add }}">
                            </div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="bill_address">
                @if($order->same_add == 0)
                <div class="col-12" id="bill_remove">
                    <div class="card pt-30">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Customer Billing Address</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4 ">
                                    <label>First Name</label><span class="required"> *</span>
                                    <input class="form-control" type="text" name="bill_fname" required value="{{ $order->bill_fname }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Last Name</label>
                                    <input class="form-control" type="text" name="bill_lname"  value="{{ $order->bill_lname }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Company </label>
                                    <input class="form-control" type="text" name="bill_company" value="{{ $order->bill_company }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Phone</label><span class="required"> *</span>
                                    <input class="form-control" type="text" name="bill_phone" required value="{{ $order->bill_phone }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Address</label><span class="required"> *</span>
                                    <textarea class="form-control" name="bill_address" required value="">{{ $order->bill_address }}</textarea>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Address</label>
                                    <textarea class="form-control" name="bill_address_2" value="">{{ $order->bill_address_2 }}</textarea>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Country</label><span class="required"> *</span>
                                    <select class="form-control" name="bill_country" required>
                                        <option value="">Select Country</option>
                                        @foreach ($counrtries as $country)
                                        <option value="{{ $country->id }}" @if($order->bill_country == $country->id) selected @endif>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Zip/postal code</label><span class="required"> *</span>
                                    <input class="form-control" type="number" name="bill_pincode" required value="{{ $order->bill_pincode }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>City </label><span class="required"> *</span>
                                    <input class="form-control" type="text" name="bill_city" value="{{ $order->bill_city }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>State/province </label><span class="required"> *</span>
                                    <input class="form-control" type="text" name="bill_state" value="{{ $order->bill_state }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Latitude </label>
                                    <input class="form-control" type="text" name="bill_latitude" value="{{ $order->bill_latitude }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Longitude </label>
                                    <input class="form-control" type="text" name="bill_longitude" value="{{ $order->bill_longitude }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>GSTIN </label>
                                    <input class="form-control maxlenght" type="text" name="bill_gstin" value="{{ $order->bill_gstin }}" max="15">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-12">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Products</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <table class="table table-bordered" id="product">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>SKU/Product Code</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Discount Type</th>
                                        <th>Discount</th>
                                        <th>Tax Percent</th>
                                        <th>Tax Amount</th>
                                        <th>Total Price</th>
                                        <th><button type="button" id="add_more" class="btn btn-success"><i class="fa fa-plus"></i></button></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->detail as $product)
                                    <tr>
                                        <td><input type="text" name="name[]" value="{{ $product->name }}" placeholder="Name" class="form-control" required /></td>
                                        <td><input type="text" name="code[]" value="{{ $product->code }}" placeholder="Code" class="form-control" required /></td>
                                        <td><input type="number" name="qty[]" value="{{ $product->qty }}" class="form-control calculate" required /></td>
                                        <td><input type="number" name="price[]" value="{{ $product->price }}" class="form-control calculate" required step="any"/></td>
                                        <td><select name="discount_type[]" class="form-control discount_type">
                                                <option value="">Select</option>
                                                <option value="f" @if($product->discount_type == 'f') selected @endif>Flat</option>
                                                <option value="p" @if($product->discount_type == 'p') selected @endif>Percentage</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="discount[]" step="any" value="{{ $product->discount }}" class="form-control discount" @if(($product->discount == 0) && ($product->discount_type == '')) readonly @endif/></td>
                                        <td><input type="number" name="tax_percent[]" value="{{ $product->tax_percent ?? 0 }}" class="form-control tax_percent" step="any" min="0" /></td>
                                        <td><input type="number" name="tax_amount[]" value="{{ $product->tax_amount }}" class="form-control tax_amount" step="any" /></td>
                                        <td><input type="number" name="total_price[]" value="{{ $product->total_price }}" class="form-control" readonly step="any" /></td>
                                        <td><button type="button" class="btn btn-danger" onclick="remove_row('product',this)"><i class="fa fa-close"></i></button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 ">
                                <label>Order Discount</label>
                                <input class="form-control" type="number" name="order_discount" value="{{ $order->discount }}" id="order_discount" oninput="update('discount')" step="any">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Shipping Cost</label>
                                <input class="form-control" type="number" name="shipping_cost" value="{{ $order->shipping_cost }}" id="shipping_cost" oninput="update()" step="any">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Calculated Order Total</label>
                                <input class="form-control" type="number" id="total" name="total" value="{{ $order->total }}" readonly step="any">
                            </div>
                            <div class="form-group col-md-4">
                               <label>Custom Order Total(For COD* Collectable Amount )</label>
                                <input class="form-control" type="number" name="custom_total" id="custom_total" value="{{ $order->custom_total }}" step="any">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Payment Information</label>
                                <select name="payment_mode" class="form-control">
                                    @if($order->reverse_order =='0')
                                        <option value="12" @if($order->payment_mode == '<span class="badge text-white" style="background:#3a87ad">Pre-Paid</span>') selected @endif>Pre-Paid</option>
                                        <option value="6" @if($order->payment_mode == '<span class="badge text-white" style="background:#3a87ad">C.O.D</span>') selected @endif>C.O.D</option>
                                    @else
                                        <option value="16" @if($order->payment_mode == '<span class="badge text-white" style="background:#3a87ad">Reverse</span>') selected @endif>Reverse</option>
                                    @endif
                                    <!--<option value="13" @if($order->payment_mode == '13') selected @endif>Bank Transfer</option>-->
                                    <!--<option value="14" @if($order->payment_mode == '14') selected @endif>Paytm</option>-->
                                    <!--<option value="15" @if($order->payment_mode == '15') selected @endif>Gpay</option>-->
                                    <!--<option value="1" @if($order->payment_mode == '1') selected @endif>Credit card</option>-->
                                    <!--<option value="2" @if($order->payment_mode == '2') selected @endif>Phone ordering</option>-->
                                    <!--<option value="3" @if($order->payment_mode == '3') selected @endif>Check</option>-->
                                    <!--<option value="4" @if($order->payment_mode == '4') selected @endif>Fax Ordering</option>-->
                                    <!--<option value="5" @if($order->payment_mode == '5') selected @endif>Money Order</option>-->
                                    <!--<option value="7" @if($order->payment_mode == '7') selected @endif>Purchase Order</option>-->
                                    <!--<option value="8" @if($order->payment_mode == '8') selected @endif>Personal Check</option>-->
                                    <!--<option value="9" @if($order->payment_mode == '9') selected @endif>Business Check</option>-->
                                    <!--<option value="10" @if($order->payment_mode == '10') selected @endif>Government Check</option>-->
                                    <!--<option value="11" @if($order->payment_mode == '11') selected @endif>Traveller's Check</option>-->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Other Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Order ID</label><span class="required"> *</span>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="vendor_order_id" name="vendor_order_id" required value="{{ $order->vendor_order_id }}">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" onclick="generateOrderId()">Generate</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Invoice Number</label>
                                <input class="form-control" type="text" name="invoice_no" value="{{ $order->invoice_no }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Channel</label>
                                <input class="form-control" type="text" name="channel" value="{{ $order->channel }}" >
                                <!--<select class="form-control" name="channel">-->
                                <!--    <option value="">Select</option>-->
                                <!--    <option value="1">Hyloship</option>-->
                                <!--</select>-->
                            </div>
                            <div class="form-group col-md-4">
                                <label>Order Weight (grams) </label><span class="required"> *</span>
                                <input class="form-control" type="number" name="weight" value="{{ $order->weight }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Length (cm's) </label><span class="required"> *</span>
                                <input class="form-control" type="number" name="length" value="{{ $order->length }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Breadth (cm's) </label><span class="required"> *</span>
                                <input class="form-control" type="number" name="breadth" value="{{ $order->breadth }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Height (cm's) </label><span class="required"> *</span>
                                <input class="form-control" type="number" name="height" value="{{ $order->height }}" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-control-label">Order Notes</label>
                                <textarea class="form-control" name="note" value="">{{ $order->note }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn-primary btn h-45 w-100">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

<script src="{{ asset('admin/order.js') }}"></script>
<script>
    "use strict";
    function toggleBilling(value) {
        // Update hidden input
        document.getElementById('same_add_val').value = value;
        // Update button styles
        if(value == 0) {
            document.getElementById('btn_bill_yes').style.cssText = 'padding:6px 18px;border-radius:4px;border:2px solid #ccc;background:#fff;color:#333;cursor:pointer;font-size:14px;';
            document.getElementById('btn_bill_no').style.cssText = 'padding:6px 18px;border-radius:4px;border:2px solid #e74c3c;background:#e74c3c;color:#fff;cursor:pointer;font-size:14px;';
            var html = `<div class="col-12" id="bill_remove">
                                <div class="card pt-30">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Customer Billing Address</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-4 ">
                                                <label>First Name</label><span class="required"> *</span>
                                                <input class="form-control" type="text" name="bill_fname" required value="{{ $order->bill_fname }}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="form-control-label">Last Name</label><span class="required"> *</span>
                                                <input class="form-control" type="text" name="bill_lname" required value="{{ $order->bill_lname }}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Company </label>
                                                <input class="form-control" type="text" name="bill_company" value="{{ $order->bill_company }}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="form-control-label">Phone</label><span class="required"> *</span>
                                                <input class="form-control phone-number" type="text" name="bill_phone" required value="{{ $order->bill_phone }}" max="10">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="form-control-label">Address</label><span class="required"> *</span>
                                                <textarea class="form-control" name="bill_address" required value="">{{ $order->bill_address }}</textarea>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="form-control-label">Address</label>
                                                <textarea class="form-control" name="bill_address_2" value="">{{ $order->bill_address_2 }}</textarea>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="form-control-label">Country</label><span class="required"> *</span>
                                                <select class="form-control" name="bill_country" required>
                                                    <option value="">Select Country</option>
                                                    @foreach ($counrtries as $country)
                                                    <option value="{{ $country->id }}" @if($order->bill_country == $country->id) selected @endif>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="form-control-label">Zip/postal code</label><span class="required"> *</span>
                                                <input class="form-control" type="number" name="bill_pincode" required value="{{ $order->bill_pincode }}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>City </label><span class="required"> *</span>
                                                <input class="form-control" type="text" name="bill_city" value="{{ $order->bill_city }}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>State/province </label><span class="required"> *</span>
                                                <input class="form-control" type="text" name="bill_state" value="{{ $order->bill_state }}">
                                            </div>
                                            <input class="form-control  d-none" type="text" name="bill_latitude" value="{{ $order->bill_latitude }}">
                                            <input class="form-control d-none" type="text" name="bill_longitude" value="{{ $order->bill_longitude }}">
                                            <div class="form-group col-md-4">
                                                <label>GSTIN </label>
                                                <input class="form-control maxlenght" type="text" name="bill_gstin" value="{{ $order->bill_gstin }}" max="15">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
            $('#bill_address').html(html);
        } else {
            document.getElementById('btn_bill_yes').style.cssText = 'padding:6px 18px;border-radius:4px;border:2px solid #26a65b;background:#26a65b;color:#fff;cursor:pointer;font-size:14px;';
            document.getElementById('btn_bill_no').style.cssText = 'padding:6px 18px;border-radius:4px;border:2px solid #ccc;background:#fff;color:#333;cursor:pointer;font-size:14px;';
            $('#bill_address').empty();
        }
    }

    (function($) { 
        // Initial listeners removed in favor of toggleBilling()

        $('#pncd').on('change', function(){
            var pincode = $('#pncd').val();
            if(pincode.length !='6'){
                alert('Wrong Pincode');
            }else{
                $.get({
                    url: "{{ route('admin.order.get.pincode') }}",
                    data: { 
                        pincode: pincode 
                    },
                    beforeSend: function() {
                        $('#loader').removeClass('hidden')
                    },
                    success: function(data) {
                    if(data.status == 1){
                        $.each(data.data, function(key, value){
                            document.getElementById('cityid').value=value.city ; 
                            document.getElementById('stateid').value=value.state ; 
                        });
                    } else {
                        alert('Pincode not found,please contact admin');
                        document.getElementById('cityid').value='' ; 
                            document.getElementById('stateid').value='' ; 
                    }
                    
                },
                complete: function(){
                    $('#loader').addClass('hidden')
                },
            });
            }
            
        });
    })(jQuery);

    function generateOrderId() {
        const unixTime = Math.floor(Date.now() / 1000);
        document.getElementById('vendor_order_id').value = unixTime;
    }
</script>
@endsection
