@extends('admin.admin_layouts')
@section('admin_content')
<style>
.card.pt-30 .card-title {
    font-size: 20px;
    font-family: 'FontAwesome';
}
.col-6 .card-body{
    padding: 15px;
    text-align: center;
}
.col-6 .card-header{
    text-align: center;
}
.btn-group{
    display: block;
}
.btn-group .multiselect{
    width:100%
}
.multiselect-container, 
.multiselect-container>li>a>label.checkbox {
    width: 100%;
}
</style>

<div class="row clearfix">
    @if ($user->role_id =='1' || $user->role_id =='2')
        <div class="col-xl-12">
            <div class="card bg-light mb-3">
                <div class="card-header style_filter_header" style="display:flex;flex-wrap: wrap;align-items: center;justify-content: space-between;">
                    <div class="col-md-9">
                        <a href="javascript:void(0)" class="expand">Filters <?php if(empty($re_data)){ echo '<<';}else{ echo '>>';} ?></a>
                    </div>
                    @if($user->role_id =='1')
                    <div class="col-md-3 text-right">
                        <x-button type="import" route="{{ route('admin.order.codcreate') }}" name="Import"/>
                    </div>
                    @endif
                </div>
                
                <div class="card-body">
                    <form id="data" action="{{ route('admin.order.remlist') }}" method="GET">
                        <div class="col-md-12">
                            <?php 
                            $selected_user_id = isset($re_data['user_id']) ? $re_data['user_id'] : '';
                            ?>
                            <div class="show_more" style="width: 100%; <?php if(empty($re_data)){ echo 'display:none'; } ?>">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-control-label">User / Seller</label>
                                        <select name="user_id" class="form-control">
                                            <option value="">All Sellers</option>
                                            @foreach($allusers as $us)
                                                <option value='{{$us->id}}' <?php if($selected_user_id == $us->id){echo 'selected';} ?>>{{ $us->id }} - {{ $us->name }} ({{ $us->mobile }})</option>
                                            @endforeach  
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <x-button type="submit" name="Search" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif   

    <div class="col-xl-12">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-6">
                    <div class="card pt-30">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Total Remittance Paid</h5>
                        </div>
                        <div class="card-body">
                            <h5>RS. {{ number_format($paid, 2) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card pt-30">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Last Remittance</h5>
                        </div>
                        <div class="card-body">
                            <h5>RS. {{ number_format($lastremitance[0]->lastlemamount ?? 0, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 mt-3">
        <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
            @csrf
            <div class="card new_orders">
                <div class="header d-flex justify-content-between" style="padding-bottom: 0;">
                    <h2>Remittance List</h2>
                    <input type="hidden" name='path' value="all">
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered sorttablenew table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>R-Id</th>
                                    <th>Seller-id</th>
                                    <th>Seller</th>
                                    <th>Order Id</th>
                                    <th>AWB Number</th>
                                    <th>Payment Date</th>
                                    <th>Total Amount Collected</th>
                                    <th>Paid Amount</th>
                                    <th>UTR</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                    @if ($user->role_id == '1')
                                    <th data-field="hideexport">Action</th> 
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($order)>0)
                                    @foreach($order as $or)
                                    <tr>
                                        <td>{{ $or->rem_id ?? '-' }}</td>
                                        <td>{{ $or->use_id }}</td>
                                        <td>{{ $or->name }}</td>
                                        <td><a href="{{ route('admin.order.detail', $or->id) }}" target="_blank">{{ $or->vendor_order_id }}</a></td>
                                        <td>{{ $or->tracking_info }}</td>
                                        <td>{{ $or->cod_transaction_date ? \Carbon\Carbon::parse($or->cod_transaction_date)->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $or->total }}</td>
                                        <td>{{ $or->cod_paid_amount ?? '-' }}</td>
                                        <td>{{ $or->cod_utr ?? '-' }}</td>
                                        <td>{{ $or->cod_remark ?? '-' }}</td>
                                        <td>
                                            @if ($or->cod_status == 'success')
                                                Paid
                                            @else
                                                {{ ucfirst($or->cod_status) }}
                                            @endif
                                        </td>
                                        @if ($user->role_id == '1')
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
                                        @endif
                                    </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="{{ $user->role_id == '1' ? 12 : 11 }}">No Order Delivered yet</td></tr>
                                @endif
                            </tbody>
                        </table>        
                    </div>
                </div>
            </div>
        </form>         
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
            if ($(this).text().indexOf('>>') !== -1) {
                $(this).html('Filters <<');
            } else {
                $(this).html('Filters >>');
            }
            $('.show_more').slideToggle('fast');
        });
    })(jQuery);
</script>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('.sorttablenew')) {
            $('.sorttablenew').DataTable().destroy();
        }

        $('.sorttablenew').DataTable({
            paging: true,
            pageLength: 50,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csv',
                    text: 'Export CSV',
                    exportOptions: {
                        columns: ':not([data-field="hideexport"])'
                    }
                }
            ],
            order: [[6, 'desc']] // Order by Payment Date descending
        });
    });
</script>
@endsection