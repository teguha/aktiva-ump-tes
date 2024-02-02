<?php

namespace App\Imports\Master;

use App\Models\Master\Aspect\Aspect;
use App\Models\Master\Ict\IctType;
use App\Models\Master\Org\OrgStruct;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AspectImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // Validasi Template
        $row = $collection->first();
        if (empty($row[1]) || strtoupper($row[1]) != strtoupper('Nama Aspek Audit')) {
            throw new \Exception("MESSAGE--File tidak tidak sesuai dengan template terbaru. Silahkan download template kembali!", 1);
        }

        // Maping Data
        foreach ($collection as $rw => $row) 
        {
            if ($rw == 0) continue;
            
            $name     = trim($row[1] ?? '');
            $category = trim($row[2] ?? '');
            $objectType = trim($row[3] ?? '');
            $objectName = trim($row[4] ?? '');

            if (!empty($name) && !empty($category) && !empty($objectType) && !empty($objectName)) {
                // Check Category
                if (in_array(strtolower($category), ['operation','operasional'])) {
                    $category = 'operation';
                }
                elseif (in_array(strtolower($category), ['special','khusus'])) {
                    $category = 'special';
                }
                elseif (in_array(strtolower($category), ['ict'])) {
                    $category = 'ict';
                }

                if (in_array($category, ['operation','special'])) {
                    // Check Object Audit
                    $object = OrgStruct::where('name', $objectName);
                    if (in_array(strtolower($objectType), ['division','divisi'])) {
                        $object = $object->division();
                    }
                    elseif (in_array(strtolower($objectType), ['department','departemen'])) {
                        $object = $object->department();
                    }
                    elseif (in_array(strtolower($objectType), ['branch','cabang'])) {
                        $object = $object->branch();
                    }
                    else {
                        $object->whereNull('id');
                    }

                    if ($object = $object->first()) {
                        Aspect::firstOrCreate([
                            'name' => $name,
                            'category' => $category,
                            'object_id' => $object->id,
                        ]);
                    }
                }
                elseif (in_array($category, ['ict'])) {
                    if ($ictType = IctType::where('name', $objectType)->first()) {
                        if ($ictObject = $ictType->ictObject()->where('name', $objectName)->first()) {
                            Aspect::firstOrCreate([
                                'name' => $name,
                                'category' => $category,
                                'ict_object_id' => $object->id,
                            ]);
                        }
                    }
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
