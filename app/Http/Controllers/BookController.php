<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookController extends Controller
{
    /**
     * Display a paginated listing of books.
     */
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        $books = Book::with('authors')
            ->when($q, function ($query, $q) {
                $query->where('title', 'ilike', "%{$q}%")
                      ->orWhere('isbn', 'ilike', "%{$q}%")
                      ->orWhereHas('authors', function ($q2) use ($q) {
                          $q2->where('name', 'ilike', "%{$q}%");
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('books.index', compact('books', 'q'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        // Load all authors for the multi-select in the form
        $allAuthors = Author::orderBy('name')->get();

        // empty book instance for form binding
        $book = new Book();

        return view('books.create', compact('book', 'allAuthors'));
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'isbn'          => 'nullable|string|max:100',
            'quantity'      => 'nullable|integer|min:0',
            'published_at'  => 'nullable|date',
            'publisher'     => 'nullable|string|max:255',
            'edition'       => 'nullable|string|max:100',
            'pages'         => 'nullable|integer|min:1',
            'language'      => 'nullable|string|max:50',
            'genre'         => 'nullable|string|max:100',
            'location'      => 'nullable|string|max:100',
            'description'   => 'nullable|string',
            'authors'       => 'nullable|array',
            'authors.*'     => 'exists:authors,id',
        ]);

        DB::beginTransaction();
        try {
            // Safely parse published_at
            if (!empty($validated['published_at'])) {
                $validated['published_at'] = Carbon::parse($validated['published_at'])->startOfDay();
            }

            $book = Book::create([
                'title'        => $validated['title'],
                'isbn'         => $validated['isbn'] ?? null,
                'published_at' => $validated['published_at'] ?? null,
                'quantity'     => $validated['quantity'] ?? 0,
                'publisher'    => $validated['publisher'] ?? null,
                'edition'      => $validated['edition'] ?? null,
                'pages'        => $validated['pages'] ?? null,
                'language'     => $validated['language'] ?? null,
                'genre'        => $validated['genre'] ?? null,
                'location'     => $validated['location'] ?? null,
                'description'  => $validated['description'] ?? null,
            ]);

            // Attach authors if provided
            if (!empty($validated['authors'])) {
                $book->authors()->sync($validated['authors']);
            }

            DB::commit();

            return redirect()->route('books.index')->with('success', 'Book created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create book: '.$e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        // Load all authors so the form can show the multi-select
        $allAuthors = Author::orderBy('name')->get();

        // Eager load authors relationship
        $book->load('authors');

        return view('books.edit', compact('book', 'allAuthors'));
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'isbn'          => 'nullable|string|max:100',
            'quantity'      => 'nullable|integer|min:0',
            'published_at'  => 'nullable|date',
            'publisher'     => 'nullable|string|max:255',
            'edition'       => 'nullable|string|max:100',
            'pages'         => 'nullable|integer|min:1',
            'language'      => 'nullable|string|max:50',
            'genre'         => 'nullable|string|max:100',
            'location'      => 'nullable|string|max:100',
            'description'   => 'nullable|string',
            'authors'       => 'nullable|array',
            'authors.*'     => 'exists:authors,id',
        ]);

        DB::beginTransaction();
        try {
            if (!empty($validated['published_at'])) {
                $validated['published_at'] = Carbon::parse($validated['published_at'])->startOfDay();
            }

            $book->update([
                'title'        => $validated['title'],
                'isbn'         => $validated['isbn'] ?? null,
                'published_at' => $validated['published_at'] ?? null,
                'quantity'     => $validated['quantity'] ?? ($book->quantity ?? 0),
                'publisher'    => $validated['publisher'] ?? null,
                'edition'      => $validated['edition'] ?? null,
                'pages'        => $validated['pages'] ?? null,
                'language'     => $validated['language'] ?? null,
                'genre'        => $validated['genre'] ?? null,
                'location'     => $validated['location'] ?? null,
                'description'  => $validated['description'] ?? null,
            ]);

            // sync authors: if none provided, detach all
            $book->authors()->sync($validated['authors'] ?? []);

            DB::commit();

            return redirect()->route('books.index')->with('success', 'Book updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update book: '.$e->getMessage()]);
        }
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        DB::beginTransaction();
        try {
            // detach authors and delete
            $book->authors()->detach();
            $book->delete();

            DB::commit();
            return redirect()->route('books.index')->with('success', 'Book deleted.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete book: '.$e->getMessage()]);
        }
    }
}
