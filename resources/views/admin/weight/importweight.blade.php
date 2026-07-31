@extends('admin.admin_layouts')
@section('admin_content')
@php 
$user_id = Auth::guard('admin')->user()->id;
@endphp

<div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-8 col-sm-12">
                <h2>Import Extra Weight</h2>
            </div>
            
        </div>
    </div>
<div class="card pt-30 ratecard col-lg-6">
    <div class="card-body">
        <form class="product-form" action="{{ route('admin.weight.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <a href="{{ asset('public/extra_weight.xlsx') }}" download="" class="btn btn-secondary">Download
                Format</a>
            
                <div class="form-group">
                    <!-- <div class="image-upload">
                        <div class="thumb">
                            <div class="avatar-edit">
                                <input type="file" class="profilePicUpload" name="excel" id="profilePicUpload1" required
                                    accept=".xlsx">
                                <label for="profilePicUpload1" class="bg-secondary text-white">Upload
                                    File</label>
                                <small class="mt-2">Supported files: <b>xlsx</b>.</small>
                            </div>
                        </div>
                    </div> -->
                    <label for="">Excel File:</label><span class="required"> *</span>
                            <input type="file" name="excel" class="form-control" required accept=".xlsx">
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <button type="submit" class="btn-primary btn w-100">Submit</button>
            </div>
        </form>
    </div>
</div>
  
@endsection
