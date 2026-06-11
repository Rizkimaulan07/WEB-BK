@extends('layouts.app')
@section('title', 'Tambah User')
@section('content')
<div class="py-4 max-w-2xl">
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('users.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
        <h2 class="text-lg font-semibold text-gray-800">Tambah User Baru</h2>
    </div>

    <form method="POST" action="{{ route('users.store') }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
        @csrf
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
            <input type="text" name="name" required value="{{ old('name') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" required value="{{ old('email') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                <select name="role" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <option value="">Pilih Role</option>
                    <option value="admin">Admin</option>
                    <option value="guru_bk">Guru BK</option>
                    <option value="pimpinan">Pimpinan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas Akses <span class="text-red-500">*</span></label>
                <select name="kelas" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <option value="semua">Semua Kelas</option>
                    <option value="10">Kelas 10</option>
                    <option value="11">Kelas 11</option>
                    <option value="12">Kelas 12</option>
                </select>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-900 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-800">Simpan</button>
            <a href="{{ route('users.index') }}" class="px-5 py-2 rounded-lg text-sm border border-gray-200 text-gray-600 hover:bg-gray-50">Batal</a>
        </div>
    </form>
</div>
@endsection