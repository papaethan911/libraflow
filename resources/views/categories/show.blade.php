@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Category Details</h1>
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">{{ $category->name }}</h3>
            <p><strong>Description:</strong> {{ $category->description }}</p>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection 