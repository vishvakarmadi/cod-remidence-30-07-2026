@extends('admin.admin_layouts')
@section('admin_content')
<style>

.canvasjs-chart-credit
{
    display: none !important;
}
 </style> 
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

  <div class="container-fluid">

<div class="container-fluid">
        <div class="row clearfix">
            <div class="col-12">

<div id="navbar-animmenu">
    <ul class="show-dropdown main-navbar">
        <div class="hori-selector" style="margin-left: 20px;">
            <div class="left"></div>
            <div class="right"></div>
        </div>
        <!-- <li >
            <a href="{{ route('admin.shipping_charges') }}" data-id="shipping_charges">Shipping charges</a>
        </li> -->
        <li class="active">
            <a href="{{ route('admin.invoices') }}" data-id="invoices">Invoices</a>
        </li>
        <li>
            <a href="{{ route('admin.credit_notes') }}" data-id="credit-notes">Credit Notes</a>
        </li>
        <li>
            <a href="{{ route('admin.wallet_transaction') }}" data-id="wallet_transaction">Wallet Transaction</a>
        </li>
    </ul>
</div>
<div class="card top_report card mt-30 Overview hide">
                    Please wait.. Data is loading
                </div>

                <div class="card mt-30 channel hide">
                    Please wait.. Data is loading
                </div>

                <div class="card mt-30 invoices ">
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
                                        <label class="fancy-checkbox">
                                                <input class="select-all" type="checkbox" name="checkbox">
                                                <span></span>
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
                                                    <label class="fancy-checkbox">
                                                        <input class="checkbox-tick" type="checkbox" name="id[{{ $in->id }}]" value="{{ $in->id }}">
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
                                                   <!-- <a href="{{ asset('public/invoice/invoice_'.$in->id.'.xlsx') }}" class="btn btn-danger"
                                                        title="Edit"><span class="sr-only">Edit</span> <i
                                                            class="fa fa-file-excel-o"></i></a>
                                                    <a href="{{ route('admin.billing.invoice_generate',$in->id) }}" class="btn btn-primary"
                                                        title="Edit"><span class="sr-only">Edit</span> <i
                                                            class="fa fa-file-pdf-o"></i></a> -->
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
                    </div>
                    <script>
                        
                        $('select[name="status"]').change(function() {
    if ($('input[name^="id"]:checked').length > 0) {
        var action_type = $(this).val();
        if (action_type == 'downloadinvoice') {
            let action_route = `{{ route('admin.payment.downloadinvoice') }}`;
            console.log('Form submitting to:', action_route); // Debugging line
            $('#myForm').attr("action", action_route);
            $('#myForm').submit();
        }
    } else {
        toastr.error('Select at least one invoice');
        $(this).val('');
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
              

@endsection
