@extends('layouts.publik')

@section('judul', 'Data Capaian Mahasiswa')

@php
    $filterAktif = collect([
        'q' => request('q') ? 'Cari: "'.request('q').'"' : null,
        'kategori' => request('kategori')
            ? 'Kategori: '.optional($kategori->firstWhere('kategori_id', (int) request('kategori')))->nama_kategori
            : null,
        'level' => request('level') ? 'Level: '.(\App\Models\Portofolio::LEVEL_LABEL[request('level')] ?? request('level')) : null,
        'tahun' => request('tahun') ? 'Tahun: '.request('tahun') : null,
        'angkatan' => request('angkatan') ? 'Angkatan: '.request('angkatan') : null,
    ])->filter();

    $urut = request('urut');
@endphp

@section('konten')
    {{-- Hero gradasi ala GESIT --}}
    <section class="hero-gesit">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-sm font-semibold uppercase tracking-widest text-navy-100">
                    Prodi Manajemen · FEB · Universitas Negeri Makassar
                </p>
                <h1 class="mt-3 text-3xl font-bold leading-tight sm:text-5xl">
                    Data Capaian Mahasiswa
                </h1>
                <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-navy-100 sm:text-lg">
                    Arsip prestasi, PKM, organisasi, MBKM, sertifikasi, dan publikasi mahasiswa
                    yang telah diverifikasi program studi.
                </p>

                <form action="{{ route('showcase.index') }}" method="GET" class="mx-auto mt-8 flex max-w-xl rounded-xl bg-white shadow-lg">
                    <input type="search" name="q" value="{{ request('q') }}"
                           placeholder="Cari judul capaian, penyelenggara, atau nama mahasiswa..."
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

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-5 lg:grid-cols-12">

            {{-- Panel filter kiri (sticky, dapat dilipat di layar sempit) --}}
            <aside class="lg:col-span-3">
                <form method="GET" id="formFilter" class="card lg:sticky lg:top-4"
                      x-data="{ buka: true }"
                      x-init="buka = window.matchMedia('(min-width: 1024px)').matches">
                    <button type="button" class="card-header w-full justify-between text-left" @click="buka = !buka">
                        <span><i class="bi bi-funnel"></i> Filter</span>
                        <span class="flex items-center gap-3">
                            @if ($filterAktif->isNotEmpty())
                                <span class="badge badge-primary">{{ $filterAktif->count() }}</span>
                            @endif
                            <i class="bi bi-chevron-down text-sm text-slate-400 transition-transform lg:hidden" :class="buka ? 'rotate-180' : ''"></i>
                        </span>
                    </button>

                    <div x-show="buka" x-cloak>
                        <div class="space-y-5 px-5 py-4">
                            <div>
                                <label class="form-label text-xs uppercase tracking-wider text-slate-400">Pencarian</label>
                                <div class="flex gap-1.5">
                                    <input type="text" name="q" class="form-control form-control-sm" value="{{ request('q') }}"
                                           placeholder="Judul, penyelenggara, nama...">
                                    <button class="btn btn-sm btn-maps" aria-label="Cari"><i class="bi bi-search"></i></button>
                                </div>
                            </div>

                            <div>
                                <span class="form-label text-xs uppercase tracking-wider text-slate-400">Kategori</span>
                                <div class="space-y-1">
                                    <label class="flex cursor-pointer items-center justify-between rounded-lg px-2 py-1.5 text-sm hover:bg-slate-50">
                                        <span class="flex items-center gap-2">
                                            <input type="radio" name="kategori" value="" class="form-check-input" @checked(!request()->filled('kategori'))>
                                            Semua kategori
                                        </span>
                                    </label>
                                    @foreach ($kategori as $k)
                                        <label class="flex cursor-pointer items-center justify-between rounded-lg px-2 py-1.5 text-sm hover:bg-slate-50">
                                            <span class="flex items-center gap-2">
                                                <input type="radio" name="kategori" value="{{ $k->kategori_id }}" class="form-check-input"
                                                       @checked(request('kategori') == $k->kategori_id)>
                                                {{ $k->nama_kategori }}
                                            </span>
                                            <span class="badge badge-soft">{{ $k->total_publik }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <span class="form-label text-xs uppercase tracking-wider text-slate-400">Level</span>
                                <div class="space-y-1">
                                    <label class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-1.5 text-sm hover:bg-slate-50">
                                        <input type="radio" name="level" value="" class="form-check-input" @checked(!request()->filled('level'))>
                                        Semua level
                                    </label>
                                    @foreach (\App\Models\Portofolio::LEVEL_LABEL as $nilai => $label)
                                        <label class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-1.5 text-sm hover:bg-slate-50">
                                            <input type="radio" name="level" value="{{ $nilai }}" class="form-check-input" @checked(request('level') === $nilai)>
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="form-label text-xs uppercase tracking-wider text-slate-400">Tahun</label>
                                    <select name="tahun" class="form-select form-select-sm">
                                        <option value="">Semua</option>
                                        @foreach ($daftarTahun as $t)
                                            <option value="{{ $t }}" @selected(request('tahun') == $t)>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label text-xs uppercase tracking-wider text-slate-400">Angkatan</label>
                                    <select name="angkatan" class="form-select form-select-sm">
                                        <option value="">Semua</option>
                                        @foreach ($daftarAngkatan as $a)
                                            <option value="{{ $a }}" @selected(request('angkatan') == $a)>{{ $a }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </aside>

            {{-- Konten utama --}}
            <div class="space-y-5 lg:col-span-9">

                <div class="grid gap-4 xl:grid-cols-2">
                    <div class="card">
                        <div class="card-header text-sm"><i class="bi bi-graph-up-arrow"></i>Tren capaian per tahun</div>
                        <div class="px-4 py-3"><canvas id="grafikTren" height="150"></canvas></div>
                    </div>
                    <div class="card">
                        <div class="card-header text-sm"><i class="bi bi-people"></i>Capaian per angkatan</div>
                        <div class="px-4 py-3"><canvas id="grafikAngkatan" height="150"></canvas></div>
                    </div>
                </div>

                {{-- Chip filter aktif --}}
                @if ($filterAktif->isNotEmpty())
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Filter aktif:</span>
                        @foreach ($filterAktif as $kunci => $label)
                            <a href="{{ route('showcase.index', collect(request()->except([$kunci, 'page']))->all()) }}"
                               class="badge badge-primary hover:bg-navy-100" title="Lepas filter ini">
                                {{ $label }} <i class="bi bi-x"></i>
                            </a>
                        @endforeach
                        <a href="{{ route('showcase.index') }}" class="text-xs font-semibold text-slate-500 hover:text-navy-700 hover:underline">
                            Hapus semua
                        </a>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header justify-between text-sm">
                        <span><i class="bi bi-list-check"></i>Daftar capaian terverifikasi</span>
                        <span class="text-xs font-normal text-slate-400">{{ $entri->total() }} entri</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table-maps">
                            <thead>
                                <tr>
                                    <th>Capaian</th>
                                    <th>Mahasiswa</th>
                                    <th>Kategori</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['urut' => $urut === 'level_turun' ? 'level_naik' : 'level_turun', 'page' => null]) }}"
                                           class="inline-flex items-center gap-1 hover:text-navy-700">
                                            Level
                                            @if ($urut === 'level_turun') <i class="bi bi-arrow-down"></i>
                                            @elseif ($urut === 'level_naik') <i class="bi bi-arrow-up"></i>
                                            @else <i class="bi bi-arrow-down-up opacity-40"></i> @endif
                                        </a>
                                    </th>
                                    <th class="text-right">
                                        <a href="{{ request()->fullUrlWithQuery(['urut' => $urut === 'tahun_turun' ? 'tahun_naik' : 'tahun_turun', 'page' => null]) }}"
                                           class="inline-flex items-center gap-1 hover:text-navy-700">
                                            Tahun
                                            @if ($urut === 'tahun_turun') <i class="bi bi-arrow-down"></i>
                                            @elseif ($urut === 'tahun_naik') <i class="bi bi-arrow-up"></i>
                                            @else <i class="bi bi-arrow-down-up opacity-40"></i> @endif
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($entri as $p)
                                    <tr class="cursor-pointer" onclick="window.location='{{ route('showcase.mahasiswa', $p->mahasiswa) }}'">
                                        <td>
                                            <div class="font-semibold text-navy-700">{{ $p->judul }}</div>
                                            <div class="text-xs text-slate-500">
                                                {{ $p->penyelenggara ?: 'Penyelenggara tidak dicantumkan' }}
                                                @if ($p->peran_capaian) · {{ $p->peran_capaian }} @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="font-medium text-navy-700">{{ $p->mahasiswa->nama_lengkap }}</span>
                                            <div class="text-xs text-slate-500">{{ $p->mahasiswa->nimSamar() }} · {{ $p->mahasiswa->angkatan }}</div>
                                        </td>
                                        <td><span class="badge badge-soft">{{ $p->kategori->kode }}</span></td>
                                        <td><span class="badge badge-level-{{ $p->level }}">{{ $p->levelLabel() }}</span></td>
                                        <td class="text-right font-medium">{{ $p->tahun_pencapaian }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-slate-400">
                                            <i class="bi bi-search mb-2 block text-3xl"></i>
                                            Tidak ada capaian yang cocok dengan filter Anda.
                                            @if ($filterAktif->isNotEmpty())
                                                <div class="mt-3">
                                                    <a href="{{ route('showcase.index') }}" class="btn btn-sm btn-outline">Hapus semua filter</a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($entri->hasPages())
                        <div class="border-t border-slate-100 px-5 py-3">{{ $entri->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    // Filter langsung diterapkan saat pilihan berubah
    const formFilter = document.getElementById('formFilter');
    formFilter.addEventListener('change', (e) => {
        if (e.target.matches('input[type="radio"], select')) formFilter.submit();
    });

    // Gaya grafik seragam + tooltip berbahasa Indonesia
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
