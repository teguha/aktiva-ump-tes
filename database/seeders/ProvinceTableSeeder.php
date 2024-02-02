<?php

namespace Database\Seeders;

use App\Models\Master\Geo\Province;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class ProvinceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('database/seeders/json/province.json');
        $json = File::get($path);
        $data = json_decode($json);

        $this->generate($data);
    }

    public function generate($data)
    {
        foreach ($data as $val) {
            $prov = Province::where('name', $val->name)->first();

            //jika nama provinsi belum ada maka di buat baru
            if (!$prov) {
                $prov = new Province;
            }
            // $prov->id = $val->id;
            $prov->name = $val->name;
            $prov->code = $val->id;
            $prov->created_by = 1;
            $prov->created_at = \Carbon\Carbon::now();
            $prov->save();
        }
    }
}
