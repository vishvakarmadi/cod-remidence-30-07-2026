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

        <li class="selected" id="page1" onclick="change_tab(this.id);">Rate</li>
        <li class="notselected" id="page2" onclick="change_tab(this.id);">Ratecard</li>
        {{-- <li class="notselected" id="page3" onclick="change_tab(this.id);">Page3</li> --}}

        <div class='hidden_desc' id="page1_desc">
            <div class="block-header">
                <div class="row">
                    <div class="col-lg-5 col-md-8 col-sm-12">
                        <h2>Rate Card</h2>
                    </div>
                    <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                        <ul class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                        class="icon-home"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            @if(isset($rate))
                <div class="row clearfix">
                    <div class="col-12">
                        <div class="card pt-30">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Rate</h5>
                            </div>
                            <div class="card-body">
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                @foreach ($rate as $row)
                                                    @if ($row->id == 1)
                                                        <tr>
                                                            <th>{{ $row->transport }} ( {{ $row->weight }}kg )</th>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr>
                                                    <th>Courier Name</th>
                                                    <th>Within City</th>
                                                    <th>Within State</th>
                                                    <th>Metro to Metro</th>
                                                    <th>Rest of India</th>
                                                    <th>North East, J&K</th>
                                                    <th>COD Charges</th>
                                                    <th>COD%</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($rate as $row)
                                                    @if ($row->weight == 0.5 && $row->transport == 'Air')
                                                        <tr>
                                                            @php
                                                                $courier = DB::table('couriers')
                                                                    ->where('id', $row->courier_id)
                                                                    ->first();
                                                            @endphp
                                                            <td>{{ @$courier->courier }} ( {{ $row->weight }}kg )</td>
                                                            <td>RS {{ $row->within_city }}.00</td>
                                                            <td>RS {{ $row->within_state }}.00</td>
                                                            <td>RS {{ $row->metro_to_metro }}.00</td>
                                                            <td>RS {{ $row->rest_of_india }}.00</td>
                                                            <td>RS {{ $row->north_east }}.00</td>
                                                            <td>RS {{ $row->cod_charges }}.00</td>
                                                            <td>{{ $row->cod }}%</td>
                                                            <td><a class="btn btn-primary btn-sm" style="cursor: pointer;"
                                                                href="{{ route('admin.rate.edit',$row->id)}}">
                                                                Edit
                                                                </a></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                @foreach ($rate as $row)
                                                    @if ($row->id == 5)
                                                        <tr>
                                                            <th>{{ $row->transport }} ( {{ $row->weight }}kg )</th>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr>
                                                    <th>Courier Name</th>
                                                    <th>Within City</th>
                                                    <th>Within State</th>
                                                    <th>Metro to Metro</th>
                                                    <th>Rest of India</th>
                                                    <th>North East, J&K</th>
                                                    <th>COD Charges</th>
                                                    <th>COD%</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($rate as $row)
                                                    @if ($row->weight == 0.5 && $row->transport == 'Surface')
                                                        <tr>
                                                            @php
                                                                $courier = DB::table('couriers')
                                                                    ->where('id', $row->courier_id)
                                                                    ->first();
                                                            @endphp
                                                            <td>{{ @$courier->courier }} ( {{ $row->weight }}kg )</td>
                                                            <td>RS {{ $row->within_city }}.00</td>
                                                            <td>RS {{ $row->within_state }}.00</td>
                                                            <td>RS {{ $row->metro_to_metro }}.00</td>
                                                            <td>RS {{ $row->rest_of_india }}.00</td>
                                                            <td>RS {{ $row->north_east }}.00</td>
                                                            <td>RS {{ $row->cod_charges }}.00</td>
                                                            <td>{{ $row->cod }}%</td>
                                                            <td><a class="btn btn-primary btn-sm" style="cursor: pointer;"
                                                                href="{{ route('admin.rate.edit',$row->id)}}">
                                                                Edit
                                                                </a></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- @php 
                            foreach($rate as $row){
                               if ($row->weight == 0.5 && $row->transport == 'Air'){
                                   $city = $row->within_city;
                                   $state = $row->within_state;
                                   $metro_to_metro = $row->metro_to_metro;
                                   $rest_of_india = $row->rest_of_india;
                                   $north_east = $row->north_east;
                               } }
                           @endphp --}}
                            <div class="card-body">
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                @foreach ($rate as $row)
                                                    @if ($row->id == 2)
                                                        <tr>
                                                            <th>{{ $row->transport }} ( {{ $row->weight }}kg )</th>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr>
                                                    <th>Courier Name</th>
                                                    <th>Within City</th>
                                                    <th>Within State</th>
                                                    <th>Metro to Metro</th>
                                                    <th>Rest of India</th>
                                                    <th>North East, J&K</th>
                                                    <th>COD Charges</th>
                                                    <th>COD%</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                                {{-- @php 
                                                $ratecard = DB::table('ratecards')->where('weight',0.5)->where('transport','Air')->get();
                                                foreach($ratecard as $row){
                                                        $city = $row->within_city;
                                                        $state = $row->within_state;
                                                        $metro_to_metro = $row->metro_to_metro;
                                                        $rest_of_india = $row->rest_of_india;
                                                        $north_east = $row->north_east;
                                                    } 
                                                @endphp  --}}
                                               
                                            <tbody>
                                                @foreach ($rate as $row)
                                                    @if ($row->weight == 1 && $row->transport == 'Air')
                                                    @foreach($ratecard as $data)
                                                        <tr>
                                                            @php
                                                                $courier = DB::table('couriers')
                                                                    ->where('id', $row->courier_id)
                                                                    ->first();
                                                            @endphp
                                                            <td>{{ @$courier->courier }} ( {{ $row->weight }}kg )</td>
                                                            <td>RS {{ $row->within_city + $data->within_city }}.00 + {{ $data->within_city }}</td>
                                                            <td>RS {{ $row->within_state + $data->within_state }}.00 + {{ $data->within_state }}</td>
                                                            <td>RS {{ $row->metro_to_metro + $data->metro_to_metro }}.00 + {{ $data->metro_to_metro }}</td>
                                                            <td>RS {{ $row->rest_of_india + $data->rest_of_india }}.00 + {{ $data->rest_of_india }}</td>
                                                            <td>RS {{ $row->north_east + $data->north_east }}.00 + {{ $data->north_east }}</td>
                                                            <td>RS {{ $row->cod_charges }}.00</td>
                                                            <td>{{ $row->cod }}%</td>
                                                            <td><a class="btn btn-primary btn-sm" style="cursor: pointer;"
                                                                href="{{ route('admin.rate.edit',$row->id)}}">
                                                                Edit
                                                                </a></td>
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                    
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @php 
                            foreach ($rate as $row)
                                if ($row->weight == 0.5 && $row->transport == 'Surface'){
                                    $citys = $row->within_city;
                                    $states = $row->within_state;
                                    $metro_to_metros = $row->metro_to_metro;
                                    $rest_of_indias = $row->rest_of_india;
                                    $north_easts = $row->north_east;
                                }
                            @endphp 
                            <div class="card-body">
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                @foreach ($rate as $row)
                                                    @if ($row->id == 5)
                                                        <tr>
                                                            <th>{{ $row->transport }} ( {{ $row->weight }}kg )</th>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr>
                                                    <th>Courier Name</th>
                                                    <th>Within City</th>
                                                    <th>Within State</th>
                                                    <th>Metro to Metro</th>
                                                    <th>Rest of India</th>
                                                    <th>North East, J&K</th>
                                                    <th>COD Charges</th>
                                                    <th>COD%</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                             
                                            <tbody>
                                                @foreach ($rate as $row)
                                                    @if ($row->weight == 1 && $row->transport == 'Surface')
                                                        <tr>
                                                            @php
                                                                $courier = DB::table('couriers')
                                                                    ->where('id', $row->courier_id)
                                                                    ->first();
                                                            @endphp
                                                            <td>{{ @$courier->courier }} ( {{ $row->weight }}kg )</td>
                                                            <td>RS {{ $row->within_city + $citys }}.00 </td>
                                                            <td>RS {{ $row->within_state + $states }}.00</td>
                                                            <td>RS {{ $row->metro_to_metro + $metro_to_metros }}.00</td>
                                                            <td>RS {{ $row->rest_of_india + $rest_of_indias }}.00</td>
                                                            <td>RS {{ $row->north_east + $north_easts }}.00</td>
                                                            <td>RS {{ $row->cod_charges }}.00</td>
                                                            <td>{{ $row->cod }}%</td>
                                                            <td><a class="btn btn-primary btn-sm" style="cursor: pointer;"
                                                                href="{{ route('admin.rate.edit',$row->id)}}">
                                                                Edit
                                                                </a></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>Terms</th>
                                                    <th>Condition</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
            
                                            <tbody>
                                                @foreach ($terms as $row)
                                                        <tr>
                                                            <td>{{ $row->terms }}</td>
                                                            <td>{{ $row->conditions }}</td>
                                                            <td><a class="btn btn-primary btn-sm" style="cursor: pointer;"
                                                                href="{{ route('admin.rate.termedit',$row->id)}}">
                                                                Edit
                                                                </a></td>
                                                        </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <hr>
                    
                </div>
            @endif
        </div>
        <div class='hidden_desc' id="page2_desc">
            <div class="block-header">
                <div class="row">
                    <div class="col-lg-5 col-md-8 col-sm-12">
                        <h2>Rate Card</h2>
                    </div>
                    <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                        <ul class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                        class="icon-home"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <form action="{{ route('admin.rate') }}" method="get">
                    @csrf
                    <div class="col-12">
                        <div class="card pt-30">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Rate Calculator</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <x-field type="text" label="Pickup Pincode" placeholder="Pickup Pincode"
                                        name="pickup_pin" required="required" />
                                    <x-field type="text" label="Drop Pincode" placeholder="Drop Pincode" name="drop_pin"
                                        required="required" />
                                    <br><br>
                                    <div class="form-group col-md-4">
                                        <label>Dimension(in Cms)</label><span class="required">*</span><br>
                                        <input type="number" class="form-group col-sm-3" name="length" value="10"
                                            required />
                                        <input type="number" class="form-group col-sm-3" name="breadth" value="10"
                                            required />
                                        <input type="number" class="form-group col-sm-3" name="height" value="10"
                                            required />
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
                                            <option value="Forward">Forward</option>
                                            <option value="Reverse">Reverse</option>
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
                                <button type="submit" id="btn"
                                    class="btn-primary btn h-45 w-100">Calculate</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <hr>
            <!-- Display section for the JSON response -->
            @if(isset($pin ))
            <div class="row mt-30" id="responseSection" style="display: block;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Rate for shipping a {{ $weight }}kg @if($payment == "cod") {{ strtoupper($payment) }} @else {{ucfirst($payment)}} @endif packet from {{ $pickupPincode }} to {{ $dropPincode }}</h5>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SNO</th>
                                        <th>Courier Provider</th>
                                        <th>Charged Weight (kg)</th>
                                        <th>Freight Charge</th>
                                        <th>COD Charges</th>
                                        <th>GST</th>
                                        <th>Total Charges</th>
                                    </tr>
                                </thead>
                                <tbody id="responseData">
                                    @foreach($rate_weight as $row)
                                    
                                    @php 
                                    if($value && $payment == 'cod'){
    $within_citygst_cod = ($value + $row->cod_charges) * (0.18); 
    $within_citygst2_cod = ($value * ($row->cod)/100) * (0.18);
    $within_citygstmax_cod = max($within_citygst_cod,$within_citygst2_cod);
    
    $total_cod = $value + $row->cod_charges + $within_citygstmax_cod;
    }
    
    if($value){
    $within_citygst = ($value + 0.00) * (0.18); 
    $within_citygst2 = ($value * 0.00) * (0.18);
    $within_citygstmax = max($within_citygst,$within_citygst2);
    $total = $value + 0.00 + $within_citygstmax;
    }
    if($value && $reverse == 'Reverse'){
    $within_cityrevgst = (($value * 1.5) + 0.00) * (0.18); 
    $within_cityrevgst2 = (($value * 1.5) * 0.00) * (0.18);
    $within_cityrevgstmax = max($within_cityrevgst,$within_cityrevgst2);
    $totalrev = ($value *1.5) +  $within_cityrevgstmax;
    }
    if($value && $payment == 'cod' && $reverse == 'Reverse'){
    $within_cityrevgst_cod = (($value * 1.5) + $row->cod_charges) * (0.18); 
    $within_cityrevgst2_cod = (($value * 1.5) * ($row->cod)/100) * (0.18);
    $within_cityrevgstmax_cod = max($within_cityrevgst_cod,$within_cityrevgst2_cod);
    
    $total_cod_rev = ($value*1.5) + $row->cod_charges + $within_cityrevgstmax_cod;
    }

    if($value && $payment == 'cod'){
    $within_stategst_cod = ($value + $row->cod_charges) * (0.18); 
    $within_stategst2_cod = ($value * ($row->cod)/100) * (0.18);
    $within_stategstmax_cod = max($within_stategst_cod,$within_stategst2_cod);
    
    $total_statecod = $value + $row->cod_charges + $within_stategstmax_cod;
    }
    if($value){
    $within_stategstpre = ($value + 0.00) * (0.18); 
    $within_stategst2pre = ($value * 0.00) * (0.18);
    $within_stategstmaxpre = max($within_stategstpre,$within_stategst2pre);
    $total_pre = $value + 0.00 + $within_stategstmaxpre;
    }

    if($value && $payment == 'cod' && $reverse == 'Reverse'){
    $within_staterevgst_cod = (($value * 1.5) + $row->cod_charges) * (0.18); 
    $within_staterevgst2_cod = (($value * 1.5) * ($row->cod)/100) * (0.18);
    $within_staterevgstmax_cod = max($within_staterevgst_cod,$within_staterevgst2_cod);
    
    $total_staterevcod = ($value * 1.5) + $row->cod_charges + $within_staterevgstmax_cod;
    }
    if($value && $reverse == 'Reverse'){
    $within_staterevgstpre = (($value * 1.5) + 0.00) * (0.18); 
    $within_staterevgst2pre = (($value * 1.5) * 0.00) * (0.18);
    $within_staterevgstmaxpre = max($within_staterevgstpre,$within_staterevgst2pre);
    $total_pre_rev = ($value * 1.5) + 0.00 + $within_staterevgstmaxpre;
    }
    if($value && $payment == 'cod'){
    $metro_to_metrogst_cod = ($value + $row->cod_charges) * (0.18); 
    $metro_to_metrogst2_cod = ($value * ($row->cod)/100) * (0.18);
    $metro_to_metrogstmax_cod = max($metro_to_metrogst_cod,$metro_to_metrogst2_cod);
    
    $metro_to_metrocod = $value + $row->cod_charges + $metro_to_metrogstmax_cod;
    }
    if($value){
    $metro_to_metrogstpre = ($value + 0.00) * (0.18); 
    $metro_to_metrogst2pre = ($value * 0.00) * (0.18);
    $metro_to_metrogstmaxpre = max($metro_to_metrogstpre,$metro_to_metrogst2pre);
    $metro_to_metropre = $value + 0.00 + $metro_to_metrogstmaxpre;
    }
    if($value && $payment == 'cod' && $reverse == 'Reverse'){
    $metro_to_metrorevgst_cod = ($value + $row->cod_charges) * (0.18); 
    $metro_to_metrorevgst2_cod = ($value * ($row->cod)/100) * (0.18);
    $metro_to_metrorevgstmax_cod = max($metro_to_metrorevgst_cod,$metro_to_metrorevgst2_cod);
    
    $metro_to_metrorevcod = $value + $row->cod_charges + $metro_to_metrorevgstmax_cod;
    }
    if($value  && $reverse == 'Reverse'){
    $metro_to_metrorevgstpre = ($value + 0.00) * (0.18); 
    $metro_to_metrorevgst2pre = ($value * 0.00) * (0.18);
    $metro_to_metrorevgstmaxpre = max($metro_to_metrorevgstpre,$metro_to_metrorevgst2pre);
    $metro_to_metrorevpre = $value + 0.00 + $metro_to_metrorevgstmaxpre;
    }
    if($value && $payment == 'cod'){
    $rest_of_indiagst_cod = ($value + $row->cod_charges) * (0.18); 
    $rest_of_indiagst2_cod = ($value * ($row->cod)/100) * (0.18);
    $rest_of_indiagstmax_cod = max($rest_of_indiagst_cod,$rest_of_indiagst2_cod);
    
    $rest_of_indiacod = $value + $row->cod_charges + $rest_of_indiagstmax_cod;
    }
    if($value){
    $rest_of_indiagstpre = ($value + 0.00) * (0.18); 
    $rest_of_indiagst2pre = ($value * 0.00) * (0.18);
    $rest_of_indiagstmaxpre = max($rest_of_indiagstpre,$rest_of_indiagst2pre);
    $rest_of_indiapre = $value + 0.00 + $rest_of_indiagstmaxpre;
    }
    if($value && $payment == 'cod' && $reverse == 'Reverse'){
    $rest_of_indiarevgst_cod = ($value + $row->cod_charges) * (0.18); 
    $rest_of_indiarevgst2_cod = ($value * ($row->cod)/100) * (0.18);
    $rest_of_indiarevgstmax_cod = max($rest_of_indiarevgst_cod,$rest_of_indiarevgst2_cod);
    
    $rest_of_indiarevcod = $value + $row->cod_charges + $rest_of_indiarevgstmax_cod;
    }
    if($value && $reverse == 'Reverse'){
    $rest_of_indiarevgstpre = ($value + 0.00) * (0.18); 
    $rest_of_indiarevgst2pre = ($value * 0.00) * (0.18);
    $rest_of_indiarevgstmaxpre = max($rest_of_indiarevgstpre,$rest_of_indiarevgst2pre);
    $rest_of_indiarevpre = $value + 0.00 + $rest_of_indiarevgstmaxpre;
    }
    if($value && $payment == 'cod'){
    $north_eastgst_cod = ($value + $row->cod_charges) * (0.18); 
    $north_eastgst2_cod = ($value * ($row->cod)/100) * (0.18);
    $north_eastgstmax_cod = max($north_eastgst_cod,$north_eastgst2_cod);
    
    $north_eastcod = $value + $row->cod_charges + $north_eastgstmax_cod;
    }
    if($value){
    $north_eastgstpre = ($value + 0.00) * (0.18); 
    $north_eastgst2pre = ($value * 0.00) * (0.18);
    $north_eastgstmaxpre = max($north_eastgstpre,$north_eastgst2pre);
    $north_eastpre = $value + 0.00 + $north_eastgstmaxpre;
    }
    if($value && $payment == 'cod' && $reverse == 'Reverse'){
    $north_eastrevgst_cod = ($value + $row->cod_charges) * (0.18); 
    $north_eastrevgst2_cod = ($value * ($row->cod)/100) * (0.18);
    $north_eastrevgstmax_cod = max($north_eastrevgst_cod,$north_eastrevgst2_cod);
    
    $north_eastrevcod = $value + $row->cod_charges + $north_eastrevgstmax_cod;
    }
    if($value && $reverse == 'Reverse'){
    $north_eastrevgstpre = ($value + 0.00) * (0.18); 
    $north_eastrevgst2pre = ($value * 0.00) * (0.18);
    $north_eastrevgstmaxpre = max($north_eastrevgstpre,$north_eastrevgst2pre);
    $north_eastrevpre = $value + 0.00 + $north_eastrevgstmaxpre;
    }
                                    @endphp
                                        <tr>
                                            
                                            <td>{{ $loop->iteration }}</td>
                                            @php
                                                $courier = DB::table('couriers')
                                                    ->where('id', $row->courier_id)
                                                    ->first();
                                            @endphp
                                            <td>{{ @$courier->courier }} {{ $row->transport }} ( {{ $row->weight }}kg )</td>
                                            <td>{{ $row->weight }}</td>
                                            @if($pick->Zone == 'Z1' && $drop->Zone == 'Z1')
                                                <td>Rs.@if($reverse == 'Reverse') {{ $row->within_city * 1.5 }}  @else {{ $row->within_city }} @endif</td>
                                            @elseif($pick->Zone == 'Z2' && $drop->Zone == 'Z2')
                                                <td>Rs.@if($reverse == 'Reverse') {{ $row->within_state * 1.5 }}  @else {{ $row->within_state }} @endif</td>
                                            @elseif($pick->Zone == 'Z3' && $drop->Zone == 'Z3')
                                                <td>Rs.@if($reverse == 'Reverse') {{ $row->metro_to_metro * 1.5 }}  @else {{ $row->metro_to_metro }} @endif</td>
                                            @elseif($pick->Zone == 'Z4' && $drop->Zone == 'Z4')
                                                <td>Rs.@if($reverse == 'Reverse') {{ $row->rest_of_india * 1.5 }}  @else {{ $row->rest_of_india }} @endif</td>
                                            @elseif($pick->Zone == 'Z5' && $drop->Zone == 'Z5')
                                                <td>Rs.@if($reverse == 'Reverse') {{ $row->north_east * 1.5 }}  @else {{ $row->north_east }} @endif</td>
                                            @endif
                                            @if($payment == 'cod')
                                                <td>Rs.{{ $row->cod_charges }}</td>
                                            @else
                                                <td>Rs. 0.00</td> 
                                            @endif
                                            @if($row->within_city && $payment == 'cod')
                                            <td>Rs.@if($reverse == 'Reverse') {{ $within_cityrevgstmax_cod }}  @else {{ $within_citygstmax_cod }} @endif</td>
                                            <td>Rs.@if($reverse == 'Reverse') {{ $total_cod_rev }} @else {{ $total_cod }} @endif</td>
                                            @elseif($row->within_city && $payment == 'prepaid')
                                            <td>Rs.@if($reverse == 'Reverse') {{ $within_cityrevgstmax }} @else {{ $within_citygstmax }} @endif</td>
                                            <td>Rs.@if($reverse == 'Reverse') {{ $totalrev }} @else {{ $total }} @endif</td>
                                            
                                            @elseif($row->within_state && $payment == 'cod')
                                            <td>Rs.@if($reverse == 'Reverse') {{ $within_staterevgstmax_cod }} @else {{ $within_stategstmax_cod }} @endif</td>
                                            <td>Rs.@if($reverse == 'Reverse') {{ $total_staterevcod }} @else {{ $total_statecod }} @endif</td>
                                            @elseif($row->within_state && $payment == 'prepaid')
                                            <td>Rs.@if($reverse == 'Reverse') {{ $within_staterevgstmaxpre }} @else {{ $within_stategstmaxpre }} @endif</td>
                                            <td>Rs.@if($reverse == 'Reverse') {{ $total_pre_rev }} @else {{ $total_pre }} @endif</td>
                                            
                                            @elseif($row->metro_to_metro && $payment == 'cod')
                                            <td>Rs.@if($reverse == 'Reverse') {{ $metro_to_metrorevgstmax_cod }} @else {{ $metro_to_metrogstmax_cod }} @endif</td>
                                            <td>Rs.@if($reverse == 'Reverse') {{ $metro_to_metrorevcod }} @else {{ $metro_to_metrocod }} @endif</td>
                                            @elseif($row->metro_to_metro && $payment == 'prepaid')
                                            <td>Rs.@if($reverse == 'Reverse') {{ $metro_to_metrorevgstmaxpre }} @else {{ $metro_to_metrogstmaxpre }} @endif</td>
                                            <td>Rs.@if($reverse == 'Reverse') {{ $metro_to_metrorevpre }} @else {{ $metro_to_metropre }} @endif</td>
                                           
                                            @elseif($row->rest_of_india && $payment == 'cod')
                                            <td>Rs.@if($reverse == 'Reverse') {{ $rest_of_indiarevgstmax_cod }} @else {{ $rest_of_indiagstmax_cod }} @endif</td>
                                            <td>Rs.@if($reverse == 'Reverse') {{ $rest_of_indiarevcod }} @else {{ $rest_of_indiacod }} @endif</td>
                                            @elseif($row->rest_of_india && $payment == 'prepaid')
                                            <td>Rs.@if($reverse == 'Reverse') {{ $rest_of_indiarevgstmaxpre }} @else {{ $rest_of_indiagstmaxpre }} @endif</td>
                                            <td>Rs.@if($reverse == 'Reverse') {{ $rest_of_indiarevpre }} @else {{ $rest_of_indiapre }} @endif</td>
                                            
                                            @elseif($row->north_east && $payment == 'cod')
                                            <td>Rs.@if($reverse == 'Reverse') {{ $north_eastrevgstmax_cod }} @else {{ $north_eastgstmax_cod }} @endif</td>
                                            <td>Rs.@if($reverse == 'Reverse') {{ $north_eastrevcod }} @else {{ $north_eastcod }} @endif</td>
                                            @elseif($row->north_east && $payment == 'prepaid')
                                            <td>Rs.@if($reverse == 'Reverse') {{ $north_eastrevgstmaxpre }} @else {{ $north_eastgstmaxpre }} @endif</td>
                                            <td>Rs.@if($reverse == 'Reverse') {{ $north_eastrevpre }} @else {{ $north_eastpre }} @endif</td>
                                           
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @elseif(isset($message))
            <h1>{{ $message }}</h1>
            @endif
        </div>

        {{-- <div class='hidden_desc' id="page3_desc">
            <h2>Page 3</h2>
            Hello this is Page 3 description and this is just a sample text .This is the demo of Multiple Tab In Single Page
            Using JavaScript and CSS.
            Hello this is Page 3 description and this is just a sample text .This is the demo of Multiple Tab In Single Page
            Using JavaScript and CSS.
        </div> --}}

        <div id="page_content">
            <div class="block-header">
                <div class="row">
                    <div class="col-lg-5 col-md-8 col-sm-12">
                        <h2>Rate Card</h2>
                    </div>
                    <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                        <ul class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                        class="icon-home"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-12">
                    <div class="card pt-30">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Rate</h5>
                        </div>
                        <div class="card-body">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            @foreach ($rate as $row)
                                                @if ($row->id == 1)
                                                    <tr>
                                                        <th>{{ $row->transport }} ( {{ $row->weight }}kg )</th>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            <tr>
                                                <th>Courier Name</th>
                                                <th>Within City</th>
                                                <th>Within State</th>
                                                <th>Metro to Metro</th>
                                                <th>Rest of India</th>
                                                <th>North East, J&K</th>
                                                <th>COD Charges</th>
                                                <th>COD%</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($rate as $row)
                                                @if ($row->weight == 0.5 && $row->transport == 'Air')
                                                    <tr>
                                                        @php
                                                            $courier = DB::table('couriers')
                                                                ->where('id', $row->courier_id)
                                                                ->first();
                                                        @endphp
                                                        <td>{{ @$courier->courier }} ( {{ $row->weight }}kg )</td>
                                                        <td>RS {{ $row->within_city }}.00</td>
                                                        <td>RS {{ $row->within_state }}.00</td>
                                                        <td>RS {{ $row->metro_to_metro }}.00</td>
                                                        <td>RS {{ $row->rest_of_india }}.00</td>
                                                        <td>RS {{ $row->north_east }}.00</td>
                                                        <td>RS {{ $row->cod_charges }}.00</td>
                                                        <td>{{ $row->cod }}%</td>
                                                        <td><a class="btn btn-primary btn-sm" style="cursor: pointer;"
                                                            href="{{ route('admin.rate.edit',$row->id)}}">
                                                            Edit
                                                            </a></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            @foreach ($rate as $row)
                                                @if ($row->id == 5)
                                                    <tr>
                                                        <th>{{ $row->transport }} ( {{ $row->weight }}kg )</th>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            <tr>
                                                <th>Courier Name</th>
                                                <th>Within City</th>
                                                <th>Within State</th>
                                                <th>Metro to Metro</th>
                                                <th>Rest of India</th>
                                                <th>North East, J&K</th>
                                                <th>COD Charges</th>
                                                <th>COD%</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($rate as $row)
                                                @if ($row->weight == 0.5 && $row->transport == 'Surface')
                                                    <tr>
                                                        @php
                                                            $courier = DB::table('couriers')
                                                                ->where('id', $row->courier_id)
                                                                ->first();
                                                        @endphp
                                                        <td>{{ @$courier->courier }} ( {{ $row->weight }}kg )</td>
                                                        <td>RS {{ $row->within_city }}.00</td>
                                                        <td>RS {{ $row->within_state }}.00</td>
                                                        <td>RS {{ $row->metro_to_metro }}.00</td>
                                                        <td>RS {{ $row->rest_of_india }}.00</td>
                                                        <td>RS {{ $row->north_east }}.00</td>
                                                        <td>RS {{ $row->cod_charges }}.00</td>
                                                        <td>{{ $row->cod }}%</td>
                                                        <td><a class="btn btn-primary btn-sm" style="cursor: pointer;"
                                                            href="{{ route('admin.rate.edit',$row->id)}}">
                                                            Edit
                                                            </a></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            @foreach ($rate as $row)
                                                @if ($row->id == 2)
                                                    <tr>
                                                        <th>{{ $row->transport }} ( {{ $row->weight }}kg )</th>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            <tr>
                                                <th>Courier Name</th>
                                                <th>Within City</th>
                                                <th>Within State</th>
                                                <th>Metro to Metro</th>
                                                <th>Rest of India</th>
                                                <th>North East, J&K</th>
                                                <th>COD Charges</th>
                                                <th>COD%</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($rate as $row)
                                                @if ($row->weight == 1 && $row->transport == 'Air')
                                                    <tr>
                                                        @php
                                                            $courier = DB::table('couriers')
                                                                ->where('id', $row->courier_id)
                                                                ->first();
                                                        @endphp
                                                        <td>{{ @$courier->courier }} ( {{ $row->weight }}kg )</td>
                                                        <td>RS {{ $row->within_city }}.00</td>
                                                        <td>RS {{ $row->within_state }}.00</td>
                                                        <td>RS {{ $row->metro_to_metro }}.00</td>
                                                        <td>RS {{ $row->rest_of_india }}.00</td>
                                                        <td>RS {{ $row->north_east }}.00</td>
                                                        <td>RS {{ $row->cod_charges }}.00</td>
                                                        <td>{{ $row->cod }}%</td>
                                                        <td><a class="btn btn-primary btn-sm" style="cursor: pointer;"
                                                            href="{{ route('admin.rate.edit',$row->id)}}">
                                                            Edit
                                                            </a></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            @foreach ($rate as $row)
                                                @if ($row->id == 5)
                                                    <tr>
                                                        <th>{{ $row->transport }} ( {{ $row->weight }}kg )</th>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            <tr>
                                                <th>Courier Name</th>
                                                <th>Within City</th>
                                                <th>Within State</th>
                                                <th>Metro to Metro</th>
                                                <th>Rest of India</th>
                                                <th>North East, J&K</th>
                                                <th>COD Charges</th>
                                                <th>COD%</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($rate as $row)
                                                @if ($row->weight == 1 && $row->transport == 'Surface')
                                                    <tr>
                                                        @php
                                                            $courier = DB::table('couriers')
                                                                ->where('id', $row->courier_id)
                                                                ->first();
                                                        @endphp
                                                        <td>{{ @$courier->courier }} ( {{ $row->weight }}kg )</td>
                                                        <td>RS {{ $row->within_city }}.00</td>
                                                        <td>RS {{ $row->within_state }}.00</td>
                                                        <td>RS {{ $row->metro_to_metro }}.00</td>
                                                        <td>RS {{ $row->rest_of_india }}.00</td>
                                                        <td>RS {{ $row->north_east }}.00</td>
                                                        <td>RS {{ $row->cod_charges }}.00</td>
                                                        <td>{{ $row->cod }}%</td>
                                                        <td><a class="btn btn-primary btn-sm" style="cursor: pointer;"
                                                            href="{{ route('admin.rate.edit',$row->id)}}">
                                                            Edit
                                                            </a></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            <tr>
                                                <th>Terms</th>
                                                <th>Condition</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
        
                                        <tbody>
                                            @foreach ($terms as $row)
                                                    <tr>
                                                        <td>{{ $row->terms }}</td>
                                                        <td>{{ $row->conditions }}</td>
                                                        <td><a class="btn btn-primary btn-sm" style="cursor: pointer;"
                                                            href="{{ route('admin.rate.termedit',$row->id)}}">
                                                            Edit
                                                            </a></td>
                                                    </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        function change_tab(id) {
            document.getElementById("page_content").innerHTML = document.getElementById(id + "_desc").innerHTML;
            document.getElementById("page1").className = "notselected";
            document.getElementById("page2").className = "notselected";
            document.getElementById("page3").className = "notselected";
            document.getElementById(id).className = "selected";
        }
    </script>

@endsection
