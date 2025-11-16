<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAuthorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Keeping this `true` by default so the request works immediately.
     * If you want to restrict updates to logged-in admins/librarians later,
     * change this to: return auth()->check() && auth()->user()->role === 'admin';
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Validation rules for updating an author.
     *
     * Uses the route-model-bound 'author' parameter to ignore the current
     * record when checking unique constraints.
     */
    public function rules()
    {
        // Works whether the route param is named 'author' (resource routes) or 'id'
        $authorId = $this->route('author')?->id ?? $this->route('id');

        return [
            'name'    => [
                'required',
                'string',
                'max:255',
                Rule::unique('authors', 'name')->ignore($authorId),
            ],
            'email'   => [
                'nullable',
                'email',
                'max:191',
                Rule::unique('authors', 'email')->ignore($authorId),
            ],
            'website' => ['nullable', 'url', 'max:255'],
            'bio'     => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages()
    {
        return [
            'name.required' => 'Please provide the author name.',
            'name.unique'   => 'An author with this name already exists.',
            'email.email'   => 'Enter a valid email address.',
            'email.unique'  => 'This email is already used by another author.',
            'website.url'   => 'Website must be a valid URL (include http:// or https://).',
            'bio.max'       => 'Bio must not exceed :max characters.',
        ];
    }
}

