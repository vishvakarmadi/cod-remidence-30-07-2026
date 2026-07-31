@extends('admin.admin_layouts')
@section('admin_content')
@php
use App\Models\Admin\Courier;
$session = Auth::guard('admin')->user();
@endphp
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/public/admin/semantic.min.js"></script>
<link rel="stylesheet" href="/public/admin/semantic.min.css">
<style>
label.radio-card {
  cursor: pointer;
}
label.radio-card .card-content-wrapper {
  background: #fff;
  border-radius: 5px;
  max-width: 280px;
  padding: 15px;
  display: grid;
  box-shadow: 0 2px 4px 0 rgba(219, 215, 215, 0.04);
  transition: 200ms linear;
}
label.radio-card .check-icon {
  width: 20px;
  height: 20px;
  display: inline-block;
  border: solid 2px #e3e3e3;
  border-radius: 50%;
  transition: 200ms linear;
  position: relative;
}
label.radio-card .check-icon:before {
  content: "";
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg width='12' height='9' viewBox='0 0 12 9' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0.93552 4.58423C0.890286 4.53718 0.854262 4.48209 0.829309 4.42179C0.779553 4.28741 0.779553 4.13965 0.829309 4.00527C0.853759 3.94471 0.889842 3.88952 0.93552 3.84283L1.68941 3.12018C1.73378 3.06821 1.7893 3.02692 1.85185 2.99939C1.91206 2.97215 1.97736 2.95796 2.04345 2.95774C2.11507 2.95635 2.18613 2.97056 2.2517 2.99939C2.31652 3.02822 2.3752 3.06922 2.42456 3.12018L4.69872 5.39851L9.58026 0.516971C9.62828 0.466328 9.68554 0.42533 9.74895 0.396182C9.81468 0.367844 9.88563 0.353653 9.95721 0.354531C10.0244 0.354903 10.0907 0.369582 10.1517 0.397592C10.2128 0.425602 10.2672 0.466298 10.3112 0.516971L11.0651 1.25003C11.1108 1.29672 11.1469 1.35191 11.1713 1.41247C11.2211 1.54686 11.2211 1.69461 11.1713 1.82899C11.1464 1.88929 11.1104 1.94439 11.0651 1.99143L5.06525 7.96007C5.02054 8.0122 4.96514 8.0541 4.90281 8.08294C4.76944 8.13802 4.61967 8.13802 4.4863 8.08294C4.42397 8.0541 4.36857 8.0122 4.32386 7.96007L0.93552 4.58423Z' fill='white'/%3E%3C/svg%3E%0A");
  background-repeat: no-repeat;
  background-size: 12px;
  background-position: center center;
  transform: scale(1.6);
  transition: 200ms linear;
  opacity: 0;
}
label.radio-card input[type=radio] {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
}
label.radio-card input[type=radio]:checked + .card-content-wrapper {
  box-shadow: 0 2px 4px 0 rgba(219, 215, 215, 0.5), 0 0 0 2px #3057d5;
}
label.radio-card input[type=radio]:checked + .card-content-wrapper .check-icon {
  background: #3057d5;
  border-color: #3057d5;
  transform: scale(1.2);
}
label.radio-card input[type=radio]:checked + .card-content-wrapper .check-icon:before {
  transform: scale(1);
  opacity: 1;
}
label.radio-card input[type=radio]:focus + .card-content-wrapper .check-icon {
  box-shadow: 0 0 0 4px rgba(48, 86, 213, 0.2);
  border-color: #3056d5;
}

label.radio-card .card-content h4 {
  font-size: 16px;
  letter-spacing: -0.24px;
  text-align: center;
  color: #1f2949;
  margin-bottom: 10px;
}
label.radio-card .card-content h5 {
  font-size: 14px;
  line-height: 1.4;
  text-align: center;
  color: #686d73;
}
</style>


