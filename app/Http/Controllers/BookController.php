<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->query('q', ''));
        $author = $request->query('author', null);

        $books = Book::with('authors')
            ->when($q, function($qry) use ($q) {
                $qry->where(function($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('isbn', 'like', "%{$q}%");
                });
            })
            ->when($author, function($qry) use ($author) {
                $qry->whereHas('authors', function($q2) use ($author) {
                    $q2->where('authors.id', $author);
                });
            })
            ->orderBy('created_at','desc')
            ->paginate(12)
            ->withQueryString();

        $authors = Author::orderBy('name')->get();

        return view('books.index', compact('books','authors','q','author'));
    }

    public function create()
    {
        $authors = Author::orderBy('name')->get();
        return view('books.create', compact('authors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author_text' => 'nullable|string|max:255',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:authors,id',
            'published_at' => 'nullable|date',
            'isbn' => 'nullable|string|max:40',
            'quantity' => 'nullable|integer|min:0',
            'publisher' => 'nullable|string|max:255',
            'edition' => 'nullable|string|max:100',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:100',
            'genre' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048', // 2MB
        ]);

        // handle cover upload
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        $book = Book::create([
            'title' => $data['title'],
            'author' => $data['author_text'] ?? null,
            'published_at' => $data['published_at'] ?? null,
            'isbn' => $data['isbn'] ?? null,
            'quantity' => $data['quantity'] ?? 1,
            'publisher' => $data['publisher'] ?? null,
            'edition' => $data['edition'] ?? null,
            'pages' => $data['pages'] ?? null,
            'language' => $data['language'] ?? null,
            'genre' => $data['genre'] ?? null,
            'location' => $data['location'] ?? null,
            'description' => $data['description'] ?? null,
            'cover_image' => $coverPath,
        ]);

        if (!empty($data['authors'])) {
            $book->authors()->sync($data['authors']);
        } elseif (!empty($data['author_text'])) {
            $a = Author::firstOrCreate(['name' => $data['author_text']], ['email'=>null]);
            $book->authors()->sync([$a->id]);
        }

        return redirect()->route('books.index')->with('success','Book added.');
    }

    public function edit(Book $book)
    {
        $authors = Author::orderBy('name')->get();
        $book->load('authors');
        return view('books.edit', compact('book','authors'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author_text' => 'nullable|string|max:255',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:authors,id',
            'published_at' => 'nullable|date',
            'isbn' => 'nullable|string|max:40',
            'quantity' => 'nullable|integer|min:0',
            'publisher' => 'nullable|string|max:255',
            'edition' => 'nullable|string|max:100',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:100',
            'genre' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        // handle cover upload (replace old)
        if ($request->hasFile('cover_image')) {
            // delete old if exists
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $coverPath = $request->file('cover_image')->store('covers', 'public');
            $book->cover_image = $coverPath;
        }

        $book->title = $data['title'];
        $book->author = $data['author_text'] ?? $book->author;
        $book->published_at = $data['published_at'] ?? $book->published_at;
        $book->isbn = $data['isbn'] ?? $book->isbn;
        $book->quantity = $data['quantity'] ?? $book->quantity;
        $book->publisher = $data['publisher'] ?? $book->publisher;
        $book->edition = $data['edition'] ?? $book->edition;
        $book->pages = $data['pages'] ?? $book->pages;
        $book->language = $data['language'] ?? $book->language;
        $book->genre = $data['genre'] ?? $book->genre;
        $book->location = $data['location'] ?? $book->location;
        $book->description = $data['description'] ?? $book->description;
        $book->save();

        if (!empty($data['authors'])) {
            $book->authors()->sync($data['authors']);
        } elseif (!empty($data['author_text'])) {
            $a = Author::firstOrCreate(['name' => $data['author_text']], ['email'=>null]);
            $book->authors()->sync([$a->id]);
        } else {
            $book->authors()->detach();
        }

        return redirect()->route('books.index')->with('success','Book updated.');
    }

    public function destroy(Book $book)
    {
        // delete cover if exists
        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->authors()->detach();
        $book->delete();

        return redirect()->route('books.index')->with('success','Book deleted.');
    }
}
