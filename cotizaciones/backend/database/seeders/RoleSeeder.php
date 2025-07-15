<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrador',
                'description' => 'Acceso completo al sistema'
            ],
            [
                'name' => 'arquitecto',
                'display_name' => 'Arquitecto',
                'description' => 'Puede crear y editar cotizaciones'
            ],
            [
                'name' => 'ingeniero',
                'display_name' => 'Ingeniero',
                'description' => 'Puede revisar y aprobar cotizaciones'
            ],
            [
                'name' => 'vendedor',
                'display_name' => 'Vendedor',
                'description' => 'Puede crear cotizaciones básicas'
            ]
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert($role);
        }
    }
} 