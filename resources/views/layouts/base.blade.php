<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'MAPS') — MAPS Prodi Manajemen FEB UNM</title>
    <meta name="description" content="MAPS — arsip dan showcase portofolio capaian mahasiswa Program Studi Manajemen FEB Universitas Negeri Makassar.">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('css')
</head>
<body class="flex min-h-screen flex-col font-sans text-gray-900 antialiased">
    <a href="#konten"
       class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-navy-600 focus:shadow-lg">
        Lewati ke konten
    </a>

    @yield('navbar')

    <main id="konten" class="grow">
        @yield('isi')
    </main>

    {{-- Kembali ke atas --}}
    <button type="button"
            x-data="{ tampil: false }"
            x-init="window.addEventListener('scroll', () => tampil = window.scrollY > 600, { passive: true })"
            x-show="tampil" x-cloak
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            aria-label="Kembali ke atas"
            class="fixed bottom-6 right-6 z-40 rounded-full bg-navy-500 p-3 text-white shadow-lg transition hover:bg-navy-600">
        <i class="bi bi-chevron-up block text-lg leading-none"></i>
    </button>

    {{-- Footer gelap tiga kolom --}}
    <footer class="mt-16 bg-gray-900 text-gray-300">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:grid-cols-2 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('favicon.svg') }}" alt="Logo MAPS" class="h-10 w-10 rounded-lg">
                    <span class="text-base font-bold text-white">MAPS</span>
                </div>
                <p class="mt-4 text-sm leading-relaxed">
                    Management Student Achievement Portfolio System — arsip dan showcase
                    portofolio capaian mahasiswa Prodi Manajemen FEB UNM.
                </p>
            </div>
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Tautan</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="{{ route('showcase.index') }}" class="hover:text-navy-200">Data Capaian</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="hover:text-navy-200">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-navy-200">Masuk</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Kontak</h3>
                <ul class="mt-4 space-y-2 text-sm leading-relaxed">
                    <li>Program Studi Manajemen, Fakultas Ekonomi dan Bisnis</li>
                    <li>Universitas Negeri Makassar, Kampus Gunung Sari, Makassar</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 py-5 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} Program Studi Manajemen FEB UNM. Hak cipta dilindungi.
        </div>
    </footer>

    @stack('js')
</body>
</html>
