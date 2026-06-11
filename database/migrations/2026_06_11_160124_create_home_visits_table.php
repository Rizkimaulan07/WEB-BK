<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('home_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasus_id')->constrained('kasuses')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->date('tanggal_kunjungan');
            $table->string('alamat_kunjungan');
            $table->text('hasil_kunjungan');
            $table->string('yang_ditemui'); // nama yang ditemui
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('home_visits'); }
};