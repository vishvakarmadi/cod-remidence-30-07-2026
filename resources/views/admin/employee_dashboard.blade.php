@php
    $general_setting = DB::table('general_settings')
        ->where('id', 1)
        ->first();
    $leave = DB::table('leaves')
        ->where('status', 0)
        ->get();
    $role = DB::table('roles')
        ->where(
            'id',
            auth()
                ->guard('admin')
                ->user()->role_id,
        )
        ->first();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>{{ ucfirst($role->name) }} Panel</title>

    @include('admin.includes.styles')

    <!-- Favicon -->
    <link href="{{ asset('public/uploads/' . $general_setting->favicon) }}" rel="shortcut icon" type="image/png">


</head>
@php
    $g_setting = DB::table('general_settings')
        ->where('id', 1)
        ->first();
@endphp

@php
    $url = Request::path();
    $conName = explode('/', $url);
    if (!isset($conName[3])) {
        $conName[3] = '';
    }
    if (!isset($conName[2])) {
        $conName[2] = '';
    }
@endphp

<style>
    ::selection {
  color: white;
  background: blue;
}
.display-none {
    display: none !important;
}

#getDataBtn{
    background: #e2e222;
    border: 1px solid #e2e222;
    padding:  10px 20px;
}
.text-center{
    text-align: center;
}
.lds-dual-ring.hidden { 
display: none;
}
.lds-dual-ring {
  display: inline-block;
  width: 80px;
  height: 80px;
}
.lds-dual-ring:after {
  content: " ";
  display: block;
  width: 64px;
  height: 64px;
  margin: 23% auto;
  border-radius: 50%;
  border: 6px solid #fff;
  border-color: #fff transparent #fff transparent;
  animation: lds-dual-ring 1.2s linear infinite;
}
@keyframes lds-dual-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}


