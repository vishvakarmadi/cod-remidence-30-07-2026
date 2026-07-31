@extends('admin.admin_layouts')
@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/public/admin/semantic.min.js"></script>
<link rel="stylesheet" href="/public/admin/semantic.min.css">
<style>
.table tbody tr td{
    font-size: 12px;
}
label.radio-card {
  cursor: pointer;
}
label.radio-card .card-content-wrapper {
  background: #fff;
  border-radius: 5px;
  max-width: 280px;
  padding: 5px;
  display: grid;
  box-shadow: 0 2px 4px 0 rgba(219, 215, 215, 0.04);
  transition: 200ms linear;
}
label.radio-card .check-icon {
  width: 20px;
  height: 20px;
  display: inline-block;
  border: solid 2px #e3e3e3;
  border-radius: 50%;
  transition: 200ms linear;
  position: relative;
}
.AWBnum{
    font-weight: bold !important;
}
label.radio-card .check-icon:before {
  content: "";
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg width='12' height='9' viewBox='0 0 12 9' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0.93552 4.58423C0.890286 4.53718 0.854262 4.48209 0.829309 4.42179C0.779553 4.28741 0.779553 4.13965 0.829309 4.00527C0.853759 3.94471 0.889842 3.88952 0.93552 3.84283L1.68941 3.12018C1.73378 3.06821 1.7893 3.02692 1.85185 2.99939C1.91206 2.97215 1.97736 2.95796 2.04345 2.95774C2.11507 2.95635 2.18613 2.97056 2.2517 2.99939C2.31652 3.02822 2.3752 3.06922 2.42456 3.12018L4.69872 5.39851L9.58026 0.516971C9.62828 0.466328 9.68554 0.42533 9.74895 0.396182C9.81468 0.367844 9.88563 0.353653 9.95721 0.354531C10.0244 0.354903 10.0907 0.369582 10.1517 0.397592C10.2128 0.425602 10.2672 0.466298 10.3112 0.516971L11.0651 1.25003C11.1108 1.29672 11.1469 1.35191 11.1713 1.41247C11.2211 1.54686 11.2211 1.69461 11.1713 1.82899C11.1464 1.88929 11.1104 1.94439 11.0651 1.99143L5.06525 7.96007C5.02054 8.0122 4.96514 8.0541 4.90281 8.08294C4.76944 8.13802 4.61967 8.13802 4.4863 8.08294C4.42397 8.0541 4.36857 8.0122 4.32386 7.96007L0.93552 4.58423Z' fill='white'/%3E%3C/svg%3E%0A");
  background-repeat: no-repeat;
  background-size: 12px;
  background-position: center center;
  transform: scale(1.6);
  transition: 200ms linear;
  opacity: 0;
}
label.radio-card input[type=radio] {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
}
label.radio-card input[type=radio]:checked + .card-content-wrapper {
  box-shadow: 0 2px 4px 0 rgba(219, 215, 215, 0.5), 0 0 0 2px #3057d5;
}
label.radio-card input[type=radio]:checked + .card-content-wrapper .check-icon {
  background: #3057d5;
  border-color: #3057d5;
  transform: scale(1.2);
}
label.radio-card input[type=radio]:checked + .card-content-wrapper .check-icon:before {
  transform: scale(1);
  opacity: 1;
}
label.radio-card input[type=radio]:focus + .card-content-wrapper .check-icon {
  box-shadow: 0 0 0 4px rgba(48, 86, 213, 0.2);
  border-color: #3056d5;
}

label.radio-card .card-content h4 {
  font-size: 15px;
  color: #1f2949;
  margin:0;
 display: inline;;
}
label.radio-card .card-content h5 {
  font-size: 14px;
  margin-left:8px;
  color: #686d73;
}
.table td{
    padding: 0px 10px;
}

</style>


<!-- Main body part  -->

<div class="container-fluid">
    
    <!-- Page header section  -->
    <!-- ===== FIXED COURIER CUT-OFF MARQUEE ===== -->
