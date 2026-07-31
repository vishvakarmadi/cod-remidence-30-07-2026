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
/* Style for table rows and data cells */
.table.table-striped.table-hover tbody tr td {
  padding: 10px 15px; /* Adjust padding for better spacing */
  font-size: 14px; /* Adjust font size for readability */
  color: #333; /* Adjust the font color as needed */
  vertical-align: middle; /* Centers content vertically */
}

/* Style for links within the data cells */
.table.table-striped.table-hover tbody tr td a {
  color: #3057d5; /* Primary color for links */
  text-decoration: none; /* Removes underline from links */
  transition: color 0.3s ease; /* Smooth transition for hover effect */
}

.table.table-striped.table-hover tbody tr td a:hover {
  color: #0a3d62; /* Darker shade for hover effect */
  text-decoration: underline; /* Adds underline on hover for better indication */
}
</style>


            <!-- Main body part  -->
            <div class="container-fluid">
                    <!-- Page header section  -->
                    <div class="block-header">
                        <div class="row">
                            <div class="col-lg-5 col-md-8 col-sm-12">
                            <h2>Order List</h2>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                <th>Order Status</th>
                                                <th>Total Orders</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order as $o)
                                                <tr>
                                                    <td>{!!$o->status !!}</td>
                                                    <td>
                                                        {{$o->total }}
                                                    </td>
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
