<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap');

*{
    padding:0;
    margin:0;
}
.container{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background-color:#eee;
}
.container .card{
    height:100%;
    width:100%;
    background-color:#fff;
    position:relative;
    box-shadow:0 15px 30px rgba(0,0,0,0.1);
    font-family: 'Poppins', sans-serif;
    border-radius:20px;
}
.container .card .form{
    width:100%;
    height:100%;
    display:flex;
}
.container .card .left-side{
    width:22%;
    background-color:#c93d00;
    height:100%;
  padding:20px 30px;
  box-sizing:border-box;
}
/*left-side-start*/
.left-heading{
    color:#fff;
}
.steps-content{
    margin-top:30px;
    color:#fff;
}
.steps-content p{
    font-size:12px;
    margin-top:15px;
}
.progress-bar{
    list-style:none;
    /*color:#fff;*/
    margin-top:30px;
    font-size:13px;
    font-weight:700;
    counter-reset:container 0;
}
.progress-bar li{
       position:relative;
       margin-left:40px;
       margin-top:50px;
       counter-increment:container 1;
      color:#000;
}
.progress-bar li::before{
    content:counter(container);
    line-height:25px;
    text-align:center;
    position:absolute;
    height:25px;
    width:25px;
    border:1px solid #000;
    border-radius:50%;
    left:-40px;
    top:-5px;
    z-index:10;
    background-color:#c93d00;
}
.progress-bar li::after{
    content: '';
    position: absolute;
    height: 90px;
    width: 2px;
    background-color: #000;
    z-index: 1;
    left: -27px;
    top: -70px;
}
.progress-bar li.active::after{
    background-color: #fff;
}
.progress-bar li:first-child:after{
  display:none;
}
/*.progress-bar li:last-child:after{*/
/*  display:none;  */
/*}*/
.progress-bar li.active::before{
    color:#fff;
      border:1px solid #fff;
}
.progress-bar li.active{
    color:#fff;
}
.d-none{
   display:none;
}
/*left-side-end*/
.container .card .right-side{
    width:80%;
    background-color:#fff;
    height:100%;
  border-radius:20px;
}
/*right-side-start*/
.main{
    display:none;
}
.active{
    display:block;
}
.main{
    padding:40px;
}
.main small{
    display:flex;
    justify-content:center;
    align-items:center;
    margin-top:2px;
    height:30px;
    width:30px;
    background-color:#ccc;
    border-radius:50%;
    color:yellow;
    font-size:19px;
}
.text{
    margin-top:20px;
}
.congrats{
    text-align:center;
}
.text p{
    margin-top:10px;
    font-size:13px;
    font-weight:700;
    color:#cbced4;
}
.input-text{
    margin:30px 0;
     display:flex;
    gap:20px;
}
.input-text .input-div{
    width:100%;
    position:relative;
}
input[type="text"],
input[type="email"],
input[type="number"]{
    width:100%;
    height:40px;
    border:none;
    outline:0;
    border-radius:5px;
    border:1px solid #cbced4;
    gap:20px;
    box-sizing:border-box;
    padding:0px 10px;
}
select{
    width:100%;
    height:40px;
    border:none;
    outline:0;
    border-radius:5px;
    border:1px solid #cbced4;
    gap:20px;
    box-sizing:border-box;
    padding:0px 10px;
}
.input-text .input-div span{
    position:absolute;
    top:10px;
    left:10px;
    font-size:14px;
    transition:all 0.5s;
    display: none;
}
.input-div input:focus ~ span,.input-div input:valid ~ span  {
    top:-17px;
    left:6px;
    font-size:12px;
    font-weight:600;
    display: inline;
}
.input-div input:focus::placeholder {
  color: transparent;
}
.input-div span{
    top:-15px;
    left:6px;
    font-size:10px;
}
.abutton,.skip,.skip1{    display: inline-table;
}
.abutton span,.skip span,.skip1 span{
    top: 10px;
    position: relative;
    left: 20px;
}
.back_button span{
    top: 10px;
    position: relative;
    left: 20px;
}
.buttons button,.abutton,.skip,.back_button,.skip1{
    height:40px;
    width:100px;
    border:none;
    border-radius:5px;
    background-color:#c93d00;
    font-size:12px;
    color:#fff;
    cursor:pointer;
}
.button_space{
    display:flex;
    gap:20px;
}
.button_space a:nth-child(1){
    background-color:#fff;
    color:#000;
    border:1px solid#000;
}
.user_card{
    margin-top:20px;
    margin-bottom:40px;
    height:60%;
    width:100%;
    border:1px solid #c7d3d9;
    border-radius:10px;
    display:flex;
    overflow:hidden;
    position:relative;
    box-sizing:border-box;
}
.user_card span{
    height:80px;
    width:100%;
    background-color:#dfeeff;
}
.circle{
    position:absolute;
    top:40px;
    left:60px;
}
.circle span{
    height:70px;
    width:70px;
    background-color:#fff;
    display:flex;
    justify-content:center;
    align-items:center;
    border:2px solid #fff;
    border-radius:50%;
}
.circle span img{
    width:100%;
    height:100%;
    border-radius:50%;
    object-fit:cover;
}
.social{
    display:flex;
    position:absolute;
    top:100px;
    right:10px;
}
.social span{
    height:30px;
    width:30px;
    border-radius:7px;
    background-color:#fff;
    border:1px solid #cbd6dc;
    display:flex;
    justify-content:center;
    align-items:center;
    margin-left:10px;
    color:#cbd6dc;
}
.social span i{
        cursor:pointer;
}
.heart{
    color:red !important;
}
.share{
        color:red !important;
}
.user_name{
    position:absolute;
    top:110px;
    margin:10px;
    padding:0 30px;
    display:flex;
    flex-direction:column;
    width:100%;
}
.user_name h3{
    color:#4c5b68;
}
.detail{
    /*margin-top:10px;*/
   display:flex;
   justify-content:space-between;
   margin-right:50px;
}
.detail p{
    font-size:12px;
    font-weight:700;
}
.detail p a{
    text-decoration:none;
    color:blue;
}
.checkmark__circle {
  stroke-dasharray: 166;
  stroke-dashoffset: 166;
  stroke-width: 2;
  stroke-miterlimit: 10;
  stroke: #7ac142;
  fill: none;
  animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}
.checkmark {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  display: block;
  stroke-width: 2;
  stroke: #fff;
  stroke-miterlimit: 10;
  margin: 10% auto;
  box-shadow: inset 0px 0px 0px #7ac142;
  animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
}
.checkmark__check {
  transform-origin: 50% 50%;
  stroke-dasharray: 48;
  stroke-dashoffset: 48;
  animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
}
@keyframes stroke {
  100% {
    stroke-dashoffset: 0;
  }
}
@keyframes scale {
  0%, 100% {
    transform: none;
  }
  50% {
    transform: scale3d(1.1, 1.1, 1);
  }
}
@keyframes fill {
  100% {
    box-shadow: inset 0px 0px 0px 30px #7ac142;
  }
}
.warning{
    border:1px solid red !important;
}
/*right-side-end*/
@media (max-width:750px) {
    .container{
        height:scroll;
    }
    .container .card {
        max-width: 350px;
        height:auto !important;
        margin:30px 0;
    }
    .container .card .right-side {
     width:100%;
    }
     .input-text{
         display:block;
     }
     .input-text .input-div{
  margin-top:20px;
}
    .container .card .left-side {
     display: none;
    }
}


.parent {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-template-rows: repeat(4, 1fr);
    grid-column-gap: 15px;
    grid-row-gap: 15px;
    }
    .card-header:first-child {
    border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
}
.text-white{
    color:#fff !important;
}
.card-header .card-title {
    height: 40px;
    position: relative;
    top: 10px;
    left: 32%;
}
</style>

<link rel="icon" href="{{ asset('public/favicon.svg') }}" type="image/x-icon">

