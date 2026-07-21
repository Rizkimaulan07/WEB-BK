@extends('layouts.app')
@section('title', 'Edit Siswa')
@section('content')
<div class="py-4">
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('siswa.show', $siswa) }}" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-lg font-semibold text-gray-800">Edit Data Siswa</h2>
    </div>

    <form method="POST" action="{{ route('siswa.update', $siswa) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- ============================================ --}}
        {{-- FOTO SISWA --}}
        {{-- ============================================ --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-6" x-data="{ preview: null }">
                <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center overflow-hidden flex-shrink-0 border-2 border-blue-200">
                    <img x-show="preview" :src="preview" class="w-full h-full object-cover">
                    @if($siswa->foto)
                    <img x-show="!preview" src="{{ asset('storage/'.$siswa->foto) }}" class="w-full h-full object-cover">
                    @else
                    <i x-show="!preview" class="fas fa-user text-blue-400 text-3xl"></i>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Siswa</label>
                    <input type="file" name="foto" accept="image/*"
                           @change="preview = URL.createObjectURL($event.target.files[0])"
                           class="text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah foto. Maks 2MB (jpg/png)</p>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- A. INFORMASI SISWA --}}
        {{-- ============================================ --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="bg-blue-600 text-white w-7 h-7 rounded-full flex items-center justify-center text-sm font-bold">A</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Informasi Siswa</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nama Lengkap --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" required value="{{ old('nama', $siswa->nama) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Nama Panggilan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Panggilan</label>
                    <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan', $siswa->nama_panggilan) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- NIS --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIS <span class="text-red-500">*</span></label>
                    <input type="text" name="nis" required value="{{ old('nis', $siswa->nis) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                {{-- Anak ke- --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Anak ke-</label>
                    <input type="number" name="anak_ke" min="1" value="{{ old('anak_ke', $siswa->anak_ke) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Jumlah Saudara --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Saudara</label>
                    <input type="number" name="jumlah_saudara" min="0" value="{{ old('jumlah_saudara', $siswa->jumlah_saudara) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Usia --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usia (Tahun)</label>
                    <input type="number" name="usia" min="1" max="100" value="{{ old('usia', $siswa->usia) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Tinggal Bersama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tinggal Bersama</label>
                    <input type="text" name="tinggal_bersama" value="{{ old('tinggal_bersama', $siswa->tinggal_bersama) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Tempat Tinggal</label>
                    <textarea name="alamat" rows="2"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('alamat', $siswa->alamat) }}</textarea>
                </div>

                {{-- Transportasi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Transportasi ke Sekolah</label>
                    <select name="transportasi"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih --</option>
                        <option value="Sepeda Motor" {{ old('transportasi', $siswa->transportasi) == 'Sepeda Motor' ? 'selected' : '' }}>Sepeda Motor</option>
                        <option value="Jalan Kaki" {{ old('transportasi', $siswa->transportasi) == 'Jalan Kaki' ? 'selected' : '' }}>Jalan Kaki</option>
                        <option value="Antar Jemput" {{ old('transportasi', $siswa->transportasi) == 'Antar Jemput' ? 'selected' : '' }}>Antar Jemput</option>
                        <option value="Angkot" {{ old('transportasi', $siswa->transportasi) == 'Angkot' ? 'selected' : '' }}>Angkot</option>
                        <option value="Kendaraan Pribadi" {{ old('transportasi', $siswa->transportasi) == 'Kendaraan Pribadi' ? 'selected' : '' }}>Kendaraan Pribadi</option>
                        <option value="Lainnya" {{ old('transportasi', $siswa->transportasi) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                {{-- No HP Siswa --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. HP Siswa</label>
                    <input type="text" name="no_hp_siswa" value="{{ old('no_hp_siswa', $siswa->no_hp_siswa) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- B. DATA AKADEMIK --}}
        {{-- ============================================ --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="bg-green-600 text-white w-7 h-7 rounded-full flex items-center justify-center text-sm font-bold">B</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Data Akademik</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas <span class="text-red-500">*</span></label>
                    <select name="kelas" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="10" {{ old('kelas', $siswa->kelas) == '10' ? 'selected' : '' }}>Kelas 10</option>
                        <option value="11" {{ old('kelas', $siswa->kelas) == '11' ? 'selected' : '' }}>Kelas 11</option>
                        <option value="12" {{ old('kelas', $siswa->kelas) == '12' ? 'selected' : '' }}>Kelas 12</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan <span class="text-red-500">*</span></label>
                    <select name="jurusan" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="PPLG" {{ old('jurusan', $siswa->jurusan) == 'PPLG' ? 'selected' : '' }}>PPLG</option>
                        <option value="TJKT" {{ old('jurusan', $siswa->jurusan) == 'TJKT' ? 'selected' : '' }}>TJKT</option>
                        <option value="AKL" {{ old('jurusan', $siswa->jurusan) == 'AKL' ? 'selected' : '' }}>AKL</option>
                        <option value="AXIO" {{ old('jurusan', $siswa->jurusan) == 'AXIO' ? 'selected' : '' }}>AXIO</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Angkatan <span class="text-red-500">*</span></label>
                    <input type="text" name="angkatan" required value="{{ old('angkatan', $siswa->angkatan) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- C. KONDISI KESEHATAN --}}
        {{-- ============================================ --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="bg-red-600 text-white w-7 h-7 rounded-full flex items-center justify-center text-sm font-bold">C</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Kondisi Kesehatan Siswa</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Golongan Darah --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Golongan Darah</label>
                    <select name="golongan_darah"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih --</option>
                        <option value="A" {{ old('golongan_darah', $siswa->golongan_darah) == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('golongan_darah', $siswa->golongan_darah) == 'B' ? 'selected' : '' }}>B</option>
                        <option value="AB" {{ old('golongan_darah', $siswa->golongan_darah) == 'AB' ? 'selected' : '' }}>AB</option>
                        <option value="O" {{ old('golongan_darah', $siswa->golongan_darah) == 'O' ? 'selected' : '' }}>O</option>
                    </select>
                </div>

                {{-- Alergi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alergi</label>
                    <div class="flex items-center gap-3 mb-2">
                        <label class="flex items-center gap-1 text-sm">
                            <input type="checkbox" name="alergi" value="1" {{ old('alergi', $siswa->alergi) ? 'checked' : '' }}>
                            Ada Alergi
                        </label>
                    </div>
                    <input type="text" name="alergi_detail" value="{{ old('alergi_detail', $siswa->alergi_detail) }}"
                           placeholder="Sebutkan alergi..."
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Riwayat Penyakit --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Riwayat Penyakit</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="penyakit_jantung" value="1" {{ old('penyakit_jantung', $siswa->penyakit_jantung) ? 'checked' : '' }}>
                            Penyakit Jantung
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="tuberculosis" value="1" {{ old('tuberculosis', $siswa->tuberculosis) ? 'checked' : '' }}>
                            Tuberculosis (Flek Paru)
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="asma" value="1" {{ old('asma', $siswa->asma) ? 'checked' : '' }}>
                            Asma
                        </label>
                    </div>
                </div>

                {{-- Kondisi Mata --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Mata</label>
                    <select name="kondisi_mata"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih --</option>
                        <option value="Normal" {{ old('kondisi_mata', $siswa->kondisi_mata) == 'Normal' ? 'selected' : '' }}>Normal</option>
                        <option value="Minus" {{ old('kondisi_mata', $siswa->kondisi_mata) == 'Minus' ? 'selected' : '' }}>Minus</option>
                        <option value="Silinder" {{ old('kondisi_mata', $siswa->kondisi_mata) == 'Silinder' ? 'selected' : '' }}>Silinder</option>
                        <option value="Minus & Silinder" {{ old('kondisi_mata', $siswa->kondisi_mata) == 'Minus & Silinder' ? 'selected' : '' }}>Minus & Silinder</option>
                    </select>

                    <div class="grid grid-cols-2 gap-2 mt-2">
                        <div>
                            <label class="text-xs text-gray-500">Minus Kanan</label>
                            <input type="text" name="minus_kanan" value="{{ old('minus_kanan', $siswa->minus_kanan) }}"
                                   placeholder="-0.5"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Minus Kiri</label>
                            <input type="text" name="minus_kiri" value="{{ old('minus_kiri', $siswa->minus_kiri) }}"
                                   placeholder="-0.5"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Silinder Kanan</label>
                            <input type="text" name="silinder_kanan" value="{{ old('silinder_kanan', $siswa->silinder_kanan) }}"
                                   placeholder="-0.5"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Silinder Kiri</label>
                            <input type="text" name="silinder_kiri" value="{{ old('silinder_kiri', $siswa->silinder_kiri) }}"
                                   placeholder="-0.5"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mt-2">
                        <label class="text-xs text-gray-500">Buta Warna</label>
                        <select name="buta_warna"
                                class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih --</option>
                            <option value="normal" {{ old('buta_warna', $siswa->buta_warna) == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="partial" {{ old('buta_warna', $siswa->buta_warna) == 'partial' ? 'selected' : '' }}>Buta Warna Partial</option>
                            <option value="total" {{ old('buta_warna', $siswa->buta_warna) == 'total' ? 'selected' : '' }}>Buta Warna Total</option>
                        </select>
                    </div>
                </div>

                {{-- Penyakit Lainnya --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penyakit Lainnya</label>
                    <textarea name="penyakit_lain" rows="2"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('penyakit_lain', $siswa->penyakit_lain) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- D. INFORMASI AYAH --}}
        {{-- ============================================ --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="bg-purple-600 text-white w-7 h-7 rounded-full flex items-center justify-center text-sm font-bold">D</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Informasi Ayah Kandung</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah</label>
                    <input type="text" name="ayah_nama" value="{{ old('ayah_nama', $siswa->ayah_nama) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usia Ayah</label>
                    <input type="number" name="ayah_usia" min="1" max="120" value="{{ old('ayah_usia', $siswa->ayah_usia) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Perkawinan</label>
                    <select name="ayah_status_perkawinan"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih --</option>
                        <option value="Menikah" {{ old('ayah_status_perkawinan', $siswa->ayah_status_perkawinan) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                        <option value="Cerai" {{ old('ayah_status_perkawinan', $siswa->ayah_status_perkawinan) == 'Cerai' ? 'selected' : '' }}>Cerai</option>
                        <option value="Duda" {{ old('ayah_status_perkawinan', $siswa->ayah_status_perkawinan) == 'Duda' ? 'selected' : '' }}>Duda</option>
                        <option value="Meninggal" {{ old('ayah_status_perkawinan', $siswa->ayah_status_perkawinan) == 'Meninggal' ? 'selected' : '' }}>Meninggal</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir</label>
                    <select name="ayah_pendidikan"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih --</option>
                        <option value="SD" {{ old('ayah_pendidikan', $siswa->ayah_pendidikan) == 'SD' ? 'selected' : '' }}>SD</option>
                        <option value="SMP" {{ old('ayah_pendidikan', $siswa->ayah_pendidikan) == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ old('ayah_pendidikan', $siswa->ayah_pendidikan) == 'SMA' ? 'selected' : '' }}>SMA</option>
                        <option value="D1-D3" {{ old('ayah_pendidikan', $siswa->ayah_pendidikan) == 'D1-D3' ? 'selected' : '' }}>D1-D3</option>
                        <option value="S1" {{ old('ayah_pendidikan', $siswa->ayah_pendidikan) == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ old('ayah_pendidikan', $siswa->ayah_pendidikan) == 'S2' ? 'selected' : '' }}>S2</option>
                        <option value="S3" {{ old('ayah_pendidikan', $siswa->ayah_pendidikan) == 'S3' ? 'selected' : '' }}>S3</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                    <input type="text" name="ayah_pekerjaan" value="{{ old('ayah_pekerjaan', $siswa->ayah_pekerjaan) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penghasilan Bulanan (Rp)</label>
                    <input type="number" name="ayah_penghasilan" min="0" step="50000" value="{{ old('ayah_penghasilan', $siswa->ayah_penghasilan) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tanggungan</label>
                    <input type="number" name="ayah_tanggungan" min="0" value="{{ old('ayah_tanggungan', $siswa->ayah_tanggungan) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Tempat Tinggal</label>
                    <select name="ayah_status_tempat_tinggal"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih --</option>
                        <option value="Milik Sendiri" {{ old('ayah_status_tempat_tinggal', $siswa->ayah_status_tempat_tinggal) == 'Milik Sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
                        <option value="Kontrak" {{ old('ayah_status_tempat_tinggal', $siswa->ayah_status_tempat_tinggal) == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="Sewa" {{ old('ayah_status_tempat_tinggal', $siswa->ayah_status_tempat_tinggal) == 'Sewa' ? 'selected' : '' }}>Sewa</option>
                        <option value="Keluarga" {{ old('ayah_status_tempat_tinggal', $siswa->ayah_status_tempat_tinggal) == 'Keluarga' ? 'selected' : '' }}>Keluarga</option>
                        <option value="Lainnya" {{ old('ayah_status_tempat_tinggal', $siswa->ayah_status_tempat_tinggal) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- E. INFORMASI IBU --}}
        {{-- ============================================ --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="bg-pink-600 text-white w-7 h-7 rounded-full flex items-center justify-center text-sm font-bold">E</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Informasi Ibu Kandung</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu</label>
                    <input type="text" name="ibu_nama" value="{{ old('ibu_nama', $siswa->ibu_nama) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usia Ibu</label>
                    <input type="number" name="ibu_usia" min="1" max="120" value="{{ old('ibu_usia', $siswa->ibu_usia) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Perkawinan</label>
                    <select name="ibu_status_perkawinan"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih --</option>
                        <option value="Menikah" {{ old('ibu_status_perkawinan', $siswa->ibu_status_perkawinan) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                        <option value="Cerai" {{ old('ibu_status_perkawinan', $siswa->ibu_status_perkawinan) == 'Cerai' ? 'selected' : '' }}>Cerai</option>
                        <option value="Janda" {{ old('ibu_status_perkawinan', $siswa->ibu_status_perkawinan) == 'Janda' ? 'selected' : '' }}>Janda</option>
                        <option value="Meninggal" {{ old('ibu_status_perkawinan', $siswa->ibu_status_perkawinan) == 'Meninggal' ? 'selected' : '' }}>Meninggal</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir</label>
                    <select name="ibu_pendidikan"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih --</option>
                        <option value="SD" {{ old('ibu_pendidikan', $siswa->ibu_pendidikan) == 'SD' ? 'selected' : '' }}>SD</option>
                        <option value="SMP" {{ old('ibu_pendidikan', $siswa->ibu_pendidikan) == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ old('ibu_pendidikan', $siswa->ibu_pendidikan) == 'SMA' ? 'selected' : '' }}>SMA</option>
                        <option value="D1-D3" {{ old('ibu_pendidikan', $siswa->ibu_pendidikan) == 'D1-D3' ? 'selected' : '' }}>D1-D3</option>
                        <option value="S1" {{ old('ibu_pendidikan', $siswa->ibu_pendidikan) == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ old('ibu_pendidikan', $siswa->ibu_pendidikan) == 'S2' ? 'selected' : '' }}>S2</option>
                        <option value="S3" {{ old('ibu_pendidikan', $siswa->ibu_pendidikan) == 'S3' ? 'selected' : '' }}>S3</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                    <input type="text" name="ibu_pekerjaan" value="{{ old('ibu_pekerjaan', $siswa->ibu_pekerjaan) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penghasilan Bulanan (Rp)</label>
                    <input type="number" name="ibu_penghasilan" min="0" step="50000" value="{{ old('ibu_penghasilan', $siswa->ibu_penghasilan) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tanggungan</label>
                    <input type="number" name="ibu_tanggungan" min="0" value="{{ old('ibu_tanggungan', $siswa->ibu_tanggungan) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Tempat Tinggal</label>
                    <select name="ibu_status_tempat_tinggal"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih --</option>
                        <option value="Milik Sendiri" {{ old('ibu_status_tempat_tinggal', $siswa->ibu_status_tempat_tinggal) == 'Milik Sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
                        <option value="Kontrak" {{ old('ibu_status_tempat_tinggal', $siswa->ibu_status_tempat_tinggal) == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="Sewa" {{ old('ibu_status_tempat_tinggal', $siswa->ibu_status_tempat_tinggal) == 'Sewa' ? 'selected' : '' }}>Sewa</option>
                        <option value="Keluarga" {{ old('ibu_status_tempat_tinggal', $siswa->ibu_status_tempat_tinggal) == 'Keluarga' ? 'selected' : '' }}>Keluarga</option>
                        <option value="Lainnya" {{ old('ibu_status_tempat_tinggal', $siswa->ibu_status_tempat_tinggal) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- F. INFORMASI WALI --}}
        {{-- ============================================ --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="bg-orange-600 text-white w-7 h-7 rounded-full flex items-center justify-center text-sm font-bold">F</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Informasi Wali (Jika Ada)</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Wali</label>
                    <input type="text" name="wali_nama" value="{{ old('wali_nama', $siswa->wali_nama) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usia Wali</label>
                    <input type="number" name="wali_usia" min="1" max="120" value="{{ old('wali_usia', $siswa->wali_usia) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Perkawinan</label>
                    <select name="wali_status_perkawinan"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih --</option>
                        <option value="Menikah" {{ old('wali_status_perkawinan', $siswa->wali_status_perkawinan) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                        <option value="Cerai" {{ old('wali_status_perkawinan', $siswa->wali_status_perkawinan) == 'Cerai' ? 'selected' : '' }}>Cerai</option>
                        <option value="Duda/Janda" {{ old('wali_status_perkawinan', $siswa->wali_status_perkawinan) == 'Duda/Janda' ? 'selected' : '' }}>Duda/Janda</option>
                        <option value="Meninggal" {{ old('wali_status_perkawinan', $siswa->wali_status_perkawinan) == 'Meninggal' ? 'selected' : '' }}>Meninggal</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir</label>
                    <select name="wali_pendidikan"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih --</option>
                        <option value="SD" {{ old('wali_pendidikan', $siswa->wali_pendidikan) == 'SD' ? 'selected' : '' }}>SD</option>
                        <option value="SMP" {{ old('wali_pendidikan', $siswa->wali_pendidikan) == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ old('wali_pendidikan', $siswa->wali_pendidikan) == 'SMA' ? 'selected' : '' }}>SMA</option>
                        <option value="D1-D3" {{ old('wali_pendidikan', $siswa->wali_pendidikan) == 'D1-D3' ? 'selected' : '' }}>D1-D3</option>
                        <option value="S1" {{ old('wali_pendidikan', $siswa->wali_pendidikan) == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ old('wali_pendidikan', $siswa->wali_pendidikan) == 'S2' ? 'selected' : '' }}>S2</option>
                        <option value="S3" {{ old('wali_pendidikan', $siswa->wali_pendidikan) == 'S3' ? 'selected' : '' }}>S3</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                    <input type="text" name="wali_pekerjaan" value="{{ old('wali_pekerjaan', $siswa->wali_pekerjaan) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penghasilan Bulanan (Rp)</label>
                    <input type="number" name="wali_penghasilan" min="0" step="50000" value="{{ old('wali_penghasilan', $siswa->wali_penghasilan) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tanggungan</label>
                    <input type="number" name="wali_tanggungan" min="0" value="{{ old('wali_tanggungan', $siswa->wali_tanggungan) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Tempat Tinggal</label>
                    <select name="wali_status_tempat_tinggal"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih --</option>
                        <option value="Milik Sendiri" {{ old('wali_status_tempat_tinggal', $siswa->wali_status_tempat_tinggal) == 'Milik Sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
                        <option value="Kontrak" {{ old('wali_status_tempat_tinggal', $siswa->wali_status_tempat_tinggal) == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="Sewa" {{ old('wali_status_tempat_tinggal', $siswa->wali_status_tempat_tinggal) == 'Sewa' ? 'selected' : '' }}>Sewa</option>
                        <option value="Keluarga" {{ old('wali_status_tempat_tinggal', $siswa->wali_status_tempat_tinggal) == 'Keluarga' ? 'selected' : '' }}>Keluarga</option>
                        <option value="Lainnya" {{ old('wali_status_tempat_tinggal', $siswa->wali_status_tempat_tinggal) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- G. STATUS & KONTAK DARURAT --}}
        {{-- ============================================ --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="bg-gray-600 text-white w-7 h-7 rounded-full flex items-center justify-center text-sm font-bold">G</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Status & Kontak Darurat</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. HP Orang Tua/Wali</label>
                    <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu', $siswa->no_hp_ortu) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Orang Tua/Wali</label>
                    <input type="text" name="nama_ortu" value="{{ old('nama_ortu', $siswa->nama_ortu) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="flex items-center gap-2 cursor-pointer pt-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $siswa->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded text-blue-600">
                        <span class="text-sm text-gray-700">Siswa Aktif</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- TOMBOL ACTION --}}
        {{-- ============================================ --}}
        <div class="flex flex-wrap gap-3">
            <button type="submit"
                    class="bg-blue-900 text-white px-6 py-2.5 rounded-lg text-sm hover:bg-blue-800 transition font-medium">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
            </button>
            <a href="{{ route('siswa.show', $siswa) }}"
               class="px-6 py-2.5 rounded-lg text-sm border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                Batal
            </a>
        </div>

    </form>
</div>
@endsection