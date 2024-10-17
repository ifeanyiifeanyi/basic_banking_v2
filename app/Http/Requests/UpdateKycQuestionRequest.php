<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateKycQuestionRequest extends FormRequest
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
            'question' => ['required', 'string', 'max:255'],
            'response_type' => ['required', Rule::in(['text', 'select', 'file', 'multiple_files'])],
            'options' => ['nullable', 'array', 'required_if:response_type,select'],
            'options.*' => ['string', 'max:255'],
            'is_required' => ['required', 'boolean'],
            'order' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'question.required' => 'The question field is required.',
            'question.max' => 'The question may not be greater than 255 characters.',
            'response_type.required' => 'The response type field is required.',
            'response_type.in' => 'The selected response type is invalid.',
            'options.required_if' => 'The options field is required when response type is select.',
            'options.*.max' => 'Each option may not be greater than 255 characters.',
            'is_required.required' => 'The required field is required.',
            'is_required.boolean' => 'The required field must be true or false.',
            'order.required' => 'The order field is required.',
            'order.integer' => 'The order must be an integer.',
            'order.min' => 'The order must be at least 0.',
        ];
    }
}
