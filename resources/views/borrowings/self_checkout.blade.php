@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Self-Service Checkout</h1>
                <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-book"></i> Browse All Books
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Search and Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search books by title, author, or category...">
                        </div>
                        <div class="col-md-3">
                            <select id="categoryFilter" class="form-control">
                                <option value="">All Categories</option>
                                @foreach($books->pluck('category.name')->unique() as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button id="clearFilters" class="btn btn-outline-secondary w-100">Clear Filters</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Books Grid -->
            <div class="row" id="booksGrid">
                @forelse($books as $book)
                    <div class="col-md-4 mb-4 book-card" 
                         data-title="{{ strtolower($book->title) }}" 
                         data-author="{{ strtolower($book->author->name) }}" 
                         data-category="{{ strtolower($book->category->name) }}">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $book->title }}</h5>
                                <p class="card-text">
                                    <strong>Author:</strong> {{ $book->author->name }}<br>
                                    <strong>Category:</strong> {{ $book->category->name }}
                                </p>
                                <div class="mt-auto">
                                    <form method="POST" action="{{ route('borrowings.store') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="btn btn-primary w-100" 
                                                onclick="return confirm('Are you sure you want to borrow this book?')">
                                            <i class="fas fa-book"></i> Borrow Book
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h4>No Available Books</h4>
                            <p>All books are currently borrowed. Please check back later.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($books->isEmpty())
                <div class="text-center mt-4">
                    <a href="{{ route('books.index') }}" class="btn btn-primary">
                        <i class="fas fa-search"></i> Browse All Books
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const clearFilters = document.getElementById('clearFilters');
    const bookCards = document.querySelectorAll('.book-card');

    function filterBooks() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value.toLowerCase();

        bookCards.forEach(card => {
            const title = card.dataset.title;
            const author = card.dataset.author;
            const category = card.dataset.category;

            const matchesSearch = title.includes(searchTerm) || author.includes(searchTerm);
            const matchesCategory = !selectedCategory || category === selectedCategory;

            if (matchesSearch && matchesCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterBooks);
    categoryFilter.addEventListener('change', filterBooks);
    
    clearFilters.addEventListener('click', function() {
        searchInput.value = '';
        categoryFilter.value = '';
        filterBooks();
    });
});
</script>
@endsection
