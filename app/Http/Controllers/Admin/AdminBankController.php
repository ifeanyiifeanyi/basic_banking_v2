<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use Illuminate\Http\Request;
use App\Services\BankService;
use App\Http\Requests\BankRequest;
use App\Http\Controllers\Controller;

class AdminBankController extends Controller
{
    protected $bankService;
    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }

    public function index()
    {
        // dd("on");
        $banks = Bank::with('requirements')->get();
        return view('admin.banks.index', compact('banks'));
    }

    public function create()
    {
        return view('admin.banks.create');
    }

    public function store(BankRequest $request)
    {
        $bank = $this->bankService->createBank($request->validated());
        return redirect()->route('banks.index')->with('success', 'Bank created successfully.');
    }

    public function show(Bank $bank)
    {
        $bank->load(['requirements', 'activities.causer']);
        return view('admin.banks.show', compact('bank'));
    }

    public function edit(Bank $bank)
    {
        $bank->load('requirements');
        return view('admin.banks.edit', compact('bank'));
    }

    public function update(BankRequest $request, Bank $bank)
    {
        $this->bankService->updateBank($bank, $request->validated());
        return redirect()->route('banks.index')->with('success', 'Bank updated successfully.');
    }

    public function destroy(Bank $bank)
    {
        $this->bankService->deleteBank($bank);
        return redirect()->route('banks.index')->with('success', 'Bank deleted successfully.');
    }
}
