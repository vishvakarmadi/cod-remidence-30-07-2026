@extends('admin.admin_layouts')
@section('admin_content')
    <div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-8 col-sm-12">
            <h2>Edit Favicon</h2>
            </div>
            
        </div>
    </div>
    
    <form action="{{ url('admin/setting/general/favicon/update') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="current_photo" value="{{ $general_setting->favicon }}">
        <div class="row">
        <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="form-group">
                    <label for="">Existing Favicon</label>
                    <div>
                        <img style="height:100px;" src="{{ asset('public/uploads/'.$general_setting->favicon) }}" alt="" class="w_100">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Change Favicon</label>
                    <div>
                        <input type="file" name="favicon" accept="image/png, image/jpg, image/jpeg">
                    </div>
                </div>
                <div class="row">
                    <x-button size="col-lg-6" type="submit" name="Update" />
                </div>
            </div>
        </div>
        </div>
        </div>
    </form>

@endsection