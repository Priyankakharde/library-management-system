@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    /* small visual polish for the dashboard */
    .stat-card { border-radius: 10px; box-shadow: 0 6px 14px rgba(20,30,60,0.06); }
    .quick-actions .btn { margin-right: .5rem; }
    .chart-card { min-height: 360px; }
</style>

<div class="container-fluid px-4">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h1 class="fw-bold">Dashboard</h1>
            <p class="text-muted">Here’s a quick summary of your library status.</p>
        </div>

        <div class="col-auto text-end">
            <div class="btn btn-sm btn-light rounded-pill shadow-sm">
                <i class="fa fa-user-circle me-2"></i> Admin
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="p-3 stat-card bg-white">
                <div class="small text-primary"><i class="fa fa-book me-1"></i> Total Books</div>
                <div class="h3 mt-2">{{ $totalBooks ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 stat-card bg-white">
                <div class="small text-success"><i class="fa fa-user-graduate me-1"></i> Students</div>
                <div class="h3 mt-2">{{ $totalStudents ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 stat-card bg-white">
                <div class="small text-info"><i class="fa fa-book-reader me-1"></i> Issued Books</div>
                <div class="h3 mt-2">{{ $issuedBooks ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 stat-card bg-white">
                <div class="small text-danger"><i class="fa fa-exclamation-triangle me-1"></i> Overdue Books</div>
                <div class="h3 mt-2">{{ $overdueBooks ?? 0 }}</div>
            </div>
        </div>
    </div>

    <!-- Quick actions -->
    <div class="mb-4 quick-actions">
        <a href="{{ route('books.create') }}" class="btn btn-primary">+ Add Book</a>
        <a href="{{ route('issues.create') }}" class="btn btn-warning text-dark">Issue Book</a>
        <a href="{{ route('defaulters.index') }}" class="btn btn-danger">View Defaulters</a>
    </div>

    <!-- Chart card -->
    <div class="card chart-card mb-5">
        <div class="card-header bg-white">
            <strong>Books per Author</strong>
        </div>

        <div class="card-body">
            <!-- If authors provided by controller, chart will use them.
                 If not, we show demo data so UI isn't empty. -->
            <div style="height:360px;">
                <canvas id="booksByAuthorChart" style="width:100%; height:100%;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Data from server (may be empty arrays)
    let authors = {!! json_encode($authors ?? []) !!};
    let counts  = {!! json_encode($booksCount ?? []) !!};

    // If server returned no author data, show a tidy demo dataset
    if (!Array.isArray(authors) || authors.length === 0) {
        authors = [
            "Mark Twain",
            "Robert C. Martin",
            "Martin Fowler",
            "Charles Dickens",
            "Jane Austen",
            "Andrew Hunt",
            "David Thomas",
            "Agatha Christie",
            "George Orwell",
            "F. Scott Fitzgerald",
            "J. K. Rowling"
        ];
        // set counts to show a few authors with books and rest zero
        counts = [2, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0];
    }

    // Keep labels and data in the same order but limit to top 12 for neat UI
    const maxItems = 12;
    if (authors.length > maxItems) {
        authors = authors.slice(0, maxItems);
        counts = counts.slice(0, maxItems);
    }

    // Create horizontal bar chart with nice gradient and rounded bars
    const canvas = document.getElementById('booksByAuthorChart');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    // Create gradient for bars (left→right)
    const grad = ctx.createLinearGradient(0, 0, canvas.width, 0);
    grad.addColorStop(0, 'rgba(37,99,235,0.95)');   // blue
    grad.addColorStop(1, 'rgba(59,130,246,0.85)');  // lighter blue

    // adjust font sizing based on number of labels
    const labelFontSize = authors.length > 8 ? 12 : 14;

    // Chart configuration
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: authors,
            datasets: [{
                label: 'Books',
                data: counts,
                backgroundColor: grad,
                borderRadius: 8,
                barThickness: authors.length <= 6 ? 18 : 12,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, precision:0 },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                y: {
                    ticks: { font: { size: labelFontSize, weight: 600 } },
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.parsed.x + ' book' + (ctx.parsed.x === 1 ? '' : 's');
                        }
                    }
                }
            },
            animation: {
                duration: 700,
                easing: 'easeOutQuart'
            }
        }
    });

    // redraw gradient on resize so it fills the canvas width properly
    window.addEventListener('resize', () => {
        // update gradient
        const w = canvas.width;
        const newGrad = ctx.createLinearGradient(0, 0, w, 0);
        newGrad.addColorStop(0, 'rgba(37,99,235,0.95)');
        newGrad.addColorStop(1, 'rgba(59,130,246,0.85)');
        myChart.data.datasets[0].backgroundColor = newGrad;
        myChart.update();
    });
});
</script>
@endsection
