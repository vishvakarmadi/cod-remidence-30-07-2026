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
                    <h2>Employee List</h2>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                    <div class="row clearfix">
                        <div class="col-xl-5 col-md-5 col-sm-12"></div>
                        <div class="col-xl-7 col-md-7 col-sm-12 text-md-right hidden-xs">
                            <div
                                class="d-flex align-items-center justify-content-md-end mt-4 mt-md-0 flex-wrap vivify pullUp delay-550">
                                <div class="mb-3 mb-xl-0">
                                    @if ($session->role_id == '1' || $session->role_id == '2')
                                        <a href="{{ route('admin.role.user-create') }}" class="btn btn-dark">
                                            <i class="fa fa-plus"></i> Create
                                        </a>
                                        <a href="{{ route('admin.role.user-query') }}" class="btn btn-primary">
                                            <i class="fa fa-file-o"></i> New Query
                                        </a>
                                        <a href="{{ route('admin.role.user-view') }}" class="btn btn-secondary">
                                            <i class="fa fa-inbox"></i> New Visit
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card">

                        <div class="body">
                            <div class="table-responsive" style="min-height:220px">
                                <table class="table table-striped table-hover dataTable js-exportable sorttableexcel">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Seller id</th>
                                            <th>Name</th>
                                            <th>Parent ID</th>
                                            <th>Email Address</th>
                                            <th>SM</th>
                                            <th>Role</th>
                                            <th>Company Name</th>
                                            <th>GST</th>
                                            <th>Bank Name</th>
                                            <th>A/C No</th>
                                            <th>IFSC Code</th>
                                            <th>Phone</th>
                                            <th>Kyc Status</th>
                                            <th>Rate Type</th>
                                            <th>User Type</th>
                                            <th>Wallet Amount</th>
                                            <th>Loan Amount</th>
                                            <th>Max limit</th>
                                            <th>Active Status</th>
                                            <th>Account Created</th>
                                            @if ($session->role_id == '1')
                                                <th>Last Login</th>
                                            @endif
                                            <th data-field="hideexport">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($admin_users as $row)
                                            @if ($row->id != 1)
                                                <?php
                                                $dublicate = admin::chkdublictaegst($row->id);
                                                ?>
                                                <tr class="dublicate_{{ $dublicate }}">
                                                    <td>{{ $row->id }}</td>
                                                    <td>{{ $row->user_code }}</td>
                                                    <td>{{ $row->name }}</td>
                                                    <td>{{ $row->parent_id ? ($row->parent_id . ($row->parent ? ' ('.$row->parent->name.')' : '')) : 'N/A' }}</td>
                                                    <td>{{ $row->email }}</td>
                                                    <td>{{ $row->sm }}</td>
                                                    <td>
                                                        @if ($row->role_id == '1')
                                                            <span class="badge text-white bg-secondary">Admin</span>
                                                        @elseif ($row->role_id == '2')
                                                            <span class="badge text-white bg-dark">Seller-admin</span>
                                                        @elseif ($row->role_id == '3')
                                                            @if ($row->role_action == '1')
                                                                <span class="badge text-white bg-dark">Seller-admin</span>
                                                            @else
                                                                <span class="badge text-white bg-danger">Pemission
                                                                    Required</span>
                                                                <br>(Seller-admin)
                                                            @endif
                                                        @else
                                                            <span class="badge text-white bg-success">User</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $row->company_name }}</td>
                                                    <td>{{ @$row->profile[0]->gst }}</td>
                                                    <td>{{ @$row->profile[0]->bank_name }}</td>
                                                    <td>{{ @$row->profile[0]->account_no }}</td>
                                                    <td>{{ @$row->profile[0]->ifsc_code }}</td>
                                                    <td>{{ $row->mobile }}</td>
                                                    <td>{!! $row->kyc_status !!}</td>
                                                    <td>{{ $row->ratecard_type }}</td>
                                                    <td>{{ $row->userpayment_type }}</td>
                                                    <td>{{ number_format($row->wallet_blc, 2) }}</td>
                                                    <td>{{ number_format($row->loan_amount, 2) }}</td>
                                                    <td>
                                                        @if ($row->userpayment_type == 'postpaid' || $row->userpayment_type == 'codminusremittance')
                                                            {{ number_format($row->limit_loan, 2) }}
                                                        @else
                                                            0
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row->active == '0')
                                                            <span class="badge text-white bg-secondary">In-active</span>
                                                        @else
                                                            <span class="badge text-white bg-success">Active</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $row->created_at->format('d/m/Y H:i:s') }}</td>
                                                    @if ($session->role_id == '1')
                                                        <td>{{ $row->last_login }}</td>
                                                    @endif
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-info dropdown-toggle"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                @if ($session->role_id == '1')
                                                                    @if ($row->kyc_status != '<span class="badge text-white bg-warning">Yet To Fill</span>')
                                                                        <a class="dropdown-item"
                                                                            href="{{ URL::to('kyc/show/' . $row->id) }}"
                                                                            target="_blank">View KYC</a>

                                                                        <a class="dropdown-item"
                                                                            href="{{ URL::to('getrate/' . $row->id) }}">View
                                                                            Ratecard</a>
                                                                        <!--<a class="dropdown-item" href="{{ URL::to('addloan/' . $row->id) }}">Add Money</a>-->
                                                                    @endif
                                                                    <a class="dropdown-item"
                                                                        href="{{ URL::to('role/user/edit/password/' . $row->id) }}">Password</a>
                                                                    <a class="dropdown-item"
                                                                        href="{{ URL::to('role/user/edit/' . $row->id) }}">Edit</a>
                                                                @endif
                                                                <a class="dropdown-item"
                                                                    href="{{ URL::to('role/user/login/' . $row->id) }}"
                                                                    onClick="return confirm('Are you sure You want to login as a User?');">Login
                                                                    as User</a>
                                                                <!-- <a class="dropdown-item edit-btn" href="javascript:void(0)" data-edit='@json($row)'>Upload Ratecard</a> -->
                                                                @if ($session->role_id == '1')
                                                                    @if ($row->active == '0')
                                                                        <a class="dropdown-item"
                                                                            href="{{ URL::to('role/user/active/' . $row->id) }}"
                                                                            onClick="return confirm('Are you sure You want to activate this User?');">Active</a>
                                                                    @else
                                                                        <a class="dropdown-item"
                                                                            href="{{ URL::to('role/user/active/' . $row->id) }}"
                                                                            onClick="return confirm('Are you sure You want to deactivate this User?');">In-Active</a>
                                                                    @endif


                                                                    @if ($row->role_id == '3' && $row->role_action == '0')
                                                                        <a class="dropdown-item"
                                                                            href="{{ URL::to('role/user/superseller/' . $row->id) }}"
                                                                            onClick="return confirm('Are you sure to make this user super-seller');">Make
                                                                            superseller</a>
                                                                    @endif
                                                                @endif
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.payment.request', ['id' => $row->id]) }}">Request Limit</a>
                                                                @if ($session->id == 1)
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.recharge.wallet', ['id' => $row->id]) }}">Recharge
                                                                        wallet</a>
                                                                    <a class="dropdown-item"
                                                                        href="{{ URL::to('role/user/delete/' . $row->id) }}"
                                                                        onClick="return confirm('Are you sure You want to Delete a User?');">Delete</a>
                                                                @endif
                                                            </div>
                                                        </div>

                                                    </td>
                                                </tr>
                                            @endif
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
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="rechargeForm" action="{{ route('admin.bulkrate') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Import Rate File</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <a href="{{ asset('ratecards.xlsx') }}" download=""
                                    class="btn btn-secondary">Download Format</a>
                            </div>
                            <div class="form-group col-md-12">
                                <input class="form-control" type="hidden" name="id" id="id" required
                                    readonly>
                                <div class="image-upload">
                                    <div class="thumb">
                                        <div class="avatar-edit">

                                            <input type="file" class="profilePicUpload" name="excel"
                                                id="profilePicUpload1" required accept=".xlsx">
                                            <label for="profilePicUpload1" class="bg-secondary text-white">Upload
                                                File</label>
                                            <small class="mt-2">Supported files: <b>xlsx</b>.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="my-modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Rate File</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.bulkrate') }}" method="POST" class="myform"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="modal-body">
                                <div class="form-group">
                                    <a href="{{ asset('ratecards.xlsx') }}" download=""
                                        class="btn btn-secondary">Download Format</a>
                                </div>
                                <input class="form-control" type="hidden" name="id" id="id" value=""
                                    readonly>
                                <div class="form-group">
                                    <label for="">Choose Excel File</label>
                                    <input type="file" class="form-control profilePicUpload" name="excel"
                                        id="profilePicUpload1" required accept=".xlsx">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#model").on("click", function(event) {
                var id = document.getElementById("model").value;
                $('#exampleModalCenter').modal('show');
                $('#id').val(id);
            });
        });


        "use strict";
        (function($) {
            $(document).ready(function() {
                let myModal = new bootstrap.Modal(document.getElementById('my-modal'));

                $('.edit-btn').on('click', function(e) {
                    let data = $(this).data('edit');
                    $("input[name=id]").val(data.id);
                    myModal.show();
                });
            });
        })(jQuery);
    </script>
@endsection
