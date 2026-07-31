@extends('admin.admin_layouts') @section('admin_content')
@php
    $session = Auth::guard('admin')->user();
    // Safe defaults — populated by DashboardController@dashboard
    $tshipment        = $tshipment        ?? 0;
    $delivredorder    = $delivredorder    ?? 0;
    $rtoorder         = $rtoorder         ?? 0;
    $revenue          = $revenue          ?? 0;
    $intransit        = $intransit        ?? 0;
    $deliveredPercentage = $deliveredPercentage ?? 0;
    $transitPercentage   = $transitPercentage   ?? 0;
    $rtopercentage       = $rtopercentage       ?? 0;
    $percentage       = $percentage       ?? 0;
    $percentage2      = $percentage2      ?? 0;
    $percentage3      = $percentage3      ?? 0;
    $percentage4      = $percentage4      ?? 0;
    $avgrevenue       = $avgrevenue       ?? 0;
    $pieDataPoints    = $pieDataPoints    ?? [];
    $barDataPoints    = $barDataPoints    ?? [];
    $statusdata       = $statusdata       ?? [];
    $courierdata      = $courierdata      ?? [];
@endphp

<style>
    .bg-white-50 {
        background-color: rgba(255, 255, 255, 0.5);
    }

    .text-warning {
        --bs-text-opacity: 1;
        color: rgba(var(--bs-warning-rgb), var(--bs-text-opacity)) !important;
    }

    .top_report .body2 {
        padding: 10px;
    }

    .card {
        margin-bottom: 0px;
    }

    .col-lg-3.col-md-6.col-sm-6,
    .col-lg-6.col-md-6.col-sm-6 {
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
        background: transparent !important;
        float: left;
        overflow: hidden;
        position: relative;
        padding: 5px 0px;
        width: 100%;
        border-radius: 10px;
    }

    #navbar-animmenus ul li a {
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        line-height: 30px;
        display: block;
        padding: 0px 10px;
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
        margin-top: 13px;
    }

    .hori-selector .right,
    .hori-selector .left {
        position: absolute;
        width: 25px;
        height: 25px;
        background-color: #fff;
        bottom: 10px;
    }

    #navbar-animmenus>ul>li.active>a {
        color: #fff;
        background-color: transparent;
        transition: all 0.7s;
        border-bottom: 3px solid #fff !important;
    }

    .display-5.text-white {
        line-height: 2rem;
        font-size: 1.5rem;
    }

    .text-white a {
        color: #fff !important;
    }

    .canvasjs-chart-credit {
        display: none !important;
    }

    .font-size1em {
        font-size: 1.19em !important;
    }

    .me-2 .card-text {
        font-size: 13px;
    }

    .d-flex {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        padding: 20px;
    }

    @media (min-width: 992px) {
        .col-lg-2 {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 19.666667%;
            max-width: 20.666667%;
        }
    }
</style>

