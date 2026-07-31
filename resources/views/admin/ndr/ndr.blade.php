@extends('admin.admin_layouts')
@section('admin_content')

<style>

.icon-circle {
    height: 3rem;
    width: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}
.text-warning {
    --bs-text-opacity: 1;
    color: rgba(var(--bs-warning-rgb), var(--bs-text-opacity)) !important;
}
.top_report .body2 {
    padding: 10px;
}
.card{
    margin-bottom:  0px;
}
.col-lg-3.col-md-6.col-sm-6,.col-lg-6.col-md-6.col-sm-6 {
    padding: 0;
}
.bg-white-50 {
    background-color: rgba(255, 255, 255, 0.5);
}
.display-5.text-white {
    /* font-size: 20px; */
    line-height: 2rem;
    font-size: 1.5rem;
}
.canvasjs-chart-credit
{
    display: none !important;
}
.ordercodeclss::before {
    content: "\A";
    white-space: pre;
    display: block;
    line-height: 0px;
}
textarea {
  width: 100%;
  height: 80px;
  padding: 12px 20px;
  box-sizing: border-box;
  border: 2px solid #ccc;
  border-radius: 4px;
  background-color: #f8f8f8;
  font-size: 16px;
  resize: none;
}
</style> 
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <!-- Main body part  -->
    <div class="container-fluid">
        <!-- Page header section  -->
        
       
        <div class="block-header">
            <div class="row">
                <div class="col-lg-5 col-md-8 col-sm-12">
                    <h2>Hyloship - NDR ( Non-Delivery Report ) </h2>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-12">
                <!-- <div id="navbar-animmenu">
                    <ul class="show-dropdown main-navbar">
                        <div class="hori-selector" style="margin-left: 20px;">
                            <div class="left"></div>
                            <div class="right"></div>
                        </div>
                        <li <?php if($activepage =='dashboard' || $activepage ==''){ echo 'class="active"'; }?>><a href="javascript:void(0);" data-id="dashboard">Dashboard</a></li>
                        <li <?php if($activepage !='dashboard'){ echo 'class="active"'; }?>><a href="javascript:void(0);" data-id="action_pending">Action Pending</a></li>
                        <li><a href="javascript:void(0);" data-id="action_taken">Action Taken</a></li>
                        <li><a href="javascript:void(0);" data-id="rto_deliverd">RTO/Delivered</a></li>
                    </ul>
                </div> -->
                <div class="rate-tabs">
                    <ul class="rate-tabs-menu">
                        <li class="active" data-id="dashboard">Dashboard</li>
                        <li data-id="action_pending">Action Pending</li>
                        <li data-id="action_taken">Action Taken</li>
                        <li data-id="rto_deliverd">RTO/Delivered</li>
                    </ul>
                </div>

                <div class="card mt-30 dashboard <?php if($activepage !='dashboard') echo 'hide'; ?>">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Dashboard</h5>
                    </div>
                    <div class="card-body">
                        <div class="top_report">
                            <div class="row" style="margin:0">
                                <div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0px">
                                <div class="body2">
                                    <div class="card card-raised dashboard">
                                        <div class="card-body px-4 content">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="me-2">
                                                    <div class="display-5 font-size3em" style="font-size: 2em;">
                                                    <a href="{{ route('admin.order.all', ['ndr' => true]) }}" target="_blank">{{$order_q}}</a>
                                                    </div>
                                                    <div class="card-text">Total Shipments</div>
                                                    </div>
                                                    <div class="icon-circle bg-white-50 text-warning">
                                                        <i class="fa fa-2x fa-home text-col-yellow"></i>
                                                    </div>
                                                </div>
                                                <div class="card-text" style="">
                                                    Till Date
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0px">
                                <div class="body2">
                                    <div class="card card-raised dashboard">
                                        <div class="card-body px-4 content">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="me-2">
                                                    <div class="display-5 font-size3em" style="font-size: 2em;">
                                                    <a href="{{ route('admin.order.all', ['order_status' => 10]) }}" target="_blank">{{count($ndr_order)}}</a>
                                                    </div>
                                                        <div class="card-text">Total NDR</div>
                                                    </div>
                                                    <div class="icon-circle bg-white-50 text-warning"> <i class="fa fa-2x fa-truck  text-col-green"></i></div>
                                                </div>
                                                <div class="card-text" style="">
                                                <div class="caption fw-500 me-2">
                                                            @if ($order_q !=0 && count($ndr_order) !=0)
                                                                {{round((count($ndr_order)/$order_q)*100,2)}}
                                                            @else 
                                                                0
                                                            @endif
                                                            % of total orders</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0px">
                                <div class="body2">
                                    <div class="card card-raised dashboard">
                                        <div class="card-body px-4 content">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="me-2">
                                                    <div class="display-5 font-size3em" style="font-size: 2em;">
                                                    <a href="{{ route('admin.order.all', ['delivered' => true]) }}" target="_blank">{{count($delivered)}}</a>
                                                    </div>
                                                        <div class="card-text">Delivered</div>
                                                    </div>
                                                    <div class="icon-circle bg-white-50 text-warning"> <i class="fa fa-2x fa-money text-col-red"></i></div>                                                </div>
                                                <div class="card-text" >
                                                <div class="caption fw-500 me-2" style="">
                                                @if (count($ndr_order) !=0 && count($delivered) !=0)
                                                                {{round((count($delivered)/count($ndr_order))*100,2)}}
                                                            @else 
                                                                0
                                                            @endif
                                                            % of total orders</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0px">
                                <div class="body2">
                                    <div class="card card-raised dashboard">
                                        <div class="card-body px-4 content">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="me-2">
                                                    <div class="display-5 font-size3em" style="font-size: 2em;">
                                                    <a href="{{ route('admin.order.all', ['rto' => true]) }}" target="_blank">{{count($rto_order)}}</a>
                                                    </div>
                                                        <div class="card-text">RTO</div>
                                                    </div>
                                                    <div class="icon-circle bg-white-50 text-warning"> <i class="fa fa-2x fa-balance-scale text-col-blue"></i></div>                                                </div>
                                                <div class="card-text" >
                                                <div class="caption fw-500 me-2" style="">
                                                
                                                    @if (count($rto_order) !=0 && count($ndr_order) !=0)
                                                                {{round((count($rto_order)/count($ndr_order)),2)}}
                                                            @else 
                                                                0
                                                            @endif
                                                            % of total orders</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row" style="margin:0;padding: 10px;">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                                    
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
                                </div>
                        </div>
                    </div>    
                </div> 

                <div class="card mt-30 action_pending <?php if($activepage =='dashboard') echo 'hide'; ?>">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Action Pending</h5>
                    </div>
                    <div class="card-body">
                        <form id="myForm" action="{{ route('admin.payment.action') }}" method="POST">
                        @csrf
                        <div class="headers d-flex justify-content-between" style="padding-bottom: 0;">
                            <div class="form-group col-md-3">
                                <label class="mr-2">@lang('Action')</label>
                                <select class="form-control" name="status" id="myselect">
                                    <option value="" selected disabled>@lang('Select One')</option>
                                    <option value="ndraction">Add NDR Action</option>
                                   
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="closing_description" class="form-control-label">Description</label><span class="required"> *</span>:<br>
                                <textarea name="closing_description" id="textareaID" required></textarea>
                            </div>
                            <input type="hidden" name ='path' value="all">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered sorttable table-striped table-hover" id="table1" name='action_pending{{date('Ym')}}' width="100%" cellspacing="0" data-show-export="true" >
                                <thead>
                                    <tr>
                                        <th data-field="hideexport" class="text-center">
                                            <label class="fancy-checkbox">
                                                <input class="select-all" type="checkbox" name="checkbox">
                                                <span></span>
                                            </label>
                                        </th>
                                        <th>NDR Date</th>
                                        <th>Order Id</th>
                                        <th>Product</th>
                                        <?php if($user_id ==100){
                                           echo '<th>Product Name</th>'; 
                                        }?>
                                        <th>Customer Info</th>
                                        <th>Customer contact</th>
                                        <?php if($user_id ==100){
                                           echo '<th>Customer Address</th>'; 
                                        }?>
                                        <th>Courier</th>
                                        <th>Status</th>
                                        <th>NDR</th>
                                        <th data-field="hideexport">Action</th>
                                        <!-- <th data-field="hideexport">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody id="myTable">
                                    @foreach($action_pending as $row)
                                    <tr>
                                        <td class="text-center">
                                            <label class="fancy-checkbox">
                                                <input class="checkbox-tick" type="checkbox" name="id[{{ $row->id }}]" value="{{ $row->id }}">
                                                <span></span>
                                            </label>
                                        </td>
                                        <td>
                                        @php 
                                            $date =''
                                            @endphp
                                            @foreach($row->orderlog as $orderlog)
                                            @if($orderlog->new_value == 'NDR')
                                            @php
                                            $date = $orderlog->created_at;
                                            @endphp
                                            @endif
                                            @endforeach
                                            {{explode(' ',$date)[0]}}
                                        </td>
                                        <td>{{$row->order_id}}</td>
                                        <td>
                                            @php 
                                            $codes = $pname ='';
                                            @endphp
                                            @foreach($row->detail as $detail)
                                            @php
                                            $codes .= $detail->code.',';
                                            $pname .= $detail->name.',';
                                            @endphp
                                            @endforeach
                                            @if (strlen(rtrim($codes,',')) >13)
                                                {{substr(rtrim($codes,','),0,10)}} ...
                                            @else
                                            {{rtrim($codes,',')}}
                                            @endif
                                        </td>
                                        @if ($user_id ==100)
                                        <td>{{$pname}}</td>
                                        @endif
                                        <td>{{$row->ship_fname}} {{$row->ship_lname}} </td>
                                        
                                        <td>{{$row->ship_phone}}</td>
                                        @if ($user_id ==100)
                                        <td>{{$row->ship_address}}</td>
                                        @endif
                                        <td>{{$row->tracking_info}}
                                            @if ($row->ship_courier_id)
                                            | {{ $couriers[$row->ship_courier_id]['name'] }} 
                                            @endif
                                        </td>
                                        <td>
                                            @if ($row->manifest_id && strip_tags($row->status) =='Shipped')
                                                <span class="badge text-white bg-danger">Manifested</span>
                                            @else
                                                {!! $row->status !!}
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($row->orderlog as $orderlog)
                                                @if($orderlog->new_value == 'NDR')
                                                
                                                    <span class="ordercodeclss">{{$orderlog->created_at}}</span>
                                                    {!! $orderlog->ordercode !!}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ URL::to('admin/payment/addndr/' . $row->id) }}" class="btn btn-secondary assign" data-title="Add NDR"
                                                style="" ><i class="fa fa-plus"></i></a>
                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        </form>    
                    </div>
                </div>
                <div class="card mt-30 action_taken hide">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Action Taken</h5>
                    </div>
                    <form action="{{ route('admin.ndr.ndr') }}" method="GET" style='padding: 16px;'>
                    <div class="row">
                        <div class="form-group col-md-8">
                            <label>Select Date Range</label><span class="required"> *</span>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input class="form-control " type="date" name="start_date" required="" value="{{explode(' ',$re_data['start_date'])[0]}}" id="_1">
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control " type="date" name="end_date" required="" value="{{explode(' ',$re_data['end_date'])[0]}}" id="_2">
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <x-button size="col-lg-3" type="submit" name="Search" />
                    </div>
                </form>  
                <hr>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered sorttable table-striped table-hover" id="table2" name='action_taken{{date('Ym')}}' width="100%" cellspacing="0" data-show-export="true" >
                            <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Product</th>
                                    <th>Customer Info</th>
                                    <th>Customer contact</th>
                                    <th>Awb</th>
                                    <th>Courier</th>
                                    <th>Status</th>
                                    @for($i=1;$i<6;$i++)
                                    <th>Reason{{$i}}</th>
                                    <th>Date{{$i}}</th>
                                    @endfor
                                    <th data-field="hideexport">Remaining Reason</th>
                                    <th>Updated Date </th>
                                    <th>Action</th>
                                    <!-- <th data-field="hideexport">Action</th> -->
                                </tr>
                            </thead>
                            <tbody id="myTable">
                                @foreach($action_taken as $row)
                                <tr>
                                    <td>{{$row->order_id}}</td>
                                    <td>
                                        @php 
                                        $codes =''
                                        @endphp
                                        @foreach($row->detail as $detail)
                                        @php
                                        $codes .= $detail->code.',';
                                        @endphp
                                        @endforeach
                                        @if (strlen(rtrim($codes,',')) >13)
                                            {{substr(rtrim($codes,','),0,10)}} ...
                                        @else
                                        {{rtrim($codes,',')}}
                                        @endif
                                    </td>
                                    <td>{{$row->ship_fname}} {{$row->ship_lname}}</td>
                                    <td>{{$row->ship_phone}}</td>
                                    <td>{{$row->tracking_info}}</td>
                                    <td>   
                                        @if ($row->ship_courier_id)
                                         {{ $couriers[$row->ship_courier_id]['name'] }} 
                                        @endif
                                    </td>
                                    <td>
                                        @if ($row->manifest_id && strip_tags($row->status) =='Shipped')
                                            <span class="badge text-white bg-danger">Manifested</span>
                                        @else
                                            {!! $row->status !!}
                                        @endif
                                    </td>
                                    <?php $j=0;$logarray =  array();?>
                                    @foreach($row->orderlog->reverse() as $orderlog)
                                        @if($orderlog->new_value == 'NDR' )
                                            <?php 
                                            $logarray[$j]['code'] =    $orderlog->ordercode;
                                            $logarray[$j]['date'] =    $orderlog->created_at;
                                             $j+=1; ?>
                                        @endif
                                    @endforeach
                                        
                                    @for($i=0;$i<5;$i++)
                                        @if(isset($logarray[$i]['code']))
                                            <td style="white-space: inherit;">{!! $logarray[$i]['code'] !!}</td>
                                            <td style="white-space: inherit;">{{$logarray[$i]['date']}}</td>
                                        @else
                                            <td></td><td></td>
                                        @endif
                                    @endfor
                                    @if(count($logarray)>5)
                                    <td style="white-space: inherit;">
                                        @for($i=5;$i< count($logarray);$i++)
                                        {!! $logarray[$i]['code'] !!} - {{$logarray[$i]['date']}}
                                        @endfor
                                    </td>
                                    @else
                                        <td></td>
                                    @endif
                                        
                                    <td>
                                        {{$row->ndr_action_date}}
                                    </td>
                                    <td style="white-space: inherit;">
                                        {{$row->ndr_action}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-30 rto_deliverd hide">
                    <div class="card-header">
                        <h5 class="card-title mb-0">RTO/Delivered</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-bordered sorttable  table-striped table-hover"  id="table3" name='allndr{{date('Ym')}}' width="100%" cellspacing="0" data-show-export="true" >
                            <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Product</th>
                                    <th>Customer Info</th>
                                    <th>Customer contact</th>
                                    <th>Courier</th>
                                    <th>Status</th>
                                    <th>NDR (Last) </th>
                                    <!-- <th data-field="hideexport">Action</th> -->
                                </tr>
                            </thead>
                            <tbody id="myTable">
                                @foreach($delivered as $row)
                                <tr>
                                    <td>{{$row->order_id}}</td>
                                    <td>
                                        @php 
                                        $codes =''
                                        @endphp
                                        @foreach($row->detail as $detail)
                                        @php
                                        $codes .= $detail->code.',';
                                        @endphp
                                        @endforeach
                                        @if (strlen(rtrim($codes,',')) >13)
                                            {{substr(rtrim($codes,','),0,10)}} ...
                                        @else
                                        {{rtrim($codes,',')}}
                                        @endif
                                    </td>
                                    <td>{{$row->ship_fname}} {{$row->ship_lname}}</td>
                                    <td>{{$row->ship_phone}}</td>
                                    <td>{{$row->tracking_info}}
                                        @if ($row->ship_courier_id)
                                         | {{ $couriers[$row->ship_courier_id]['name'] }} 
                                        @endif
                                    </td>
                                    <td>
                                        @if ($row->manifest_id && strip_tags($row->status) =='Shipped')
                                            <span class="badge text-white bg-danger">Manifested</span>
                                        @else
                                            {!! $row->status !!}
                                        @endif
                                    </td>
                                    <td>
                                        @php 
                                        $date =$ordercode=''
                                        @endphp
                                        @foreach($row->orderlog as $orderlog)
                                        @if($orderlog->new_value == 'NDR')
                                        @php
                                        $date = $orderlog->created_at;
                                        $ordercode = $orderlog->ordercode;
                                        @endphp
                                        @endif
                                        @endforeach
                                        {{$date}}{!! $ordercode !!}
                                    </td>
                                </tr>
                                @endforeach
                                @foreach($rto_order as $row)
                                <tr>
                                    <td>{{$row->order_id}}</td>
                                    <td>
                                        @php 
                                        $codes =''
                                        @endphp
                                        @foreach($row->detail as $detail)
                                        @php
                                        $codes .= $detail->code.',';
                                        @endphp
                                        @endforeach
                                        @if (strlen(rtrim($codes,',')) >13)
                                            {{substr(rtrim($codes,','),0,10)}} ...
                                        @else
                                        {{rtrim($codes,',')}}
                                        @endif
                                    </td>
                                    <td>{{$row->ship_fname}} {{$row->ship_lname}} - {{$row->ship_phone}}</td>
                                    <td>{{$row->tracking_info}}
                                        @if ($row->ship_courier_id)
                                         | {{ $couriers[$row->ship_courier_id]['name'] }} 
                                        @endif
                                    </td>
                                    <td>
                                        @if ($row->manifest_id && strip_tags($row->status) =='Shipped')
                                            <span class="badge text-white bg-danger">Manifested</span>
                                        @else
                                            {!! $row->status !!}
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>     
                <div class="card mt-30 all hide">
                    <div class="card-header">
                        <h5 class="card-title mb-0">All</h5>
                    </div>
                    <div class="card-body">
                    
                    </div>
                </div>     
            </div>
        </div>
    </div>
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light",
    // backgroundColor: "#f3ad06",
	title:{
		text: "Day wise NDR(current week)",
        fontSize: 15,
	},
	data: [{        
		type: "line",
        lineColor: "#f3bb23",
        markerColor: "#f3bb23",
      	indexLabelFontSize: 16,
		dataPoints: [
            <?php 
                foreach($weekDays as $key=>$o): ?>
                {label:"<?php echo ucfirst(str_replace('_',' ',$key))?>",  y: <?php echo $o ?>},
            <?php endforeach
            ?>
			
		]
	}]
});
chart.render();

