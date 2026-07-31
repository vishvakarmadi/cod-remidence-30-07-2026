@extends('admin.admin_layouts')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">Coupons</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 mt-2 font-weight-bold text-primary">View Coupons</h6>
            <div class="d-inline">
                <a href="{{ route('admin.coupon.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Discount</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($coupon as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->coupon_code }}</td>
                            <td>{{ $row->coupon_type }}</td>
                            <td>{{ $row->coupon_discount }}</td>
                            <td>{{ $row->coupon_start_date }}</td>
                            <td>{{ $row->coupon_end_date }}</td>
                            <td>{{ $row->coupon_status }}</td>
                            <td>
                                <a href="{{ URL::to('admin/coupon/edit/'.$row->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a> <span style="margin: 0 10px;">|</span>
                                <a href="{{ URL::to('admin/coupon/delete/'.$row->id) }}" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure?');"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
