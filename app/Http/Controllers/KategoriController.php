<?php

namespace App\Http\Controllers;

use App\Models\KategoriKasus;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = KategoriKasus::latest()->paginate(10);
        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100|unique:kategori_kasuses,nama',
            'warna' => 'required|string|max:20',
        ]);

        KategoriKasus::create($data);

        return redirect()->route('kategori.index')->with('success', 'Kategori kasus berhasil ditambahkan.');
    }

    public function edit(KategoriKasus $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, KategoriKasus $kategori)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100|unique:kategori_kasuses,nama,' . $kategori->id,
            'warna' => 'required|string|max:20',
        ]);

        $kategori->update($data);

        return redirect()->route('kategori.index')->with('success', 'Kategori kasus berhasil diperbarui.');
    }

    public function destroy(KategoriKasus $kategori)
    {
        // Cek apakah kategori sudah digunakan di kasus
        if ($kategori->kasuses()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena sudah digunakan pada kasus.');
        }

        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori kasus berhasil dihapus.');
    }
}