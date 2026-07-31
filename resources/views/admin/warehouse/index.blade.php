@extends('admin.admin_layouts')
@section('admin_content')
@php 
$user_id = Auth::guard('admin')->user()->id;
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="row">
        <div class="col-lg-12">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-9">
                    <h2>Warehouse List</h2>
                </div>
                <div class="col-lg-3">
                <button type="button" class="btn btn-primary add-btn h-45">
                        <i class="las la-plus"></i>@lang('Create New Warehouse')
                    </button>&nbsp;
                </div>
            </div>
        </div>
            
            <div class="card b-radius--10 ">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered sorttable table-striped table-hover" width="100%" cellspacing="0">

                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Warehouse ID')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Contact Person')</th>
                                    <th>@lang('Pincode')</th>
                                    <th>@lang('Assigned User')</th>
                                    <th>@lang('Contact Details')</th>
                                    <th>@lang('Default')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($warehouse as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>WHKP00{{ $loop->iteration }}</td>
                                        <td>{{ __(ucfirst($row->name)) }}</td> <!--First Letter Capital-->
                                        <td>{{ __(ucfirst($row->contact_name)) }}</td>
                                        <td>{{ ($row->pincode) }}</td>
                                        <td>All</td>
                                        <td><i class="fa fa-user">&nbsp;&nbsp;{{ ($row->contact_name) }}<i><br><br><i class="fa fa-envelope">&nbsp;&nbsp;{{ ($row->email) }}<i><br><br><i class="fa fa-phone">&nbsp;&nbsp;{{ ($row->phone) }}</td>
                                        <td>
                                            @if($row->default == 1)
                                            Yes
                                            @else
                                            No
                                            @endif
                                        </td>
                                        <td>
                                        @if($row->user_id == auth()->guard('admin')->user()->id)
                                        <div class="btn-group" role="group">
                                        <!-- <button type="button" class="btn btn-primary location m-0"  data-toggle="modal" data-id="{{ $row->id }}" data-action="{{ URL::to('admin/warehouse/location/' . $row->id) }}">
                                        <b><i class="fa fa-map-marker" aria-hidden="true"></i></b>
                                        </button> -->
                                        <button type="button" class="btn btn-primary edit-btn" title="Edit" data-edit='@json($row)'>
                                        <b><i class="fa fa-pencil" aria-hidden="true"></i></b>
                                        </button>
                                        <a href="{{ URL::to('warehouse/delete/' . $row->id) }}" class="btn btn-secondary" title="Delete" onClick="return confirm('Are you sure?');" class="btn btn-danger btn-sm m-0">
                                        <b><i class="fa fa-trash" aria-hidden="true"></i></b>
                                        </a>
                                        </div>
                                    @endif
                                    </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">No Warehouse Data Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div id="my-modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document" style="max-width: 1000px;">
            <div class="modal-content">
                <div class="modal-header  card-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST" class="myform">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input class="form-control" id="name" type="text" name="name" required
                                        value="{{ old('name') }}">
                                </div>

                                <div class="form-group">
                                    <label>@lang('Contact Name')</label>
                                    <input class="form-control" type="text" name="contact_name" required
                                        value="{{ old('contact_name') }}">
                                </div>

                                <div class="form-group">
                                    <label>@lang('Company')</label>
                                    <input class="form-control" type="text" name="company" required
                                        value="{{ old('company') }}">
                                </div>

                                <div class="form-group">
                                    <label>@lang('Email')</label>
                                    <input class="form-control" type="email" name="email" required
                                        value="{{ old('email') }}">
                                </div>

                                <div class="form-group">
                                    <label>@lang('Phone')</label>
                                    <input class="form-control" type="number" name="phone" required
                                        value="{{ old('phone') }}">
                                </div>

                                

                                

                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="modal-body">
                                
                                <div class="form-group">
                                    <label>@lang('Address 1')</label>
                                    <input class="form-control" type="text" name="address" required
                                        value="{{ old('address') }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('Address 2')</label>
                                    <input class="form-control" type="text" name="address_2" required
                                        value="{{ old('address_2') }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('City')</label>
                                    <input class="form-control" type="text" name="city" required
                                        value="{{ old('city') }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('State')</label>
                                    <input class="form-control" type="text" name="state" required
                                        value="{{ old('state') }}">
                                </div>

                                <div class="form-group">
                                    <label>@lang('Country')</label>
                                    <select name="country_id" class="form-control" required>
                                        <option> Select Country </option>
                                        @foreach ($country as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Pincode')</label>
                                    <input class="form-control" type="number" name="pincode" required
                                        value="{{ old('pincode') }}">
                                </div>

                               

                                


                            </div>
                        </div>
                        <div  class="col-lg-4">
                            <div class="modal-body">
                                 <div class="form-group">
                                    <label>@lang('Latitude')</label>
                                    <input class="form-control" type="number" name="latitude" 
                                        value="{{ old('latitude') }}">
                                </div>

                                <div class="form-group">
                                    <label>@lang('Longitude')</label>
                                    <input class="form-control" type="number" name="longitude" 
                                        value="{{ old('longitude') }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('GST Number')</label>
                                    <input class="form-control" type="text" name="gst_no" required
                                        value="{{ old('gst_no') }}">
                                </div>

                                <div class="form-group">
                                    <label>@lang('FSSAI Licence')</label>
                                    <input class="form-control" type="text" name="fssai_licence" 
                                        value="{{ old('fssai_licence') }}">
                                </div>

                                <div class="form-group">
                                    <label>@lang('Note')</label>
                                    <input class="form-control" type="text" name="note" 
                                        value="{{ old('note') }}">
                                </div>

                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="default" id="setDefaultAddressCheckbox"
                                            value="1">
                                        Set as Default Address
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-4 col-lg-4">
                        <button type="submit" class="btn btn-secondary h-45 w-100 ">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  
  <!-- Modal -->
  <div class="modal fade" id="location" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmation..</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">Do you want to update the current location ?</div>
                <input type="hidden" name="latitude" id="latitude" value="">
                <input type="hidden" name="longitude" id="longitude" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="sbumit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
  </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function ($) {
            "use strict";
            $(document).on('click','.location', function () {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;
                        document.getElementById("latitude").value = position.coords.latitude;
                        document.getElementById("longitude").value = position.coords.longitude;
                    });

                    let modal = new bootstrap.Modal(document.getElementById('location'));
                    let action = `{{ route('admin.warehouse.location', ':id') }}`;
                    let data  = $(this).data();
                    $('#location form').attr("action", action.replace(":id", data.id));
                    modal.show();
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            });
        })(jQuery);
    </script>



    <script>
        "use strict";
        (function($) {
            $(document).ready(function() {
                let myModal = new bootstrap.Modal(document.getElementById('my-modal'));
                let action = `{{ route('admin.warehouse.save') }}`

                $('.add-btn').on('click', function(e) {

                    $('.modal-title').text("@lang('New Warehouse')");
                    $('.myform').trigger("reset");
                    $('.myform').attr("action", action);
                    // Ensure the modal is initialized as a Bootstrap modal
                    myModal.show();
                });

                $('.edit-btn').on('click', function(e) {
                let action = `{{ route('admin.warehouse.save', ':id') }}`;
                let data = $(this).data('edit');
                $('.modal-title').text("@lang('Update Warehouse')");
                $("input[name=name]").val(data.name);
                $("input[name=contact_name]").val(data.contact_name);
                $("input[name=company]").val(data.company);
                $("input[name=email]").val(data.email);
                $("input[name=phone]").val(data.phone);
                $("input[name=address]").val(data.address);
                $("input[name=address_2]").val(data.address_2);
                $("input[name=city]").val(data.city);
                $("input[name=state]").val(data.state);
                $("select[name=country_id]").val(data.country_id);
                $("input[name=pincode]").val(data.pincode);
                $("input[name=latitude]").val(data.latitude);
                $("input[name=longitude]").val(data.longitude);
                $("input[name=gst_no]").val(data.gst_no);
                $("input[name=fssai_licence]").val(data.fssai_licence);
                $("input[name=note]").val(data.note);
                $("input[name='default']").prop("checked", data.default);

                $('form').attr("action", action.replace(":id", data.id));
                myModal.show();
                
            });


            });
        })(jQuery);
    </script>
  
@endsection
