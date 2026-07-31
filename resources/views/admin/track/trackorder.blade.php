@php
    $general_setting = DB::table('general_settings')->where('id',1)->first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
<link rel="icon" href="{{ asset('public/favicon.svg') }}" type="image/x-icon">
<title>Hyloship - Track Shipments</title>
<meta name="description" content="Login">
<meta name="author" content="https://hyloship.com/">

<!-- Web Fonts
========================= -->
<!-- <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'> -->

<!-- Stylesheet
========================= -->
<!-- <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}"> -->
<link rel="stylesheet" href="{{ asset('public/admin/assets/css/bootstrap.min.css') }}">
<!-- MAIN CSS -->
<link rel="stylesheet" href="{{ asset('public/admin/assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/css/stylesheet1.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/css/color_skins.css') }}">
<link href="{{ asset('public/toastr/toastr.css') }}" rel="stylesheet" />
<script src="{{ asset('public/toastr/jquery-3.6.0.min.js') }}" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="{{ asset('public/toastr/popper.min.js') }}" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="{{ asset('public/toastr/toastr.js') }}"></script>
<style>
.form-control:disabled, .form-control[readonly] {
    background-color: #e9ecef;
    opacity: 1;
}

.blueOrangeGradient{
  color: transparent;
    background: transparent linear-gradient(99deg, #ffc465, #5338ff) 0 0 no-repeat padding-box;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.headerOne.gradientBanner {
    background: transparent linear-gradient(244deg, #fde6cd, #ebebff) 0 0 no-repeat padding-box !important;
    background-position: bottom;
    z-index: 0;
    padding: 180px 0 160px;
    margin-top: -155px;
    overflow: hidden;
}
.kimpost-register:after {
    pointer-events: none;
}
.card {
    position: relative;
    z-index: 10;
    background: #fff !important;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15) !important;
    border: none !important;
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
        $('.preloader').delay(1000).fadeOut('slow');
    });
</script>
<body>
<!-- Preloader -->
<div class="preloader">
  <div class="lds-ellipsis">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
  </div>
</div>
<!-- Preloader End -->


<div id="main-wrapper" class="kimpost-register headerOne Section gradientBanner contactFormSection">
  <div class="container-fluid px-0">
    <div class="row g-0 min-vh-100"> 
      <!-- Welcome Text
      ========================= -->
      <div class="col-md-5 d-none d-lg-block">
        <div class="hero-wrap h-100">
          <div class=" opacity-3 "></div>
          <div class="hero-content w-100">
            <div class="container d-flex flex-column min-vh-100">
              <div class="row g-0">
                <div class="col-11 col-md-10 col-lg-9 mx-auto">
                  <div class="logo mt-5 mb-5 mb-md-0">
                    <a class="d-flex align-items-center text-5" href="{{ route('admin.login') }}">
                     <!-- <img src="{{ asset('public/favicon.svg') }}" alt="hyloship"> hyloship -->
                     <!-- <img src="{{ asset('public/hyloshiplogo.png') }}" alt="hyloship"> -->
                    </a> </div>
                </div>
              </div>
              <div class="row g-0 my-auto">
                <div class="col-11 col-md-10 col-lg-9 mx-auto">
                    <h1 class="text-11  mb-4" style="font-size: 52px;
        line-height: 58px;color: #0b0757;">
                        Track your <span class="blueOrangeGradient"><br> orders easily </span></h1>
                    <p class="text-4  lh-base mb-5">Just enter your AWB tracking number & it’s done.</p>
                    <img width="1049" height="341" decoding="async" src="https://d2kh7o38xye1vj.cloudfront.net/wp-content/uploads/2023/04/tracking.png" style="width: auto;max-width: 526px;" class="mt-xl" alt="Track">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Welcome Text End --> 
      
      <!-- Login Form
      ========================= -->
      <div class="col-md-7 d-flex flex-column">
        <div class="container pt-5">
          <div class="row g-0">
            {{-- <div class="col-11 mx-auto">
              <p class="text-end text-2 mb-0">Not a member? <a href="{{ route('admin.register', ['company_code' => 'default']) }}">Sign Up</a></p>
            </div> --}}
          </div>
        </div>
        <div class="container my-auto py-5">
          <div class="row g-0">
            <div class="col-11 col-md-10 col-lg-9 col-xl-8 mx-auto">
              <div class="card">
                <div class="header" style="padding: 20px 20px 10px;">
                  <h3 class="fw-600 mb-0">Track Shipment</h3>
                  <p class="text-muted mb-0 mt-2">Please enter your AWB tracking number below.</p>
                </div>
                <div class="body" style="padding: 20px;">
                  <form action="{{ route('admin.track') }}" method="POST" id="myForm">
                        @csrf
                        <div class="form-group mb-3">
                          <label for="signin-email" class="control-label" style="float: left; margin-bottom: 8px; font-weight: 500;">AWB Number</label>
                          <input type="text" name="awb" class="form-control" placeholder="Enter AWB Number" required>
                        </div>
                        <button type="submit" id="btnsubmit" class="btn btn-primary btn-lg btn-block mt-3 w-100">Track Order</button>
                    </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Login Form End --> 
    </div>
    
  </div>
</div>


</body>
</html>