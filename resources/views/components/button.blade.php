@props(['route' => '','type' => '','name' => '','size' =>''])


@if($type == 'create')
<a href="{{ $route }}" class="btn btn-dark">
    <i class="fa fa-plus"></i> {{ @$name }}
</a>
@endif

@if($type == 'import')
<a href="{{ $route }}" class="btn btn-primary">
    <i class="fa fa-upload"></i> {{ @$name }}
</a>
@endif


@if($type == 'submit')
<div class="{{ $size ?? 'col-md-12' }}">
    <button type="submit" class="btn btn-success" style="width:100%">{{ @$name }}</button>
</div>
@endif
{{-- <a href="{{ $route }}" class="btn {{ $class }} btn-outline--primary">
    <i class="la la-undo"></i>@lang('Back')
</a> --}}
