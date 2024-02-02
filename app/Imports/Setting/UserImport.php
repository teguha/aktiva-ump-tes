<?php

namespace App\Imports\Setting;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Master\Org\OrgStruct;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UserImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {   
        // Validasi Template
        $row = $collection->first();
        if (empty($row[1]) && !empty($row[5]) && strtoupper(trim($row[5])) != strtoupper('JABATAN')) {
            throw new \Exception("MESSAGE--File tidak tidak sesuai dengan template terbaru. Silahkan download template kembali!", 1);
        }

        foreach ($collection as $rw => $row) 
        {
            if ($rw == 0) continue;

            $name         = trim($row[1] ?? '');
            $username     = trim($row[2] ?? '');
            $email        = trim($row[3] ?? '');
            $password     = trim($row[4] ?? '');
            $positionName = trim($row[5] ?? '');
            $locationName = trim($row[6] ?? '');
            $roleName     = trim($row[7] ?? '');

            // Check Role
            $role = Role::whereName($roleName)->first();
            if (!$role) {
                throw new \Exception('MESSAGE--Role: "'.$roleName.'" tidak tersedia!', 1);
            }
            // Check Location
            $struct = OrgStruct::whereName($locationName)->first();
            if (!$struct) {
                throw new \Exception('MESSAGE--Lokasi/Struktur: "'.$locationName.'" tidak tersedia!', 1);
            }
            // Check Position
            $position = $struct->positions()->whereName($positionName)->first();
            if (!$position) {
                throw new \Exception('MESSAGE--Jabatan: "'.$positionName.'" dengan Lokasi: "'.$locationName.'" tidak tersedia!', 1);
            }

            // Simpan User
            $user = User::where('username', $username)->first();
            if (!$user) {
                $user = new User;
                $user->username = $username;
                $user->password = bcrypt($password);
            }
            $user->name = $name;
            $user->email = $email;
            $user->position_id = $position->id;
            $user->save();
            $user->syncRoles($role);
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
