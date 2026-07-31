@extends('admin.admin_layouts')
@section('admin_content')

<div class='col-12'>
    <h6>Xpressbees</h6>
    <div class="row clearfix">
        <form action="{{ route('admin.integration.store') }}" method="post">
            @csrf
            <div class="col-12">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">General Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <x-field type="text" label="Carrier Title" placeholder="Carrier Title"
                                name="xcarrier_title" required="required" />
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Version</label><span class="required">
                                    *</span>
                                <select class="form-control" name="xversion" id="xversion"
                                onchange="change(this.value)">
                                    <option value="0">Select</option>
                                    <option value="1">New(Recommended)</option>
                                    <option value="2">Old</option>
                                    <option value="3">Wallet(Ecom)</option>
                                    <option value="4">Wallet(Franchise)</option>
                                </select>
                            </div>
                            {{-- <div id="new"></div> --}}
                            <div class="form-group col-12" id="new" style="display:none;">
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Shipment Type</label><span class="required">
                                        *</span>
                                    <select class="form-control" name="xnshipment_type" required>
                                        <option value="Forward">Forward</option>
                                        <option value="Reverse">Reverse</option>
                                    </select>
                                </div>
                                <x-field type="text" label="XBKey" placeholder="XBKey" name="xxb_key"
                                    required="required" />
                                <x-field type="text" label="XBKey1" placeholder="XBKey1" name="xxb_key1"
                                    required="required" />
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Account Mode</label><span class="required">
                                        *</span>
                                    <select class="form-control" name="xnaccount_mode" required>
                                        <option value="Live">Live</option>
                                        <option value="Test">Test</option>
                                    </select>
                                </div>
                                <x-field type="text" label="Username" placeholder="Username" name="xusername"
                                    required="required" />
                                <x-field type="text" label="Password" placeholder="Password" name="xpassword"
                                    required="required" />
                                <x-field type="text" label="Secret Key" placeholder="Secret Key"
                                    name="secret_key" required="required" />
                                <x-field type="text" label="Business Account Name" name="b_account_name"
                                    required="required" />
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">URL Type</label><span class="required">
                                        *</span>
                                    <select class="form-control" name="nurl_type" required>
                                        <option value="third_party">Third Party</option>
                                        <option value="default">Default</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Service Type</label><span class="required">
                                        *</span>
                                    <select class="form-control" name="xservice_type" required>
                                        <option value="">Select Service Type</option>
                                        <option value="Standard Service">Standard Service</option>
                                        <option value="Same day Delivery">Same day Delivery</option>
                                        <option value="Next day Delivery">Next day Delivery</option>
                                        <option value="Intracity">Intracity</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-12" id="old" style="display:none;">
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Shipment Type</label><span class="required">
                                        *</span>
                                    <select class="form-control" name="xoshipment_type" required>
                                        <option value="Forward">Forward</option>
                                        <option value="Reverse">Reverse</option>
                                    </select>
                                </div>
                                <x-field type="text" label="XBKey" placeholder="XBKey" name="xb_key"
                                    required="required" />
                                <x-field type="text" label="XBKey1" placeholder="XBKey1" name="xb_key1"
                                    required="required" />
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Account Mode</label><span class="required">
                                        *</span>
                                    <select class="form-control" name="xoaccount_mode" required>
                                        <option value="Live">Live</option>
                                        <option value="Test">Test</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">URL Type</label><span class="required">
                                        *</span>
                                    <select class="form-control" name="ourl_type" required>
                                        <option value="third_party">Third Party</option>
                                        <option value="default">Default</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Service Type</label><span class="required">
                                        *</span>
                                    <select class="form-control" name="xoservice_type" required>
                                        <option value="">Select Service Type</option>
                                        <option value="Standard Service">Standard Service</option>
                                        <option value="Same day Delivery">Same day Delivery</option>
                                        <option value="Next day Delivery">Next day Delivery</option>
                                        <option value="Intracity">Intracity</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-12" id="wallet1" style="display:none;">
                                <x-field type="text" label="Username" placeholder="Username"
                                    name="xwusername" required="required" />
                                <x-field type="text" label="Password" placeholder="Password"
                                    name="xwpassword" required="required" />
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Carrier ID</label><span class="required">
                                        *</span>
                                    <select class="form-control" name="xwaccount_mode" required>
                                        <option value="">Carrier ID</option>
                                        <option value="1">Surface Xpressbees 0.5 K.G</option>
                                        <option value="6">Air Xpressbees 0.5 K.G</option>
                                        <option value="2">Xpressbees 2 K.G</option>
                                        <option value="3">Xpressbees 5 K.G</option>
                                        <option value="4">Xpressbees 10 K.G</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Shipping Label</label><span
                                        class="required">
                                        *</span><br>
                                    <input type="radio" name="xwlabel" value="Shipway ">
                                    <label for="Shipway ">Shipway </label><br>
                                    <input type="radio" name="xwlabel" value="Xpressbees">
                                    <label for="Xpressbees">Xpressbees</label><br>
                                </div>
                            </div>
                            <div class="form-group col-12" id="wallet2" style="display:none;">
                                <x-field type="text" label="Username" placeholder="Username"
                                    name="xw2username" required="required" />
                                <x-field type="text" label="Password" placeholder="Password"
                                    name="xw2password" required="required" />
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Shipping Label</label><span
                                        class="required">
                                        *</span><br>
                                    <input type="radio" name="xw2label" value="Shipway ">
                                    <label for="Shipway ">Shipway </label><br>
                                    <input type="radio" name="xw2label" value="Xpressbees">
                                    <label for="Xpressbees">Xpressbees</label><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn-primary btn h-45 w-100">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function change(value) {
            var newElement = document.getElementById("new");
            var oldElement = document.getElementById("old");
            var wallet1Element = document.getElementById("wallet1");
            var wallet2Element = document.getElementById("wallet2");

            console.log(newElement);
            if (value === '1') {
                newElement.style.display = 'block';
            } else if (value === '2') {
                oldElement.style.display = 'block';
            }else if (value === '3') {
                wallet1Element.style.display = 'block';
            }else if (value === '4') {
                wallet2Element.style.display = 'block';
            }else if (value === '0') {
                newElement.style.display = 'none';
            }
        }

