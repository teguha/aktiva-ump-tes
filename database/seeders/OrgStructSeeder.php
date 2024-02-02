<?php

namespace Database\Seeders;

use App\Models\Master\Org\OrgStruct;
use Illuminate\Database\Seeder;

class OrgStructSeeder extends Seeder
{
    public function run()
    {
        $structs = [
            // type => 1:presdir, 2:direktur, 3:ia division, 4:it division
            // Level Root
            [
                'level'         => 'root',
                'name'          => config('base.company.name'),
                'phone'         => config('base.company.phone'),
                'address'       => config('base.company.address'),
                'website'       => 'www.galeri24.co.id',
                'email'         => 'galeri24@email.com',
                'city_id'       => 156,
                'code'          => 1001,
                'type'          => 0,
            ],
            // // Level BOC
            // [
            //     'level'         => 'boc',
            //     'name'          => 'Dewan Komisaris',
            //     'phone'         => config('base.company.phone'),
            //     'address'       => config('base.company.address'),
            //     'parent_code'   => 1001,
            //     'code'          => 1101,
            //     'type'          => 0,
            // ],
            // [
            //     'level'         => 'boc',
            //     'name'          => 'Komite Audit',
            //     'phone'         => config('base.company.phone'),
            //     'address'       => config('base.company.address'),
            //     'parent_code'   => 1001,
            //     'code'          => 1102,
            //     'type'          => 0,
            // ],
            // Level BOD
            [
                'level'         => 'bod',
                'name'          => 'Direktur',
                'phone'         => config('base.company.phone'),
                'address'       => config('base.company.address'),
                'parent_code'   => 1001,
                'code'          => 2001,
                'type'          => 0,
            ],
            // Level Division
            [
                'level'         => 'division',
                'name'          => 'Divisi Bisnis',
                'phone'         => config('base.company.phone'),
                'address'       => config('base.company.address'),
                'parent_code'   => 2001,
                'code'          => 3001,
                'type'          => 0,
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi Suporting',
                'phone'         => config('base.company.phone'),
                'address'       => config('base.company.address'),
                'parent_code'   => 2001,
                'code'          => 3002,
                'type'          => 0,
            ],
            [
                'level'         => 'division',
                'name'          => 'Regional',
                'phone'         => config('base.company.phone'),
                'address'       => config('base.company.address'),
                'parent_code'   => 2001,
                'code'          => 3003,
                'type'          => 0,
            ],
            // Level Departement
            [
                'level'         => 'department',
                'name'          => 'Departemen Kesekretariatan',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 3001,
                'code'          => 4001,
                'type'          => 0,
            ],
            [
                'level'         => 'department',
                'name'          => 'Departemen Pengembangan Bisnis',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 3001,
                'code'          => 4002,
                'type'          => 0,
            ],
            [
                'level'         => 'department',
                'name'          => 'Departemen Stock & Distribusi',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 3001,
                'code'          => 4003,
                'type'          => 0,
            ],
            [
                'level'         => 'department',
                'name'          => 'Departemen Jaringan Operasional',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 3001,
                'code'          => 4004,
                'type'          => 0,
            ],
            [
                'level'         => 'department',
                'name'          => 'Departemen IT',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 3002,
                'code'          => 4005,
                'type'          => 0,
            ],
            [
                'level'         => 'department',
                'name'          => 'Departemen Finance & Accounting',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 3002,
                'code'          => 4006,
                'type'          => 0,
            ],
            [
                'level'         => 'department',
                'name'          => 'Departemen HR',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 3002,
                'code'          => 4007,
                'type'          => 0,
            ],
            [
                'level'         => 'department',
                'name'          => 'Departemen Audit & Compliance',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 3002,
                'code'          => 4008,
                'type'          => 0,
            ],
            [
                'level'         => 'branch',
                'name'          => 'Regional Jawa Barat',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 2001,
                'code'          => 5001,
                'type'          => 0,
            ],
            [
                'level'         => 'branch',
                'name'          => 'Regional Jawa Tengah',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 2001,
                'code'          => 5002,
                'type'          => 0,
            ],
            [
                'level'         => 'branch',
                'name'          => 'Regional Jawa Timur',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 2001,
                'code'          => 5003,
                'type'          => 0,
            ],
            [
                'level'         => 'subbranch',
                'name'          => 'Outlet Bandung',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 5001,
                'code'          => 6001,
                'type'          => 0,
            ],
            [
                'level'         => 'subbranch',
                'name'          => 'Outlet Cimahi',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 5001,
                'code'          => 6002,
                'type'          => 0,
            ],
            [
                'level'         => 'subbranch',
                'name'          => 'Outlet Garut',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 5001,
                'code'          => 6003,
                'type'          => 0,
            ],
            [
                'level'         => 'subbranch',
                'name'          => 'Outlet Semarang',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 5002,
                'code'          => 6004,
                'type'          => 0,
            ],
            [
                'level'         => 'subbranch',
                'name'          => 'Outlet Cilacap',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 5002,
                'code'          => 6005,
                'type'          => 0,
            ],
            [
                'level'         => 'subbranch',
                'name'          => 'Outlet Surabaya',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 5003,
                'code'          => 6006,
                'type'          => 0,
            ],
            [
                'level'         => 'subbranch',
                'name'          => 'Outlet Madiun',
                'phone'         => NULL,
                'address'       => NULL,
                'parent_code'   => 5003,
                'code'          => 6007,
                'type'          => 0,
            ],
            // Level Branch
            // [
            //     'level'         => 'branch',
            //     'name'          => 'Wilayah Kalimantan',
            //     'phone'         => NULL,
            //     'address'       => NULL,
            //     'parent_code'   => 2001,
            //     'code'          => 5001,
            //     'type'          => 0,
            // ],
            
        ];

        $this->generate($structs);
    }

    public function generate($structs)
    {
        ini_set("memory_limit", -1);

        foreach ($structs as $val) {
            $struct = OrgStruct::firstOrNew(['code' => $val['code']]);
            $struct->level   = $val['level'];
            $struct->name    = $val['name'];
            $struct->type    = $val['type'] ?? 0;
            $struct->phone   = $val['phone'] ?? null;
            $struct->address = $val['address'] ?? null;
            $struct->email   = $val['email'] ?? null;
            $struct->website   = $val['website'] ?? null;
            $struct->city_id   = $val['city_id'] ?? null;
            $struct->status = 2;
            if (!empty($val['parent_code'])) {
                if ($parent = OrgStruct::where('code', $val['parent_code'])->first()) {
                    $struct->parent_id = $parent->id;
                }
            }
            $struct->save();
        }
    }

    public function countActions($data)
    {
        $count = count($data);

        return $count;
    }
}
