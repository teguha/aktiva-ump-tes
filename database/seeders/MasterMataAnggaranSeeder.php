<?php

namespace Database\Seeders;

use App\Models\Master\MataAnggaran\MataAnggaran;
use Illuminate\Database\Seeder;

class MasterMataAnggaranSeeder extends Seeder
{
    public function run()
    {
        $mataAnggaran = [
            [
                'nama'              => 'Inventaris Kantor',
                'mata_anggaran'     => '5130501',
                'deskripsi'         => null,
                'status'            => '2',
            ],
            [
                'nama'              => 'Aset Berwujud',
                'mata_anggaran'     => '1440104',
                'deskripsi'         => null,
                'status'            => '2',
            ],
        ];

        $this->generate($mataAnggaran);
    }

    public function generate($mataAnggaran)
    {
        ini_set("memory_limit", -1);

        foreach ($mataAnggaran as $val) {
            $mataAnggaran = MataAnggaran::firstOrNew(['mata_anggaran' => $val['mata_anggaran']]);
            $mataAnggaran->nama             = $val['nama'];
            $mataAnggaran->mata_anggaran    = $val['mata_anggaran'];
            $mataAnggaran->deskripsi        = $val['deskripsi'];
            $mataAnggaran->status           = $val['status'];
            $mataAnggaran->save();
        }
    }

    public function countActions($data)
    {
        $count = count($data);

        return $count;
    }
}
