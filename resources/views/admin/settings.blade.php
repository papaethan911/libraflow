@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-cogs"></i> System Settings</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Library Configuration</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="borrowing_duration_days" class="form-label">Borrowing Duration (Days)</label>
                                <input type="number" class="form-control" id="borrowing_duration_days" 
                                       name="borrowing_duration_days" 
                                       value="{{ $settings['borrowing_duration_days']->value ?? 14 }}" 
                                       min="1" max="365" required>
                                <div class="form-text">How many days a book can be borrowed</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_renewals" class="form-label">Maximum Renewals</label>
                                <input type="number" class="form-control" id="max_renewals" 
                                       name="max_renewals" 
                                       value="{{ $settings['max_renewals']->value ?? 2 }}" 
                                       min="0" max="10" required>
                                <div class="form-text">Maximum number of times a book can be renewed</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fine_per_day" class="form-label">Fine Per Day (₱)</label>
                                <input type="number" class="form-control" id="fine_per_day" 
                                       name="fine_per_day" 
                                       value="{{ $settings['fine_per_day']->value ?? 5.00 }}" 
                                       min="0" max="1000" step="0.01" required>
                                <div class="form-text">Fine amount charged per day for overdue books</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_books_per_user" class="form-label">Max Books Per User</label>
                                <input type="number" class="form-control" id="max_books_per_user" 
                                       name="max_books_per_user" 
                                       value="{{ $settings['max_books_per_user']->value ?? 3 }}" 
                                       min="1" max="20" required>
                                <div class="form-text">Maximum number of books a user can borrow at once</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="self_service_enabled" 
                                           name="self_service_enabled" 
                                           {{ ($settings['self_service_enabled']->value ?? 'true') === 'true' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="self_service_enabled">
                                        Enable Self-Service Checkout
                                    </label>
                                    <div class="form-text">Allow students to borrow books without librarian assistance</div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="email_notifications_enabled" 
                                           name="email_notifications_enabled" 
                                           {{ ($settings['email_notifications_enabled']->value ?? 'true') === 'true' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_notifications_enabled">
                                        Enable Email Notifications
                                    </label>
                                    <div class="form-text">Send email notifications for overdue books</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                            <a href="{{ route('admin.settings') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">System Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Current Settings:</strong>
                        <ul class="list-unstyled mt-2">
                            <li><i class="fas fa-calendar text-primary"></i> Borrowing Duration: {{ $settings['borrowing_duration_days']->value ?? 14 }} days</li>
                            <li><i class="fas fa-redo text-info"></i> Max Renewals: {{ $settings['max_renewals']->value ?? 2 }}</li>
                            <li><i class="fas fa-dollar-sign text-warning"></i> Fine Rate: ₱{{ number_format($settings['fine_per_day']->value ?? 5.00, 2) }}/day</li>
                            <li><i class="fas fa-book text-success"></i> Max Books/User: {{ $settings['max_books_per_user']->value ?? 3 }}</li>
                            <li><i class="fas fa-shopping-cart text-secondary"></i> Self-Service: {{ ($settings['self_service_enabled']->value ?? 'true') === 'true' ? 'Enabled' : 'Disabled' }}</li>
                            <li><i class="fas fa-envelope text-danger"></i> Email Notifications: {{ ($settings['email_notifications_enabled']->value ?? 'true') === 'true' ? 'Enabled' : 'Disabled' }}</li>
                        </ul>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Changes to these settings will affect all users immediately.
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('borrowings.update_fines') }}" class="btn btn-outline-warning" 
                           onclick="return confirm('Update fines for all overdue books?')">
                            <i class="fas fa-calculator"></i> Update All Fines
                        </a>
                        <a href="{{ route('analytics.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-chart-line"></i> View Analytics
                        </a>
                        <a href="{{ route('borrowings.report') }}" class="btn btn-outline-info">
                            <i class="fas fa-file-alt"></i> Generate Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
