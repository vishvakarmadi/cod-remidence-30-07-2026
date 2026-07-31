@extends('admin.admin_layouts')
@section('admin_content')
<style>
.card.pt-30 .card-title {
    font-size: 20px;
    font-family: 'FontAwesome';
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
        @if ($user->role_id =='1' || $user->role_id =='2')
            <div class="col-xl-12">
                <div class="card bg-light mb-3">
                    <div class="card-header" style="display:flex;flex-wrap: wrap;">
                        <!-- <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Filter</h5> -->
                            <div class="col-md-9"><a href="javascript:void(0)" class="expand">Filters <?php if(empty($re_data)){ echo '<<';}else{ echo '>>';} ?></a></div>
                            @if($user->role_id =='1')
                            <div class="col-md-3">
                                <x-button type="import" route="{{ route('admin.order.codcreate') }}" name="Import"/>
                            </div>
                            @endif
                    </div>
                    
                    <div class="card-body">
                        <form id="data"action="{{ route('cod') }}" method="GET">
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
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">Status</label>
                                        <select name="codstatus" class="form-control">
                                            <option value='all' @if ($codstatus == 'all') Selected @endif>All</option>
                                            <option value='pending' @if ($codstatus == 'pending') Selected @endif>Pending</option>
                                            <option value='success' @if ($codstatus == 'success') Selected @endif>Success</option>
                                               
                                        </select>
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
        @endif   
        <div class="col-xl-12">
            {{--@if (!in_array($user->role_id,array('1','2'))) --}}

    <div class="container-fluid">
        
        <div class="row clearfix">
            <div class="col-3">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Remitted till date</h5>
                    </div>
                    <div class="card-body">
                        <h5>RS. {{ $total }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Last Remittance</h5>
                    </div>
                    <div class="card-body">
                        <h5>RS. {{ round(isset($lastremitance[0]) && is_object($lastremitance[0]) ? ($lastremitance[0]->lastlemamount ?? 0) : 0, 2) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Next Remittance(Expected)</h5>
                    </div>
                    <div class="card-body">
                        <h5>RS. {{ $next }}  </h5>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Total Remittance Due</h5>
                    </div>
                    <div class="card-body">
                        
                        <h5>RS. {{$pending}}</h5>
                    </div>
                </div>
            </div>
        </div>
    {{--@endif--}}
        </div>
        <div class="col-xl-12">
        <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
            @csrf
            <div class="card  new_orders">
                <div class="header d-flex justify-content-between" style="padding-bottom: 0;">
                    <h2>Remittance List</h2>
                    @if ($user->role_id =='1')
                        <div class="form-group col-2 mb-0">
                            <label class="mr-2">@lang('Action')</label>
                            <select class="form-control" name="status" id="myselect">
                                <option value="" selected disabled>@lang('Select One')</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        
                    @endif
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
                                        <!--<th>Created</th>-->
                                        <th>Delivered</th>
                                        <th>Payment Date</th>
                                        <th>Total Amount Collected</th>
                                        <th>Paid Amount</th>
                                        <th>Shipping Charge</th>
                                        <th>COD Charge</th>
                                        <th>RTO</th>
                                        <th>Cod Remittance</th>
                                        <th>Payment-id</th>
                                        <th>UTR</th>
                                        <th>Remark</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($order)>0)
                                        @foreach($order as $or)
                                        <tr>
                                            <td class="text-center">
                                                <label class="fancy-checkbox{{ $or->id }}">
                                                    <input class="checkbox-tick" type="checkbox" name="id[{{ $or->id }}]"
                                                        value="{{ $or->id }}">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td><a href="{{ route('admin.order.detail', $or->id) }}" target="_blank">{{ $or->vendor_order_id }}</a></td>
                                            <td>{{ $or->use_id }}</td>
                                            <td>{{ $or->name }}</td>
                                            <td>{{ $or->tracking_info  }}</td>
                                            <td> {{@$couriers[$or->ship_courier_id]['name']}} </td>
<!--                                            <td>
                                                <span class="fa fa-calendar"></span>&nbsp;
                                                {{ \Carbon\Carbon::parse($or->created_at)->format('d M, Y') }}<br>
                                                <span class="fa fa-clock-o"></span>&nbsp;
                                                {{ \Carbon\Carbon::parse($or->created_at)->format('H:i') }}
                                            </td>-->
                                            <td>
                                                
                                                {{ \Carbon\Carbon::parse($or->delivered_date)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                {{ $or->cod_transaction_date ? \Carbon\Carbon::parse($or->cod_transaction_date)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>{{ $or->total }}</td>
                                            <td>{{ $or->cod_paid_amount ?? '-' }}</td>
                                            <td>{{ $or->freight +$or->gst_freight }}</td>
                                            <td>{{ $or->cod +$or->gst_cod }}</td>
                                            <td>0</td>
                                            <td>{{ $or->total }}</td>
                                            <td>
                                                @if($or->remittance_id)
                                                 {{$or->remittance_id}}
                                                {{--<a href="coddownload/{{$or->remittance_id}}"> <i class="fa fa-upload"></i></a>--}}
                                                @else 
                                                - 
                                                @endif
                                            </td>
                                            <td>{{ $or->cod_utr ?? '-' }}</td>
                                            <td>{{ $or->cod_remark ?? '-' }}</td>
                                            <td>
                                            @if ($or->cod_status == 'success')
                                            Paid
                                            @else
                                            {{ ucfirst($or->cod_status) }}
                                            @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary edit-remittance-btn" 
                                                    data-id="{{ $or->id }}"
                                                    data-awb="{{ $or->tracking_info }}"
                                                    data-paid-amount="{{ $or->cod_paid_amount }}"
                                                    data-payment-date="{{ $or->cod_transaction_date ? \Carbon\Carbon::parse($or->cod_transaction_date)->format('Y-m-d\TH:i') : '' }}"
                                                    data-utr="{{ $or->cod_utr }}"
                                                    data-remark="{{ $or->cod_remark }}"
                                                    data-toggle="modal" data-target="#editRemittanceModal">
                                                    <i class="fa fa-edit"></i> Edit
                                                </button>
                                            </td>
                                            {{-- @if($user->role_id =='1')
                                                @if ($or->cod_status != 'success')
                                                <td>
                                                    <!--<a href="{{ route('admin.order.markpaid',$or->id ) }}" class="btn btn-success" title="Paid"-->
                                                    <!--    style="width:110px;" data-order-id=""><span-->
                                                    <!--        class="sr-only">Paid</span> Mard as Paid</a>-->
                                                </td>
                                                @else
                                                    <td>
                                                        <a href="#" class="btn btn-warning" title="Paid"
                                                        style="width:110px;" data-order-id=""><span
                                                            class="sr-only">Paid</span>Paid</a>
                                                    </td>
                                                @endif
                                            @endif
                                            --}}
                                            
                                        @endforeach
                                    @else
                                    <tr><td colspan=19> No Order Delivered yet</td></tr>
                                    @endif
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

<!-- Edit Remittance Modal -->
<div id="editRemittanceModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Remittance (AWB: <span id="modal-awb-display"></span>)</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="editRemittanceForm" method="POST" action="{{ route('admin.order.updateremittance') }}">
                @csrf
                <input type="hidden" name="order_id" id="modal-order-id">
                <div class="modal-body">
                    <div class="form-group">
                         <label>Paid Amount</label>
                         <input type="number" step="any" name="cod_paid_amount" id="modal-paid-amount" class="form-control" required>
                    </div>
                    <div class="form-group">
                         <label>Payment Date</label>
                         <input type="datetime-local" name="cod_transaction_date" id="modal-payment-date" class="form-control">
                    </div>
                    <div class="form-group">
                         <label>UTR</label>
                         <input type="text" name="cod_utr" id="modal-utr" class="form-control">
                    </div>
                    <div class="form-group">
                         <label>Remark</label>
                         <textarea name="cod_remark" id="modal-remark" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
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

        $(document).on('click', '.edit-remittance-btn', function() {
            var id = $(this).data('id');
            var awb = $(this).data('awb');
            var paidAmount = $(this).data('paid-amount');
            var paymentDate = $(this).data('payment-date');
            var utr = $(this).data('utr');
            var remark = $(this).data('remark');

            $('#modal-order-id').val(id);
            $('#modal-awb-display').text(awb);
            $('#modal-paid-amount').val(paidAmount);
            $('#modal-payment-date').val(paymentDate);
            $('#modal-utr').val(utr);
            $('#modal-remark').val(remark);
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