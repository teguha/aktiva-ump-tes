<?php

namespace App\Imports\Master;

use App\Models\Master\Extern\ExternInstance;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ExternInstanceImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // Validasi Template
        $row = $collection->first();
        if (empty($row[1]) || strtoupper($row[1]) != strtoupper('Nama')) {
            throw new \Exception("MESSAGE--File tidak tidak sesuai dengan template terbaru. Silahkan download template kembali!", 1);
        }

        // Maping Data
        foreach ($collection as $rw => $row) 
        {
            if ($rw == 0) continue;
            
            $name     = trim($row[1] ?? '');

            if (!empty($name)) {
                // Simpan Data
                $data = ExternInstance::firstOrNew(['name' => $name]);
                $data->fill([
                    'email' => trim($row[1] ?? ''),
                    'phone' => trim($row[1] ?? ''),
                    'address' => trim($row[1] ?? ''),
                    'pic' => trim($row[1] ?? ''),
                ]);
                $data->save();
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
