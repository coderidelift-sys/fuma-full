<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestUserDetailUpdate extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . ($this->id ? $this->id: null)],
            'file_avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:800'],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The full name is required.',
            'name.string' => 'The full name must be a string.',
            'name.max' => 'The full name may not be greater than :max characters.',
            'email.required' => 'The email address is required.',
            'email.string' => 'The email address must be a string.',
            'email.lowercase' => 'The email address must be lowercase.',
            'email.email' => 'The email address must be a valid email address.',
            'email.max' => 'The email address may not be greater than :max characters.',
            'email.unique' => 'The email address has already been taken.',
            'file_avatar.image' => 'The avatar must be an image.',
            'file_avatar.mimes' => 'The avatar must be a file of type: :values.',
            'file_avatar.max' => 'The avatar may not be greater than :max kilobytes.',
        ];
    }
}
