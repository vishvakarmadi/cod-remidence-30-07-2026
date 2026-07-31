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
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12 col-sm-12">
                <h1>Hi, Welcomeback!</h1>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-xl-12">
            <div class="card bg-light mb-3">
                <div class="card-header">
                    <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Filter</h5>
                        <a href="javascript:void(0)" class="expand">More Filters>></a>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.order.index') }}" method="GET">
                        <div class="col-md-12">
                            <div class="row">
                                <x-field type="text" label="Order ID" placeholder="Order ID" name="order_id" />
                                <x-field type="date-range" label="Select Date Range" name="start_date-end_date" />
                                <x-field type="text" label="Customer" placeholder="Customer" name="customer" />
                            </div>
                            <div class="show_more" style="width: 100%;display:none">
                                <div class="row">
                                    <x-field type="number" label="Phone" placeholder="Phone" name="phone" />
                                    <x-field type="number" label="Order Value" placeholder="value" name="price" />
                                    <x-field type="number" label="Weight" placeholder="kilograms" name="weight" />
                                    <x-field type="number" label="Length" placeholder="cms" name="length" />
                                    <x-field type="number" label="Breadth" placeholder="cms" name="breadth" />
                                    <x-field type="number" label="Height" placeholder="cms" name="height" />
                                </div>
                            </div>
                            <div class="row">
                                <x-button type="submit" name="Search" />
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if(isset($order_unfulfill))
        <div class="col-xl-12">
        <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
            @csrf
            <div class="card">
                <div class="header">
                    <h2>Unfulfilled Orders<small>
                </div>
                <div class="body row">
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
                                    @foreach($order_unfulfill as $row)
                                    <tr>
                                        <td class="text-center">
                                            <label class="fancy-checkbox{{ $row->id }}">
                                                <input class="checkbox-tick" type="checkbox" name="id[{{ $row->id }}]"
                                                    value="{{ $row->id }}">
                                                <span></span>
                                            </label>
                                        </td>
                                        <td class="text-center"> <a
                                            href="{{ route('admin.order.detail',$row->id) }}">{{ $row->order_id }}</a>
                                        </td>
                                        <td>{!! $row->status !!}</td>
                                        <td>{{ $row->ship_fname }} - {{ $row->ship_pincode }} <br> {{ $row->ship_phone }}</td>
                                        <td>{{ $row->total }} {!! $row->payment_mode !!}</td>
                                        <td><b>Dim :</b> {{ $row->length }}x{{ $row->breadth }}x{{ $row->height }}
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
                </div>
            </div>
            <div class="flex-grow-1 align-self-end">
                <div class="form-group col-2">
                    <label>@lang('Action')</label>
                    <select class="form-control" name="status" id="myselect">
                        <option value="" selected disabled>@lang('Select One')</option>
                        <option value="fulfilled">Mark As Fulfillment</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    @elseif(!isset($order_unfulfill))
    <div class="card">
        <h1>No data found</h1>
    </div>
    @endif
    </div>
</div>
<script src="{{ asset('public/admin/rate.js') }}"></script>

<script>
$('.expand').click(function() {
    if ($(this).text() == 'More Filters>>') {
        $(this).text('Less Filters>>');
    } else {
        $(this).text('More Filters>>');
    }
    $('.show_more').slideToggle('fast');
});

</script>
<script type="text/javascript">
    (function($) {
        "use strict";
        $('select[name=status]').change(function() {
        if ($('input[name^="id"]:checked').length > 0) {
            var action_type = $('select[name=status]').val();
            if(action_type == 'fulfilled'){
                let action_route = `{{ route('admin.order.fulfilled') }}`;
                $('#myForm').attr("action", action_route);
                $('#myForm').submit();
            }
        } else {
            toastr.error('Select atleast one Order');
            $('select[name=status]').val('');
        }
    });

       
    })(jQuery);

</script>
@endsection