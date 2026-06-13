{{-- Input bukti dua sumber: tautan URL (utama/disarankan) atau unggah berkas (opsional).
     Dipakai di formulir buat/ubah dan footer tambah bukti. Param opsional: $barisTautan, $sufiks. --}}
@php
    $barisTautan = $barisTautan ?? 3;
    $sufiks = $sufiks ?? 'form';
@endphp

<div class="mb-2">
    <div class="form-label">
        <i class="bi bi-link-45deg me-1"></i>Tautan bukti
        <span class="badge bg-azure-lt ms-1">Disarankan</span>
    </div>
    @for ($i = 0; $i < $barisTautan; $i++)
        <div class="input-group mb-2">
            <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
            <input type="url" name="tautan[]" class="form-control @error('tautan.'.$i) is-invalid @enderror"
                   value="{{ old('tautan.'.$i) }}" placeholder="https://drive.google.com/...">
            @error('tautan.'.$i)<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    @endfor
    <div class="form-hint">
        Tempel tautan Google Drive, OneDrive, dll. Pastikan aksesnya
        <strong>"siapa saja yang memiliki link"</strong> agar verifikator dapat membukanya.
        Cara ini tidak membebani penyimpanan server.
    </div>
</div>

<div class="mt-3">
    <a class="text-decoration-none small" data-bs-toggle="collapse" href="#unggah-{{ $sufiks }}" role="button"
       aria-expanded="false" aria-controls="unggah-{{ $sufiks }}">
        <i class="bi bi-upload me-1"></i>atau unggah berkas ke server
    </a>
    <div class="collapse mt-2" id="unggah-{{ $sufiks }}">
        <input type="file" name="bukti[]" class="form-control @error('bukti.*') is-invalid @enderror"
               accept=".pdf,.jpg,.jpeg,.png" multiple>
        @error('bukti.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-hint">PDF/JPG/PNG, maks 5 MB per berkas, maks 5 berkas.</div>
    </div>
</div>
