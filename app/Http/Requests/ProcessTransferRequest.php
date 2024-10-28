<?php

namespace App\Http\Requests;

use App\Models\Bank;
use Illuminate\Foundation\Http\FormRequest;

class ProcessTransferRequest extends FormRequest
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
        $rules = [
            'from_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'narration' => 'nullable|string|max:255',
            'transfer_type' => 'required|in:internal,external',
        ];

        if ($this->transfer_type === 'internal') {
            $rules['to_account_number'] = 'required|string|exists:accounts,account_number';
        } else {
            $rules['bank_id'] = 'required|exists:banks,id';

            // Dynamically add rules for bank requirements
            $bank = Bank::with('requirements')->find($this->bank_id);
            if ($bank) {
                foreach ($bank->requirements as $requirement) {
                    $fieldRules = ['required_if:bank_id,' . $bank->id];

                    switch ($requirement->field_type) {
                        case 'email':
                            $fieldRules[] = 'email';
                            break;
                        case 'number':
                            $fieldRules[] = 'numeric';
                            break;
                        case 'text':
                            $fieldRules[] = 'string';
                            break;
                    }

                    if ($requirement->is_required) {
                        $fieldRules[] = 'required';
                    }

                    $rules[$requirement->field_name] = $fieldRules;
                }
            }
        }

        return $rules;
    }
}
