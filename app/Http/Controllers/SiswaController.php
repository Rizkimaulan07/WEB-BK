<?php
namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller {
    public function index(Request $request) {
        $query = Siswa::query();
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('nis', 'like', "%{$request->search}%");
            });
        }
        
        // Filter kelas (untuk SMK: 10, 11, 12)
        if ($request->kelas) {
            $query->where('kelas', $request->kelas);
        }
        
        // Filter jurusan (PPLG, AKL, TJKT, AXIO)
        if ($request->jurusan) {
            $query->where('jurusan', $request->jurusan);
        }
        
        // Filter status aktif/nonaktif
        if ($request->status) {
            $query->where('is_active', $request->status === 'aktif');
        }

        // Guru BK hanya lihat kelas mereka
        $user = auth()->user();
        if ($user->isGuruBK() && $user->kelas !== 'semua') {
            $query->where('kelas', $user->kelas);
        }

        $siswas = $query->withCount('kasuses')->orderBy('nama')->paginate(15)->withQueryString();
        return view('siswa.index', compact('siswas'));
    }

    public function create() {
        $this->authorize('create', Siswa::class); // hanya admin
        return view('siswa.create');
    }

    public function store(Request $request) {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        $data = $request->validate([
            'nis' => 'required|unique:siswas,nis',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => 'required|in:10,11,12', // SMK: 10,11,12
            'jurusan' => 'nullable|in:PPLG,AKL,TJKT,AXIO', // Jurusan SMK
            'angkatan' => 'required|string|max:4',
            'alamat' => 'nullable|string',
            'no_hp_siswa' => 'nullable|string|max:15',
            'nama_ortu' => 'nullable|string|max:255',
            'no_hp_ortu' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('siswa', 'public');
        }
        
        Siswa::create($data);
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa) {
        $kasuses = $siswa->kasuses()->with(['kategori', 'guruBK', 'tindakLanjuts'])->latest()->get();
        return view('siswa.show', compact('siswa', 'kasuses'));
    }

    public function edit(Siswa $siswa) {
        abort_if(!auth()->user()->isAdmin(), 403);
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa) {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        $data = $request->validate([
            'nis' => 'required|unique:siswas,nis,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => 'required|in:10,11,12', // SMK: 10,11,12
            'jurusan' => 'nullable|in:PPLG,AKL,TJKT,AXIO', // Jurusan SMK
            'angkatan' => 'required|string|max:4',
            'alamat' => 'nullable|string',
            'no_hp_siswa' => 'nullable|string|max:15',
            'nama_ortu' => 'nullable|string|max:255',
            'no_hp_ortu' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',
        ]);
        
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($siswa->foto && file_exists(storage_path('app/public/' . $siswa->foto))) {
                unlink(storage_path('app/public/' . $siswa->foto));
            }
            $data['foto'] = $request->file('foto')->store('siswa', 'public');
        }
        
        $siswa->update($data);
        return redirect()->route('siswa.show', $siswa)->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa) {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        // Hapus foto jika ada
        if ($siswa->foto && file_exists(storage_path('app/public/' . $siswa->foto))) {
            unlink(storage_path('app/public/' . $siswa->foto));
        }
        
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}