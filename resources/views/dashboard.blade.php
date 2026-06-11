@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="py-4 space-y-6">
    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        @php
        $cards = [
            ['label' => 'Total Kasus', 'value' => $stats['total_kasus'], 'icon' => 'fa-folder-open', 'color' => 'blue'],
            ['label' => 'Kasus Baru', 'value' => $stats['kasus_baru'], 'icon' => 'fa-exclamation-circle', 'color' => 'yellow'],
            ['label' => 'Sedang Proses', 'value' => $stats['kasus_proses'], 'icon' => 'fa-spinner', 'color' => 'purple'],
            ['label' => 'Selesai', 'value' => $stats['kasus_selesai'], 'icon' => 'fa-check-circle', 'color' => 'green'],
            ['label' => 'Total Siswa', 'value' => $stats['total_siswa'], 'icon' => 'fa-users', 'color' => 'indigo'],
        ];
        $colorMap = ['blue'=>'bg-blue-50 text-blue-700 border-blue-200','yellow'=>'bg-yellow-50 text-yellow-700 border-yellow-200','purple'=>'bg-purple-50 text-purple-700 border-purple-200','green'=>'bg-green-50 text-green-700 border-green-200','indigo'=>'bg-indigo-50 text-indigo-700 border-indigo-200'];
        @endphp
        @foreach($cards as $card)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg {{ explode(' ', $colorMap[$card['color']])[0] }} flex items-center justify-center flex-shrink-0">
                <i class="fas {{ $card['icon'] }} {{ explode(' ', $colorMap[$card['color']])[1] }}"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
                <p class="text-xs text-gray-500">{{ $card['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Recent Kasus Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Kasus Terbaru</h2>
            <a href="{{ route('kasus.index') }}" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Siswa</th>
                        <th class="px-5 py-3 text-left">Kategori</th>
                        <th class="px-5 py-3 text-left">Tanggal</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kasusRecent as $k)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3">
                            <a href="{{ route('kasus.show', $k) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                {{ $k->siswa->nama }}
                            </a>
                            <p class="text-xs text-gray-400">{{ $k->siswa->kelas }} · {{ $k->siswa->nis }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full"
                                  style="background: {{ $k->kategori->warna }}20; color: {{ $k->kategori->warna }}">
                                {{ $k->kategori->nama }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $k->tanggal_kejadian->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-medium px-2 py-1 rounded-full {{ $k->status_badge }}">
                                {{ $k->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $k->guruBK->name }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada kasus tercatat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection