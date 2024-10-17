<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankTransactionRequest extends FormRequest
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
    public function rules()
    {
        $bank = $this->route('bank');
        $rules = [
            'amount' => 'required|numeric|min:0.01',
            'transaction_type' => 'required|in:credit,debit',
            'requirements' => 'required|array',
        ];

        foreach ($bank->requirements as $requirement) {
            $fieldRule = $requirement->is_required ? 'required' : 'nullable';

            switch ($requirement->field_type) {
                case 'number':
                    $fieldRule .= '|numeric';
                    break;
                case 'file':
                    $fieldRule .= '|file|max:10240'; // 10MB max
                    break;
                default:
                    $fieldRule .= '|string|max:255';
            }

            $rules["requirements.{$requirement->field_name}"] = $fieldRule;
        }

        return $rules;
    }
}
