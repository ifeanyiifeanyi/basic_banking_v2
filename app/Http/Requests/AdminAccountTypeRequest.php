<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminAccountTypeRequest extends FormRequest
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
            'account_type' => 'required|string|max:255',
            'code' => 'required|string|unique:account_types,code',
            'description' => 'nullable|string',
            'minimum_balance' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean'
        ];
    }
}
