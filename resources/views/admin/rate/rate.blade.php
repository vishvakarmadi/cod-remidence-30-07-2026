@extends('admin.admin_layouts')
@section('admin_content')
    <!-- Main body part  -->
    <div class="container-fluid">
        <!-- Page header section  -->
        
        <div class="row clearfix">
            <div class="col-12">

                <div class="rate-tabs">
                    <ul class="rate-tabs-menu">
                        <li class="active" data-id="ratecard">Rate</li>
                        <li data-id="calculator">Rate Calculator</li>
                    </ul>
                </div>


                <div class="card mt-30 ratecard">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Rate Card</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable js-exportable sorttable">
                                <thead>
                                    <tr>
                                        <th>Courier Name</th>
                                        <th class="text-center">Mode</th>
                                        <th>Weight</th>
                                        <th>Within City</th>
                                        <th>Within State</th>
                                        <th>Metro to Metro</th>
                                        <th>Rest of India</th>
                                        <th>North East, J&K</th>
                                        <th>COD Charges</th>
                                        <th>COD%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rate as $key => $row)
                                        <tr @if($row->additional =='1') style="background-color: antiquewhite;" @endif>
                                            <td>{{ $couriers[$row->courier_id]['name'] }}</td>
                                            <td class="text-center">
                                                @if($row->courier_id == '1')

                                                @elseif($row->transport == 'Air')
                                                 <i class="fa fa-plane"></i>
                                                @elseif($row->transport == 'Surface') 
                                                 <i class="fa fa-truck"></i> 
                                                 @else
                                                 {{$row->transport}}
                                                @endif
                                            </td>
                                            <td>@if($row->additional =='1') Add ( @endif {{ $row->weight }} kg @if($row->additional =='1') ) @endif</td>
                                            <td>Rs {{ $row->within_city }}</td>
                                            <td>Rs {{ $row->within_state }}</td>
                                            <td>Rs {{ $row->metro_to_metro }}</td>
                                            <td>Rs {{ $row->rest_of_india }}</td>
                                            <td>Rs {{ $row->north_east }}</td>
                                            <td>
                                                @if($row->additional =='0')
                                                Rs {{ $row->cod_charges }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($row->additional =='0')
                                                {{ $row->cod }}%
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table><br><br>
                            <h3>Terms & Condition</h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>Terms</th>
                                            <th>Condition</th>
                                            @if (Auth::guard('admin')->user()->role_id == 1)  
                                            <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
    
                                    <tbody>
                                        @foreach ($terms as $row)
                                                <tr>
                                                    <td>{{ $row->terms }}</td>
                                                    <td>{{ $row->conditions }}</td>
                                                    @if (Auth::guard('admin')->user()->role_id == 1)  
                                                    <td><a class="btn btn-primary btn-sm" style="cursor: pointer;"
                                                        href="{{ route('admin.rate.termedit',$row->id)}}">
                                                        Edit
                                                        </a></td>
                                                    @endif    
                                                </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                
                 @if (Auth::guard('admin')->user()->role_id == 1 || Auth::guard('admin')->user()->role_id == 2)
                    <div class="card pt-30 ratecard">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Rate Card Excel Upload</h5>
                    </div>
                    <div class="card-body">
                        <form class="product-form" action="{{ route('admin.bulkrate.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h4>Import Rate File</h4>
                            <a href="{{ asset('public/ratecards.xlsx') }}" download="" class="btn btn-secondary">Download
                                Format</a>
                            
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">User</label><span class="required"> *</span>
                                    <input type="hidden" name="user_id" id="userSelectValue">
                                    <div class="csd-wrapper" id="userSelectWrapper" tabindex="0">
                                        <div class="csd-trigger form-control" id="userSelectTrigger">
                                            <span id="userSelectLabel">Select User</span>
                                            <i class="csd-arrow">&#9660;</i>
                                        </div>
                                        <div class="csd-dropdown" id="userSelectDropdown">
                                            <div class="csd-search-wrap">
                                                <input type="text" id="csdSearchInput" class="csd-search" placeholder="Search..." autocomplete="off">
                                            </div>
                                            <ul class="csd-list" id="userOptionsList">
                                                <li data-value="" class="csd-option csd-placeholder">Select User</li>
                                                @foreach ($users as $user)
                                                    <li data-value="{{ $user->id }}" class="csd-option">{{ $user->user_code ?? $user->id }} {{ $user->name }} - ({{ $user->mobile }} , {{ $user->company_name}})</li>
                                                @endforeach
                                                @if (Auth::guard('admin')->user()->role_id == 1)
                                                    <li data-value="0" class="csd-option">Default RateCard</li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    {{-- For HTML5 required validation fallback --}}
                                    <select name="user_id_native" id="userSelectNative" style="display:none" required>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="excel" id="profilePicUpload1" required
                                                    accept=".xlsx">
                                                <label for="profilePicUpload1" class="bg-secondary text-white">Upload
                                                    File</label>
                                                <small class="mt-2">Supported files: <b>xlsx</b>.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <button type="submit" class="btn-primary btn w-100 col-lg-4">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
                <div class="card mt-30 calculator hide">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Rate Calculator</h5>
                    </div>
                    <div class="card-body">
                        <form action="" id="ratecalulator">
                        <div class="row">
                            <x-field type="number" label="Pickup Pincode" placeholder="Pickup Pincode"
                                name="pickup_pin" required="required" />
                            <x-field type="number" label="Drop Pincode" placeholder="Drop Pincode" name="drop_pin"
                                required="required" />
                            <br><br>
                            <div class="form-group col-md-4">
                                <label>Dimension(in Cms)</label><span class="required">*</span><br>
                                <div class="row" style="margin-left: -1px;">
                                <input type="number" class="form-control col-sm-3" name="length" value="10"
                                    required />&nbsp;&nbsp;&nbsp;
                                <input type="number" class="form-control col-sm-3" name="breadth" value="10"
                                    required />&nbsp;&nbsp;&nbsp;
                                <input type="number" class="form-control col-sm-3" name="height" value="10"
                                    required />
                                </div>
                            </div>
                            <x-field type="text" label="Weight (in KGs)" name="weight" required="required"
                                value="1" />
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Prepaid / COD</label><span class="required">
                                    *</span>
                                <select class="form-control" name="payment" required>
                                    <option value="prepaid">Prepaid</option>
                                    <option value="cod">COD</option>
                                </select>
                            </div>
                            <x-field type="number" label="Declared Value" name="value" required="" />
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Shipment Type</label><span class="required">
                                    *</span>
                                <select class="form-control" name="shipment_type" required>
                                    <option value="forward">Forward</option>
                                    <option value="reverse">Reverse</option>
                                </select>
                            </div>
                        </div>
                        </form>
                        <button type="button" class="btn-primary btn h-45 fetchdetails col-lg-4">Calculate</button><br><br>
                        <div id="serviable"></div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<style>
.csd-wrapper {
    position: relative;
    outline: none;
}
.csd-trigger {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    user-select: none;
    background: #fff;
}
.csd-trigger:focus, .csd-wrapper:focus .csd-trigger {
    border-color: #80bdff;
    box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
}
.csd-arrow {
    font-style: normal;
    font-size: 11px;
    color: #666;
    pointer-events: none;
}
.csd-dropdown {
    display: none;
    position: absolute;
    z-index: 9999;
    bottom: 100%;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #ced4da;
    border-bottom: none;
    border-radius: 4px 4px 0 0;
    box-shadow: 0 -4px 10px rgba(0,0,0,.1);
}
.csd-search-wrap {
    padding: 6px 8px;
    border-bottom: 1px solid #e9ecef;
    background: #f8f9fa;
}
.csd-search {
    width: 100%;
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 5px 10px;
    font-size: 13px;
    outline: none;
}
.csd-search:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 .15rem rgba(0,123,255,.2);
}
.csd-dropdown.open { display: block; }
.csd-list { list-style: none; margin: 0; padding: 0; max-height: 180px; overflow-y: auto; }
.csd-option {
    padding: 7px 12px;
    cursor: pointer;
    font-size: 14px;
    transition: background .15s;
}
.csd-option:hover, .csd-option.active { background: #e9f3ff; }
.csd-option.csd-placeholder { color: #999; }
.csd-option.csd-match { background: #f0fff0; font-weight: 500; }
.csd-option.hidden { display: none; }
</style>
<script>
    "use strict";
    // Custom searchable dropdown — type anywhere to filter, matches float to top
    (function() {
        var wrapper   = document.getElementById('userSelectWrapper');
        var trigger   = document.getElementById('userSelectTrigger');
        var label     = document.getElementById('userSelectLabel');
        var dropdown  = document.getElementById('userSelectDropdown');
        var list      = document.getElementById('userOptionsList');
        var hiddenVal = document.getElementById('userSelectValue');
        if (!wrapper) return;

        // Collect all options once
        var allItems = Array.from(list.querySelectorAll('.csd-option')).map(function(li) {
            return { el: li, value: li.dataset.value, text: li.textContent.trim().toLowerCase() };
        });

        var typingBuffer = '';
        var typingTimer;
        var isOpen = false;

        function openDropdown() {
            dropdown.classList.add('open');
            isOpen = true;
            renderAll('');
            // Auto-focus search input
            var si = document.getElementById('csdSearchInput');
            if (si) { si.value = ''; setTimeout(function(){ si.focus(); }, 30); }
        }

        function closeDropdown() {
            dropdown.classList.remove('open');
            isOpen = false;
            typingBuffer = '';
            var si = document.getElementById('csdSearchInput');
            if (si) si.value = '';
        }

        function renderAll(q) {
            var matched = [], unmatched = [];
            allItems.forEach(function(item) {
                if (item.value === '') return; // placeholder stays at top always
                item.el.classList.remove('csd-match');
                if (q && item.text.includes(q)) {
                    matched.push(item);
                } else {
                    unmatched.push(item);
                }
            });

            // Reorder: matched first, then unmatched
            matched.forEach(function(item) {
                item.el.classList.add('csd-match');
                list.appendChild(item.el);
            });
            unmatched.forEach(function(item) {
                list.appendChild(item.el);
            });

            // Auto-highlight first match
            list.querySelectorAll('.csd-option').forEach(function(li) { li.classList.remove('active'); });
            if (matched.length > 0) matched[0].el.classList.add('active');
        }

        function selectOption(value, text) {
            hiddenVal.value = value;
            label.textContent = text || 'Select User';
            // sync native hidden select for required validation
            var native = document.getElementById('userSelectNative');
            native.innerHTML = '<option value="' + value + '" selected>' + text + '</option>';
            closeDropdown();
        }

        // Toggle on click
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            isOpen ? closeDropdown() : openDropdown();
            wrapper.focus();
        });

        // Click on option
        list.addEventListener('click', function(e) {
            var li = e.target.closest('.csd-option');
            if (!li) return;
            selectOption(li.dataset.value, li.textContent.trim());
        });

        // Search input typing
        var searchInput = document.getElementById('csdSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                renderAll(this.value.trim().toLowerCase());
            });
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') { closeDropdown(); }
                if (e.key === 'Enter') {
                    var active = list.querySelector('.csd-option.active');
                    if (active) selectOption(active.dataset.value, active.textContent.trim());
                }
                e.stopPropagation();
            });
            // Prevent dropdown close when clicking inside search
            searchInput.addEventListener('click', function(e) { e.stopPropagation(); });
        }

        // Keyboard typing on wrapper
        wrapper.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { closeDropdown(); return; }
            if (e.key === 'Enter') {
                var active = list.querySelector('.csd-option.active');
                if (active) selectOption(active.dataset.value, active.textContent.trim());
                return;
            }
            if (e.key.length !== 1) return;

            if (!isOpen) openDropdown();

            typingBuffer += e.key.toLowerCase();
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function() { typingBuffer = ''; renderAll(''); }, 1000);

            renderAll(typingBuffer);
        });

        // Close on outside click
        document.addEventListener('click', function() {
            if (isOpen) closeDropdown();
        });
        wrapper.addEventListener('click', function(e) { e.stopPropagation(); });
    })();
    (function($) {
        $('.fetchdetails').on('click',function(){
            $.get({
                url: "{{ route('admin.rate.calculate') }}",
                data: $('#ratecalulator').serialize(),
                success: function(response) {
                    $('#serviable').empty();
                    if(response['status'] == 1){
                        let html = `<div class="table-responsive">
                                        <h5 class="text-center">Rate for shipping a ${response['request'].weight } kg ${response['request'].payment } packet from ${response['request'].pickup_pin } to ${response['request'].drop_pin }
                                        <br><span style="color:red;float:right">GST Excluded*</span></h5>
                                        <table class="table table-striped table-hover dataTable js-exportable" id="servicedata">
                                            <thead>
                                                <tr>
                                                    <th>Sr No</th>
                                                    <th>Courier Provider</th>
                                                    <th>Zone</th>
                                                    <th>Charged Weight (kg)</th>
                                                    <th>Fright Charge</th>
                                                    <th>COD Charge</th>
                                                    <th>Total Charges</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>`;
                        $('#serviable').html(html);
                        $.each(response['data'], function(index, value) {
                            $('#servicedata').append(`<tr>
                                                        <td>${index + 1}</td>
                                                        <td>${value.courier}</td>
                                                        <td>${value.zone}</td>
                                                        <td>${value.weight}</td>
                                                        <td>${value.freight_charge}</td>
                                                        <td>${value.cod}</td>
                                                        <td>${value.withoutgsttotal}</td>
                                                    </tr>`);
                        });
                    } else {
                        toastr.error('Pincode not found');
                    }
                },
                error: function(response) {
                    if (response.responseJSON && response.responseJSON.errors) {
                        var errors = response.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            toastr.error(errors[key][0]);
                        });
                    } else {
                        toastr.error('An error occurred while processing your request.', 'Error');
                    }
                },
            });
        });
    })(jQuery);
    $('.rate-tabs-menu li').on('click', function () {

    $('.rate-tabs-menu li').removeClass('active');
    $(this).addClass('active');

    let target = $(this).data('id');

    $('.ratecard, .calculator').addClass('hide');

    if(target === 'ratecard'){
        $('.ratecard').removeClass('hide');
    }

    if(target === 'calculator'){
        $('.calculator').removeClass('hide');
    }

});

</script>
@endsection