<!-- amCharts 5 core libraries (must be before the chart init script) -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<script>
    window.addEventListener('load', function() {
        var dataPoints   = @json($pieDataPoints);   {{-- Zone wise pie chart --}}
        var pieDataPoints = @json($barDataPoints);  {{-- Courier wise bar chart --}}

        am5.ready(function() {
            // ─── Zone wise Pie Chart (chartdiv1) ───────────────────────────
            var root = am5.Root.new("chartdiv1");
            root.setThemes([am5themes_Animated.new(root)]);

            var chart = root.container.children.push(am5percent.PieChart.new(root, {
                startAngle: 0,
                endAngle: 360,
                layout: root.verticalLayout,
                innerRadius: am5.percent(50),
                radius: am5.percent(66)
            }));

            var series = chart.series.push(am5percent.PieSeries.new(root, {
                startAngle: 0,
                endAngle: 360,
                valueField: "value",
                categoryField: "category",
                alignLabels: false
            }));

            series.slices.template.setAll({
                strokeWidth: 2,
                stroke: am5.color(0xffffff),
                shadowOpacity: 0.1,
                shadowOffsetX: 2,
                shadowOffsetY: 2,
                shadowColor: am5.color(0x000000),
                cornerRadius: 10
            });
            series.slices.template.set("fillGradient", am5.RadialGradient.new(root, {
                stops: [
                    { brighten: -0.8 },
                    { brighten: -0.8 },
                    { brighten: -0.5 },
                    { brighten: 0 },
                    { brighten: -0.5 }
                ]
            }));
            series.labels.template.set('text', '{category}\n {value}');
            series.ticks.template.setAll({ forceHidden: false });
            series.set("colors", am5.ColorSet.new(root, {
                colors: [
                    am5.color(0x73556E), am5.color(0x9FA1A6), am5.color(0xF2AA6B),
                    am5.color(0xF28F6B), am5.color(0xA95A52), am5.color(0xE35B5D),
                    am5.color(0xFFA446)
                ]
            }));

            series.data.setAll(dataPoints);

            var legend = chart.children.push(am5.Legend.new(root, {
                centerX: am5.percent(50),
                x: am5.percent(50),
                marginTop: 15,
                marginBottom: 15,
            }));
            legend.markerRectangles.template.adapters.add("fillGradient", function() {
                return undefined;
            });
            legend.data.setAll(series.dataItems);
            series.appear(1000, 100);

            // ─── Courier wise Bar Chart (chartdiv2) ────────────────────────
            var root2 = am5.Root.new("chartdiv2");
            root2.setThemes([am5themes_Animated.new(root2)]);

            var barChart = root2.container.children.push(am5xy.XYChart.new(root2, {
                paddingLeft: 0,
                paddingRight: 1
            }));

            barChart.set("colors", am5.ColorSet.new(root2, {
                colors: [
                    am5.color(0x73556E), am5.color(0x9FA1A6), am5.color(0xF2AA6B),
                    am5.color(0xF28F6B), am5.color(0xA95A52), am5.color(0xE35B5D),
                    am5.color(0xFFA446)
                ]
            }));

            var cursor = barChart.set("cursor", am5xy.XYCursor.new(root2, {}));
            cursor.lineY.set("visible", false);
            cursor.lineX.set("visible", false);

            var xRenderer = am5xy.AxisRendererX.new(root2, {
                minGridDistance: 30,
                minorGridEnabled: true
            });
            xRenderer.labels.template.setAll({
                rotation: -90,
                centerY: am5.p50,
                centerX: am5.p100,
                paddingRight: 15
            });
            xRenderer.grid.template.setAll({ location: 1 });

            var xAxis = barChart.xAxes.push(am5xy.CategoryAxis.new(root2, {
                categoryField: "category",
                renderer: xRenderer
            }));

            var yRenderer = am5xy.AxisRendererY.new(root2, { strokeOpacity: 0.1 });
            var yAxis = barChart.yAxes.push(am5xy.ValueAxis.new(root2, {
                renderer: yRenderer
            }));

            var barSeries = barChart.series.push(am5xy.ColumnSeries.new(root2, {
                name: "Series 1",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                categoryXField: "category",
                tooltip: am5.Tooltip.new(root2, { labelText: "{categoryX}: {valueY}" })
            }));

            barSeries.columns.template.setAll({
                tooltipY: 0,
                tooltipText: "{categoryX}: {valueY}",
                shadowOpacity: .5,
                shadowOffsetX: 2,
                shadowOffsetY: 2,
                shadowBlur: 20,
                strokeWidth: 2,
                stroke: am5.color(0xffffff),
                shadowColor: am5.color(0x000000),
                cornerRadiusTL: 50,
                cornerRadiusTR: 50,
            });
            barSeries.columns.template.adapters.add("fill", function(fill, target) {
                return barChart.get("colors").getIndex(barSeries.columns.indexOf(target));
            });
            barSeries.columns.template.states.create("hover", {
                shadowOpacity: 1,
                shadowBlur: 10,
                cornerRadiusTL: 10,
                cornerRadiusTR: 10
            });

            xAxis.data.setAll(pieDataPoints);
            barSeries.data.setAll(pieDataPoints);

            barSeries.appear(1000);
            barChart.appear(1000, 100);
        });
    });