var chart2 = new CanvasJS.Chart("chartContainer2", {
	animationEnabled: true,
	theme: "dark",
  
	title:{
		text: "Day wise Delivered/RTO(current week)",
      fontSize: 15,
	},
	
	axisY: {
		includeZero: true,
		crosshair: {
			enabled: true
		}
	},
	toolTip:{
		shared:true
	},  
	legend:{
		cursor:"pointer",
		verticalAlign: "bottom",
		horizontalAlign: "left",
		dockInsidePlotArea: true,
		// itemclick: toogleDataSeries
	},
	data: [{
		type: "line",
		showInLegend: true,
		name: "Delivered",
		markerType: "square",
		lineColor: "#ee2558",
        markerColor: "#ee2558",
		color: "#ee2558",
		dataPoints: [
            <?php 
                foreach($weekDelivrd as $key=>$o): ?>
                {label:"<?php echo ucfirst(str_replace('_',' ',$key))?>",  y: <?php echo $o ?>},
            <?php endforeach
            ?>
			
		]
	},
	{
		type: "line",
		showInLegend: true,
		name: "RTO",
        lineColor: "#86c541",
        markerColor: "#86c541",
		dataPoints: [
            <?php 
                foreach($weekRTO as $key=>$o): ?>
                {label:"<?php echo ucfirst(str_replace('_',' ',$key))?>",  y: <?php echo $o ?>},
            <?php endforeach
            ?>
			
		]
	}]
});
chart2.render();

}
$('select[name=status]').change(function() {
        if ($('input[name^="id"]:checked').length > 0) {
            var action_type = $('select[name=status]').val();
            if(action_type == 'ndraction'){
                let action_route = `{{ route('admin.payment.action') }}`;
                $('#myForm').attr("action", action_route);
                if ($("#textareaID").val() != "") {
                    $('#myForm').submit();
                }else{
                    toastr.error('Please add Description');
                    $('select[name=status]').val('');
                }
            }
        } else {
            toastr.error('Select atleast one Order');
            $('select[name=status]').val('');
        }
    });

</script>   
<script>
$(document).ready(function () {

    function moveCaret($el) {
        const left = $el.position().left;
        const width = $el.outerWidth();

        $('.rate-tabs-menu')
            .css('--caret-left', left + 'px')
            .css('--caret-width', width + 'px');
    }

    // On tab click
    $('.rate-tabs-menu li').on('click', function () {
        const tab = $(this).data('id');

        // Active tab
        $('.rate-tabs-menu li').removeClass('active');
        $(this).addClass('active');
        moveCaret($(this));

        // Hide all sections
        $('.dashboard, .action_pending, .action_taken, .rto_deliverd').addClass('hide');

        // Show selected section
        $('.' + tab).removeClass('hide');
    });

    // Init on load
    moveCaret($('.rate-tabs-menu li.active'));
});
</script>


@endsection
