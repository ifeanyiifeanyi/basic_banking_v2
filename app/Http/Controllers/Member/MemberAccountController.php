<?php

namespace App\Http\Controllers\Member;

use App\Models\Currency;
use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MemberCreateAccount;
use App\Models\Account;
use App\Models\BankTransaction;
use App\Services\AccountCreationService;
use Illuminate\Container\Attributes\Auth;

class MemberAccountController extends Controller
{
    private $accountService;

    public function __construct(AccountCreationService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function index()
    {
        $accounts = request()->user()->accounts()->with('accountType')->get();
        return view('members.account.index', compact('accounts'));
    }

    public function create()
    {

        $currency = Currency::active()->first();
        $accountTypes = AccountType::where('is_active', true)->get();
        $userAccounts = request()->user()->accounts;

        return view('members.account.create', compact('accountTypes', 'currency', 'userAccounts'));
    }

    public function store(MemberCreateAccount $request)
    {
        $validated = $request->validated();

        $this->accountService->createAccount(request()->user(), $validated, $request);

        return redirect()->route('member.account.index')->with('success', 'Account created successfully');
    }


    public function show(Request $request, Account $account)
    {
        // Verify account belongs to user
        if ($account->user_id !== request()->user()->id) {
            abort(403);
        }

        // Get the account details with its type
        $accountDetails = Account::with('accountType')
            ->where('id', $account->id)
            ->firstOrFail();

        // Build transaction query with filters
        $query = BankTransaction::where('account_id', $account->id);

        // Apply transaction type filter
        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        // Apply date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('members.account.show', compact('accountDetails', 'transactions'));
    }


    public function exportTransactions(Request $request, Account $account)
    {
        // Verify account belongs to user
        if ($account->user_id !== request()->user()->id) {
            abort(403);
        }

        // Build query with filters
        $query = BankTransaction::where('account_id', $account->id);

        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="account_transactions.csv"',
        ];

        $callback = function () use ($transactions, $account) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'Account Number: ' . $account->account_number,
                'Account Type: ' . $account->accountType->account_type,
                '',
                '',
                '',
                ''
            ]);

            fputcsv($file, []); // Empty line for spacing

            fputcsv($file, [
                'Date & Time',
                'Reference',
                'Description',
                'Type',
                'Amount',
                'Status'
            ]);

            // Add data rows
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->created_at->format('M d, Y h:i A'),
                    $transaction->reference_number,
                    $transaction->description . ($transaction->recipient_name ? " (To: {$transaction->recipient_name})" : ''),
                    ucfirst($transaction->transaction_type),
                    ($transaction->transaction_type === 'credit' ? '+' : '-') . number_format($transaction->amount, 2),
                    ucfirst($transaction->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function report(Request $request)
    {
        // Get all transactions query
        $query = BankTransaction::with(['account', 'account.accountType'])
            ->whereHas('account', function ($q) {
                $q->where('user_id', request()->user()->id);
            });

        // Apply filters if present
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Get transactions
        $transactions = $query->orderBy('created_at', 'desc')->get();

        // Calculate summaries
        $summary = [
            'total_transactions' => $transactions->count(),
            'total_credit' => $transactions->where('transaction_type', 'credit')->sum('amount'),
            'total_debit' => $transactions->where('transaction_type', 'debit')->sum('amount'),
            'net_balance' => $transactions->where('transaction_type', 'credit')->sum('amount') -
                $transactions->where('transaction_type', 'debit')->sum('amount'),
        ];

        // Get account-wise summaries
        $accountSummaries = $transactions->groupBy('account_id')
            ->map(function ($accountTransactions) {
                return [
                    'account' => $accountTransactions->first()->account,
                    'total_transactions' => $accountTransactions->count(),
                    'total_credit' => $accountTransactions->where('transaction_type', 'credit')->sum('amount'),
                    'total_debit' => $accountTransactions->where('transaction_type', 'debit')->sum('amount'),
                    'net_balance' => $accountTransactions->where('transaction_type', 'credit')->sum('amount') -
                        $accountTransactions->where('transaction_type', 'debit')->sum('amount'),
                ];
            });

        // Get user's accounts for filter
        $accounts = request()->user()->accounts()->with('accountType')->get();

        return view('members.account.report', compact(
            'transactions',
            'summary',
            'accountSummaries',
            'accounts'
        ));
    }

    public function exportReport(Request $request)
    {
        $query = BankTransaction::with(['account', 'account.accountType'])
            ->whereHas('account', function ($q) {
                $q->where('user_id', request()->user()->id);
            });

        // Apply same filters
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        // Calculate totals for the report header
        $totals = [
            'total_transactions' => $transactions->count(),
            'total_credit' => $transactions->where('transaction_type', 'credit')->sum('amount'),
            'total_debit' => $transactions->where('transaction_type', 'debit')->sum('amount'),
            'net_balance' => $transactions->where('transaction_type', 'credit')->sum('amount') -
                $transactions->where('transaction_type', 'debit')->sum('amount'),
        ];

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transaction_report.csv"',
        ];

        $callback = function () use ($transactions, $totals) {
            $file = fopen('php://output', 'w');

            // Add report header
            fputcsv($file, ['Transaction Report']);
            fputcsv($file, ['Generated on: ' . now()->format('M d, Y H:i:s')]);
            fputcsv($file, []);

            // Add summary
            fputcsv($file, ['Summary']);
            fputcsv($file, ['Total Transactions:', $totals['total_transactions']]);
            fputcsv($file, ['Total Credits:', '$' . number_format($totals['total_credit'], 2)]);
            fputcsv($file, ['Total Debits:', '$' . number_format($totals['total_debit'], 2)]);
            fputcsv($file, ['Net Balance:', '$' . number_format($totals['net_balance'], 2)]);
            fputcsv($file, []);

            // Add transaction headers
            fputcsv($file, [
                'Date & Time',
                'Account',
                'Reference',
                'Description',
                'Type',
                'Amount',
                'Status'
            ]);

            // Add transactions
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->created_at->format('M d, Y h:i A'),
                    $transaction->account->account_number . ' (' . $transaction->account->accountType->account_type . ')',
                    $transaction->reference_number,
                    $transaction->description . ($transaction->recipient_name ? " (To: {$transaction->recipient_name})" : ''),
                    ucfirst($transaction->transaction_type),
                    ($transaction->transaction_type === 'credit' ? '+' : '-') . number_format($transaction->amount, 2),
                    ucfirst($transaction->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
