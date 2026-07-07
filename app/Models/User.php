<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    // ==============================================
    // ============ CEK ROLE =======================
    // ==============================================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBK(): bool
    {
        return $this->role === 'bk';
    }

    // ALIAS untuk isBK() - biar lebih jelas
    public function isGuruBK(): bool
    {
        return $this->role === 'bk';
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    // ==============================================
    // ============ RELASI ==========================
    // ==============================================

    // Relasi ke kasus (sebagai guru BK yang menangani)
    public function kasusDitangani()
    {
        return $this->hasMany(Kasus::class, 'bk_id');
    }

    // Relasi ke kasus (sebagai user yang menginput)
    public function kasusInput()
    {
        return $this->hasMany(Kasus::class, 'input_by');
    }

    // Relasi ke tindak lanjut
    public function tindakLanjuts()
    {
        return $this->hasMany(TindakLanjut::class, 'user_id');
    }

    // ==============================================
    // ============ HELPER ==========================
    // ==============================================

    // Cek apakah user punya akses ke fitur tertentu
    public function canManageSiswa(): bool
    {
        return $this->isAdmin();
    }

    public function canManageKasus(): bool
    {
        return $this->isAdmin() || $this->isBK();
    }

    public function canViewAllData(): bool
    {
        return $this->isAdmin() || $this->isPimpinan();
    }
}