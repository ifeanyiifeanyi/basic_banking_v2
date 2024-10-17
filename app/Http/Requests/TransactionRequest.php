<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'transaction_type' => 'required|in:credit,debit'
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'Transaction amount is required',
            'amount.numeric' => 'Amount must be a valid number',
            'amount.min' => 'Amount must be at least 0.01',
            'description.required' => 'Transaction description is required',
            'description.max' => 'Description cannot exceed 255 characters',
            'transaction_type.required' => 'Transaction type is required',
            'transaction_type.in' => 'Invalid transaction type'
        ];
    }
}
