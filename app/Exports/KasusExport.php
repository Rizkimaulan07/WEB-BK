<?php

namespace App\Exports;

use App\Models\Kasus;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class KasusExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Kasus::with(['siswa', 'kategori', 'guruBK']);

        if ($this->request->dari) {
            $query->whereDate('tanggal_kejadian', '>=', $this->request->dari);
        }
        if ($this->request->sampai) {
            $query->whereDate('tanggal_kejadian', '<=', $this->request->sampai);
        }
        if ($this->request->kategori_id) {
            $query->where('kategori_id', $this->request->kategori_id);
        }
        if ($this->request->status) {
            $query->where('status', $this->request->status);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Kejadian',
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Kategori',
            'Status',
            'Deskripsi',
            'Guru BK',
            'Keterangan'
        ];
    }

    public function map($kasus): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $kasus->tanggal_kejadian->format('d/m/Y'),
            $kasus->siswa->nis,
            $kasus->siswa->nama,
            $kasus->siswa->kelas,
            $kasus->kategori->nama,
            $kasus->status,
            $kasus->deskripsi,
            $kasus->guruBK->name,
            $kasus->keterangan ?? '-',
        ];
    }
}