@extends('admin.admin_layouts')
@section('admin_content')
<style>
.card.pt-30 .card-title , .card-body h5{
    text-align: center;
}
.col-3 .card-body{
    padding: 8px;
    text-align: center;
}
.col-3 .card-header{
    text-align: center;
}
.btn-group{
        display: block;
    }
    .btn-group .multiselect{
        width:100%
    }
    .multiselect-container
    , .multiselect-container>li>a>label.checkbox {
        width: 100%;
    }
    </style>

    <div class="row clearfix">
         @if($user->role_id =='1')
        <div class="col-md-3">
            <x-button type="import" route="{{ route('admin.order.codcreate') }}" name="Import"/>
        </div>
        @endif
            <div class="col-xl-12">
                <div class="card bg-light mb-3">
                    <div class="card-header" style="display:flex;flex-wrap: wrap;">
                        <!-- <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Filter</h5> -->
                            <div class="col-md-9"><a href="javascript:void(0)" class="expand">Filters <?php if(empty($re_data)){ echo '<<';}else{ echo '>>';} ?></a></div>
                            @if($user->role_id =='1')
                           
                            @endif
                    </div>
                    
                    <div class="card-body">
                        <form id="data"action="{{ route('codrem') }}" method="GET">
                            <div class="col-md-12">
                                <?php $codstatus =''; $user_id=0;
                                if(!empty($re_data)){
                                if(isset($re_data['user_id']))
                                    $user_id = $re_data['user_id'];
                                if(isset($re_data['codstatus']))
                                    $codstatus = $re_data['codstatus'];
                                }
                                ?>
                                <div class="show_more" style="width: 100%; <?php if(empty($re_data)){ echo 'display:none'; } ?>">
                                    <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">User</label>
                                        <select id="multiple-checkboxes" name="user_id[]" class="form-control" required multiple="multiple">
                                              @foreach($allusers as $us)
                                                <option value='{{$us->id}}' <?php if(in_array($us->id,$re_data['user_id'])){echo 'selected';} ?>  >{{ $us->id }} {{ $us->name }} - ({{ $us->mobile }})</option>
                                              @endforeach  
                                        </select>
                                    </div>
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
                                        
                                        
                                    </div>
                                    <div class="row">
                                        <x-button type="submit" name="Search" />
                                    </div>
                                    <script>
                                        $(document).ready(function() {
                                            $('#multiple-checkboxes').multiselect({
                                            includeSelectAllOption: true,
                                            });
                                        });
                                    </script>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
          
        <div class="col-xl-12">
         <div class="container-fluid">
        
        <div class="row clearfix">
            <div class="col-4">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Amount Collected </h5>
                    </div>
                    <div class="card-body">
                        <h5>RS. {{ $total }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Freight Charge</h5>
                    </div>
                    <div class="card-body">
                        <h5>RS. {{round($shipping,2)}}</h5>
                    </div>
                </div>
            </div>
            <!-- <div class="col-2">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">RTO</h5>
                    </div>
                    <div class="card-body">
                        <h5>RS. {{round($rto,2)}}  </h5>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Extra Weight Charge</h5>
                    </div>
                    <div class="card-body">
                        
                        <h5>RS. {{round(($extra_weight),2)}}</h5>
                    </div>
                </div>
            </div>
             <div class="col-2">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Extra Weight RTO </h5>
                    </div>
                    <div class="card-body">
                        
                        <h5>RS. {{round(($extra_weight_rto),2)}}</h5>
                    </div>
                </div>
            </div> -->
            <div class="col-4">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Total Payable</h5>
                    </div>
                    <div class="card-body">
                        
                        <h5>RS. {{round(($total - ( $shipping)),2)}}</h5>
                    </div>
                </div>
            </div>
        </div>
    {{--@endif--}}
        </div>  
        <div class="col-xl-12 remotancedata" style="">
        <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
            @csrf
            <div class="card  new_orders">
                <div class="header d-flex justify-content-between" style="padding-bottom: 0;">
                    <h2>Remittance List</h2>
                   <input type="hidden" name ='path' value="all">
                </div>
                <div class="">
                        <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sorttable" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="text-center" data-field="hideexport">
                                            <label class="fancy-checkbox1" style="margin: 0;">
                                                <input class="select-all" type="checkbox" name="checkbox">
                                            </label>
                                        </th>
                                        <th>Order Id</th>
                                        <th>SellerId</th>
                                        <th>Seller</th>
                                        <th>Tracking Id</th>
                                        <th>Courier Name</th>
                                        <th>Delivered</th>
                                        <th>Total Amount Collected</th>
                                        <th>Freight Charge</th>
                                        <!-- <th>RTO Charge</th>
                                        <th>Extra Weight Charge</th>
                                        <th>Extra Weight RTO Charge</th> -->
                                         <th>Paymentstatus</th> 
                                        <th>Status</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($row as $key=>$value)
                                    <tr>
                                            <td class="text-center">
                                                <label class="fancy-checkbox{{$value['od_id']}}">
                                                    <input class="checkbox-tick" type="checkbox" name="id[{{$value['od_id']}}]"
                                                        value="{{$value['od_id']}}">
                                                    <span></span>
                                                </label>
                                            </td>
                                        <td>{{$value['order_id']}}</td>
                                        <td>{{$value['sellerid']}}</td>
                                        <td>{{$value['seller']}}</td>
                                        <td>{{$key}}</td>
                                        <!-- <td> <a href="{{ route('codawb') }}?awb={{ $key }}&start_date={{ $value['start_date'] }}&end_date={{ $value['end_date'] }}" class=""
                                                title="View">{{$key}}</a></td> -->
                                        <td>{{$value['courier']}}</td>
                                        <td>{{$value['d_date']}}</td>
                                        <td>{{$value['total']}}</td>
                                        <td>{{$value['shipping']}}</td>
                                        <!-- <td>{{$value['rto']}}</td>
                                        <td>{{$value['extra_weight']}}</td>
                                        <td>{{$value['extra_weight_rto']}}</td> -->
                                         <td>{{$value['payment_status']}}</td>
                                        <td>{!!$value['status']!!}</td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>        
                        </div>
                </div>
                </div>   
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
                if(action_type == 'paid'){
                    let action_route = `{{ route('admin.order.paid') }}`;
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