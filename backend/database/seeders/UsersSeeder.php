<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clienteId = DB::table('roles')->where('name', 'role_client')->first()->id;
        $soporteId = DB::table('roles')->where('name', 'role_support')->first()->id;
        $adminId = DB::table('roles')->where('name', 'role_admin')->first()->id;

        DB::table('users')->insert([
            [
                'name' => 'Cliente Demo',
                'email' => 'cliente@demo.com',
                'password' => Hash::make('password123'),
                'role_id' => $clienteId,
            ],
            [
                'name' => 'Soporte Demo',
                'email' => 'soporte@demo.com',
                'password' => Hash::make('password123'),
                'role_id' => $soporteId,
            ],
            [
                'name' => 'Admin Demo',
                'email' => 'admin@demo.com',
                'password' => Hash::make('password123'),
                'role_id' => $adminId,
            ],
            [
                'name' => 'Cliente2 Demo2',
                'email' => 'cliente2@demo.com',
                'password' => Hash::make('password123'),
                'role_id' => $clienteId,
            ],
            [
                'name' => 'Soporte2 Demo2',
                'email' => 'soporte2@demo.com',
                'password' => Hash::make('password123'),
                'role_id' => $soporteId,
            ],
            [
                'name' => 'Admin2 Demo2',
                'email' => 'admin2@demo.com',
                'password' => Hash::make('password123'),
                'role_id' => $adminId,
            ],
        ]);
    }
}
