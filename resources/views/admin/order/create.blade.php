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
            
        </div>
    </div>

    <div class="row clearfix">
        <form id="form_submit" action="{{ route('admin.order.store') }}" method="POST" enctype="multipart/form-data">
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
                                <label class="form-control-label">Last Name</label>
                                <!--<span class="required"> *</span>-->
                                <input class="form-control" type="text" name="ship_lname"  value="">
                            </div>
                            <!--<div class="form-group col-md-4">-->
                                <!--<label>Email </label><span class="required"> *</span>-->
                                <input class="form-control d-none" type="email" name="ship_email" value="info@hyloship.com" >
                            <!--</div>-->
                            <div class="form-group col-md-4">
                                <label>Company </label>
                                <input class="form-control" type="text" name="ship_company" value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Phone</label><span class="required"> *</span>
                                <input class="form-control phone-number" type="text" name="ship_phone" required value="" max="10">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Address</label><span class="required"> *</span>
                                <textarea class="form-control" name="ship_address" required value=""></textarea>
                            </div>
<!--                            <div class="form-group col-md-4">
                                <label class="form-control-label">Address</label>-->
                                <textarea class="form-control  d-none" name="ship_address_2" value=""></textarea>
                            <!--</div>-->
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
                                    <input class="form-control" type="number" id="pncd" name="ship_pincode" required value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label>City </label><span class="required"> *</span>
                                <input class="form-control" type="text" id="cityid" name="ship_city" required value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label>State/province </label><span class="required"> *</span>
                                <input class="form-control" type="text" id="stateid" name="ship_state" required value="">
                            </div>
<!--                            <div class="form-group col-md-4">
                                <label>Latitude </label>-->
                                <input class="form-control  d-none" type="text" name="ship_latitude" value="">
                            <!--</div>-->
<!--                            <div class="form-group col-md-4">
                                <label>Longitude </label>-->
                                <input class="form-control d-none" type="text" name="ship_longitude" value="">
                            <!--</div>-->
                            <div class="form-group col-md-4">
                                <label>GSTIN </label>
                                <input class="form-control maxlenght" type="text" name="ship_gstin" value="" max="15">
                            </div>
                            <div class="form-group col-md-4">
                                <label>e-Way Bill No </label>
                                <input class="form-control" type="text" name="e_bill_no" value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Billing address same as Shipping address</label>
                                <div style="display:flex;gap:8px;margin-top:6px;">
                                    <button type="button" id="btn_bill_yes" onclick="toggleBilling(1)" style="padding:6px 18px;border-radius:4px;border:2px solid #26a65b;background:#26a65b;color:#fff;cursor:pointer;font-size:14px;">&#10004; Yes</button>
                                    <button type="button" id="btn_bill_no" onclick="toggleBilling(0)" style="padding:6px 18px;border-radius:4px;border:2px solid #ccc;background:#fff;color:#333;cursor:pointer;font-size:14px;">&#10008; No</button>
                                </div>
                                <input type="hidden" name="same_add" id="same_add_val" value="1">
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
                                        <!--<th>SKU/Product Code</th>-->
                                        <th>MSDS (PDF)</th>
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
                                <tr>
                                    <td><input type="text" name="name[]" placeholder="Name" class="form-control" required /></td>
                                    <td>
                                        <input type="file" name="msds[]" class="form-control" accept="application/pdf">
                                    </td>
                                    <!--<td><input type="text" name="code[]" placeholder="Code" class="form-control" required /></td>-->
                                    <td><input type="number" name="qty[]" value="0" class="form-control calculate" required /></td>
                                    <td><input type="number" name="price[]" value="0.00" class="form-control calculate" required  step="any" /></td>
                                    <td><select name="discount_type[]" class="form-control discount_type">
                                            <option value="">Select</option>"
                                            <option value="f">Flat</option>"
                                            <option value="p">Percentage</option>"
                                        </select></td>
                                    <td><input type="number" name="discount[]" value="0" class="form-control discount" readonly  step="any" /></td>
                                    <td><input type="number" name="tax_percent[]" value="0" class="form-control tax_percent" step="any" min="0" /></td>
                                    <td><input type="number" name="tax_amount[]" value="0.00" class="form-control tax_amount" readonly step="any"  /></td>
                                    <td><input type="number" name="total_price[]" value="0.00" class="form-control" readonly  step="any" /></td>
                                    <td><button type="button" class="btn btn-danger" onclick="remove_row('product',this)"><i class="fa fa-close"></i></button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 ">
                                <label>Order Discount</label>
                                <input class="form-control" type="number" name="order_discount" value="0.00" id="order_discount" oninput="update('discount')" step="any">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Shipping Cost</label>
                                <input class="form-control" type="number" name="shipping_cost" value="0.00" id="shipping_cost" oninput="update()" step="any">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Calculated Order Total</label>
                                <input class="form-control" type="number" id="total" name="total" value="0.00" readonly step="any">
                            </div>
                            <div class="form-group col-md-4" id="custom_total_div" style="">
                                <label>Custom Order Total(For COD* Collectable Amount )</label>
                                <input class="form-control" type="number" name="custom_total" id="custom_total" readonly value="0.00" step="any">
                            </div>
                                <div class="form-group col-md-4">
                                <label>Payment Information</label>
                                <select name="payment_mode" id="payment_mode" class="form-control">
                                    <option value="12" selected="selected">Pre-Paid</option>
                                    <!--<option value="13">Bank Transfer</option>-->
                                    <!--<option value="14">Paytm</option>-->
                                    <!--<option value="15">Gpay</option>-->
                                    <!--<option value="1">Credit card</option>-->
                                    <!--<option value="2">Phone ordering</option>-->
                                    <!--<option value="3">Check</option>-->
                                    <!--<option value="4">Fax Ordering</option>-->
                                    <!--<option value="5">Money Order</option>-->
                                    <option value="6">C.O.D</option>
                                    <!--<option value="7">Purchase Order</option>-->
                                    <!--<option value="8">Personal Check</option>-->
                                    <!--<option value="9">Business Check</option>-->
                                    <!--<option value="10">Government Check</option>-->
                                    <!--<option value="11">Traveller's Check</option>-->
                                </select>
                            </div>
