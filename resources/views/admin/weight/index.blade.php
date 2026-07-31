@extends('admin.admin_layouts')
@section('admin_content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<style>
.btn-group{
    display: block;
}
.btn-group .multiselect{
    width:100%
}
.multiselect-container
, .multiselect-container>li>a>label.checkbox {
    width: 100%;
}
textarea {
  width: 100%;
  height: 80px;
  padding: 12px 20px;
  box-sizing: border-box;
  border: 2px solid #ccc;
  border-radius: 4px;
  background-color: #f8f8f8;
  font-size: 16px;
  resize: none;
}
.badge{
    white-space: break-spaces;
}
</style>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
@php 
$looged_user = Auth::guard('admin')->user();
@endphp
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xl-12">
            <div class="card  mb-3">
                <div class="card-header" style="display:flex;flex-wrap: wrap;">
                    <!-- <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Filter</h5> -->
                    <div class="col-md-9"> <a href="javascript:void(0)" class="expand">Filters <?php if(empty($re_data)){ echo '<<';}else{ echo '>>';} ?></a></div>
                    @if($looged_user->role_id =='1')
                    <div class="col-md-3">
                        <x-button type="import" route="{{ route('admin.weight.create') }}" name="Import"/>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.weight') }}" method="GET">
                        <div class="col-md-12">
                            <div class="show_more" style="width: 100%;">
                                <?php
                                $o_st =0;
                                if(!empty($re_data)){
                                    $o_st = $re_data['extra_weight_status'];
                                    $c_id = $re_data['courier_id'];
                                }
                                ?>
                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <label>Select Date Range</label><span class="required"> *</span>    <div class="row">
                                            <div class="col-md-6">
                                                <input class="form-control " type="date" name="start_date" required="" value="{{explode(' ',$re_data['start_date'])[0]}}" id="_1">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control " type="date" name="end_date" required="" value="{{explode(' ',$re_data['end_date'])[0]}}" id="_2">
                                            </div>
                                                </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">Courier</label>
                                        <select name="courier_id" class="form-control">
                                            <option value='0' >Courier</option>
                                            <option value='1' <?php if($c_id =='1'){echo 'selected';} ?> >Ecom Express</option>
                                            <option value='2' <?php if($c_id =='2'){echo 'selected';} ?> >Delhivery</option>
                                            <option value='3' <?php if($c_id =='3'){echo 'selected';} ?> >Bludart</option>
                                            <option value='4' <?php if($c_id =='4'){echo 'selected';} ?> >XpressBees</option>
                                            <option value='5' <?php if($c_id =='5'){echo 'selected';} ?> >DTDC</option>
                                            <option value='6' <?php if($c_id =='6'){echo 'selected';} ?> >Smartr</option>
                                            <option value='7' <?php if($c_id =='7'){echo 'selected';} ?> >Ekart</option>
                                            <option value='8' <?php if($c_id =='8'){echo 'selected';} ?> >Shadowfax</option>
                                            <option value='9' <?php if($c_id =='9'){echo 'selected';} ?> >ATS</option>
                                            
                                            
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-control-label">User<span class="required">*</span></label>
                                        <!-- <input mbsc-input id="my-input" data-dropdown="true" data-tags="true" /> -->
                                        <select id="multiple-checkboxes" name="user_id[]" class="form-control" required multiple="multiple">
                                            @foreach($users as $user)
                                                <option value='{{$user->id}}' <?php if(empty($re_data['user_id']) || in_array($user->id,$re_data['user_id'])){echo 'selected';} ?> >{{$user->name}}</option>
                                            @endforeach
                                            
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-control-label">Status</label>
                                        <select name="extra_weight_status" class="form-control">
                                            <option value='0' >All</option>
                                            <option value='1' <?php if($o_st =='1'){echo 'selected';} ?> >New</option>
                                            <option value='2' <?php if($o_st =='2'){echo 'selected';} ?> >Closed in Seller favor</option>
                                            <option value='3' <?php if($o_st =='3'){echo 'selected';} ?> >Auto Accepted</option>
                                            <option value='4' <?php if($o_st =='4'){echo 'selected';} ?> >Closed in Client favor</option>
                                            
                                            
                                        </select>
                                    </div>
                                    <x-field type="text" size="col-md-3" label="AWB" placeholder="AWB" name="tracking_info" value="{{$re_data['tracking_info']}}" />
                                    <x-field type="text" size="col-md-3" label="SKU" placeholder="SKU" name="sku" value="{{$re_data['sku']}}" />
                                    
                                </div>
                                <div class="row">
                                    <x-button size="col-lg-3" type="submit" name="Search" />
                                </div>
                                <script>
                                    $(document).ready(function() {
                                        $('#multiple-checkboxes').multiselect({
                                        includeSelectAllOption: true,
                                        });
                                    });
                                </script>
                            </div>
                           
                        </div>
                    </form>
                </div>    
            </div>
            
        </div>
        <div class="col-xl-12">
            <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
            @csrf
                <div class="card new_orders">
                    <div class="header d-flex justify-content-between">
                        @if($looged_user->role_id =='1')
                            <div class="form-group col-md-3">
                                <label class="mr-2">@lang('Action')</label>
                                <select class="form-control" name="status" id="myselect">
                                    <option value="" selected disabled>@lang('Select One')</option>
                                    <!--<option value="downloadfile">Download Supported files</option>-->
                                    <option value="seller">Closed in Seller favor</option>
                                    <option value="client">Closed in Client favor</option>
                                    
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="closing_description" class="form-control-label">Closing Description</label><span class="required"> *</span>:<br>
                                <textarea name="closing_description" id="textareaID" required></textarea>
                            </div>
                            <input type="hidden" name ='path' value="view">
                        @endif    
                    </div>
                    <div class="">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover sorttableexceldate" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center"  data-field="hideexport">
                                                <label class="fancy-checkbox">
                                                        <input class="select-all" type="checkbox" name="checkbox">
                                                        <span></span>
                                                </label>
                                            </th>
                                            <th>Weight Applied Date</th>
                                            <th>Seller</th>
                                            <th>AWB</th>
                                            <th>Courier</th>
                                            <th>Order_ID</th>
                                            <!--<th>SKU</th>-->
                                            <th>Entered Weight</th>
                                            <th>Applied Weight</th>
                                            @if($looged_user->role_id =='1')
                                            <th>Courier Used</th>
                                            <th>Rate</th>
                                            <th>Additional rate</th>
                                            <th>Initial Cost</th>
                                            @endif
                                            <th>Extra Weight Charges</th>
                                            <th>Status</th>
                                            <th>Description</th>
                                             <th>Supported file</th>
                                            <th data-field="hideexport">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order as $row)
                                            <tr>
                                                <td class="text-center anchor-column">
                                                    <label class="fancy-checkbox">
                                                        <input class="checkbox-tick" type="checkbox" name="id[{{ $row->id }}]" value="{{ $row->id }}">
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($row->extra_weight_added_on)->format('d M, Y') }}   {{ \Carbon\Carbon::parse($row->extra_weight_added_on)->format('H:i') }}
                                                    
                                               </td>
                                                <td>{{ substr($row->user_id,0,13) }}</td>
                                                <td>{{ $row->tracking_info }}</td>
                                                <td>
                                                @if($row->ship_courier_id)    {{  $couriers[$row->ship_courier_id]['name']}} @endif
                                                </td>
                                                <td>{{ $row->vendor_order_id }}</td>
