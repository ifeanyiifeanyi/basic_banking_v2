@extends('admin.layouts.admin')

@section('title', "Admin Dashboard")

@section('css')

@endsection


@section('admin')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action active">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">Users</a>
                    <a href="{{ route('admin.roles.index') }}" class="list-group-item list-group-item-action">Roles</a>
                    <a href="{{ route('admin.permissions.index') }}" class="list-group-item list-group-item-action">Permissions</a>
                    <a href="{{ route('admin.currency.index') }}" class="list-group-item list-group-item-action">Currency Manager</a>
                    
    </div>

@endsection


@section('javascript')
