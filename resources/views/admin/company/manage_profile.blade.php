@extends('admin.admin_layouts')
@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp

<div class="block-header">
    <div class="row">
        <div class="col-lg-5 col-md-8 col-sm-12">
            <h2>Manage Profile</h2>
        </div>
        <div class="col-lg-7 col-md-4 col-sm-12 text-right">
            <ul class="breadcrumb justify-content-end">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
            </ul>
        </div>
    </div>
</div>

<div class="row clearfix">
    <form  action="{{ route('admin.manage_profile.update', $admin->id) }}" method="POST">
        @csrf
        <div class="col-12">
            <div class="card pt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">Product details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4 ">
                            <label>Legal Name of Business</label><span class="required"> *</span>
                            <input class="form-control" type="text" name="business_name" required value="{{ @$admin->business_name }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-control-label">Trade Name</label>
                            <input class="form-control" type="text" name="trade_name" value="{{ @$admin->trade_name }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Licence Key </label>
                            <input class="form-control" type="text" name="license_key" value="{{ @$admin->license_key }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>TIN Number </label>
                            <input class="form-control" type="text" name="tin_number" value="{{ @$admin->tin_number }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-control-label">CST/VAT Number</label>
                            <input class="form-control" type="number" name="vat_number" value="{{ @$admin->vat_number }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-control-label">GST Number</label>
                            <input class="form-control" type="number" name="gst_number" value="{{ @$admin->gst_number }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-control-label">Description</label>
                            <textarea class="form-control" name="description">{{ @$admin->description }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card pt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contact information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4 ">
                            <label>Email</label><span class="required"> *</span>
                            <input class="form-control" type="email" name="contact_email" required Placeholder="Email" value="{{ $admin->contact_email }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-control-label">Enable Email</label>
                            <select class="form-control" name="enable_email">
                                <option value="Yes" {{ (@$admin->enable_email == 'Yes')? "selected" : "" }}>Yes</option>
                                <option value="No" {{ (@$admin->enable_email == 'No')? "selected" : "" }}>No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card pt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">Notification Email</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4 ">
                            <label>Email</label><span class="required"> *</span>
                            <input class="form-control" type="email" name="notification_email" required
                                Placeholder="Email" value="{{ @$admin->notification_email }}">
                        </div>
                        <div class="form-group col-md-4 ">
                            <label>Phone</label><span class="required"> *</span>
                            <input class="form-control" type="number" name="contact_phone" required Placeholder="Phone" value="{{ @$admin->contact_phone }}">
                        </div>
                        <div class="form-group col-md-4 ">
                            <label>URL</label>
                            <input class="form-control" type="text" name="url" Placeholder="URL" value="{{ @$admin->url }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card pt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">Company Registered address</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="country-dropdown" class="form-control-label">Country</label><span
                                class="required"> *</span>
                            <select class="form-control" id="country-dropdown" name="country">
                                <option value="">-- Select Country --</option>
                                @foreach ($countries as $data)
                                <option value="{{ $data->id }}" {{ (@$profile->country == $data->id)? "selected" : "" }}>
                                    {{ $data->name }} 
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="state-dropdown" class="form-control-label">State</label><span class="required">
                                *</span>
                            <select class="form-control" id="state-dropdown" name="state">
                                <option value="">-- Select State --</option>
                                @foreach ($states as $data)
                                <option value="{{ $data->id }}" {{ (@$profile->state == $data->id)? "selected" : "" }}>
                                    {{ $data->name }} 
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">City </label><span class="required"> *</span>
                            <input type="text" name="city" class="form-control" value="{{ @$profile->city }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Address </label><span class="required"> *</span>
                            <input type="text" name="address" class="form-control" value="{{ @$profile->address }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Zip/Postal Code </label><span class="required"> *</span>
                            <input type="text" name="zip_code" class="form-control" value="{{ @$profile->zip_code }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="col-12">
            <div class="card pt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">Manage Currency</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="country-dropdown" class="form-control-label">Currency</label>
                            <select class="form-control" name="currency">
                                <option value="">-- Select Currency --</option>
                                @foreach ($currency as $data)
                                <option value="{{ $data->code }}" {{ ($admin->currency == $data->code)? "selected" : "" }}>
                                    {{ $data->code }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card pt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">Other Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="">SLA Time (Number of Days) </label><span class="required"> *</span>
                            <input type="text" name="sla_time" class="form-control" value="{{ $admin->sla_time }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="country-dropdown" class="form-control-label">MPS Status</label>
                            <select class="form-control" name="mps_status">
                                <option value="Yes" {{ ($admin->mps_status == 'Yes')? "selected" : "" }}>Yes</option>
                                <option value="No" {{ ($admin->mps_status == 'No')? "selected" : "" }}>No
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn-primary btn h-45 w-100">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
$(document).ready(function() {
    $('#country-dropdown').on('change', function() {
        var idCountry = this.value;
        $("#state-dropdown").html('');
        $.ajax({

            url: "{{ route('states') }}",
            method: "POST",
            data: {
                country_id: idCountry,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                $('#state-dropdown').html(
                    '<option value="">-- Select State --</option>');
                $.each(result, function(key, value) {
                    $("#state-dropdown").append('<option value="' +
                        value
                        .id + '">' + value.name + '</option>');
                });

            }
        });
    });
});
</script>
@endsection