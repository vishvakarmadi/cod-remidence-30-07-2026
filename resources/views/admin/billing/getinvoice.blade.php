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
                            <table class="table table-striped table-hover dataTable js-exportable sorttableexcel">
                                <thead>
                                    <tr>
                                        <th>Bill Type</th>
                                        <th>Carrier</th>
                                        <th>AWB Number</th>
                                        <th>Shipment Status</th>
                                        <th>Seller Name</th>
                                        <th>Order ID</th>
                                        <th>Payment Type</th>
                                        <th>Pincode</th>
                                        <th>City</th>
                                        <th>Charged Weight</th>
                                        <th>Foward Freight</th>
                                        <th>RTO Freight</th>
                                        <th>Extra Wgt Charges</th>
                                        <th>RTO Extra Wgt Charges</th>
                                        <th>COD Charges</th>
                                        <th>Sub Total</th>
                                        <th>IGST</th>
                                        <th>SGST</th>
                                        <th>CGST</th>
                                        <th>Grand Total</th>
                                        <th>Order Amount</th>
                                        <th>Warehouse City</th>
                                        <th>Warehouse State</th>
                                        <th>Warehouse Pin Code</th>
                                        @for($j=1;$j<=10;$j++)
                                        <th>SKU({{$j}})</th>
                                        <th>Product({{$j}})</th>
                                        <th>Quantity({{$j}})</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i=0;$i<count($in_data);$i++)
                                    <tr>
                                        <td>{{$in_data[$i]['Bill Type']}}</td>
                                        <td>{{$in_data[$i]['Carrier']}}</td>
                                        <td>{{$in_data[$i]['AWB Number']}}</td>
                                        <td>{{$in_data[$i]['Shipment Status']}}</td>
                                        <td>{{$in_data[$i]['Seller Name']}}</td>
                                        <td>{{$in_data[$i]['Order ID']}}</td>
                                        <td>{{$in_data[$i]['Payment Type']}}</td>
                                        <td>{{$in_data[$i]['Pincode']}}</td>
                                        <td>{{$in_data[$i]['City']}}</td>
                                        <td>{{($in_data[$i]['Charged Weight'])}}</td>
                                        <td>
                                            @if ($in_data[$i]['Foward Freight'] !='-')
                                                {{round($in_data[$i]['Foward Freight'],2)}}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($in_data[$i]['RTO Freight'] !='-')
                                                {{round($in_data[$i]['RTO Freight'],2)}}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($in_data[$i]['Extra Wgt Charges'] !='-')
                                                {{round($in_data[$i]['Extra Wgt Charges'],2)}}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($in_data[$i]['RTO Extra Wgt Charges'] !='-')
                                                {{round($in_data[$i]['RTO Extra Wgt Charges'],2)}}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($in_data[$i]['COD Charges'] !='-')
                                                {{round($in_data[$i]['COD Charges'],2)}}
                                            @endif
                                        </td>
                                        <td>{{round($in_data[$i]['Sub Total'],2)}}</td>
                                        <td>
                                         @if ($in_data[$i]['pstate']== 'Haryana' )  
                                         0
                                         @else
                                         {{round($in_data[$i]['gst'],2)}}
                                         @endif
                                        </td>
                                        <td>
                                            @if ($in_data[$i]['pstate']== 'Haryana' ) 
                                                {{round(($in_data[$i]['gst']/2),2)}}
                                            @else
                                                 0
                                            @endif
                                        
                                        </td>
                                        <td>
                                            @if ($in_data[$i]['pstate']== 'Haryana' ) 
                                                {{round(($in_data[$i]['gst']/2),2)}}
                                            @else
                                                 0
                                            @endif
                                        
                                        </td>
                                        <td>{{$in_data[$i]['Grand Total']}}</td>
                                        <td>{{$in_data[$i]['Order Amount']}}</td>
                                        <td>{{$in_data[$i]['Warehouse City']}}</td>
                                        <td>{{$in_data[$i]['Warehouse State']}}</td>
                                        <td>{{$in_data[$i]['Warehouse Pin Code']}}</td>
                                        @for($j=1;$j<=10;$j++)
                                            <td>{{$in_data[$i]['SKU('.$j.')']}}</td>
                                            <td>{{$in_data[$i]['Product('.$j.')']}}</td>
                                            <td>{{$in_data[$i]['Quantity('.$j.')']}}</td>
                                        @endfor
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                    

            </div>
        </div>
    </div>



@endsection
