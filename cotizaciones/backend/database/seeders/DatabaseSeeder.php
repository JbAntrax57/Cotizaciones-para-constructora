<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear roles primero
        $this->call([
            RoleSeeder::class,
        ]);

        // Crear usuarios
        $this->call([
            UserSeeder::class,
        ]);

        // Crear tipos de construcción
        $this->call([
            ConstructionTypeSeeder::class,
        ]);

        // Crear materiales
        $this->call([
            MaterialSeeder::class,
        ]);

        // Crear rendimientos de materiales
        $this->call([
            MaterialYieldSeeder::class,
        ]);

        // Crear tarifas de mano de obra
        $this->call([
            LaborRateSeeder::class,
        ]);

        // Crear clientes
        $this->call([
            ClientSeeder::class,
        ]);

        // Crear proyectos
        $this->call([
            ProjectSeeder::class,
        ]);

        // Crear servicios
        $this->call([
            ServiceSeeder::class,
        ]);

        // Crear materiales por tipo de construcción
        $this->call([
            ConstructionTypeMaterialSeeder::class,
        ]);

        // Crear servicios por tipo de construcción
        $this->call([
            ConstructionTypeServiceSeeder::class,
        ]);
    }
}
