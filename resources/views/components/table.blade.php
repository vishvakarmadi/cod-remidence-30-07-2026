@props(['column' => '', 'rowsData' => '', 'field' => '', 'edit','delete','password','module'])

@php
    $session = Auth::guard('admin')->user();
@endphp

<div class="table-responsive">
    <table class="table table-striped table-hover dataTable js-exportable">
        <thead>
            <tr>
                @foreach ($column as $col)
                    <th>{{ $col }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rowsData as $data)
                <tr>
                    @foreach ($field as $get)
                        @php
                            if (count(explode('.', $get)) > 1) {
                                $expload = explode('.', $get);
                                $value = $data->{$expload[1]}[0]->name;
                            } else if(count(explode(':', $get)) > 1) {
                                $expload = explode(':', $get);
                                $value = '<img src="' . asset('public/uploads/' . ($data->{$expload[1]} ?? 'avatar.png')) . '"
                                            data-toggle="tooltip" data-placement="top" title="Avatar Name"
                                            alt="Avatar" class="rounded-circle w35">';
                            } else if(count(explode('-', $get)) > 1) {
                                $expload = explode('-', $get);
                                $value = '<span class="badge bg-' . $expload[1] . '">' . $data->{$expload[0]} . '</span>';
                            } else {
                                $value = $data->$get;
                            }

                            
                        @endphp
                    <td>{!! $value !!}</td>
                    @endforeach
                    <td>
                        @if($password && $session->hasPermissionTo('change '.$module))
                        <a href="{{ route($password, $data->id) }}" class="btn btn-secondary" title="Password"><span
                                class="sr-only">Password</span> <i class="fa fa-key"></i></a>
                        @endif
                        @if($edit && $session->hasPermissionTo('edit '.$module))
                        <span style="margin: 0 10px;">|</span>
                        <a href="{{ route($edit, $data->id) }}" class="btn btn-primary" title="Edit"><span
                                class="sr-only">Edit</span> <i class="fa fa-pencil"></i></a>
                        @endif
                        @if($delete && $session->hasPermissionTo('delete '.$module))
                        <span style="margin: 0 10px;">|</span>
                        <a href="{{ route($delete, $data->id) }}" class="btn btn-danger" title="Delete"
                            onClick="return confirm('Are you sure?');"><span class="sr-only">Delete</span> <i
                                class="fa fa-trash-o"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