// function myFunction() {
//     let x = document.getElementById("xversion").value;
//     console.log(x);
//     let html = `<div class="form-group col-12" id="new1">
//                                           <h1>hi</h1>
//                                           <div class="form-group col-md-4">
//                                               <label class="form-control-label">Shipment Type</label><span class="required">
//                                                   *</span>
//                                               <select class="form-control" name="xnshipment_type" required>
//                                                   <option value="Forward">Forward</option>
//                                                   <option value="Reverse">Reverse</option>
//                                               </select>
//                                           </div>
//                                           <x-field type="text" label="XBKey" placeholder="XBKey" name="xxb_key"
//                                               required="required" />
//                                           <x-field type="text" label="XBKey1" placeholder="XBKey1" name="xxb_key1"
//                                               required="required" />
//                                           <div class="form-group col-md-4">
//                                               <label class="form-control-label">Account Mode</label><span class="required">
//                                                   *</span>
//                                               <select class="form-control" name="xnaccount_mode" required>
//                                                   <option value="Live">Live</option>
//                                                   <option value="Test">Test</option>
//                                               </select>
//                                           </div>
//                                           <x-field type="text" label="Username" placeholder="Username" name="xusername"
//                                               required="required" />
//                                           <x-field type="text" label="Password" placeholder="Password" name="xpassword"
//                                               required="required" />
//                                           <x-field type="text" label="Secret Key" placeholder="Secret Key"
//                                               name="secret_key" required="required" />
//                                           <x-field type="text" label="Business Account Name" name="b_account_name"
//                                               required="required" />
//                                           <div class="form-group col-md-4">
//                                               <label class="form-control-label">URL Type</label><span class="required">
//                                                   *</span>
//                                               <select class="form-control" name="nurl_type" required>
//                                                   <option value="third_party">Third Party</option>
//                                                   <option value="default">Default</option>
//                                               </select>
//                                           </div>
//                                           <div class="form-group col-md-4">
//                                               <label class="form-control-label">Service Type</label><span class="required">
//                                                   *</span>
//                                               <select class="form-control" name="xservice_type" required>
//                                                   <option value="">Select Service Type</option>
//                                                   <option value="Standard Service">Standard Service</option>
//                                                   <option value="Same day Delivery">Same day Delivery</option>
//                                                   <option value="Next day Delivery">Next day Delivery</option>
//                                                   <option value="Intracity">Intracity</option>
//                                               </select>
//                                           </div>
//                                       </div>`;
//                                       document.getElementById("new").innerHTML = "You selected: " + html;
//                                       console.log(document.getElementById("new").innerHTML = "You selected: " + html);
//   if(x == '0'){
//       alert(x);
//       document.getElementById("new").append(html);
  
//   }
//   }
</script>
     
     
     
      
@endsection