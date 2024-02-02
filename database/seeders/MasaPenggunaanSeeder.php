<?php

namespace Database\Seeders;

use App\Models\Master\MasaPenggunaan\MasaPenggunaan;
use Illuminate\Database\Seeder;

class MasaPenggunaanSeeder extends Seeder
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
                'masa_penggunaan'   => "2",
                'status'            => "2",
            ],
            [
                'masa_penggunaan'   => "3",
                'status'            => "2",
            ],
            [
                'masa_penggunaan'   => "4",
                'status'            => "2",
            ],
            [
                'masa_penggunaan'   => "5",
                'status'            => "2",
            ],
            [
                'masa_penggunaan'   => "6",
                'status'            => "3",
            ],
        ];

        foreach ($data as $val) {
            $record = MasaPenggunaan::firstOrCreate(
                [
                    'masa_penggunaan'   => $val['masa_penggunaan'],
                    'status'            => $val['status'],
                ]
            );
        }
    }
}