<!-- Main body part  -->
<div class="container-fluid">
    <!-- Page header section  -->
    <!-- <div class="block-header">
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
                        <x-button type="create" route="{{ route('admin.order.create') }}" name="Create" />
                    </div>
                </div>
            </div>
        </div>
    </div> -->

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


        <form id="myForm" action="{{ route('admin.order.manifest') }}" method="POST">
            @csrf
            <div class="card mt-30 new_orders">
                <div class="header d-flex justify-content-between">
                    <h2>Order List</h2>
                    <div class="form-group col-2 mb-0">
                        <label class="mr-2">@lang('Action')</label>
                        <select class="form-control" name="status" id="myselect">
                            <option value="" selected disabled>@lang('Select One')</option>
                            <option value="manifest">Manifest Awb</option>
                            <!-- <option value="cancel">Cancel</option>
                            <option value="rto">RTO</option>
                            <option value="ndr">NDR</option>
                            <option value="refund">Refund</option>
                            <option value="delete">Delete</option> -->
                        </select>
                    </div>
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
                                        <th>SL</th>
                                        <th>Channel</th>
                                        <th>Order Number</th>
                                        <th>Date</th>
                                        <th>Courier Assigned</th>
                                        <th>Courier Name</th>
                                        <th>Product</th>
                                        <th>Payment</th>
                                        <th>Dim. & Wt. <a title="Default Settings" data-toggle="popover"
                                                data-placement="bottom" data-trigger="hover"
                                                data-content="L: 10cm , B: 10cm , H: 10cm , Weight : 50gm "><span
                                                    class="fa fa-info-circle"></span></a>
                                        </th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order as $row)
                                    <tr>
                                        <td class="text-center">
                                            <label class="fancy-checkbox{{ $row->id }}">
                                                <input class="checkbox-tick" type="checkbox" name="id[{{ $row->id }}]"
                                                    value="{{ $row->id }}">
                                                <span></span>
                                            </label>
                                        </td>
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
                                        <td><span class="fa fa-calendar"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($row->shipped_date)->format('d M, Y') }}<br>
                                            <span class="fa fa-clock-o"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($row->shipped_date)->format('H:i') }}
                                        </td>
                                        <td>
                                        @if($row->ship_courier_id)
                                        @php $courierData = Courier::find($row->ship_courier_id) @endphp
                                        {{ $courierData->courier}}
                                        @endif
                                        </td>
                                        <td>{{ $row->detail[0]->name }}</td>
                                        <td> {{ $row->total }} .00<br> {!! $row->payment_mode !!}</td>
                                        <td><b>Dim :</b> {{ $row->length }}x{{ $row->breadth }}x{{ $row->height }}
                                            cm<br><b>Wt :</b> {{ $row->weight }} gm</td>
                                        <td>{!! $row->status !!}</td>
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{ $order->links() }}
            </div>

           
        </form>
    </div>
    </div>
</div>

