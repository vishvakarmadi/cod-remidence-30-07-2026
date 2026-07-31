@extends('admin.admin_layouts')
@section('admin_content')
<style>
.card {
    height: 170px;
}
.pad{
    padding:25px;
}
i::before{
    padding:5px;
}
.parent {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    /* grid-template-rows: repeat(3, 1fr); */
    grid-column-gap: 20px;
    grid-row-gap: 10px;
    line-height: 150%;
}

.div1 {
    grid-area: 1 / 1 / 2 / 2;
}

.div2 {
    grid-area: 1 / 2 / 2 / 3;
}

.div3 {
    grid-area: 1 / 3 / 2 / 4;
}

.div4 {
    grid-area: 1 / 4 / 2 / 5;
}

.div5 {
    grid-area: 2 / 1 / 3 / 2;
}

.div6 {
    grid-area: 2 / 2 / 3 / 3;
}

.div7 {
    grid-area: 2 / 3 / 3 / 4;
}

.div8 {
    grid-area: 2 / 4 / 3 / 5;
}

.div9 {
    grid-area: 3 / 1 / 4 / 2;
}
#contraband-content ul {
    column-count: 2;
    column-gap: 40px;
}

#contraband-content li {
    break-inside: avoid;
    padding: 4px 0;
}
</style>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>


