@extends('admin.admin_layouts')
@section('admin_content')
 <style>
    textarea {
  width: 100%;
  height: 80px;
  padding: 12px 20px;
  box-sizing: border-box;
  border: 2px solid #ccc;
  border-radius: 4px;
  background-color: #f8f8f8;
  font-size: 16px;
  resize: none;
}
 </style>  
 <div class="block-header">
            <div class="row">
                <div class="col-lg-5 col-md-8 col-sm-12">
                    <h2>Add NDR Action</h2>
                </div>
            </div>
        </div> 
    <form action="{{ route('admin.payment.update') }}" method="post" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <!-- <div class="card-header py-3">
                        <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
                        
                    </div> -->
                    <div class="card-body">
                        @if($order->ship_courier_id == 13)
                            <div class="form-group col-md-12" style="padding:0">
                                <label for="pu_ndr_action" class="form-control-label">Parcel Uncle Action</label><span class="required"> *</span>:
                                <select name="pu_ndr_action" id="pu_ndr_action" class="form-control select2" required style="width: 100%; height: 38px; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; margin-bottom: 15px;">
                                    <option value="REATTEMPT">Reattempt Delivery</option>
                                    <option value="ADDRESS_UPDATE">Update Address & Reattempt</option>
                                    <option value="RTO">Return to Origin (RTO)</option>
                                </select>
                            </div>

                            <!-- Reattempt fields -->
                            <div id="pu_reattempt_fields" class="pu_fields">
                                <div class="form-group col-md-12" style="padding:0">
                                    <label for="pu_reattempt_date" class="form-control-label">Reattempt Date</label><span class="required"> *</span>:
                                    <input type="date" name="pu_reattempt_date" id="pu_reattempt_date" class="form-control" value="{{ date('Y-m-d', strtotime('+1 day')) }}" style="width: 100%; height: 38px; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; margin-bottom: 15px;">
                                </div>
                                <div class="form-group col-md-12" style="padding:0">
                                    <label for="pu_reattempt_slot" class="form-control-label">Reattempt Slot (Optional)</label>
                                    <select name="pu_reattempt_slot" id="pu_reattempt_slot" class="form-control" style="width: 100%; height: 38px; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; margin-bottom: 15px;">
                                        <option value="">Select slot</option>
                                        <option value="10AM-1PM">10AM - 1PM</option>
                                        <option value="1PM-4PM">1PM - 4PM</option>
                                        <option value="4PM-7PM">4PM - 7PM</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Address update fields -->
                            <div id="pu_address_fields" class="pu_fields" style="display:none;">
                                <div class="form-group col-md-12" style="padding:0">
                                    <label for="pu_updated_address" class="form-control-label">Updated Address</label><span class="required"> *</span>:
                                    <textarea name="pu_updated_address" id="pu_updated_address" class="form-control" style="width: 100%; height: 60px; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; margin-bottom: 15px; resize: none;">{{ $order->ship_address }}</textarea>
                                </div>
                                <div class="form-group col-md-12" style="padding:0">
                                    <label for="pu_updated_phone" class="form-control-label">Updated Phone (Optional)</label>
                                    <input type="text" name="pu_updated_phone" id="pu_updated_phone" class="form-control" value="{{ $order->ship_phone }}" style="width: 100%; height: 38px; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; margin-bottom: 15px;">
                                </div>
                            </div>

                            <!-- RTO fields -->
                            <div id="pu_rto_fields" class="pu_fields" style="display:none;">
                                <div class="form-group col-md-12" style="padding:0">
                                    <label for="pu_rto_remarks" class="form-control-label">RTO Remarks (Optional)</label>
                                    <input type="text" name="pu_rto_remarks" id="pu_rto_remarks" class="form-control" placeholder="RTO reason or remarks" style="width: 100%; height: 38px; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; margin-bottom: 15px;">
                                </div>
                            </div>

                            <script>
                                document.getElementById('pu_ndr_action').addEventListener('change', function() {
                                    var action = this.value;
                                    var fields = document.querySelectorAll('.pu_fields');
                                    fields.forEach(function(field) {
                                        field.style.display = 'none';
                                    });
                                    if (action === 'REATTEMPT') {
                                        document.getElementById('pu_reattempt_fields').style.display = 'block';
                                    } else if (action === 'ADDRESS_UPDATE') {
                                        document.getElementById('pu_address_fields').style.display = 'block';
                                    } else if (action === 'RTO') {
                                        document.getElementById('pu_rto_fields').style.display = 'block';
                                    }
                                });
                            </script>
                        @endif

                        <div class="form-group col-md-12" style="padding:0">
                            <label for="closing_description" class="form-control-label">Description / Internal Remarks</label><span class="required"> *</span>:<br>
                            <textarea name="closing_description" id="textareaID" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success col-md-4">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
