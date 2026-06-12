@extends('layouts.app')

@section('judul', 'Dashboard Statistik')

@section('konten')
    <div class="page-header mb-4">
        <div class="row align-items-center g-2">
            <div class="col">
                <h1 class="page-title">Dashboard Statistik</h1>
                <p class="text-secondary mb-0">Seluruh angka dihitung otomatis dari data portofolio terverifikasi.</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.verifikasi.index') }}" class="btn btn-primary">
                    <i class="bi bi-patch-check me-1"></i>Antrian Verifikasi
                    @if ($antrianVerifikasi > 0)
                        <span class="badge bg-white text-blue ms-2">{{ $antrianVerifikasi }}</span>
                    @endif
                </a>
            </div>
        </div>
    </div>

    <div class="row row-cards row-cols-2 row-cols-xl-4 mb-3">
        <div class="col">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="subheader">Mahasiswa Berprestasi</div>
                        <div class="h1 mb-0">{{ $totalMahasiswaAktif }}</div>
                        <div class="text-secondary small">punya ≥1 capaian terverifikasi</div>
                    </div>
                    <i class="bi bi-people-fill fs-1 text-secondary opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="subheader">Capaian Terverifikasi</div>
                        <div class="h1 mb-0">{{ $totalTerverifikasi }}</div>
                        <div class="text-secondary small">dari {{ $totalEntri }} total entri</div>
                    </div>
                    <i class="bi bi-patch-check-fill fs-1 text-secondary opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="subheader">Menunggu Verifikasi</div>
                        <div class="h1 mb-0">{{ $antrianVerifikasi }}</div>
                        <div class="text-secondary small">entri berstatus diajukan</div>
                    </div>
                    <i class="bi bi-hourglass-split fs-1 text-secondary opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="subheader">Tingkat Internasional</div>
                        <div class="h1 mb-0">{{ $perLevel['internasional'] ?? 0 }}</div>
                        <div class="text-secondary small">capaian terverifikasi</div>
                    </div>
                    <i class="bi bi-globe-americas fs-1 text-secondary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cards mb-3">
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-bar-chart-fill me-2"></i>Capaian Terverifikasi per Kategori</h3></div>
                <div class="card-body position-relative" style="height: 16rem;"><canvas id="grafikKategori"></canvas></div>
            </div>
        </div>
        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-pie-chart-fill me-2"></i>Distribusi per Level</h3></div>
                <div class="card-body position-relative" style="height: 16rem;"><canvas id="grafikLevel"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row row-cards mb-3">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-people me-2"></i>Distribusi per Angkatan</h3></div>
                <div class="card-body position-relative" style="height: 16rem;"><canvas id="grafikAngkatan"></canvas></div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-graph-up-arrow me-2"></i>Tren per Tahun Pencapaian</h3></div>
                <div class="card-body position-relative" style="height: 16rem;"><canvas id="grafikTren"></canvas></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title"><i class="bi bi-trophy me-2"></i>10 Mahasiswa dengan Capaian Terverifikasi Terbanyak</h3></div>
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Angkatan</th>
                        <th class="text-end">Capaian Terverifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topMahasiswa as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $m->nama_lengkap }}</td>
                            <td>{{ $m->nim }}</td>
                            <td>{{ $m->angkatan }}</td>
                            <td class="text-end"><span class="badge bg-success-lt">{{ $m->total_terverifikasi }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-5">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const gayaAkar = getComputedStyle(document.documentElement);
    const warnaPrimer = gayaAkar.getPropertyValue('--tblr-primary').trim() || '#066fd1';
    const warnaPrimerMuda = gayaAkar.getPropertyValue('--primer-400').trim() || warnaPrimer;
    const warnaPrimerPucat = gayaAkar.getPropertyValue('--primer-200').trim() || warnaPrimer;
    const warnaPrimerRgb = gayaAkar.getPropertyValue('--tblr-primary-rgb').trim() || '6, 111, 209';

    // Gaya grafik seragam + tooltip berbahasa Indonesia
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

    new Chart(document.getElementById('grafikKategori'), {
        type: 'bar',
        data: {
            labels: @json($perKategori->pluck('nama_kategori')),
            datasets: [{
                label: 'Capaian terverifikasi',
                data: @json($perKategori->pluck('total')),
                backgroundColor: warnaPrimer,
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
                backgroundColor: [warnaPrimerPucat, warnaPrimerMuda, warnaPrimer],
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { ...tooltipMaps, callbacks: { label: (c) => ` ${c.label}: ${c.formattedValue} capaian` } },
            },
        },
    });

    new Chart(document.getElementById('grafikAngkatan'), {
        type: 'bar',
        data: {
            labels: @json($perAngkatan->keys()->map(fn ($a) => 'Angkatan '.$a)),
            datasets: [{
                label: 'Capaian terverifikasi',
                data: @json($perAngkatan->values()),
                backgroundColor: warnaPrimerMuda,
                borderRadius: 6,
            }],
        },
        options: opsiRingkas,
    });

    new Chart(document.getElementById('grafikTren'), {
        type: 'line',
        data: {
            labels: @json($trenTahun->keys()),
            datasets: [{
                label: 'Capaian terverifikasi',
                data: @json($trenTahun->values()),
                borderColor: warnaPrimer,
                backgroundColor: `rgba(${warnaPrimerRgb}, .12)`,
                fill: true,
                tension: .3,
            }],
        },
        options: opsiRingkas,
    });
});
</script>
@endpush
