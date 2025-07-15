<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Administrador Sistema',
                'email' => 'admin@cotizaciones.com',
                'password' => Hash::make('password'),
                'role_id' => 1, // admin
                'email_verified_at' => now()
            ],
            [
                'name' => 'Arquitecto Juan Pérez',
                'email' => 'juan.perez@cotizaciones.com',
                'password' => Hash::make('password'),
                'role_id' => 2, // arquitecto
                'email_verified_at' => now()
            ],
            [
                'name' => 'Ingeniero María García',
                'email' => 'maria.garcia@cotizaciones.com',
                'password' => Hash::make('password'),
                'role_id' => 3, // ingeniero
                'email_verified_at' => now()
            ],
            [
                'name' => 'Vendedor Carlos López',
                'email' => 'carlos.lopez@cotizaciones.com',
                'password' => Hash::make('password'),
                'role_id' => 4, // vendedor
                'email_verified_at' => now()
            ]
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }
} 