</script>

<!-- Main body part -->
<div class="container-fluid">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-12">
                <div class="card top_report card mt-30 Overview">
                    <div class="row" style="margin:0">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                {{-- Card 1: Total Shipments --}}
                                <div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0px">
                                    <div class="body2">
                                        <div class="card card-raised">
                                            <div class="card-body px-4">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="me-2">
                                                        <div class="display-5 font-size1em">
                                                            <a href="{{ route('admin.order.all', ['exceptnewcancel' => true, 'role_id' => $role_id]) }}" target="_blank">
                                                                <span id="tshipment">{{ $tshipment }}</span>
                                                            </a>
                                                        </div>
                                                        <div class="card-text">Total Shipments</div>
                                                    </div>
                                                    <div class="icon-circle bg-white-50 text-warning">
                                                        <i class="fa fa-2x fa-briefcase text-col-yellow"></i>
                                                    </div>
                                                </div>
                                                <div class="card-text">
                                                    <a>
                                                        <div class="d-inline-flex align-items-center">
                                                            <div class="caption fw-500 me-2">
                                                                <span id="percentage">{{ $percentage }}</span>
                                                            </div>
                                                            <div class="caption">% of total orders</div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card 2: Delivered Shipments --}}
                                <div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0px">
                                    <div class="body2">
                                        <div class="card card-raised">
                                            <div class="card-body px-4">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="me-2">
                                                        <div class="display-5 font-size1em">
                                                            <a href="{{ route('admin.order.all', ['delivered' => true, 'role_id' => $role_id]) }}" target="_blank">
                                                                <span id="delshipment">{{ $delivredorder }}</span> (
                                                                <span id="delshipmentperce">{{ $deliveredPercentage }}%</span>)
                                                            </a>
                                                        </div>
                                                        <div class="card-text">Delivered Shipments</div>
                                                    </div>
                                                    <div class="icon-circle bg-white-50 text-warning">
                                                        <i class="fa fa-2x fa-plane text-col-green"></i>
                                                    </div>
                                                </div>
                                                <div class="card-text">
                                                    <div class="d-inline-flex align-items-center">
                                                        <div class="caption fw-500 me-2">
                                                            <span id="percentage2">{{ $percentage2 }}</span>
                                                        </div>
                                                        <div class="caption">% of total orders</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card 3: In Transit --}}
                                <div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0px">
                                    <div class="body2">
                                        <div class="card card-raised">
                                            <div class="card-body px-4">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="me-2">
                                                        <div class="display-5 font-size1em">
                                                            <a href="{{ route('admin.order.all', ['intrait' => true, 'role_id' => $role_id]) }}" target="_blank">
                                                                <span id="trasitshipment">{{ $intransit }}</span> (
                                                                <span id="trasitshipmentperce">{{ $transitPercentage }}%</span>)
                                                            </a>
                                                        </div>
                                                        <div class="card-text">In Transit</div>
                                                    </div>
                                                    <div class="icon-circle bg-white-50 text-warning">
                                                        <i class="fa fa-2x fa-car text-muted"></i>
                                                    </div>
                                                </div>
                                                <div class="card-text">
                                                    <div class="d-inline-flex align-items-center">
                                                        <div class="caption fw-500 me-2">
                                                            <span id="percentage4">{{ $percentage4 }}</span>
                                                        </div>
                                                        <div class="caption">% of total orders</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card 4: Total RTO --}}
                                <div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0px">
                                    <div class="body2">
                                        <div class="card card-raised">
                                            <div class="card-body px-4">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="me-2">
                                                        <div class="display-5 font-size1em">
                                                            <a href="{{ route('admin.order.all', ['rto' => true, 'role_id' => $role_id]) }}" target="_blank">
                                                                <span id="rto">{{ $rtoorder }}</span> (
                                                                <span id="rtopercent">{{ $rtopercentage }}%</span>)
                                                            </a>
                                                        </div>
                                                        <div class="card-text">Total RTO</div>
                                                    </div>
                                                    <div class="icon-circle bg-white-50 text-warning">
                                                        <i class="fa fa-2x fa-reply text-col-red"></i>
                                                    </div>
                                                </div>
                                                <div class="card-text">
                                                    <div class="d-inline-flex align-items-center">
                                                        <div class="caption fw-500 me-2">
                                                            <span id="percentage3">{{ $percentage3 }}</span>
                                                        </div>
                                                        <div class="caption">% of total orders</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin:0;">
                        {{-- Courier wise Bar Chart --}}
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="col-12" style="margin-top:10px">
                                <div class="card card-raised h-100" style="border:1px solid">
                                    <div class="card-header bg-primary text-white px-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-4">
                                                <h2 class="card-title text-white mb-0">Courier wise shipment</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="d-flex h-100 w-100 align-items-center justify-content-center">
                                            <div id="chartdiv2" style="height: 370px; width: 100%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Zone wise Pie Chart --}}
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="col-12" style="margin-top:10px">
                                <div class="card card-raised h-100" style="border:1px solid">
                                    <div class="card-header bg-primary text-white px-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-4">
                                                <h2 class="card-title text-white mb-0">Zone wise shipment</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row gx-4">
                                            <div id="chartdiv1" style="height: 370px; width: 100%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Courier Status Table --}}
                        <div class="col-lg-12 col-md-6 col-sm-12">
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
                                        @foreach($statusdata as $courierName => $sd)
                                            @php
                                                $total = ($sd['New'] ?? 0) + ($sd['Shipped'] ?? 0) + ($sd['Delivered'] ?? 0) + ($sd['RTO'] ?? 0) +
                                                        ($sd['RTO Delivered'] ?? 0) + ($sd['NDR'] ?? 0) + ($sd['Pickup Pending'] ?? 0) +
                                                        ($sd['RTO In Transit'] ?? 0) + ($sd['In Transit'] ?? 0) +
                                                        ($sd['Out for Delivery'] ?? 0) + ($sd['Lost'] ?? 0) + ($sd['Damaged'] ?? 0);
                                                $rtoT = ($sd['RTO'] ?? 0) + ($sd['RTO Delivered'] ?? 0) + ($sd['RTO In Transit'] ?? 0);
                                                $inT  = ($sd['NDR'] ?? 0) + ($sd['In Transit'] ?? 0) + ($sd['RTO In Transit'] ?? 0) +
                                                        ($sd['Damaged'] ?? 0) + ($sd['Lost'] ?? 0) + ($sd['Out for Delivery'] ?? 0);
                                            @endphp
                                            @if($total > 0 || ($courierdata[$courierName] ?? 0) > 0)
                                            <tr>
                                                <td>{{ $courierName }}</td>
                                                <td>{{ $total }}</td>
                                                <td>
                                                    {{ $sd['Pickup Pending'] ?? 0 }}
                                                    @if($total > 0)({{ number_format((($sd['Pickup Pending'] ?? 0) / $total) * 100, 2) }}%)@endif
                                                </td>
                                                <td>
                                                    {{ $inT }}
                                                    @if($total > 0)({{ number_format(($inT / $total) * 100, 2) }}%)@endif
                                                </td>
                                                <td>
                                                    {{ $sd['Delivered'] ?? 0 }}
                                                    @if($total > 0)({{ number_format((($sd['Delivered'] ?? 0) / $total) * 100, 2) }}%)@endif
                                                </td>
                                                <td>
                                                    {{ $rtoT }}
                                                    @if($total > 0)({{ number_format(($rtoT / $total) * 100, 2) }}%)@endif
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection