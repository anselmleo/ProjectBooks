<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('RolesTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('OrdersTableSeeder');
        // $this->call('GeneralSettingsTableSeeder');
        $this->call('CitiesTableSeeder');
        // $this->call('CategoriesTableSeeder');
        $this->call('FrameTypesTableSeeder');
        $this->call('FrameDimensionsTableSeeder');
    }
}
