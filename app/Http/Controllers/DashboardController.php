<?php
namespace App\Http\Controllers;

use App\Models\Kasus;
use App\Models\Siswa;
use App\Models\User;
use App\Models\KategoriKasus;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller {
    public function index() {
        $user = auth()->user();

        $kasusQuery = Kasus::query();
        if ($user->isGuruBK() && $user->kelas !== 'semua') {
            $kasusQuery->whereHas('siswa', fn($q) => $q->where('kelas', 'like', $user->kelas . '%'));
        }

        $stats = [
            'total_kasus' => (clone $kasusQuery)->count(),
            'kasus_baru' => (clone $kasusQuery)->where('status', 'Baru')->count(),
            'kasus_proses' => (clone $kasusQuery)->whereNotIn('status', ['Baru', 'Selesai'])->count(),
            'kasus_selesai' => (clone $kasusQuery)->where('status', 'Selesai')->count(),
            'total_siswa' => Siswa::where('is_active', true)->count(),
        ];

        $kasusRecent = (clone $kasusQuery)
            ->with(['siswa', 'kategori', 'guruBK'])
            ->latest()
            ->take(10)
            ->get();

        $kasusPerKategori = KategoriKasus::withCount(['kasuses' => function($q) use ($kasusQuery) {
            // Filter sama
        }])->get();

        return view('dashboard', compact('stats', 'kasusRecent', 'kasusPerKategori', 'user'));
    }

    public function statistik() {
        $kasusPerBulan = Kasus::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $kasusPerStatus = Kasus::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $kasusPerKategori = KategoriKasus::withCount('kasuses')->get();

        return view('statistik', compact('kasusPerBulan', 'kasusPerStatus', 'kasusPerKategori'));
    }
}