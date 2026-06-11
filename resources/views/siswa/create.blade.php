<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas <span class="text-red-500">*</span></label>
        <select name="kelas" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="">Pilih Kelas</option>
            <option value="10" {{ old('kelas') == '10' ? 'selected' : '' }}>Kelas 10</option>
            <option value="11" {{ old('kelas') == '11' ? 'selected' : '' }}>Kelas 11</option>
            <option value="12" {{ old('kelas') == '12' ? 'selected' : '' }}>Kelas 12</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
        <select name="jurusan" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="">Pilih Jurusan</option>
            <option value="PPLG" {{ old('jurusan') == 'PPLG' ? 'selected' : '' }}>PPLG (Pengembangan Perangkat Lunak dan Gim)</option>
            <option value="AKL" {{ old('jurusan') == 'AKL' ? 'selected' : '' }}>AKL (Akuntansi dan Keuangan Lembaga)</option>
            <option value="TJKT" {{ old('jurusan') == 'TJKT' ? 'selected' : '' }}>TJKT (Teknik Jaringan Komputer dan Telekomunikasi)</option>
            <option value="AXIO" {{ old('jurusan') == 'AXIO' ? 'selected' : '' }}>AXIO (Axioo Class Program)</option>
        </select>
    </div>
</div>