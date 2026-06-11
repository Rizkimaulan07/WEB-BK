<div class="mt-5 space-y-2 text-sm text-left">
    <div class="flex justify-between py-1.5 border-b border-gray-50">
        <span class="text-gray-400">Kelas</span>
        <span class="font-medium">Kelas {{ $siswa->kelas }}</span>
    </div>
    <div class="flex justify-between py-1.5 border-b border-gray-50">
        <span class="text-gray-400">Jurusan</span>
        <span class="font-medium">{{ $siswa->jurusan ?? '-' }}</span>
    </div>
    <!-- ... sisanya sama ... -->
</div>