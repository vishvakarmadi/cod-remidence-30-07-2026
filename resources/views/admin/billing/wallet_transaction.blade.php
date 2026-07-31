@extends('admin.admin_layouts')
@section('admin_content')



    <!--<div id="navbar-animmenu">
        <ul class="show-dropdown main-navbar">
            <div class="hori-selector" style="margin-left: 20px;">
                <div class="left"></div>
                <div class="right"></div>
            </div>
             <li class="{{ request()->routeIs('admin.shipping_charges') ? 'active' : '' }}">
                <a href="{{ route('admin.shipping_charges') }}" data-id="shipping_charges">Shipping charges</a>
            </li>
            <li class="{{ request()->routeIs('admin.invoices') ? 'active' : '' }}">
                <a href="{{ route('admin.invoices') }}" data-id="invoices">Invoices</a>
            </li>
            <li class="{{ request()->routeIs('admin.credit_notes') ? 'active' : '' }}">
                <a href="{{ route('admin.credit_notes') }}" data-id="credit-notes">Credit Notes</a>
            </li>
            <li class="{{ request()->routeIs('admin.wallet_transaction') ? 'active' : '' }}">
                <a href="{{ route('admin.wallet_transaction') }}" data-id="wallet_transaction">Wallet Transaction</a>
            </li>
        </ul>
    </div>-->

    <div class="card mt-30 wallet_transaction">
        <div class="card-header">
            <h5 class="card-title mb-0">Wallet Transaction</h5>
        </div>
        <form action="{{ route('admin.wallet_transaction') }}" method="GET" style="padding: 16px;">
            <div class="row">
                <div class="form-group col-md-8">
                    <label>Select Date Range</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input class="form-control" type="date" name="start_date"
                                value="{{ $re_data['start_date'] ? explode(' ', $re_data['start_date'])[0] : '' }}"
                                id="_1">
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" type="date" name="end_date"
                                value="{{ $re_data['end_date'] ? explode(' ', $re_data['end_date'])[0] : '' }}"
                                id="_2">
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label>Search (AWB / Remarks)</label>
                    <input class="form-control" type="text" name="search" value="{{ $re_data['search'] ?? '' }}"
                        placeholder="Enter AWB or Remarks">
                </div>
            </div>
            <div class="row">
                <x-button size="col-lg-3" type="submit" name="Search" />
                <div class="col-lg-3">
                    <button type="button" id="exportExcelBtn" class="btn btn-secondary w-100">
                        <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;Export to Excel
                    </button>
                </div>
            </div>
        </form>
        <hr>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover dataTable js-exportable sorttableexceldatesortsecond">
                    <thead>
                        <tr>
                            @if (Auth::guard('admin')->user()->role_id == 1 || Auth::guard('admin')->user()->role_id == 2)
                                <th>User/Company</th>
                            @endif
                            <th>Date & Time</th>
                            <th>Tracking ID</th>
                            <th>AWB</th>
                            <th>Credit</th>
                            <th>Debit</th>
                            <th>Closing Blc</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction as $row)
                            @php
                                $isExtraWeightTx = (str_contains(strtolower($row->remarks), 'extra weight') || str_contains(strtolower($row->remarks), 'weight charge') || str_contains(strtolower($row->remarks), 'weight reconciliation'));
                            @endphp
                            <tr @if($isExtraWeightTx) style="background-color: #fff8f0;" @endif>
                                @if (Auth::guard('admin')->user()->role_id == 1 || Auth::guard('admin')->user()->role_id == 2)
                                    <td>
                                        {{ $row->admin->name ?? '' }}<br>
                                        <small class="text-muted">{{ $row->admin->company_name ?? '' }}</small>
                                    </td>
                                @endif
                                <td class="date-column"><span class="fa fa-calendar"></span>&nbsp;
                                    {{ \Carbon\Carbon::parse($row->created_at)->format('d M, Y') }}<br>
                                    <span class="fa fa-clock-o"></span>&nbsp;
                                    {{ \Carbon\Carbon::parse($row->created_at)->format('H:i') }}
                                </td>
                                <td class="text-center">
                                    {{ $row->id }}
                                </td>
                                <td class="anchor-column">
                                    @if ($row->order_id != 0)
                                        <a href="{{ route('admin.order.detail', $row->order_id) }}">
                                            {{ $row->awb }}
                                        </a>
                                    @endif
                                </td>
                                <td style="color:green">{{ $row->credit }}</td>
                                <td style="color:red">
                                    @if ($row->debit != 0)
                                        - {{ $row->debit }}
                                    @else
                                        {{ $row->debit }}
                                    @endif
                                </td>
                                <td>{{ $row->closing_blc }}</td>
                                <td>
                                    @if ($isExtraWeightTx)
                                        <span class="badge text-white" style="background:#ff9800; font-size: 10px; margin-bottom: 4px; display: inline-block;">Weight Reconciliation</span><br>
                                        <strong>{{ $row->remarks }}</strong>
                                    @else
                                        @if (strlen($row->remarks) > 45)
                                            {{ substr($row->remarks, 0, 44) }} ...
                                        @else
                                            {{ $row->remarks }}
                                        @endif
                                    @endif

                                    @if ($isExtraWeightTx && $row->order && floatval($row->order->extra_weight) > 0)
                                        @php
                                            $rawStatus = $row->order->getRawOriginal('extra_weight_status');
                                        @endphp
                                        <div style="margin-top: 6px; padding: 6px 10px; border-left: 3px solid #ff9800; background-color: #fffaf5; font-size: 11px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                            <div style="margin-bottom: 4px;">
                                                @if (in_array($rawStatus, [2, 3]))
                                                    <span class="badge text-white bg-success" style="font-size: 9px; padding: 2px 4px;">Reconciled & Debited</span>
                                                @elseif (in_array($rawStatus, [1, 5]))
                                                    <span class="badge text-white bg-warning" style="font-size: 9px; padding: 2px 4px; background-color: #ff9800 !important;">Pending Reconciliation</span>
                                                @elseif ($rawStatus == 4)
                                                    <span class="badge text-white bg-info" style="font-size: 9px; padding: 2px 4px;">Closed (No Debit)</span>
                                                @else
                                                    {!! $row->order->extra_weight_status !!}
                                                @endif
                                            </div>
                                            <span class="text-danger" style="font-weight:600;">
                                                Extra Wt: {{ number_format(floatval($row->order->extra_weight) / 1000 - floatval($row->order->shipping_courier_weight), 5) }} kg
                                            </span><br>
                                            <small class="text-muted" style="display:block; margin: 1px 0 3px 0;">
                                                Applied: {{ number_format(floatval($row->order->extra_weight) / 1000, 3) }} kg | Entered: {{ number_format(floatval($row->order->shipping_courier_weight), 3) }} kg
                                            </small>
                                            <span class="text-primary" style="font-weight:600;">
                                                @if (in_array($rawStatus, [2, 3]))
                                                    Wt Charge: ₹{{ number_format(floatval($row->order->extra_weight_cost), 2) }}
                                                @else
                                                    Est. Wt Charge: ₹{{ number_format(floatval($row->order->extra_weight_cost), 2) }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card mt-30 invoices hide"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('exportExcelBtn').addEventListener('click', function(e) {
                e.preventDefault();
                var startDate = document.getElementById('_1').value;
                var endDate = document.getElementById('_2').value;
                var search = document.querySelector('input[name="search"]').value;
                var url = "{{ route('admin.wallet_transaction.export') }}";
                var params = [];
                if (startDate && endDate) {
                    params.push("start_date=" + encodeURIComponent(startDate));
                    params.push("end_date=" + encodeURIComponent(endDate));
                }
                if (search) {
                    params.push("search=" + encodeURIComponent(search));
                }
                if (params.length > 0) {
                    url += "?" + params.join("&");
                }
                window.location.href = url;
            });
        });
    </script>
@endsection
