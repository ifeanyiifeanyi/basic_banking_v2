<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKycQuestionRequest extends FormRequest
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
            'question' => ['required','string','max:255'],
            'options' => ['nullable','array'],
            'options.*' => ['nullable','string'],
            'file' => ['nullable','file','mimes:pdf,jpg,jpeg,png'],
            'order' => ['required','integer'],
            'response_type' => ['required','in:text,select,file,multiple_files'],
            'is_required' => ['required','boolean']
        ];
    }

    public function messages(): array{
        return [
            'question.required' => 'The question field is required.',
            'question.string' => 'The question field must be a string.',
            'question.max' => 'The question field may not be greater than 255 characters.',
            'options.required' => 'The options field is required.',
            'options.*' => 'The options field must contain at least one string.',
            'file.file' => 'The file field must be a file.',
            'file.mimes' => 'The file field must be a PDF, JPG, JPEG, or PNG file.',
            'order.required' => 'The order field is required.',
            'order.integer' => 'The order field must be an integer.',
           'response_type.required' => 'The response type field is required.',
           'response_type.in' => 'The response type field must be one of the following: text, select',
           'is_required.required' => 'The is required field is required.',
           'is_required.boolean' => 'The is required field must be a boolean.'

        ];
    }
}
