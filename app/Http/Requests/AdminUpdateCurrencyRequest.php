<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateCurrencyRequest extends FormRequest
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
        $currencyId = $this->route('currency')->id;

        return [
            'code' => [
                'required',
                'string',
                'max:3',
                Rule::unique('currencies', 'code')->ignore($currencyId)
            ],
            'symbol' => [
                'required',
                'string',
                'max:10'
            ],
            'currency' => [
                'required',
                'string',
                'max:50',
                Rule::unique('currencies', 'currency')->ignore($currencyId)
            ],
            'exchange_rate' => [
                'required',
                'numeric',
                'min:0'
            ],
            'is_active' => [
                'required',
                'boolean'
            ],
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'The currency code is required',
            'code.max' => 'The currency code must not exceed 3 characters',
            'code.unique' => 'This currency code is already in use',
            'symbol.required' => 'The currency symbol is required',
            'symbol.max' => 'The currency symbol must not exceed 10 characters',
            'currency.required' => 'The currency name is required',
            'currency.max' => 'The currency name must not exceed 50 characters',
            'currency.unique' => 'This currency name is already in use',
            'exchange_rate.required' => 'The exchange rate is required',
            'exchange_rate.numeric' => 'The exchange rate must be a number',
            'exchange_rate.min' => 'The exchange rate must be greater than or equal to 0',
            'is_active.required' => 'The status is required',
            'is_active.boolean' => 'The status must be either active or inactive',
        ];
    }
}
