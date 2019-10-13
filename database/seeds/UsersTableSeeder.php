<?php

use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_admin_user = User::create([
            'email' => 'anselm@intellchub.com',
            'phone' => '08069523313',
            'password' => bcrypt('P@ssword@01'),
            'is_premium' => true,
            'is_active' => true,
            'is_confirmed' => true,
        ]);

        $super_admin_user->profile()->create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'avatar' => Profile::AVATAR
        ]);

        $admin_user = User::create([
            'email' => 'admin@fotomi.com',
            'phone' => '00000000001',
            'password' => bcrypt('admin'),
            'is_premium' => true,
            'is_active' => true,
            'is_confirmed' => true,
        ]);
        $admin_user->profile()->create([
            'first_name' => 'Admin',
            'last_name' => 'Fotomi',
            'avatar' => Profile::AVATAR
        ]);

        $user = User::create([
            'email' => 'user@fotomi.com',
            'phone' => '00000000004',
            'password' => bcrypt('user'),
            'is_premium' => true,
            'is_active' => true,
            'is_confirmed' => true,
        ]);

        $user->profile()->create([
            'first_name' => 'User',
            'last_name' => 'Fotomi',
            'avatar' => Profile::AVATAR
        ]);

        $super_admin_role = Role::where('name', 'super_admin')->first();
        $admin_role = Role::where('name', Role::ADMIN)->first();
        $worker_role = Role::where('name', Role::USER)->first();

        $super_admin_user->attachRole($super_admin_role);
        $admin_user->attachRole($admin_role);
        $user->attachRole($worker_role);
    }
}