<script type="text/javascript">
    (function($) {
        "use strict";
        let lastExpandedRow = null;
        $('.assign').on('click', function() {
            var quickAssignRow = $('#order_' + $(this).data('order-id'));
            let id = $(this).data('order-id');
            if (lastExpandedRow !== null) {
                lastExpandedRow.find('.courierList').html('');
                lastExpandedRow.slideUp('fast');
            }
            $.get({
                url: "{{ route('admin.order.get.courier') }}",
                data: { 
                    order_id: $(this).data('order-id'),
                    warehouse_id: {{  @$warehouse[0]->id ?? 0 }} 
                },
                beforeSend: function() {
                    $('#loader').removeClass('hidden')
                },
                success: function(data) {
                    if(data.status == 1){
                        var html = '';
                        html += `<div class="grid-wrapper grid-col-auto row" style="margin:0px 0px 0px 20px;">`;
                        $.each(data.data, function(key, value){
                            html += `<label for="radio-card-${key}" class="radio-card" style="width:320px;">
                                        <input type="radio" name="courier_id" value="${key}" id="radio-card-${key}" ${key == 0 ? 'checked' : ''}/>
                                        <div class="card-content-wrapper" style="background-color:#e1e1e1;">
                                            <div class="card-content">
                                                <span class="check-icon"></span>
                                                <img src="${value.img}" alt=""  style="width:50px;margin-left:10px;"  />
                                                <i class="fa ${value.mode} fa-2x" style="margin-left:15px;"></i>
                                                <h4 style="margin: 10px 0px 0px -40%">${value.name}</h4>
                                                <h5 style="margin: 10px 0px 0px -17% !important;color: #009521;">(${value.weight}) | ${value.price}</h5>
                                            </div>
                                        </div>
                                    </label>`;
                        });
                        html += `</div><button type="button" class="btn btn-success assignCourier" data-id="${id}" style="left: 1.5%;top: 10px;position: relative;">Assign Tracking</button><br><br>`;
                    } else {
                        html = `<span class="badge text-white bg-danger" style="margin-top: 3%;left: 40%;position: relative;">${data.message}</span>`;
                    }
                    quickAssignRow.find('.courierList').html(html);
                    quickAssignRow.slideToggle('fast');
                    lastExpandedRow = quickAssignRow;
                },
                complete: function(){
                    $('#loader').addClass('hidden')
                },
            });
        });

        $("select[name=warehouse_id]").on('change',function(){
            var quickAssignRow = $('#order_' + $(this).data('id'));
            let id = $(this).data('id');
            $.get({
                url: "{{ route('admin.order.get.courier') }}",
                data: { 
                    order_id: id,
                    warehouse_id: $(this).val() 
                },
                beforeSend: function() {
                    $('#loader').removeClass('hidden')
                },
                success: function(data) {
                    if(data.status == 1){
                        var html = '';
                        html += `<div class="grid-wrapper grid-col-auto row" style="margin:0px 0px 0px 20px;">`;
                        $.each(data.data, function(key, value){
                            html += `<label for="radio-card-${key}" class="radio-card" style="width:320px;">
                                        <input type="radio" name="courier_id" id="radio-card-${key}" value="${key}" ${key == 0 ? 'checked' : ''}/>
                                        <div class="card-content-wrapper" style="background-color:#e1e1e1;">
                                            <div class="card-content">
                                                <span class="check-icon"></span>
                                                <img src="${value.img}" alt=""  style="width:50px;margin-left:10px;"  />
                                                <i class="fa ${value.mode} fa-2x" style="margin-left:15px;"></i>
                                                <h4 style="margin: 10px 0px 0px -40%">${value.name}</h4>
                                                <h5 style="margin: 10px 0px 0px -17% !important;color: #009521;">(${value.weight}) | ${value.price}</h5>
                                            </div>
                                        </div>
                                    </label>`;
                        });
                        html += `</div><button type="button" class="btn btn-success assignCourier" data-id="${id}" style="left: 1.5%;top: 10px;position: relative;">Assign Tracking</button><br><br>`;
                    } else {
                        html = `<span class="badge text-white bg-danger" style="margin-top: 3%;left: 40%;position: relative;">${data.message}</span>`;
                    }
                    quickAssignRow.find('.courierList').html(html);
                },
                complete: function(){
                    $('#loader').addClass('hidden')
                },
            });
        });

        $(document).on('click', '.assignCourier', function(){
            let id = $(this).data('id');
            var data = $('#order_' + id + ' :input').serialize();
            $.get({
                url: "{{ route('admin.order.assign') }}",
                data: data,
                beforeSend: function() {
                    $('#loader').removeClass('hidden');
                },
                success: function(response) {
                    if(response.status == 1) {
                        toastr.success(response.message); 
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(error) {
                    if (response.responseJSON && response.responseJSON.errors) {
                        var errors = response.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            toastr.error(errors[key][0]);
                        });
                    } else {
                        toastr.error('An error occurred while processing your request.', 'Error');
                    }
                },
                complete: function() {
                    $('#loader').addClass('hidden');
                },
            });
        });

        $('select[name=status]').change(function() {
        if ($('input[name^="id"]:checked').length > 0) {
            var action_type = $('select[name=status]').val();
            if(action_type == 'delete'){
                let action_route = `{{ route('admin.order.action') }}`;
                $('#myForm').attr("action", action_route);
            }else if(action_type == 'on_hold'){
                let action_route = `{{ route('admin.order.on_hold') }}`;
                $('#myForm').attr("action", action_route);
            }else if(action_type == 'cancel'){
                let action_route = `{{ route('admin.order.cancel') }}`;
                $('#myForm').attr("action", action_route);
            }
            else if(action_type == 'rto'){
                let action_route = `{{ route('admin.order.rto') }}`;
                $('#myForm').attr("action", action_route);
            }
            else if(action_type == 'ndr'){
                let action_route = `{{ route('admin.order.ndr') }}`;
                $('#myForm').attr("action", action_route);
            }
            else if(action_type == 'refund'){
                let action_route = `{{ route('admin.order.refund') }}`;
                $('#myForm').attr("action", action_route);
            }
            $('#myForm').submit();
        } else {
            toastr.error('Select atleast one Order');
            $('select[name=status]').val('');
        }
    });

        $('.expand').on('click',function() {
            if ($(this).text() == 'More Filters>>') {
                $(this).text('Less Filters>>');
            } else {
                $(this).text('More Filters>>');
            }
            $('.show_more').slideToggle('fast');
        });
    })(jQuery);

</script>


@endsection