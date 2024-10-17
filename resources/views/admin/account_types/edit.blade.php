@extends('admin.layouts.admin')

@section('title', 'Edit Account Type')

@section('admin')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Account Type</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.update.account-types', $accountType) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @include('admin.account_types._form')
                            <button type="submit" class="btn btn-primary">Update Account Type</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
