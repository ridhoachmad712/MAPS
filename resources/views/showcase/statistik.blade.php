@extends('layouts.publik')

@section('judul', 'Statistik Capaian')

@section('deskripsi', 'Statistik capaian mahasiswa Prodi Manajemen FEB UNM: '.$statistik['total_capaian'].' capaian terverifikasi — distribusi per kategori, level, angkatan, dan tren per tahun.')

@section('konten')
    <div class="container-xl py-4">

        <div class="page-header mb-4">
            <h1 class="page-title">Statistik Capaian</h1>
            <p class="text-secondary mb-0">
                Seluruh angka dihitung otomatis dari entri yang telah diverifikasi program studi.
            </p>
        </div>

        {{-- Baris metrik --}}
        <div class="row row-cards row-cols-2 row-cols-lg-4 mb-3">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader">Capaian terverifikasi</div>
                        <div class="h1 mb-0 mt-1">{{ $statistik['total_capaian'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader">Mahasiswa berprestasi</div>
                        <div class="h1 mb-0 mt-1">{{ $statistik['mahasiswa_berprestasi'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader">Tingkat nasional</div>
                        <div class="h1 mb-0 mt-1">{{ $statistik['nasional'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader">Tingkat internasional</div>
                        <div class="h1 mb-0 mt-1">{{ $statistik['internasional'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cards mb-3">
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="bi bi-bar-chart-fill me-2"></i>Capaian per kategori</h3></div>
                    <div class="card-body position-relative" style="height: 18rem;"><canvas id="grafikKategori"></canvas></div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="bi bi-pie-chart-fill me-2"></i>Distribusi per level</h3></div>
                    <div class="card-body position-relative" style="height: 18rem;"><canvas id="grafikLevel"></canvas></div>
                </div>
            </div>
        </div>

        <div class="row row-cards">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="bi bi-graph-up-arrow me-2"></i>Tren capaian per tahun</h3></div>
                    <div class="card-body position-relative" style="height: 16rem;"><canvas id="grafikTren"></canvas></div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="bi bi-people me-2"></i>Capaian per angkatan</h3></div>
                    <div class="card-body position-relative" style="height: 16rem;"><canvas id="grafikAngkatan"></canvas></div>
                </div>
            </div>
        </div>

        <p class="text-center text-secondary small mt-4 mb-0">
            <i class="bi bi-patch-check me-1"></i>
            Hanya entri berstatus terverifikasi yang dihitung dalam statistik resmi ini.
        </p>
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

    // Bar horizontal: nama kategori panjang tampil utuh tanpa dimiringkan
    new Chart(document.getElementById('grafikKategori'), {
        type: 'bar',
        data: {
            labels: @json($perKategori->pluck('nama_kategori')),
            datasets: [{
                data: @json($perKategori->pluck('total')),
                backgroundColor: warnaPrimer,
                borderRadius: 6,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: tooltipMaps },
            scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
        },
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
