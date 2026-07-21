<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query();

        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_panggilan', 'like', '%' . $request->search . '%');
        }

        if ($request->kelas) {
            $query->where('kelas', 'like', $request->kelas . '%');
        }

        if ($request->jurusan) {
            $query->where('jurusan', $request->jurusan);
        }

        if ($request->status) {
            $query->where('is_active', $request->status == 'aktif' ? 1 : 0);
        }

        $siswas = $query->withCount('kasuses')->orderBy('nama')->paginate(15)->withQueryString();
        return view('siswa.index', compact('siswas'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat menambah data siswa.');
        }
        
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat menambah data siswa.');
        }

        $data = $request->validate([
            // A. Informasi Siswa
            'nis' => 'required|unique:siswas,nis',
            'nama' => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'anak_ke' => 'nullable|integer|min:1',
            'jumlah_saudara' => 'nullable|integer|min:0',
            'usia' => 'nullable|integer|min:1|max:100',
            'tinggal_bersama' => 'nullable|string|max:50',
            'kelas' => 'required|string|max:20',
            'jurusan' => 'nullable|string|max:50',
            'angkatan' => 'required|string|max:10',
            'alamat' => 'nullable|string',
            'transportasi' => 'nullable|string|max:50',
            'no_hp_siswa' => 'nullable|string|max:20',
            'no_hp_ortu' => 'nullable|string|max:20',
            'nama_ortu' => 'nullable|string|max:255',
            
            // B. Kondisi Kesehatan
            'golongan_darah' => 'nullable|in:A,B,O,AB',
            'alergi' => 'nullable|boolean',
            'alergi_detail' => 'nullable|string|max:255',
            'penyakit_jantung' => 'nullable|boolean',
            'tuberculosis' => 'nullable|boolean',
            'asma' => 'nullable|boolean',
            'kondisi_mata' => 'nullable|string|max:50',
            'minus_kanan' => 'nullable|string|max:10',
            'minus_kiri' => 'nullable|string|max:10',
            'silinder_kanan' => 'nullable|string|max:10',
            'silinder_kiri' => 'nullable|string|max:10',
            'buta_warna' => 'nullable|in:normal,partial,total',
            'penyakit_lain' => 'nullable|string',
            
            // C. Informasi Ayah
            'ayah_nama' => 'nullable|string|max:255',
            'ayah_usia' => 'nullable|integer|min:1|max:120',
            'ayah_status_perkawinan' => 'nullable|string|max:50',
            'ayah_pendidikan' => 'nullable|string|max:50',
            'ayah_pekerjaan' => 'nullable|string|max:100',
            'ayah_penghasilan' => 'nullable|numeric|min:0',
            'ayah_tanggungan' => 'nullable|integer|min:0',
            'ayah_status_tempat_tinggal' => 'nullable|string|max:50',
            
            // D. Informasi Ibu
            'ibu_nama' => 'nullable|string|max:255',
            'ibu_usia' => 'nullable|integer|min:1|max:120',
            'ibu_status_perkawinan' => 'nullable|string|max:50',
            'ibu_pendidikan' => 'nullable|string|max:50',
            'ibu_pekerjaan' => 'nullable|string|max:100',
            'ibu_penghasilan' => 'nullable|numeric|min:0',
            'ibu_tanggungan' => 'nullable|integer|min:0',
            'ibu_status_tempat_tinggal' => 'nullable|string|max:50',
            
            // E. Informasi Wali
            'wali_nama' => 'nullable|string|max:255',
            'wali_usia' => 'nullable|integer|min:1|max:120',
            'wali_status_perkawinan' => 'nullable|string|max:50',
            'wali_pendidikan' => 'nullable|string|max:50',
            'wali_pekerjaan' => 'nullable|string|max:100',
            'wali_penghasilan' => 'nullable|numeric|min:0',
            'wali_tanggungan' => 'nullable|integer|min:0',
            'wali_status_tempat_tinggal' => 'nullable|string|max:50',
            
            // Foto & Status
            'is_active' => 'nullable|boolean',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Handle checkbox boolean
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['alergi'] = $request->has('alergi') ? 1 : 0;
        $data['penyakit_jantung'] = $request->has('penyakit_jantung') ? 1 : 0;
        $data['tuberculosis'] = $request->has('tuberculosis') ? 1 : 0;
        $data['asma'] = $request->has('asma') ? 1 : 0;

        // Handle foto
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('siswa_foto', 'public');
            $data['foto'] = $path;
        }

        Siswa::create($data);

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function show(Siswa $siswa)
    {
        $kasuses = $siswa->kasuses()
            ->with(['kategori', 'guruBK', 'tindakLanjuts'])
            ->latest()
            ->get();
        
        return view('siswa.show', compact('siswa', 'kasuses'));
    }

    public function edit(Siswa $siswa)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat mengedit data siswa.');
        }

        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat mengedit data siswa.');
        }

        $data = $request->validate([
            // A. Informasi Siswa
            'nis' => 'required|unique:siswas,nis,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'anak_ke' => 'nullable|integer|min:1',
            'jumlah_saudara' => 'nullable|integer|min:0',
            'usia' => 'nullable|integer|min:1|max:100',
            'tinggal_bersama' => 'nullable|string|max:50',
            'kelas' => 'required|string|max:20',
            'jurusan' => 'nullable|string|max:50',
            'angkatan' => 'required|string|max:10',
            'alamat' => 'nullable|string',
            'transportasi' => 'nullable|string|max:50',
            'no_hp_siswa' => 'nullable|string|max:20',
            'no_hp_ortu' => 'nullable|string|max:20',
            'nama_ortu' => 'nullable|string|max:255',
            
            // B. Kondisi Kesehatan
            'golongan_darah' => 'nullable|in:A,B,O,AB',
            'alergi' => 'nullable|boolean',
            'alergi_detail' => 'nullable|string|max:255',
            'penyakit_jantung' => 'nullable|boolean',
            'tuberculosis' => 'nullable|boolean',
            'asma' => 'nullable|boolean',
            'kondisi_mata' => 'nullable|string|max:50',
            'minus_kanan' => 'nullable|string|max:10',
            'minus_kiri' => 'nullable|string|max:10',
            'silinder_kanan' => 'nullable|string|max:10',
            'silinder_kiri' => 'nullable|string|max:10',
            'buta_warna' => 'nullable|in:normal,partial,total',
            'penyakit_lain' => 'nullable|string',
            
            // C. Informasi Ayah
            'ayah_nama' => 'nullable|string|max:255',
            'ayah_usia' => 'nullable|integer|min:1|max:120',
            'ayah_status_perkawinan' => 'nullable|string|max:50',
            'ayah_pendidikan' => 'nullable|string|max:50',
            'ayah_pekerjaan' => 'nullable|string|max:100',
            'ayah_penghasilan' => 'nullable|numeric|min:0',
            'ayah_tanggungan' => 'nullable|integer|min:0',
            'ayah_status_tempat_tinggal' => 'nullable|string|max:50',
            
            // D. Informasi Ibu
            'ibu_nama' => 'nullable|string|max:255',
            'ibu_usia' => 'nullable|integer|min:1|max:120',
            'ibu_status_perkawinan' => 'nullable|string|max:50',
            'ibu_pendidikan' => 'nullable|string|max:50',
            'ibu_pekerjaan' => 'nullable|string|max:100',
            'ibu_penghasilan' => 'nullable|numeric|min:0',
            'ibu_tanggungan' => 'nullable|integer|min:0',
            'ibu_status_tempat_tinggal' => 'nullable|string|max:50',
            
            // E. Informasi Wali
            'wali_nama' => 'nullable|string|max:255',
            'wali_usia' => 'nullable|integer|min:1|max:120',
            'wali_status_perkawinan' => 'nullable|string|max:50',
            'wali_pendidikan' => 'nullable|string|max:50',
            'wali_pekerjaan' => 'nullable|string|max:100',
            'wali_penghasilan' => 'nullable|numeric|min:0',
            'wali_tanggungan' => 'nullable|integer|min:0',
            'wali_status_tempat_tinggal' => 'nullable|string|max:50',
            
            // Foto & Status
            'is_active' => 'nullable|boolean',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Handle checkbox boolean
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['alergi'] = $request->has('alergi') ? 1 : 0;
        $data['penyakit_jantung'] = $request->has('penyakit_jantung') ? 1 : 0;
        $data['tuberculosis'] = $request->has('tuberculosis') ? 1 : 0;
        $data['asma'] = $request->has('asma') ? 1 : 0;

        // Handle foto
        if ($request->hasFile('foto')) {
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $path = $request->file('foto')->store('siswa_foto', 'public');
            $data['foto'] = $path;
        }

        $siswa->update($data);

        return redirect()->route('siswa.show', $siswa)->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Siswa $siswa)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat menghapus data siswa.');
        }

        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }

        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus!');
    }

    // ======================================================
    // ============ METHOD UNTUK IMPORT SISWA ==============
    // ======================================================

    /**
     * Import data siswa dari file Excel/CSV
     */
    public function import(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat import data siswa.');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $import = new SiswaImport();
            Excel::import($import, $request->file('file'));

            $count = $import->getImportedCount();
            $errors = $import->getErrors();

            $message = "Berhasil mengimport {$count} data siswa.";

            if (!empty($errors)) {
                $message .= " Data yang gagal: " . implode(', ', $errors);
            }

            return redirect()->route('siswa.index')->with('success', $message);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }
            return redirect()->route('siswa.index')->with('error', 'Gagal import: ' . implode('; ', $errorMessages));
        } catch (\Exception $e) {
            return redirect()->route('siswa.index')->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    /**
     * Download template import siswa (format CSV)
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_siswa.csv"',
        ];

        $columns = [
            'nis',
            'nama',
            'nama_panggilan',
            'jenis_kelamin',
            'anak_ke',
            'jumlah_saudara',
            'usia',
            'tinggal_bersama',
            'kelas',
            'jurusan',
            'angkatan',
            'alamat',
            'transportasi',
            'no_hp_siswa',
            'no_hp_ortu',
            'nama_ortu',
            'golongan_darah',
            'alergi',
            'alergi_detail',
            'penyakit_jantung',
            'tuberculosis',
            'asma',
            'kondisi_mata',
            'minus_kanan',
            'minus_kiri',
            'silinder_kanan',
            'silinder_kiri',
            'buta_warna',
            'penyakit_lain',
            'ayah_nama',
            'ayah_usia',
            'ayah_status_perkawinan',
            'ayah_pendidikan',
            'ayah_pekerjaan',
            'ayah_penghasilan',
            'ayah_tanggungan',
            'ayah_status_tempat_tinggal',
            'ibu_nama',
            'ibu_usia',
            'ibu_status_perkawinan',
            'ibu_pendidikan',
            'ibu_pekerjaan',
            'ibu_penghasilan',
            'ibu_tanggungan',
            'ibu_status_tempat_tinggal',
            'wali_nama',
            'wali_usia',
            'wali_status_perkawinan',
            'wali_pendidikan',
            'wali_pekerjaan',
            'wali_penghasilan',
            'wali_tanggungan',
            'wali_status_tempat_tinggal'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            $sample = [
                '2024001',
                'Ahmad Fauzi',
                'Ahmad',
                'L',
                '1',
                '3',
                '16',
                'Orang Tua',
                '10',
                'PPLG',
                '2024',
                'Jl. Merdeka No. 1',
                'Sepeda Motor',
                '08123456789',
                '08123456780',
                'Bapak Ahmad',
                'O',
                '0',
                '',
                '0',
                '0',
                '0',
                'Normal',
                '',
                '',
                '',
                '',
                '',
                '',
                'Bapak Ahmad',
                '45',
                'Menikah',
                'S1',
                'PNS',
                '5000000',
                '4',
                'Milik Sendiri',
                'Ibu Siti',
                '40',
                'Menikah',
                'SMA',
                'Ibu Rumah Tangga',
                '0',
                '0',
                'Milik Sendiri',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ];

            fputcsv($file, $sample);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}