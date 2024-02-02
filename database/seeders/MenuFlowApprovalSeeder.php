<?php

namespace Database\Seeders;

use App\Models\Globals\MenuFlow as GlobalsMenuFlow;
use Illuminate\Database\Seeder;

class MenuFlowApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            // Pembelian Aktiva
            [
                "menu_id" => 1,
                "role_id" => 3,
                "type" => 1,
                "order" => 1
            ],
            // Biaya Operasional
            [
                "menu_id" => 2,
                "role_id" => 3,
                "type" => 1,
                "order" => 1
            ],
            // Sewa Guna Usaha
            [
                "menu_id" => 3,
                "role_id" => 3,
                "type" => 1,
                "order" => 1
            ],
            // Uang Muka Pembayaran
            [
                "menu_id" => 5,
                "role_id" => 3,
                "type" => 1,
                "order" => 1
            ],
            [
                "menu_id" => 6,
                "role_id" => 3,
                "type" => 1,
                "order" => 1
            ],
            [
                "menu_id" => 7,
                "role_id" => 3,
                "type" => 1,
                "order" => 1
            ],
            [
                "menu_id" => 8,
                "role_id" => 3,
                "type" => 1,
                "order" => 1
            ],
            // Termin Pembayaran
            [
                "menu_id" => 10,
                "role_id" => 3,
                "type" => 1,
                "order" => 1
            ],
            [
                "menu_id" => 11,
                "role_id" => 3,
                "type" => 1,
                "order" => 1
            ],
            // Pelepasan Aktiva
            [
                "menu_id" => 13,
                "role_id" => 3,
                "type" => 1,
                "order" => 1
            ],
            [
                "menu_id" => 14,
                "role_id" => 3,
                "type" => 1,
                "order" => 1
            ],
            // Mutasi Aktiva
            [
                "menu_id" => 15,
                "role_id" => 3,
                "type" => 1,
                "order" => 1
            ],
        ];

        foreach ($data as $val) {
            //sys_menu_flow
            $record = GlobalsMenuFlow::firstOrNew([
                'menu_id' => $val['menu_id'],
                'role_id' => $val['role_id'],
                'type' => $val['type'],
                'order' => $val['order'],
            ]);
            $record->save();
        }
    }
}
