<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Services\BankService;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\AccountCreationService;
use App\Http\Requests\AdminCreateMemberRequest;
use App\Models\Currency;
use WisdomDiala\Countrypkg\Models\Country;

class AdminCreateNewMemberAccountController extends Controller
{
    protected $bankService;
    protected $accountCreationService;
    protected $userService;

    public function __construct(
        BankService $bankService,
        AccountCreationService $accountCreationService,
        UserService $userService
    ) {
        $this->bankService = $bankService;
        $this->accountCreationService = $accountCreationService;
        $this->userService = $userService;
    }

    public function index()
    {
        $countries = Country::all();
        // Get all account types for the form
        $accountTypes = AccountType::active()->get();
        return view('admin.create_new_member_account.index', compact('accountTypes', 'countries'));
    }


     public function store(AdminCreateMemberRequest $request)
    {
        $currency_id = Currency::active()->first();
        // dd($request->all());
        DB::beginTransaction();
        try {
            // Create the user first
            $userData = $request->safe()->except(['account_type_id', 'initial_balance']);
            $user = $this->userService->createUser($userData);

            // Create bank account if account type is selected
            if ($request->filled('account_type_id')) {
                $accountData = [
                    'account_type_id' => $request->account_type_id,
                    'initial_balance' => $request->initial_balance ?? 0,
                    'currency_id' => $currency_id->id,
                    'requires_approval' => false
                ];

                $result = $this->accountCreationService->createAccount($user, $accountData, $request);

                if (!$result['success']) {
                    throw new \Exception($result['message']);
                }
            }

            DB::commit();
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User created successfully with bank account');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create user with account: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }
}
