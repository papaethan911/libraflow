<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Author;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Book::with(['category', 'author']);
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhereHas('author', function($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%")
                  ;})
                  ->orWhere('genre', 'like', "%$search%")
                ;
            });
        }
        if ($categoryId = request('category_id')) {
            $query->where('category_id', $categoryId);
        }
        $books = $query->paginate(10);
        $categories = Category::all();
        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')->with('error', 'Unauthorized.');
        }
        $categories = Category::all();
        $authors = Author::all();
        return view('books.create', compact('categories', 'authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')->with('error', 'Unauthorized.');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'genre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        Book::create($validated);
        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load(['category', 'author']);
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')->with('error', 'Unauthorized.');
        }
        $categories = Category::all();
        $authors = Author::all();
        return view('books.edit', compact('book', 'categories', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')->with('error', 'Unauthorized.');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'genre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        $book->update($validated);
        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')->with('error', 'Unauthorized.');
        }
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
}
