@extends('admin.layouts.admin')

@section('title', 'Members')

@section('css')

@endsection


@section('admin')

    <div class="container">
        <div class="card">
            <x-alert-info />
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
                                <th>Profile Photo</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ Str::title($user->full_name) }}</td>
                                    <td>
                                        <img src="{{ $user->photo }}" alt="profile photo" class="rounded-circle"
                                            width="100" height="100">
                                    </td>
                                    <td>{{ $user->account_status ? 'ACTIVE' : 'INACTIVE' }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm"
                                            style="background: blueviolet;color:white">View</a>

                                        <a href="{{ route('admin.users.create_account', $user) }}"
                                            class="btn btn-sm btn-success">Add Account</a>


                                        <a href="" class="btn btn-sm btn-primary">Edit</a>

                                        <form action="" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
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
