@extends('layouts.app')
@section('title', 'Detail Kasus')
@section('content')
<div class="py-4 space-y-5 max-w-4xl">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('kasus.index') }}" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Detail Kasus #{{ $kasus->id }}</h2>
            <p class="text-sm text-gray-400">{{ $kasus->tanggal_kejadian->format('d F Y') }}</p>
        </div>
        <div class="ml-auto flex gap-2">
            @if(!auth()->user()->isPimpinan())
            <a href="{{ route('kasus.edit', $kasus) }}" class="text-sm bg-yellow-50 text-yellow-700 border border-yellow-200 px-3 py-1.5 rounded-lg hover:bg-yellow-100 transition">
                <i class="fas fa-edit mr-1"></i>Edit
            </a>
            @endif
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-5">
        {{-- Left: Info Kasus --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- Info Kasus Card --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">Informasi Kasus</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Kategori</p>
                        <span class="font-medium px-2 py-1 rounded-full text-sm"
                              style="background: {{ $kasus->kategori->warna }}20; color: {{ $kasus->kategori->warna }}">
                            {{ $kasus->kategori->nama }}
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Status</p>
                        <span class="text-sm font-medium px-2 py-1 rounded-full {{ $kasus->status_badge }}">
                            {{ $kasus->status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Guru BK</p>
                        <p class="font-medium text-gray-800">{{ $kasus->guruBK->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Tanggal Kejadian</p>
                        <p class="font-medium text-gray-800">{{ $kasus->tanggal_kejadian->format('d F Y') }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-gray-400 text-xs mb-1">Deskripsi Kasus</p>
                    <p class="text-gray-800 text-sm leading-relaxed">{{ $kasus->deskripsi }}</p>
                </div>
                @if($kasus->keterangan)
                <div class="mt-3">
                    <p class="text-gray-400 text-xs mb-1">Keterangan Tambahan</p>
                    <p class="text-gray-600 text-sm">{{ $kasus->keterangan }}</p>
                </div>
                @endif
            </div>

            {{-- Tindak Lanjut --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                    Riwayat Tindak Lanjut ({{ $kasus->tindakLanjuts->count() }})
                </h3>
                <div class="space-y-3 mb-4">
                    @forelse($kasus->tindakLanjuts->sortByDesc('tanggal') as $tl)
                    <div class="bg-gray-50 rounded-lg p-4 relative">
                        <div class="flex items-start justify-between mb-1">
                            <span class="text-xs font-semibold text-gray-500">{{ $tl->user->name }} · {{ $tl->tanggal->format('d M Y') }}</span>
                            @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('tindak-lanjut.destroy', $tl) }}" onsubmit="return confirm('Hapus catatan ini?')">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-600 text-xs"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                        <p class="text-sm text-gray-700">{{ $tl->catatan }}</p>
                        <span class="mt-2 inline-block text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">→ {{ $tl->status_setelah }}</span>
                    </div>
                    @empty
                    <p class="text-gray-400 text-sm text-center py-4">Belum ada tindak lanjut.</p>
                    @endforelse
                </div>

                @if(!auth()->user()->isPimpinan())
                <form method="POST" action="{{ route('tindak-lanjut.store', $kasus) }}" class="border-t border-gray-100 pt-4 space-y-3">
                    @csrf
                    <p class="text-sm font-medium text-gray-700">Tambah Tindak Lanjut</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Tanggal</label>
                            <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Update Status</label>
                            <select name="status_setelah" required
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @foreach(['Baru','Diproses','Konseling','Pemanggilan Orang Tua','Pembinaan','Selesai'] as $s)
                                <option value="{{ $s }}" {{ $kasus->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <textarea name="catatan" rows="3" required placeholder="Catatan tindak lanjut..."
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    <button type="submit" class="bg-blue-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                        <i class="fas fa-plus mr-1"></i> Simpan
                    </button>
                </form>
                @endif
            </div>

            {{-- Home Visit --}}
            @if(!auth()->user()->isPimpinan())
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                    Home Visit ({{ $kasus->homeVisits->count() }})
                </h3>
                @foreach($kasus->homeVisits as $hv)
                <div class="bg-gray-50 rounded-lg p-4 mb-3 text-sm">
                    <p class="font-medium text-gray-700 mb-1">{{ $hv->tanggal_kunjungan->format('d M Y') }} · Bertemu: {{ $hv->yang_ditemui }}</p>
                    <p class="text-gray-500 text-xs mb-1"><i class="fas fa-map-marker-alt mr-1"></i>{{ $hv->alamat_kunjungan }}</p>
                    <p class="text-gray-700">{{ $hv->hasil_kunjungan }}</p>
                    <p class="text-xs text-gray-400 mt-1">Oleh: {{ $hv->user->name }}</p>
                </div>
                @endforeach

                <form method="POST" action="{{ route('home-visit.store', $kasus) }}" class="border-t border-gray-100 pt-4 space-y-3">
                    @csrf
                    <p class="text-sm font-medium text-gray-700">Tambah Home Visit</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Tanggal Kunjungan</label>
                            <input type="date" name="tanggal_kunjungan" required value="{{ date('Y-m-d') }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Yang Ditemui</label>
                            <input type="text" name="yang_ditemui" required placeholder="Nama orang tua/wali..."
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <input type="text" name="alamat_kunjungan" required placeholder="Alamat kunjungan..."
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <textarea name="hasil_kunjungan" rows="3" required placeholder="Hasil kunjungan..."
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    <button type="submit" class="bg-green-700 text-white text-sm px-4 py-2 rounded-lg hover:bg-green-800 transition">
                        <i class="fas fa-home mr-1"></i> Simpan Home Visit
                    </button>
                </form>
            </div>
            @else
            {{-- Pimpinan: read only home visit --}}
            @if($kasus->homeVisits->isNotEmpty())
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Home Visit ({{ $kasus->homeVisits->count() }})</h3>
                @foreach($kasus->homeVisits as $hv)
                <div class="bg-gray-50 rounded-lg p-4 mb-2 text-sm">
                    <p class="font-medium">{{ $hv->tanggal_kunjungan->format('d M Y') }} · Bertemu: {{ $hv->yang_ditemui }}</p>
                    <p class="text-gray-500 text-xs">{{ $hv->alamat_kunjungan }}</p>
                    <p class="text-gray-700 mt-1">{{ $hv->hasil_kunjungan }}</p>
                </div>
                @endforeach
            </div>
            @endif
            @endif
        </div>

        {{-- Right: Info Siswa --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">Profil Siswa</h3>
                <div class="text-center mb-4">
                    @if($kasus->siswa->foto)
                    <img src="{{ asset('storage/' . $kasus->siswa->foto) }}" class="w-16 h-16 rounded-full mx-auto object-cover mb-2">
                    @else
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="text-blue-700 font-bold text-xl">{{ strtoupper(substr($kasus->siswa->nama, 0, 2)) }}</span>
                    </div>
                    @endif
                    <p class="font-semibold text-gray-800">{{ $kasus->siswa->nama }}</p>
                    <p class="text-sm text-gray-500">{{ $kasus->siswa->nis }}</p>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Kelas</span>
                        <span class="font-medium text-gray-700">{{ $kasus->siswa->kelas }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Jurusan</span>
                        <span class="font-medium text-gray-700">{{ $kasus->siswa->jurusan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Angkatan</span>
                        <span class="font-medium text-gray-700">{{ $kasus->siswa->angkatan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Orang Tua</span>
                        <span class="font-medium text-gray-700">{{ $kasus->siswa->nama_ortu ?? '-' }}</span>
                    </div>
                    @if($kasus->siswa->no_hp_ortu)
                    <div class="flex justify-between">
                        <span class="text-gray-400">No. HP Ortu</span>
                        <a href="tel:{{ $kasus->siswa->no_hp_ortu }}" class="text-blue-600 font-medium">{{ $kasus->siswa->no_hp_ortu }}</a>
                    </div>
                    @endif
                </div>
                <a href="{{ route('siswa.show', $kasus->siswa) }}" class="mt-4 block text-center text-sm text-blue-600 hover:underline">
                    Lihat Profil Lengkap →
                </a>
            </div>

            {{-- Total kasus siswa ini --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <p class="text-xs text-gray-400 mb-1">Total Kasus Siswa Ini</p>
                <p class="text-3xl font-bold text-gray-900">{{ $kasus->siswa->kasuses()->count() }}</p>
                <a href="{{ route('kasus.index') }}?search={{ $kasus->siswa->nis }}" class="text-xs text-blue-500 hover:underline">Lihat semua kasusnya</a>
            </div>
        </div>
    </div>
</div>
@endsection