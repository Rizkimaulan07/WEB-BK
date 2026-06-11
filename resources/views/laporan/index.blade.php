@extends('layouts.app')
@section('title', 'Laporan Kasus')
@section('content')
<div class="py-4 space-y-4">
    <h2 class="text-lg font-semibold text-gray-800">Laporan Kasus</h2>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('laporan.index') }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
            <input type="date" name="dari" value="{{ request('dari') }}" placeholder="Dari tanggal"
                   class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="date" name="sampai" value="{{ request('sampai') }}" placeholder="Sampai tanggal"
                   class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="kategori_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $k)
                <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                @endforeach
            </select>
            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                @foreach($statuses as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-800">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('laporan.pdf', request()->all()) }}" class="bg-red-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-red-700">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
                <a href="{{ route('laporan.excel', request()->all()) }}" class="bg-green-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-file-excel mr-1"></i> Excel
                </a>
            </div>
        </div>
    </form>

    {{-- Statistik Card --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-gray-400 text-xs">Total Kasus</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalKasus }}</p>
        </div>
        @foreach($kasusPerKategori as $nama => $jumlah)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-gray-400 text-xs">Kasus {{ $nama }}</p>
            <p class="text-3xl font-bold text-gray-900">{{ $jumlah }}</p>
        </div>
        @endforeach
    </div>

    {{-- Tabel Kasus --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">No</th>
                        <th class="px-5 py-3 text-left">Tanggal</th>
                        <th class="px-5 py-3 text-left">Siswa</th>
                        <th class="px-5 py-3 text-left">Kelas</th>
                        <th class="px-5 py-3 text-left">Kategori</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Deskripsi</th>
                        <th class="px-5 py-3 text-left">Guru BK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kasus as $k)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3">{{ $k->tanggal_kejadian->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 font-medium text-gray-900">{{ $k->siswa->nama }}</td>
                        <td class="px-5 py-3">{{ $k->siswa->kelas }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-medium px-2 py-1 rounded-full" style="background: {{ $k->kategori->warna }}20; color: {{ $k->kategori->warna }}">
                                {{ $k->kategori->nama }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-medium px-2 py-1 rounded-full {{ $k->status_badge }}">
                                {{ $k->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500 max-w-xs truncate">{{ Str::limit($k->deskripsi, 50) }}</td>
                        <td class="px-5 py-3">{{ $k->guruBK->name }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400">Tidak ada data kasus.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection