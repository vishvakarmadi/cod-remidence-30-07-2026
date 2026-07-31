@extends('admin.admin_layouts')
@section('admin_content')
    @php
        $session = Auth::guard('admin')->user();
    @endphp
    


    <div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-8 col-sm-12">
                <h2>Order Edit</h2>
            </div>
            <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                <ul class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <form id="form_submit" action="{{ route('admin.order.updateweight',$order->id) }}" method="POST">
            @csrf
            <div class="col-12">
                <div class="card">
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6 ">
                                <label>Order Extra Weight (gms)</label><span class="required"> *</span>
                                <input class="form-control" type="number" name="extra_weight" required value="{{ $order->extra_weight }}">
                                <input class="form-control" type="hidden" name="order_id" required value="{{ $order->order_id }}">
                            </div>
                           
                            <div class="form-group col-md-6">
                                <label>Order Extra Charge (Rs.) </label><span class="required"> *</span>
                                <input class="form-control" type="number" name="extra_weight_cost" value="{{ $order->extra_weight_cost }}" required>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            
            
            <hr>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn-primary btn h-45 w-100">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


@endsection
