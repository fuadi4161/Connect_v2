<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = User::create([
            'name' => 'super admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password')
        ]);

        $superadmin->assignRole('superadmin');

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password')
        ]);

        $admin->assignRole('admin');
        

        $client = User::create([
            'name' => 'client',
            'email' => 'client@gmail.com',
            'password' => bcrypt('password')
        ]);

        $client->assignRole('client');

        $users = User::create([
            'name' => 'users',
            'email' => 'users@gmail.com',
            'password' => bcrypt('password')
        ]);

        $users->assignRole('users');
    }
}
