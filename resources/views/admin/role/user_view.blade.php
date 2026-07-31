@extends('admin.admin_layouts')
@section('admin_content')
    @php
        $session = Auth::guard('admin')->user();
        use App\Models\Admin\Admin;
    @endphp
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <!-- Main body part  -->
    <style>
        tr.dublicate_yes {
    background-color: beige !important;
}
    </style>
    <div class="container-fluid">
        <!-- Page header section  -->
        
        <div class="block-header">
            <div class="row">
                <div class="col-lg-4">
                    <h2>Query List</h2>
                </div>
                
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card">
                        
                        <div class="body">
                            <div class="table-responsive"  style="min-height:220px">
                                <table class="table table-striped table-hover dataTable js-exportable sorttableexcel">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Name</th>
                                            <th>Email Address</th>
                                            <th>Phone</th>
                                            <th>Ip Address</th>
                                            <th>Visited at</th>
                                            <!--<th>Query</th>-->
                                            
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($user_query as $row)
                                        
                                       
                                            <tr >
                                                <td>{{ $row->id }}</td>
                                                <td>{{ $row->name }}</td>
                                                <td>{{ $row->email }}</td>
                                                <td>{{ $row->phone }}</td>
                                                <td>{{$row->ip_address}}</td>
                                                <td>{{ $row->created_at}}</td>
                                                <!--<td>{{ $row->query }}</td>-->
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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

@endsection
