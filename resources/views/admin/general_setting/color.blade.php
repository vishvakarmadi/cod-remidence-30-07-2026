@extends('admin.admin_layouts')
@section('admin_content')
<div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-8 col-sm-12">
            <h2>Edit Color Information</h2>
            </div>
            
        </div>
    </div>
    
    <form action="{{ url('setting/general/color/update') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Theme Color</label>
                            <div class="input-group colorpicker">                                   
                                <input type="text" name="theme_color" class="form-control" value="{{ $general_setting->theme_color }}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><span class="input-group-addon"><i></i></span></span>
                                </div>
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