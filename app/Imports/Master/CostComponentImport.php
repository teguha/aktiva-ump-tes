<?php

namespace App\Imports\Master;

use App\Models\Master\Fee\CostComponent;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CostComponentImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // Validasi Template
        $row = $collection->first();
        if (empty($row[1]) || strtoupper($row[1]) != strtoupper('Nama Komponen Biaya')) {
            throw new \Exception("MESSAGE--File tidak tidak sesuai dengan template terbaru. Silahkan download template kembali!", 1);
        }

        // Maping Data
        foreach ($collection as $rw => $row) 
        {
            if ($rw == 0) continue;
            
            $name     = trim($row[1] ?? '');

            if (!empty($name)) {
                // Simpan Data
                CostComponent::firstOrCreate(['name' => $name]);
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
