{{-- Variabel warna tema dari Pengaturan Tampilan (admin) — berlaku tanpa build ulang --}}
@php
    use App\Models\Setting;
    use App\Support\PaletWarna;

    $gradasi = PaletWarna::gradasi(Setting::get('warna_primer'));
    $hero = PaletWarna::gradasi(Setting::get('warna_hero'));

    $navbarBg = Setting::get('warna_navbar');
    $navbarTerang = PaletWarna::terang($navbarBg);

    $footerBg = Setting::get('warna_footer');
    $footerTerang = PaletWarna::terang($footerBg);

    $logo = Setting::get('logo');

    if ($logo === '') {
        [$ir, $ig, $ib] = PaletWarna::hexKeRgb(Setting::get('warna_primer'));
        $inisial = mb_strtoupper(mb_substr(Setting::get('nama_aplikasi') ?: 'M', 0, 1));
        $faviconSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">'
            .sprintf('<rect width="64" height="64" rx="14" fill="#%02x%02x%02x"/>', $ir, $ig, $ib)
            .'<text x="32" y="45" font-family="Arial, sans-serif" font-size="36" font-weight="bold" fill="#ffffff" text-anchor="middle">'
            .e($inisial)
            .'</text></svg>';
    }
@endphp

@if ($logo !== '')
    <link rel="icon" href="{{ asset('storage/'.$logo) }}">
@else
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,{{ rawurlencode($faviconSvg) }}">
@endif

<style>
    :root {
        @foreach ($gradasi as $stop => $warna)
            --color-navy-{{ $stop }}: {{ $warna }};
        @endforeach

        --hero-1: {{ $hero[500] }};
        --hero-2: {{ $hero[600] }};
        --hero-3: {{ $hero[700] }};

        --warna-navbar: {{ $navbarBg }}f2;
        --navbar-teks: {{ $navbarTerang ? '#374151' : 'rgb(255 255 255 / 0.85)' }};
        --navbar-teks-kuat: {{ $navbarTerang ? '#111827' : '#ffffff' }};
        --navbar-sub: {{ $navbarTerang ? '#6b7280' : 'rgb(255 255 255 / 0.65)' }};
        --navbar-hover-bg: {{ $navbarTerang ? '#f3f4f6' : 'rgb(255 255 255 / 0.12)' }};
        --navbar-aktif-bg: {{ $navbarTerang ? $gradasi[50] : 'rgb(255 255 255 / 0.18)' }};
        --navbar-aktif-teks: {{ $navbarTerang ? $gradasi[600] : '#ffffff' }};
        --navbar-garis: {{ $navbarTerang ? '#e5e7eb' : 'rgb(255 255 255 / 0.15)' }};

        --footer-bg: {{ $footerBg }};
        --footer-teks: {{ $footerTerang ? '#374151' : '#d1d5db' }};
        --footer-teks-kuat: {{ $footerTerang ? '#111827' : '#ffffff' }};
        --footer-redup: {{ $footerTerang ? '#6b7280' : '#6b7280' }};
        --footer-garis: {{ $footerTerang ? 'rgb(0 0 0 / 0.12)' : 'rgb(255 255 255 / 0.10)' }};
        --footer-aksen: {{ $footerTerang ? $gradasi[600] : $gradasi[200] }};
    }
</style>
