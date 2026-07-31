@extends('admin.admin_layouts')
@section('admin_content')



    <div class="row">

        <div class="col-lg-12">
        <form action="{{ route('admin.role.updateloan') }}" method="POST" enctype="multipart/form-data">
            @csrf
         <input type="hidden" name="u_id" value="{{ $id }}">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 mt-2 font-weight-bold text-primary">Add Loan Amount</h6>
                        
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Amount *</label>
                            <input type="number" name="amount" class="form-control" value="" autofocus>
                        </div>
                        
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
        </div>
    </div>


@endsection
