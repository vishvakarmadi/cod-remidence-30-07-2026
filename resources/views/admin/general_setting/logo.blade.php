@extends('admin.admin_layouts')
@section('admin_content')

           <!-- Main body part  -->
           <div class="container-fluid">
                    <!-- Page header section  -->
                    <div class="block-header">
                        <div class="row">
                            <div class="col-lg-5 col-md-8 col-sm-12">
                            <h2>Edit logo</h2>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row clearfix">

                    <div class="col-lg-12">
                            <div class="card">
                                <div class="body">
                                    
                                    <form action="{{ url('setting/general/logo/update') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                        <input type="hidden" name="current_photo" value="{{ $general_setting->logo }}">
                                        <input type="hidden" name="current_photo1" value="{{ $general_setting->white_logo }}">
                                        <div class="row">
                                            <div class="form-group c_form_group col-5">
                                                <label>Existing Dark Logo</label>
                                                <img style="height:100px;" src="{{ asset('public/uploads/'.$general_setting->logo) }}" alt="" class="w_200">
                                            </div>&nbsp;&nbsp;&nbsp;&nbsp;

                                            <div class="form-group c_form_group col-5">
                                                <label>Existing White Logo</label>
                                                <img style="height:100px;" src="{{ asset('public/uploads/'.$general_setting->white_logo) }}" alt="" class="w_200">
                                            </div>

                                            <div class="form-group c_form_group col-5">
                                                <label>Change Dark Logo</label>
                                                <input type="file" name="logo" accept="image/png, image/jpg, image/jpeg" />
                                            </div>&nbsp;&nbsp;&nbsp;&nbsp;

                                            <div class="form-group c_form_group col-5">
                                                <label>Change White Logo</label>
                                                <input type="file" name="white_logo" accept="image/png, image/jpg, image/jpeg" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <x-button size="col-lg-3" type="submit" name="Submit" />
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


  

@endsection