@extends('admin.admin_layouts')
@section('admin_content')
    <div class="row">

        <div class="col-lg-12">
        <form action="{{ route('admin.role.per_store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card responsive-filter-card mb-4">
                <div class="card-body">
                        <div class="d-flex flex-wrap gap-4">
                            <div class="flex-grow-1">
                                <label>@lang('Permission')</label>
                                <input type="text" name="prefix" value="" class="form-control" required>
                            </div>&nbsp;
                        </div>
                </div>
            </div>

            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th class="text-center">@lang('Permissionss')</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="row " style="padding:20px">
                                        <div class="col-md-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="list" name="permission[]" value="list">
                                                <label class="custom-control-label" for="list">List</label>
                                            </div>
                                            <div class="col-md-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="create" name="permission[]" value="create">
                                                <label class="custom-control-label" for="create">Create</label>
                                            </div>
                                            <div class="col-md-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="edit" name="permission[]" value="edit">
                                                <label class="custom-control-label" for="edit">Edit</label>
                                            </div>
                                            <div class="col-md-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="delete" name="permission[]" value="delete">
                                                <label class="custom-control-label" for="delete">Delete</label>
                                            </div>
                                            <div class="col-md-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="show" name="permission[]" value="show">
                                                <label class="custom-control-label" for="show">Show</label>
                                            </div>
                                            <div class="col-md-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="download" name="permission[]" value="download">
                                                <label class="custom-control-label" for="download">Download</label>
                                            </div>
                                            <div class="col-md-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="export" name="permission[]" value="export">
                                                <label class="custom-control-label" for="export">Export</label>
                                            </div>
                                            <div class="col-md-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="approve" name="permission[]" value="approve">
                                                <label class="custom-control-label" for="approve">Approve</label>
                                            </div>
                                            <div class="col-md-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="reject" name="permission[]" value="reject">
                                                <label class="custom-control-label" for="reject">Reject</label>
                                            </div>
                                            <div class="col-md-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="change" name="permission[]" value="change">
                                                <label class="custom-control-label" for="change">Status Change</label>
                                            </div>
                                            <div class="col-md-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="duplicate" name="permission[]" value="duplicate">
                                                <label class="custom-control-label" for="duplicate">Duplicate</label>
                                            </div>
                                            <div class="col-md-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="enable" name="permission[]" value="enable">
                                                <label class="custom-control-label" for="enable">Enable</label>
                                            </div>
                                            <div class="col-md-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="split" name="permission[]" value="split">
                                                <label class="custom-control-label" for="split">Split</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn--primary btn h-45 w-100">@lang('Submit')</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>

@endsection

