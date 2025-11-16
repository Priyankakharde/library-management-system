<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::orderBy('name')->paginate(12);
        return view('authors.index', compact('authors'));
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio'  => 'nullable|string',
            'email'=> 'nullable|email|unique:authors,email',
        ]);

        Author::create($data);
        return redirect()->route('authors.index')->with('success','Author added.');
    }

    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio'  => 'nullable|string',
            'email'=> 'nullable|email|unique:authors,email,'.$author->id,
        ]);

        $author->update($data);
        return redirect()->route('authors.index')->with('success','Author updated.');
    }

    public function destroy(Author $author)
    {
        $author->books()->detach();
        $author->delete();
        return redirect()->route('authors.index')->with('success','Author deleted.');
    }
}
