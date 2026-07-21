<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            // A. Informasi Siswa (tambahan)
            $table->string('nama_panggilan')->nullable()->after('nama');
            $table->integer('anak_ke')->nullable()->after('jenis_kelamin');
            $table->integer('jumlah_saudara')->nullable()->after('anak_ke');
            $table->integer('usia')->nullable()->after('jumlah_saudara');
            $table->string('tinggal_bersama')->nullable()->after('usia');
            $table->string('transportasi')->nullable()->after('alamat');
            
            // B. Kondisi Kesehatan
            $table->string('golongan_darah', 5)->nullable()->after('transportasi');
            $table->boolean('alergi')->default(false)->after('golongan_darah');
            $table->string('alergi_detail')->nullable()->after('alergi');
            $table->boolean('penyakit_jantung')->default(false)->after('alergi_detail');
            $table->boolean('tuberculosis')->default(false)->after('penyakit_jantung');
            $table->boolean('asma')->default(false)->after('tuberculosis');
            $table->string('kondisi_mata')->nullable()->after('asma');
            $table->string('minus_kanan')->nullable()->after('kondisi_mata');
            $table->string('minus_kiri')->nullable()->after('minus_kanan');
            $table->string('silinder_kanan')->nullable()->after('minus_kiri');
            $table->string('silinder_kiri')->nullable()->after('silinder_kanan');
            $table->string('buta_warna')->nullable()->after('silinder_kiri');
            $table->text('penyakit_lain')->nullable()->after('buta_warna');
            
            // C. Informasi Ayah
            $table->string('ayah_nama')->nullable()->after('penyakit_lain');
            $table->integer('ayah_usia')->nullable()->after('ayah_nama');
            $table->string('ayah_status_perkawinan')->nullable()->after('ayah_usia');
            $table->string('ayah_pendidikan')->nullable()->after('ayah_status_perkawinan');
            $table->string('ayah_pekerjaan')->nullable()->after('ayah_pendidikan');
            $table->decimal('ayah_penghasilan', 15, 2)->nullable()->after('ayah_pekerjaan');
            $table->integer('ayah_tanggungan')->nullable()->after('ayah_penghasilan');
            $table->string('ayah_status_tempat_tinggal')->nullable()->after('ayah_tanggungan');
            
            // D. Informasi Ibu
            $table->string('ibu_nama')->nullable()->after('ayah_status_tempat_tinggal');
            $table->integer('ibu_usia')->nullable()->after('ibu_nama');
            $table->string('ibu_status_perkawinan')->nullable()->after('ibu_usia');
            $table->string('ibu_pendidikan')->nullable()->after('ibu_status_perkawinan');
            $table->string('ibu_pekerjaan')->nullable()->after('ibu_pendidikan');
            $table->decimal('ibu_penghasilan', 15, 2)->nullable()->after('ibu_pekerjaan');
            $table->integer('ibu_tanggungan')->nullable()->after('ibu_penghasilan');
            $table->string('ibu_status_tempat_tinggal')->nullable()->after('ibu_tanggungan');
            
            // E. Informasi Wali
            $table->string('wali_nama')->nullable()->after('ibu_status_tempat_tinggal');
            $table->integer('wali_usia')->nullable()->after('wali_nama');
            $table->string('wali_status_perkawinan')->nullable()->after('wali_usia');
            $table->string('wali_pendidikan')->nullable()->after('wali_status_perkawinan');
            $table->string('wali_pekerjaan')->nullable()->after('wali_pendidikan');
            $table->decimal('wali_penghasilan', 15, 2)->nullable()->after('wali_pekerjaan');
            $table->integer('wali_tanggungan')->nullable()->after('wali_penghasilan');
            $table->string('wali_status_tempat_tinggal')->nullable()->after('wali_tanggungan');
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn([
                'nama_panggilan', 'anak_ke', 'jumlah_saudara', 'usia', 'tinggal_bersama',
                'transportasi', 'golongan_darah', 'alergi', 'alergi_detail',
                'penyakit_jantung', 'tuberculosis', 'asma', 'kondisi_mata',
                'minus_kanan', 'minus_kiri', 'silinder_kanan', 'silinder_kiri',
                'buta_warna', 'penyakit_lain',
                'ayah_nama', 'ayah_usia', 'ayah_status_perkawinan', 'ayah_pendidikan',
                'ayah_pekerjaan', 'ayah_penghasilan', 'ayah_tanggungan',
                'ayah_status_tempat_tinggal',
                'ibu_nama', 'ibu_usia', 'ibu_status_perkawinan', 'ibu_pendidikan',
                'ibu_pekerjaan', 'ibu_penghasilan', 'ibu_tanggungan',
                'ibu_status_tempat_tinggal',
                'wali_nama', 'wali_usia', 'wali_status_perkawinan', 'wali_pendidikan',
                'wali_pekerjaan', 'wali_penghasilan', 'wali_tanggungan',
                'wali_status_tempat_tinggal'
            ]);
        });
    }
};