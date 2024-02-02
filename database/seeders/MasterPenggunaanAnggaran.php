<?php

namespace Database\Seeders;

use App\Models\Master\Penggunaan\Penggunaan;
use Illuminate\Database\Seeder;

class MasterPenggunaanAnggaran extends Seeder
{
    public function run()
    {
        $penggunaan = [
            [
                'name'          => 'Penggunaan Anggaran 1',
            ],
            [
                'name'          => 'Penggunaan Anggaran 2',
            ],
            [
                'name'          => 'Penggunaan Anggaran 3',
            ],
        ];

        $this->generate($penggunaan);
    }

    public function generate($penggunaan)
    {
        ini_set("memory_limit", -1);

        foreach ($penggunaan as $val) {
            $penggunaan = Penggunaan::firstOrNew(['name' => $val['name']]);
           // $penggunaan->level   = $val['level'];
            $penggunaan->name    = $val['name'];
            $penggunaan->save();
        }
    }

    public function countActions($data)
    {
        $count = count($data);

        return $count;
    }
}
