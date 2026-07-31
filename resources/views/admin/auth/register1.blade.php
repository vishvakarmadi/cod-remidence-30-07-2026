@php
  $general_setting = DB::table('general_settings')->where('id',1)->first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hyloship | Register</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://hyloship.com/admin/public/uploads/585574842.png" rel="shortcut icon">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Toastr -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<style>
:root {
  --pink:#EC1C63;
  --purple:#9B27AF;
  --indigo:#4B3FA7;
  --bg:#F5F7FB;
  --border:#E6E8EF;
  --text:#0F172A;
  --muted:#6B7280;
}

*{box-sizing:border-box}
html,body{
  margin:0;
  height:100%;
  overflow:hidden;
  font-family:'Inter',sans-serif;
  background:var(--bg);
}

.wrapper{
  display:flex;
  height:100vh;
}
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
/* LEFT */
/*.left{*/
/*  flex:50%;*/
/*  padding:80px;*/
/*  background:linear-gradient(135deg,var(--pink),var(--purple),var(--indigo));*/
/*  color:#fff;*/
/*  display:flex;*/
/*  flex-direction:column;*/
/*  justify-content:center;*/
/*}*/

/*.left img{height:36px;margin-bottom:60px}*/
.left h1{font-size:52px;line-height:1.1}
.left span{font-style:italic}
.left p{margin-top:40px;opacity:.9}

/* RIGHT */
.right{
  flex:50%;
  display:flex;
  align-items:center;
  justify-content:center;
}

.card{
  width:640px;
  max-height:90vh;
  overflow:auto;
  background:#fff;
  border-radius:24px;
  padding:40px;
  box-shadow:0 30px 60px rgba(15,23,42,.08);
}

.card h3{
  margin-bottom:20px;
  font-size:22px;
}

/* FORM */
input,select{
  width:100%;
  padding:14px;
  border-radius:12px;
  border:1px solid var(--border);
  margin-bottom:14px;
  font-size:14px;
}

.row{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:14px;
}

label{
  font-size:13px;
  font-weight:500;
}

.primary-btn{
  width:100%;
  padding:16px;
  border:none;
  border-radius:14px;
  background:var(--pink);
  color:#fff;
  font-size:16px;
  font-weight:600;
  cursor:pointer;
}

.terms{
  font-size:13px;
  margin:14px 0;
}

.terms a{color:#2563EB;text-decoration:none}

.footer{
  text-align:center;
  margin-top:14px;
  font-size:14px;
}

.terms-inline {
  margin: 14px 0;
}

.checkbox-line {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  font-size: 13px;
  color: var(--text);
  cursor: pointer;
}

.checkbox-line input {
  margin-top: 2px;
  width: 16px;
  height: 16px;
}


/* MOBILE */
@media(max-width:900px){
  .left{display:none}
  .card{width:100%;margin:20px}
}
</style>
</head>

<body>

<div class="wrapper">

  <!-- LEFT -->
  <!--<div class="left">-->
  <!--  <img src="{{ asset('public/hyloshiplogo.png') }}">-->
  <!--  <h1>Shipping That Helps<br><span>Your Business Grow</span></h1>-->
  <!--  <p>Trusted by 100,000+ Businesses Across India</p>-->
  <!--</div>-->
<div class="left">
  <img src="{{ asset('public/hyloshiplogo.jpeg') }}" alt="Hyloship" class="left-image">
</div>
  <!-- RIGHT -->
  <div class="right">
    <div class="card">

      <h3>Create your Hyloship Account</h3>

      <form method="POST" action="{{ route('admin.register.store') }}">
        @csrf

        <label>Monthly Shipping Volume *</label>
        <select name="volume" required>
          <option value="1-100">1–100</option>
          <option value="100-1000">100–1000</option>
          <option value="1000-10000">1000–10000</option>
          <option value="10000+">10000+</option>
        </select>

        <div class="row">
          <input type="text" name="name" placeholder="Full Name *" required>
          <input type="text"
                name="company_name"
                class="form-control bg-light border-light"
                value="{{ old('company_name') }}"
                required
                minlength="2"
                maxlength="150"
                pattern="^[a-zA-Z0-9 .,&'-]+$"
                title="Only letters, numbers, spaces, and . , & ' - allowed"
                placeholder="Company Name">
        </div>

        <div class="row">
          <input type="email" name="email" placeholder="Email *" required>
          <input type="text"
                        name="mobile"
                        class="form-control bg-light border-light"
                        value="{{ old('mobile') }}"
                        required
                        inputmode="numeric"
                        pattern="[0-9]{10}"
                        maxlength="10"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        title="Please enter a 10-digit mobile number"
                        placeholder="Mobile number">
        </div>

        <input type="url"
                            name="company_url"
                            class="form-control bg-light border-light"
                            value="{{ old('company_url') }}"
                            placeholder="https://example.com">
        <input type="text" name="company_address" placeholder="Office Address *" required>

        <div class="row">
          <input type="password" name="password"
                            minlength="6" class="form-control bg-light border-light" placeholder="Password *"  required>
          <input type="password" name="re_password"
                            class="form-control bg-light border-light"  minlength="6" placeholder="Confirm Password *" required>
        </div>

        <input type="hidden" name="role_id" value="4">
        <input type="hidden" name="company_id" value="{{ $company_id }}">

        <div class="terms terms-inline">
            <label class="checkbox-line">
              <input type="checkbox" name="term" required>
              <span>
                I agree to the
                <a href="https://hyloship.com/terms-and-conditions-for-hyloship/" target="_blank">Terms</a> &
                <a href="https://hyloship.com/privacy-policy/" target="_blank">Privacy Policy</a>
              </span>
            </label>
          </div>


        <button class="primary-btn">Create Account</button>

        <div class="footer">
          Already have an account?
          <a href="{{ route('admin.login1') }}">Login</a>
        </div>

      </form>
    </div>
  </div>
</div>

<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
toastr.options = { closeButton:true, progressBar:true };

@if ($errors->any())
  @foreach ($errors->all() as $error)
    toastr.error("{{ $error }}");
  @endforeach
@endif

@if (Session::has('success'))
  toastr.success("{{ Session::get('success') }}");
@endif
</script>

</body>
</html>
