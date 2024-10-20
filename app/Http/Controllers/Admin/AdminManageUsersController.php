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
use Illuminate\Support\Facades\Auth;
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

    /**
     * show user  account and profile
     */
    public function show($user)
    {
        $user = $this->bankUserService->getUserById($user);
        $accounts = $user->accounts()->with('accountType', 'currency')->get();
        return view('admin.users.show', compact('user', 'accounts'));
    }

    /**
     * show user account transactions
     */
    public function showAccount($userId, $accountId)
    {
        $account = Account::with(['user', 'accountType', 'transactions'])->findOrFail($accountId);
        return view('admin.users.show_account', compact('account'));
    }


    /**
     * create specific account view
     */
    public function createAccount($userId)
    {
        $user = $this->bankUserService->getUserById($userId);

        $accountTypes = AccountType::active()->get();
        $currencies = Currency::active()->get();
        return view('admin.users.create_account', compact('user', 'accountTypes', 'currencies'));
    }

    /**
     * create a new account for user from the admin
     */
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




    /**
     * credit specific account from admin
     */
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

    /**
     * debit specific account from admin section
     */
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

    /*******************************************************************/



    /**
     * Suspend specific account
     */
    public function suspendAccount(Request $request, $accountId)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $this->bankUserService->suspendAccount($accountId);

        return back()->with('success', 'Account suspended successfully');
    }


    /**
     * Reactivate specific account
     */
    public function reactivateAccount($accountId)
    {
        $this->bankUserService->reactivateAccount($accountId);

        return back()->with('success', 'Account reactivated successfully');
    }

    /**
     * Suspend user and all accounts
     */
    public function suspendUser(Request $request, User $user)
    {
        // dd("here..");
        $reason = $request->reason ?? 'Action perform by ADMIN, ID: ' . Auth::id();
        $this->bankUserService->suspendUser($user->id, $reason);
        return back()->with('success', 'User and all accounts suspended successfully');
    }
/**
     * reactivate user and all accounts
     */
    public function reactivateUser(Request $request, $userId)
    {
        // dd("here..");
        $reason = $request->reason ?? 'Action performed by ADMIN, ID: ' . Auth::id();
        $this->bankUserService->reactivateUser($userId, $reason);
        return back()->with('success', 'User and all accounts reactivated successfully');
    }



    /**
     * Restore user from archive
     */
    public function restoreUser($userId)
    {
        $this->bankUserService->restoreUser($userId);

        return back()->with('success', 'User restored successfully');
    }

    /**
     * Toggle user's ability to transfer
     */
    public function toggleTransferAbility(Request $request, $userId)
    {
        $this->bankUserService->toggleTransferAbility($userId);
        return back()->with('success', 'Transfer ability updated successfully');
    }

    /**
     * Toggle user's ability to receive
     */
    public function toggleReceiveAbility(Request $request, $userId)
    {
        $this->bankUserService->toggleReceiveAbility($userId);
        return back()->with('success', 'Receive ability updated successfully');
    }


    /**
     * Archive user
     */
    public function archiveUser(Request $request, $userId)
    {
        $this->bankUserService->archiveUser($userId);
        return back()->with('success', 'User archived successfully');
    }
}
