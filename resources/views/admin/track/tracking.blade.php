<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/x-icon">
<title>Hyloship - Tracking</title>
<meta name="description" content="Tracking">
<meta name="author" content="https://hyloship.com/">

<!-- Web Fonts
========================= -->
<!-- <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'> -->

<!-- Stylesheet
========================= -->
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
<!-- MAIN CSS -->
<!-- <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}"> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/css/stylesheet1.css') }}"> -->

<link href="{{ asset('public/toastr/toastr.css') }}" rel="stylesheet" />
<script src="{{ asset('public/toastr/jquery-3.6.0.min.js') }}" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="{{ asset('public/toastr/popper.min.js') }}" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="{{ asset('public/toastr/toastr.js') }}"></script>
<style>
  .container-fluid {
    background-image: url(https://d2kh7o38xye1vj.cloudfront.net/wp-content/uploads/2023/04/tracking.png);
    background-repeat: no-repeat;
    background-size: 70%;
    background-position: bottom left;
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
   
}
.status-delivery-heading{
  color: #000;
    padding: 20px;
    text-decoration: underline;
    font-size: large;
}
.ready_to_go {
    color: #f58220;
    font-size: 25px;
    margin-top: 20px;
}
.track_details {
    margin: 0;
    padding: 0;
    margin-top: 30px;
    margin-bottom: 60px;
}
.track_details li {
    list-style: none;
    font-size: 14px;
    margin-bottom: 8px;
}
.track {
    position: relative;
    /*background-color: #ddd;*/
    height: 7px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    margin-bottom: 40px;
margin-top: 35px;
}

.track .step {
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    width: 25%;
    margin-top: -12px;
    text-align: center;
    position: relative
}

.track .step.active:before {
    background: #f58220;
	left: 50%;
	position: absolute;
}
.track .step.current .icon {
  background: #f58220;
  color: #fff;
}
.track .step::before {
    height: 7px;
    position: absolute;
    content: "";
    width: 100%;
    left: 50%;
    top: 12px; background:#ddd;
}

.track .step.active .icon {
    background: #f58220;
    color: #fff
}

.track .icon {
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    position: relative;
    border-radius: 100%;
    background: #ddd;
	font-size: 12px;
  z-index: 9;
}

.track .step.active .text {
    color: #000
}

.track .text {
    display: block;
    margin-top: 7px;
	font-size: 14px;
font-weight: 600;
}
.track .step.last::before { display:none;
}
.track .step.current.rto .icon{
  background: #f10303;
}
.scrollbar {
    height: 258px;
    width: 100%;
    /* background: #fff; */
    overflow-y: scroll;
    margin-bottom: 0;}

#style-4::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
  background-color: #F5F5F5;
}

#style-4::-webkit-scrollbar {
  width: 10px;
  background-color: #F5F5F5;
}

#style-4::-webkit-scrollbar-thumb {
  background-color: #000000;
  border: 2px solid #555555;
}

.force-overflow {
  min-height: 450px;
}
/*.step.current.last.rto::after{
  content: "";
  height: 7px;
    position: absolute;
    content: "";
    width: 100%;
    right: 50%;
    top: 12px;
    background: #f10303;
}*/
/*--------------------------------------------------------------
# Shipment Progress
--------------------------------------------------------------*/

