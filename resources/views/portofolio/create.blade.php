@extends('layouts.app')

@section('judul', 'Tambah Portofolio')

@section('konten')
    <h1 class="mb-6 text-xl font-extrabold text-navy-700">Tambah Portofolio</h1>

    <div class="card">
        <div class="card-body p-6">
            <form method="POST" action="{{ route('portofolio.store') }}" enctype="multipart/form-data">
                @include('portofolio._form')
            </form>
        </div>
    </div>
@endsection
