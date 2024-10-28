<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Bank;
use App\Models\BankRequirement;
use Illuminate\Http\Request;

class MemberTransactionController extends Controller
{
    public function create(){
        $user = request()->user();
        $accounts = $user->accounts;
        $banks = Bank::with('requirements')
            ->where('is_active', true)
            ->get()
            ->map(function ($bank) {
                return [
                    'id' => $bank->id,
                    'name' => $bank->name,
                    'code' => $bank->code,
                    'swift_code' => $bank->swift_code,
                    'requirements' => $bank->requirements->map(function ($req) {
                        return [
                            'id' => $req->id,
                            'name' => $req->field_name,
                            'type' => $req->field_type,
                            'options' => $req->field_options,
                            'required' => $req->is_required,
                            'description' => $req->description,
                            'order' => $req->order
                        ];
                    })->sortBy('order')->values()
                ];
            });

            // dd($banks);
        return view('members.transfer.create', compact('banks', 'accounts'));
    }
}
