<?php

namespace Database\Seeders;

use App\Models\Master\Jurnal\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
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
                'kategori'      => "Pengajuan Aktiva",
                'nama_template' => "Tangible Asset",
                'status'        => "2",
            ],
            [
                'kategori'      => "Pengajuan Aktiva",
                'nama_template' => "Intangible Asset",
                'status'        => "2",
            ],
            [
                'kategori'      => "Pengajuan Hak Guna Usaha",
                'nama_template' => "Pengajuan Hak Guna Usaha",
                'status'        => "2",
            ],
            [
                'kategori'      => "UMP",
                'nama_template' => "Pembayaran UMP",
                'status'        => "2",
            ],
            [
                'kategori'      => "UMP",
                'nama_template' => "Pembayaran pertanggungjawaban UMP",
                'status'        => "1",
            ],
            [
                'kategori'      => "Penghapusan Aktiva",
                'nama_template' => "Habis masa manfaat dan tidak ada nilai residu",
                'status'        => "1",
            ],
            [
                'kategori'      => "Penghapusan Aktiva",
                'nama_template' => "Habis masa manfaat dan ada nilai residu",
                'status'        => "1",
            ],
            [
                'kategori'      => "Penghapusan Aktiva",
                'nama_template' => "Masih masa manfaat, rusak, dan tidak ada nilai residu",
                'status'        => "1",
            ],
            [
                'kategori'      => "Penghapusan Aktiva",
                'nama_template' => "Masih masa manfaat, rusak, dan ada nilai residu",
                'status'        => "1",
            ],
            [
                'kategori'      => "Pengajuan Aktiva",
                'nama_template' => "Tangible Asset",
                'status'        => "1",
            ],
            [
                'kategori'      => "Pengajuan Aktiva",
                'nama_template' => "Intangible Asset",
                'status'        => "1",
            ],
            [
                'kategori'      => "Pengajuan Hak Guna Usaha",
                'nama_template' => "Pengajuan Hak Guna Usaha",
                'status'        => "1",
            ],
            [
                'kategori'      => "UMP",
                'nama_template' => "Pembayaran UMP",
                'status'        => "1",
            ],
        ];

        foreach ($data as $val) {
            $record = Template::firstOrCreate(
                [
                    'kategori'          => $val['kategori'],
                    'nama_template'     => $val['nama_template'],
                    'status'            => $val['status'],
                ]
            );
        }
    }
}
