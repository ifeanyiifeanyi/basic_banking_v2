<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCreateMemberRequest extends FormRequest
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
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'occupation' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'role' => 'required|string|in:member,admin',
            'account_status' => 'required|boolean',
            'two_factor_enabled' => 'required|string|in:enabled,disabled',
            'can_transfer' => 'nullable|boolean',
            'can_receive' => 'nullable|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB max


            // Bank account related fields
            'account_type_id' => 'nullable|exists:account_types,id',
            'initial_balance' => 'required_with:account_type_id|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'Email already exists.',
            'role.required' => 'Role is required.',
            'role.in' => 'Invalid role.',
            'account_status.required' => 'Account status is required.',
            'account_status.boolean' => 'Account status must be a boolean value.',
            'two_factor_enabled.required' => 'Two factor authentication status is required.',
            'two_factor_enabled.in' => 'Invalid two factor authentication status.',
            'can_transfer.required' => 'Can transfer status is required.',
            'can_transfer.boolean' => 'Can transfer status must be a boolean value.',
            'can_receive.required' => 'Can receive status is required.',
            'can_receive.boolean' => 'Can receive status must be a boolean value.',
            'initial_balance.required_with' => 'Initial balance is required when selecting an account type.',
            'initial_balance.numeric' => 'Initial balance must be a numeric value.',
            'initial_balance.min' => 'Initial balance must be greater than or equal to 0.',
            'account_type_id.exists' => 'Selected account type does not exist.',
        ];
    }
}
