<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TindakLanjut extends Model {
    protected $fillable = ['kasus_id', 'user_id', 'tanggal', 'catatan', 'status_setelah'];
    protected $casts = ['tanggal' => 'date'];

    public function kasus() { return $this->belongsTo(Kasus::class); }
    public function user() { return $this->belongsTo(User::class); }
}