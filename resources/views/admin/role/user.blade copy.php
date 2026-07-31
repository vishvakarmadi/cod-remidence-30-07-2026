@extends('admin.admin_layouts')
@section('admin_content')

<x-page title="Seller List" module="user" :rowsData="$admin_users" create="{{ route('admin.role.user-create') }}" edit="admin.role.user-edit" delete="admin.role.user-delete" password="admin.role.user-edit-password" :column="['SL','Thumbnail','Name','Email Address','Role','Action']" :field="['id','photo:img','name','email','name']"/>

@endsection
