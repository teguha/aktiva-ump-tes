<?php

namespace Database\Seeders;

use App\Models\Master\Jurnal\COA;
use Illuminate\Database\Seeder;

class COASeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coas = [
            [
                'kode_akun' => '1160101360',
                'nama_akun' => 'UMP',
                'tipe_akun' => 'biaya',
                'deskripsi' => null,
                'status'    => 2,
            ],
            [
                'kode_akun' => '1120404360',
                'nama_akun' => 'Bank',
                'tipe_akun' => 'biaya',
                'deskripsi' => null,
                'status'    => 2,
            ],
            [
                'kode_akun' => '5220104',
                'nama_akun' => 'Biaya penyusutan non meubelair kantor',
                'tipe_akun' => 'biaya',
                'deskripsi' => null,
                'status'    => 2,
            ],
            [
                'kode_akun' => '1450104',
                'nama_akun' => 'Akumulasi penyusutan non meubelair kantor',
                'tipe_akun' => 'aset',
                'deskripsi' => null,
                'status'    => 2,
            ],
            //
            [
                'kode_akun' => '5240109',
                'nama_akun' => 'Biaya amortisasi aset tidak berwujud',
                'tipe_akun' => 'biaya',
                'deskripsi' => null,
                'status'    => 2,
            ],
            [
                'kode_akun' => '1450301',
                'nama_akun' => 'Akumulasi amortisasi aset tidak berwujud',
                'tipe_akun' => 'aset',
                'deskripsi' => null,
                'status'    => 2,
            ],
            [
                'kode_akun' => '4530102',
                'nama_akun' => 'Pendapatan lainnya',
                'tipe_akun' => 'pendapatan',
                'deskripsi' => null,
                'status'    => 2,
            ],
            [
                'kode_akun' => '5250101',
                'nama_akun' => 'Beban amortisasi SGU',
                'tipe_akun' => 'biaya',
                'deskripsi' => null,
                'status'    => 2,
            ],
            [
                'kode_akun' => '1720101',
                'nama_akun' => 'Akumulasi amortisasi aktiva tetap SGU bangunan kantor',
                'tipe_akun' => 'aset',
                'deskripsi' => null,
                'status'    => 2,
            ],
        ];

        foreach ($coas as $val) {
            $coa = new COA();
            $coa->kode_akun = $val['kode_akun'];
            $coa->nama_akun = $val['nama_akun'];
            $coa->tipe_akun = $val['tipe_akun'];
            $coa->deskripsi = $val['deskripsi'];
            $coa->status    = $val['status'];
            $coa->save();
        }
    }
}
