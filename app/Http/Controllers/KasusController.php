<?php
namespace App\Http\Controllers;

use App\Models\Kasus;
use App\Models\Siswa;
use App\Models\KategoriKasus;
use App\Models\User;
use Illuminate\Http\Request;

class KasusController extends Controller {
    public function index(Request $request) {
        $user = auth()->user();
        $query = Kasus::with(['siswa', 'kategori', 'guruBK']);

        // Filter berdasarkan role guru BK
        if ($user->isGuruBK() && $user->kelas !== 'semua') {
            $query->whereHas('siswa', fn($q) => $q->where('kelas', $user->kelas));
        }

        // Filter search (nama atau NIS siswa)
        if ($request->search) {
            $query->whereHas('siswa', fn($q) => $q->where('nama', 'like', "%{$request->search}%")
                ->orWhere('nis', 'like', "%{$request->search}%"));
        }
        
        // Filter berdasarkan kelas
        if ($request->kelas) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas', $request->kelas));
        }
        
        // Filter berdasarkan jurusan
        if ($request->jurusan) {
            $query->whereHas('siswa', fn($q) => $q->where('jurusan', $request->jurusan));
        }
        
        // Filter berdasarkan kategori
        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }
        
        // Filter berdasarkan status
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan rentang tanggal
        if ($request->dari) {
            $query->whereDate('tanggal_kejadian', '>=', $request->dari);
        }
        if ($request->sampai) {
            $query->whereDate('tanggal_kejadian', '<=', $request->sampai);
        }

        $kasuses = $query->latest()->paginate(15)->withQueryString();
        $kategoris = KategoriKasus::all();
        $statuses = [
            'Baru', 
            'Diproses (Konseling)', 
            'SP1', 
            'SP2', 
            'Pemanggilan Orang Tua', 
            'Wakil Kesiswaan', 
            'Selesai'
        ];

        return view('kasus.index', compact('kasuses', 'kategoris', 'statuses'));
    }

    public function create() {
        abort_if(auth()->user()->isPimpinan(), 403);
        $user = auth()->user();
        $siswaQuery = Siswa::where('is_active', true);
        
        // Guru BK hanya bisa melihat siswa sesuai kelasnya
        if ($user->isGuruBK() && $user->kelas !== 'semua') {
            $siswaQuery->where('kelas', $user->kelas);
        }
        
        $siswas = $siswaQuery->orderBy('nama')->get();
        $kategoris = KategoriKasus::all();
        $statuses = [
            'Baru', 
            'Diproses (Konseling)', 
            'SP1', 
            'SP2', 
            'Pemanggilan Orang Tua', 
            'Wakil Kesiswaan', 
            'Selesai'
        ];
        return view('kasus.create', compact('siswas', 'kategoris', 'statuses'));
    }

    public function store(Request $request) {
        abort_if(auth()->user()->isPimpinan(), 403);
        
        $data = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'kategori_id' => 'required|exists:kategori_kasuses,id',
            'tanggal_kejadian' => 'required|date',
            'deskripsi' => 'required|string',
            'status' => 'required|in:Baru,Diproses (Konseling),SP1,SP2,Pemanggilan Orang Tua,Wakil Kesiswaan,Selesai',
            'keterangan' => 'nullable|string',
        ]);
        
        $data['guru_bk_id'] = auth()->id();
        $kasus = Kasus::create($data);
        
        return redirect()->route('kasus.show', $kasus)->with('success', 'Kasus berhasil dicatat.');
    }

    public function show(Kasus $kasus) {
        $kasus->load(['siswa', 'kategori', 'guruBK', 'tindakLanjuts.user', 'homeVisits.user']);
        return view('kasus.show', compact('kasus'));
    }

    public function edit(Kasus $kasus) {
        abort_if(auth()->user()->isPimpinan(), 403);
        
        // Guru BK hanya bisa edit kasus sendiri
        if (auth()->user()->isGuruBK() && $kasus->guru_bk_id !== auth()->id()) {
            abort(403);
        }
        
        $siswas = Siswa::where('is_active', true)->orderBy('nama')->get();
        $kategoris = KategoriKasus::all();
        $guruBK = User::where('role', 'guru_bk')->orderBy('name')->get();
        $statuses = [
            'Baru', 
            'Diproses (Konseling)', 
            'SP1', 
            'SP2', 
            'Pemanggilan Orang Tua', 
            'Wakil Kesiswaan', 
            'Selesai'
        ];
        
        return view('kasus.edit', compact('kasus', 'siswas', 'kategoris', 'guruBK', 'statuses'));
    }

    public function update(Request $request, Kasus $kasus) {
        abort_if(auth()->user()->isPimpinan(), 403);
        
        if (auth()->user()->isGuruBK() && $kasus->guru_bk_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'kategori_id' => 'required|exists:kategori_kasuses,id',
            'tanggal_kejadian' => 'required|date',
            'deskripsi' => 'required|string',
            'status' => 'required|in:Baru,Diproses (Konseling),SP1,SP2,Pemanggilan Orang Tua,Wakil Kesiswaan,Selesai',
            'keterangan' => 'nullable|string',
        ]);
        
        $kasus->update($data);
        return redirect()->route('kasus.show', $kasus)->with('success', 'Kasus berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Kasus $kasus) {
        abort_if(auth()->user()->isPimpinan(), 403);
        
        $request->validate([
            'status' => 'required|in:Baru,Diproses (Konseling),SP1,SP2,Pemanggilan Orang Tua,Wakil Kesiswaan,Selesai'
        ]); 
        
        $kasus->update(['status' => $request->status]);
        return back()->with('success', 'Status kasus berhasil diubah.');
    }

    public function destroy(Kasus $kasus) {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        $kasus->delete();
        return redirect()->route('kasus.index')->with('success', 'Kasus berhasil dihapus.');
    }

    // Helper untuk mendapatkan badge color berdasarkan status
    public static function getStatusBadge($status) {
        $badges = [
            'Baru' => 'bg-blue-100 text-blue-700',
            'Diproses (Konseling)' => 'bg-yellow-100 text-yellow-700',
            'SP1' => 'bg-red-100 text-red-700',
            'SP2' => 'bg-red-200 text-red-800',
            'Pemanggilan Orang Tua' => 'bg-orange-100 text-orange-700',
            'Wakil Kesiswaan' => 'bg-purple-100 text-purple-700',
            'Selesai' => 'bg-green-100 text-green-700',
        ];
        return $badges[$status] ?? 'bg-gray-100 text-gray-700';
    }
}