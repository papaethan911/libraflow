@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Borrow for User (Scan QR)</h1>
    <div class="card mb-4">
        <div class="card-body text-center">
            <h5 class="card-title">Scan User QR Code</h5>
            <div id="qr-reader" style="width: 320px; margin: 0 auto;"></div>
            <div id="qr-result" class="mt-3"></div>
        </div>
    </div>
    <div id="user-info-section" style="display:none;">
        <!-- User info and borrowing form will be dynamically inserted here -->
    </div>
</div>
@endsection

@push('scripts')
<!-- html5-qrcode library (CDN) -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
function onScanSuccess(decodedText, decodedResult) {
    // Stop scanning after first successful scan
    qrScanner.clear();
    document.getElementById('qr-result').innerHTML = '<div class="alert alert-success">Scanned Student ID: <b>' + decodedText + '</b></div>';
    // Lookup user info via AJAX
    fetch("{{ route('borrowings.admin_user_lookup') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ student_id: decodedText })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            document.getElementById('user-info-section').style.display = 'none';
            document.getElementById('qr-result').innerHTML += '<div class="alert alert-danger">' + data.error + '</div>';
        } else {
            // Show user info and borrowing form
            document.getElementById('user-info-section').style.display = 'block';
            document.getElementById('user-info-section').innerHTML = `
                <div class='card mb-4'>
                    <div class='card-body'>
                        <h5 class='card-title'>User Information</h5>
                        <p><b>Name:</b> ${data.name}</p>
                        <p><b>Email:</b> ${data.email}</p>
                        <p><b>Student ID:</b> ${data.student_id}</p>
                        ${data.qr_code ? `<img src='${data.qr_code}' alt='QR Code' style='max-width:100px;'>` : ''}
                    </div>
                </div>
                <div class='card'>
                    <div class='card-body'>
                        <h5 class='card-title'>Borrow Book for User</h5>
                        <form id='borrow-form' method='POST' action='{{ route('borrowings.store') }}'>
                            @csrf
                            <input type='hidden' name='user_id' value='${data.id}'>
                            <div class='mb-3'>
                                <label for='book_id' class='form-label'>Select Book to Borrow</label>
                                <select name='book_id' id='book_id' class='form-control' required>
                                    <option value=''>Choose a book...</option>
                                    @foreach($books as $book)
                                        <option value='{{ $book->id }}'>{{ $book->title }} - {{ $book->author->name }} ({{ $book->category->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type='submit' class='btn btn-primary'>Borrow</button>
                        </form>
                    </div>
                </div>
            `;
        }
    })
    .catch(err => {
        document.getElementById('user-info-section').style.display = 'none';
        document.getElementById('qr-result').innerHTML += '<div class="alert alert-danger">Error: ' + err + '</div>';
    });
}

let qrScanner;
document.addEventListener('DOMContentLoaded', function() {
    qrScanner = new Html5Qrcode("qr-reader");
    qrScanner.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 250 },
        onScanSuccess
    );
});
</script>
@endpush 