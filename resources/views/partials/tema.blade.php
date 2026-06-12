{{-- Variabel warna tema dari Pengaturan Tampilan (admin) — berlaku tanpa build ulang --}}
@php
    use App\Models\Setting;
    use App\Support\PaletWarna;

    $gradasi = PaletWarna::gradasi(Setting::get('warna_primer'));
    $hero = PaletWarna::gradasi(Setting::get('warna_hero'));
    [$pr, $pg, $pb] = PaletWarna::hexKeRgb(Setting::get('warna_primer'));

    $logo = Setting::get('logo');

    if ($logo === '') {
        $inisial = mb_strtoupper(mb_substr(Setting::get('nama_aplikasi') ?: 'M', 0, 1));
        $faviconSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">'
            .sprintf('<rect width="64" height="64" rx="14" fill="#%02x%02x%02x"/>', $pr, $pg, $pb)
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
        --tblr-primary: {{ $gradasi[500] }};
        --tblr-primary-rgb: {{ $pr }}, {{ $pg }}, {{ $pb }};
        --tblr-link-color: {{ $gradasi[500] }};
        --tblr-link-color-rgb: {{ $pr }}, {{ $pg }}, {{ $pb }};
        --tblr-link-hover-color: {{ $gradasi[600] }};

        @foreach ($gradasi as $stop => $warna)
            --primer-{{ $stop }}: {{ $warna }};
        @endforeach

        --hero-1: {{ $hero[500] }};
        --hero-2: {{ $hero[600] }};
        --hero-3: {{ $hero[700] }};

        --warna-navbar: {{ Setting::get('warna_navbar') }};
        --warna-footer: {{ Setting::get('warna_footer') }};
    }
</style>
