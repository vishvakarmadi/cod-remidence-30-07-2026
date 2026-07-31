@extends('admin.admin_layouts')
@section('admin_content')
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
                        <li class="active"><a href="javascript:void(0);" data-id="ratecard">Rate</a></li>
                        <li><a href="javascript:void(0);" data-id="calculator">Rate Calculator</a></li>
                    </ul>
                </div>



                <div class="card mt-30 ratecard">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Rate Card</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>Air ( 0.5 kg )</th>
                                    </tr>
                                    <tr>
                                        <th>Courier Name</th>
                                        <th class="text-center">Mode</th>
                                        <th>Within City</th>
                                        <th>Within State</th>
                                        <th>Metro to Metro</th>
                                        <th>Rest of India</th>
                                        <th>North East, J&K</th>
                                        <th>COD Charges</th>
                                        <th>COD%</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rate as $key => $row)
                                        @if($row->transport == 'Air' && $row->weight == 0.5)
                                            <tr>
                                                <td>{{ $couriers[$row->courier_id]['name'] }}</td>
                                                <td class="text-center"><i class="fa fa-plane"></i></td>
                                                <td>Rs {{ $row->within_city }}.00</td>
                                                <td>Rs {{ $row->within_state }}.00</td>
                                                <td>Rs {{ $row->metro_to_metro }}.00</td>
                                                <td>Rs {{ $row->rest_of_india }}.00</td>
                                                <td>Rs {{ $row->north_east }}.00</td>
                                                <td>Rs {{ $row->cod_charges }}.00</td>
                                                <td>{{ $row->cod }}%</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div><br>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>Surface ( 0.5 kg )</th>
                                    </tr>
                                    <tr>
                                        <th>Courier Name</th>
                                        <th class="text-center">Mode</th>
                                        <th>Within City</th>
                                        <th>Within State</th>
                                        <th>Metro to Metro</th>
                                        <th>Rest of India</th>
                                        <th>North East, J&K</th>
                                        <th>COD Charges</th>
                                        <th>COD%</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rate as $key => $row)
                                        @if($row->transport == 'Surface' && $row->weight == 0.5)
                                            <tr>
                                                <td>{{ $couriers[$row->courier_id]['name'] }}</td>
                                                <td class="text-center"><i class="fa fa-truck"></i></td>
                                                <td>Rs {{ $row->within_city }}.00</td>
                                                <td>Rs {{ $row->within_state }}.00</td>
                                                <td>Rs {{ $row->metro_to_metro }}.00</td>
                                                <td>Rs {{ $row->rest_of_india }}.00</td>
                                                <td>Rs {{ $row->north_east }}.00</td>
                                                <td>Rs {{ $row->cod_charges }}.00</td>
                                                <td>{{ $row->cod }}%</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div><br>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>Air ( 1 kg )</th>
                                    </tr>
                                    <tr>
                                        <th>Courier Name</th>
                                        <th class="text-center">Mode</th>
                                        <th>Within City</th>
                                        <th>Within State</th>
                                        <th>Metro to Metro</th>
                                        <th>Rest of India</th>
                                        <th>North East, J&K</th>
                                        <th>COD Charges</th>
                                        <th>COD%</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rate as $key => $row)
                                        @if($row->transport == 'Air' && $row->weight == 1)
                                            <tr>
                                                <td>{{ $couriers[$row->courier_id]['name'] }}</td>
                                                <td class="text-center"><i class="fa fa-plane"></i></td>
                                                <td>Rs {{ $row->within_city + @$get->within_city }}.00</td>
                                                <td>Rs {{ $row->within_state + @$get->within_state }}.00</td>
                                                <td>Rs {{ $row->metro_to_metro + @$get->metro_to_metro }}.00</td>
                                                <td>Rs {{ $row->rest_of_india + @$get->rest_of_india }}.00</td>
                                                <td>Rs {{ $row->north_east + @$get->north_east }}.00</td>
                                                <td>Rs {{ $row->cod_charges }}.00</td>
                                                <td>{{ $row->cod }}%</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table><br>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>Surface ( 1 kg )</th>
                                        </tr>
                                        <tr>
                                            <th>Courier Name</th>
                                            <th class="text-center">Mode</th>
                                            <th>Within City</th>
                                            <th>Within State</th>
                                            <th>Metro to Metro</th>
                                            <th>Rest of India</th>
                                            <th>North East, J&K</th>
                                            <th>COD Charges</th>
                                            <th>COD%</th>
    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rate as $key => $row)
                                            @if($row->transport == 'Surface' && $row->weight == 1)
                                                <tr>
                                                    <td>{{ $couriers[$row->courier_id]['name'] }}</td>
                                                    <td class="text-center"><i class="fa fa-truck"></i></td>
                                                    <td>Rs {{ $row->within_city + @$get->within_city }}.00</td>
                                                    <td>Rs {{ $row->within_state + @$get->within_state }}.00</td>
                                                    <td>Rs {{ $row->metro_to_metro + @$get->metro_to_metro }}.00</td>
                                                    <td>Rs {{ $row->rest_of_india + @$get->rest_of_india }}.00</td>
                                                    <td>Rs {{ $row->north_east + @$get->north_east }}.00</td>
                                                    <td>Rs {{ $row->cod_charges }}.00</td>
                                                    <td>{{ $row->cod }}%</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table><br><br>

                                <h3>Terms & Condition</h3>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            <tr>
                                                <th>Terms</th>
                                                <th>Condition</th>
                                                @if (Auth::guard('admin')->user()->role_id == 1)  
                                                <th>Action</th>
                                                @endif
                                            </tr>
                                        </thead>
        
                                        <tbody>
                                            @foreach ($terms as $row)
                                                    <tr>
                                                        <td>{{ $row->terms }}</td>
                                                        <td>{{ $row->conditions }}</td>
                                                        @if (Auth::guard('admin')->user()->role_id == 1)  
                                                        <td><a class="btn btn-primary btn-sm" style="cursor: pointer;"
                                                            href="{{ route('admin.rate.termedit',$row->id)}}">
                                                            Edit
                                                            </a></td>
                                                        @endif    
                                                    </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (Auth::guard('admin')->user()->role_id == 1)
                    <div class="card pt-30 ratecard">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Rate Card Excel Upload</h5>
                    </div>
                    <div class="card-body">
                        <form class="product-form" action="{{ route('admin.bulkrate.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h4>Import Rate File</h4>
                            <a href="{{ asset('public/ratecards.xlsx') }}" download="" class="btn btn-secondary">Download
                                Format</a>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">User</label><span class="required"> *</span>
                                    <select class="form-control" name="user_id" required>
                                        <option value="">Select User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->id }} {{ $user->name }} - ({{ $user->mobile }})</option>
                                        @endforeach
                                        <option value="0">Default RateCard</option>
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="excel" id="profilePicUpload1" required
                                                    accept=".xlsx">
                                                <label for="profilePicUpload1" class="bg-secondary text-white">Upload
                                                    File</label>
                                                <small class="mt-2">Supported files: <b>xlsx</b>.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn-primary btn w-100">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
                <div class="card mt-30 calculator hide">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Rate Calculator</h5>
                    </div>
                    <div class="card-body">
                        <form action="" id="ratecalulator">
                        <div class="row">
                            <x-field type="number" label="Pickup Pincode" placeholder="Pickup Pincode"
                                name="pickup_pin" required="required" />
                            <x-field type="number" label="Drop Pincode" placeholder="Drop Pincode" name="drop_pin"
                                required="required" />
                            <br><br>
                            <div class="form-group col-md-4">
                                <label>Dimension(in Cms)</label><span class="required">*</span><br>
                                <div class="row" style="margin-left: -1px;">
                                <input type="number" class="form-control col-sm-3" name="length" value="10"
                                    required />&nbsp;&nbsp;&nbsp;
                                <input type="number" class="form-control col-sm-3" name="breadth" value="10"
                                    required />&nbsp;&nbsp;&nbsp;
                                <input type="number" class="form-control col-sm-3" name="height" value="10"
                                    required />
                                </div>
                            </div>
                            <x-field type="text" label="Weight (in KGs)" name="weight" required="required"
                                value="1" />
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Prepaid / COD</label><span class="required">
                                    *</span>
                                <select class="form-control" name="payment" required>
                                    <option value="prepaid">Prepaid</option>
                                    <option value="cod">COD</option>
                                </select>
                            </div>
                            <x-field type="number" label="Declared Value" name="value" required="" />
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Shipment Type</label><span class="required">
                                    *</span>
                                <select class="form-control" name="shipment_type" required>
                                    <option value="forward">Forward</option>
                                    <option value="reverse">Reverse</option>
                                </select>
                            </div>
                        </div>
                        </form>
                        <button type="button" class="btn-primary btn h-45 fetchdetails">Calculate</button><br><br>
                        <div id="serviable"></div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    "use strict";
    (function($) {
        $('.fetchdetails').on('click',function(){
            $.get({
                url: "{{ route('admin.rate.calculate') }}",
                data: $('#ratecalulator').serialize(),
                success: function(response) {
                    $('#serviable').empty();
                    if(response['status'] == 1){
                        let html = `<div class="table-responsive">
                                        <h5 class="text-center">Rate for shipping a ${response['request'].weight } kg ${response['request'].payment } packet from ${response['request'].pickup_pin } to ${response['request'].drop_pin }</h5>
                                        <table class="table table-striped table-hover dataTable js-exportable" id="servicedata">
                                            <thead>
                                                <tr>
                                                    <th>Sr No</th>
                                                    <th>Courier Provider</th>
                                                    <th>Zone</th>
                                                    <th>Charged Weight (kg)</th>
                                                    <th>Fright Charge</th>
                                                    <th>COD Charges</th>
                                                    <th>Total Charges</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                        <h5><a href="javascript:void(0)">Above Prices are Exclusive of GST.</a></h5>
                                    </div>`;
                        $('#serviable').html(html);
                        $.each(response['data'], function(index, value) {
                            $('#servicedata').append(`<tr>
                                                        <td>${index + 1}</td>
                                                        <td>${value.courier}</td>
                                                        <td>${value.zone}</td>
                                                        <td>${value.weight}</td>
                                                        <td>${value.freight_charge}</td>
                                                        <td>${value.cod}</td>
                                                        <td>${value.total}</td>
                                                    </tr>`);
                        });
                    } else {
                        toastr.error('Pincode not found');
                    }
                },
                error: function(response) {
                    if (response.responseJSON && response.responseJSON.errors) {
                        var errors = response.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            toastr.error(errors[key][0]);
                        });
                    } else {
                        toastr.error('An error occurred while processing your request.', 'Error');
                    }
                },
            });
        });
    })(jQuery);
</script>
@endsection