<!--                                                <td>
                                                    @php 
                                                    $codes =''
                                                    @endphp
                                                    @foreach($row->detail as $detail)
                                                    @php
                                                    $codes .= $detail->code.',';
                                                    @endphp
                                                    @endforeach
                                                    {{rtrim($codes,',')}}
                                                </td>-->
                                                <td>{{ $row->shipping_courier_weight }}</td>
                                                <td>{{ $row->extra_weight/1000 }} kg</td>
                                                @if($looged_user->role_id =='1')
                                                <td>{{$row->shipping_courier_weight_used}}</td>
                                                <td>{{$row->rate}}</td>
                                                <td>{{$row->rateadd}}</td>
                                                <td>{{$row->freight+$row->gst_freight}}</td>
                                                @endif
                                                <?php 
                                                $addedon = \Carbon\Carbon::parse($row->extra_weight_added_on)->addDays(7)->format('Y-m-d');
                                                $now = time();
                                                $datediff = strtotime($addedon) - $now;
                                                ?>
                                                
                                                @if(strip_tags($row->extra_weight_status) !='Open')
                                                    <td>{{ $row->extra_weight_cost }}</td>
                                                    <td  class="special-column">
                                                        {!! $row->extra_weight_status !!}
                                                        @if(strip_tags($row->extra_weight_status) =='New')
                                                        <br><span style="color:red"> {{round($datediff / (60 * 60 * 24))}} day(s) left
                                                        </span>
                                                        @else
