@extends('admin.admin_layouts')
@section('admin_content')
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 mt-2 font-weight-bold text-primary">Report</h6>
            <!-- <button id="btnExport" class="btn btn-primary" onclick="fnExcelReport();" style="height:30px;font-size:12px"><i class="fa fa-file-excel-o" style=""></i> EXPORT </button> -->
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered sorttableexcel" id="dataTable" name="update" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Order_ID*</th>
                        <th>First_Name*</th>
                        <th>Last_Name*</th>
                        <th>Email_id</th>
                        <th>Company_Name</th>
                        <th>Phone_No*</th>
                        <th>Address_1*</th>
                        <th>Address_2</th>
                        <th>Country*</th>
                        <th>City*</th>
                        <th>State*</th>
                        <th>Pincode*</th>
                        <th>latitude</th>
                        <th>longitude</th>
                        <th>Gstin</th>
                        <th>Billing address is same as Shipping address(Y/N)*</th>
                        <th>Billing_First_Name</th>
                        <th>Billing_Last_Name</th>
                        <th>Billing_Company_Name</th>
                        <th>Billing_Address_1</th>
                        <th>Billing_Address_2</th>
                        <th>Billing_Country</th>
                        <th>Billing_Pincode</th>
                        <th>Billing_City</th>
                        <th>Billing_State</th>
                        <th>Billing_latitude</th>
                        <th>Billing_longitude</th>
                        <th>Billing_Gstin</th>
                        <th>e_bill_no</th>
                        <th>channel(hyloship/amazon/shopify)</th>
                        <th>weight_(gms)*</th>
                        <th>length_(cms)*</th>
                        <th>breadth_(cms)*</th>
                        <th>height_(cms)*</th>
                        <th>discount</th>
                        <th>shipping_cost</th>
                        <th>order_total_amount*</th>
                        <th>note</th>
                        <th>payment_mode(cod/pre-paid)*</th>
                        <th>Product_Name*</th>
                        <th>SKU*</th>
                        <th>Unit_Price*</th>
                        <th>discount_type(f->flat/p->percentage)</th>
                        <th>product_discount</th>
                        <th>qty*</th>
                        <th>tax_percent</th>
                        <th>tax_amount</th>
                        <th>total_Amount</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{$order->id}}</td>
                            <td>{{$order->vendor_order_id}}</td>
                            <td>{{$order->ship_fname}}</td>
                            <td>{{$order->ship_lname}}</td>
                            <td>{{$order->ship_email}}</td>
                            <td>{{$order->ship_company}}</td>
                            <td>{{$order->ship_phone}}</td>
                             <td>{{$order->ship_address}}</td>
                            <td>{{$order->ship_address_2}}</td>
                            <td>India</td>
                            <td>{{$order->ship_city}}</td>
                            <td>{{$order->ship_state}}</td>
                            <td>{{$order->ship_pincode}}</td>
                            <td>{{$order->ship_latitude}}</td>
                            <td>{{$order->ship_longitude}}</td>
                            <td>{{$order->ship_gstin}}</td>

                            @if ($order->same_add =='1')
                            <td>Y</td>
                            @else
                            <td>N</td>
                            @endif

                            <td>{{ $order->bill_fname}}</td>
                            <td>{{ $order->bill_lname}}</td>
                            <td>{{ $order->bill_company}}</td>
                            <td>{{ $order->bill_address}}</td>
                            <td>{{ $order->bill_address_2}}</td>
                            <td>India</td>
                            <td>{{ $order->bill_pincode}}</td>
                            <td>{{ $order->bill_city}}</td>
                            <td>{{ $order->bill_state}}</td>
                            <td>{{ $order->bill_latitude}}</td>
                            <td>{{ $order->bill_longitude}}</td>
                            <td>{{ $order->bill_gstin}}</td>
                            <td>{{ $order->e_bill_no}}</td>
                            <td>{{ $order->channel}}</td>
                            <td>{{ $order->weight}}</td>
                            <td>{{ $order->length}}</td>
                            <td>{{ $order->breadth}}</td>
                            <td>{{ $order->height}}</td>
                            <td>{{ $order->discount}}</td>
                            <td>{{ $order->shipping_cost}}</td>
                            <td>{{ $order->total}}</td>
                            <td>{{ $order->note}}</td>

                           
                            @if (strip_tags($order['payment_mode']) =='C.O.D')
                            <td>cod</td>
                            @elseif (strip_tags($order['payment_mode']) =='Pre-Paid')
                            <td>pre-paid</td>
                            @endif
                            <?php 
                            $name = '';
                            $code = '';
                            $price = '';
                            $discount_type = '';
                            $discount = '';
                            $qty = '';
                            $tax_percent = '';
                            $tax_amount = '';
                            $total_price = '';
                            foreach($order->detail as $detail){
                                $name .= $detail->name.';';
                                $code .= $detail->code.';';
                                $price .= $detail->price.';';
                                $discount_type .= $detail->discount_type.';';
                                $discount .= $detail->discount.';';
                                $qty .= $detail->qty.';';
                                $tax_percent .= $detail->tax_percent.';';
                                $tax_amount .= $detail->tax_amount.';';
                                $total_price .= $detail->total_price.';';
                            }
                            ?>
                            <td>{{rtrim($name,';')}}</td>
                            <td>{{rtrim($code,';')}}</td>
                            <td>{{rtrim($price,';')}}</td>
                            <td>{{rtrim($discount_type,';')}}</td>
                            <td>{{rtrim($discount,';')}}</td>
                            <td>{{rtrim($qty,';')}}</td>
                            <td>{{rtrim($tax_percent,';')}}</td>
                            <td>{{rtrim($tax_amount,';')}}</td>
                            <td>{{rtrim($total_price,';')}}</td>
                            
                           
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</div>

@endsection