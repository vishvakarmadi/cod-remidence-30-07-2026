@extends('admin.admin_layouts')
@section('admin_content')

<!-- Main body part -->
<div class="container-fluid">
    <!-- Page header section -->
    <div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-8 col-sm-12">
                <h2>Manage Courier</h2>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-12">
            <div class="card">
                <div class="body row">
                    <div class="col-md-12">
                        <div class="float-right">
                            <div class="row">
                                <div class="col-8">
                                    <b>Enable/ Disable all couriers</b>
                                </div>
                                <div class="col-sm-4">
                                <button type="button" 
                                    class="btn btn-sm btn-toggle @if($count != 0) active @endif all_courier" 
                                    data-toggle="button" 
                                    aria-pressed="@if($count != 0) 'active' @else 'inactive' @endif" 
                                    autocomplete="off" 
                                    data-company-id="{{ $companyId }}">
                                    <div class="handle"></div>
                                </button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Courier Name</th>
                                        <th class="text-center">Mode</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach($couriersFromDb as $courier)
                                    <tr>
                                        <td class="text-center">
                                            <!-- Using the image from courier.json using the courier's id -->
                                            <img src="{{ asset('public/courier/' . $couriers[$courier->courier_id]['image']) }}" alt="" style="width:50px">
                                        </td>
                                        <td>
                                            <b>{{ $couriers[$courier->courier_id]['name'] }}</b><br>
                                            {{ $couriers[$courier->courier_id]['name'] }} ({{ $courier->mode }})
                                        </td>
                                        <td class="text-center">
                                            @if($courier->mode == 'air')
                                            <i class="fa fa-plane fa-2x"></i>
                                            @else
                                            <i class="fa fa-truck fa-2x"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <!-- Toggle status button -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-toggle status @if($courier->status == 1) active @endif" 
                                                    aria-pressed="{{ $courier->status == 1 ? 'true' : 'false' }}" 
                                                    data-id="{{ $courier->courier_id }}" 
                                                    data-mode="{{ $courier->mode }}" 
                                                    data-company-id="{{ $companyId }}">
                                                <div class="handle"></div>
                                            </button>
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
    </div>
</div>

<script>

    "use strict";
    $('.status').on('click', function() {
        var courierId = $(this).data('id');
        var currentStatus = $(this).attr('aria-pressed') === 'true' ? 1 : 0;
        var mode = $(this).data('mode');
        var companyId = $(this).data('company-id');

        $('.page-loader-wrapper').show();
        var newStatus = currentStatus === 1 ? 0 : 1;
        $(this).attr('aria-pressed', newStatus === 1 ? 'true' : 'false');
        $(this).toggleClass('active');


        console.log('Courier ID:', courierId, 'Mode:', mode, 'Company ID:', companyId, 'Status:', newStatus);

        $.ajax({
            url: "{{ route('admin.integration.courier_status') }}", // Correct route
            method: "GET", 
            data: {
                courier_id: courierId,
                status: newStatus,
                mode: mode,
                company_id: companyId
            },
            success: function(response) {
                $('.page-loader-wrapper').hide();
                toastr.success(response.message);
            },
            error: function(xhr) {
                $('.page-loader-wrapper').hide();
                toastr.error('An error occurred while updating the status.');
            }
        });
    });

 
    $('.all_courier').on('click', function() {
    var companyId = $(this).data('company-id');
    let isPressed = $(this).attr('aria-pressed');  // 'true' or 'false'
    let isActive = $(this).hasClass('active');  // Check if the button has the 'active' class
    $('.page-loader-wrapper').show();
    console.log('Current aria-pressed value:', isPressed);  // Log the value of aria-pressed
    console.log('Current active class state:', isActive);  // Log whether the 'active' class is present
    console.log('Company ID:', companyId);

    var newStatus;

    // If the button is 'active' (visually on) or aria-pressed is 'true', turn it off
    if (isActive || isPressed === 'true') {
        newStatus = 0;  // Set newStatus to 0 (off)
    } else {
        newStatus = 1;  // Otherwise, set newStatus to 1 (on)
    }

    console.log('New Status:', newStatus);

    // AJAX request to update the status on the server
    $.ajax({
        url: "{{ route('admin.integration.courier_status_all') }}", 
        method: "GET",
        data: {
            company_id: companyId,
            status: newStatus, 
        },
        success: function(response) {
            console.log(response.message);  // Log success message
          // Display success message
            window.location.reload();  
        },
        error: function(xhr) {
            $('.page-loader-wrapper').hide();
            console.error('Error:', xhr);  // Log any errors
            toastr.error('An error occurred while updating the status.');  // Display error message
        }
    });
});



</script>

@endsection
