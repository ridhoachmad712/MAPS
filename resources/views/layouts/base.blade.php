<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $namaAplikasi = \App\Models\Setting::get('nama_aplikasi');
        $namaPemilik = \App\Models\Setting::get('nama_pemilik');
        $logoAplikasi = \App\Models\Setting::get('logo');
        $deskripsiBawaan = $namaAplikasi.' — arsip dan showcase portofolio capaian mahasiswa '.$namaPemilik.', Universitas Negeri Makassar.';
        $footerTerang = \App\Support\PaletWarna::terang(\App\Models\Setting::get('warna_footer'));
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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.tema')
    @stack('css')
</head>
<body>
    <a href="#konten" class="visually-hidden-focusable position-absolute top-0 start-0 z-3 m-3 btn btn-primary">
        Lewati ke konten
    </a>

    <div class="page">
        @yield('navbar')

        <div class="page-wrapper">
            <main id="konten" class="page-body">
                @yield('isi')
            </main>

            {{-- Footer tiga kolom (warna & isi dari pengaturan) --}}
            <footer class="footer footer-maps d-print-none" data-bs-theme="{{ $footerTerang ? 'light' : 'dark' }}">
                <div class="container-xl">
                    <div class="row gy-4 py-3">
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $logoAplikasi ? asset('storage/'.$logoAplikasi) : asset('favicon.svg') }}"
                                     alt="Logo {{ $namaAplikasi }}" width="40" height="40" class="rounded">
                                <span class="fw-bold text-body">{{ $namaAplikasi }}</span>
                            </div>
                            <p class="text-secondary mt-3 mb-0">
                                {{ \App\Models\Setting::get('footer_deskripsi') }}
                            </p>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <h3 class="h4 text-uppercase">Tautan</h3>
                            <ul class="list-unstyled d-grid gap-2 mb-0">
                                <li><a href="{{ route('showcase.index') }}" class="link-secondary">Beranda</a></li>
                                <li><a href="{{ route('showcase.capaian') }}" class="link-secondary">Capaian</a></li>
                                <li><a href="{{ route('showcase.mahasiswa.indeks') }}" class="link-secondary">Mahasiswa</a></li>
                                <li><a href="{{ route('showcase.statistik') }}" class="link-secondary">Statistik</a></li>
                                <li><a href="{{ route('showcase.tentang') }}" class="link-secondary">Tentang</a></li>
                                @auth
                                    <li><a href="{{ route('dashboard') }}" class="link-secondary">Dasbor</a></li>
                                @else
                                    <li><a href="{{ route('login') }}" class="link-secondary">Masuk</a></li>
                                @endauth
                            </ul>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <h3 class="h4 text-uppercase">Kontak</h3>
                            <ul class="list-unstyled d-grid gap-2 mb-0 text-secondary">
                                <li>{{ \App\Models\Setting::get('footer_kontak1') }}</li>
                                <li>{{ \App\Models\Setting::get('footer_kontak2') }}</li>
                                @if (\App\Models\Setting::get('footer_link_url') !== '')
                                    <li>
                                        <a href="{{ \App\Models\Setting::get('footer_link_url') }}" target="_blank" rel="noopener" class="link-secondary">
                                            {{ \App\Models\Setting::get('footer_link_label') ?: \App\Models\Setting::get('footer_link_url') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="border-top py-3 text-center text-secondary">
                        &copy; {{ date('Y') }} {{ $namaPemilik }}. Hak cipta dilindungi.
                    </div>
                </div>
            </footer>
        </div>
    </div>

    {{-- Kembali ke atas --}}
    <button type="button" id="ke-atas" aria-label="Kembali ke atas"
            class="btn btn-primary btn-icon position-fixed bottom-0 end-0 m-4 rounded-circle d-none" style="z-index: 1030;">
        <i class="bi bi-chevron-up"></i>
    </button>
    <script>
        (() => {
            const tombol = document.getElementById('ke-atas');
            window.addEventListener('scroll', () => {
                tombol.classList.toggle('d-none', window.scrollY <= 600);
            }, { passive: true });
            tombol.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
        })();
    </script>

    @stack('js')
</body>
</html>
