<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;

class SystemSettingsController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $settings = SystemSetting::all()->keyBy('key');
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'borrowing_duration_days' => 'required|integer|min:1|max:365',
            'max_renewals' => 'required|integer|min:0|max:10',
            'fine_per_day' => 'required|numeric|min:0|max:1000',
            'max_books_per_user' => 'required|integer|min:1|max:20',
            'self_service_enabled' => 'boolean',
            'email_notifications_enabled' => 'boolean',
        ]);

        $settings = [
            'borrowing_duration_days' => $request->borrowing_duration_days,
            'max_renewals' => $request->max_renewals,
            'fine_per_day' => $request->fine_per_day,
            'max_books_per_user' => $request->max_books_per_user,
            'self_service_enabled' => $request->has('self_service_enabled'),
            'email_notifications_enabled' => $request->has('email_notifications_enabled'),
        ];

        foreach ($settings as $key => $value) {
            SystemSetting::set($key, $value, $key === 'fine_per_day' ? 'number' : ($key === 'borrowing_duration_days' || $key === 'max_renewals' || $key === 'max_books_per_user' ? 'number' : 'boolean'));
        }

        return redirect()->back()->with('success', 'System settings updated successfully!');
    }
}