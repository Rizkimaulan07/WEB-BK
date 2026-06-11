<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update data kelas yang lama ke format baru
        // 10A, 10B, 10C -> 10
        // 11A, 11B, 11C -> 11  
        // 12A, 12B, 12C -> 12
        DB::table('siswas')->update([
            'kelas' => DB::raw("SUBSTRING(kelas, 1, 2)")
        ]);
        
        // Update jurusan yang kosong menjadi NULL
        DB::table('siswas')->whereNull('jurusan')->orWhere('jurusan', '')->update(['jurusan' => null]);
    }

    public function down(): void
    {
        // Tidak bisa rollback karena data sudah berubah
    }
};