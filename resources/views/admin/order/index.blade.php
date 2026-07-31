@extends('admin.admin_layouts')
@section('admin_content')
    @php
        $session = Auth::guard('admin')->user();
        use App\Models\Admin\Order;
    @endphp
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/admin/public/admin/semantic.min.js"></script>
    <link rel="stylesheet" href="/admin/public/admin/semantic.min.css">
    <style>
        label.radio-card {
            cursor: pointer;
        }

        label.radio-card .card-content-wrapper {
            background: #fff;
            border-radius: 5px;
            max-width: 280px;
            padding: 5px;
            display: grid;
            box-shadow: 0 2px 4px 0 rgba(219, 215, 215, 0.04);
            transition: 200ms linear;
        }

        label.radio-card .check-icon {
            width: 10px;
            height: 10px;
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

        label.radio-card input[type=radio]:checked+.card-content-wrapper {
            box-shadow: 0 2px 4px 0 rgba(219, 215, 215, 0.5), 0 0 0 2px #3057d5;
        }

        label.radio-card input[type=radio]:checked+.card-content-wrapper .check-icon {
            background: #3057d5;
            border-color: #3057d5;
            transform: scale(1.2);
        }

        label.radio-card input[type=radio]:checked+.card-content-wrapper .check-icon:before {
            transform: scale(1);
            opacity: 1;
        }

        label.radio-card input[type=radio]:focus+.card-content-wrapper .check-icon {
            box-shadow: 0 0 0 4px rgba(48, 86, 213, 0.2);
            border-color: #3056d5;
        }

        label.radio-card .card-content h4 {
            font-size: 15px;
            color: #1f2949;
            margin: 0px;
            display: inline;

        }

        label.radio-card .card-content h5 {
            font-size: 14px;
            margin-left: 8px;
            color: #686d73;
        }

        /* .courierList{
        align-items: start;
    } */
        /* .table td{
        padding: 0px 10px;
    }
    .card .header {
        padding: 5px;
    }
    tr.dublicate_yes {
        background-color: beige !important;
    } */
        .table tbody tr td {
            font-size: 12px;
        }

        .table td {
            padding: 0px 10px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: unset;
        }

        .table-striped tbody tr:nth-of-type(4n + 1) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }
    </style>

    <div class="container-fluid">
        <!-- ===== FIXED COURIER CUT-OFF MARQUEE ===== -->
        <div class="cutoff-marquee">
            <!--<div class="cutoff-marquee-content">-->
            <!--    🚚 <strong>Courier Cut-Off Timings:</strong>-->

            <!--    Ecom Express – 2:00 PM |-->
            <!--    Delhivery – 2:00 PM |-->
            <!--    Blue Dart – 2:00 PM |-->
            <!--    XpressBees –2:00 PM |-->
            <!--    DTDC – 2:00 PM |-->
            <!--    Ekart – 2:00 PM |-->
            <!--    Shadowfax – 2:00 PM |-->

            <!--    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;-->

            <!--    🚚 <strong>Courier Cut-Off Timings:</strong>-->

            <!--    Ecom Express – 2:00 PM |-->
            <!--    Delhivery – 2:00 PM |-->
            <!--    Blue Dart – 2:00 PM |-->
            <!--    XpressBees –2:00 PM |-->
            <!--    DTDC – 2:00 PM |-->
            <!--    Ekart – 2:00 PM |-->
            <!--    Shadowfax – 2:00 PM |-->
            <!--</div>-->
            <div class="cutoff-marquee-content">
                🚚 <strong>Courier Cut-Off Timings:</strong>

                Ekart – 12:00 PM | 
                Delhivery – 1:00 PM | 
                XB – 11:00 AM | 
                Blue Dart – 11:00 AM | 
                Pikndel – SDD: 11:00 AM, NDD: 3:00 PM | 
                Blitz – SDD: 11:00 AM, NDD: 3:00 PM

                &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;

                🚚 <strong>Courier Cut-Off Timings:</strong>

                Ekart – 12:00 PM | 
                Delhivery – 1:00 PM | 
                XB – 11:00 AM | 
                Blue Dart – 11:00 AM | 
                Pikndel – SDD: 11:00 AM, NDD: 3:00 PM | 
                Blitz – SDD: 11:00 AM, NDD: 3:00 PM
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-xl-12">
                <div class="card mb-3">
                    <!-- Header Section -->
                    <div class="card-header" style="display:flex;flex-wrap: wrap;border-radius: 16px 16px 0 0;">
                        <!-- <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Filter</h5> -->
                        <div class="col-md-9"><a href="javascript:void(0)" class="expand">Filters
                                <?php if (empty($re_data)) {
        echo '<<';
    } else {
        echo '>>';
    } ?></a></div>
                        <div class="col-md-3">
                            <x-button type="import" route="{{ route('admin.bulkorder.create') }}" name="Import" />
                            <x-button type="create" route="{{ route('admin.order.create') }}" name="Create" />
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.order.index') }}" method="GET">
                            <div class="col-md-12">
                                <?php 
                            $trac = $ven = '';
    $c_id = 0;
    $sta = 1;
    if (!empty($re_data)) {
        $trac = $re_data['tracking_info'] ?? '';
        $sta = $re_data['status_order'] ?? 1;
        $ven = $re_data['vendor_order_id'] ?? '';
        $c_id = $re_data['seller_id'] ?? 0;
    }
                            ?>
                                <div class="show_more" style="width: 100%; {{ empty($re_data) ? 'display:none;' : '' }}">
                                    <div class="row">
                                        <x-field type="text" label="Order Number" placeholder="Order Number"
                                            name="vendor_order_id" value="{{ $ven ?? '' }}" />
                                        <!--<x-field type="text" label="AWB" placeholder="AWB" name="tracking_info" value="{{ $trac ?? '' }}" />-->

                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">User</label>
                                            <select name="seller_id" class="form-control">
                                                <option value="0">User</option>
                                                @foreach($userwithneworder as $user)
                                                    <option value="{{ $user->id }}" {{ $c_id == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">Status</label>
                                            <select name="status_order" class="form-control">
                                                <option value="1" {{ $sta == '1' ? 'selected' : '' }}>New</option>
                                                <option value="4" {{ $sta == '4' ? 'selected' : '' }}>Cancelled</option>
                                                <option value="0" {{ $sta == '0' ? 'selected' : '' }}>Both</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-control-label">Payment Type</label>
                                            <select name="payment_mode_filter" class="form-control">
                                                <option value="0" {{ ($re_data['payment_mode_filter'] ?? '0') == '0' ? 'selected' : '' }}>All Payments</option>
                                                <option value="1" {{ ($re_data['payment_mode_filter'] ?? '0') == '1' ? 'selected' : '' }}>COD Only</option>
                                                <option value="2" {{ ($re_data['payment_mode_filter'] ?? '0') == '2' ? 'selected' : '' }}>Prepaid Only</option>
                                            </select>
                                        </div>

                                        <input type="hidden" name="sortField"
                                            value="{{ $_GET['sortField'] ?? $sortField }}">
                                        <input type="hidden" name="sortDirection"
                                            value="{{ $_GET['sortDirection'] ?? $sortDirection }}">
                                    </div>

                                    <div class="row">
                                        <x-button size="col-lg-3" type="submit" name="Search" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="sts">
                <i class="fa fa-info-circle"></i>
                <span><b>Total Orders : <span class="badge-pill bg-primary">{{$totalOrdersCount}}</span></b></span>
            </div>
            <div class="col-xl-12">
                <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
                    @csrf
                    <div class="card new_orders">
                        <div class="header" style="padding-bottom: 0;">
                            <h2>Order List</h2><br>

                            <div class="row col-md-12">
                                <div class="form-group col-md-4">
                                    <label class="mr-2">@lang('Action')</label>
                                    <select class="form-control" name="status" id="myselect">
                                        <option value="" selected disabled>@lang('Select One')</option>
                                        <option value="awb">AWB</option>
                                        <option value="delete">Delete</option>
                                        <option value="download">Download</option>
                                        <option value="downloadupdate">Download for update</option>
                                        @if($session->id == '100' || $session->id == '1' || $session->id == '69')
                                            <option value="updateweight">Update weight to 500</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Select Pickup Address</label><span class="required"> (For AWB)</span>:
                                    <select class="form-control" name="multiplewarehouse_id">
                                        @foreach ($warehouse as $ware)
                                            <option value="{{ $ware->id }}">{{ $ware->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Select Return Address</label><span class="required"> (For AWB)</span>:
                                    <select class="form-control" name="multiplereturn_warehouse_id">
                                        @foreach ($warehouse as $ware)
                                            <option value="{{ $ware->id }}">{{ $ware->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class>
                            <div class="col-md-12">
                                Selected: <span class="order_selected_count">0</span>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    <label class="fancy-checkbox">
                                                        <input class="select-all" type="checkbox" name="checkbox">
                                                        <span></span>
                                                    </label>
                                                </th>
                                                <th>ID</th>
                                                <th>S.ID</th>
                                                <th>Seller</th>
                                                <th>Channel</th>
                                                <th>Order Number</th>
                                                <th>Buyer</th>
                                                <th>Date</th>
                                                <th>Product</th>
                                                <th>Payment</th>
                                                <th>Dim. & Wt.</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order as $row)
                                                <tr>
                                                    <td class="text-center">
                                                        <label class="fancy-checkbox">
                                                            <input class="checkbox-tick" type="checkbox"
                                                                name="id[{{ $row->id }}]" value="{{ $row->id }}">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>{{ $row->id }}</td>
                                                    <td>{{ $row->order_id }}</td>
                                                    <td>{{ substr($row->user_id, 0, 32) }}</td>
                                                    <td class="text-center">
                                                        @if($row->channel == 'Hyloship')
                                                            {{ $row->channel }}
                                                        @elseif($row->channel == 'Shopify')
                                                            <img src="{{ asset('public/channel/shopify.png') }}" style="width:30px"
                                                                alt="Channel Logo">
                                                        @elseif($row->channel == 'WhatsApp')
                                                            <img src="{{ asset('public/channel/WhatsApp.jpg') }}" style="width:30px"
                                                                alt="Channel Logo">
                                                        @elseif($row->channel == 'Woocommerce')
                                                            <img src="{{ asset('public/channel/woocommerce.png') }}"
                                                                style="width:30px" alt="Channel Logo">
                                                        @else
                                                            {{ $row->channel }}
                                                        @endif
                                                    </td>
                                                    <td class="text-center"><a
                                                            href="{{ route('admin.order.detail', $row->id) }}">{{ $row->vendor_order_id }}</a>
                                                    </td>
                                                    <td>{{ $row->ship_fname }} {{ $row->ship_lname }}</td>
                                                    <td>
                                                        <span
                                                            class="fa fa-calendar"></span>&nbsp;{{ \Carbon\Carbon::parse($row->created_at)->format('d M, Y') }}<br>
                                                        <span
                                                            class="fa fa-clock-o"></span>&nbsp;{{ \Carbon\Carbon::parse($row->created_at)->format('H:i') }}
                                                    </td>
                                                    <td>{{ substr(@$row->detail[0]->name, 0, 32) }}{{ strlen(@$row->detail[0]->name) > 32 ? '...' : '' }}
                                                    </td>
                                                    <td>{{ $row->custom_total }} .00<br>{!! $row->payment_mode !!}</td>
                                                    <td><b>Dim :</b> {{ $row->length }}x{{ $row->breadth }}x{{ $row->height }}
                                                        cm<br><b>Wt :</b> {{ $row->weight }} gm
                                                        <br><b>Vol. Wt :</b> {{ number_format(($row->length * $row->breadth * $row->height) / 5000, 2) }} kg
                                                    <td>{!! $row->status !!}</td>
                                                    <!--<td>-->
                                                    <!--    <div class="btn-group">-->
                                                    <!--        <a href="javascript:void(0)" class="btn btn-primary assign"-->
                                                    <!--            data-order-id="{{ $row->id }}">-->
                                                    <!--            <i class="fa fa-cart-plus" aria-hidden="true"></i>-->
                                                    <!--        </a>-->
                                                    <!--        <a href="{{ route('admin.order.edit', $row->id) }}"-->
                                                    <!--            class="btn btn-secondary" title="Edit">-->
                                                    <!--            <i class="fa fa-edit" aria-hidden="true"></i>-->
                                                    <!--        </a>-->
                                                    <!--    </div>-->
                                                    <!--</td>-->
                                                     <td>
                                                        <div class="btn-group">
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-primary assign btn-sm"
                                                                data-order-id="{{ $row->id }}"
                                                                style="font-size: 11px;">
                                                                Ship Now
                                                            </a>
                                                            <a href="{{ route('admin.order.edit', $row->id) }}"
                                                                class="btn btn-secondary btn-sm" title="Edit"
                                                                style="font-size: 11px; margin-left: 5px;">
                                                                Edit
                                                            </a>
                                                        </div>
                                                    </td>
                                                    
                                                </tr>
                                                <tr id="order_{{ $row->id }}" data-order-id="{{ $row->id }}"
                                                    class="quick_assign" style="display: none">

                                                    <td colspan="13" style="padding: 0;">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h5
                                                                    class="m-0 mt-2 font-weight-bold text-primary invoice-heading">
                                                                    Quick Assign</h5>
                                                                <span onclick="$('#order_{{ $row->id }}').hide();"
                                                                    class="btn btn-secondary"
                                                                    style="float:right;margin-top:-30px;margin-right:10px">Hide</span>
                                                            </div>

                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <label class="form-control-label">Select Pickup
                                                                            Address:</label><span class="required"> *</span>
                                                                        @if($row->reverse_order == '1')
                                                                            <br><span style="color:red">
                                                                                This is a Reverse order, pickup address will be
                                                                                treated as shipping address
                                                                            </span>
                                                                        @endif
                                                                        <input type="hidden" name="order_id"
                                                                            value="{{ $row->id }}">
                                                                        <select class="form-control" name="warehouse_id"
                                                                            data-id="{{ $row->id }}" required>
                                                                            @foreach ($warehouse as $ware)
                                                                                <option value="{{ $ware->id }}">{{ $ware->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    @if($row->reverse_order == '0')
                                                                        <div class="form-group col-md-4">
                                                                            <label class="form-control-label">Select Return Address
                                                                                (in case of RTO):</label><span class="required">
                                                                                *</span>
                                                                            <select class="form-control" name="return_warehouse_id"
                                                                                data-id="{{ $row->id }}" required>
                                                                                @foreach ($warehouse as $ware)
                                                                                    <option value="{{ $ware->id }}">{{ $ware->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="row courierList"></div>
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
                    </div>
                </form>
            </div>


        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $order->links() }}
    </div>



    <script type="text/javascript">
        (function ($) {
            "use strict";
            let lastExpandedRow = null;
            $('.assign').on('click', function () {
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
                    beforeSend: function () {
                        $('#loader').removeClass('hidden')
                    },
                    success: function (data) {
                        if (data.status == 1) {
                            var html = '';
                            html += `<div class="grid-wrapper grid-col-auto row" style="margin:0px 0px 0px 10px;">`;
                            $.each(data.data, function (key, value) {
                                html += `<label for="radio-card-${key}" class="radio-card" style="min-width: 15%;padding: 0 5px;margin: 0;">
                                            <input type="radio" name="courier_id" value="${key}" id="radio-card-${key}" ${key == 0 ? 'checked' : ''}/>
                                            <div class="card-content-wrapper" style="background-color:#e1e1e1;">
                                                <div class="card-content" style="display:flex;align-items: center;">
                                                    <span class="check-icon" style="align-self: start;"></span>
                                                                                                        <img src="${value.img}" alt=""  style="width:40px;display:none"  />

                                                    <div class="assin_right">
                                                    ${value.name} ${value.mode === 'fa-plane' ? 'Air' : (value.mode === 'fa-truck' ? 'Surface' : (value.mode === 'fa-bicycle' ? 'NDD' : (value.mode === 'fa-motorcycle' ? 'SDD' : (value.mode === 'fa-bolt' ? 'QUICK' : ''))))}-${value.weight_used} kg
                                                    <h5 style="color: #009521;margin:5px;margin-left:-8px"><i class="fa ${value.mode} " style="font-size:15px;color: black;"></i>(${value.weight}) | ${value.price}</h5>
                                                    ${value.serviceable === false ? `<span class="badge badge-danger" style="font-size:10px;display:block;">Pincode is not serviceable</span>` : (value.edd ? `<span class="badge badge-info" style="font-size:10px">EDD: ${value.edd}</span>` : '')}
                                                    </div>
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
                    complete: function () {
                        $('#loader').addClass('hidden')
                    },
                });
            });

            $("select[name=warehouse_id]").on('change', function () {
                var quickAssignRow = $('#order_' + $(this).data('id'));
                let id = $(this).data('id');
                $.get({
                    url: "{{ route('admin.order.get.courier') }}",
                    data: {
                        order_id: id,
                        warehouse_id: $(this).val()
                    },
                    beforeSend: function () {
                        $('#loader').removeClass('hidden')
                    },
                    success: function (data) {
                        if (data.status == 1) {
                            var html = '';
                            html += `<div class="grid-wrapper grid-col-auto row" style="margin:0px 0px 0px 10px;">`;
                            $.each(data.data, function (key, value) {
                                html += `<label for="radio-card-${key}" class="radio-card" style="width: 15%;padding: 0 5px;margin: 0;">
                                            <input type="radio" name="courier_id" id="radio-card-${key}" value="${key}" ${key == 0 ? 'checked' : ''}/>
                                            <div class="card-content-wrapper" style="background-color:#e1e1e1;">
                                                <div class="card-content" style="display:flex;align-items: center;">
                                                    <span class="check-icon" style="align-self: start;"></span>
                                                    <img src="${value.img}" alt=""  style="width:40px;"  />
                                                    <div class="assin_right">
                                                   ${value.name} ${value.mode === 'fa-plane' ? 'Air' : (value.mode === 'fa-truck' ? 'Surface' : (value.mode === 'fa-bicycle' ? 'NDD' : (value.mode === 'fa-motorcycle' ? 'SDD' : (value.mode === 'fa-bolt' ? 'QUICK' : ''))))}-${value.weight_used} kg
                                                    <h5 style="color: #009521;margin:5px;margin-left:-8px"><i class="fa ${value.mode} " style="font-size:15px;color: black;"></i>(${value.weight}) | ${value.price}</h5>
                                                    ${value.serviceable === false ? `<span class="badge badge-danger" style="font-size:10px;display:block;">Pincode is not serviceable</span>` : (value.edd ? `<span class="badge badge-info" style="font-size:10px">EDD: ${value.edd}</span>` : '')}
                                                    </div>
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
                    complete: function () {
                        $('#loader').addClass('hidden')
                    },
                });
            });

            $(document).on('click', '.assignCourier', function () {
                let id = $(this).data('id');
                var data = $('#order_' + id + ' :input').serialize();
                $.ajax({
                    method: 'GET',
                    url: "{{ route('admin.order.assign') }}",
                    data: data,
                    beforeSend: function () {
                        $('#loader').removeClass('hidden');
                    },
                    success: function (response) {
                        if (response.status == 1) {
                            toastr.success(response.message);
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(function (key) {
                                toastr.error(errors[key][0]);
                            });
                        } else {
                            toastr.error('An error occurred while processing your request.', 'Error');
                        }
                    },
                    complete: function () {
                        $('#loader').addClass('hidden');
                    },
                });
            });

            $('select[name=status]').change(function () {
                if ($('input[name^="id"]:checked').length > 0) {
                    var action_type = $('select[name=status]').val();
                    if (action_type == 'delete') {
                        let action_route = `{{ route('admin.order.action') }}`;
                        $('#myForm').attr("action", action_route);
                    } else if (action_type == 'on_hold') {
                        let action_route = `{{ route('admin.order.on_hold') }}`;
                        $('#myForm').attr("action", action_route);
                    } else if (action_type == 'cancel') {
                        let action_route = `{{ route('admin.order.cancel') }}`;
                        $('#myForm').attr("action", action_route);
                    }
                    else if (action_type == 'rto') {
                        let action_route = `{{ route('admin.order.rto') }}`;
                        $('#myForm').attr("action", action_route);
                    }
                    else if (action_type == 'ndr') {
                        let action_route = `{{ route('admin.order.ndr') }}`;
                        $('#myForm').attr("action", action_route);
                    }
                    else if (action_type == 'refund') {
                        let action_route = `{{ route('admin.order.refund') }}`;
                        $('#myForm').attr("action", action_route);
                    }
                    else if (action_type == 'download') {
                        let action_route = `{{ route('admin.order.download') }}`;
                        $('#myForm').attr("action", action_route);
                    }
                    else if (action_type == 'downloadupdate') {
                        let action_route = `{{ route('admin.order.downloadupdate') }}`;
                        $('#myForm').attr("action", action_route);
                    }
                    else if (action_type == 'updateweight') {
                        let action_route = `{{ route('admin.order.updateweightto500') }}`;
                        $('#myForm').attr("action", action_route);
                    }
                    $('#loader').removeClass('hidden')
                    $('#myForm').submit();
                } else {
                    toastr.error('Select atleast one Order');
                    $('select[name=status]').val('');
                }
            });

            $('.expand').on('click', function () {
                if ($(this).text() == 'Filters>>') {
                    $(this).text('Filters<<');
                } else {
                    $(this).text('Filters>>');
                }
                $('.show_more').slideToggle('fast');
            });
        })(jQuery);

    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('filter-toggle').addEventListener('click', function () {
            const filterSection = document.querySelector('.show_more');
            filterSection.style.display = filterSection.style.display === 'none' ? 'block' : 'none';
        });
    </script>

@endsection