<div class="cutoff-marquee">
    <!--<div class="cutoff-marquee-content">-->
    <!--    🚚 <strong>Courier Cut-Off Timings:</strong> -->
        
    <!--    Ecom Express – 3:00 PM | -->
    <!--    Delhivery – 2:30 PM | -->
    <!--    Blue Dart – 1:30 PM | -->
    <!--    XpressBees – 4:00 PM | -->
    <!--    DTDC – 3:30 PM | -->
    <!--    Ekart – 2:00 PM | -->
    <!--    Shadowfax – 5:00 PM-->
        
    <!--    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;-->
        
    <!--    🚚 <strong>Courier Cut-Off Timings:</strong> -->
        
    <!--    Ecom Express – 3:00 PM | -->
    <!--    Delhivery – 2:30 PM | -->
    <!--    Blue Dart – 1:30 PM | -->
    <!--    XpressBees – 4:00 PM | -->
    <!--    DTDC – 3:30 PM | -->
    <!--    Ekart – 2:00 PM | -->
    <!--    Shadowfax – 5:00 PM-->
    <!--</div>-->
    <div class="cutoff-marquee-content">
                🚚 <strong>Courier Cut-Off Timings:</strong>

                Ekart – 12:00 PM | 
                Delhivery – 1:00 PM | 
                XB – 11:00 AM | 
                Blue Dart – 11:00 AM | 
                Pikndel – SDD: 11:00 AM, NDD: 3:00 PM | 
                Blitz – SDD: 11:00 AM, NDD: 3:00 PM

                &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;

                🚚 <strong>Courier Cut-Off Timings:</strong>

                Ekart – 12:00 PM | 
                Delhivery – 1:00 PM | 
                XB – 11:00 AM | 
                Blue Dart – 11:00 AM | 
                Pikndel – SDD: 11:00 AM, NDD: 3:00 PM | 
                Blitz – SDD: 11:00 AM, NDD: 3:00 PM
            </div>
