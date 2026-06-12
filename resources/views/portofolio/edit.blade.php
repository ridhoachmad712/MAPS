@extends('layouts.app')

@section('judul', 'Ubah Portofolio')

@section('konten')
    <h1 class="mb-6 text-xl font-extrabold text-navy-700">Ubah Portofolio</h1>

    <div class="card mb-4">
        <div class="card-body p-6">
            <form method="POST" action="{{ route('portofolio.update', $portofolio) }}" enctype="multipart/form-data">
                @method('PUT')
                @include('portofolio._form')
            </form>
        </div>
    </div>

    @if ($portofolio->bukti->isNotEmpty())
        <div class="card">
            <div class="card-header"><i class="bi bi-paperclip"></i>Bukti Terunggah</div>
            <ul class="divide-y divide-slate-100">
                @foreach ($portofolio->bukti as $b)
                    <li class="flex items-center justify-between gap-3 px-5 py-3">
                        <a href="{{ route('bukti.show', $b) }}" target="_blank" class="text-sm font-medium text-navy-700 hover:underline">
                            <i class="bi {{ $b->isGambar() ? 'bi-file-image' : 'bi-file-pdf' }}"></i> {{ $b->nama_file }}
                        </a>
                        <form method="POST" action="{{ route('bukti.destroy', $b) }}"
                              onsubmit="return confirm('Hapus berkas bukti ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