<!--                            <div class="form-group col-md-4 d-none" id="cod_amount_div">
                                <label>COD Amount</label>
                                <input class="form-control" type="number" id="cod_amount" readonly>
                            </div>-->
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
                                <label>Order ID</label><span class="required"> *</span>
                                <input class="form-control" type="text" name="vendor_order_id" required value="">
                                <div class="input-group-append">
                                    <button type="button"
                                            class="btn btn-primary"
                                            onclick="generateOrderId()">
                                        Generate
                                    </button>
                                </div>
                            </div>
                             <div class="form-group col-md-4">
                                <label>Invoice Number</label>
                                <input class="form-control" type="text" name="invoice_no" value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Channel</label>
                                <input class="form-control" type="text" name="channel" value="" >
                                <!--<select class="form-control" name="channel">-->
                                <!--    <option value="">Select</option>-->
                                <!--    <option value="1">hyloship</option>-->
                                <!--</select>-->
                            </div>
                            <div class="form-group col-md-4">
                                <label>Order Weight (grams) </label><span class="required"> *</span>
                                <input class="form-control" type="number" name="weight" value="" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Length (cm's) </label><span class="required"> *</span>
                                <input class="form-control" type="number" name="length" value="10" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Breadth (cm's) </label><span class="required"> *</span>
                                <input class="form-control" type="number" name="breadth" value="10" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Height (cm's) </label><span class="required"> *</span>
                                <input class="form-control" type="number" name="height" value="10" required>
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
            <div class="col-lg-4 mb-4">
                
                        <button type="submit" class="btn-primary btn h-45 w-100 ">Submit</button>
                
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
                                    <h5 class="card-title mb-0">Customer Billing Address<a data-action="collapse" class="float-right toggle-icon"><i class="fa fa-minus"></i></a></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-4 ">
                                            <label>First Name</label><span class="required"> *</span>
                                            <input class="form-control" type="text" name="bill_fname" required value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">Last Name</label>
                                            <input class="form-control" type="text" name="bill_lname"  value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Company </label>
                                            <input class="form-control" type="text" name="bill_company" value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">Phone</label><span class="required"> *</span>
                                            <input class="form-control phone-number" type="text" name="bill_phone" required value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">Address</label><span class="required"> *</span>
                                            <textarea class="form-control" name="bill_address" required value=""></textarea>
                                        </div>
                                        <textarea class="form-control d-none" name="bill_address_2" value=""></textarea>
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
                                        <input class="form-control d-none" type="text" name="bill_latitude" value="">
                                        <input class="form-control  d-none" type="text" name="bill_longitude" value="">
                                        <div class="form-group col-md-4">
                                            <label>GSTIN </label>
                                            <input class="form-control maxlenght" type="text" name="bill_gstin" value="" max="15">
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
    
</script>
<script>
$(document).ready(function () {

    function toggleCOD() {
        let paymentMode = $('#payment_mode').val();
        let customTotal = $('#custom_total').val();

        if (paymentMode == '6') { // COD
//            $('#custom_total_div').addClass('d-none');
            $('#cod_amount_div').removeClass('d-none');
            $('#cod_amount').val(customTotal);
            $('#custom_total').prop('readonly', false);
        } else {
//            $('#custom_total_div').removeClass('d-none');
            $('#cod_amount_div').addClass('d-none');
            $('#custom_total').prop('readonly', true);
        }
    }

    // On payment mode change
    $('#payment_mode').on('change', toggleCOD);

    // Sync COD amount when custom total changes
    $('#custom_total').on('input', function () {
        $('#cod_amount').val($(this).val());
    });

    // Initial load
    toggleCOD();
});
</script>
<script>
const HARD_BAN = [
    'weed','ganja','marijuana','hash','charas','cocaine','heroin',
    'gun','pistol','revolver','rifle','bullet','ammo','grenade',
    'rdx','tnt','dynamite',
    'fakecurrency','counterfeit',
    'ivory','tigerskin',
    'militaryid','governmentseal'
];

function normalizeText(text) {
    return text.toLowerCase().replace(/[^a-z]/g, '');
}

function containsBannedKeyword(text) {
    const normalized = normalizeText(text);
    return HARD_BAN.some(word => normalized.includes(word));
}
const PATTERNS = [
    /g[^a-z]*a[^a-z]*n[^a-z]*j[^a-z]*a/i,  // g@nj@
    /w[^a-z]*e[^a-z]*e[^a-z]*d/i,         // w33d
    /c[^a-z]*o[^a-z]*c[^a-z]*a[^a-z]*i[^a-z]*n[^a-z]*e/i  // c0caine
];

function containsObfuscatedKeyword(text) {
    return PATTERNS.some(pattern => pattern.test(text));
}

$(document).on('input', 'input[name="name[]"]', function () {
    let val = $(this).val();

    if (containsBannedKeyword(val) || containsObfuscatedKeyword(val)) {
        alert('❌ This product contains a banned keyword and cannot be submitted.');
        $(this).val('');
        $(this).focus();
    }
});


const MSDS_LIST = [
    // 🔴 Flammable
//   /* 'petrol','diesel','kerosene','paints','paint thinner','varnish','lacquer',
//     'spray paint','nail polish','nail polish remover','deodorant',
//     'room freshener','lighter fluid',*/

    // 🟠 Chemicals & industrial
    'acetone','methanol','ethanol','isopropyl alcohol','toluene','xylene',
    'formaldehyde','hydrogen peroxide','ammonia','sodium hydroxide','hydrochloric acid',
    'sulphuric acid','nitric acid',

    // 🟡 Cleaning & household
    // 'toilet cleaner','floor cleaner','phenyl','bleach','detergent','dishwashing liquid',
    // 'glass cleaner','drain cleaner','disinfectant','hand sanitizer',

    // 🔵 Aerosols & compressed gas
    'deodorant spray','insecticide spray','pesticide spray','air duster',
    'fire extinguisher','gas cartridge','co2 cylinder','oxygen cylinder',

    // ⚫ Adhesives / resins
    'fevicol','epoxy','hardener','super glue','silicone','rubber adhesive','pu foam',

    // 🟤 Oils & lubricants
    'engine oil','gear oil','hydraulic oil','transformer oil','grease','cutting oil',
    'brake fluid','coolant','antifreeze',

    // 🟢 Cosmetics
    // 'hair dye','hair straightening','skin peel','chemical exfoliant','sunscreen','tattoo ink',
    // 'permanent makeup',

    // 🔶 Batteries
    'lithium-ion battery','lithium metal battery','lead-acid battery','battery cell','ups battery','ev battery',

    // 🔺 Lab & medical
    'lab reagent','diagnostic chemical','x-ray chemical','pathology reagent','medical disinfectant','formalin',

    // ❌ Extremely restricted
    'explosive','radioactive','biohazard','toxic gas','human specimen'
];
$(document).on('input', 'input[name="name[]"]', function () {
    let val = $(this).val().toLowerCase();
    for (let item of MSDS_LIST) {
        if (val.includes(item)) {
            alert(`⚠️ This product "${val}" requires MSDS. Make sure to upload before saving!`);
            break;
        }
    }
});
function generateOrderId() {
    const unixTime = Math.floor(Date.now() / 1000);
    document.getElementById('vendor_order_id').value = unixTime;
}
</script>

@endsection 