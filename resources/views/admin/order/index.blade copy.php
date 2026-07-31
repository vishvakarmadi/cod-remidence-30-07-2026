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
            <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                <div class="row clearfix">
                    <div class="col-xl-5 col-md-5 col-sm-12">
                    </div>
                    <div class="col-xl-7 col-md-9 col-sm-12 text-md-right hidden-xs">
                       <x-button type="import" route="{{ route('admin.bulkorder.create') }}" name="Import"/>
                        @if($session->hasPermissionTo('create order'))
                        <x-button type="create" route="{{ route('admin.order.create') }}" name="Create" />
                        @endif
                    </div>
                </div>
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
                                    <x-field type="number" label="Weight" placeholder="grams" name="weight" />
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
        <div class="col-xl-12">
        <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
            @csrf
            <div class="card">
                <div class="header">
                    <h2>Order List<small>
                </div>
                <div class="body row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        @if($session->hasPermissionTo('export order'))
                                        <th class="text-center">
                                            <label class="fancy-checkbox1">
                                                <input class="select-all" type="checkbox" name="checkbox">
                                                &nbsp;<label></label>
                                            </label>
                                        </th>
                                        @endif
                                        <th>SL</th>
                                        <th>Channel</th>
                                        <th>Order Number</th>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Payment</th>
                                        <th>Dim. & Wt. <a title="Default Settings" data-toggle="popover"
                                                data-placement="bottom" data-trigger="hover"
                                                data-content="L: 10cm , B: 10cm , H: 10cm , Weight : 50gm "><span
                                                    class="fa fa-info-circle"></span></a>
                                        </th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order as $row)
                                    <tr>
                                        @if($session->hasPermissionTo('export order'))
                                        <td class="text-center">
                                            <label class="fancy-checkbox{{ $row->id }}">
                                                <input class="checkbox-tick" type="checkbox" name="id[{{ $row->id }}]"
                                                    value="{{ $row->id }}">
                                                <span></span>
                                            </label>
                                        </td>
                                        @endif
                                        <td>{{ $loop->iteration }}</td>
                                        <td><img src="{{ asset('public/favicon.svg') }}" style="width:70px"
                                                alt="Channel Logo"></td>
                                        <td class="text-center"> <a
                                                href="{{ route('admin.order.detail',$row->id) }}">{{ $row->order_id }}</a>
                                        </td>
                                        <td><span class="fa fa-calendar"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($row->created_at)->format('d M, Y') }}<br>
                                            <span class="fa fa-clock-o"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($row->created_at)->format('H:i') }}
                                        </td>
                                        <td>{{ $row->detail[0]->name }}</td>
                                        <td> {{ $row->total }} .00<br> {!! $row->payment_mode !!}</td>
                                        <td><b>Dim :</b> {{ $row->length }}x{{ $row->breadth }}x{{ $row->height }}
                                            cm<br><b>Wt :</b> {{ $row->weight }} gm</td>
                                        <td>{!! $row->status !!}</td>
                                        <td>
                                            <a href="javascript:void" class="btn btn-secondary" title="Quick Assign"
                                                style="width:13px;" data-order-id="{{ $row->id }}"><span
                                                    class="sr-only">Quick Assign</span> <i class="fa fa-bolt"></i></a>

                                            @if($session->hasPermissionTo('edit order'))
                                            <span style="margin: 0 10px;">|</span>
                                            <a href="{{ route('admin.order.edit',$row->id) }}" class="btn btn-primary"
                                                title="Edit"><span class="sr-only">Edit</span> <i
                                                    class="fa fa-pencil"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr id="order_{{ $row->id }}" data-order-id="{{ $row->id }}" class="quick_assign"
                                        style="display: none">
                                        <td colspan="12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">
                                                        Quick Assign</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                        <input type="hidden" name="id" value="{{ $row->id }}" />
                                                        <div class="form-group col-md-4">
                                                            <label class="form-control-label">Select
                                                                Carrier:</label><span class="required">
                                                                *</span>
                                                            <select class="form-control" name="carrier" required>
                                                                <option value="">Select Carrier</option>
                                                                @foreach($courier as $row)
                                                                <option value="{{$row->id}}">{{ $row->courier }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label class="form-control-label">Select Pickup
                                                                Address:</label><span class="required">
                                                                *</span>
                                                            <select class="form-control" name="pickup_address" required>
                                                                <option value="">Select Pickup Address</option>
                                                                @foreach ($warehouse as $row)
                                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group clearfix"
                                                            style="align-items: unset;display: flex;">
                                                            <label class="element-left"
                                                                style="padding: 34px 0px 0px 27px;">
                                                                <input type="checkbox" name="same_add" class="same_add"
                                                                    id="remember" value="0">
                                                                <span for="same_add">Return Address same as
                                                                    Pickup</span>
                                                            </label>
                                                        </div>
                                                        <div class="form-group col-md-4" id="bill_address"></div>

                                                    </div><br>
                                                    <button type="button" class="btn btn-success click">Assign
                                                        Tracking</button><br><br>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
                {{ $order->links() }}
            </div>

            @if($session->hasPermissionTo('export order'))
            <div class="flex-grow-1 align-self-end">
                <div class="form-group col-2">
                    <label>@lang('Action')</label>
                    <select class="form-control" name="status" id="myselect">
                        <option value="" selected disabled>@lang('Select One')</option>
                        @if($session->hasPermissionTo('delete order'))
                        <option value="delete">Delete</option>
                        @endif
                    </select>
                </div>
            </div>
            @endif
        </form>
    </div>
    </div>
</div>



<script>
$('.expand').click(function() {
    if ($(this).text() == 'More Filters>>') {
        $(this).text('Less Filters>>');
    } else {
        $(this).text('More Filters>>');
    }
    $('.show_more').slideToggle('fast');
});

$('.same_add').on('click', function() {
    var value = $('input[name="same_add"]:checked').val();
    var html = `<div id="bill_remove">
                        <label class="form-control-label">Select Return Address:</label><span
                            class="required">
                            *</span>
                        <select class="form-control" name="return_address" required>
                            <option value="">Select Return Address</option>
                            @foreach ($warehouse as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>`;
    if (value == 0) {
        $('#bill_address').append(html);
    }
    if (value == undefined) {
        $('#bill_remove').remove();
    }
})

$(document).ready(function() {
    $('.btn-secondary').click(function() {
        var quickAssignRow = $('#order_' + $(this).data('order-id'));
        quickAssignRow.slideToggle('fast');
    });
});

function handleChange(selectElement) {
    var id = selectElement.getAttribute('data-id');
    var value = selectElement.getAttribute('data-value');

    if (confirm("Are you sure you want to process this Order?")) {
        if (value !== 'process_fully') {
            window.location.href = '/admin/order/process/partially/' + id;
        } else {
            $.ajax({
                type: 'GET',
                url: '/admin/order/process',
                data: {
                    id: id,
                    change: value
                },
                success: function(data) {
                    location.reload();
                }
            });
        }
    }
}

(function($) {
    "use strict";

    $('select[name=status]').change(function() {
        if ($('input[name^="id"]:checked').length > 0) {
            $('#myForm').submit();
        } else {
            toastr.error('Select atleast one Order');
            $('select[name=status]').val('');
        }
    });


})(jQuery);
</script>

{{-- <script type="text/javascript">
    $(document).ready(function() {
        $('.click').click(function(){ 
            var currentValue = $(this).attr("value");

            $.ajax({
                url: '{{ route('ship') }}',
                method: 'get',             
                data: {id: $('input[name="id"]').val(), carrier: $('select[name="carrier"]').val(), pickup_address: $('select[name="pickup_address"]').val(), _token: $('input[name="_token"]').val()},
                success: function(data){
                    console.log(data);
                },
                error: function(data){
                    alert(data.id);
                },
            });
        });         
    });
</script> --}}
@endsection