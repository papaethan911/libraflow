@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Add Author</h1>
    <form action="{{ route('authors.store') }}" method="POST">
        @csrf
        @include('authors._form', ['author' => null])
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('authors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 