@extends('admin.admin_layouts')
@section('admin_content')
    @php
        $session = Auth::guard('admin')->user();
    @endphp
    <!--<h1 class="h3 mb-3 text-gray-800">Add User</h1>-->

    <form action="{{ route('admin.settings.ticket-store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 mt-2 font-weight-bold text-primary">Add Ticket</h6>
                        <div class="float-right d-inline">
                        </div>
                    </div>
                    <div class="card-body">

                        {{-- Order ID Dropdown --}}
                        <div class="form-group">
                            <label>Order ID</label>
                            <select name="order_id" class="form-control select2">
                                <option value="">Select Order</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->order_id }}" {{ old('order_id') == $order->order_id ? 'selected' : '' }}>
                                        {{ $order->order_id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>AWB</label>
                            <input type="text" name="awb" class="form-control" value="{{ old('awb') }}">
                        </div>
                        <div class="form-group">
                            <label>Courier</label>
                             <select name="courier" class="form-control select2">
                                <option value="">Select Courier</option>
                                <option value="Delhivery Surface" {{ old('courier') == 'Delhivery Surface' ? 'selected' : '' }}>Delhivery Surface</option>
                                <option value="Bluedart Surface" {{ old('courier') == 'Bluedart Surface' ? 'selected' : '' }}>Bluedart Surface</option>
                                <option value="Xpressbees Surface" {{ old('courier') == 'Xpressbees Surface' ? 'selected' : '' }}>Xpressbees Surface</option>
                                <option value="Shree Maruti Courier Surface" {{ old('courier') == 'Shree Maruti Courier Surface' ? 'selected' : '' }}>Shree Maruti Courier Surface</option>
                                <option value="Ekart Surface" {{ old('courier') == 'Ekart Surface' ? 'selected' : '' }}>Ekart Surface</option>
                                <option value="DTDC surface" {{ old('courier') == 'DTDC surface' ? 'selected' : '' }}>DTDC surface</option>
                                <option value="Shadowfax Surface" {{ old('courier') == 'Shadowfax Surface' ? 'selected' : '' }}>Shadowfax Surface</option>
                                <option value="blitz" {{ old('courier') == 'blitz' ? 'selected' : '' }}>blitz</option>
                                <option value="pikndel" {{ old('courier') == 'pikndel' ? 'selected' : '' }}>pikndel</option>
                            </select>
                        </div>

                        {{-- Category Dropdown --}}
                        <div class="form-group">
                            <label>Category *</label>
                            <select name="category" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            <label>Description *</label>
                            <input type="text" name="description" class="form-control" value="{{ old('description') }}"
                                required>
                        </div>

                    </div>

                    <div class="card-body">
                        <button type="submit" class="btn btn-success col-lg-4">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection