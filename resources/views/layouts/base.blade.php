<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $namaAplikasi = \App\Models\Setting::get('nama_aplikasi');
        $namaPemilik = \App\Models\Setting::get('nama_pemilik');
        $logoAplikasi = \App\Models\Setting::get('logo');
        $deskripsiBawaan = $namaAplikasi.' — arsip dan showcase portofolio capaian mahasiswa '.$namaPemilik.', Universitas Negeri Makassar.';
    @endphp
    <title>@yield('judul', $namaAplikasi) — {{ $namaAplikasi }} {{ $namaPemilik }}</title>
    <meta name="description" content="@yield('deskripsi', $deskripsiBawaan)">
    @yield('robots')
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph & Twitter Card — tautan yang dibagikan tampil rapi --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $namaAplikasi }} {{ $namaPemilik }}">
    <meta property="og:title" content="@yield('judul', $namaAplikasi) — {{ $namaAplikasi }} {{ $namaPemilik }}">
    <meta property="og:description" content="@yield('deskripsi', $deskripsiBawaan)">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', $logoAplikasi ? asset('storage/'.$logoAplikasi) : asset('favicon.svg'))">
    <meta name="twitter:card" content="summary">

    @stack('head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.tema')
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

    {{-- Footer tiga kolom (warna & isi dari pengaturan) --}}
    <footer class="footer-maps mt-16">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:grid-cols-2 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ $logoAplikasi ? asset('storage/'.$logoAplikasi) : asset('favicon.svg') }}" alt="Logo {{ $namaAplikasi }}" class="h-10 w-10 rounded-lg object-contain">
                    <span class="footer-judul text-base font-bold">{{ $namaAplikasi }}</span>
                </div>
                <p class="mt-4 text-sm leading-relaxed">
                    {{ \App\Models\Setting::get('footer_deskripsi') }}
                </p>
            </div>
            <div>
                <h3 class="footer-judul text-sm font-semibold uppercase tracking-wider">Tautan</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="{{ route('showcase.index') }}" class="footer-tautan">Beranda</a></li>
                    <li><a href="{{ route('showcase.capaian') }}" class="footer-tautan">Capaian</a></li>
                    <li><a href="{{ route('showcase.mahasiswa.indeks') }}" class="footer-tautan">Mahasiswa</a></li>
                    <li><a href="{{ route('showcase.statistik') }}" class="footer-tautan">Statistik</a></li>
                    <li><a href="{{ route('showcase.tentang') }}" class="footer-tautan">Tentang</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="footer-tautan">Dasbor</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="footer-tautan">Masuk</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <h3 class="footer-judul text-sm font-semibold uppercase tracking-wider">Kontak</h3>
                <ul class="mt-4 space-y-2 text-sm leading-relaxed">
                    <li>{{ \App\Models\Setting::get('footer_kontak1') }}</li>
                    <li>{{ \App\Models\Setting::get('footer_kontak2') }}</li>
                    @if (\App\Models\Setting::get('footer_link_url') !== '')
                        <li>
                            <a href="{{ \App\Models\Setting::get('footer_link_url') }}" target="_blank" rel="noopener" class="footer-tautan underline">
                                {{ \App\Models\Setting::get('footer_link_label') ?: \App\Models\Setting::get('footer_link_url') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="footer-bawah py-5 text-center text-xs">
            &copy; {{ date('Y') }} {{ $namaPemilik }}. Hak cipta dilindungi.
        </div>
    </footer>

    @stack('js')
</body>
</html>
