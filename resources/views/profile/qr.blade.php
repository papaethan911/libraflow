@extends('layouts.app')

@section('content')
<div class="container py-4 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 d-flex justify-content-center">
            <div class="card shadow-sm w-100" style="max-width: 400px;">
                <div class="card-body text-center">
                    <h3 class="mb-3">My Library QR Code</h3>
                    @if($user->qr_code)
                        <div class="mb-3 d-flex justify-content-center">
                            <img src="{{ asset('storage/' . $user->qr_code) }}" alt="QR Code" style="max-width: 250px; display: block; margin: 0 auto;">
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-secondary">Student ID: {{ $user->student_id }}</span>
                        </div>
                        <p class="text-muted">Show this QR code to the librarian to borrow books.</p>
                    @else
                        <div class="alert alert-warning">No QR code found for your account.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 