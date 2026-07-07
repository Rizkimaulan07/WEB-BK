<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ImportToModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $importedCount = 0;
    private $errors = [];

    public function model(array $row)
    {
        // Cek apakah siswa sudah ada berdasarkan NIS
        $existing = Siswa::where('nis', $row['nis'])->first();
        
        if ($existing) {
            $this->errors[] = "NIS {$row['nis']} sudah terdaftar atas nama {$existing->nama}";
            return null;
        }

        $this->importedCount++;

        return new Siswa([
            'nis' => $row['nis'],
            'nama' => $row['nama'],
            'jenis_kelamin' => strtoupper($row['jenis_kelamin']) === 'L' ? 'L' : 'P',
            'kelas' => $row['kelas'] . ' ' . $row['jurusan'],
            'jurusan' => $row['jurusan'] ?? null,
            'angkatan' => $row['angkatan'] ?? date('Y'),
            'alamat' => $row['alamat'] ?? null,
            'no_hp_siswa' => $row['no_hp_siswa'] ?? null,
            'no_hp_ortu' => $row['no_hp_ortu'] ?? null,
            'nama_ortu' => $row['nama_ortu'] ?? null,
            'is_active' => 1,
        ]);
    }

    public function rules(): array
    {
        return [
            'nis' => 'required|unique:siswas,nis',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P,l,p',
            'kelas' => 'required|in:10,11,12',
            'jurusan' => 'required|in:PPLG,TJKT,AKL,AXIO',
            'angkatan' => 'nullable|numeric|digits:4',
            'alamat' => 'nullable|string',
            'no_hp_siswa' => 'nullable|string|max:20',
            'no_hp_ortu' => 'nullable|string|max:20',
            'nama_ortu' => 'nullable|string|max:255',
        ];
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}