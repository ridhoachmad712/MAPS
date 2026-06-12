@extends('layouts.base')

@php
    $menuPublik = [
        ['label' => 'Beranda', 'href' => route('showcase.index'), 'aktif' => request()->routeIs('showcase.index')],
        ['label' => 'Capaian', 'href' => route('showcase.capaian'), 'aktif' => request()->routeIs('showcase.capaian')],
        ['label' => 'Mahasiswa', 'href' => route('showcase.mahasiswa.indeks'), 'aktif' => request()->routeIs('showcase.mahasiswa.indeks', 'showcase.mahasiswa')],
        ['label' => 'Statistik', 'href' => route('showcase.statistik'), 'aktif' => request()->routeIs('showcase.statistik')],
        ['label' => 'Tentang', 'href' => route('showcase.tentang'), 'aktif' => request()->routeIs('showcase.tentang')],
    ];
@endphp

@section('navbar')
    <nav class="navbar-maps" x-data="{ menu: false }">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-3">
            <a class="flex items-center gap-3" href="{{ route('showcase.index') }}">
                <img src="{{ \App\Models\Setting::get('logo') ? asset('storage/'.\App\Models\Setting::get('logo')) : asset('favicon.svg') }}"
                     alt="Logo {{ \App\Models\Setting::get('nama_aplikasi') }}" class="h-10 w-10 rounded-lg object-contain">
                <span class="leading-tight">
                    <span class="navbar-judul block text-sm font-bold sm:text-base">{{ \App\Models\Setting::get('nama_aplikasi') }}</span>
                    <span class="navbar-sub block text-xs">{{ \App\Models\Setting::get('nama_pemilik') }}</span>
                </span>
            </a>

            <button class="navbar-toggle rounded-lg px-3 py-1.5 md:hidden" @click="menu = !menu" aria-label="Buka menu">
                <i class="bi" :class="menu ? 'bi-x-lg' : 'bi-list'"></i>
            </button>

            <div class="w-full md:flex md:w-auto md:items-center" :class="menu ? 'block' : 'hidden md:flex'">
                <ul class="flex flex-col gap-1 py-2 md:flex-row md:items-center md:py-0">
                    @foreach ($menuPublik as $item)
                        <li>
                            <a class="nav-link-maps {{ $item['aktif'] ? 'aktif' : '' }}" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                    <li class="md:ml-2">
                        @auth
                            <a class="btn btn-sm btn-maps" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i>Dasbor</a>
                        @else
                            <a class="btn btn-sm btn-maps" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i>Masuk</a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endsection

@section('isi')
    @yield('konten')
@endsection
