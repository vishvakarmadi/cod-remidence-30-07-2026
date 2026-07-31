@php
    $admin = auth()->guard('admin')->user();
    $ls = \App\Models\LabelSetting::where('user_id', $admin->id)->first();
    $general_setting = \DB::table('general_settings')->where('company_id', $admin->company_id)->first();
    $logo_path = $ls && $ls->logo ? $ls->logo : (isset($general_setting->logo) ? $general_setting->logo : null);

    // Group orders or get unique sellers
    $sellerNames = $orders->pluck('user_id')->unique()->implode(', ');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courier Pickup Manifest - {{ \Carbon\Carbon::now()->format('d-M-Y') }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 13px;
            color: #333;
            background: #fff;
            padding: 20px;
            line-height: 1.4;
        }

        /* Screen Navigation / Print Bar */
        .no-print-bar {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-back {
            color: #495057;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-back:hover {
            color: #212529;
        }

        .btn-print {
            background: #0d6efd;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.15s ease-in-out;
        }

        .btn-print:hover {
            background: #0b5ed7;
        }

        /* Manifest Container */
        .manifest-container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            border: 1px solid #dee2e6;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            background: #fff;
        }

        /* Header section */
        .manifest-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-img {
            max-height: 55px;
            max-width: 180px;
            object-fit: contain;
        }

        .header-title-area h1 {
            font-size: 20px;
            font-weight: 700;
            color: #111;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .header-title-area p {
            font-size: 12px;
            color: #6c757d;
        }

        .header-right {
            text-align: right;
            font-size: 13px;
            line-height: 1.6;
        }

        .header-right strong {
            color: #111;
        }

        /* Manifest Table */
        .manifest-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .manifest-table th,
        .manifest-table td {
            border: 1px solid #333;
            padding: 10px 8px;
            text-align: left;
            font-size: 12px;
        }

        .manifest-table th {
            background-color: #f1f3f5;
            font-weight: 700;
            color: #111;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.3px;
        }

        .manifest-table tr:nth-child(even) td {
            background-color: #fafbfc;
        }

        .col-sno {
            width: 5%;
            text-align: center;
        }

        .col-orderno {
            width: 15%;
        }

        .col-awb {
            width: 20%;
            font-weight: 600;
        }

        .col-courier {
            width: 15%;
        }

        .col-amount {
            width: 15%;
        }

        .col-boyno {
            width: 15%;
        }

        .col-signature {
            width: 15%;
        }

        /* CSS Counter for sequential numbering of active rows */
        .manifest-table tbody {
            counter-reset: rowNumber;
        }

        .manifest-table tbody tr:not(.exclude-row) {
            counter-increment: rowNumber;
        }

        .manifest-table tbody tr:not(.exclude-row) td.col-sno::before {
            content: counter(rowNumber);
        }

        /* Checkbox styling */
        .col-select {
            width: 40px;
            text-align: center;
        }

        .col-select input[type="checkbox"] {
            transform: scale(1.25);
            cursor: pointer;
        }

        /* Row exclusion styling */
        tr.exclude-row {
            opacity: 0.35;
            background-color: #ffeef0 !important;
        }

        tr.exclude-row td {
            text-decoration: line-through;
            color: #888;
        }

        tr.exclude-row td.col-select {
            text-decoration: none;
        }

        /* Sign-off Section */
        .manifest-summary {
            display: flex;
            justify-content: space-between;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 35px;
            font-size: 13px;
        }

        .manifest-sign-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 20px;
        }

        .sign-box {
            border: 1px dashed #6c757d;
            padding: 20px;
            border-radius: 6px;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sign-title {
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .sign-line {
            margin-top: auto;
            border-top: 1px solid #999;
            padding-top: 5px;
            text-align: center;
            font-size: 11px;
            color: #495057;
        }

        /* Footer disclaimer */
        .manifest-footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }

        /* Print styles */
        @media print {
            body {
                padding: 0;
                color: #000;
            }

            .no-print-bar {
                display: none !important;
            }

            .no-print,
            th.no-print,
            td.no-print,
            .col-select,
            input[type="checkbox"] {
                display: none !important;
            }

            tr.exclude-row {
                display: none !important;
            }

            .manifest-container {
                border: none;
                padding: 0;
                box-shadow: none;
            }

            .manifest-table th {
                background-color: #f1f3f5 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .manifest-table tr:nth-child(even) td {
                background-color: transparent !important;
            }

            @page {
                size: portrait;
                margin: 1.5cm;
            }
        }
    </style>
    <style media="print">
        .no-print,
        th.no-print,
        td.no-print,
        .col-select,
        #select-all-orders,
        .order-checkbox,
        input[type="checkbox"] {
            display: none !important;
            width: 0 !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }
    </style>
</head>

<body>

    <!-- Top Action Bar for Screen view -->
    <div class="no-print-bar">
        <a href="javascript:history.back()" class="btn-back">
            <span>&#8592;</span> Back to Orders
        </a>
        <div style="display: flex; align-items: center; gap: 15px;">
            <span
                style="font-size: 13px; color: #4b5563; font-weight: 500; background: #e5e7eb; padding: 6px 12px; border-radius: 6px;">
                💡 Uncheck orders to exclude them from the printed manifest
            </span>
            <button class="btn-print" onclick="window.print()">
                🖨️ Print Manifest
            </button>
        </div>
    </div>

    <!-- Printable Manifest Container -->
    <div class="manifest-container">

        <!-- Header -->
        <div class="manifest-header">
            <div class="header-left">
                @if ($logo_path)
                    <img src="{{ asset('public/uploads/' . $logo_path) }}" class="logo-img" alt="Logo">
                @endif
                <div class="header-title-area">
                    <h1>Courier Pickup Manifest</h1>
                    <p>Logistics Handover Verification Sheet</p>
                </div>
            </div>

            <div class="header-right">
                <div><strong>Seller Name:</strong> {{ $sellerNames ?: 'N/A' }}</div>
                <div><strong>Date & Time:</strong> {{ \Carbon\Carbon::now()->format('d-M-Y h:i A') }}</div>
            </div>
        </div>

        <!-- Orders Table -->
        <table class="manifest-table">
            <thead>
                <tr>
                    <th class="col-select no-print">
                        <input type="checkbox" id="select-all-orders" checked>
                    </th>
                    <th class="col-sno">S.No</th>
                    <th class="col-orderno">Order No</th>
                    <th class="col-awb">AWB No</th>
                    <th class="col-courier">Courier Name</th>
                    <th class="col-amount">Amount</th>
                    <th class="col-boyno">Pickup Boy No</th>
                    <th class="col-signature">Pickup Boy Sign</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalCodAmount = 0;
                    $codCount = 0;
                    $prepaidCount = 0;
                @endphp
                @foreach ($orders as $index => $order)
                    @php
                        $rawMode = $order->getRawOriginal('payment_mode');
                        $modeText = '';
                        if ($rawMode == 6) {
                            $modeText = 'COD';
                            $totalCodAmount += $order->custom_total;
                            $codCount++;
                        } elseif ($rawMode == 12) {
                            $modeText = 'Prepaid';
                            $prepaidCount++;
                        } else {
                            $stripped = strip_tags($order->payment_mode);
                            if (stripos($stripped, 'cod') !== false || stripos($stripped, 'c.o.d') !== false) {
                                $modeText = 'COD';
                                $totalCodAmount += $order->custom_total;
                                $codCount++;
                            } else {
                                $modeText = 'Prepaid';
                                $prepaidCount++;
                            }
                        }
                    @endphp
                    <tr>
                        <td class="col-select no-print">
                            <input type="checkbox" class="order-checkbox" checked
                                data-amount="{{ $order->custom_total }}" data-mode="{{ $modeText }}">
                        </td>
                        <td class="col-sno"></td> <!-- Filled automatically via CSS counter -->
                        <td>{{ $order->vendor_order_id }}</td>
                        <td>{{ $order->tracking_info ?: 'N/A' }}</td>
                        <td>{{ $couriers[$order->ship_courier_id]['name'] ?? 'N/A' }}</td>
                        <td>
                            ₹{{ number_format($order->custom_total, 2) }}
                            <span style="font-size: 9px; font-weight: bold; color: #666; margin-left: 2px;">
                                ({{ $modeText }})
                            </span>
                        </td>
                        <!-- Blank columns for manual input -->
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary Statistics -->
        <div class="manifest-summary">
            <div>
                <strong>Total Shipments:</strong> <span id="stat-total-shipments">{{ count($orders) }}</span>
                &nbsp;|&nbsp;
                <strong>COD Shipments:</strong> <span id="stat-cod-shipments">{{ $codCount }}</span> &nbsp;|&nbsp;
                <strong>Prepaid Shipments:</strong> <span id="stat-prepaid-shipments">{{ $prepaidCount }}</span>
            </div>
            <div>
                <strong>Total COD Amount to Collect:</strong> <span
                    id="stat-cod-amount">₹{{ number_format($totalCodAmount, 2) }}</span>
            </div>
        </div>

        <!-- Verification / Sign-off area -->
        <div class="manifest-sign-section">
            <div class="sign-box">
                <div class="sign-title">Logistics / Courier Partner Verification</div>
                <div style="font-size: 12px; color: #495057; line-height: 1.8;">
                    <div>Courier Boy Name: ____________________________</div>
                    <div style="margin-top: 5px;">Courier Boy Phone: ___________________________</div>
                </div>
                <div class="sign-line">Pickup Boy Signature & Date</div>
            </div>

            <div class="sign-box">
                <div class="sign-title">Seller Acknowledgment</div>
                <div style="font-size: 12px; color: #495057; line-height: 1.8;">
                    <div>Handed Over By: ____________________________</div>
                </div>
                <div class="sign-line">Seller Representative Signature & Date</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="manifest-footer">
            <p>This is a system generated document printed on behalf of the merchant.</p>
            @if (isset($general_setting->name))
                <p style="margin-top: 5px; font-weight: 600;">Powered by {{ $general_setting->name }}</p>
            @endif
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all-orders');
            const orderCheckboxes = document.querySelectorAll('.order-checkbox');

            // Recalculate and update summary totals
            function recalculateTotals() {
                let totalShipments = 0;
                let codCount = 0;
                let prepaidCount = 0;
                let totalCodAmount = 0;

                orderCheckboxes.forEach(function(cb) {
                    if (cb.checked) {
                        totalShipments++;
                        const mode = cb.getAttribute('data-mode');
                        const amount = parseFloat(cb.getAttribute('data-amount')) || 0;
                        if (mode === 'COD') {
                            codCount++;
                            totalCodAmount += amount;
                        } else {
                            prepaidCount++;
                        }
                    }
                });

                document.getElementById('stat-total-shipments').textContent = totalShipments;
                document.getElementById('stat-cod-shipments').textContent = codCount;
                document.getElementById('stat-prepaid-shipments').textContent = prepaidCount;
                document.getElementById('stat-cod-amount').textContent = '₹' + totalCodAmount.toLocaleString(
                    'en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
            }

            // Toggle individual row selection
            orderCheckboxes.forEach(function(cb) {
                cb.addEventListener('change', function() {
                    const tr = cb.closest('tr');
                    if (cb.checked) {
                        tr.classList.remove('exclude-row');
                    } else {
                        tr.classList.add('exclude-row');
                    }

                    // Update select-all checkbox state
                    const allChecked = Array.from(orderCheckboxes).every(c => c.checked);
                    const noneChecked = Array.from(orderCheckboxes).every(c => !c.checked);
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = (!allChecked && !noneChecked);

                    recalculateTotals();
                });
            });

            // Toggle all row selections
            selectAllCheckbox.addEventListener('change', function() {
                const checked = selectAllCheckbox.checked;
                orderCheckboxes.forEach(function(cb) {
                    cb.checked = checked;
                    const tr = cb.closest('tr');
                    if (checked) {
                        tr.classList.remove('exclude-row');
                    } else {
                        tr.classList.add('exclude-row');
                    }
                });
                recalculateTotals();
            });
        });
    </script>
</body>

</html>
