<?php

namespace Database\Seeders;

use App\Models\Master\Bank\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [
            [
                'bank'      => 'Bank Mandiri Jakarta Mal Kelapa Gading',
                'alamat'    => 'Mal Kelapa Gading 3, Unit LG 47, Jl. Bulevar Blok M, Kelapa Gading, Kelapa Gading Timur, Kelapa Gading, RT.9/RW.11, Klp. Gading Tim., Jakarta Utara, Jkt Utara, Daerah Khusus Ibukota Jakarta 14240',
            ],
            [
                'bank'      => 'Bank Mandiri KCP Bandung Buah Batu',
                'alamat'    => 'Jl. Buah Batu No.268, Cijagra, Kec. Lengkong, Kota Bandung, Jawa Barat 40265',
            ],
            [
                'bank'      => 'Bank BCA KCU Asia Afrika',
                'alamat'    => 'Jl. Asia Afrika No.122-124, Paledang, Kec. Lengkong, Kota Bandung, Jawa Barat 40261',
            ],
        ];

        foreach ($banks as $val) {
            $bank = new Bank();
            $bank->bank    = $val['bank'];
            $bank->alamat  = $val['alamat'];
            $bank->status  = 2;
            $bank->save();
        }
    }
}
