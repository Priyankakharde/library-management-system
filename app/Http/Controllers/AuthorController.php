<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::orderBy('name')->paginate(20);
        return view('authors.index', compact('authors'));
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $req)
    {
        $data = $req->validate(['name'=>'required|string|max:255','bio'=>'nullable|string']);
        Author::create($data);
        return redirect()->route('authors.index')->with('success','Author created.');
    }

    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    public function update(Request $req, Author $author)
    {
        $data = $req->validate(['name'=>'required|string|max:255','bio'=>'nullable|string']);
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
