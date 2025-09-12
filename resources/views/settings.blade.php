@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="list-group sticky-top">
            <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="tab">Profile</a>
            <a href="#account" class="list-group-item list-group-item-action" data-bs-toggle="tab">Account</a>
            <a href="#security" class="list-group-item list-group-item-action" data-bs-toggle="tab">Security</a>
        </div>
    </div>
    <div class="col-md-9">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="profile">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">Profile Information</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input type="text" class="form-control" id="student_id" name="student_id" value="{{ old('student_id', $user->student_id) }}" readonly>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="account">
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">Account Details</div>
                    <div class="card-body">
                        <p><b>Role:</b> {{ ucfirst($user->role) }}</p>
                        <p><b>Member Since:</b> {{ $user->created_at->format('F d, Y') }}</p>
                        <p><b>Student ID:</b> {{ $user->student_id }}</p>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="security">
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">Change Password</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current-password">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                            </div>
                            <button type="submit" class="btn btn-warning">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Enable Bootstrap tab navigation
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(tab) {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            var tabTrigger = new bootstrap.Tab(tab);
            tabTrigger.show();
        });
    });
</script>
@endsection 