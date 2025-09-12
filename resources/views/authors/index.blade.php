@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Authors</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(auth()->user()->isAdmin())
        <a href="{{ route('authors.create') }}" class="btn btn-success mb-3">Add Author</a>
    @endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Bio</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($authors as $author)
            <tr>
                <td>{{ $author->name }}</td>
                <td>{{ $author->bio }}</td>
                <td>
                    <a href="{{ route('authors.show', $author) }}" class="btn btn-info btn-sm">View</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('authors.edit', $author) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('authors.destroy', $author) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this author?')">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $authors->links() }}
    </div>
</div>
@endsection 