<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeVisit extends Model
{
    protected $fillable = [
        'kasus_id',
        'user_id',
        'tanggal_kunjungan',
        'yang_ditemui',
        'alamat_kunjungan',
        'hasil_kunjungan',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    public function kasus()
    {
        return $this->belongsTo(Kasus::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}