<title>Profile</title>
<form id="myForm" action="{{ route('admin.profile.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="container">
    <div class="card" style="top:0px; bottom:0px; position:absolute;height:100%;">
        <div class="form">
            <div class="left-side">
                <div class="left-heading">
                    <h1>Hyloship</h1>
                </div>
                <div class="steps-content">
                    <h3>Step <span class="step-number">1</span></h3>
                    <p class="step-number-content active">Enter personal information to get closer to companies.</p>
                    <p class="step-number-content d-none">Get to know better by adding Documents for kyc verification.</p>
                    <p class="step-number-content d-none">You can add your own warehouse.</p>
                    <p class="step-number-content d-none">If you need any channel integrations.</p>
                </div>
                <ul class="progress-bar">
                    <li class="active"><h2><b>Profile</b></h2></li>
                    <li><h2><b>KYC Details</b></h2></li>
                    <li><h2><b>Add Warehouse</b></h2></li>
                    <li><h2><b>Channel Integration</b></h2></li>
                </ul>
            </div>
            <div class="right-side">
                <div class="main active">
                    <div class="text">
                        <h2>Personal Information</h2>
                        <p>Enter personal information to get closer to companies.</p>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <select id="country-dropdown" name="country" required require>
                                <option value="">Select Country</option>
                                @foreach ($countries as $data)
                                @if($data->id =='101')
                                <option value="{{ $data->id }}">
                                    {{ $data->name }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="input-div">
                            <select id="state-dropdown" name="state" required require>
                                <option>Select State</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" name="city" required require placeholder="City">
                            <span>City</span>
                        </div>
                        <div class="input-div">
                            <input type="text" name="zip_code" required require placeholder="Zip Code">
                            <span>Zip Code</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" name="address" required require placeholder="Personal Address">
                            <span>Personal Address</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" name="billing_address" required require placeholder="Billing Address">
                            <span>Billing Address</span>
                        </div>
                        
                    </div>
                    <div class="input-text">
                            <span><input type="checkbox" name="billing_same_personal_address" value="1" onchange="getaddressvalue()"> Billing Address Same as Personal address</span>
                    </div>
                    <div class="buttons">
                        <a class="abutton"><span><b>Next Step</b></span></a>
                        <a class="skip" style="background: #000"><span><b>Skip &nbsp;<i class="fa fa-arrow-circle-right" aria-hidden="true"></i></b></span></a>
                    </div>
                </div>
                <div class="main">
                    <div class="text">
                        <h2>KYC Information</h2>
                        <p>Get to know better by adding your Documents for kyc verification.</p>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <select name="company_type" id="company-dropdown" required require >
                                <option value="">Select Company Type</option>
                                <option value="Individual">Individual</option>
                                <option value="Partnership">Partnership</option>
                                <option value="Public Limited">Public Limited</option>
                                <option value="Private Limited">Private Limited</option>
                                <option value="LLP">LLP</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="input-div othersname">
                            <input type="text" name="company_type_name" required require placeholder="Company Type">
                            <span>Company Type</span>
                        </div>
                        
                    </div>
                        
                    <div class="input-text">
                        
                        <div class="input-div">
                            <input type="text" name="gst" id="gst_name" required require placeholder="GSTIN">
                            <span>GSTIN (Enter N/A for non-gst)</span>
                        </div>
                        <div class="input-div">
                            <label for=""><b>Attach GST Proof : </b></label>
                            <input type="file" name="doc_proof" required require accept=".pdf,.docs" id="doc_file" onchange="doc_validate()">
                            <div id="doc_error" style="color: #f00"></div>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" name="aadhaar_no" required require placeholder="Aadhaar Card Number">
                            <span>Aadhaar Card Number</span>
                        </div>
                        <div class="input-div">
                            <label for=""><b>Aadhaar Card : </b></label>
                            <input type="file" name="aadhaar" required require accept=".pdf,.docs" id="aadhaar" onchange="aadhaar_validate()">
                            <div id="aadhaar_error" style="color: #f00"></div>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" name="pan_no" required require placeholder="PAN Card Number">
                            <span>PAN Card Number</span>
                        </div>
                        <div class="input-div">
                            <label for=""><b>PAN Card : </b></label>
                            <input type="file" name="pan" required require accept=".pdf,.docs" id="pan" onchange="pan_validate()">
                            <div id="pan_error" style="color: #f00"></div>
                        </div>
                    </div>
                   
                    <div class="text">
                        <h2>Bank Information's</h2>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" name="bank_name" required require placeholder="Bank Name">
                            <span>Bank Name</span>
                        </div>
                        <div class="input-div">
                            <input type="text" name="ifsc_code" required require placeholder="IFSC Code">
                            <span>IFSC Code</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" name="account_no" required require placeholder="Account Number">
                            <span>Account Number</span>
                        </div>
                        <div class="input-div">
                            <input type="text" name="beneficiary_name" required require placeholder="Account Holder Name">
                            <span>Account Holder Name</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <label for=""><b>Blank Cheque : </b></label>
                            <input type="file" name="cheque" required require accept=".pdf,.docs" id="cheque" onchange="cheque_validate()">
                            <div id="cheque_error" style="color: #f00"></div>
                        </div>
                        <div class="input-div">
                            <select name="account_type" id="company-dropdown" required require >
                                <option value='' >Account Type</option>
                                <option  value='Saving'>Saving</option>
                                <option  value='Current'>Current</option>
                                <option  value='Overdraft'>Overdraft</option>
                                <option  value='Cash Credit'>Cash Credit</option>
                                <option  value='Loan Account'>Loan Account</option>
                                <option  value='NRE'>NRE</option>
                            </select>
                        </div>
                    </div>
                    <div class="buttons button_space">
                        <a class="back_button"><span><b>Back</b></span></a>
                        <a class="abutton"><span><b>Next Step</b></span></a>
                        <a class="skip" style="background: #000"><span><b>Skip &nbsp;<i class="fa fa-arrow-circle-right" aria-hidden="true"></i></b></span></a>
                    </div>
                </div>
                <div class="main">
                    <div class="text">
                        <h2>Add Warehouse</h2>
                        <p>You can add your own warehouse.</p>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" name="ware_name" required require placeholder="Name">
                            <span>Name</span>
                        </div>
                        <div class="input-div">
                            <input type="text" name="ware_contact_name" required require placeholder="Contact Name">
                            <span>Contact Name</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" name="ware_company" required require placeholder="Company">
                            <span>Company</span>
                        </div>
                        <div class="input-div">
                            <input type="email" name="ware_email" required require placeholder="Email">
                            <span>Email</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="number" name="ware_phone" required require placeholder="Mobile">
                            <span>Mobile</span>
                        </div>
                        <div class="input-div">
                            <input type="text" name="ware_address" required require placeholder="Address">
                            <span>Address</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" name="ware_address_2" placeholder="Address 2">
                            <span>Address 2</span>
                        </div>
                        <div class="input-div">
                            <input type="text" name="ware_city" required require placeholder="City">
                            <span>City</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" name="ware_state" required require placeholder="State">
                            <span>State</span>
                        </div>
                        <div class="input-div">
                            <select name="ware_country" required require>
                                <option value="">Select Country</option>
                                @foreach ($countries as $data)
                                @if($data->id =='101')
                                <option value="{{ $data->id }}">
                                    {{ $data->name }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="number" name="ware_pincode" required require placeholder="Zip Code">
                            <span>Zip Code</span>
                        </div>
                        <div class="input-div">
                            <input type="text" name="ware_gst_no" required require placeholder="GST No">
                            <span>GST No</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="number" name="ware_latitude" placeholder="Latitude">
                            <span>Latitude</span>
                        </div>
                        <div class="input-div">
                            <input type="number" name="ware_longitude"  placeholder="Longitude">
                            <span>Longitude</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" name="ware_fssai_licence"  placeholder="FSSAI Licence">
                            <span>FSSAI Licence</span>
                        </div>
                        <div class="input-div">
                            <input type="text" name="ware_note"  placeholder="Note">
                            <span>Note</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <span><input type="checkbox" name="ware_default" value="1"> Set as Default Address</span>
                        </div>
                    </div><br>
                    <div class="buttons button_space">
                        <a class="back_button"><span><b>Back</b></span></a>
                        <a class="abutton"><span><b>Next Step</b></span></a>
                        <a class="skip" style="background: #000"><span><b>Skip &nbsp;<i class="fa fa-arrow-circle-right" aria-hidden="true"></i></b></span></a>
                    </div>
                </div>



                <div class="main">
                    <div class="text">
                        <h2>Channel Integration</h2>
                        <p>If you need any channel integrations.</p>
                    </div>
                    <div class="user_card">
                        <span><h1 style="position: absolute;top:15px;left:10px;">Channels</h1></span>
                        <div class="user_name">
                            <div class="parent">
                                <div class="card">
                                    <div class="card-header bg--primary d-flex justify-content-between flex-wrap" style="background: #c93d00">
                                        <h4 class="card-title text-white" > Integrate </h4>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="channel-image">
                                            <img src="{{ asset('public/shopify.ico') }}" alt="Template" class="w-100" style="padding: 40px;width:140px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header bg--primary d-flex justify-content-between flex-wrap" style="background: #c93d00">
                                        <h4 class="card-title text-white" > Integrate </h4>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="channel-image">
                                            <img src="{{ asset('public/amazon.png') }}" alt="Template" class="w-100" style="padding: 40px;width:175px;">
                                        </div>
                                    </div> 
                                </div>
                                <div class="card">
                                    <div class="card-header bg--primary d-flex justify-content-between flex-wrap" style="background: #c93d00">
                                        <h4 class="card-title text-white" > Integrate </h4>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="channel-image">
                                            <img src="{{ asset('public/flipkart.png') }}" alt="Template" class="w-100" style="padding: 40px;width:185px;">
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            
                        </div>
                    </div>
                       <p>Once you successfully log in, you will find an option to integrate with multiple shipping channels</p>
                    <br>
                    <div class="buttons button_space">
                        <a class="back_button"><span><b>Back</b></span></a>
                        <button class="submit_button">Submit</button>
                        <a class="skip1" style="text-decoration-line: none;background: #000"><span><b>Finish Later <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></b></span></a>
                    </div>
                </div>
                 <div class="main">
                     <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                         <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>

                    <div class="text congrats">
                        <h2>Congratulations!</h2>
                        <p>Thanks You for Your Details</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>


<script>
    function doc_validate() {
      const input = document.getElementById('doc_file');
      const maxSizeInBytes = 2 * 1024 * 1024; // 5MB
  
      if (input.files.length > 0) {
        const fileSize = input.files[0].size;
        if (fileSize > maxSizeInBytes) {
          document.getElementById('doc_error').innerHTML = 'File size exceeds the maximum limit (2MB).';
          input.value = ''; // Clear the input
        } else {
          document.getElementById('doc_error').innerHTML = '';
        }
      }
    }

    function pan_validate() {
      const input = document.getElementById('pan');
      const maxSizeInBytes = 2 * 1024 * 1024; // 5MB
  
      if (input.files.length > 0) {
        const fileSize = input.files[0].size;
        if (fileSize > maxSizeInBytes) {
          document.getElementById('pan_error').innerHTML = 'File size exceeds the maximum limit (2MB).';
          input.value = ''; // Clear the input
        } else {
          document.getElementById('pan_error').innerHTML = '';
        }
      }
    }

    function aadhaar_validate() {
      const input = document.getElementById('aadhaar');
      const maxSizeInBytes = 2 * 1024 * 1024; // 5MB
  
      if (input.files.length > 0) {
        const fileSize = input.files[0].size;
        if (fileSize > maxSizeInBytes) {
          document.getElementById('aadhaar_error').innerHTML = 'File size exceeds the maximum limit (2MB).';
          input.value = ''; // Clear the input
        } else {
          document.getElementById('aadhaar_error').innerHTML = '';
        }
      }
    }

    function cheque_validate() {
      const input = document.getElementById('cheque');
      const maxSizeInBytes = 2 * 1024 * 1024; // 5MB
  
      if (input.files.length > 0) {
        const fileSize = input.files[0].size;
        if (fileSize > maxSizeInBytes) {
          document.getElementById('cheque_error').innerHTML = 'File size exceeds the maximum limit (2MB).';
          input.value = ''; // Clear the input
        } else {
          document.getElementById('cheque_error').innerHTML = '';
        }
      }
    }

    function chkcompanytype(){

    }
  </script>




<script>
    var skip=document.querySelectorAll(".skip");
    var next_click=document.querySelectorAll(".abutton");
    var main_form=document.querySelectorAll(".main");
    var step_list = document.querySelectorAll(".progress-bar li");
    var num = document.querySelector(".step-number");
    let formnumber=0;

    skip.forEach(function(next_click_form){
        next_click_form.addEventListener('click',function(){
        formnumber++;
        updateform();
        progress_forward();
        contentchange();
        });
    });

    next_click.forEach(function(next_click_form){
        next_click_form.addEventListener('click',function(){
            if(!validateform()){
                return false
            }
        formnumber++;
        updateform();
        progress_forward();
        contentchange();
        });
    });

    var back_click=document.querySelectorAll(".back_button");
    back_click.forEach(function(back_click_form){
        back_click_form.addEventListener('click',function(){
        formnumber--;
        updateform();
        progress_backward();
        contentchange();
        });
    });

    var username=document.querySelector("#user_name");
    var shownname=document.querySelector(".shown_name");

    var submit_click=document.querySelectorAll(".submit_button");
    submit_click.forEach(function(submit_click_form){
        submit_click_form.addEventListener('click',function(){
        updateform1();
        });
    });


var later=document.querySelector(".skip1");
later.addEventListener('click',function(){
    formnumber++;
    main_form.forEach(function(mainform_number){
        mainform_number.classList.remove('active');
    })
    main_form[formnumber].classList.add('active');
    setTimeout(function() {
        window.location.href = '/admin/dashboard';
      }, 2000);
});

function updateform1(){
    main_form.forEach(function(mainform_number){
        mainform_number.classList.remove('active');
    })
    main_form[formnumber].classList.add('active');
    if(validateform1()){
        formnumber++;
        setTimeout(function() {
        $('#myForm').submit();
      }, 1000);
    } else {
        alert('Fill all the required fields..'); 
    }
}

function updateform(){
    main_form.forEach(function(mainform_number){
        mainform_number.classList.remove('active');
    })
    main_form[formnumber].classList.add('active');
}

function progress_forward(){
    num.innerHTML = formnumber+1;
    step_list[formnumber].classList.add('active');
}

function progress_backward(){
    var form_num = formnumber+1;
    step_list[form_num].classList.remove('active');
    num.innerHTML = form_num;
}

var step_num_content=document.querySelectorAll(".step-number-content");

 function contentchange(){
     step_num_content.forEach(function(content){
        content.classList.remove('active');
        content.classList.add('d-none');
     });
     step_num_content[formnumber].classList.add('active');
 }

 function validateform1(){
    validate=true;
    var validate_inputs=document.querySelectorAll(".main input");
    var select=document.querySelectorAll(".main select");
    validate_inputs.forEach(function(vaildate_input){
        vaildate_input.classList.remove('warning');
        if(vaildate_input.hasAttribute('require')){
            if(vaildate_input.value.length==0){
                validate=false;
                vaildate_input.classList.add('warning');
            }
        }
    });
    select.forEach(function(row){
        row.classList.remove('warning');
        if(row.hasAttribute('require')){
            if(row.value.length==0){
                validate=false;
                row.classList.add('warning');
            }
        }
    });
    return validate;

}

function validateform(){
    validate=true;
    var validate_inputs=document.querySelectorAll(".main.active input");
    var select=document.querySelectorAll(".main.active select");
    validate_inputs.forEach(function(vaildate_input){
        vaildate_input.classList.remove('warning');
        if(vaildate_input.hasAttribute('require')){
            if(vaildate_input.value.length==0){
                validate=false;
                vaildate_input.classList.add('warning');
            }
        }
    });
    select.forEach(function(row){
        row.classList.remove('warning');
        if(row.hasAttribute('require')){
            if(row.value.length==0){
                validate=false;
                row.classList.add('warning');
            }
        }
    });
    return validate;

}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        $('#country-dropdown').on('change', function() {
            var idCountry = this.value;
            $.ajax({
                url: "{{ route('states') }}",
                data: {
                    country_id: idCountry,
                },
                success: function(result) {
                    $('#state-dropdown').html(
                        '<option value="">Select State</option>');
                    $.each(result, function(key, value) {
                        $("#state-dropdown").append('<option value="' +
                            value
                            .id + '">' + value.name + '</option>');
                    });

                }
            });
        });
    });

    function getaddressvalue(){
        var checkbox = document.querySelector('input[name="billing_same_personal_address"]');
        var billingAddressInput = document.querySelector('input[name="billing_address"]');
        var isChecked = checkbox.checked;
        if (isChecked) {
            billingAddressInput.removeAttribute('required');
            billingAddressInput.removeAttribute('require');
            // Here you can perform further actions if needed
        } else {
            billingAddressInput.setAttribute('require', 'true');
            billingAddressInput.setAttribute('required', 'true');
            // Here you can perform further actions if needed
        }
    }
    $('#company-dropdown').on('change', function() {
        var idCompany = this.value;
        var billingAddressInput = document.querySelector('input[name="company_type_name"]');
        if(idCompany == 'Other'){
            $('.othersname').show();
            billingAddressInput.setAttribute('require', 'true');
            billingAddressInput.setAttribute('required', 'true');
        }else{
            $('.othersname').hide();
            billingAddressInput.removeAttribute('required');
            billingAddressInput.removeAttribute('require');
        }
        
    });
    $('#gst_name').on('change', function() {
        var idgst = this.value;
        var doc_proof = document.querySelector('input[name="doc_proof"]');
        if(idgst =='N/A'){
            doc_proof.removeAttribute('required');
            doc_proof.removeAttribute('require');
        }else{
            doc_proof.setAttribute('require', 'true');
            doc_proof.setAttribute('required', 'true');
        }
        
        
    });
    </script>
