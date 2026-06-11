<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'nis', 'nama', 'jenis_kelamin', 'kelas', 'jurusan', 'angkatan',
        'foto', 'alamat', 'no_hp_siswa', 'nama_ortu', 'no_hp_ortu', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function kasuses()
    {
        return $this->hasMany(Kasus::class);
    }

    public function getKelasAngkatanAttribute()
    {
        return "Kelas {$this->kelas} (Angkatan {$this->angkatan})";
    }
    
    public function getKelasLabelAttribute()
    {
        return "Kelas " . $this->kelas;
    }
    
    public function getJurusanLabelAttribute()
    {
        $jurusanList = [
            'PPLG' => 'Pengembangan Perangkat Lunak dan Gim',
            'AKL' => 'Akuntansi dan Keuangan Lembaga',
            'TJKT' => 'Teknik Jaringan Komputer dan Telekomunikasi',
            'AXIO' => 'Axioo Class Program'
        ];
        return $jurusanList[$this->jurusan] ?? $this->jurusan;
    }
}