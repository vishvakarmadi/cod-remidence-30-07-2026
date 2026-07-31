@props(['type' => '','size','class','label' => '','required' => '','name' => '','value' => '','id' => '','options' => '','placeholder' => '','store' => '', 'print' => '','index' => '','src' => ''])

@if($type=='text' || $type=='number' || $type=='email' || $type=='file' || $type=='password' || $type=='date' || $type=='time' || $type=='color' || $type=='hidden' || $type=='search' || $type=='url' || $type=='tel' || $type=='datetime-local' || $type=='month' || $type=='week' || $type=='datetime' || $type=='datetime-local' || $type=='range')
{{-- <x-field type="text" class="class" label="Text" size="" value="tag-1" name="text" required="" id=""/> --}}
<div class="form-group  {{ @$size ?: 'col-md-4' }} ">
    <label>{{ @$label }}</label>@if(@$required)<span class="required"> *</span>@endif
    <input class="form-control {{ @$class }}" placeholder="{{ @$placeholder }}" type="{{ @$type }}" name="{{ @$name }}" @if(@$required) required @endif value="{{ @$value }}" id="{{ @$id }}">
</div>
@endif

@if($type == 'tags')
{{-- <x-field type="tags" class="class" label="Tags" size="" value="tag-1" name="tag" required="" id=""/> --}}
<div class="form-group {{ @$size ?? 'col-md-4' }}">
    <label>{{ @$label }}</label>@if(@$required)<span class="required"> *</span>@endif
    <input type="text" class="inputTag {{ @$class }}" placeholder="{{ @$placeholder }}" name="{{ @$name }}" value="{{ @$value }}" data-role="tagsinput" @if(@$required) required @endif>
</div>
@endif

@if($type=='date-range')
@php($date = explode('-',$name))
@php($v = explode('/',$value))
@php($clas = explode('-',@$class))
{{-- <x-field type="date-range" class="class_1-class_2" label="Date Range" value="2020-11-20/2020-11-20" name="start_date-end_date" required="" id=""/> --}}
<div class="form-group {{ @$size ?? 'col-md-4' }}">
    <label>{{ @$label }}</label>@if(@$required)<span class="required"> *</span>@endif
    <div class="row">
        @foreach($date as $index => $row)
        <div class="col-md-6">
            <input class="form-control {{ @$clas[$index] }}" type="date" name="{{ @$row }}" @if(@$required) required @endif value="{{ @$v[$index] }}" id="{{ $id }}_{{ $loop->iteration }}">
        </div>
        @endforeach
    </div>
</div>
@endif

@if($type == 'textarea')
{{-- <x-field type="textarea" label="Textarea" value="" name="" required="" placeholder="" id="" class=""/> --}}
<div class="form-group {{ @$size ?? 'col-md-4' }}">
    <label>{{ @$label }}</label>@if(@$required)<span class="required"> *</span>@endif
    <textarea class="form-control {{ @$class }}" placeholder="{{ @$placeholder }}" name="{{ @$name }}" @if(@$required) required @endif id="{{ @$id }}">{{ @$value }}</textarea>
</div>
@endif


@if($type == 'radio')
{{-- <x-field type="radio" class="class" label="Radio" size="" :options="['yes:1','No:0']" value="1" name="text" required="" id=""/> --}}
<div class="form-group {{ @$size ?? 'col-md-4' }}">
    <label for="text-input1">{{ @$label }}</label><br>
    @foreach($options as $key => $option)
    @php($radio = explode(':', $option))
    <label class="fancy-radio custom-color-green"><input class="{{ @$class }}" name="{{ @$name }}"
            value="{{ @$radio[1] }}" type="{{ @$type }}" @if(@$value == $radio[1] || $key == 0) checked @endif id="{{ @$id }}_{{ @$key }}"><span><i></i>{{ $radio[0] }}</span></label>
    @endforeach
</div>
@endif


@if($type == 'checkbox')
{{-- <x-field type="checkbox" name="" label="Checkbox" store="1" value="1" id="" class=""/> --}}
<div class="form-group clearfix" style="align-items: unset;display: flex;">
    <label class="element-left" style="padding: 34px 0px 0px 27px;">
        <input type="{{ @$type }}" name="{{ @$name }}" class="{{ @$class }}" id="{{ @$id }}" value="{{ @$store }}" @if(@$value == @$store) checked @endif>
        <span for="{{ @$name }}">{{ @$label }}</span>
    </label>								
</div>
@endif


@if($type == 'select')
{{-- <x-field type="select" name="" size="" label="Select" value="1" required="" :options="[['id'=>'1','name'=>'One'],['id'=>'2','name'=>'Two']]" print="name" store="id"/> --}}
<div class="form-group {{ @$size ?? 'col-md-4' }}">
    <label class="form-control-label">{{ @$label }}</label>@if(@$required)<span class="required"> *</span>@endif
    <select class="form-control {{ @$class }}" name="{{ @$name }}" id="{{ @$id }}" @if(@$required) required @endif>
        <option value="">Select {{ @$label }}</option>
        @foreach(@$options as $option)
        <option value="{{ $option[$store] }}" @if(@$option[$store] == @$value) Selected @endif>{{ $option[$print] }}</option>
        @endforeach
    </select>
</div>
@endif


@if($type == 'submit' || $type == 'reset')
{{-- <x-field type="submit" class="btn-success" value="Admin"/> --}}
<div class="form-group">
    <input type="{{ @$type }}" class="form-control btn {{ @$class }}" id="{{ @$id }}" value="{{ @$value }}">
</div>
@endif

@if($type == 'img_preview')
{{-- <x-field label="Admin Image" src="{{asset('public/admin/assets/images/image.jpg')}}" index="1" type="img_preview" name="admin"/> --}}
<div class="form-group {{ @$size ?? 'col-md-4' }}">
    <div style="max-width:200px;">
        <label for="{{ @$name }}_image">{{ @$label }}</label>
        <div id="{{ @$name }}_image_container_{{ $index }}">
            <img id="{{ @$name }}_image_preview_{{ $index }}" class="preview" src="{{ $src }}" alt="" onclick="image('{{ @$name }}',{{ @$index }})">
        </div>
        <input type="file" id="{{ @$name }}_image_{{ $index }}" name="{{ @$name }}" style="display: none;" accept="image/*" onchange="preview_Image('{{ @$name }}',{{ @$index }})" @if(@$required) required @endif>
    </div>
</div>
@endif