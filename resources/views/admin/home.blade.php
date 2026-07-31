@extends('admin.admin_layouts')

@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp

<style>
    .pt-30 {
    background-color: #aae4e4;
}
    .courier ul li {
        list-style : none;
    }

    label.radio-card {
    cursor: pointer;
    }
    .c-dashboardInfo {
  margin-bottom: 15px;
}
.c-dashboardInfo .wrap {
  background: #ffffff;
  box-shadow: 2px 10px 20px rgba(0, 0, 0, 0.1);
  border-radius: 7px;
  text-align: center;
  position: relative;
  overflow: hidden;
  padding: 40px 25px 20px;
  height: 100%;
}
.c-dashboardInfo__title,
.c-dashboardInfo__subInfo {
  color: #6c6c6c;
  font-size: 1.18em;
}
.c-dashboardInfo span {
  display: block;
}
.c-dashboardInfo__count {
  font-weight: 600;
  font-size: 2.5em;
  line-height: 64px;
  color: #323c43;
}
.c-dashboardInfo .wrap:after {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 10px;
  content: "";
}

.c-dashboardInfo:nth-child(1) .wrap:after {
  background: linear-gradient(82.59deg, #00c48c 0%, #00a173 100%);
}
.c-dashboardInfo:nth-child(2) .wrap:after {
  background: linear-gradient(81.67deg, #0084f4 0%, #1a4da2 100%);
}
.c-dashboardInfo:nth-child(3) .wrap:after {
  background: linear-gradient(69.83deg, #0084f4 0%, #00c48c 100%);
}
.c-dashboardInfo:nth-child(4) .wrap:after {
  background: linear-gradient(81.67deg, #ff647c 0%, #1f5dc5 100%);
}
.c-dashboardInfo__title svg {
  color: #d7d7d7;
  margin-left: 5px;
}
.MuiSvgIcon-root-19 {
  fill: currentColor;
  width: 1em;
  height: 1em;
  display: inline-block;
  font-size: 24px;
  transition: fill 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
  user-select: none;
  flex-shrink: 0;
}
 

 </style> 
<!-- Main body part  -->
<div class="container-fluid">
    <!-- Page header section  -->
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12 col-sm-12">
                <h1>Hi, Welcomeback! </h1>
                <span>{{$session->name}}'s Dashboard,</span>
            </div>
        </div>
    </div>

    
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-12">
                <div id="navbar-animmenu">
                    <ul class="show-dropdown main-navbar">
                        <div class="hori-selector" style="margin-left: 20px;"><div class="left"></div><div class="right"></div></div>
                        <li class="active"><a href="javascript:void(0);" data-id="Overview">Overview</a></li>
                        <li><a href="javascript:void(0);" data-id="channel">Shipment Status</a></li>
                        <li><a href="javascript:void(0);" data-id="top10">Top 10</a></li>
                        <li><a href="javascript:void(0);" data-id="ndr">NDR</a></li>
                    </ul>
                </div>
                <div class="card top_report card mt-30 Overview">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="body">
                                <div class="clearfix">
                                    <div class="float-left">
                                        <i class="fa fa-2x fa-product-hunt text-col-default"></i>
                                    </div>
                                    <a href="" style="color:#444;">
                                        <div class="number float-right text-right">
                                            <h4>Today so far</h4>
                                            <span class="font700">Orders - {{ $today_count }}</span><br>

                                            <span class="font700">Total Amount - {{ $today }}</span>

                                        </div>
                                    </a>
                                </div>
                                <div class="progress progress-xs progress-transparent custom-color-default mb-0 mt-3">
                                    <div class="progress-bar" style="background:#444444;"
                                        data-transitiongoal="{{ intval($today_count) }}"></div>
                                </div>
                                <small class="text-muted">{{ intval($today_count) }}% of Orders</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="body">
                                <div class="clearfix">
                                    <div class="float-left">
                                        <i class="fa fa-2x fa-bar-chart-o text-col-yellow"></i>
                                    </div>
                                    <a href="" style="color:#444;">
                                        <div class="number float-right text-right">
                                            <h6>Yesterday</h6>
                                            <span class="font700"> Orders - {{ $yesterday_count }}</span><br>
                                            <span class="font700"> Total Amount - {{ $yesterday }}</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="progress progress-xs progress-transparent custom-color-yellow mb-0 mt-3">
                                    <div class="progress-bar" data-transitiongoal="{{ intval($yesterday_count) }}">
                                    </div>
                                </div>
                                <small class="text-muted">{{ intval($yesterday_count) }}% of Orders</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="body">
                                <div class="clearfix">
                                    <div class="float-left">
                                        <i class="fa fa-2x fa-thumbs-up text-col-green"></i>
                                    </div>
                                    <a href="" style="color:#444;">
                                        <div class="number float-right text-right">
                                            <h6>Last 7 Days</h6>
                                            <span class="font700"> Orders - {{ $week_count }}</span><br>
                                            <span class="font700">Total Amount - {{ $week }}</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="progress progress-xs progress-transparent custom-color-green mb-0 mt-3">
                                    <div class="progress-bar" data-transitiongoal="{{ intval($week_count) }}"></div>
                                </div>
                                <small class="text-muted">{{ intval($week_count) }}% of Orders</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="body">
                                <div class="clearfix">
                                    <div class="float-left">
                                        <i class="fa fa-2x fa-ban text-col-red"></i>
                                    </div>
                                    <a href="" style="color:#444;">
                                        <div class="number float-right text-right">
                                            <h6>Last 30 Days</h6>
                                            <span class="font700"> Orders - {{ $month_count }}</span><br>
                                            <span class="font700"> Total Amount - {{ $month }}</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="progress progress-xs progress-transparent custom-color-red mb-0 mt-3">
                                    <div class="progress-bar" data-transitiongoal="{{ intval($month_count) }}"></div>
                                </div>
                                <small class="text-muted">{{ intval($month_count) }}% of Orders</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-30 channel hide">
                    <div class="card-header">
                        <form id="user_report" action="{{ route('admin.dashboard') }}" method="get">
                            @csrf
                            <div class="header d-flex justify-content-between">
                                <h2>Shipment Status<small>
                                <div class="row pull-right">
                                    <div class="form-group col-4 mb-0">
                                        <label for="">Start Date</label>
                                        <input type="date" name="start_date" class="form-control" value="{{ now()->format('Y-m-d') }}">
                                    </div>
                                    <div class="form-group col-4 mb-0">
                                        <label for="">End Date</label>
                                        <input type="date" name="end_date" class="form-control" value="{{ now()->format('Y-m-d') }}">
                                    </div>
                                    <div class="form-group col-4 mb-0">
                                        <h5></h5>
                                        <button type="submit" class="btn btn-primary mt-2 fetchdetails">Filter</button>
                                    </div>
                                </div>
                            </div>
                                                            
                        </form>
                    </div>
                    <div class="card-body">
                        <div id="root">
                              <div class="row align-items-stretch">
                                <div class="c-dashboardInfo col-lg-3 col-md-6">
                                  <div class="wrap">
                                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">Total Orders</h4>
                                    <span class="hind-font caption-12 c-dashboardInfo__count">@if(isset($total_orders)) {{ $total_orders }} @else 0 @endif</span>
                                  </div>
                                </div>
                                <div class="c-dashboardInfo col-lg-3 col-md-6">
                                  <div class="wrap">
                                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">Booked</h4><span class="hind-font caption-12 c-dashboardInfo__count">@if(isset($bookeddata)) {{ $bookeddata }} @else 0 @endif</span>
                                  </div>
                                </div>
                                <div class="c-dashboardInfo col-lg-3 col-md-6">
                                  <div class="wrap">
                                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">Delivered</h4><span class="hind-font caption-12 c-dashboardInfo__count">@if(isset($delivereddata)) {{ $delivereddata }} @else 0 @endif</span>
                                  </div>
                                </div>
                                <div class="c-dashboardInfo col-lg-3 col-md-6">
                                  <div class="wrap">
                                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">RTO</h4><span class="hind-font caption-12 c-dashboardInfo__count">@if(isset($rtodata)) {{ $rtodata }} @else 0 @endif</span>
                                  </div>
                                </div>
                              </div>
                          </div>
                   </div>
                   <div class="card-body">
                    <div class="col-md-12">
                        <h5 class="card-title mb-0">Courier Wise Analytics</h5>
                        <div class="table-borderless">
                            <table class="table table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                         <th>Courier Name</th>
                                        <th>Total Orders</th>
                                        <th>Booked</th>
                                        <th>Delivered</th>
                                        <th>RTO</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @if(isset($courierdata))
                                    <tr>  
                                        <td>@if($courierdata != 0) {{ $courierdata }} @else 0 @endif</td>
                                        <td>@if(isset($courier_ordercount)) {{ $courier_ordercount }} @else 0 @endif</td>     
                                        <td>@if(isset($courier_bookeddata)) {{ $courier_bookeddata }} @else 0 @endif</td>     
                                        <td>@if(isset($courier_delivereddata)) {{ $courier_delivereddata }} @else 0 @endif</td>     
                                        <td>@if(isset($courier_rtodata)) {{ $courier_rtodata }} @else 0 @endif</td>     
                                    </tr>
                                    @else
                                    <tr>
                                        <td><td><td>no data found<td><td></td></td></td></td></td>
                                    </tr>
                                    @endif
                                </tbody>
                                     
                            </table>
                        </div>
                    </div>
               </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <h5 class="card-title mb-0">COD Remittance</h5><br>
                        <div class="row">
                            <div class="col-3">
                                <div class="pt-30">
                                    <div class="header">
                                        <h5 class="card-title mb-0">Total Generated Remittance </h5>
                                    </div>
                                    <div class="card-body">
                                        <h5>RS. {{ $reductionmax }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="pt-30">
                                    <div class="header">
                                        <h5 class="card-title mb-0">Next Remittance({{\Carbon\Carbon::parse($targetDate)->format('Y-m-d')}})</h5>
                                    </div>
                                    <div class="body">
                                        <h5>RS. @if($targetDate == $currentdate){{ $reductionmax }} @else 0 @endif</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="pt-30">
                                    <div class="header">
                                        <h5 class="card-title mb-0">Future Remittance({{\Carbon\Carbon::parse($future)->format('Y-m-d')}})</h5>
                                    </div>
                                    <div class="body">
                                        <h5>RS. {{ $totalreductionmax }}  </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="pt-30">
                                    <div class="header">
                                        <h5 class="card-title mb-0">Last Remittance</h5>
                                    </div>
                                    <div class="body">
                                        
                                        <h5>RS. @if($lastremit == $currentdate) {{ $reductionmax }}  @else 0  @endif</h5>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-30 hide top10">
            <div class="header d-flex justify-content-between">
                <h2>Top 10<small>
                <div class="row">
                    <div class="form-group col-6 mb-0">
                        <label for="">Start Date</label>
                        <input type="date" name="top10_start_date" class="form-control top_10" value="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div class="form-group col-6 mb-0">
                        <label for="">End Date</label>
                        <input type="date" name="top10_end_date" class="form-control top_10" value="{{ now()->format('Y-m-d') }}">
                    </div>
                </div>
            </div>
            <div class="body row">
                <div class="col-md-6 mt-30">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable js-exportable" id="top10_customers">
                            <h3>Top Customers</h3>
                            <thead>
                                <tr>
                                <th>S.No</th>
                                    <th>Name</th>
                                    <th>Count</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($order as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$row->ship_fname}}</td>
                                    <td>{{count($row->detail)}}</td>
                                    <td>{{ number_format($row->total,2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 mt-30">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable js-exportable" id="top10_products">
                            <h3>Top Product</h3>
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th> Product Name</th>
                                    <th>Code</th>
                                    <th>Count</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($order as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$row->detail[0]->name}}</td>
                                    <td>{{$row->detail[0]->code}}</td>
                                    <td>{{count($row->detail)}}</td>
                                    <td>{{number_format($row->total,2)}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-6 mt-30">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable js-exportable" id ="top10_pincodes">
                            <h3>Top PinCodes</h3>
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Pincode</th>
                                    <th>Count</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($order as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$row->ship_pincode}}</td>
                                <td>{{count($row->detail)}}</td>
                                <td>{{number_format($row->total,2)}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 mt-30">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable js-exportable" id="top10_states">
                            <h3>Top States</h3>
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>State</th>
                                    <th>Count</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($order as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$row->ship_state}}</td>
                                <td>{{count($row->detail)}}</td>
                                <td>{{number_format($row->total,2)}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-6 mt-30">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable js-exportable" id="top10_cities">
                            <h3>Top Cities</h3>
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>City</th>
                                    <th>Count</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($order as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$row->ship_city}}</td>
                                <td>{{count($row->detail)}}</td>
                                <td>{{number_format($row->total,2)}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
           
            </div>
        </div>

        <div class="card mt-30 ndr hide">
            <div class="card-header">
                <h5 class="card-title mb-0">NDR</h5>
            </div>
            <div class="card-body">
                <div class="col-12">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="body">
                                    <div class="clearfix">
                                        <div class="float-left">
                                            <i class="fa fa-2x fa-pause text-col-default" style="color:orange;"></i>
                                        </div>
                                        <a href="" style="color:#444;">
                                            <div class="number float-right text-right">
                                                <h4>Action Pending</h4>
                                                <span class="font700">0</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div
                                        class="progress progress-xs progress-transparent custom-color-default mb-0 mt-3">
                                        <div class="progress-bar" style="background:#444444;"
                                            data-transitiongoal="0"></div>
                                    </div>
                                    <small class="text-muted">0 % of Orders</small>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="body">
                                    <div class="clearfix">
                                        <div class="float-left">
                                            <i class="fa fa-check fa-2x" aria-hidden="true"
                                                style="color:green;"></i>
                                        </div>
                                        <a href="" style="color:#444;">
                                            <div class="number float-right text-right">
                                                <h4>Action Taken</h4>
                                                <span class="font700">0</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="container">
                                        <div class="column">
                                            <p><b>Re-Attempts</b></p>
                                            <h1>0</h1>
                                        </div>
                                        <div class="column">
                                            <p><b>RTOs</b></p>
                                            <h1 style="margin-top: 46px;">0</h1>
                                        </div>
                                        <div class="column">
                                            <p><b>Action Failed</b></p>
                                            <h1>0</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="body">
                                    <div class="clearfix">
                                        <div class="float-left">
                                            <i class="fa fa-times-circle fa-2x" aria-hidden="true"
                                                style="color:red;"></i>
                                        </div>
                                        <a href="" style="color:#444;">
                                            <div class="number float-right text-right">
                                                <h4>Closed</h4>
                                                <span class="font700">0</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div
                                        class="progress progress-xs progress-transparent custom-color-default mb-0 mt-3">
                                        <div class="progress-bar" style="background:#444444;"
                                            data-transitiongoal="0"></div>
                                    </div>
                                    <small class="text-muted">0 % of Orders</small>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
         
            </div>
        </div>
    </div>

    
    </div>
</div>


<script>
    "use strick";
    (function($){
        $('.top_10').on('input',function(){
            var start = $("input[name=top10_start_date]").val();
            var end = $("input[name=top10_end_date]").val();
            $.get({
                url: "{{ route('admin.dashboard.filter') }}",
                data: {
                    start_date : start,
                    end_date : end
                },
                beforeSend: function(){
                    $('#loader').removeClass('hidden');
                },
                success: function(response){
                    $.each(response,function(key, value){
                        $('#top10_customers tbody').html('')
                        $('#top10_customers').append(`<tr>
                            <td>${key + 1}</td>
                            <td>${value.ship_fname}</td>
                            <td>${value.detail.length}</td>
                            <td>${value.total}</td>
                        </tr>`);

                        $('#top10_products tbody').html('')
                        $('#top10_products').append(`<tr>
                            <td>${key + 1}</td>
                                               
                            <td>${value.detail[0].name}</td>
                            <td>${value.detail[0].code}</td>
                            <td>${value.detail.length}</td>
                            <td>${value.total}</td>
                        </tr>`);

                        $('#top10_pincodes tbody').html('')
                        $('#top10_pincodes').append(`<tr>
                            <td>${key + 1}</td>
                            <td>${value.ship_pincode}</td>
                            <td>${value.detail.length}</td>
                            <td>${value.total}</td>
                        </tr>`);

                        
                        $('#top10_states tbody').html('')
                        $('#top10_states').append(`<tr>
                            <td>${key + 1}</td>
                            <td>${value.ship_state}</td>
                            <td>${value.detail.length}</td>
                            <td>${value.total}</td>
                        </tr>`);

                          
                        $('#top10_cities tbody').html('')
                        $('#top10_cities').append(`<tr>
                            <td>${key + 1}</td>
                            <td>${value.ship_city}</td>
                            <td>${value.detail.length}</td>
                            <td>${value.total}</td>
                        </tr>`);
                    });
                },
                complete: function() {
                    $('#loader').addClass('hidden');
                },
            });
        });
    })(jQuery);
</script>
@endsection


 