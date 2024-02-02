<?php

namespace App\Imports\Master;

use App\Models\Master\Org\OrgStruct;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class OrgStructImport implements ToCollection, WithStartRow
{
    protected $level = '';

    public function __construct($level)
    {
        $this->level = $level;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // Validasi Template
        $row = $collection->first();
        if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
            throw new \Exception("MESSAGE--File tidak tidak sesuai dengan template terbaru. Silahkan download template kembali!", 1);
        }

        $root = OrgStruct::root()->first();
        // Maping Data
        foreach ($collection as $rw => $row) 
        {
            if ($rw == 0) continue;

            $level       = $this->level;
            $name        = trim($row[1] ?? '');
            $parentName  = trim($row[2] ?? '');
            $phone       = trim($row[3] ?? '');
            $address     = trim($row[4] ?? '');
            $parentLevel = [
                'bod'        => ['bod'],
                'division'   => ['bod'],
                'department' => ['division'],
                'branch'     => ['bod'],
                'subbranch'  => ['branch'],
                'cash'       => ['branch'],
            ];

            // Check Parent
            $parent = OrgStruct::whereIn('level', $parentLevel[$level])
                                ->where('name', $parentName)->first();
            if (!$parent) {
                throw new \Exception('MESSAGE--Parent: "'.$parentName.'" tidak tersedia!', 1);
            }

            // Check Name
            if (!empty($name)) {
                // Simpan Struct
                $struct = OrgStruct::where('level',$level)->where('name', $name)->first();
                if (!$struct) {
                    $struct = new OrgStruct;
                    $struct->code = $struct->getNewCode($level);
                    $struct->level = $level;
                    $struct->name = $name;
                }
                $struct->phone = $phone ?? $root->phone;
                $struct->address = $address ?? $root->address;
                $struct->parent_id = $parent->id;
                $struct->save();
            }

        }

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
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
