<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUpdateCurrencyRequest;
use App\Http\Requests\CurrencyRequest;
use App\Models\Currency;
use Exception;
use Illuminate\Http\Request;

class AdminCurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = Currency::all();
        return view('admin.currency.index', ['currencies' => $currencies]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('admin.currency.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CurrencyRequest $request)
    {
        Currency::create($request->validated());
        return redirect()->route('admin.currency.index')
            ->with('success', 'Currency has been created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        return view('admin.currency.edit', ['currency' => $currency]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminUpdateCurrencyRequest $request, Currency $currency)
    {
        // dd($request->validated());

        try{
            $currency->update($request->validated());
            return redirect()->route('admin.currency.index')
                ->with('success', 'Currency has been updated successfully.');
        }catch(Exception $e){
            return back()->withErrors(['error' => 'Failed to update currency.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        try{
            $currency->delete();
            return redirect()->route('admin.currency.index')
                ->with('success', 'Currency has been deleted successfully.');
        }catch(Exception $e){
            return back()->withErrors(['error' => 'Failed to delete currency.']);
        }
    }
}
