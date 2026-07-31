@extends('admin.admin_layouts')
@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/public/admin/semantic.min.js"></script>
<link rel="stylesheet" href="/public/admin/semantic.min.css">
<style>

</style>


<!-- Main body part  -->
<div class="container-fluid">
    <!-- Page header section  -->
    

    <div class="row clearfix">
    <div class="col-xl-12">
        <div class="card mb-3">
            <div class="card-header" style="display:flex;flex-wrap: wrap;">
                <!-- <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Filter</h5> -->
                    <div class="col-md-9"><a href="javascript:void(0)" class="expand">Filters <?php if(empty($re_data)){ echo '<<';}else{ echo '>>';} ?></a></div>
                    
            </div>
            
            <div class="card-body">
                <form action="{{ route('admin.order.shipped_order') }}" method="GET">
                    <div class="col-md-12">
                        <?php $trac = $ven =''; $o_st=0;
                        if(!empty($re_data)){
                            if(isset($re_data['ship_courier_id'])){
                            $o_st = $re_data['ship_courier_id'];
                            }
                        }
                        ?>
                        <div class="show_more" style="width: 100%;">
                            <div class="row">
                                    <div class="form-group col-md-8">
                                        <label>Select Date Range</label><span class="required"> </span>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input class="form-control " type="date" name="start_date" value="{{@$re_data['start_date']}}" id="_1">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control " type="date" name="end_date" value="{{@$re_data['end_date']}}" id="_2">
                                            </div>
                                        </div>
                                    </div>
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Courier</label>
                                    <select name="ship_courier_id" class="form-control">
                                        <option value='0' >Courier</option>
                                        <option value='1' <?php if($o_st =='1'){echo 'selected';} ?> >Ecom Express</option>
                                        <option value='2' <?php if($o_st =='2'){echo 'selected';} ?> >Delhivery</option>
                                        <option value='3' <?php if($o_st =='3'){echo 'selected';} ?> >Bluedart</option>
                                        <option value='4' <?php if($o_st =='4'){echo 'selected';} ?> >XpressBees</option>
                                        <option value='5' <?php if($o_st =='5'){echo 'selected';} ?> >DTDC</option>
                                        <option value='6' <?php if($o_st =='6'){echo 'selected';} ?> >Smartr</option>
                                        <option value='7' <?php if($o_st =='7'){echo 'selected';} ?> >Ekart</option>
                                        <option value='8' <?php if($o_st =='8'){echo 'selected';} ?> >Shadowfax</option>
                                        
                                    </select>
                                </div>
                                
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
    <div class="col-xl-12">


        <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
            @csrf
            <div class="card new_orders">
                <div class="header d-flex justify-content-between" style="padding-bottom: 0;">
                    <h2>Shipped Order</h2>
                    <div class="form-group col-2 mb-0">
                        <label class="mr-2">@lang('Action')</label>
                        <select class="form-control" name="status" id="myselect">
                            <option value="" selected disabled>@lang('Select One')</option>
                            <!-- <option value="on_hold">On Hold</option> -->
                            <option value="cancel">Cancel</option>
                            <option value="download">Download</option>
                            <option value="manifest">Manifest</option>
                           @if($session->id =='65' || $session->id =='69' || $session->id =='1')
                           <!-- <option value="manifestproductwise">Manifest Productwise</option> -->
                           @endif
                            <!-- <option value="rto">RTO</option>
                            <option value="refund">Refund</option>
                            <option value="delete">Delete</option> -->
                        </select>
                    </div>
                </div>
                <div class="body row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                        <table class="table table-bordered sorttable table-striped table-hover" id="sorttable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            <label class="fancy-checkbox">
                                                <input class="select-all" type="checkbox" name="checkbox">
                                                <span></span>
                                            </label>
                                        </th>
                                        <th>SL</th>
                                        <th>Order id</th>
                                        <th>Seller</th>
                                        <th>Channel</th>
                                        <th>Order Number</th>
                                        <th>Tracking Info</th>
                                        <th>Created Date</th>
                                        <th>Shipping Date</th>
                                        <th>Product</th>
                                        <th>Payment</th>
                                        <th>Dim. & Wt. <a title="Default Settings" data-toggle="popover"
                                                data-placement="bottom" data-trigger="hover"
                                                data-content="L: 10cm , B: 10cm , H: 10cm , Weight : 50gm "><span
                                                    class="fa fa-info-circle"></span></a>
                                        </th>
                                        <th>Status</th>
                                        <!-- <th>Extra Weight</th> -->
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
                                        <td>{{ $loop->iteration }}</td>
                                         <td>{{ $row->id }}</td>
                                         <td>{{ $row->user_id }}</td>
                                        <td>{{ $row->channel }}</td>
                                        <td class="text-center"> <a
                                                href="{{ route('admin.order.detail',$row->id) }}">{{ $row->vendor_order_id }}</a>
                                        </td>
                                        <!--<td>{{ $row->tracking_info ?? 'Tracking number N/A' }}-->
                                        <!--    @if($row->ship_courier_id)-->
                                        <!--    <br>-->
                                        <!--    {{$couriers[$row->ship_courier_id]['name']}}-->
                                        <!--    @endif-->
                                        
                                        <!--</td>-->
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
                                            {{ \Carbon\Carbon::parse($row->shipped_date)->format('d M, Y') }}<br>
                                            <span class="fa fa-clock-o"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($row->shipped_date)->format('H:i') }}
                                        </td>
                                        <td>{{ @$row->detail[0]->name }}</td>
                                        <td> {{ $row->total }} .00<br> {!! $row->payment_mode !!}</td>
                                        <td><b>Dim :</b> {{ $row->length }}x{{ $row->breadth }}x{{ $row->height }}
                                            cm<br><b>Wt :</b> {{ $row->weight }} gm</td>
                                        <td>
                                            @if($row->manifest_id)
                                                <span class="badge text-white bg-danger">Pickup Pending</span>
                                            @else
                                                <span class="badge text-white bg-dark">Shipped</span>
                                            @endif
                                            
                                           
                                        </td>
                                        
                                        <!-- <td>
                                            @if ($session->role_id =='1')
                                                {{$row->extra_weight}}gms ({{$row->extra_weight_cost}} rs)
                                                <br>
                                                <a href="{{ route('admin.order.addextraweight',$row->id ) }}" class="btn btn-warning" title="Add Weight"
                                                       style="width:120px;" data-order-id=""><span
                                                          class="sr-only">Add Weight</span> Add Weight</a>
                                            @else
                                                {{$row->extra_weight}}gms ({{$row->extra_weight_cost}})
                                            @endif
                                        </td> -->
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{ $order->links() }}
            </div>

           
        </form>
    </div>
    </div>
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
                }else if(action_type == 'on_hold'){
                    let action_route = `{{ route('admin.order.on_hold') }}`;
                    $('#myForm').attr("action", action_route);
                }else if(action_type == 'cancel'){
                    let action_route = `{{ route('admin.order.cancel') }}`;
                    $('#myForm').attr("action", action_route);
                }
                else if(action_type == 'rto'){
                    let action_route = `{{ route('admin.order.rto') }}`;
                    $('#myForm').attr("action", action_route);
                }
                else if(action_type == 'refund'){
                    let action_route = `{{ route('admin.order.refund') }}`;
                    $('#myForm').attr("action", action_route);
                }else if(action_type == 'download'){
                    let action_route = `{{ route('admin.order.download') }}`;
                    $('#myForm').attr("action", action_route);
                }else if(action_type == 'manifest'){
                    let action_route = `{{ route('admin.order.manifestorder') }}`;
                    $('#myForm').attr("action", action_route);
                }else if(action_type == 'manifestproductwise'){
                    let action_route = `{{ route('admin.order.manifestproductwise') }}`;
                    $('#myForm').attr("action", action_route);
                }
                $('#myForm').submit();
            } else {
                toastr.error('Select atleast one Order');
                $('select[name=status]').val('');
            }
        });

        
        $('.expand').on('click',function() {
            if ($(this).text() == 'More Filters>>') {
                $(this).text('Less Filters>>');
            } else {
                $(this).text('More Filters>>');
            }
            $('.show_more').slideToggle('fast');
        });
    })(jQuery);
</script>


@endsection