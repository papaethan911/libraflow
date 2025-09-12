<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $recentBorrowings = $user->borrowings()->with('book')->orderByDesc('created_at')->limit(5)->get();
        $totalBorrowed = $user->borrowings()->count();
        $overdue = $user->borrowings()->where('status', 'borrowed')->whereDate('borrowed_at', '<=', now()->subDays(14))->count();
        $finePerBook = 20;
        $totalFine = $overdue * $finePerBook;
        return view('profile.edit', [
            'user' => $user,
            'recentBorrowings' => $recentBorrowings,
            'totalBorrowed' => $totalBorrowed,
            'totalFine' => $totalFine,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        \Log::info('Profile update request received', [
            'hasFile' => $request->hasFile('profile_photo'),
            'file' => $request->file('profile_photo'),
        ]);

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = uniqid('profile_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profile_photos', $filename);
            \Log::info('Profile photo saved', ['filename' => $filename]);
            // Delete old photo if exists and is not default
            if ($user->profile_photo && \Storage::disk('public')->exists('profile_photos/' . $user->profile_photo)) {
                \Storage::disk('public')->delete('profile_photos/' . $user->profile_photo);
            }
            $user->profile_photo = $filename;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('settings')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function downloadData(Request $request)
    {
        $user = $request->user();
        $borrowings = $user->borrowings()->with('book')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="borrowing_history.csv"',
        ];
        $columns = ['Book Title', 'Borrowed At', 'Returned At', 'Status'];
        $callback = function() use ($borrowings, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($borrowings as $b) {
                fputcsv($file, [
                    $b->book->title ?? 'N/A',
                    $b->borrowed_at,
                    $b->returned_at,
                    $b->status,
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function qr()
    {
        $user = auth()->user();
        return view('profile.qr', compact('user'));
    }
}
