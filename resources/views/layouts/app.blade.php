@extends('layouts.base')

@section('navbar')
    <nav class="navbar-maps" x-data="{ menu: false }">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-3">
            <a class="flex items-center gap-3" href="{{ route('dashboard') }}">
                <img src="{{ asset('favicon.svg') }}" alt="Logo MAPS" class="h-10 w-10 rounded-lg">
                <span class="leading-tight">
                    <span class="block text-sm font-bold text-gray-900 sm:text-base">MAPS</span>
                    <span class="block text-xs text-gray-500">Prodi Manajemen FEB UNM</span>
                </span>
            </a>

            <button class="rounded-lg border border-slate-200 px-3 py-1.5 text-slate-600 lg:hidden" @click="menu = !menu" aria-label="Buka menu">
                <i class="bi" :class="menu ? 'bi-x-lg' : 'bi-list'"></i>
            </button>

            <div class="w-full lg:flex lg:w-auto lg:grow lg:items-center lg:justify-between"
                 :class="menu ? 'block' : 'hidden lg:flex'">
                <ul class="flex flex-col gap-1 py-2 lg:flex-row lg:items-center lg:py-0">
                    <li>
                        <a class="nav-link-maps {{ request()->routeIs('dashboard') ? 'aktif' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i>Dashboard
                        </a>
                    </li>

                    @if (auth()->user()->isMahasiswa())
                        <li>
                            <a class="nav-link-maps {{ request()->routeIs('portofolio.*') ? 'aktif' : '' }}" href="{{ route('portofolio.index') }}">
                                <i class="bi bi-journal-album"></i>Portofolio Saya
                            </a>
                        </li>
                        <li>
                            <a class="nav-link-maps {{ request()->routeIs('profil.*') ? 'aktif' : '' }}" href="{{ route('profil.edit') }}">
                                <i class="bi bi-person-circle"></i>Profil
                            </a>
                        </li>
                    @else
                        <li>
                            <a class="nav-link-maps {{ request()->routeIs('admin.verifikasi.*') ? 'aktif' : '' }}" href="{{ route('admin.verifikasi.index') }}">
                                <i class="bi bi-patch-check"></i>Verifikasi
                            </a>
                        </li>
                        <li>
                            <a class="nav-link-maps {{ request()->routeIs('admin.portofolio.*') ? 'aktif' : '' }}" href="{{ route('admin.portofolio.index') }}">
                                <i class="bi bi-collection"></i>Semua Portofolio
                            </a>
                        </li>
                        @if (auth()->user()->isAdmin())
                            <li>
                                <a class="nav-link-maps" href="{{ url('/master') }}">
                                    <i class="bi bi-gear"></i>Data Master &amp; Laporan
                                </a>
                            </li>
                        @endif
                    @endif

                    <li>
                        <a class="nav-link-maps" href="{{ route('showcase.index') }}" target="_blank">
                            <i class="bi bi-globe2"></i>Showcase Publik
                        </a>
                    </li>
                </ul>

                <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 py-3 lg:border-0 lg:py-0">
                    <span class="text-sm text-slate-500">
                        <i class="bi bi-person-fill"></i>
                        {{ auth()->user()->mahasiswa->nama_lengkap ?? auth()->user()->username }}
                        <span class="badge badge-secondary ml-1 uppercase">{{ auth()->user()->role }}</span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-sm btn-outline">
                            <i class="bi bi-box-arrow-right"></i>Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
@endsection

@section('isi')
    <div class="mx-auto w-full max-w-7xl px-4 py-6">
        @include('partials.flash')
        @yield('konten')
    </div>
@endsection
