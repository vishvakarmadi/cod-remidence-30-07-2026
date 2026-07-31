@extends('admin.admin_layouts')

@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp

<!-- Main body part  -->
<div class="container-fluid">
    <!-- Page header section  -->
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12 col-sm-12">
                <h1>Hi, Welcomeback! </h1>
                <span>{{$session->name}}'s Dashboard,</span>
            </div>
        </div>
    </div>

    
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-12">
            
            <div id="navbar-animmenu">
                    <ul class="show-dropdown main-navbar">
                        <div class="hori-selector" style="margin-left: 20px;"><div class="left"></div><div class="right"></div></div>
                        <li><a href="{{ route('admin.dashboard') }}" data-id="Overview">Overview</a></li>
                        <li><a href="{{route('admin.dashboard.shipment')}}" data-id="channel">Shipment Status</a></li>
                        <li><a href="{{route('admin.dashboard.top')}}" data-id="top10">Top 10</a></li>
                        <li class="active"><a  href="{{route('admin.dashboard.ndr')}}" data-id="ndr">NDR</a></li>
                    </ul>
                </div>
                <div class="card top_report card mt-30 Overview hide">
                Please wait.. Data is loading
                </div>

                <div class="card mt-30 channel hide">
                Please wait.. Data is loading
                </div>

                <div class="card mt-30 top10 hide">
                Please wait.. Data is loading
                </div>

                <div class="card mt-30 ndr ">
                        ndr
                </div> 
            </div>
        </div>

    </div>


</div>


     
@endsection


 