@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-chart-line"></i> Library Analytics Dashboard</h1>
        <div>
            <a href="{{ route('borrowings.report') }}" class="btn btn-outline-primary">
                <i class="fas fa-file-alt"></i> Basic Reports
            </a>
            <a href="{{ route('borrowings.update_fines') }}" class="btn btn-outline-warning" 
               onclick="return confirm('Update fines for all overdue books?')">
                <i class="fas fa-calculator"></i> Update Fines
            </a>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalBooks) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Available Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($availableBooks) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active Borrowings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($activeBorrowings) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Overdue Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($overdueBorrowings) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fine Statistics -->
    <div class="row mb-4">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Fines</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($totalFines, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Unpaid Fines</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($unpaidFines, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Popular Books -->
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Most Popular Books</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Author</th>
                                    <th>Borrowings</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($popularBooks as $book)
                                <tr>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->author->name }}</td>
                                    <td><span class="badge badge-primary">{{ $book->borrowings_count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Categories -->
        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Popular Categories</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Borrowings</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($popularCategories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td><span class="badge badge-success">{{ $category->borrowings_count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Overdue Books -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">Overdue Books</h6>
                </div>
                <div class="card-body">
                    @if($overdueDetails->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th>Borrower</th>
                                        <th>Due Date</th>
                                        <th>Days Overdue</th>
                                        <th>Fine</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($overdueDetails as $borrowing)
                                    <tr>
                                        <td>{{ $borrowing->book->title }}</td>
                                        <td>{{ $borrowing->user->name }}</td>
                                        <td>{{ $borrowing->due_date->format('M d, Y') }}</td>
                                        <td><span class="badge badge-danger">{{ now()->diffInDays($borrowing->due_date) }}</span></td>
                                        <td>₱{{ number_format($borrowing->calculateFine(), 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No overdue books at the moment.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-info">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Book</th>
                                    <th>Borrower</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBorrowings as $borrowing)
                                <tr>
                                    <td>{{ Str::limit($borrowing->book->title, 20) }}</td>
                                    <td>{{ Str::limit($borrowing->user->name, 15) }}</td>
                                    <td>{{ $borrowing->created_at->format('M d') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Borrowing Trends</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" width="400" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Daily Activity (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <canvas id="dailyChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly borrowing trends chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthlyData = @json($monthlyBorrowings);
const monthlyLabels = monthlyData.map(item => {
    const date = new Date(item.year, item.month - 1);
    return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
});
const monthlyCounts = monthlyData.map(item => item.count);

new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'Borrowings',
            data: monthlyCounts,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Daily activity chart
const dailyCtx = document.getElementById('dailyChart').getContext('2d');
const dailyData = @json($dailyBorrowings);
const dailyLabels = dailyData.map(item => {
    const date = new Date(item.date);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
});
const dailyCounts = dailyData.map(item => item.count);

new Chart(dailyCtx, {
    type: 'bar',
    data: {
        labels: dailyLabels,
        datasets: [{
            label: 'Daily Borrowings',
            data: dailyCounts,
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}
.border-left-secondary {
    border-left: 0.25rem solid #858796 !important;
}
</style>
@endsection
