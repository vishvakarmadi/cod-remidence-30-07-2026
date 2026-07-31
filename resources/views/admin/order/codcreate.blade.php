@extends('admin.admin_layouts')
@section('admin_content')
@php 
$user_id = Auth::guard('admin')->user()->id;
@endphp
<div class="card pt-30 ratecard">
    <div class="card-body">
        <form class="product-form" action="{{ route('admin.order.storecod') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h4>Import Paid COD</h4>
            <a href="{{ route('admin.order.download_cod_paid_format') }}" class="btn btn-secondary">Download Format</a>
            
                <div class="form-group col-4">
                    <div class="image-upload">
                        <div class="thumb">
                            <div class="avatar-edit">
                                <input type="file" class="profilePicUpload" name="excel" id="profilePicUpload1" required
                                    accept=".xlsx,.csv">
                                <label for="profilePicUpload1" class="bg-secondary text-white">Upload
                                    File</label>
                                <small class="mt-2">Supported files: <b>xlsx, csv</b>.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn-primary btn w-100">Submit</button>
            </div>
        </form>
    </div>
</div>
  
@endsection
