<?php 
use App\Models\Admin\Order;
?>
@extends('admin.admin_layouts')
@section('admin_content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<style>
    .btn-group{
        display: block;
    }
    .btn-group .multiselect{
        width:100%
    }
    .multiselect-container
    , .multiselect-container>li>a>label.checkbox {
        width: 100%;
    }
    </style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xl-12">
            <div class="card  mb-3">
                <div class="card-header" style="display:flex;flex-wrap: wrap;">
                    <!-- <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Filter</h5> -->
                        <div class="col-md-9"><a href="javascript:void(0)" class="expand">Filters <?php if(empty($re_data)){ echo '<<';}else{ echo '>>';} ?></a></div>
                        
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.reports.mis_filter') }}" method="POST">
                    @csrf
                        <div class="col-md-12">
                            <div class="show_more" style="width: 100%; <?php if(empty($re_data)){ echo 'display:none'; } ?>">
                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <label>Select Date Range</label><span class="required"> *</span>    <div class="row">
                                            <div class="col-md-6">
                                                <input class="form-control " type="date" name="start_date" required="" value="{{explode(' ',$re_data['start_date'])[0]}}" id="_1">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control " type="date" name="end_date" required="" value="{{explode(' ',$re_data['end_date'])[0]}}" id="_2">
                                            </div>
                                                </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">User<span class="required">*</span></label>
                                        <!-- <input mbsc-input id="my-input" data-dropdown="true" data-tags="true" /> -->
                                        <select id="multiple-checkboxes" name="user_id[]" class="form-control" required multiple="multiple">
                                            @foreach($users as $user)
                                                <option value='{{$user->id}}' <?php if(in_array($user->id,$re_data['user_id'])){echo 'selected';} ?> >{{$user->name}}</option>
                                            @endforeach
                                            
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">Status<span class="required">*</span></label>
                                        <!-- <input mbsc-input id="my-input" data-dropdown="true" data-tags="true" /> -->
                                        <select id="multiple-checkboxesstus" name="status_mis[]" class="form-control" required multiple="multiple">
                                            <option value='2' <?php if(in_array('2',$re_data['status_mis'])){echo 'selected';} ?> >Shipped</option>
                                            <option value='3' <?php if(in_array('3',$re_data['status_mis'])){echo 'selected';} ?> >Delivered</option>
                                            <option value='4' <?php if(in_array('4',$re_data['status_mis'])){echo 'selected';} ?> >Canceled</option>
                                            <option value='5' <?php if(in_array('5',$re_data['status_mis'])){echo 'selected';} ?> >RTO</option>
                                            <option value='6' <?php if(in_array('6',$re_data['status_mis'])){echo 'selected';} ?> >RTO Received</option>
                                            <option value='10' <?php if(in_array('10',$re_data['status_mis'])){echo 'selected';} ?> >NDR</option>
                                            <option value='12' <?php if(in_array('12',$re_data['status_mis'])){echo 'selected';} ?> >Pickup Pending</option>
                                            <option value='13' <?php if(in_array('13',$re_data['status_mis'])){echo 'selected';} ?> >RTO In Transit</option>
                                            <option value='14' <?php if(in_array('14',$re_data['status_mis'])){echo 'selected';} ?> >In Transit</option>
                                            <option value='15' <?php if(in_array('15',$re_data['status_mis'])){echo 'selected';} ?> >Out for Delivery</option>
                                            <option value='16' <?php if(in_array('16',$re_data['status_mis'])){echo 'selected';} ?> >Lost</option>
                                            <option value='17' <?php if(in_array('17',$re_data['status_mis'])){echo 'selected';} ?> >Damaged</option>
                                            <option value='18' <?php if(in_array('18',$re_data['status_mis'])){echo 'selected';} ?> >Destroyed</option>
                                            
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label class="form-control-label">Courier</label>
                                        <select name="ship_courier_id" class="form-control">
                                            <option value='0' >Courier</option>
                                            <option value='1' <?php if($re_data['ship_courier_id'] =='1'){echo 'selected';} ?> >Ecom Express</option>
                                            <option value='2' <?php if($re_data['ship_courier_id'] =='2'){echo 'selected';} ?> >Delhivery</option>
                                            <option value='3' <?php if($re_data['ship_courier_id'] =='3'){echo 'selected';} ?> >Bludart</option>
                                            <option value='4' <?php if($re_data['ship_courier_id'] =='4'){echo 'selected';} ?> >XpressBees</option>
                                            <option value='5' <?php if($re_data['ship_courier_id'] =='5'){echo 'selected';} ?> >DTDC</option>
                                            <option value='6' <?php if($re_data['ship_courier_id'] =='6'){echo 'selected';} ?> >Smartr</option>
                                            <option value='7' <?php if($re_data['ship_courier_id'] =='7'){echo 'selected';} ?> >Ekart</option>
                                            <option value='8' <?php if($re_data['ship_courier_id'] =='8'){echo 'selected';} ?> >Shadowfax</option>
                                            <option value='9' <?php if($re_data['ship_courier_id'] =='9'){echo 'selected';} ?> >ATS</option>
                                            
                                            
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <x-button size="col-lg-3" type="submit" name="Search" />
                                </div>
                                <script>
                                    $(document).ready(function() {
                                        $('#multiple-checkboxes').multiselect({
                                            includeSelectAllOption: true,
                                            enableFiltering: true,
                                            enableCaseInsensitiveFiltering: true,
                                            filterPlaceholder: 'Search User / Seller...'
                                        });
                                    });
                                    $(document).ready(function() {
                                        $('#multiple-checkboxesstus').multiselect({
                                        includeSelectAllOption: true,
                                        });
                                    });
                                </script>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card">
                <div class="header d-flex justify-content-between">
                    <h2>MIS Report</h2>
                </div>
            <?php
            if(isset($addedtocron)){ ?>
                <div class="card-body">
                        <b>Report Generation in Progress</b><br>
                        Thank you for your patience. Your report is currently being generated and will be ready for download within the next 5-10 minutes.You can find this inside '<a href="{{route('admin.reports.requestedreport')}}" target="_blank">Requested Reports</a>' under Reports section.
                </div>                 
            <?php }else{ ?>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered sorttable table-striped table-hover" id="sorttable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Channel</th>
                                <th>Seller</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>GST</th>
                                <th>Billing Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Warehouse Pincode</th>
                                <th>Created</th>
                                <th>READY TO SHIP DATE</th>
                                <th>Pickup date</th>
                                <th>RTO Received date</th>
                                <th>Delivered</th>
                                <th>Status</th>
                                @if( Auth::guard('admin')->user()->role_id==1)
                                    <th>Current Status</th>
                                    <th>Current Place</th>
                                    <th>Current Remarks</th>
                                    <th>Status Updated On</th> 
                                @endif
                                <th>SKU</th>
                                <th>Qty</th>
                                <th>Order Subtotal</th>
                                <th>Order Discount Amount</th>
                                <th>Total Amount</th>
                                <th>Payment Mode</th>
                                <th>Transport Mode</th>
                                <!-- <th>Shipping Amount</th> -->
                                <th>Courier Name</th>
                                <th>Tracking Number</th>
                                <th>Manifest ID</th>
                                <th>Remittance ID</th>
                                <!-- <th>Shipment Status</th>
                                <th>Pickup date</th> -->
                                <th>Pickup Warehouse</th>
                                <th>Pickup Address</th>
                                <th>Warehouse Phone</th>
                                <th>RTO Warehouse</th>
                                <th>Warehouse Phone</th>
                                <th>Total Attempts</th>
                                <th>Customer Name</th>
                                <th>Customer Phone</th>
                                <th>Customer Email</th>
                                <th>Pincode</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Country</th>
                                <th>Address</th>
                                <th>Billing Pin code</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Country</th>
                                <th>Address</th>
                                <th>Dimension(CM)</th>
                                <th>Weight(kg)</th>
                                <th>Vol.Weight(kg)</th>
                                <th>Used Weight(kg)</th>
                                <th>Courier weight used(kg)</th>
                                <th>ExtraWeightDate</th>
                                <th>Courier Extra Weight Charges</th>
                                <!-- <th>Shipping Charge(Freight + COD)</th> -->
                                <th>SHIPPING (FREIGHT)</th>
                                <th>COD CHARGES</th>
                                <th>CGST+SGST</th>
                                <th>IGST</th>
                                <th>Zone</th>
                                <th>RTO Date</th>
                                <th>RTO AWB</th>
                                <th>RTO Charge</th>
                                <th>RTO extra weight charge</th>
                                <th>Other Charges</th>
                                <th>TOTAL CHARGES</th>
                                <th>E-WayBill Number</th>
                                <th>Tag</th>
                                <th>Place of supply</th>
                                <!-- <th>GST(CGST+SGST/IGST)</th> -->
                                <th>TOTAL INVOICE VALUE</th>
                                <th>Company name</th>
                                <th>SM</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td class="anchor-column"><a href="{{ route('admin.order.detail',$order->id) }}" target="_blank">{{$order->vendor_order_id}}</a></td>
                                    <td>{{$order->channel}}</td>
                                    <td>{{$order->user_id}}</td>
                                    <td>{{$order->mobile}}</td>
                                    <td>{{$order->email}}</td>
                                    <td>{{$order->gst}}</td>
                                    <td>{{$order->billing_address}}</td>
                                    <td>{{$order->city}}</td>
                                    <td>{{$order->pstate}}</td>
                                    <td>{{$order->zip_code}}</td>
                                    <td class="date-column">
                                        <span class="fa fa-calendar"></span>&nbsp;
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }} 
                                        <span class="fa fa-clock-o"></span>&nbsp;
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}
                                    </td>
                                    <td class="date-column">
                                        @if($order->shipped_date != null && $order->shipped_date != '')
                                            <span class="fa fa-calendar"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y') }} 
                                            <span class="fa fa-clock-o"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($order->shipped_date)->format('H:i') }}
                                        @endif
                                    </td>
                                    <td class="date-column">
                                        @if($order->picked_date != null && $order->picked_date != '')
                                            <span class="fa fa-calendar"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($order->picked_date)->format('d M, Y') }} 
                                            
                                        @endif
                                    </td>
                                    <td class="date-column">
                                        @if(strip_tags($order->status) =='RTO Delivered')
                                            @if($order->rto_received_date != null && $order->rto_received_date != '')
                                                <span class="fa fa-calendar"></span>&nbsp;
                                                {{ \Carbon\Carbon::parse($order->rto_received_date)->format('d M, Y') }} 
                                                <span class="fa fa-clock-o"></span>&nbsp;
                                                {{ \Carbon\Carbon::parse($order->rto_received_date)->format('H:i') }}
                                            @else
                                                <span class="fa fa-calendar"></span>&nbsp;
                                                {{ \Carbon\Carbon::parse($order->status_date)->format('d M, Y') }} 
                                                <span class="fa fa-clock-o"></span>&nbsp;
                                                {{ \Carbon\Carbon::parse($order->status_date)->format('H:i') }}
                                            @endif
                                        @endif
                                    </td>
                                    <td class="date-column">
                                        @if($order->delivered_date != null && $order->delivered_date != '')
                                            <span class="fa fa-calendar"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($order->delivered_date)->format('d M, Y') }} 
                                            <span class="fa fa-clock-o"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($order->delivered_date)->format('H:i') }}
                                        @endif
                                    </td>
                                    <td class="anchor-column">
                                        @if($order->manifest_id && strip_tags($order->status) == 'Shipped')
                                            Manifested
                                        @else
                                            {{ strip_tags($order->status) }}
                                        @endif
                                    </td>
                                    @if( Auth::guard('admin')->user()->role_id==1)
                                        <td>{{$order->c_action}}</td>
                                        <td>{{$order->c_place}}</td>
                                        <td>{{$order->c_remarks}}</td>
                                        <td>{{$order->c_date}}</td>
                                    @endif
                                    <td>
                                        <?php $sku =''; ?>
                                        @foreach($order->detail as $od)
                                            <?php $sku .= $od->code.', '; ?>
                                        @endforeach
                                        {{rtrim($sku,', ')}}
                                    </td>
                                    <td>{{count($order->detail)}}</td>
                                    <td>
                                        <?php $sub =0; ?>
                                        @foreach($order->detail as $od)
                                            <?php $sub = $sub + $od->total_price ?>
                                        @endforeach
                                        {{$sub}}
                                    </td>
                                    <td>
                                        {{$order->discount}}
                                    </td>
                                    <td>
                                        {{$order->total}}
                                    </td>
                                    <td class="anchor-column">{!! $order->payment_mode !!}</td>
                                    <td>
                                        @php
                                            $rawMode = strtolower($order->shipping_courier_type ?? '');
                                        @endphp
                                        @if($rawMode == 'fa-plane' || $rawMode == 'air') Air
                                        @elseif($rawMode == 'fa-truck' || $rawMode == 'surface') Surface
                                        @elseif($rawMode == 'fa-motorcycle' || $rawMode == 'sdd') SDD
                                        @elseif($rawMode == 'fa-bicycle' || $rawMode == 'ndd') NDD
                                        @elseif($rawMode == 'fa-bolt' || $rawMode == 'quick') Quick
                                        @else {{ $order->shipping_courier_type }}
                                        @endif
                                    </td>
                                    <!-- <td>{{$order->shipping_courier_cost}}</td> -->
    <!--                                <td>
                                        @if($order->shipped_date != null && $order->shipped_date != '')
                                            {{'invmy_'.date('my', strtotime($order->shipped_date)).$order->user_id}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->shipped_date != null && $order->shipped_date != '')
                                            {{ date("Y-m-t", strtotime($order->shipped_date)) }}
                                        @endif
                                    </td>-->
                                    <td>
                                        @if($order->ship_courier_id)
                                            {{ $couriers[$order->ship_courier_id]['name'] }}
                                        @endif
                                    </td>
                                    <td>
                                        {{$order->tracking_info}}
                                    </td>
                                    <td>
                                        {{$order->manifest_id}}
                                    </td>
                                    <td>
                                        {{$order->remittance_id}}
                                    </td>
                                    <!-- <td></td> -->
                                    <!-- <td class="date-column">
                                        @if($order->picked_date != null && $order->picked_date != '')
                                            <span class="fa fa-calendar"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($order->picked_date)->format('d M, Y') }} 
                                            <span class="fa fa-clock-o"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($order->picked_date)->format('H:i') }}
                                        @endif
                                    </td> -->
                                    <td>  
                                        <?php 
                                        $p_warehouse = $p_address ='';
                                        if($order->warehouse_id){
                                            $p_warehouse = order::getwarehousedata($order->warehouse_id); 
                                            if($p_warehouse){
                                                $p_address = $p_warehouse->address.' '.$p_warehouse->address_2;
                                                echo $p_warehouse->name;
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td style="white-space: break-spaces;">{{ltrim(rtrim($p_address))}}</td>
                                    <td>
                                        @if ($p_warehouse !='')
                                            {{$p_warehouse->phone}}
                                        @endif
                                    </td>
                                    <td>  
                                        <?php 
                                        $r_warehouse ='';
                                        if($order->return_warehouse_id){
                                            $r_warehouse = order::getwarehousedata($order->return_warehouse_id); 
                                            if($r_warehouse){
                                                echo $r_warehouse->name;
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        @if ($r_warehouse !='')
                                            {{$r_warehouse->phone}}
                                        @endif
                                    </td>
                                    <td>{{$order->total_attempt}}</td>
                                    <td>{{$order->ship_fname.' '.$order->ship_lname}}</td>
                                    <td>{{$order->ship_phone}}</td>
                                    <td>{{$order->ship_email}}</td>
                                    <td>{{$order->ship_pincode}}</td>
                                    <td>{{$order->ship_city}}</td>
                                    <td>{{$order->ship_state}}</td>
                                    <td>
                                        <?php 
                                        if($order->ship_country){
                                            $country = $counrtries->find($order->ship_country);
                                            if($country){
                                                echo $country->name;
                                            }
                                        }?>
                                    </td>
                                    <td>{{$order->ship_address.' '.$order->ship_address2}}</td>
                                    <td>
                                        @if ($order->same_add)
                                            {{$order->ship_pincode}}
                                        @else
                                            {{$order->bill_pincode}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->same_add)
                                            {{$order->ship_city}}
                                        @else
                                            {{$order->bill_city}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->same_add)
                                            {{$order->ship_state}}
                                        @else
                                            {{$order->bill_state}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->same_add)
                                            {{@$country->name;}}
                                        @else
                                        <?php 
                                        if($order->bill_country){
                                            $bcountry = $counrtries->find($order->bill_country);
                                            if($bcountry){
                                                echo $bcountry->name;
                                            }
                                        }?>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->same_add)
                                            {{$order->ship_address.' '.$order->ship_address2}}
                                        @else
                                            {{$order->bill_address.' '.$order->bill_address_2}}
                                        @endif
                                    </td>
                                    <td>{{$order->length.'*'.$order->breadth.'*'.$order->height}}</td>
                                    <td>{{$order->weight/1000}}</td>
                                    <td>
                                        @if($order->ship_courier_id == '2' || $order->ship_courier_id == '5')
                                            @if($order->ship_courier_id == '2')
                                            <?php 
                                            $vol_weight = ($order->length*$order->breadth*$order->height)/4000
                                            ?>
                                            @else
                                            <?php 
                                            $vol_weight =($order->length*$order->breadth*$order->height)/4750
                                            ?>         
                                            @endif    
                                        @else
                                            <?php 
                                            $vol_weight =($order->length*$order->breadth*$order->height)/5000
                                            ?>           
                                        @endif
                                        {{$vol_weight}}
                                    </td>
                                    <td>
                                        <?php 
                                        $used = ($order->weight/1000 > $vol_weight)? $order->weight/1000 : $vol_weight;
                                        ?>
                                        {{$used}}
                                    </td>
                                    <td>{{round(($order->extra_weight/1000),2)}}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->extra_weight_closed_on)->format('d M, Y') }}</td>
                                    <td>{{$order->extracostwithoutgst}}</td>
                                    <!-- <td> {{$order->freight + $order->cod}}</td> -->
                                    <td> {{$order->freight}}</td>
                                    <td> {{$order->cod}}</td>
                                    @if ($order->pstate == 'Haryana')
                                    
                                        <td>
                                            @if($order->rtocharge_applied =='1')
                                                @if(in_array(strip_tags($order->extra_weight_status),array('Closed in Seller favor','Auto Accepted')))
                                                    {{$order->extracostgst + $order->gst_cod + $order->gst_freight + $order->rto_charge_gst + $order->extracostgst}}
                                                @else 
                                                    {{$order->extracostgst + $order->gst_cod + $order->gst_freight + $order->rto_charge_gst}}
                                                @endif
                                            @else
                                                {{$order->extracostgst + $order->gst_cod + $order->gst_freight}}
                                            @endif 
                                        </td>
                                        <td>0</td>
                                    @else   
                                        <td>0</td>
                                        <td>
                                            @if($order->rtocharge_applied =='1')
                                                @if(in_array(strip_tags($order->extra_weight_status),array('Closed in Seller favor','Auto Accepted')))
                                                    {{$order->extracostgst + $order->gst_cod + $order->gst_freight + $order->rto_charge_gst + $order->extracostgst}}
                                                @else 
                                                    {{$order->extracostgst + $order->gst_cod + $order->gst_freight + $order->rto_charge_gst}}
                                                @endif
                                            @else
                                                {{$order->extracostgst + $order->gst_cod + $order->gst_freight}}
                                            @endif 
                                        </td>
                                    @endif
                                    <td>{{$order->zone}}</td>
                                    <td>{{$order->rto_date}}</td>
                                    <td>{{$order->rev_tracking_info}}</td>
                                    <td>
                                        @if($order->rtocharge_applied =='1')
                                        {{$order->rto_charge_witoutgst}}
                                        @else 
                                        0
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->rtocharge_applied =='1' && in_array(strip_tags($order->extra_weight_status),array('Closed in Seller favor','Auto Accepted')))
                                        {{$order->extracostwithoutgst}}
                                        @else 
                                        0
                                        @endif
                                    </td>
                                    <td>
                                        {{$order->other_charges}}
                                        
                                    </td><!-- 0  is for extra chrge if change, please update total charge also-->
                                    <td>
                                        @if($order->rtocharge_applied =='1')
                                            @if(in_array(strip_tags($order->extra_weight_status),array('Closed in Seller favor','Auto Accepted')))
                                            {{$order->freight + $order->cod + $order->extracostwithoutgst + $order->rto_charge_witoutgst +  $order->extracostwithoutgst}}
                                            @else 
                                            {{$order->freight + $order->cod + $order->extracostwithoutgst + $order->rto_charge_witoutgst}}
                                            @endif
                                        @else
                                            {{$order->freight + $order->cod + $order->extracostwithoutgst}}
                                        @endif 
                                    </td>
                                    <td>{{$order->e_bill_no}}</td>
                                    <td>{{$order->tags}}</td>
                                    <td>{{$order->wcity.' '.$order->wstate}}</td>
                                    <!-- <td>
                                        @if($order->rtocharge_applied ==1)
                                            @if(in_array($order->extra_weight_status,array('2','3')))
                                                {{$order->extracostgst + $order->gst_cod + $order->gst_freight + $order->rto_charge_gst + $order->extracostgst}}
                                            @else 
                                                {{$order->extracostgst + $order->gst_cod + $order->gst_freight + $order->rto_charge_gst}}
                                            @endif
                                        @else
                                            {{$order->extracostgst + $order->gst_cod + $order->gst_freight}}
                                        @endif    
                                    </td> -->
                                    <td>
                                        @if($order->rtocharge_applied =='1')
                                            @if(in_array(strip_tags($order->extra_weight_status),array('Closed in Seller favor','Auto Accepted')))
                                                {{$order->extracostgst + $order->gst_cod + $order->gst_freight + $order->freight + $order->cod + $order->extracostwithoutgst + $order->other_charges + $order->rto_charge_witoutgst + $order->rto_charge_gst + $order->extracostwithoutgst + $order->extracostgst}}
                                            @else 
                                                {{$order->extracostgst + $order->gst_cod + $order->gst_freight + $order->freight + $order->cod + $order->extracostwithoutgst + $order->other_charges + $order->rto_charge_witoutgst + $order->rto_charge_gst }}
                                            @endif
                                        @else
                                            {{$order->extracostgst + $order->gst_cod + $order->gst_freight + $order->freight + $order->cod + $order->extracostwithoutgst + $order->other_charges}}
                                        @endif
                                    </td>
                                    <td>{{$order->company_name}}</td>
                                    <td>{{$order->sm}}</td>
                                </tr>
                                @endforeach
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?> 
            </div>   
        </div>   
    </div>     
</div>

@endsection