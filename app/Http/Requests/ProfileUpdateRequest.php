<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        // Only allow authenticated users to update profile
        return auth()->check();
    }

    public function rules()
    {
        $userId = $this->user()?->id;

        return [
            'name' => ['nullable', 'string', 'max:191'],
            'email' => [
                'required',
                'email',
                'max:191',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
            // avatar optional file upload
            'avatar' => ['nullable','file','image','max:4096'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email is already used.',
            'avatar.image' => 'Avatar must be an image file.',
        ];
    }
}
