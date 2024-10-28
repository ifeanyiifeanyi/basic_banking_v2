<?php

namespace App\Http\Controllers\Member;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Services\AccountCreationService;

class DashboardController extends Controller
{
    protected $userService;
    protected $accountCreationService;

    public function __construct(UserService $userService, AccountCreationService $accountCreationService)
    {
        $this->userService = $userService;
        $this->accountCreationService = $accountCreationService;
    }
    public function index()
    {
        $user = request()->user();
        $accounts = $user->accounts()->with(['accountType', 'currency'])->get();
        $totalBalance = $accounts->sum('account_balance');
        $currency = Currency::active()->first();
            $hasAnyAccounts = $user->accounts()->exists();

        return view('members.dashboard', [
            'user' => $user,
            'accounts' => $accounts,
            'totalBalance' => $totalBalance,
            'currency' => $currency,
            'hasAnyAccounts' => $hasAnyAccounts,
            'accountCount' => $user->accounts()->count(),
            'maxAccounts' => AccountCreationService::MAX_ACCOUNTS_PER_USER,
            'canCreateMoreAccounts' => $user->accounts()->count() < AccountCreationService::MAX_ACCOUNTS_PER_USER
        ]);
    }
}
