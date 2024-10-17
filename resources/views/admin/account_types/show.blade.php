@extends('admin.layouts.admin')

@section('title', 'Account Type Details')

@section('css')
<style>
    .activity-timeline {
        position: relative;
        padding-left: 50px;
    }
    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 25px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -37px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #0d6efd;
    }
    .detail-row {
        display: flex;
        border-bottom: 1px solid #e9ecef;
        padding: 0.75rem 0;
    }
    .detail-label {
        flex: 0 0 200px;
        font-weight: bold;
    }
    .detail-value {
        flex: 1;
    }
</style>
@endsection

@section('admin')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Account Type Details</h5>
                        <div>
                            <a href="{{ route('admin.edit.account-types', $accountType) }}" class="btn btn-primary btn-sm">Edit</a>
                            <a href="{{ route('admin.account-types') }}" class="btn btn-secondary btn-sm">Back to List</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="detail-row">
                            <div class="detail-label">Account Type Name</div>
                            <div class="detail-value">{{ $accountType->account_type }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Code</div>
                            <div class="detail-value">{{ $accountType->code }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Description</div>
                            <div class="detail-value">{{ $accountType->description ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Minimum Balance</div>
                            <div class="detail-value">{{ number_format($accountType->minimum_balance, 2) }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Interest Rate</div>
                            <div class="detail-value">{{ $accountType->interest_rate }}%</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <span class="badge {{ $accountType->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $accountType->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Created At</div>
                            <div class="detail-value">{{ $accountType->created_at->format('F j, Y H:i:s') }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Last Updated</div>
                            <div class="detail-value">{{ $accountType->updated_at->format('F j, Y H:i:s') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Activity Log</h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-timeline">
                            @forelse($accountType->activities()->latest()->get() as $activity)
                                <div class="timeline-item">
                                    <strong>{{ $activity->description }}</strong>
                                    <div class="text-muted">
                                        by {{ $activity->causer ? $activity->causer->full_name : 'System' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </small>
                                    @if($activity->properties->count() > 0)
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-outline-primary" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#activity{{ $activity->id }}">
                                                Show Details
                                            </button>
                                            <div class="collapse mt-2" id="activity{{ $activity->id }}">
                                                <div class="card card-body">
                                                    @if($activity->properties->has('old'))
                                                        <strong>Old Values:</strong>
                                                        <pre class="mb-2">{{ json_encode($activity->properties['old'], JSON_PRETTY_PRINT) }}</pre>
                                                    @endif
                                                    @if($activity->properties->has('new'))
                                                        <strong>New Values:</strong>
                                                        <pre>{{ json_encode($activity->properties['new'], JSON_PRETTY_PRINT) }}</pre>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p>No activity recorded yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection
