@extends('admin.admin_layouts')
@section('admin_content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <div class="container-fluid">
        <!-- Page header section  -->
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                    <div class="row clearfix">
                        <div class="col-xl-5 col-md-5 col-sm-12">
                            <h2>Wallet Recharge Transactions</h2>
                        </div>
                        <div class="col-xl-7 col-md-9 col-sm-12 text-md-right hidden-xs">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-12">
                <div class="card pt-30">
                    <div class="card-body">
                        {{-- Filter Form --}}
                        <form action="{{ route('admin.payment.walletreport') }}" method="GET" class="mb-4">
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label class="font-weight-bold">From Date</label>
                                        <input type="date" name="start_date" class="form-control"
                                            value="{{ request('start_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label class="font-weight-bold">To Date</label>
                                        <input type="date" name="end_date" class="form-control"
                                            value="{{ request('end_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label class="font-weight-bold">User</label>
                                        <select name="user_id" class="form-control select2">
                                            <option value="">All Users</option>
                                            @foreach ($users as $u)
                                                <option value="{{ $u->id }}"
                                                    {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                                    {{ $u->name }} ({{ $u->company_name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-2">
                                        <label class="font-weight-bold">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="captured"
                                                {{ request('status') == 'captured' ? 'selected' : '' }}>Successful</option>
                                            <option value="refunded"
                                                {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>
                                                Failed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group mb-2">
                                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group mb-2">
                                        <a href="{{ route('admin.payment.walletreport') }}"
                                            class="btn btn-secondary btn-block">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered sorttableexceldate" id="dataTable" width="100%"
                                        cellspacing="0">
                                        <thead>
                                            <th>Created At</th>
                                            <th>Payment ID</th>
                                            <th>Method</th>
                                            <th>Currency</th>
                                            <th>Company</th>
                                            <th>User</th>

                                            <th>Amount (₹)</th>
                                            <th>Coupon Amount (₹)</th>
                                            <th>Status</th>
                                            <th>Refund</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($transactions as $transaction)
                                                @php
                                                    $paymentId = 'N/A';
                                                    $status = 'N/A';
                                                    $method = 'N/A';
                                                    $currency = 'INR';
                                                    $amount = 0;

                                                    // Use the new coupon_amount column directly
                                                    $coupon_bonus = $transaction->coupon_amount ?? 0;

                                                    // Safely extract Razorpay payment ID from tracking_info or remarks
                                                    if (
                                                        preg_match(
                                                            '/pay[a-zA-Z0-9]*_[a-zA-Z0-9_]+/',
                                                            $transaction->tracking_info,
                                                            $matches,
                                                        )
                                                    ) {
                                                        $paymentId = $matches[0];
                                                    } elseif (
                                                        preg_match(
                                                            '/pay[a-zA-Z0-9]*_[a-zA-Z0-9_]+/',
                                                            $transaction->remarks,
                                                            $matches,
                                                        )
                                                    ) {
                                                        $paymentId = $matches[0];
                                                    } elseif (
                                                        stripos($transaction->tracking_info, 'cod') !== false ||
                                                        stripos($transaction->remarks, 'cod') !== false
                                                    ) {
                                                        $method = 'COD';
                                                        $status = 'success';
                                                        $amount = $transaction->credit - $coupon_bonus; // Deduct bonus to get base amount
                                                    }

                                                    // Try to get data from payments table first
                                                    $paymentRec = null;
                                                    if ($paymentId !== 'N/A') {
                                                        $paymentRec = \App\Models\Admin\Payment::where(
                                                            'r_payment_id',
                                                            $paymentId,
                                                        )->first();
                                                    }

                                                    if ($paymentRec) {
                                                        $amount = $paymentRec->amount;
                                                        $method = $paymentRec->method ?: 'N/A';
                                                        $currency = $paymentRec->currency ?: 'INR';

                                                        // Extract status from payment json
                                                        $pJson = json_decode($paymentRec->json_response, true);
                                                        if (is_array($pJson)) {
                                                            $data = $pJson["\0*\0attributes"] ?? $pJson;
                                                            $status = $data['status'] ?? 'captured';
                                                        } else {
                                                            $status = 'captured';
                                                        }

                                                        // If for some reason the transaction column is 0 but payment record has it, fallback
                                                        if ($coupon_bonus == 0 && $paymentRec->coupon_amount > 0) {
                                                            $coupon_bonus = $paymentRec->coupon_amount;
                                                        }
                                                    } else {
                                                        // Fallback to remarks JSON if no payment record found
                                                        $jsonData = json_decode($transaction->remarks, true);
                                                        if (is_array($jsonData)) {
                                                            $attrKey = "\0*\0attributes";
                                                            $data = isset($jsonData[$attrKey])
                                                                ? $jsonData[$attrKey]
                                                                : $jsonData;
                                                            $status = $data['status'] ?? $status;
                                                            if ($paymentId === 'N/A') {
                                                                $paymentId = $data['id'] ?? 'N/A';
                                                            }
                                                            if ($method === 'N/A') {
                                                                $method = $data['method'] ?? 'N/A';
                                                            }
                                                            $currency = $data['currency'] ?? $currency;
                                                            if ($amount == 0 && isset($data['amount'])) {
                                                                $amount = $data['amount'] / 100;
                                                            }
                                                        }
                                                    }

                                                    // Fallback amount if both DB and JSON didn't give amount
                                                    if ($amount == 0 && $transaction->credit > 0) {
                                                        $amount = $transaction->credit - $coupon_bonus;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $transaction->created_at }}</td>
                                                    <td>{{ $paymentId }}</td>
                                                    <td>{{ strtoupper($method) }}</td>
                                                    <td>{{ strtoupper($currency) }}</td>
                                                    {{-- <td>
                                                        @if ($transaction->admin && $transaction->admin->name)
                                                            <strong>{{ $transaction->admin->name }}</strong><br>
                                                        @endif
                                                        @if ($transaction->admin && $transaction->admin->company_name)
                                                            <span
                                                                class="text-muted">{{ $transaction->admin->company_name }}</span><br>
                                                        @endif
                                                        <small>{{ $transaction->admin->email ?? '' }}</small>
                                                    </td> --}}
                                                    <td>{{ $transaction->admin->company_name }}</td>
                                                    <td>{{ $transaction->admin->name }}</td>
                                                    <td>₹{{ number_format($amount, 2) }}</td>
                                                    <td>
                                                        @if ($coupon_bonus > 0)
                                                            <span
                                                                class="text-success">+₹{{ number_format($coupon_bonus, 2) }}</span>
                                                        @else
                                                            <span class="text-muted">₹0.00</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $statusString = strtolower($status);
                                                            if (
                                                                in_array($statusString, [
                                                                    'captured',
                                                                    'success',
                                                                    'paid',
                                                                    'authorized',
                                                                ])
                                                            ) {
                                                                $msg = 'Payment Successful';
                                                                $badgeClass = 'badge-success';
                                                            } elseif ($statusString === 'refunded') {
                                                                $msg = 'Refund Processed';
                                                                $badgeClass = 'badge-info';
                                                            } elseif (in_array($statusString, ['failed', 'failure'])) {
                                                                $msg = 'Payment Failed';
                                                                $badgeClass = 'badge-danger';
                                                            } else {
                                                                $msg = 'Payment Processing';
                                                                $badgeClass = 'badge-warning';
                                                            }
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }} status-badge"
                                                            id="status-{{ $paymentId }}">{{ $msg }}</span>
                                                    </td>
                                                    <td>
                                                        @if ($paymentId && $paymentId !== 'N/A')
                                                            <button class="btn btn-sm btn-info check-refund-btn"
                                                                data-payment-id="{{ $paymentId }}"
                                                                data-url="{{ route('admin.payment.refund_check', $paymentId) }}">
                                                                Check Refund
                                                            </button>
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                </tr>
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

    {{-- Refund Result Modal --}}
    <div class="modal fade" id="refundModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Refund Details</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body" id="refundModalBody">
                    <div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            if ($('.select2').length) {
                $('.select2').select2({
                    placeholder: "Select a User",
                    allowClear: true,
                    width: '100%'
                });
            }
        });

        $(document).on('click', '.check-refund-btn', function() {
            var url = $(this).data('url');
            var paymentId = $(this).data('payment-id');

            $('#refundModalBody').html(
                '<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</div>');
            $('#refundModal').modal('show');

            $.ajax({
                url: url,
                type: 'GET',
                success: function(res) {
                    if (res.success) {
                        var statusMessage = res.message || "Payment Processing";
                        var badgeClass = "badge-warning";
                        if (res.status === "captured" || res.status === "authorized") {
                            statusMessage = "Payment Successful";
                            badgeClass = "badge-success";
                        } else if (res.status === "refunded") {
                            statusMessage = "Refund Processed";
                            badgeClass = "badge-info";
                        } else if (res.status === "failed") {
                            statusMessage = "Payment Failed";
                            badgeClass = "badge-danger";
                        }

                        // Auto update the badge in the table
                        var paymentId = res.payment_id;
                        var $badge = $('#status-' + paymentId);
                        if ($badge.length) {
                            $badge.removeClass().addClass('badge ' + badgeClass + ' status-badge').text(
                                statusMessage);
                        }

                        var refundColor = res.amount_refunded > 0 ? 'text-danger' : 'text-success';
                        var html = '<table class="table table-bordered">' +
                            '<tr><th>Payment ID</th><td>' + res.payment_id + '</td></tr>' +
                            '<tr><th>Status</th><td>' + statusMessage + ' (' + res.status +
                            ')</td></tr>' +
                            '<tr><th>Total Amount</th><td>₹' + res.amount + '</td></tr>' +
                            '<tr><th>Amount Refunded</th><td class="' + refundColor + '">₹' + res
                            .amount_refunded + '</td></tr>' +
                            '<tr><th>Refund Status</th><td>' + (res.refund_status || 'None') +
                            '</td></tr>' +
                            '<tr><th>Result</th><td><strong>' + res.refund_label +
                            '</strong></td></tr>' +
                            '</table>';
                        $('#refundModalBody').html(html);
                    } else {
                        $('#refundModalBody').html('<div class="alert alert-danger">' + res.message +
                            '</div>');
                    }
                },
                error: function(xhr) {
                    var msg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.';
                    $('#refundModalBody').html('<div class="alert alert-danger">' + msg + '</div>');
                }
            });
        });
    </script>
@endsection
