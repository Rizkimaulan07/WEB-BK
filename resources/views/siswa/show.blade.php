@extends('layouts.app')
@section('title', 'Profil Siswa')
@section('content')
<div class="py-4 space-y-5">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('siswa.index') }}" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-lg font-semibold text-gray-800">Profil Siswa</h2>
        <div class="ml-auto flex gap-2">
            @if(!auth()->user()->isPimpinan())
            <a href="{{ route('kasus.create') }}"
               class="inline-flex items-center gap-1.5 text-sm bg-blue-900 text-white px-3 py-1.5 rounded-lg hover:bg-blue-800 transition">
                <i class="fas fa-plus"></i> Tambah Kasus
            </a>
            @endif
            @if(auth()->user()->isAdmin())
            <a href="{{ route('siswa.edit', $siswa) }}"
               class="inline-flex items-center gap-1.5 text-sm bg-yellow-50 text-yellow-700 border border-yellow-200 px-3 py-1.5 rounded-lg hover:bg-yellow-100 transition">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form method="POST" action="{{ route('siswa.destroy', $siswa) }}"
                  onsubmit="return confirm('Hapus siswa {{ $siswa->nama }}? Semua kasus terkait akan ikut terhapus.')">
                @csrf @method('DELETE')
                <button class="inline-flex items-center gap-1.5 text-sm bg-red-50 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-5">

        {{-- Kolom Kiri: Kartu Profil --}}
        <div class="space-y-4">

            {{-- Foto & Info Utama --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="text-center mb-5">
                    @if($siswa->foto)
                    <img src="{{ asset('storage/'.$siswa->foto) }}"
                         class="w-24 h-24 rounded-full mx-auto object-cover mb-3 border-4 border-blue-100">
                    @else
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center mx-auto mb-3 border-4 border-blue-100">
                        <span class="text-blue-700 font-bold text-3xl">
                            {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                        </span>
                    </div>
                    @endif
                    <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $siswa->nama }}</h3>
                    <p class="text-gray-400 text-sm mt-0.5">{{ $siswa->nis }}</p>
                    <div class="flex items-center justify-center gap-2 mt-2">
                        @php
                            $kelasNum = preg_replace('/[^0-9]/', '', $siswa->kelas);
                            $kelasColors = ['10'=>'bg-blue-100 text-blue-700','11'=>'bg-purple-100 text-purple-700','12'=>'bg-green-100 text-green-700'];
                            $cc = $kelasColors[$kelasNum] ?? 'bg-gray-100 text-gray-700';
                            $jurusanColors = ['PPLG'=>'bg-pink-100 text-pink-700','TJKT'=>'bg-orange-100 text-orange-700','AKL'=>'bg-teal-100 text-teal-700','AXIO'=>'bg-indigo-100 text-indigo-700'];
                            $jc = $jurusanColors[$siswa->jurusan] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $cc }}">
                            Kelas {{ $kelasNum }}
                        </span>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $jc }}">
                            {{ $siswa->jurusan ?? '-' }}
                        </span>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $siswa->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $siswa->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                </div>

                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-400 flex items-center gap-2">
                            <i class="fas fa-id-card w-4 text-center text-gray-300"></i> NIS
                        </span>
                        <span class="font-medium text-gray-700 font-mono">{{ $siswa->nis }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-400 flex items-center gap-2">
                            <i class="fas fa-venus-mars w-4 text-center text-gray-300"></i> Jenis Kelamin
                        </span>
                        <span class="font-medium text-gray-700">
                            {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-400 flex items-center gap-2">
                            <i class="fas fa-chalkboard w-4 text-center text-gray-300"></i> Kelas
                        </span>
                        <span class="font-medium text-gray-700">{{ $siswa->kelas }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-400 flex items-center gap-2">
                            <i class="fas fa-graduation-cap w-4 text-center text-gray-300"></i> Jurusan
                        </span>
                        <span class="font-medium text-gray-700">{{ $siswa->jurusan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-400 flex items-center gap-2">
                            <i class="fas fa-calendar w-4 text-center text-gray-300"></i> Angkatan
                        </span>
                        <span class="font-medium text-gray-700">{{ $siswa->angkatan }}</span>
                    </div>
                    @if($siswa->no_hp_siswa)
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-400 flex items-center gap-2">
                            <i class="fas fa-mobile-alt w-4 text-center text-gray-300"></i> HP Siswa
                        </span>
                        <a href="tel:{{ $siswa->no_hp_siswa }}" class="font-medium text-blue-600 hover:underline">
                            {{ $siswa->no_hp_siswa }}
                        </a>
                    </div>
                    @endif
                    @if($siswa->alamat)
                    <div class="py-2 border-b border-gray-50">
                        <span class="text-gray-400 flex items-center gap-2 mb-1">
                            <i class="fas fa-map-marker-alt w-4 text-center text-gray-300"></i> Alamat
                        </span>
                        <p class="font-medium text-gray-700 text-xs leading-relaxed pl-6">{{ $siswa->alamat }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Kartu Orang Tua --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-users text-gray-400"></i> Orang Tua / Wali
                </h4>
                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between items-center py-1.5 border-b border-gray-50">
                        <span class="text-gray-400">Nama</span>
                        <span class="font-medium text-gray-700">{{ $siswa->nama_ortu ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5">
                        <span class="text-gray-400">No. HP</span>
                        @if($siswa->no_hp_ortu)
                        <a href="tel:{{ $siswa->no_hp_ortu }}" class="font-medium text-blue-600 hover:underline">
                            {{ $siswa->no_hp_ortu }}
                        </a>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Statistik Kasus --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-gray-400"></i> Statistik Kasus
                </h4>
                @php
                    $totalKasus   = $kasuses->count();
                    $kasusSelesai = $kasuses->where('status', 'Selesai')->count();
                    $kasusAktif   = $totalKasus - $kasusSelesai;
                @endphp
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-2xl font-bold text-gray-900">{{ $totalKasus }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Total</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-3">
                        <p class="text-2xl font-bold text-red-600">{{ $kasusAktif }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Aktif</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3">
                        <p class="text-2xl font-bold text-green-600">{{ $kasusSelesai }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Selesai</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Riwayat Kasus --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">
                        Riwayat Kasus
                        <span class="ml-1 text-xs font-normal bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">
                            {{ $kasuses->count() }}
                        </span>
                    </h3>
                </div>

                <div class="space-y-3">
                    @forelse($kasuses as $k)
                    <a href="{{ route('kasus.show', $k) }}"
                       class="block border border-gray-100 rounded-xl p-4 hover:border-blue-200 hover:bg-blue-50/30 transition group">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                {{-- Badges --}}
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    @if($k->kategori)
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                                          style="background: {{ $k->kategori->warna }}20; color: {{ $k->kategori->warna }}">
                                        {{ $k->kategori->nama }}
                                    </span>
                                    @endif
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $k->status_badge }}">
                                        {{ $k->status }}
                                    </span>
                                </div>

                                {{-- Deskripsi --}}
                                <p class="text-sm text-gray-700 line-clamp-2 leading-relaxed">
                                    {{ $k->deskripsi }}
                                </p>

                                {{-- Meta --}}
                                <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $k->tanggal_kejadian ? $k->tanggal_kejadian->format('d M Y') : '-' }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-user"></i>
                                        {{ $k->guruBK->name ?? '-' }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-history"></i>
                                        {{ $k->tindakLanjuts->count() }} tindak lanjut
                                    </span>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-300 group-hover:text-blue-400 transition text-sm flex-shrink-0 mt-1"></i>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-folder-open text-4xl block mb-3"></i>
                        <p class="text-sm">Belum ada riwayat kasus untuk siswa ini.</p>
                        @if(!auth()->user()->isPimpinan())
                        <a href="{{ route('kasus.create') }}"
                           class="inline-block mt-3 text-sm text-blue-600 hover:underline">
                            + Catat kasus pertama
                        </a>
                        @endif
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection