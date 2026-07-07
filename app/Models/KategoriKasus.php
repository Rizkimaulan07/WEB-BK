<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKasus extends Model
{
    use HasFactory;

    protected $table = 'kategori_kasuses';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'warna',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke kasus
    public function kasuses()
    {
        return $this->hasMany(Kasus::class, 'kategori_id');
    }

    // Alias
    public function kasus()
    {
        return $this->hasMany(Kasus::class, 'kategori_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getTotalKasusAttribute()
    {
        return $this->kasuses()->count();
    }
}