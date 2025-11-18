@extends('layouts.app')

@section('title','Dashboard')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-start mb-4">
    <div>
      <h1 class="card-title-lg">Dashboard</h1>
      <div class="muted-sm">Here's a quick summary of your library status.</div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('books.create') }}" class="btn btn-accent-1 btn-sm"><i class="bi bi-plus-lg me-2"></i> Add Book</a>
      <a href="{{ route('issues.create') }}" class="btn btn-accent-2 btn-sm"><i class="bi bi-arrow-right-circle me-2"></i> Issue Book</a>
      <a href="{{ route('defaulters.index') }}" class="btn btn-accent-3 btn-sm"><i class="bi bi-people-fill me-2"></i> View Defaulters</a>
    </div>
  </div>

  {{-- stats row --}}
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card-pro stat-card d-flex">
        <div class="stat-icon bg-accent-blue"><i class="bi bi-journal-bookmark"></i></div>
        <div>
          <div class="muted-sm">Total Books</div>
          <div class="h4 mb-0">{{ $totalBooks ?? 0 }}</div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card-pro stat-card d-flex">
        <div class="stat-icon bg-accent-green"><i class="bi bi-people"></i></div>
        <div>
          <div class="muted-sm">Students</div>
          <div class="h4 mb-0">{{ $totalStudents ?? 0 }}</div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card-pro stat-card d-flex">
        <div class="stat-icon bg-accent-orange"><i class="bi bi-box-arrow-in-right"></i></div>
        <div>
          <div class="muted-sm">Issued Books</div>
          <div class="h4 mb-0">{{ $issuedCount ?? 0 }}</div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card-pro stat-card d-flex">
        <div class="stat-icon bg-accent-pink"><i class="bi bi-exclamation-circle-fill"></i></div>
        <div>
          <div class="muted-sm">Overdue Books</div>
          <div class="h4 mb-0 text-danger">{{ $overdueCount ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- main grid --}}
  <div class="row">
    <div class="col-lg-8 mb-4">
      <div class="card-pro mb-3" style="padding:18px;">
        <h5 class="mb-3">Issue & Return Trend (Last 30 days)</h5>
        <div style="min-height:460px; position:relative">
          <canvas id="trendChart" style="width:100%;height:420px"></canvas>

          {{-- overlay message if all zero --}}
          @php
            $allZero = true;
            if (isset($issuesSeries) && isset($returnsSeries)) {
              foreach($issuesSeries as $v){ if($v>0){ $allZero=false; break; } }
              if($allZero){ foreach($returnsSeries as $v){ if($v>0){ $allZero=false; break; } } }
            }
          @endphp
          @if($allZero)
            <div style="position:absolute;left:0;right:0;top:0;bottom:0;display:flex;align-items:center;justify-content:center;pointer-events:none">
              <div class="text-muted">No issue/return activity in the last 30 days.</div>
            </div>
          @endif
        </div>
      </div>

      <div class="card-pro p-3">
        <h5 class="mb-3">Most Borrowed Books</h5>
        @if($mostBorrowedBooks && $mostBorrowedBooks->count())
          <ul class="list-group list-group-flush">
            @foreach($mostBorrowedBooks as $book)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <div class="fw-semibold">{{ $book->title }}</div>
                  <div class="small muted-sm">
                    @if($book->authors && $book->authors->count())
                      {{ $book->authors->pluck('name')->join(', ') }}
                    @else
                      Unknown author
                    @endif
                  </div>
                </div>
                <div class="text-end">
                  <div class="fw-bold">{{ $book->borrow_count ?? 0 }}</div>
                  <div class="small muted-sm">borrows</div>
                </div>
              </li>
            @endforeach
          </ul>
        @else
          <div class="p-3 text-muted">No borrow records.</div>
        @endif
      </div>
    </div>

    <div class="col-lg-4 mb-4">
      <div class="card-pro p-3 mb-3">
        <h5 class="mb-3">Recently Added Books</h5>
        @if($recentBooks && $recentBooks->count())
          <div class="list-group list-group-flush">
            @foreach($recentBooks as $b)
              <div class="list-group-item">
                <div class="d-flex align-items-center">
                  <div style="width:58px;height:78px;background:linear-gradient(180deg,#eff3f7,#e2e8f0);border-radius:6px;display:flex;align-items:center;justify-content:center;color:#8a8a8a;margin-right:12px">
                    <i class="bi bi-book" style="font-size:1.6rem;color:rgba(0,0,0,0.35)"></i>
                  </div>
                  <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $b->title }}</div>
                    <div class="small muted-sm">
                      @if($b->authors && $b->authors->count()) {{ $b->authors->pluck('name')->join(', ') }} @else Unknown author @endif
                    </div>
                    <div class="small muted-sm">Added: {{ $b->created_at->format('M j, Y') }}</div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="p-3 text-muted">No recent books.</div>
        @endif
      </div>

      <div class="card-pro p-3">
        <h5 class="mb-3">Top Authors (by books)</h5>
        @if($authorsChart && $authorsChart->count())
          <ul class="list-group list-group-flush">
            @foreach($authorsChart as $a)
              <li class="list-group-item d-flex justify-content-between">
                <div>{{ $a->name }}</div>
                <div class="text-muted">{{ $a->books_count }}</div>
              </li>
            @endforeach
          </ul>
        @else
          <div class="p-3 text-muted">No authors yet.</div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const labels = {!! json_encode($labels ?? []) !!};
  const issues = {!! json_encode($issuesSeries ?? []) !!};
  const returns = {!! json_encode($returnsSeries ?? []) !!};

  const ctx = document.getElementById('trendChart');
  if(!ctx) return;
  const dataExists = (issues && issues.some(v=>v>0)) || (returns && returns.some(v=>v>0));

  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Issues',
          data: issues,
          borderColor: '#2e8ef7',
          backgroundColor: 'rgba(46,142,247,0.08)',
          tension: 0.15,
          pointRadius: 2,
          fill: true,
          borderWidth: 2
        },
        {
          label: 'Returns',
          data: returns,
          borderColor: '#ffb020',
          backgroundColor: 'rgba(255,176,32,0.06)',
          tension: 0.15,
          pointRadius: 2,
          fill: true,
          borderWidth: 2
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      scales: {
        x: {
          ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 10 },
          grid: { color: 'rgba(255,255,255,0.03)' }
        },
        y: {
          beginAtZero: true,
          ticks: { precision: 0, stepSize: 1 },
          grid: { color: 'rgba(255,255,255,0.03)' }
        }
      },
      plugins: {
        legend: { position: 'top' }
      }
    }
  });

  if(!dataExists) {
    chart.data.datasets.forEach(ds => {
      ds.borderColor = 'rgba(255,255,255,0.06)';
      ds.backgroundColor = 'rgba(255,255,255,0.01)';
      ds.borderDash = [6,6];
      ds.pointRadius = 0;
    });
    chart.update();
  }
});
</script>
@endsection
