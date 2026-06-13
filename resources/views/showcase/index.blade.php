@extends('layouts.publik')

@section('judul', 'Data Capaian Mahasiswa')

@section('deskripsi', $statistik['total_capaian'].' capaian terverifikasi dari '.$statistik['mahasiswa_berprestasi'].' mahasiswa berprestasi Prodi Manajemen FEB UNM — prestasi, PKM, organisasi, MBKM, sertifikasi, dan publikasi.')

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'MAPS — Data Capaian Mahasiswa Prodi Manajemen FEB UNM',
            'url' => route('showcase.index'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('showcase.capaian').'?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('konten')
    @php
        $heroFoto = \App\Models\Setting::get('hero_foto');
        [$hr, $hg, $hb] = \App\Support\PaletWarna::hexKeRgb(\App\Models\Setting::get('warna_hero'));
        $heroOverlay = max(0, min(95, (int) \App\Models\Setting::get('hero_overlay'))) / 100;
    @endphp

    {{-- Hero: gradasi warna tema, atau foto + overlay bila diatur dari admin --}}
    <section class="{{ $heroFoto ? 'position-relative text-white' : 'hero-maps' }}"
             @if ($heroFoto) style="background-image: url('{{ asset('storage/'.$heroFoto) }}'); background-size: cover; background-position: center;" @endif>
        @if ($heroFoto)
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgb({{ $hr }} {{ $hg }} {{ $hb }} / {{ $heroOverlay }})" aria-hidden="true"></div>
        @endif
        <div class="container-xl position-relative py-5 py-md-6">
            <div class="row justify-content-center text-center py-4">
                <div class="col-12 col-lg-8">
                    <p class="text-uppercase fw-semibold tracking-wide mb-2 opacity-75">
                        {{ \App\Models\Setting::get('hero_eyebrow') }}
                    </p>
                    <h1 class="display-5 fw-bold">
                        {{ \App\Models\Setting::get('hero_judul') }}
                    </h1>
                    <p class="fs-3 opacity-75 mx-auto mt-3" style="max-width: 40rem;">
                        {{ \App\Models\Setting::get('hero_deskripsi') }}
                    </p>

                    <form action="{{ route('showcase.capaian') }}" method="GET" class="mx-auto mt-4" style="max-width: 36rem;" data-bs-theme="light">
                        <div class="input-group input-group-lg shadow">
                            <input type="search" name="q" class="form-control border-0"
                                   placeholder="{{ \App\Models\Setting::get('hero_placeholder') }}">
                            <button type="submit" class="btn btn-primary px-4">Cari</button>
                        </div>
                    </form>

                    <div class="d-flex flex-wrap justify-content-center gap-5 mt-5">
                        <div>
                            <div class="h1 mb-0">{{ $statistik['total_capaian'] }}</div>
                            <div class="opacity-75">Capaian terverifikasi</div>
                        </div>
                        <div>
                            <div class="h1 mb-0">{{ $statistik['mahasiswa_berprestasi'] }}</div>
                            <div class="opacity-75">Mahasiswa berprestasi</div>
                        </div>
                        <div>
                            <div class="h1 mb-0">{{ $statistik['nasional'] }}</div>
                            <div class="opacity-75">Tingkat nasional</div>
                        </div>
                        <div>
                            <div class="h1 mb-0">{{ $statistik['internasional'] }}</div>
                            <div class="opacity-75">Tingkat internasional</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Sorotan: capaian internasional & nasional terbaru --}}
    @if ($sorotan->isNotEmpty() && \App\Models\Setting::get('sorotan_tampil') === '1')
        <section class="container-xl pt-5">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-3">
                <div>
                    <h2 class="h1 mb-1">{{ \App\Models\Setting::get('sorotan_judul') }}</h2>
                    <p class="text-secondary mb-0">{{ \App\Models\Setting::get('sorotan_sub') }}</p>
                </div>
                <a href="{{ route('showcase.capaian') }}" class="fw-semibold">
                    Lihat semua capaian <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="row row-cards">
                @foreach ($sorotan as $p)
                    <div class="col-12 col-md-4 d-flex">
                        <a href="{{ route('showcase.mahasiswa', $p->mahasiswa) }}" class="card card-link w-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex flex-wrap gap-1 mb-2">
                                    <span class="badge bg-{{ $p->levelBadge() }}-lt">{{ $p->levelLabel() }}</span>
                                    <span class="badge bg-secondary-lt">{{ $p->kategori->nama_kategori }}</span>
                                </div>
                                <h3 class="card-title mb-1">{{ $p->judul }}</h3>
                                <div class="text-secondary small">
                                    {{ $p->penyelenggara ?: 'Penyelenggara tidak dicantumkan' }} · {{ $p->tahun_pencapaian }}
                                </div>
                                @if ($p->peran_capaian)
                                    <div class="mt-1"><i class="bi bi-award text-secondary me-1"></i>{{ $p->peran_capaian }}</div>
                                @endif
                                <div class="d-flex align-items-center gap-2 border-top pt-3 mt-auto">
                                    @if ($p->mahasiswa->foto)
                                        <span class="avatar avatar-sm rounded-circle" style="background-image: url('{{ asset('storage/'.$p->mahasiswa->foto) }}')"></span>
                                    @else
                                        <span class="avatar avatar-sm rounded-circle">{{ strtoupper(substr($p->mahasiswa->nama_lengkap, 0, 1)) }}</span>
                                    @endif
                                    <span class="min-width-0">
                                        <span class="d-block text-truncate fw-semibold text-body">{{ $p->mahasiswa->nama_lengkap }}</span>
                                        <span class="d-block text-secondary small">Angkatan {{ $p->mahasiswa->angkatan }}</span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Galeri mahasiswa berprestasi --}}
    @if ($mahasiswaTop->isNotEmpty() && \App\Models\Setting::get('galeri_tampil') === '1')
        <section class="container-xl pt-5">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-3">
                <div>
                    <h2 class="h1 mb-1">{{ \App\Models\Setting::get('galeri_judul') }}</h2>
                    <p class="text-secondary mb-0">{{ \App\Models\Setting::get('galeri_sub') }}</p>
                </div>
                <a href="{{ route('showcase.mahasiswa.indeks') }}" class="fw-semibold">
                    Lihat semua mahasiswa <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="row row-cards row-cols-2 row-cols-sm-3 row-cols-lg-6">
                @foreach ($mahasiswaTop as $m)
                    <div class="col d-flex">
                        <a href="{{ route('showcase.mahasiswa', $m) }}" class="card card-link w-100 text-center">
                            <div class="card-body">
                                @if ($m->foto)
                                    <span class="avatar avatar-lg rounded-circle mx-auto" style="background-image: url('{{ asset('storage/'.$m->foto) }}')"></span>
                                @else
                                    <span class="avatar avatar-lg rounded-circle mx-auto">{{ strtoupper(substr($m->nama_lengkap, 0, 1)) }}</span>
                                @endif
                                <div class="fw-semibold text-body text-truncate mt-3" title="{{ $m->nama_lengkap }}">{{ $m->nama_lengkap }}</div>
                                <div class="text-secondary small">Angkatan {{ $m->angkatan }}</div>
                                <span class="badge bg-blue-lt mt-2">{{ $m->total_publik }} capaian</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Grafik ringkas --}}
    @if (\App\Models\Setting::get('grafik_tampil') === '1')
    <section class="container-xl pt-5">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-3">
            <div>
                <h2 class="h1 mb-1">{{ \App\Models\Setting::get('grafik_judul') }}</h2>
                <p class="text-secondary mb-0">{{ \App\Models\Setting::get('grafik_sub') }}</p>
            </div>
            <a href="{{ route('showcase.statistik') }}" class="fw-semibold">
                Statistik lengkap <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row row-cards">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="bi bi-graph-up-arrow me-2"></i>Tren capaian per tahun</h3></div>
                    <div class="card-body position-relative" style="height: 14rem;"><canvas id="grafikTren"></canvas></div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="bi bi-people me-2"></i>Capaian per angkatan</h3></div>
                    <div class="card-body position-relative" style="height: 14rem;"><canvas id="grafikAngkatan"></canvas></div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Ajakan menjelajah data --}}
    @if (\App\Models\Setting::get('cta_tampil') === '1')
    <section class="container-xl py-5">
        <div class="card card-active">
            <div class="card-body d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 text-center text-sm-start">
                <div>
                    <h2 class="h2 mb-1">{{ \App\Models\Setting::get('cta_judul') }}</h2>
                    <p class="text-secondary mb-0">{{ \App\Models\Setting::get('cta_deskripsi') }}</p>
                </div>
                <a href="{{ route('showcase.capaian') }}" class="btn btn-primary flex-shrink-0">
                    <i class="bi bi-table me-1"></i>{{ \App\Models\Setting::get('cta_tombol') }}
                </a>
            </div>
        </div>
    </section>
    @else
    <div class="pb-5"></div>
    @endif
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const gayaAkar = getComputedStyle(document.documentElement);
    const warnaPrimer = gayaAkar.getPropertyValue('--tblr-primary').trim() || '#066fd1';
    const warnaPrimerMuda = gayaAkar.getPropertyValue('--primer-400').trim() || warnaPrimer;
    const warnaPrimerRgb = gayaAkar.getPropertyValue('--tblr-primary-rgb').trim() || '6, 111, 209';

    Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
    Chart.defaults.color = '#667382';

    const tooltipMaps = {
        backgroundColor: warnaPrimer,
        padding: 10,
        cornerRadius: 8,
        displayColors: false,
        callbacks: { label: (c) => ` ${c.formattedValue} capaian terverifikasi` },
    };

    const opsiRingkas = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: tooltipMaps },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
    };

    new Chart(document.getElementById('grafikTren'), {
        type: 'line',
        data: {
            labels: @json($trenTahun->keys()),
            datasets: [{
                data: @json($trenTahun->values()),
                borderColor: warnaPrimer,
                backgroundColor: `rgba(${warnaPrimerRgb}, .1)`,
                fill: true,
                tension: .3,
                pointRadius: 3,
            }],
        },
        options: opsiRingkas,
    });

    new Chart(document.getElementById('grafikAngkatan'), {
        type: 'bar',
        data: {
            labels: @json($perAngkatan->keys()->map(fn ($a) => (string) $a)),
            datasets: [{
                data: @json($perAngkatan->values()),
                backgroundColor: warnaPrimerMuda,
                borderRadius: 5,
            }],
        },
        options: opsiRingkas,
    });
});
</script>
@endpush
