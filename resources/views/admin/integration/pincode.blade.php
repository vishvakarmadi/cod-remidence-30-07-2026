@extends('admin.admin_layouts')
@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp


<div class="block-header">
    <div class="row">
        <div class="col-lg-5 col-md-8 col-sm-12">
            <h2>Upload Serviceable pincode</h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="jumbotron">
            <h1 class="display-5">Instructions : </h1>
            <p> 1. Download the format file and fill it with proper data.</p>
            <p>2. You can download the example file to understand how the data must be filled.</p>
            <p>3. Once you have downloaded and filled the format file, upload it in the form below and submit.</p>
            <a href="{{ asset('public/pincode.xlsx') }}" download="" class="btn btn-secondary">Download Format</a>
        </div>
</div>

    <div class="col-lg-6 col-md-12">
        <form class="product-form" action="{{ route('admin.integration.storepincode') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <h4>Import Pincode File</h4>
                </div>
                <div class="card card-body bottom-curved">
                    <div class="form-group">
                    <span style="color: red;font-size: 16px;">Please ensure that the Excel file contains only <b>one sheet</b>.</span>
                        <div class="row">
                            <div class="form-group col-8">
                                <label for="">Excel File:</label><span class="required"> *</span>
                                <input type="file" name="excel" class="form-control" required accept=".xlsx">
                                </div>
                            </div>
                            <div class="">
                            <button type="submit" class="btn-primary btn h-45 w-100 col-lg-4" onclick="$('#loader').removeClass('hidden')">Submit</button>
                        </div>    
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection