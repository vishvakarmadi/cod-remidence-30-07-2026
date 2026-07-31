@extends('admin.admin_layouts')
@section('admin_content')
<style>
    .courier ul li {
        list-style : none;
    }

    label.radio-card {
    cursor: pointer;
    }
    

</style>
    <!-- Main body part  -->
    <div class="container-fluid">
        <!-- Page header section  -->
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <h1>Hi, Welcomeback!</h1>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                    <div class="row clearfix">
                        <div class="col-xl-5 col-md-5 col-sm-12">
                        </div>
                        <div class="col-xl-7 col-md-9 col-sm-12 text-md-right hidden-xs">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-12">

                <div id="navbar-animmenu">
                    <ul class="show-dropdown main-navbar">
                        <div class="hori-selector" style="margin-left: 20px;"><div class="left"></div><div class="right"></div></div>
                        <li  class="active"><a href="{{ route('admin.integration.index') }}" data-id="courier">Courier Integrations</a></li>
                        <li> <a href="{{ route('admin.integration.channel') }}" data-id="channel">Channel Integrations</a></li>
                        <!--<li><a href="javascript:void(0);" data-id="thirdparty">Thirdparty Integrations</a></li>-->
                    </ul>
                </div>



                <div class="card mt-30 courier ">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Courier Integrations</h5>
                    </div>
                    <div class="card-body">
                        <h4>Select courier and fill the API credentials</h4>
                        <div class="row" style="padding:10px;">
                            <div class="card col-5 courier" style="background-color: #e7e5e5;border-radius: 10px;">
                                <div class="row" style="padding: 20px;">
                                    @foreach($data['couriers'] as $key => $row)
                                        <label for="radio-card-{{ $loop->iteration }}" class="card courier radio-card courierselect" style="margin-bottom:10px;border-radius:10px;">
                                            <div class="row">
                                                <span class="check-icon"></span>
                                                <div class="col-1"><input type="radio" name="courier" value="courier_{{ $key }}" id="radio-card-{{ $loop->iteration }}" @if($loop->iteration == 1) checked  @endif / style="margin:10px"></div>
                                                <div class="col-2"><img src="{{ asset('public/courier/'.$row['image']) }}" alt="" style="margin: 10px;max-width:50px"></div>
                                                <div class="col-9" style="margin-top: 25px;"><h4>{{ $row['name'] }}</h4></div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card col-7 courier" style="background-color: #e7e5e5;border-radius: 10px;">
                                <div class="card mt-30 courier">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">General Info</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="courier_1">
                                            <form action="{{ route('admin.integration.store') }}" class="row" method="post">
                                                @csrf
                                                <x-field type="text" label="Carrier Title" placeholder="Carrier Title" value="{{ @$data['ecom']->ecarrier_title }}" name="ecarrier_title" required="required" size="col-md-6" />
                                                <x-field type="text" label="Username" placeholder="Username" name="eusername" value="{{ @$data['ecom']->eusername }}" required="required" size="col-md-6" />
                                                <x-field type="text" label="Password" placeholder="Password" name="epassword" value="{{ @$data['ecom']->epassword }}" required="required" size="col-md-6" />
                                                <x-field type="text" label="Customer Code" placeholder="Customer Code" value="{{ @$data['ecom']->customer_code }}"  name="customer_code" required="required" size="col-md-6" />
                                                <x-field type="select" name="otp_enable" size="col-md-6" label="OTP_Enable" value="{{ @$data['ecom']->otp_enable }}" required="required" :options="[['id'=>'yes','name'=>'Yes'],['id'=>'no','name'=>'No']]" print="name" store="id"/>
                                                <input type="hidden" name="courier" value="1">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                    @if(in_array(1,$data['check']))
                                                        <a href="{{ route('admin.integration.remove_courier',1) }}" class="btn btn-danger">Remove Courier</a>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                        <div class="courier_2 hide">
                                            <form action="{{ route('admin.integration.store') }}" class="row" method="post">
                                                @csrf
                                                <x-field type="text" label="Carrier Title" placeholder="Carrier Title" name="dcarrier_title" size="col-md-6" required="required" value="{{ @$data['delhivery']->dcarrier_title }}"/>
                                                <x-field class="form-group" type="text" label="Client" placeholder="Client" name="dclient" required="required" size="col-md-6" value="{{ @$data['delhivery']->dclient }}" />
                                                <x-field class="form-group" type="text" label="Api Token" placeholder="Api Token" name="dapi_token" required="required" size="col-md-6" value="{{ @$data['delhivery']->dapi_token }}" />
                                                <x-field type="select" name="dship_mode" size="col-md-6" label="Shipping Mode" value="{{ @$data['delhivery']->dship_mode }}" required="required" :options="[['id'=>'Surface','name'=>'Surface'],['id'=>'Express','name'=>'Express']]" print="name" store="id"/>
                                                <input type="hidden" name="courier" value="2">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                    @if(in_array(2,$data['check']))
                                                        <a href="{{ route('admin.integration.remove_courier',2) }}" class="btn btn-danger">Remove Courier</a>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                        <div class="courier_3 hide">
                                            <form action="{{ route('admin.integration.store') }}" class="row" method="post">
                                                @csrf
                                                    <x-field type="text" label="Carrier Title" placeholder="Carrier Title" value="{{ @$data['bludart']->bcarrier_title }}" name="bcarrier_title" size="col-md-6" required="required" />
                                                    <x-field type="select" name="server" size="col-md-6" label="Account Mode" value="1" value="{{ @$data['bludart']->server }}" required="required" :options="[['id'=>'1','name'=>'Production Server'],['id'=>'2','name'=>'Test Server']]" print="name" store="id"/>
                                                    <x-field type="text" label="Login ID" size="col-md-6" placeholder="Login ID" name="login_id" value="{{ @$data['bludart']->login_id }}" required="required" />
                                                    <x-field type="text" label="Licence Key" size="col-md-6" placeholder="Licence Key" name="licence_key" value="{{ @$data['bludart']->licence_key }}" required="required" />
                                                    <x-field type="text" label="Vendor Code" size="col-md-6" placeholder="Vendor Code" name="vendor_code" value="{{ @$data['bludart']->vendor_code }}" required="required" />
                                                    <x-field type="text" label="Origin Area" size="col-md-6" placeholder="Origin Area" name="origin_area" value="{{ @$data['bludart']->origin_area }}" required="required" />
                                                    <x-field type="text" label="Customer Code(Pre-Paid)" size="col-md-6" name="pre_paid" value="{{ @$data['bludart']->pre_paid }}" required="required" />
                                                    <x-field type="text" label="Customer Code(COD)" size="col-md-6" name="cod" value="{{ @$data['bludart']->cod }}" required="required" />
                                                    <x-field type="select" name="isToPayCustomer" size="col-md-6" label="Is To Pay Customer" value="{{ @$data['bludart']->isToPayCustomer }}" required="required" :options="[['id'=>'true','name'=>'Yes'],['id'=>'false','name'=>'No']]" print="name" store="id"/>
                                                    <div class="form-group col-md-6">
                                                        <label class="form-control-label">Check if your service belongs to APEX DART PLUS:</label>
                                                        <input type="checkbox" name="packtype" value="1" @if(@$data['bludart']->packtype == 1) checked @endif>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="form-control-label"> Select Yes if you want to share your GST/IGST
                                                            data with your Courier:</label>
                                                        <input type="radio" name="gst_status" value="1" @if(@$data['bludart']->gst_status == 1) checked @endif> Yes
                                                        <input type="radio" name="gst_status" value="0" @if(@$data['bludart']->gst_status == 0) checked @endif> No
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="form-control-label"> Auto Pickup:</label>
                                                        <input type="radio" name="auto_pickup" value="true" @if(@$data['bludart']->auto_pickup == true) checked @endif> Yes
                                                        <input type="radio" name="auto_pickup" value="false" @if(@$data['bludart']->auto_pickup == false) checked @endif> No
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="form-control-label"> OTP based delivery :</label>
                                                        <input type="radio" name="otp_no" value="true" @if(@$data['bludart']->otp_no == true) checked @endif> Yes
                                                        <input type="radio" name="otp_no" value="false" @if(@$data['bludart']->otp_no == false) checked @endif> No
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="form-control-label"></label> Esclation Delivery :</label>
                                                        <input type="radio" name="esclation_status" value="true" @if(@$data['bludart']->esclation_status == true) checked @endif> Yes
                                                        <input type="radio" name="esclation_status" value="false" @if(@$data['bludart']->esclation_status == false) checked @endif>
                                                        No
                                                    </div>
                                                <input type="hidden" name="courier" value="3">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                    @if(in_array(3,$data['check']))
                                                        <a href="{{ route('admin.integration.remove_courier',3) }}" class="btn btn-danger">Remove Courier</a>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                        <div class="courier_4 hide">
                                            <form action="{{ route('admin.integration.store') }}" class="row" method="post">
                                                @csrf
                                                <x-field type="text" label="Carrier Title" placeholder="Carrier Title" name="xcarrier_title" required="required" size="col-md-6" value="{{ @$data['express']->xcarrier_title }}"/>
                                                <x-field type="select" name="xnshipment_type" size="col-md-6" label="Shipment Type" value="{{ @$data['express']->xnshipment_type }}" required="required" :options="[['id'=>'Forward','name'=>'Forward'],['id'=>'Reverse','name'=>'Reverse']]" print="name" store="id"/>
                                                <x-field type="text" label="XBKey" placeholder="XBKey" size="col-md-6" name="xxb_key" required="required" value="{{ @$data['express']->xxb_key }}" />
                                                <x-field type="select" name="xnaccount_mode" size="col-md-6" label="Account Mode" value="{{ @$data['express']->xnaccount_mode }}" required="required" :options="[['id'=>'Live','name'=>'Live'],['id'=>'Test','name'=>'Test']]" print="name" store="id"/>
                                                <x-field type="text" label="Username" placeholder="Username" size="col-md-6" name="xusername" value="{{ @$data['express']->xusername }}" required="required" />
                                                <x-field type="text" label="Password" placeholder="Password" size="col-md-6" name="xpassword" value="{{ @$data['express']->xpassword }}" required="required" />
                                                <x-field type="text" label="Secret Key" placeholder="Secret Key" size="col-md-6" name="secret_key" value="{{ @$data['express']->secret_key }}" required="required" />
                                                <x-field type="text" label="Business Account Name" name="b_account_name" size="col-md-6" value="{{ @$data['express']->b_account_name }}" required="required" />
                                                <x-field type="text" label="Vendor Code" placeholder="Vendor Code" name="vendor_code" size="col-md-6" value="{{ @$data['express']->vendor_code }}" required="required" />
                                                <input type="hidden" name="courier" value="4">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                    @if(in_array(4,$data['check']))
                                                        <a href="{{ route('admin.integration.remove_courier',4) }}" class="btn btn-danger">Remove Courier</a>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                        <div class="courier_13 hide">
                                            <form action="{{ route('admin.integration.store') }}" class="row" method="post">
                                                @csrf
                                                <x-field type="text" label="Carrier Title" placeholder="Carrier Title" name="pu_carrier_title" required="required" size="col-md-6" value="{{ @$data['parcel_uncle']->pu_carrier_title }}"/>
                                                <x-field type="text" label="API Key" placeholder="pu_test_... or pu_live_..." size="col-md-6" name="pu_api_key" required="required" value="{{ @$data['parcel_uncle']->pu_api_key }}" />
                                                <x-field type="select" name="pu_mode" size="col-md-6" label="Account Mode" value="{{ @$data['parcel_uncle']->pu_mode }}" required="required" :options="[['id'=>'Test','name'=>'Sandbox (Test)'],['id'=>'Live','name'=>'Production (Live)']]" print="name" store="id"/>
                                                <input type="hidden" name="courier" value="13">
                                                <div class="col-md-12 mt-2">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                    @if(in_array(13,$data['check']))
                                                        <a href="{{ route('admin.integration.remove_courier',13) }}" class="btn btn-danger">Remove Courier</a>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card mt-30 thirdparty hide">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thirdparty Integrations</h5>
                    </div>
                    <div class="card-body">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        "use strict";
        (function($){
            $('.courierselect').on('change', function(){
                let courier = $("input[name='courier']:checked").val(); 
                $('[class^="courier_"]').addClass('hide');
                $('.' + courier).removeClass('hide');
            });
        })(jQuery);
    </script>
    

@endsection


