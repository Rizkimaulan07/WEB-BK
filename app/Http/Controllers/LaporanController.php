<?php

namespace App\Http\Controllers;

use App\Models\Kasus;
use App\Models\Siswa;
use App\Models\KategoriKasus;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KasusExport;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kasus::with(['siswa', 'kategori', 'guruBK']);

        // Filter
        if ($request->dari) {
            $query->whereDate('tanggal_kejadian', '>=', $request->dari);
        }
        if ($request->sampai) {
            $query->whereDate('tanggal_kejadian', '<=', $request->sampai);
        }
        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $kasus = $query->latest()->get();
        $kategoris = KategoriKasus::all();
        $statuses = ['Baru', 'Diproses', 'Konseling', 'Pemanggilan Orang Tua', 'Pembinaan', 'Selesai'];

        // Statistik
        $totalKasus = $kasus->count();
        $kasusPerKategori = $kasus->groupBy('kategori.nama')->map->count();
        $kasusPerStatus = $kasus->groupBy('status')->map->count();

        return view('laporan.index', compact('kasus', 'kategoris', 'statuses', 'totalKasus', 'kasusPerKategori', 'kasusPerStatus'));
    }

    public function exportPdf(Request $request)
    {
        $query = Kasus::with(['siswa', 'kategori', 'guruBK']);

        if ($request->dari) {
            $query->whereDate('tanggal_kejadian', '>=', $request->dari);
        }
        if ($request->sampai) {
            $query->whereDate('tanggal_kejadian', '<=', $request->sampai);
        }
        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $kasus = $query->latest()->get();
        $totalKasus = $kasus->count();

        $pdf = Pdf::loadView('laporan.pdf', compact('kasus', 'totalKasus'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('laporan-kasus-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new KasusExport($request), 'laporan-kasus-' . date('Y-m-d') . '.xlsx');
    }
}