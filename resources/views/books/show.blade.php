@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Book Details</h1>
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">{{ $book->title }}</h3>
            <p><strong>Author:</strong> {{ $book->author->name ?? '-' }}</p>
            <p><strong>Category:</strong> {{ $book->category->name ?? '-' }}</p>
            <p><strong>Genre:</strong> {{ $book->genre }}</p>
            <p><strong>Status:</strong> {{ ucfirst($book->status) }}</p>
            <p><strong>Description:</strong> {{ $book->description }}</p>
            <a href="{{ route('books.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection 