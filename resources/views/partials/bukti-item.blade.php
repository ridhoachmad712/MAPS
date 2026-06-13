{{-- Satu baris bukti: tautan URL atau berkas unggahan. Variabel: $b (Bukti), $bolehHapus (bool) --}}
<li class="list-group-item">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3 min-width-0">
            @if ($b->isTautan())
                <span class="avatar rounded bg-azure-lt"><i class="bi bi-link-45deg"></i></span>
            @elseif ($b->isGambar())
                <span class="avatar rounded" style="background-image: url('{{ route('bukti.show', $b) }}')"></span>
            @else
                <span class="avatar rounded bg-secondary-lt"><i class="bi bi-file-pdf"></i></span>
            @endif
            <div class="min-width-0">
                <a href="{{ $b->alamat() }}" target="_blank" rel="noopener" class="d-block text-truncate fw-medium">
                    {{ $b->nama_file }}
                    @if ($b->isTautan())<i class="bi bi-box-arrow-up-right ms-1 small"></i>@endif
                </a>
                @if ($b->isTautan())
                    <span class="text-secondary small text-truncate d-block">{{ $b->url }}</span>
                @endif
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <span class="text-secondary d-none d-sm-inline">{{ $b->uploaded_at->format('d/m/Y') }}</span>
            @if (! $b->isTautan() || $b->urlSematan())
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse"
                        data-bs-target="#pratinjau-bukti-{{ $b->getKey() }}" aria-expanded="false"
                        aria-controls="pratinjau-bukti-{{ $b->getKey() }}">
                    <i class="bi bi-eye me-1"></i><span class="d-none d-sm-inline">Pratinjau</span>
                </button>
            @else
                <a href="{{ $b->alamat() }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-box-arrow-up-right me-1"></i><span class="d-none d-sm-inline">Buka</span>
                </a>
            @endif
            @if ($bolehHapus ?? false)
                <form method="POST" action="{{ route('bukti.destroy', $b) }}"
                      onsubmit="return confirm('Hapus bukti ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" aria-label="Hapus bukti"><i class="bi bi-trash"></i></button>
                </form>
            @endif
        </div>
    </div>

    @if (! $b->isTautan() || $b->urlSematan())
        <div class="collapse mt-3" id="pratinjau-bukti-{{ $b->getKey() }}">
            @if ($b->isTautan())
                <iframe src="{{ $b->urlSematan() }}" title="Pratinjau {{ $b->nama_file }}"
                        class="w-100 rounded border" style="height: 24rem;" allow="autoplay"></iframe>
            @elseif ($b->isGambar())
                <img src="{{ route('bukti.show', $b) }}" alt="Pratinjau {{ $b->nama_file }}"
                     class="rounded border" style="max-height: 24rem;">
            @else
                <iframe src="{{ route('bukti.show', $b) }}" title="Pratinjau {{ $b->nama_file }}"
                        class="w-100 rounded border" style="height: 24rem;"></iframe>
            @endif
        </div>
    @endif
</li>
