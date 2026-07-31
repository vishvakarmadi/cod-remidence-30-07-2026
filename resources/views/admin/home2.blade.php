@extends('admin.admin_layouts')

@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp

<style>
 .bg-white-50 {
    background-color: rgba(255, 255, 255, 0.5);
}
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
.card .card-title {
    font-size: 1.25rem;
    line-height: 2rem;
    font-weight: 500;
    letter-spacing: 0.0125em;
    margin-bottom: 0;
}
.card .card-subtitle {
    font-size: 0.875rem;
    line-height: 1.375rem;
    font-weight: 500;
    letter-spacing: 0.0071428571em;
    margin-bottom: 0;
    opacity: 0.6;
}
.card-subtitle {
    margin-top: calc(-0.5* var(--bs-card-title-spacer-y));
    margin-bottom: 0;
}
#navbar-animmenus {
    background: #ff904f;
    float: left;
    overflow: hidden;
    position: relative;
    padding: 5px 0px;
    width: 100%;
    border-radius: 10px;
}
#navbar-animmenus ul li a {
    color: rgb(0, 0, 0);
    text-decoration: none;
    font-size: 15px;
    line-height: 45px;
    display: block;
    padding: 0px 20px;
    transition-duration: 0.6s;
    transition-timing-function: cubic-bezier(0.68, -0.55, 0.265, 1.55);
    position: relative;
}
#navbar-animmenus ul {
    padding: 0px;
    margin: 0px;
}
.hori-selector {
    display: inline-block;
    position: absolute;
    height: 100%;
    top: 10px;
    left: 0px;
    transition-duration: 0.6s;
    transition-timing-function: cubic-bezier(0.68, -0.55, 0.265, 1.55);
    background-color: #fff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}
#navbar-animmenus li {
    list-style-type: none;
    float: left;
    margin-left: 20px;
}
.hori-selector .right, .hori-selector .left {
    position: absolute;
    width: 25px;
    height: 25px;
    background-color: #fff;
    bottom: 10px;
}
#navbar-animmenus>ul>li.active>a {
    color: #000000;
    background-color: transparent;
    transition: all 0.7s;
}
.display-5.text-white {
    /* font-size: 20px; */
    line-height: 2rem;
    font-size: 1.5rem;
}
.text-white a{
    color: #fff !important;
    /*text-decoration: underline;*/
}
.canvasjs-chart-credit
{
    display: none !important;
}
 </style> 
 <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<!-- Main body part  -->
<div class="container-fluid">
    <!-- Page header section  -->
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12 col-sm-12">
                <h1>Hi, Welcomeback! </h1>
                <span>{{$session->name}}'s Dashboard,</span>
            </div>
        </div>
    </div>

    
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-12">
                <div id="navbar-animmenus">
                    <ul class="show-dropdown main-navbar">
                        <div class="hori-selector" style="margin-left: 20px;    left: 0px;    width: 106.137px;"><div class="left"></div><div class="right"></div></div>
                        <li class="active"><a href="javascript:void(0);" data-id="Overview">Overview</a></li>
                        <li><a href="{{route('admin.dashboard.shipment')}}" data-id="channel">Shipment Status</a></li>
                        <li><a href="{{route('admin.dashboard.top')}}" data-id="top10">Top 10</a></li>
                        <!-- <li><a href="javascript:void(0);" data-id="ndr">NDR</a></li> -->
                    </ul>
                </div>
                <div class="card top_report card mt-30 Overview">
                    <form action="{{ route('admin.dashboard.infilter') }}"  method="post" enctype="multipart/form-data">
                    @csrf
                        
                            <div class="row">
                                <div class="" style="display: initial; margin-left: 29px;">
                                    <!-- <label for="">Select Role *</label> -->
                                    <select name="role_id" class="form-control" >
                                        <option value="td" @if ($role_id =='td') selected @endif>Till date</option>
