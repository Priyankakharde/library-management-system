<!-- Dashboard improved sections -->
<div class="row mt-4">
    <!-- Section 1: Issue Trend Chart (full-width or half) -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Issue & Return Trend (Last 30 days)</h5>
            </div>
            <div class="card-body">
                <canvas id="trendChart" width="100%" height="40"></canvas>
            </div>
        </div>

        <!-- Section 2: Most Borrowed Books -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Most Borrowed Books</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @forelse($mostBorrowed as $book)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ \Illuminate\Support\Str::limit($book['title'], 60) }}</strong><br>
                                <small class="text-muted">Borrowed {{ $book['borrow_count'] }} time(s)</small>
                            </div>
                            <span class="badge badge-primary badge-pill">{{ $book['borrow_count'] }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">No borrow records yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Section 3: Recently Added Books -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Recently Added Books</h5>
            </div>
            <div class="card-body">
                @if($recentBooks->count())
                    @foreach($recentBooks as $book)
                        <div class="d-flex mb-3">
                            <div style="width:64px; height:90px; overflow:hidden; border-radius:4px; background:#f5f5f5; flex:0 0 64px;">
                                @if($book->cover_path)
                                    <img src="{{ asset($book->cover_path) }}" alt="cover" style="width:100%; height:100%; object-fit:cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 w-100 text-muted">
                                        <small>No image</small>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3" style="flex:1">
                                <strong>{{ \Illuminate\Support\Str::limit($book->title, 60) }}</strong><br>
                                <small class="text-muted">{{ optional($book->author)->name ?? 'Unknown author' }}</small><br>
                                <small class="text-muted">Added: {{ $book->created_at->format('M j, Y') }}</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No recent books.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN (put once in layout or here) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data from controller
        const labels = @json($labels);
        const issuesData = @json($issuesPerDay);
        const returnsData = @json($returnsPerDay);

        const ctx = document.getElementById('trendChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Issues',
                        data: issuesData,
                        fill: false,
                        borderWidth: 2,
                        tension: 0.2,
                    },
                    {
                        label: 'Returns',
                        data: returnsData,
                        fill: false,
                        borderWidth: 2,
                        tension: 0.2,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { mode: 'index', intersect: false }
                }
            }
        });
    });
</script>
