@csrf

<div class="row g-3">
    <div class="col-12 col-md-8">
        <label class="form-label required">Judul Capaian</label>
        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
               value="{{ old('judul', $portofolio->judul ?? '') }}"
               placeholder="contoh: Juara 1 Public Speaking Competition" required>
        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label required">Kategori</label>
        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
            <option value="">— pilih kategori —</option>
            @foreach ($kategori as $k)
                <option value="{{ $k->kategori_id }}" @selected(old('kategori_id', $portofolio->kategori_id ?? '') == $k->kategori_id)>
                    {{ $k->kode }} — {{ $k->nama_kategori }}
                </option>
            @endforeach
        </select>
        @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-3">
        <label class="form-label required">Tahun Pencapaian</label>
        <input type="number" name="tahun_pencapaian" min="2000" max="2100"
               class="form-control @error('tahun_pencapaian') is-invalid @enderror"
               value="{{ old('tahun_pencapaian', $portofolio->tahun_pencapaian ?? date('Y')) }}" required>
        @error('tahun_pencapaian')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-3">
        <label class="form-label required">Level</label>
        <select name="level" class="form-select @error('level') is-invalid @enderror" required>
            <option value="">— pilih level —</option>
            @foreach (\App\Models\Portofolio::LEVEL_LABEL as $nilai => $label)
                <option value="{{ $nilai }}" @selected(old('level', $portofolio->level ?? '') === $nilai)>{{ $label }}</option>
            @endforeach
        </select>
        @error('level')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-3">
        <label class="form-label">Penyelenggara</label>
        <input type="text" name="penyelenggara" class="form-control"
               value="{{ old('penyelenggara', $portofolio->penyelenggara ?? '') }}"
               placeholder="contoh: Universitas Indonesia">
    </div>
    <div class="col-12 col-md-3">
        <label class="form-label">Peran / Capaian</label>
        <input type="text" name="peran_capaian" class="form-control"
               value="{{ old('peran_capaian', $portofolio->peran_capaian ?? '') }}"
               placeholder="contoh: Ketua Tim (Juara 2)">
    </div>

    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" rows="3" class="form-control"
                  placeholder="Ceritakan singkat tentang capaian ini...">{{ old('deskripsi', $portofolio->deskripsi ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label">Bukti Capaian</label>
        @include('portofolio._input-bukti')
        <div class="form-hint mt-2">Minimal satu bukti (tautan atau berkas) diperlukan sebelum entri dapat diajukan untuk verifikasi.</div>
    </div>

    <div class="col-12">
        <label class="form-check mb-0">
            <input class="form-check-input" type="checkbox" name="is_publik" value="1"
                   @checked(old('is_publik', $portofolio->is_publik ?? false))>
            <span class="form-check-label">Saya setuju capaian ini ditampilkan di halaman publik (showcase) setelah terverifikasi.</span>
        </label>
    </div>
</div>

<hr class="my-4">
<div class="d-flex flex-wrap align-items-center gap-2">
    <button type="submit" name="aksi" value="simpan" class="btn btn-outline-secondary">
        <i class="bi bi-save me-1"></i>Simpan sebagai Draft
    </button>
    <button type="submit" name="aksi" value="ajukan" class="btn btn-primary">
        <i class="bi bi-send me-1"></i>Simpan &amp; Ajukan Verifikasi
    </button>
    <a href="{{ route('portofolio.index') }}" class="btn btn-link link-secondary">Batal</a>
</div>
