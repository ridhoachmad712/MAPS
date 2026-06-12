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
    <section class="{{ $heroFoto ? 'relative bg-cover bg-center text-white' : 'hero-gesit' }}"
             @if ($heroFoto) style="background-image: url('{{ asset('storage/'.$heroFoto) }}')" @endif>
        @if ($heroFoto)
            <div class="absolute inset-0" style="background-color: rgb({{ $hr }} {{ $hg }} {{ $hb }} / {{ $heroOverlay }})" aria-hidden="true"></div>
        @endif
        <div class="relative mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-sm font-semibold uppercase tracking-widest text-navy-100">
                    {{ \App\Models\Setting::get('hero_eyebrow') }}
                </p>
                <h1 class="mt-3 text-3xl font-bold leading-tight sm:text-5xl">
                    {{ \App\Models\Setting::get('hero_judul') }}
                </h1>
                <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-navy-100 sm:text-lg">
                    {{ \App\Models\Setting::get('hero_deskripsi') }}
                </p>

                <form action="{{ route('showcase.capaian') }}" method="GET" class="mx-auto mt-8 flex max-w-xl rounded-xl bg-white shadow-lg">
                    <input type="search" name="q"
                           placeholder="{{ \App\Models\Setting::get('hero_placeholder') }}"
                           class="w-full rounded-l-xl border-0 bg-transparent px-5 py-3.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-0">
                    <button type="submit" class="rounded-r-xl bg-navy-800 px-6 text-sm font-semibold text-white transition hover:bg-navy-900">
                        Cari
                    </button>
                </form>

                <div class="mt-10 flex flex-wrap justify-center gap-8">
                    <div>
                        <div class="text-3xl font-bold">{{ $statistik['total_capaian'] }}</div>
                        <div class="mt-1 text-sm text-navy-100">Capaian terverifikasi</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold">{{ $statistik['mahasiswa_berprestasi'] }}</div>
                        <div class="mt-1 text-sm text-navy-100">Mahasiswa berprestasi</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold">{{ $statistik['nasional'] }}</div>
                        <div class="mt-1 text-sm text-navy-100">Tingkat nasional</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold">{{ $statistik['internasional'] }}</div>
                        <div class="mt-1 text-sm text-navy-100">Tingkat internasional</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Sorotan: capaian internasional & nasional terbaru --}}
    @if ($sorotan->isNotEmpty() && \App\Models\Setting::get('sorotan_tampil') === '1')
        <section class="mx-auto max-w-7xl px-4 pt-12 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 sm:text-2xl">{{ \App\Models\Setting::get('sorotan_judul') }}</h2>
                    <p class="mt-1 text-sm text-gray-600">{{ \App\Models\Setting::get('sorotan_sub') }}</p>
                </div>
                <a href="{{ route('showcase.capaian') }}" class="text-sm font-semibold text-navy-600 hover:underline">
                    Lihat semua capaian <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="mt-5 grid gap-4 md:grid-cols-3">
                @foreach ($sorotan as $p)
                    <a href="{{ route('showcase.mahasiswa', $p->mahasiswa) }}" class="card card-hover flex flex-col p-5">
                        <div class="mb-3 flex flex-wrap items-center gap-1.5">
                            <span class="badge badge-level-{{ $p->level }}">{{ $p->levelLabel() }}</span>
                            <span class="badge badge-soft">{{ $p->kategori->nama_kategori }}</span>
                        </div>
                        <h3 class="font-bold leading-snug text-navy-600">{{ $p->judul }}</h3>
                        <div class="mt-1.5 text-xs text-gray-500">
                            {{ $p->penyelenggara ?: 'Penyelenggara tidak dicantumkan' }} · {{ $p->tahun_pencapaian }}
                        </div>
                        @if ($p->peran_capaian)
                            <div class="mt-1.5 text-sm text-gray-700"><i class="bi bi-award text-gray-400"></i> {{ $p->peran_capaian }}</div>
                        @endif
                        <div class="mt-auto flex items-center gap-2.5 border-t border-gray-100 pt-3.5">
                            @if ($p->mahasiswa->foto)
                                <img src="{{ asset('storage/'.$p->mahasiswa->foto) }}" alt="" class="h-9 w-9 rounded-full object-cover">
                            @else
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-navy-50 text-sm font-bold text-navy-600">
                                    {{ strtoupper(substr($p->mahasiswa->nama_lengkap, 0, 1)) }}
                                </span>
                            @endif
                            <span class="min-w-0">
                                <span class="block truncate text-sm font-semibold text-gray-900">{{ $p->mahasiswa->nama_lengkap }}</span>
                                <span class="block text-xs text-gray-500">Angkatan {{ $p->mahasiswa->angkatan }}</span>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Galeri mahasiswa berprestasi --}}
    @if ($mahasiswaTop->isNotEmpty() && \App\Models\Setting::get('galeri_tampil') === '1')
        <section class="mx-auto max-w-7xl px-4 pt-12 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 sm:text-2xl">{{ \App\Models\Setting::get('galeri_judul') }}</h2>
                    <p class="mt-1 text-sm text-gray-600">{{ \App\Models\Setting::get('galeri_sub') }}</p>
                </div>
                <a href="{{ route('showcase.mahasiswa.indeks') }}" class="text-sm font-semibold text-navy-600 hover:underline">
                    Lihat semua mahasiswa <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($mahasiswaTop as $m)
                    <a href="{{ route('showcase.mahasiswa', $m) }}" class="card card-hover p-5 text-center">
                        @if ($m->foto)
                            <img src="{{ asset('storage/'.$m->foto) }}" alt="Foto {{ $m->nama_lengkap }}"
                                 class="mx-auto h-16 w-16 rounded-full object-cover">
                        @else
                            <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-navy-50 text-xl font-bold text-navy-600">
                                {{ strtoupper(substr($m->nama_lengkap, 0, 1)) }}
                            </span>
                        @endif
                        <div class="mt-3 truncate text-sm font-semibold text-gray-900" title="{{ $m->nama_lengkap }}">{{ $m->nama_lengkap }}</div>
                        <div class="text-xs text-gray-500">Angkatan {{ $m->angkatan }}</div>
                        <span class="badge badge-primary mt-2.5">{{ $m->total_publik }} capaian</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Grafik ringkas --}}
    @if (\App\Models\Setting::get('grafik_tampil') === '1')
    <section class="mx-auto max-w-7xl px-4 pt-12 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold text-gray-900 sm:text-2xl">{{ \App\Models\Setting::get('grafik_judul') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ \App\Models\Setting::get('grafik_sub') }}</p>
            </div>
            <a href="{{ route('showcase.statistik') }}" class="text-sm font-semibold text-navy-600 hover:underline">
                Statistik lengkap <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <div class="card">
                <div class="card-header text-sm"><i class="bi bi-graph-up-arrow"></i>Tren capaian per tahun</div>
                <div class="relative h-56 px-4 py-3"><canvas id="grafikTren"></canvas></div>
            </div>
            <div class="card">
                <div class="card-header text-sm"><i class="bi bi-people"></i>Capaian per angkatan</div>
                <div class="relative h-56 px-4 py-3"><canvas id="grafikAngkatan"></canvas></div>
            </div>
        </div>
    </section>
    @endif

    {{-- Ajakan menjelajah data --}}
    @if (\App\Models\Setting::get('cta_tampil') === '1')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="card flex flex-col items-center gap-4 bg-navy-50 p-8 text-center sm:flex-row sm:justify-between sm:text-left">
            <div>
                <h2 class="text-lg font-bold text-navy-700">{{ \App\Models\Setting::get('cta_judul') }}</h2>
                <p class="mt-1 text-sm text-navy-600">{{ \App\Models\Setting::get('cta_deskripsi') }}</p>
            </div>
            <a href="{{ route('showcase.capaian') }}" class="btn btn-maps shrink-0">
                <i class="bi bi-table"></i>{{ \App\Models\Setting::get('cta_tombol') }}
            </a>
        </div>
    </section>
    @else
    <div class="pb-12"></div>
    @endif
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
    Chart.defaults.color = '#64748b';

    const tooltipMaps = {
        backgroundColor: '#172c68',
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
                borderColor: '#1e3a8a',
                backgroundColor: 'rgba(30,58,138,.1)',
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
            labels: @json($perAngkatan->keys()->map(fn ($a) => 'Angkatan '.$a)),
            datasets: [{
                data: @json($perAngkatan->values()),
                backgroundColor: '#5577c0',
                borderRadius: 5,
            }],
        },
        options: opsiRingkas,
    });
</script>
@endpush
