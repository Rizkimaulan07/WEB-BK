@extends('layouts.app')
@section('title', 'Data Siswa')
@section('content')
<div class="py-4 space-y-4">
    <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold text-gray-800">Data Siswa</h2>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('siswa.create') }}" class="inline-flex items-center gap-2 bg-blue-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-800 transition">
            <i class="fas fa-plus"></i> Tambah Siswa
        </a>
        @endif
    </div>

    {{-- Filter --}}
    <form method="GET" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NIS..."
                   class="col-span-2 lg:col-span-1 border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <select name="kelas" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Kelas</option>
                <option value="10" {{ request('kelas') == '10' ? 'selected' : '' }}>Kelas 10</option>
                <option value="11" {{ request('kelas') == '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request('kelas') == '12' ? 'selected' : '' }}>Kelas 12</option>
            </select>
            <select name="jurusan" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Jurusan</option>
                <option value="PPLG" {{ request('jurusan') == 'PPLG' ? 'selected' : '' }}>PPLG</option>
                <option value="AKL" {{ request('jurusan') == 'AKL' ? 'selected' : '' }}>AKL</option>
                <option value="TJKT" {{ request('jurusan') == 'TJKT' ? 'selected' : '' }}>TJKT</option>
                <option value="AXIO" {{ request('jurusan') == 'AXIO' ? 'selected' : '' }}>AXIO</option>
            </select>
            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
            <button type="submit" class="bg-blue-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-800">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">No</th>
                        <th class="px-5 py-3 text-left">NIS</th>
                        <th class="px-5 py-3 text-left">Nama</th>
                        <th class="px-5 py-3 text-left">Kelas</th>
                        <th class="px-5 py-3 text-left">Jurusan</th>
                        <th class="px-5 py-3 text-left">Angkatan</th>
                        <th class="px-5 py-3 text-left">Jml Kasus</th>
                        <th class="px-5 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($siswas as $s)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-gray-400">{{ $siswas->firstItem() + $loop->index }}</td>
                        <td class="px-5 py-3 font-medium text-gray-900">{{ $s->nis }}</td>
                        <td class="px-5 py-3">{{ $s->nama }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                Kelas {{ $s->kelas }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                                {{ $s->jurusan ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-3">{{ $s->angkatan }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                {{ $s->kasuses_count }} kasus
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('siswa.show', $s) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('siswa.edit', $s) }}" class="text-yellow-600 hover:text-yellow-800 ml-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-10 text-center text-gray-400">
                            <i class="fas fa-users text-3xl mb-2 block"></i>Belum ada data siswa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($siswas->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $siswas->links() }}</div>
        @endif
    </div>
</div>
@endsection