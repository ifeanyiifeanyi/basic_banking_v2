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
                                class="btn btn-primary btn-sm">View Transactions</button></a>
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
                                    <td>{{ $user->address ?? NA }}</td>
                                </tr>
                                <tr>
                                    <td>City</td>
                                    <td>{{ $user->city ?? NA }}</td>
                                </tr>
                                <tr>
                                    <td>State</td>
                                    <td>{{ $user->state ?? NA }}</td>
                                </tr>
                                <tr>
                                    <td>Country</td>
                                    <td>{{ $user->country ?? NA }}</td>
                                </tr>
                                <tr>
                                    <td>Zip</td>
                                    <td>{{ $user->zip ?? NA }}</td>
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
                                        <a href="{{ route('admin.users.show_account', [$user->id, $account->id]) }}"
                                            class="btn btn-sm btn-info">View Transactions</a>

                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#creditDebitModal{{ $account->id }}">
                                            Credit/Debit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
                        <form id="transactionForm{{ $account->id }}" method="POST" class="needs-validation" novalidate>
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
                                <select class="form-select" id="transactionType{{ $account->id }}" name="transaction_type"
                                    required onchange="updateFormAction{{ $account->id }}(this.value)">
                                    <option value="">Select transaction type</option>
                                    <option value="credit">Credit</option>
                                    <option value="debit">Debit</option>
                                </select>
                                <div class="invalid-feedback">Please select a transaction type.</div>
                            </div>
                            <div class="mb-3">
                                <label for="transactionDescription{{ $account->id }}"
                                    class="form-label">Description</label>
                                <input type="text" class="form-control" id="transactionDescription{{ $account->id }}"
                                    name="description" required />
                                <div class="invalid-feedback">Please provide a description.</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Process Transaction</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


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
@endsection
