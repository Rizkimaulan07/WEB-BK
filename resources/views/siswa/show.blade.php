@extends('layouts.app')
@section('title', 'Detail Siswa - ' . $siswa->nama)
@section('content')
<div class="py-4">
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('siswa.index') }}" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-lg font-semibold text-gray-800">Detail Siswa</h2>
        <div class="ml-auto flex gap-2">
            @if(!auth()->user()->isPimpinan())
            <a href="{{ route('kasus.create') }}?siswa_id={{ $siswa->id }}"
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

        {{-- ============================================ --}}
        {{-- KOLOM KIRI: PROFIL & STATISTIK --}}
        {{-- ============================================ --}}
        <div class="space-y-4">

            {{-- Kartu Profil --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 text-center">
                @if($siswa->foto)
                <img src="{{ asset('storage/'.$siswa->foto) }}"
                     class="w-28 h-28 rounded-full mx-auto object-cover mb-3 border-4 border-blue-100">
                @else
                <div class="w-28 h-28 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center mx-auto mb-3 border-4 border-blue-100">
                    <span class="text-blue-700 font-bold text-4xl">
                        {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                    </span>
                </div>
                @endif
                
                <h3 class="font-bold text-gray-900 text-lg">{{ $siswa->nama }}</h3>
                @if($siswa->nama_panggilan)
                <p class="text-sm text-gray-400">({{ $siswa->nama_panggilan }})</p>
                @endif
                <p class="text-gray-400 text-sm mt-0.5">NIS: {{ $siswa->nis }}</p>
                
                <div class="flex items-center justify-center gap-2 mt-3 flex-wrap">
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
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $siswa->status_badge }}">
                        {{ $siswa->status_text }}
                    </span>
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

        {{-- ============================================ --}}
        {{-- KOLOM KANAN: DATA LENGKAP --}}
        {{-- ============================================ --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- A. Informasi Siswa --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">A</span>
                    <h4 class="text-sm font-semibold text-gray-700">Informasi Siswa</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Nama Lengkap</span>
                        <span class="font-medium">{{ $siswa->nama }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Nama Panggilan</span>
                        <span class="font-medium">{{ $siswa->nama_panggilan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">NIS</span>
                        <span class="font-mono font-medium">{{ $siswa->nis }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Jenis Kelamin</span>
                        <span class="font-medium">{{ $siswa->jenis_kelamin_text }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Anak ke-</span>
                        <span class="font-medium">{{ $siswa->anak_ke ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Jumlah Saudara</span>
                        <span class="font-medium">{{ $siswa->jumlah_saudara ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Usia</span>
                        <span class="font-medium">{{ $siswa->usia ?? '-' }} tahun</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Tinggal Bersama</span>
                        <span class="font-medium">{{ $siswa->tinggal_bersama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Kelas</span>
                        <span class="font-medium">Kelas {{ $siswa->kelas }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Jurusan</span>
                        <span class="font-medium">{{ $siswa->jurusan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Angkatan</span>
                        <span class="font-medium">{{ $siswa->angkatan }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Transportasi</span>
                        <span class="font-medium">{{ $siswa->transportasi ?? '-' }}</span>
                    </div>
                    <div class="md:col-span-2 flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Alamat</span>
                        <span class="font-medium text-right">{{ $siswa->alamat ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-400">No. HP Siswa</span>
                        <span class="font-medium">{{ $siswa->no_hp_siswa ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- B. Kondisi Kesehatan --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <span class="bg-red-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">B</span>
                    <h4 class="text-sm font-semibold text-gray-700">Kondisi Kesehatan</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Golongan Darah</span>
                        <span class="font-medium">{{ $siswa->golongan_darah_label }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Alergi</span>
                        <span class="font-medium">
                            @if($siswa->alergi)
                                {{ $siswa->alergi_detail ?? 'Ada' }}
                            @else
                                Tidak Ada
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Penyakit Jantung</span>
                        <span class="font-medium">{{ $siswa->penyakit_jantung ? 'Ya' : 'Tidak' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Tuberculosis</span>
                        <span class="font-medium">{{ $siswa->tuberculosis ? 'Ya' : 'Tidak' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Asma</span>
                        <span class="font-medium">{{ $siswa->asma ? 'Ya' : 'Tidak' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Kondisi Mata</span>
                        <span class="font-medium">{{ $siswa->mata_text }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Buta Warna</span>
                        <span class="font-medium">
                            @if($siswa->buta_warna)
                                {{ ucfirst(str_replace('_', ' ', $siswa->buta_warna)) }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="md:col-span-2 flex justify-between py-2">
                        <span class="text-gray-400">Penyakit Lainnya</span>
                        <span class="font-medium text-right">{{ $siswa->penyakit_lain ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- C. Informasi Ayah --}}
            @if($siswa->ayah_nama)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <span class="bg-purple-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">C</span>
                    <h4 class="text-sm font-semibold text-gray-700">Informasi Ayah Kandung</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Nama</span>
                        <span class="font-medium">{{ $siswa->ayah_nama }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Usia</span>
                        <span class="font-medium">{{ $siswa->ayah_usia ?? '-' }} tahun</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Status Perkawinan</span>
                        <span class="font-medium">{{ $siswa->ayah_status_perkawinan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Pendidikan Terakhir</span>
                        <span class="font-medium">{{ $siswa->ayah_pendidikan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Pekerjaan</span>
                        <span class="font-medium">{{ $siswa->ayah_pekerjaan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Penghasilan Bulanan</span>
                        <span class="font-medium">{{ $siswa->ayah_penghasilan_formatted }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Jumlah Tanggungan</span>
                        <span class="font-medium">{{ $siswa->ayah_tanggungan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-400">Status Tempat Tinggal</span>
                        <span class="font-medium">{{ $siswa->ayah_status_tempat_tinggal ?? '-' }}</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- D. Informasi Ibu --}}
            @if($siswa->ibu_nama)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <span class="bg-pink-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">D</span>
                    <h4 class="text-sm font-semibold text-gray-700">Informasi Ibu Kandung</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Nama</span>
                        <span class="font-medium">{{ $siswa->ibu_nama }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Usia</span>
                        <span class="font-medium">{{ $siswa->ibu_usia ?? '-' }} tahun</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Status Perkawinan</span>
                        <span class="font-medium">{{ $siswa->ibu_status_perkawinan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Pendidikan Terakhir</span>
                        <span class="font-medium">{{ $siswa->ibu_pendidikan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Pekerjaan</span>
                        <span class="font-medium">{{ $siswa->ibu_pekerjaan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Penghasilan Bulanan</span>
                        <span class="font-medium">{{ $siswa->ibu_penghasilan_formatted }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Jumlah Tanggungan</span>
                        <span class="font-medium">{{ $siswa->ibu_tanggungan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-400">Status Tempat Tinggal</span>
                        <span class="font-medium">{{ $siswa->ibu_status_tempat_tinggal ?? '-' }}</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- E. Informasi Wali --}}
            @if($siswa->wali_nama)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <span class="bg-orange-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">E</span>
                    <h4 class="text-sm font-semibold text-gray-700">Informasi Wali</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Nama</span>
                        <span class="font-medium">{{ $siswa->wali_nama }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Usia</span>
                        <span class="font-medium">{{ $siswa->wali_usia ?? '-' }} tahun</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Status Perkawinan</span>
                        <span class="font-medium">{{ $siswa->wali_status_perkawinan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Pendidikan Terakhir</span>
                        <span class="font-medium">{{ $siswa->wali_pendidikan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Pekerjaan</span>
                        <span class="font-medium">{{ $siswa->wali_pekerjaan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Penghasilan Bulanan</span>
                        <span class="font-medium">{{ $siswa->wali_penghasilan_formatted }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Jumlah Tanggungan</span>
                        <span class="font-medium">{{ $siswa->wali_tanggungan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-400">Status Tempat Tinggal</span>
                        <span class="font-medium">{{ $siswa->wali_status_tempat_tinggal ?? '-' }}</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- F. Kontak Darurat --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <span class="bg-gray-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">F</span>
                    <h4 class="text-sm font-semibold text-gray-700">Kontak Darurat</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400">Nama Orang Tua/Wali</span>
                        <span class="font-medium">{{ $siswa->nama_ortu ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-400">No. HP Orang Tua/Wali</span>
                        <span class="font-medium">{{ $siswa->no_hp_ortu ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- RIWAYAT KASUS --}}
    {{-- ============================================ --}}
    <div class="mt-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">
                    <i class="fas fa-history text-gray-400 mr-2"></i>
                    Riwayat Kasus
                    <span class="ml-1 text-xs font-normal bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">
                        {{ $kasuses->count() }}
                    </span>
                </h3>
                @if(!auth()->user()->isPimpinan())
                <a href="{{ route('kasus.create') }}?siswa_id={{ $siswa->id }}"
                   class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-plus"></i> Tambah Kasus
                </a>
                @endif
            </div>

            <div class="space-y-3">
                @forelse($kasuses as $k)
                <a href="{{ route('kasus.show', $k) }}"
                   class="block border border-gray-100 rounded-xl p-4 hover:border-blue-200 hover:bg-blue-50/30 transition group">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
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
                            <p class="text-sm text-gray-700 line-clamp-2 leading-relaxed">
                                {{ $k->deskripsi }}
                            </p>
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
                    <a href="{{ route('kasus.create') }}?siswa_id={{ $siswa->id }}"
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
@endsection