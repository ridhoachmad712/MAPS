@php
    $gagal = in_array($portofolio->status, ['revisi', 'ditolak']);
    $posisi = ['draft' => 1, 'diajukan' => 2, 'revisi' => 2, 'ditolak' => 2, 'diverifikasi' => 3, 'dipublikasikan' => 4][$portofolio->status];
    $langkah = [1 => 'Draft', 2 => 'Diajukan', 3 => 'Diverifikasi', 4 => 'Dipublikasikan'];
@endphp

<div class="card mb-3">
    <div class="card-body">
        <ul class="steps steps-counter {{ $gagal ? 'steps-red' : '' }}">
            @foreach ($langkah as $i => $label)
                <li class="step-item {{ $i === $posisi ? 'active' : '' }}">
                    @if ($i === $posisi && $gagal)
                        <span class="text-red fw-bold">{{ $portofolio->statusLabel() }}</span>
                    @else
                        {{ $label }}
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    @if ($gagal)
        <div class="card-footer text-secondary">
            <i class="bi bi-info-circle me-1"></i>
            Entri dikembalikan oleh verifikator. Perbaiki sesuai catatan lalu ajukan ulang — status akan kembali ke <strong>Diajukan</strong>.
        </div>
    @endif
</div>
