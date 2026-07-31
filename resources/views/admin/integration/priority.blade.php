@extends('admin.admin_layouts')
@section('admin_content')




    <!-- Main body part  -->
    <div class="container-fluid">
        <!-- Page header section  -->
        <div class="block-header">
            <div class="row">
                <div class="col-lg-5 col-md-8 col-sm-12">
                <h2>Courier Priority</h2>
                </div>
                
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-12">
                <div class="card">
                    
                    <div class="body row">
                        <?php if(!empty($couriers)){ ?>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <form class="product-form" action="{{ route('admin.priority.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf <!-- {{ csrf_field() }} -->
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Priority</th>
                                                    <th>Courier Name</th>
                                                </tr>   
                                            </thead>

                                            <tbody>
                                                @for ($i =1; $i <= 4; $i++)
                                                <?php $j = $i-1; ?>
                                                    <tr>
                                                        <td class="text-center">{{ $i }}</td>
                                                        <td> 
                                                            <div class="form-group col-md-8">
                                                                <select class="form-control" name="{{$j}}" required>
                                                                    <option value="">Select Courier</option>
                                                                    @foreach ($couriers as $key => $courier)
                                                                        <?php if(in_array($key.'surface',$c_array)){ ?>
                                                                            <option value="{{ $key }}_S"
                                                                                <?php
                                                                                    if(!empty($usercourier_priority)){
                                                                                        $use_pr_array = explode('_',$usercourier_priority[$j]);
                                                                                        if(count($use_pr_array) == 2 && strtoupper($couriers[$use_pr_array[0]]['name']) == strtoupper($courier['name']) && $use_pr_array[1] =='S'){
                                                                                            echo 'selected';
                                                                                        }
                                                                                    }    
                                                                                ?>
                                                                            >
                                                                                {{ $courier['name'] }} - Surface
                                                                            </option>
                                                                        <?php } if(in_array($key.'air',$c_array)  && $key !='3'){ ?>   
                                                                            <option value="{{ $key }}_A"
                                                                                <?php
                                                                                    if(!empty($usercourier_priority)){
                                                                                        $use_pr_array = explode('_',$usercourier_priority[$j]);
                                                                                        if(count($use_pr_array) == 2 && strtoupper($couriers[$use_pr_array[0]]['name']) == strtoupper($courier['name']) && $use_pr_array[1] =='A'){
                                                                                            echo 'selected';
                                                                                        }
                                                                                    }   
                                                                                ?>
                                                                            >
                                                                                {{ $courier['name'] }}- Air
                                                                            </option>
                                                                        <?php } ?>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endfor     
                                            </tbody>
                                        </table>
                                        <div class="col-12">
                                            <button type="submit" class="btn-primary btn w-100">Submit</button>
                                        </div>
                                    </form>   
                                </div>
                            </div>
                        <?php }else{ ?>
                                <div class="col-md-12 col-red" >No Courier Available for this Company</div>
                        <?php } ?>
                    </div>
            </div>
        </div>
    </div>
</div>


      
@endsection