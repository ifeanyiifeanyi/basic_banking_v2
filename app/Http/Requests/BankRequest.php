<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:banks,code,' . $this->bank?->id,
            'swift_code' => 'nullable|string|max:11',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'requirements' => 'array',
            'requirements.*.field_name' => 'required|string|max:255',
            'requirements.*.field_type' => 'required|in:text,number,file,select',
            'requirements.*.field_options' => 'required_if:requirements.*.field_type,select|array',
            'requirements.*.is_required' => 'boolean',
            'requirements.*.description' => 'nullable|string',
        ];

        return $rules;
    }
}
