<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminBankController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminCurrencyController;
use App\Http\Controllers\Member\MemberProfileController;
use App\Http\Controllers\Admin\AdminAccountTypeController;
use App\Http\Controllers\Admin\AdminCreateNewMemberAccountController;
use App\Http\Controllers\Admin\AdminKycQuestionController;
use App\Http\Controllers\Admin\AdminManageUsersController;
use App\Http\Controllers\Admin\AdminTwoFactorAuthController;
use App\Http\Controllers\Admin\AdminViewAllTransactionsController;
use App\Http\Controllers\DashboardController as MainDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\MemberAccountController;
use App\Http\Controllers\Member\MemberTransactionController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', MainDashboard::class)->name('dashboard');

Route::prefix('admin')->middleware(['auth', 'role:admin', '2fa'])->group(function () {
    Route::controller(AdminDashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('admin.dashboard');
        Route::post('logout', 'logout')->name('admin.logout');
    });

    Route::controller(AdminProfileController::class)->group(function () {
        Route::get('profile', 'index')->name('admin.profile');
        Route::get('edit-profile', 'edit')->name('admin.edit-profile');
        Route::put('update-profile/{user}', 'update')->name('admin.profile.update');
        Route::get('update-password', 'updatePasswordView')->name('admin.update.password');
        Route::put('update-password/{user}', 'updatePassword')->name('admin.password.update');
    });

    Route::controller(AdminTwoFactorAuthController::class)->group(function () {
        Route::get('/profile/2fa', 'show2faForm')->name('profile.2fa');
        Route::post('/profile/2fa/enable', 'enable2fa')->name('profile.2fa.enable');
        Route::delete('/profile/2fa/disable', 'disable2fa')->name('profile.2fa.disable');

        Route::get('/2fa/verify', 'showVerifyForm')->name('2fa.verify');
        Route::post('/2fa/verify', 'verify')->name('2fa.verify.post');
        Route::post('/2fa/verify/recovery', 'verifyWithRecoveryCode')->name('2fa.verify.recovery');
    });

    Route::get('/activity-log', [ActivityLogController::class, 'index'])
        ->name('admin.activity-log');

    Route::controller(AdminCurrencyController::class)->group(function () {
        Route::get('/currency', 'index')->name('admin.currency.index');
        Route::get('/currency/create', 'create')->name('admin.currency.create');
        Route::post('/currency', 'store')->name('admin.currency.store');
        Route::get('/currency/{currency}', 'show')->name('admin.currency.show');
        Route::get('currency/edit/{currency}', 'edit')->name('admin.edit.currency');
        Route::put('currency/{currency}/update', 'update')->name('admin.update.currency');
        Route::delete('/currency/{currency}/del', 'destroy')->name('admin.currency.destroy');
    });

    Route::controller(AdminAccountTypeController::class)->group(function () {
        Route::get('/account-types', 'index')->name('admin.account-types');
        Route::get('/account-types/create', 'create')->name('admin.account-types.create');
        Route::post('/account-types', 'store')->name('admin.account-types.store');
        Route::get('/account-types/{accountType}', 'show')->name('admin.account-types.show');
        Route::get('/account-types/edit/{accountType}', 'edit')->name('admin.edit.account-types');
        Route::put('/account-types/{accountType}/update', 'update')->name('admin.update.account-types');
        Route::delete('/account-types/{accountType}/del', 'destroy')->name('admin.account-types.destroy');
    });

    Route::controller(AdminBankController::class)->group(function () {
        // Bank CRUD routes
        Route::get('/banks', 'index')->name('banks.index');
        Route::get('/banks/create', 'create')->name('banks.create');
        Route::post('/banks', 'store')->name('banks.store');
        Route::get('/banks/{bank}', 'show')->name('banks.show');
        Route::get('/banks/{bank}/edit', 'edit')->name('banks.edit');
        Route::put('/banks/{bank}', 'update')->name('banks.update');
        Route::delete('/banks/{bank}', 'destroy')->name('banks.destroy');

        // Additional admin routes
        Route::get('/banks/{bank}/transactions', 'transactions')->name('banks.transactions');
        Route::post('/banks/{bank}/toggle-status', 'toggleStatus')->name('banks.toggle-status');
    });

    Route::controller(AdminKycQuestionController::class)->group(function () {
        Route::get('/kyc-questions', 'index')->name('admin.kyc_questions.index');
        Route::get('/kyc-questions/create', 'create')->name('admin.kyc_questions.create');
        Route::post('/kyc-questions', 'store')->name('admin.kyc_questions.store');
        Route::get('/kyc-questions/{kycQuestion}/edit', 'edit')->name('admin.kyc_questions.edit');
        Route::put('/kyc-questions/{kycQuestion}', 'update')->name('admin.kyc_questions.update');
        Route::delete('/kyc-questions/{kycQuestion}', 'destroy')->name('admin.kyc_questions.destroy');
    });

    Route::controller(AdminManageUsersController::class)->group(function () {
        Route::get('/users', 'index')->name('admin.users.index');
        Route::get('user/{user}/details', 'show')->name('admin.users.show');
        Route::get('user/{user}/create_account', 'createAccount')->name('admin.users.create_account');
        Route::post('user/{user}/create_account_number', 'storeAccount')->name('admin.users.store_account');
        Route::get('account-details/user/{user}/account/{account}', 'showAccount')->name('admin.users.show_account');


        Route::post('/users/{userId}/accounts/{accountId}/credit', 'creditAccount')->name('admin.users.credit_account');
        Route::post('/users/{userId}/accounts/{accountId}/debit', 'debitAccount')->name('admin.users.debit_account');


        // Account management
        Route::post('/accounts/{account}/suspend', 'suspendAccount')->name('admin.users.accounts.suspend');


        Route::post('/accounts/{account}/reactivate', 'reactivateAccount')->name('admin.users.accounts.reactivate');

        // User management
        // same functionality different request method
        Route::post('profile/{user}/suspend', 'suspendUser')->name('admin.users.suspend');
        Route::get('get/{user}/suspend', 'suspendUser')->name('admin.users.suspend_get');


        Route::post('profile/{user}/reactivate', 'reactivateUser')->name('admin.users.reactivate');
        Route::get('get/{user}/reactivate', 'reactivateUser')->name('admin.users.reactivate_get');

        Route::post('profile/{user}/toggle-transfer', 'toggleTransferAbility')->name('admin.users.toggle-transfer');
        Route::post('profile/{user}/toggle-receive', 'toggleReceiveAbility')->name('admin.users.toggle-receive');
        Route::post('profile/{user}/archive', 'archiveUser')->name('admin.users.archive');




        // Route::post('/{user}/archive', 'archiveUser')->name('admin.users.archive');
        Route::post('/{user}/restore', 'restoreUser')->name('admin.users.restore');
        // Route::post('/{user}/toggle-transfer', 'toggleTransfer')->name('admin.users.toggle-transfer');
        // Route::post('/{user}/toggle-receive', 'toggleReceive')->name('admin.users.toggle-receive');

        // fetch archived users (softDeletes)
        Route::get('archived-members', 'archivedMembers')->name('admin.users.archived_members');;
    });

    Route::controller(AdminCreateNewMemberAccountController::class)->group(function () {
        Route::get('create-new-member', 'index')->name('admin.create_new.user');
        Route::post('create-new-member', 'store')->name('admin.create_new.store');
    });

    Route::controller(AdminViewAllTransactionsController::class)->group(function () {
        Route::get('transactions', 'index')->name('admin.transaction.index');
        Route::get('/transactions/export/csv', 'exportCsv')->name('admin.transaction.export.csv');
        Route::get('/transactions/export/excel', 'exportExcel')->name('admin.transaction.export.excel');
        Route::get('/transactions/export/pdf', 'exportPdf')->name('admin.transaction.export.pdf');
        Route::get('/transactions/export/bulk', 'exportBulk')->name('admin.transaction.bulk-action');


        Route::get('/transactions/{transaction}',  'show')->name('admin.transaction.show');
        Route::post('/transactions/{transaction}/update-status',  'updateStatus')->name('admin.transaction.update-status');
    });






    // Route::controller(AdminBankRequirementController::class)->group(function () {
    //     Route::post('/banks/{bank}/requirements', 'store')->name('banks.requirements.store');
    //     Route::put('/banks/{bank}/requirements/{requirement}', 'update')->name('banks.requirements.update');
    //     Route::delete('/banks/{bank}/requirements/{requirement}', 'destroy')->name('banks.requirements.destroy');
    //     Route::post('/banks/{bank}/requirements/reorder', 'reorder')->name('banks.requirements.reorder');
    // });

    // // Transaction management routes
    // Route::controller(AdminBankTransactionController::class)->group(function () {
    //     Route::get('/transactions', 'index')->name('transactions.index');
    //     Route::get('/transactions/{transaction}', 'show')->name('transactions.show');
    //     Route::post('/transactions/{transaction}/approve', 'approve')->name('transactions.approve');
    //     Route::post('/transactions/{transaction}/reject', 'reject')->name('transactions.reject');
    // });
});

