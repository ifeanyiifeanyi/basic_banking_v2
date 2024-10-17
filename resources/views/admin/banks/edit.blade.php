@extends('admin.layouts.admin')

@section('title', 'Create Bank')

@section('admin')
<div class="container">
    <h1>Edit Bank: {{ $bank->name }}</h1>

    <form action="{{ route('banks.update', $bank) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.banks.form')

        <button type="submit" class="btn btn-primary">Update Bank</button>
    </form>
</div>
@endsection