<div class="container-fluid">
    <!-- Page header section  -->
    <!-- <div class="block-header">
        <div class="row clearfix">
            <div class="col-xl-6 col-md-5 col-sm-12">
                <h1>Hi, Welcomeback!</h1>
                <span>JustDo Settings,</span>
            </div>
            <div class="col-xl-6 col-md-7 col-sm-12 text-md-right">
                <div class="d-flex align-items-center justify-content-between flex-wrap vivify pullUp delay-550">
                    <div class="ml-auto mb-3 mb-xl-0">
                        <p class="text-muted mb-1">Date</p>
                        <h5 class="mb-0">{{ date('Y-m-d') }}</h5>
                    </div>
                    <div class="ml-auto mb-3 mb-xl-0">
                        <p class="text-muted mb-1">Time</p>
                        <h5 class="mb-0">{{ date('H:i:s') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="card" style="box-shadow: 0 0 1.6rem 0 rgba(2, 16, 40, .08);border-radius: 1.8rem;height: unset;color: red;"><div class="card-body">
            Dear Client,  <br>Greetings from Hyloship !  <br><br>This is in regards to "<b><a href="javascript:void(0)" class="contraband-link">Contraband Items</a></b>", Please  be informed that if we come across any  contraband items in shipments at any hub or in  transit, we would be informing the police/customs  officers and handing over the shipments  
            <br><b>Please Note </b>- The merchant will be solely  responsible for any actions taken by the  authorities, penalties, charges, or FIRs filed by the  authorities.  <br>
            Additionally, if directed by any authority to  destroy the shipments, we would be complying  without notifying the merchant 
        </div></div>
    <div class="row parent" style="margin:0">
        <!-- <div class="col"> -->
            <div class="div1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="m-0 mt-2 font-weight-bold text-black invoice-heading"><i class="fa fa-user" style="font-size: 0.73em;"></i>Account</h5>
                        <a  href="{{ route('admin.profile_change') }}">Profile</a><br>
                        <a  href="{{ route('admin.kyc') }}">KYC</a><br>
                        @if(Auth::guard('admin')->user()->role_id =='1')
                        <a  href="{{ route('admin.role.logs') }}">Logs</a><br>
                        @endif
                        <a  href="{{ route('admin.dashboard.tc_contract') }}">Terms & Condition</a> accepted at {{Auth::guard('admin')->user()->tc_accepted_at}}
                        @if(Auth::guard('admin')->user()->tc_accepted_ipaddress) ({{Auth::guard('admin')->user()->tc_accepted_ipaddress}}) @endif
                    </div>
                </div>
            </div>
            <div class="div2">
                <div class="card">
                    <div class="card-body">
                        <h5><i class="fi fi-rr-network" style="font-size: 0.73em;"></i>Integrations</h5>
                        <a  href="{{ route('admin.integration.channel') }}">Channel Integrations</a><br>
                        <!--<a  href="{{ route('admin.integration.index') }}">3rd Party Integrations</a>-->
                    </div>
                </div>
            </div>
            <div class="div3">
                <div class="card">
                    <div class="card-body">
                        <h5><i class="fa fa-truck" style="font-size: 0.73em;"></i>Carrier</h5>
                        <a  href="{{ route('admin.integration.manage_courier') }}">Manage Couriers</a><br>
                        <a  href="{{ route('admin.pin.create') }}">Manage Serviceable Pincodes</a><br>
                        {{-- <a  href="javascript:void">Upload Tracking Number</a><br> --}}
                        {{-- <a  href="javascript:void">Auto Assign Rule </a> --}}
                    </div>
                </div>
            </div>
            <div class="div4">
                <div class="card">
                    <div class="card-body">
                        <h5><i class="fa fa-home" style="font-size: 0.73em;"></i>Manage Warehouse</h5>
                        <a  href="{{ route('admin.warehouse.list') }}">Warehouse Address</a>
                    </div>
                </div>
            </div>
            <div class="div5">
                <div class="card">
                    <div class="card-body">
                        <h5><i class="fa fa-users" style="font-size: 0.73em;"></i>Staff Management</h5>
                        <a  href="{{ route('admin.role.user') }}">Staff Accounts</a>
                    </div>
                </div>
            </div>
<!--            <div class="div6">
                <div class="card">
                    <div class="card-body">
                        <h5><i class="fa fa-users" style="font-size: 0.73em;"></i>Web Site Design</h5>
                        <span class="add-btn"style='color: #4183c4; text-decoration: none;cursor: pointer;'>Redesigning website</span>
                    </div>
                </div>
            </div>-->
             <div class="div6">
                <div class="card">
                    <div class="card-body">
                        <h5><i class="fa fa-print" style="font-size: 0.73em;"></i>Label Settings</h5>
                        <a href="{{ route('admin.settings.labalsetting') }}">Label Settings</a><br>
                    </div>
                </div>
            </div>
            @if (Auth::guard('admin')->user()->role_id =='1')
            <div class="div7">
                <div class="card">
                    <div class="card-body">
                        <h5><i class="fa fa-envelope-o" style="font-size: 0.73em;"></i>Broadcast</h5>
                        <a class="" href="{{ route('admin.broadcast.new') }}">Add New</a><br>
                        <a class="" href="{{ route('admin.broadcast') }}">View list</a><br>
                    </div>
                </div>
            </div>
            @endif
            <div class="div8">
                <div class="card">
                    <div class="card-body">
<!--                        <i class="fa fa-envelope-o" style="font-size: 0.73em;"></i><a class="" href="{{ route('admin.settings.sop') }}">Operational SOP </a><br>
                        <i class="fa fa-envelope-o" style="font-size: 0.73em;"></i><a class="" href="{{ route('admin.settings.tat') }}">TAT for Forward-RTO-POD </a><br>-->
                        <i class="fa fa-envelope-o" style="font-size: 0.73em;"></i><a class="" href="{{ route('admin.settings.ticket') }}">Raise Ticket </a>
                    
                    </div>
                </div>
            </div>
        
    <!-- </div> -->
    </div>
</div>
<div id="my-modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document" style="max-width: 1000px;top:20%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" class="myform">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="modal-body">
                            <div class="popup">
                                <h2>Special Offer: Website Redesign</h2>
                                <ul>
                                    <li> If you redesign your website with us for ₹15,000, you'll receive ₹1,000 in your wallet to use towards shipping your products.</li>
                                    <li> Redesign completion within one day!</li>
                                </ul>

                                <div class="form-group">
                                    <label for="website">Your company website URL:</label>
                                    <input  class="form-control" type="text" id="website" name="website" required>
                                </div> 
                                <div>
                                <label for="email" >Confirm your email ID:</label>
                                <input  class="form-control" type="email" id="email" name="email" placeholder="you@example.com" required>
                                </div>  
                                <div class="form-group">
                                <label>Platform</label>
                                <select name="Platform" class="form-control" required>
                                    <option> Select Platform </option>
                                    <option value="Shopify">Shopify</option>
                                </select>
                                 </div>
                                <br>

                                <p>Questions? Email us at <a href="mailto:hello@hyloship.com">hello@hyloship.com</a></p>
                        </div>

                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary h-45 w-100">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
    <div id="contraband-content" style="display:none">
    <h5>🚫 Prohibited / Contraband Items</h5>
        <div class="row">
        <div class="col-md-6">
            <ul>
                <li>Explosives, fireworks, crackers</li>
                <li>Firearms, ammunition, weapons</li>
                <li>Narcotic drugs, psychotropic substances</li>
                <li>Alcohol, liquor, tobacco products</li>
                <li>Cash, currency notes</li>
                <li>Gold, silver, precious stones</li>
                <li>Hazardous chemicals</li>
                <li>Gas cylinders</li>
            </ul>
        </div>
        <div class="col-md-6">
            <ul>
                <li>Live animals, birds, insects</li>
                <li>Animal skins, ivory, fur</li>
                <li>Pornographic material</li>
                <li>Counterfeit goods</li>
                <li>SIM cards, spy devices</li>
                <li>Perishable food items</li>
                <li>Medical waste</li>
                <li>Human organs</li>
            </ul>
        </div>
    </div>


    <p style="color:red;font-weight:bold;">
        Any shipment containing contraband items will be handed over to
        police/customs authorities. Merchant will be solely responsible for
        legal consequences.
    </p>
</div>

</div>
<script>
                    "use strict";
                    (function($) {
                        $(document).ready(function() {
                            let myModal = new bootstrap.Modal(document.getElementById('my-modal'));
                            let action = `{{ route('admin.settings.mail') }}`

                            $('.add-btn').on('click', function(e) {

                                $('.modal-title').text("@lang('Redesigning website')");
                                $('.myform').trigger("reset");
                                $('.myform').attr("action", action);
                                // Ensure the modal is initialized as a Bootstrap modal
                                myModal.show();
                            });

                           $('.contraband-link').on('click', function () {
                                $('.modal-title').text('Contraband Items Policy');
                                $('.modal-body').html($('#contraband-content').html());
                                $('.modal-footer').hide();
                                myModal.show();
                            }); 

                        });
                    })(jQuery);

                </script>
@endsection