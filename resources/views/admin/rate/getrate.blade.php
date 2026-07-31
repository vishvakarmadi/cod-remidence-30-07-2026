@extends('admin.admin_layouts')
@section('admin_content')
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 mt-2 font-weight-bold text-primary">Ratecard</h6>
            <!-- <button id="btnExport" class="btn btn-primary" onclick="fnExcelReport();" style="height:30px;font-size:12px"><i class="fa fa-file-excel-o" style=""></i> EXPORT </button> -->
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered sorttableexcel" id="dataTable" name="update" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>courier_id</th>
                        <th>additional</th>
                        <th>transport</th>
                        <th>weight</th>
                        <th>within_city</th>
                        <th>within_state</th>
                        <th>metro_to_metro</th>
                        <th>rest_of_india</th>
                        <th>north_east</th>
                        <th>cod_charges</th>
                        <th>cod</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($rate as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $couriers[$row->courier_id]['name'] }}</td>
                                <td>{{$row->additional}}</td>
                                <td>
                                    @if ($row->courier_id =='1')
                                    @else
                                        {{$row->transport}}
                                    @endif
                                </td>
                                <td>{{$row->weight}}</td>
                                <td>{{$row->within_city}}</td>
                                <td>{{$row->within_state}}</td>
                                <td>{{$row->metro_to_metro}}</td>
                                <td>{{$row->rest_of_india}}</td>
                                <td>{{$row->north_east}}</td>
                                <td>{{$row->cod_charges}}</td>
                                <td>{{$row->cod}}</td>
                            </tr>
                           
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</div>

@endsection