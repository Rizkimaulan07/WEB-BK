<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKasus extends Model
{
    protected $fillable = ['nama', 'warna'];

    public function kasuses()
    {
        return $this->hasMany(Kasus::class, 'kategori_id');
    }
}