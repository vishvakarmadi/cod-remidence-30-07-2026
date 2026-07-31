@extends('admin.admin_layouts')
@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/public/admin/semantic.min.js"></script>
<link rel="stylesheet" href="/public/admin/semantic.min.css">



<!-- Main body part  -->
<div class="container-fluid">
    <!-- Page header section  -->
    
    <div class="row clearfix">
        
        <div class="col-xl-12">


        <form id="myForm" action="" method="POST">
            @csrf
            <div class="card mt-30 new_orders">
                <div class="header">
                    <h2>RTO Orders<small>
                </div>
                <div class="body row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sorttable table-striped table-hover" id="sorttable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        
                                        <th>SL</th>
                                        <th>Seller</th>
                                        <!-- <th>Channel</th> -->
                                        <th>Order Number</th>
                                        <th>AWB</th>
                                        <th>Courier</th>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Payment</th>
                                        <th>Dim. & Wt. <a title="Default Settings" data-toggle="popover"
                                                data-placement="bottom" data-trigger="hover"
                                                data-content="L: 10cm , B: 10cm , H: 10cm , Weight : 50gm "><span
                                                    class="fa fa-info-circle"></span></a>
                                        </th>
                                        <th>Status</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order as $row)
                                    <tr>
                                    

                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->user_id }}</td>
                                        <!-- <td><img src="{{ asset('public/favicon.svg') }}" style="width:70px"
                                                alt="Channel Logo"></td> -->
                                        <td class="text-center"> <a
                                                href="{{ route('admin.order.detail',$row->id) }}">{{ $row->order_id }}</a>
                                        </td>
                                        <td>{{$row->tracking_info }}</td>
                                        <td class="text-center">{{@$couriers[$row->ship_courier_id]['name'] }}</td>
                                        <td><span class="fa fa-calendar"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($row->created_at)->format('d M, Y') }}<br>
                                            <span class="fa fa-clock-o"></span>&nbsp;
                                            {{ \Carbon\Carbon::parse($row->created_at)->format('H:i') }}
                                        </td>
                                        <td>{{ $row->detail[0]->name }}</td>
                                        <td> {{ $row->total }} .00<br> {!! $row->payment_mode !!}</td>
                                        <td><b>Dim :</b> {{ $row->length }}x{{ $row->breadth }}x{{ $row->height }}
                                            cm<br><b>Wt :</b> {{ $row->weight }} gm</td>
                                        <td>{!! $row->status !!}</td>
                                        
                                    </tr>
                             
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{ $order->links() }}
            </div>

         
        </form>
    </div>
    </div>
</div>


@endsection