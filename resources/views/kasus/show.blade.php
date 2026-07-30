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
            <p class="text-sm text-gray-400">{{ $kasus->tanggal_kejadian ? $kasus->tanggal_kejadian->format('d F Y') : '-' }}</p>
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
                        @if($kasus->kategori)
                        <span class="font-medium px-2 py-1 rounded-full text-sm"
                              style="background: {{ $kasus->kategori->warna }}20; color: {{ $kasus->kategori->warna }}">
                            {{ $kasus->kategori->nama }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Status</p>
                        @php
                            $statusBadges = [
                                'Baru' => 'bg-blue-100 text-blue-700',
                                'Diproses (Konseling)' => 'bg-yellow-100 text-yellow-700',
                                'SP1' => 'bg-red-100 text-red-700',
                                'SP2' => 'bg-red-200 text-red-800',
                                'Pemanggilan Orang Tua' => 'bg-orange-100 text-orange-700',
                                'Wakil Kesiswaan' => 'bg-purple-100 text-purple-700',
                                'Selesai' => 'bg-green-100 text-green-700',
                            ];
                            $badge = $statusBadges[$kasus->status] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="text-sm font-medium px-2 py-1 rounded-full {{ $badge }}">
                            {{ $kasus->status ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Guru BK</p>
                        <p class="font-medium text-gray-800">{{ $kasus->guruBK->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Tanggal Kejadian</p>
                        <p class="font-medium text-gray-800">
                            {{ $kasus->tanggal_kejadian ? $kasus->tanggal_kejadian->format('d F Y') : '-' }}
                        </p>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-gray-400 text-xs mb-1">Deskripsi Kasus</p>
                    <p class="text-gray-800 text-sm leading-relaxed">{{ $kasus->deskripsi ?? '-' }}</p>
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
                    <i class="fas fa-history text-gray-400 mr-2"></i>
                    Riwayat Tindak Lanjut ({{ $kasus->tindakLanjuts->count() }})
                </h3>

                <div class="space-y-3 mb-4">
                    @forelse($kasus->tindakLanjuts->sortByDesc('tanggal') as $tl)
                    <div class="bg-gray-50 rounded-lg p-4 relative">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <span class="text-xs font-semibold text-gray-500">
                                        <i class="fas fa-user mr-1"></i>{{ $tl->user->name ?? '-' }}
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        <i class="fas fa-calendar-alt mr-1"></i>{{ $tl->tanggal ? $tl->tanggal->format('d M Y') : '-' }}
                                    </span>
                                    @php
                                        $tlBadges = [
                                            'Baru' => 'bg-blue-100 text-blue-700',
                                            'Diproses (Konseling)' => 'bg-yellow-100 text-yellow-700',
                                            'SP1' => 'bg-red-100 text-red-700',
                                            'SP2' => 'bg-red-200 text-red-800',
                                            'Pemanggilan Orang Tua' => 'bg-orange-100 text-orange-700',
                                            'Wakil Kesiswaan' => 'bg-purple-100 text-purple-700',
                                            'Selesai' => 'bg-green-100 text-green-700',
                                        ];
                                        $tlBadge = $tlBadges[$tl->status_setelah] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $tlBadge }}">
                                        <i class="fas fa-arrow-right mr-1"></i>{{ $tl->status_setelah }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 mt-1">{{ $tl->catatan }}</p>
                            </div>
                            @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('tindak-lanjut.destroy', $tl) }}"
                                  onsubmit="return confirm('Hapus catatan ini?')" class="flex-shrink-0 ml-2">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-600 text-xs p-1">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-comment text-4xl block mb-3 text-gray-300"></i>
                        <p class="text-sm">Belum ada tindak lanjut.</p>
                    </div>
                    @endforelse
                </div>

                @if(!auth()->user()->isPimpinan())
                <form method="POST" action="{{ route('tindak-lanjut.store', $kasus) }}"
                      class="border-t border-gray-100 pt-4 space-y-3">
                    @csrf
                    <p class="text-sm font-medium text-gray-700">
                        <i class="fas fa-plus-circle text-blue-600 mr-1"></i>
                        Tambah Tindak Lanjut
                    </p>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Update Status <span class="text-red-500">*</span></label>
                            <select name="status_setelah" required
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="Baru" {{ old('status_setelah', $kasus->status) == 'Baru' ? 'selected' : '' }}>Baru</option>
                                <option value="Diproses (Konseling)" {{ old('status_setelah', $kasus->status) == 'Diproses (Konseling)' ? 'selected' : '' }}>Diproses (Konseling)</option>
                                <option value="SP1" {{ old('status_setelah', $kasus->status) == 'SP1' ? 'selected' : '' }}>SP1</option>
                                <option value="SP2" {{ old('status_setelah', $kasus->status) == 'SP2' ? 'selected' : '' }}>SP2</option>
                                <option value="Pemanggilan Orang Tua" {{ old('status_setelah', $kasus->status) == 'Pemanggilan Orang Tua' ? 'selected' : '' }}>Pemanggilan Orang Tua</option>
                                <option value="Wakil Kesiswaan" {{ old('status_setelah', $kasus->status) == 'Wakil Kesiswaan' ? 'selected' : '' }}>Wakil Kesiswaan</option>
                                <option value="Selesai" {{ old('status_setelah', $kasus->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>
                    <textarea name="catatan" rows="3" required placeholder="Catatan tindak lanjut..."
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('catatan') }}</textarea>
                    <button type="submit"
                            class="bg-blue-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                        <i class="fas fa-save mr-1"></i> Simpan
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
                    <p class="font-medium text-gray-700 mb-1">
                        {{ $hv->tanggal_kunjungan ? $hv->tanggal_kunjungan->format('d M Y') : '-' }}
                        · Bertemu: {{ $hv->yang_ditemui }}
                    </p>
                    <p class="text-gray-500 text-xs mb-1">
                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $hv->alamat_kunjungan }}
                    </p>
                    <p class="text-gray-700">{{ $hv->hasil_kunjungan }}</p>
                    <p class="text-xs text-gray-400 mt-1">Oleh: {{ $hv->user->name ?? '-' }}</p>
                </div>
                @endforeach

                <form method="POST" action="{{ route('home-visit.store', $kasus) }}"
                      class="border-t border-gray-100 pt-4 space-y-3">
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
                    <button type="submit"
                            class="bg-green-700 text-white text-sm px-4 py-2 rounded-lg hover:bg-green-800 transition">
                        <i class="fas fa-home mr-1"></i> Simpan Home Visit
                    </button>
                </form>
            </div>
            @else
            {{-- Pimpinan: read only --}}
            @if($kasus->homeVisits->isNotEmpty())
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Home Visit ({{ $kasus->homeVisits->count() }})</h3>
                @foreach($kasus->homeVisits as $hv)
                <div class="bg-gray-50 rounded-lg p-4 mb-2 text-sm">
                    <p class="font-medium">
                        {{ $hv->tanggal_kunjungan ? $hv->tanggal_kunjungan->format('d M Y') : '-' }}
                        · Bertemu: {{ $hv->yang_ditemui }}
                    </p>
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
                @if($kasus->siswa)
                <div class="text-center mb-4">
                    @if($kasus->siswa->foto)
                    <img src="{{ asset('storage/' . $kasus->siswa->foto) }}"
                         class="w-16 h-16 rounded-full mx-auto object-cover mb-2">
                    @else
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="text-blue-700 font-bold text-xl">
                            {{ strtoupper(substr($kasus->siswa->nama, 0, 2)) }}
                        </span>
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
                        <a href="tel:{{ $kasus->siswa->no_hp_ortu }}" class="text-blue-600 font-medium">
                            {{ $kasus->siswa->no_hp_ortu }}
                        </a>
                    </div>
                    @endif
                </div>
                <a href="{{ route('siswa.show', $kasus->siswa) }}"
                   class="mt-4 block text-center text-sm text-blue-600 hover:underline">
                    Lihat Profil Lengkap →
                </a>
                @else
                <p class="text-gray-400 text-sm text-center py-4">Data siswa tidak ditemukan.</p>
                @endif
            </div>

            @if($kasus->siswa)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <p class="text-xs text-gray-400 mb-1">Total Kasus Siswa Ini</p>
                <p class="text-3xl font-bold text-gray-900">{{ $kasus->siswa->kasuses()->count() }}</p>
                <a href="{{ route('kasus.index') }}?search={{ $kasus->siswa->nis }}"
                   class="text-xs text-blue-500 hover:underline">Lihat semua kasusnya</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection