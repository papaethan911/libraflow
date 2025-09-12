@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Author Details</h1>
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">{{ $author->name }}</h3>
            <p><strong>Bio:</strong> {{ $author->bio }}</p>
            <a href="{{ route('authors.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection 