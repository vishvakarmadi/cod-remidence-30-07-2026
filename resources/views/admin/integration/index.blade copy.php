@extends('admin.admin_layouts')
@section('admin_content')
    @php
        $session = Auth::guard('admin')->user();
    @endphp

    <style>
        body {
            background-color: #ffffff;
            margin: 0px auto;
            padding: 0px;
            font-family: helvetica;
            height: 2000px;
        }

        h1 {
            text-align: center;
            font-size: 35px;
            margin-top: 60px;
            color: #BEF781;
        }

        h1 p {
            text-align: center;
            margin: 0px;
            font-size: 18px;
            text-decoration: underline;
            color: white;
        }

        #main_content {
            margin-top: 50px;
            width: 1098px;
            margin-left: 48px;
        }

        #main_content li {
            display: inline;
            list-style-type: none;
            background-color: #000000;
            padding: 10px;
            border-radius: 5px 5px 0px 0px;
            color: #292A0A;
            font-weight: bold;
            cursor: pointer;
        }


        #main_content li.notselected {
            background-color: #000000;
            color: #ffffff;
        }

        #main_content li.selected {
            background-color: #000000;
            color: #ffffff;
        }

        #main_content .hidden_desc {
            display: none;
        }

        #main_content #page_content {
            background-color: #c93d00;
            padding: 10px;
            margin-top: 9px;
            border-radius: 0px 5px 5px 5px;
            color: #2E2E2E;
            line-height: 1.6em;
            word-spacing: 4px;
        }
    </style>
    <div id="main_content">

        @foreach ($courier as $row)
            <li class="selected" id="page{{ $row->id }}" onclick="change_tab(this.id);">{{ $row->courier }}</li>
        @endforeach

        <div class='hidden_desc' id="page1_desc">
            <h2>Bluedart</h2>
            <div class="row clearfix">
                <form action="{{ route('admin.integration.store') }}" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="card pt-30">
                            <div class="card-header">
                                <h5 class="card-title mb-0">General Info</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <x-field type="text" label="Carrier Title" placeholder="Carrier Title"
                                        name="bcarrier_title" required="required" />
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">Account Mode</label><span class="required">
                                            *</span>
                                        <select class="form-control" name="server" required>
                                            <option value="1">Production Server</option>
                                            <option value="2">Test Server</option>
                                        </select>
                                    </div>
                                    <x-field type="text" label="Login ID" placeholder="Login ID" name="login_id"
                                        required="required" />
                                    <x-field type="text" label="Licence Key" placeholder="Licence Key" name="licence_key"
                                        required="required" />
                                    <x-field type="text" label="Vendor Code" placeholder="Vendor Code" name="vendor_code"
                                        required="required" />
                                    <x-field type="text" label="Origin Area" placeholder="Origin Area" name="origin_area"
                                        required="required" />
                                    <x-field type="text" label="Customer Code(Pre-Paid)" name="pre_paid"
                                        required="required" />
                                    <x-field type="text" label="Customer Code(COD)" name="cod" required="required" />
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">Is To Pay Customer:</label>
                                        <select class="form-control" name="isToPayCustomer">
                                            <option value="">Select</option>
                                            <option value="true">Yes</option>
                                            <option value="false">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">Check if your service belongs to APEX DART
                                            PLUS:</label>
                                        <input type="checkbox" name="packtype" value="L">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label"> Select Yes if you want to share your GST/IGST
                                            data with your Courier:</label>
                                        <input type="radio" name="gst_status" value="1"> Yes
                                        <input type="radio" name="gst_status" value="0" checked="checked"> No
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label"> Auto Pickup:</label>
                                        <input type="radio" name="auto_pickup" value="true"> Yes
                                        <input type="radio" name="auto_pickup" value="false"> No
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label"> OTP based delivery :</label>
                                        <input type="radio" name="otp_no" value="true"> Yes
                                        <input type="radio" name="otp_no" value="false" checked="checked"> No
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label"></label> Esclation Delivery :</label>
                                        <input type="radio" name="esclation_status" value="true"> Yes
                                        <input type="radio" name="esclation_status" value="false" checked="checked">
                                        No
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <button type="submit" class="btn-primary btn h-45 w-100">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
            </div>
        </div>
        <div class='hidden_desc' id="page2_desc">
            <h6>Delhivery</h6>
            <div class="row clearfix">
                <form action="{{ route('admin.integration.store') }}" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="card pt-30">
                            <div class="card-header">
                                <h5 class="card-title mb-0">General Info</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <x-field type="text" label="Carrier Title" placeholder="Carrier Title"
                                        name="dcarrier_title" required="required" />
                                    <x-field class="form-group" type="text" label="Client" placeholder="Client" name="dclient"
                                            required="required" size="col-md-4" />
                                    <x-field class="form-group" type="text" label="Api Token" placeholder="Api Token"
                                            name="dapi_token" required="required" size="col-md-4" />
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">Shipping Mode</label><span class="required">
                                            *</span>
                                        <select class="form-control" name="dship_mode" required>
                                            <option value="Surface">Surface</option>
                                            <option value="Express">Express</option>
                                        </select>
                                    </div>
                                    <br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <button type="submit" class="btn-primary btn h-45 w-100">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <hr>
        </div>

        <div class='hidden_desc' id="page3_desc">
            <h6>Xpressbees</h6>
            <div class="row clearfix">
                <form action="{{ route('admin.integration.store') }}" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="card pt-30">
                            <div class="card-header">
                                <h5 class="card-title mb-0">General Info</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <x-field type="text" label="Carrier Title" placeholder="Carrier Title"
                                        name="xcarrier_title" required="required" />
                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">Shipment Type</label><span class="required">
                                                *</span>
                                            <select class="form-control" name="xnshipment_type" required>
                                                <option value="Forward">Forward</option>
                                                <option value="Reverse">Reverse</option>
                                            </select>
                                        </div>
                                        <x-field type="text" label="XBKey" placeholder="XBKey" name="xxb_key"
                                            required="required" />
                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">Account Mode</label><span class="required">
                                                *</span>
                                            <select class="form-control" name="xnaccount_mode" required>
                                                <option value="Live">Live</option>
                                                <option value="Test">Test</option>
                                            </select>
                                        </div>
                                        <x-field type="text" label="Username" placeholder="Username" name="xusername"
                                            required="required" />
                                        <x-field type="text" label="Password" placeholder="Password" name="xpassword"
                                            required="required" />
                                        <x-field type="text" label="Secret Key" placeholder="Secret Key"
                                            name="secret_key" required="required" />
                                        <x-field type="text" label="Business Account Name" name="b_account_name"
                                            required="required" />
                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">Carrier ID</label><span class="required">
                                                *</span>
                                            <select class="form-control" name="xwaccount_mode" required>
                                                <option value="">Carrier ID</option>
                                                <option value="1">Surface Xpressbees 0.5 K.G</option>
                                                <option value="6">Air Xpressbees 0.5 K.G</option>
                                                <option value="2">Xpressbees 2 K.G</option>
                                                <option value="3">Xpressbees 5 K.G</option>
                                                <option value="4">Xpressbees 10 K.G</option>
                                            </select>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <button type="submit" class="btn-primary btn h-45 w-100">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class='hidden_desc' id="page4_desc">
            <h6>Shree Mauruthi</h6>
            <div class="row clearfix">
                <form action="{{ route('admin.integration.store') }}" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="card pt-30">
                            <div class="card-header">
                                <h5 class="card-title mb-0">General Info</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <x-field type="text" label="Carrier Title" placeholder="Carrier Title"
                                        name="mcarrier_title" required="required" />
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">Shipping Mode</label><span class="required">
                                            *</span>
                                        <select class="form-control" name="mship_mode" required>
                                            <option value="Surface">Surface</option>
                                            <option value="Express">Express</option>
                                        </select>
                                    </div>
                                    <x-field type="text" label="Client" placeholder="Client" name="mclient"
                                        required="required" />
                                    <x-field type="text" label="Api Token" placeholder="Api Token" name="mapi_token"
                                        required="required" />
                                    <br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <button type="submit" class="btn-primary btn h-45 w-100">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <hr>
        </div>
        <div class='hidden_desc' id="page5_desc">
            <h6>Ecom Express</h6>
            <div class="row clearfix">
                <form action="{{ route('admin.integration.store') }}" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="card pt-30">
                            <div class="card-header">
                                <h5 class="card-title mb-0">General Info</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <x-field type="text" label="Carrier Title" placeholder="Carrier Title"
                                        name="ecarrier_title" required="required" />
                                    <x-field type="text" label="Username" placeholder="Username" name="eusername"
                                        required="required" />
                                    <x-field type="text" label="Password" placeholder="Password" name="epassword"
                                        required="required" />
                                    <x-field type="text" label="Customer Code" placeholder="Customer Code"
                                        name="customer_code" required="required" />
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">OTP_Enable</label><span class="required">
                                            *</span>
                                        <select class="form-control" name="otp_enable" required>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <button type="submit" class="btn-primary btn h-45 w-100">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <hr>
        </div>
        <div id="page_content">
            <div class="block-header">
                <div class="row">
                    <div class="col-12">
                        <h2>Bluedart</h2>
                        <div class="row clearfix">
                            <form action="{{ route('admin.integration.store') }}" method="post">
                                @csrf
                                <div class="col-12">
                                    <div class="card pt-30">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">General Info</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <x-field type="text" label="Carrier Title" placeholder="Carrier Title"
                                                    name="bcarrier_title" required="required" />
                                                <div class="form-group col-md-4">
                                                    <label class="form-control-label">Account Mode</label><span
                                                        class="required">
                                                        *</span>
                                                    <select class="form-control" name="server" required>
                                                        <option value="Production">Production Server</option>
                                                        <option value="Test">Test Server</option>
                                                    </select>
                                                </div>
                                                <x-field type="text" label="Login ID" placeholder="Login ID"
                                                    name="login_id" required="required" />
                                                <x-field type="text" label="Licence Key" placeholder="Licence Key"
                                                    name="licence_key" required="required" />
                                                <x-field type="text" label="Vendor Code" placeholder="Vendor Code"
                                                    name="vendor_code" required="required" />
                                                <x-field type="text" label="Origin Area" placeholder="Origin Area"
                                                    name="origin_area" required="required" />
                                                <x-field type="text" label="Customer Code(Pre-Paid)" name="pre_paid"
                                                    required="required" />
                                                <x-field type="text" label="Customer Code(COD)" name="cod"
                                                    required="required" />
                                                
                                                <div class="form-group col-md-4">
                                                    <label class="form-control-label">Is To Pay Customer:</label>
                                                    <select class="form-control" name="isToPayCustomer">
                                                        <option value="">Select</option>
                                                        <option value="true">Yes</option>
                                                        <option value="false">No</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="form-control-label">Check if your service belongs to APEX
                                                        DART
                                                        PLUS:</label>
                                                    <input type="checkbox" name="packtype" value="L">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="form-control-label"> Select Yes if you want to share your
                                                        GST/IGST
                                                        data with your Courier:</label>

                                                    <input type="radio" name="gst_status" value="1"> Yes
                                                    <input type="radio" name="gst_status" value="0"
                                                        checked="checked"> No

                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="form-control-label"> Auto Pickup:</label>
                                                    <input type="radio" name="auto_pickup" value="true"> Yes
                                                    <input type="radio" name="auto_pickup" value="false"> No
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="form-control-label"> OTP based delivery :</label>
                                                    <input type="radio" name="otp_no" value="true"> Yes
                                                    <input type="radio" name="otp_no" value="false"
                                                        checked="checked"> No
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="form-control-label"></label> Esclation Delivery :</label>
                                                    <input type="radio" name="esclation_status" value="true"> Yes
                                                    <input type="radio" name="esclation_status" value="false"
                                                        checked="checked">
                                                    No
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <button type="submit" class="btn-primary btn h-45 w-100">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr>
                        </div>
                    </div>

                </div>
            </div>



        </div>

    </div>
 
    
    <script type="text/javascript">
        function change_tab(id) {
            document.getElementById("page_content").innerHTML = document.getElementById(id + "_desc").innerHTML;
            document.getElementById("page1").className = "notselected";
            document.getElementById("page2").className = "notselected";
            document.getElementById("page3").className = "notselected";
            document.getElementById("page4").className = "notselected";
            document.getElementById("page5").className = "notselected";
            document.getElementById(id).className = "selected";
        }

        function change(value) {
            var newElement = document.getElementById("new");
            console.log(newElement);
            if (value === '1') {
                newElement.style.display = 'block';
            } else if (value === '0') {
                newElement.style.display = 'none';
            }
        }

</script>
   
   
   
    
@endsection


