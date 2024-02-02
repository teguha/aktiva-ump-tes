<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(OrgStructSeeder::class);
        $this->call(KelJabatanSeeder::class);
        $this->call(PositionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(MenuFlowSeeder::class);
        $this->call(ProvinceTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(MasterMataAnggaranSeeder::class);
        $this->call(MasterVendorSeeder::class);
        $this->call(BankSeeder::class);
        $this->call(TemplateSeeder::class);
        $this->call(MasaPenggunaanSeeder::class);
        $this->call(CoaSeeder::class);
        $this->call(BankAccountSeeder::class);
    }
}
