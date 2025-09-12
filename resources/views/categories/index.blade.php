@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Categories</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(auth()->user()->isAdmin())
        <a href="{{ route('categories.create') }}" class="btn btn-success mb-3">Add Category</a>
    @endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
                <td>
                    <a href="{{ route('categories.show', $category) }}" class="btn btn-info btn-sm">View</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this category?')">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $categories->links() }}
    </div>
</div>
@endsection 