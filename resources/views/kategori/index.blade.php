@extends('layouts.app')
@section('title', 'Kategori Kasus')
@section('content')
<div class="py-4 space-y-4">
    <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold text-gray-800">Kategori Kasus</h2>
        <a href="{{ route('kategori.create') }}" class="inline-flex items-center gap-2 bg-blue-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-800 transition">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">No</th>
                        <th class="px-5 py-3 text-left">Nama Kategori</th>
                        <th class="px-5 py-3 text-left">Warna</th>
                        <th class="px-5 py-3 text-left">Preview</th>
                        <th class="px-5 py-3 text-left">Jumlah Kasus</th>
                        <th class="px-5 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kategoris as $k)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-gray-400">{{ $kategoris->firstItem() + $loop->index }}</td>
                        <td class="px-5 py-3 font-medium text-gray-900">{{ $k->nama }}</td>
                        <td class="px-5 py-3">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $k->warna }}</code>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs text-white" style="background: {{ $k->warna }}">
                                {{ $k->nama }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                {{ $k->kasuses_count ?? 0 }} kasus
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('kategori.edit', $k) }}" class="text-yellow-600 hover:text-yellow-800 mr-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('kategori.destroy', $k) }}" class="inline" onsubmit="return confirm('Hapus kategori {{ $k->nama }}?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                            <i class="fas fa-tags text-3xl mb-2 block"></i>Belum ada kategori kasus.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kategoris->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $kategoris->links() }}</div>
        @endif
    </div>
</div>
@endsection