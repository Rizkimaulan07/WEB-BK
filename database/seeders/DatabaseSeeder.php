<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use App\Models\KategoriKasus;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // Users
        User::create(['name' => 'Administrator', 'email' => 'admin@bk.sch.id', 'password' => Hash::make('password'), 'role' => 'admin', 'kelas' => 'semua']);
        User::create(['name' => 'Guru BK Kelas 10', 'email' => 'bk10@bk.sch.id', 'password' => Hash::make('password'), 'role' => 'guru_bk', 'kelas' => '10']);
        User::create(['name' => 'Guru BK Kelas 11', 'email' => 'bk11@bk.sch.id', 'password' => Hash::make('password'), 'role' => 'guru_bk', 'kelas' => '11']);
        User::create(['name' => 'Guru BK Kelas 12', 'email' => 'bk12@bk.sch.id', 'password' => Hash::make('password'), 'role' => 'guru_bk', 'kelas' => '12']);
        User::create(['name' => 'Kepala Sekolah', 'email' => 'kepsek@bk.sch.id', 'password' => Hash::make('password'), 'role' => 'pimpinan', 'kelas' => 'semua']);
        User::create(['name' => 'Wakasek Kesiswaan', 'email' => 'wakasek@bk.sch.id', 'password' => Hash::make('password'), 'role' => 'pimpinan', 'kelas' => 'semua']);

        // Kategori Kasus
        $kategoris = [
            ['nama' => 'Disiplin', 'warna' => '#EF4444'],
            ['nama' => 'Akademik', 'warna' => '#3B82F6'],
            ['nama' => 'Sosial', 'warna' => '#8B5CF6'],
            ['nama' => 'Bullying', 'warna' => '#EC4899'],
            ['nama' => 'Merokok', 'warna' => '#F97316'],
            ['nama' => 'Perkelahian', 'warna' => '#DC2626'],
            ['nama' => 'Keterlambatan', 'warna' => '#F59E0B'],
            ['nama' => 'Lainnya', 'warna' => '#6B7280'],
        ];
        foreach ($kategoris as $k) KategoriKasus::create($k);

        // Sample Siswa SMK dengan Kelas 10,11,12 dan Jurusan PPLG, AKL, TJKT, AXIO
        $siswas = [
            // Kelas 10
            ['nis' => '2024001', 'nama' => 'Ahmad Fauzi', 'jenis_kelamin' => 'L', 'kelas' => '10', 'jurusan' => 'PPLG', 'angkatan' => '2024', 'nama_ortu' => 'Bapak Fauzi', 'no_hp_ortu' => '08123456789'],
            ['nis' => '2024002', 'nama' => 'Siti Rahayu', 'jenis_kelamin' => 'P', 'kelas' => '10', 'jurusan' => 'PPLG', 'angkatan' => '2024', 'nama_ortu' => 'Ibu Rahayu', 'no_hp_ortu' => '08234567890'],
            ['nis' => '2024003', 'nama' => 'Budi Santoso', 'jenis_kelamin' => 'L', 'kelas' => '10', 'jurusan' => 'TJKT', 'angkatan' => '2024', 'nama_ortu' => 'Bapak Santoso', 'no_hp_ortu' => '08345678901'],
            ['nis' => '2024004', 'nama' => 'Dewi Lestari', 'jenis_kelamin' => 'P', 'kelas' => '10', 'jurusan' => 'AKL', 'angkatan' => '2024', 'nama_ortu' => 'Ibu Lestari', 'no_hp_ortu' => '08456789012'],
            ['nis' => '2024005', 'nama' => 'Eko Prasetyo', 'jenis_kelamin' => 'L', 'kelas' => '10', 'jurusan' => 'AXIO', 'angkatan' => '2024', 'nama_ortu' => 'Bapak Prasetyo', 'no_hp_ortu' => '08567890123'],
            
            // Kelas 11
            ['nis' => '2023001', 'nama' => 'Fitri Handayani', 'jenis_kelamin' => 'P', 'kelas' => '11', 'jurusan' => 'PPLG', 'angkatan' => '2023', 'nama_ortu' => 'Ibu Handayani', 'no_hp_ortu' => '08678901234'],
            ['nis' => '2023002', 'nama' => 'Gilang Ramadhan', 'jenis_kelamin' => 'L', 'kelas' => '11', 'jurusan' => 'TJKT', 'angkatan' => '2023', 'nama_ortu' => 'Bapak Ramadhan', 'no_hp_ortu' => '08789012345'],
            ['nis' => '2023003', 'nama' => 'Heni Susanti', 'jenis_kelamin' => 'P', 'kelas' => '11', 'jurusan' => 'AKL', 'angkatan' => '2023', 'nama_ortu' => 'Ibu Susanti', 'no_hp_ortu' => '08890123456'],
            ['nis' => '2023004', 'nama' => 'Indra Wijaya', 'jenis_kelamin' => 'L', 'kelas' => '11', 'jurusan' => 'AXIO', 'angkatan' => '2023', 'nama_ortu' => 'Bapak Wijaya', 'no_hp_ortu' => '08901234567'],
            ['nis' => '2023005', 'nama' => 'Joko Susilo', 'jenis_kelamin' => 'L', 'kelas' => '11', 'jurusan' => 'PPLG', 'angkatan' => '2023', 'nama_ortu' => 'Bapak Susilo', 'no_hp_ortu' => '08912345678'],
            
            // Kelas 12
            ['nis' => '2022001', 'nama' => 'Kartika Sari', 'jenis_kelamin' => 'P', 'kelas' => '12', 'jurusan' => 'PPLG', 'angkatan' => '2022', 'nama_ortu' => 'Ibu Sari', 'no_hp_ortu' => '08923456789'],
            ['nis' => '2022002', 'nama' => 'Lukman Hakim', 'jenis_kelamin' => 'L', 'kelas' => '12', 'jurusan' => 'TJKT', 'angkatan' => '2022', 'nama_ortu' => 'Bapak Hakim', 'no_hp_ortu' => '08934567890'],
            ['nis' => '2022003', 'nama' => 'Mega Utami', 'jenis_kelamin' => 'P', 'kelas' => '12', 'jurusan' => 'AKL', 'angkatan' => '2022', 'nama_ortu' => 'Ibu Utami', 'no_hp_ortu' => '08945678901'],
            ['nis' => '2022004', 'nama' => 'Nanda Putra', 'jenis_kelamin' => 'L', 'kelas' => '12', 'jurusan' => 'AXIO', 'angkatan' => '2022', 'nama_ortu' => 'Bapak Putra', 'no_hp_ortu' => '08956789012'],
            ['nis' => '2022005', 'nama' => 'Olivia Zahra', 'jenis_kelamin' => 'P', 'kelas' => '12', 'jurusan' => 'PPLG', 'angkatan' => '2022', 'nama_ortu' => 'Ibu Zahra', 'no_hp_ortu' => '08967890123'],
        ];
        foreach ($siswas as $s) Siswa::create($s);
    }
}