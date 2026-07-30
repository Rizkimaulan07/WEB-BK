@extends('layouts.app')
@section('title', 'Edit Kasus')
@section('content')
<div class="py-4 max-w-2xl">
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('kasus.show', $kasus) }}" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-lg font-semibold text-gray-800">Edit Kasus</h2>
    </div>

    <form method="POST" action="{{ route('kasus.update', $kasus) }}" 
          class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-5">
        @csrf @method('PUT')

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- Siswa --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Siswa <span class="text-red-500">*</span></label>
            <select name="siswa_id" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Siswa --</option>
                @foreach($siswas as $s)
                <option value="{{ $s->id }}" 
                        {{ old('siswa_id', $kasus->siswa_id) == $s->id ? 'selected' : '' }}>
                    {{ $s->nama }} ({{ $s->nis }}) - {{ $s->kelas }} {{ $s->jurusan }}
                </option>
                @endforeach
            </select>
            @error('siswa_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Kategori --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
            <select name="kategori_id" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $k)
                <option value="{{ $k->id }}" 
                        {{ old('kategori_id', $kasus->kategori_id) == $k->id ? 'selected' : '' }}
                        style="color: {{ $k->warna ?? '#000' }}">
                    {{ $k->nama }}
                </option>
                @endforeach
            </select>
            @error('kategori_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Tanggal Kejadian --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kejadian <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal_kejadian" required 
                   value="{{ old('tanggal_kejadian', $kasus->tanggal_kejadian?->format('Y-m-d') ?? date('Y-m-d')) }}"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('tanggal_kejadian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kasus <span class="text-red-500">*</span></label>
            <textarea name="deskripsi" rows="4" required
                      class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Jelaskan kejadian secara detail...">{{ old('deskripsi', $kasus->deskripsi) }}</textarea>
            @error('deskripsi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Status --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
            <select name="status" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Baru" {{ old('status', $kasus->status) == 'Baru' ? 'selected' : '' }}>Baru</option>
                <option value="Diproses (Konseling)" {{ old('status', $kasus->status) == 'Diproses (Konseling)' ? 'selected' : '' }}>Diproses (Konseling)</option>
                <option value="SP1" {{ old('status', $kasus->status) == 'SP1' ? 'selected' : '' }}>SP1</option>
                <option value="SP2" {{ old('status', $kasus->status) == 'SP2' ? 'selected' : '' }}>SP2</option>
                <option value="Pemanggilan Orang Tua" {{ old('status', $kasus->status) == 'Pemanggilan Orang Tua' ? 'selected' : '' }}>Pemanggilan Orang Tua</option>
                <option value="Wakil Kesiswaan" {{ old('status', $kasus->status) == 'Wakil Kesiswaan' ? 'selected' : '' }}>Wakil Kesiswaan</option>
                <option value="Selesai" {{ old('status', $kasus->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Keterangan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
            <textarea name="keterangan" rows="3"
                      class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Tambahkan keterangan tambahan...">{{ old('keterangan', $kasus->keterangan) }}</textarea>
            @error('keterangan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3 pt-2 border-t border-gray-100">
            <button type="submit"
                    class="bg-blue-900 text-white px-5 py-2.5 rounded-lg text-sm hover:bg-blue-800 transition font-medium">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
            </button>
            <a href="{{ route('kasus.show', $kasus) }}"
               class="px-5 py-2.5 rounded-lg text-sm border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection