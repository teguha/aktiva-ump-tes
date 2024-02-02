<?php

namespace Database\Seeders;

use App\Models\Master\CaraPembayaran\CaraPembayaran;
use Illuminate\Database\Seeder;

class MasterCaraPembayaranSeeder extends Seeder
{
    public function run()
    {
        $caraPembayaran = [
            [
                'nama'          => 'Cara Pembayaran 1',
            ],
            [
                'nama'          => 'Cara Pembayaran 2',
            ],
            [
                'nama'          => 'Cara Pembayaran 3',
            ],
        ];

        $this->generate($caraPembayaran);
    }

    public function generate($caraPembayaran)
    {
        ini_set("memory_limit", -1);

        foreach ($caraPembayaran as $val) {
            $caraPembayaran = CaraPembayaran::firstOrNew(['nama' => $val['nama']]);
           // $caraPembayaran->level   = $val['level'];
            $caraPembayaran->nama    = $val['nama'];
            $caraPembayaran->save();
        }
    }

    public function countActions($data)
    {
        $count = count($data);

        return $count;
    }
}
