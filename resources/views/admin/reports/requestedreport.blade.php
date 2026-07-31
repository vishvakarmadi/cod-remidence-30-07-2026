@extends('admin.admin_layouts')
@section('admin_content')


<div class="container-fluid">
<div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-8 col-sm-12">
            <h2>MIS Requested Reports</h2>
            </div>
            
        </div>
    </div>
    <div class="row clearfix">
        
        <div class="card shadow mb-4">
            
           
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered sorttableexceldate table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Seller</th>
                            <th>Requested On</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($requestedreport as $order)
                            <tr>
                                <td>{{$order->user_id}}</td>
                                <td>{{$order->created_at}}</td>
                                <td>
                                    @if($order->file_name =='')
                                    {{$order->file_name}}
                                        Generating...
                                    @else
                                    <div class="btn-group">
                                    <a href="{{ asset('public/misreport/'.$order->file_name) }}" download="" class="btn btn-primary"><i
                                    class="fa fa-file-pdf-o"></i></a>
                                    <a href="{{ URL::to('admin/report/dtrqrp/' . $order->id) }}"  onClick="return confirm('Are you sure You want to delete this?');" class="btn btn-secondary"><i
                                    class="fa fa-trash"></i></a>
                                    </div>
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

@endsection