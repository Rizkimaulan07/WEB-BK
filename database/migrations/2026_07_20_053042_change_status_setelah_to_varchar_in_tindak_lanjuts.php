<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus kolom status_setelah yang lama (ENUM)
        Schema::table('tindak_lanjuts', function (Blueprint $table) {
            $table->dropColumn('status_setelah');
        });

        // Buat kolom status_setelah baru sebagai VARCHAR
        Schema::table('tindak_lanjuts', function (Blueprint $table) {
            $table->string('status_setelah', 50)->default('Baru')->after('catatan');
        });
    }

    public function down(): void
    {
        // Hapus kolom VARCHAR
        Schema::table('tindak_lanjuts', function (Blueprint $table) {
            $table->dropColumn('status_setelah');
        });

        // Kembalikan ke ENUM
        Schema::table('tindak_lanjuts', function (Blueprint $table) {
            $table->enum('status_setelah', [
                'Baru', 
                'Diproses', 
                'Konseling', 
                'Pemanggilan Orang Tua', 
                'Pembinaan', 
                'Selesai'
            ])->default('Baru')->after('catatan');
        });
    }
};