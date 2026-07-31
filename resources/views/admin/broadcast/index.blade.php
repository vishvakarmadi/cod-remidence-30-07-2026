@extends('admin.admin_layouts')
@section('admin_content')

    <div class="row">
        <div class="col-lg-12">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-9">
                    <h2>Broadcast List</h2>
                </div>
                <div class="col-lg-3">
                        <a href="{{ route('admin.broadcast.new') }}" class="btn btn-primary"
                                                title="Add">
                            <span class="sr-only">Add</span> 
                            <i class="las la-plus"></i>@lang('Create New Broadcast')
                        </a>
                        
                    &nbsp;
                </div>
                </div>
            </div><br>
            <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Message</th>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($broadcasts as $row)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td style="white-space: normal;">{{ $row->message }}</td>
                                                        <td>{{ $row->from_date }}</td>
                                                        <td>{{ $row->to_date }}</td>
                                                        <td>
                                                            <a href="{{ URL::to('broadcast/edit/' . $row->id) }}" class="btn btn-primary"><i
                                                                class="fa fa-pencil"></i></a>
                                                            <a href="{{ URL::to('broadcast/delete/' . $row->id) }}"  onClick="return confirm('Are you sure You want to delete this?');" class="btn btn-secondary"><i
                                                                class="fa fa-trash"></i></a>
                                                        </td>
                                                        
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td class="text-muted text-center" colspan="100%">No Broadcast Data Found</td>
                                                    </tr>
                                                @endforelse
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
