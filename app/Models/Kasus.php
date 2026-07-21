<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kasus extends Model
{
    protected $fillable = [
        'siswa_id',
        'kategori_id',
        'guru_bk_id',
        'tanggal_kejadian',
        'deskripsi',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_kejadian' => 'date',
    ];

    // Relasi
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriKasus::class, 'kategori_id');
    }

    public function guruBK()
    {
        return $this->belongsTo(User::class, 'guru_bk_id');
    }

    public function tindakLanjuts()
    {
        return $this->hasMany(TindakLanjut::class);
    }

    public function homeVisits()
    {
        return $this->hasMany(HomeVisit::class);
    }

    // ================================================
    // STATUS HELPER METHODS
    // ================================================

    /**
     * Get all available statuses
     */
    public static function getStatuses()
    {
        return [
            'Baru',
            'Diproses (Konseling)',
            'Pemanggilan Orang Tua',
            'SP1',
            'SP2',
            'Wakil Kesiswaan',
            'Selesai'
        ];
    }

    /**
     * Get status badge color based on status
     */
    public static function getStatusBadge($status)
    {
        $badges = [
            'Baru' => 'bg-blue-100 text-blue-700',
            'Diproses (Konseling)' => 'bg-yellow-100 text-yellow-700',
            'Pemanggilan Orang Tua' => 'bg-orange-100 text-orange-700',
            'SP1' => 'bg-red-100 text-red-700',
            'SP2' => 'bg-red-200 text-red-800',
            'Wakil Kesiswaan' => 'bg-purple-100 text-purple-700',
            'Selesai' => 'bg-green-100 text-green-700',
        ];
        return $badges[$status] ?? 'bg-gray-100 text-gray-700';
    }

    /**
     * Get status badge for this instance
     */
    public function getStatusBadgeAttribute()
    {
        return self::getStatusBadge($this->status);
    }

    /**
     * Get status label with icon
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'Baru' => '🆕 Baru',
            'Diproses (Konseling)' => '📋 Diproses (Konseling)',
            'Pemanggilan Orang Tua' => '📞 Pemanggilan Orang Tua',
            'SP1' => '📄 SP1',
            'SP2' => '📄 SP2',
            'Wakil Kesiswaan' => '👔 Wakil Kesiswaan',
            'Selesai' => '✅ Selesai',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Check if status is active (not finished)
     */
    public function isActive()
    {
        return $this->status !== 'Selesai';
    }

    /**
     * Get next status suggestions based on current status
     */
    public function getNextStatuses()
    {
        $flow = [
            'Baru' => ['Diproses (Konseling)', 'Selesai'],
            'Diproses (Konseling)' => ['Pemanggilan Orang Tua', 'SP1', 'Selesai'],
            'Pemanggilan Orang Tua' => ['SP1', 'Selesai'],
            'SP1' => ['SP2', 'Wakil Kesiswaan', 'Selesai'],
            'SP2' => ['Wakil Kesiswaan', 'Selesai'],
            'Wakil Kesiswaan' => ['Selesai'],
            'Selesai' => [],
        ];
        return $flow[$this->status] ?? [];
    }
}