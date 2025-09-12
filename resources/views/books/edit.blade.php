@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Book</h1>
    <form action="{{ route('books.update', $book) }}" method="POST">
        @csrf
        @method('PUT')
        @include('books._form', ['book' => $book, 'categories' => $categories, 'authors' => $authors])
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('books.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 