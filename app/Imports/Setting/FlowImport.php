<?php

namespace App\Imports\Setting;

use App\Models\Auth\Role;
use App\Models\Globals\Menu;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class FlowImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {   
        // Validasi Template
        $row = $collection->first();
        if (empty($row[1]) && !empty($row[2]) && strtoupper(trim($row[2])) != strtoupper('SEKUENSIAL/PARALEL')) {
            throw new \Exception("MESSAGE--File tidak tidak sesuai dengan template terbaru. Silahkan download template kembali!", 1);
        }

        $records = Menu::grid()->whereNotNull('parent_id')->get();
        foreach ($collection as $rw => $row) 
        {
            if ($rw == 0) continue;

            $index = 0;
            foreach ($records as $record) {
                $no = trim($row[$index++] ?? '');
                $roleName = trim($row[$index++ ?? '']);
                $type = trim($row[$index++] ?? '');
                $order = 1;
                if ($roleName) {
                    // Check Position
                    $role = Role::whereName($roleName)->first();
                    if (!$role) {
                        throw new \Exception('MESSAGE--Role: "'.$roleName.'" tidak tersedia!', 1);
                    }

                    // Simpan Flow Approval
                    $flow = $record->flows()->firstOrNew(['role_id' => $role->id]);
                    $flow->type  = (strtoupper($type) == 'PARALEL') ? 2 : 1;
                    $flow->order = $order;
                    $flow->save();
                    $order++;
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
