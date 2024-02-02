<?php

namespace Database\Seeders;

use App\Models\Master\RekeningBank\Bank;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class MasterBankAccount extends Seeder
{
    public function run()
    {
        $path = base_path('database/seeders/json/bank.json');
        $json = File::get($path);
        $data = json_decode($json);

        $this->generate($data);
    }

    public function generate($data)
    {
        foreach ($data as $bank) {
            Bank::firstOrCreate(
                [
                    'name' => $bank->name,
                ]
            );
        }
    }
}
