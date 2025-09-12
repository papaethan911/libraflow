<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BorrowingController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SystemSettingsController;

Route::get('/', function () {
    return Auth::check()
        ? view('dashboard')
        : view('welcome');
});

Route::get('/dashboard', function () {
    return Auth::check()
        ? view('dashboard')
        : redirect()->route('login');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', function() { return redirect()->route('settings'); });
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/download-data', [ProfileController::class, 'downloadData'])->name('profile.download_data');
    Route::get('/profile/qr', [\App\Http\Controllers\ProfileController::class, 'qr'])->name('profile.qr');

    Route::resource('books', BookController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('borrowings', BorrowingController::class);
    Route::patch('borrowings/{borrowing}/return', [BorrowingController::class, 'update'])->name('borrowings.return');
    Route::get('/my-borrowings', [BorrowingController::class, 'myHistory'])->name('borrowings.my_history');
    Route::get('/admin/report', [BorrowingController::class, 'report'])->name('borrowings.report');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    
    // Self-service routes
    Route::get('/self-checkout', [BorrowingController::class, 'selfCheckout'])->name('borrowings.self_checkout');
    Route::post('/borrowings/{borrowing}/renew', [BorrowingController::class, 'renew'])->name('borrowings.renew');
    Route::post('/borrowings/{borrowing}/pay-fine', [BorrowingController::class, 'payFine'])->name('borrowings.pay_fine');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/borrow', [\App\Http\Controllers\BorrowingController::class, 'adminBorrow'])->name('borrowings.admin_borrow');
    Route::post('/admin/borrow/user-lookup', [\App\Http\Controllers\BorrowingController::class, 'adminUserLookup'])->name('borrowings.admin_user_lookup');
    Route::get('/admin/update-fines', [\App\Http\Controllers\BorrowingController::class, 'updateFines'])->name('borrowings.update_fines');
    Route::post('/admin/update-fines', [\App\Http\Controllers\BorrowingController::class, 'updateFines'])->name('borrowings.update_fines.post');
    Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/admin/settings', [SystemSettingsController::class, 'index'])->name('admin.settings');
    Route::post('/admin/settings', [SystemSettingsController::class, 'update'])->name('admin.settings.update');
});

require __DIR__.'/auth.php';
