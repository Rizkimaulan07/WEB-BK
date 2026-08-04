<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithBatchInserts, WithChunkReading
{
    use SkipsFailures;

    private $importedCount = 0;
    private $errors = [];

    /**
     * Konversi nilai boolean dari berbagai format
     */
    private function convertBoolean($value)
    {
        if (is_null($value) || $value === '') {
            return 0;
        }
        
        $trueValues = ['1', 1, 'true', 'TRUE', 'True', 'yes', 'Yes', 'YES', 'iya', 'Iya', 'Y', 'y', 'ada', 'Ada', 'ADA'];
        
        if (in_array($value, $trueValues, true)) {
            return 1;
        }
        
        return 0;
    }

    /**
     * Bersihkan nilai kelas (ambil angka saja)
     */
    private function cleanKelas($value)
    {
        if (is_null($value) || $value === '') {
            return null;
        }
        
        // Ambil angka saja dari string
        $cleaned = preg_replace('/[^0-9]/', '', (string)$value);
        
        // Pastikan hanya 10, 11, atau 12
        if (in_array($cleaned, ['10', '11', '12'])) {
            return $cleaned;
        }
        
        return null;
    }

    public function model(array $row)
    {
        // Cek apakah siswa sudah ada berdasarkan NIS
        $existingSiswa = Siswa::where('nis', $row['nis'])->first();
        
        if ($existingSiswa) {
            $this->errors[] = "Siswa dengan NIS {$row['nis']} sudah ada. Data dilewati.";
            return null;
        }

        // Bersihkan nilai kelas
        $kelas = $this->cleanKelas($row['kelas']);
        if (is_null($kelas)) {
            $this->errors[] = "Baris dengan NIS {$row['nis']}: Kelas harus 10, 11, atau 12. Data dilewati.";
            return null;
        }

        $this->importedCount++;

        // Mapping field dari Excel ke database
        return new Siswa([
            'nis' => $row['nis'] ?? null,
            'nama' => $row['nama'] ?? null,
            'nama_panggilan' => $row['nama_panggilan'] ?? null,
            'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
            'anak_ke' => $row['anak_ke'] ?? null,
            'jumlah_saudara' => $row['jumlah_saudara'] ?? null,
            'usia' => $row['usia'] ?? null,
            'tinggal_bersama' => $row['tinggal_bersama'] ?? null,
            'kelas' => $kelas,
            'jurusan' => $row['jurusan'] ?? null,
            'angkatan' => $row['angkatan'] ?? null,
            'alamat' => $row['alamat'] ?? null,
            'transportasi' => $row['transportasi'] ?? null,
            'no_hp_siswa' => $row['no_hp_siswa'] ?? null,
            'no_hp_ortu' => $row['no_hp_ortu'] ?? null,
            'nama_ortu' => $row['nama_ortu'] ?? null,
            'is_active' => true,
            
            // Field kesehatan dengan konversi boolean
            'golongan_darah' => $row['golongan_darah'] ?? null,
            'alergi' => $this->convertBoolean($row['alergi'] ?? 0),
            'alergi_detail' => $row['alergi_detail'] ?? null,
            'penyakit_jantung' => $this->convertBoolean($row['penyakit_jantung'] ?? 0),
            'tuberculosis' => $this->convertBoolean($row['tuberculosis'] ?? 0),
            'asma' => $this->convertBoolean($row['asma'] ?? 0),
            'kondisi_mata' => $row['kondisi_mata'] ?? null,
            'minus_kanan' => $row['minus_kanan'] ?? null,
            'minus_kiri' => $row['minus_kiri'] ?? null,
            'silinder_kanan' => $row['silinder_kanan'] ?? null,
            'silinder_kiri' => $row['silinder_kiri'] ?? null,
            'buta_warna' => $row['buta_warna'] ?? null,
            'penyakit_lain' => $row['penyakit_lain'] ?? null,
            
            // Ayah
            'ayah_nama' => $row['ayah_nama'] ?? null,
            'ayah_usia' => $row['ayah_usia'] ?? null,
            'ayah_status_perkawinan' => $row['ayah_status_perkawinan'] ?? null,
            'ayah_pendidikan' => $row['ayah_pendidikan'] ?? null,
            'ayah_pekerjaan' => $row['ayah_pekerjaan'] ?? null,
            'ayah_penghasilan' => $row['ayah_penghasilan'] ?? null,
            'ayah_tanggungan' => $row['ayah_tanggungan'] ?? null,
            'ayah_status_tempat_tinggal' => $row['ayah_status_tempat_tinggal'] ?? null,
            
            // Ibu
            'ibu_nama' => $row['ibu_nama'] ?? null,
            'ibu_usia' => $row['ibu_usia'] ?? null,
            'ibu_status_perkawinan' => $row['ibu_status_perkawinan'] ?? null,
            'ibu_pendidikan' => $row['ibu_pendidikan'] ?? null,
            'ibu_pekerjaan' => $row['ibu_pekerjaan'] ?? null,
            'ibu_penghasilan' => $row['ibu_penghasilan'] ?? null,
            'ibu_tanggungan' => $row['ibu_tanggungan'] ?? null,
            'ibu_status_tempat_tinggal' => $row['ibu_status_tempat_tinggal'] ?? null,
            
            // Wali
            'wali_nama' => $row['wali_nama'] ?? null,
            'wali_usia' => $row['wali_usia'] ?? null,
            'wali_status_perkawinan' => $row['wali_status_perkawinan'] ?? null,
            'wali_pendidikan' => $row['wali_pendidikan'] ?? null,
            'wali_pekerjaan' => $row['wali_pekerjaan'] ?? null,
            'wali_penghasilan' => $row['wali_penghasilan'] ?? null,
            'wali_tanggungan' => $row['wali_tanggungan'] ?? null,
            'wali_status_tempat_tinggal' => $row['wali_status_tempat_tinggal'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nis' => 'required|unique:siswas,nis',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => 'required|in:10,11,12',
            'angkatan' => 'required|digits:4',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nis.required' => 'NIS wajib diisi',
            'nis.unique' => 'NIS sudah terdaftar di database',
            'nama.required' => 'Nama wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            'kelas.required' => 'Kelas wajib diisi',
            'kelas.in' => 'Kelas harus 10, 11, atau 12',
            'angkatan.required' => 'Angkatan wajib diisi',
            'angkatan.digits' => 'Angkatan harus 4 digit (contoh: 2024)',
        ];
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}