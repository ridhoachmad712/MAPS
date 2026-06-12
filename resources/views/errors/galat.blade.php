{{-- Kerangka halaman error: mandiri, tanpa query database, agar tetap tampil saat aplikasi bermasalah --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>@yield('kode') — @yield('judul')</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="border-top-wide border-primary d-flex flex-column">
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="empty">
                <div class="empty-header">@yield('kode')</div>
                <p class="empty-title">@yield('judul')</p>
                <p class="empty-subtitle text-secondary">@yield('pesan')</p>
                <div class="empty-action">
                    <a href="{{ url('/') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
