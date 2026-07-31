@extends('admin.admin_layouts')
@section('admin_content')

    <div class="block-header">
        <div class="row">
             <div class="col-lg-4">
                <h2>Order Detail</h2>
            </div>
             @if ($order->reverse_order =='1') 
                <span style="color:red">
                    This is a Reverse order, shipping address will be treated as pickup address
                </span>
            @endif
        </div>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-9 pull-left">
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Products</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Discount</th>
                                <th scope="col">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->detail as $row)
                                <tr>
                                    <td style="white-space: unset;">{{ $row->name }}<br> <b>SKU Code:</b>&nbsp; {{ $row->code }}</td>
                                    <td>Rs.{{ $row->price }}</td>
                                    <td>{{ $row->qty }}</td>
                                    <td>{{ $row->discount }}</td>
                                    <td>Rs.{{ $row->total_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="text-right">
                            <tr>
                                <td colspan="4"></td>
                                <td>
                                    <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Totals</h5>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">Subtotal: </td>
                                <td>Rs.{{ $order->total }}</td>
                            </tr>
                            <tr>
                                <td colspan="4">Shipping cost: </td>
                                <td>Rs.{{ $order->shipping_cost }}</td>
                            </tr>
                            <tr>
                                <td colspan="4">Total: </td>
                                <td>Rs.{{ $order->custom_total }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-3 pull-right">
            <div class="card">
                <div class="card-body">
                    <h6 class="font-weight-bold text-primary"><i class="fa fa-user-circle"></i> CUSTOMER DETAILS</h6><br>
                    <h6 class="float-left title"><b>Name : </b></h6>
                    <h6> {{ $order->ship_fname }} {{ $order->ship_lname }}</h6><br>
                    <h6 class="float-left title"><b>Email : </b></h6>
                    <h6> {{ $order->ship_email }}</h6><br>
                    <h6 class="float-left title"><b>Latitude : </b></h6>
                    <h6> {{ $order->ship_latitude }}</h6><br>
                    <h6 class="float-left title"><b>Longitude : </b></h6>
                    <h6> {{ $order->ship_longitude }}</h6><br>
                    <h6 class="float-left title"><b>Phone : </b></h6>
                    <h6> {{ $order->ship_phone }}</h6><br>
                    <h6 class="float-left title"><b>GST : </b></h6>
                    <h6> {{ $order->ship_gstin }}</h6>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="font-weight-bold text-primary"><i class="fa fa-map-marker"></i> SHIPPING ADDRESS</h6><br>
                    <h6 class="float-left title"><b>Name : </b></h6>
                    <h6> {{ $order->ship_fname }} {{ $order->ship_lname }}</h6>
                    <h6 class="float-left title"><b>Address : </b></h6>
                    <h6> {{ $order->ship_address }}</h6>
                    <h6 class="float-left title"><b>City : </b></h6>
                    <h6> {{ $order->ship_city }}</h6>
                    <h6 class="float-left title"><b>State : </b></h6>
                    <h6> {{ $order->ship_state }}</h6>
                    <h6 class="float-left title"><b>Country : </b></h6>
                    <h6> {{ $order->shipCountry->name }}</h6>
                    <h6 class="float-left title"><b>Pincode : </b></h6>
                    <h6> {{ $order->ship_pincode }}</h6>
                    <h6 class="float-left title"><b>Phone : </b></h6>
                    <h6> {{ $order->ship_phone }}</h6>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6 class="font-weight-bold text-primary"><i class="fa fa-bold"></i> Billing ADDRESS</h6><br>
                    @if ($order->same_add == 0)
                        <h6 class="float-left title"><b>Name : </b></h6>
                        <h6> {{ $order->bill_fname }} {{ $order->bill_lname }}</h6>
                        <h6 class="float-left title"><b>Address : </b></h6>
                        <h6> {{ $order->bill_address }}</h6>
                        <h6 class="float-left title"><b>City : </b></h6>
                        <h6> {{ $order->bill_state }}</h6>
                        <h6 class="float-left title"><b>State : </b></h6>
                        <h6> {{ $order->bill_state }}</h6>
                        <h6 class="float-left title"><b>Country : </b></h6>
                        <h6> {{ $order->billCountry->name }}</h6>
                        <h6 class="float-left title"><b>Pincode : </b></h6>
                        <h6> {{ $order->bill_pincode }}</h6>
                        <h6 class="float-left title"><b>Phone : </b></h6>
                        <h6> {{ $order->bill_phone }}</h6>
                    @else
                        <h6 class="float-center">Billing Address Same as Shipping</h6>
                    @endif
                </div>
            </div>
            <!-- <div class="card">
                <div class="card-body">
                    <h6 class="font-weight-bold text-primary"><i class="fa fa-ravelry"></i> Other Information's</h6><br>
                    <h6 class="float-left title"><b>Order Weight : </b></h6>
                    <h6> {{ $order->weight }} Grams</h6>
                    <h6 class="float-left title"><b>Box Length : </b></h6>
                    <h6> {{ $order->length }} cms</h6>
                    <h6 class="float-left title"><b>Box Breadth : </b></h6>
                    <h6> {{ $order->breadth }} cms</h6>
                    <h6 class="float-left title"><b>Box Height : </b></h6>
                    <h6> {{ $order->height }} cms</h6>
                    @if ($order->note != null)
                        <h6 class="float-left title"><b>Notes : </b></h6>
                        <h6>{{ $order->note }}</h6>
                    @endif
                    <h6 class="float-left title"><b>Status : </b></h6>
                    <h6>{!! $order->status !!}</h6>
                    <h6 class="float-left title"><b>Payment : </b></h6>
                    <h6>{!! $order->payment_mode !!}</h6>
                </div>
            </div> -->
            <form action="{{ route('admin.order.tags',$order->id) }}" method="POST">
                @csrf
            <div class="card">
                <div class="card-body">
                    <h6 class="font-weight-bold text-primary"><i class="fa fa-tags"></i> Tag's</h6><br>
                    <input type="text" class="inputTag" name="tags[]" value="{{ @$tags }}" data-role="tagsinput" required><br><br>
                    <button type="submit" class="btn btn-success">Save tag</button><br>
                </div>
            </div>
            </form>
        </div>
        <div class="col-sm-9 pull-left">
            <div class="card">
                <div class="card-header">
                <!-- <h6 class="font-weight-bold text-primary"></h6><br> -->
                    <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading"><i class="fa fa-ravelry"></i> Other Information</h5>
                </div>
                <!-- <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="form-control-label">Select Carrier:</label><span class="required">
                                *</span>
                            <select class="form-control" name="carrier" required>
                                <option value="">Select Carrier</option>
                                <option value="1">Shipway</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-control-label">Select Pickup Address:</label><span class="required">
                                *</span>
                            <select class="form-control" name="pickup_address" required>
                                <option value="">Select Pickup Address</option>
                                @foreach ($warehouse as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group clearfix" style="align-items: unset;display: flex;">
                            <label class="element-left" style="padding: 34px 0px 0px 27px;">
                                <input type="checkbox" name="same_add" class="same_add" id="remember" value="0">
                                <span for="same_add">Return Address same as Pickup</span>
                            </label>								
                        </div>
                        <div class="form-group col-md-4" id="bill_address"></div>

                    </div><br>
                    <button type="button" class="btn btn-success">Assign Tracking</button><br><br>
                </div> -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="float-left title"><b>Order id : </b></h6>
                            <h6> {{ $order->order_id }} </h6>
                            <h6 class="float-left title"><b>Courier Name : </b></h6>
                            <h6> {{ $c_name }}</h6>
                            <h6 class="float-left title"><b>AWB : </b></h6>
                            <h6> {{ $order->tracking_info }} </h6>
                            <h6 class="float-left title"><b>Payment : </b></h6>
                            <h6>{!! $order->payment_mode !!}</h6>
                            <h6 class="float-left title"><b>Status : </b></h6>
                            <h6>{!! $order->status !!}</h6>
                            <h6 class="float-left title"><b>Zone : </b></h6>
                            <h6>{{ $order->zone }}</h6>
                        </div>
                        <div class="col-md-4">
                            <h6 class="float-left title"><b>Order Weight : </b></h6>
                            <h6> {{ $order->weight }} Grams</h6>
                            <h6 class="float-left title"><b>Box Length : </b></h6>
                            <h6> {{ $order->length }} cms</h6>
                            <h6 class="float-left title"><b>Box Breadth : </b></h6>
                            <h6> {{ $order->breadth }} cms</h6>
                            <h6 class="float-left title"><b>Box Height : </b></h6>
                            <h6> {{ $order->height }} cms</h6>
                            <h6 class="float-left title"><b>Vol Weight : </b></h6>
                             <h6> {{ ($order->height*$order->breadth*$order->length)/5 }} Grams</h6>    
                            <h6 class="float-left title"><b>Courier type used : </b></h6>
                            <h6> {{ ($order->shipping_courier_weight_used) }} </h6>
                            <h6 class="float-left title"><b>Rate used : </b></h6>
                            <h6> {{ $order->rate }}({{$order->rateadd}} -additional) </h6>
                            @if ($order->note != null)
                                <h6 class="float-left title"><b>Notes : </b></h6>
                                <h6>{{ $order->note }}</h6>
                            @endif
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9 pull-left">
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading"><i class="fa fa-home"></i> WareHouse Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if ($order->warehouse_id)
                            @foreach ($warehouse as $row)
                                @if($row->id == $order->warehouse_id)
                                    <div class="col-md-6">
                                        <h6 class="title">Pickup Warehouse</h6></br>
                                        <h6 class="float-left title"><b>Name : </b></h6>
                                        <h6> {{ $row->name }} </h6>
                                        <h6 class="float-left title"><b>ADDRESS LINE1 : </b></h6>
                                        <h6> {{ $row->address }} </h6>
                                        <h6 class="float-left title"><b>ADDRESS LINE2 : </b></h6>
                                        <h6> {{ $row->address_2 }} </h6>
                                        <h6 class="float-left title"><b>PINCODE : </b></h6>
                                        <h6> {{ $row->pincode }} </h6>
                                        <h6 class="float-left title"><b>Contact Name : </b></h6>
                                        <h6> {{ $row->contact_name }} </h6>
                                        <h6 class="float-left title"><b>Phone : </b></h6>
                                        <h6> {{ $row->phone }} </h6>
                                    </div>
                                @endif
                                @if(isset($order->return_warehouse_id) && $row->id == $order->return_warehouse_id)
                                    <div class="col-md-6">
                                        <h6 class="title">Return Warehouse</h6></br>
                                        <h6 class="float-left title"><b>Name : </b></h6>
                                        <h6> {{ $row->name }} </h6>
                                        <h6 class="float-left title"><b>ADDRESS LINE1 : </b></h6>
                                        <h6> {{ $row->address }} </h6>
                                        <h6 class="float-left title"><b>ADDRESS LINE2 : </b></h6>
                                        <h6> {{ $row->address_2 }} </h6>
                                        <h6 class="float-left title"><b>PINCODE : </b></h6>
                                        <h6> {{ $row->pincode }} </h6>
                                        <h6 class="float-left title"><b>Contact Name : </b></h6>
                                        <h6> {{ $row->contact_name }} </h6>
                                        <h6 class="float-left title"><b>Phone : </b></h6>
                                        <h6> {{ $row->phone }} </h6>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>       
        </div>
    </div>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.same_add').on('click', function() {
                var value = $('input[name="same_add"]:checked').val();
                var html = `<div id="bill_remove">
                                <label class="form-control-label">Select Return Address:</label><span
                                    class="required">
                                    *</span>
                                <select class="form-control" name="return_address" required>
                                    <option value="">Select Return Address</option>
                                    @foreach ($warehouse as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>`;
                if (value == 0) {
                    $('#bill_address').append(html);
                }
                if (value == undefined) {
                    $('#bill_remove').remove();
                }
            })

        });
    </script>



@endsection
