@extends('admin.admin_layouts')

@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp
<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="{{ asset('/admin/assets/js/index_amchart.js') }}"></script>

<style>
.canvasjs-chart-credit
{
    display: none !important;
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
h2.main_header{
    font-size: 1.5rem;
    line-height: 2rem;
    font-weight: 600;
    letter-spacing: 0.0125em;
    margin-bottom: 0;
}
.loading-indicator {
    display: inline-block;
    padding: 10px;
    border: 2px solid #ff904f; /* Blue */
    border-radius: 5px;
    color: #474bff;
    animation: pulse 1.5s infinite;
}
.card_header .icon-circle {
    position: absolute;
    border-radius: 50%;
    background: #fff;
    box-shadow: inset 0 0 10px #0002;
}


.card_header {
    position: relative;
    text-align: center;
    line-height: 2.5;
    font-size: 18px;
    font-weight: 400;

}

.body2 .card{
    padding: 10px;
    border-radius: 22px;
    box-shadow: 2px 2px 10px #0002;
}

h2.main_header {
    padding: 0 15px;
}

@keyframes pulse {
    0% {
        opacity: 100;
    }
    50% {
        opacity: -0.1;
    }
    100% {
        opacity: 1;
    }
}

@keyframes spinner-z355kx {
   to {
      transform: rotate(360deg);
   }
}

@media (min-width: 992px){
.col-lg-2 {
    -ms-flex: 0 0 20%;
    flex: 0 0 20%;
    max-width: 20%;
}
}
 </style> 


 <script>
    function myFunction() {
        console.log('starting request');
        document.querySelectorAll('.spinner').forEach(function(spinner) {
            spinner.style.display = 'inline-block'; // Show the spinner
        });
        $.ajax({
            url: "{{ route('admin.dashboard.shipmentload') }}",
            type: 'GET',
            dataType: 'json',
            
            success: function(data) {
                console.log('AJAX request successful:', data); 
                let allorderCount = (Array.isArray(data.orderall)) ? data.orderall.length : 0;
                let totalshipment = data.tshipment || 0;
                let intransit = data.intrsit || 0;
                let delivered = data.delivredorder || 0;
                let rto= data.rtoorder || 0;
                let todayship = data.todayshipment || 0;
                let todaypending =data.todaypickpending || 0;
                let todayofd = data.todayood || 0;
                let todaydel =data.todaydeliverd || 0;
                let todayr =data.todayrev || 0;
                let manifest =data.todaybefore12pickpending || 0;
                let mode = data.modedata || 0;
                 $('#orderall').text(allorderCount);
                 $('#tshipment').text(totalshipment);
                 $('#intrsit').text(intransit);
                 $('#delivredorder').text(delivered);
                 $('#rtoorder').text(rto);
                 $('#todayshipment').text(todayship);
                 $('#todaypickpending').text(todaypending);
                 $('#todayood').text(todayofd);
                 $('#todaydeliverd').text(todaydel);
                 $('#todayrev').text(todayr);
                 $('#todaybefore12pickpending').text(manifest);
                 $('#modedata').text(mode);

                 $('#courier-status-table').empty();
                 $.each(mode, function(skey, sd) {
                    let total = sd['Pre-Paid'] + sd['C.O.D'];
                    let ppdPercentage = total !== 0 ? Math.round((sd['Pre-Paid'] / total) * 100 * 10) / 10 : 0;
                    let codPercentage = total !== 0 ? Math.round((sd['C.O.D'] / total) * 100 * 10) / 10 : 0;

                    // Build the row HTML string
                    let newRow = `
                        <tr>
                            <td>${skey}</td>
                            <td>${total}</td>
                            <td>${sd['Pre-Paid']} (${ppdPercentage}%)</td>
                            <td>${sd['C.O.D']} (${codPercentage}%)</td>
                        </tr>
                    `;

                    // Append the row to the table body
                    $('#courier-status-table').append(newRow);
                    
                 });

                 am5.ready(function() {

                    // Create root element
                    // https://www.amcharts.com/docs/v5/getting-started/#Root_element
                    var root = am5.Root.new("chartContainer");


                    // Set themes
                    // https://www.amcharts.com/docs/v5/concepts/themes/
                    root.setThemes([
                    am5themes_Animated.new(root)
                    ]);


                    // Create chart
                    // https://www.amcharts.com/docs/v5/charts/xy-chart/
                    var chart = root.container.children.push(am5xy.XYChart.new(root, {
                    
                        layout: root.verticalLayout
                    }));

                    
                    // Subchild label
                    chart.children.unshift(am5.Label.new(root, {
                        text: "CourierWise",
                        fontSize: 12,
                        fontWeight: "400",
                        textAlign: "center",
                        x: am5.percent(50),
                        centerX: am5.percent(50),
                        paddingTop: 5, // Adjust padding for spacing
                        paddingBottom: 10
                    }));
                    chart.children.unshift(am5.Label.new(root, {
                        text: "C.O.D Vs Pre-paid",
                        fontSize: 25,
                        fontWeight: "500",
                        textAlign: "center",
                        x: am5.percent(50),
                        centerX: am5.percent(50),
                        paddingTop: 0,
                        paddingBottom: 0
                    }));

                    chart.set("colors", am5.ColorSet.new(root, {
                    colors: [
                        am5.color(0x73556E),
                        am5.color(0x9FA1A6),
                        am5.color(0xF2AA6B),
                        am5.color(0xF28F6B),
                        am5.color(0xA95A52),
                        am5.color(0xE35B5D),
                        am5.color(0xFFA446)
                    ]
                    }));

                    // Add legend
                    // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
                    var legend = chart.children.push(am5.Legend.new(root, {
                    centerX: am5.p50,
                    x: am5.p50
                    }))

                    

                    var data = Object.keys(mode).flatMap(function(key) {
                        if ((mode[key]['Pre-Paid'] + mode[key]['C.O.D']) !== 0) {
                            return [
                                { year: key, income: mode[key]['Pre-Paid'], expenses: mode[key]['C.O.D'] }
                            ];
                        }
                        return [];
                    });
                    // Create axes
                    // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
                    var yRenderer = am5xy.AxisRendererY.new(root, {
                    cellStartLocation: 0.1,
                    cellEndLocation: 0.9,
                    minorGridEnabled: true
                    });

                    yRenderer.grid.template.set("location", 1);

                    var yAxis = chart.yAxes.push(
                    am5xy.CategoryAxis.new(root, {
                        categoryField: "year",
                        renderer: yRenderer,
                        tooltip: am5.Tooltip.new(root, {})
                    })
                    );

                    yAxis.data.setAll(data);

                    var xAxis = chart.xAxes.push(
                    am5xy.ValueAxis.new(root, {
                        min: 0,
                        renderer: am5xy.AxisRendererX.new(root, {
                        strokeOpacity: 0.1,
                        minGridDistance:70
                        })
                    })
                    );


                    // Add series
                    // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
                    var series1 = chart.series.push(am5xy.ColumnSeries.new(root, {
                    name: "Pre-paid",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueXField: "income",
                    categoryYField: "year",
                    sequencedInterpolation: true,
                    tooltip: am5.Tooltip.new(root, {
                        pointerOrientation: "horizontal",
                        labelText: "[bold]{categoryY}[/]\n{name}: {valueX}"
                    })
                    }));

                    
                    

                    series1.columns.template.setAll({
                        height: am5.percent(70),
                        shadowOpacity: .5,
                        shadowOffsetX: 2,
                        shadowOffsetY: 2,
                        shadowBlur: 20,
                        strokeWidth: 2,
                        stroke: am5.color(0xffffff),
                        shadowColor: am5.color(0x000000),
                        cornerRadiusTR: 50,
                        cornerRadiusBR: 50,
                    });
                    series1.columns.template.adapters.add("fill", function (fill, target) {
                        return chart.get("colors").getIndex(series1.columns.indexOf(target));
                    });

                    var series2 = chart.series.push(am5xy.LineSeries.new(root, {
                    name: "C.O.D",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueXField: "expenses",
                    categoryYField: "year",
                    sequencedInterpolation: true,
                    tooltip: am5.Tooltip.new(root, {
                        pointerOrientation: "horizontal",
                        labelText: "[bold]{categoryY}[/]\n{name}: {valueX}"
                    })
                    }));

                    series2.strokes.template.setAll({
                    strokeWidth: 2,
                    });

                    series2.bullets.push(function () {
                    return am5.Bullet.new(root, {
                        locationY: 0.5,
                        sprite: am5.Circle.new(root, {
                        radius: 5,
                        stroke: series2.get("stroke"),
                        strokeWidth: 2,
                        fill: root.interfaceColors.get("background")
                        })
                    });
                    });


                    // Add legend
                    // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
                    var legend = chart.children.push(am5.Legend.new(root, {
                    centerX: am5.p50,
                    x: am5.p50
                    }));

                    legend.data.setAll(chart.series.values);

                    // Add cursor
                    // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
                    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
                    behavior: "zoomY"
                    }));
                    cursor.lineX.set("visible", false);

                    series1.data.setAll(data);
                    series2.data.setAll(data);

                    // Make stuff animate on load
                    // https://www.amcharts.com/docs/v5/concepts/animations/
                    series1.appear();
                    series2.appear();
                    chart.appear(1000, 100);

                    }); // end am5.ready()
                 
                $('.loading-indicator').hide();    
                document.querySelectorAll('.spinner').forEach(function(spinner) {
                    spinner.style.display = 'none'; // Hide the spinner
                });

            },
            error: function(xhr, status, error) {
                console.error('Error loading shipment data:', error);
            }
        });
    }

    window.addEventListener('load', myFunction);
