@extends('admin.admin_layouts')
@section('admin_content')
    @php
        $session = Auth::guard('admin')->user();
    @endphp
    <style>
        ul.a {
            list-style-type: circle;
        }

        .container {
            max-width: 900px;
            width: 100%;
            background-color: #fff;
            margin: auto;
            padding: 15px;
            box-shadow: 0 2px 20px #0001, 0 1px 6px #0001;
            border-radius: 5px;
            overflow-x: auto;
        }

        ._table {
            width: 100%;
            border-collapse: collapse;
        }

        ._table :is(th, td) {
            border: 1px solid #0002;
            padding: 8px 10px;
        }

        /* form field design start */
        .form_control {
            border: 1px solid #0002;
            background-color: transparent;
            outline: none;
            padding: 8px 12px;
            font-family: 1.2rem;
            width: 100%;
            color: #333;
            font-family: Arial, Helvetica, sans-serif;
            transition: 0.3s ease-in-out;
        }

        .form_control::placeholder {
            color: inherit;
            opacity: 0.5;
        }

        .form_control:is(:focus, :hover) {
            box-shadow: inset 0 1px 6px #0002;
        }

        /* form field design end */


        .success {
            background-color: #24b96f !important;
        }

        .warning {
            background-color: #ebba33 !important;
        }

        .primary {
            background-color: #259dff !important;
        }

        .secondery {
            background-color: #00bcd4 !important;
        }

        .danger {
            background-color: #ff5722 !important;
        }

        .action_container {
            display: inline-flex;
        }

        .action_container>* {
            border: none;
            outline: none;
            color: #fff;
            text-decoration: none;
            display: inline-block;
            padding: 8px 14px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        .action_container>*+* {
            border-left: 1px solid #fff5;
        }

        .action_container>*:hover {
            filter: hue-rotate(-20deg) brightness(0.97);
            transform: scale(1.05);
            border-color: transparent;
            box-shadow: 0 2px 10px #0004;
            border-radius: 2px;
        }

        .action_container>*:active {
            transition: unset;
            transform: scale(.95);
        }

        @media only screen and (max-width: 600px) {
            .responsive-iframe {
                top: 0;
                left: 0;
                bottom: 0;
                right: 0;
                width: 100%;
                height: 200px;
                border: none;
            }

            .rounded-circle {
                width: 48px !important;
            }

            ul {
                line-height: 162%;
                width: 220px;
                font-size: 11px;
            }
        }
    </style>


    <div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-8 col-sm-12">
                <h2>Kyc Details</h2>
            </div>
            <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                <ul class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item">{!! $user->kyc_status !!}</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-12">
            <div class="card pt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">Kyc Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="form-control-label" class="form-label">Company
                                Type:</label>
                            <input type="text" name="company_type" class="form-control" value="@if (@$kyc->company_type =='Other'){{ @$kyc->company_type_name }}@else{{ @$kyc->company_type }}@endif"
                                readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">Aadhaar Card Number: *</label>
                            <input type="text" name="pan_no" class="form-control" value="{{ @$kyc->aadhaar_no }}" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">PAN Card Number: *</label>
                            <input type="text" name="pan_no" class="form-control" value="{{ @$kyc->pan_no }}" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">GSTIN</label>
                            <input type="text" name="gst" class="form-control" value="{{ @$kyc->gst }}" readonly>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-12">
            <div class="card pt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">Bank Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="form-control-label" class="form-label">Name of Bank</label>
                            <input type="text" name="bank_name" class="form-control" value="{{ @$kyc->bank_name }}"
                                readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label" class="form-label">Account Holder Name</label>
                            <input type="text" name="beneficiary_name" class="form-control"
                                value="{{ @$kyc->beneficiary_name }}" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">Account Number*</label>
                            <input type="text" name="account_no" class="form-control" value="{{ @$kyc->account_no }}"
                                readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">IFSC Code *</label>
                            <input type="text" name="ifsc_code" class="form-control" value="{{ @$kyc->ifsc_code }}"
                                readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">Account Type *</label>
                            <input type="text" name="account_type" class="form-control" value="{{ @$kyc->account_type }}"
                                readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (@$kyc->doc_proof)
            <div class="col-12">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h3>GST Proof :</h3>
                            <iframe src="{{ asset('public/uploads/' . @$kyc->doc_proof) }}" frameborder="0"
                                style="width: 100%;height:400px;"></iframe>
                        </div>
                        <div class="col-md-3">
                            <h3>Aadhaar Card :</h3>
                            @if ($kyc->aadhaar)
                            <iframe src="{{ asset('public/uploads/'.@$kyc->aadhaar) }}" frameborder="0" style="width: 100%;height:400px;"></iframe>
                            @else
                            <iframe  frameborder="0" style="width: 100%;height:400px;"></iframe>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <h3>Pan Card :</h3>
                            <iframe src="{{ asset('public/uploads/' . @$kyc->pan) }}" frameborder="0"
                                style="width: 100%;height:400px;"></iframe>
                        </div>
                        <div class="col-md-3">
                            <h3>Blank Cheque :</h3>
                            <iframe src="{{ asset('public/uploads/' . @$kyc->cheque) }}" frameborder="0"
                                style="width: 100%;height:400px;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Select Parent User / Share Admin & KYC Approval</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.approve', @$kyc->user_id) }}" method="POST">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-md-6 form-group">
                                <label for="parent_id"><strong>Select Parent / Share Admin (Onboarder):</strong></label>
                                <select name="parent_id" id="parent_id" class="form-control">
                                    <option value="">-- Select Parent / Share Admin --</option>
                                    @if(isset($parents) && count($parents) > 0)
                                        @foreach($parents as $parent)
                                            <option value="{{ $parent->id }}" {{ (!empty($user->parent_id) && $parent->id == $user->parent_id) ? 'selected' : '' }}>
                                                {{ $parent->name }} {{ $parent->company_name ? ' ('.$parent->company_name.')' : '' }} [ID: {{ $parent->id }}]
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 form-group mt-3 d-flex" style="gap: 10px;">
                                <button type="submit" name="approve" class="btn btn-success flex-fill p-2" value="2">
                                    <i class="fa fa-check"></i> Approve KYC & Save Parent ID
                                </button>
                                <button type="submit" name="save_parent" class="btn btn-info flex-fill p-2" value="">
                                    <i class="fa fa-save"></i> Save Parent ID Only
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        function doc_validate() {
            const input = document.getElementById('doc_file');
            const maxSizeInBytes = 2 * 1024 * 1024; // 5MB

            if (input.files.length > 0) {
                const fileSize = input.files[0].size;
                if (fileSize > maxSizeInBytes) {
                    document.getElementById('doc_error').innerHTML = 'File size exceeds the maximum limit (2MB).';
                    input.value = ''; // Clear the input
                } else {
                    document.getElementById('doc_error').innerHTML = '';
                }
            }
        }

        function pan_validate() {
            const input = document.getElementById('pan');
            const maxSizeInBytes = 2 * 1024 * 1024; // 5MB

            if (input.files.length > 0) {
                const fileSize = input.files[0].size;
                if (fileSize > maxSizeInBytes) {
                    document.getElementById('pan_error').innerHTML = 'File size exceeds the maximum limit (2MB).';
                    input.value = ''; // Clear the input
                } else {
                    document.getElementById('pan_error').innerHTML = '';
                }
            }
        }

        function cheque_validate() {
            const input = document.getElementById('cheque');
            const maxSizeInBytes = 2 * 1024 * 1024; // 5MB

            if (input.files.length > 0) {
                const fileSize = input.files[0].size;
                if (fileSize > maxSizeInBytes) {
                    document.getElementById('cheque_error').innerHTML = 'File size exceeds the maximum limit (2MB).';
                    input.value = ''; // Clear the input
                } else {
                    document.getElementById('cheque_error').innerHTML = '';
                }
            }
        }
    </script>
@endsection
