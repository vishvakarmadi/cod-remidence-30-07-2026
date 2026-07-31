@extends('admin.admin_layouts')
@section('admin_content')


<!-- Main body part  -->
<div class="container-fluid">
    <!-- Page header section  -->
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12 col-sm-12">
               
            </div>
            <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                <div class="row clearfix">
                    <div class="col-xl-5 col-md-5 col-sm-12"></div>
                    <div class="col-xl-7 col-md-7 col-sm-12 text-md-right hidden-xs">
                        <a href="{{ route('admin.product.create') }}" class="btn btn-success">View Rejected Report</a>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">

                <div class="card">
                    <div class="header">
                        <h2>Report - Rejected Order List<small>
                                <ul class="header-dropdown dropdown">

                                    <li><a href="javascript:void(0);" class="full-screen"><i
                                                class="fa fa-expand"></i></a></li>
                                </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Order ID</th>
                                        <th>Product (Item)</th>
                                        <th>Customer</th>
                                        <th>Quantity</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($rej_report as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->order_id }}</td>
                                        <td>{{ $row->product_id }}</td>
                                        <td>{{ $row->customer_id }}</td>
                                        <td>{{ $row->qty }}</td>
                                        <td><b>{{ $row->status }}</b></td>
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
</div>
@endsection