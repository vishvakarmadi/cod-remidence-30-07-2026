@php
    $general_setting = DB::table('general_settings')->where('id',1)->first();
@endphp
	<style>
		    ::selection {
  color: white;
  background: blue;
}
.fotter_li {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed; /* Fixed position */
    left: 0; /* Span the full width of the viewport */
    bottom: 0; /* Anchor it to the bottom */
    width: 100%; /* Span the full width of the viewport */
    margin: 0;
    padding: 10px 0; /* Provide some padding above and below */
    background-color: #333; /* Assuming you want a dark background; change as needed */
    color: white; /* Light text color for contrast */
    list-style: none;
    text-align: center; /* Center the text */
    box-shadow: 0 -2px 5px rgba(0,0,0,0.2); /* Optional: Adds a subtle shadow for depth */
}

/* Style for individual list items if needed */
.fotter_li li {
    padding: 0 15px; /* Spacing between menu items */
}

/* Ensure anchor tags in the list are styled correctly */
.fotter_li li a {
    color: white; /* Light text color for contrast */
    text-decoration: none; /* Removes underline from links */
    transition: color 0.3s ease-in-out; /* Smooth transition for hover effect */
}

/* Hover state for links */
.fotter_li li a:hover {
    color: #f8f8f8; /* Slightly lighter color on hover */
}


ul.fotter_li a {color: #fff;}

@media (max-width:992px) {
    ul.fotter_li {
        margin: 20px;
    }
    
}
	</style>

<!doctype html>
<html lang="en">

<head>
<title>Hyloship -  Login</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="Hyloship Bootstrap 4.1.1 Admin Template">
<meta name="author" content="Hyloship, design by: Aastrazen.com">

<link rel="icon" href="{{ asset('public/favicon.svg') }}" type="image/x-icon">
<!-- VENDOR CSS -->
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/animate-css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/font-awesome/css/font-awesome.min.css') }}">

<link rel="icon" href="{{ asset('public/uploads/'.$general_setting->favicon) }}" type="image/x-icon">
<!-- MAIN CSS -->
<link rel="stylesheet" href="{{ asset('public/admin/assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/css/color_skins.css') }}">
<link href="{{ asset('public/toastr/toastr.css') }}" rel="stylesheet" />
<script src="{{ asset('public/toastr/jquery-3.6.0.min.js') }}" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="{{ asset('public/toastr/popper.min.js') }}" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="{{ asset('public/toastr/toastr.js') }}"></script>

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
                    <div class="mobile-logo"><a href="{{ route('admin.login') }}"><img src="{{ asset('public/favicon.svg') }}" alt="Hyloship"></a></div>
                    <div class="auth-left">
                        <div class="left-top">
                            <a href="{{ route('admin.login') }}">
                                <img src="{{ asset('public/favicon.svg') }}" alt="Hyloship">
                                <span>Hyloship</span>
                            </a>
                        </div>
                        <div class="left-slider">
                            <img src="{{ asset('public/admin/assets/images/login/1.jpg') }}" class="img-fluid" alt="">
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
                                <p class="lead">Log in</p>
                            </div>
                            <div class="body">
                                <form action="{{ route('admin.login.store') }}" id="login" class="user" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="signin-email" class="control-label" style="float: left;">Email</label>
                                        <input type="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <label for="signin-password" class="control-label" style="float: left;">Password</label>
                                        <input type="password" name="password" class="form-control" required value="{{ old('password') }}" placeholder="Password">
                                    </div>
                                    <div class="form-group clearfix" style="align-items: unset;display: flex;">
                                        <label class="element-left">
                                            <input type="checkbox" name="remember" id="remember" style="width: 22px;">
                                            <span for="remember">Remember Me</span>
                                        </label>								
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="btnsubmit">LOGIN</button>
                                    <button type="" class="btn btn-primary btn-lg btn-block" id="btnwait" style="display:none">Please Wait...</button><br>
                                    <div class="bottom">
                                        <span class="helper-text m-b-10"><i class="fa fa-lock"></i> <a href="{{ route('admin.forget_password') }}">Forgot password?</a>&nbsp;&nbsp; / &nbsp;&nbsp;<i class="fa fa-sign-in"></i>
                                         <!-- <a href="{{ route('admin.register') }}"> Register Account?</a> -->
                                        </span>
                                        <span class="helper-text m-b-10"></span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
				</div>
				<div class="login_footer">
                    <ul class="fotter_li">
                        <li><a href="https://kimipost.com/about-us/" target="_blank">About Us</a></li>
                        <li><a href="https://kimipost.com/terms-of-service-kimipost-software/" target="_blank">Terms and Conditions</a></li>
                        <li><a href="https://kimipost.com/privacy-policy-software/" target="_blank">Privacy</a></li>
                        <li><a href="https://kimipost.com/refunds-and-cancellations/" target="_blank">Refunds/Cancellations</a></li>
                        <li><a href="https://kimipost.com/contact-us/" target="_blank">Contact Us</a></li>
                    </ul>
                </div>
			</div>
		</div>
	</div>
	<!-- END WRAPPER -->
</body>
<script>
   $('#btnsubmit').click(function() {
       // hides text for 5 secs  
    
    const btn = document.querySelector('#btnsubmit');
    const btnwait = document.querySelector('#btnwait');
    
    btn.style.display = 'none'
    btnwait.style.display = 'block'
    setTimeout(()=>{btn.style.display = 'block';btnwait.style.display = 'none'; }, 5000)
    
    });
</script>
</html>



