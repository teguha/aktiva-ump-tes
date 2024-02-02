<?php

namespace Database\Seeders;

use App\Models\Master\Org\Kelompok;
use Illuminate\Database\Seeder;

class KelJabatanSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $kelompok = [

            [
                'name' => "Staff",
                'status' => 2
            ],
            [
                'name' => "First Line Manager",
                'status' => 2
            ],
            [
                'name' => "Middle Management",
                'status' => 2
            ],
            [
                'name' => "Senior Management",
                'status' => 2
            ],
            [
                'name' => "Board of Directors",
                'status' => 2
            ],
            [
                'name'   => "Board of Commisioners",
                'status' => 2
            ],
        ];
        
        $this->generate($kelompok);
    }
    
    public function generate($data)
    {
        ini_set("memory_limit", -1);
        
        foreach ($data as $val) {
            $bank = Kelompok::firstOrCreate(
                [
                    'name' => $val['name'],
                    'status' => $val['status'],
                    ]
                );
            }
        }
    }
    