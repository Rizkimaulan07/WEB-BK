<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas <span class="text-red-500">*</span></label>
        <select name="kelas" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="10" {{ old('kelas', $siswa->kelas) == '10' ? 'selected' : '' }}>Kelas 10</option>
            <option value="11" {{ old('kelas', $siswa->kelas) == '11' ? 'selected' : '' }}>Kelas 11</option>
            <option value="12" {{ old('kelas', $siswa->kelas) == '12' ? 'selected' : '' }}>Kelas 12</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
        <select name="jurusan" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="">Pilih Jurusan</option>
            <option value="PPLG" {{ old('jurusan', $siswa->jurusan) == 'PPLG' ? 'selected' : '' }}>PPLG</option>
            <option value="AKL" {{ old('jurusan', $siswa->jurusan) == 'AKL' ? 'selected' : '' }}>AKL</option>
            <option value="TJKT" {{ old('jurusan', $siswa->jurusan) == 'TJKT' ? 'selected' : '' }}>TJKT</option>
            <option value="AXIO" {{ old('jurusan', $siswa->jurusan) == 'AXIO' ? 'selected' : '' }}>AXIO</option>
        </select>
    </div>
</div>