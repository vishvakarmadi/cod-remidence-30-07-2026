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
                            <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                                <div class="row clearfix">
                                <div class="col-xl-5 col-md-5 col-sm-12"></div>            
                            <div class="col-xl-7 col-md-9 col-sm-12 text-md-right hidden-xs">

                        </div>
                    </div>
                                </div>
                            </div>
                    </div>

                    


                    <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-lg-12">
                               
                            <div class="card">
                                <div class="header">
                                    <h2>Order List<small>
                                    <ul class="header-dropdown dropdown">
                                        <li><a href="{{ route('admin.filter.status','Pending') }}" class="btn bg-dark btn-sm btn-block" style="color:#fff;">Pending</a></li>
                                        <li><a href="{{ route('admin.filter.status','Processing') }}" class="btn btn-warning btn-sm btn-block" style="color:#fff;">Processing</a></li>
                                        <li><a href="{{ route('admin.filter.status','Completed') }}" class="btn btn-success btn-sm btn-block" style="color:#fff;">Completed</a></li>
                                        <li><a href="{{ route('admin.filter.status','Rejected') }}" class="btn btn-danger btn-sm btn-block" style="color:#fff;">Rejected</a></li>
                                    </ul>
                                </div>

                                
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                @if($session->hasPermissionTo('export order'))
                                                <th>
                                                    <label class="fancy-checkbox1">
                                                        <input class="select-all" type="checkbox" name="checkbox">
                                                        &nbsp;<label></label>
                                                    </label>
                                                </th>
                                                @endif
                                                <th>SL</th>
                                                <th>Order Number</th>
                                                @if($session->hasPermissionTo('show customer'))
                                                <th>Customer Initial</th>
                                                @endif
                                                <th>Product Name</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                            @foreach($order as $row)
                                                <tr>
                                                
                                                    @if($session->hasPermissionTo('export order'))
                                                    <td>
                                                        <label class="fancy-checkbox{{ $row->id }}">
                                                            <input class="checkbox-tick" type="checkbox" name="id[{{ $row->id }}]" value="{{ $row->id }}">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    @endif
                                                   

                                                    <td> <a href="{{ URL::to('admin/order/detail/'.$row->id) }}" >{{ $loop->iteration }}</a></td>
                                                    <td> <a href="{{ URL::to('admin/order/detail/'.$row->id) }}">{{ $row->order_id }}</a></td>
                                                    
                                                    
                                                    @if($session->hasPermissionTo('show customer'))
                                                    <td>
                                                    <a href="{{ URL::to('admin/order/detail/'.$row->id) }}">{{ $row ->initial }}</a><br>
                                                    </td>
                                                    @endif
                                                    <td><a href="{{ URL::to('admin/order/detail/'.$row->id) }}">{{ $row->product->name }}</a></td>
													<td>
                                                        @if($row->status == 'Completed')
                                                        <span class="badge text-white bg-success">{{ $row->status }}</span>
                                                        @elseif($row->status == 'Rejected')
                                                        <span class="badge text-white bg-danger">{{ $row->status }}</span>
                                                        @elseif($row->status == 'Pending')
                                                        <span class="badge text-white bg-dark">{{ $row->status }}</span>
                                                        @else
                                                        <span class="badge text-white bg-warning">{{ $row->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($session->hasPermissionTo('split order') && ($row->status == 'Processing'))
                                                        <a href="{{ route('admin.order.split',$row->id) }}" class="btn btn-success" title="Split"><span class="sr-only">Split</span> <i class="fa fa-exchange"></i></a>
                                                        @endif
                                                        <!--@if($session->hasPermissionTo('show order')) 
                                                        <a href="{{ URL::to('admin/order/detail/'.$row->id) }}" class="btn btn-info" title="Detail"><span class="sr-only">Detail</span> <i class="fa fa-eye"></i></a>
                                                        @endif -->
                                                        @if($session->hasPermissionTo('edit order') && ($row->status == 'Pending'))
                                                        <a href="{{ URL::to('admin/order/edit/'.$row->id) }}" class="btn btn-primary" title="Edit"><span class="sr-only">Edit</span> <i class="fa fa-pencil"></i></a>
                                                        @endif
                                                        <!-- <a href="{{ URL::to('admin/order/invoice/'.$row->id) }}" class="btn btn-success" title="Invoice"><span class="sr-only">Invoice</span> <i class="fa fa-save"></i></a> -->
                                                        <!-- @if($session->hasPermissionTo('delete order'))
                                                        <a href="{{ URL::to('admin/order/delete/'.$row->id) }}" class="btn btn-danger" title="Delete" onClick="return confirm('Are you sure?');"><span class="sr-only">Delete</span> <i class="fa fa-trash-o"></i></a>
                                                        @endif -->
                                                    </td>
                                                    
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                    {{ $order->links() }}
                            </div>

                            @if($session->hasPermissionTo('export order'))
                            <div class="flex-grow-1 align-self-end">
                                <div class="form-group col-2">
                                    <label>@lang('Action')</label>
                                    <select class="form-control" name="status" id="myselect">
                                        <option value="" selected disabled>@lang('Select One')</option>
                                        @if($session->hasPermissionTo('delete order'))
                                        <option value="delete">Delete</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @endif


                        </div>
                    </div>
                    </form>
                </div>
            </div>   


<script>
    function handleChange(selectElement) {
        var id = selectElement.getAttribute('data-id');
        var value = selectElement.getAttribute('data-value');

        if (confirm("Are you sure you want to process this Order?")) {
            if (value !== 'process_fully') {
            window.location.href = '/admin/order/process/partially/' + id;
        } else {
            $.ajax({
                type: 'GET',
                url: '/admin/order/process',
                data: {
                    id: id,
                    change: value
                },
                success: function(data) {
                    location.reload();
                }
            });
        }
        }
    }

    (function($){
    "use strict";

    $('select[name=status]').change(function(){
        if ($('input[name^="id"]:checked').length > 0) {
            $('#myForm').submit();
        } else {
            toastr.error('Select atleast one Order');
            $('select[name=status]').val('');
        }
    });


    })(jQuery);
</script>


@endsection
