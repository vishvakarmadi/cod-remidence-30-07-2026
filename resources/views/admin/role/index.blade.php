@extends('admin.admin_layouts')
@section('admin_content')
@php
    $session = Auth::guard('admin')->user();
@endphp
<div class="container-fluid">
                    <!-- Page header section  -->
                    <div class="block-header">
                        <div class="row">
                            <div class="col-lg-5 col-md-8 col-sm-12">
                            <h2>View Roles</h2>
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
                                                    <th>SL</th>
                                                    <th>Role Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                @foreach($roles as $row)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $row->name }}</td>
                                                    <td>
                                                        @if($row->id != 1)
                                                            @if($session->hasPermissionTo('show role'))
                                                            <a class="btn btn-warning btn-sm view" style="cursor: pointer;" href="{{ URL::to('admin/role/view/'.$row->id) }}">
                                                                <i class="tio-edit"></i>View
                                                            </a>
                                                            @endif
                                                            @if($session->hasPermissionTo('edit role'))
                                                            <a class="btn btn-primary btn-sm edit" style="cursor: pointer;" href="{{ URL::to('admin/role/edit/'.$row->id) }}">
                                                                <i class="tio-edit"></i>Edit
                                                            </a>
                                                            @endif
                                                            @if($session->hasPermissionTo('delete role'))
                                                            <a href="{{ URL::to('admin/role/delete/'.$row->id) }}" class="btn btn-danger btn-sm delete" style="cursor: pointer;" href="#" onClick="return confirm('Are you sure?');">
                                                                <i class="tio-add-to-trash"></i>delete
                                                            </a>
                                                            @endif
                                                        @endif
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