Route::prefix('private')->middleware(['auth', 'role:member'])->group(function () {
    Route::controller(MemberDashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('member.dashboard');
    });

    Route::controller(MemberProfileController::class)->group(function () {
        Route::get('profile', 'index')->name('member.profile');
        Route::get('edit-profile', 'edit')->name('member.edit-profile');
        Route::put('update-profile', 'update')->name('member.update-profile');
        Route::post('upload-avatar', 'uploadAvatar')->name('member.upload-avatar');
    });

    Route::controller(MemberAccountController::class)->group(function(){
        Route::get('accounts', 'index')->name('member.account.index');
        Route::get('accounts/create', 'create')->name('member.account.create');
        Route::post('accounts', 'store')->name('member.account.store');
        Route::get('accounts/{account}', 'show')->name('member.account.show');
        Route::get('accounts/{account}/export', 'exportTransactions')->name('member.account.export-transactions');

        Route::get('account/report', 'report')->name('member.account.report');
        Route::get('account/report/export', 'exportReport')->name('member.account.exportReport');

    });

    Route::controller(MemberTransactionController::class)->group(function(){
       Route::get('money-transfer', 'create')->name('member.money_transfer.create');
    });

    // Route::controller(BankController::class)->group(function () {
    //     Route::get('/banks', 'index')->name('banks.index');
    //     Route::get('/banks/{bank}', 'show')->name('banks.show');
    // });

    // Route::controller(BankTransactionController::class)->group(function () {
    //     // Transaction routes
    //     Route::get('/banks/{bank}/transfer', 'create')->name('transactions.create');
    //     Route::post('/banks/{bank}/transfer', 'store')->name('transactions.store');
    //     Route::get('/transactions', 'index')->name('transactions.index');
    //     Route::get('/transactions/{transaction}', 'show')->name('transactions.show');
    // });
});


require __DIR__ . '/auth.php';
