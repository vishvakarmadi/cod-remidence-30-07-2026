@extends('admin.admin_layouts')

@section('admin_content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-4">
                <h2>Ticket List</h2>
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
                                placeholder="Search Ticket ID, User Code or Details...">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable js-exportable" id="ticketTable">
                            <thead>
                                <tr>
                                    <th>User Id</th>
                                    <th>Ticket Id</th>
                                    <th class="text-center">Created On</th>
                                    <th class="text-center">Created By</th>
                                    <th>Details</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $row)
                                    <tr class="ticket-row">
                                        <td class="text-center user-code">
                                            {{ $row->creator->user_code ?? 'N/A' }}
                                        </td>
                                        <td class="text-center ticket-id">
                                            {{ $row->id ?? 'N/A' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->created_at->format('d-m-Y H:i') }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->creator->name ?? 'N/A' }}
                                        </td>
                                        <td class="ticket-details">
                                            {{ $row->description }}
                                        </td>
                                        <td class="text-center">
                                            @if(in_array($row->status, ['open', 'reopen']))
                                                <button type="button" class="btn btn-sm btn-success resolve-btn" data-toggle="modal"
                                                    data-target="#resolveModal" data-ticket-id="{{ $row->id }}">
                                                    Mark as Resolved
                                                </button>
                                            @else
                                                <span class="badge badge-secondary">
                                                    Closed
                                                </span>
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

    {{-- ================= RESOLVE MODAL ================= --}}
    <div class="modal fade" id="resolveModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('admin.ticket.resolve') }}">
                @csrf

                <input type="hidden" name="ticket_id" id="ticket_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Resolve Ticket</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Resolution Description</label>
                            <textarea name="resolved_description" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                            Submit & Close Ticket
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= JS ================= --}}
    <script>
        $(document).on('click', '.resolve-btn', function () {
            let ticketId = $(this).data('ticket-id');
            $('#ticket_id').val(ticketId);
        });

        // Search Script
        document.getElementById('ticketSearch').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#ticketTable tbody tr');

            rows.forEach(row => {
                let userCode = row.querySelector('.user-code').textContent.toLowerCase();
                let ticketId = row.querySelector('.ticket-id').textContent.toLowerCase();
                let details = row.querySelector('.ticket-details').textContent.toLowerCase();

                if (userCode.includes(filter) || ticketId.includes(filter) || details.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

@endsection