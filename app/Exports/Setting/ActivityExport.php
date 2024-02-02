<?php

namespace App\Exports\Setting;

use App\Models\Globals\Activity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ActivityExport implements FromCollection, WithStyles
{
    public function thead()
    {
        return [
            'A' => 'No',
            'B' => 'Modul',
            'C' => 'Deskripsi',
            'D' => 'Dibuat Oleh',
            'E' => 'Dibuat Pada',
        ];
    }

    public function collection()
    {
        $data = [];
        $data[] = array_values($this->thead());
        $records = Activity::grid()->filters()->get();
        foreach ($records as $i => $record) {
            $data[] = [
                ($i + 1),
                $record->show_module,
                $record->show_message,
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
