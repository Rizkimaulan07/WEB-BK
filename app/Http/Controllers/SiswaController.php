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
     * Import data siswa dari file Excel (.xlsx)
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

            $message = "✅ Berhasil mengimport {$count} data siswa.";

            if (!empty($errors)) {
                $message .= " ⚠️ " . implode(', ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " dan " . (count($errors) - 5) . " data lainnya gagal.";
                }
            }

            return redirect()->route('siswa.index')->with('success', $message);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }
            return redirect()->route('siswa.index')->with('error', '❌ Gagal import: ' . implode('; ', array_slice($errorMessages, 0, 10)));
        } catch (\Exception $e) {
            return redirect()->route('siswa.index')->with('error', '❌ Gagal import data: ' . $e->getMessage());
        }
    }

    /**
     * Download template import siswa (format Excel .xlsx)
     */
    public function downloadTemplate()
    {
        // Buat file Excel template menggunakan PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Siswa');

        // Daftar kolom dengan array manual (menghindari error range)
        $columns = [
            'A' => 'nis*',
            'B' => 'nama*',
            'C' => 'nama_panggilan',
            'D' => 'jenis_kelamin*',
            'E' => 'anak_ke',
            'F' => 'jumlah_saudara',
            'G' => 'usia',
            'H' => 'tinggal_bersama',
            'I' => 'kelas*',
            'J' => 'jurusan',
            'K' => 'angkatan*',
            'L' => 'alamat',
            'M' => 'transportasi',
            'N' => 'no_hp_siswa',
            'O' => 'no_hp_ortu',
            'P' => 'nama_ortu',
            'Q' => 'golongan_darah',
            'R' => 'alergi',
            'S' => 'alergi_detail',
            'T' => 'penyakit_jantung',
            'U' => 'tuberculosis',
            'V' => 'asma',
            'W' => 'kondisi_mata',
            'X' => 'minus_kanan',
            'Y' => 'minus_kiri',
            'Z' => 'silinder_kanan',
            'AA' => 'silinder_kiri',
            'AB' => 'buta_warna',
            'AC' => 'penyakit_lain',
            'AD' => 'ayah_nama',
            'AE' => 'ayah_usia',
            'AF' => 'ayah_status_perkawinan',
            'AG' => 'ayah_pendidikan',
            'AH' => 'ayah_pekerjaan',
            'AI' => 'ayah_penghasilan',
            'AJ' => 'ayah_tanggungan',
            'AK' => 'ayah_status_tempat_tinggal',
            'AL' => 'ibu_nama',
            'AM' => 'ibu_usia',
            'AN' => 'ibu_status_perkawinan',
            'AO' => 'ibu_pendidikan',
            'AP' => 'ibu_pekerjaan',
            'AQ' => 'ibu_penghasilan',
            'AR' => 'ibu_tanggungan',
            'AS' => 'ibu_status_tempat_tinggal',
            'AT' => 'wali_nama',
            'AU' => 'wali_usia',
            'AV' => 'wali_status_perkawinan',
            'AW' => 'wali_pendidikan',
            'AX' => 'wali_pekerjaan',
            'AY' => 'wali_penghasilan',
            'AZ' => 'wali_tanggungan',
            'BA' => 'wali_status_tempat_tinggal',
        ];

        // Set header
        $row = 1;
        foreach ($columns as $col => $header) {
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->getFont()->setBold(true);
            $sheet->getStyle($col . $row)->getFont()->getColor()->setARGB('FFFFFFFF');
            $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF4472C4');
            $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        // Set lebar kolom
        foreach ($columns as $col => $header) {
            $sheet->getColumnDimension($col)->setWidth(15);
        }

        // Keterangan
        $sheet->setCellValue('A3', 'Keterangan: Kolom dengan tanda * wajib diisi');
        $sheet->getStyle('A3')->getFont()->setItalic(true)->getColor()->setARGB('FFFF0000');

        // Sample data
        $sample = [
            '2024001', 'Ahmad Fauzi', 'Ahmad', 'L', '1', '3', '16', 'Orang Tua',
            '10', 'PPLG', '2024', 'Jl. Merdeka No. 1', 'Sepeda Motor',
            '08123456789', '08123456780', 'Bapak Ahmad',
            'O', '0', '', '0', '0', '0', 'Normal', '', '', '', '', '', '',
            'Bapak Ahmad', '45', 'Menikah', 'S1', 'PNS', '5000000', '4', 'Milik Sendiri',
            'Ibu Siti', '40', 'Menikah', 'SMA', 'Ibu Rumah Tangga', '0', '0', 'Milik Sendiri',
            '', '', '', '', '', '', '', ''
        ];

        $row = 2;
        $colIndex = 0;
        $colKeys = array_keys($columns);
        foreach ($colKeys as $col) {
            $sheet->setCellValue($col . $row, $sample[$colIndex] ?? '');
            $colIndex++;
        }

        // Border
        $lastCol = end($colKeys);
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FFCCCCCC'],
                ],
            ],
        ];
        $sheet->getStyle('A1:' . $lastCol . '2')->applyFromArray($styleArray);

        // Buat file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'template_');
        $writer->save($tempFile);

        return response()->download($tempFile, 'template_import_siswa.xlsx')->deleteFileAfterSend(true);
    }
}