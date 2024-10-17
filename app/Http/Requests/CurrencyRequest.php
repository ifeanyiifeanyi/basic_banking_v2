<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
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
            'code' => ['required', 'string', 'unique:currencies,code'],
            'currency' => ['required', 'string', 'unique:currencies,currency'],
            'exchange_rate' => ['required', 'numeric'],
            'symbol' => ['required', 'string', 'unique:currencies,symbol'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Currency code is required',
            'code.string' => 'Currency code must be a string',
            'code.unique' => 'Currency code already exists',
            'symbol.required' => 'Currency symbol is required',
            'symbol.string' => 'Currency symbol must be a string',
            'symbol.unique' => 'Currency symbol already exists',
            'code.unique' => 'Currency code already exists',
            'currency.required' => 'Currency name is required',
            'currency.string' => 'Currency name must be a string',
            'currency.unique' => 'Currency name already exists',
            'exchange_rate.required' => 'Interest rate is required',
            'exchange_rate.numeric' => 'Interest rate must be a number',
            'is_active.required' => 'Status is required',
            'is_active.boolean' => 'Status is required',

        ];
    }
}
