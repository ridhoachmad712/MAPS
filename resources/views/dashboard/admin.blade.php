@extends('layouts.app')

@section('judul', 'Dashboard Statistik')

@section('konten')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-extrabold text-navy-700">Dashboard Statistik</h1>
            <p class="mt-0.5 text-sm text-slate-500">Seluruh angka dihitung otomatis dari data portofolio terverifikasi.</p>
        </div>
        <a href="{{ route('admin.verifikasi.index') }}" class="btn btn-maps">
            <i class="bi bi-patch-check"></i>Antrian Verifikasi
            @if ($antrianVerifikasi > 0)
                <span class="ml-1 rounded-full bg-white px-2 py-0.5 text-xs font-bold text-navy-700">{{ $antrianVerifikasi }}</span>
            @endif
        </a>
    </div>

    <div class="mb-6 grid grid-cols-2 gap-3 xl:grid-cols-4">
        <div class="card">
            <div class="flex items-center justify-between px-5 py-4">
                <div>
                    <div class="text-xs text-slate-500">Mahasiswa Berprestasi</div>
                    <div class="text-3xl font-extrabold text-navy-700">{{ $totalMahasiswaAktif }}</div>
                    <div class="text-xs text-slate-400">punya ≥1 capaian terverifikasi</div>
                </div>
                <i class="bi bi-people-fill text-3xl text-slate-300"></i>
            </div>
        </div>
        <div class="card">
            <div class="flex items-center justify-between px-5 py-4">
                <div>
                    <div class="text-xs text-slate-500">Capaian Terverifikasi</div>
                    <div class="text-3xl font-extrabold text-navy-700">{{ $totalTerverifikasi }}</div>
                    <div class="text-xs text-slate-400">dari {{ $totalEntri }} total entri</div>
                </div>
                <i class="bi bi-patch-check-fill text-3xl text-slate-300"></i>
            </div>
        </div>
        <div class="card">
            <div class="flex items-center justify-between px-5 py-4">
                <div>
                    <div class="text-xs text-slate-500">Menunggu Verifikasi</div>
                    <div class="text-3xl font-extrabold text-navy-700">{{ $antrianVerifikasi }}</div>
                    <div class="text-xs text-slate-400">entri berstatus diajukan</div>
                </div>
                <i class="bi bi-hourglass-split text-3xl text-slate-300"></i>
            </div>
        </div>
        <div class="card">
            <div class="flex items-center justify-between px-5 py-4">
                <div>
                    <div class="text-xs text-slate-500">Tingkat Internasional</div>
                    <div class="text-3xl font-extrabold text-navy-700">{{ $perLevel['internasional'] ?? 0 }}</div>
                    <div class="text-xs text-slate-400">capaian terverifikasi</div>
                </div>
                <i class="bi bi-globe-americas text-3xl text-slate-300"></i>
            </div>
        </div>
    </div>

    <div class="mb-4 grid gap-4 lg:grid-cols-12">
        <div class="card lg:col-span-7">
            <div class="card-header"><i class="bi bi-bar-chart-fill"></i>Capaian Terverifikasi per Kategori</div>
            <div class="card-body"><canvas id="grafikKategori" height="220"></canvas></div>
        </div>
        <div class="card lg:col-span-5">
            <div class="card-header"><i class="bi bi-pie-chart-fill"></i>Distribusi per Level</div>
            <div class="card-body flex justify-center"><canvas id="grafikLevel" height="220"></canvas></div>
        </div>
    </div>

    <div class="mb-6 grid gap-4 lg:grid-cols-2">
        <div class="card">
            <div class="card-header"><i class="bi bi-people"></i>Distribusi per Angkatan</div>
            <div class="card-body"><canvas id="grafikAngkatan" height="220"></canvas></div>
        </div>
        <div class="card">
            <div class="card-header"><i class="bi bi-graph-up-arrow"></i>Tren per Tahun Pencapaian</div>
            <div class="card-body"><canvas id="grafikTren" height="220"></canvas></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><i class="bi bi-trophy"></i>10 Mahasiswa dengan Capaian Terverifikasi Terbanyak</div>
        <div class="overflow-x-auto">
            <table class="table-maps">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Angkatan</th>
                        <th class="text-right">Capaian Terverifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topMahasiswa as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="font-semibold">{{ $m->nama_lengkap }}</td>
                            <td>{{ $m->nim }}</td>
                            <td>{{ $m->angkatan }}</td>
                            <td class="text-right"><span class="badge badge-success">{{ $m->total_terverifikasi }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-10 text-center text-slate-400">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    const warnaNavy = '#1e3a8a', warnaNavyMuda = '#5577c0';

    // Gaya grafik seragam + tooltip berbahasa Indonesia
    Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
    Chart.defaults.color = '#64748b';

    const tooltipMaps = {
        backgroundColor: warnaNavy,
        padding: 10,
        cornerRadius: 8,
        displayColors: false,
        callbacks: { label: (c) => ` ${c.formattedValue} capaian terverifikasi` },
    };

    new Chart(document.getElementById('grafikKategori'), {
        type: 'bar',
        data: {
            labels: @json($perKategori->pluck('nama_kategori')),
            datasets: [{
                label: 'Capaian terverifikasi',
                data: @json($perKategori->pluck('total')),
                backgroundColor: warnaNavy,
                borderRadius: 6,
            }],
        },
        options: { plugins: { legend: { display: false }, tooltip: tooltipMaps }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } },
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
        options: { plugins: { legend: { position: 'bottom' }, tooltip: { ...tooltipMaps, callbacks: { label: (c) => ` ${c.label}: ${c.formattedValue} capaian` } } } },
    });

    new Chart(document.getElementById('grafikAngkatan'), {
        type: 'bar',
        data: {
            labels: @json($perAngkatan->keys()->map(fn ($a) => 'Angkatan '.$a)),
            datasets: [{
                label: 'Capaian terverifikasi',
                data: @json($perAngkatan->values()),
                backgroundColor: warnaNavyMuda,
                borderRadius: 6,
            }],
        },
        options: { plugins: { legend: { display: false }, tooltip: tooltipMaps }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } },
    });

    new Chart(document.getElementById('grafikTren'), {
        type: 'line',
        data: {
            labels: @json($trenTahun->keys()),
            datasets: [{
                label: 'Capaian terverifikasi',
                data: @json($trenTahun->values()),
                borderColor: warnaNavy,
                backgroundColor: 'rgba(30,58,138,.12)',
                fill: true,
                tension: .3,
            }],
        },
        options: { plugins: { legend: { display: false }, tooltip: tooltipMaps }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } },
    });
</script>
@endpush
