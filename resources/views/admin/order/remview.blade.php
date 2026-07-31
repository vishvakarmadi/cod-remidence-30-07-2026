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
            

    
        <div class="col-xl-12">
        <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
            @csrf
            <div class="card  new_orders">
                <div class="header d-flex justify-content-between" style="padding-bottom: 0;">
                    <h2>Remittance List</h2>
                    
                </div>
                <div class="">
                        <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sorttablenew" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>R-Id</th>
                                        <th>Seller</th>
                                        <th>Order_id</th>
                                        <th>AWB</th>
                                        <th>Delivered On</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach($orders as $or)
                                        <tr>
                                            <td>{{$or->remittance_id}}</td>
                                            <td>{{$or->user_id}}</td>
                                            <td>{{$or->vendor_order_id}}</td>
                                            <td>{{$or->tracking_info}}</td>
                                            <td> {{ \Carbon\Carbon::parse($or->delivered_date)->format('d M, Y') }}</td>
                                            <td>{{$or->total}}</td>
                                            <td>{{$or->cod_status}}</td>
                                            
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