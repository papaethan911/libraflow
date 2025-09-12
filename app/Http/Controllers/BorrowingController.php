<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }
        $borrowings = Borrowing::with(['book', 'user'])->orderByDesc('created_at')->paginate(10);
        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::where('status', 'available')->get();
        $users = User::all();
        return view('borrowings.create', compact('books', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);

        // Check if self-service is enabled and user is borrowing for themselves
        $isSelfService = SystemSetting::get('self_service_enabled', true) && 
                        $validated['user_id'] == auth()->id() && 
                        !auth()->user()->isAdmin();

        // Check if user can borrow more books
        $maxBooks = SystemSetting::get('max_books_per_user', 3);
        $currentBorrowings = Borrowing::where('user_id', $validated['user_id'])
            ->where('status', 'borrowed')
            ->count();

        if ($currentBorrowings >= $maxBooks) {
            return redirect()->back()->with('error', "You can only borrow up to {$maxBooks} books at a time.");
        }

        // Check if book is available
        $book = Book::find($validated['book_id']);
        if ($book->status !== 'available') {
            return redirect()->back()->with('error', 'This book is not available for borrowing.');
        }

        $borrowingDuration = SystemSetting::get('borrowing_duration_days', 14);
        
        $borrowing = Borrowing::create([
            'user_id' => $validated['user_id'],
            'book_id' => $validated['book_id'],
            'borrowed_at' => now(),
            'status' => 'borrowed',
            'due_date' => now()->addDays($borrowingDuration),
        ]);

        // Update book status
        $borrowing->book->update(['status' => 'borrowed']);

        $message = $isSelfService ? 
            'Book borrowed successfully! You can manage your borrowings in "My Borrowing History".' : 
            'Book borrowed successfully.';

        return redirect()->route('borrowings.my_history')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['book', 'user']);
        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }
        return redirect()->route('borrowings.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }
        $borrowing->update([
            'returned_at' => now(),
            'status' => 'returned',
        ]);
        $borrowing->book->update(['status' => 'available']);
        return redirect()->route('borrowings.index')->with('success', 'Book returned successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }
        $borrowing->delete();
        return redirect()->route('borrowings.index')->with('success', 'Borrowing record deleted.');
    }

    public function myHistory()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Admins do not have a borrowing history.');
        }
        $borrowings = Borrowing::with('book')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('borrowings.my_history', compact('borrowings'));
    }

    public function report()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }
        
        // Basic stats
        $totalBooks = \App\Models\Book::count();
        $borrowedBooks = \App\Models\Book::where('status', 'borrowed')->count();
        $availableBooks = \App\Models\Book::where('status', 'available')->count();
        $borrowings = Borrowing::with(['book', 'user'])->orderByDesc('created_at')->paginate(20);
        
        return view('borrowings.report', compact(
            'totalBooks', 
            'borrowedBooks', 
            'availableBooks', 
            'borrowings'
        ));
    }

    public function adminBorrow()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        $books = Book::where('status', 'available')->with(['author', 'category'])->get();
        return view('borrowings.admin_borrow', compact('books'));
    }

    public function adminUserLookup(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $request->validate([
            'student_id' => 'required|string',
        ]);
        $user = \App\Models\User::where('student_id', $request->student_id)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'student_id' => $user->student_id,
            'qr_code' => $user->qr_code ? asset('storage/' . $user->qr_code) : null,
        ]);
    }

    /**
     * Self-service checkout for students
     */
    public function selfCheckout()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Admins cannot use self-service checkout.');
        }

        if (!SystemSetting::get('self_service_enabled', true)) {
            return redirect()->route('dashboard')->with('error', 'Self-service checkout is currently disabled.');
        }

        $books = Book::where('status', 'available')->with(['author', 'category'])->get();
        return view('borrowings.self_checkout', compact('books'));
    }

    /**
     * Renew a borrowed book
     */
    public function renew(Borrowing $borrowing)
    {
        // Check if user owns this borrowing or is admin
        if ($borrowing->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        if (!$borrowing->canRenew()) {
            $maxRenewals = SystemSetting::get('max_renewals', 2);
            return redirect()->back()->with('error', "This book cannot be renewed. Maximum renewals ({$maxRenewals}) reached.");
        }

        if ($borrowing->renew()) {
            return redirect()->back()->with('success', 'Book renewed successfully! New due date: ' . $borrowing->due_date->format('M d, Y'));
        }

        return redirect()->back()->with('error', 'Failed to renew book.');
    }

    /**
     * Pay fine for overdue book
     */
    public function payFine(Borrowing $borrowing)
    {
        // Check if user owns this borrowing or is admin
        if ($borrowing->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        if ($borrowing->fine_amount <= 0) {
            return redirect()->back()->with('error', 'No fine to pay for this book.');
        }

        $borrowing->update([
            'fine_paid' => true,
            'fine_amount' => 0,
        ]);

        return redirect()->back()->with('success', 'Fine paid successfully!');
    }

    /**
     * Update fine amounts for all overdue books
     */
    public function updateFines()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $overdueBorrowings = Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->get();

        $updatedCount = 0;
        foreach ($overdueBorrowings as $borrowing) {
            $borrowing->updateFine();
            $updatedCount++;
        }

        return redirect()->back()->with('success', "Updated fines for {$updatedCount} overdue books.");
    }
}
