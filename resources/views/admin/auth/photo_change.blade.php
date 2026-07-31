@extends('admin.admin_layouts')
@section('admin_content')


<head>
  		<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
</head>

<div class="container-fluid">
            <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <h1>Edit Photo</h1>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                                <div class="row clearfix">
                                    <div class="col-xl-5 col-md-5 col-sm-12"></div>            

                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="row clearfix">
                    <div class="col-lg-4">
                            <div class="card">
                                <div class="body">
                                    <form action="{{ url('admin/photo-change/update') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                        <div class="form-group">
                                            <label for="">Current Photo *</label>
                                            <div>
                                                <input type="hidden" name="current_photo" value="{{ $admin_data->photo }}">
                                                <img src="{{ asset('public/uploads/'.$admin_data->photo) }}" alt="" style="height:125px;" class="w_150">
                                            </div>
                                        </div>
                                      <div class="form-group c_form_group col-6">
                            				<label>Photo <small>(JPG, PNG only)</small></label>
                            				<input type="file" name="photo" accept=".jpg,.jpeg,.png">
                            				<small class="form-text text-muted">Please upload the photo in JPG or PNG format only.</small>
                        				</div>
                                        <div class="col-lg-3 col-md-4 col-sm-12">
                                            <div class="mb-2">
                                            <button type="submit" class="btn btn-success">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
@endsection
