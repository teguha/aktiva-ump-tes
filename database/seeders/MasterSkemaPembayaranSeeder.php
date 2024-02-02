<?php

namespace Database\Seeders;

use App\Models\Master\SkemaPembayaran\SkemaPembayaran;
use Illuminate\Database\Seeder;

class MasterSkemaPembayaranSeeder extends Seeder
{
    public function run()
    {
        $skemaPembayaran = [
            [
                'name'          => 'Skema Pembayaran 1',
            ],
            [
                'name'          => 'Skema Pembayaran 2',
            ],
            [
                'name'          => 'Skema Pembayaran 3',
            ],
        ];

        $this->generate($skemaPembayaran);
    }

    public function generate($skemaPembayaran)
    {
        ini_set("memory_limit", -1);

        foreach ($skemaPembayaran as $val) {
            $skemaPembayaran = SkemaPembayaran::firstOrNew(['name' => $val['name']]);
           // $skemaPembayaran->level   = $val['level'];
            $skemaPembayaran->name    = $val['name'];
            $skemaPembayaran->save();
        }
    }

    public function countActions($data)
    {
        $count = count($data);

        return $count;
    }
}
