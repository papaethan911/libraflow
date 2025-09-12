@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">
        <i class="fas fa-chart-bar text-primary"></i> 
        Library Inventory & Borrowing Report
    </h1>
    
    <!-- AI-Powered Insights Alert -->
    @if(isset($smartNotifications) && count($smartNotifications) > 0)
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-robot"></i> 
        <strong>AI Insights:</strong> {{ $smartNotifications[0]['message'] ?? 'AI has detected some interesting patterns in your library data.' }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Basic Stats -->
    <div class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card text-bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Books</h5>
                        <p class="card-text fs-3">{{ $totalBooks }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Available Books</h5>
                        <p class="card-text fs-3">{{ $availableBooks }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Borrowed Books</h5>
                        <p class="card-text fs-3">{{ $borrowedBooks }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Overdue Books</h5>
                        <p class="card-text fs-3">{{ $realTimeData['overdue_books'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Predictions Section -->
    @if(isset($overduePredictions) && $overduePredictions->count() > 0)
    <div class="card mb-4 border-warning">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="fas fa-exclamation-triangle"></i> 
                AI Overdue Predictions
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Books that are likely to be overdue based on AI analysis:</p>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>User</th>
                            <th>Borrowed Date</th>
                            <th>Overdue Probability</th>
                            <th>Predicted Return</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($overduePredictions->take(5) as $prediction)
                        <tr>
                            <td>{{ $prediction->book->title ?? 'Unknown' }}</td>
                            <td>{{ $prediction->user->name ?? 'Unknown' }}</td>
                            <td>{{ \Carbon\Carbon::parse($prediction->borrowed_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-danger" style="width: {{ $prediction->overdue_probability * 100 }}%">
                                        {{ number_format($prediction->overdue_probability * 100, 1) }}%
                                    </div>
                                </div>
                            </td>
                            <td>{{ $prediction->predicted_return_date ? \Carbon\Carbon::parse($prediction->predicted_return_date)->format('M d, Y') : 'Unknown' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Real-time Activity -->
    @if(isset($realTimeData))
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-bg-info">
                <div class="card-body text-center">
                    <h6 class="card-title">Today's Borrowings</h6>
                    <p class="card-text fs-4">{{ $realTimeData['today_borrowings'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success">
                <div class="card-body text-center">
                    <h6 class="card-title">Today's Returns</h6>
                    <p class="card-text fs-4">{{ $realTimeData['today_returns'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-primary">
                <div class="card-body text-center">
                    <h6 class="card-title">Active Users Today</h6>
                    <p class="card-text fs-4">{{ $realTimeData['active_users_today'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-secondary">
                <div class="card-body text-center">
                    <h6 class="card-title">Available Books</h6>
                    <p class="card-text fs-4">{{ $realTimeData['available_books'] }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Borrowing Records -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> 
                Borrowing Records
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Book</th>
                <th>User</th>
                <th>Borrowed At</th>
                <th>Returned At</th>
                <th>Status</th>
                            <th>Duration</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowings as $borrowing)
            <tr>
                <td>{{ $borrowing->book->title ?? '-' }}</td>
                <td>{{ $borrowing->user->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($borrowing->borrowed_at)->format('M d, Y H:i') }}</td>
                            <td>{{ $borrowing->returned_at ? \Carbon\Carbon::parse($borrowing->returned_at)->format('M d, Y H:i') : '-' }}</td>
                            <td>
                                @if($borrowing->status === 'borrowed')
                                    <span class="badge bg-warning">Borrowed</span>
                                @else
                                    <span class="badge bg-success">Returned</span>
                                @endif
                            </td>
                            <td>
                                @if($borrowing->returned_at)
                                    {{ \Carbon\Carbon::parse($borrowing->borrowed_at)->diffInDays(\Carbon\Carbon::parse($borrowing->returned_at)) }} days
                                @else
                                    {{ \Carbon\Carbon::parse($borrowing->borrowed_at)->diffInDays(now()) }} days
                                @endif
                            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
            </div>
            <div class="mt-3">
        {{ $borrowings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 