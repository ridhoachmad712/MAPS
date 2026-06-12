@extends('layouts.publik')

@section('judul', 'Statistik Capaian')

@section('deskripsi', 'Statistik capaian mahasiswa Prodi Manajemen FEB UNM: '.$statistik['total_capaian'].' capaian terverifikasi — distribusi per kategori, level, angkatan, dan tren per tahun.')

@section('konten')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-navy-700">Statistik Capaian</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Seluruh angka dihitung otomatis dari entri yang telah diverifikasi program studi.
                </p>
            </div>
        </div>

        {{-- Baris metrik --}}
        <div class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4">
            <div class="card px-5 py-4">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Capaian terverifikasi</div>
                <div class="mt-1 text-3xl font-extrabold text-navy-700">{{ $statistik['total_capaian'] }}</div>
            </div>
            <div class="card px-5 py-4">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mahasiswa berprestasi</div>
                <div class="mt-1 text-3xl font-extrabold text-navy-700">{{ $statistik['mahasiswa_berprestasi'] }}</div>
            </div>
            <div class="card px-5 py-4">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tingkat nasional</div>
                <div class="mt-1 text-3xl font-extrabold text-navy-700">{{ $statistik['nasional'] }}</div>
            </div>
            <div class="card px-5 py-4">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tingkat internasional</div>
                <div class="mt-1 text-3xl font-extrabold text-navy-700">{{ $statistik['internasional'] }}</div>
            </div>
        </div>

        <div class="mb-4 grid gap-4 lg:grid-cols-12">
            <div class="card lg:col-span-7">
                <div class="card-header text-sm"><i class="bi bi-bar-chart-fill"></i>Capaian per kategori</div>
                <div class="px-4 py-3"><canvas id="grafikKategori" height="190"></canvas></div>
            </div>
            <div class="card lg:col-span-5">
                <div class="card-header text-sm"><i class="bi bi-pie-chart-fill"></i>Distribusi per level</div>
                <div class="flex justify-center px-4 py-3"><canvas id="grafikLevel" height="190"></canvas></div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <div class="card">
                <div class="card-header text-sm"><i class="bi bi-graph-up-arrow"></i>Tren capaian per tahun</div>
                <div class="px-4 py-3"><canvas id="grafikTren" height="170"></canvas></div>
            </div>
            <div class="card">
                <div class="card-header text-sm"><i class="bi bi-people"></i>Capaian per angkatan</div>
                <div class="px-4 py-3"><canvas id="grafikAngkatan" height="170"></canvas></div>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-slate-400">
            <i class="bi bi-patch-check"></i>
            Hanya entri berstatus terverifikasi yang dihitung dalam statistik resmi ini.
        </p>
    </div>
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
        plugins: { legend: { display: false }, tooltip: tooltipMaps },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
    };

    new Chart(document.getElementById('grafikKategori'), {
        type: 'bar',
        data: {
            labels: @json($perKategori->pluck('nama_kategori')),
            datasets: [{
                data: @json($perKategori->pluck('total')),
                backgroundColor: '#1e3a8a',
                borderRadius: 6,
            }],
        },
        options: opsiRingkas,
    });

    new Chart(document.getElementById('grafikLevel'), {
        type: 'doughnut',
        data: {
            labels: ['Regional', 'Nasional', 'Internasional'],
            datasets: [{
                data: [{{ $perLevel['regional'] ?? 0 }}, {{ $perLevel['nasional'] ?? 0 }}, {{ $perLevel['internasional'] ?? 0 }}],
                backgroundColor: ['#b9c9eb', '#5577c0', '#1e3a8a'],
            }],
        },
        options: {
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { ...tooltipMaps, callbacks: { label: (c) => ` ${c.label}: ${c.formattedValue} capaian` } },
            },
        },
    });

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
