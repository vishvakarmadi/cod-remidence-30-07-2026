@extends('admin.admin_layouts')
@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp


<div class="block-header">
    <div class="row">
        <div class="col-lg-5 col-md-8 col-sm-12">
            <h2>KYC Update</h2>
        </div>
        <div class="col-lg-7 col-md-4 col-sm-12 text-right">
            <ul class="breadcrumb justify-content-end">
                <li class="breadcrumb-item">{!! $user->kyc_status !!}</li>
                <!-- <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li> -->
            </ul>
        </div>
    </div>
</div>

<div class="row clearfix">
    <form action="{{ route('admin.kyc.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
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
                            <select class="form-control" name="company_type" id="company-dropdown" required>
                                <option value="">Select Company Type</option>
                                <option value="Individual" @if(@$kyc->company_type == 'Individual') Selected @endif>Individual</option>
                                <option value="Partnership" @if(@$kyc->company_type == 'Partnership') Selected @endif>Partnership</option>
                                <option value="Public Limited" @if(@$kyc->company_type == 'Public Limited') Selected @endif>Public Limited</option>
                                <option value="Private Limited" @if(@$kyc->company_type == 'Private Limited') Selected @endif>Private Limited</option>
                                <option value="LLP" @if(@$kyc->company_type == 'Limited Liability' || @$kyc->company_type == 'LLP') Selected @endif>LLP</option>
                                <option value="Other"  @if(@$kyc->company_type == 'Other') Selected @endif>Other</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4 othersname  @if(@$kyc->company_type != 'Other') hide @endif">
                            <label for="form-control-label">Company Type: *</label>
                            <input type="text" name="company_type_name" class="form-control" value="{{ @$kyc->company_type_name }}"  @if(@$kyc->company_type == 'Other') required @endif >
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">GSTIN (Enter N/A for non-gst): *</label>
                            <input type="text" name="gst" id="gst_name" class="form-control" value="{{ @$kyc->gst }}" required placeholder="GSTIN">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">Attach GST Proof *</label>
                            <input type="file" name="doc_proof" class="form-control" accept=".pdf,.doc,.docx" @if(!@$kyc->doc_proof) required @endif id="doc_file" onchange="doc_validate()">
                            <div id="doc_error" style="color: #f00"></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">Aadhaar Card Number: *</label>
                            <input type="text" name="aadhaar_no" class="form-control" value="{{ @$kyc->aadhaar_no }}"
                            pattern="[0-9]{12}"
                            maxlength="12"
                            title="Enter valid 12-digit Aadhaar number"
                            required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">Aadhaar Card : *</label>
                            <input type="file" name="aadhaar" class="form-control" accept=".pdf,.doc,.docx" @if(!@$kyc->aadhaar) required @endif id="aadhaar" onchange="aadhaar_validate()">
                            <div id="aadhaar_error" style="color: #f00"></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">PAN Card Number: *</label>
                            <input type="text" name="pan_no"
                                class="form-control"
                                value="{{ $kyc->pan_no ?? '' }}"
                                pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}"
                                title="Enter valid PAN (ABCDE1234F)"
                                oninput="this.value = this.value.toUpperCase()"
                                required>

                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">PAN Card : *</label>
                            <input type="file" name="pan" class="form-control" accept=".pdf,.doc,.docx" @if(!@$kyc->pan) required @endif id="pan" onchange="pan_validate()">
                            <div id="pan_error" style="color: #f00"></div>
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
                        <label for="form-control-label" class="form-label">Name of Bank*</label>
                        <select name="bank_name" id="bank-dropdown" class="form-control" required>
                            <option value="">Select Bank</option>
                            @php
                                $banks = [
                                    "State Bank of India",
                                    "HDFC Bank",
                                    "ICICI Bank",
                                    "Axis Bank",
                                    "Punjab National Bank",
                                    "Bank of Baroda",
                                    "Kotak Mahindra Bank",
                                    "Yes Bank",
                                    "IndusInd Bank",
                                    "Union Bank of India",
                                    "Canara Bank",
                                    "IDFC First Bank"
                                ];
                            @endphp

                            @foreach($banks as $bank)
                                <option value="{{ $bank }}" @if(@$kyc->bank_name == $bank) selected @endif>{{ $bank }}</option>
                            @endforeach
                            <option value="Other" @if(@$kyc->bank_name && !in_array($kyc->bank_name, $banks)) selected @endif>Other</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4 bank-other-input @if(!@$kyc->bank_name || in_array(@$kyc->bank_name, $banks)) hide @endif">
                        <label for="form-control-label">Other Bank Name *</label>
                        <input type="text" name="bank_name_other" class="form-control" value="@if(@$kyc->bank_name && !in_array($kyc->bank_name, $banks)) {{ $kyc->bank_name }} @endif" 
                        @if(@$kyc->bank_name && !in_array($kyc->bank_name, $banks)) required @endif>
                    </div>


                        <div class="form-group col-md-4">
                            <label for="form-control-label" class="form-label">Account Holder Name*</label>
                            <input type="text" name="beneficiary_name" class="form-control" value="{{ @$kyc->beneficiary_name }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">Account Number*</label>
                            <input type="text" name="account_no" class="form-control" value="{{ @$kyc->account_no }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">IFSC Code *</label>
                            <input type="text" name="ifsc_code" class="form-control" value="{{ @$kyc->ifsc_code }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="form-control-label">Blank Cheque: </label>
                            <input type="file" name="cheque" class="form-control" accept=".pdf,.doc,.docx" @if(!@$kyc->cheque) required @endif id="cheque" onchange="cheque_validate()">
                            <div id="cheque_error" style="color: #f00"></div>
                        </div>
                        <?php // echo '<pre>';print_R($kyc);?>
                        <div class="form-group col-md-4">
                           <label for="form-control-label">Account Type * </label>
                           <select class="form-control" name="account_type" id="account_type-dropdown" required>
                                 <option value='' >Account Type</option>
                                 <option  value='Saving' <?php if(@$kyc->account_type == 'Saving'){echo 'selected';} ?> >Saving</option>
                                 <option  value='Current' <?php if(@$kyc->account_type == 'Current'){echo 'selected';} ?>>Current</option>
                                 <option  value='Overdraft' <?php if(@$kyc->account_type == 'Overdraft'){echo 'selected';} ?> >Overdraft</option>
                                 <option  value='Cash Credit' <?php if(@$kyc->account_type == 'Cash Credit'){echo 'selected';} ?> >Cash Credit</option>
                                 <option  value='Loan Account' <?php if(@$kyc->account_type == 'Loan Account'){echo 'selected';} ?> >Loan Account</option>
                                 <option  value='NRE' <?php if(@$kyc->account_type == 'NRE'){echo 'selected';} ?>>NRE</option>
                             </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(@$kyc->doc_proof)
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h3>GST Proof :</h3>
                        <iframe src="{{ asset('public/uploads/'.@$kyc->doc_proof) }}" frameborder="0" style="width: 100%;height:400px;"></iframe>
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
                        <iframe src="{{ asset('public/uploads/'.@$kyc->pan) }}" frameborder="0" style="width: 100%;height:400px;"></iframe>
                    </div>
                    <div class="col-md-3">
                        <h3>Blank Cheque :</h3>
                        <iframe src="{{ asset('public/uploads/'.@$kyc->cheque) }}" frameborder="0" style="width: 100%;height:400px;"></iframe>
                    </div>
                </div>
            </div>
        </div>
        @endif

        
        <hr>
            <div class="col-lg-4 mb-4">
                
                <button type="submit" class="btn-primary btn h-45 w-100" id="submitBtn">
                    Submit
                </button>

                <script>
                document.querySelector('form').addEventListener('submit', function () {
                    document.getElementById('submitBtn').disabled = true;
                });
                </script>
                
            </div>
       
    </form>
