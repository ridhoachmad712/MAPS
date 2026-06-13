@extends('layouts.base')

{{-- Halaman internal tidak perlu diindeks mesin pencari --}}
@section('robots')
    <meta name="robots" content="noindex">
@endsection

{{-- Footer showcase hanya untuk halaman publik --}}
@section('tanpa_footer', '1')

@php
    $navbarTerang = \App\Support\PaletWarna::terang(\App\Models\Setting::get('warna_navbar'));
    $dataMasterAktif = request()->routeIs('admin.mahasiswa.*', 'admin.kategori.*', 'admin.pengguna.*', 'admin.pengaturan.*');
@endphp

@section('navbar')
    <header class="navbar navbar-expand-lg navbar-maps sticky-top d-print-none" data-bs-theme="{{ $navbarTerang ? 'light' : 'dark' }}">
        <div class="container-xl">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu-aplikasi"
                    aria-controls="menu-aplikasi" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand pe-lg-3" href="{{ route('dashboard') }}" title="{{ \App\Models\Setting::get('nama_aplikasi') }}">
                <img src="{{ \App\Models\Setting::get('logo') ? asset('storage/'.\App\Models\Setting::get('logo')) : asset('favicon.svg') }}"
                     alt="Logo {{ \App\Models\Setting::get('nama_aplikasi') }}" width="40" height="40" class="rounded">
            </a>

            <div class="collapse navbar-collapse" id="menu-aplikasi">
                <ul class="navbar-nav">
                    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <span class="nav-link-icon"><i class="bi bi-speedometer2"></i></span>
                            <span class="nav-link-title">Dashboard</span>
                        </a>
                    </li>

                    @if (auth()->user()->isMahasiswa())
                        <li class="nav-item {{ request()->routeIs('portofolio.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('portofolio.index') }}">
                                <span class="nav-link-icon"><i class="bi bi-journal-album"></i></span>
                                <span class="nav-link-title">Portofolio Saya</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('profil.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('profil.edit') }}">
                                <span class="nav-link-icon"><i class="bi bi-person-circle"></i></span>
                                <span class="nav-link-title">Profil</span>
                            </a>
                        </li>
                    @else
                        <li class="nav-item {{ request()->routeIs('admin.verifikasi.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.verifikasi.index') }}">
                                <span class="nav-link-icon"><i class="bi bi-patch-check"></i></span>
                                <span class="nav-link-title">Verifikasi</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('admin.portofolio.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.portofolio.index') }}">
                                <span class="nav-link-icon"><i class="bi bi-collection"></i></span>
                                <span class="nav-link-title">Semua Portofolio</span>
                            </a>
                        </li>
                        @if (auth()->user()->isAdmin())
                            <li class="nav-item dropdown {{ $dataMasterAktif ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="nav-link-icon"><i class="bi bi-gear"></i></span>
                                    <span class="nav-link-title">Data Master</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item {{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}" href="{{ route('admin.mahasiswa.index') }}">
                                        <i class="bi bi-people me-2"></i>Mahasiswa
                                    </a>
                                    <a class="dropdown-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">
                                        <i class="bi bi-tags me-2"></i>Kategori
                                    </a>
                                    <a class="dropdown-item {{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}" href="{{ route('admin.pengguna.index') }}">
                                        <i class="bi bi-person-gear me-2"></i>Pengguna
                                    </a>
                                    @if (Route::has('admin.pengaturan.edit'))
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}" href="{{ route('admin.pengaturan.edit') }}">
                                            <i class="bi bi-palette me-2"></i>Pengaturan Tampilan
                                        </a>
                                    @endif
                                </div>
                            </li>
                        @endif
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('showcase.index') }}" target="_blank">
                            <span class="nav-link-icon"><i class="bi bi-globe2"></i></span>
                            <span class="nav-link-title">Showcase Publik</span>
                        </a>
                    </li>
                </ul>

                @php $namaAkun = auth()->user()->mahasiswa->nama_lengkap ?? auth()->user()->username; @endphp
                <div class="nav-item dropdown ms-lg-auto my-2 my-lg-0">
                    <a href="#" class="nav-link d-flex align-items-center lh-1 p-0" data-bs-toggle="dropdown"
                       aria-expanded="false" aria-label="Buka menu akun">
                        <span class="avatar avatar-sm">{{ strtoupper(substr($namaAkun, 0, 1)) }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-item-text">
                            <div class="fw-semibold">{{ $namaAkun }}</div>
                            <div class="text-secondary text-uppercase small">{{ auth()->user()->role }}</div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item">
                                <i class="bi bi-box-arrow-right me-2"></i>Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('isi')
    <div class="container-xl py-4">
        @include('partials.flash')
        @yield('konten')
    </div>
@endsection
