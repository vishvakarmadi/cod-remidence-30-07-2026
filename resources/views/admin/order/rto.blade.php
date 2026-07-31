@extends('admin.admin_layouts')
@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/public/admin/semantic.min.js"></script>
<link rel="stylesheet" href="/public/admin/semantic.min.css">
<style>
label {
    display: inline-block;
    margin-bottom: 0px;
}
</style>
<!-- Main body part  -->
<div class="container-fluid">
    <!-- Page header section  -->
    
    <div class="row clearfix">
        <div class="col-12">


            <div id="navbar-animmenu">
                <ul class="show-dropdown main-navbar">
                    <div class="hori-selector" style="margin-left: 20px;">
                        <div class="left"></div>
                        <div class="right"></div>
                    </div>
                    <li class="active"><a href="javascript:void(0);" data-id="rto_order">RTO Order</a></li>
                    <li><a href="javascript:void(0);" data-id="rto_receive">RTO Received</a></li>
                </ul>
            </div>



            <div class="card mt-30 rto_order">
                <div class="card-header">
                    <h5 class="card-title mb-0">RTO Order</h5>

                </div>


                <div class="card-body">
                    <form id="myForm" action="" method="POST">
                        @csrf
                        <div class="body row">
                        @if(isset($order))
                            <div class="col-md-12">

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    <label class="fancy-checkbox1">
                                                        <input class="select-all" type="checkbox" name="checkbox">
                                                        &nbsp;<label></label>
                                                    </label>
                                                </th>
                                                <th>Order ID</th>
                                                <th>Seller</th>
                                                <th>Order Status</th>
                                                <th>Customer Info</th>
                                                <th>Value</th>
                                                <th>Dim. & Wt. <a title="Default Settings" data-toggle="popover"
                                                        data-placement="bottom" data-trigger="hover"
                                                        data-content="L: 10cm , B: 10cm , H: 10cm , Weight : 50gm "><span
                                                            class="fa fa-info-circle"></span></a>
                                                </th>
                                                <th>Channel Date</th>
                                                <th>Updated Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order as $row)
                                            <tr>
                                                <td class="text-center">
                                                    <label class="fancy-checkbox{{ $row->id }}">
                                                        <input class="checkbox-tick" type="checkbox"
                                                            name="id[{{ $row->id }}]" value="{{ $row->id }}">
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <td class="text-center"> <a
                                                        href="{{ route('admin.order.detail',$row->id) }}">{{ $row->order_id }}</a>
                                                </td>
                                                <td>{{$row->user_id }}</td>
                                                <td>{!! $row->status !!}</td>
                                                <td>{{ $row->ship_fname }} - {{ $row->ship_pincode }} <br>
                                                    {{ $row->ship_phone }}</td>
                                                <td>{{ $row->total }} {!! $row->payment_mode !!}</td>
                                                <td><b>Dim :</b>
                                                    {{ $row->length }}x{{ $row->breadth }}x{{ $row->height }}
                                                    cm<br><b>Wt :</b> {{ $row->weight }} gm</td>
                                                <td><span class="fa fa-calendar"></span>&nbsp;
                                                    {{ \Carbon\Carbon::parse($row->on_hold_date)->format('d M, Y') }}<br>
                                                    <span class="fa fa-clock-o"></span>&nbsp;
                                                    {{ \Carbon\Carbon::parse($row->on_hold_date)->format('H:i') }}
                                                </td>
                                                <td><span class="fa fa-calendar"></span>&nbsp;
                                                    {{ \Carbon\Carbon::parse($row->updated_at)->format('d M, Y') }}<br>
                                                    <span class="fa fa-clock-o"></span>&nbsp;
                                                    {{ \Carbon\Carbon::parse($row->updated_at)->format('H:i') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 align-self-end">
                            <div class="form-group col-2">
                                <label>@lang('Action')</label>
                                <select class="form-control" name="status" id="myselect">
                                    <option value="" selected disabled>@lang('Select One')</option>

                                    <option vlaue="RTO Received">RTO Received</option>

                                </select>
                            </div>
                        </div>
                    </form>


                </div>
            </div>




            <div class="card mt-30 rto_receive hide">
                <div class="card-header">
                    <h5 class="card-title mb-0"></h5>
                </div>
                <div class="card-body">

                <form id="myForm" action="" method="POST">
                        @csrf
                        <div class="body row">
                            @if(isset($rto_receive))
                            <div class="col-md-12">

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    <label class="fancy-checkbox1">
                                                        <input class="select-all" type="checkbox" name="checkbox">
                                                        &nbsp;<label></label>
                                                    </label>
                                                </th>
                                                <th>Order ID</th>
                                                <th>Seller</th>
                                                <th>Order Status</th>
                                                <th>Customer Info</th>
                                                <th>Value</th>
                                                <th>Dim. & Wt. <a title="Default Settings" data-toggle="popover"
                                                        data-placement="bottom" data-trigger="hover"
                                                        data-content="L: 10cm , B: 10cm , H: 10cm , Weight : 50gm "><span
                                                            class="fa fa-info-circle"></span></a>
                                                </th>
                                                <th>Channel Date</th>
                                                <th>Updated Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rto_receive as $row)
                                            <tr>
                                                <td class="text-center">
                                                    <label class="fancy-checkbox{{ $row->id }}">
                                                        <input class="checkbox-tick" type="checkbox"
                                                            name="id[{{ $row->id }}]" value="{{ $row->id }}">
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <td class="text-center"> <a
                                                        href="{{ route('admin.order.detail',$row->id) }}">{{ $row->order_id }}</a>
                                                </td>
                                                <td>{{$row->user_id }}</td>
                                                <td>{!! $row->status !!}</td>
                                                <td>{{ $row->ship_fname }} - {{ $row->ship_pincode }} <br>
                                                    {{ $row->ship_phone }}</td>
                                                <td>{{ $row->total }} {!! $row->payment_mode !!}</td>
                                                <td><b>Dim :</b>
                                                    {{ $row->length }}x{{ $row->breadth }}x{{ $row->height }}
                                                    cm<br><b>Wt :</b> {{ $row->weight }} gm</td>
                                                <td><span class="fa fa-calendar"></span>&nbsp;
                                                    {{ \Carbon\Carbon::parse($row->on_hold_date)->format('d M, Y') }}<br>
                                                    <span class="fa fa-clock-o"></span>&nbsp;
                                                    {{ \Carbon\Carbon::parse($row->on_hold_date)->format('H:i') }}
                                                </td>
                                                <td><span class="fa fa-calendar"></span>&nbsp;
                                                    {{ \Carbon\Carbon::parse($row->updated_at)->format('d M, Y') }}<br>
                                                    <span class="fa fa-clock-o"></span>&nbsp;
                                                    {{ \Carbon\Carbon::parse($row->updated_at)->format('H:i') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>
                     
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
            (function($) {
                "use strict";

                $('select[name=status]').change(function() {
        // alert('hi');
            if ($('input[name^="id"]:checked').length > 0) {
                // alert('hi');
                var action_type = $('select[name=status]').val();
                // alert(action_type);
                if(action_type =='RTO Received'){
                    alert('Are you sure?');
                    let action_route = `{{ route('admin.order.rto_received') }}`;
                    // alert(action_route);
                    $('#myForm').attr("action", action_route);
                    $('#myForm').submit();
                } 
              
            }
            
            else {
                toastr.error('Select atleast one Order');
                $('select[name=status]').val('');
            }
        });

    })(jQuery);
    </script>


@endsection