</div>


<script>

$(document).ready(function() {

    // ----- File size validation -----
    function validateFile(inputId, errorId, maxSizeMB) {
        const input = document.getElementById(inputId);
        const maxSizeInBytes = maxSizeMB * 1024 * 1024;
        if (input.files.length > 0) {
            const fileSize = input.files[0].size;
            if (fileSize > maxSizeInBytes) {
                document.getElementById(errorId).innerHTML = `File size exceeds ${maxSizeMB}MB limit.`;
                input.value = ''; // Clear input
                return false;
            } else {
                document.getElementById(errorId).innerHTML = '';
                return true;
            }
        }
        return true;
    }

    $('#doc_file').on('change', function() { validateFile('doc_file', 'doc_error', 2); });
    $('#pan').on('change', function() { validateFile('pan', 'pan_error', 2); });
    $('#aadhaar').on('change', function() { validateFile('aadhaar', 'aadhaar_error', 2); });
    $('#cheque').on('change', function() { validateFile('cheque', 'cheque_error', 2); });

    // ----- Company type "Other" logic -----
    $('#company-dropdown').on('change', function() {
        const val = $(this).val();
        const input = $('input[name="company_type_name"]');
        if(val === 'Other') {
            $('.othersname').show();
            input.prop('required', true);
        } else {
            $('.othersname').hide();
            input.prop('required', false).val('');
        }
    });

    // ----- GST "N/A" logic -----
    $('#gst_name').on('input', function () {
        let val = $(this).val().toUpperCase().trim();
        $(this).val(val);
        if(val === 'N/A') {
            $('#doc_file').prop('required', false).val('');
        } else {
            $('#doc_file').prop('required', true);
        }
    });

    // ----- Form submit validation -----
    $('form').on('submit', function(e) {
        let valid = true;

        // GST proof check
        const gstVal = $('#gst_name').val().toUpperCase().trim();
        if(gstVal !== 'N/A' && !$('#doc_file').val()) {
            alert('GST proof is required');
            valid = false;
        }

        // File size checks
        if(!validateFile('doc_file', 'doc_error', 2)) valid = false;
        if(!validateFile('pan', 'pan_error', 2)) valid = false;
        if(!validateFile('aadhaar', 'aadhaar_error', 2)) valid = false;
        if(!validateFile('cheque', 'cheque_error', 2)) valid = false;

        if(!valid) {
            e.preventDefault(); // stop form submission
            return false;
        }

        // Disable submit button only after all validation passes
        $('#submitBtn').prop('disabled', true);
    });

});

$('#bank-dropdown').on('change', function() {
    const val = $(this).val();
    const input = $('input[name="bank_name_other"]');
    if(val === 'Other') {
        $('.bank-other-input').show();
        input.prop('required', true);
    } else {
        $('.bank-other-input').hide();
        input.prop('required', false).val('');
    }
});



  </script>
@endsection