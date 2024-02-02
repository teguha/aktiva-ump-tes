<?php

namespace Database\Seeders;

use App\Models\Master\Geo\City;
use App\Models\Master\Geo\Province;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('database/seeders/json/city.json');
        $json = File::get($path);
        $data = json_decode($json);

        $this->generate($data);
    }

    public function generate($data)
    {
        foreach ($data as $val) {
            $prov = Province::where('code', $val->province_id)->first();
            $kab = City::where('code', $val->id)
                ->first();
            if (!$kab) {
                $kab = new City;
            }
            $kab->province_id = $prov->id;
            $kab->name = $val->name;
            $kab->code = $val->id;
            $kab->created_by = $val->created_by;
            $kab->created_at = \Carbon\Carbon::now();
            $kab->save();
        }
    }
}
