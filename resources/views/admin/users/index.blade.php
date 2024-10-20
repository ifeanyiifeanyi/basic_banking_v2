@extends('admin.layouts.admin')

@section('title', 'Members')

@section('css')

@endsection


@section('admin')

    <div class="container">
        <x-alert-info />
        <div class="card">
            <div class="card-header">
                <h2>Members</h2>
            </div>
            <div class="card-body shadow">
                <div class="table-responsive">
                    <table id="datatable2" class="table dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                {{-- <th>Profile Photo</th> --}}
                                <th>country</th>
                                <th>Status</th>
                                <th>Accounts</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        {{ Str::title($user->full_name) }}
                                        <p>
                                            <a href="{{ route('admin.users.show', $user) }}" class="link text-primary">{{ "@".$user->username }}</a>
                                        </p>
                                    </td>
                                    <td>{{ $user->country }}</td>

                                    {{-- <td>
                                        <img src="{{ $user->photo }}" alt="profile photo" class="rounded-circle"
                                            width="100" height="100">
                                    </td> --}}
                                    <td>
                                        @if ($user->account_status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Suspended</span>
                                        @endif
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">
                                            @foreach ($user->accounts as $account)
                                                <li>
                                                    {{ $account->account_number }}
                                                    @if ($account->is_suspended)
                                                        <span class="badge bg-danger">Suspended</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <a data-toggle="tooltip" data-placement="top" title="View user details"
                                        href="{{ route('admin.users.show', $user) }}" class="btn btn-sm"
                                            style="background: blueviolet;color:white">
                                            <i class="fas fa-street-view"></i>
                                        </a>

                                        @if ($user->account_status == true)
                                            <a data-toggle="tooltip" data-placement="top" title="Suspend all user assets" class="btn btn-sm btn-warning"
                                                href="{{ route('admin.users.suspend_get', $user) }}"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-user-times"></i>
                                                </a>
                                        @else
                                            <a data-toggle="tooltip" data-placement="top" title="Reactive user assets"
                                            onclick="return confirm('Are you sure of reactivation ?')"
                                                href="{{ route('admin.users.reactivate_get', $user) }}"
                                                class="btn btn-sm btn-secondary">
                                                Reactivate All</a>
                                        @endif



                                        <a data-toggle="tooltip" data-placement="top" title="Create new user account" href="{{ route('admin.users.create_account', $user) }}"
                                            class="btn btn-sm btn-success"><i class="fas fa-user-plus"></i></a>


                                        {{-- <a href="" class="btn btn-sm btn-primary">Edit</a> --}}

                                        @if (!$user->is_archived)
                                            <form data-toggle="tooltip" data-placement="top" title="Archive user account" action="{{ route('admin.users.archive', $user) }}" method="POST"
                                                class="d-inline mt-2">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to archive this user?')">
                                                     <i class="fas fa-file-archive"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-5">
                    {{-- @include('admin.pagination.index') --}}
                </div>
            </div>
        </div>
    </div>
@endsection


@section('javascript')

@endsection
