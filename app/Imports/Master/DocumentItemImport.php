<?php

namespace App\Imports\Master;

use App\Models\Master\Aspect\Aspect;
use App\Models\Master\Document\DocumentItem;
use App\Models\Master\Ict\IctType;
use App\Models\Master\Org\OrgStruct;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DocumentItemImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // Validasi Template
        $row = $collection->first();
        if (empty($row[1]) || strtoupper($row[1]) != strtoupper('Nama Dokumen')) {
            throw new \Exception("MESSAGE--File tidak tidak sesuai dengan template terbaru. Silahkan download template kembali!", 1);
        }

        // Maping Data
        foreach ($collection as $rw => $row) 
        {
            if ($rw == 0) continue;
            
            $name        = trim($row[1] ?? '');
            $description = trim($row[2] ?? '');
            $aspectName  = trim($row[3] ?? '');
            $category    = trim($row[4] ?? '');
            $objectType  = trim($row[5] ?? '');
            $objectName  = trim($row[6] ?? '');

            if (!empty($name) && !empty($description) && !empty($aspectName) && !empty($category) && !empty($objectType) && !empty($objectName)) {
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
                        $aspect = Aspect::firstOrCreate([
                            'name' => $aspectName,
                            'category' => $category,
                            'object_id' => $object->id,
                        ]);

                        DocumentItem::firstOrCreate([
                            'aspect_id' => $aspect->id,
                            'name' => $name,
                            'description' => $description,
                        ]);
                    }
                }
                elseif (in_array($category, ['ict'])) {
                    if ($ictType = IctType::where('name', $objectType)->first()) {
                        if ($ictObject = $ictType->ictObject()->where('name', $objectName)->first()) {
                            $aspect = Aspect::firstOrCreate([
                                'name' => $aspectName,
                                'category' => $category,
                                'ict_object_id' => $object->id,
                            ]);
                            DocumentItem::firstOrCreate([
                                'aspect_id' => $aspect->id,
                                'name' => $name,
                                'description' => $description,
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
