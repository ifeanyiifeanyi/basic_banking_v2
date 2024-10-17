@extends('admin.layouts.admin')

@section('title', 'Account Types')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.12/sweetalert2.min.css">
@endsection

@section('admin')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Account Types</h5>
                        <a href="{{ route('admin.account-types.create') }}" class="btn btn-primary">Add Account Type</a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Min Balance</th>
                                    <th>Interest Rate</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accountTypes as $accountType)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $accountType->code }}</td>
                                        <td>{{ $accountType->account_type }}</td>
                                        <td>{{ number_format($accountType->minimum_balance, 2) }}</td>
                                        <td>{{ $accountType->interest_rate }}%</td>
                                        <td>
                                            <span class="badge {{ $accountType->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $accountType->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.account-types.show', $accountType) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('admin.edit.account-types', $accountType) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('admin.account-types.destroy', $accountType) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger delete-btn">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.12/sweetalert2.all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
