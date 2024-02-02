<?php

return [
    [
        'section' => 'NAVIGASI',
        'name' => 'navigasi',
        'perms' => 'dashboard',
    ],
    // Dashboard
    [
        'name' => 'dashboard',
        'perms' => 'dashboard',
        'title' => 'Dashboard',
        'icon' => 'fa fa-th-large',
        'url' => '/home',
    ],
    // Monitoring
    [
        'name' => 'monitoring',
        'title' => 'Monitoring',
        'icon' => 'fa fa-search-plus',
        'submenu' => [
            [
                'name' => 'monitoring.aktiva',
                'perms' => 'monitoring',
                'title' => 'Monitoring Aktiva',
                'url' => '/monitoring/monitoring-aktiva',
            ],
            [
                'name' => 'monitoring.sgu',
                'perms' => 'monitoring',
                'title' => 'Monitoring SGU',
                'url' => '/monitoring/monitoring-sgu',
            ],
        ]
    ],
    [
        'name' => 'ump',
        'title' => 'Uang Muka Pembayaran',
        'icon' => 'fa fa-cubes',
        'submenu' => [
            [
                'name' => 'ump.pengajuan-ump',
                'perms' => 'ump.pengajuan-ump',
                'title' => 'Pengajuan',
                'url' => '/ump/pengajuan-ump',
            ],
            [
                'name' => 'ump.pembatalan-ump',
                'perms' => 'ump.pembatalan-ump',
                'title' => 'Pembatalan',
                'url' => '/ump/pembatalan-ump',
            ],
            [
                'name' => 'ump.pembayaran-ump',
                'perms' => 'ump.pembayaran-ump',
                'title' => 'Pembayaran',
                'url' => '/ump/pembayaran-ump',
            ],
            [
                'name' => 'ump.perpanjangan-ump',
                'perms' => 'ump.perpanjangan-ump',
                'title' => 'Perpanjangan',
                'url' => '/ump/perpanjangan-ump',
            ],
            [
                'name' => 'ump.pj-ump',
                'perms' => 'ump.pj-ump',
                'title' => 'Pertanggungjawaban',
                'url' => '/ump/pj-ump',
            ],
        ]
    ],
    [
        'name'      => 'pengajuan-pembelian',
        'title'     => 'Pembelian Aktiva',
        'icon'      => 'fa fa-shopping-cart',
        'url'       => '/pengajuan-pembelian',
        'perms'     => 'pengajuan-pembelian'
    ],
    [
        'name'      => 'operational-cost',
        'title'     => 'Biaya Operasional',
        'icon'      => 'fa fa-users',
        'url'       => '/operational-cost',
        'perms'     => 'operational-cost'
    ],
    // [
    //     'name'      => 'aktiva',
    //     'title'     => 'Aktiva',
    //     'icon'      => 'fa fa-list',
    //     'url'       => '/aktiva',
    //     'perms'     => 'aktiva',
    // ],
    [
        'name'      => 'sgu',
        'perms'     => 'sgu',
        'title'     => 'Sewa Guna Usaha',
        'icon'      => 'fa fa-university',
        'url'       => '/sgu'
    ],
    // Pembayaran termin
    [
        'name'      => 'termin',
        'title'     => 'Termin Pembayaran',
        'icon'      => 'fa fa-credit-card',
        'submenu'   => [
            [
                'name'  => 'termin.pengajuan',
                'perms'  => 'termin.pengajuan',
                'title' => 'Pengajuan Termin',
                'url'   => '/termin/pengajuan'
            ],
            [
                'name'  => 'termin.pembayaran',
                'perms'  => 'termin.pembayaran',
                'title' => 'Pembayaran Termin',
                'url'   => '/termin/pembayaran'
            ],
        ],
    ],
    [
        'name'      => 'pemeriksaan',
        'title'     => 'Pemeriksaan Kondisi',
        'icon'      => 'fa fa-check',
        'url'       => '/pemeriksaan',
        'perms'     => 'pemeriksaan'
    ],
    [
        'name'      => 'penghapusan',
        'title'     => 'Penghapusan Aktiva',
        'icon'      => 'fa fa-trash',
        'submenu'   => [
            [
                'name'  => 'penghapusan.pengajuan',
                'perms'  => 'penghapusan.pengajuan',
                'title' => 'Pengajuan',
                'url'   => '/penghapusan'
            ],
            [
                'name'  => 'penghapusan.pelaksanaan',
                'perms'  => 'penghapusan.pelaksanaan',
                'title' => 'Pelaksanaan',
                'url'   => '/pelaksanaan-penghapusan'
            ],
        ],
    ],
    [
        'name'      => 'penjualan',
        'title'     => 'Penjualan Aktiva',
        'icon'      => 'fa fa-money-bill',
        'submenu'   => [
            [
                'name'  => 'penjualan.pengajuan',
                'perms'  => 'penjualan.pengajuan',
                'title' => 'Pengajuan',
                'url'   => '/penjualan'
            ],
            [
                'name'  => 'penjualan.pelaksanaan',
                'perms'  => 'penjualan.pelaksanaan',
                'title' => 'Pelaksanaan',
                'url'   => '/pelaksanaan-penjualan'
            ],
        ],
    ],
    [
        'name'      => 'mutasi',
        'title'     => 'Mutasi Aktiva',
        'icon'      => 'fa fa-sync',
        'submenu'   => [
            [
                'name'      => 'mutasi.pengajuan',
                'title'     => 'Pengajuan',
                'url'       => '/pengajuan-mutasi',
                'perms'     => 'mutasi.pengajuan'
            ],
            [
                'name'      => 'mutasi.pelaksanaan',
                'title'     => 'Pelaksanaan',
                'url'       => '/pelaksanaan-mutasi',
                'perms'     => 'mutasi.pelaksanaan'
            ],
        ]
    ],
    // Laporan
    [
        'name' => 'laporan',
        'title' => 'Laporan',
        'icon' => 'fa fa-book',
        'submenu' => [
            [
                'name' => 'laporan.laporan-aktiva',
                'perms' => 'laporan',
                'title' => 'Aktiva',
                'url' => '/laporan/laporan-aktiva',
            ],
            [
                'name' => '.laporan.laporan-sgu',
                'perms' => 'laporan',
                'title' => 'SGU',
                'url' => '/laporan/laporan-sgu',
            ],
            [
                'name' => 'laporan.laporan-pembayaran',
                'perms' => 'laporan',
                'title' => 'Pembayaran',
                'url' => '/laporan/laporan-pembayaran',
            ],
            [
                'name' => '.laporan.laporan-penghapusan',
                'perms' => 'laporan',
                'title' => 'Penghapusan Aktiva',
                'url' => '/laporan/laporan-penghapusan',
            ],
            [
                'name' => '.laporan.laporan-penjualan',
                'perms' => 'laporan',
                'title' => 'Penjualan Aktiva',
                'url' => '/laporan/laporan-penjualan',
            ],
            [
                'name' => '.laporan.laporan-mutasi',
                'perms' => 'laporan',
                'title' => 'Mutasi Aktiva',
                'url' => '/laporan/laporan-mutasi',
            ],
            [
                'name' => '.laporan.laporan-pemeriksaan',
                'perms' => 'laporan',
                'title' => 'Pemeriksaan Kondisi',
                'url' => '/laporan/laporan-pemeriksaan',
            ],
        ],
    ],

    
    // Admin Console
    [
        'section' => 'KONSOL ADMIN',
        'name' => 'setting',
    ],
    [
        'name' => 'master',
        'perms' => 'master',
        'title' => 'Parameter',
        'icon' => 'fa fa-database',
        'submenu' => [
            [
                'name' => 'master_org',
                'title' => 'Struktur Organisasi',
                'url' => '',
                'submenu' => [
                    [
                        'name' => 'master_org_root',
                        'title' => 'Root',
                        'url' => '/master/org/root'
                    ],
                    // [
                    //     'name' => 'master_org_boc',
                    //     'title' => 'Pengawas',
                    //     'url' => '/master/org/boc',
                    // ],
                    [
                        'name' => 'master_org_bod',
                        'title' => 'Direksi',
                        'url' => '/master/org/bod',
                    ],
                    [
                        'name' => 'master_org_division',
                        'title' => 'Divisi',
                        'url' => '/master/org/division',
                    ],
                    [
                        'name' => 'master_org_department',
                        'title' => 'Departemen',
                        'url' => '/master/org/department',
                    ],
                    [
                        'name' => 'master_org_branch',
                        'title' => 'Regional',
                        'url' => '/master/org/branch',
                    ],
                    [
                        'name' => 'master_org_subbranch',
                        'title' => 'Outlet',
                        'url' => '/master/org/subbranch',
                    ],
                    [
                        'name' => 'master_org_kelompok',
                        'title' => 'Kelompok Jabatan',
                        'url' => '/master/org/kelompok',
                    ],
                    // [
                    //     'name' => 'master_org_cash',
                    //     'title' => 'Kantor Kas',
                    //     'url' => '/master/org/cash',
                    // ],
                    // [
                    //     'name' => 'master_org_payment',
                    //     'title' => 'Payment Point',
                    //     'url' => '/master/org/payment',
                    // ],
                    // [
                    //     'name' => 'master_org_group',
                    //     'title' => 'Grup Lainnya',
                    //     'url' => '/master/org/group',
                    // ],
                    [
                        'name' => 'master_org_position',
                        'title' => 'Jabatan',
                        'url' => '/master/org/position',
                    ],
                ]
            ],
            [
                'name' => 'master_masa_penggunaan',
                'title' => 'Masa Penggunaan Aset Tangible',
                'url' => '/master/masa-penggunaan',
            ],
            [
                'name' => 'master_jurnal',
                'title' => 'Jurnal',
                'url' => '',
                'submenu' => [
                    [
                        'name' => 'master_coa',
                        'title' => 'Chart of Accounts',
                        'url' => '/master/coa',
                    ],
                    [
                        'name' => 'master_template',
                        'title' => 'Template',
                        'url' => '/master/template',
                    ],
                ]
            ],
            [
                'name' => 'master_mata_anggaran',
                'title' => 'Mata Anggaran',
                'url' => '/master/mata-anggaran',
            ],
            [
                'name' => 'master_bank',
                'title' => 'Bank',
                'url' => '/master/bank',
            ],
            [
                'name' => 'master_bank-account',
                'title' => 'Rekening Bank',
                'url' => '/master/bank-account',
            ],
            [
                'name' => 'vendor',
                'title' => 'Vendor',
                'url' => '/master/vendor',
            ],
        ]
    ],
    [
        'name' => 'setting',
        'perms' => 'setting',
        'title' => 'Pengaturan Umum',
        'icon' => 'fa fa-cogs',
        'submenu' => [
            [
                'name' => 'setting_role',
                'title' => 'Hak Akses',
                'url' => '/setting/role',
            ],
            [
                'name' => 'setting_user',
                'title' => 'Manajemen User',
                'url' => '/setting/user',
            ],
            [
                'name' => 'setting_flow',
                'title' => 'Alur Approval',
                'url' => '/setting/flow',
            ],
            [
                'name' => 'setting_activity',
                'title' => 'Audit Trail',
                'url' => '/setting/activity',
            ],
        ]
    ],
];
