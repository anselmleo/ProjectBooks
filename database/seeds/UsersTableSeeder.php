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
            'email' => 'anselmleo@gmail.com',
            'phone' => '08069523313',
            'password' => bcrypt('P@ssword@01'),
            'is_active' => true,
            'is_confirmed' => true,
        ]);

        $super_admin_user->profile()->create([
            'full_name' => 'Super Admin',
            'avatar' => Profile::AVATAR
        ]);

        $admin_user = User::create([
            'email' => 'admin@getdevbooks.com',
            'phone' => '00000000001',
            'password' => bcrypt('admin'),
            'is_active' => true,
            'is_confirmed' => true,
        ]);
        
        $admin_user->profile()->create([
            'full_name' => 'Admin GetDevBooks',
            'avatar' => Profile::AVATAR
        ]);

        $user = User::create([
            'email' => 'user@getdevbooks.com',
            'phone' => '00000000004',
            'password' => bcrypt('user'),
            'is_active' => true,
            'is_confirmed' => true,
        ]);

        $user->profile()->create([
            'full_name' => 'User GetDevBooks',
            'avatar' => Profile::AVATAR
        ]);

        $super_admin_role = Role::where('name', 'super_admin')->first();
        $admin_role = Role::where('name', Role::ADMIN)->first();
        $user_role = Role::where('name', Role::USER)->first();

        $super_admin_user->attachRole($super_admin_role);
        $admin_user->attachRole($admin_role);
        $user->attachRole($user_role);
    }
}
