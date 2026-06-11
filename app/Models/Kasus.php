<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kasus extends Model {
    protected $fillable = ['siswa_id', 'kategori_id', 'guru_bk_id', 'tanggal_kejadian', 'deskripsi', 'status', 'keterangan'];
    protected $casts = ['tanggal_kejadian' => 'date'];

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function kategori() { return $this->belongsTo(KategoriKasus::class, 'kategori_id'); }
    public function guruBK() { return $this->belongsTo(User::class, 'guru_bk_id'); }
    public function tindakLanjuts() { return $this->hasMany(TindakLanjut::class); }
    public function homeVisits() { return $this->hasMany(HomeVisit::class); }

    public function getStatusBadgeAttribute() {
        return match($this->status) {
            'Baru' => 'bg-blue-100 text-blue-800',
            'Diproses' => 'bg-yellow-100 text-yellow-800',
            'Konseling' => 'bg-purple-100 text-purple-800',
            'Pemanggilan Orang Tua' => 'bg-orange-100 text-orange-800',
            'Pembinaan' => 'bg-red-100 text-red-800',
            'Selesai' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}