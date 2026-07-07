@extends('layouts.app')
@section('title', 'Data Siswa')
@section('content')
<div class="py-4 space-y-4">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold text-gray-800">📋 Data Siswa</h2>
        <div class="flex flex-wrap items-center gap-2">
            @if(auth()->user()->isAdmin())
            <a href="{{ route('siswa.create') }}"
               class="inline-flex items-center gap-2 bg-blue-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                <i class="fas fa-plus"></i> Tambah Siswa
            </a>
            <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 bg-green-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-file-import"></i> Import
            </button>
            @endif
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg text-sm">
        {{ session('error') }}
    </div>
    @endif

    {{-- FILTER --}}
    <form method="GET" action="{{ route('siswa.index') }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
            <input type="text" name="search" value="{{ request()->input('search') }}"
                   placeholder="Cari nama / NIS..."
                   class="col-span-2 lg:col-span-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

            <select name="kelas" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kelas</option>
                <option value="10" {{ request()->input('kelas') == '10' ? 'selected' : '' }}>Kelas 10</option>
                <option value="11" {{ request()->input('kelas') == '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request()->input('kelas') == '12' ? 'selected' : '' }}>Kelas 12</option>
            </select>

            <select name="jurusan" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Jurusan</option>
                <option value="PPLG" {{ request()->input('jurusan') == 'PPLG' ? 'selected' : '' }}>PPLG</option>
                <option value="TJKT" {{ request()->input('jurusan') == 'TJKT' ? 'selected' : '' }}>TJKT</option>
                <option value="AKL"  {{ request()->input('jurusan') == 'AKL'  ? 'selected' : '' }}>AKL</option>
                <option value="AXIO" {{ request()->input('jurusan') == 'AXIO' ? 'selected' : '' }}>AXIO</option>
            </select>

            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request()->input('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="tidak_aktif" {{ request()->input('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 bg-blue-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                @if(request()->hasAny(['search','kelas','jurusan','status']))
                <a href="{{ route('siswa.index') }}"
                   class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-500 hover:bg-gray-50 transition">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </div>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-center w-10">NO</th>
                        <th class="px-4 py-3 text-left">NIS</th>
                        <th class="px-4 py-3 text-left">NAMA</th>
                        <th class="px-4 py-3 text-center">KELAS</th>
                        <th class="px-4 py-3 text-center">JURUSAN</th>
                        <th class="px-4 py-3 text-center">ANGKATAN</th>
                        <th class="px-4 py-3 text-center">JML KASUS</th>
                        <th class="px-4 py-3 text-center">STATUS</th>
                        <th class="px-4 py-3 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($siswas as $s)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-400 text-center text-xs">{{ $siswas->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3 font-mono text-gray-600 text-xs">{{ $s->nis }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    @if($s->foto)
                                    <img src="{{ asset('storage/'.$s->foto) }}" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                    <span class="text-blue-700 font-bold text-xs">{{ strtoupper(substr($s->nama,0,2)) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $s->nama }}</p>
                                    <span class="text-xs {{ $s->jenis_kelamin === 'L' ? 'text-blue-500' : 'text-pink-500' }}">
                                        <i class="fas fa-{{ $s->jenis_kelamin === 'L' ? 'mars' : 'venus' }} mr-1"></i>
                                        {{ $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $kelasNum = preg_replace('/[^0-9]/', '', $s->kelas);
                                $kelasColors = ['10'=>'bg-blue-100 text-blue-700','11'=>'bg-purple-100 text-purple-700','12'=>'bg-green-100 text-green-700'];
                                $cc = $kelasColors[$kelasNum] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $cc }}">
                                {{ $kelasNum }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $jurusanColors = ['PPLG'=>'bg-pink-100 text-pink-700','TJKT'=>'bg-orange-100 text-orange-700','AKL'=>'bg-teal-100 text-teal-700','AXIO'=>'bg-indigo-100 text-indigo-700'];
                                $jc = $jurusanColors[$s->jurusan] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $jc }}">
                                {{ $s->jurusan ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-center text-xs">{{ $s->angkatan }}</td>
                        <td class="px-4 py-3 text-center">
                            @php $jmlKasus = $s->kasuses_count ?? 0; @endphp
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $jmlKasus > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $jmlKasus }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $s->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $s->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('siswa.show', $s) }}"
                                   class="inline-flex items-center gap-1 text-xs bg-blue-50 text-blue-700 px-2.5 py-1.5 rounded-lg hover:bg-blue-100 transition"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('siswa.edit', $s) }}"
                                   class="inline-flex items-center gap-1 text-xs bg-yellow-50 text-yellow-700 px-2.5 py-1.5 rounded-lg hover:bg-yellow-100 transition"
                                   title="Edit Data">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('siswa.destroy', $s) }}"
                                      onsubmit="return confirm('Hapus siswa {{ $s->nama }}? Semua kasus terkait akan ikut terhapus.')"
                                      class="inline">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex items-center gap-1 text-xs bg-red-50 text-red-600 px-2.5 py-1.5 rounded-lg hover:bg-red-100 transition"
                                            title="Hapus Data">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-gray-400">
                            <i class="fas fa-users text-4xl block mb-2"></i>
                            Belum ada data siswa.
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('siswa.create') }}" class="block mt-2 text-blue-500 hover:underline text-sm">
                                + Tambah siswa pertama
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($siswas->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-400">
                Menampilkan {{ $siswas->firstItem() }}–{{ $siswas->lastItem() }} dari {{ $siswas->total() }} siswa
            </p>
            {{ $siswas->links() }}
        </div>
        @endif
    </div>
</div>

{{-- MODAL IMPORT --}}
<div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="document.getElementById('importModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full p-6">
            <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h3 class="text-lg font-medium text-gray-900 mb-4">Import Data Siswa</h3>
                <p class="text-sm text-gray-500 mb-4">Upload file Excel (.xlsx, .xls, .csv)</p>
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <div class="mt-3">
                    <a href="{{ route('siswa.download-template') }}" class="text-sm text-blue-600 hover:underline">
                        <i class="fas fa-download"></i> Download Template
                    </a>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                            class="px-4 py-2 border border-gray-200 rounded-lg text-sm">Batal</button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('importModal').classList.add('hidden');
        }
    });
</script>
@endpush
@endsection