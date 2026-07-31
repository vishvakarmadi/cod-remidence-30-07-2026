@extends('admin.admin_layouts')

@section('admin_content')
<style>

.canvasjs-chart-credit
{
    display: none !important;
}
 </style> 
  <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>


<div id="navbar-animmenu">
    <ul class="show-dropdown main-navbar">
        <div class="hori-selector" style="margin-left: 20px;">
            <div class="left"></div>
            <div class="right"></div>
        </div>
        <!-- <li>
            <a href="{{ route('admin.shipping_charges') }}" data-id="shipping_charges">Shipping Charges</a>
        </li> -->
        <li>
            <a href="{{ route('admin.invoices') }}" data-id="invoices">Invoices</a>
        </li>
        <li class="active">
            <a href="{{ route('admin.credit_notes') }}" data-id="credit-notes">Credit Notes</a>
        </li>
        <li>
            <a href="{{ route('admin.wallet_transaction') }}" data-id="wallet_transaction">Wallet Transaction</a>
        </li>
    </ul>
</div>
<div class="card mt-30 credit-notes">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Credit Notes</h5>
                    </div>
                    <div class="card-body">
                     <form id="myForm-cn" action="{{ route('admin.payment.action') }}" method="POST">
                        @csrf
                       <!-- <div class="form-group col-md-3">
                            <label class="mr-2">@lang('Action')</label>
                            <select class="form-control" name="statuscrdit" id="myselect">
                                <option value="" selected disabled>@lang('Select One')</option>
                                <option value="downloadinvoice">Download</option>

                            </select>
                        </div> -->
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



@endsection
