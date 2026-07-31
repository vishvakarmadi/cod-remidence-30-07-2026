@extends('admin.admin_layouts')

@section('admin_content')
<style>
    .table tbody tr td {
        font-size: 12px;
    }
    .table-responsive {
        margin-bottom: 15px;
    }
    /* CSS to change the text color of table headers */
    .table thead th {
        color: #6495ED; /* Blue text color */
        background-color: #ffffff; /* White background color */
        font-weight: bold;
        text-align: center; /* Center text alignment */
    }
    /* Center text in Action column */
    .table tbody tr td:last-child {
        text-align: center;
    }
    /* CSS to adjust button size */
    .btn-group .btn {
        font-size: 12px; /* Match the font size of the table cells */
        padding: 5px 10px; /* Adjust padding to reduce button size */
    }
    /* Add space between buttons */
    .btn-group .btn + .btn {
        margin-left: 5px; /* Add space between buttons */
    }
</style>

<div class="card  new_orders">
    <div class="card-header" style="display:flex;flex-wrap: wrap;"> <h2>Credit Request Verification</h2></div>
     <div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped table-hover dataTable js-exportable">
            <thead>
                <tr>
                    <th class="text-center">Request ID</th>
                    <th>User ID</th>
                    <th>Credit Amount</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th class="text-center">Action</th> <!-- Centered header -->
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach($creditRequests as $creditRequest)
                    <tr>
                        <td>{{ $creditRequest->id }}</td>
                        <td>{{ $creditRequest->user_id }}</td>
                        <td>{{ $creditRequest->credit_amount }}</td>
                        <td>{{ $creditRequest->credit_describe }}</td>
                        <td>{{ $creditRequest->status }}</td>
                        <td class="text-center">
                            @if($creditRequest->status == 'pending')
                                <div class="btn-group" role="group">
                                    <form action="{{ route('credit.approve', $creditRequest->id) }}" method="POST" onsubmit="return confirmApproval()">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form action="{{ route('credit.decline', $creditRequest->id) }}" method="POST" onsubmit="return declineApproval()">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div></div>
</div>

<script>
    function confirmApproval() {
        return confirm('Are you sure you want to approve this credit request?');
    }
    
    function declineApproval() {
        return confirm('Are you sure you want to decline this credit request?');
    }

    // Select/Deselect all checkboxes
    document.getElementById('selectAll').addEventListener('click', function(event) {
        let checkboxes = document.querySelectorAll('input[type="checkbox"][name="selectedRequests[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = event.target.checked);
    });
</script>

@endsection