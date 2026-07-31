@extends('admin.admin_layouts')
@section('admin_content')

        <div class="container-fluid">
            <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <h1>Edit Password</h1>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                                <div class="row clearfix">
                                    <div class="col-xl-5 col-md-5 col-sm-12"></div>            

                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="row clearfix">
                    <div class="col-lg-6">
                            <div class="card">
                                <div class="body">
                                    <form action="{{ url('admin/password-change/update') }}" method="post">
                                    @csrf
                                        <div class="form-group c_form_group">
                                            <label>Password</label>
                                            <input type="password" name="password" class="form-control" placeholder="Password" value="" required autofocus>
                                        </div>

                                        <div class="form-group c_form_group">
                                            <label>Re-password</label>
                                            <input type="password" name="re_password" class="form-control" placeholder="Re-password" value="" required>
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
