<?php

namespace Database\Seeders;

use App\Models\Master\JenisPembayaran\JenisPembayaran;
use Illuminate\Database\Seeder;

class MasterJenisPembayaranSeeder extends Seeder
{
    public function run()
    {
        $jenisPembayaran = [
            // type => 1:presdir, 2:direktur, 3:ia division, 4:it division
            // Level Root
            [
                'name'          => 'Jenis Pembayaran 1',
            ],
            [
                'name'          => 'Jenis Pembayaran 2',
            ],
            [
                'name'          => 'Jenis Pembayaran 3',
            ],
            // // Level BOC
            // [
            //     'level'         => 'boc',
            //     'name'          => 'Dewan Komisaris',
            //     'phone'         => config('base.company.phone'),
            //     'address'       => config('base.company.address'),
            //     'parent_code'   => 1001,
            //     'code'          => 1101,
            //     'type'          => null,
            // ],
            // [
            //     'level'         => 'boc',
            //     'name'          => 'Komite Audit',
            //     'phone'         => config('base.company.phone'),
            //     'address'       => config('base.company.address'),
            //     'parent_code'   => 1001,
            //     'code'          => 1102,
            //     'type'          => null,
            // ],
            // // Level BOD
            // [
            //     'level'         => 'bod',
            //     'name'          => 'Direktur Utama',
            //     'phone'         => config('base.company.phone'),
            //     'address'       => config('base.company.address'),
            //     'parent_code'   => 1001,
            //     'code'          => 2001,
            //     'type'          => 'presdir',
            // ],
            // // Level Division
            // [
            //     'level'         => 'division',
            //     'name'          => 'Divisi Audit Internal',
            //     'phone'         => config('base.company.phone'),
            //     'address'       => config('base.company.address'),
            //     'parent_code'   => 2001,
            //     'code'          => 3001,
            //     'type'          => 'ia',
            // ],
            // [
            //     'level'         => 'division',
            //     'name'          => 'Divisi Teknologi Informasi',
            //     'phone'         => config('base.company.phone'),
            //     'address'       => config('base.company.address'),
            //     'parent_code'   => 2001,
            //     'code'          => 3002,
            //     'type'          => 'it',
            // ],
        ];

        $this->generate($jenisPembayaran);
    }

    public function generate($jenisPembayaran)
    {
        ini_set("memory_limit", -1);

        foreach ($jenisPembayaran as $val) {
            $jenisPembayaran = JenisPembayaran::firstOrNew(['name' => $val['name']]);
           // $jenisPembayaran->level   = $val['level'];
            $jenisPembayaran->name    = $val['name'];
            $jenisPembayaran->save();
        }
    }

    public function countActions($data)
    {
        $count = count($data);

        return $count;
    }
}
