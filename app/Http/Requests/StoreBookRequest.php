<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
{
    public function authorize()
    {
        // Allow storing books for now. Add auth checks later if required.
        return true;
    }

    public function rules()
    {
        return [
            'book_id'      => ['required', 'string', 'max:50', 'unique:books,book_id'],
            'title'        => ['required', 'string', 'max:255'],
            // Accept either author_id (existing author) or author_name (free text).
            'author_id'    => ['nullable', 'integer', 'exists:authors,id'],
            'author_name'  => ['nullable', 'string', 'max:255'],
            'quantity'     => ['required', 'integer', 'min:0'],
            'notes'        => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages()
    {
        return [
            'book_id.required' => 'Please provide a Book ID.',
            'book_id.unique' => 'A book with this Book ID already exists.',
            'title.required' => 'Please provide a book title.',
            'quantity.required' => 'Please provide quantity (0 or more).',
            'author_id.exists' => 'Selected author not found.',
        ];
    }
}
