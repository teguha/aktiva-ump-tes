<?php

namespace App\Imports\Master;

use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Org\Position;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PositionImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // Validasi Template
        $row = $collection->first();
        if (empty($row[3]) || empty($row[4]) || empty($row[5]) || empty($row[6]) || empty($row[7])) {
            throw new \Exception("MESSAGE--File tidak tidak sesuai dengan template terbaru. Silahkan download template kembali!", 1);
        }

        // Maping Data
        foreach ($collection as $rw => $row)
        {
            if ($rw == 0) continue;

            $name       = trim($row[1] ?? '');
            $boc        = trim($row[2] ?? '');
            $bod        = trim($row[3] ?? '');
            $division   = trim($row[4] ?? '');
            $department = trim($row[5] ?? '');
            $branch     = trim($row[6] ?? '');
            $subbranch  = trim($row[7] ?? '');

            if (!empty($name)) {
                // Check salah satu lokasi/struct
                if (!empty($boc) || !empty($bod) || !empty($boc) || !empty($division) || !empty($department) || !empty($branch) || !empty($subbranch)) {
                }

                // Check struct dari belakang dulu
                // Check Cabang Pembantu
                $struct = OrgStruct::subbranch()->where('name', $subbranch)->first();
                if (!$struct) {
                    // Check Cabang
                    $struct = OrgStruct::branch()->where('name', $branch)->first();
                    if (!$struct) {
                        // Check Department
                        $struct = OrgStruct::department()->where('name', $department)->first();
                        if (!$struct) {
                            // Check Divisi
                            $struct = OrgStruct::division()->where('name', $division)->first();
                            if (!$struct) {
                                // Check Bod
                                $struct = OrgStruct::bod()->where('name', $bod)->first();
                                if (!$struct) {
                                    // Check Boc
                                    $struct = OrgStruct::boc()->where('name', $boc)->first();
                                }
                            }
                        }
                    }
                }

                // Check stuct
                if (!$struct) {
                    throw new \Exception('MESSAGE--lokasi/Struktur untuk Jabatan "'.$name.'" tidak tersedia!', 1);
                }

                // Simpan Position
                $position = Position::where('location_id',$struct->id)->where('name', $name)->first();
                if (!$position) {
                    $position = new Position;
                    $position->code = $position->getNewCode();
                    $position->location_id = $struct->id;
                    $position->name = $name;
                    $position->save();
                }
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
        return 2;
    }
}
