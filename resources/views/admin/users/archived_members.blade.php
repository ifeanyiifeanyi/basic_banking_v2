@extends('admin.layouts.admin')

@section('title', 'Archived Members')

@section('admin')
    <div class="container">
        <x-alert-info />

        <a href="{{ route('admin.users.index') }}" class="btn btn-sm mb-3" style="background: purple;color:white">All Active
            Members</a>

        <div class="card">
            <div class="card-header">
                <h2>Archived Members</h2>
            </div>
            <div class="card-body shadow">
                <div class="table-responsive">
                    <table id="datatable2" class="table dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Accounts</th>
                                <th>Archived Date</th>
                                <th>Archived By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($archivedUsers as $user)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ Str::title($user->full_name) }}</td>
                                    <td>
                                        <ul class="list-unstyled">

                                            @forelse ($user->accounts as $account)
                                                <li>
                                                    {{ $account->account_number }}
                                                </li>
                                            @empty
                                                <span class="text-muted">Unknown</span>
                                            @endforelse
                                        </ul>
                                    </td>
                                    <td>{{ $user->archived_at->format('d M, Y H:i A') }}</td>
                                    <td>
                                        @if ($user->archivedBy)
                                            {{ $user->archivedBy->full_name }}
                                        @else
                                            <span class="text-muted">Unknown</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.users.restore', $user->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"
                                                onclick="return confirm('Are you sure you want to restore this user?')">
                                                Restore
                                            </button>
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
@endsection
