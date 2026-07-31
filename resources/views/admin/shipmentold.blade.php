@extends('admin.admin_layouts')

@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp
<!-- Resources -->
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

<style>
.canvasjs-chart-credit
{
    display: none !important;
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
.card-raised {
    box-shadow: 0px 2px 1px -1px rgba(0, 0, 0, 0.2), 0px 1px 1px 0px rgba(0, 0, 0, 0.14), 0px 1px 3px 0px rgba(0, 0, 0, 0.12);
}
.card-raised {
    border: none;
}
.card {
    line-height: 1.25rem;
    color: #000;
    letter-spacing: 0.0178571429em;
    font-size: 0.875rem;
    margin-bottom: 0px;
}
.border-primary {
    --bs-border-opacity: 1;
    border-color: rgba(var(--bs-primary-rgb), var(--bs-border-opacity)) !important;
}
.icon-circle {
    height: 3rem;
    width: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}
.body2 {
    padding: 10px;
}
.body2 a{
    color: black;
}
.display-5 {
    /* font-size: 20px; */
    line-height: 2rem;
    font-size: 1.5rem;
}
.border-start{
    border-left:2px solid;
}
.border-warning{
border-color:#f3ad06;
}
.col-lg-2.col-md-6.col-sm-6 {
    padding: 0;
}
@media (min-width: 992px){
.col-lg-2 {
    -ms-flex: 0 0 20%;
    flex: 0 0 20%;
    max-width: 20%;
}
}
 </style> 
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
                        <div class="hori-selector" style="margin-left: 20px;left: 126.138px;width: 154.875px;"><div class="left"></div><div class="right"></div></div>
                        <li><a href="{{ route('admin.dashboard') }}" data-id="Overview">Overview</a></li>
                        <li  class="active"><a href="{{route('admin.dashboard.shipment')}}" data-id="channel">Shipment Status</a></li>
                        <li><a href="{{route('admin.dashboard.top')}}" data-id="top10">Top 10</a></li>
                        
                    </ul>
                </div>
                <div class="card top_report card mt-30 Overview hide">
                Please wait.. Data is loading
                </div>

                <div class="card mt-30 channel ">
                    <script>
                        
                        window.onload = function () {

                            var chart = new CanvasJS.Chart("chartContainer", {
                                exportEnabled: false,
                                animationEnabled: true,
                                title:{
                                    text: "C.O.D vs Pre-Paid"
                                },
                                subtitles: [{
                                    text: "Courier wise"
                                }], 
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
                                    name: "Pre-Paid",
                                    showInLegend: true,  
                                    color: "#6200ea",   
                                    fillOpacity: .8,  
                                    yValueFormatString: "#,##0.# Units",
                                    dataPoints: [
                                        <?php 
                                           foreach($modedata as $key=>$o): ?>
                                           {label:"<?php echo ucfirst(str_replace('_',' ',$key))?>",  y: <?php echo $o['Pre-Paid'] ?>},
                                        <?php endforeach
                                        ?>
                                       
                                    ]
                                },
                                {
                                    type: "column",
                                    name: "C.O.D",
                                    axisYType: "secondary",
                                    showInLegend: true,
                                    color : "#b07ff4",
                                    fillOpacity: .8, 
                                    yValueFormatString: "#,##0.# Units",
                                    dataPoints: [
                                        <?php 
                                           foreach($modedata as $key=>$o): ?>
                                           {label:"<?php echo ucfirst(str_replace('_',' ',$key))?>",  y: <?php echo $o['C.O.D'] ?>},
                                        <?php endforeach
                                        ?>
                                    ]
                                }
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
                        }
                    </script>
                    <div class="row" style="margin:0">
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="body2">
                                <div class="card card-raised border-start border-warning border-4">
                                    <div class="bg-warning text-white" style=" line-height: 2rem;padding: 10px 0px;font-size: 1.5rem;text-align: center;">Total Orders</div>        
                                    <div style="line-height: 4rem;font-size: 1.5rem; text-align: center;"><a href="{{ route('admin.order.all') }}" target="_blank">{{count($orderall)}}</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="body2">
                                <div class="card card-raised border-start border-success border-4">
                                    <div class="bg-success text-white" style=" line-height: 2rem;padding: 10px 0px;font-size: 1.5rem;text-align: center;">Booked</div>        
                                    <div style="line-height: 4rem;font-size: 1.5rem; text-align: center;"><a href="{{ route('admin.order.all', ['exceptnewcancel' => true]) }}" target="_blank">{{$tshipment}}</a></div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="body2">
                                <div class="card card-raised border-start border-danger border-4">
                                    <div class="bg-danger text-white" style=" line-height: 2rem;padding: 10px 0px;font-size: 1.5rem;text-align: center;">In Transit</div>        
                                    <div style="line-height: 4rem;font-size: 1.5rem; text-align: center;"><a href="{{ route('admin.order.all', ['intransit' => true]) }}" target="_blank">{{$intrsit}}</a></div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="body2">
                                <div class="card card-raised border-start border-info border-4">
                                    <div class="bg-info text-white" style=" line-height: 2rem;padding: 10px 0px;font-size: 1.5rem;text-align: center;">Delivered</div>        
                                    <div style="line-height: 4rem;font-size: 1.5rem; text-align: center;">
                                        <a href="{{ route('admin.order.all', ['delivered' => true]) }}" target="_blank"> {{$delivredorder}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="body2">
                                <div class="card card-raised border-start border-secondary border-4">
                                    <div class="bg-secondary text-white" style=" line-height: 2rem;padding: 10px 0px;font-size: 1.5rem;text-align: center;">RTO</div>        
                                    <div style="line-height: 4rem;font-size: 1.5rem; text-align: center;">
                                        <a href="{{ route('admin.order.all', ['rto' => true]) }}" target="_blank"> {{$rtoorder}}</a>
                                    </div>
                                </div>
                            </div>
                        </div> 
                            
                    </div>
                    <div class="card-header bg-secondary text-white px-4" style="text-align: center;font-size: 21px;">
                        Today's Activity
                    </div>
                    <div class="row" style="margin:0">
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="body2">
                                <div class="card card-raised border-start border-warning border-4">
                                    <div class="bg-warning text-white" style="line-height: 2rem;font-size: 1.5rem;height: 70px;">
                                        <span style="padding: 10px;position: absolute;padding-top: 23px;text-align: center;">Shipments</span>
                                        <span class="bg-white" style="float: right;margin: 0;height: 68px;width: 34%;text-align: center;color: black;padding-top: 23px;margin: 1px;">
                                            <a href="{{ route('admin.order.all', ['shipped_today' => true]) }}" target="_blank"> {{$todayshipment}}</a>
                                        </span>
                                    </div>        
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="body2">
                                <div class="card card-raised border-start border-success border-4">
                                    <div class="bg-success text-white" style="line-height: 2rem;font-size: 1.5rem;height: 70px;">
                                        <span style="padding: 10px;position: absolute;padding-top: 6px;text-align: center;padding-left: 20px;">Pickup<br>Pending</span>
                                        <span class="bg-white" style="float: right;margin: 0;height: 68px;width: 34%;text-align: center;color: black;padding-top: 23px;margin: 1px;">
                                            <a href="{{ route('admin.order.all', ['manifest_today' => true]) }}" target="_blank">    {{$todaypickpending}}</a>
                                        </span>
                                    </div>        
                                </div>
                            </div>
                        </div> 
                         <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="body2">
                                <div class="card card-raised border-start border-secondary border-4">
                                    <div class="bg-secondary text-white" style="line-height: 2rem;font-size: 1.5rem;height: 70px;">
                                        <span style="padding: 10px;position: absolute;padding-top: 6px;text-align: center;padding-left: 20px;">Manifested<br>before 12</span>
                                        <span class="bg-white" style="float: right;margin: 0;height: 68px;width: 34%;text-align: center;color: black;padding-top: 23px;margin: 1px;">
                                            <a href="{{ route('admin.order.all', ['manifested_before12' => true]) }}" target="_blank">    {{$todaybefore12pickpending}}</a>
                                        </span>
                                    </div>        
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="body2">
                                <div class="card card-raised border-start border-danger border-4">
                                    <div class="bg-danger text-white" style="line-height: 2rem;font-size: 1.5rem;height: 70px;">
                                        <span style="padding: 10px;position: absolute;padding-top: 6px;text-align: center;padding-left: 20px;">Out for<br>Delivery</span>
                                        <span class="bg-white" style="float: right;margin: 0;height: 68px;width: 34%;text-align: center;color: black;padding-top: 23px;margin: 1px;">
                                            <a href="{{ route('admin.order.all', ['ood' => true]) }}" target="_blank"> {{$todayood}}</a>
                                        </span>
                                    </div>        
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="body2">
                                <div class="card card-raised border-start border-info border-4">
                                    <div class="bg-info text-white" style="line-height: 2rem;font-size: 1.5rem;height: 70px;">
                                        <span style="padding: 10px;position: absolute;padding-top: 23px;text-align: center;">Delivered</span>
                                        <span class="bg-white" style="float: right;margin: 0;height: 68px;width: 34%;text-align: center;color: black;padding-top: 23px;margin: 1px;"> <a href="{{ route('admin.order.all', ['delivered_today' => true]) }}" target="_blank"> {{$todaydeliverd}}</a></span>
                                    </div>        
                                </div>
                            </div>
                        </div>
<!--                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="body2">
                                <div class="card card-raised border-start border-secondary border-4">
                                    <div class="bg-secondary text-white" style="line-height: 2rem;font-size: 1.5rem;height: 70px;">
                                        <span style="padding: 10px;position: absolute;padding-top: 23px;text-align: center;font-size: 11px;">Revenue(₹)</span>
                                        <span class="bg-white" style="float: right;margin: 0;height: 68px;width: 58%;text-align: center;color: black;padding-top: 23px;margin: 1px;">
                                            {{$todayrev}}
                                        </span>
                                    </div>        
                                </div>
                            </div>
                        </div>    -->
                    </div>
                    <div class="row" style="margin:0">
                        <div class="col-lg-7 col-md-6 col-sm-6">
                            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                        </div>
                        <div class="col-lg-5 col-md-6 col-sm-6">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>Courier</th>
                                            <th>Total</th>
                                            <th>PPD</th>
                                            <th>COD</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($modedata as $skey=>$sd)
                                        @php
                                        $total = $sd['Pre-Paid']+$sd['C.O.D'];
                                        @endphp
                                        <tr>
                                            <td>{{$skey}}</td>
                                            <td>{{$total}}</td>
                                            <td>{{$sd['Pre-Paid']}}
                                                @if($total !=0)
                                                    ({{ round($sd['Pre-Paid']/$total*100,1)}}%)
                                                @else
                                                    (0%)    
                                                @endif
                                            </td>
                                           
                                            <td>{{$sd['C.O.D']}}
                                                @if($total !=0)
                                                    ({{ round($sd['C.O.D']/$total*100,1)}}%)
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

                <div class="card mt-30 top10 hide">
                   Please wait.. Data is loading
                </div>

                <div class="card mt-30 ndr hide">
                    Please wait.. Data is loading
                </div> 
            </div>
        </div>

    </div>


</div>


     
@endsection


 