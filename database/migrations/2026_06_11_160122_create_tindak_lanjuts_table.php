<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasus_id')->constrained('kasuses')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->date('tanggal');
            $table->text('catatan');
            $table->enum('status_setelah', ['Baru', 'Diproses', 'Konseling', 'Pemanggilan Orang Tua', 'Pembinaan', 'Selesai']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tindak_lanjuts'); }
};