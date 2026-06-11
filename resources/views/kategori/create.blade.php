@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('content')
<div class="py-4 max-w-2xl">
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('kategori.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
        <h2 class="text-lg font-semibold text-gray-800">Tambah Kategori Kasus</h2>
    </div>

    <form method="POST" action="{{ route('kategori.store') }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
        @csrf
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
            <input type="text" name="nama" required value="{{ old('nama') }}" placeholder="Contoh: Disiplin, Akademik, Bullying" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Warna (HEX) <span class="text-red-500">*</span></label>
            <div class="flex gap-3 items-center">
                <input type="color" name="warna" required value="{{ old('warna', '#3B82F6') }}" class="w-16 h-10 border border-gray-200 rounded-lg cursor-pointer">
                <input type="text" name="warna_text" value="{{ old('warna', '#3B82F6') }}" class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm" readonly>
                <div class="w-10 h-10 rounded-full border" id="previewWarna" style="background: {{ old('warna', '#3B82F6') }}"></div>
            </div>
            <p class="text-xs text-gray-400 mt-1">Pilih warna untuk badge kategori kasus.</p>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-900 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-800">Simpan</button>
            <a href="{{ route('kategori.index') }}" class="px-5 py-2 rounded-lg text-sm border border-gray-200 text-gray-600 hover:bg-gray-50">Batal</a>
        </div>
    </form>
</div>

<script>
    const colorInput = document.querySelector('input[name="warna"]');
    const textInput = document.querySelector('input[name="warna_text"]');
    const preview = document.getElementById('previewWarna');
    
    colorInput.addEventListener('change', function() {
        textInput.value = this.value;
        preview.style.background = this.value;
    });
</script>
@endsection