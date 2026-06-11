@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
<div class="py-4 max-w-2xl">
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('users.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
        <h2 class="text-lg font-semibold text-gray-800">Edit User</h2>
    </div>

    <form method="POST" action="{{ route('users.update', $user) }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
            <input type="text" name="name" required value="{{ old('name', $user->name) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" required value="{{ old('email', $user->email) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="guru_bk" {{ $user->role == 'guru_bk' ? 'selected' : '' }}>Guru BK</option>
                    <option value="pimpinan" {{ $user->role == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas Akses</label>
                <select name="kelas" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <option value="semua" {{ $user->kelas == 'semua' ? 'selected' : '' }}>Semua Kelas</option>
                    <option value="10" {{ $user->kelas == '10' ? 'selected' : '' }}>Kelas 10</option>
                    <option value="11" {{ $user->kelas == '11' ? 'selected' : '' }}>Kelas 11</option>
                    <option value="12" {{ $user->kelas == '12' ? 'selected' : '' }}>Kelas 12</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="is_active" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-900 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-800">Update</button>
            <a href="{{ route('users.index') }}" class="px-5 py-2 rounded-lg text-sm border border-gray-200 text-gray-600 hover:bg-gray-50">Batal</a>
        </div>
    </form>
</div>
@endsection