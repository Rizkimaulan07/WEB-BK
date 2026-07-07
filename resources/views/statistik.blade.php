@extends('layouts.app')
@section('title', 'Statistik Kasus')
@section('content')
<div class="py-4 space-y-6">
    <h2 class="text-lg font-semibold text-gray-800">Statistik Kasus</h2>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Chart Kasus per Bulan --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">Kasus per Bulan (Tahun {{ date('Y') }})</h3>
            <canvas id="chartBulan" height="250"></canvas>
        </div>

        {{-- Chart Kasus per Status --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">Kasus per Status</h3>
            <canvas id="chartStatus" height="250"></canvas>
        </div>
    </div>

    {{-- Tabel Kasus per Kategori --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Kasus per Kategori</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Kategori</th>
                        <th class="px-5 py-3 text-left">Jumlah Kasus</th>
                        <th class="px-5 py-3 text-left">Warna</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kasusPerKategori as $k)
                    <tr>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $k->nama }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                {{ $k->kasuses_count }} kasus
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full" style="background: {{ $k->warna }}"></div>
                                <span class="text-xs text-gray-500">{{ $k->warna }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-5 py-10 text-center text-gray-400">Belum ada data kategori.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // =============================================
    // Chart Kasus per Bulan
    // =============================================
    const ctxBulan = document.getElementById('chartBulan').getContext('2d');
    const bulanData = @json($kasusPerBulan);
    
    const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const bulanValues = Array(12).fill(0);
    
    // Isi data dari database
    bulanData.forEach(item => {
        bulanValues[item.bulan - 1] = item.total;
    });
    
    new Chart(ctxBulan, {
        type: 'bar',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Jumlah Kasus',
                data: bulanValues,
                backgroundColor: '#3B82F6',
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { 
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // =============================================
    // Chart Kasus per Status
    // =============================================
    const ctxStatus = document.getElementById('chartStatus').getContext('2d');
    const statusData = @json($kasusPerStatus);
    
    const statusLabels = Object.keys(statusData);
    const statusValues = Object.values(statusData);
    
    const statusColors = {
        'Baru': '#3B82F6',
        'Diproses': '#F59E0B',
        'Konseling': '#8B5CF6',
        'Pemanggilan Orang Tua': '#F97316',
        'Pembinaan': '#EF4444',
        'Selesai': '#10B981'
    };
    
    const backgroundColors = statusLabels.map(label => statusColors[label] || '#6B7280');
    
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusValues,
                backgroundColor: backgroundColors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            },
            cutout: '60%'
        }
    });

});
</script>
@endpush
@endsection