</div>

    <div class="row clearfix">
        <div class="col-xl-12">
            <div class="card  mb-3">
                <div class="card-header" style="display:flex;flex-wrap: wrap;border-radius: 16px 16px 0 0;">
                    <!-- <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Filter</h5> -->
                        <div class="col-md-9"><a href="javascript:void(0)" class="expand">Filters <?php if(empty($re_data)){ echo '<<';}else{ echo '>>';} ?></a></div>
                        <div class="col-md-3">
                                <x-button type="import" route="{{ route('admin.bulkorder.create') }}" name="Import"/>
                                <x-button type="create" route="{{ route('admin.order.create') }}" name="Create" />
                        </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.order.all') }}" method="GET">
                        <div class="col-md-12">
                            <?php $trac = $ven = $buyer_name =  $payment_mode = ''; $o_st=$c_id=0;
                            if(!empty($re_data)){
                              if(isset($re_data['tracking_info']))
                                $trac = $re_data['tracking_info'];
                              if(isset($re_data['vendor_order_id']))
                                $ven = $re_data['vendor_order_id'];
                              if(isset($re_data['order_status']))
                                $o_st = $re_data['order_status'];
                              if(isset($re_data['ship_courier_id']))
                                $c_id = $re_data['ship_courier_id'];
                              if(isset($re_data['payment_mode']))
                                $payment_mode = $re_data['payment_mode'];
                              $s_data = explode(' ',$re_data['start_date'])[0];
                              $e_data = explode(' ',$re_data['end_date'])[0];
                              if(isset($re_data['buyer_name'])){$buyer_name = $re_data['buyer_name'];}
                              $seller_id = $re_data['seller_id'] ?? '';
//                              echo '<pre>';print_R($sellers);die;
                            }
                            ?>
                            <div class="show_more" style="width: 100%; <?php if(empty($re_data)){ echo 'display:none'; } ?>">
                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <label>Select Date Range</label><span class="required"> *</span>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input class="form-control " type="date" name="start_date" required="" value="{{explode(' ',$re_data['start_date'])[0]}}" id="_1">
                                                </div>
                                                <div class="col-md-6">
                                                    <input class="form-control " type="date" name="end_date" required="" value="{{explode(' ',$re_data['end_date'])[0]}}" id="_2">
                                                </div>
                                            </div>
                                    </div>
                                    <x-field type="text" label="Order Number" placeholder="Order Number" name="vendor_order_id" value="{{$ven}}" />
                                    
                                    <x-field type="text" label="Buyer Name" size="col-md-2" placeholder="Buyer Name" name="buyer_name" value="{{$buyer_name}}" />
                                    <x-field type="select" name="payment_mode" size="col-md-2 select_style" label="Payment-mode" value="{{ $payment_mode}}"  :options="[['id'=>'6','name'=>'C.O.D'],['id'=>'12','name'=>'Pre-Paid']]" print="name" store="id"/>
                                    <x-field 
                                        type="select" 
                                        name="seller_id" 
                                        size="col-md-2 select_style" 
                                        label="Seller" 
                                        value="{{ $seller_id }}"  
                                        :options="$sellers" 
                                        print="name" 
                                        store="id"
                                    />
                                    <div class="form-group col-md-2">
                                        <label class="form-control-label">Status</label>
                                        <select name="order_status" class="form-control">
                                            <option value='0' >Status</option>
                                            <option value='1' <?php if($o_st =='1'){echo 'selected';} ?> >New</option>
                                            <option value='2' <?php if($o_st =='2'){echo 'selected';} ?> >Shipped</option>
                                            <option value='3' <?php if($o_st =='3'){echo 'selected';} ?> >Delivered</option>
                                            <option value='4' <?php if($o_st =='4'){echo 'selected';} ?> >Canceled</option>
                                            <option value='5' <?php if($o_st =='5'){echo 'selected';} ?> >RTO</option>
                                            <option value='6' <?php if($o_st =='6'){echo 'selected';} ?> >RTO Received</option>
                                            <option value='10' <?php if($o_st =='10'){echo 'selected';} ?> >NDR</option>
                                            <option value='12' <?php if($o_st =='12'){echo 'selected';} ?> >Pickup Pending</option>
                                            <option value='13' <?php if($o_st =='13'){echo 'selected';} ?> >RTO In Transit</option>
                                            <option value='14' <?php if($o_st =='14'){echo 'selected';} ?> >In Transit</option>
                                            <option value='15' <?php if($o_st =='15'){echo 'selected';} ?> >Out for Delivery</option>
                                            <option value='16' <?php if($o_st =='16'){echo 'selected';} ?> >Lost</option>
                                            <option value='17' <?php if($o_st =='17'){echo 'selected';} ?> >Damaged</option>
                                            <option value='18' <?php if($o_st =='18'){echo 'selected';} ?> >Destroyed</option>
                                            
                                        </select>
                                    </div>
                                  	<div class="form-group col-md-2">
                                        <label class="form-control-label">Courier</label>
                                        <select name="ship_courier_id" class="form-control">
                                            <option value='0' >Courier</option>
                                            <option value='1' <?php if($c_id =='1'){echo 'selected';} ?> >Ecom Express</option>
                                            <option value='2' <?php if($c_id =='2'){echo 'selected';} ?> >Delhivery</option>
                                            <option value='3' <?php if($c_id =='3'){echo 'selected';} ?> >Bludart</option>
                                            <option value='4' <?php if($c_id =='4'){echo 'selected';} ?> >XpressBees</option>
                                            <option value='5' <?php if($c_id =='5'){echo 'selected';} ?> >DTDC</option>
                                            <option value='6' <?php if($c_id =='6'){echo 'selected';} ?> >Smartr</option>
                                            <option value='7' <?php if($c_id =='7'){echo 'selected';} ?> >Ekart</option>
                                            <option value='8' <?php if($c_id =='8'){echo 'selected';} ?> >Shadowfax</option>
                                            <option value='9' <?php if($c_id =='9'){echo 'selected';} ?> >ATS</option>
                                            
                                            
                                        </select>
                                    </div>
                                    <x-field type="text" label="AWB" size="col-md-2"  class="AWBnum" placeholder="AWB" name="tracking_info" value="{{$trac}}" />
                                    
                                </div>
                                <div class="row">
                                    <x-button size="col-lg-3" type="submit" name="Search" />
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <div class="sts">
        <i class="fa fa-info-circle"></i>
        <span><b>Total Orders : <span class="badge-pill bg-primary">{{$totalOrdersCount}}</span></b></span>
        </div>
        <div class="col-xl-12">
         @if($session->id =='211')
            <a href="{{ route('get.track.update') }}" class="btn btn-info" style="font-size: 12px; padding: 5px 10px;">Update Status</a>
        @endif
        <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
            @csrf
            <div class="card  new_orders">
                <div class="header d-flex justify-content-between" style="padding-bottom: 0;">
                    <h2>Order List</h2>
                     @php
                        $ls = \App\Models\LabelSetting::where('company_id', $session->company_id)->first();
                        $printLabelText = ($ls && $ls->printer_type == 2) ? 'Print Labels (A4)' : 'Print Labels (Thermal 4x6)';
                    @endphp
                    <div class="form-group col-2 mb-0">
                        <label class="mr-2">@lang('Action')</label>
                        <select class="form-control" name="status" id="myselect">
                            <option value="" selected disabled>@lang('Select One')</option>
                            <!-- <option value="rto">Reverse Order</option> -->
                            <!--<option value="ndr">NDR</option>-->
                            <option value="cancel">Cancel</option>
                            <option value="delete">Delete</option>
                            <option value="download">Download</option>
                            <option value="manifest">Ship Now</option>
                            <option value="clone">Clone</option>
                            <!--<option value="printlabel">Print Label (4/Page)</option>-->
                            <!--<option value="printlabel4x6">Print Label (4x6 Thermal)</option>-->
                            <option value="printlabel">{{ $printLabelText }}</option>
                            
                        </select>
                    </div>
                    <input type="hidden" name ='path' value="all">
                </div>
                <div class="">
                    <div class="col-md-12">
                        Selected: <span class="order_selected_count">0</span>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            <label class="fancy-checkbox">
                                                <input class="select-all" type="checkbox" name="checkbox">
                                                <span></span>
                                            </label>
                                        </th>
                                        <th>
                                            <a href="{{ route('admin.order.all', ['sortField' => 'id', 'sortDirection' => Request::get('sortDirection') == 'asc' && Request::get('sortField') == 'id' ? 'desc' : 'asc']) }}">
                                            O.ID
                                            @if(Request::get('sortField') == 'id')
                                                <i class="fa {{ Request::get('sortDirection') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                            @endif
                                             </a>
                                            
                                        </th>
                                        <th>
                                            <a href="{{ route('admin.order.all', ['sortField' => 'user_id', 'sortDirection' => Request::get('sortDirection') == 'asc' && Request::get('sortField') == 'user_id' ? 'desc' : 'asc']) }}">
                                            Seller
                                            @if(Request::get('sortField') == 'user_id')
                                                <i class="fa {{ Request::get('sortDirection') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                            @endif
                                             </a>
                                            
                                        </th>
                                        <th>
                                            <a href="{{ route('admin.order.all', ['sortField' => 'channel', 'sortDirection' => Request::get('sortDirection') == 'asc' && Request::get('sortField') == 'channel' ? 'desc' : 'asc']) }}">
                                            Channel
                                            @if(Request::get('sortField') == 'channel')
                                                <i class="fa {{ Request::get('sortDirection') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                            @endif
                                             </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('admin.order.all', ['sortField' => 'vendor_order_id', 'sortDirection' => Request::get('sortDirection') == 'asc' && Request::get('sortField') == 'vendor_order_id' ? 'desc' : 'asc']) }}">
                                            Order Number
                                            @if(Request::get('sortField') == 'vendor_order_id')
                                                <i class="fa {{ Request::get('sortDirection') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                            @endif
                                             </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('admin.order.all', ['sortField' => 'ship_fname', 'sortDirection' => Request::get('sortDirection') == 'asc' && Request::get('sortField') == 'ship_fname' ? 'desc' : 'asc']) }}">
                                            Buyer
                                            @if(Request::get('sortField') == 'ship_fname')
                                                <i class="fa {{ Request::get('sortDirection') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                            @endif
                                             </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('admin.order.all', ['sortField' => 'tracking_info', 'sortDirection' => Request::get('sortDirection') == 'asc' && Request::get('sortField') == 'tracking_info' ? 'desc' : 'asc']) }}">
                                            Tracking Info
                                            @if(Request::get('sortField') == 'tracking_info')
                                                <i class="fa {{ Request::get('sortDirection') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                            @endif
                                             </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('admin.order.all', ['sortField' => 'created_at', 'sortDirection' => Request::get('sortDirection') == 'asc' && Request::get('sortField') == 'created_at' ? 'desc' : 'asc']) }}">
                                            Created Date
                                            @if(Request::get('sortField') == 'created_at')
                                                <i class="fa {{ Request::get('sortDirection') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                            @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('admin.order.all', ['sortField' => 'updated_at', 'sortDirection' => Request::get('sortDirection') == 'asc' && Request::get('sortField') == 'updated_at' ? 'desc' : 'asc']) }}">
                                            Updated Date
                                            @if(Request::get('sortField') == 'updated_at')
                                                <i class="fa {{ Request::get('sortDirection') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                            @endif
                                            </a>
                                            
                                        </th>
                                        <th>Product</th>
                                        <th>
                                            <a href="{{ route('admin.order.all', ['sortField' => 'total', 'sortDirection' => Request::get('sortDirection') == 'asc' && Request::get('sortField') == 'total' ? 'desc' : 'asc']) }}">
                                            Payment
                                            @if(Request::get('sortField') == 'total')
                                                <i class="fa {{ Request::get('sortDirection') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                            @endif
                                            </a>
                                        </th>
                                        <th>Dim. & Wt. <a title="Default Settings" data-toggle="popover"
                                                data-placement="bottom" data-trigger="hover"
                                                data-content="L: 10cm , B: 10cm , H: 10cm , Weight : 50gm "><span
                                                    class="fa fa-info-circle"></span></a>
                                        </th>
                                        <th>
                                            <a href="{{ route('admin.order.all', ['sortField' => 'status', 'sortDirection' => Request::get('sortDirection') == 'asc' && Request::get('sortField') == 'status' ? 'desc' : 'asc']) }}">
                                            Status
                                            @if(Request::get('sortField') == 'status')
                                                <i class="fa {{ Request::get('sortDirection') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                            @endif
                                            </a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order as $row)
                                    <tr>
                                        <td class="text-center">
                                            <label class="fancy-checkbox">
                                                <input class="checkbox-tick" type="checkbox" name="id[{{ $row->id }}]" value="{{ $row->id }}">
                                                <span></span>
                                            </label>
                                        </td>
                                        <td>{{ $row->id }}</td>
                                        <td>{{ $row->user_id }}</td>
                                        <td  style="text-align: center;">
<!--                                            <img src="{{ asset('public/favicon.svg') }}" style="width:50px"
                                                alt="Channel Logo">-->
                                            @if($row->channel)
                                            {{ $row->channel }}
                                            @else
                                            Hyloship
                                            @endif
                                        </td>
                                        <td class="text-center"> <a
                                                href="{{ route('admin.order.detail',$row->id) }}">{{ $row->vendor_order_id }}</a>
                                        </td>
                                        <td>{{$row->ship_fname}} {{$row->ship_lname}}</td>
                                        <td> 
                                            @if($row->ship_courier_id)
                                                @if($row->ship_courier_id =='1')
                                                <a href="{{ URL::to('tracking/' . $row->tracking_info) }}" target="_blank"> {{ $row->tracking_info}} </a>
                                                    <!--<a href="{{$couriers[$row->ship_courier_id]['url'].$row->tracking_info }}" target="_blank"> {{ $row->tracking_info}} </a>-->
                                                @else
                                                    <a href="{{ URL::to('tracking/' . $row->tracking_info) }}" target="_blank"> {{ $row->tracking_info}} </a>
                                                @endif
                                            @else 
                                                {{ $row->tracking_info ?? 'N/A' }} | 
                                            @endif
                                                @if($row->ship_courier_id)
                                                <br>
                                                @if($row->ship_courier_id =='1')
                                                <a href="{{ URL::to('admin/tracking/' . $row->tracking_info) }}" target="_blank">{{ $couriers[$row->ship_courier_id]['name'] }}</a>
                                                    <!--<a href="{{$couriers[$row->ship_courier_id]['url'].$row->tracking_info }}" target="_blank"> {{  $couriers[$row->ship_courier_id]['name']}} </a>-->
                                                @else
                                                    <a href="{{ URL::to('admin/tracking/' . $row->tracking_info) }}" target="_blank">{{ $couriers[$row->ship_courier_id]['name'] }}</a>
                                                @endif
                                            @endif
                                        </td>
                                        <td><span class="fa fa-calendar"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($row->created_at)->format('d M, Y') }}<br>
                                            <span class="fa fa-clock-o"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($row->created_at)->format('H:i') }}
                                        </td>
                                        <td><span class="fa fa-calendar"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($row->updated_at)->format('d M, Y') }}<br>
                                            <span class="fa fa-clock-o"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($row->updated_at)->format('H:i') }}
                                        </td>
                                        <td style="max-width:100px;white-space: normal" title="{{@$row->detail[0]->name}}">
                                            {{ substr(@$row->detail[0]->name,0,32) }}
                                            @if (strlen(@$row->detail[0]->name) >32)
                                            ...
                                            @endif
                                        </td>
                                        <td> {{ $row->custom_total }} .00<br> {!! $row->payment_mode !!}</td>
                                        <td><b>Dim :</b> {{ $row->length }}x{{ $row->breadth }}x{{ $row->height }}
                                            <!--cm<br><b>Wt :</b> {{ $row->weight }} gm</td>-->
                                            <br><b>Wt :</b> {{ $row->weight }} gm
                                            <br><b>Vol. Wt :</b> {{ number_format(($row->length * $row->breadth * $row->height) / 5000, 2) }} kg
                                        <td>
                                            
                                                <div class="d-flex justify-content-between align-items-center">

                                                    <div>
                                                        @if ($row->manifest_id && strip_tags($row->status) =='Shipped')
                                                            <span class="badge text-white bg-danger">Manifested</span>
                                                        @else
                                                            {!! $row->status !!}
                                                        @endif
                                                    </div>

                                                    @if($row->ship_courier_id)
                                                        <div class="dropdown ml-2">
                                                            <button class="btn btn-link p-0" type="button" data-toggle="dropdown">
                                                                <i class="fa fa-ellipsis-v"></i>
                                                            </button>

                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item"
                                                                href="{{ route('admin.order.shippingprintparticular', [$row->id, $row->ship_courier_id]) }}"
                                                                target="_blank">
                                                                    <i class="fa fa-print"></i> View Label
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- {{ $order->appends(['sortField' => $sortField, 'sortDirection' => $sortDirection,'start_date'=>$s_data,'end_date'=>$e_data,'vendor_order_id'=>$ven,'tracking_info'=>$trac,'order_status'=>$o_st,'ship_courier_id'=>$c_id])->links() }} -->
            </div>

           
        </form>
    </div>
    </div>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-3">
{{ $order->appends(['sortField' => $sortField, 'sortDirection' => $sortDirection,'start_date'=>$s_data,'end_date'=>$e_data,'vendor_order_id'=>$ven,'tracking_info'=>$trac,'order_status'=>$o_st,'ship_courier_id'=>$c_id])->links() }}
</div>
<script type="text/javascript">
    (function($) {
        "use strict";
        $('select[name=status]').change(function() {
            if ($('input[name^="id"]:checked').length > 0) {
                var action_type = $('select[name=status]').val();
                if(action_type == 'delete'){
                    let action_route = `{{ route('admin.order.action') }}`;
                    $('#myForm').attr("action", action_route);
                }else if(action_type == 'rto'){
                    let action_route = `{{ route('admin.order.rto') }}`;
                    $('#myForm').attr("action", action_route);  
                }
                else if(action_type == 'ndr'){
                let action_route = `{{ route('admin.order.ndr') }}`;
                $('#myForm').attr("action", action_route);
            } else if(action_type == 'cancel'){
                let action_route = `{{ route('admin.order.cancel') }}`;
                $('#myForm').attr("action", action_route);
            }
                else if(action_type == 'unassign'){
                    let action_route = `{{ route('admin.order.unassign') }}`;
                    $('#myForm').attr("action", action_route);
                }
                else if(action_type == 'download'){
                    let action_route = `{{ route('admin.order.download') }}`;
                    $('#myForm').attr("action", action_route);
                }
                else if(action_type == 'manifest'){
                    let action_route = `{{ route('admin.order.manifestmultiple') }}`;
                    $('#myForm').attr("action", action_route);
                }
                else if(action_type == 'clone'){
                     let action_route = `{{ route('admin.order.action') }}`;
                    $('#myForm').attr("action", action_route);
                }
                else if(action_type == 'printlabel'){
                    let action_route = `{{ route('admin.order.printmultiple') }}`;
                    $('#myForm').attr("action", action_route);
                }
                 else if(action_type == 'printlabel4x6'){
                    let action_route = `{{ route('admin.order.printmultiple4x6') }}`;
                    $('#myForm').attr("action", action_route);
                }
                $('#myForm').submit();
            } else {
                toastr.error('Select atleast one Order');
                $('select[name=status]').val('');
            }
        });


        
        $('.expand').on('click',function() {
            if ($(this).text() == 'Filters>>') {
                $(this).text('Filters<<');
            } else {
                $(this).text('Filters>>');
            }
            $('.show_more').slideToggle('fast');
        });
    })(jQuery);


</script>


@endsection