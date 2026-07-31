@extends('admin.admin_layouts')
@section('admin_content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
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
    </style>
<div class="row ">
    <div class="col-xl-12">
        <form id="form_submit" action="{{ route('admin.broadcast.store') }}" method="POST">
             @csrf
             <div class="col-12">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@if(isset($broadcast)) Edit @else
                            Add @endif
                            Broadcast<a data-action="collapse" class="float-right toggle-icon"><i class="fa fa-minus"></i></a></h5>
                    </div>
                    @if(isset($broadcast)) 
                    <input type="hidden" value="{{$broadcast->id}}" name="b_id">
                    @endif
                    <input type="hidden" name="company_id" value="{{ Auth::guard('admin')->user()->company_id }}">
                    <div class="card-body">
                        <div class="form-group col-md-12">
                            <label>Select Date Range</label><span class="required"> *</span>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input class="form-control " type="date" name="from_date" required="" @if(isset($broadcast)) value="{{$broadcast->from_date}}" @else value="" @endif id="_1">
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control " type="date" name="to_date" required="" @if(isset($broadcast)) value="{{$broadcast->to_date}}" @else value="" @endif id="_2">
                                    </div>
                                </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div  class="row">
                                <div class="col-md-6">
                                    <label>Users</label><span class="required"> *</span>
                                    <select id="multiple-checkboxes" name="user_id[]" class="form-control" required multiple="multiple">
                                        @foreach($users as $user)
                                        <option value='{{$user->id}}'  <?php if(isset($broadcast) && in_array($user->id, explode(',', $broadcast->user_id))){echo 'selected';} ?>>{{$user->name}}</option>
                                        @endforeach

                                    </select>
                                </div>
                                 <div class="col-md-6">
                                    <label>Message</label><span class="required"> *</span>
                                     <input class="form-control" type="text" name="message" required @if(isset($broadcast)) value="{{$broadcast->message}}" @else value="" @endif>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <hr>
                    <div class="col-lg-4 mb-4">
                        
                                <button type="submit" class="btn-primary btn h-45 w-100 ">Submit</button>
                        
                    </div>
                </div>
             </div>    
             
        </form>
    
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#multiple-checkboxes').multiselect({
        includeSelectAllOption: true
        });
    });
</script>
@endsection