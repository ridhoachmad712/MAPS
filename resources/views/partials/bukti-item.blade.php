{{-- Satu baris berkas bukti dengan thumbnail + pratinjau sematan. Variabel: $b (Bukti), $bolehHapus (bool) --}}
<li class="px-5 py-3" x-data="{ pratinjau: false }">
    <div class="flex items-center justify-between gap-3">
        <div class="flex min-w-0 items-center gap-3">
            @if ($b->isGambar())
                <img src="{{ route('bukti.show', $b) }}" alt="" loading="lazy"
                     class="h-10 w-10 flex-shrink-0 rounded-lg border border-slate-200 object-cover">
            @else
                <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500">
                    <i class="bi bi-file-pdf"></i>
                </span>
            @endif
            <a href="{{ route('bukti.show', $b) }}" target="_blank" class="truncate text-sm font-medium text-navy-700 hover:underline">
                {{ $b->nama_file }}
            </a>
        </div>
        <div class="flex flex-shrink-0 items-center gap-2">
            <span class="hidden text-xs text-slate-400 sm:inline">{{ $b->uploaded_at->format('d/m/Y') }}</span>
            <button type="button" class="btn btn-sm btn-outline" @click="pratinjau = !pratinjau">
                <i class="bi" :class="pratinjau ? 'bi-chevron-up' : 'bi-eye'"></i>
                <span class="hidden sm:inline" x-text="pratinjau ? 'Tutup' : 'Pratinjau'">Pratinjau</span>
            </button>
            @if ($bolehHapus ?? false)
                <form method="POST" action="{{ route('bukti.destroy', $b) }}"
                      onsubmit="return confirm('Hapus berkas bukti ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" aria-label="Hapus bukti"><i class="bi bi-trash"></i></button>
                </form>
            @endif
        </div>
    </div>

    <template x-if="pratinjau">
        <div class="mt-3">
            @if ($b->isGambar())
                <img src="{{ route('bukti.show', $b) }}" alt="Pratinjau {{ $b->nama_file }}"
                     class="max-h-96 rounded-lg border border-slate-200">
            @else
                <iframe src="{{ route('bukti.show', $b) }}" title="Pratinjau {{ $b->nama_file }}"
                        class="h-96 w-full rounded-lg border border-slate-200"></iframe>
            @endif
        </div>
    </template>
</li>
