<!-- @extends('admin.admin_layouts') -->
@section('admin_content')

<a onclick="printDiv('content_print');" class="btn btn-primary" title="Print"
                                style="width:100px;" data-order-id=""><span
                                    class="sr-only">Print</span> <i class="fa fa-print" style="margin: 5px;"></i>Print</a>
    <div class="card shadow mb-4" id="content_print">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"> Aframax Pvt Ltd</h6>
            <br><span style="font-size:10px"> Generated on {{now()}}</span>
        </div>
        <div class="card-body">
            <span style="font-size:12px">Manifest id : <b>{{$manifest->id}}</b> </span><br>
            <span style="font-size:12px">Courier : <b>{{ @$couriers[$manifest->courier_id]['name'] }}</b></span>
            <div style="float:right;margin-left:50px">
                <span style="font-size:12px">Total shipments to dispatch : <b>{{ count($manifest->manifest_order) }}</b> </span>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Order no</th>
                            <th>AWB no</th>
                            <th>No.of items</th>
                            <th>Contents</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php for($i=0;$i<count($manifest->manifest_order);$i++){ ?>
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $manifest->manifest_order[$i][0]['vendor_order_id'] }}</td>
                            <td>{{ $manifest->manifest_order[$i][0]['tracking_info'] }}</td>
                            <td>{{ count($manifest->manifest_order[$i][0]['detail'])}}</td>
                            <td style="white-space: break-spaces;">@foreach($manifest->manifest_order[$i][0]['detail'] as $detail){{$detail->name}}<br>
                                 @endforeach
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                
            </div>
            <br>
            <div class="print_footer" style="margin-top:3%">
                <span class="textCenter" style="border-bottom: 1px dotted black;border-top: 1px dotted black;margin-left: 28%;padding: 8px;">
                    <b>To Be Filled By Logistics Executives</b>
                </span>
                <br/>
                <div class=" row" style="margin-top:20px">
                    <div class="" style="float:left;flex: 0 0 66%;max-width: 66%;">
                        <h6 class="title">Pickup Time : <span>---------------</span></h6><br>
                        <h6 class="title"><span>FE Name : <span>---------------</span></h6><br>
                        <h6 class="title"><span>FE Signature  : <span>---------------</span></h6><br>
                        <h6 class="title"><span>FE Phone  : <span>---------------</span></h6><br>
                    </div>
                    <div class="" style="flex: 0 0 50%;max-width: 33%;">
                        <h6 class="title">Total Item Picked: <span>---------------</span></h6><br>
                        <h6 class="title"><span>Seller Signature  : <span>---------------</span></h6><br>
                        
                    </div>
                </div>
            </div>
            <div class="footer_signature" style="text-align: center;">
            This is a system generated document
            </div>
        </div>
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
