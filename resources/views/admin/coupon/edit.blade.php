@extends('admin.admin_layouts')
@section('admin_content')
    <!-- <h1 class="h3 mb-3 text-gray-800">Edit Coupon</h1> -->

    <div class="row">
        <div class="col-md-12">
            <div class="card pt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0 text-primary">Edit Coupon
                        <a data-action="collapse" class="float-right toggle-icon"><i class="fa fa-minus"></i></a>
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/coupon/update/'.$coupon->id) }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Coupon Code </label><span class="required"> *</span>
                                <input class="form-control" type="text" name="coupon_code" required value="{{ $coupon->coupon_code }}" autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Coupon Type</label>
                                <select class="form-control" name="coupon_type">
                                    <option value="Percentage" @if($coupon->coupon_type == 'Percentage') selected @endif>Percentage</option>
                                    <option value="Amount" @if($coupon->coupon_type == 'Amount') selected @endif>Amount</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Coupon Discount </label><span class="required"> *</span>
                                <input class="form-control" type="text" name="coupon_discount" value="{{ $coupon->coupon_discount }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Coupon Start Date </label><span class="required"> *</span>
                                <input class="form-control" type="date" name="coupon_start_date" value="{{ $coupon->coupon_start_date }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Coupon End Date </label><span class="required"> *</span>
                                <input class="form-control" type="date" name="coupon_end_date" value="{{ $coupon->coupon_end_date }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">User</label><span class="required"> *</span>
                                <select class="form-control" name="created_by" required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                        @if ($user->id == $coupon->created_by)
                                        selected
                                        @endif
                                        >{{ $user->name }} - ({{ $user->mobile }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Coupon Status</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="coupon_status" id="rr1" value="Show" @if($coupon->coupon_status == 'Show') checked @endif>
                                        <label class="form-check-label font-weight-normal" for="rr1">Show</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="coupon_status" id="rr2" value="Hide" @if($coupon->coupon_status == 'Hide') checked @endif>
                                        <label class="form-check-label font-weight-normal" for="rr2">Hide</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12 text-center">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
