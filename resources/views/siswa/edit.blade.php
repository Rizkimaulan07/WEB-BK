@extends('layouts.app')
@section('title', 'Edit Siswa')
@section('content')
<div class="py-4 max-w-2xl">
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('siswa.show', $siswa) }}" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-lg font-semibold text-gray-800">Edit Data Siswa</h2>
    </div>

    <form method="POST" action="{{ route('siswa.update', $siswa) }}" enctype="multipart/form-data"
          class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-5">
        @csrf @method('PUT')

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- Foto --}}
        <div class="flex items-center gap-5" x-data="{ preview: null }">
            <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center overflow-hidden flex-shrink-0 border-2 border-blue-200">
                <img x-show="preview" :src="preview" class="w-full h-full object-cover">
                @if($siswa->foto)
                <img x-show="!preview" src="{{ asset('storage/'.$siswa->foto) }}" class="w-full h-full object-cover">
                @else
                <i x-show="!preview" class="fas fa-user text-blue-400 text-2xl"></i>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Siswa</label>
                <input type="file" name="foto" accept="image/*"
                       @change="preview = URL.createObjectURL($event.target.files[0])"
                       class="text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah foto.</p>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Data Identitas</p>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" required value="{{ old('nama', $siswa->nama) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIS <span class="text-red-500">*</span></label>
                    <input type="text" name="nis" required value="{{ old('nis', $siswa->nis) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('nis')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Data Akademik</p>
            @php
                $kelasVal   = old('kelas', $siswa->kelas);
                $kelasParts = explode(' ', $kelasVal);
                $kelasAngkaVal   = $kelasParts[0] ?? '';
                $jurusanVal = $kelasParts[1] ?? $siswa->jurusan ?? '';
            @endphp
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas <span class="text-red-500">*</span></label>
                    <select name="kelas_angka" required id="kelasAngka"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="10" {{ $kelasAngkaVal == '10' ? 'selected' : '' }}>Kelas 10</option>
                        <option value="11" {{ $kelasAngkaVal == '11' ? 'selected' : '' }}>Kelas 11</option>
                        <option value="12" {{ $kelasAngkaVal == '12' ? 'selected' : '' }}>Kelas 12</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan <span class="text-red-500">*</span></label>
                    <select name="jurusan" required id="jurusanSelect"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="PPLG" {{ $jurusanVal == 'PPLG' ? 'selected' : '' }}>PPLG</option>
                        <option value="TJKT" {{ $jurusanVal == 'TJKT' ? 'selected' : '' }}>TJKT</option>
                        <option value="AKL"  {{ $jurusanVal == 'AKL'  ? 'selected' : '' }}>AKL</option>
                        <option value="AXIO" {{ $jurusanVal == 'AXIO' ? 'selected' : '' }}>AXIO</option>
                    </select>
                    @error('jurusan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Angkatan <span class="text-red-500">*</span></label>
                    <input type="text" name="angkatan" required value="{{ old('angkatan', $siswa->angkatan) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <input type="hidden" name="kelas" id="kelasGabung" value="{{ $kelasVal }}">
            @error('kelas')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Kontak & Alamat</p>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('alamat', $siswa->alamat) }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. HP Siswa</label>
                        <input type="text" name="no_hp_siswa" value="{{ old('no_hp_siswa', $siswa->no_hp_siswa) }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. HP Orang Tua</label>
                        <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu', $siswa->no_hp_ortu) }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Orang Tua / Wali</label>
                    <input type="text" name="nama_ortu" value="{{ old('nama_ortu', $siswa->nama_ortu) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <label class="flex items-center gap-2 cursor-pointer w-fit">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $siswa->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 rounded text-blue-600">
                <span class="text-sm text-gray-700">Siswa masih aktif</span>
            </label>
        </div>

        <div class="flex gap-3 pt-2 border-t border-gray-100">
            <button type="submit"
                    class="bg-blue-900 text-white px-5 py-2.5 rounded-lg text-sm hover:bg-blue-800 transition font-medium">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
            </button>
            <a href="{{ route('siswa.show', $siswa) }}"
               class="px-5 py-2.5 rounded-lg text-sm border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                Batal
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const kelasAngka    = document.getElementById('kelasAngka');
    const jurusanSelect = document.getElementById('jurusanSelect');
    const kelasGabung   = document.getElementById('kelasGabung');

    function updateKelas() {
        const k = kelasAngka.value;
        const j = jurusanSelect.value;
        kelasGabung.value = (k && j) ? k + ' ' + j : '';
    }

    kelasAngka.addEventListener('change', updateKelas);
    jurusanSelect.addEventListener('change', updateKelas);
</script>
@endpush
@endsection