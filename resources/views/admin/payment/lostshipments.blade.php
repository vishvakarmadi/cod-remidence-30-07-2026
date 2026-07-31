<?php 
use App\Models\Admin\Order;
?>
@extends('admin.admin_layouts')
@section('admin_content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<style>
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
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xl-12">
            <div class="card mb-3">
                <div class="card-header" style="display:flex;flex-wrap: wrap;">
                    <!-- <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Filter</h5> -->
                        <div class="col-md-9"><a href="javascript:void(0)" class="expand">Filters <?php if(empty($re_data)){ echo '<<';}else{ echo '>>';} ?></a></div>
                        
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.payment.lostshipments') }}" method="GET">
                    @csrf
                        <div class="col-md-12">
                            <div class="show_more" style="width: 100%; <?php if(empty($re_data)){ echo 'display:none'; } ?>">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">User<span class="required">*</span></label>
                                        <!-- <input mbsc-input id="my-input" data-dropdown="true" data-tags="true" /> -->
                                        <select id="multiple-checkboxes" name="user_id[]" class="form-control" required multiple="multiple">
                                            @foreach($users as $user)
                                                <option value='{{$user->id}}' <?php if(in_array($user->id,$re_data['user_id'])){echo 'selected';} ?> >{{$user->name}}</option>
                                            @endforeach
                                            
                                        </select>
                                    </div>
                                    
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">Courier</label>
                                        <select name="ship_courier_id" class="form-control">
                                            <option value='0' >Courier</option>
                                            <option value='1' <?php if($re_data['ship_courier_id'] =='1'){echo 'selected';} ?> >Ecom Express</option>
                                            <option value='2' <?php if($re_data['ship_courier_id'] =='2'){echo 'selected';} ?> >Delhivery</option>
                                            <option value='3' <?php if($re_data['ship_courier_id'] =='3'){echo 'selected';} ?> >Bludart</option>
                                            <option value='4' <?php if($re_data['ship_courier_id'] =='4'){echo 'selected';} ?> >XpressBees</option>
                                            <option value='5' <?php if($re_data['ship_courier_id'] =='5'){echo 'selected';} ?> >DTDC</option>
                                            <option value='6' <?php if($re_data['ship_courier_id'] =='6'){echo 'selected';} ?> >Smartr</option>
                                            <option value='7' <?php if($re_data['ship_courier_id'] =='7'){echo 'selected';} ?> >Ekart</option>
                                            <option value='8' <?php if($re_data['ship_courier_id'] =='8'){echo 'selected';} ?> >Shadowfax</option>
                                            <option value='9' <?php if($re_data['ship_courier_id'] =='9'){echo 'selected';} ?> >ATS</option>
                                            
                                            
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <x-button size="col-lg-3" type="submit" name="Search" />
                                </div>
                                <script>
                                    $(document).ready(function() {
                                        $('#multiple-checkboxes').multiselect({
                                        includeSelectAllOption: true,
                                        });
                                    });
                                    $(document).ready(function() {
                                        $('#multiple-checkboxesstus').multiselect({
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
        <div class="card new_orders">
        <div class="header d-flex justify-content-between" style="padding-bottom: 0;">
        <h2>Lost Shipments</h2>
               <!-- <button id="btnExport" class="btn btn-primary" onclick="fnExcelReport();" style="height:30px;font-size:12px"><i class="fa fa-file-excel-o" style=""></i> EXPORT </button> -->
           </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered sorttableexceldate" id="dataTable" name='Lost_shipments{{date('Ym')}}' width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Orderid</th>
                                <th>Order Number</th>
                                <th>Tracking Number</th>
                                <th>Seller</th>
                                <th>Courier</th>
                                <th>Order Created date</th>
                                <th>Amount</th>
                                <th>Amount Paid</th>
                                <th>Payment Status</th>
                                <th data-field="hideexport">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lostorder as $order)
                                <tr>
                                    <td>{{$order->id}}</td>
                                    <td> <a
                                                href="{{ route('admin.order.detail',$order->id) }}">{{ $order->vendor_order_id }}</a>
                                    </td>
                                    <td>{{$order->tracking_info}}</td>
                                    <td>{{$order->user_id}}</td>
                                    <td>{{@$couriers[$order->ship_courier_id]['name']}}</td>
                                    <td>{{$order->created_at}}</td>
                                    <td>{{$order->total}}</td>
                                    <td>
                                        @if(empty($order->lostpayment))
                                        0
                                        @else
                                        {{$order->lostpayment->lost_payment_amountpaid}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(empty($order->lostpayment))
                                        Pending
                                        @else
                                        {{$order->lostpayment->	lost_payment_status}}
                                        @endif</td>
                                    <td>
                                    @if(empty($order->lostpayment))
                                    <button type="button" class="btn btn-sm edit-btn btn-success" title="Edit"
                                                    data-edit='@json($order)'>
                                                    <b>Pay</b>
                                    </button>
                                    @endif
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>   
        </div>   
    </div>     
     <div id="my-modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog" role="document" style="max-width: 1000px;top:20%">
                            <div class="modal-content">
                                <div class="modal-header card-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" class="myform">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="modal-body">
                                             
                                                <div class="form-group">
                                                    <label class="st_url">Total Amount</label>
                                                    <input class="form-control" id="total" type="text" name="total" required readonly
                                                        value="{{ old('total') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="st_url">Order id</label>
                                                    <input class="form-control" id="ord_id" type="text" name="ord_id" required readonly
                                                        value="{{ old('ord_id') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="st_url">Amount Paid(in account)*</label>
                                                    <input class="form-control" id="paid" step="any" type="text" name="paid" required
                                                        value="{{ old('paid') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="st_url">Utr*</label>
                                                    <input class="form-control" id="utr" type="text" name="utr" required
                                                        value="{{ old('utr') }}">
                                                </div>
                                                

                                              <div class="form-group"> <label for="">
                                                <input type="checkbox" name="term" required class="form-control" style="width: 22px;float: left; margin-top: 6px;"> I certify that the above information is true to the best of my knowledge</label> 
                                              </div> 
                                              
                                            </div>
                                        </div>

                                        
                                    </div>
                                    <div class="col-lg-4 mb-4">
                                        <button type="submit" class="btn btn-secondary h-45 w-100">@lang('Submit')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>  
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.0/xlsx.full.min.js"></script>
<script>
    function fnExcelReport() {
    var tab = document.getElementById('dataTable');
    var wb = XLSX.utils.table_to_book(tab);
    XLSX.writeFile(wb, 'report.xlsx');
}
$('.expand').on('click',function() {
            if ($(this).text() == 'Filters>>') {
                $(this).text('Filters<<');
            } else {
                $(this).text('Filters>>');
            }
            $('.show_more').slideToggle('fast');
        });

    "use strict";
        (function($) {
            $(document).ready(function() {
                let myModal = new bootstrap.Modal(document.getElementById('my-modal'));
                let action = `{{ route('admin.payment.addlostpay') }}`

                
                $('.edit-btn').on('click', function(e) {
                let action = `{{ route('admin.payment.addlostpay', ':id') }}`;
                let data = $(this).data('edit');
                $('.modal-title').text("@lang('Pay Lost Shipments')");
                $("input[name=total]").val(data.total);
                $("input[name=ord_id]").val(data.id);
                
                
                // }
                // $("select[name=country_id]").val(data.country_id);

                $('form').attr("action", action.replace(":id", data.id));
                myModal.show();
                
                });


            });
        })(jQuery);
       
</script>
@endsection