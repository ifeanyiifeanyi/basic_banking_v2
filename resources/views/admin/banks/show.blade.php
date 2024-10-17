@extends('admin.layouts.admin')

@section('title', 'Bank Details')
@section('admin')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $bank->name }}</h1>
        <a href="{{ route('banks.edit', $bank) }}" class="btn btn-primary">Edit Bank</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Bank Details</h5>
            <dl class="row">
                <dt class="col-sm-3">Code</dt>
                <dd class="col-sm-9">{{ $bank->code }}</dd>

                <dt class="col-sm-3">Swift Code</dt>
                <dd class="col-sm-9">{{ $bank->swift_code ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">
                    <span class="badge {{ $bank->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $bank->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </dd>

                <dt class="col-sm-3">Description</dt>
                <dd class="col-sm-9">{{ $bank->description ?? 'No description available' }}</dd>
            </dl>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Requirements</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Field Name</th>
                        <th>Type</th>
                        <th>Required</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bank->requirements as $requirement)
                    <tr>
                        <td>{{ $requirement->field_name }}</td>
                        <td>{{ ucfirst($requirement->field_type) }}</td>
                        <td>{{ $requirement->is_required ? 'Yes' : 'No' }}</td>
                        <td>{{ $requirement->description ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Recent Activities</h5>
            <ul class="list-group">
                @foreach($bank->activities as $activity)
                <li class="list-group-item">
                    {{ $activity->description }} by {{ $activity->causer->full_name ?? 'System' }}
                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
