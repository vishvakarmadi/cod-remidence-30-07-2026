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
            padding: 0px 15px;
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

        .hori-selector .right,
        .hori-selector .left {
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
    </style>
    <div class="card shadow mb-4">
        <!-- @if ($session->id == '65' || $session->id == '69' || $session->id == '1')
    <div class="container-fluid">
                            <div class="row clearfix">
                                <div class="col-12">
                                    <div id="navbar-animmenu">
                                            <ul class="show-dropdown main-navbar">
                                                <div class="hori-selector" style="margin-left: 20px;    left: 0px;    width: 106.137px;"><div class="left"></div><div class="right"></div></div>
                                                <li class="active"><a href="javascript:void(0);" data-id="Overview">Courier wise</a></li>
                                                <li><a href="{{ route('admin.order.manifestpview') }}" data-id="channel">Product wise</a></li>
                                                
                                            </ul>
                                        </div>
                                </div>
                            </div>
                        </div>
    @endif -->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Manifest</h5>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered sorttable table-striped table-hover" id="sorttable" width="100%"
                    cellspacing="0">
                    <thead>
                        <tr>
                            <th data-field="hideexport">SL</th>
                            <th>Manifest Id</th>
                            <th>Created</th>
                            <th>Created By</th>
                            <th>Courier</th>
                            <th>Total Orders</th>
                            <th>AWB</th>
                            <th data-field="hideexport">Action</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php $counts = 1; ?>
                        @foreach ($manifest as $row)
                            @if (isset($row->manifest_order) && count($row->manifest_order) > 0)
                                <tr>
                                    <td>{{ $counts }}</td>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->created_at }}</td>
                                    <td>{{ $row->created_by }}</td>
                                    <td>{{ @$couriers[$row->courier_id]['name'] }}</td>
                                    <td>
                                        @if (isset($row->manifest_order))
                                            {{ count($row->manifest_order) }}
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($row->manifest_order))
                                            @foreach ($row->manifest_order as $ord)
                                                <a
                                                    href="{{ route('admin.order.shippingprintparticular', [$ord->id, $ord->ship_courier_id]) }}">
                                                    {{ $ord->tracking_info }} - {{ $ord->vendor_order_id ?: $ord->id }}
                                                    @if ($ord->reverse_order == '1')
                                                        <span style="color:red">
                                                            (Reverse)
                                                        </span>
                                                    @endif
                                                </a>
                                                <br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <?php $file = 'shipping_' . $row->id . '.pdf'; ?>
                                        <a href="{{ route('admin.order.shippingprint', [$row->id, $row->courier_id]) }}" class="btn btn-secondary @if (!file_exists($file)) clickme_{{ $row->id }} @endif" title="Shipping label"
                                            style="width:36px;" data-manifest-id="{{ $row->id }}" target="_blank"><span
                                                class="sr-only">View</span> <i class="fa fa-book"
                                                style="margin: -5px;"></i></a>
                                        <a href="{{ route('admin.order.manifestprint', $row->id) }}"
                                            class="btn btn-primary" title="Print Manifest" style="width:36px;"
                                            target="_blank"><span class="sr-only">Print</span> <i class="fa fa-print"
                                                style="margin: -5px;"></i></a>
                                    </td>
                                    <!-- <td>
                                            <a href="{{ URL::to('admin/order/shippingprint/' . $row->id . '/' . $row->courier_id) }}" class="btn btn-secondary  
                        @if (!file_exists($file)) clickme_{{ $row->id }} @endif
                        " title="Shipping label"
                                                style="width:36px;" data-manifest-id="{{ $row->id }}"><span
                                                    class="sr-only">View</span> <i class="fa fa-book" style="margin: -5px;"></i></a>
                                            </td> -->
                                    <?php $counts++; ?>
                                </tr>
                                <script>
                                    $(function() {
                                        $('.clickme_{{ $row->id }}').one('click', function() {
                                            // e.preventDefault();
                                            $('div').toggleClass('hidden');
                                            // $(this).hide().delay(10000).show("slow");
                                            setTimeout(function() {
                                                // toggle another class
                                                $('#loader').toggleClass('hidden');
                                            }, 10000)
                                            $(this).prop('disabled', true);
                                        });
                                    });
                                    $(document).ready(function() {
                                        $("#myInput").on("keyup", function() {
                                            var value = $(this).val().toLowerCase();
                                            $("#myTable tr").filter(function() {
                                                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                            });
                                        });
                                    });
                                </script>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>



        </div>
    </div>
@endsection
