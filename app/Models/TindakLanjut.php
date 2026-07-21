<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TindakLanjut extends Model {
    protected $fillable = ['kasus_id', 'user_id', 'tanggal', 'catatan', 'status_setelah'];
    protected $casts = ['tanggal' => 'date'];

    public function kasus() { 
        return $this->belongsTo(Kasus::class); 
    }
    
    public function user() { 
        return $this->belongsTo(User::class); 
    }

    /**
     * Get all available statuses for tindak lanjut
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
     * Get status badge color
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
     * Accessor for status badge
     */
    public function getStatusBadgeAttribute()
    {
        return self::getStatusBadge($this->status_setelah);
    }

    /**
     * Accessor for status label with icon
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
        return $labels[$this->status_setelah] ?? $this->status_setelah;
    }
}