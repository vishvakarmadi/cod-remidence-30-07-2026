<div class="container">
    <ul class="nav nav-pills">
      <li class="active"><a href="#home" data-target="#home" data-toggle="tab">Rate</a></li>
      <li><a href="#about" data-toggle="tab">Ratecard</a></li>
    </ul>
</div>
  
  <div class="tab-content">
    <div class="tab-pane active" id="home">
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
                                        <tbody>
                                            @php
                                            $rate1= DB::table('ratecards')->where('weight',1)->where('transport','Air')->get();
                                            $rate2= DB::table('ratecards')->where('weight',0.5)->where('transport','Air')->get();
                                            $i=0;
                                            @endphp
                                            
                                            @foreach ($rate1 as $row)
                                                    <tr>
                                                        @php
                                                            $courier = DB::table('couriers')
                                                                ->where('id', $row->courier_id)
                                                                ->first();
                                                        @endphp
                                                        <td>{{ @$courier->courier }} ( {{ $row->weight }}kg )</td>
                                                        <td>RS {{ $row->within_city + $rate2[$i]->within_city }}.00</td>
                                                        <td>RS {{ $row->within_state + $rate2[$i]->within_state }}.00</td>
                                                        <td>RS {{ $row->metro_to_metro + $rate2[$i]->metro_to_metro }}.00</td>
                                                        <td>RS {{ $row->rest_of_india + $rate2[$i]->rest_of_india }}.00</td>
                                                        <td>RS {{ $row->north_east + $rate2[$i]->north_east }}.00</td>
                                                        <td>RS {{ $row->cod_charges }}.00</td>
                                                        <td>{{ $row->cod }}%</td>
                                                        <td><a class="btn btn-primary btn-sm" style="cursor: pointer;"
                                                            href="{{ route('admin.rate.edit',$row->id)}}">
                                                            Edit
                                                            </a></td>
                                                    </tr>
                                                    @php $i++; @endphp
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
                                                @if ($row->id == 6)
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
                                            @php
                                            $rate1= DB::table('ratecards')->where('weight',1)->where('transport','Surface')->get();
                                            $rate2= DB::table('ratecards')->where('weight',0.5)->where('transport','Surface')->get();
                                            $i=0;
                                            @endphp
                                            
                                            @foreach ($rate1 as $row)
                                                    <tr>
                                                        @php
                                                            $courier = DB::table('couriers')
                                                                ->where('id', $row->courier_id)
                                                                ->first();
                                                        @endphp
                                                        <td>{{ @$courier->courier }} ( {{ $row->weight }}kg )</td>
                                                        <td>RS {{ $row->within_city + $rate2[$i]->within_city }}.00</td>
                                                        <td>RS {{ $row->within_state + $rate2[$i]->within_state }}.00</td>
                                                        <td>RS {{ $row->metro_to_metro + $rate2[$i]->metro_to_metro }}.00</td>
                                                        <td>RS {{ $row->rest_of_india + $rate2[$i]->rest_of_india }}.00</td>
                                                        <td>RS {{ $row->north_east + $rate2[$i]->north_east }}.00</td>
                                                        <td>RS {{ $row->cod_charges }}.00</td>
                                                        <td>{{ $row->cod }}%</td>
                                                        <td><a class="btn btn-primary btn-sm" style="cursor: pointer;"
                                                            href="{{ route('admin.rate.edit',$row->id)}}">
                                                            Edit
                                                            </a></td>
                                                    </tr>
                                                    @php $i++; @endphp
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
    <div class="tab-pane" id="about">
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
            <form id="rateForm">
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
                        <a href="javascript:void(0)">Above Prices are Exclusive of GST.</a>
                    </div>
                </div>
                <hr>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" onclick="calculate()"
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
                                    <th>Total Charges</th>
                                </tr>
                            </thead>
                            @php 
                            $rate3= DB::table('ratecards')->where('weight',1)->get();
                            $rate4= DB::table('ratecards')->where('weight',0.5)->get();
                            $i=0;
                            @endphp
                            
                            
                            <tbody id="responseData">
                                @foreach($rate3 as $row)
                                
                                @php 
                                
                                $cod = $value * ($row->cod)/100;
                                $charge_cod = max($row->cod_charges,$cod);
                                
                                if($row->within_city && $payment == 'cod'){
                                $within_citygst_cod = ($row->within_city + $charge_cod) * (0.18); 
                                $total_cod = $row->within_city + $charge_cod + $within_citygst_cod;
                                }
                                
                                if($row->within_city){
                                $within_citygst = ($row->within_city + 0.00) * (0.18); 
                                $total = $row->within_city + 0.00 + $within_citygst;
                                }
                                if($row->within_city && $reverse == 'Reverse'){
                                $within_cityrevgst = (($row->within_city * 1.5) + 0.00) * (0.18); 
                                $totalrev = ($row->within_city *1.5) +  $within_cityrevgst;
                                }
                                if($row->within_city && $payment == 'cod' && $reverse == 'Reverse'){
                                $within_cityrevgst_cod = (($row->within_city * 1.5) + $charge_cod) * (0.18); 
                                $total_cod_rev = ($row->within_city*1.5) + $charge_cod + $within_cityrevgst_cod;
                                }
                                // greater than 0.5 weight start
                                if($weight > 0.5 && $row->within_city && $payment == 'cod') {
                            $within_citygst_codweight = ($row->within_city + $rate4[$i]->within_city + $charge_cod) * (0.18); 
                            $total_codweight = ($row->within_city + $rate4[$i]->within_city) + $charge_cod + $within_citygst_codweight;
                            }
                            if($weight > 0.5 && $row->within_city){
                                $within_citygstweight = ($row->within_city + $rate4[$i]->within_city + 0.00) * (0.18); 
                                $totalweight = $row->within_city + $rate4[$i]->within_city + 0.00 + $within_citygstweight;
                                }
                            if($weight > 0.5 && $row->within_city && $reverse == 'Reverse'){
                                $within_cityrevgstweight = ((($row->within_city + $rate4[$i]->within_city) * 1.5) + 0.00) * (0.18); 
                                $totalrevweight = (($row->within_city+ $rate4[$i]->within_city) *1.5) +  $within_cityrevgstweight;
                                }
                            if($weight > 0.5 && $row->within_city && $payment == 'cod' && $reverse == 'Reverse'){
                                $within_cityrevgst_codweight = ((($row->within_city+ $rate4[$i]->within_city) * 1.5) + $charge_cod) * (0.18); 
                                $total_cod_revweight = (($row->within_city+ $rate4[$i]->within_city)*1.5) + $charge_cod + $within_cityrevgst_codweight;
                                }
                                // greater than 0.5 weight end

                                if($row->within_state && $payment == 'cod'){
                                $within_stategst_cod = ($row->within_state + $charge_cod) * (0.18); 
                                $total_statecod = $row->within_state + $charge_cod + $within_stategst_cod;
                                }
                                if($row->within_state){
                                $within_stategstpre = ($row->within_state + 0.00) * (0.18); 
                                $total_pre = $row->within_state + 0.00 + $within_stategstpre;
                                }

                                if($row->within_state && $payment == 'cod' && $reverse == 'Reverse'){
                                $within_staterevgst_cod = (($row->within_state * 1.5) + $charge_cod) * (0.18); 
                                $total_staterevcod = ($row->within_state * 1.5) + $charge_cod + $within_staterevgst_cod;
                                }
                                if($row->within_state && $reverse == 'Reverse'){
                                $within_staterevgstpre = (($row->within_state * 1.5) + 0.00) * (0.18); 
                                $total_pre_rev = ($row->within_state * 1.5) + 0.00 + $within_staterevgstpre;
                                }
                                //>0.5 statrt state
                                if($weight > 0.5 && $row->within_state && $payment == 'cod'){
                                $within_stategst_codweight = ($row->within_state + $rate4[$i]->within_state  + $charge_cod) * (0.18); 
                                $total_statecodweight = $row->within_state + $charge_cod + $within_stategst_codweight;
                                }
                                if($weight > 0.5 && $row->within_state){
                                $within_stategstpreweight = ($row->within_state + $rate4[$i]->within_state  + 0.00) * (0.18); 
                                $total_preweight = $row->within_state + 0.00 + $within_stategstpreweight;
                                }

                                if($weight > 0.5 && $row->within_state && $payment == 'cod' && $reverse == 'Reverse'){
                                $within_staterevgst_codweight = (($row->within_state + $rate4[$i]->within_state  * 1.5) + $charge_cod) * (0.18); 
                                $total_staterevcodweight = ($row->within_state + $rate4[$i]->within_state  * 1.5) + $charge_cod + $within_staterevgst_codweight;
                                }
                                if($weight > 0.5 && $row->within_state && $reverse == 'Reverse'){
                                $within_staterevgstpreweight = (($row->within_state + $rate4[$i]->within_state  * 1.5) + 0.00) * (0.18); 
                                $total_pre_revweight = ($row->within_state + $rate4[$i]->within_state  * 1.5) + 0.00 + $within_staterevgstpreweight;
                                }
                                //>0.5 end state
                                if($row->metro_to_metro && $payment == 'cod'){
                                $metro_to_metrogst_cod = ($row->metro_to_metro + $charge_cod) * (0.18); 
                                $metro_to_metrocod = $row->metro_to_metro + $charge_cod + $metro_to_metrogst_cod;
                                }
                                if($row->metro_to_metro){
                                $metro_to_metrogstpre = ($row->metro_to_metro + 0.00) * (0.18); 
                                $metro_to_metropre = $row->metro_to_metro + 0.00 + $metro_to_metrogstpre;
                                }
                                if($row->metro_to_metro && $payment == 'cod' && $reverse == 'Reverse'){
                                $metro_to_metrorevgst_cod = ($row->metro_to_metro + $charge_cod) * (0.18); 
                                $metro_to_metrorevcod = $row->metro_to_metro + $charge_cod + $metro_to_metrorevgst_cod;
                                }
                                if($row->metro_to_metro  && $reverse == 'Reverse'){
                                $metro_to_metrorevgstpre = ($row->metro_to_metro + 0.00) * (0.18); 
                                $metro_to_metrorevpre = $row->metro_to_metro + 0.00 + $metro_to_metrorevgstpre;
                                }
                                //>0.5 metro start
                                if($weight > 0.5  &&  $row->metro_to_metro && $payment == 'cod'){
                                $metro_to_metrogst_codweight = ($row->metro_to_metro+ $rate4[$i]->metro_to_metro + $charge_cod) * (0.18); 
                                $metro_to_metrocodweight = $row->metro_to_metro + $charge_cod + $metro_to_metrogst_codweight;
                                }
                                if($weight > 0.5  &&  $row->metro_to_metro){
                                $metro_to_metrogstpreweight = ($row->metro_to_metro+ $rate4[$i]->metro_to_metro + 0.00) * (0.18); 
                                $metro_to_metropreweight = $row->metro_to_metro + 0.00 + $metro_to_metrogstpreweight;
                                }
                                if($weight > 0.5  &&  $row->metro_to_metro && $payment == 'cod' && $reverse == 'Reverse'){
                                $metro_to_metrorevgst_codweight = ($row->metro_to_metro+ $rate4[$i]->metro_to_metro + $charge_cod) * (0.18); 
                                $metro_to_metrorevcodweight = $row->metro_to_metro + $charge_cod + $metro_to_metrorevgst_codweight;
                                }
                                if($weight > 0.5  &&  $row->metro_to_metro  && $reverse == 'Reverse'){
                                $metro_to_metrorevgstpreweight = ($row->metro_to_metro+ $rate4[$i]->metro_to_metro + 0.00) * (0.18); 
                                $metro_to_metrorevpreweight = $row->metro_to_metro + 0.00 + $metro_to_metrorevgstpreweight;
                                }
                                //>0.5 metro end
                                if($row->rest_of_india && $payment == 'cod'){
                                $rest_of_indiagst_cod = ($row->rest_of_india + $charge_cod) * (0.18); 
                                $rest_of_indiacod = $row->rest_of_india + $charge_cod + $rest_of_indiagst_cod;
                                }
                                if($row->rest_of_india){
                                $rest_of_indiagstpre = ($row->rest_of_india + 0.00) * (0.18); 
                                $rest_of_indiapre = $row->rest_of_india + 0.00 + $rest_of_indiagstpre;
                                }
                                if($row->rest_of_india && $payment == 'cod' && $reverse == 'Reverse'){
                                $rest_of_indiarevgst_cod = ($row->rest_of_india + $charge_cod) * (0.18); 
                                $rest_of_indiarevcod = $row->rest_of_india + $charge_cod + $rest_of_indiarevgst_cod;
                                }
                                if($row->rest_of_india && $reverse == 'Reverse'){
                                $rest_of_indiarevgstpre = ($row->rest_of_india + 0.00) * (0.18); 
                                $rest_of_indiarevpre = $row->rest_of_india + 0.00 + $rest_of_indiarevgstpre;
                                }
                                //>0.5 rest of india start 
                                if($weight > 0.5 && $row->rest_of_india && $payment == 'cod'){
                                $rest_of_indiagst_codweight = ($row->rest_of_india+ $rate4[$i]->rest_of_india + $charge_cod) * (0.18); 
                                $rest_of_indiacodweight = $row->rest_of_india + $charge_cod + $rest_of_indiagst_codweight;
                                }
                                if($weight > 0.5 && $row->rest_of_india){
                                $rest_of_indiagstpreweight = ($row->rest_of_india+ $rate4[$i]->rest_of_india + 0.00) * (0.18); 
                                $rest_of_indiapreweight = $row->rest_of_india + 0.00 + $rest_of_indiagstpreweight;
                                }
                                if($weight > 0.5 && $row->rest_of_india && $payment == 'cod' && $reverse == 'Reverse'){
                                $rest_of_indiarevgst_codweight = ($row->rest_of_india+ $rate4[$i]->rest_of_india + $charge_cod) * (0.18); 
                                $rest_of_indiarevcodweight = $row->rest_of_india + $charge_cod + $rest_of_indiarevgst_codweight;
                                }
                                if($weight > 0.5 && $row->rest_of_india && $reverse == 'Reverse'){
                                $rest_of_indiarevgstpreweight = ($row->rest_of_india+ $rate4[$i]->rest_of_india + 0.00) * (0.18); 
                                $rest_of_indiarevpreweight = $row->rest_of_india + 0.00 + $rest_of_indiarevgstpreweight;
                                }
                                //>0.5 rest of india end
                                if($row->north_east && $payment == 'cod'){
                                $north_eastgst_cod = ($row->north_east + $charge_cod) * (0.18); 
                                $north_eastcod = $row->north_east + $charge_cod + $north_eastgst_cod;
                                }
                                if($row->north_east){
                                $north_eastgstpre = ($row->north_east + 0.00) * (0.18); 
                                $north_eastpre = $row->north_east + 0.00 + $north_eastgstpre;
                                }
                                if($row->north_east && $payment == 'cod' && $reverse == 'Reverse'){
                                $north_eastrevgst_cod = ($row->north_east + $charge_cod) * (0.18); 
                                $north_eastrevcod = $row->north_east + $charge_cod + $north_eastrevgst_cod;
                                }
                                if($row->north_east && $reverse == 'Reverse'){
                                $north_eastrevgstpre = ($row->north_east + 0.00) * (0.18); 
                                $north_eastrevpre = $row->north_east + 0.00 + $north_eastrevgstpre;
                                }
                                //>0.5 north east start
                                if($weight > 0.5 && $row->north_east && $payment == 'cod'){
                                $north_eastgst_codweight = ($row->north_east + $rate4[$i]->north_east + $charge_cod) * (0.18); 
                                $north_eastcodweight = $row->north_east + $charge_cod + $north_eastgst_codweight;
                                }
                                if($weight > 0.5 && $row->north_east){
                                $north_eastgstpreweight = ($row->north_east+ $rate4[$i]->north_east + 0.00) * (0.18); 
                                $north_eastpreweight = $row->north_east + 0.00 + $north_eastgstpreweight;
                                }
                                if($weight > 0.5 && $row->north_east && $payment == 'cod' && $reverse == 'Reverse'){
                                $north_eastrevgst_codweight = ($row->north_east+ $rate4[$i]->north_east + $charge_cod) * (0.18); 
                                $north_eastrevcodweight = $row->north_east + $charge_cod + $north_eastrevgst_codweight;
                                }
                                if($weight > 0.5 && $row->north_east && $reverse == 'Reverse'){
                                $north_eastrevgstpreweight = ($row->north_east+ $rate4[$i]->north_east + 0.00) * (0.18); 
                                $north_eastrevpreweight = $row->north_east + 0.00 + $north_eastrevgstpreweight;
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
                                            <td>Rs.@if($reverse == 'Reverse') {{ $row->within_city * 1.5 }} @elseif($reverse == 'Reverse' && $weight > 0.5) {{ ($row->within_city+ $rate4[$i]->within_city) * 1.5 }} @elseif($weight > 0.5) {{$row->within_city + $rate4[$i]->within_city}}  @else {{ $row->within_city }} @endif</td>
                                        @elseif($pick->Zone == 'Z2' && $drop->Zone == 'Z2')
                                            <td>Rs.@if($reverse == 'Reverse') {{ $row->within_state * 1.5 }} @elseif($reverse == 'Reverse' && $weight > 0.5) {{ ($row->within_city+ $rate4[$i]->within_state) * 1.5 }}  @elseif($weight > 0.5) {{$row->within_state + $rate4[$i]->within_state}}   @else {{ $row->within_state }} @endif</td>
                                        @elseif($pick->Zone == 'Z3' && $drop->Zone == 'Z3')
                                            <td>Rs.@if($reverse == 'Reverse') {{ $row->metro_to_metro * 1.5 }} @elseif($reverse == 'Reverse' && $weight > 0.5) {{ ($row->within_city+ $rate4[$i]->metro_to_metro) * 1.5 }}  @elseif($weight > 0.5) {{$row->metro_to_metro + $rate4[$i]->metro_to_metro}}   @else {{ $row->metro_to_metro }} @endif</td>
                                        @elseif($pick->Zone == 'Z4' && $drop->Zone == 'Z4')
                                            <td>Rs.@if($reverse == 'Reverse') {{ $row->rest_of_india * 1.5 }} @elseif($reverse == 'Reverse' && $weight > 0.5) {{ ($row->within_city+ $rate4[$i]->rest_of_india) * 1.5 }}  @elseif($weight > 0.5) {{$row->rest_of_india + $rate4[$i]->rest_of_india}}   @else {{ $row->rest_of_india }} @endif</td>
                                        @elseif($pick->Zone == 'Z5' && $drop->Zone == 'Z5')
                                            <td>Rs.@if($reverse == 'Reverse') {{ $row->north_east * 1.5 }} @elseif($reverse == 'Reverse' && $weight > 0.5) {{ ($row->within_city+ $rate4[$i]->north_east) * 1.5 }}  @elseif($weight > 0.5) {{$row->north_east + $rate4[$i]->north_east}}   @else {{ $row->north_east }} @endif</td>
                                        @endif
                                        @if($payment == 'cod')
                                            <td>Rs.{{ $charge_cod }}</td>
                                        @else
                                            <td>Rs. 0.00</td> 
                                        @endif
                                        @if($row->within_city && $payment == 'cod')
                                        <td>Rs.@if($reverse == 'Reverse') {{ $total_cod_rev }} @elseif($reverse == 'Reverse' && $weight > 0.5){{ $total_cod_revweight }} @elseif($weight > 0.5){{ $total_codweight }} @else {{ $total_cod }} @endif</td>
                                        @elseif($row->within_city && $payment == 'prepaid')
                                        <td>Rs.@if($reverse == 'Reverse') {{ $totalrev }} @elseif($reverse == 'Reverse' && $weight > 0.5){{ $totalrevweight }} @elseif($weight > 0.5){{ $totalweight }}  @else {{ $total }} @endif</td>
                                        
                                        @elseif($row->within_state && $payment == 'cod')
                                        <td>Rs.@if($reverse == 'Reverse') {{ $total_staterevcod }} @elseif($reverse == 'Reverse' && $weight > 0.5){{ $total_staterevcodweight }} @elseif($weight > 0.5){{ $total_statecodweight }}  @else {{ $total_statecod }} @endif</td>
                                        @elseif($row->within_state && $payment == 'prepaid')
                                        <td>Rs.@if($reverse == 'Reverse') {{ $total_pre_rev }} @elseif($reverse == 'Reverse' && $weight > 0.5){{ $total_pre_revweight }} @elseif($weight > 0.5){{ $total_preweight }} @else {{ $total_pre }} @endif</td>
                                        
                                        @elseif($row->metro_to_metro && $payment == 'cod')
                                        <td>Rs.@if($reverse == 'Reverse') {{ $metro_to_metrorevcod }} @elseif($reverse == 'Reverse' && $weight > 0.5){{ $metro_to_metrorevcodweight }} @elseif($weight > 0.5){{ $metro_to_metrocodweight }}   @else {{ $metro_to_metrocod }} @endif</td>
                                        @elseif($row->metro_to_metro && $payment == 'prepaid')
                                        <td>Rs.@if($reverse == 'Reverse') {{ $metro_to_metrorevpre }} @elseif($reverse == 'Reverse' && $weight > 0.5){{ $metro_to_metrorevpreweight }} @elseif($weight > 0.5){{ $metro_to_metropreweight }}   @else {{ $metro_to_metropre }} @endif</td>
                                       
                                        @elseif($row->rest_of_india && $payment == 'cod')
                                        <td>Rs.@if($reverse == 'Reverse') {{ $rest_of_indiarevcod }} @elseif($reverse == 'Reverse' && $weight > 0.5){{ $rest_of_indiarevcodweight }} @elseif($weight > 0.5){{ $rest_of_indiacodweight }}   @else {{ $rest_of_indiacod }} @endif</td>
                                        @elseif($row->rest_of_india && $payment == 'prepaid')
                                        <td>Rs.@if($reverse == 'Reverse') {{ $rest_of_indiarevpre }} @elseif($reverse == 'Reverse' && $weight > 0.5){{ $rest_of_indiarevpreweight }} @elseif($weight > 0.5){{ $rest_of_indiapreweight }}   @else {{ $rest_of_indiapre }} @endif</td>
                                        
                                        @elseif($row->north_east && $payment == 'cod')
                                        <td>Rs.@if($reverse == 'Reverse') {{ $north_eastrevcod }} @elseif($reverse == 'Reverse' && $weight > 0.5){{ $north_eastrevcodweight }} @elseif($weight > 0.5){{ $north_eastcodweight }}   @else {{ $north_eastcod }} @endif</td>
                                        @elseif($row->north_east && $payment == 'prepaid')
                                        <td>Rs.@if($reverse == 'Reverse') {{ $north_eastrevpre }} @elseif($reverse == 'Reverse' && $weight > 0.5){{ $north_eastrevpreweight }} @elseif($weight > 0.5){{ $north_eastpreweight }}   @else {{ $north_eastpre }} @endif</td>
                                       
                                        @endif
                                    </tr>
                                    @php $i++; @endphp
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
    <div class="tab-pane" id="contacts">Contacts</div>
  </div>

  <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
  <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script>
  $(function() {
  
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      localStorage.setItem('lastTab', $(this).attr('href'));
    });
    var lastTab = localStorage.getItem('lastTab');
    
    if (lastTab) {
      $('[href="' + lastTab + '"]').tab('show');
    }
    
  });
  </script>