@extends('admin.admin_layouts')
@section('admin_content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
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
                                            <th>Actions</th>
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
                                                         <div class="btn-group" role="group">
                                                             @if ($paymentId && $paymentId !== 'N/A')
                                                                 <button class="btn btn-sm btn-info check-refund-btn"
                                                                     data-payment-id="{{ $paymentId }}"
                                                                     data-url="{{ route('admin.payment.refund_check', $paymentId) }}"
                                                                     title="Check Refund">
                                                                     Check Refund
                                                                 </button>
                                                             @endif

                                                             {{-- Receipt Button --}}
                                                             <button class="btn btn-sm btn-success view-receipt-btn ml-1"
                                                                 data-id="{{ $transaction->id }}"
                                                                 data-date="{{ $transaction->created_at }}"
                                                                 data-payment-id="{{ $paymentId }}"
                                                                 data-method="{{ strtoupper($method) }}"
                                                                 data-company="{{ $transaction->admin->company_name ?? 'N/A' }}"
                                                                 data-user="{{ $transaction->admin->name ?? 'N/A' }}"
                                                                 data-email="{{ $transaction->admin->email ?? '' }}"
                                                                 data-mobile="{{ $transaction->admin->mobile ?? '' }}"
                                                                 data-amount="{{ number_format($amount, 2, '.', '') }}"
                                                                 data-coupon="{{ number_format($coupon_bonus, 2, '.', '') }}"
                                                                 data-total="{{ number_format($amount + $coupon_bonus, 2, '.', '') }}"
                                                                 data-status="{{ $msg }}"
                                                                 data-badge-class="{{ $badgeClass }}"
                                                                 title="View & Print Receipt">
                                                                 <i class="fa fa-file-text-o"></i> Receipt
                                                             </button>

                                                             {{-- Edit Button (Only Admin) - Commented out
                                                             @if (auth()->guard('admin')->user()->role_id == '1')
                                                                 <button class="btn btn-sm btn-warning edit-recharge-btn ml-1 text-white"
                                                                     data-id="{{ $transaction->id }}"
                                                                     data-user="{{ $transaction->admin->name ?? 'N/A' }} ({{ $transaction->admin->company_name ?? 'N/A' }})"
                                                                     data-amount="{{ number_format($amount, 2, '.', '') }}"
                                                                     data-coupon="{{ number_format($coupon_bonus, 2, '.', '') }}"
                                                                     data-remarks="{{ is_array(json_decode($transaction->remarks, true)) ? (json_decode($transaction->remarks, true)['admin_edit_remark'] ?? '') : $transaction->remarks }}"
                                                                     title="Edit Recharge (Admin Only)">
                                                                     <i class="fa fa-edit"></i> Edit
                                                                 </button>
                                                             @endif
                                                             --}}
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

    {{-- Receipt Modal --}}
    <div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 850px;">
            <div class="modal-content" id="receiptModalContent">
                <div class="modal-header bg-light py-3">
                    <h5 class="modal-title font-weight-bold text-primary mb-0">
                        <i class="fa fa-receipt mr-2"></i> Wallet Recharge Payment Receipt
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3" id="receiptModalBody" style="max-height: 75vh; overflow-y: auto;">
                    <!-- Receipt content dynamically generated -->
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-success" onclick="downloadReceiptPDF()">
                        <i class="fa fa-download mr-1"></i> Download PDF
                    </button>
                    <button type="button" class="btn btn-primary" onclick="printReceipt()">
                        <i class="fa fa-print mr-1"></i> Print Receipt
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Recharge Modal (Admin Only) - Commented out
    @if (auth()->guard('admin')->user()->role_id == '1')
    <div class="modal fade" id="editRechargeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.payment.walletreport.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="transaction_id" id="edit_transaction_id">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fa fa-edit"></i> Edit Wallet Recharge Transaction
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">User / Company</label>
                            <input type="text" id="edit_user_name" class="form-control bg-light" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Recharge Base Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="amount" id="edit_amount" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Coupon Bonus Amount (₹)</label>
                            <input type="number" step="0.01" min="0" name="coupon_amount" id="edit_coupon_amount" class="form-control" value="0.00">
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Remarks / Admin Note</label>
                            <textarea name="remarks" id="edit_remarks" class="form-control" rows="3" placeholder="Enter remarks..."></textarea>
                        </div>
                        <div class="alert alert-info py-2 mb-0">
                            <small><i class="fa fa-info-circle"></i> Updating these amounts will automatically adjust the user's wallet balance by the difference.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning font-weight-bold text-white">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    --}}

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

        var currentReceiptId = '';

        // Receipt Modal Trigger
        $(document).on('click', '.view-receipt-btn', function() {
            var id = $(this).data('id');
            currentReceiptId = id;
            var date = $(this).data('date');
            var paymentId = $(this).data('payment-id');
            var method = $(this).data('method');
            var company = $(this).data('company');
            var user = $(this).data('user');
            var email = $(this).data('email');
            var mobile = $(this).data('mobile');
            var amount = parseFloat($(this).data('amount')).toFixed(2);
            var coupon = parseFloat($(this).data('coupon')).toFixed(2);
            var total = parseFloat($(this).data('total')).toFixed(2);
            var status = $(this).data('status');
            var badgeClass = $(this).data('badge-class');

            var couponRow = '';
            if (parseFloat(coupon) > 0) {
                couponRow = '<tr>' +
                    '<td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0;"><i class="fa fa-gift text-success mr-2"></i> Coupon Cashback / Bonus Credit</td>' +
                    '<td class="text-right text-success font-weight-bold" style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0;">+₹' + coupon + '</td>' +
                    '</tr>';
            }

            var emailHtml = email ? '<div><small class="text-muted"><i class="fa fa-envelope-o mr-1"></i> ' + email + '</small></div>' : '';
            var mobileHtml = mobile ? '<div><small class="text-muted"><i class="fa fa-phone mr-1"></i> ' + mobile + '</small></div>' : '';

            var receiptHtml = 
            '<div class="receipt-container p-3" id="printableReceiptArea" style="background:#ffffff; border-radius:8px; border:1px solid #e2e8f0; font-family:\'Segoe UI\', Roboto, Arial, sans-serif; color:#1e293b;">' +
                // Top Header Bar
                '<div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3" style="border-color:#e2e8f0!important;">' +
                    '<div>' +
                        '<h3 class="font-weight-bold mb-0" style="color:#0f172a; letter-spacing:-0.5px;"><i class="fa fa-paper-plane text-primary mr-2"></i>HYLOSHIP</h3>' +
                        '<small class="text-muted font-weight-bold" style="letter-spacing:1px; font-size:10px; text-transform:uppercase;">Logistics & Freight Solutions</small>' +
                    '</div>' +
                    '<div class="text-right">' +
                        '<span class="badge badge-primary px-3 py-1 mb-1" style="font-size:11px; letter-spacing:0.5px; border-radius:20px;">OFFICIAL RECHARGE RECEIPT</span>' +
                        '<div class="font-weight-bold" style="font-size:14px; color:#334155;">Receipt #' + id + '</div>' +
                        '<small class="text-muted"><i class="fa fa-calendar mr-1"></i> Date: ' + date + '</small>' +
                    '</div>' +
                '</div>' +

                // Billed To & Payment Info Cards
                '<div class="row mb-3">' +
                    '<div class="col-md-6 col-6">' +
                        '<div class="p-3 rounded" style="background:#f8fafc; border:1px solid #f1f5f9;">' +
                            '<div class="text-uppercase font-weight-bold mb-2" style="font-size:11px; color:#64748b; letter-spacing:0.5px;">BILLED TO (CUSTOMER)</div>' +
                            '<h6 class="font-weight-bold mb-1" style="color:#0f172a;">' + user + '</h6>' +
                            '<div class="text-secondary font-weight-bold mb-1" style="font-size:13px;">' + company + '</div>' +
                            emailHtml +
                            mobileHtml +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-6 col-6">' +
                        '<div class="p-3 rounded" style="background:#f8fafc; border:1px solid #f1f5f9;">' +
                            '<div class="text-uppercase font-weight-bold mb-2" style="font-size:11px; color:#64748b; letter-spacing:0.5px;">PAYMENT DETAILS</div>' +
                            '<div class="mb-1" style="font-size:13px;"><strong>Transaction Ref:</strong> <span class="text-monospace text-dark">' + paymentId + '</span></div>' +
                            '<div class="mb-1" style="font-size:13px;"><strong>Payment Method:</strong> ' + method + '</div>' +
                            '<div style="font-size:13px;"><strong>Status:</strong> <span class="badge ' + badgeClass + ' px-2 py-1">' + status + '</span></div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +

                // Itemized Table
                '<div class="table-responsive mb-3">' +
                    '<table class="table table-bordered mb-0" style="border-color:#e2e8f0;">' +
                        '<thead style="background:#0f172a; color:#ffffff;">' +
                            '<tr>' +
                                '<th style="font-size:12px; font-weight:600; letter-spacing:0.5px; border:none; padding:10px 14px;">DESCRIPTION</th>' +
                                '<th class="text-right" style="font-size:12px; font-weight:600; letter-spacing:0.5px; border:none; padding:10px 14px;">AMOUNT (₹)</th>' +
                            '</tr>' +
                        '</thead>' +
                        '<tbody>' +
                            '<tr>' +
                                '<td style="padding:12px 14px; border-bottom:1px solid #e2e8f0;">Wallet Recharge Base Amount</td>' +
                                '<td class="text-right font-weight-bold" style="padding:12px 14px; border-bottom:1px solid #e2e8f0;">₹' + amount + '</td>' +
                            '</tr>' +
                            couponRow +
                        '</tbody>' +
                    '</table>' +
                '</div>' +

                // Total Highlight Box
                '<div class="p-3 rounded d-flex justify-content-between align-items-center mb-3" style="background:#f0fdf4; border:1px solid #bbf7d0;">' +
                    '<div>' +
                        '<div class="text-uppercase font-weight-bold text-success" style="font-size:11px; letter-spacing:0.5px;">Total Amount Credited</div>' +
                        '<small class="text-muted">Added to User Wallet Balance</small>' +
                    '</div>' +
                    '<div class="font-weight-bold text-success" style="font-size:22px;">₹' + total + '</div>' +
                '</div>' +

                // Footer Disclaimer & Stamp
                '<div class="pt-2 border-top text-center text-muted" style="font-size:11px; border-color:#e2e8f0!important;">' +
                    '<div class="mb-1"><i class="fa fa-shield text-success mr-1"></i> <strong>Verified Electronic Payment Receipt</strong></div>' +
                    '<div>This is a system-generated document issued by Hyloship Logistics. No physical signature is required.</div>' +
                    '<div class="mt-1 text-secondary"><small>Support: support@hyloship.com | Web: www.hyloship.com</small></div>' +
                '</div>' +
            '</div>';

            $('#receiptModalBody').html(receiptHtml);
            $('#receiptModal').modal('show');
        });

        // Direct PDF Download Function
        function downloadReceiptPDF() {
            var element = document.getElementById('printableReceiptArea');
            if (!element) return;

            var opt = {
                margin:       0.3,
                filename:     'Wallet_Recharge_Receipt_' + currentReceiptId + '.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save();
        }

        // Print Receipt via Hidden Iframe
        function printReceipt() {
            var printContents = document.getElementById('printableReceiptArea').outerHTML;
            var iframe = document.createElement('iframe');
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0px';
            iframe.style.height = '0px';
            iframe.style.border = 'none';
            document.body.appendChild(iframe);

            var doc = iframe.contentWindow.document;
            doc.open();
            doc.write('<html><head><title>Wallet Recharge Receipt</title>');
            doc.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">');
            doc.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">');
            doc.write('<style>body { font-family: sans-serif; padding: 20px; background: #fff; } .badge { font-size: 12px; padding: 5px 10px; }</style>');
            doc.write('</head><body>');
            doc.write(printContents);
            doc.write('</body></html>');
            doc.close();

            setTimeout(function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                setTimeout(function() {
                    if (document.body.contains(iframe)) {
                        document.body.removeChild(iframe);
                    }
                }, 2000);
            }, 300);
        }

        // Edit Recharge Modal Trigger (Admin Only)
        $(document).on('click', '.edit-recharge-btn', function() {
            var id = $(this).data('id');
            var user = $(this).data('user');
            var amount = $(this).data('amount');
            var coupon = $(this).data('coupon');
            var remarks = $(this).data('remarks');

            $('#edit_transaction_id').val(id);
            $('#edit_user_name').val(user);
            $('#edit_amount').val(amount);
            $('#edit_coupon_amount').val(coupon);
            $('#edit_remarks').val(remarks);

            $('#editRechargeModal').modal('show');
        });
    </script>
@endsection
