@extends('admin.layouts.admin')

@section('title', 'Create Bank')

@section('admin')
<div class="container">
    <<x-alert-info/>
    <h1>Create New Bank</h1>

    <form action="{{ route('banks.store') }}" method="POST">
        @csrf
        @include('admin.banks.form')

        <button type="submit" class="btn btn-primary">Create Bank</button>
    </form>
</div>
@endsection
