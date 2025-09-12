@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="p-5 mb-4 bg-primary text-white rounded-3 shadow-sm">
        <div class="container-fluid py-2">
            <h1 class="display-5 fw-bold">Welcome, {{ ucfirst(auth()->user()->role) }} {{ auth()->user()->name }}!</h1>
            <p class="col-md-8 fs-4">This is the Dagupan City National Highschool Library System. Manage your books, borrowing, and more with ease.</p>
            <div class="d-flex gap-2">
                <a href="{{ route('books.index') }}" class="btn btn-light btn-lg">Browse Books</a>
                @if(!auth()->user()->isAdmin())
                    <a href="{{ route('borrowings.self_checkout') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-book"></i> Self-Service Checkout
                    </a>
                @endif
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark"><i class="bi bi-megaphone"></i> Library Announcements</div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>📚 <b>New Arrivals:</b> Check out the latest books in our collection!</li>
                        <li>⏰ <b>Reminder:</b> Please return books on time to avoid overdue penalties.</li>
                        <li>🗓️ <b>Event:</b> Book Fair next Friday in the library hall.</li>
                    </ul>
                </div>
            </div>
            @if(auth()->user()->isStudent() || auth()->user()->isTeacher())
            <div class="card mb-4">
                <div class="card-header bg-info text-white"><i class="bi bi-exclamation-circle"></i> My Borrowing Status</div>
                <div class="card-body">
                    <p><b>Borrowing Limit:</b> {{ auth()->user()->isStudent() ? 3 : 5 }} books at a time.</p>
                    @php
                        $myBorrowings = \App\Models\Borrowing::where('user_id', auth()->id())->where('status', 'borrowed')->get();
                        $overdue = $myBorrowings->filter(fn($b) => $b->isOverdue());
                        $dueSoon = $myBorrowings->filter(fn($b) => $b->due_date && $b->due_date->diffInDays(now()) <= 3 && !$b->isOverdue());
                        $totalFine = $myBorrowings->sum(fn($b) => $b->calculateFine());
                    @endphp
                    <p><b>Currently Borrowed:</b> {{ $myBorrowings->count() }}</p>
                    @if($overdue->count())
                        <div class="alert alert-danger"><b>Overdue Books:</b>
                            <ul class="mb-0">
                                @foreach($overdue as $b)
                                    <li>{{ $b->book->title }} (Due: {{ $b->due_date->format('M d, Y') }}) - Fine: ₱{{ number_format($b->calculateFine(), 2) }}</li>
                                @endforeach
                            </ul>
                            <div class="mt-2"><b>Total Fine:</b> ₱{{ number_format($totalFine, 2) }}</div>
                        </div>
                    @endif
                    @if($dueSoon->count())
                        <div class="alert alert-warning"><b>Books Due Soon:</b>
                            <ul class="mb-0">
                                @foreach($dueSoon as $b)
                                    <li>{{ $b->book->title }} (Due: {{ $b->due_date->format('M d, Y') }})</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <a href="{{ route('borrowings.my_history') }}" class="btn btn-outline-secondary">My Borrowings</a>
                </div>
            </div>
            @endif
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="https://yt3.googleusercontent.com/ytc/AIdro_lkEzByQWiP7aN8FsnOE0YDcDAAYka5o4WkmHWJgbmldw=s900-c-k-c0x00ffffff-no-rj" alt="School Logo" width="80" class="mb-3 rounded-circle shadow">
                    <h5 class="card-title">Dagupan City National Highschool</h5>
                    <p class="card-text">Empowering students and teachers through reading.</p>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header bg-success text-white"><i class="bi bi-info-circle"></i> Need Help?</div>
                <div class="card-body">
                    <p>Contact the librarian at <a href="mailto:librarian@dagupancnhs.edu.ph">librarian@dagupancnhs.edu.ph</a> or visit the library office during school hours.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-book-half fs-1 text-primary"></i></div>
                    <h5 class="card-title">Books</h5>
                    <p class="card-text fs-4">{{ \App\Models\Book::count() }}</p>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-primary btn-sm">View Books</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-tags fs-1 text-success"></i></div>
                    <h5 class="card-title">Categories</h5>
                    <p class="card-text fs-4">{{ \App\Models\Category::count() }}</p>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-success btn-sm">View Categories</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-person-lines-fill fs-1 text-warning"></i></div>
                    <h5 class="card-title">Authors</h5>
                    <p class="card-text fs-4">{{ \App\Models\Author::count() }}</p>
                    <a href="{{ route('authors.index') }}" class="btn btn-outline-warning btn-sm">View Authors</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-arrow-left-right fs-1 text-info"></i></div>
                    <h5 class="card-title">Borrowings</h5>
                    <p class="card-text fs-4">{{ \App\Models\Borrowing::count() }}</p>
                    <a href="{{ route('borrowings.index') }}" class="btn btn-outline-info btn-sm">View Borrowings</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-clock-history me-2"></i>My Borrowing History</h5>
                    <p class="card-text">See all the books you have borrowed and their status.</p>
                    <a href="{{ route('borrowings.my_history') }}" class="btn btn-outline-secondary">My Borrowings</a>
                </div>
            </div>
        </div>
        @if(!auth()->user()->isAdmin())
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-cart-check me-2"></i>Self-Service Checkout</h5>
                    <p class="card-text">Borrow books instantly without waiting for librarian assistance.</p>
                    <a href="{{ route('borrowings.self_checkout') }}" class="btn btn-outline-primary">Start Self-Checkout</a>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->user()->isAdmin())
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-bar-chart-line me-2"></i>Analytics Dashboard</h5>
                    <p class="card-text">View comprehensive analytics, trends, and insights for the library.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('analytics.index') }}" class="btn btn-outline-primary">Analytics</a>
                        <a href="{{ route('borrowings.report') }}" class="btn btn-outline-dark">Basic Reports</a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('borrowings.admin_borrow') }}" class="btn btn-primary mb-3">
            <i class="fas fa-qrcode"></i> Borrow for User (Scan QR)
        </a>
    @endif
</div>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection
