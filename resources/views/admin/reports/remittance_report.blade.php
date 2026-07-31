@extends('admin.admin_layouts')
@section('admin_content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 mt-2 font-weight-bold text-primary">Remittance Report</h6>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('admin.reports.rem_report') }}" class="mb-4">
                    <div class="form-row">
                        <div class="col">
                            <input type="text" name="id" class="form-control" placeholder="Remittance ID" value="{{ request('id') }}">
                        </div>
                        <div class="col">
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="Start Date">
                        </div>
                        <div class="col">
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" placeholder="End Date">
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>

                <!-- Conditional Display of Remittance Table -->
                @if($remittances->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered sorttable" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Remittance ID</th>
                                    <th>Order ID</th>
                                    <th>Total</th>
                                    <th>Vendor Order ID</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($remittances as $remittance)
                                    <tr>
                                        <td>{{ $remittance['id'] }}</td>
                                        <td>{{ $remittance['order_id'] }}</td>
                                        <td>{{ $remittance['total'] }}</td>
                                        <td>{{ $remittance['vendor_order_id'] }}</td>
                                        <td>{{ $remittance['start_date'] }}</td>
                                        <td>{{ $remittance['end_date'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    {{ $pagination->links() }}
                @else
                    @if(request()->has('id') || request()->has('start_date') || request()->has('end_date'))
                        <p>No results found.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
