<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        // A. Informasi Siswa
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
        'foto',
        'is_active',
        
        // B. Kondisi Kesehatan
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
        
        // C. Informasi Ayah
        'ayah_nama',
        'ayah_usia',
        'ayah_status_perkawinan',
        'ayah_pendidikan',
        'ayah_pekerjaan',
        'ayah_penghasilan',
        'ayah_tanggungan',
        'ayah_status_tempat_tinggal',
        
        // D. Informasi Ibu
        'ibu_nama',
        'ibu_usia',
        'ibu_status_perkawinan',
        'ibu_pendidikan',
        'ibu_pekerjaan',
        'ibu_penghasilan',
        'ibu_tanggungan',
        'ibu_status_tempat_tinggal',
        
        // E. Informasi Wali
        'wali_nama',
        'wali_usia',
        'wali_status_perkawinan',
        'wali_pendidikan',
        'wali_pekerjaan',
        'wali_penghasilan',
        'wali_tanggungan',
        'wali_status_tempat_tinggal',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'alergi' => 'boolean',
        'penyakit_jantung' => 'boolean',
        'tuberculosis' => 'boolean',
        'asma' => 'boolean',
        'anak_ke' => 'integer',
        'jumlah_saudara' => 'integer',
        'usia' => 'integer',
        'ayah_usia' => 'integer',
        'ayah_penghasilan' => 'decimal:2',
        'ayah_tanggungan' => 'integer',
        'ibu_usia' => 'integer',
        'ibu_penghasilan' => 'decimal:2',
        'ibu_tanggungan' => 'integer',
        'wali_usia' => 'integer',
        'wali_penghasilan' => 'decimal:2',
        'wali_tanggungan' => 'integer',
    ];

    // Relasi
    public function kasuses()
    {
        return $this->hasMany(Kasus::class);
    }

    // ================================================
    // ACCESSORS (Helper untuk tampilan)
    // ================================================

    // Kelas & Angkatan
    public function getKelasAngkatanAttribute()
    {
        return "Kelas {$this->kelas} (Angkatan {$this->angkatan})";
    }
    
    public function getKelasLabelAttribute()
    {
        return "Kelas " . $this->kelas;
    }
    
    // Jurusan dengan label lengkap
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

    // Nama lengkap dengan panggilan
    public function getNamaLengkapAttribute()
    {
        if ($this->nama_panggilan) {
            return "{$this->nama} ({$this->nama_panggilan})";
        }
        return $this->nama;
    }

    // Jenis Kelamin dalam teks
    public function getJenisKelaminTextAttribute()
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    // Status aktif dalam teks
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Tidak Aktif';
    }

    // Status badge untuk CSS
    public function getStatusBadgeAttribute()
    {
        return $this->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
    }

    // Golongan Darah
    public function getGolonganDarahLabelAttribute()
    {
        return $this->golongan_darah ?? '-';
    }

    // Kondisi Kesehatan lengkap - FIXED
    public function getKondisiKesehatanAttribute()
    {
        $conditions = [];
        if ($this->penyakit_jantung) $conditions[] = 'Jantung';
        if ($this->tuberculosis) $conditions[] = 'Tuberculosis';
        if ($this->asma) $conditions[] = 'Asma';
        if ($this->alergi) $conditions[] = 'Alergi: ' . ($this->alergi_detail ?? 'Ada');
        if ($this->penyakit_lain) $conditions[] = $this->penyakit_lain;
        
        return !empty($conditions) ? implode(', ', $conditions) : 'Tidak ada';
    }

    // Kondisi Mata lengkap - FIXED (PHP 8.4 compatible)
    public function getKondisiMataLengkapAttribute()
    {
        $parts = [];
        if ($this->kondisi_mata) $parts[] = $this->kondisi_mata;
        
        $minusKanan = $this->minus_kanan ?? '-';
        $minusKiri = $this->minus_kiri ?? '-';
        if ($this->minus_kanan || $this->minus_kiri) {
            $parts[] = "Minus (K:{$minusKanan}, T:{$minusKiri})";
        }
        
        $silinderKanan = $this->silinder_kanan ?? '-';
        $silinderKiri = $this->silinder_kiri ?? '-';
        if ($this->silinder_kanan || $this->silinder_kiri) {
            $parts[] = "Silinder (K:{$silinderKanan}, T:{$silinderKiri})";
        }
        
        if ($this->buta_warna) {
            $butaWarnaMap = ['normal' => 'Normal', 'partial' => 'Buta Warna Partial', 'total' => 'Buta Warna Total'];
            $parts[] = $butaWarnaMap[$this->buta_warna] ?? $this->buta_warna;
        }
        
        return !empty($parts) ? implode(', ', $parts) : 'Normal';
    }

    // Mata text untuk ditampilkan - FIXED
    public function getMataTextAttribute()
    {
        $text = [];
        if ($this->kondisi_mata) $text[] = $this->kondisi_mata;
        if ($this->minus_kanan && $this->minus_kiri) {
            $text[] = "Minus R:{$this->minus_kanan} L:{$this->minus_kiri}";
        }
        if ($this->silinder_kanan && $this->silinder_kiri) {
            $text[] = "Silinder R:{$this->silinder_kanan} L:{$this->silinder_kiri}";
        }
        return !empty($text) ? implode(' | ', $text) : '-';
    }

    // ================================================
    // FORMAT PENGHASILAN
    // ================================================
    protected function formatPenghasilan($amount)
    {
        if (!$amount) return '-';
        return 'Rp ' . number_format((float)$amount, 0, ',', '.');
    }

    public function getAyahPenghasilanFormattedAttribute()
    {
        return $this->formatPenghasilan($this->ayah_penghasilan);
    }

    public function getIbuPenghasilanFormattedAttribute()
    {
        return $this->formatPenghasilan($this->ibu_penghasilan);
    }

    public function getWaliPenghasilanFormattedAttribute()
    {
        return $this->formatPenghasilan($this->wali_penghasilan);
    }

    // ================================================
    // DATA ORANG TUA (ARRAY)
    // ================================================
    public function getDataAyahAttribute()
    {
        if (!$this->ayah_nama) return null;
        
        return [
            'nama' => $this->ayah_nama,
            'usia' => $this->ayah_usia,
            'status_perkawinan' => $this->ayah_status_perkawinan,
            'pendidikan' => $this->ayah_pendidikan,
            'pekerjaan' => $this->ayah_pekerjaan,
            'penghasilan' => $this->ayah_penghasilan,
            'tanggungan' => $this->ayah_tanggungan,
            'status_tempat_tinggal' => $this->ayah_status_tempat_tinggal,
        ];
    }

    public function getDataIbuAttribute()
    {
        if (!$this->ibu_nama) return null;
        
        return [
            'nama' => $this->ibu_nama,
            'usia' => $this->ibu_usia,
            'status_perkawinan' => $this->ibu_status_perkawinan,
            'pendidikan' => $this->ibu_pendidikan,
            'pekerjaan' => $this->ibu_pekerjaan,
            'penghasilan' => $this->ibu_penghasilan,
            'tanggungan' => $this->ibu_tanggungan,
            'status_tempat_tinggal' => $this->ibu_status_tempat_tinggal,
        ];
    }

    public function getDataWaliAttribute()
    {
        if (!$this->wali_nama) return null;
        
        return [
            'nama' => $this->wali_nama,
            'usia' => $this->wali_usia,
            'status_perkawinan' => $this->wali_status_perkawinan,
            'pendidikan' => $this->wali_pendidikan,
            'pekerjaan' => $this->wali_pekerjaan,
            'penghasilan' => $this->wali_penghasilan,
            'tanggungan' => $this->wali_tanggungan,
            'status_tempat_tinggal' => $this->wali_status_tempat_tinggal,
        ];
    }

    // Informasi lengkap siswa
    public function getInfoLengkapAttribute()
    {
        return "{$this->nama} (NIS: {$this->nis}) - Kelas {$this->kelas} {$this->jurusan}";
    }
}