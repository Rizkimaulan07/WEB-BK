@extends('layouts.app')
@section('title', 'Tambah Kasus')
@section('content')
<div class="py-4 max-w-2xl">
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('kasus.index') }}" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-lg font-semibold text-gray-800">Tambah Kasus Baru</h2>
    </div>

    <form method="POST" action="{{ route('kasus.store') }}"
          class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
        @csrf

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- ===== SISWA DROPDOWN ===== --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Siswa <span class="text-red-500">*</span>
            </label>

            <div x-data="{
                open: false,
                search: '',
                selectedId: '{{ old('siswa_id', '') }}',
                selectedLabel: '-- Pilih Siswa --',

                init() {
                    // auto-focus search saat dropdown buka
                    this.$watch('open', val => {
                        if (val) this.$nextTick(() => this.$refs.searchInput.focus());
                    });
                    // restore label jika ada old value
                    if (this.selectedId) {
                        const found = this.siswas.find(s => String(s.id) === String(this.selectedId));
                        if (found) this.selectedLabel = found.nama + ' · ' + found.nis + ' (' + found.kelas + (found.jurusan ? ' ' + found.jurusan : '') + ')';
                    }
                },

                siswas: {{ Js::from($siswas->map(fn($s) => [
                    'id'      => $s->id,
                    'nama'    => $s->nama,
                    'nis'     => $s->nis,
                    'kelas'   => $s->kelas,
                    'jurusan' => $s->jurusan,
                ])) }},

                get filtered() {
                    if (!this.search) return this.siswas;
                    const q = this.search.toLowerCase();
                    return this.siswas.filter(s =>
                        s.nama.toLowerCase().includes(q) ||
                        s.nis.toLowerCase().includes(q) ||
                        s.kelas.toLowerCase().includes(q) ||
                        (s.jurusan && s.jurusan.toLowerCase().includes(q))
                    );
                },

                get grouped() {
                    const groups = {};
                    this.filtered.forEach(s => {
                        const num   = s.kelas.match(/\d+/)?.[0] ?? '?';
                        const label = 'Kelas ' + num;
                        if (!groups[label]) groups[label] = [];
                        groups[label].push(s);
                    });
                    return Object.keys(groups).sort().map(k => ({ label: k, items: groups[k] }));
                },

                select(s) {
                    this.selectedId    = s.id;
                    this.selectedLabel = s.nama + ' · ' + s.nis + ' (' + s.kelas + (s.jurusan ? ' ' + s.jurusan : '') + ')';
                    this.$refs.siswaHidden.value = s.id;
                    this.open   = false;
                    this.search = '';
                }
            }" @click.outside="open = false" class="relative">

                {{-- Hidden input — nilai di-set langsung via $refs agar pasti ikut submit --}}
                <input type="hidden" name="siswa_id" x-ref="siswaHidden"
                       x-effect="$refs.siswaHidden.value = selectedId"
                       value="{{ old('siswa_id', '') }}">

                {{-- Trigger --}}
                <button type="button" @click="open = !open"
                        class="w-full flex items-center justify-between border rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-left transition"
                        :class="[open ? 'border-blue-400 ring-2 ring-blue-500' : 'border-gray-200',
                                 selectedId ? 'text-gray-900' : 'text-gray-400']">
                    <span x-text="selectedLabel" class="truncate"></span>
                    <i class="fas fa-chevron-down text-gray-400 text-xs ml-2 flex-shrink-0 transition-transform duration-200"
                       :class="open ? 'rotate-180' : ''"></i>
                </button>

                {{-- Dropdown panel --}}
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden">

                    {{-- Search --}}
                    <div class="p-2 border-b border-gray-100 bg-gray-50">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                            <input type="text" x-model="search" x-ref="searchInput"
                                   placeholder="Cari nama, NIS, kelas, atau jurusan..."
                                   @click.stop @keydown.escape="open = false"
                                   class="w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        </div>
                    </div>

                    {{-- List --}}
                    <div class="max-h-60 overflow-y-auto">

                        {{-- Empty state --}}
                        <template x-if="filtered.length === 0">
                            <div class="px-4 py-8 text-center text-gray-400 text-sm">
                                <i class="fas fa-user-slash text-2xl block mb-2"></i>
                                Siswa tidak ditemukan
                            </div>
                        </template>

                        {{-- Groups --}}
                        <template x-for="group in grouped" :key="group.label">
                            <div>
                                <div class="px-3 py-1.5 bg-gray-50 border-y border-gray-100 sticky top-0 z-10">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider"
                                          x-text="group.label"></span>
                                </div>
                                <template x-for="s in group.items" :key="s.id">
                                    <button type="button" @click="select(s)"
                                            class="w-full flex items-center justify-between px-4 py-2.5 hover:bg-blue-50 transition-colors text-left"
                                            :class="String(selectedId) === String(s.id) ? 'bg-blue-50' : ''">
                                        <div class="min-w-0">
                                            <span class="text-sm font-medium text-gray-800 block truncate"
                                                  x-text="s.nama"></span>
                                            <span class="text-xs text-gray-400" x-text="s.nis"></span>
                                        </div>
                                        <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-medium"
                                                  x-text="s.kelas + (s.jurusan ? ' ' + s.jurusan : '')"></span>
                                            <i x-show="String(selectedId) === String(s.id)"
                                               class="fas fa-check text-blue-600 text-xs"></i>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </template>

                    </div>
                </div>
            </div>

            @error('siswa_id')
            <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        {{-- ===== KATEGORI & TANGGAL ===== --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select name="kategori_id" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                    @endforeach
                </select>
                @error('kategori_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal Kejadian <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_kejadian" required
                       value="{{ old('tanggal_kejadian', date('Y-m-d')) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('tanggal_kejadian')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- ===== STATUS ===== --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status Awal</label>
            <select name="status"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach($statuses as $s)
                <option value="{{ $s }}" {{ old('status', 'Baru') === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>

        {{-- ===== DESKRIPSI ===== --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Deskripsi Kasus <span class="text-red-500">*</span>
            </label>
            <textarea name="deskripsi" rows="4" required
                      placeholder="Jelaskan kronologi dan detail kasus..."
                      class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ===== KETERANGAN ===== --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
            <textarea name="keterangan" rows="2" placeholder="Opsional..."
                      class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('keterangan') }}</textarea>
        </div>

        {{-- ===== ACTIONS ===== --}}
        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-blue-900 text-white px-5 py-2.5 rounded-lg text-sm hover:bg-blue-800 transition font-medium">
                <i class="fas fa-save mr-1"></i> Simpan Kasus
            </button>
            <a href="{{ route('kasus.index') }}"
               class="px-5 py-2.5 rounded-lg text-sm border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                Batal
            </a>
        </div>

    </form>
</div>
@endsection