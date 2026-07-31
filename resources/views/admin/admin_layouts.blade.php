@php
    $user_data = auth()->guard('admin')->user();
    $general_setting = DB::table('general_settings')
        ->where('company_id', $user_data->company_id)
        ->first();
// echo '<pre>';print_R($user_data->company_id);die;
    $role = DB::table('roles')
        ->where(
            'id',
            $user_data->role_id,
        )
        ->first();
        $userId = $user_data->id;
        $today = date('Y-m-d'); // Assuming the date format in your database is 'Y-m-d'
        $broadcast = DB::table('broadcasts')
        ->whereRaw("FIND_IN_SET(?, user_id) > 0", [$userId])
        ->where('active','1')
         ->where(function($query) use ($today) {
        $query->whereDate('from_date', '<=', $today)
              ->whereDate('to_date', '>=', $today);
    })
        ->first();
$broadcast =true
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>
        @if(isset($general_setting))
                    {{$general_setting->name}}
        @else
            HYLO
        @endif
    </title>

    @include('admin.includes.styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    @php
        if(isset($general_setting)){
    @endphp
        <link href="{{ asset('public/uploads/' . $general_setting->favicon) }}" rel="shortcut icon" type="image/png">
    @php
        }
    @endphp

</head>
@php
    $g_setting = $general_setting;
    // echo '<pre>';print_R($g_setting);die;
    if(!isset($general_setting)) {
        $g_setting->theme_color = '#000';
    }
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

<style>/* ================= GLOBAL THEME ================= */
::selection{ color:#fff; background: {{$g_setting->theme_color}}; }
option:checked{ background: {{$g_setting->theme_color}} !important; color: #fff; }
option:hover{ background: {{$g_setting->theme_color}} !important; }
.display-none { display: none !important; }
#getDataBtn{ background: #e2e222; border: 1px solid #e2e222; padding: 10px 20px; }
body{ margin:0; padding:0; font-family: 'Segoe UI', sans-serif; }
thead{ background: {{$g_setting->theme_color}} !important; color:#fff; }
thead a{ color: #fff; text-decoration: underline; }
tbody a{ color: #000; text-decoration: underline; }
.btn-success{ background: {{$g_setting->theme_color}} !important; border:none; }

/* ================= TOP MARQUEE ================= */
.fixed-marquee{ position:fixed; top:0; left:0; width:100%; height:50px; background:#fff; display:flex; align-items:center; z-index:1060; box-shadow:0 2px 6px rgba(0,0,0,.1); overflow:hidden; white-space:nowrap; }
.marquee-wrapper{ display:inline-flex; animation:marquee 25s linear infinite; }
@keyframes marquee{ 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }

/* ================= CUT-OFF MARQUEE ================= */
.cutoff-marquee{ position:fixed; top:50px; left:0; width:100%; z-index:1055; background:#fff3cd; color:#000; font-size:13px; font-weight:500; padding:8px 0; overflow:hidden; white-space:nowrap; border:1px solid #ffeeba; }
.cutoff-marquee-content{ display:inline-block; padding-left:100%; animation:cutoff-scroll 30s linear infinite; }
@keyframes cutoff-scroll{ 0%{transform:translateX(0)} 100%{transform:translateX(-100%)} }

/* ================= TOP HEADER BAR ================= */
.top-header{ position:fixed; top:50px; left:0; right:0; height:56px; background:{{$g_setting->theme_color}}; z-index:1045; display:flex; align-items:center; padding:0 20px; box-shadow:0 2px 8px rgba(0,0,0,.15); }
.has-second-marquee .top-header{ top:90px; }
.hamburger-btn{ background:none; border:none; color:#fff; font-size:22px; cursor:pointer; padding:8px; margin-right:15px; display:flex; align-items:center; }
.hamburger-btn:hover{ opacity:.8; }
.top-header .company-logo{ height:34px; object-fit:contain; }
.top-header-right{ margin-left:auto; display:flex; align-items:center; gap:14px; }
.user-btn{ display:flex; align-items:center; gap:8px; color:#fff !important; text-decoration:none; font-size:14px; }
.wallet-pill{ background:rgba(255,255,255,.18); padding:6px 14px; border-radius:8px; color:#fff; font-weight:600; font-size:13px; text-decoration:none; }
.wallet-pill:hover{ background:rgba(255,255,255,.3); color:#fff; text-decoration:none; }

/* Top header dropdown */
.top-header .dropdown-menu{ border-radius:12px; padding:10px 0; min-width:200px; box-shadow:0 8px 25px rgba(0,0,0,.2); border:none; margin-top:10px; }
.top-header .dropdown-menu .dropdown-header{ color:#999; font-size:12px; font-weight:600; padding:8px 18px; text-transform:uppercase; letter-spacing:.5px; }
.top-header .dropdown-menu > li > a{ padding:10px 18px !important; font-size:14px; color:#444 !important; transition:all .2s ease; display:block; }
.top-header .dropdown-menu > li > a:hover{ background:{{$g_setting->theme_color}}; color:#fff !important; padding-left:22px !important; }
.top-header .dropdown-menu .divider{ margin:6px 0; border-top:1px solid #eee; }

/* ================= LEFT SIDEBAR ================= */
.left-sidebar{ position:fixed; top:106px; left:0; bottom:0; width:260px; background:linear-gradient(180deg, {{$g_setting->theme_color}} 0%, color-mix(in srgb, {{$g_setting->theme_color}} 70%, #111) 100%); z-index:1040; overflow-y:auto; overflow-x:hidden; transition:width .3s cubic-bezier(.4,0,.2,1); box-shadow:4px 0 20px rgba(0,0,0,.2); }
.has-second-marquee .left-sidebar{ top:146px; }
.left-sidebar::-webkit-scrollbar{ width:4px; }
.left-sidebar::-webkit-scrollbar-track{ background:transparent; }
.left-sidebar::-webkit-scrollbar-thumb{ background:rgba(255,255,255,.12); border-radius:4px; }
.left-sidebar::-webkit-scrollbar-thumb:hover{ background:rgba(255,255,255,.2); }

/* Sidebar menu */
.sidebar-menu{ list-style:none; margin:0; padding:12px 10px; }
.sidebar-menu li{ position:relative; margin-bottom:2px; }
.sidebar-menu > li > a{ display:flex; align-items:center; padding:11px 16px; color:rgba(255,255,255,.55) !important; font-size:14.5px; font-weight:500; text-decoration:none !important; transition:all .25s cubic-bezier(.4,0,.2,1); border-radius:10px; gap:12px; white-space:nowrap; overflow:hidden; letter-spacing:.2px; }
.sidebar-menu > li > a i.menu-icon{ width:20px; min-width:20px; text-align:center; font-size:15px; line-height:1; transition:all .25s ease; }
.sidebar-menu > li > a .caret-icon{ margin-left:auto; font-size:10px; transition:transform .3s cubic-bezier(.4,0,.2,1); min-width:10px; opacity:.5; }
.sidebar-menu > li > a .menu-text{ transition:opacity .2s ease; }
.sidebar-menu > li > a:hover{ background:rgba(255,255,255,.06); color:rgba(255,255,255,.9) !important; }
.sidebar-menu > li > a:hover i.menu-icon{ color:#fff; }
.sidebar-menu > li.active > a{ background:rgba(255,255,255,.22) !important; color:#fff !important; font-weight:600; box-shadow:0 2px 10px rgba(0,0,0,.12); }
.sidebar-menu > li.active > a i.menu-icon{ color:#fff !important; }
.sidebar-menu > li.active > a .caret-icon{ opacity:1; }
.sidebar-menu > li.open > a{ background:rgba(255,255,255,.06); color:rgba(255,255,255,.85) !important; }

/* Sub-menu */
.sidebar-submenu{ list-style:none; padding:4px 0 4px 0; margin:0; display:none; overflow:hidden; transition:all .25s ease; }
.sidebar-submenu li{ margin-bottom:0; }
.sidebar-submenu li a{ display:flex; align-items:center; padding:9px 16px 9px 48px; color:rgba(255,255,255,.75) !important; font-size:14px; font-weight:500; text-decoration:none !important; transition:all .2s ease; white-space:nowrap; position:relative; border-radius:8px; margin:0 10px; }
.sidebar-submenu li a::before{ content:''; width:5px; height:5px; border-radius:50%; background:rgba(255,255,255,.2); position:absolute; left:28px; top:50%; transform:translateY(-50%); transition:all .25s ease; }
.sidebar-submenu li a:hover{ color:rgba(255,255,255,.9) !important; background:rgba(255,255,255,.04); }
.sidebar-submenu li a:hover::before{ background:#fff; box-shadow:0 0 6px rgba(255,255,255,.5); }
.sidebar-menu > li.open > .sidebar-submenu{ display:block; }
.sidebar-menu > li.open > a .caret-icon{ transform:rotate(90deg); opacity:.8; }

/* Sidebar section label */
.sidebar-label{ padding:20px 16px 8px; font-size:10px; text-transform:uppercase; letter-spacing:1.8px; color:rgba(255,255,255,.2); font-weight:700; }

/* ================= MAIN CONTENT ================= */
#leftsidebar{ display:none !important; }
#main-content{ margin-left:260px !important; width:auto !important; transition:margin-left .3s cubic-bezier(.4,0,.2,1); min-height:100vh; margin-top:0 !important; float:none !important; padding:20px 15px 0 15px !important; }
#wrapper{ padding-top:106px; }
.has-second-marquee #wrapper{ padding-top:146px; }
.modal{ top:60px !important; }

/* Content spacing - row clearfix gap from header */
.container-fluid > .block-header:first-child,
.container-fluid > .row.clearfix:first-child{ margin-top:0; }
.row.clearfix{ margin-top:0; }

/* ===== Sidebar collapsed (ICON ONLY) ===== */
body.sidebar-collapsed .left-sidebar{ width:64px; }
body.sidebar-collapsed .left-sidebar .menu-text,
body.sidebar-collapsed .left-sidebar .caret-icon{ opacity:0; visibility:hidden; width:0; overflow:hidden; }
body.sidebar-collapsed .left-sidebar:not(:hover) .sidebar-submenu{ display:none !important; }
body.sidebar-collapsed .left-sidebar .sidebar-menu{ padding:12px 6px; }
body.sidebar-collapsed .left-sidebar .sidebar-menu > li > a{ padding:14px 0; justify-content:center; gap:0; border-radius:10px; }
body.sidebar-collapsed .left-sidebar .sidebar-menu > li > a i.menu-icon{ font-size:17px; width:64px; min-width:unset; }
body.sidebar-collapsed .left-sidebar .sidebar-menu > li.active > a{ width:44px; margin:0 auto; }
body.sidebar-collapsed #main-content{ margin-left:64px !important; }

/* Expanded state on hover when collapsed */
body.sidebar-collapsed .left-sidebar:hover {
    width: 260px;
    z-index: 1050;
    box-shadow: 6px 0 25px rgba(0,0,0,0.3);
}
body.sidebar-collapsed .left-sidebar:hover .menu-text {
    opacity: 1;
    visibility: visible;
    width: auto;
    overflow: visible;
    transition: opacity 0.2s ease 0.1s;
}
body.sidebar-collapsed .left-sidebar:hover .caret-icon {
    opacity: 0.5;
    visibility: visible;
    width: auto;
    overflow: visible;
    transition: opacity 0.2s ease 0.1s;
}
body.sidebar-collapsed .left-sidebar:hover .sidebar-menu {
    padding: 12px 10px;
}
body.sidebar-collapsed .left-sidebar:hover .sidebar-menu > li > a {
    padding: 11px 16px;
    justify-content: flex-start;
    gap: 12px;
    border-radius: 10px;
}
body.sidebar-collapsed .left-sidebar:hover .sidebar-menu > li > a i.menu-icon {
    font-size: 15px;
    width: 20px;
    min-width: 20px;
}
body.sidebar-collapsed .left-sidebar:hover .sidebar-menu > li.active > a {
    width: auto;
    margin: 0;
}

/* Tooltip on hover when collapsed (only show when not hovering/expanded) */
body.sidebar-collapsed .left-sidebar:not(:hover) .sidebar-menu > li:hover > a::after{ content:attr(data-title); position:absolute; left:68px; top:50%; transform:translateY(-50%); background:color-mix(in srgb, {{$g_setting->theme_color}} 85%, #000); color:#fff; padding:7px 16px; border-radius:8px; font-size:12.5px; font-weight:500; white-space:nowrap; z-index:9999; box-shadow:0 6px 20px rgba(0,0,0,.35); pointer-events:none; letter-spacing:.3px; }

/* ================= MOBILE RESPONSIVE ================= */
@media(max-width:991px){
    .left-sidebar{ width:260px; transform:translateX(-260px); }
    #main-content{ margin-left:0 !important; }
    body.sidebar-open .left-sidebar{ transform:translateX(0); }
    body.sidebar-open .sidebar-overlay{ display:block; }
    .top-header-right .user-name-text{ display:none; }
    body.sidebar-collapsed .left-sidebar{ width:260px; transform:translateX(-260px); }
    body.sidebar-collapsed #main-content{ margin-left:0 !important; }
}
.sidebar-overlay{ display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,.5); backdrop-filter:blur(2px); z-index:1039; }

/* ===== Rate Page Tabs ===== */
.rate-tabs{ background:#fff; border-radius:10px; padding:5px; box-shadow:0 2px 10px rgba(0,0,0,.05); }
.rate-tabs-menu{ list-style:none; display:flex; padding:0; margin:0; }
.rate-tabs-menu li{ padding:12px 25px; cursor:pointer; font-weight:500; border-radius:8px; transition:all .3s ease; color:#555; }
.rate-tabs-menu li.active{ background:{{$g_setting->theme_color}}; color:#fff; }

/* ===== WHATSAPP FLOAT ===== */
.whatsapp-float{ position:fixed; bottom:20px; right:20px; width:60px; height:60px; background:#25D366; color:#fff !important; border-radius:50%; text-align:center; font-size:30px; box-shadow:0 4px 15px rgba(0,0,0,.3); z-index:99999; display:flex; align-items:center; justify-content:center; transition:all .3s ease; }
.whatsapp-float:hover{ background:#1ebe5d; transform:scale(1.1); }

/* ===== TRACKING FLOAT ===== */
.track-float{ position:fixed; bottom:90px; right:20px; width:60px; height:60px; background:{{$g_setting->theme_color}}; color:#fff !important; border-radius:50%; text-align:center; font-size:24px; box-shadow:0 4px 15px rgba(0,0,0,.3); z-index:99999; display:flex; align-items:center; justify-content:center; transition:all .3s ease; cursor:pointer; border:none; animation:pulse-blue 2s infinite; }
@keyframes pulse-blue{ 0%{transform:scale(.95);box-shadow:0 0 0 0 rgba(52,152,219,.7)} 70%{transform:scale(1);box-shadow:0 0 0 10px rgba(52,152,219,0)} 100%{transform:scale(.95);box-shadow:0 0 0 0 rgba(52,152,219,0)} }
.track-float:hover{ background:{{$g_setting->theme_color}}; transform:scale(1.1); animation:none; }
.track-popup{ position:fixed; bottom:160px; right:20px; width:320px; background:#fff; border-radius:20px; box-shadow:0 15px 35px rgba(0,0,0,.25); z-index:99998; display:none; overflow:hidden; animation:slideUp .4s cubic-bezier(.175,.885,.32,1.275); }
@keyframes slideUp{ from{opacity:0;transform:translateY(30px) scale(.9)} to{opacity:1;transform:translateY(0) scale(1)} }
.track-popup-header{ background:{{$g_setting->theme_color}}; color:#fff; padding:18px; font-weight:600; display:flex; justify-content:space-between; align-items:center; font-size:16px; }
.track-popup-body{ padding:25px; }
.track-popup-body .form-control{ border-radius:25px; padding:12px 20px; margin-bottom:15px; border:1px solid #eee; box-shadow:inset 0 2px 4px rgba(0,0,0,.02); }
.track-popup-body .form-control:focus{ border-color:{{$g_setting->theme_color}}; box-shadow:0 0 0 3px rgba(52,152,219,.1); }
.track-popup-body .btn-track{ width:100%; border-radius:25px; background:{{$g_setting->theme_color}}; color:#fff; border:none; padding:12px; font-weight:bold; font-size:15px; transition:all .3s; box-shadow:0 4px 10px rgba(0,0,0,.1); }
.track-popup-body .btn-track:hover{ filter:brightness(1.1); transform:translateY(-2px); box-shadow:0 6px 15px rgba(0,0,0,.15); }
</style>
<body class="theme-blue {{ $broadcast ? 'has-second-marquee' : '' }}">
    <div class="page-loader-wrapper">
        <div class="loader">
            <?php
            if(isset($general_setting)){ ?>
            <div class="m-t-30"><img src="{{ asset('public/uploads/' . $general_setting->preloader_photo) }}"
                    width="48" height="48" alt="{{$user_data->name}}"></div>
            <?php }else{ ?>
             <div class="m-t-30"><img src="{{ asset('public/uploads/preload.gif') }}"
                    width="48" height="48" alt=""></div>   
            <?php } ?>
            <p>Please wait...</p>
        </div>
    </div>

    <div id="loader" class="lds-dual-ring hidden overlay"></div>
    <div id="wrapper">
        
         <!-- ===== TOP OFFER MARQUEE (ADD HERE) ===== -->
    <!--<div class="fixed-marquee fw-bold">-->
    <!--    <div class="marquee-wrapper">-->
    <!--        <span class="marquee-content">-->
    <!--            🚚 Recharge Your Wallet for <strong >₹1000</strong> & Get <strong>₹1600</strong> Bonus 🎁 |-->
    <!--            Use Code: <strong>HYLO600</strong> | Limited Time Offer ⏳-->
    <!--        </span>-->
    <!--        <span class="marquee-content">-->
    <!--            🚚 Recharge Your Wallet for <strong>₹1000</strong> & Get <strong>₹1600</strong> Bonus 🎁 |-->
    <!--            Use Code: <strong>HYLO600</strong> | Limited Time Offer ⏳-->
    <!--        </span>-->
    <!--    </div>-->
    <!--</div>-->
    
    <div class="fixed-marquee fw-bold">
        <div class="marquee-wrapper">

            <span class="marquee-content">
                🚚 <strong>First Recharge Offer!</strong> 
                ₹1000 → <strong>₹1200</strong> (Code: <strong>HYLO200</strong>) | 
                ₹2000 → <strong>₹2500</strong> (Code: <strong>HYLO500</strong>) 🎁 | 
                Valid for <strong>New Users – First Recharge Only</strong> | 
                Limited Time ⏳
            </span>

            <span class="marquee-content">
                🚚 <strong>First Recharge Offer!</strong> 
                ₹1000 → <strong>₹1200</strong> (Code: <strong>HYLO200</strong>) | 
                ₹2000 → <strong>₹2500</strong> (Code: <strong>HYLO500</strong>) 🎁 | 
                Valid for <strong>New Users – First Recharge Only</strong> | 
                Limited Time ⏳
            </span>

        </div>
    </div>
    <?php if($broadcast) { ?>
        <div class="cutoff-marquee">
            <div class="cutoff-marquee-content">
                ⚠️ Weight Updated! Review Now & Raise Disputes Within TAT to Avoid Extra Charges
            </div>
        </div>
    <?php } ?>
    <!-- ===== END MARQUEE ===== -->
    
      
      <!-- ===== TOP HEADER BAR ===== -->
    <div class="top-header">
        <button class="hamburger-btn" id="sidebarToggle"><i class="fa fa-bars"></i></button>
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('public/uploads/'.$general_setting->logo) }}" class="company-logo">
        </a>
        <div class="top-header-right">
            <a href="{{ route('admin.settings.ticket') }}" class="user-btn"><i class="fa fa-ticket"></i></a>
            <a href="{{route('admin.payment.wallet')}}" class="wallet-pill">₹ {{ number_format($user_data->wallet_blc,2) }}</a>
            <div class="dropdown">
                <a href="#" class="user-btn" data-toggle="dropdown">
                    <i class="fa fa-user-circle"></i> <span class="user-name-text">{{ $user_data->name }}</span> ▾
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="dropdown-header">Seller ID: {{ $user_data->user_code }}</li>
                    <li><a href="{{ route('admin.kyc') }}">KYC</a></li>
                    <li><a href="{{ route('admin.settings') }}">Settings</a></li>
                    @if(in_array(Auth::guard('admin')->user()->role_id, ['1','2']))
                        <li><a href="{{ route('admin.role.user') }}">Users</a></li>
                        <li><a href="{{ route('admin.orders') }}">Order list</a></li>
                    @endif
                    @if(Auth::guard('admin')->user()->role_id == '1')
                        <li><a href="{{ route('admin.integration.pincode') }}">Pincodes</a></li>
                    @endif
                    <li class="divider"></li>
                    <li><a href="{{ route('admin.logout') }}">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- ===== SIDEBAR OVERLAY (mobile) ===== -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ===== LEFT SIDEBAR ===== -->
    <aside class="left-sidebar" id="leftSidebar">
        <ul class="sidebar-menu">

            {{-- Dashboard --}}
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" data-title="Dashboard">
                    <i class="fa fa-dashboard menu-icon"></i> <span class="menu-text">Dashboard</span>
                </a>
            </li>

            {{-- Orders --}}
            <li class="{{ request()->routeIs('admin.order.all','admin.order.index','admin.order.return','admin.bulkorder.create','admin.ndr.ndr') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="sidebar-toggle" data-title="Orders">
                    <i class="fa fa-shopping-cart menu-icon"></i> <span class="menu-text">Orders</span>
                    <i class="fa fa-angle-right caret-icon"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('admin.order.all') }}">All Orders</a></li>
                    <li><a href="{{ route('admin.order.index') }}">New Orders</a></li>
                    <li><a href="{{ route('admin.order.return') }}">RTO Orders</a></li>
                    <li><a href="{{ route('admin.bulkorder.create') }}">Bulk Order Import</a></li>
                    <li><a href="{{ route('admin.ndr.ndr') }}">NDR</a></li>
                </ul>
            </li>

            {{-- Operations --}}
            <li class="{{ request()->routeIs('admin.order.shipped_order','admin.order.manifest','admin.payment.lostshipments') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="sidebar-toggle" data-title="Operations">
                    <i class="fa fa-cogs menu-icon"></i> <span class="menu-text">Operations</span>
                    <i class="fa fa-angle-right caret-icon"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('admin.order.shipped_order') }}">Shipped</a></li>
                    <li><a href="{{ route('admin.order.manifest') }}">Manifest</a></li>
                    <li><a href="{{ route('admin.payment.lostshipments') }}">Lost Shipments</a></li>
                </ul>
            </li>

            {{-- Payments --}}
            <li class="{{ request()->routeIs('admin.rate','admin.wallet_transaction','admin.invoices','admin.credit_notes','admin.order.remlist','admin.payment.walletreport','admin.weight') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="sidebar-toggle" data-title="Payments">
                    <i class="fa fa-inr menu-icon"></i> <span class="menu-text">Payments</span>
                    <i class="fa fa-angle-right caret-icon"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('admin.order.remlist') }}">COD Remittance</a></li>
                    <li><a href="{{ route('admin.rate') }}">Rate Card</a></li>
                    <li><a href="{{ route('admin.wallet_transaction') }}">Shipping Charges</a></li>
                    <li><a href="{{ route('admin.weight') }}">Weight Reconciliation</a></li>
                    <li><a href="{{ route('admin.payment.walletreport') }}">Wallet Recharge</a></li>
                </ul>
            </li>

            {{-- Integration --}}
            <li class="{{ request()->routeIs('admin.integration.channel') ? 'active' : '' }}">
                <a href="{{ route('admin.integration.channel') }}" data-title="Integration">
                    <i class="fa fa-plug menu-icon"></i> <span class="menu-text">Integration</span>
                </a>
            </li>

            {{-- Courier --}}
            <li class="{{ request()->routeIs('admin.integration.manage_courier','admin.integration.courier_serviceable','admin.integration.courier_priority') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="sidebar-toggle" data-title="Courier">
                    <i class="fa fa-truck menu-icon"></i> <span class="menu-text">Courier</span>
                    <i class="fa fa-angle-right caret-icon"></i>
                </a>
                <ul class="sidebar-submenu">
                    @if(Auth::guard('admin')->user()->role_id == '1')
                        <li><a href="{{ route('admin.integration.manage_courier') }}">Manage Courier</a></li>
                        <li><a href="{{ route('admin.integration.courier_serviceable') }}">Courier Serviceable</a></li>
                    @endif
                    <li><a href="{{ route('admin.integration.courier_priority') }}">Courier Priority</a></li>
                </ul>
            </li>

            {{-- Warehouse --}}
            <li class="{{ request()->routeIs('admin.warehouse.list') ? 'active' : '' }}">
                <a href="{{ route('admin.warehouse.list') }}" data-title="Warehouse">
                    <i class="fa fa-building menu-icon"></i> <span class="menu-text">Warehouse</span>
                </a>
            </li>

            {{-- Reports --}}
            <li class="{{ request()->routeIs('admin.reports.mis','admin.reports.requestedreport','admin.order.shipment_report') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="sidebar-toggle" data-title="Reports">
                    <i class="fa fa-bar-chart menu-icon"></i> <span class="menu-text">Reports</span>
                    <i class="fa fa-angle-right caret-icon"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('admin.reports.mis') }}">MIS Report</a></li>
                    <li><a href="{{ route('admin.reports.requestedreport') }}">Requested Report</a></li>
                    <li><a href="{{ route('admin.order.shipment_report') }}">Shipment Reports</a></li>
                </ul>
            </li>

        </ul>
    </aside>

    <!-- ===== SIDEBAR TOGGLE JS ===== -->
    <script>
    document.addEventListener('DOMContentLoaded', function(){
        var toggle = document.getElementById('sidebarToggle');
        var sidebar = document.getElementById('leftSidebar');
        var overlay = document.getElementById('sidebarOverlay');
        var body = document.body;
        var isMobile = window.innerWidth <= 991;

        toggle.addEventListener('click', function(){
            if(isMobile){
                body.classList.toggle('sidebar-open');
            } else {
                body.classList.toggle('sidebar-collapsed');
            }
        });

        overlay.addEventListener('click', function(){
            body.classList.remove('sidebar-open');
        });

        window.addEventListener('resize', function(){
            isMobile = window.innerWidth <= 991;
            if(!isMobile) body.classList.remove('sidebar-open');
        });

        // Sub-menu toggle
        var toggles = document.querySelectorAll('.sidebar-toggle');
        toggles.forEach(function(t){
            t.addEventListener('click', function(e){
                e.preventDefault();
                var li = this.parentElement;
                li.classList.toggle('open');
            });
        });
    });
    </script>



        <div id="main-content">
            <div class="container-fluid">
                    @yield('admin_content')
                
            </div>
        </div>
    
    </div>

    @include('admin.includes.scripts-footer')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<a href="https://wa.me/919217856226" 
   class="whatsapp-float" 
   target="_blank">
    <i class="fa fa-whatsapp"></i>
</a>

<!-- Floating AWB Search -->
<a href="javascript:void(0)" class="track-float" id="trackBtn" title="Track AWB" onclick="$('#trackPopup').fadeToggle(300)">
    <i class="fa fa-search"></i>
</a>

<div class="track-popup" id="trackPopup">
    <div class="track-popup-header">
        <span>Track Your AWB</span>
        <i class="fa fa-times" style="cursor:pointer" id="closeTrack" onclick="$('#trackPopup').fadeOut(300)"></i>
    </div>
    <div class="track-popup-body">
        <input type="text" id="awb_number" class="form-control" placeholder="Enter AWB Number">
        <button class="btn-track" id="performTrack">Track Now</button>
    </div>
</div>

<script>
$(document).ready(function() {
    $(document).on('click', '#performTrack', function() {
        var awb = $('#awb_number').val().trim();
        if (awb !== '') {
            $.ajax({
                url: "{{ route('admin.tracking.check') }}",
                type: 'GET',
                data: { awb: awb },
                beforeSend: function() {
                    $('#performTrack').prop('disabled', true).text('Checking...');
                },
                success: function(response) {
                    if (response.success) {
                        var url = "{{ route('admin.tracking', ['awb' => ':awb']) }}";
                        url = url.replace(':awb', awb);
                        window.location.href = url;
                    } else {
                        toastr.error('AWB not found! Please check again.');
                        $('#performTrack').prop('disabled', false).text('Track Now');
                    }
                },
                error: function() {
                    toastr.error('Something went wrong. Please try again.');
                    $('#performTrack').prop('disabled', false).text('Track Now');
                }
            });
        } else {
            toastr.warning('Please enter an AWB number');
        }
    });

    $(document).on('keypress', '#awb_number', function(e) {
        if(e.which == 13) {
            $('#performTrack').click();
        }
    });

    $(document).click(function(e) {
        if (!$(e.target).closest('#trackPopup, #trackBtn').length) {
            $('#trackPopup').fadeOut(300);
        }
    });
});
</script>
</body>


<script>
     $(document).ready(function() {
       
        $('.sorttable').DataTable(
        {
            paging: false,
            dom: 'Bfrtip',
            buttons: [
                {
                extend: 'csv',
                text: 'Export CSV',
                filename: function() {
                    // Get the table name
                    var tableName = $('.sorttable').attr('name');
                    return tableName;
                },
                exportOptions: {
                    columns: ':not([data-field="hideexport"])'
                }
                },
            ]
        });
        $('.sorttableexcel').DataTable(
        {
            pageLength: 100,  // Set the default page length to 10
            dom: 'Bfrtip',
            buttons: [
                {
                extend: 'excel',
                text: 'Export excel',
                filename: function() {
                    // Get the table name
                    var tableName = $('.sorttable').attr('name');
                    return tableName;
                },
                exportOptions: {
                    columns: ':not([data-field="hideexport"])'
                }
                },
            ]
        });
        $('.sorttableexceldate').DataTable(
        {

            pageLength: 250,  // Set the default page length to 10
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: 'Export excel',
                    filename: function() {
                        // Get the table name
                        var tableName = $('.sorttableexceldate').attr('name');
                        return tableName;
                    },
                    exportOptions: {
                        columns: ':not([data-field="hideexport"])',   
                        format: {
                            body: function(data, row, column, node) {
                                // If the column has the date-column class, replace data with 'ritesh'
                                if ($(node).hasClass('date-column')) {
                                    var datedata = $(data).text();
                                    datedata = datedata.replace(/^\s+|\s+$/gm,'');
                                    if(datedata != ''){
                                    console.log(datedata);
                                    myArray = datedata.split(" ");
                                    mon  = myArray[1].replace(',','');
                                    word = myArray[2]+'-'+parseMonth(mon)+'-'+myArray[0];
                                    console.log(word);
                                    return word;
                                    }else{
                                        return '';
                                    }
                                }
                                if ($(node).hasClass('anchor-column')) {
                                    return $(data).text();
                                }
                                if ($(node).hasClass('special-column')) {
                                    var datedata = $(data).text();
                                    datedata = datedata.replace(/^\s+|\s+$/gm,'');
                                    if(datedata != ''){
                                        return datedata;
                                    }else{
                                        return '';
                                    }
                                }
                                // For other columns, return the original data
                                // return data;
                                  return $(node).text().replace(/\s+/g, ' ').trim();
                            }
                        }
                    }
                }
            ]
        });
        $('.sorttableexceldatesortsecond').DataTable(
        {
            pageLength: 100,  // Set the default page length to 10
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: 'Export excel',
                    filename: function() {
                        // Get the table name
                        var tableName = $('.sorttableexceldatesortsecond').attr('name');
                        return tableName;
                    },
                    exportOptions: {
                        columns: ':not([data-field="hideexport"])',   
                        format: {
                            body: function(data, row, column, node) {
                                // If the column has the date-column class, replace data with 'ritesh'
                                if ($(node).hasClass('date-column')) {
                                    var datedata = $(data).text();
                                    datedata = datedata.replace(/^\s+|\s+$/gm,'');
                                    if(datedata != ''){
                                    console.log(datedata);
                                    myArray = datedata.split(" ");
                                    mon  = myArray[1].replace(',','');
                                    word = myArray[2]+'-'+parseMonth(mon)+'-'+myArray[0];
                                    console.log(word);
                                    return word;
                                    }else{
                                        return '';
                                    }
                                }
                                if ($(node).hasClass('anchor-column')) {
                                    return $(data).text();
                                }
                                if ($(node).hasClass('special-column')) {
                                    var datedata = $(data).text();
                                    datedata = datedata.replace(/^\s+|\s+$/gm,'');
                                    if(datedata != ''){
                                        return datedata;
                                    }else{
                                        return '';
                                    }
                                }
                                // For other columns, return the original data
                               return $(node).text().replace(/\s+/g, ' ').trim();
                            }
                        }
                    }
                }
            ]
        });
         $('.sorttableexceldatesortsecond').DataTable().order([1, 'desc']).draw();
              
    });
      // Function to parse month abbreviation to a number
        function parseMonth(month) {
            var months = {
                Jan: '01',
                Feb: '02',
                Mar: '03',
                Apr: '04',
                May: '05',
                Jun: '06',
                Jul: '07',
                Aug: '08',
                Sep: '09',
                Oct: '10',
                Nov: '11',
                Dec: '12'
            };
            return months[month];
        }
        function broadcastclose(broadcast_id){
            $.get({
                url: "{{ route('admin.broadcast.hideuser') }}",
                data: { 
                    b_id: broadcast_id
                   
                },
                beforeSend: function() {
                    $('#loader').removeClass('hidden')
                },
                success: function(data) {
                    $('.broadcast-conatiner').animate({
                    opacity: 0
                    }, 1000, function() {
                        $(this).hide(); // Ensure the element is hidden after the animation
                    });
                },
                complete: function(){
                    $('#loader').addClass('hidden')
                },
            });
            
        }
</script>
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

    {{-- ===== GLOBAL TOASTR FLASH MESSAGES ===== --}}
    $(document).ready(function() {
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "4000"
        };
        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif
        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif
        @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}");
        @endif
        @if(Session::has('info'))
            toastr.info("{{ Session::get('info') }}");
        @endif
    });

</script>
</html>
