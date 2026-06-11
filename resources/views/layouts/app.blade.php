<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem BK') - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        .sidebar-link.active { @apply bg-blue-700 text-white; }
        [x-cloak] { display: none !important; }
    </style>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 font-sans" x-data="{ sidebarOpen: false }">

{{-- Sidebar --}}
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar Overlay Mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden" x-cloak></div>

    {{-- Sidebar --}}
    <aside class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-blue-900 text-white flex flex-col transform transition-transform duration-300"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-blue-800">
            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-graduation-cap text-blue-900 text-lg"></i>
            </div>
            <div>
                <p class="font-bold text-sm leading-tight">Sistem BK</p>
                <p class="text-blue-300 text-xs">Bimbingan & Konseling</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @php $r = auth()->user()->role; @endphp

            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm hover:bg-blue-800 transition {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-chart-pie w-5 text-center"></i> Dashboard
            </a>

            <a href="{{ route('siswa.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm hover:bg-blue-800 transition {{ request()->routeIs('siswa.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-users w-5 text-center"></i> Data Siswa
            </a>

            <a href="{{ route('kasus.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm hover:bg-blue-800 transition {{ request()->routeIs('kasus.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-folder-open w-5 text-center"></i> Kasus Siswa
            </a>

            @if($r === 'admin' || $r === 'pimpinan')
            <a href="{{ route('statistik') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm hover:bg-blue-800 transition {{ request()->routeIs('statistik') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-chart-bar w-5 text-center"></i> Statistik
            </a>
            @endif

            @if($r === 'admin')
            <div class="pt-3 pb-1">
                <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider px-3">Administrasi</p>
            </div>
            <a href="{{ route('users.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm hover:bg-blue-800 transition {{ request()->routeIs('users.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-user-cog w-5 text-center"></i> Kelola User
            </a>
            <a href="{{ route('kategori.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm hover:bg-blue-800 transition {{ request()->routeIs('kategori.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-tags w-5 text-center"></i> Kategori Kasus
            </a>
            <a href="{{ route('laporan.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm hover:bg-blue-800 transition {{ request()->routeIs('laporan.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-file-export w-5 text-center"></i> Laporan
            </a>
            @endif
        </nav>

        {{-- User Info --}}
        <div class="border-t border-blue-800 p-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-blue-700 rounded-full flex items-center justify-center text-sm font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-blue-400 text-xs capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center gap-2 px-3 py-2 text-sm text-blue-300 hover:text-white hover:bg-blue-800 rounded-lg transition">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Top Bar --}}
        <header class="bg-white border-b border-gray-200 px-4 lg:px-6 py-4 flex items-center justify-between">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                <i class="fas fa-bars text-gray-600"></i>
            </button>
            <h1 class="text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
            <div class="text-sm text-gray-500">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</div>
        </header>

        {{-- Flash Messages --}}
        <div class="px-4 lg:px-6 pt-4">
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 mb-4 flex items-center gap-2">
                <i class="fas fa-check-circle text-green-600"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 mb-4 flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-red-600"></i> {{ session('error') }}
            </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-4 lg:px-6 pb-8">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>