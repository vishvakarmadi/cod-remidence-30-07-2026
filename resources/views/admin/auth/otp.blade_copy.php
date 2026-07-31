@php
    $general_setting = DB::table('general_settings')
        ->where('id', 1)
        ->first();
@endphp


<!doctype html>
<html lang="en">

<head>
    <title>Hyloship - Login</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="Hyloship Bootstrap 4.1.1 Admin Template">
    <meta name="author" content="Hyloship, design by: Aastrazen.com">

    <link rel="icon" href="{{ asset('public/favicon.svg') }}" type="image/x-icon">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="/public/admin/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/admin/assets/vendor/animate-css/animate.min.css">
    <link rel="stylesheet" href="/public/admin/assets/vendor/font-awesome/css/font-awesome.min.css">

    <link rel="icon" href="{{ asset('public/uploads/' . $general_setting->favicon) }}" type="image/x-icon">
    <!-- MAIN CSS -->
    <link rel="stylesheet" href="/public/admin/assets/css/main.css">
    <link rel="stylesheet" href="/public/admin/assets/css/color_skins.css">
    <link href="/public/toastr/toastr.css" rel="stylesheet" />
    <script src="/public/toastr/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script src="/public/toastr/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="/public/toastr/toastr.js"></script>

    <script>
        $(document).ready(function() {
            toastr.options.timeOut = 10000;
            @if (Session::has('error'))
                toastr.error('{{ Session::get('error') }}');
            @elseif (Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @endif
        });
    </script>
</head>

<body class="theme-blue">
    <!-- WRAPPER -->
    <div id="wrapper">
        <div class="vertical-align-wrap">
            <div class="vertical-align-middle auth-main">
                <div class="auth-box">
                    <div class="mobile-logo"><a href="{{ route('admin.login') }}"><img
                                src="{{ asset('public/favicon.svg') }}" alt="Hyloship"></a></div>
                    <div class="auth-left">
                        <div class="left-top">
                            <a href="{{ route('admin.login') }}">
                                <img src="{{ asset('public/favicon.svg') }}" alt="Hyloship">
                                <span>Hyloship</span>
                            </a>
                        </div>
                        <div class="left-slider">
                            <img src="/public/admin/assets/images/login/1.jpg" class="img-fluid" alt="">
                        </div>
                    </div>
                    <div class="auth-right">
                        <div class="right-top">
                            <ul class="list-unstyled clearfix d-flex">
                                <li><a href="{{ route('admin.home') }}"><i class="fa fa-home"></i></a></li>
                            </ul>
                        </div>
                        <div class="card">
                            <div class="header">
                                <p class="lead">Email Verification</p>
                            </div>
                            <div class="body">
                                <form action="{{ route('admin.otp.store') }}" method="POST">
                                    @csrf
                                    <div class="user_details">
                                        <div class="form-group">
                                            <label for="signin-email" class="control-label sr-only">Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ $admin->email }}" readonly required>
                                        </div>
                                        <div class="form-group">
                                            <label for="signin-otp" class="control-label sr-only">OTP</label>
                                            <input type="number" name="otp" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="button">
                                        <div class="col-6"><button type="submit" class="btn btn-primary btn-lg btn-block" name="otp_verified"
                                                value='1'>Verify</button></div><br>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END WRAPPER -->
</body>
<script>
    $('#btnsubmit').click(function() {
        $(this).html('Please wait ...').attr('disabled', 'disabled');
        $('#login').submit();
    });
</script>

</html>
