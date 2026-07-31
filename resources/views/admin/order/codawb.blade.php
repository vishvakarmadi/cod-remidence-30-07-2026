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
         
        <div class="col-xl-12">
            {{--@if (!in_array($user->role_id,array('1','2'))) --}}

    
        <div class="col-xl-12">
        <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
            @csrf
            <div class="card  new_orders">
                <div class="header d-flex justify-content-between" style="padding-bottom: 0;">
                    <h2>Tracking Info Description</h2>
                   <input type="hidden" name ='path' value="all">
                </div>
                <div class="">
                        <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sorttable" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Order Id</th>
                                        <th>SellerId</th>
                                        <th>Seller</th>
                                        <th>Tracking Id</th>
                                        <th>Courier Name</th>
                                        <th>Delivered</th>
                                        <th>Charge Date</th>
                                        <th>Total Amount Collected</th>
                                        <th>Shipping Charge</th>
                                        <!-- <th>RTO Charge</th>
                                        <th>Extra Weight Charge</th>
                                        <th>Extra Weight RTO Charge</th> -->
                                        
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($order)>0 || count($ordextra)>0)
                                        @foreach($order as $or)
                                        <tr>
                                            <td>{{ $or->vendor_order_id }}</td>
                                            <td>{{ $or->use_id }}</td>
                                            <td>{{ $or->name }}</td>
                                            <td>{{ $or->tracking_info  }}</td>
                                            <td> {{@$couriers[$or->ship_courier_id]['name']}} </td>
                                            <td>
                                                
                                                {{ \Carbon\Carbon::parse($or->delivered_date)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                -
                                            </td>
                                            <td>{{ $or->total }}</td>
                                            <td>{{ $or->freight +$or->gst_freight }}</td>
                                            <!-- <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                             -->
                                            
                                            
                                        @endforeach

                                        @foreach($ordextra as $or)
                                        <tr style="background-color: antiquewhite;">
                                            <td>{{ $or->vendor_order_id }}</td>
                                            <td>{{ $or->use_id }}</td>
                                            <td>{{ $or->name }}</td>
                                            <td>{{ $or->tracking_info  }}</td>
                                            <td> {{@$couriers[$or->ship_courier_id]['name']}} </td>
                                            <td>
                                                
                                                {{ \Carbon\Carbon::parse($or->delivered_date)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                
                                                {{ \Carbon\Carbon::parse($or->t_date)->format('d/m/Y') }}
                                            </td>
                                            <td>0</td>
                                            <td>0</td>
                                            <!-- <td>
                                                @if($or->remarks =='Amount Debit for RTO')
                                                    {{$or->debit}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($or->remarks =='Amount Debit for extra weight')
                                                    {{$or->debit}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($or->remarks =='Amount Debit for extra weight - RTO')
                                                    {{$or->debit}}
                                                @endif
                                            </td> -->
                                            
                                           
                                            
                                            
                                        @endforeach
                                    @else
                                    <tr><td colspan=12> No Order Delivered yet</td></tr>
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