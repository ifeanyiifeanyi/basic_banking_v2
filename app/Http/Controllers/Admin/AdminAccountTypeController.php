<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminAccountTypeRequest;
use App\Models\AccountType;
use Illuminate\Http\Request;

class AdminAccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accountTypes = AccountType::all();
        return view('admin.account_types.index', ['accountTypes' => $accountTypes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.account_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminAccountTypeRequest $request)
    {
        $validatedData = $request->validated();
        $accountType = AccountType::create($validatedData);
        // Log the activity
        activity()
            ->causedBy($request->user())
            ->performedOn($accountType)
            ->withProperties([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'old' => null,
                'new' => $validatedData
            ])
            ->log('Account type created');

        return redirect()->route('admin.account-types')
            ->with('success', 'Account Type created successfully.');
    }

    public function show(AccountType $accountType)
    {
        // Eager load activities with their causer
        $accountType->load(['activities' => function($query) {
            $query->with('causer')->latest();
        }]);

        return view('admin.account_types.show', compact('accountType'));
    }

    public function edit(AccountType $accountType)
    {
        return view('admin.account_types.edit', compact('accountType'));
    }

    public function update(Request $request, AccountType $accountType)
    {
        $validatedData = $request->validate([
            'account_type' => 'required|string|max:255',
            'code' => 'required|string|unique:account_types,code,' . $accountType->id,
            'description' => 'nullable|string',
            'minimum_balance' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean'
        ]);

        $oldData = $accountType->toArray();
        $accountType->update($validatedData);

        // Log the activity
        activity()
            ->causedBy($request->user())
            ->performedOn($accountType)
            ->withProperties([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'old' => $oldData,
                'new' => $validatedData
            ])
            ->log('Account type updated');

        return redirect()->route('admin.account-types')
            ->with('success', 'Account type updated successfully.');
    }

    public function destroy(Request $request, AccountType $accountType)
    {
        $oldData = $accountType->toArray();
        $accountType->delete();

        // Log the activity
        activity()
            ->causedBy($request->user())
            ->performedOn($accountType)
            ->withProperties([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'old' => $oldData,
                'new' => null
            ])
            ->log('Account type deleted');

        return redirect()->route('admin.account-types')
            ->with('success', 'Account type deleted successfully.');
    }
}
