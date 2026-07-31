@extends('admin.admin_layouts')

@section('admin_content')
<div class="container-fluid">
    <!-- Page header section -->
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12 col-sm-12">
                <h1>Hi, Welcome back!</h1>
            </div>
            <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                <div class="row clearfix">
                    <div class="col-xl-5 col-md-5 col-sm-12"></div>
                    <div class="col-xl-7 col-md-9 col-sm-12 text-md-right hidden-xs"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row clearfix">
        <div class="col-12">
            <div class="card pt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">Request Credit</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('request.credit') }}" method="POST">
                        @csrf
                        <!-- Hidden field for user_id -->
                        <input type="hidden" name="user_id" value="{{ request()->query('id') }}">

                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="creditAmount">Credit Amount</label>
                                    <input type="number" class="form-control" id="creditAmount" name="credit_amount" placeholder="Enter amount" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="creditDescription">Description</label>
                                    <textarea class="form-control" id="creditDescription" name="credit_describe" rows="1" placeholder="Enter Description" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Request</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection