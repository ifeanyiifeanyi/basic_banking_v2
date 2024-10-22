<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MemberUpdateProfile extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'occupation' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'dob' => 'nullable|date',
            'gender' => 'required|string|in:male,female,other',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user()->id)],

        ];
    }
}
