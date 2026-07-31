@extends('admin.admin_layouts')
@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp
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
            <div class="card bg-light mb-3">
                <div class="card-header" style="display:flex;flex-wrap: wrap;">
                    <!-- <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Filter</h5> -->
                        <div class="col-md-9"><a href="javascript:void(0)" class="expand">Filters <?php if(empty($re_data)){ echo '<<';}else{ echo '>>';} ?></a></div>
                        
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.reports.index_filter') }}" method="POST">
                    @csrf
                        <div class="col-md-12">
                            <div class="show_more" style="width: 100%; <?php if(empty($re_data)){ echo 'display:none'; } ?>">
                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <label>Select Date Range</label><span class="required"> *</span>    <div class="row">
                                            <div class="col-md-6">
                                                <input class="form-control " type="date" name="start_date" required="" value="{{explode(' ',$re_data['start_date'])[0]}}" id="_1">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control " type="date" name="end_date" required="" value="{{explode(' ',$re_data['end_date'])[0]}}" id="_2">
                                            </div>
                                                </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">User<span class="required">*</span></label>
                                        <!-- <input mbsc-input id="my-input" data-dropdown="true" data-tags="true" /> -->
                                        <select id="multiple-checkboxes" name="user_id[]" class="form-control" required multiple="multiple">
                                            @foreach($users as $user)
                                                <option value='{{$user->id}}' <?php if(in_array($user->id,$re_data['user_id'])){echo 'selected';} ?> >{{$user->name}}</option>
                                            @endforeach
                                            
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <x-button type="submit" name="Search" />
                                </div>
                                <script>
                                    $(document).ready(function() {
                                        $('#multiple-checkboxes').multiselect({
                                            includeSelectAllOption: true,
                                            enableFiltering: true,
                                            enableCaseInsensitiveFiltering: true,
                                            filterPlaceholder: 'Search User / Seller...'
                                        });
                                    });
                                </script>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 mt-2 font-weight-bold text-primary">Report</h6>
                <form id="myForm" action="{{ route('admin.update-sheet') }}" method="POST">
                @csrf
                <input type="hidden" name="filename" value="" id="filenameid" />
                @if($session->role_id =='1')
                <button id="downloadBtn" class="btn btn-info">Update Google Sheet</button>
                @endif
                </form>
                <!--<button id="btnExport" class="btn btn-primary" onclick="fnExcelReport();" style="height:30px;font-size:12px"><i class="fa fa-file-excel-o" style=""></i> EXPORT </button>-->
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered sorttableexceldate table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Order id</th>
                            <th>Awb</th>
                            <th>Courier</th>
                            <th>Seller</th>
                            <th>Channel</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>PaymentMode</th>
                            <th>Total Amount</th>
                            <th>Shipping date</th>
                            
                            <th>Manifest id</th>
                            <!--<th>Shipment Status</th>-->
                            <th>Total Attempt Count</th>
                            <th>Customer Name</th>
                            <th>Customer Phone</th>
                            <th>Customer Email</th>
                            <th>Pincode</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Dimension(CM)</th>
                            <th>Weight(kg)</th>
                            <th>Vol.Weight(kg)</th>
                            <!--<th>Shiping Charge</th>-->
                            <th>Zone</th>
                            <th>Order tag</th>
                            <th>Product 1</th>
                            <th>SKU 1</th>
                            <th>QTY 1</th>
                            <th>Price 1</th>
                            <th>Discount type 1</th>
                            <th>Discount amount 1</th>
                            <th>Tax amount 1</th>
                            <th>T. amount 1</th>
                            <th>Product 2</th>
                            <th>SKU 2</th>
                            <th>QTY 2</th>
                            <th>Price 2</th>
                            <th>Discount type 2</th>
                            <th>Discount amount 2</th>
                            <th>Tax amount 2</th>
                            <th>T. amount 2</th>
                            <th>Product 3</th>
                            <th>SKU 3</th>
                            <th>QTY 3</th>
                            <th>Price 3</th>
                            <th>Discount type 3</th>
                            <th>Discount amount 3</th>
                            <th>Tax amount 3</th>
                            <th>T. amount 3</th>
                            <th>Product 4</th>
                            <th>SKU 4</th>
                            <th>QTY 4</th>
                            <th>Price 4</th>
                            <th>Discount type 4</th>
                            <th>Discount amount 4</th>
                            <th>Tax amount 4</th>
                            <th>T. amount 4</th>
                            <th>Product 5</th>
                            <th>SKU 5</th>
                            <th>QTY 5</th>
                            <th>Price 5</th>
                            <th>Discount type 5</th>
                            <th>Discount amount 5</th>
                            <th>Tax amount 5</th>
                            <th>T. amount 5</th>
                            <th>Company name</th>
                            <th>Email</th>
                            <th>SM</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{$order->vendor_order_id}}</td>
                                <td>
                                    @if($order->tracking_info !='')
                                    {{$order->tracking_info}} 
                                    @endif
                                </td>
                                <td>
                                   @if($order->ship_courier_id !='' && $order->ship_courier_id !='0') 
                                   {{ @$couriers[$order->ship_courier_id]['name'] }}
                                   @endif
                                </td>
                                
                                <td>{{$order->user_id}}</td>
                                <td>{{$order->channel}}</td>
                                <td class="date-column">
                                    <span class="fa fa-calendar"></span>&nbsp;
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }} 
                                    <span class="fa fa-clock-o"></span>&nbsp;
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}
                                </td>
                                <td class="anchor-column">
                                    @if($order->manifest_id && strip_tags($order->status) == 'Shipped')
                                        Manifested
                                    @else
                                        {{ strip_tags($order->status) }}
                                    @endif
                                </td>
                                <td class="anchor-column">{!! $order->payment_mode !!}</td>
                                <td>{{ $order->total }}</td>
                                <td>
                                    @if($order->shipped_date != null && $order->shipped_date != '')
                                        {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y') }} 
                                        {{ \Carbon\Carbon::parse($order->shipped_date)->format('H:i') }}
                                    @endif
                                </td>
                                
                                <td>{{$order->manifest_id}}</td>
                                <!--<td>{{$order->status}}</td>-->
                                <td>{{ $order->total_attempt ?? 0 }}</td>
                                <td>{{$order->ship_fname.' '.$order->ship_lname}}</td>
                                <td>{{$order->ship_phone}}</td>
                                <td>{{$order->ship_email}}</td>
                                <td>{{$order->ship_pincode}}</td>
                                <td>{{$order->ship_city}}</td>
                                <td>{{$order->ship_state}}</td>
                                <td>{{$order->length.'*'.$order->breadth.'*'.$order->height}}</td>
                                <td>{{$order->weight/1000}}</td>
                                <td>
                                    @if($order->ship_courier_id == '2' || $order->ship_courier_id == '5')
                                        @if($order->ship_courier_id == '2')
                                            {{($order->length*$order->breadth*$order->height)/4000}}
                                        @else
                                            {{($order->length*$order->breadth*$order->height)/4750}}
                                        @endif    
                                    @else
                                            {{($order->length*$order->breadth*$order->height)/5000}}
                                    @endif
                                </td>
<!--                                <td>
                                    {{$order->shipping_courier_cost}}
                                </td>-->
                                <td>{{$order->zone}}</td>
                                <td>{{$order->tags}}</td>
                                @for($j=0;$j<5;$j++)
                                    @if(isset($order->detail[$j]))
                                        <?php $detail = $order->detail[$j];?>
                                        <td>{{$detail->name}}</td>
                                        <td>{{$detail->code}}</td>
                                        <td>{{$detail->qty}}</td>
                                        <td>{{$detail->price}}</td>
                                        <td>
                                            @if($detail->discount_type =='p')
                                                Percentage
                                            @else
                                                Flat
                                            @endif
                                        </td>
                                        <td>{{$detail->discount}}</td>
                                        <td>{{$detail->tax_amount}}</td>
                                        <td>{{$detail->total_price}}</td>
                                    @else
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endif    
                                @endfor
                                
                                <td>{{$order->company_name}}</td>
                                <td>{{$order->seller_email}}</td>
                                <td>{{$order->sm}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
    </div>
</div>
<script>
    function fnCsvReport() {
        var tab = document.getElementById('dataTable');
        var rows = tab.rows;
        var csvRows = [];
        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].cells;
            var rowData = [];
            for (var j = 0; j < cells.length; j++) {
                var cellText = cells[j].innerText || cells[j].textContent || '';
                cellText = cellText.trim();
                cellText = cellText.replace(/"/g, '""');
                if (cellText.indexOf(',') > -1 || cellText.indexOf('\n') > -1 || cellText.indexOf('"') > -1) {
                    cellText = '"' + cellText + '"';
                }
                rowData.push(cellText);
            }
            csvRows.push(rowData.join(','));
        }
        var csvContent = '\uFEFF' + csvRows.join('\n');
        var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        var url = window.URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'order_report.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        return true;
    }
$('.expand').on('click',function() {
    if ($(this).text() == 'Filters>>') {
        $(this).text('Filters<<');
    } else {
        $(this).text('Filters>>');
    }
    $('.show_more').slideToggle('fast');
});
</script>
<script>
document.getElementById('downloadBtn').addEventListener('click', function () {
    var table = document.getElementById('dataTable');
    var data = [];

    for (var i = 0; i < table.rows.length; i++) {
        var row = table.rows[i];
        var rowData = [];

        for (var j = 0; j < row.cells.length; j++) {
            rowData.push(row.cells[j].innerText.trim());
        }

        data.push(rowData);
    }
    document.getElementById("filenameid").value = (JSON.stringify(data));
    $('#myForm').submit();
    
});
</script>
@endsection