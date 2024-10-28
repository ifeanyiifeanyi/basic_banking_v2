{{-- resources/views/transfers/create.blade.php --}}
@extends('members.layouts.member')

@section('title', 'Money Transfer')
@section('member')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form id="transferForm">
                            @csrf
                            <!-- From Account -->
                            <div class="mb-4">
                                <label class="form-label">From Account</label>
                                <select name="from_account_id" class="form-select" required>
                                    <option value="">Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}"
                                                data-balance="{{ $account->account_balance }}"
                                                data-number="{{ $account->account_number }}">
                                            {{ $account->account_number }} (Balance: {{ $account->formatted_balance }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="showQrCode">
                                        <i class="bi bi-qr-code"></i> Show QR Code
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary copy-btn"
                                            data-clipboard-target="#selectedAccountNumber">
                                        <i class="bi bi-clipboard"></i> Copy Account Number
                                    </button>
                                    <span id="selectedAccountNumber" class="ms-2"></span>
                                </div>
                            </div>

                            <!-- Transfer Type Selection -->
                            <div class="mb-4">
                                <label class="form-label">Transfer Type</label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check card border p-3">
                                            <input class="form-check-input" type="radio" name="transfer_type"
                                                id="internal" value="internal" checked>
                                            <label class="form-check-label" for="internal">
                                                <i class="bi bi-arrow-left-right text-primary"></i>
                                                Internal Transfer
                                                <small class="text-muted d-block">Transfer within the bank</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check card border p-3">
                                            <input class="form-check-input" type="radio" name="transfer_type"
                                                id="external" value="external">
                                            <label class="form-check-label" for="external">
                                                <i class="bi bi-bank text-primary"></i>
                                                External Transfer
                                                <small class="text-muted d-block">Transfer to other banks</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Internal Transfer Fields -->
                            <div id="internalFields">
                                <div class="mb-4">
                                    <label class="form-label">Recipient Account Number</label>
                                    <div class="input-group">
                                        <input type="text" name="to_account_number" class="form-control"
                                            placeholder="Enter account number" required>
                                        <button class="btn btn-outline-secondary" type="button" id="validateAccount">
                                            Validate
                                        </button>
                                    </div>
                                </div>

                                <div id="accountDetails" class="mb-4" style="display: none;">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">Account Details</h6>
                                            <div id="accountInfo"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- External Transfer Fields -->
                            <div id="externalFields" style="display: none;">
                                <div class="mb-4">
                                    <label class="form-label">Select Bank</label>
                                    <select name="bank_id" class="form-select" required>
                                        <option value="">Select Bank</option>
                                        @foreach (json_decode($banks) as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="dynamicRequirements">
                                    <!-- Bank-specific requirements will be inserted here -->
                                </div>
                            </div>

                            <!-- Common Fields -->
                            <div class="mb-4">
                                <label class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="amount" class="form-control" step="0.01" min="0"
                                        required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Narration</label>
                                <textarea name="narration" class="form-control" rows="2"
                                    placeholder="Enter transfer description"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Continue Transfer</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Transfer Information</h5>
                        <div class="transfer-info">
                            <!-- Transfer details will be displayed here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrCodeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Account QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="qrCode"></div>
                    <div class="mt-3">
                        <small class="text-muted">Scan this QR code to quickly get account details</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js" rel="stylesheet">
@endsection

@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const transferForm = document.getElementById('transferForm');
    const fromAccountSelect = document.querySelector('select[name="from_account_id"]');
    const bankSelect = document.querySelector('select[name="bank_id"]');
    const validateAccountBtn = document.getElementById('validateAccount');
    const accountDetails = document.getElementById('accountDetails');
    const clipboard = new ClipboardJS('.copy-btn');

    // Handle from account selection
    fromAccountSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        document.getElementById('selectedAccountNumber').textContent = selected.dataset.number;
    });

    // Handle account number validation
    validateAccountBtn.addEventListener('click', async function() {
        const accountNumber = document.querySelector('input[name="to_account_number"]').value;

        try {
            const response = await fetch('/validate-account', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ account_number: accountNumber })
            });

            const data = await response.json();

            if (response.ok) {
                accountDetails.style.display = 'block';
                document.getElementById('accountInfo').innerHTML = `
                    <p class="mb-1"><strong>Account Name:</strong> ${data.account_name}</p>
                    <p class="mb-1"><strong>Account Type:</strong> ${data.account_type}</p>
                    <p class="mb-0"><strong>Status:</strong> ${data.account_status}</p>
                `;
            } else {
                throw new Error(data.error);
            }
        } catch (error) {
            accountDetails.style.display = 'none';
            showErrorMessage(error.message);
        }
    });

    // Handle bank selection and requirements
    bankSelect?.addEventListener('change', function() {
        const bank = @json($banks).find(b => b.id == this.value);
        if (!bank) return;

        const requirementsDiv = document.getElementById('dynamicRequirements');
        requirementsDiv.innerHTML = '';

        bank.requirements.forEach(req => {
            const field = document.createElement('div');
            field.className = 'mb-4';
            field.innerHTML = `
                <label class="form-label">${req.label}</label>
                <input type="${req.type}" name="${req.name}" class="form-control"
                    placeholder="${req.placeholder || ''}" ${req.required ? 'required' : ''}>
                ${req.description ? `<small class="text-muted">${req.description}</small>` : ''}
            `;
            requirementsDiv.appendChild(field);
        });
    });

    // QR Code handling
    document.getElementById('showQrCode').addEventListener('click', function() {
        const selected = fromAccountSelect.options[fromAccountSelect.selectedIndex];
        if (!selected.value) return;

        const qrData = {
            account_number: selected.dataset.number,
            bank_name: '{{ config("app.bank_name") }}',
            account_name: selected.textContent.split('(')[0].trim()
        };

        const qrContainer = document.getElementById('qrCode');
        qrContainer.innerHTML = '';
        new QRCode(qrContainer, {
            text: JSON.stringify(qrData),
            width: 200,
            height: 200
        });

        new bootstrap.Modal(document.getElementById('qrCodeModal')).show();
    });

    // Clipboard success handling
    clipboard.on('success', function(e) {
        showSuccessMessage('Account number copied to clipboard!');
        e.clearSelection();
    });
});
</script>
@endsection
