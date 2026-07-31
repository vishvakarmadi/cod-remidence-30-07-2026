<!-- @extends('admin.admin_layouts') -->
@section('admin_content')
<style>
    @media print {
    .pagebreak { page-break-before: always; } /* page-break-after works, as well */
    }
    </style>
<a onclick="printDiv('content_print');" class="btn btn-primary" title="Print"
                                style="width:100px;" data-order-id=""><span
                                    class="sr-only">Print</span> <i class="fa fa-print" style="margin: 5px;"></i>Print</a>
    <div class="card shadow mb-4" id="content_print">
        <?php 
        if(count($manifest->manifest_order) >0){
            for($i=0;$i<count($manifest->manifest_order);$i++){ ?>
                <div style="text-align: center;margin-top:5px;page-break-before: always;">
                    <h5>Aframax Pvt Ltd</h5>
                </div>
                <hr style="border-top: 1px solid black;">
                <div style="text-align: center;">
                    <h6 style="font-size:12px">TAX INVOICE</h6>
                </div>
                <hr style="border-top: 1px solid black;">
                <div class="card-body">
                    <div class="row" style="margin-top:5%;margin-left:10px">
                        <div class="" style="padding-left: 2%;flex: 0 0 66%;max-width: 66%;">
                            <b>SHIPPING ADDRESS</b><br>
                            {{ucfirst($manifest->manifest_order[$i][0]['ship_fname']).' '.ucfirst($manifest->manifest_order[$i][0]['ship_lname'])}} <br>
                            {{($manifest->manifest_order[$i][0]['ship_address'])}} <br>
                            {{($manifest->manifest_order[$i][0]['ship_address_2'])}} <br>
                            {{($manifest->manifest_order[$i][0]['ship_city'])}} <br>
                            {{($manifest->manifest_order[$i][0]['ship_pincode'])}} <br>
                        </div>
                        <div class="" style="flex: 0 0 50%;max-width: 33%;">
                            <b>INVOICE DETAILS</b><br>
                            <b>Invoice No. : #in_{{$manifest->manifest_order[$i][0]['order_id']}}<br>
                            <b>GST NO : {{$manifest->manifest_order[$i][0]['ship_gstin']}}<br>
                            <b>INVOICE DATE : {{now()}}<br>
                            <b>CHANNEL : {{$manifest->manifest_order[$i][0]['channel']}}<br>
                            <b>SHIPPED BY : {{ @$couriers[$manifest->courier_id]['name'] }}<br>
                            <b>AWB NO : {{ $manifest->manifest_order[$i][0]['tracking_info'] }}<br>
                            <b>PAYMENT METHOD : {{ strip_tags($manifest->manifest_order[$i][0]['payment_mode']) }}<br>
                            
                        </div>
                    </div>
                    <div style="margin-top:5%;margin-left:10px">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>PRODUCT NAME</th>
                                    <th>PRODUCT SKU</th>
                                    <th>QTY</th>
                                    <th>Price</th>
                                    <th>Tax</th>
                                    <th>Discount</th>
                                    <th>T.Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total_amount =0;?>
                            @foreach($manifest->manifest_order[$i][0]['detail'] as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="white-space: break-spaces;">{{ $detail->name }}</td>
                                <td>{{ $detail->code }}</td>
                                <td>{{ $detail->qty }}</td>
                                <td>{{ $detail->price }}</td>
                                <td>{{ $detail->tax_amount }}</td>
                                <td>
                                <?php 
                                                               $discount =0;
                                    if($detail->discount_type !='' || $detail->discount_type != null){
                                    if($detail->discount_type =='f'){
                                        $discount =$detail->discount;
                                    }
                                    if($detail->discount_type =='p'){
                                        $discount = ($detail->price * $detail->qty * $detail->discount)/100;
                                    }
                                } ?>
                                {{$discount}}</td>
                                <td>{{ $detail->total_price }}
                                    <?php $total_amount = $total_amount + $detail->total_price; ?>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan=5></td>
                                <td colspan=2>Total Amount</td>
                                <td >{{$total_amount}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top:10%;margin-left:10px">
                    <div style="width:200px;height:70px;border:1px solid black"></div>
                    Authorized Signature
                    </div>
                </div>
            <?php }
        }else{ ?>
            <div style="text-align: center;margin-top:5px;page-break-before: always;">
                <h5>Aframax Pvt Ltd</h5>
            </div>
            <hr style="border-top: 1px solid black;">
            <div style="text-align: center;">
                <h6 style="font-size:12px">TAX INVOICE</h6>
            </div>
            <hr style="border-top: 1px solid black;">
            <div class="card-body">
                NO Order in this manifest
            </div>
        <?php }     ?>    
    </div>
<script language="javascript">
    function printDiv(divId) {
     var printContents = document.getElementById(divId).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
@endsection
