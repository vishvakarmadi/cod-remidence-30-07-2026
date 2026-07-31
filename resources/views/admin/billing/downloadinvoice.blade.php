@extends('admin.admin_layouts')
@section('admin_content')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <!-- Main body part  -->
    <div class="container-fluid">
        <!-- Page header section  -->
        <div class="block-header">
            <div class="row clearfix">
                
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-12">

                <div class="card mt-30 shipping_charges">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Invoice Data</h5>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <span style="color: red">GST Included*</span>
                            <table class="table table-striped table-hover dataTable js-exportable sorttableexcel">
                                
                                <thead>
                                    <tr>
                                        <th>Invoice id</th>
                                        <th>Bill Type</th>
                                        <th>Carrier</th>
                                        <th>AWB Number</th>
                                        <th>Shipment Status</th>
                                        <th>Seller Name</th>
                                        <th>Seller state</th>
                                        <th>Order ID</th>
                                        <th>Pincode</th>
                                        <th>City</th>
                                        <th>Freight</th>
                                        <th>COD Charges</th>
                                        <th>RTO Charges</th>
                                        <th>Extra Wgt Charges</th>
                                        <th>RTO Extra Wgt Charges</th>
                                        <th>Cacellation Amount</th>
                                        <th>COD Refunded Amount</th>
                                        <th>RTO Refunded Amount</th>
                                        <th>Extra Weight Refunded</th>
                                        <th>Freight Refunded Amount</th>
                                       
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($in_data as $datas)
                                    @foreach($datas as $data)
                                    <tr>
                                       <td> {{$data['invoice_id']}}</td>
                                       <td> {{$data['bill_type']}}</td>
                                       <td> {{$data['courier']}}</td>
                                       <td> {{$data['awb']}}</td>
                                       <td> 
                                            @if($data['status'] =='na')
                                                    @if($data['cancel_amount'] !=0)
                                                    Canceled
                                                    @endif
                                            @else
                                                {{$data['status']}}
                                            @endif
                                        </td>
                                        <td>{{$data['seller_name']}}</td>
                                        <td>{{$data['pstate']}}</td>
                                        <td>{{$data['order_id']}}</td>
                                        <td>{{$data['pincode']}}</td>
                                        <td>{{$data['city']}}</td>
                                        <td>{{$data['freight']}}</td>
                                        <td>{{$data['cod']}}</td>
                                        <td>{{$data['rto']}}</td>
                                        <td>{{$data['extra_weight']}}</td>
                                        <td>{{$data['extra_weight_rto']}}</td>
                                        <td>{{$data['cancel_amount']}}</td>
                                        <td>{{$data['cod_refunded']}}</td>
                                        <td>{{$data['rto_refunded']}}</td>
                                        <td>{{$data['extraweight_refunded']}}</td>
                                        <td>{{$data['freight_refunded']}}</td>
                                        
                                    </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                    

            </div>
        </div>
    </div>



@endsection
