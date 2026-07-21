<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kasuses', function (Blueprint $table) {
            // Ubah kolom status dari enum ke varchar(50)
            $table->string('status', 50)->default('Baru')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kasuses', function (Blueprint $table) {
            // Kembalikan ke enum (opsional)
            $table->enum('status', [
                'Baru', 
                'Diproses', 
                'Konseling', 
                'Pemanggilan Orang Tua', 
                'Pembinaan', 
                'Selesai'
            ])->default('Baru')->change();
        });
    }
};