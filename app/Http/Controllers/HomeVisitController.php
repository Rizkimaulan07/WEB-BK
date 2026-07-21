<?php

namespace App\Http\Controllers;

use App\Models\Kasus;
use App\Models\HomeVisit;
use Illuminate\Http\Request;

class HomeVisitController extends Controller
{
    public function store(Request $request, Kasus $kasus)
    {
        // Cek permission
        if (auth()->user()->isPimpinan()) {
            abort(403, 'Pimpinan tidak dapat menambah home visit.');
        }

        // Validasi
        $request->validate([
            'tanggal_kunjungan' => 'required|date',
            'yang_ditemui' => 'required|string|max:255',
            'alamat_kunjungan' => 'required|string|max:255',
            'hasil_kunjungan' => 'required|string',
        ]);

        // Simpan
        HomeVisit::create([
            'kasus_id' => $kasus->id,
            'user_id' => auth()->id(),
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'yang_ditemui' => $request->yang_ditemui,
            'alamat_kunjungan' => $request->alamat_kunjungan,
            'hasil_kunjungan' => $request->hasil_kunjungan,
        ]);

        return redirect()->route('kasus.show', $kasus)
            ->with('success', 'Home Visit berhasil ditambahkan!');
    }

    public function destroy(HomeVisit $homeVisit)
    {
        // Cek permission
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat menghapus home visit.');
        }

        $kasusId = $homeVisit->kasus_id;    
        $homeVisit->delete();

        return redirect()->route('kasus.show', $kasusId)
            ->with('success', 'Home Visit berhasil dihapus!');
    }
}