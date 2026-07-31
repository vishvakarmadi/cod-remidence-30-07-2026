@extends('admin.admin_layouts')
@section('admin_content')


    <div class="row clearfix">
        
        <div class="col-xl-12">
            {{--@if (!in_array($user->role_id,array('1','2'))) --}}

    
        <div class="col-xl-12">
        <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
            @csrf
            <div class="card  new_orders">
                <div class="header d-flex justify-content-between" style="padding-bottom: 0;">
                    <h2>Weekly Remittance List</h2>
                   <input type="hidden" name ='path' value="all">
                </div>
                <div class="">
                        <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sorttablenew" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Week No.</th>
                                        <th>Start-date</th>
                                        <th>End-date</th>
                                        <th>Total Amount Collected</th>
                                        <th>Convenience Charge</th>
                                        <!-- <th>RTO Charge</th>
                                        <th>Extra Weight Charge</th>
                                        <th>Extra Weight RTO Charge</th> -->
                                        
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach($row as $key=>$or)
                                     @if($or['total'] !=0)
                                     <tr>
                                         <td>
                                             <a href="{{ route('codrem') }}?user_id%5B%5D={{ $user->id }}&start_date={{ $or['start_date'] }}&end_date={{ $or['end_date'] }}" class=""
                                                title="View">{{$key}}</a>
                                         </td>
                                         <td>{{ \Carbon\Carbon::parse($or['start_date'])->format('d/m/Y') }}</td>
                                         <td>{{ \Carbon\Carbon::parse($or['end_date'])->format('d/m/Y') }}</td>
                                         <td>{{$or['total']}}</td>
                                         <td>0</td>
                                         <!-- <td>{{$or['shipping']}}</td> -->
                                         <!-- <td>{{$or['rto']}}</td>
                                         <td>{{$or['extra_weight']}}</td>
                                         <td>{{$or['extra_weight_rto']}}</td> -->
                                         <!--<td>{{$or['total']}}</td>-->
                                     </tr>
                                     @endif
                                     @endforeach
                                </tbody>
                            </table>        
                        </div>
                </div>
                </div>   
            </div>
        </form>         
        </div>        
    </div>
</div>

<script>
    $(document).ready(function() {
    // Destroy existing DataTable instance, if it exists
    if ($.fn.DataTable.isDataTable('.sorttablenew')) {
        $('.sorttablenew').DataTable().destroy();
    }

    // Initialize DataTable with your options
    $('.sorttablenew').DataTable({
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'csv',
                text: 'Export CSV',
                filename: function() {
                    // Get the table name
                    var tableName = $('.sorttablenew').attr('name');
                    return tableName;
                },
                exportOptions: {
                    columns: ':not([data-field="hideexport"])'
                }
            }
        ]
    });

    // Sorting the DataTable by a specific column in descending order
    $('.sorttablenew').DataTable().order([0, 'desc']).draw();
    });
</script>
@endsection