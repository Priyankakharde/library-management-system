<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>{{ $title ?? 'Library Management System' }}</title>

  {{-- Vite (or mix) --}}
  @if (file_exists(public_path('build/manifest.json')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @else
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
  @endif

  <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="flex">

    {{-- sidebar — include your sidebar partial (if you made layouts.sidebar) --}}
    @includeIf('layouts.sidebar')

    <div class="flex-1 flex flex-col min-h-screen">
      <header class="bg-white shadow px-6 py-3 flex items-center justify-between">
        <div>
          {{-- optional header slot --}}
          @isset($header)
            {{ $header }}
          @else
            <h1 class="text-2xl font-semibold">{{ $title ?? 'Dashboard' }}</h1>
          @endisset
        </div>

        <div class="flex items-center gap-4">
          @auth
            <span class="text-sm">Welcome, {{ auth()->user()->role === 'admin' ? 'Admin' : ucfirst(auth()->user()->role ?? 'User') }}</span>
          @else
            <span class="text-sm">Welcome, Guest</span>
          @endauth
        </div>
      </header>

      <main class="p-6 flex-1">
        {{ $slot }}
      </main>

      <footer class="p-4 text-right text-sm text-gray-500 bg-white border-t">
        © {{ date('Y') }} Library
      </footer>
    </div>
  </div>

  <script>
    document.getElementById('mobileToggle')?.addEventListener('click', function () {
      document.getElementById('sidebar')?.classList.toggle('hidden');
    });
  </script>
</body>
</html>
