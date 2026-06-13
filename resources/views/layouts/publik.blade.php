@extends('layouts.base')

@php
    $menuPublik = [
        ['label' => 'Beranda', 'ikon' => 'bi-house', 'href' => route('showcase.index'), 'aktif' => request()->routeIs('showcase.index')],
        ['label' => 'Capaian', 'ikon' => 'bi-trophy', 'href' => route('showcase.capaian'), 'aktif' => request()->routeIs('showcase.capaian')],
        ['label' => 'Mahasiswa', 'ikon' => 'bi-people', 'href' => route('showcase.mahasiswa.indeks'), 'aktif' => request()->routeIs('showcase.mahasiswa.indeks', 'showcase.mahasiswa')],
        ['label' => 'Statistik', 'ikon' => 'bi-bar-chart', 'href' => route('showcase.statistik'), 'aktif' => request()->routeIs('showcase.statistik')],
        ['label' => 'Tentang', 'ikon' => 'bi-info-circle', 'href' => route('showcase.tentang'), 'aktif' => request()->routeIs('showcase.tentang')],
    ];
    $navbarTerang = \App\Support\PaletWarna::terang(\App\Models\Setting::get('warna_navbar'));
@endphp

@section('navbar')
    <header class="navbar navbar-expand-md navbar-maps sticky-top d-print-none" data-bs-theme="{{ $navbarTerang ? 'light' : 'dark' }}"
            data-tema-terang="{{ $navbarTerang ? 'light' : 'dark' }}">
        <div class="container-xl">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu-publik"
                    aria-controls="menu-publik" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand pe-md-3" href="{{ route('showcase.index') }}" title="{{ \App\Models\Setting::get('nama_aplikasi') }}">
                <img src="{{ \App\Models\Setting::get('logo') ? asset('storage/'.\App\Models\Setting::get('logo')) : asset('favicon.svg') }}"
                     alt="Logo {{ \App\Models\Setting::get('nama_aplikasi') }}" width="40" height="40" class="rounded">
            </a>

            <div class="collapse navbar-collapse" id="menu-publik">
                <ul class="navbar-nav ms-md-auto">
                    @foreach ($menuPublik as $item)
                        <li class="nav-item {{ $item['aktif'] ? 'active' : '' }}">
                            <a class="nav-link" href="{{ $item['href'] }}" @if ($item['aktif']) aria-current="page" @endif>
                                <span class="nav-link-icon d-md-none d-lg-inline-flex"><i class="bi {{ $item['ikon'] }}"></i></span>
                                <span class="nav-link-title">{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="d-flex align-items-center gap-2 ms-md-3 my-2 my-md-0">
                    <button type="button" class="btn btn-icon btn-ghost-secondary tombol-tema" aria-label="Ganti tema terang/gelap">
                        <i class="bi bi-moon ikon-gelap"></i>
                        <i class="bi bi-sun ikon-terang"></i>
                    </button>
                    @auth
                        <a class="btn btn-primary flex-fill" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-1"></i>Dasbor</a>
                    @else
                        <a class="btn btn-primary flex-fill" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i>Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
@endsection

@section('isi')
    @yield('konten')
@endsection
