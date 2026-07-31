@extends('admin.admin_layouts')
@section('admin_content')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <!-- Main body part  -->
    <div class="container-fluid">
        <!-- Page header section  -->
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <h1>Hyloship - Billing Info</h1>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                    <div class="row clearfix">
                        <div class="col-xl-5 col-md-5 col-sm-12">
                        </div>
                        <div class="col-xl-7 col-md-9 col-sm-12 text-md-right hidden-xs">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-12">

                <div id="navbar-animmenu">
                    <ul class="show-dropdown main-navbar">
                        <div class="hori-selector" style="margin-left: 20px;">
                            <div class="left"></div>
                            <div class="right"></div>
                        </div>
                        <li><a href="javascript:void(0);" data-id="shipping_charges">Shipping charges</a>
                        </li>
                        <li><a href="javascript:void(0);" data-id="invoices">Invoices</a></li>
                        <li><a href="javascript:void(0);" data-id="credit-notes">Credit Notes</a></li>
                        <li class="active"><a href="javascript:void(0);" data-id="wallet_transaction">Wallet Transaction</a></li>

                    </ul>
                </div>



                <div class="card mt-30 shipping_charges hide">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Shipping charges (Forward Journey)</h5>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable js-exportable">
                                <thead>

                                    <tr>
                                        <th>Order id</th>
                                        <th>Customer Info</th>
                                        <th>Tracking Info</th>
                                        <th>Zone</th>
                                        <th>Charges Applied(Forward)</th>
                                        <th>Dim. & Wt. Enter</th>
                                        <th>Order Assign Date</th>
                                        <!-- <th>Transaction Details</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order_fwd as $row)
                                        <tr>


                                            <td class="text-center date-column">
                                                <a
                                                    href="{{ route('admin.order.detail', $row->id) }}">{{ $row->order_id }}</a>
                                            </td>
                                            <td>{{ $row->ship_phone }}</td>
                                            <td>
                                                @if ($row->ship_courier_id)
                                                {{ $row->tracking_info }}|{{ $couriers[$row->ship_courier_id]['name'] }}
                                                @endif
                                            </td>
                                            <td>{{ $row->zone }}</td>
                                            <td>{{ $row->shipping_courier_cost }}</td>
                                            <td>Dim : {{ $row->length }}x{{ $row->breadth }}x{{ $row->height }}<br>
                                                Wt : {{ $row->weight }}</td>
                                            <td>{{ $row->shipped_date }}</td>
                                            <!-- <td><i class="fa fa-file" data-toggle="modal" data-target="#exampleModalCenter"
                                                    aria-hidden="true"></i></td> -->
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div><br>



                    </div>
                    @if (count($order_bwd) !='0')
                    <div class="card-header">
                        <h5 class="card-title mb-0">Shipping charges (Backward Journey)</h5>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable js-exportable">
                                <thead>

                                    <tr>
                                        <th>Order id</th>
                                        <th>Customer Info</th>
                                        <th>Tracking Info</th>
                                        <th>Zone</th>
                                        <th>Charges Applied(RTO)</th>
                                        <th>Dim. & Wt. Enter</th>
                                        <th>Order Assign Date</th>
                                        <!-- <th>Transaction Details</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order_bwd as $row)
                                        <tr>


                                            <td class="text-center">
                                                <a
                                                    href="{{ route('admin.order.detail', $row->id) }}">{{ $row->order_id }}</a>
                                            </td>
                                            <td>{{ $row->ship_phone }}</td>
                                            <td>
                                                @if ($row->ship_courier_id)
                                                {{ $row->tracking_info }}|{{ $couriers[$row->ship_courier_id]['name'] }}
                                                @endif
                                            </td>
                                            <td>{{ $row->zone }}</td>
                                            <td>{{ $row->shipping_courier_cost }}</td>
                                            <td>Dim : {{ $row->length }}x{{ $row->breadth }}x{{ $row->height }}<br>
                                                Wt : {{ $row->weight }}</td>
                                            <td>{{ $row->shipped_date }}</td>
                                            <!-- <td><i class="fa fa-file" data-toggle="modal" data-target="#exampleModalCenter"
                                                    aria-hidden="true"></i></td> -->
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div><br>



                    </div>
                    @endif
                </div>



                <div class="card mt-30 invoices hide">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Invoices</h5>
                    </div>
                    <div class="card-body">
                     <form id="myForm" action="{{ route('admin.payment.action') }}" method="POST">
                        @csrf
                        <div class="form-group col-md-3">
                            <label class="mr-2">@lang('Action')</label>
                            <select class="form-control" name="status" id="myselect">
                                <option value="" selected disabled>@lang('Select One')</option>
                                <option value="downloadinvoice">Download</option>

                            </select>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable js-exportable sorttableexcel">
                                <thead>
                                <tr>
                                    <th class="text-center"  data-field="hideexport">
                                        <label class="fancy-checkbox1" style="margin: 0;">
                                            <input class="select-all" type="checkbox" name="checkbox">

                                        </label>
                                    </th>
                                    <!--<th>id</th>-->
                                    <th>Invoice id</th>
                                    <th>Invoice Date</th>
                                    <th>Seller-id</th>
                                    <th>Seller</th>
                                    <th>Company</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>sub total</th>
                                    <th>IGST</th>
                                    <th>SGST</th>
                                    <th>CGST</th>
                                    <th>Total</th>
                                    @if($role_id =='1') <th>Received Amount</th> @endif 
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    
                                        @foreach($invoices as $in)
                                            @if($in->invoice_type =='n' || $in->invoice_type =='p')
                                                <tr>
                                                <td class="text-center">
                                                    <label class="fancy-checkbox{{ $in->id }}">
                                                        <input class="checkbox-tick" type="checkbox" name="id[{{ $in->id }}]"
                                                            value="{{ $in->id }}">
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <!--<td>{{$in->id}}</td>-->
                                                <td>{{$in->invoice_id}}</td>
                                                <td>{{$in->invoice_date}}</td>
                                                <td>{{$in->us_id}}</td>
                                                <td>{{$in->user_id}}</td>
                                                <td>{{$in->company_name}}</td>
                                                <td>{{$in->start_date}}</td>
                                                <td>{{$in->end_date}}</td>
                                                <td>{{$in->subtotal}}</td>
                                                <td>{{$in->igst}}</td>
                                                <td>{{$in->sgst}}</td>
                                                <td>{{$in->cgst}}</td>
                                                <td>{{$in->total}}</td>
                                                 @if($role_id =='1')
                                                 <td>
                                                     <?php $totalam = 0; ?>
                                                        @if(count($in->detail) > 0)
                                                            @foreach($in->detail as $in_d)
                                                                <?php $totalam += $in_d->amount; ?>
                                                            @endforeach
                                                            {{ $totalam }}
                                                        @else
                                                            -
                                                        @endif
                                                 </td> 
                                                 @endif 
                                                <td>{{$in->remarks}}</td>
                                                <td>
    <!--                                                <a href="{{ asset('public/invoice/invoice_'.$in->id.'.xlsx') }}" class="btn btn-danger"
                                                        title="Edit"><span class="sr-only">Edit</span> <i
                                                            class="fa fa-file-excel-o"></i></a>
                                                    <a href="{{ route('admin.billing.invoice_generate',$in->id) }}" class="btn btn-primary"
                                                        title="Edit"><span class="sr-only">Edit</span> <i
                                                            class="fa fa-file-pdf-o"></i></a>-->
                                                @if(($in->id >116 && $in->id <123))
                                                <a href="{{ asset('public/invoice/'.$in->invoice_id.'.xlsx') }}" class="btn btn-danger"
                                                        title="Excel"><span class="sr-only">Excel</span> <i
                                                            class="fa fa-file-excel-o"></i></a>
                                                    <a href="{{ asset('public/invoice/'.$in->invoice_id.'.pdf') }}" class="btn btn-primary"
                                                        title="PDF" download><span class="sr-only">PDF</span> <i
                                                            class="fa fa-file-pdf-o"></i></a>
                                                @else
                                                    @if(($in->id >=306 && $in->id <=309) || $in->id ==544 || ($in->id >=641 && $in->id <=655))
                                                    <a href="{{ asset('public/invoice/'.$in->invoice_id.'.xlsx') }}" class="btn btn-danger"
                                                        title="Excel"><span class="sr-only">Excel</span> <i
                                                            class="fa fa-file-excel-o"></i></a>
                                                    @else        
                                                    <a href="{{ asset('admin/billing/invoicedata/'.$in->id) }}" class="btn btn-danger"
                                                       title="Excel" target="_blank"><span class="sr-only">Excel</span> <i
                                                            class="fa fa-file-excel-o"></i></a>
                                                    @endif         
                                                    <a href="{{ route('admin.billing.invoice_generate',$in->id) }}" class="btn btn-primary"
                                                        title="PDF"><span class="sr-only">PDF</span> <i
                                                            class="fa fa-file-pdf-o"></i></a>
                                                @endif 
                                                
                                                @if($role_id =='1')
                                                <button type="button" class="btn btn-info edit-btn" title="Edit"
                                                                   data-edit='@json($in)'>
                                                                   <i
                                                            class="fa fa-plus"></i>
                                                               </button>
                                                @endif
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        
                                    
                                </tbody>
                            </table>

                        </div><br>
                     </form>   
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
                                            <!-- <input class="hidden" id="rem_id" type="text" name="rem_id"  value=""> -->
                                            
                                                <div class="form-group">
                                                    <label class="st_url">Invoice id</label>
                                                    <input class="form-control" id="inv_id" type="text" name="inv_id" required readonly
                                                        value="{{ old('inv_id') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="st_url">Amount Received*</label>
                                                    <input class="form-control" id="amount" type="text" name="amount" required
                                                        value="{{ old('amount') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="st_url">Utr*</label>
                                                    <input class="form-control" id="utr" type="text" name="utr" required
                                                        value="{{ old('utr') }}">
                                                </div>
                                                

                                              <div class="form-group"> <label for="">
                                                <input type="checkbox" name="term" required class="form-control" style="width: 22px;float: left; margin-top: 6px;"> I certify that the above information is true to the best of my knowledge</label> 
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
                    </div>   
                    <script>
                        $('select[name=status]').change(function() {
                            if ($('input[name^="id"]:checked').length > 0) {
                                var action_type = $('select[name=status]').val();
                                if(action_type == 'downloadinvoice'){
                                    let action_route = `{{ route('admin.payment.downloadinvoice') }}`;
                                    $('#myForm').attr("action", action_route);
                                    $('#myForm').submit();
                                }
                            } else {
                                toastr.error('Select atleast one invoice');
                                $('select[name=status]').val('');
                            }
                        });


                    </script>  
                    <script>
                        "use strict";
                            (function($) {
                                $(document).ready(function() {
                                    let myModal = new bootstrap.Modal(document.getElementById('my-modal'));
                                    let action = `{{ route('admin.payment.saveinvdetails') }}`


                                    $('.edit-btn').on('click', function(e) {
                                    let action = `{{ route('admin.payment.saveinvdetails', ':id') }}`;
                                    let data = $(this).data('edit');
                                    $('.modal-title').text("@lang('Add Received Money')");
                                    $("input[name=inv_id]").val(data.invoice_id);


                                    // }
                                    // $("select[name=country_id]").val(data.country_id);

                                    $('form').attr("action", action.replace(":id", data.id));
                                    myModal.show();

                                    });


                                });
                            })(jQuery);
                           
                    </script>
                </div>
                <div class="card mt-30 credit-notes hide">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Credit Notes</h5>
                    </div>
                    <div class="card-body">
                     <form id="myForm-cn" action="{{ route('admin.payment.action') }}" method="POST">
                        @csrf
<!--                        <div class="form-group col-md-3">
                            <label class="mr-2">@lang('Action')</label>
                            <select class="form-control" name="statuscrdit" id="myselect">
                                <option value="" selected disabled>@lang('Select One')</option>
                                <option value="downloadinvoice">Download</option>

                            </select>
                        </div>-->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable js-exportable sorttableexcel">
                                <thead>
                                <tr>
                                    <th class="text-center"  data-field="hideexport">
                                        <label class="fancy-checkbox1" style="margin: 0;">
                                            <input class="select-all" type="checkbox" name="checkbox">

                                        </label>
                                    </th>
                                    <!--<th>id</th>-->
                                    <th>Credit Note id</th>
                                    <th>Invoice Date</th>
                                    <th>Seller-id</th>
                                    <th>Seller</th>
                                    <th>Company</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>sub total</th>
                                    <th>IGST</th>
                                    <th>SGST</th>
                                    <th>CGST</th>
                                    <th>Total</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    
                                        @foreach($invoices as $in)
                                            @if($in->invoice_type =='c')
                                                <tr>
                                                <td class="text-center">
                                                    <label class="fancy-checkbox{{ $in->id }}">
                                                        <input class="checkbox-tick" type="checkbox" name="id[{{ $in->id }}]"
                                                            value="{{ $in->id }}">
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <!--<td>{{$in->id}}</td>-->
                                                <td>{{$in->invoice_id}}</td>
                                                <td>{{$in->invoice_date}}</td>
                                                <td>{{$in->us_id}}</td>
                                                <td>{{$in->user_id}}</td>
                                                <td>{{$in->company_name}}</td>
                                                <td>{{$in->start_date}}</td>
                                                <td>{{$in->end_date}}</td>
                                                <td>{{$in->subtotal}}</td>
                                                <td>{{$in->igst}}</td>
                                                <td>{{$in->sgst}}</td>
                                                <td>{{$in->cgst}}</td>
                                                <td>{{$in->total}}</td>
                                                <td>{{$in->remarks}}</td>
                                                <td>
    <!--                                                <a href="{{ asset('public/invoice/invoice_'.$in->id.'.xlsx') }}" class="btn btn-danger"
                                                        title="Edit"><span class="sr-only">Edit</span> <i
                                                            class="fa fa-file-excel-o"></i></a>
                                                    <a href="{{ route('admin.billing.invoice_generate',$in->id) }}" class="btn btn-primary"
                                                        title="Edit"><span class="sr-only">Edit</span> <i
                                                            class="fa fa-file-pdf-o"></i></a>-->
                                                @if($in->id ==594)
                                                <a href="{{ asset('public/invoice/'.$in->invoice_id.'.xlsx') }}" class="btn btn-danger"
                                                        title="Excel"><span class="sr-only">Excel</span> <i
                                                            class="fa fa-file-excel-o"></i></a>
                                                    
                                                @else
                                                         
                                                    <a href="{{ asset('admin/billing/invoicedata/'.$in->id) }}" class="btn btn-danger"
                                                       title="Excel" target="_blank"><span class="sr-only">Excel</span> <i
                                                            class="fa fa-file-excel-o"></i></a>
                                                        
                                                    
                                                @endif
                                                <a href="{{ route('admin.billing.invoice_generate',$in->id) }}" class="btn btn-primary"
                                                        title="PDF"><span class="sr-only">PDF</span> <i
                                                            class="fa fa-file-pdf-o"></i></a>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        
                                    
                                </tbody>
                            </table>

                        </div><br>
                     </form>   
                    </div>
                    <script>
                        $('select[name=statuscrdit]').change(function() {
                            if ($('input[name^="id"]:checked').length > 0) {
                                var action_type = $('select[name=statuscrdit]').val();
                                if(action_type == 'downloadinvoice'){
                                    alert(action_type);
                                    let action_route = `{{ route('admin.payment.downloadinvoice') }}`;
                                    $('#myFormmyForm-cn').attr("action", action_route);
                                    $('#myFormmyForm-cn').submit();
                                }
                            } else {
                                toastr.error('Select atleast one invoice');
                                $('select[name=statuscrdit]').val('');
                            }
                        });


                    </script>  
                </div>
            </div>


            <div class="card mt-30 wallet_transaction">
                <div class="card-header">
                    <h5 class="card-title mb-0">Wallet Transaction</h5>
                </div>
                <form action="{{ route('admin.billing.billing_info') }}" method="GET" style='padding: 16px;'>
                    <div class="row">
                        <div class="form-group col-md-8">
                            <label>Select Date Range</label><span class="required"> *</span>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input class="form-control " type="date" name="start_date" required="" value="{{explode(' ',$re_data['start_date'])[0]}}" id="_1">
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control " type="date" name="end_date" required="" value="{{explode(' ',$re_data['end_date'])[0]}}" id="_2">
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <x-button type="submit" name="Search" />
                    </div>
                </form>    
                <div class="card-body">


                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable js-exportable sorttableexceldatesortsecond">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Tracking ID</th>
                                    <th>AWB</th>
                                    <th>Credit</th> 
                                    <th>Debit</th>
                                    <th>Closing Blc</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction as $row)
                                <tr>
                                    <td class="date-column"><span class="fa fa-calendar"></span>&nbsp;
                                        {{ \Carbon\Carbon::parse($row->updated_at)->format('d M, Y') }}<br>
                                        <span class="fa fa-clock-o"></span>&nbsp;
                                        {{ \Carbon\Carbon::parse($row->updated_at)->format('H:i') }}
                                    </td>
                                    <td class="text-center"> 
                                        {{ $row->id }}
                                    </td>
                                    <td class="anchor-column">
                                        @if ($row->order_id !=0) 
                                        <a href="{{ route('admin.order.detail',$row->row_id ?? $row->order_id) }}">
                                            {{$row->awb}}
                                        </a>
                                        @else
                                            {{$row->awb}}
                                        @endif
                                    </td>
                                    <td style="color:green">{{$row->credit}}</td>
                                    <td style="color:red">
                                        @if ($row->debit !=0) 
                                        -
                                        @endif
                                        {{$row->debit}}</td>
                                    
                                    <td>{{ $row->closing_blc }}</td>
                                    <td>
                                        @if (strlen($row->remarks) >45)
                                            {{ substr($row->remarks,0,44) }} ...
                                        @else
                                            {{$row->remarks}}
                                        @endif
                                    </td>
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
    </div>




    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="width:132%;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        Freight Charges Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-hover dataTable js-exportable">
                        <thead>

                            <tr>
                                <th>Generated Date</th>
                                <th>Category</th>
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td> Jan 4, 2024, 11:32 </td>
                                <td> Forward Charges </td>
                                <td> Rs 0.00 </td>
                                <td> Rs 38.94 </td>
                                <td> Forward Charges Applied </td>

                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
     
@endsection

