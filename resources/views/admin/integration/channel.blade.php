@extends('admin.admin_layouts')
@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp
<style>
    .courier ul li {
        list-style : none;
    }

    label.radio-card {
    cursor: pointer;
    }
    

</style>
    <!-- Main body part  -->
    <div class="container-fluid">
        <!-- Page header section  -->
        <div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-8 col-sm-12">
                <h2>Channel Integration</h2>
            </div>
            
        </div>
    </div>
        <div class="row clearfix">
            <div class="col-12">

                
                <div class="card mt-30 channel">
                <div class="header d-flex justify-content-between" style="padding-bottom: 0;">
                                <h2>Select Channel and fill the credentials</h2>
                            </div>
                    <div class="card-body">
                        
                    <!-- <h4></h4> -->
                        <div class="row" style="padding:10px;">
                            <div class="card col-4 channel" style="background-color: #e7e5e5;border-radius: 10px;">
                                <div class="row" style="padding: 20px;">
                                    @foreach($data['channel'] as $key => $row)
                                        <label for="radio-card-{{ $loop->iteration }}" class="card channel radio-card channelselect" style="margin-bottom:10px;border-radius:10px;">
                                            <div class="row">
                                                <span class="check-icon"></span>
                                                <div class="col-1"><input type="radio" name="channel" value="channel_{{ $key }}" id="radio-card-{{ $loop->iteration }}" @if($loop->iteration == 1) checked  @endif / style="margin:10px"></div>
                                                <div class="col-2"><img src="{{ asset('public/channel/'.$row['image']) }}" alt="" style="margin: 10px;max-width:50px"></div>
                                                <div class="col-9" style="margin-top: 25px;"><h4>{{ $row['channel'] }}</h4></div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card col-8 channel" style="background-color: #e7e5e5;border-radius: 10px;">
                                <div class="card mt-30 channel">
                                    <div class="card-header" style="padding-bottom:30px">
                                        <h5 class="card-title mb-0">General Info
                                        <button type="button" class="btn btn-primary add-btn h-45" style="float:right">
                                                    <i class="las la-plus"></i>@lang('Add New')
                                                </button>
                                        </h5>
                                       
                                                
                                            
                                    </div>
                                    <div class="card-body">
                                        @foreach($data['channel'] as $key => $row)
                                            <div class="channel_{{ $key }} @if($loop->iteration !=1) hide  @endif">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover dataTable js-exportable" width="100%" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('Store_Url')</th>
                                                            <th>@lang('Store_token')</th>
                                                            <th>@lang('status')</th>
                                                            <th>@lang('Action')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse($data['channel_data'] as $key => $dc)
                                                    @if($dc->channel_id == $row->id)
                                                    <tr>
                                                        <td>{{$dc->store_name}}</td>
                                                        <td>{{$dc->store_access}}</td>
                                                      	<td>
                                                            @if($dc->status =='1')
                                                                In-active
                                                            @elseif($dc->status =='2')
                                                                Active
                                                            @elseif($dc->status =='3')
                                                                Wrong Info
                                                            @elseif($dc->status =='4')
                                                                Deleted
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($dc->user_id == auth()->guard('admin')->user()->id)
                                                            <button type="button" class="btn btn-sm edit-btn btn-success" title="Edit"
                                                                data-edit='@json($dc)'>
                                                                <b>Edit</b>
                                                            </button>
                                                                <a href="{{ URL::to('integration/distroy_channel/' . $dc->id) }}" title="Delete"
                                                                onClick="return confirm('Are you sure?');" class="btn btn-danger btn-sm"><b>Delete</b>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @empty
                                                        <tr>
                                                            <td class="text-muted text-center" colspan="100%">No Data Found</td>
                                                        </tr>
                                                    @endforelse
                                                    </tbody>
                                                    </table>
                                                   
                                                </div>
                                            </div>
                                        
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>   
<!--                        <div class="video-container"> 
                            <iframe width="320" height="180" src="https://www.youtube.com/embed/n2thn62SNrw?start=55" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> 
                            <iframe width="320" height="180" src="https://www.youtube.com/embed/CaT7wtGDT-s?start=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> 
                        </div> -->
                    </div>
                    <div id="my-modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog" role="document" style="max-width: 1000px;top:20%">
                            <div class="modal-content">
                                <div class="modal-header card-header">
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
                                                <div class="form-group">
                                                    <label>@lang('Channel')</label>
                                                    <select name="channel_id" class="form-control" required>
                                                        <option> Select Channel </option>
                                                        @foreach ($data['channel'] as $ch)
                                                        <option value="{{ $ch->id }}">{{ $ch->channel }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="st_url">@lang('Store_Url')*</label>
                                                    <input class="form-control" id="store_name" type="text" name="store_name" required
                                                        value="{{ old('store_name') }}">
                                                </div>
                                                
                                                
                                                <div class="form-group">
                                                    <label  class="cs_key">@lang('Consumer_key')*</label>
                                                    <input class="form-control" type="text" name="customer_key" required
                                                        value="{{ old('customer_key') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="st_token">@lang('Store_token')*</label>
                                                    <input class="form-control" type="text" name="store_access" required
                                                        value="{{ old('store_access') }}">
                                                </div>

                                              <div class="form-group"> <label for="">
                                                <input type="checkbox" name="term" required class="form-control" style="width: 22px;float: left; margin-top: 6px;"> I certify that the above information is true to the best of my knowledge</label> 
                                              </div> 
                                              
                                            </div>
                                        </div>

                                        
                                    </div>
                                    <div class="col-lg-4 mb-4">
                                        <button type="submit" class="btn btn-secondary h-45 w-100">@lang('Submit')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <script>
                    "use strict";
                    (function($) {
                        $(document).ready(function() {
                            let myModal = new bootstrap.Modal(document.getElementById('my-modal'));
                            let action = `{{ route('admin.integration.save') }}`

                            $('.add-btn').on('click', function(e) {

                                $('.modal-title').text("@lang('New Channel')");
                                $('.myform').trigger("reset");
                                $('.myform').attr("action", action);
                                // Ensure the modal is initialized as a Bootstrap modal
                                myModal.show();
                            });

                            $('.edit-btn').on('click', function(e) {
                            let action = `{{ route('admin.integration.save', ':id') }}`;
                            let data = $(this).data('edit');
                            $('.modal-title').text("@lang('Update Channel')");
                            $("input[name=store_access]").val(data.store_access);
                            $("input[name=store_name]").val(data.store_name);
                            $("input[name=customer_key]").val(data.customer_key);
                            $("select[name=channel_id]").val(data.channel_id);
                            if(data.channel_id ==1){
                                $('.st_token').text("Store Token*");
                                $('.cs_key').text("Api key*");
                                $('.st_url').html('Store URL* <span style="color:red">(if url is <b>https://1d95f6-4.myshopify.com</b> then use only <b>1d95f6-4</b>)</span>');
                            }else if(data.channel_id ==2){
                                $('.st_token').text("Consumer secret*");
                                $('.cs_key').text("Customer key*");
                                $('.st_url').html('Store URL*<span style="color:red">(if url is <b>https://demo.com/</b> then use only <b>https://demo.com/</b>)</span>');
                            }
                            
                            // }
                            // $("select[name=country_id]").val(data.country_id);

                            $('form').attr("action", action.replace(":id", data.id));
                            myModal.show();
                            
                        });


                        });
                    })(jQuery);

                    $('select').on('change', function() {
                        if(this.value ==1){
                            $('.st_token').text("Store Token*");
                            $('.cs_key').text("Api key*");
                            $('.st_url').html('Store URL* <span style="color:red">(if url is <b>https://1d95f6-4.myshopify.com</b> then use only <b>1d95f6-4</b>)</span>');
                        }else if(this.value ==2){
                            $('.st_token').text("Consumer secret*");
                            $('.cs_key').text("Consumer key*");
                            $('.st_url').html('Store URL*<span style="color:red">(if url is <b>https://demo.com/</b> then use only <b>https://demo.com/</b>)</span>');
                        }
                        });
                </script>
                </div>




               
            </div>
        </div>
    </div>



    <script>
        "use strict";
        (function($){
            $('.channelselect').on('change', function(){
                let channel = $("input[name='channel']:checked").val(); 
                $('[class^="channel_"]').addClass('hide');
                $('.' + channel).removeClass('hide');
            });
        })(jQuery);
    </script>

@endsection


