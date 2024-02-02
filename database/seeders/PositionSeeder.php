<?php

namespace Database\Seeders;

use App\Models\Master\Org\Kelompok;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Org\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        //generate data posisi
        $data = [
            [
                'name' => 'Kepala Divisi Bisnis',
                'code' => 1001,
                'parent' => 3001, //kode terhubung ke table organisasi untuk mendapatkan lokasi jbatan
                'kelompok' => 4,
            ],
            [
                'name' => 'Direktur',
                'code' => 1002,
                'parent' => 2001,
                'kelompok' => 5,
            ],
            [
                'name' => 'Officer Divisi Bisnis',
                'code' => 1002,
                'parent' => 3001,
                'kelompok' => 3,
            ],
        ];

    
        foreach ($data as $key => $val) {
            // if ($struct = OrgStruct::where('code', $val['parent'])->first()) {
                $struct = OrgStruct::where('code', $val['parent'])->first();
                 //Mencari struktur organisasi (OrgStruct) berdasarkan kode yang diberikan pada 'parent'. Kode ini adalah kode dari posisi induk untuk posisi yang akan dimasukan

                // $kelompok = Kelompok::where('name', $val['kelompok'])->first();

                $position = Position::firstOrNew(['name' => $val['name']]);
                // Menggunakan fungsi firstOrNew() untuk mencari posisi kerja berdasarkan nama posisi yang akan dimasukkan. Jika posisi sudah ada dalam database, maka data posisi tersebut akan diambil. Jika tidak ada, maka objek baru Position akan dibuat.

                //generate data posisi
                $position->name = $val['name']; // isi name
                $position->code = $val['code']; // isi code

                $position->location_id = $struct ? $struct->id : null; // isi organisasi id
                $position->kelompok_id = $val['kelompok']; //isi kelompok
                // $position->kelompok_id = $kelompok ? $kelompok->id : null;
                if(isset($val['parent_code'])){
                    $parent = Position::where('code', $val['parent_code'])->first();
                    $position->parent_id = $parent->id;
                }
                // isset($val['parent_code']) digunakan untuk memeriksa apakah ada kunci 'parent_code' dalam array data $val. Kunci ini menunjukkan bahwa data posisi kerja memiliki posisi induk (parent position).
                $position->save();
            // }
        }
    }
}
