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