.shipment_progress{ border-bottom:1px solid #000;padding-top: 40px;}

.wrapper {
  margin: 0 auto;
  width: 330px;
  font-family: 'Helvetica';
  font-size: 14px;
  border: 1px solid #ccc;
  padding: 20px;
}
.step-progress {
  position: relative;
  padding-left: 45px;
  list-style: none;
  margin-top: 30px;
}
.step-progress::before {
  display: inline-block;
  content: '';
  position: absolute;
  top: 0;
  left: 24px;
  width: 10px;
  height: 100%;
  border-left: 2px solid #ccc;
}
.step-progress-item {
  position: relative;
  counter-increment: list;
  padding-top: 0px;
  padding-left: 10px;
}
.step-progress-item:not(:last-child) {
  padding-bottom: 20px;
}
.step-progress-item::before {
  display: inline-block;
  content: '';
  position: absolute;
  left: -21px;
  height: 100%;
  width: 10px;
}
.step-progress-item::after {
  content: '';
  display: inline-block;
  position: absolute;
  top: 0;
  left: -38px;
  width: 32px;
  height: 32px;
  border: 2px solid #ccc;
  border-radius: 50%;
  background-color: #fff;
}
.step-progress-item.is-done::before {
  border-left: 2px solid #f58220;
}
.step-progress-item.is-done::after {
	background: url(../img/tick.jpg) no-repeat #f58220;
border: 2px solid #f58220;
background-size: 10px 9px;
background-repeat: no-repeat;
background-position: 6px 7px;
width: 25px;
height: 25px;
top: 0;
left: -33px;
font-size: 14px;
text-align: center;
color: #f58220;
border: 2px solid #f58220;
background-color: #f58220;
}

.step-progress-item.current::before {
  border-left: 2px solid #f58220;
}
.step-progress-item.current::after {background: url(../img/tick.jpg) no-repeat #f58220;
border: 2px solid #f58220;

background-size: 10px 9px;
background-repeat: no-repeat;
background-position: 6px 7px;
width: 25px;
height: 25px;
top: 0;
left: -33px;
font-size: 14px;
text-align: center;
color: #f58220;
border: 2px solid #f58220;
background-color: #f58220;

  
}
.step-progress strong {display: block;}
.progress_content p{margin-bottom: 3px;font-size: 13px;color: #425875;}
.progress_content p small{ color:#848585; font-size:11px;}
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



<div id="main-wrapper" class="kimpost-register headerOne Section gradientBanner contactFormSection">
  <div class="container-fluid px-0">
    <div class="row g-0 min-vh-100" style="justify-content: center;"> 

      <div class="col-md-8 d-flex flex-column">
        
        <div class="container my-auto py-5" style="">
          <div class="row g-0">
            <!-- <div class="col-12 col-md-10 col-lg-9 col-xl-8 mx-auto"> -->
                <div class="col-md-12 col-lg-4 d-flex m-b-20 mb-lg-0 " style="height:50%">
                  <div style="background: transparent linear-gradient(244deg, #f4a248, #ebebff) 0 0 no-repeat padding-box !important;width: 100%;padding:20px">
                      <div class="status-delivery-heading">Order Summary</div>
                      <div class="order-details">
                          <div class="ready_to_go">{!! $ordernew->status !!}</div>
                          <hr style="border-top: 2.5px solid black;">
                          <ul class="track_details">
                              <li><span>ORDER PLACED ON :</span>
                                            {{ \Carbon\Carbon::parse($ordernew->shipped_date)->format('d M, Y') }}
                                           &nbsp;
                                            {{ \Carbon\Carbon::parse($ordernew->shipped_date)->format('H:i') }}
                              </li>
                              <li><span>COURIER :</span>  {{ isset($couriers[$ordernew->ship_courier_id]) ? $couriers[$ordernew->ship_courier_id]['name'] : 'Unknown' }}</li>
                              <li><span>TRACKING ID :</span> 
                                  @if(isset($couriers[$ordernew->ship_courier_id]))
                                      <a href="{{ $couriers[$ordernew->ship_courier_id]['url'] . $ordernew->tracking_info }}" target="_blank"> {{ $ordernew->tracking_info }}</a>
                                  @else
                                      {{ $ordernew->tracking_info }}
                                  @endif
                              </li>
                              <li><span>ORDER ID :</span>  {{$ordernew->vendor_order_id}}</li>
                              <li><span>EDD :</span> 
                                  @if($ordernew->expected_delivery_date)
                                      {{ \Carbon\Carbon::parse($ordernew->expected_delivery_date)->format('d M, Y') }}
                                  @else
                                      TBA
                                  @endif
                              </li>
                          </ul>
                      </div>
                  </div>
                </div>
                <div class="col-md-12 col-lg-8" style="padding:20px;height:600px;background: transparent linear-gradient(244deg, #f4a248, #ebebff) 0 0 no-repeat padding-box !important;">
                <div style=" width: 100%;">
                  <div class="status-delivery-heading">Status Delivery

                  </div>
                  <div class="track">

                    <div class="step active">
                      <span class="icon"> 
                         <i class="fa fa-check"></i>
                      </span>
                      <span class="text">Booked</span>
                    </div>

<!--                    @if($ordernew->manifest_date !='' && $orderlogs =='')
                    <div class="step   current last">
                      <span class="icon"> 
                         <i class="fa fa-check"></i>
                      </span>
                      <span class="text">Pending Pickup</span>
                    </div>
                    @endif-->
                    @if($orderlogs !='')
                    <?php $i=0; ?>
                      @foreach($orderlogs as $logs)
                          <div class="step @if($logs->new_value =='NDR' || $logs->new_value =='RTO' ) rto @else current  @endif @if($i == count($orderlogs)-1) last @else active @endif">
                          <span class="icon"> 
                            <i class="fa @if($logs->new_value =='Delivered' || $logs->new_value =='RTO') fa-truck @else fa-check @endif"></i>
                          </span>
                          <span class="text">
                              @if($logs->new_value =='Shipped')
                              Courier Assigned
                              @else
                              {{$logs->new_value}}
                              @endif
                          </span>
                        </div>
                        <?php $i++; ?>
                      @endforeach
                    @endif

                    <!-- <div class="step  current last">
                      <span class="icon"> 
                         <i class="fa fa-check"></i>
                      </span>
                      <span class="text">Pending Pickup</span>
                    </div> -->
                    <!-- <div class="step active">
                      <span class="icon">  <i class="fa fa-check"></i></span>
                      <span class="text">In Transit</span>
                    </div>
                    
                    <div class="step active">
                      <span class="icon">  <i class="fa fa-check"></i></span>
                      <span class="text">Out For Delivery</span>
                    </div>
                    <div class="step current last rto">
                      <span class="icon">  <i class="fa fa-truck"></i></span>
                      <span class="text">Rto</span>
                    </div>               -->
                   </div>
                  
                  </div>
                  <div class="status-delivery-heading">Shipment Progress</div>
                  <ul class="step-progress scrollbar" id="style-4">
                      <?php for($j=count($progress)-1;$j>=0;$j--){ ?>
                        <li class="step-progress-item is-done">
                          <div class="progress_content">
                            <p>{{$progress[$j]['date']}}</p>
                            <p>
                                <strong>{{$progress[$j]['action']}}</strong>
                                @if($progress[$j]['remarks'] !='')
                                - {{$progress[$j]['remarks']}}
                                @endif
                            </p>
                            <p>{{$progress[$j]['place']}}</small></p>
                          </div>
                        </li>
                      <?php } ?>
                   </ul>
                </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</div>


</body>
</html>