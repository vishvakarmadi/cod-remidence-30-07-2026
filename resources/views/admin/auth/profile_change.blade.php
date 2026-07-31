@extends('admin.admin_layouts')
@section('admin_content')


<div class="container-fluid">
            <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <h1>Edit Profile</h1>
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
                                    <form action="{{ route('admin.profile_change_update') }}" method="post">
                                    @csrf
                                        <div class="form-group c_form_group">
                                            <label>Name</label>
                                            <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $admin_data->name }}" required autofocus>
                                        </div>

                                        <div class="form-group c_form_group">
                                            <label>Email Address</label>
                                            <input type="text" name="email" class="form-control" placeholder="Email" value="{{ $admin_data->email }}" required>
                                        </div>

                                        <div class="form-group c_form_group">
                                            <label>Phone No</label>
                                            <input type="text" name="mobile" class="form-control" placeholder="Phone No" value="{{ $admin_data->mobile }}" required>
                                        </div>
                                        <div class="form-group c_form_group">
                                            <label>Company Name</label>
                                            <input type="text" name="company_name" class="form-control" placeholder="Company Name" value="{{ $admin_data->company_name }}" required>
                                        </div> 
                                        <div class="form-group c_form_group">
                                            <label>Company Address</label>
                                            <input type="text" name="company_address" class="form-control" placeholder="Company Address" value="{{ $admin_data->company_address }}" required>
                                        </div>
                                        <div class="form-group c_form_group">
                                            <label>Place of supply</label>
                                            <input type="text" name="place_supply" class="form-control" placeholder="Place of supply" value="{{ $admin_data->place_supply }}" required>
                                        </div>
                                        <div class="form-group c_form_group">
                                            <label>State</label>
                                            <select name="state" class="form-control" required>
                                            <option value='' >State</option>
                                             @foreach($states as $state)
                                                    <option value='{{$state->id}}' <?php if($state->id == $admin_data->state){echo 'selected';} ?>>{{$state->name}}</option>
                                             @endforeach
                                            
                                            
                                        </select>
                                        </div>
                                        <div class="form-group c_form_group">
                                            <label>Pin Code</label>
                                            <input type="text" name="pin_code" class="form-control" placeholder="Pin Code" value="{{ $admin_data->pin_code }}" required>
                                        </div>
                                    
                                    
                                        <hr>
                                        <div>
                                            
                                                    <button type="submit" class="btn-primary btn h-45 w-100 col-lg-4">Update</button>
                                            
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
