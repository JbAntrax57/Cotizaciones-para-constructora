<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConstructionTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            [
                'name' => 'Recámara',
                'description' => 'Habitación estándar con muros de ladrillo y piso de concreto',
                'default_specs' => json_encode([
                    'height' => 2.4,
                    'wall_thickness' => 0.15,
                    'floor_thickness' => 0.06,
                    'includes_electrical' => true,
                    'includes_plumbing' => false
                ])
            ],
            [
                'name' => 'Sala',
                'description' => 'Área de estar con acabados estándar',
                'default_specs' => json_encode([
                    'height' => 2.6,
                    'wall_thickness' => 0.15,
                    'floor_thickness' => 0.08,
                    'includes_electrical' => true,
                    'includes_plumbing' => false
                ])
            ],
            [
                'name' => 'Cocina',
                'description' => 'Cocina completa con instalaciones',
                'default_specs' => json_encode([
                    'height' => 2.4,
                    'wall_thickness' => 0.15,
                    'floor_thickness' => 0.08,
                    'includes_electrical' => true,
                    'includes_plumbing' => true
                ])
            ],
            [
                'name' => 'Baño',
                'description' => 'Baño completo con instalaciones sanitarias',
                'default_specs' => json_encode([
                    'height' => 2.4,
                    'wall_thickness' => 0.15,
                    'floor_thickness' => 0.08,
                    'includes_electrical' => true,
                    'includes_plumbing' => true
                ])
            ],
            [
                'name' => 'Garage',
                'description' => 'Estacionamiento cubierto',
                'default_specs' => json_encode([
                    'height' => 2.8,
                    'wall_thickness' => 0.15,
                    'floor_thickness' => 0.10,
                    'includes_electrical' => true,
                    'includes_plumbing' => false
                ])
            ]
        ];

        foreach ($types as $type) {
            DB::table('construction_types')->insert($type);
        }
    }
} 