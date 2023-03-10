<?php

namespace App\Exports;

use App\Models\Report;
//mengambil data dari db
use Maatwebsite\Excel\Concerns\FromCollection;
//mengatur nama nama column header di excelnya
use Maatwebsite\Excel\Concerns\WithHeadings;
//mengatur data yang dimunculkan tiap column di excelnya
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //didalam ini boleh menyertakan perintah alouquent lain seperti where,all,dll
        return Report::with('response')->orderBy('created_at','DESC')->get();
    }

    public function headings(): array
    {
        return[
            'ID',
            'NIK Pelopor',
            'Nama Pelopor',
            'No Telp Pelopor',
            'Tanggal Pelopor',
            'Pengaduan',
            'Status Response',
            'Pesan Response'
        ];
    }
    // mengatur data yang ditampilkan percolumn di excelnya
    // fungsinya seperti foreach. $item merupakan as pada foreach
    public function map($item): array
    {
        return [
            $item->id,
            $item->nik,
            $item->nama,
            $item->no_telp,
            \Carbon\Carbon::parse($item->created_at)->format('j F Y'),
            $item->pengaduan,
            $item->response ? $item->response['status'] : '-',
            $item->response ? $item->response['pesan'] : '-',
        ];
    }
}

