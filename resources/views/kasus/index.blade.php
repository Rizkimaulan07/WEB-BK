@extends('layouts.app')
@section('title', 'Daftar Kasus')
@section('content')
<div class="py-4 space-y-4">
    <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold text-gray-800">Daftar Kasus Siswa</h2>
        @if(!auth()->user()->isPimpinan())
        <a href="{{ route('kasus.create') }}" class="inline-flex items-center gap-2 bg-blue-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-800 transition">
            <i class="fas fa-plus"></i> Tambah Kasus
        </a>
        @endif
    </div>

    {{-- Filter --}}
    <form method="GET" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="grid grid-cols-2 lg:grid-cols-6 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NIS..."
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
                <option value="AKL" {{ request('jurusan') == 'AKL' ? 'selected' : '' }}>AKL</option>
                <option value="TJKT" {{ request('jurusan') == 'TJKT' ? 'selected' : '' }}>TJKT</option>
                <option value="AXIO" {{ request('jurusan') == 'AXIO' ? 'selected' : '' }}>AXIO</option>
            </select>
            <select name="kategori" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kat)
                <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                @endforeach
            </select>
            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                @foreach($statuses as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
            <input type="date" name="dari" value="{{ request('dari') }}" placeholder="Dari"
                   class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="date" name="sampai" value="{{ request('sampai') }}" placeholder="Sampai"
                   class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('kasus.index') }}" class="bg-gray-500 text-white text-sm px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-sync-alt mr-1"></i> Reset
            </a>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">No</th>
                        <th class="px-5 py-3 text-left">Siswa</th>
                        <th class="px-5 py-3 text-left">Kelas</th>
                        <th class="px-5 py-3 text-left">Jurusan</th>
                        <th class="px-5 py-3 text-left">Kategori</th>
                        <th class="px-5 py-3 text-left">Tanggal</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Guru BK</th>
                        <th class="px-5 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kasuses as $k)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-gray-400">{{ $kasuses->firstItem() + $loop->index }}</td>
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-900">{{ $k->siswa->nama }}</p>
                            <p class="text-xs text-gray-400">{{ $k->siswa->nis }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                Kelas {{ $k->siswa->kelas }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            @if($k->siswa->jurusan)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                                {{ $k->siswa->jurusan }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-medium px-2 py-1 rounded-full"
                                  style="background: {{ $k->kategori->warna }}20; color: {{ $k->kategori->warna }}">
                                {{ $k->kategori->nama }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $k->tanggal_kejadian->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-medium px-2 py-1 rounded-full {{ $k->status_badge }}">
                                {{ $k->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $k->guruBK->name }}</td>
                        <td class="px-5 py-3">
                            <a href="{{ route('kasus.show', $k) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(!auth()->user()->isPimpinan())
                            <a href="{{ route('kasus.edit', $k) }}" class="text-yellow-600 hover:text-yellow-800 mr-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('kasus.destroy', $k) }}" class="inline"
                                  onsubmit="return confirm('Hapus kasus ini?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-10 text-center text-gray-400">
                            <i class="fas fa-folder-open text-3xl mb-2 block"></i>Belum ada kasus.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kasuses->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $kasuses->links() }}</div>
        @endif
    </div>
</div>
@endsection