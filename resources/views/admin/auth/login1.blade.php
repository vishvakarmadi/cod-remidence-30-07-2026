<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hyloship | Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://hyloship.com/admin/public/uploads/585574842.png" rel="shortcut icon">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


<style>
:root {
  --pink: #EC1C63;
  --purple: #9B27AF;
  --indigo: #4B3FA7;
  --light-bg: #F5F7FB;
  --border: #E6E8EF;
  --text-dark: #0F172A;
  --text-muted: #6B7280;
}

* { box-sizing: border-box; }

html, body {
  margin: 0;
  height: 100%;
  overflow: hidden;
  font-family: 'Inter', sans-serif;
  background: var(--light-bg);
}

/* Layout */
.wrapper {
  display: flex;
  height: 100vh;
}

/* Left */
/*.left {*/
/*  flex: 50%;*/
/*  padding: 70px;*/
/*  background: linear-gradient(135deg, var(--pink), var(--purple), var(--indigo));*/
/*  color: #fff;*/
/*  display: flex;*/
/*  flex-direction: column;*/
/*  justify-content: center;*/
/*}*/
.left {
  flex: 50%;
  background-image: url("{{ asset('public/hyloshiplogin.jpg') }}");
  background-size: cover;       /* fills entire area */
  background-position: center;  /* keeps image centered */
  background-repeat: no-repeat;
}

.left-image {
  width: 100%;
  height: 100%;
  object-fit: cover; /* fills left side nicely */
}

.left h1 {
  font-size: 56px;
  font-weight: 700;
  line-height: 1.05;
}

.left span {
  font-style: italic;
}

.left p {
  margin-top: 40px;
  opacity: 0.9;
}

/* Right */
.right {
  flex: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.card {
  width: 550px;
  background: #fff;
  border-radius: 24px;
  padding: 45px;
  box-shadow: 0 30px 60px rgba(15,23,42,0.08);
}

.card h3 {
  margin-bottom: 20px;
  font-size: 22px;
  font-weight: 600;
}

/* Inputs */
input {
  width: 100%;
  padding: 16px;
  border-radius: 14px;
  border: 1px solid var(--border);
  font-size: 15px;
  margin-bottom: 16px;
}

/* Buttons */
.primary-btn {
  width: 100%;
  padding: 16px;
  background: var(--pink);
  border: none;
  color: #fff;
  border-radius: 14px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
}

.disabled-btn {
  width: 100%;
  padding: 16px;
  background: #E5E7EB;
  border-radius: 14px;
  border: none;
  font-size: 16px;
  color: #9CA3AF;
}

/* Text */
.terms {
  margin: 14px 0;
  font-size: 13px;
  text-align: center;
  color: var(--text-muted);
}

.terms a {
  color: #2563EB;
  text-decoration: none;
}

.track-title {
  margin-top: 25px;
  font-size: 18px;
  font-weight: 600;
}

/* Radio */
.radio {
  display: flex;
  gap: 30px;
  margin-bottom: 18px;
}

/* Remember Me Toggle */
.remember-wrap {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 12px 0 18px;
}

.toggle {
  position: relative;
  width: 42px;
  height: 22px;
}

.toggle input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  inset: 0;
  background-color: #E5E7EB;
  border-radius: 999px;
  transition: 0.3s;
}

.slider::before {
  content: "";
  position: absolute;
  height: 18px;
  width: 18px;
  left: 2px;
  bottom: 2px;
  background-color: white;
  border-radius: 50%;
  transition: 0.3s;
}

.toggle input:checked + .slider {
  background-color: var(--pink);
}

.toggle input:checked + .slider::before {
  transform: translateX(20px);
}

.remember-text {
  font-size: 14px;
  color: var(--text-dark);
}

/* Mobile */
@media (max-width: 900px) {
  .left { display: none; }
  .card { width: 100%; margin: 20px; }
}
</style>
</head>

<body>

<div class="wrapper">

  <div class="left">
  <img src="{{ asset('public/hyloshiplogo.jpeg') }}" alt="Hyloship" class="left-image">
</div>

  <div class="right">
    <div class="card">

      <h3>For Business</h3>

      <form action="{{ route('admin.login.store') }}" method="POST">
        @csrf
        <input type="email" name="email" placeholder="Enter email address *" required>
        <input type="password" name="password" placeholder="Enter password *" required>
        <div class="remember-wrap">
            <label class="toggle">
              <input type="checkbox" name="remember">
              <span class="slider"></span>
            </label>
            <span class="remember-text">Remember me</span>
          </div>
        <a href="{{ route('admin.forget_password') }}" style="float: right;"  class="terms">
            Forgot password?
        </a>
        <button class="primary-btn">Login to Hyloship</button>
      </form>

    <div class="terms">
        Don’t have an account?
        <a href="{{ route('admin.register', 1) }}">
            Register here
        </a>
    </div>

      <div class="terms">
        By continuing, you agree to our
        <a href="https://hyloship.com/terms-and-conditions">Terms & Conditions</a>
      </div>

      <div class="track-title">For Order Tracking</div>

        <form id="trackingForm">
          <input
            type="text"
            id="awb"
            name="awb"
            placeholder="Airway Bill Number (AWB) *"
            required
          >

          <button type="submit" class="primary-btn">
            Track
          </button>
        </form>


    </div>
  </div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  document.getElementById('trackingForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const awb = document.getElementById('awb').value.trim();

    if (!awb) return;

    window.location.href =
      'https://hyloship.com/admin/tracking/' + encodeURIComponent(awb);
  });

  toastr.options = {
    closeButton: true,
    progressBar: true,
    timeOut: 6000,
    positionClass: "toast-top-right"
  };

  @if (Session::has('error'))
    toastr.error("{{ Session::get('error') }}");
  @endif

  @if (Session::has('success'))
    toastr.success("{{ Session::get('success') }}");
  @endif

  @if (Session::has('warning'))
    toastr.warning("{{ Session::get('warning') }}");
  @endif

  @if (Session::has('info'))
    toastr.info("{{ Session::get('info') }}");
  @endif
</script>


</body>
</html>
