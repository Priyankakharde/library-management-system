<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    /**
     * Allow this request by default. Change to auth checks later if needed.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Validation rules for updating a book.
     * Uses the route model binding 'book' parameter to ignore current record for unique checks.
     */
    public function rules()
    {
        $bookRecordId = $this->route('book')?->id ?? $this->route('id');

        return [
            // unique book identifier, ignore current book
            'book_id' => [
                'required',
                'string',
                'max:50',
                Rule::unique('books', 'book_id')->ignore($bookRecordId),
            ],

            'title' => ['required', 'string', 'max:255'],

            // Accept either a valid existing author id or a free-text author name.
            'author_id'   => ['nullable', 'integer', 'exists:authors,id'],
            'author_name' => ['nullable', 'string', 'max:255'],

            'quantity' => ['required', 'integer', 'min:0'],

            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Custom messages to make errors clearer in the UI.
     */
    public function messages()
    {
        return [
            'book_id.required' => 'Please enter a Book ID.',
            'book_id.unique'   => 'This Book ID is already in use by another book.',
            'title.required'   => 'Please enter the book title.',
            'author_id.exists' => 'The selected author was not found in the system.',
            'quantity.required'=> 'Please specify a quantity (0 or more).',
            'quantity.integer' => 'Quantity must be an integer value.',
            'quantity.min'     => 'Quantity cannot be negative.',
            'notes.max'        => 'Notes may not exceed :max characters.',
        ];
    }
}
