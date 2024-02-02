<?php

namespace App\Exports\MutasiAktiva;

use App\Models\MutasiAktiva\Pengajuan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengajuanExport implements FromCollection, WithStyles
{
    public function thead()
    {
        return [
            'A' => 'No',
            'B' => 'ID Aset',
            'C' => 'ID Pengajuan Mutasi',
            'D' => 'Tanggal Pengajuan Mutasi',
            'E' => 'Alasan Mutasi',
            'F' => 'PIC Staff Mutasi',
            'G' => 'PIC Kepala Dept Mutasi',
            'H' => 'Lokasi Tujuan Aktiva',
            'I' => 'Status',
            'J' => 'Dibuat Oleh',
            'K' => 'Dibuat Pada',
        ];
    }

    public function collection()
    {
        $data = [];
        $data[] = array_values($this->thead());
        $records = Pengajuan::grid()->filters()->get();
        foreach ($records as $i => $record) {
            $data[] = [
                ($i + 1),
                $record->id_aset,
                $record->code,
                $record->date,
                $record->alasan_mutasi,
                $record->pic_staff_mutasi,
                $record->pic_kepala_dept_mutasi,
                $record->lokasiTujuanPembelianAktiva->name,
                $record->status,
                $record->creator->name ?? '[System]',
                $record->created_at->format('Y-m-d, H:i'),
            ];
        }

        return collect($data);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(80);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:E')->getAlignment()->setVertical('center');
        $sheet->getStyle('A:E')->getAlignment()->setWrapText(true);
    }
}
