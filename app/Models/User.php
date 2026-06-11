<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'kelas', 'is_active'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['password' => 'hashed', 'is_active' => 'boolean'];

    public function kasuses() { return $this->hasMany(Kasus::class, 'guru_bk_id'); }
    public function tindakLanjuts() { return $this->hasMany(TindakLanjut::class); }
    public function homeVisits() { return $this->hasMany(HomeVisit::class); }
    
    public function isAdmin() { return $this->role === 'admin'; }
    public function isGuruBK() { return $this->role === 'guru_bk'; }
    public function isPimpinan() { return $this->role === 'pimpinan'; }
}