@extends('admin.admin_layouts')
@section('admin_content')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xl-12">
            <div class="card  mb-3">
            <div class="card-header" style="display:flex;flex-wrap: wrap;border-radius: 16px 16px 0 0;">
                    <!-- <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Filter</h5> -->
                        <div class="col-md-9"><a href="javascript:void(0)" class="expand">Filters </a></div>
                        
                </div>
                <div class="card-body">
                <!-- Export Button -->
                <form method="GET" action="#" class="mb-4">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}" >
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}" >
                        </div>
                        <!-- User ID Filter -->
                        <div class="col-md-4">
                            <label for="user_id" class="form-label">User</label>
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} (ID: {{ $user->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    </div>
                    <div class="mt-4">
                            <x-button size="col-lg-3" type="submit" name="Search" />
                        </div>
                        
                </form>
                </div>
                </div>
                </div>
                </div>
                <div class="row clearfix">
                    <div class="col-xl-12">
                        <div class="card  mb-3">   
                            <div class="header d-flex justify-content-between" style="padding-bottom: 0;">
                                <h2>Activity logs</h2>
                            </div>
                            <div class="card-body">
                                <!-- Activity Logs Table -->
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table-bordered align-middle sorttableexcel">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User ID</th>
                                                <th>Action</th>
                                                <th>Action Type</th>
                                                <th>Action ID</th>
                                                <th>Action Description</th>
                                                <th>Date & Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($logs as $log)
                                                <tr>
                                                    <td>{{ $log->id }}</td>
                                                    <td>{{ $log->user_id }}</td>
                                                    <td>
                                                        <a href="#" class="action-link" 
                                                        data-ip-address="{{ $log->ip_address }}"
                                                        data-geo-location="{{ $log->geo_location }}"
                                                        data-user-agent="{{ $log->user_agent }}"
                                                        data-request-method="{{ $log->request_method }}"
                                                        data-response-time="{{ $log->response_time }} ms"
                                                        data-action-description="{{ $log->action_description }}"
                                                        data-link-requested="{{ $log->link_requested }}">
                                                            {{ $log->action }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $log->action_type }}</td>
                                                    <td>{{ $log->action_id }}</td>
                                                    <td>{{ $log->action_description }}</td>
                                                    <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
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


<!-- Modal for displaying details -->
<div id="detailsModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Action Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>IP Address:</strong> <span id="modal-ip-address" class="d-block"></span></p>
                <p><strong>Geo Location:</strong> <span id="modal-geo-location" class="d-block"></span></p>
                <p><strong>User Agent:</strong> <span id="modal-user-agent" class="d-block"></span></p>
                <p><strong>Request Method:</strong> <span id="modal-request-method" class="d-block"></span></p>
                <p><strong>Response Time:</strong> <span id="modal-response-time" class="d-block"></span></p>
                <p><strong>Action Description:</strong> <span id="modal-action-description" class="d-block"></span></p>
                <p><strong>Link Requested:</strong> <span id="modal-link-requested" class="d-block"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.action-link').click(function(event) {
            event.preventDefault();

            // Get data from attributes
            var ipAddress = $(this).data('ip-address');
            var geoLocation = $(this).data('geo-location');
            var userAgent = $(this).data('user-agent');
            var requestMethod = $(this).data('request-method');
            var responseTime = $(this).data('response-time');
            var actionDescription = $(this).data('action-description');
            var linkrequested= $(this).data('link-requested')

            // Log the data to the console
            console.log('IP Address:', ipAddress);
            console.log('Geo Location:', geoLocation);
            console.log('User Agent:', userAgent);
            console.log('Request Method:', requestMethod);
            console.log('Response Time:', responseTime);
            console.log('Action Description:', actionDescription);

            // Set data in modal
            $('#modal-ip-address').text(ipAddress);
            $('#modal-geo-location').text(geoLocation);
            $('#modal-user-agent').text(userAgent);
            $('#modal-request-method').text(requestMethod);
            $('#modal-response-time').text(responseTime);
            $('#modal-action-description').text(actionDescription);
            $('#modal-link-requested').text(linkrequested);

            // Show modal
            $('#detailsModal').modal('show');
        });
    });
</script>

@endsection