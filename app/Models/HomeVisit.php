<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeVisit extends Model {
    protected $fillable = ['kasus_id', 'user_id', 'tanggal_kunjungan', 'alamat_kunjungan', 'hasil_kunjungan', 'yang_ditemui'];
    protected $casts = ['tanggal_kunjungan' => 'date'];

    public function kasus() { return $this->belongsTo(Kasus::class); }
    public function user() { return $this->belongsTo(User::class); }
}