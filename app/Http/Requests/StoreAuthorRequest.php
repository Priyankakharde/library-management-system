<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;

class AuthorController extends Controller
{
    // index - list authors
    public function index()
    {
        $authors = Author::orderBy('name')->paginate(15);
        return view('authors.index', compact('authors'));
    }

    // show create form
    public function create()
    {
        return view('authors.create');
    }

    // store new author
    public function store(StoreAuthorRequest $request)
    {
        $data = $request->validated();
        $author = Author::create($data);

        return redirect()->route('authors.index')->with('success', 'Author created successfully.');
    }

    // show single author
    public function show(Author $author)
    {
        return view('authors.show', compact('author'));
    }

    // edit form
    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    // update
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $author->update($request->validated());
        return redirect()->route('authors.index')->with('success', 'Author updated.');
    }

    // destroy
    public function destroy(Author $author)
    {
        // if you want to prevent delete when books exist, check here
        $author->delete();
        return redirect()->route('authors.index')->with('success', 'Author deleted.');
    }

    // small JSON list used for typeahead/autocomplete
    public function list(Request $request)
    {
        $q = $request->query('q', null);
        $query = Author::orderBy('name');
        if ($q) {
            $query->where('name', 'ilike', "%{$q}%"); // PostgreSQL ilike - works on many DBs; change to 'like' if needed
        }
        $items = $query->limit(20)->get(['id','name']);
        return response()->json($items);
    }
}
