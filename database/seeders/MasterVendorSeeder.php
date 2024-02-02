<?php

namespace Database\Seeders;

use App\Models\Master\Barang\Vendor;
use App\Models\Master\Geo\City;
use App\Models\Master\Geo\Province;
use Illuminate\Database\Seeder;

class MasterVendorSeeder extends Seeder
{
    public function run()
    {
        $vendor = [
            [
                'id_vendor'         => '001',
                'name'              => 'ACE HARDWARE - Kelapa Gading',
                'address'           => 'Blk. XC -9, Jl. Raya Boulevard Barat Blok XC - 9 No.3, Blok XC -9, RW.5, Klp. Gading Bar., Kec. Klp. Gading, Jkt Utara, Daerah Khusus Ibukota Jakarta 14240',
                'telp'              => '(021) 45846664',
                'email'             => 'acehwklpgdng@email.com',
                'contact_person'    => 'Sundar Pichai',
                'status'            => '2',
            ],
            [
                'id_vendor'         => '002',
                'name'              => 'ACE Hardware Batununggal Bandung',
                'address'           => 'Jl. Batununggal Indah IV No.1-3, RT.008/RW.001, Batununggal, Kec. Bandung Kidul, Kota Bandung, Jawa Barat 40266',
                'telp'              => '0857-4963-6035',
                'email'             => 'acehwbatununggal@email.com',
                'contact_person'    => 'Jeff Bezos',
                'status'            => '1',
            ],
            [
                'id_vendor'         => '003',
                'name'              => 'Jakartanotebook Benhil',
                'address'           => 'Jl. Bendungan Hilir No.10, Bend. Hilir, Kecamatan Tanah Abang, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10210',
                'telp'              => '(021) 39700200',
                'email'             => 'jakartanotebookbenhil@email.com',
                'contact_person'    => 'Shou Zi Chew',
                'status'            => '2',
            ],
            [
                'id_vendor'         => '004',
                'name'              => 'Bhinneka.Com - Mangga Dua Mall',
                'address'           => 'Jl. Mangga Dua Mal No.48–49, Mangga Dua Sel., Kecamatan Sawah Besar, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10730',
                'telp'              => '(021) 62301383',
                'email'             => 'bhinnekamanggadua@email.com',
                'contact_person'    => 'Tim Cook',
                'status'            => '2',
            ],
            [
                'id_vendor'         => '005',
                'name'              => 'Bank OCBC NISP Bandung',
                'address'           => 'Jl. Tm. Cibeunying Sel. No.31, Lkr. Sel., Kec. Bandung Wetan, Kota Bandung, Jawa Barat 40114',
                'telp'              => '(022) 7159888',
                'email'             => 'ocbcnisp@email.com',
                'contact_person'    => 'Elizabeth Holmes',
                'status'            => '1',
            ],
        ];

        $this->generate($vendor);
    }

    public function generate($vendor)
    {
        ini_set("memory_limit", -1);

        foreach ($vendor as $val) {
            $vendor = Vendor::firstOrNew(['id_vendor' => $val['id_vendor']]);
           // $vendor->level   = $val['level'];
            $vendor->name             = $val['name'];
            $vendor->status             = $val['status'];
            $vendor->address            = $val['address'];
            $vendor->telp               = $val['telp'];
            $vendor->email              = $val['email'];
            $vendor->contact_person     = $val['contact_person'];
            $vendor->save();
        }
    }

    public function countActions($data)
    {
        $count = count($data);

        return $count;
    }
}
