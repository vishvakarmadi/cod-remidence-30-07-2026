@props(['title' => '','column'=>'','field'=>'','create'=>'','edit'=>'','delete'=>'','password'=>'','rowsData'=>'','module'=>'','edit'=>'','delete'=>'','show'=>''])

@php
    $session = Auth::guard('admin')->user();
@endphp
<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12 col-sm-12">
                <h1>Hi, Welcomeback!</h1>
            </div>
            <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                <div class="row clearfix">
                    <div class="col-xl-5 col-md-5 col-sm-12"></div>
                    <div class="col-xl-7 col-md-7 col-sm-12 text-md-right hidden-xs">
                        <div
                            class="d-flex align-items-center justify-content-md-end mt-4 mt-md-0 flex-wrap vivify pullUp delay-550">
                            <div class="mb-3 mb-xl-0">
                                @if ($session->hasPermissionTo('create '.$module))
                                    <x-button type="create" route="{{ $create }}" />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="card">
                    <div class="header">
                        <h2>{{ $title }}<small>
                            <ul class="header-dropdown dropdown">
                                <li><a href="javascript:void(0);" class="full-screen">
                                    <i class="fa fa-expand"></i></a></li>
                            </ul>
                    </div>
                    <div class="body">
                        <x-table :rowsData="$rowsData" :column="$column" :field="$field" module="{{ $module }}" password="{{ $password }}" edit="{{ $edit }}" delete="{{ $delete }}"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
