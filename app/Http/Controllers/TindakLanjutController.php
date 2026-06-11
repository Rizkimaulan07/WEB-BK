<?php
namespace App\Http\Controllers;

use App\Models\Kasus;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;

class TindakLanjutController extends Controller {
    public function store(Request $request, Kasus $kasus) {
        abort_if(auth()->user()->isPimpinan(), 403);
        $data = $request->validate([
            'tanggal' => 'required|date',
            'catatan' => 'required|string',
            'status_setelah' => 'required|in:Baru,Diproses,Konseling,Pemanggilan Orang Tua,Pembinaan,Selesai',
        ]);
        $data['kasus_id'] = $kasus->id;
        $data['user_id'] = auth()->id();
        TindakLanjut::create($data);
        $kasus->update(['status' => $data['status_setelah']]);
        return back()->with('success', 'Tindak lanjut berhasil ditambahkan.');
    }

    public function destroy(TindakLanjut $tindakLanjut) {
        abort_if(!auth()->user()->isAdmin(), 403);
        $tindakLanjut->delete();
        return back()->with('success', 'Tindak lanjut berhasil dihapus.');
    }
}