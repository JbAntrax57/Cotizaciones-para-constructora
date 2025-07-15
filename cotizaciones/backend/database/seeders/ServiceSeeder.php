<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            [
                'name' => 'Excavación y Nivelación',
                'description' => 'Excavación manual y nivelación del terreno',
                'estimated_cost' => 150.00
            ],
            [
                'name' => 'Cimentación',
                'description' => 'Colado de cimientos y zapatas',
                'estimated_cost' => 800.00
            ],
            [
                'name' => 'Muros de Ladrillo',
                'description' => 'Construcción de muros con ladrillo rojo',
                'estimated_cost' => 350.00
            ],
            [
                'name' => 'Muros de Block',
                'description' => 'Construcción de muros con block de concreto',
                'estimated_cost' => 280.00
            ],
            [
                'name' => 'Piso de Concreto',
                'description' => 'Colado de piso firme de concreto',
                'estimated_cost' => 200.00
            ],
            [
                'name' => 'Instalación Eléctrica',
                'description' => 'Instalación eléctrica básica residencial',
                'estimated_cost' => 120.00
            ],
            [
                'name' => 'Instalación Hidráulica',
                'description' => 'Instalación de tuberías y drenaje',
                'estimated_cost' => 180.00
            ],
            [
                'name' => 'Acabados de Yeso',
                'description' => 'Aplicación de yeso en muros y techos',
                'estimated_cost' => 45.00
            ],
            [
                'name' => 'Pintura Interior',
                'description' => 'Aplicación de pintura vinílica interior',
                'estimated_cost' => 35.00
            ],
            [
                'name' => 'Pintura Exterior',
                'description' => 'Aplicación de pintura exterior',
                'estimated_cost' => 50.00
            ],
            [
                'name' => 'Impermeabilización',
                'description' => 'Impermeabilización de azotea',
                'estimated_cost' => 85.00
            ],
            [
                'name' => 'Instalación de Ventanas',
                'description' => 'Instalación de ventanas de aluminio',
                'estimated_cost' => 250.00
            ],
            [
                'name' => 'Instalación de Puertas',
                'description' => 'Instalación de puertas de madera',
                'estimated_cost' => 300.00
            ],
            [
                'name' => 'Cerámica de Piso',
                'description' => 'Instalación de cerámica en pisos',
                'estimated_cost' => 120.00
            ],
            [
                'name' => 'Cerámica de Muro',
                'description' => 'Instalación de cerámica en muros',
                'estimated_cost' => 100.00
            ]
        ];

        foreach ($services as $service) {
            DB::table('services')->insert($service);
        }
    }
} 