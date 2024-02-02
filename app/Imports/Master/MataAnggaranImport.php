<?php

namespace App\Imports\Master;

use App\Models\Master\MataAnggaran\MataAnggaran;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MataAnggaranImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // Validasi Template
        $row = $collection->first();
        if (empty($row[0]) || strtoupper($row[1]) != strtoupper('Id Anggaran') || strtoupper($row[2]) != strtoupper('Nama Anggaran')) {
            throw new \Exception("MESSAGE--File tidak tidak sesuai dengan template terbaru. Silahkan download template kembali!", 1);
        }

        // Insert Data
        $id_anggaran = null;
        $nama = null;
        foreach ($collection as $rw => $row)
        {
            if ($rw > 0) {
                $id_anggaran = trim($row[1] ?? null);
                $nama = trim($row[2] ?? null);
                $mataAnggaran = MataAnggaran::where('id_anggaran', $id_anggaran)->where('nama', $nama)->first();
                if (!$mataAnggaran) {
                    $mataAnggaran = new MataAnggaran;
                    $mataAnggaran->id_anggaran = $id_anggaran;
                    $mataAnggaran->nama = $nama;
                    $mataAnggaran->save();
                }
            }
        }


        return $collection;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 1;
    }
}
