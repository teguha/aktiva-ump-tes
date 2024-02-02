<?php

namespace App\Imports\Setting;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class RoleImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $modules = \Base::getModulesPerms();
        $actions = ['view','create','edit','delete','approve'];
        
        // Validasi Template
        $row = $collection->first();
        $index = 2;
        foreach ($modules as $menu) {
            foreach ($actions as $i => $action) {
                if (empty($row[$index]) || strtoupper(trim($row[$index])) != strtoupper($action)) {
                    throw new \Exception("MESSAGE--File tidak tidak sesuai dengan template terbaru. Silahkan download template kembali!", 1);
                }
                $index++;
            }
        }

        // Maping Data
        foreach ($collection as $rw => $row) 
        {
            if ($rw == 0) continue;

            if (!empty($row[1])) {
                $perms = [];
                $index = 2;
                foreach ($modules as $menu) {
                    foreach ($actions as $action) {
                        if (in_array(strtoupper(trim($row[$index])), ['Y','YES','YA'])) {
                            $permsName = trim($menu['perms']).'.'.$action;
                            if (Permission::where('name', $permsName)->exists()) {
                                $perms[] = $permsName;
                            }
                        }
                        $index++;
                    }
                }
                $role = Role::firstOrCreate(['name' => trim($row[1])]);
                $role->syncPermissions($perms);
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
        return 3;
    }
}
