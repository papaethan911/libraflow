@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Author</h1>
    <form action="{{ route('authors.update', $author) }}" method="POST">
        @csrf
        @method('PUT')
        @include('authors._form', ['author' => $author])
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('authors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 