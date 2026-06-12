@extends('layouts.base')

@section('navbar')
    <nav class="navbar-maps" x-data="{ menu: false }">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-3">
            <a class="flex items-center gap-3" href="{{ route('showcase.index') }}">
                <img src="{{ asset('favicon.svg') }}" alt="Logo MAPS" class="h-10 w-10 rounded-lg">
                <span class="leading-tight">
                    <span class="block text-sm font-bold text-gray-900 sm:text-base">MAPS</span>
                    <span class="block text-xs text-gray-500">Prodi Manajemen FEB UNM</span>
                </span>
            </a>

            <button class="rounded-lg border border-slate-200 px-3 py-1.5 text-slate-600 md:hidden" @click="menu = !menu" aria-label="Buka menu">
                <i class="bi" :class="menu ? 'bi-x-lg' : 'bi-list'"></i>
            </button>

            <div class="w-full md:flex md:w-auto md:items-center" :class="menu ? 'block' : 'hidden md:flex'">
                <ul class="flex flex-col gap-2 py-2 md:flex-row md:items-center md:py-0">
                    <li>
                        <a class="nav-link-maps" href="{{ route('showcase.index') }}"><i class="bi bi-stars"></i>Showcase</a>
                    </li>
                    <li class="md:ml-2">
                        @auth
                            <a class="btn btn-sm btn-maps" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i>Dashboard</a>
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
