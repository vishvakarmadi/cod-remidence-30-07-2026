@php
    $general_setting = DB::table('general_settings')->where('id',1)->first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
<link href="{{ asset('public/uploads/' . $general_setting->favicon) }}" rel="shortcut icon" type="image/png">
<title>Validate OTP</title>
<meta name="description" content="OTP">
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
.otpf, .otpf1{
    flex: 1 0 0%;
    margin-right: 15px;
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


<div id="main-wrapper" class="kimpost-register">
  <div class="container-fluid px-0">
    <div class="row g-0 min-vh-100"> 
      <!-- Welcome Text
      ========================= -->
      <!-- <div class="col-md-5">
        <div class="hero-wrap h-100">
          <div class="hero-mask opacity-3 bg-primary"></div>
          <div class="hero-content w-100">
            <div class="container d-flex flex-column min-vh-100">
              <div class="row g-0">
                <div class="col-11 col-md-10 col-lg-9 mx-auto">
                  <div class="logo mt-5 mb-5 mb-md-0">
                    <a class="d-flex align-items-center text-5" href="{{ route('admin.login') }}">
                     <img src="{{ asset('public/hyloshiplogo.png') }}" alt="hyloship">
                    </a> </div>
                </div>
              </div>
              <div class="row g-0 my-auto">
                <div class="col-11 col-md-10 col-lg-9 mx-auto">
                    <h1 class="text-11  mb-4">We care about your account security.</h1>
                    <p class="text-4  lh-base mb-5">We are glad to see you again! Get access to your Orders, Products and Reports.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> -->
      <!-- Welcome Text End --> 
      
      <!-- Login Form
      ========================= -->
      <div class="col-md-5 d-flex flex-column mx-auto">
       
        <div class="container my-auto py-5 loginform">
          <div class="row g-0">
            <div class="col-11 col-md-10 col-lg-9 col-xl-8 mx-auto">
              <h3 class="fw-600 mb-4 txt-heading">Validate OTP</h3>
              <p class="text-muted mb-4">Please enter the OTP (one time password) to verify your account.</p>
              <form action="{{ route('admin.otp.store') }}" method="POST" id="myForm">
                    @csrf
                    <div class="form-group mb-3">
                    <label for="signin-email" class="control-label sr-only">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $admin->email }}" readonly required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-500">Enter 4 digit code</label>
                        <div class="row g-3">
                            <input type="text" class="form-control text-center otpf" name="otp1" maxlength="1" required autocomplete="off" autofocus>
                            <input type="text" class="form-control text-center otpf" name="otp2" maxlength="1" required autocomplete="off">
                            <input type="text" class="form-control text-center otpf" name="otp3" maxlength="1" required autocomplete="off">
                            <input type="text" class="form-control text-center otpf1" name="otp4" maxlength="1" required autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="button">
                        <div class="col-6"><button type="submit" id="btnsubmit" class="btn btn-primary btn-lg btn-block shadow-none mt-2" name="otp_verified"
                                value='1'>Verify</button>
                        </div><br>
                    </div>
                    <script>
                        var elts = document.getElementsByClassName('otpf')
                        Array.from(elts).forEach(function(elt){
                        elt.addEventListener("keyup", function(event) {
                            // Number 13 is the "Enter" key on the keyboard
                            if (event.keyCode === 13 || elt.value.length == 1) {
                            // Focus on the next sibling
                            elt.nextElementSibling.focus()
                            }
                        });
                        })
                      var elts1 = document.getElementsByClassName('otpf1')
                        Array.from(elts1).forEach(function(elt1){
                        elt1.addEventListener("keyup", function(event) {
                            // Number 13 is the "Enter" key on the keyboard
                            if (event.keyCode === 13 || elt1.value.length == 1) {
                            // submit form
                              if($("input[name=otp1]").val() !='' && $("input[name=otp2]").val() !='' && $("input[name=otp3]").val() !='' && $("input[name=otp4]").val() !='') 
                              {
                                $('#myForm').submit();
                              }
                            }
                        });
                        })
                    </script>
                </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Login Form End --> 
    </div>
    <div class="login_footer">
        <ul class="fotter_li">
            <li><a href="https://hyloship.com/about.html" target="_blank">About Us</a></li>
            <li><a href="https://hyloship.com/t_c.html" target="_blank">Terms and Conditions</a></li>
            <li><a href="https://hyloship.com/privacy-policy.html" target="_blank">Privacy</a></li>
            <li><a href="https://hyloship.com/refunds-and-cancellations.html" target="_blank">Refunds/Cancellations</a></li>
            <li><a href="https://hyloship.com/payments.html" target="_blank">Payments</a></li>
            <li><a href="https://hyloship.com/contact-us.html" target="_blank">Contact Us</a></li>
        </ul>
    </div>
  </div>
</div>


</body>
</html>