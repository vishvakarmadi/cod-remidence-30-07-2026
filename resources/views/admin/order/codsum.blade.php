@php
use App\Models\Admin\Order;
@endphp

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
                <div class="card bg-light mb-3">
                    <div class="card-header" style="display:flex;flex-wrap: wrap;">
                        <!-- <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Filter</h5> -->
                            <div class="col-md-9"><a href="javascript:void(0)" class="expand">Filters <?php if(empty($re_data)){ echo '<<';}else{ echo '>>';} ?></a></div>
                            @if($user->role_id =='1')
                                <x-button type="create" route="{{ route('admin.order.remlist') }}" name="View Created Remittance"/>
                            @endif
                    </div>
                    
                    <div class="card-body">
                        <form id="data"action="{{ route('codsum') }}" method="GET">
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
                                                    <input class="form-control " type="date" name="end_date" required="" value="{{explode(' ',$re_data['end_date'])[0]}}" id="_2" max="{{date('Y-m-d')}}">
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
            {{--@if (!in_array($user->role_id,array('1','2'))) --}}

    
        <div class="col-xl-12">
        <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
            @csrf
            <div class="card  new_orders">
                <div class="header d-flex justify-content-between" style="padding-bottom: 0;">
                    <h2>Remittance List</h2>
                    @if (in_array($user->role_id,array('1')))
                    <div class="form-group col-2 mb-0">
                        <label class="mr-2">@lang('Action')</label>
                        <select class="form-control" name="status" id="myselect">
                            <option value="" selected disabled>@lang('Select One')</option>
                            <option value="remitance">Add Remittance</option>
                            <option value="downloadrem">Download Remittance</option>
                            
                        </select>
                        <input type="hidden" name ='start_date' value="{{explode(' ',$re_data['start_date'])[0]}}">
                        <input type="hidden" name ='end_date' value="{{explode(' ',$re_data['end_date'])[0]}}">
                    </div>
                    @endif
                   <input type="hidden" name ='path' value="all">
                </div>
                <div class="">
                        <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sorttablenew" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="text-center" data-field="hideexport">
                                            <label class="fancy-checkbox1" style="margin: 0;">
                                                <input class="select-all" type="checkbox" name="checkbox">
                                            </label>
                                        </th>
                                        <th>SellerId</th>
                                        <th>Seller</th>
                                        <th>Sellertype</th>
                                        <th>StartDate</th>
                                        <th>EndDate</th>
                                        <th>Total Amount Collected</th>
                                        <!--<th>Freight Charge</th>-->
                                        <!-- <th>RTO Charge</th>
                                        <th>Extra Weight Charge</th>
                                        <th>Extra Weight RTO Charge</th> -->
                                        <th>Total Payable</th>
                                        <th>Freight Charge</th>
                                        <th>Current Wallet</th>
                                        <th>Current Intransit Orders</th>
                                        <th>Current Intransit Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach($row as $key=>$or)
                                        <tr>
                                            <td class="text-center">
                                                <label class="fancy-checkbox{{$key}}">
                                                    <input class="checkbox-tick" type="checkbox" name="id[{{$key}}]"
                                                        value="{{$or['datasum']}}">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>{{ $key }}</td>
                                            <td>{{ $or['seller'] }}</td>
                                            <td>{{ $or['sellertype'] }}</td>
                                            <td>{{ $or['start_date'] }}</td><!-- comment -->
                                            <td>{{ $or['end_date'] }}</td> 
                                            <td>{{ $or['total_re'] }}</td>
                                            <!--<td>0</td>-->
                                            <!-- <td>{{ $or['rto'] }}</td>
                                            <td>{{ $or['extra_weight'] }}</td>
                                            <td>{{ $or['extra_weight_rto'] }}</td> -->
                                            <td>{{ $or['total_re'] -(0)}}</td>
                                            
                                            @php
                                            $intransitdetails =  Order::getintransitdetails($key);
                                            $wllaetonlast =  Order::getwalleton($or['end_date'],$key);
                                            @endphp
                                            <td>{{$wllaetonlast}}</td>
                                            <td>{{ $or['wallet_blc']}}</td>
                                            <td>{{$intransitdetails['count']}}</td>
                                            <td>{{$intransitdetails['total']}}</td>
                                            <td>
                                                <a href="{{ route('codrem') }}?user_id%5B%5D={{ $key }}&start_date={{ $or['start_date'] }}&end_date={{ $or['end_date'] }}" class="btn btn-danger"
                                                    title="View"><span class="sr-only">View</span> <i
                                                        class="fa fa-file-excel-o"></i></a>
                                            </td>
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
                if(action_type == 'remitance'){
                    if (confirm('Are you sure to add Remittance?')) {
                        $('#myForm').submit();
                    }else{
                        $('select[name=status]').val('');
                    }
                }else{
                    $('#myForm').submit();
                }
            } else {
                toastr.error('Select atleast one seller');
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
<script>
    $(document).ready(function() {
    // Destroy existing DataTable instance, if it exists
    if ($.fn.DataTable.isDataTable('.sorttablenew')) {
        $('.sorttablenew').DataTable().destroy();
    }

    // Initialize DataTable with your options
    $('.sorttablenew').DataTable({
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'csv',
                text: 'Export CSV',
                filename: function() {
                    // Get the table name
                    var tableName = $('.sorttablenew').attr('name');
                    return tableName;
                },
                exportOptions: {
                    columns: ':not([data-field="hideexport"])'
                }
            }
        ]
    });

    // Sorting the DataTable by a specific column in descending order
    $('.sorttablenew').DataTable().order([2, 'desc']).draw();
    });
    "use strict";
        (function($) {
            $(document).ready(function() {
                let myModal = new bootstrap.Modal(document.getElementById('my-modal'));
                let action = `{{ route('admin.order.saverem') }}`

                
                $('.edit-btn').on('click', function(e) {
                let action = `{{ route('admin.order.saverem', ':id') }}`;
                let data = $(this).data('edit');
                $('.modal-title').text("@lang('Pay Remittance')");
                $("input[name=cod_amount]").val(data.cod_amount);
                $("input[name=shipping_amount]").val(data.shipping_amount);
                $("select[name=rem_id]").val(data.id);
                
                
                // }
                // $("select[name=country_id]").val(data.country_id);

                $('form').attr("action", action.replace(":id", data.id));
                myModal.show();
                
                });


            });
        })(jQuery);
</script>
@endsection