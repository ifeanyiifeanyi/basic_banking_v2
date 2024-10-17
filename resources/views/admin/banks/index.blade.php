@extends('admin.layouts.admin')

@section('title', 'Banks')

@section('admin')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Banks</h5>
                        <a href="{{ route('banks.create') }}" class="btn btn-primary">Add Bank</a>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>sn</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Swift Code</th>
                                    <th>Requirements</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($banks as $bank)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $bank->name }}</td>
                                        <td>{{ $bank->code }}</td>
                                        <td>{{ $bank->swift_code ?? 'N/A' }}</td>
                                        <td>{{ $bank->requirements->count() }}</td>
                                        <td>
                                            <span class="badge {{ $bank->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $bank->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('banks.show', $bank) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('banks.edit', $bank) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('banks.destroy', $bank) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Are you sure of this action ?')"
                                                    class="btn btn-sm btn-danger delete-btn">Delete</button>
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
