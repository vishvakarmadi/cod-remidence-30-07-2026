@extends('admin.admin_layouts')
@section('admin_content')

<div class="container-fluid">
                <!-- Page header section  -->
                <div class="block-header">
                    <div class="row clearfix">
                        <div class="col-xl-6 col-md-5 col-sm-12">
                            <h1>Hi, Welcomeback!</h1>
                            <span>JustDo Settings,</span>
                        </div>
                        <div class="col-xl-6 col-md-7 col-sm-12 text-md-right">
                            <div class="d-flex align-items-center justify-content-between flex-wrap vivify pullUp delay-550">                        
                                <div class="ml-auto mb-3 mb-xl-0">
                                    <p class="text-muted mb-1">Date</p>
                                    <h5 class="mb-0">{{ date('Y-m-d') }}</h5>
                                </div>
                                <div class="ml-auto mb-3 mb-xl-0">
                                    <p class="text-muted mb-1">Time</p>
                                    <h5 class="mb-0">{{ date('H:i:s') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-12">
                        <div class="d-lg-flex justify-content-between">
                            <ul class="nav nav-tabs4 page-header-tab">
                                <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#Company_Settings">Company</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Email_Settings">Email</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Change_Password">Change Password </a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#background-img">Background image</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active show" id="Company_Settings">
                                <div class="card">
                                    <div class="card-header">
                                        Company Settings
                                    </div>
                                    <div class="card-body">
                                    <form action="{{ route('admin.company.store') }}" method="post">
                                        @csrf
                                            <div class="row">
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group c_form_group">
                                                        <label>Company Name <span class="text-danger">*</span></label>
                                                        <input class="form-control" name="app_name" type="text" value="{{ env('APP_NAME') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group c_form_group">
                                                        <label>Contact Person</label>
                                                        <input class="form-control" type="text" name="contact_person" value="{{ $general_setting->contact_person }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group c_form_group">
                                                        <label>Mobile Number <span class="text-danger">*</span></label>
                                                        <input class="form-control" name="footer_phone"  value="{{ $general_setting->footer_phone }}" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group c_form_group">
                                                        <label>Address</label>
                                                        <textarea class="form-control" name="footer_address" aria-label="With textarea">{{ $general_setting->footer_address }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group c_form_group">
                                                        <label>Email <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="icon-envelope"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control" name="footer_email" value="{{ $general_setting->footer_email }}" type="email">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <div class="row">
                                                <div class="col-sm-12 text-right m-t-20">
                                                    <button type="submit" class="btn btn-success theme-bg">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>                        
                            </div>
                            
                            <div class="tab-pane" id="Email_Settings">
                                <div class="card">
                                    <div class="card-header">
                                        SMTP Email Settings
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.smtp_mail.store') }}" method="post">
                                            @csrf							
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group c_form_group">
                                                        <label>Email From Address</label>
                                                        <input class="form-control" type="email" name="email_from" value="{{ env('MAIL_FROM_ADDRESS') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group c_form_group">
                                                        <label>Emails From Name</label>
                                                        <input class="form-control" type="text" name="from_name" value="{{ env('MAIL_FROM_NAME') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group c_form_group">
                                                        <label>SMTP HOST</label>
                                                        <input class="form-control" type="text" name="mail_host" value="{{ env('MAIL_HOST') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group c_form_group">
                                                        <label>SMTP USER</label>
                                                        <input class="form-control" type="text" name="user_name" value="{{ env('MAIL_USERNAME') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group c_form_group">
                                                        <label>SMTP PASSWORD</label>
                                                        <input class="form-control" type="password" name="password" value="{{ env('MAIL_PASSWORD') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group c_form_group">
                                                        <label>SMTP PORT</label>
                                                        <input class="form-control" type="text" name="port" value="{{ env('MAIL_PORT') }}" required>
                                                    </div>
                                                </div>
                                                @php($encryption = env('MAIL_ENCRYPTION'))
                                                <div class="col-sm-6">
                                                    <div class="form-group c_form_group">
                                                        <label>SMTP Security</label>
                                                        <select class="form-control" name="security" required>
                                                            <option>None</option>
                                                            <option value="ssl" @if($encryption == "ssl") selected @endif>SSL</option>
                                                            <option value="tls" @if($encryption == "tls") selected @endif>TLS</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 m-t-20 text-right">
                                                    <button type="submit" class="btn btn-primary theme-bg">SAVE</button>
                                                </div>
                                            </div>                            
                                        </form>
                                    </div>
                                </div>                    
                            </div>
                            
                            <div class="tab-pane" id="Change_Password">
                            <form action="{{ url('admin/password-change/update') }}" method="post">
                                @csrf
                                <div class="card">
                                    <div class="card-header">
                                        Change Password
                                    </div>
                                    <div class="card-body">
                                        <div class="row clearfix">                              
                                            <div class="col-lg-12 col-md-12">
                                                <div class="form-group c_form_group">
                                                    <input type="password" name="password" class="form-control" placeholder="New Password">
                                                </div>
                                                <div class="form-group c_form_group">
                                                    <input type="password" name="re_password" class="form-control" placeholder="Confirm New Password">
                                                </div>
                                            </div>
                                            <div class="col-sm-12 m-t-20 text-right">
                                                <button type="submit" class="btn btn-primary theme-bg">SAVE</button> &nbsp;
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            </div>

                            <div class="tab-pane" id="background-img">
                            <form action="{{ url('admin/background-img/update') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card">
                                    <div class="card-header">
                                        Change Background Image
                                    </div>
                                    <div class="card-body">
                                        <div class="row clearfix">                              
                                            <div class="col-lg-6 col-md-12">
                                            <div class="form-group c_form_group">
                                                    <img style="width:100%;" src="{{ asset('public/uploads/'.$general_setting->background_img) }}" alt="" />
                                                </div>

                                                <div class="form-group c_form_group">
                                                    <input type="hidden" name="current_photo" class="form-control" value="{{ $general_setting->background_img }}">
                                                    <input type="file" name="background_img" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 m-t-20 text-right">
                                                <button type="submit" class="btn btn-primary theme-bg">SAVE</button> &nbsp;
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@endsection
