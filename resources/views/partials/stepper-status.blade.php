@php
    $gagal = in_array($portofolio->status, ['revisi', 'ditolak']);
    $posisi = ['draft' => 1, 'diajukan' => 2, 'revisi' => 2, 'ditolak' => 2, 'diverifikasi' => 3, 'dipublikasikan' => 4][$portofolio->status];
    $langkah = [1 => 'Draft', 2 => 'Diajukan', 3 => 'Diverifikasi', 4 => 'Dipublikasikan'];
@endphp

<div class="card mb-4">
    <div class="flex items-start px-4 py-4 sm:px-6">
        @foreach ($langkah as $i => $label)
            @php
                $selesai = $i < $posisi || ($i === $posisi && ! $gagal && in_array($portofolio->status, ['diverifikasi', 'dipublikasikan']));
                $sekarang = $i === $posisi && ! $selesai;
            @endphp

            @if ($i > 1)
                <div class="mt-4 h-0.5 flex-1 {{ $i <= $posisi ? 'bg-navy-700' : 'bg-slate-200' }}"></div>
            @endif

            <div class="flex w-16 flex-col items-center gap-1.5 sm:w-24">
                @if ($sekarang && $gagal)
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-red-600 text-white">
                        <i class="bi {{ $portofolio->status === 'ditolak' ? 'bi-x-lg' : 'bi-arrow-counterclockwise' }} text-sm"></i>
                    </span>
                    <span class="text-center text-xs font-bold text-red-600">{{ $portofolio->statusLabel() }}</span>
                @elseif ($selesai)
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-navy-700 text-white">
                        <i class="bi bi-check-lg text-sm"></i>
                    </span>
                    <span class="text-center text-xs font-semibold text-navy-700">{{ $label }}</span>
                @elseif ($sekarang)
                    <span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-navy-700 bg-white text-sm font-bold text-navy-700">{{ $i }}</span>
                    <span class="text-center text-xs font-bold text-navy-700">{{ $label }}</span>
                @else
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-sm font-semibold text-slate-400">{{ $i }}</span>
                    <span class="text-center text-xs text-slate-400">{{ $label }}</span>
                @endif
            </div>
        @endforeach
    </div>

    @if ($gagal)
        <div class="border-t border-slate-100 px-6 py-3 text-xs text-slate-500">
            <i class="bi bi-info-circle"></i>
            Entri dikembalikan oleh verifikator. Perbaiki sesuai catatan lalu ajukan ulang — status akan kembali ke <strong>Diajukan</strong>.
        </div>
    @endif
</div>
