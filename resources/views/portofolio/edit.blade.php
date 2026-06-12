@extends('layouts.app')

@section('judul', 'Ubah Portofolio')

@section('konten')
    <div class="page-header mb-4">
        <h1 class="page-title">Ubah Portofolio</h1>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="POST" action="{{ route('portofolio.update', $portofolio) }}" enctype="multipart/form-data">
                @method('PUT')
                @include('portofolio._form')
            </form>
        </div>
    </div>

    @if ($portofolio->bukti->isNotEmpty())
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="bi bi-paperclip me-2"></i>Bukti Terunggah</h3></div>
            <ul class="list-group list-group-flush">
                @foreach ($portofolio->bukti as $b)
                    <li class="list-group-item d-flex align-items-center justify-content-between gap-3">
                        <a href="{{ route('bukti.show', $b) }}" target="_blank" class="fw-medium">
                            <i class="bi {{ $b->isGambar() ? 'bi-file-image' : 'bi-file-pdf' }} me-1"></i>{{ $b->nama_file }}
                        </a>
                        <form method="POST" action="{{ route('bukti.destroy', $b) }}"
                              onsubmit="return confirm('Hapus berkas bukti ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger btn-icon" aria-label="Hapus bukti"><i class="bi bi-trash"></i></button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
