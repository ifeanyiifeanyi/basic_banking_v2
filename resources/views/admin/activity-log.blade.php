@extends('admin.layouts.admin')

@section('title', "Admin Dashboard")

@section('css')

@endsection


@section('admin')
<div class="container">
    <h2>Activity Log</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $activity)
                    <tr>
                        <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $activity->causer->name ?? 'System' }}</td>
                        <td>{{ $activity->description }}</td>
                        <td>
                            @if($activity->properties->isNotEmpty())
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modal-{{ $activity->id }}">
                                    View Details
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="modal-{{ $activity->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Activity Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <pre>{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{ $activities->links() }}
</div>
@endsection


@section('javascript')
