<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use App\Models\Category;
use App\Models\Author;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        // Basic statistics
        $totalBooks = Book::count();
        $availableBooks = Book::where('status', 'available')->count();
        $borrowedBooks = Book::where('status', 'borrowed')->count();
        $totalUsers = User::count();
        $totalBorrowings = Borrowing::count();
        $activeBorrowings = Borrowing::where('status', 'borrowed')->count();
        $overdueBorrowings = Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->count();

        // Monthly borrowing trends (last 12 months)
        $monthlyBorrowings = Borrowing::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Popular books (most borrowed)
        $popularBooks = Book::withCount('borrowings')
            ->orderBy('borrowings_count', 'desc')
            ->limit(10)
            ->get();

        // Popular categories
        $popularCategories = Category::withCount(['books as borrowings_count' => function($query) {
            $query->join('borrowings', 'books.id', '=', 'borrowings.book_id');
        }])
        ->orderBy('borrowings_count', 'desc')
        ->limit(10)
        ->get();

        // Popular authors
        $popularAuthors = Author::withCount(['books as borrowings_count' => function($query) {
            $query->join('borrowings', 'books.id', '=', 'borrowings.book_id');
        }])
        ->orderBy('borrowings_count', 'desc')
        ->limit(10)
        ->get();

        // Recent activity
        $recentBorrowings = Borrowing::with(['book', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Overdue books with details
        $overdueDetails = Borrowing::with(['book', 'user'])
            ->where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->orderBy('due_date', 'asc')
            ->get();

        // Fine statistics
        $totalFines = Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->get()
            ->sum(fn($b) => $b->calculateFine());

        $unpaidFines = Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->where('fine_paid', false)
            ->get()
            ->sum(fn($b) => $b->calculateFine());

        // User activity (borrowing frequency)
        $userActivity = User::withCount('borrowings')
            ->orderBy('borrowings_count', 'desc')
            ->limit(10)
            ->get();

        // Daily borrowing patterns (last 30 days)
        $dailyBorrowings = Borrowing::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('analytics.index', compact(
            'totalBooks',
            'availableBooks',
            'borrowedBooks',
            'totalUsers',
            'totalBorrowings',
            'activeBorrowings',
            'overdueBorrowings',
            'monthlyBorrowings',
            'popularBooks',
            'popularCategories',
            'popularAuthors',
            'recentBorrowings',
            'overdueDetails',
            'totalFines',
            'unpaidFines',
            'userActivity',
            'dailyBorrowings'
        ));
    }
}