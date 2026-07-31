@extends('admin.admin_layouts')
@section('admin_content')
<div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-8 col-sm-12">
            <h2>Edit Company Name</h2>
            </div>
            
        </div>
    </div>
    
    <form action="{{ url('admin/setting/general/name/update') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Name</label>
                            <div class="input-group">                                   
                                <input type="text" name="name" class="form-control" value="{{ $general_setting->name }}">
                                
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