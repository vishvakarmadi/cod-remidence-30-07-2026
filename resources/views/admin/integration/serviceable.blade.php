@extends('admin.admin_layouts')
@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp

<style>
label {
    display: inline-block;
    margin-bottom: 0px;
}
td.active_0 {
    background: #ff904f;
}
</style>
<!-- Main body part  -->
<div class="container-fluid">
    <!-- Page header section  -->

    <div class="row clearfix">
        <div class="col-xl-12">
            <div class="card bg-light mb-3">
                <div class="card-header" style="display:flex;flex-wrap: wrap;">
                    <!-- <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Filter</h5> -->
                    <div class="col-md-9"> <a href="javascript:void(0)" class="expand">Filters <?php if(empty($re_data)){ echo '<<';}else{ echo '>>';} ?></a></div>
                    <div class="col-md-3">
                        <x-button type="import" route="{{ route('admin.integration.createserviceable') }}" name="Import"/>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.integration.courier_serviceable') }}" method="GET">
                        <div class="col-md-12">
                            <?php $pincodes='';
                            if(!empty($re_data)){
                              if(isset($re_data['pincodes']))
                                $pincodes = $re_data['pincodes'];
                              
                            }
                            ?>
                            <div class="show_more" style="width: 100%; ">
                                <div class="row">
                                    <x-field type="text" label="Pincodes" placeholder="Pincodes" name="pincodes" value="{{$pincodes}}" />
                                </div>
                                <div class="row">
                                    <x-button size="col-lg-3" type="submit" name="Search" />
                                </div>
                            </div>
                           
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-xl-12">
            <div class="card new_orders">
                 <div class="header d-flex justify-content-between" style="padding-bottom: 0;">
                        <h2 class="col-md-9">Serviceable Pincode</h2>
                        <div class="col-md-3">
                            <button id="btnExport" class="btn btn-success" onclick="fnCsvReport();" style="height:30px;font-size:12px"><i class="fa fa-file-text-o" style=""></i> EXPORT CSV </button> 
                        </div>
                        
                   </div>
                  
                

                <div class="card-body">
                    <div class="table-responsive">
                <table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Pincode</th>
                            <th>City</th>
                            <th>State</th>
                            <th colspan ="42" class="text-center">Courier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td colspan="3" class="text-center">Ecom Express</td>
                            <td colspan="3" class="text-center">Delhivery</td>
                            <td colspan="3" class="text-center">Bluedart</td>
                            <td colspan="3" class="text-center">XpressBees</td>
                            <td colspan="3" class="text-center">DTDC</td>
                            <td colspan="3" class="text-center">Smartr</td>
                            <td colspan="3" class="text-center">Ekart</td>
                            <td colspan="3" class="text-center">Shadowfax</td>
                            <td colspan="3" class="text-center">ATS</td>
                            <td colspan="3" class="text-center">Blitz</td>
                            <td colspan="3" class="text-center">Shree Maruti</td>
                            <td colspan="3" class="text-center">PiknDel</td>
                            <td colspan="3" class="text-center">Parcel Uncle</td>
                            <td colspan="3" class="text-center">India Post</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            @for($i=1;$i<=14;$i++)
                            <td>Transfer</td>
                            <td>Payment</td>
                            <td>Mode</td>
                            @endfor
                        </tr>
                        @foreach($pincodedata as $pc)
                        <tr>
                            <td>{{$pc['pincode']}}</td>
                            <td>{{$pc['city'] ?? ''}}</td>
                            <td>{{$pc['state'] ?? ''}}</td>
                            @for($i=1;$i<=14;$i++)
                                @if(isset($pc[$i]))
                                    <td class="active_{{$pc[$i]['active']}}">{{$pc[$i]['mode']}}</td>
                                    <td class="active_{{$pc[$i]['active']}}">{{$pc[$i]['payment']}}</td>
                                    <td class="active_{{$pc[$i]['active']}}">
                                        {{$pc[$i]['type']}}
                                        @if(!empty($pc[$i]['shipment_type']))
                                            <br><small style="font-size: 10px; opacity: 0.8; font-weight: bold;">({{ $pc[$i]['shipment_type'] }})</small>
                                        @endif
                                    </td>
                                @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif
                            @endfor
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>    
                </div>    
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.0/xlsx.full.min.js"></script>
<script>
    function fnCsvReport() {
        var tab = document.getElementById('dataTable');
        var rows = tab.rows;
        var csvRows = [];
        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].cells;
            var rowData = [];
            for (var j = 0; j < cells.length; j++) {
                var cellText = cells[j].innerText || cells[j].textContent || '';
                cellText = cellText.trim();
                cellText = cellText.replace(/"/g, '""');
                if (cellText.indexOf(',') > -1 || cellText.indexOf('\n') > -1 || cellText.indexOf('"') > -1) {
                    cellText = '"' + cellText + '"';
                }
                rowData.push(cellText);
            }
            csvRows.push(rowData.join(','));
        }
        var csvContent = '\uFEFF' + csvRows.join('\n');
        var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        var url = window.URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'serviceable_pincodes.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        return true;
    }
    (function($) {
        "use strict";
        
        $('.expand').on('click',function() {
            if ($(this).text() == 'Filters>>') {
                $(this).text('Filters<<');
            } else {
                $(this).text('Filters>>');
            }
            $('.show_more').slideToggle('fast');
        });
    })(jQuery);

</script>


@endsection