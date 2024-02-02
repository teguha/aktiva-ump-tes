<?php

namespace Database\Seeders;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Administrator',
            ]
        );
        // Fungsi ini digunakan untuk mencari baris dalam tabel berdasarkan kriteria yang diberikan. Jika baris dengan kriteria tersebut tidak ditemukan, maka akan membuat baris baru dengan data yang diberikan. Jika sudah ada baris yang sesuai dengan kriteria, maka akan mengembalikan baris tersebut tanpa membuat yang baru.
        
        $user = User::firstOrCreate(
            ['id' => 1],
            // Metode ini berfungsi untuk mencari pengguna dengan atribut yang sesuai yang diberikan dalam array pertama. Jika pengguna dengan atribut tersebut tidak ditemukan, maka akan membuat pengguna baru dengan data yang diberikan dalam array kedua. Jika pengguna sudah ada, maka akan mengembalikan pengguna yang telah ada tanpa membuat yang baru.
            // Array pertama: ['id' => 1]:

            // Di sini, kode mencari pengguna dengan ID 1. Jika pengguna dengan ID 1 tidak ditemukan, maka akan menciptakan pengguna baru dengan atribut yang didefinisikan di array kedua.
            [
                'name'          => 'Administrator',
                'username'      => 'admin',
                'email'         => 'admin@email.com',
                'password'      => bcrypt('password'),
                'nik'           => 'admin',
            ]
        );
        $user->assignRole($role);

        // Staff Departemen
        $role = Role::firstOrCreate(
            ['name' => 'Staff Departemen'],
            [
                'name'      => 'Staff Departemen',
            ]
        );

        // add ke table user
        $user = User::firstOrCreate(
            ['name' => 'rusman'], 
            // jika name rusman tidak ada maka tambahkan data dibawah ini
            [
                'position_id'   => 3,
                'name'          => 'satrio',
                'username'      => 'satrio',
                'email'         => 'satrio@email.com',
                'password'      => bcrypt('password'),
                'phone'         => '322311',
                'nik'           => '001002',
                'location_id'   => 3,
            ]
        );
        $user->assignRole($role);

        // Kepala Departemen
        $role = Role::firstOrCreate(
            ['name' => 'Kepala Departemen'],
            [
                'name'      => 'Kepala Departemen',
            ]
        );

        $user = User::firstOrCreate(
            ['name' => 'rusman'],
            [
                'position_id'   => 1,
                'name'          => 'rusman',
                'username'      => 'rusman',
                'email'         => 'rusman@email.com',
                'password'      => bcrypt('password'),
                'phone'         => '655455',
                'nik'           => '655644',
                'location_id'   => 3,
            ]
        );
        $user->assignRole($role);

        // Direktur
        $role = Role::firstOrCreate(
            ['name' => 'Direktur'],
            [
                'name'      => 'Direktur',
            ]
        );

        $user = User::firstOrCreate(
            ['name' => 'zacky'],
            [
                'position_id'   => 2,
                'name'          => 'zacky',
                'username'      => 'zacky',
                'email'         => 'zacky@email.com',
                'password'      => bcrypt('password'),
                'phone'         => '655644',
                'nik'           => '999888',
                'location_id'   => 2,
            ]
        );
        $user->assignRole($role);

        // $role = Role::firstOrCreate(
        //     ['name' => 'Kepala Departemen Logistik'],
        //     [
        //         'name' => 'Kepala Departemen Logistik',
        //     ]
        // );
        // $user = User::firstOrCreate(
        //     ['name' => 'Tanzil'],
        //     [
        //         'position_id'   => 15,
        //         'name'          => 'Tanzil',
        //         'username'      => 'Tanzil',
        //         'email'         => 'tanzil@email.com',
        //         'password'      => bcrypt('password'),
        //         'phone'         => '087655476652',
        //         'nik'           => '3452',
        //         'location_id'   => 9,
        //     ]
        // );
        // $user->assignRole($role);

        // $role = Role::firstOrCreate(
        //     ['name' => 'Kepala Departemen Akunting'],
        //     [
        //         'name'      => 'Kepala Departemen Akunting',
        //     ]
        // );
        // $user = User::firstOrCreate(
        //     ['name' => 'Andrean'],
        //     [
        //         'position_id'   => 22,
        //         'name'          => 'Andrean',
        //         'username'      => 'Andrean',
        //         'email'         => 'andrean@email.com',
        //         'password'      => bcrypt('password'),
        //         'phone'         => '08787362725',
        //         'nik'           => '3463',
        //         'location_id'   => 11,
        //     ]
        // );
        // $user->assignRole($role);

        // $role = Role::firstOrCreate(
        //     ['name' => 'Kepala Departemen Treasuri'],
        //     [
        //         'name'      => 'Kepala Departemen Treasuri',
        //     ]
        // );
        // $user = User::firstOrCreate(
        //     ['name' => 'Lisa'],
        //     [
        //         'position_id'   => 23,
        //         'name'          => 'Lisa',
        //         'username'      => 'Lisa',
        //         'email'         => 'lisa@email.com',
        //         'password'      => bcrypt('password'),
        //         'phone'         => '085277749265',
        //         'nik'           => '3564',
        //         'location_id'   => 11,
        //     ]
        // );
        // $user->assignRole($role);

        // $role = Role::firstOrCreate(
        //     ['name' => 'Staff Departemen'],
        //     [
        //         'name'      => 'Staff Departemen',
        //     ]
        // );
        // $user = User::firstOrCreate(
        //     ['name' => 'Bryan'],
        //     [
        //         'position_id'   => 34,
        //         'name'          => 'Bryan',
        //         'username'      => 'Bryan',
        //         'email'         => 'bryan@email.com',
        //         'password'      => bcrypt('password'),
        //         'phone'         => '08562314142',
        //         'nik'           => '4761',
        //         'location_id'   => 10,
        //     ]
        // );
        // $user->assignRole($role);

        // $role = Role::firstOrCreate(
        //     ['name' => 'Kepala Departemen'],
        //     [
        //         'name'      => 'Kepala Departemen',
        //     ]
        // );
        // $user = User::firstOrCreate(
        //     ['name' => 'Zyana'],
        //     [
        //         'position_id'   => 26,
        //         'name'          => 'Zyana',
        //         'username'      => 'Zyana',
        //         'email'         => 'zyana@email.com',
        //         'password'      => bcrypt('password'),
        //         'phone'         => '087976781324',
        //         'nik'           => '46711',
        //         'location_id'   => 10,
        //     ]
        // );
        // $user->assignRole($role);
    }
}
