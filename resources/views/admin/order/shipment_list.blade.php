@extends('admin.admin_layouts')
@section('admin_content')
@php
    $session = Auth::guard('admin')->user();
@endphp

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/public/admin/semantic.min.js"></script>
<link rel="stylesheet" href="/public/admin/semantic.min.css">
<style>
    label {
    display: inline-block;
    margin-bottom: 0px;
}

.form-control {
    display: block;
    width: 82%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
</style>


            <!-- Main body part  -->
            <div class="container-fluid">
                    <!-- Page header section  -->
                    <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <h1>Hi, Welcomeback!</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Shipment List<small>
                                </div>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Order Number</th>
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
                                                <td class="text-center"> <a
                                                        href="{{ route('admin.order.detail',$row->id) }}">{{ $row->order_id }}</a>
                                                </td>
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
                        </div>
                    </div>
                </div>
@endsection