.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: #000000f0;
    z-index: 999;
    opacity: 1;
    transition: all 0.5s;
}
</style>
<body class="theme-blue">
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img src="{{ asset('public/uploads/' . $general_setting->preloader_photo) }}"
                    width="48" height="48" alt="Hyloship"></div>
            <p>Please wait...</p>
        </div>
    </div>


    <!-- Overlay For Sidebars -->
    <div id="loader" class="lds-dual-ring hidden overlay"></div>
    <div id="wrapper">

        <nav class="navbar navbar-fixed-top">
            <div class="container-fluid">

                <div class="navbar-brand">
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('public/uploads/' . $general_setting->favicon) }}" alt="Hyloship Logo"
                            class="img-responsive logo" style="margin-top:-8px !important;width:39px;">
                        <span class="name" style="font-family: fantasy;
                        font-size: 25px;">Hyloship</span>
                    </a>
                </div>

                <style>
                    .preview {
                        width: 100%;
                        height: auto;
                        border: 2px dashed #ddd;
                        border-radius: 3px;
                        cursor: pointer;
                        text-align: center;
                        overflow: hidden;
                        padding: 5px;
                        margin-top: 5px;
                        margin-bottom: 5px;
                        position: relative;
                        display: flex;
                        align-items: center;
                        margin: auto;
                        justify-content: center;
                        flex-direction: column;
                    }

                    .search-list {
                        position: absolute;
                        top: 100%;
                        background-color: #fff;
                        width: 100%;
                        z-index: 99;
                        max-height: 310px;
                        overflow: auto;
                        border-radius: 0 0 5px 5px;
                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                    }

                    .search-list li {
                        padding: 8px 8px 8px 0px;
                        border-bottom: 1px solid #e5e5e5;
                        list-style-type: none;
                    }
                </style>

                <div class="navbar-right">
                    <ul class="list-unstyled clearfix mb-0">
                    @if (Auth::guard('admin')->user()->terms_condition_accept == 1)
                        <li>
                            <div class="navbar-btn btn-toggle-show">
                                <button type="button" class="btn-toggle-offcanvas"><i
                                        class="lnr lnr-menu fa fa-bars"></i></button>
                            </div>
                            <a href="javascript:void(0);" class="btn-toggle-fullwidth btn-toggle-hide"><i
                                    class="fa fa-bars"></i></a>
                        </li>
                        <li>
                            <form id="navbar-search" class="navbar-form search-form">
                                <input value="" class="form-control navbar-search-field"
                                    placeholder="Search here..." type="search" autocomplete="off">
                                <ul class="search-list"></ul>
                            </form>
                        </li>
                        <li>
                            <div id="navbar-menu">
                                <ul class="nav navbar-nav">
                                    <li style="margin-right: 40px"><a href="{{ route('admin.payment.wallet') }}" style="@if(Auth::guard('admin')->user()->wallet_blc < 10) color: #f00;  @else color:#00bd2a; @endif"><i class="fa fa-google-wallet" aria-hidden="true"></i><span style="margin-left:10px;">Rs. {{ Auth::guard('admin')->user()->wallet_blc }}.00</span></a></li>

                                    <li>
                                    <a href="{{ route('admin.payment.wallet') }}" class="btn btn-secondary assign" title="Recharge Wallet"
                                                style="width:87px;" "><span
                                                    class="sr-only">Recharge</span> <i class="fa fa-bolt" style="margin-right: 2px "></i>Recharge</a>
                                    </li>
                                    <li class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle icon-menu"
                                            data-toggle="dropdown"><i class="fa fa-language"></i></a>
                                        <ul class="dropdown-menu animated flipInX choose_language">
                                            <li><a href="javascript:void(0);">English</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle icon-menu"
                                            data-toggle="dropdown">
                                            <img class="rounded-circle"
                                                src="{{ Auth::guard('admin')->user()->photo != null ? asset('public/uploads/' . Auth::guard('admin')->user()->photo) : asset('public/uploads/avatar.png') }}"
                                                width="30" height="30" alt="">
                                        </a>
                                        <div class="dropdown-menu animated flipInY user-profile">
                                            <div class="d-flex p-3 align-items-center">
                                                <div class="drop-left m-r-10">
                                                    <img src="{{ Auth::guard('admin')->user()->photo != null ? asset('public/uploads/' . Auth::guard('admin')->user()->photo) : asset('public/uploads/avatar.png') }}"
                                                        class="rounded" width="50" alt="">
                                                </div>
                                                <div class="drop-right">
                                                    <h4>{{ Auth::guard('admin')->user()->name }}</h4>
                                                    <p class="user-name">{{ Auth::guard('admin')->user()->email }}</p>
                                                </div>
                                            </div>
                                            <div class="m-t-10 p-3 drop-list">
                                                <ul class="list-unstyled">
                                                    <li><a href="{{ route('admin.profile_change') }}"><i
                                                                class="icon-user"></i>My Profile</a></li>
                                                    <li><a href="{{ route('admin.password_change') }}"><i
                                                                class="icon-envelope-open"></i>Password</a></li>
                                                    <li><a href="{{ route('admin.photo_change') }}"><i
                                                                class="icon-settings"></i>Photo</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="{{ route('admin.logout') }}"><i
                                                                class="icon-power"></i>Logout</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.settings') }}" class="icon-menu js-right-sidebar"><i
                                                class="icon-settings"></i></a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </li>
                        @else
                        <li style="margin-right:50px"><a href="{{ route('admin.logout') }}"><i
                                                                class="icon-power"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>








        <div id="leftsidebar" class="sidebar">
            <div class="sidebar-scroll">
                <nav id="leftsidebar-nav" class="sidebar-nav">
                    <ul id="main-menu" class="metismenu">
                        <li class="heading">Main</li>
                        @if (Auth::guard('admin')->user()->role_id == 1)
                            <li class=""><a class="menu" href="{{ route('admin.dashboard') }}"><i
                                        class="fa fa-tachometer"></i><span>Dashboard</span></a></li>
                        @else
                            <li class=""><a class="menu" href="{{ route('employee.dashboard') }}"><i
                                        class="fa fa-tachometer"></i><span>Dashboard</span></a></li>
                        @endif
                        @if (Auth::guard('admin')->user()->terms_condition_accept == 1)
                        <li class="middle sidebar-menu-item sidebar-dropdown">
                            <a href="javascript:void" class="has-arrow"><i class="icon-check"></i><span
                                    class="menu-title">Orders</span></a>
                            <ul>
                                <li><a class="menu" href="{{ route('admin.order.all') }}">All Orders</a></li>
                                <li><a class="menu" href="{{ route('admin.order.index') }}">New Orders</a></li>
                                <li><a class="menu" href="{{ route('admin.bulkorder.create') }}">Bulk Order Import</a></li>
                                <li><a class="menu" href="{{ route('admin.order.onholdpage') }}">On Hold Orders</a></li>
                                <!--<li><a class="menu" href="{{ route('admin.order.rto_order') }}">RTO Orders</a></li>-->
                                <li><a class="menu" href="{{ route('admin.order.sla_order') }}">SLA Orders</a></li>
                                <li><a class="menu" href="{{ route('admin.order.unfulfilled') }}">Unfulfillment Order</a></li>
                            </ul>
                        </li>

                        <li class="middle sidebar-menu-item sidebar-dropdown">
                            <a href="javascript:void" class="has-arrow"><i class="icon-pointer"></i><span
                                    class="menu-title">Shipment</span></a>
                            <ul>
                                <li><a class="menu" href="{{ route('admin.order.shipped_order') }}">Shipped Order</a></li>
                                <li><a class="menu" href="{{ route('admin.order.manifest') }}">Manifest</a></li>
                                <!--<li><a class="menu" href="javascript:void">Notifications</a></li>-->
                            </ul>
                        </li>

                        <li class="middle sidebar-menu-item sidebar-dropdown">
                            <a href="javascript:void" class="has-arrow"><i class="icon-share-alt"></i><span
                                    class="menu-title">Returns</span></a>
                            <ul>
                                <li><a class="menu" href="{{ route('admin.order.return') }}">View Orders</a></li>
                                <li><a class="menu" href="{{ route('admin.order.rto_order') }}">RTO Orders</a></li>
                                <!--<li><a class="menu" href="javascript:void">Pickup Failed Report</a></li>-->
                                <!--<li><a class="menu" href="{{ route('admin.integration.index') }}">Integrations</a></li>-->
                                <!--<li><a class="menu" href="{{ route('admin.order.refundpage') }}">Refunds</a></li>-->
                            </ul>
                        </li>

                        <li class=""><a class="menu" href="{{ route('admin.ndr.ndr') }}"><i
                                    class="icon-briefcase"></i><span>NDR</span></a></li>


                        <li class="middle sidebar-menu-item sidebar-dropdown">
                            <a href="javascript:void" class="has-arrow"><i class="fa fa-truck"></i><span
                                    class="menu-title">Payments</span></a>
                            <ul>
                        <!--        <li><a class="menu" href="{{ route('admin.payment.wallet') }}">Wallet Recharge</a></li> -->
                                <li><a class="menu" href="{{ route('admin.coupon.index') }}">Coupon</a></li>
                                <li><a class="menu" href="{{ route('cod') }}">COD Remittance</a></li>
                                <li><a class="menu" href="{{ route('admin.rate') }}">Rate Card</a></li>
                                <li class="middle sidebar-menu-item sidebar-submenu-dropdown">
                                    <a class="has-arrow" href="javascript:void"><span
                                            class="menu-title">Billing</span></a>
                                    <ul class="sub">
                                        <li><a class="menu" href="{{ route('admin.billing.billing_info') }}">Shipping charges</a></li>
                                        <li><a class="menu" href="javascript:void">Weight Reconciliation</a></li>
                                        <li><a class="menu" href="javascript:void">Invoices</a></li>
                                        <li><a class="menu" href="javascript:void">Wallet Transaction</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                      	@if (Auth::guard('admin')->user()->role_id =='1')
                      	<li class=""><a class="menu" href="{{ route('admin.integration.index') }}"><i
                                    class="icon-briefcase"></i><span>Integration</span></a></li>
                      	@else
                     	 <li class=""><a class="menu" href="{{ route('admin.integration.channel') }}"><i
                                    class="icon-briefcase"></i><span>Integration</span></a></li>
                      	@endif
                        <li class="middle sidebar-menu-item sidebar-dropdown">
                            <a href="javascript:void" class="has-arrow"><i class="fa fa-codepen"></i><span
                                    class="menu-title">Courier</span></a>
                            <ul>
                            @if (Auth::guard('admin')->user()->role_id =='1')
                                <li><a class="menu" href="{{ route('admin.integration.manage_courier') }}">Manage Courier</a></li>
                            @endif    
                                <li><a class="menu" href="{{ route('admin.integration.courier_priority') }}">Courier Priority</a></li>
                            </ul>        
                        </li>
                        <li class=""><a class="menu" href="{{ route('admin.warehouse.list') }}"><i
                                    class="icon-home"></i><span>Manage Warehouse</span></a></li>
                        <li class=""><a class="menu" href="{{ route('admin.kyc') }}"><i
                                    class="fa fa-book"></i><span>KYC</span></a></li>

                        <!--<li class="middle sidebar-menu-item sidebar-dropdown">-->
                        <!--    <a href="javascript:void" class="has-arrow"><i class="icon-bell"></i><span-->
                        <!--            class="menu-title">Notifications</span></a>-->
                        <!--    <ul>-->
                        <!--        <li><a class="menu" href="javascript:void">Manage Templates</a></li>-->
                        <!--        <li><a class="menu" href="javascript:void">Pricing</a></li>-->
                        <!--        <li><a class="menu" href="{{ route('admin.add.balance') }}">Add Balance</a></li>-->
                        <!--        <li><a class="menu" href="javascript:void">Payment History</a></li>-->
                        <!--        <li><a class="menu" href="javascript:void">History</a></li>-->
                        <!--    </ul>-->
                        <!--</li>-->

                        @if (Auth::guard('admin')->user()->role_id =='1')
                            <li class=""><a class="menu" class=""
                                    href="{{ route('admin.role.user') }}"><i class="fa fa-users"></i> <span
                                        class="menu-title">Users</span></a></li>
                        @endif

                        @if (Auth::guard('admin')->user()->hasPermissionTo('list role'))
                            <li class=""><a class="menu" href="{{ route('admin.role.index') }}"><i
                                        class="fa fa-user-secret"></i> <span class="menu-title">Roles &
                                        Permission</span></a></li>
                        @endif

                        @if (Auth::guard('admin')->user()->role_id =='1')
                            <li class="middle sidebar-menu-item sidebar-dropdown">
                                <a href="javascript:void" class="has-arrow"><i class="icon-diamond"></i><span
                                        class="menu-title">Component</span></a>
                                <ul>
                                    <li><a class="menu" href="{{ route('admin.general_setting.logo') }}">Logo</a>
                                    </li>
                                    <li><a class="menu"
                                            href="{{ route('admin.general_setting.favicon') }}">Favicon</a></li>
                                    <li><a class="menu"
                                            href="{{ route('admin.general_setting.preloader') }}">Pre-loader</a></li>
                                    <li><a class="menu" href="{{ route('admin.general_setting.color') }}">Theme
                                            Color</a></li>
                                </ul>
                            </li>
                        @endif



                        @if (Auth::guard('admin')->user()->hasPermissionTo('edit setting'))
                            <li class=""><a class="menu" href="{{ route('admin.settings') }}"><i
                                        class="icon-settings"></i><span>Settings</span></a></li>
                                        
                        @endif
                        <li class="middle sidebar-menu-item sidebar-dropdown">
                            <a href="javascript:void" class="has-arrow"><i class="fa fa-file"></i><span
                                    class="menu-title">Reports</span></a>
                            <ul>
                                
                                <li><a class="menu" href="{{route('admin.reports.view') }}">Order Report</a></li>
                                <li><a class="menu" href="{{route('admin.reports.mis') }}">MIS Report</a></li>
                                
                               <li><a class="menu" href="{{ route('admin.order.shipment_report') }}">Current Shipment Reports</a></li>
                           </ul>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>




        <div id="main-content">
            <div class="container-fluid">
                @yield('admin_content')
            </div>
        </div>
    </div>

    @include('admin.includes.scripts-footer')

