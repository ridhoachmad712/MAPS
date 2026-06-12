{{-- Satu baris berkas bukti dengan thumbnail + pratinjau sematan. Variabel: $b (Bukti), $bolehHapus (bool) --}}
<li class="list-group-item">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3 min-width-0">
            @if ($b->isGambar())
                <span class="avatar rounded" style="background-image: url('{{ route('bukti.show', $b) }}')"></span>
            @else
                <span class="avatar rounded bg-secondary-lt"><i class="bi bi-file-pdf"></i></span>
            @endif
            <a href="{{ route('bukti.show', $b) }}" target="_blank" class="text-truncate fw-medium">
                {{ $b->nama_file }}
            </a>
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <span class="text-secondary d-none d-sm-inline">{{ $b->uploaded_at->format('d/m/Y') }}</span>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse"
                    data-bs-target="#pratinjau-bukti-{{ $b->getKey() }}" aria-expanded="false"
                    aria-controls="pratinjau-bukti-{{ $b->getKey() }}">
                <i class="bi bi-eye me-1"></i><span class="d-none d-sm-inline">Pratinjau</span>
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

    <div class="collapse mt-3" id="pratinjau-bukti-{{ $b->getKey() }}">
        @if ($b->isGambar())
            <img src="{{ route('bukti.show', $b) }}" alt="Pratinjau {{ $b->nama_file }}"
                 class="rounded border" style="max-height: 24rem;">
        @else
            <iframe src="{{ route('bukti.show', $b) }}" title="Pratinjau {{ $b->nama_file }}"
                    class="w-100 rounded border" style="height: 24rem;"></iframe>
        @endif
    </div>
</li>
