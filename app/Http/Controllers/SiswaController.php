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
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
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
        // MANUAL CHECK - Hanya admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat menambah data siswa.');
        }
        
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        // MANUAL CHECK - Hanya admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat menambah data siswa.');
        }

        $data = $request->validate([
            'nis' => 'required|unique:siswas,nis',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => 'required|string|max:20',
            'jurusan' => 'nullable|string|max:50',
            'angkatan' => 'required|string|max:10',
            'alamat' => 'nullable|string',
            'no_hp_siswa' => 'nullable|string|max:20',
            'no_hp_ortu' => 'nullable|string|max:20',
            'nama_ortu' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('siswa_foto', 'public');
            $data['foto'] = $path;
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

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
        // MANUAL CHECK - Hanya admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat mengedit data siswa.');
        }

        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        // MANUAL CHECK - Hanya admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat mengedit data siswa.');
        }

        $data = $request->validate([
            'nis' => 'required|unique:siswas,nis,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => 'required|string|max:20',
            'jurusan' => 'nullable|string|max:50',
            'angkatan' => 'required|string|max:10',
            'alamat' => 'nullable|string',
            'no_hp_siswa' => 'nullable|string|max:20',
            'no_hp_ortu' => 'nullable|string|max:20',
            'nama_ortu' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $path = $request->file('foto')->store('siswa_foto', 'public');
            $data['foto'] = $path;
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $siswa->update($data);

        return redirect()->route('siswa.show', $siswa)->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Siswa $siswa)
    {
        // MANUAL CHECK - Hanya admin
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
        // MANUAL CHECK - Hanya admin
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
            'jenis_kelamin',
            'kelas',
            'jurusan',
            'angkatan',
            'alamat',
            'no_hp_siswa',
            'no_hp_ortu',
            'nama_ortu'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            
            // Tambahkan BOM untuk UTF-8 (biar Excel baca dengan benar)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, $columns);

            // Contoh data (5 sample)
            $samples = [
                ['2024001', 'Ahmad Fauzi', 'L', '10', 'PPLG', '2024', 'Jl. Merdeka No. 1', '08123456789', '08123456780', 'Bapak Ahmad'],
                ['2024002', 'Siti Rahma', 'P', '10', 'TJKT', '2024', 'Jl. Sudirman No. 2', '08123456788', '08123456779', 'Ibu Siti'],
                ['2024003', 'Budi Santoso', 'L', '10', 'AKL', '2024', 'Jl. Gatot Subroto No. 3', '08123456787', '08123456778', 'Bapak Budi'],
                ['2024004', 'Dewi Lestari', 'P', '10', 'AXIO', '2024', 'Jl. Diponegoro No. 4', '08123456786', '08123456777', 'Ibu Dewi'],
                ['2024005', 'Eko Prasetyo', 'L', '11', 'PPLG', '2023', 'Jl. Hasanuddin No. 5', '08123456785', '08123456776', 'Bapak Eko'],
            ];

            foreach ($samples as $sample) {
                fputcsv($file, $sample);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}