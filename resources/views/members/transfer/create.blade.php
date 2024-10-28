{{-- resources/views/members/transfer/create.blade.php --}}
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
                                        <option value="{{ $account->id }}" data-balance="{{ $account->account_balance }}"
                                            data-currency="{{ $account->currency->code }}"
                                            data-number="{{ $account->account_number }}">
                                            {{ $account->account_number }}
                                            ({{ $account->currency->symbol }}{{ number_format($account->account_balance, 2) }})
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
                                            placeholder="Enter account number">
                                        <button class="btn btn-outline-secondary" type="button" id="validateAccount">
                                            Validate
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">Please enter a valid account number</div>
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
                                    <select name="bank_id" class="form-select">
                                        <option value="">Select Bank</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank['id'] }}">{{ Str::title($bank['name']) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select a bank</div>
                                </div>

                                <div id="dynamicRequirements">
                                    <!-- Bank-specific requirements will be inserted here -->
                                </div>
                            </div>

                            <!-- Common Fields -->
                            <div class="mb-4">
                                <label class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text currency-symbol">$</span>
                                    <input type="number" name="amount" class="form-control" step="0.01" min="0.01">
                                    <div class="invalid-feedback">Please enter a valid amount</div>
                                </div>
                                <small class="text-muted available-balance"></small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Narration</label>
                                <textarea name="narration" class="form-control" rows="2" placeholder="Enter transfer description"></textarea>
                            </div>

                            <div class="alert alert-danger" id="transferError" style="display: none;"></div>

                            <button type="submit" class="btn btn-primary" id="submitTransfer">
                                Continue Transfer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Transfer Summary</h5>
                        <div class="transfer-summary" style="display: none;">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">From Account:</dt>
                                <dd class="col-sm-7" id="summaryFromAccount">-</dd>

                                <dt class="col-sm-5">To:</dt>
                                <dd class="col-sm-7" id="summaryToAccount">-</dd>

                                <dt class="col-sm-5">Amount:</dt>
                                <dd class="col-sm-7" id="summaryAmount">-</dd>

                                <dt class="col-sm-5">Fee:</dt>
                                <dd class="col-sm-7" id="summaryFee">-</dd>

                                <dt class="col-sm-5">Total:</dt>
                                <dd class="col-sm-7" id="summaryTotal">-</dd>
                            </dl>
                        </div>
                        <div class="transfer-info mt-3">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">Transfer Information</h6>
                                <ul class="mb-0">
                                    <li>Internal transfers are processed instantly</li>
                                    <li>External transfers may take 1-3 business days</li>
                                    <li>Daily transfer limit applies</li>
                                </ul>
                            </div>
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

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="transfer-details"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmTransfer">Confirm Transfer</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const transferForm = document.getElementById('transferForm');
            const fromAccountSelect = document.querySelector('select[name="from_account_id"]');
            const transferTypeInputs = document.querySelectorAll('input[name="transfer_type"]');
            const internalFields = document.getElementById('internalFields');
            const externalFields = document.getElementById('externalFields');
            const bankSelect = document.querySelector('select[name="bank_id"]');
            const validateAccountBtn = document.getElementById('validateAccount');
            const accountDetails = document.getElementById('accountDetails');
            let validatedAccount = null;

            // Initialize clipboard.js
            const clipboard = new ClipboardJS('.copy-btn');
            clipboard.on('success', function() {
                showToast('Account number copied to clipboard!', 'success');
            });

            // Handle from account selection
            fromAccountSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                if (selected.value) {
                    document.getElementById('selectedAccountNumber').textContent = selected.dataset.number;
                    document.querySelector('.currency-symbol').textContent = selected.dataset.currency;
                    document.querySelector('.available-balance').textContent =
                        `Available Balance: ${selected.dataset.currency}${parseFloat(selected.dataset.balance).toLocaleString(undefined, {minimumFractionDigits: 2})}`;
                    updateTransferSummary();
                }
            });

            // Handle transfer type selection
            transferTypeInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value === 'internal') {
                        internalFields.style.display = 'block';
                        externalFields.style.display = 'none';
                        resetBankRequirements();
                    } else {
                        internalFields.style.display = 'none';
                        externalFields.style.display = 'block';
                        accountDetails.style.display = 'none';
                        validatedAccount = null;
                    }
                    updateTransferSummary();
                });
            });

            // Handle bank selection
            bankSelect?.addEventListener('change', function() {
                const bank = @json($banks).find(b => b.id == this.value);
                if (!bank) return;

                const requirementsDiv = document.getElementById('dynamicRequirements');
                requirementsDiv.innerHTML = '';

                bank.requirements.forEach(req => {
                    const fieldContainer = document.createElement('div');
                    fieldContainer.className = 'mb-4';

                    let fieldHtml = `<label class="form-label">${req.label}</label>`;

                    if (req.type === 'select' && req.options) {
                        fieldHtml += `
                    <select name="${req.name}" class="form-control" ${req.required ? 'required' : ''}>
                        <option value="">Select ${req.label}</option>
                        ${req.options.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                    </select>`;
                    } else {
                        fieldHtml += `
                    <input type="${req.type}"
                           name="${req.name}"
                           class="form-control"
                           placeholder="${req.placeholder}"
                           ${req.required ? 'required' : ''}>`;
                    }

                    if (req.description) {
                        fieldHtml += `<small class="text-muted">${req.description}</small>`;
                    }

                    fieldContainer.innerHTML = fieldHtml;
                    requirementsDiv.appendChild(fieldContainer);
                });

                updateTransferSummary();
            });

            // Handle account validation
            validateAccountBtn.addEventListener('click', async function() {
                const accountNumber = document.querySelector('input[name="to_account_number"]').value;
                if (!accountNumber) {
                    showError('Please enter an account number');
                    return;
                }

                try {
                    const response = await fetch('/transfer/validate-account', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: JSON.stringify({
                            account_number: accountNumber
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        validatedAccount = data;
                        accountDetails.style.display = 'block';
                        document.getElementById('accountInfo').innerHTML = `
                    <p class="mb-1"><strong>Account Name:</strong> ${data.account_name}</p>
                    <p class="mb-1"><strong>Account Type:</strong> ${data.account_type}</p>
                    <p class="mb-0"><strong>Status:</strong> ${data.account_status}</p>
                `;
                        updateTransferSummary();
                    } else {
                        throw new Error(data.error);
                    }
                } catch (error) {
                    accountDetails.style.display = 'none';
                    validatedAccount = null;
                    showError(error.message);
                }
            });

            // Handle form submission
            transferForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (!validateForm()) return;

                showConfirmationModal();
            });

            // Handle transfer confirmation
            document.getElementById('confirmTransfer').addEventListener('click', async function() {
                this.disabled = true;
                this.textContent = 'Processing...';

                try {
                    const formData = new FormData(transferForm);
                    const response = await fetch('/transfer/process', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: JSON.stringify(Object.fromEntries(formData))
                    });

                    const result = await response.json();

                    if (result.success) {
                        window.location.href = `/transfer/success/${result.data.reference_number}`;
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    showError(error.message);
                    this.disabled = false;
                    this.textContent = 'Confirm Transfer';
                }
            });

            // Helper functions
            function validateForm() {
                let isValid = true;
                const transferType = document.querySelector('input[name="transfer_type"]:checked').value;

                // Validate from account
                if (!fromAccountSelect.value) {
                    showError('Please select a source account');
                    isValid = false;
                }

                // Validate amount
                const amount = parseFloat(document.querySelector('input[name="amount"]').value);
                const selectedAccount = fromAccountSelect.options[fromAccountSelect.selectedIndex];
                if (!amount || amount <= 0) {
                    showError('Please enter a valid amount');
                    isValid = false;
                } else if (amount > parseFloat(selectedAccount.dataset.balance)) {
                    showError('Insufficient funds');
                    isValid = false;
                }

                if (transferType === 'internal') {
                    if (!validatedAccount) {
                        showError('Please validate the recipient account');
                        isValid = false;
                    }
                } else {
                    if (!bankSelect.value) {
                        showError('Please select a bank');
                        isValid = false;
                    }

                    // Validate bank requirements
                    const bank = @json($banks).find(b => b.id == bankSelect.value);
                    if (bank) {
                        bank.requirements.forEach(req => {
                            const field = document.querySelector(`[name="${req.name}"]`);
                            if (req.required && !field.value) {
                                showError(`Please fill in ${req.label}`);
                                isValid = false;
                            }
                        });
                    }
                }

                return isValid;
            }

            function updateTransferSummary() {
                const transferType = document.querySelector('input[name="transfer_type"]:checked').value;
                const amount = parseFloat(document.querySelector('input[name="amount"]').value) || 0;
                const fromAccount = fromAccountSelect.options[fromAccountSelect.selectedIndex];
                let toAccount = '';

                if (transferType === 'internal') {
                    toAccount = validatedAccount ? validatedAccount.account_name : '-';
                } else {
                    const selectedBank = bankSelect.options[bankSelect.selectedIndex];
                    toAccount = selectedBank.value ? selectedBank.text : '-';
                }

                document.getElementById('summaryFromAccount').textContent = fromAccount.value ?
                    `${fromAccount.dataset.number}` : '-';
                document.getElementById('summaryToAccount').textContent = toAccount;
                document.getElementById('summaryAmount').textContent = amount ?
                    `${fromAccount.dataset.currency}${amount.toLocaleString(undefined, {minimumFractionDigits: 2})}` :
                    '-';

                // Show summary if we have the minimum required information
                document.querySelector('.transfer-summary').style.display =
                    (fromAccount.value && amount > 0) ? 'block' : 'none';
            }

            function showConfirmationModal() {
                const transferType = document.querySelector('input[name="transfer_type"]:checked').value;
                const amount = parseFloat(document.querySelector('input[name="amount"]').value);
                const fromAccount = fromAccountSelect.options[fromAccountSelect.selectedIndex];
                const narration = document.querySelector('textarea[name="narration"]').value;

                let details = `
            <div class="alert alert-warning">
                <p class="mb-2">Please review the transfer details:</p>
                <ul class="mb-0">
                    <li>From Account: ${fromAccount.dataset.number}</li>
                    <li>Amount: ${fromAccount.dataset.currency}${amount.toLocaleString(undefined, {minimumFractionDigits: 2})}</li>
        `;

                if (transferType === 'internal') {
                    details += `
                <li>To Account: ${validatedAccount.account_name}</li>
                <li>Account Number: ${document.querySelector('input[name="to_account_number"]').value}</li>
            `;
                } else {
                    const selectedBank = bankSelect.options[bankSelect.selectedIndex];
                    details += `<li>To Bank: ${selectedBank.text}</li>`;

                    // Add bank requirement details
                    const bank = @json($banks).find(b => b.id == bankSelect.value);
                    if (bank) {
                        bank.requirements.forEach(req => {
                            const field = document.querySelector(`[name="${req.name}"]`);
                            if (field.value) {
                                details += `<li>${req.label}: ${field.value}</li>`;
                            }
                        });
                    }
                }

                if (narration) {
                    details += `<li>Narration: ${narration}</li>`;
                }

                details += `</ul></div>`;

                document.querySelector('.transfer-details').innerHTML = details;
                new bootstrap.Modal(document.getElementById('confirmationModal')).show();
            }

            function showError(message) {
                const errorDiv = document.getElementById('transferError');
                errorDiv.textContent = message;
                errorDiv.style.display = 'block';
                setTimeout(() => {
                    errorDiv.style.display = 'none';
                }, 5000);
            }

            function showToast(message, type = 'info') {
                // Implement your toast notification here
            }

            function resetBankRequirements() {
                document.getElementById('dynamicRequirements').innerHTML = '';
                if (bankSelect) bankSelect.value = '';
            }

            // Initialize amount input handler
            document.querySelector('input[name="amount"]').addEventListener('input', updateTransferSummary);
        });
    </script> --}}

    <script>
        // Transfer form initialization and handling
        document.addEventListener('DOMContentLoaded', function() {
            const transferForm = document.getElementById('transferForm');
            const fromAccountSelect = document.querySelector('select[name="from_account_id"]');
            const internalFields = document.getElementById('internalFields');
            const externalFields = document.getElementById('externalFields');
            const transferTypeInputs = document.querySelectorAll('input[name="transfer_type"]');
            const validateAccountBtn = document.getElementById('validateAccount');
            const accountDetails = document.getElementById('accountDetails');
            const bankSelect = document.querySelector('select[name="bank_id"]');
            const dynamicRequirements = document.getElementById('dynamicRequirements');

            // Handle transfer type toggle
            transferTypeInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value === 'internal') {
                        internalFields.style.display = 'block';
                        externalFields.style.display = 'none';
                        // Clear external fields
                        bankSelect.value = '';
                        dynamicRequirements.innerHTML = '';
                    } else {
                        internalFields.style.display = 'none';
                        externalFields.style.display = 'block';
                        // Clear internal fields
                        document.querySelector('input[name="to_account_number"]').value = '';
                        accountDetails.style.display = 'none';
                    }
                });
            });

            // Internal transfer account validation
            // validateAccountBtn.addEventListener('click', async function() {
            //     const accountNumber = document.querySelector('input[name="to_account_number"]').value;
            //     const fromAccountId = fromAccountSelect.value;

            //     if (!accountNumber) {
            //         showError('Please enter an account number');
            //         return;
            //     }

            //     if (accountNumber === fromAccountSelect.options[fromAccountSelect.selectedIndex].dataset
            //         .number) {
            //         showError('You cannot transfer to the same account');
            //         return;
            //     }

            //     try {
            //         const response = await fetch('/api/validate-account', {
            //             method: 'POST',
            //             headers: {
            //                 'Content-Type': 'application/json',
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
            //                     .content
            //             },
            //             body: JSON.stringify({
            //                 account_number: accountNumber,
            //                 from_account_id: fromAccountId
            //             })
            //         });

            //         const data = await response.json();
            //         console.log(data)

            //         if (!response.ok) throw new Error(data.message);

            //         accountDetails.style.display = 'block';
            //         document.getElementById('accountInfo').innerHTML = `
        //     <div class="alert alert-success mb-3">Account Verified Successfully</div>
        //     <p class="mb-1"><strong>Account Name:</strong> ${data.account_name}</p>
        //     <p class="mb-1"><strong>Account Type:</strong> ${data.account_type}</p>
        //     <p class="mb-0"><strong>Bank:</strong> Internal Transfer</p>
        // `;
            //     } catch (error) {
            //         accountDetails.style.display = 'block';
            //         document.getElementById('accountInfo').innerHTML = `
        //     <div class="alert alert-danger mb-0">${error.message || 'Account validation failed'}</div>
        // `;
            //     }
            // });

            // Get CSRF token from the meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            validateAccountBtn.addEventListener('click', async function() {
                const accountNumber = document.querySelector('input[name="to_account_number"]').value;
                const fromAccountId = document.querySelector('select[name="from_account_id"]').value;

                if (!accountNumber) {
                    showError('Please enter an account number');
                    return;
                }

                if (!fromAccountId) {
                    showError('Please select a source account first');
                    return;
                }

                try {
                    const response = await fetch('/api/validate-account', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            account_number: accountNumber,
                            from_account_id: fromAccountId
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Validation failed');
                    }

                    accountDetails.style.display = 'block';
                    document.getElementById('accountInfo').innerHTML = `
                        <div class="alert alert-success mb-3">Account Verified Successfully</div>
                        <p class="mb-1"><strong>Account Name:</strong> ${data.account_name}</p>
                        <p class="mb-1"><strong>Account Type:</strong> ${data.account_type}</p>
                        <p class="mb-0"><strong>Status:</strong> ${data.account_status}</p>
                    `;
                } catch (error) {
                    accountDetails.style.display = 'block';
                    document.getElementById('accountInfo').innerHTML = `
                        <div class="alert alert-danger mb-0">${error.message}</div>
                    `;
                }
            });

            function showError(message) {
                const errorDiv = document.getElementById('transferError');
                if (errorDiv) {
                    errorDiv.textContent = message;
                    errorDiv.style.display = 'block';
                    setTimeout(() => {
                        errorDiv.style.display = 'none';
                    }, 5000);
                } else {
                    alert(message); // Fallback if error div doesn't exist
                }
            }

            // External bank requirements handling
            bankSelect?.addEventListener('change', async function() {
                const bankId = this.value;
                if (!bankId) {
                    dynamicRequirements.innerHTML = '';
                    return;
                }

                try {
                    const response = await fetch(`/api/banks/${bankId}/requirements`);
                    const data = await response.json();

                    if (!response.ok) throw new Error(data.message);

                    dynamicRequirements.innerHTML = '';

                    data.requirements.forEach(req => {
                        const fieldContainer = document.createElement('div');
                        fieldContainer.className = 'mb-4';

                        let fieldHtml = `<label class="form-label">${req.label}</label>`;

                        switch (req.type) {
                            case 'select':
                                fieldHtml += `
                            <select name="${req.name}" class="form-select" ${req.required ? 'required' : ''}>
                                <option value="">Select ${req.label}</option>
                                ${req.options.map(opt => `
                                                                            <option value="${opt.value}">${opt.label}</option>
                                                                        `).join('')}
                            </select>
                        `;
                                break;

                            case 'textarea':
                                fieldHtml += `
                            <textarea name="${req.name}" class="form-control"
                                placeholder="${req.placeholder || ''}"
                                ${req.required ? 'required' : ''}></textarea>
                        `;
                                break;

                            default:
                                fieldHtml += `
                            <input type="${req.type}" name="${req.name}" class="form-control"
                                placeholder="${req.placeholder || ''}"
                                ${req.required ? 'required' : ''}
                                ${req.pattern ? `pattern="${req.pattern}"` : ''}
                                ${req.minlength ? `minlength="${req.minlength}"` : ''}
                                ${req.maxlength ? `maxlength="${req.maxlength}"` : ''}>
                        `;
                        }

                        if (req.description) {
                            fieldHtml +=
                                `<small class="text-muted d-block mt-1">${req.description}</small>`;
                        }

                        fieldContainer.innerHTML = fieldHtml;
                        dynamicRequirements.appendChild(fieldContainer);
                    });
                } catch (error) {
                    dynamicRequirements.innerHTML = `
                <div class="alert alert-danger">
                    Failed to load bank requirements: ${error.message}
                </div>
            `;
                }
            });

            // Form submission handling
            transferForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const transferType = formData.get('transfer_type');

                // Basic validation
                if (!formData.get('from_account_id')) {
                    showError('Please select a source account');
                    return;
                }

                if (transferType === 'internal') {
                    if (!formData.get('to_account_number') || !accountDetails.style.display ||
                        accountDetails.querySelector('.alert-danger')) {
                        showError('Please enter and validate the recipient account number');
                        return;
                    }
                } else {
                    if (!formData.get('bank_id')) {
                        showError('Please select a bank');
                        return;
                    }
                }

                try {
                    const response = await fetch('/api/transfers', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (!response.ok) throw new Error(data.message);

                    // Show success message and redirect to confirmation page
                    window.location.href = `/transfers/${data.transfer_id}/confirm`;
                } catch (error) {
                    showError(error.message || 'Transfer initiation failed');
                }
            });
        });

        // Utility functions
        function showError(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
            alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
            document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('form'));
        }
    </script>
@endsection
