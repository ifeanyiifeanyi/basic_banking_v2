<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Account;
use App\Models\Currency;
use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Models\BankTransaction;
use App\Services\BankUserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\AccountCreationService;
use App\Services\BankTransactionService;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\CreateBankAccountRequest;
use App\Services\UserAccountNotificationService;

class AdminManageUsersController extends Controller
{
    private $bankUserService;
    private $accountCreationService;

    public function __construct(BankUserService $bankUserService, AccountCreationService $accountCreationService)
    {
        $this->bankUserService = $bankUserService;
        $this->accountCreationService = $accountCreationService;
    }


    public function index()
    {
        $users = $this->bankUserService->getAllUsers();
        return view('admin.users.index', compact('users'));
    }


    public function show($user)
    {
        $user = $this->bankUserService->getUserById($user);
        $accounts = $user->accounts()->with('accountType', 'currency')->get();
        return view('admin.users.show', compact('user', 'accounts'));
    }

    
    public function showAccount($userId, $accountId)
    {
        $account = Account::with(['user', 'accountType', 'transactions'])->findOrFail($accountId);
        return view('admin.users.show_account', compact('account'));
    }



    public function createAccount($userId)
    {
        $user = $this->bankUserService->getUserById($userId);

        $accountTypes = AccountType::active()->get();
        $currencies = Currency::active()->get();
        return view('admin.users.create_account', compact('user', 'accountTypes', 'currencies'));
    }

    // create a new account for user from the admin
    public function storeAccount(CreateBankAccountRequest $request, $userId)
    {
        $user = $this->bankUserService->getUserById($userId);
        $validatedData = $request->validated();

        $result = $this->accountCreationService->createAccount($user, $validatedData, $request);

        if ($result['success']) {
            return redirect()->route('admin.users.show', $user)->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }





    public function creditAccount(TransactionRequest $request, $userId, $accountId)
    {
        // dd($request->all());
        $account = Account::findOrFail($accountId);

        // Verify this account belongs to the specified user
        if ($account->user_id != $userId) {
            activity()
                ->causedBy($request->user())
                ->performedOn($account)
                ->withProperties([
                    'error' => 'Invalid account access attempt',
                    'requested_user_id' => $userId,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Invalid account access attempt');

            return redirect()->back()->with('error', 'Invalid account access');
        }

        $data = $request->validated();
        $data['transaction_type'] = 'credit';

        try {
            $result = app(BankTransactionService::class)->processTransaction($account, $data);

            if ($result['success']) {
                activity()
                    ->causedBy($request->user())
                    ->performedOn($account)
                    ->withProperties([
                        'amount' => $data['amount'],
                        'description' => $data['description'],
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'transaction_id' => $result['transaction']->id
                    ])
                    ->log('Admin credit transaction');

                return redirect()->back()->with('success', $result['message']);
            }
        } catch (\Exception $e) {
            activity()
                ->causedBy($request->user())
                ->performedOn($account)
                ->withProperties([
                    'error' => $e->getMessage(),
                    'amount' => $data['amount'],
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Credit transaction failed');

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('error', $result['message']);
    }

    public function debitAccount(TransactionRequest $request, $userId, $accountId)
    {
        $account = Account::findOrFail($accountId);

        // Verify this account belongs to the specified user
        if ($account->user_id != $userId) {
            activity()
                ->causedBy($request->user())
                ->performedOn($account)
                ->withProperties([
                    'error' => 'Invalid account access attempt',
                    'requested_user_id' => $userId,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Invalid account access attempt');

            return redirect()->back()->with('error', 'Invalid account access');
        }

        $data = $request->validated();
        $data['transaction_type'] = 'debit';

        try {
            $result = app(BankTransactionService::class)->processTransaction($account, $data);

            if ($result['success']) {
                activity()
                    ->causedBy($request->user())
                    ->performedOn($account)
                    ->withProperties([
                        'amount' => $data['amount'],
                        'description' => $data['description'],
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'transaction_id' => $result['transaction']->id
                    ])
                    ->log('Admin debit transaction');

                return redirect()->back()->with('success', $result['message']);
            }
        } catch (\Exception $e) {
            activity()
                ->causedBy($request->user())
                ->performedOn($account)
                ->withProperties([
                    'error' => $e->getMessage(),
                    'amount' => $data['amount'],
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Debit transaction failed');

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('error', $result['message']);
    }
}
