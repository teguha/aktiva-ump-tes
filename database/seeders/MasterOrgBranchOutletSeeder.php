<?php

namespace Database\Seeders;

use App\Models\Master\Org\OrgStruct;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class MasterOrgBranchOutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('database/seeders/json/branch.json');
        $json = File::get($path);
        $data = json_decode($json);

        $this->generate($data);
    }

    public function generate($data)
    {
        $model = new OrgStruct();
        $dir = $model->presdir()->get()->first();
        for ($i = 1; $i < 13; $i++) {
            $regional = OrgStruct::create(
                [
                    'parent_id' => $dir->id,
                    'name' => 'Regional ' . $i,
                    'code' => '500' . $i,
                    'phone' => '(0274) 561614',
                    'level' => 'branch',
                    'address' => 'Jl. Tentara Pelajar No. 7 Yogyakarta',
                    'status' => 2,
                ]
            );
            foreach ($data[($i - 1)] as $outlet) {
                OrgStruct::create(
                    [
                        'parent_id' => $regional->id,
                        'name' => $outlet->name,
                        'code' => $model->getNewCode('subbranch'),
                        'phone' => '(0274) 561614',
                        'level' => 'subbranch',
                        'address' => 'Jl. Tentara Pelajar No. 7 Yogyakarta',
                        'status' => 2,
                    ]
                );
            }
        }
    }
}
