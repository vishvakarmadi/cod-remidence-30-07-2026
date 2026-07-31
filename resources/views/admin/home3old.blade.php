@extends('admin.admin_layouts') @section('admin_content') @php $session = Auth::guard('admin')->user(); @endphp

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
    .font-size1em{
        font-size: 1.19em !important;
    }
    .me-2 .card-text {
    font-size: 13px;
}
    @media (min-width: 992px) {
    .col-lg-2 {
        -ms-flex: 0 0 16.666667%;
        flex: 0 0 19.666667%;
        max-width: 20.666667%;
    }
}
</style>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function myFunction() {
    //    console.log('Starting AJAX request...');
    
        $.ajax({
          url: '/admin/load', 
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            console.log('AJAX request successful:', data); 
    
            // Assigning values and calculating percentages
            let totalShipments = data.tshipment || 0;
            let deliveredOrder = data.delivredorder || 0;
            let orderCount = data.orderall ? data.orderall.length : 0; 
            let rtoorder = data.rtoorder || 0;
            let revenue = data.revenue || 0;
            let intransit = data.intransit || 0;
            revenue = parseFloat(revenue.toFixed(2));
//    alert(intransit);
            let deliveredPercentage = (totalShipments !== 0) ? round((deliveredOrder / totalShipments) * 100, 2) : 0;
            let transitPercentage = (totalShipments !== 0) ? round((intransit / totalShipments) * 100, 2) : 0;
            let rtopercentage = (totalShipments !== 0) ? round((rtoorder / totalShipments) * 100, 2) : 0;
            let percentage = (orderCount > 0) ? round((totalShipments / orderCount) * 100, 2) : 0;
            let percentage2 = (orderCount > 0) ? round((deliveredOrder / orderCount) * 100, 2) : 0;
            let percentage3 = (orderCount > 0) ? round((rtoorder / orderCount) * 100, 2) : 0;
            let percentage4 = (orderCount > 0) ? round((intransit / orderCount) * 100, 2) : 0;
            let avgrevenue = (deliveredOrder !== 0) ? round((revenue / deliveredOrder), 2) : 0;
    
            // Updating HTML elements with the calculated values
            $('#tshipment').text(totalShipments);
            $('#delshipment').text(deliveredOrder);
            $('#trasitshipment').text(intransit);
            $('#delshipmentperce').text(deliveredPercentage + '%');
            $('#trasitshipmentperce').text(transitPercentage + '%');
            $('#rto').text(rtoorder);
            $('#revenue').text(revenue);
            $('#percentage').text(percentage);
            $('#percentage2').text(percentage2);
            $('#rtopercent').text(rtopercentage + '%');
            $('#percentage3').text(percentage3);
            $('#percentage4').text(percentage4);
            $('#avgrevenue').text(avgrevenue);
        
            $('#courier-status-table').empty();
            for (let key in data.statusdata) {
        if (data.statusdata.hasOwnProperty(key)) {
            let sd = data.statusdata[key];
        //    console.log(key);
            let total = sd.New + sd.Shipped + sd.Delivered + sd.RTO + 
                        sd["RTO Delivered"] + sd.NDR + sd["Pickup Pending"] + 
                        sd["RTO In Transit"] + sd["In Transit"] + 
                        sd["Out for Delivery"] + sd.Lost + sd.Damaged;
    
            let rto = sd.RTO + sd["RTO Delivered"] + sd["RTO In Transit"];
            
            let intransit = sd.NDR + sd["In Transit"] + sd["RTO In Transit"] + 
                             sd.Damaged + sd.Lost + sd["Out for Delivery"];
//            console.log(key); 
            // Update spans with calculated values
            $('#courier-status-table').append(`
                <tr>
                    <td>${key}</td>
                    <td>${total}</td>
                    <td>${(sd["Pickup Pending"] || 0)} ${total > 0 ? `(${((sd["Pickup Pending"] / total) * 100).toFixed(2)}%)` : ''}</td>
                    <td>${intransit} ${total > 0 ? `(${((intransit / total) * 100).toFixed(2)}%)` : ''}</td>
                    <td>${(sd.Delivered || 0)} ${total > 0 ? `(${((sd.Delivered / total) * 100).toFixed(2)}%)` : ''}</td>
                    <td>${rto} ${total > 0 ? `(${((rto / total) * 100).toFixed(2)}%)` : ''}</td>
                </tr>
            `);
        }
    }
    
            // Prepare data points for the column chart
            let dataPoints = [];
            for (let key in data.zonedaat) {
              if (data.zonedaat.hasOwnProperty(key)) {
                dataPoints.push({
                  label: capitalizeFirstLetter(key.replace(/_/g, ' ')),
                  y: data.zonedaat[key]
                });
              }
            }
    
            // Prepare data points for the pie chart
            let pieDataPoints = [];
            for (let key in data.courierdata) {
              if (data.courierdata.hasOwnProperty(key)) {
                let yValue = data.courierdata[key];
                let percentage = (totalShipments !== 0) ? round((yValue / totalShipments) * 100, 2) : 0;
    
                pieDataPoints.push({
                  label: capitalizeFirstLetter(key.replace(/_/g, ' ')),
                  y: yValue,
                  p: percentage
                });
              }
            }
            // console.log('Pie Chart Data Points:', pieDataPoints);
            // Initialize the column chart
            var columnChart = new CanvasJS.Chart("chartContainer", {
              exportEnabled: false,
              animationEnabled: true,
              axisY: {
                titleFontColor: "#4F81BC",
                lineColor: "#4F81BC",
                labelFontColor: "#4F81BC",
                tickColor: "#4F81BC",
                includeZero: true
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
                name: "Shipments",
                showInLegend: false,
                yValueFormatString: "#,##0.# Units",
                dataPoints: dataPoints
              }]
            });
            columnChart.render(); // Render the column chart
    
            // Toggle data series function
            function toggleDataSeries(e) {
              e.dataSeries.visible = (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) ? false : true;
              e.chart.render();
            }
    
            // Initialize the pie chart
            var pieChart = new CanvasJS.Chart("chartContainer2", {
              theme: "light2",
              exportEnabled: false,
              animationEnabled: true,
              data: [{
                type: "pie",
                startAngle: 25,
                indexLabel: "{label} - {y}({p}%)",
                dataPoints: pieDataPoints
              }]
            });
            pieChart.render(); // Render the pie chart
          },
          error: function(xhr, status, error) {
            console.error('Error loading dashboard data:', error);
          }
        });
      }
    
      function round(value, decimals) {
        return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
      }
    
      function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      }
    
      window.addEventListener('load', myFunction);
