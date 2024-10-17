{{-- create.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Create Account Type')

@section('admin')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Create Account Type</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.account-types.store') }}" method="POST">
                            @csrf
                            @include('admin.account_types._form')
                            <button type="submit" class="btn btn-primary">Create Account Type</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
