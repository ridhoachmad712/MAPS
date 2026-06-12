@if (session('sukses'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <div class="d-flex gap-2">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('sukses') }}</div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></a>
    </div>
@endif

@if (session('gagal'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <div class="d-flex gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>{{ session('gagal') }}</div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></a>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible" role="alert">
        <div class="d-flex gap-2">
            <i class="bi bi-exclamation-octagon-fill"></i>
            <div>
                <strong>Periksa kembali isian Anda:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></a>
    </div>
@endif
