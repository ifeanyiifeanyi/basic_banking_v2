@extends('admin.layouts.admin')

@section('title', 'User Profile')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection


@section('admin')
    <x-alert-info />

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ $user->photo }}" alt="User" class="rounded-circle" height="200" width="200">
                    </div>
                    <div class="text-center">
                        <h4 class="mb-2">{{ $user->full_name }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>

                    </div>
                    <hr>
                    <div class="text-center">
                        <a href="{{ route('admin.edit-profile') }}"><button type="button"
                                class="btn btn-primary btn-sm">Log Activities</button></a>
                        @if ($user->account_status)
                            <button type="button" class="btn btn-sm" style="background: blueviolet;color:white"
                                data-bs-toggle="modal" data-bs-target="#suspendUserModal">
                                Suspend User
                            </button>
                        @else
                            <a onclick="return confirm('Are you sure of reactivation ?')"
                                href="{{ route('admin.users.reactivate_get', $user) }}" class="btn btn-sm btn-secondary">
                                Reactivate Account</a>
                        @endif
                    </div>
                    <div class="mt-2 text-center">
                        <form action="{{ route('admin.users.toggle-transfer', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <button onclick="return confirm('Are you sure of this action ?')" type="submit"
                                class="btn btn-sm {{ $user->can_transfer ? 'btn-danger' : 'btn-success' }}">
                                {{ $user->can_transfer ? 'Disable Transfer' : 'Enable Transfer' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.users.toggle-receive', $user) }}" method="POST"
                            class="d-inline ms-1">
                            @csrf
                            <button type="submit"
                                class="btn btn-sm {{ $user->can_receive ? 'btn-danger' : 'btn-success' }}">
                                {{ $user->can_receive ? 'Disable Receive' : 'Enable Receive' }}
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-md-8">
            <div class="card shadow">

                <div class="card-body">
                    <h4 class="card-title">Basic Information</h4>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Full Name</td>
                                    <td>{{ $user->full_name }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td>Phone Number</td>
                                    <td>{{ $user->phone ?? 'NA' }}</td>
                                </tr>
                                <tr>
                                    <td>Date of Birth</td>
                                    <td>{{ $user->dob ?? 'NA' }}</td>
                                </tr>
                                <tr>
                                    <td>Gender</td>
                                    <td>{{ $user->gender ?? 'NA' }}</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4>Personal Information</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Address</td>
                                    <td>{{ $user->address ?? 'NA' }}</td>
                                </tr>
                                <tr>
                                    <td>City</td>
                                    <td>{{ $user->city ?? 'NA' }}</td>
                                </tr>
                                <tr>
                                    <td>State</td>
                                    <td>{{ $user->state ?? 'NA' }}</td>
                                </tr>
                                <tr>
                                    <td>Country</td>
                                    <td>{{ $user->country ?? 'NA' }}</td>
                                </tr>
                                <tr>
                                    <td>Zip</td>
                                    <td>{{ $user->zip ?? 'NA' }}</td>
                                </tr>
                                <tr>
                                    <td>Occupation</td>
                                    <td>{{ $user->occupation ?? 'NA' }}</td>
                                </tr>
                                <tr>
                                    <td>Location</td>
                                    <td>
                                        {{ $user->last_ip ?? 'NA' }} <br>
                                        {{ $user->last_location ?? 'NA' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>{{ $user->full_name }}</h2>
                </div>
                <div class="card-body">
                    <!-- User details here -->

                    <h3>Accounts</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Account Number</th>
                                <th>Account Type</th>
                                <th>Currency</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                                <tr>
                                    <td>{{ $account->account_number }}</td>
                                    <td>{{ $account->accountType->account_type }}</td>
                                    <td>{{ $account->currency->currency }}</td>
                                    <td>{{ number_format($account->account_balance, 2) }}</td>
                                    <td>
                                        @if ($account->is_suspended)
                                            <span class="badge bg-danger">Suspended</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($account->is_suspended)
                                            <form action="{{ route('admin.users.accounts.reactivate', $account->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    Reactivate
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#suspendModal{{ $account->id }}">
                                                Suspend
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.users.show_account', [$user->id, $account->id]) }}"
                                            class="btn btn-sm btn-info">View Transactions</a>

                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#creditDebitModal{{ $account->id }}">
                                            Credit/Debit
                                        </button>

                                    </td>
                                </tr>

                                <!-- Suspend Account Modal -->
                                <div class="modal fade" id="suspendModal{{ $account->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Suspend Account</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.users.accounts.suspend', $account->id) }}"
                                                method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="reason" class="form-label">Suspension Reason</label>
                                                        <textarea name="reason" class="form-control" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Suspend Account</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Activity Log  return for those later-->
        {{-- <div class="row mt-4">
            <div class="col-12">
                <h5>Activity Log</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Details</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->activities()->latest()->take(10)->get() as $activity)
                            <tr>
                                <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $activity->description }}</td>
                                <td>
                                    @if ($activity->changes)
                                        <pre>{{ json_encode($activity->changes, JSON_PRETTY_PRINT) }}</pre>
                                    @endif
                                </td>
                                <td>{{ $activity->properties['ip'] ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No activity recorded</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}
    </div>



    {{-- Modal Credit/Debit Form --}}
    @foreach ($accounts as $account)
        <div class="modal fade" id="creditDebitModal{{ $account->id }}" tabindex="-1"
            aria-labelledby="creditDebitModalLabel{{ $account->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="creditDebitModalLabel{{ $account->id }}">
                            Credit/Debit for Account {{ $account->account_number }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="transactionForm{{ $account->id }}" method="POST" class="needs-validation"
                            novalidate>
                            @csrf
                            <div class="mb-3">
                                <label for="transactionAmount{{ $account->id }}" class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $account->currency->symbol }}</span>
                                    <input type="number" step="0.01" min="0.01" class="form-control"
                                        id="transactionAmount{{ $account->id }}" name="amount" required />
                                    <div class="invalid-feedback">Please enter a valid amount.</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="transactionType{{ $account->id }}" class="form-label">Type</label>
                                <select class="form-select" id="transactionType{{ $account->id }}"
                                    name="transaction_type" required
                                    onchange="updateFormAction{{ $account->id }}(this.value)">
                                    <option value="">Select transaction type</option>
                                    <option value="credit">Credit</option>
                                    <option value="debit">Debit</option>
                                </select>
                                <div class="invalid-feedback">Please select a transaction type.</div>
                            </div>
                            <div class="mb-3">
                                <label for="transactionDescription{{ $account->id }}"
                                    class="form-label">Description</label>
                                <input type="text" class="form-control"
                                    id="transactionDescription{{ $account->id }}" name="description" required />
                                <div class="invalid-feedback">Please provide a description.</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Process Transaction</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Suspend User Modal -->
    <div class="modal fade" id="suspendUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Suspend User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            This will suspend all accounts and disable all banking operations for this user.
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Suspension Reason</label>
                            <textarea name="reason" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Suspend User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('javascript')

    <script>
        function updateFormAction{{ $account->id }}(type) {
            const form = document.getElementById('transactionForm{{ $account->id }}');
            const route = type === 'credit' ?
                '{{ route('admin.users.credit_account', [$user->id, $account->id]) }}' :
                '{{ route('admin.users.debit_account', [$user->id, $account->id]) }}';
            form.action = route;
        }

        // Bootstrap form validation
        document.getElementById('transactionForm{{ $account->id }}').addEventListener('submit', function(event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    </script>



    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Handle confirmation dialogs
            document.querySelectorAll('[data-confirm]').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    if (!confirm(this.dataset.confirm)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script> --}}
@endsection
