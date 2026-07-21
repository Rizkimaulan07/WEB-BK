@extends('layouts.app')
@section('title', 'Tambah User')
@section('content')
<div class="py-4 max-w-md">
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('users.index') }}" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-lg font-semibold text-gray-800">Tambah User Baru</h2>
    </div>

    <form method="POST" action="{{ route('users.store') }}"
          class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
        @csrf

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="name" required value="{{ old('name') }}"
                   placeholder="Nama lengkap user"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" required value="{{ old('email') }}"
                   placeholder="email@domain.com"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
            <input type="password" name="password" required
                   placeholder="Minimal 8 karakter"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter</p>
            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
            <select name="role" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="guru_bk" {{ old('role') == 'guru_bk' ? 'selected' : '' }}>Guru BK</option>
                <option value="pimpinan" {{ old('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
            </select>
            @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="w-4 h-4 rounded text-blue-600">
                <span class="text-sm text-gray-700">User Aktif</span>
            </label>
        </div>

        <div class="flex gap-3 pt-2 border-t border-gray-100">
            <button type="submit"
                    class="bg-blue-900 text-white px-5 py-2.5 rounded-lg text-sm hover:bg-blue-800 transition font-medium">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>
            <a href="{{ route('users.index') }}"
               class="px-5 py-2.5 rounded-lg text-sm border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection