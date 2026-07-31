@extends('admin.admin_layouts')
@section('admin_content')


<div class="container-fluid">
<form action="{{ url('admin/role/access-setup-update/'.$role->id) }}" method="post">
        @csrf
                    <!-- Page header section  -->
                    <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <h1>Roles</h1>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                                <div class="row clearfix">
                                    <div class="col-xl-5 col-md-5 col-sm-12"></div> 
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="row clearfix">
                    <div class="col-lg-12">
                            <div class="card">
                            <table class="table table-bordered">

                        @php $i=0; @endphp
                        @foreach($role_permissions as $row)
                            <input type="hidden" name="role_permission_ids[@php echo $i; @endphp]" value="{{ $row->id }}">
                            <input type="hidden" name="access_status_arr[@php echo $i; @endphp]" value="0">

                            <tr>
                                <td class="w_50">
                                    <input type="checkbox" name="access_status_arr[@php echo $i; @endphp]" value="1" @if($row->access_status == 1) @php echo 'checked'; @endphp @endif>
                                </td>
                                <td>
                                    @php
                                        $role_page_data = DB::table('role_pages')->where('id', $row->role_page_id)->first();
                                    @endphp

                                    {{ $role_page_data->page_title }}
                                </td>
                            </tr>
                            @php $i++; @endphp

                        @endforeach

                        </table>

                            <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </div>
                    </div>
</form>
                </div>



    

@endsection
