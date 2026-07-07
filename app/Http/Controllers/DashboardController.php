<?php

namespace App\Http\Controllers;

use App\Models\Kasus;
use App\Models\Siswa;
use App\Models\KategoriKasus;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Query kasus
        $kasusQuery = Kasus::query();

        // Statistik
        $stats = [
            'total_kasus' => $kasusQuery->count(),
            'kasus_baru' => (clone $kasusQuery)->where('status', 'Baru')->count(),
            'kasus_proses' => (clone $kasusQuery)->whereIn('status', ['Diproses', 'Konseling', 'Pemanggilan Orang Tua', 'Pembinaan'])->count(),
            'kasus_selesai' => (clone $kasusQuery)->where('status', 'Selesai')->count(),
            'total_siswa' => Siswa::where('is_active', true)->count(),
        ];

        // Kasus terbaru (5 terakhir)
        $kasusRecent = (clone $kasusQuery)
            ->with(['siswa', 'kategori', 'guruBK'])
            ->latest()
            ->take(5)
            ->get();

        // Data kategori kasus
        $kategoriKasus = KategoriKasus::withCount('kasuses')->get();

        // Data kasus per bulan untuk chart
        $kasusPerBulan = (clone $kasusQuery)
            ->selectRaw('MONTH(tanggal_kejadian) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_kejadian', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $bulanData = array_fill(0, 12, 0);
        foreach ($kasusPerBulan as $item) {
            $bulanData[$item->bulan - 1] = $item->total;
        }

        // Data status untuk chart donut
        $statusLabels = ['Baru', 'Proses', 'Selesai'];
        $statusData = [
            (clone $kasusQuery)->where('status', 'Baru')->count(),
            (clone $kasusQuery)->whereIn('status', ['Diproses', 'Konseling', 'Pemanggilan Orang Tua', 'Pembinaan'])->count(),
            (clone $kasusQuery)->where('status', 'Selesai')->count(),
        ];
        $statusColors = ['#3B82F6', '#F59E0B', '#10B981'];

        return view('dashboard', compact(
            'stats',
            'kasusRecent',
            'kategoriKasus',
            'bulanLabels',
            'bulanData',
            'statusLabels',
            'statusData',
            'statusColors'
        ));
    }

    public function statistik()
    {
        // Hanya admin & pimpinan
        if (!auth()->user()->isAdmin() && !auth()->user()->isPimpinan()) {
            abort(403, 'Hanya Admin dan Pimpinan yang dapat melihat statistik.');
        }

        $totalKasus = Kasus::count();
        $totalSiswa = Siswa::where('is_active', true)->count();

        // Kasus per kategori
        $kasusPerKategori = KategoriKasus::withCount('kasuses')->get();

        // Kasus per bulan
        $kasusPerBulan = Kasus::selectRaw('MONTH(tanggal_kejadian) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_kejadian', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // ===== KASUS PER STATUS (UNTUK CHART) =====
        $kasusPerStatus = [
            'Baru' => Kasus::where('status', 'Baru')->count(),
            'Diproses' => Kasus::where('status', 'Diproses')->count(),
            'Konseling' => Kasus::where('status', 'Konseling')->count(),
            'Pemanggilan Orang Tua' => Kasus::where('status', 'Pemanggilan Orang Tua')->count(),
            'Pembinaan' => Kasus::where('status', 'Pembinaan')->count(),
            'Selesai' => Kasus::where('status', 'Selesai')->count(),
        ];

        // Hapus status yang nilainya 0
        $kasusPerStatus = array_filter($kasusPerStatus, function($value) {
            return $value > 0;
        });

        // Status count (ringkasan)
        $statusCount = [
            'Baru' => Kasus::where('status', 'Baru')->count(),
            'Proses' => Kasus::whereIn('status', ['Diproses', 'Konseling', 'Pemanggilan Orang Tua', 'Pembinaan'])->count(),
            'Selesai' => Kasus::where('status', 'Selesai')->count(),
        ];

        // Top 5 siswa dengan kasus terbanyak
        $topSiswa = Siswa::withCount('kasuses')
            ->orderBy('kasuses_count', 'desc')
            ->take(5)
            ->get();

        return view('statistik', compact(
            'totalKasus',
            'totalSiswa',
            'kasusPerKategori',
            'kasusPerBulan',
            'kasusPerStatus',
            'statusCount',
            'topSiswa'
        ));
    }
}