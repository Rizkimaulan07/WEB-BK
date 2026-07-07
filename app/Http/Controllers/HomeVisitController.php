<?php

namespace App\Http\Controllers;

use App\Models\Kasus;
use App\Models\HomeVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeVisitController extends Controller
{
    public function store(Request $request, Kasus $kasus)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isBK()) {
            abort(403);
        }

        $request->validate([
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'hasil' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->all();
        $data['kasus_id'] = $kasus->id;
        $data['user_id'] = auth()->id();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('home_visit_foto', 'public');
            $data['foto'] = $path;
        }

        HomeVisit::create($data);

        return redirect()->route('kasus.show', $kasus)
            ->with('success', 'Home Visit berhasil dicatat!');
    }

    public function destroy(HomeVisit $homeVisit)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($homeVisit->foto) {
            Storage::disk('public')->delete($homeVisit->foto);
        }

        $kasusId = $homeVisit->kasus_id;
        $homeVisit->delete();

        return redirect()->route('kasus.show', $kasusId)
            ->with('success', 'Home Visit berhasil dihapus!');
    }
}