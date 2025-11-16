<!-- resources/views/profile/partials/show.blade.php -->

<div class="profile-show-card card p-4 shadow-sm">
  <div class="d-flex align-items-center gap-4 mb-4">
    {{-- Avatar --}}
    @php
      $user = auth()->user();
      $avatarUrl = null;
      if ($user) {
        // If avatar field stores a public asset path or URL
        if (!empty($user->avatar)) {
          $avatarUrl = (filter_var($user->avatar, FILTER_VALIDATE_URL)) ? $user->avatar : asset($user->avatar);
        }
      }
    @endphp

    <div style="min-width:96px; min-height:96px;">
      @if ($avatarUrl)
        <img src="{{ $avatarUrl }}" alt="avatar" class="rounded-circle" style="width:96px; height:96px; object-fit:cover; border:2px solid #eee;">
      @else
        <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white"
             style="width:96px; height:96px; font-weight:700; font-size:28px;">
          {{ $user ? strtoupper(substr($user->name ?? $user->email, 0, 1)) : '?' }}
        </div>
      @endif
    </div>

    <div class="flex-grow-1">
      <h2 class="mb-1">{{ $user->name ?? 'Guest' }}</h2>
      <div class="text-muted mb-1">{{ $user->email ?? '' }}</div>
      @if (!empty($user->phone))
        <div class="text-muted small">Phone: {{ $user->phone }}</div>
      @endif
      @if (!empty($user->role) || method_exists($user, 'isAdmin'))
        <div class="mt-2">
          <span class="badge bg-info text-dark">
            @if (!empty($user->role)) {{ ucfirst($user->role) }}
            @elseif (method_exists($user, 'isAdmin') && $user->isAdmin()) Admin
            @else User
            @endif
          </span>
        </div>
      @endif
    </div>

    <div class="text-end">
      {{-- Edit / Change password buttons: use named route if exists, otherwise fallback --}}
      @php
        $editRoute = \Illuminate\Support\Facades\Route::has('profile.edit') ? route('profile.edit') : url('/profile/edit');
        $changePwdRoute = \Illuminate\Support\Facades\Route::has('password.edit') ? route('password.edit') : url('/password/change');
      @endphp

      <div class="d-flex flex-column">
        <a href="{{ $editRoute }}" class="btn btn-sm btn-primary mb-2">Edit profile</a>
        <a href="{{ $changePwdRoute }}" class="btn btn-sm btn-outline-secondary">Change password</a>
      </div>
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-md-6 mb-3">
      <h6 class="mb-2">Account details</h6>
      <dl class="row mb-0">
        <dt class="col-5">Name</dt>
        <dd class="col-7">{{ $user->name ?? '-' }}</dd>

        <dt class="col-5">Email</dt>
        <dd class="col-7">{{ $user->email ?? '-' }}</dd>

        <dt class="col-5">Phone</dt>
        <dd class="col-7">{{ $user->phone ?? '-' }}</dd>

        <dt class="col-5">Registered</dt>
        <dd class="col-7">{{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}</dd>

        <dt class="col-5">Last updated</dt>
        <dd class="col-7">{{ $user->updated_at ? $user->updated_at->diffForHumans() : '-' }}</dd>
      </dl>
    </div>

    <div class="col-md-6 mb-3">
      <h6 class="mb-2">Library quick stats</h6>

      {{-- These variables are provided by the view composer in AppServiceProvider earlier in the conversation.
           If they don't exist, the template displays "N/A". --}}
      <ul class="list-unstyled mb-0">
        <li><strong>Total books:</strong>
          {{ isset($shared_totalBooks) ? $shared_totalBooks : (isset($shared_total_books) ? $shared_total_books : 'N/A') }}
        </li>
        <li><strong>Total students:</strong>
          {{ isset($shared_totalStudents) ? $shared_totalStudents : 'N/A' }}
        </li>
        <li><strong>Currently issued:</strong>
          {{ isset($shared_issuedCount) ? $shared_issuedCount : 'N/A' }}
        </li>
        <li><strong>Overdue:</strong>
          {{ isset($shared_overdueCount) ? $shared_overdueCount : 'N/A' }}
        </li>
      </ul>
    </div>
  </div>

  <hr>

  <div class="d-flex justify-content-between align-items-center">
    <div>
      {{-- Optionally show a small note when profile is incomplete --}}
      @php
        $incomplete = empty($user->name) || empty($user->email);
      @endphp

      @if ($incomplete)
        <div class="small text-warning">Your profile is missing some information. Please update your profile.</div>
      @endif
    </div>

    <div class="text-end">
      {{-- Logout link if route exists --}}
      @php
        $logoutRoute = \Illuminate\Support\Facades\Route::has('logout') ? route('logout') : null;
      @endphp

      @if ($logoutRoute)
        <form action="{{ $logoutRoute }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button>
        </form>
      @else
        <a href="{{ url('/logout') }}" class="btn btn-sm btn-outline-danger">Logout</a>
      @endif
    </div>
  </div>
</div>
