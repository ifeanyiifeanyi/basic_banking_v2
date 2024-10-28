<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberCreateAccount extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account_type_id' => 'required|exists:account_types,id',
            'initial_balance' => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
        ];
    }

    public function messages(): array{
        return [
            'account_type_id.required' => 'The account type is required.',
            'account_type_id.exists' => 'The selected account type does not exist.',
            'initial_balance.required' => 'The initial balance is required.',
            'initial_balance.numeric' => 'The initial balance must be a numeric value.',
        ];
    }
}