</script>

<!-- Main body part  -->
<div class="container-fluid">
    <!-- Page header section  -->
    

    
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-12">
            
            <div id="navbar-animmenu">
                    <ul class="show-dropdown main-navbar">
                        <div class="hori-selector" style="margin-left: 20px;left: 126.138px;width: 154.875px;"><div class="left"></div><div class="right"></div></div>
                        <li><a href="{{ route('admin.dashboard') }}" data-id="Overview">Overview</a></li>
                        <li  class="active"><a href="{{route('admin.dashboard.shipment')}}" data-id="channel">Shipment Status</a></li>
                        <!-- <li><a href="{{route('admin.dashboard.top')}}" data-id="top10">Top 10</a></li> -->
                        
                    </ul>
                </div>
                <div class="card top_report card mt-30 Overview hide">
                Please wait.. Data is loading
                </div>

                <div class="card mt-30 channel ">
                    
                    <div class="row" style="margin:0">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="row"><h2 class="main_header"> Overall Activity </h2></div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="body2">
                                        <div class="card card-raised border-start border-warning ">
                                            <div class="card_header">
                                                <div class="icon-circle bg-white-50 text-warning">
                                                    <i class="fa fa-x fa-home text-col-yellow"></i>
                                                </div>
                                                <span>Total Orders</span>
                                            </div>        
                                            <div style="line-height: 2rem;padding: 0 10px;font-size: 1.5rem; text-align: center;">
                                            <a href="{{ route('admin.order.all') }}" target="_blank">
                                                <div class="spinner" style="display: none;"><img src="https://hyloship.com/public/loader.gif" style="width: 30px;"></div>
                                            <span id="orderall"></span>
                                            </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="body2">
                                        <div class="card card-raised border-start border-success ">
                                            <div class="card_header">
                                                <div class="icon-circle bg-white-50 text-warning">
                                                    <i class="fa fa-x fa-truck text-col-green"></i>
                                                </div>
                                                <span>Booked</span>

                                            </div>        
                                            <div style="line-height: 2rem;padding: 0 10px;font-size: 1.5rem; text-align: center;">
                                            <a href="{{ route('admin.order.all', ['exceptnewcancel' => true]) }}" target="_blank">
                                            <div class="spinner" style="display: none;"><img src="https://hyloship.com/public/loader.gif" style="width: 30px;"></div>
                                            <span id="tshipment"></span>
                                            </a>
                                        </div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="body2">
                                        <div class="card card-raised border-start border-danger ">
                                            <div class="card_header">
                                                <div class="icon-circle bg-white-50 text-warning">
                                                    <i class="fa fa-x fa-road text-danger"></i>
                                                </div>
                                                <span>In Transit</span>

                                            </div>        
                                            <div style="line-height: 2rem;padding: 0 10px;font-size: 1.5rem; text-align: center;">
                                                <a href="{{ route('admin.order.all', ['intransit' => true]) }}" target="_blank">
                                                <div class="spinner" style="display: none;"><img src="https://hyloship.com/public/loader.gif" style="width: 30px;"></div>
                                                <span id="intrsit"></span>
                                                </a>
                                        </div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="body2">
                                        <div class="card card-raised border-start border-info ">
                                            <div class="card_header">
                                                <div class="icon-circle bg-white-50 text-warning">
                                                    <i class="fa fa-x fa-money text-col-blue"></i>
                                                </div>
                                                <span>Delivered</span>
                                            </div>        
                                            <div style="line-height: 2rem;padding: 0 10px;font-size: 1.5rem; text-align: center;">
                                                <a href="{{ route('admin.order.all', ['delivered' => true]) }}" target="_blank">
                                                <div class="spinner" style="display: none;"><img src="https://hyloship.com/public/loader.gif" style="width: 30px;"></div>    
                                                <span id="delivredorder"></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="body2">
                                        <div class="card card-raised border-start border-secondary ">
                                            <div class="card_header">
                                                <div class="icon-circle bg-white-50 text-warning">
                                                    <i class="fa fa-x fa-bookmark text-muted"></i>
                                                </div>
                                                <span>RTO</span>
                                            </div>        
                                            <div style="line-height: 2rem;padding: 0 10px;font-size: 1.5rem; text-align: center;">
                                                <a href="{{ route('admin.order.all', ['rto' => true]) }}" target="_blank">
                                                <div class="spinner" style="display: none;"><img src="https://hyloship.com/public/loader.gif" style="width: 30px;"></div>    
                                                <span id="rtoorder"></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>     
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="row"><h2 class="main_header"> Todays' Activity </h2></div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="body2">
                                        <div class="card card-raised border-top border-warning ">
                                            <div class="" style="line-height: 1.8rem;font-size: 1.2rem;height: 70px;">
                                                <span style="padding: 10px;position: absolute;padding-top: 23px;text-align: center;">Shipments</span>
                                                <span class="bg-white" style="float: right;margin: 0;height: 68px;width: 34%;text-align: center;color: black;padding-top: 23px;margin: 1px;font-size:2rem;">
                                                    <a href="{{ route('admin.order.all', ['shipped_today' => true]) }}" target="_blank">
                                                    <div class="spinner" style="display: none;"><img src="https://hyloship.com/public/loader.gif" style="width: 30px;"></div>    
                                                    <span id="todayshipment"></span></a>
                                                </span>
                                            </div>        
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="body2">
                                        <div class="card card-raised border-top border-success ">
                                            <div class="" style="line-height: 1.8rem;font-size: 1.2rem;height: 70px;">
                                                <span style="padding: 10px;position: absolute;padding-top: 6px;text-align: center;padding-left: 20px;">Pickup<br>Pending</span>
                                                <span class="bg-white" style="float: right;margin: 0;height: 68px;width: 34%;text-align: center;color: black;padding-top: 23px;margin: 1px;font-size:2rem;">
                                                    <a href="{{ route('admin.order.all', ['manifest_today' => true]) }}" target="_blank"> 
                                                    <div class="spinner" style="display: none;"><img src="https://hyloship.com/public/loader.gif" style="width: 30px;"></div>    
                                                    <span id="todaypickpending"></span></a>
                                                </span>
                                            </div>        
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="body2">
                                        <div class="card card-raised border-top border-secondary ">
                                            <div class="" style="line-height: 1.8rem;font-size: 1.2rem;height: 70px;">
                                                <span style="padding: 10px;position: absolute;padding-top: 6px;text-align: center;padding-left: 20px;">Manifested<br>before 12</span>
                                                <span class="bg-white" style="float: right;margin: 0;height: 68px;width: 34%;text-align: center;color: black;padding-top: 23px;margin: 1px;font-size:2rem;">
                                                    <a href="{{ route('admin.order.all', ['manifested_before12' => true]) }}" target="_blank"> 
                                                    <div class="spinner" style="display: none;"><img src="https://hyloship.com/public/loader.gif" style="width: 30px;"></div>    
                                                    <span id="todaybefore12pickpending"></span></a>
                                                </span>
                                            </div>        
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="body2">
                                        <div class="card card-raised border-top border-danger ">
                                            <div class="" style="line-height: 1.8rem;font-size: 1.2rem;height: 70px;">
                                                <span style="padding: 10px;position: absolute;padding-top: 6px;text-align: center;padding-left: 20px;">Out for<br>Delivery</span>
                                                <span class="bg-white" style="float: right;margin: 0;height: 68px;width: 34%;text-align: center;color: black;padding-top: 23px;margin: 1px;font-size:2rem;">
                                                    <a href="{{ route('admin.order.all', ['ood' => true]) }}" target="_blank">
                                                    <div class="spinner" style="display: none;"><img src="https://hyloship.com/public/loader.gif" style="width: 30px;"></div>      
                                                    <span id="todayood"></span></a>
                                                </span>
                                            </div>        
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="body2">
                                        <div class="card card-raised border-top border-info ">
                                            <div class="" style="line-height: 1.8rem;font-size: 1.2rem;height: 70px;">
                                                <span style="padding: 10px;position: absolute;padding-top: 23px;text-align: center;">Delivered</span>
                                                <span class="bg-white" style="float: right;margin: 0;height: 68px;width: 34%;text-align: center;color: black;padding-top: 23px;margin: 1px;font-size:2rem;"> <a href="{{ route('admin.order.all', ['delivered_today' => true]) }}" target="_blank">
                                                <div class="spinner" style="display: none;"><img src="https://hyloship.com/public/loader.gif" style="width: 30px;"></div>      
                                                <span id="todaydeliverd"></span></a></span>
                                            </div>        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" style="margin:0">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div id="chartContainer" style="height: 470px; width: 100%;">
                            <div class="loading-indicator" style=" 
                                     display: flex; 
                                     justify-content: center; 
                                     align-items: center; 
                                     height: 100%; 
                                     font-size: 20px; 
                                     color: #333; 
                                     position: absolute; 
                                     top: 0; 
                                     left: 0; 
                                     right: 0; 
                                     bottom: 0; 
                                     z-index: 10; ">Loading...</div>
                        </div>
                           
                        </div>
                        
                        <div class="col-lg-6 col-md-6 col-sm-12">
                       
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
                                    <tbody id="courier-status-table">
                                    <tr>
                                         <td colspan="4" style="text-align: center;">
                                         <div class="loading-indicator">Loading...</div>
                                         </td>
                                    </tr>
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


 