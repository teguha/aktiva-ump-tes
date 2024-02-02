<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use App\Models\Master\RekeningBank\RekeningBank;
use Illuminate\Database\Seeder;

class RekeningSeeder extends Seeder
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
                'user_id' => 6,
                'no_rekening' => 123456,
                'kcp' => 'Kelapa Gading',
                'bank_id' => 3,
                'created_by' => 1,
            ],
            [
                'user_id' => 2,
                'no_rekening' => 114477,
                'kcp' => 'Rancaekek',
                'bank_id' => 2,
                'created_by' => 1,
            ],
            [
                'user_id' => 3,
                'no_rekening' => 225588,
                'kcp' => 'Jatinangor',
                'bank_id' => 6,
                'created_by' => 1,
            ],
            [
                'user_id' => 5,
                'no_rekening' => 665544,
                'kcp' => 'Basuki Rahmad',
                'bank_id' => 1,
                'created_by' => 1,
            ],
            [
                'user_id' => 4,
                'no_rekening' => 995511,
                'kcp' => 'Cipacing',
                'bank_id' => 5,
                'created_by' => 1,
            ],
        ];

        $this->command->getOutput()->progressStart($this->countActions($banks));
        //Metode countActions() mulai menghitung jumlah data yang akan di-seed dalam $data.
        $this->generate($banks);
        $this->command->getOutput()->progressFinish();
        // Setelah selesai, progress bar akan diakhiri dengan perintah $this->command->getOutput()->progressFinish()
    }

    public function generate($data)
    {
        ini_set("memory_limit", -1);
        // Fungsi ini_set("memory_limit", -1); bertujuan untuk menetapkan batas memori pada -1, yang berarti tak terbatas. Ini dilakukan untuk menghindari batasan memori saat melakukan proses seeding jika dataset besar.

        foreach ($data as $val) {
            $this->command->getOutput()->progressAdvance();
            // Metode generate() akan dijalankan untuk memasukkan data ke dalam tabel, dan pada setiap pengisian data, progress bar akan diupdate menggunakan $this->command->getOutput()->progressAdvance() hingga semua data dimasukkan.
            $bank = new RekeningBank();
            $bank->user_id = $val['user_id'];
            $bank->no_rekening = $val['no_rekening'];
            $bank->kcp = $val['kcp'];
            $bank->bank_id = $val['bank_id'];
            $bank->created_by = $val['created_by'];
            $bank->save();
        }
    }

    public function countActions($data)
    {
        $count = count($data);

        return $count;
    }
}


