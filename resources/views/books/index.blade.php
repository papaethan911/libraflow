@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Books</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="mb-3">
        <form method="GET" action="{{ route('books.index') }}" class="row g-2 align-items-center">
            <div class="col-auto">
                <input type="text" name="search" class="form-control" placeholder="Search by title, author, or genre" value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @if(request('category_id') == $category->id) selected @endif>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            @if(auth()->user()->isAdmin())
            <div class="col-auto">
                <a href="{{ route('books.create') }}" class="btn btn-success">Add Book</a>
            </div>
            @endif
        </form>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Genre</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($books as $book)
            <tr>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author->name ?? '-' }}</td>
                <td>{{ $book->category->name ?? '-' }}</td>
                <td>{{ $book->genre }}</td>
                <td>{{ ucfirst($book->status) }}</td>
                <td>
                    <a href="{{ route('books.show', $book) }}" class="btn btn-info btn-sm">View</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('books.destroy', $book) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this book?')">Delete</button>
                        </form>
                    @endif
                    @if($book->status === 'available' && !auth()->user()->isAdmin())
                        <form action="{{ route('borrowings.store') }}" method="POST" style="display:inline-block">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <button type="submit" class="btn btn-primary btn-sm">Borrow</button>
                        </form>
                    @elseif($book->status === 'borrowed' && !auth()->user()->isAdmin())
                        <!-- Optionally show return button if this user borrowed it -->
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $books->links() }}
    </div>
</div>
@endsection 