@extends('admin.admin_layouts')
@section('admin_content')

<div class="container-fluid">
    <div class="row clearfix">
        
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 mt-2 font-weight-bold text-primary">Remittance Report</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered sorttable" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <th>SELLER NAME</th>
                            <th>SELLER ID</th>
                            <th>SELLER MAIL ID</th>
                            <th>ORDER NO</th>
                            <th>AWB</th>
                            <th>MANIFEST ID</th>
                            <th>STATUS</th>
                            <th>SKU Qty</th>
                            <th>Qty</th>
                            <th>ORDER VALUE</th>
                            <th>DELIVERED DATE</th>
                            <th>REMIITANCE CYCLE (Days)</th>
                            <th>REMITTANCE DATE</th>
                            <th>TOTAL REMITTANCE</th>
                            <th>SUM OF PAYBLE AMOUNT</th>
                            <th>Bank name</th>
                            <th>Beneficiary Name</th>
                            <th>Bank Account No</th>
                            <th>IFSC Code</th>
                            
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{$order->name}}</td>
                                    <td>{{$order->use_id}}</td>
                                    <td>{{$order->email}}</td>
                                    <td>{{$order->order_id}}</td>
                                    <td>{{$order->tracking_info}}</td>
                                    <td>{{$order->manifest_id}}</td>
                                    <td>
                                        @if($order->manifest_id && strip_tags($order->status) == 'Shipped')
                                            Manifested
                                        @else
                                            {{ strip_tags($order->status) }}
                                        @endif
                                    </td>
                                    <td>{{count($order->detail)}}</td>

                                    <?php 
                                    $count_squ =0;
                                    foreach($order->detail as $detail):
                                        $count_squ = $count_squ + $detail->qty;
                                    endforeach
                                    ?>
                                    <td>{{$count_squ}}</td>
                                    <td>{{$order->total}}</td>
                                    <td>
                                    {{ \Carbon\Carbon::parse($order->delivered_date)->format('d M, Y') }}</td>
                                    <td>7</td>
                                    <td>{{ \Carbon\Carbon::parse($order->delivered_date)->addDays(7)->format('d M, Y')}}</td>
                                    <td>{{$order->total}}</td>
                                    <td>{{$order->total}}</td>
                                    <td>{{$order->bank_name}}</td>
                                    <td>{{$order->beneficiary_name}}</td>
                                    <td>{{$order->account_no}}</td>
                                    <td>{{$order->ifsc_code}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>   
    </div>     
</div>

@endsection