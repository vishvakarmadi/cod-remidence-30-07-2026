@extends('admin.admin_layouts')
@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp


<div class="block-header">
    <div class="row">
        <div class="col-lg-5 col-md-8 col-sm-12">
            <h2>Upload Courierwise Serviceable pincode</h2>
        </div>
        <div class="col-lg-7 col-md-4 col-sm-12 text-right">
            <ul class="breadcrumb justify-content-end">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="jumbotron" style="background: white">
            <h1 class="display-4">Instructions : </h1>
            <p> 1. Download the format file and fill it with proper data.</p>
            <p>2. You can download the example file to understand how the data must be filled.</p>
            <p>3. Once you have downloaded and filled the format file, upload it in the form below and submit.</p>
            <a href="{{ route('admin.integration.download_serviceable') }}" class="btn btn-secondary">Download Format</a>
        </div>
    </div>

    <div class="col-md-12">
        <form class="product-form" action="{{ route('admin.integration.storeserviceablepincode') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <h4>Import Pincode File</h4>
                </div>
                <div class="card card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="form-group col-4">
                                <div class="image-upload">
                                    <div class="thumb">
                                        <div class="avatar-edit">
                                            <input type="file" class="profilePicUpload" name="excel" id="profilePicUpload1" required accept=".xlsx">
                                            <label for="profilePicUpload1" class="bg-secondary text-white">Upload File</label>
                                            <small class="mt-2">Supported files: <b>xlsx</b>.</small>
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
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection