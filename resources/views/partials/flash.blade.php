@if (session('sukses'))
    <div class="alert alert-success" x-data="{ tampil: true }" x-show="tampil">
        <i class="bi bi-check-circle-fill mt-0.5"></i>
        <span class="grow">{{ session('sukses') }}</span>
        <button type="button" class="opacity-60 hover:opacity-100" @click="tampil = false" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
    </div>
@endif

@if (session('gagal'))
    <div class="alert alert-danger" x-data="{ tampil: true }" x-show="tampil">
        <i class="bi bi-exclamation-triangle-fill mt-0.5"></i>
        <span class="grow">{{ session('gagal') }}</span>
        <button type="button" class="opacity-60 hover:opacity-100" @click="tampil = false" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" x-data="{ tampil: true }" x-show="tampil">
        <i class="bi bi-exclamation-octagon-fill mt-0.5"></i>
        <div class="grow">
            <strong>Periksa kembali isian Anda:</strong>
            <ul class="mt-1 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="opacity-60 hover:opacity-100" @click="tampil = false" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
    </div>
@endif