</script>
<!-- <script>
    $('form select[name="role_id"]').on('change', function() {
        $(this).closest('form').submit();
    });
</script> -->

<!--<div id="loading-indicator" style="">Loading...</div>-->
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
                        <div class="hori-selector" style="margin-left: 20px;    left: 0px;    width: 106.137px;">
                            <div class="left"></div>
                            <div class="right"></div>
                        </div>
                        <li class="active"><a href="javascript:void(0);" data-id="Overview">Overview</a></li>
                        <li><a href="{{route('admin.dashboard.shipment')}}" data-id="channel">Shipment Status</a></li>
                        <li><a href="{{route('admin.dashboard.top')}}" data-id="top10">Top 10</a></li>
                        <!-- <li><a href="javascript:void(0);" data-id="ndr">NDR</a></li> -->
                    </ul>
                </div>
                <div class="card top_report card mt-30 Overview">
                    <!-- <form action="{{ route('admin.dashboard.infilter') }}"  method="post" enctype="multipart/form-data">
                    @csrf
                            <div class="row">
                                <div class="" style="display: initial; margin-left: 29px;">
                                    <select name="role_id" class="form-control" >
                                        <option value="td" @if ($role_id =='td') selected @endif>Till date</option>
                                        <option value="tod" @if ($role_id =='tod') selected @endif>Today</option>
                                        <option value="yes" @if ($role_id =='yes') selected @endif>Yesterday</option>
                                        <option value="lt" @if ($role_id =='lt') selected @endif>Last 3 days</option>
                                        <option value="tw" @if ($role_id =='tw') selected @endif>Last 7 days</option>
                                        <option value="lf" @if ($role_id =='lf') selected @endif>Last 15 days</option>
                                        <option value="lth" @if ($role_id =='lth') selected @endif>Last 30 days</option>
                                    </select>
                                </div>
                            </div>
                    </form> -->
                    <div class="row" style="margin:0">
                        <div class="col-lg-2 col-md-6 col-sm-6" style="padding: 0px">
                            <div class="body2">
                                <div class="card card-raised bg-warning text-white loader-hidden">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white font-size1em">
                                                    <a href="{{ route('admin.order.all', ['exceptnewcancel' => true, 'role_id' => $role_id]) }}" target="_blank">
                                                        <span id="tshipment"></span>
                                                    </a>
                                                </div>
                                                <div class="card-text">Total Shipments</div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-warning">
                                                <i class="fa fa-2x fa-home text-col-yellow"></i>
                                            </div>
                                        </div>
                                        <div class="card-text">
                                            <a>
                                                <div class="d-inline-flex align-items-center">
                                                    <div class="caption fw-500 me-2">
                                                        <span id="percentage">
                                  
                                                        </span>
                                                    </div>
                                                    <div class="caption">% of total orders</div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6 col-sm-6" style="padding: 0px">
                            <div class="body2">
                                <div class="card card-raised bg-success text-white loader-hidden">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white font-size1em">
                                                    <a href="{{ route('admin.order.all', ['delivered' => true, 'role_id' => $role_id]) }}" target="_blank">
                                                        <span id="delshipment"></span> (
                                                        <span id="delshipmentperce">
                                  
                                                        </span>)
                                                    </a>
                                                </div>
                                                <div class="card-text">Delivered Shipments</div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-warning">
                                                <i class="fa fa-2x fa-truck text-col-green"></i>
                                            </div>
                                        </div>
                                        <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <div class="caption fw-500 me-2">
                                                    <span id="percentage2">
                                   
                                                    </span>
                                                </div>
                                                <div class="caption">% of total orders</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6"  style="padding: 0px">
                            <div class="body2">
                                <div class="card card-raised bg-secondary text-white loader-hidden">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white font-size1em">
                                                    <a href="{{ route('admin.order.all', ['intrait' => true, 'role_id' => $role_id]) }}" target="_blank" >
                                                        <span id="trasitshipment"></span> (
                                                        <span id="trasitshipmentperce">
                                  
                                                        </span>)
                                                    </a>
                                                </div>
                                                <div class="card-text">In Transit</div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-warning">
                                                <i class="fa fa-2x fa-road text-muted"></i>
                                            </div>
                                        </div>
                                        <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <div class="caption fw-500 me-2">
                                                    <span id="percentage4">
                                   
                                                    </span>
                                                </div>
                                                <div class="caption">% of total orders</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6"  style="padding: 0px">
                            <div class="body2">
                                <div class="card card-raised bg-danger text-white loader-hidden">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white font-size1em">
                                                    <a href="{{ route('admin.order.all', ['rto' => true, 'role_id' => $role_id]) }}" target="_blank">
                                                        <span id="rto"></span> (
                                                        <span id="rtopercent">
                                  
                                                     </span>)
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
                                                    <span id="percentage3">

                                                   </span>
                                                </div>
                                                <div class="caption">% of total orders</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6"  style="padding: 0px">
                            <div class="body2">
                                <div class="card card-raised bg-info text-white loader-hidden">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white font-size1em">
                                                    <a href="{{ route('admin.order.all', ['delivered' => true, 'role_id' => $role_id]) }}" target="_blank" id="revenue">{{$revenue ?? ' '}}</a>
                                                </div>
                                                <!--<span > </span>-->
                                                <div class="card-text">Total Revenue (₹)</div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-warning"> <i class="fa fa-2x fa-balance-scale text-col-blue"></i></div>
                                        </div>
                                        <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <!-- <i class="material-icons icon-xs">arrow_upward</i> -->
                                                <div class="caption fw-500 me-2">
                                                    <div class="caption fw-500 me-2">
                                                        <span id="avgrevenue"> </span>
                                                    </div>
                                                </div>
                                                <div class="caption"> &nbsp;Avg. Revenue (₹)</div>
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
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row gx-4">
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
                                        <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
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
                                            <th>In Transit</th>
                                            <th>Delivered</th>
                                            <th>RTO</th>
                                        </tr>
                                    </thead>
                                    <tbody id="courier-status-table">

                                        <tr>
                                            <td><span id="tablec_name"></span></td>
                                            <td><span id="tabletotal"></span></td>
                                            <td><span id="tablepending"></span></td>
                                            <td><span id="tableintransit"></span></td>
                                            <td><span id="tabledelivered"></span></td>
                                            <td><span id="tablerto"></span></td>
                                        </tr>
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