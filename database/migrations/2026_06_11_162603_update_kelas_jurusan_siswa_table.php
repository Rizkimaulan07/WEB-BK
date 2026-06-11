<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            // Ubah kolom kelas menjadi ENUM
            $table->enum('kelas', ['10', '11', '12'])->default('10')->change();
            
            // Ubah kolom jurusan menjadi ENUM
            $table->enum('jurusan', ['PPLG', 'AKL', 'TJKT', 'AXIO'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('kelas')->change();
            $table->string('jurusan')->nullable()->change();
        });
    }
};