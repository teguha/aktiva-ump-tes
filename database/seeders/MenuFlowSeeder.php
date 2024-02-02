<?php

namespace Database\Seeders;

use App\Models\Globals\Menu;
use App\Models\Globals\MenuFlow;
use Illuminate\Database\Seeder;

class MenuFlowSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'module'   => 'pengajuan-pembelian',
                'FLOWS'     => [
                    [
                        "role_id"   => 3,
                        "type"      => 1,
                    ],
                ],
            ],
            [
                'module'   => 'operational-cost',
                'FLOWS'     => [
                    [
                        "role_id"   => 3,
                        "type"      => 1,
                    ],
                ],
            ],
            [
                'module'   => 'sgu',
                'FLOWS'     => [
                    [
                        "role_id"   => 3,
                        "type"      => 1,
                    ],
                ],
            ],
            [
                'module'   => 'ump',
                'submenu' => [
                    [
                        'module'   => 'ump.pengajuan-ump',
                        'FLOWS'     => [
                            [
                                "role_id"   => 3,
                                "type"      => 1,
                            ],
                        ],
                    ],
                    [
                        'module'   => 'ump.pembatalan-ump',
                        'FLOWS'     => [
                            [
                                "role_id"   => 3,
                                "type"      => 1,
                            ],
                        ],
                    ],
                    [
                        'module'   => 'ump.perpanjangan-ump',
                        'FLOWS'     => [
                            [
                                "role_id"   => 3,
                                "type"      => 1,
                            ],
                        ],
                    ],
                    [
                        'module'   => 'ump.pembayaran-ump',
                        'FLOWS'     => [
                            [
                                "role_id"   => 3,
                                "type"      => 1,
                            ],
                        ],
                    ],
                    [
                        'module'   => 'ump.pj-ump',
                        'FLOWS'     => [
                            [
                                "role_id"   => 3,
                                "type"      => 1,
                            ],
                        ],
                    ]
                ]
            ],
            [
                'module'   => 'termin',
                'submenu' => [
                    [
                        'module'   => 'termin.pengajuan',
                        'FLOWS'     => [
                            [
                                "role_id"   => 3,
                                "type"      => 1,
                            ],
                        ],
                    ],
                    [
                        'module'   => 'termin.pembayaran',
                        'FLOWS'     => [
                            [
                                "role_id"   => 3,
                                "type"      => 1,
                            ],
                        ],
                    ],
                ]
            ],
            [
                'module'   => 'pemeriksaan',
                'FLOWS'     => [
                    [
                        "role_id"   => 3,
                        "type"      => 1,
                    ],
                ],
            ],
            [
                'module'   => 'penghapusan',
                'submenu' => [
                    [
                        'module'   => 'penghapusan.pengajuan',
                        'FLOWS'     => [
                            [
                                "role_id"   => 3,
                                "type"      => 1,
                            ],
                        ],
                    ],
                    [
                        'module'   => 'penghapusan.pelaksanaan',
                        'FLOWS'     => [
                            [
                                "role_id"   => 3,
                                "type"      => 1,
                            ],
                        ],
                    ],
                ]
            ],
            [
                'module'   => 'penjualan',
                'submenu' => [
                    [
                        'module'   => 'penjualan.pengajuan',
                        'FLOWS'     => [
                            [
                                "role_id"   => 3,
                                "type"      => 1,
                            ],
                        ],
                    ],
                    [
                        'module'   => 'penjualan.pelaksanaan',
                        'FLOWS'     => [
                            [
                                "role_id"   => 3,
                                "type"      => 1,
                            ],
                        ],
                    ],
                ]
            ],
            [
                'module'   => 'mutasi',
                'submenu' => [
                    [
                        'module'   => 'mutasi.pengajuan',
                        'FLOWS'     => [
                            [
                                "role_id"   => 3,
                                "type"      => 1,
                            ],
                        ],
                    ],
                    [
                        'module'   => 'mutasi.pelaksanaan',
                        'FLOWS'     => [
                            [
                                "role_id"   => 3,
                                "type"      => 1,
                            ],
                        ],
                    ],
                ]
            ],

        ];


        $this->generate($data);
    }

    public function generate($data)
    {
        ini_set("memory_limit", -1);
        $exists = [];
        $order = 1;
        foreach ($data as $row) {
            //sys_menu
            $menu = Menu::firstOrNew(['module' => $row['module']]);
            $menu->order = $order;
            $menu->save();
            $exists[] = $menu->id;
            $order++;
            //jika ada sub menu
            if (!empty($row['submenu'])) {
                foreach ($row['submenu'] as $val) {
                    //ambil nama module
                    $submenu = $menu->child()->firstOrNew(['module' => $val['module']]);
                    $submenu->order = $order; //order ++ dimulai dari 1
                    $submenu->save();
                    $exists[] = $submenu->id; //ambil id module
                    $order++;
                    //apakah submenu sudah di definisikan dan memiliki value ? jika ya jalankan dibawah 
                    if (isset($val['FLOWS'])) {
                        $submenu->flows()->delete();
                        //$submenu->flows()->delete();: Menghapus semua aliran (flows) yang terkait dengan submenu ini sebelum mengisi data yang baru. Ini memastikan bahwa data yang lama dihapus sebelum data baru dimasukkan.
                        $f = 1;
                        foreach ($val['FLOWS'] as $key => $flow) {
                            $record = MenuFlow::firstOrNew([
                                'menu_id'   => $submenu->id,
                                'role_id'   => $flow['role_id'],
                                'type'      => $flow['type'],
                                'order'     => $f++,
                            ]);
                            $record->save();
                        }
                    }
                }
            }

            if (isset($row['FLOWS'])) {
                $menu->flows()->delete();
                $f = 1;
                foreach ($row['FLOWS'] as $key => $flow) {
                    $record = MenuFlow::firstOrNew([
                        'menu_id'   => $menu->id,
                        'role_id'   => $flow['role_id'],
                        'type'      => $flow['type'],
                        'order'     => $f++,
                    ]);
                    $record->save();
                }
            }
        }
        Menu::whereNotIn('id', $exists)->delete();
        //menu yang tidak ada di dalam array akan dihapus
    }

    public function countActions($data)
    {
        $count = 0;
        foreach ($data as $row) {
            $count++;
            if (!empty($row['submenu'])) {
                $count += count($row['submenu']);
            }
        }
        return $count;
    }
}