<!--                                                      <br><span style="color:red">Closed on <b>{{ \Carbon\Carbon::parse($row->extra_weight_closed_on)->format('d M, Y') }}</b>
                                                        </span>-->
                                                        @endif
                                                    </td>
                                                @else 
                                                    <td>Counting..</td>
                                                    <td class="special-column">
                                                        <span class="badge text-white bg-info">New</span>
                                                    <br><span style="color:red"> {{round($datediff / (60 * 60 * 24))}} day(s) left</span>
                                                    </td>
                                                @endif
                                                <td style='white-space: unset;'>
                                                    @php 
                                                        $desc ='';$link ='';
                                                    @endphp
                                                    @foreach($row->reconciliation as $reconciliation)
                                                        <?php
                                                            if ($reconciliation->description !='Auto Closed after 7 days'){
                                                                $desc .= $reconciliation->description.',';
                                                                if($reconciliation->file_1 !=''){
                                                                    $link .= "<a href=\"https://hyloship.com/public/uploads/{$reconciliation->file_1}\">{$reconciliation->file_1}</a>";
                                                                }
                                                            }
                                                            
                                                       ?>
                                                    @endforeach
                                                    {{rtrim($desc,',')}}
                                                </td>
                                                <td class="">
                                                     @foreach($row->reconciliation as $reconciliation)
                                                    <?php
                                                    if ($reconciliation->description !='Auto Closed after 7 days'){ ?>
                                                    <?php if($reconciliation->file_1 !=''){ ?>
                                                            <a href="{{ asset('public/uploads/'.$reconciliation->file_1) }}" >{{$reconciliation->file_1}}</a>
                                                    <?php  } ?>
                                                    <?php if($reconciliation->file_2 !=''){ ?>
                                                            <a href="{{ asset('public/uploads/'.$reconciliation->file_2) }}" >{{$reconciliation->file_2}}</a>
                                                    <?php  } ?>
                                                    <?php if($reconciliation->file_3 !=''){ ?>
                                                            <a href="{{ asset('public/uploads/'.$reconciliation->file_3) }}" >{{$reconciliation->file_3}}</a>
                                                    <?php  } ?>
                                                    <?php if($reconciliation->file_4 !=''){ ?>
                                                            <a href="{{ asset('public/uploads/'.$reconciliation->file_4) }}" >{{$reconciliation->file_4}}</a>
                                                    <?php  } ?>        
                                                    
                                                    <?php } ?>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if(count($row->reconciliation) !=0)
                                                        <a href="{{ route('admin.weight.view',$row->id) }}" class="btn btn-secondary"
                                                        
                                                        data-title="View"><i class="fa fa-eye"></i></a>
                                                        @endif 
                                                        @if (in_array(strip_tags($row->extra_weight_status),array('Open','New')))
                                                            <a href="{{ route('admin.weight.add',$row->id) }}" class="btn btn-primary"
                                                            data-title="Add Details"><i class="fa fa-plus"></i> </a>
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
            </form>    
        </div>
    </div>
</div>
<script type="text/javascript">
     $('select[name=status]').change(function() {
        if ($('input[name^="id"]:checked').length > 0) {
            var action_type = $('select[name=status]').val();
            if(action_type == 'seller' || action_type == 'client' ){
                let action_route = `{{ route('admin.weight.action') }}`;
                $('#myForm').attr("action", action_route);
            
                if ($("#textareaID").val() != "") {
                $('#myForm').submit();
                }else{
                    toastr.error('Please add Closing Description');
                    $('select[name=status]').val('');
                }
            }else{
                if(action_type == 'downloadfile'){
                    let action_route = `{{ route('admin.weight.action') }}`;
                    $('#myForm').attr("action", action_route);
                    $('#myForm').submit();
                }
            }
        } else {
            toastr.error('Select atleast one Order');
            $('select[name=status]').val('');
        }
    });
    $('.expand').on('click',function() {
            if ($(this).text() == 'Filters>>') {
                $(this).text('Filters<<');
            } else {
                $(this).text('Filters>>');
            }
            $('.show_more').slideToggle('fast');
        });

</script>
@endsection
