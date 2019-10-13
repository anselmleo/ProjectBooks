<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Developer',
                'description' => 'User is the developer of the project',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Project Owner',
                'description' => 'User is the owner of the project',
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'User',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
