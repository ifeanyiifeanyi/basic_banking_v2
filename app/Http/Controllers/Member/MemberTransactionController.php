<?php

namespace App\Http\Controllers\Member;

use App\Models\Bank;
use App\Models\Account;
use App\Models\Transfer;
use Illuminate\Http\Request;
use App\Services\BankService;
use App\Models\BankRequirement;
use App\Services\TransferService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessTransferRequest;
use App\Http\Requests\ValidateAccountRequest;

class MemberTransactionController extends Controller
{

    protected $transferService;
    protected $bankService;

    public function __construct(TransferService $transferService, BankService $bankService)
    {
        $this->transferService = $transferService;
        $this->bankService = $bankService;
    }

    public function create()
    {
        $user = request()->user();
        $accounts = $user->accounts()->active()
            ->with('currency')
            ->get();
        $banks = $this->bankService->getActiveBanks();


        // dd($accounts);
        return view('members.transfer.create', compact('banks', 'accounts'));
    }


    public function getBankRequirements(Bank $bank)
    {
        if (!$bank->is_active) {
            return response()->json([
                'message' => 'This bank is currently not available for transfers'
            ], 400);
        }

        $requirements = $bank->requirements()
            ->orderBy('order')
            ->get()
            ->map(function ($req) {
                return [
                    'name' => $req->field_name,
                    'label' => ucwords(str_replace('_', ' ', $req->field_name)),
                    'type' => $req->field_type,
                    'required' => $req->is_required,
                    'description' => $req->description,
                    'options' => $req->field_options,
                    'placeholder' => $req->placeholder ?? null,
                    'pattern' => $req->pattern ?? null,
                    'minlength' => $req->minlength ?? null,
                    'maxlength' => $req->maxlength ?? null
                ];
            });

        return response()->json([
            'bank' => [
                'id' => $bank->id,
                'name' => $bank->name,
                'code' => $bank->code,
                'swift_code' => $bank->swift_code
            ],
            'requirements' => $requirements
        ]);
    }
    public function validateAccount(Request $request)
    {
        $request->validate([
            'account_number' => 'required|string',
            'from_account_id' => 'required|exists:accounts,id'
        ]);

        try {
            $accountDetails = $this->transferService->validateInternalAccount(
                $request->account_number,
                $request->from_account_id
            );

            return response()->json($accountDetails);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
    public function store(ProcessTransferRequest $request)
    {
        try {
            $transfer = $request->transfer_type === 'internal'
                ? $this->transferService->processInternalTransfer($request->validated())
                : $this->transferService->processExternalTransfer($request->validated());

            return response()->json([
                'message' => 'Transfer processed successfully',
                'transfer' => $transfer
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function confirm($reference)
    {
        $transfer = Transfer::where('reference', $reference)
            ->with(['fromAccount', 'bank'])
            ->firstOrFail();

        return view('members.transfers.confirm', compact('transfer'));
    }

    // public function validateAccount(ValidateAccountRequest $request)
    // {
    //     try {
    //         $account = Account::where('account_number', $request->account_number)
    //             ->where('is_suspended', false)
    //             ->with(['user', 'accountType'])
    //             ->firstOrFail();

    //         // Don't allow transfer to own account
    //         if ($account->user_id === request()->user()->id) {
    //             return response()->json([
    //                 'error' => 'Cannot transfer to your own account'
    //             ], 422);
    //         }

    //         return response()->json([
    //             'account_name' => $account->user->full_name,
    //             'account_type' => $account->accountType->name,
    //             'account_status' => 'Active',
    //             'account_id' => $account->id
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'Invalid account number'
    //         ], 404);
    //     }
    // }

    // public function processTransfer(ProcessTransferRequest $request)
    // {
    //     try {
    //         $result = $this->transferService->process($request->validated());

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Transfer initiated successfully',
    //             'data' => $result
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ], 422);
    //     }
    // }
}
