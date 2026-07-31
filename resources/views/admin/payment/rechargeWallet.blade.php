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
                    <h5 class="card-title mb-0">Recharge Wallet</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.recharge.process') }}" method="POST">
                        @csrf
                        <!-- Hidden field for user_id -->
                        <input type="hidden" name="user_id" value="{{ request()->query('id') }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paymentId">Payment ID</label>
                                    <input type="text" class="form-control" id="paymentId" name="payment_id" placeholder="Enter Payment ID" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paymentAmount">Payment Amount</label>
                                    <input type="number" class="form-control" id="paymentAmount" name="payment_amount" placeholder="Enter Payment Amount" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="couponCode">Coupon Code</label>
                                    <input type="text" class="form-control" id="couponCode" name="coupon_code" placeholder="Enter Coupon Code (e.g. WELCOME, HYLO200, HYLO500)">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Recharge</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

