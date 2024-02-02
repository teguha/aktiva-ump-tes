<?php

namespace Database\Seeders;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Globals\MenuFlow;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'name'          => 'dashboard',
                'action'        => ['view'],
            ],
            [
                'name'          => 'monitoring',
                'action'        => ['view'],
            ],

            [
                'name'          => 'pengajuan-pembelian',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'operational-cost',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            // [
            //     'name'          => 'aktiva',
            //     'action'        => ['view', 'edit'],
            // ],
            [
                'name'          => 'sgu',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'ump.pengajuan-ump',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'ump.pembatalan-ump',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'ump.perpanjangan-ump',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'ump.pembayaran-ump',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'ump.pj-ump',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'termin.pengajuan',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'termin.pembayaran',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'pemeriksaan',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],

            [
                'name'          => 'penghapusan.pengajuan',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'penghapusan.pelaksanaan',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'penjualan.pengajuan',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'penjualan.pelaksanaan',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],

            [
                'name'          => 'mutasi.pengajuan',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'mutasi.pelaksanaan',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'laporan',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            /** ADMIN CONSOLE **/
            [
                'name'          => 'master',
                'action'        => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'name'          => 'setting',
                'action'        => ['view', 'create', 'edit', 'delete'],
            ],
        ];

        $this->generate($permissions);

        $roles = [
            [
                'name'  => 'Administrator', // fix
                'permissions'   => [
                    'dashboard'                 => ['view'],
                    'master'                    => ['view', 'create', 'edit', 'delete'],
                    'setting'                   => ['view', 'create', 'edit', 'delete'],
                ],
            ],

            [
                'name'  => 'Kepala Departemen', // fix
                'permissions'   => [
                    'dashboard'                 => ['view'],
                    'monitoring'                => ['view'],
                    'pengajuan-pembelian'       => ['view', 'create', 'edit', 'delete', 'approve'],
                    'operational-cost'       => ['view', 'create', 'edit', 'delete', 'approve'],
                    // 'aktiva'                    => ['view', 'edit'],

                    'ump.pengajuan-ump'     => ['view', 'create', 'edit', 'delete', 'approve'],
                    'ump.pj-ump'            => ['view', 'create', 'edit', 'delete', 'approve'],
                    'ump.perpanjangan-ump'  => ['view', 'create', 'edit', 'delete', 'approve'],
                    'ump.pembatalan-ump'  => ['view', 'create', 'edit', 'delete', 'approve'],
                    'ump.pembayaran-ump'  => ['view', 'create', 'edit', 'delete', 'approve'],

                    'sgu'                       => ['view', 'create', 'edit', 'delete', 'approve'],
                    'termin.pengajuan'          => ['view', 'create', 'edit', 'delete', 'approve'],
                    'termin.pembayaran'         => ['view', 'create', 'edit', 'delete', 'approve'],
                    'pemeriksaan'               => ['view', 'create', 'edit', 'delete', 'approve'],

                    'penghapusan.pengajuan'     => ['view', 'create', 'edit', 'delete', 'approve'],
                    'penghapusan.pelaksanaan'   => ['view', 'create', 'edit', 'delete', 'approve'],
                    'penjualan.pengajuan'       => ['view', 'create', 'edit', 'delete', 'approve'],
                    'penjualan.pelaksanaan'     => ['view', 'create', 'edit', 'delete', 'approve'],
                    'mutasi.pengajuan'          => ['view', 'create', 'edit', 'delete', 'approve'],
                    'mutasi.pelaksanaan'        => ['view', 'create', 'edit', 'delete', 'approve'],

                    'laporan'                   => ['view', 'create', 'edit', 'delete', 'approve'],

                    // 'master'                    => ['view', 'create', 'edit', 'delete'],
                    // 'setting'                   => ['view', 'create', 'edit', 'delete'],
                ],
            ],

            [
                'name'  => 'Staff Departemen', // fix
                'permissions'   => [
                    'dashboard'                 => ['view'],
                    'monitoring'                => ['view'],

                    'pengajuan-pembelian'       => ['view', 'create', 'edit', 'delete'],
                    'operational-cost'       => ['view', 'create', 'edit', 'delete'],
                    // 'aktiva'                    => ['view', 'edit'],
                    'sgu'                       => ['view', 'create', 'edit', 'delete'],

                    'ump.pengajuan-ump'     => ['view', 'create', 'edit', 'delete'],
                    'ump.pj-ump'            => ['view', 'create', 'edit', 'delete'],
                    'ump.pembayaran-ump'            => ['view', 'create', 'edit', 'delete'],
                    'ump.perpanjangan-ump'  => ['view', 'create', 'edit', 'delete'],
                    'ump.pembatalan-ump'  => ['view', 'create', 'edit', 'delete'],

                    'termin.pengajuan'          => ['view', 'create', 'edit', 'delete'],
                    'termin.pembayaran'         => ['view', 'create', 'edit', 'delete'],
                    'pemeriksaan'               => ['view', 'create', 'edit', 'delete'],

                    'penghapusan.pengajuan'     => ['view', 'create', 'edit', 'delete'],
                    'penghapusan.pelaksanaan'   => ['view', 'create', 'edit', 'delete'],
                    'penjualan.pengajuan'       => ['view', 'create', 'edit', 'delete'],
                    'penjualan.pelaksanaan'     => ['view', 'create', 'edit', 'delete'],
                    'mutasi.pengajuan'          => ['view', 'create', 'edit', 'delete'],
                    'mutasi.pelaksanaan'        => ['view', 'create', 'edit', 'delete'],

                    'laporan'        => ['view', 'create', 'edit', 'delete'],
                ],
            ],
            [
                'name'  => 'Direktur', // fix
                'permissions'   => [
                    'dashboard'                 => ['view'],
                    'monitoring'                => ['view'],

                    'ump.pengajuan-ump'     => ['view', 'approve'],
                    'ump.pj-ump'            => ['view', 'approve'],
                    'ump.pembayaran-ump'            => ['view', 'approve'],
                    'ump.perpanjangan-ump'  => ['view', 'approve'],
                    'ump.pembatalan-ump'  => ['view', 'approve'],

                    'termin.pengajuan'          => ['view', 'approve'],
                    'termin.pembayaran'         => ['view', 'approve'],
                    'pemeriksaan'               => ['view', 'approve'],

                    'penghapusan.pengajuan'     => ['view', 'approve'],
                    'penghapusan.pelaksanaan'   => ['view', 'approve'],
                    'penjualan.pengajuan'       => ['view', 'approve'],
                    'penjualan.pelaksanaan'     => ['view', 'approve'],
                    'mutasi.pengajuan'          => ['view', 'approve'],
                    'mutasi.pelaksanaan'        => ['view', 'approve'],

                    'laporan' => ['view', 'approve'],
                ],
            ],
        ];

        foreach ($roles as $role) {
            //table sys_role            nama role == name
            $record = Role::firstOrNew(['name' => $role['name']]);
            $perms = [];
            foreach ($role['permissions'] as $module => $actions) { // penghapusan , pengajuan , dll
                foreach ($actions as $action) { //value [view, update, dll]
                    $perms[] = $module . '.' . $action;
                }              //pengajuan.view dll
            }
            //sys permission dan sys role permission
            $perm_ids = Permission::whereIn('name', $perms)->pluck('id');
            $record->syncpermissions($perm_ids);
            $record->save();
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedpermissions();
        }
    }

    public function generate($permissions)
    {
        $perms_ids = [];
        foreach ($permissions as $row) {
            foreach ($row['action'] as $key => $val) {

                $name = $row['name'] . '.' . trim($val);
                $perms = Permission::firstOrCreate(compact('name'));
                $perms_ids[] = $perms->id;
            }
        }
        Permission::whereNotIn('id', $perms_ids)->delete();

        // Clear Perms Cache
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedpermissions();
    }
}
