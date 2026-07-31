@extends('admin.admin_layouts')
@section('admin_content')


<div class="col-12">
    <div class="card pt-30">
        <div class="card-header">
            <h5 class="card-title mb-0 font-weight-bold text-primary">Add Coupon<a data-action="collapse"
                    class="float-right toggle-icon"><i class="fa fa-minus"></i></a></h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.coupon.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4 ">
                        <label>Coupon Code </label><span class="required"> *</span>
                        <input class="form-control" type="text" name="coupon_code" required
                            value="{{ old('coupon_code') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-control-label">Coupon Type</label>
                        <select class="form-control" name="coupon_type">
                            <option value="Percentage">Percentage</option>
                            <option value="Amount">Amount</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Coupon Discount </label><span class="required"> *</span>
                        <input class="form-control" type="text" name="coupon_discount"
                            value="{{ old('coupon_discount') }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Coupon Start Date </label><span class="required"> *</span>
                        <input class="form-control" type="date" name="coupon_start_date"
                            value="{{ old('coupon_start_date') }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Coupon End Date </label><span class="required"> *</span>
                        <input class="form-control" type="date" name="coupon_end_date"
                            value="{{ old('coupon_end_date') }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-control-label">User</label><span class="required"> *</span>
                        <select class="form-control" name="created_by" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} - ({{ $user->mobile }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-control-label">Coupon Status</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="coupon_status" id="rr1" value="Show"
                                    checked>
                                <label class="form-check-label font-weight-normal" for="rr1">Show</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="coupon_status" id="rr2" value="Hide">
                                <label class="form-check-label font-weight-normal" for="rr2">Hide</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12 text-center">
                        <button type="submit" class="btn btn-success mx-auto">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>





@endsection