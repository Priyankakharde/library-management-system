<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookApiController extends Controller
{
    public function index()
    {
        $books = Book::select(['id','title','quantity','isbn'])->limit(100)->get();
        return response()->json($books);
    }

    public function show(Book $book)
    {
        return response()->json($book);
    }

    public function decrementQuantity(Book $book)
    {
        if ($book->quantity > 0) {
            $book->decrement('quantity');
            return response()->json(['success' => true, 'quantity' => $book->quantity]);
        }
        return response()->json(['success' => false, 'message' => 'No stock'], 400);
    }
}
