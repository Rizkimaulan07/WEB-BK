<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kasuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori_kasuses');
            $table->foreignId('guru_bk_id')->constrained('users');
            $table->date('tanggal_kejadian');
            $table->text('deskripsi');
            $table->enum('status', ['Baru', 'Diproses', 'Konseling', 'Pemanggilan Orang Tua', 'Pembinaan', 'Selesai'])->default('Baru');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('kasuses'); }
};