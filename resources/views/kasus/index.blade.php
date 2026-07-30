@extends('layouts.app')
@section('title', 'Kasus Siswa')
@section('content')
<div class="py-4 space-y-4">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <i class="fas fa-folder-open text-blue-600"></i>
            Kasus Siswa
            <span class="text-xs font-normal bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">
                {{ $kasuses->total() }}
            </span>
        </h2>
        @if(!auth()->user()->isPimpinan())
        <a href="{{ route('kasus.create') }}"
           class="inline-flex items-center gap-2 bg-blue-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-800 transition">
            <i class="fas fa-plus"></i> Tambah Kasus
        </a>
        @endif
    </div>

    {{-- Filter --}}
    <form method="GET" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / NIS..."
                   class="col-span-2 lg:col-span-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

            <select name="kelas" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kelas</option>
                <option value="10" {{ request('kelas') == '10' ? 'selected' : '' }}>Kelas 10</option>
                <option value="11" {{ request('kelas') == '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request('kelas') == '12' ? 'selected' : '' }}>Kelas 12</option>
            </select>

            <select name="jurusan" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Jurusan</option>
                <option value="PPLG" {{ request('jurusan') == 'PPLG' ? 'selected' : '' }}>PPLG</option>
                <option value="TJKT" {{ request('jurusan') == 'TJKT' ? 'selected' : '' }}>TJKT</option>
                <option value="AKL" {{ request('jurusan') == 'AKL' ? 'selected' : '' }}>AKL</option>
                <option value="AXIO" {{ request('jurusan') == 'AXIO' ? 'selected' : '' }}>AXIO</option>
            </select>

            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="Baru" {{ request('status') == 'Baru' ? 'selected' : '' }}>Baru</option>
                <option value="Diproses (Konseling)" {{ request('status') == 'Diproses (Konseling)' ? 'selected' : '' }}>Diproses (Konseling)</option>
                <option value="Pemanggilan Orang Tua" {{ request('status') == 'Pemanggilan Orang Tua' ? 'selected' : '' }}>Pemanggilan Orang Tua</option>
                <option value="SP1" {{ request('status') == 'SP1' ? 'selected' : '' }}>SP1</option>
                <option value="SP2" {{ request('status') == 'SP2' ? 'selected' : '' }}>SP2</option>
                <option value="Wakil Kesiswaan" {{ request('status') == 'Wakil Kesiswaan' ? 'selected' : '' }}>Wakil Kesiswaan</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 bg-blue-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                @if(request()->hasAny(['search','kelas','jurusan','status']))
                <a href="{{ route('kasus.index') }}"
                   class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-500 hover:bg-gray-50 transition">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 mt-3">
            <input type="date" name="dari" value="{{ request('dari') }}"
                   placeholder="Dari tanggal"
                   class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="date" name="sampai" value="{{ request('sampai') }}"
                   placeholder="Sampai tanggal"
                   class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="kategori" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $k)
                <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama }}
                </option>
                @endforeach
            </select>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left w-10">No</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3 text-left">Siswa</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Kelas</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Kategori</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Guru BK</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kasuses as $k)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-400">{{ $kasuses->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3 text-gray-600 text-xs whitespace-nowrap">
                            {{ $k->tanggal_kejadian ? $k->tanggal_kejadian->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                {{-- Avatar --}}
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    @if($k->siswa && $k->siswa->foto)
                                    <img src="{{ asset('storage/'.$k->siswa->foto) }}" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                    <span class="text-blue-700 font-bold text-xs">
                                        {{ $k->siswa ? strtoupper(substr($k->siswa->nama, 0, 2)) : '??' }}
                                    </span>
                                    @endif
                                </div>
                                {{-- Nama & NIS --}}
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 truncate max-w-[150px]">
                                        {{ $k->siswa->nama ?? '-' }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $k->siswa->nis ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $kelasRaw = trim($k->siswa->kelas ?? '');
                                $kelasNum = preg_replace('/[^0-9]/', '', $kelasRaw);
                                $kelasColors = [
                                    '10' => 'bg-blue-100 text-blue-700',
                                    '11' => 'bg-purple-100 text-purple-700',
                                    '12' => 'bg-green-100 text-green-700'
                                ];
                                $cc = $kelasColors[$kelasNum] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap {{ $cc }}">
                                Kelas {{ $kelasNum ?: '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($k->kategori)
                            <span class="text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap"
                                  style="background: {{ $k->kategori->warna }}20; color: {{ $k->kategori->warna }}">
                                {{ $k->kategori->nama }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusClean = trim($k->status ?? '');
                                $statusBadges = [
                                    'Baru' => 'bg-blue-100 text-blue-700',
                                    'Diproses (Konseling)' => 'bg-yellow-100 text-yellow-700',
                                    'Pemanggilan Orang Tua' => 'bg-orange-100 text-orange-700',
                                    'SP1' => 'bg-red-100 text-red-700',
                                    'SP2' => 'bg-red-200 text-red-800',
                                    'Wakil Kesiswaan' => 'bg-purple-100 text-purple-700',
                                    'Selesai' => 'bg-green-100 text-green-700',
                                ];
                                $badge = $statusBadges[$statusClean] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap {{ $badge }}">
                                {{ $statusClean ?: '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-sm whitespace-nowrap">
                            {{ $k->guruBK->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1 whitespace-nowrap">
                                <a href="{{ route('kasus.show', $k) }}"
                                   class="inline-flex items-center gap-1 text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded-lg hover:bg-blue-100 transition">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                @if(!auth()->user()->isPimpinan())
                                <a href="{{ route('kasus.edit', $k) }}"
                                   class="inline-flex items-center gap-1 text-xs bg-yellow-50 text-yellow-700 px-2 py-1 rounded-lg hover:bg-yellow-100 transition">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @endif
                                @if(auth()->user()->isAdmin())
                                <form method="POST" action="{{ route('kasus.destroy', $k) }}"
                                      onsubmit="return confirm('Hapus kasus ini?')">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex items-center gap-1 text-xs bg-red-50 text-red-600 px-2 py-1 rounded-lg hover:bg-red-100 transition">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                            <i class="fas fa-folder-open text-4xl block mb-2"></i>
                            Belum ada data kasus.
                            @if(!auth()->user()->isPimpinan())
                            <a href="{{ route('kasus.create') }}" class="block mt-2 text-blue-500 hover:underline text-sm">
                                + Catat kasus pertama
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kasuses->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-400">
                Menampilkan {{ $kasuses->firstItem() }}–{{ $kasuses->lastItem() }} dari {{ $kasuses->total() }} kasus
            </p>
            {{ $kasuses->links() }}
        </div>
        @endif
    </div>
</div>
@endsection