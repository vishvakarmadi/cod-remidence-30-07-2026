@php
    $general_setting = DB::table('general_settings')->where('id',1)->first();
@endphp


<!doctype html>
<html lang="en">

<head>
<title>hyloship -  Login</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="hyloship Bootstrap 4.1.1 Admin Template">
<meta name="author" content="hyloship, design by: hyloshipapp.com">

<link rel="icon" href="{{ asset('public/favicon.svg') }}" type="image/x-icon">
<!-- VENDOR CSS -->
<link rel="stylesheet" href="/public/admin/assets/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="/public/admin/assets/vendor/animate-css/animate.min.css">
<link rel="stylesheet" href="/public/admin/assets/vendor/font-awesome/css/font-awesome.min.css">

<!-- MAIN CSS -->
<link rel="stylesheet" href="/public/admin/assets/css/main.css">
<link rel="stylesheet" href="/public/admin/assets/css/color_skins.css">
<link href="/public/toastr/toastr.css" rel="stylesheet" />
<script src="/public/toastr/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="/public/toastr/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="/public/toastr/toastr.js"></script>
<style>
    .theme-blue ::selection {
     /* color: #000; */
    background:#1a73e8;
    }
    </style>
<script>
        $(document).ready(function() {
            toastr.options.timeOut = 10000;
            @if (Session::has('error'))
                toastr.error('{{ Session::get('error') }}');
            @elseif(Session::has('success'))
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
                    <div class="mobile-logo"><a href="{{ route('admin.login') }}"><img src="{{ asset('public/favicon.svg') }}" alt="hyloship"></a></div>
                    <div class="auth-left">
                        <div class="left-top">
                            <a href="{{ route('admin.login') }}">
                                <img src="{{ asset('public/favicon.svg') }}" alt="hyloship">
                                <span>hyloship</span>
                            </a>
                        </div>
                        <div class="left-slider">
                            <img src="/public/admin/assets/images/login/1.jpg" class="img-fluid" alt="">
                        </div>
                    </div>
                    <div class="auth-right">
                        <div class="right-top">
                            <ul class="list-unstyled clearfix d-flex">
                                <li><a href="{{ route('admin.login') }}"><i class="fa fa-home"></i></a></li>
                            </ul>
                        </div>

                        <div class="card">
                            <div class="header">
                                <p class="lead">Recover my password</p>
                            </div>
                            <div class="body">
                                <p>Please enter your email address below to receive instructions for resetting password.</p>
                                    <form action="{{ route('admin.forget_password.store') }}" class="user" method="post">
                                    @csrf
                                    <div class="form-group">                                    
                                        <input type="email" class="form-control" name="email" id="signup-password" placeholder="Email">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">RESET PASSWORD</button><br>
                                    <div class="bottom">
                                        <span class="helper-text">Know your password? <a href="{{ route('admin.login') }}">Login</a></span>
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
</html>



