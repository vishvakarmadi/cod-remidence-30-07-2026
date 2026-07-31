@extends('admin.admin_layouts')
@section('admin_content')


<style>
    label {
    font-size: 0.85rem;
    font-weight: 500;
    margin-bottom: -2px;
}
</style>
    <div class="row">

        <div class="col-lg-12">
        <form action="{{ route('admin.role.update',$role->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card responsive-filter-card mb-4">
                <div class="card-body">
                        <div class="d-flex flex-wrap gap-4">
                            <div class="flex-grow-1">
                                <label>@lang('Role')</label>
                                <input type="text" name="name" value="{{ $role->name }}" @if($role->id == 1 || $role->id == 2) readonly @endif class="form-control">
                            </div>&nbsp;
                        </div>
                </div>
            </div>


            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th class="text-left">@lang('Module')</th>
                                <th class="text-center">
                                    <label class="fancy-checkbox1">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <label>Give All Permissions</label>
                                    </label>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($prefix as $module)
                                @php 
                                    $matchedPermissions = array_filter($permissions, function($permission) use ($module) {
                                                        return strpos($permission, $module) !== false;
                                                    });
                                @endphp
                                <tr>
                                    <td class="text-left"><strong>{{ ucfirst($module) }}</strong></td>
                                    <td>
                                        <div class="row text-left">
                                            @foreach($matchedPermissions as $key => $per)
                                                @php
                                                    $name = strtok($per, ' ');
                                                    $isChecked = $permit->contains($key);
                                                 @endphp
                                                <div class="col-md-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input checkbox-tick" id="permission{{ $key }}" name="permissions[]" value="{{ $key }}" {{ $isChecked ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="permission{{ $key }}">{{ $name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn--primary btn h-45 w-100">@lang('Submit')</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>


@endsection
