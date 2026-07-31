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
                <h2>Order create</h2>
            </div>
            <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                <ul class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <form id="form_submit" action="{{ route('admin.order.store') }}" method="POST">
            @csrf
            <div class="col-12">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Customer Shipping Address<a data-action="collapse" class="float-right toggle-icon"><i class="fa fa-minus"></i></a></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4 ">
                                <label>First Name</label><span class="required"> *</span>
                                <input class="form-control" type="text" name="ship_fname" required value="">
                                <input class="form-control" type="hidden" name="order_id" required value="{{ $order_id }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Last Name</label><span class="required"> *</span>
                                <input class="form-control" type="text" name="ship_lname" required value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Email </label><span class="required"> *</span>
                                <input class="form-control" type="email" name="ship_email" value="" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Company </label>
                                <input class="form-control" type="text" name="ship_company" value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Phone</label><span class="required"> *</span>
                                <input class="form-control" type="number" name="ship_phone" required value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Address</label><span class="required"> *</span>
                                <textarea class="form-control" name="ship_address" required value=""></textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Address</label>
                                <textarea class="form-control" name="ship_address_2" value=""></textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Country</label><span class="required"> *</span>
                                <select class="form-control" name="ship_country" required>
                                    <option value="">Select Country</option>
                                    @foreach ($counrtries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Zip/postal code</label><span class="required"> *</span>
                                <input class="form-control" type="number" name="ship_pincode" required value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label>City </label><span class="required"> *</span>
                                <input class="form-control" type="text" name="ship_city" value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label>State/province </label><span class="required"> *</span>
                                <input class="form-control" type="text" name="ship_state" value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Latitude </label>
                                <input class="form-control" type="text" name="ship_latitude" value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Longitude </label>
                                <input class="form-control" type="text" name="ship_longitude" value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label>GSTIN </label>
                                <input class="form-control" type="text" name="ship_gstin" value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label>e-Way Bill No </label>
                                <input class="form-control" type="text" name="e_bill_no" value="">
                            </div>
                            <div class="form-group col-4">
                                <label for="text-input1">Billing address same as Shipping address</label><br>
                                <label class="fancy-radio custom-color-green same_address"><input name="same_add"
                                        value="1" type="radio" checked><span><i></i>Yes</span></label>
                                <label class="fancy-radio custom-color-green same_add"><input name="same_add"
                                        value="0" type="radio"><span><i></i>No</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="bill_address"></div>
            <div class="col-12">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Products<a data-action="collapse" class="float-right toggle-icon"><i class="fa fa-minus"></i></a></h5>
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
                                        <th>Total Price</th>
                                        <th><button type="button" id="add_more" class="btn btn-success"><i class="fa fa-plus"></i></button></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input type="text" name="name[]" placeholder="Name" class="form-control" required /></td>
                                    <td><input type="text" name="code[]" placeholder="Code" class="form-control" required /></td>
                                    <td><input type="number" name="qty[]" value="0" class="form-control calculate" required /></td>
                                    <td><input type="number" name="price[]" value="0.00" class="form-control calculate" required /></td>
                                    <td><select name="discount_type[]" class="form-control discount_type">
                                            <option value="">Select</option>"
                                            <option value="f">Flat</option>"
                                            <option value="p">Percentage</option>"
                                        </select>
                                    </td>
                                    <td><input type="number" name="discount[]" value="0" class="form-control discount" readonly /></td>
                                    <td><input type="number" name="total_price[]" value="0.00" class="form-control" readonly/></td>
                                    <td><button type="button" class="btn btn-danger" onclick="delete_row('product',this)"><i class="fa fa-close"></i></button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 ">
                                <label>Order Discount</label>
                                <input class="form-control" type="number" name="order_discount" value="0.00" id="order_discount">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Shipping Cost</label>
                                <input class="form-control" type="number" name="shipping_cost" value="0.00" id="shipping_cost">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Calculated Order Total</label>
                                <input class="form-control" type="number" id="total" name="total" value="0.00" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Custom Order Total</label>
                                <input class="form-control" type="number" name="custom_total" id="custom_total" value="0.00">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Payment Information</label>
                                <select name="payment_mode" class="form-control">
                                    <option value="12" selected="selected">Pre-Paid</option>
                                    <option value="13">Bank Transfer</option>
                                    <option value="14">Paytm</option>
                                    <option value="15">Gpay</option>
                                    <option value="1">Credit card</option>
                                    <option value="2">Phone ordering</option>
                                    <option value="3">Check</option>
                                    <option value="4">Fax Ordering</option>
                                    <option value="5">Money Order</option>
                                    <option value="6">C.O.D</option>
                                    <option value="7">Purchase Order</option>
                                    <option value="8">Personal Check</option>
                                    <option value="9">Business Check</option>
                                    <option value="10">Government Check</option>
                                    <option value="11">Traveller's Check</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Other Information<a data-action="collapse" class="float-right toggle-icon"><i class="fa fa-minus"></i></a></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4 ">
                                <label>Vendor Order ID</label><span class="required"> *</span>
                                <input class="form-control" type="text" name="vendor_order_id" required value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Channel</label>
                                <select class="form-control" name="channel">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Order Weight (grams) </label><span class="required"> *</span>
                                <input class="form-control" type="number" name="weight" value="" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Length (cm's) </label><span class="required"> *</span>
                                <input class="form-control" type="number" name="length" value="" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Breath (cm's) </label><span class="required"> *</span>
                                <input class="form-control" type="number" name="breadth" value="" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Height (cm's) </label><span class="required"> *</span>
                                <input class="form-control" type="number" name="height" value="" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-control-label">Order Notes</label>
                                <textarea class="form-control" name="note" value=""></textarea>
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

<script>
    "use strict";
    (function($) { 
        $(document).on("input", '#order_discount', function(){
            var order_discount = parseFloat($('#order_discount').val()) || 0;
            var shipping_cost = parseFloat($('#shipping_cost').val()) || 0;
            var total = 0;
            $($('#product').find('tbody tr')).each(function(){
                total += parseFloat($(this).closest('tr').find('input[name="total_price[]"]').val()) || 0;
            });
            var final = (total + shipping_cost) - order_discount;
            $('#total').val(final.toFixed(2));
        });

        $(document).on("input", '#shipping_cost', function(){
            var shipping_cost = parseFloat($('#shipping_cost').val()) || 0;
            var order_discount = parseFloat($('#order_discount').val()) || 0;
            var total = 0;
            $($('#product').find('tbody tr')).each(function(){
                total += parseFloat($(this).closest('tr').find('input[name="total_price[]"]').val()) || 0;
            });
            var final = (total + shipping_cost) - order_discount;
            $('#total').val(final.toFixed(2));
            $('#custom_total').val(final.toFixed(2));
        });

        $(document).on("input", '.calculate', function(){
            var row = $(this).closest('tr');
            var qty = parseFloat(row.find('input[name="qty[]"]').val()) || 0;
            var price = parseFloat(row.find('input[name="price[]"]').val()) || 0;
            var type = row.find('select[name="discount_type[]"]').val();
            var discount = parseFloat(row.find('input[name="discount[]"]').val()) || 0;
            var order_discount = parseFloat($('#order_discount').val()) || 0;
            var shipping_cost = parseFloat($('#shipping_cost').val()) || 0;
            if(type == 'p' && price > 0 && qty > 0){
                var discountAmount = (price * qty * discount) / 100;
                var product_total = (price * qty) - discountAmount;
                row.find('input[name="total_price[]"]').val(product_total.toFixed(2));
            } else if(type == 'f' && price > 0 && qty > 0){
                row.find('input[name="total_price[]"]').val(((qty*price)-discount).toFixed(2));
            } else {
                row.find('input[name="total_price[]"]').val((qty*price).toFixed(2));
            }
            var total = 0;
            $($('#product').find('tbody tr')).each(function(){
                total += parseFloat($(this).closest('tr').find('input[name="total_price[]"]').val()) || 0;
            });
            var final = (total + shipping_cost) - order_discount;
            $('#total').val(final.toFixed(2));
            $('#custom_total').val(final.toFixed(2));
        });

        $(document).on("change", '.discount_type', function(){
            var row = $(this).closest('tr');
            var qty = parseFloat(row.find('input[name="qty[]"]').val()) || 0;
            var price = parseFloat(row.find('input[name="price[]"]').val()) || 0;
            var discount = row.find('input[name="discount[]"]');
            var total_price = parseFloat(row.find('input[name="total_price[]"]').val()) || 0;
            var order_discount = parseFloat($('#order_discount').val()) || 0;
            var shipping_cost = parseFloat($('#shipping_cost').val()) || 0;

            if ($(this).val() === "f" || $(this).val() === "p") {
                if($(this).val() === "f"){
                    row.find('input[name="total_price[]"]').val(((qty*price) - parseFloat(discount.val())).toFixed(2));
                } else {
                    var discountAmount = (price * qty * parseFloat(discount.val())) / 100;
                    var product_total = (price * qty) - discountAmount;
                    row.find('input[name="total_price[]"]').val(product_total.toFixed(2));
                }
                discount.prop('readonly', false);
                discount.prop('required', true);
            } else {
                discount.val(0);
                discount.prop('readonly', true);
                discount.prop('required', false);
                row.find('input[name="total_price[]"]').val((qty * price).toFixed(2));
            }
            var total = 0;
            $($('#product').find('tbody tr')).each(function(){
                total += parseFloat($(this).closest('tr').find('input[name="total_price[]"]').val()) || 0;
            });
            var final = (total + shipping_cost) - order_discount;
            $('#total').val(final.toFixed(2));
            $('#custom_total').val(final.toFixed(2));
        });

        $(document).on("input", '.discount', function(){
            var row = $(this).closest('tr');
            var type = row.find('select[name="discount_type[]"]').val();
            var qty = parseFloat(row.find('input[name="qty[]"]').val()) || 0;
            var price = parseFloat(row.find('input[name="price[]"]').val()) || 0;
            var discount = parseFloat($(this).val()) || 0;
            var order_discount = parseFloat($('#order_discount').val()) || 0;
            var shipping_cost = parseFloat($('#shipping_cost').val()) || 0;

            if (type === 'p') {
                var discountAmount = (price * qty * discount) / 100;
                var total = (price * qty) - discountAmount;
            } else {
                var total = (price * qty) - discount;
            }

            row.find('input[name="total_price[]"]').val(total.toFixed(2));
            var total = 0;
            $($('#product').find('tbody tr')).each(function(){
                total += parseFloat($(this).closest('tr').find('input[name="total_price[]"]').val()) || 0;
            });
            var final = (total + shipping_cost) - order_discount;
            $('#total').val(final.toFixed(2));
            $('#custom_total').val(final.toFixed(2));
        });

        $('.same_add').on('click', function() {
            var value = $('input[name="same_add"]:checked').val();
            var html = `<div class="col-12" id="bill_remove">
                            <div class="card pt-30">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Customer Billing Address<a data-action="collapse" class="float-right toggle-icon"><i class="fa fa-minus"></i></a></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-4 ">
                                            <label>First Name</label><span class="required"> *</span>
                                            <input class="form-control" type="text" name="bill_fname" required value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">Last Name</label><span class="required"> *</span>
                                            <input class="form-control" type="text" name="bill_lname" required value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Company </label>
                                            <input class="form-control" type="text" name="bill_company" value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">Phone</label><span class="required"> *</span>
                                            <input class="form-control" type="number" name="bill_phone" required value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">Address</label><span class="required"> *</span>
                                            <textarea class="form-control" name="bill_address" required value=""></textarea>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">Address</label>
                                            <textarea class="form-control" name="bill_address_2" value=""></textarea>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">Country</label><span class="required"> *</span>
                                            <select class="form-control" name="bill_country" required>
                                                <option value="">Select Country</option>
                                                @foreach ($counrtries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">Zip/postal code</label><span class="required"> *</span>
                                            <input class="form-control" type="number" name="bill_pincode" required value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>City </label><span class="required"> *</span>
                                            <input class="form-control" type="text" name="bill_city" value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>State/province </label><span class="required"> *</span>
                                            <input class="form-control" type="text" name="bill_state" value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Latitude </label>
                                            <input class="form-control" type="text" name="bill_latitude" value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Longitude </label>
                                            <input class="form-control" type="text" name="bill_longitude" value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>GSTIN </label>
                                            <input class="form-control" type="text" name="bill_gstin" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                        if(value == 0) {
                            $('#bill_address').append(html);
                        }
        })

        $('.same_address').on('click', function(){
            $('#bill_remove').remove();
        });
    })(jQuery);


    function addRow() {
        var newRow = `<tr>
                        <td><input type="text" name="name[]" placeholder="Name" class="form-control" required /></td>
                        <td><input type="text" name="code[]" placeholder="Code" class="form-control" required /></td>
                        <td><input type="number" name="qty[]" value="0" class="form-control calculate" required /></td>
                        <td><input type="number" name="price[]" value="0.00" class="form-control calculate" required /></td>
                        <td><select name="discount_type[]" class="form-control discount_type">
                                <option value="">Select</option>"
                                <option value="f">Flat</option>"
                                <option value="p">Percentage</option>"
                            </select>
                        </td>
                        <td><input type="number" name="discount[]" value="0" class="form-control discount" readonly /></td>
                        <td><input type="number" name="total_price[]" value="0.00" class="form-control" readonly/></td>
                        <td><button type="button" class="btn btn-danger" onclick="delete_row('product',this)"><i class="fa fa-close"></i></button></td>
                    </tr>`;
        $('#product').append(newRow);
    }
    $('#add_more').click(addRow);



    function delete_row(tableId, button) {
        var table = $('#' + tableId);
        var row = $(button).closest('tr');
        if (table.find('tbody tr').length > 1) {
            row.remove();
            var shipping_cost = parseFloat($('#shipping_cost').val()) || 0;
            var order_discount = parseFloat($('#order_discount').val()) || 0;
            var total = 0;
            $($('#product').find('tbody tr')).each(function(){
                total += parseFloat($(this).closest('tr').find('input[name="total_price[]"]').val()) || 0;
            });
            var final = (total + shipping_cost) - order_discount;
            $('#total').val(final.toFixed(2));
            $('#custom_total').val(final.toFixed(2));
        } else {
            toastr.error("At least one row must required!");
        }
    }
</script>
@endsection
