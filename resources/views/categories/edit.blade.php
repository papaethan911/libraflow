@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Category</h1>
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        @include('categories._form', ['category' => $category])
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 