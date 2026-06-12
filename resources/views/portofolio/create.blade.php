@extends('layouts.app')

@section('judul', 'Tambah Portofolio')

@section('konten')
    <div class="page-header mb-4">
        <h1 class="page-title">Tambah Portofolio</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('portofolio.store') }}" enctype="multipart/form-data">
                @include('portofolio._form')
            </form>
        </div>
    </div>
@endsection