<!--                                        <option value="ty" @if ($role_id =='ty') selected @endif>This Year</option>
                                        <option value="tm" @if ($role_id =='tm') selected @endif>This Month</option>-->
                                        <option value="tod" @if ($role_id =='tod') selected @endif>Today</option>
                                        <option value="yes" @if ($role_id =='yes') selected @endif>Yesterday</option>
                                        <option value="lt" @if ($role_id =='lt') selected @endif>Last 3 days</option>
                                        <option value="tw" @if ($role_id =='tw') selected @endif>Last 7 days</option>
                                        <option value="lf" @if ($role_id =='lf') selected @endif>Last 15 days</option>
                                        <option value="lth" @if ($role_id =='lth') selected @endif>Last 30 days</option>
                                    </select>
                                </div>
                            </div>
                    </form>
                    <script>
                    $('form select').on('change', function(){
                        $(this).closest('form').submit();
                    });
                        
                    window.onload = function () {

                            var chart = new CanvasJS.Chart("chartContainer", {
                                exportEnabled: false,
                                animationEnabled: true,
                                // title:{
                                //     text: "Car Parts Sold in Different States"
                                // },
                                // subtitles: [{
                                //     text: "Click Legend to Hide or Unhide Data Series"
                                // }], 
                                // axisX: {
                                //     title: "Zones"
                                // },
                                axisY: {
                                    // title: "Oil Filter - Units",
                                    titleFontColor: "#4F81BC",
                                    lineColor: "#4F81BC",
                                    labelFontColor: "#4F81BC",
                                    tickColor: "#4F81BC",
                                    includeZero: true
                                },
                                // axisY2: {
                                //     // title: "Clutch - Units",
                                //     titleFontColor: "#C0504E",
                                //     lineColor: "#C0504E",
                                //     labelFontColor: "#C0504E",
                                //     tickColor: "#C0504E",
                                //     includeZero: true
                                // },
                                toolTip: {
                                    shared: true
                                },
                                legend: {
                                    cursor: "pointer",
                                    itemclick: toggleDataSeries
                                },
                                data: [{
                                    type: "column",
                                    name: "Shippments",
                                    showInLegend: false,      
                                    yValueFormatString: "#,##0.# Units",
                                    dataPoints: [
                                        <?php 
                                           foreach($zonedaat as $key=>$o): ?>
                                           {label:"<?php echo ucfirst(str_replace('_',' ',$key))?>",  y: <?php echo $o ?>},
                                        <?php endforeach
                                        ?>
                                       
                                    ]
                                },
                                // {
                                //     type: "column",
                                //     name: "Clutch",
                                //     axisYType: "secondary",
                                //     showInLegend: true,
                                //     yValueFormatString: "#,##0.# Units",
                                //     dataPoints: [
                                //         { label: "New Jersey", y: 210.5 },
                                //         { label: "Texas", y: 135 },
                                //         { label: "Oregon", y: 425 },
                                //         { label: "Montana", y: 130 },
                                //         { label: "Massachusetts", y: 528 }
                                //     ]
                                // }
                            ]
                            });
                            chart.render();

                            function toggleDataSeries(e) {
                                if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                                    e.dataSeries.visible = false;
                                } else {
                                    e.dataSeries.visible = true;
                                }
                                e.chart.render();
                            }
                    

                            var chart = new CanvasJS.Chart("chartContainer2", {
                            theme: "light2", // "light1", "light2", "dark1", "dark2"
                            exportEnabled: false,
                            animationEnabled: true,
                           
                            data: [{
                                type: "pie",
                                startAngle: 25,
                                
                                indexLabel: "{label} - {y}({p}%)",
                                dataPoints: [
                                    <?php 
                                           foreach($courierdata as $key=>$o): ?>
                                           {label:"<?php echo ucfirst(str_replace('_',' ',$key))?>",  y: <?php echo $o ?>,p:<?php if($tshipment !=0){ echo round((($o/$tshipment)*100),2); }else{ echo 0; }?>},
                                           <?php endforeach
                                    ?>
                                   
                                ]
                            }]
                            });
                            chart.render();

                        }
                    </script>
                    <div class="row" style="margin:0">
                        <div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0px">
                            <div class="body2">
                                <div class="card card-raised bg-warning text-white">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white"><a href="{{ route('admin.order.all', ['exceptnewcancel' => true, 'role_id' => $role_id]) }}" target="_blank">{{$tshipment}}</a></div>
                                                <div class="card-text">Total Shipments</div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-warning"> <i class="fa fa-2x fa-home  text-col-yellow"></i></div>
                                        </div>
                                        <div class="card-text">
                                            <a>
                                            <div class="d-inline-flex align-items-center">
                                                <!-- <i class="material-icons icon-xs">arrow_upward</i> -->
                                                <div class="caption fw-500 me-2">
                                                    @if ($tshipment !=0 && count($orderall) !=0)
                                                   {{round(($tshipment/count($orderall))*100,2)}}
                                                    @else 
                                                        0
                                                    @endif
                                                </div>
                                                <div class="caption">% of total orders</div>
                                            </div>
                                                </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="body2">
                                <div class="card card-raised bg-success text-white">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white">
                                                  <a href="{{ route('admin.order.all', ['delivered' => true, 'role_id' => $role_id]) }}" target="_blank">{{$delivredorder}} (
                                                    @if ($tshipment !=0)
                                                    {{round(($delivredorder/$tshipment)*100,2)}}%
                                                    @else
                                                    0%
                                                    @endif
                                                    )
                                                  </a>
                                                </div>
                                                <div class="card-text">Delivered Shipments</div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-warning"> <i class="fa fa-2x fa-truck  text-col-green"></i></div>
                                        </div>
                                        <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <!-- <i class="material-icons icon-xs">arrow_upward</i> -->
                                                <div class="caption fw-500 me-2">
                                                    @if ($delivredorder !=0 && count($orderall) !=0)
                                                        {{round(($delivredorder/count($orderall))*100,2)}}
                                                    @else 
                                                        0
                                                    @endif
                                                </div>
                                                <div class="caption">% of total orders</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="body2">
                                <div class="card card-raised bg-danger text-white">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white">
                                                  <a href="{{ route('admin.order.all', ['rto' => true, 'role_id' => $role_id]) }}" target="_blank">{{$rtoorder}} (
                                                        @if ($tshipment !=0)
                                                        {{round(($rtoorder/$tshipment)*100,2)}}%
                                                        @else
                                                        0%
                                                        @endif
                                                       )
                                                  </a>
                                              	</div>
                                                <div class="card-text">Total RTO</div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-warning"> <i class="fa fa-2x fa-money text-col-red"></i></div>
                                        </div>
                                        <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <!-- <i class="material-icons icon-xs">arrow_upward</i> -->
                                                <div class="caption fw-500 me-2">
                                                    @if ($rtoorder !=0 && count($orderall) !=0)
                                                        {{round(($rtoorder/count($orderall))*100,2)}}
                                                    @else 
                                                        0
                                                    @endif
                                                </div>
                                                <div class="caption">% of total orders</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="body2">
                                <div class="card card-raised bg-info text-white">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white">
                                                    <a href="{{ route('admin.order.all', ['delivered' => true, 'role_id' => $role_id]) }}" target="_blank">{{$revenue}}</a></div>
                                                <div class="card-text">Total Revenue (₹)</div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-warning"> <i class="fa fa-2x fa-balance-scale text-col-blue"></i></div>
                                        </div>
                                        <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <!-- <i class="material-icons icon-xs">arrow_upward</i> -->
                                                <div class="caption fw-500 me-2">
                                                <div class="caption fw-500 me-2">
                                                    @if ($revenue !=0 && $delivredorder !=0)
                                                        {{round(($revenue/$delivredorder),2)}}
                                                    @else 
                                                        0
                                                    @endif
                                                </div>
                                                </div>
                                                <div class="caption">  &nbsp;Avg. Revenue (₹)</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin:0;padding: 10px;">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="card card-raised h-100" style="border:1px solid">
                                <div class="card-header bg-secondary text-white px-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-4">
                                            <h2 class="card-title text-white mb-0">Zone wise shipment</h2>
                                            <!-- <div class="card-subtitle">Compared to previous year</div> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row gx-4">
                                        <!-- <div class="col-12 col-xxl-2">
                                            <div class="d-flex flex-column flex-md-row flex-xxl-column align-items-center align-items-xl-start justify-content-between">
                                                <div class="mb-4 text-center text-md-start">
                                                    <div class="text-xs font-monospace text-muted mb-1">Actual Revenue</div>
                                                    <div class="display-5 fw-500">$59,482</div>
                                                </div>
                                                <div class="mb-4 text-center text-md-start">
                                                    <div class="text-xs font-monospace text-muted mb-1">Revenue Target</div>
                                                    <div class="display-5 fw-500">$50,000</div>
                                                </div>
                                                <div class="mb-4 text-center text-md-start">
                                                    <div class="text-xs font-monospace text-muted mb-1">Goal</div>
                                                    <div class="display-5 fw-500 text-success">119%</div>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                                        <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="card card-raised h-100" style="border:1px solid">
                                <div class="card-header bg-secondary text-white px-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-4">
                                            <h2 class="card-title text-white mb-0">Courier wise shipment</h2>
                                            <!-- <div class="card-subtitle">Revenue sources</div> -->
                                        </div>
                                       
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <div class="d-flex h-100 w-100 align-items-center justify-content-center">
                                    @if ($cd ==1)
                                    <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
                                    @else
                                    No Data found
                                    @endif
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>Courier</th>
                                            <th>Total</th>
                                            <th>Pending Pickup</th>
                                            <th>Intransit</th>
                                            <th>Delivered</th>
                                            <th>RTO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($statusdata as $skey=>$sd)
                                        @php
                                        $total = $sd['New']+$sd['Shipped']+$sd['Delivered']+$sd['RTO']+$sd['RTO Delivered']+$sd['NDR']+$sd['Pickup Pending']+$sd['RTO In Transit']+$sd['In Transit']+$sd['Out for Delivery']+$sd['Lost']+$sd['Damaged'];
                                        $rto = $sd['RTO']+$sd['RTO Delivered']+$sd['RTO In Transit'];
                                        $intrasit = $sd['NDR']+$sd['In Transit']+$sd['RTO In Transit']+$sd['Damaged']+$sd['Lost']+$sd['Out for Delivery'];
                                        @endphp
                                        <tr>
                                            <td>{{$skey}}</td>
                                            <td>{{$total}}</td>
                                            <td>{{$sd['Pickup Pending']}}
                                                @if($total !=0)
                                                    ({{ round($sd['Pickup Pending']/$total*100,2)}}%)
                                                @else
                                                    (0%)    
                                                @endif
                                            </td>
                                            <td>{{$intrasit}}
                                                @if($total !=0)
                                                    ({{ round($intrasit/$total*100,2)}}%)
                                                @else
                                                    (0%)    
                                                @endif 
                                            </td>
                                            <td>{{$sd['Delivered']}}
                                                @if($total !=0)
                                                    ({{ round($sd['Delivered']/$total*100,2)}}%)
                                                @else
                                                    (0%)    
                                                @endif
                                            </td>
                                            <td>{{($rto)}}
                                                @if($total !=0)
                                                    ({{ round($rto/$total*100,2)}}%)
                                                @else
                                                    (0%)    
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-30 channel hide">
                </div>   
                <div class="card mt-30 hide top10">
            
                </div>   
                <div class="card mt-30 ndr hide">
            
                </div>
            </div>
        </div>
    </div>
</div>


@endsection


 