</body>
<script>
    $(document).ready(function() {

        $(document).on('input','.maxlenght',function(){
            let maxLength = $(this).attr('max');
            var value = $(this).val();
            if (value.length > maxLength) {
                $(this).val(value.slice(0, maxLength));
            }
        });


        $('.toggle-icon').click(function() {
            let i = $(this).closest('.card').find('i');
            if (i.attr("class") == 'fa fa-minus') {
                i.removeClass('fa-minus').addClass('fa-plus');
            } else {
                i.removeClass('fa-plus').addClass('fa-minus');
            }
            $(this).closest('.card').find('.card-body').slideToggle();
        });

        //tab Script
        var tabsNewAnim = $('#navbar-animmenu');
        var selectorNewAnim = $('#navbar-animmenu').find('li').length;
        var activeItemNewAnim = tabsNewAnim.find('.active');
        var activeWidthNewAnimWidth = activeItemNewAnim.innerWidth();
        var itemPosNewAnimLeft = activeItemNewAnim.position();
        $(".hori-selector").css({
            "left":itemPosNewAnimLeft.left + "px",
            "width": activeWidthNewAnimWidth + "px"
        });
        $("#navbar-animmenu").on("click", "li", function(e) {
            $('#navbar-animmenu ul li').removeClass("active");
            $(this).addClass('active');
            var get = $(this).find('a').data('id');
            $('.card').addClass('hide');
            $('.' + get).removeClass('hide');
            var activeWidthNewAnimWidth = $(this).innerWidth();
            var itemPosNewAnimLeft = $(this).position();
            $(".hori-selector").css({
                "left": itemPosNewAnimLeft.left + "px",
                "width": activeWidthNewAnimWidth + "px"
            });
        });
    });


    $(document).ready(function() {
        let links = document.querySelectorAll('.sidebar-nav li a.menu');
        let currenturl = '{{ url('/') }}' + window.location.pathname;
        links.forEach(link => {
            let path = link.getAttribute('href');
            if (path === currenturl) {
                let li = link.closest('li.sidebar-dropdown');
                let ul = link.closest('ul.collapse');
                let sub_li = link.closest('li.sidebar-submenu-dropdown');
                let sub_ul = link.closest('ul.sub');
                if (sub_li) {
                    sub_ul.classList.add('in');
                    link.classList.add('active');
                    li.classList.add('active');
                    sub_li.closest('ul.collapse').classList.add('in');
                } else if (li) {
                    li.classList.add('active');
                    ul.classList.add('in');
                    link.classList.add('active');
                } else {
                    link.closest('li').classList.add('active');
                }
            } else {
                link.classList.remove('active');
            }
        });
    });

    function image(name,id) {
        document.getElementById(name +'_image_'+ id).click();
    }

    function preview_Image(name,id) {
        const input = document.getElementById(name +'_image_'+ id);
        const imagePreview = document.getElementById(name +'_image_preview_'+ id);
        const imageContainer = document.getElementById(name +'_image_container_'+ id);
        const allowedExtensions = ['jpeg', 'jpg', 'png', 'gif'];
        const maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileExtension = file.name.split('.').pop().toLowerCase();

            if (allowedExtensions.includes(fileExtension) && file.size <= maxFileSize) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
                imageContainer.style.display = 'block';
            } else {
                toastr.error('Invalid file type or file size exceeds 2MB');
                input.value = '';
                imageContainer.style.display = 'block';
            }
        }
    }

    var numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(function(input) {
      input.addEventListener('change', function() {
        if (parseInt(input.value) < 0) {
          input.value = 0;
        }
      });

      input.addEventListener('wheel', function(event) {
                if (document.activeElement === this) {
                    event.preventDefault();
                }
            });
    });

    document.addEventListener('DOMContentLoaded', function () {
            var phoneInputs = document.querySelectorAll('.phone-number');
            phoneInputs.forEach(function (input) {
                input.addEventListener('input', function () {
                    // Remove non-numeric characters
                    var phoneNumber = this.value.replace(/\D/g, '');
                    if (phoneNumber.length > 10) {
                        this.value = phoneNumber.slice(0, 10);
                    }
                });
            });
        });

    

</script>

</html>
