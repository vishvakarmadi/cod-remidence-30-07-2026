@extends('admin.admin_layouts')
@section('admin_content')
    <div class="block-header">
        <div class="row">
            <div class="col-lg-4">
                <h2>Ticket List</h2>
            </div>
            <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                <div class="row clearfix">
                    <div class="col-xl-5 col-md-5 col-sm-12"></div>
                    <div class="col-xl-7 col-md-7 col-sm-12 text-md-right hidden-xs">
                        <div
                            class="d-flex align-items-center justify-content-md-end mt-4 mt-md-0 flex-wrap vivify pullUp delay-550">
                            <div class="mb-3 mb-xl-0">
                                <a href="{{ route('admin.settings.ticket-create') }}" class="btn btn-dark">
                                    <i class="fa fa-plus"></i> Create
                                </a>
                                <?php if ($user->role_id == '1' || $user->role_id == '2') { ?>
                                <a href="{{ route('admin.settings.ticket-resolve') }}" class="btn btn-dark">
                                    <i class="fa fa-comment"></i> Resolve
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" id="ticketSearch" class="form-control"
                                placeholder="Search Ticket ID, User Code, AWB or Details...">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable js-exportable" id="ticketTable">
                            <thead>
                                <tr>
                                    <th class="text-center">User Id</th>
                                    <th class="text-center">Ticket Id</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Order Id</th>
                                    <th class="text-center">Awb</th>
                                    <th class="text-center">Details</th>
                                    <th class="text-center">Created on</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $row)
                                    <tr>
                                        <td>{{ $row->creator->user_code ?? $row->creator->id ?? 'N/A' }}</td>
                                        <td>{{ $row->id }}</td>
                                        <td>{{ $row->category }}</td>
                                        <td>{{ $row->status }}</td>
                                        <td>{{ $row->order_id }}</td>
                                        <td>{{ $row->awb }}</td>
                                        <td>{{ $row->description }}</td>
                                        <td>{{ $row->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search Script
        document.getElementById('ticketSearch').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#ticketTable tbody tr');

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

@endsection