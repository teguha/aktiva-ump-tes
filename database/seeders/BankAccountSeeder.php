<?php

namespace Database\Seeders;

use App\Models\Master\Bank\BankAccount;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "user_id" => 3,
                "number" => "100200300",
                "bank" => "bca"
            ],
            [
                "user_id" => 2,
                "number" => "500600700",
                "bank" => "btn"
            ],
            [
                "user_id" => 4,
                "number" => "700800900",
                "bank" => "bri"
            ],
        ];

        foreach ($data as $val) {
            $record          = BankAccount::firstOrNew(['user_id' => $val['user_id']]);
            $record->number = $val['number'];
            $record->bank = $val['bank'];
            $record->save();
        }
    }
}
