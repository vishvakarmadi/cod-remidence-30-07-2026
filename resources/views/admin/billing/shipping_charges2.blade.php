@extends('admin.admin_layouts')
@section('admin_content')
<style>

.canvasjs-chart-credit
{
    display: none !important;
}
 </style> 
  <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>


<div id="navbar-animmenu">
    <ul class="show-dropdown main-navbar">
        <div class="hori-selector" style="margin-left: 20px;">
            <div class="left"></div>
            <div class="right"></div>
        </div>
        <li class="active">
            <a href="{{ route('admin.shipping_charges') }}" data-id="shipping_charges">Shipping charges</a>
        </li>
        <li>
            <a href="{{ route('admin.invoices') }}" data-id="invoices">Invoices</a>
        </li>
        <li>
            <a href="{{ route('admin.credit_notes') }}" data-id="credit-notes">Credit Notes</a>
        </li>
        <li>
            <a href="{{ route('admin.wallet_transaction') }}" data-id="wallet_transaction">Wallet Transaction</a>
        </li>
    </ul>
</div>
<div class="card top_report card mt-30 Overview hide">
                    Please wait.. Data is loading
                </div>

                <div class="card mt-30 channel hide">
                    Please wait.. Data is loading
                </div>

<div class="card mt-30 shipping_charges">
    <div class="card-header">
        <h5 class="card-title mb-0">Shipping charges (Forward Journey)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover dataTable js-exportable">
                <thead>
                    <tr>
                        <th>Order id</th>
                        <th>Customer Info</th>
                        <th>Tracking Info</th>
                        <th>Zone</th>
                        <th>Charges Applied(Forward)</th>
                        <th>Dim. & Wt. Enter</th>
                        <th>Order Assign Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order_fwd as $row)
                    <tr>
                        <td class="text-center date-column">
                            <a href="{{ route('admin.order.detail', $row->id) }}">{{ $row->order_id }}</a>
                        </td>
                        <td>{{ $row->ship_phone }}</td>
                        <td>
                            @if ($row->ship_courier_id)
                                {{ $row->tracking_info }} | {{ $couriers[$row->ship_courier_id]['name'] }}
                            @endif
                        </td>
                        <td>{{ $row->zone }}</td>
                        <td>{{ $row->shipping_courier_cost }}</td>
                        <td>Dim: {{ $row->length }}x{{ $row->breadth }}x{{ $row->height }}<br>
                            Wt: {{ $row->weight }}</td>
                        <td>{{ $row->shipped_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>
    </div>
    
    @if (count($order_bwd) != '0')
    <div class="card-header">
        <h5 class="card-title mb-0">Shipping charges (Backward Journey)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover dataTable js-exportable">
                <thead>
                    <tr>
                        <th>Order id</th>
                        <th>Customer Info</th>
                        <th>Tracking Info</th>
                        <th>Zone</th>
                        <th>Charges Applied(RTO)</th>
                        <th>Dim. & Wt. Enter</th>
                        <th>Order Assign Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order_bwd as $row)
                    <tr>
                        <td class="text-center">
                            <a href="{{ route('admin.order.detail', $row->id) }}">{{ $row->order_id }}</a>
                        </td>
                        <td>{{ $row->ship_phone }}</td>
                        <td>
                            @if ($row->ship_courier_id)
                                {{ $row->tracking_info }} | {{ $couriers[$row->ship_courier_id]['name'] }}
                            @endif
                        </td>
                        <td>{{ $row->zone }}</td>
                        <td>{{ $row->shipping_courier_cost }}</td>
                        <td>Dim: {{ $row->length }}x{{ $row->breadth }}x{{ $row->height }}<br>
                            Wt: {{ $row->weight }}</td>
                        <td>{{ $row->shipped_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>
    </div>
    @endif
</div>

@endsection


