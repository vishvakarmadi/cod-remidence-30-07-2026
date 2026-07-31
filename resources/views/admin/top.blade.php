@extends('admin.admin_layouts')

@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp
<style>

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
.canvasjs-chart-credit
{
    display: none !important;
}
.loading-indicators {
    padding: 10px;
    border: 2px solid #ff904f; /* Blue */
    border-radius: 5px;
    color: #474bff;
    height: 370px; 
    width: 100%
    /*animation: pulse 1.5s infinite;*/
}
 </style> 
  <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
   <script>
    function myFunction() {
        $.ajax({
            url: "{{ route('admin.dashboard.topload') }}",
            type: 'GET',
            dataType: 'json',
            
            success: function(data) {
                console.log('AJAX request successful:', data); 
                let top10cus = data.top10cus;
                let top10pr = data.top10pr;
                let top10pin = data.top10pin;
               
                
                // Prepare data points array
                var dataPoints = top10cus.map(function(customer) {
                    return {
                        label: customer.name ? customer.name : (customer.ship_fname + ' ' + customer.ship_lname),
                        y: customer.total
                    };
                });
                
                // Prepare data points array
                var dataPoints2 = top10pr.map(function(product) {
                    return {
                        label: product.name ? product.name : (product.ship_fname + ' ' + product.ship_lname),
                        y: Number(product.total)
                    };
                });
                
               
                
                
                
                var chart = new CanvasJS.Chart("chartContainer", {
                    exportEnabled: false,
                    animationEnabled: true,
                    title:{
                        text: "Top 10 Customers"
                    },
                    axisY: {
                        // title: "Oil Filter - Units",
                        titleFontColor: "#4F81BC",
                        lineColor: "#4F81BC",
                        labelFontColor: "#4F81BC",
                        tickColor: "#4F81BC",
                        includeZero: true,
                        margin: 20,
                    },
                    axisX: {
                        labelAngle: 50,
                    },
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
                        color: "#6200ea",   
                        fillOpacity: .8,  
                        dataPoints: dataPoints // Use the prepared data points
                    },

                ]
                });
                chart.render();
                var chart2 = new CanvasJS.Chart("chartContainer2", {
                    exportEnabled: false,
                    animationEnabled: true,
                    title:{
                        text: "Top 10 Products"
                    },
                    axisY: {
                        // title: "Oil Filter - Units",
                        titleFontColor: "#4F81BC",
                        lineColor: "#4F81BC",
                        labelFontColor: "#4F81BC",
                        tickColor: "#4F81BC",
                        includeZero: true,
                        margin: 20,
                    },
                    axisX: {
                        labelAngle: 50,
                    },
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
                        color: "#b07ff4",   
                        fillOpacity: .8, 
                        dataPoints: dataPoints2 // Use the prepared data points
                    },

                ]
                });
                chart2.render();
                
                
                function toggleDataSeries(e) {
                    if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                        e.dataSeries.visible = false;
                    } else {
                        e.dataSeries.visible = true;
                    }
                    e.chart.render();
            }
            },
            error: function(xhr, status, error) {
                console.error('Error loading shipment data:', error);
            }
        });
    }
    window.addEventListener('load', myFunction);
</script>

<div class="container-fluid">
    

    
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-12">
            
            <div id="navbar-animmenu">
                    <ul class="show-dropdown main-navbar">
                        <div class="hori-selector" style="margin-left: 20px;left: 301.013px;width: 87.3875px;"><div class="left"></div><div class="right"></div></div>
                        <li><a href="{{ route('admin.dashboard') }}" data-id="Overview">Overview</a></li>
                        <li><a href="{{route('admin.dashboard.shipment')}}" data-id="channel">Shipment Status</a></li>
                        <li  class="active"><a href="{{route('admin.dashboard.top')}}" data-id="top10">Top 10</a></li>
                        <!-- <li><a  href="{{route('admin.dashboard.ndr')}}" data-id="ndr">NDR</a></li> -->
                    </ul>
                </div>
                <div class="card top_report card mt-30 Overview hide">
                    Please wait.. Data is loading
                </div>

                <div class="card mt-30 channel hide">
                    Please wait.. Data is loading
                </div>

                <div class="card mt-30 top10 ">
                    <div class="body row">
                        <div class="col-md-6 mt-30">
                            <div id="chartContainer" style="height: 370px; width: 100%;">
                                <div class="loading-indicators" ><img src="https://hyloship.com/public/loader.gif" style="width: 62px;top: 41%;position: absolute;left: 40%;"></div>
                            </div>
                             
                        </div>
                        <div class="col-md-6 mt-30">
                            <div id="chartContainer2" style="height: 370px; width: 100%;">
                                <div class="loading-indicators" ><img src="https://hyloship.com/public/loader.gif" style="width: 62px;top: 41%;position: absolute;left: 40%;"></div>
                            </div>
                            
                        </div>
                        

                        <!-- <div class="col-md-6 mt-30">
                            <div id="chartContainer3" style="height: 370px; width: 100%;">
                                <div class="loading-indicators" ><img src="https://hyloship.com/public/loader.gif" style="width: 62px;top: 41%;position: absolute;left: 40%;"></div>
                            </div>
                            
                        </div>
                        <div class="col-md-6 mt-30">
                            <div id="chartContainer4" style="height: 370px; width: 100%;">
                                <div class="loading-indicators" ><img src="https://hyloship.com/public/loader.gif" style="width: 62px;top: 41%;position: absolute;left: 40%;"></div>
                            </div>
                            
                        </div> -->
                        
                        
                    </div>
                </div>

                <div class="card mt-30 ndr hide">
                    Please wait.. Data is loading
                </div> 
            </div>
        </div>

    </div>


</div>
 @endsection