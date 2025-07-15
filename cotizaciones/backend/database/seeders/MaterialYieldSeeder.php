<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialYieldSeeder extends Seeder
{
    public function run()
    {
        $yields = [
            // Cemento Portland
            [
                'material_id' => 1, // Cemento Portland
                'application_type' => 'muro_ladrillo',
                'yield_per_unit' => 70.0,
                'unit_measure' => 'piezas',
                'notes' => '70 ladrillos por saco de cemento'
            ],
            [
                'material_id' => 1, // Cemento Portland
                'application_type' => 'piso_concreto',
                'yield_per_unit' => 1.0,
                'unit_measure' => 'm²',
                'notes' => '1 m² de piso por saco (6cm altura)'
            ],
            [
                'material_id' => 1, // Cemento Portland
                'application_type' => 'cimiento',
                'yield_per_unit' => 0.5,
                'unit_measure' => 'm³',
                'notes' => '0.5 m³ de cimiento por saco'
            ],
            [
                'material_id' => 1, // Cemento Portland
                'application_type' => 'castillo',
                'yield_per_unit' => 0.15,
                'unit_measure' => 'm³',
                'notes' => '0.15 m³ de castillo por saco'
            ],
            
            // Ladrillo Rojo
            [
                'material_id' => 2, // Ladrillo Rojo
                'application_type' => 'muro',
                'yield_per_unit' => 20.0,
                'unit_measure' => 'm²',
                'notes' => '20 ladrillos por m² de muro'
            ],
            
            // Arena de Río
            [
                'material_id' => 3, // Arena de Río
                'application_type' => 'mortero',
                'yield_per_unit' => 0.5,
                'unit_measure' => 'm³',
                'notes' => '0.5 m³ de mortero por m³ de arena'
            ],
            [
                'material_id' => 3, // Arena de Río
                'application_type' => 'concreto',
                'yield_per_unit' => 0.5,
                'unit_measure' => 'm³',
                'notes' => '0.5 m³ de concreto por m³ de arena'
            ],
            
            // Grava 3/4"
            [
                'material_id' => 4, // Grava 3/4"
                'application_type' => 'concreto',
                'yield_per_unit' => 0.5,
                'unit_measure' => 'm³',
                'notes' => '0.5 m³ de concreto por m³ de grava'
            ],
            
            // Varilla 3/8"
            [
                'material_id' => 5, // Varilla 3/8"
                'application_type' => 'castillo',
                'yield_per_unit' => 4.0,
                'unit_measure' => 'castillos',
                'notes' => '4 castillos por varilla de 6m'
            ],
            
            // Block de Concreto
            [
                'material_id' => 7, // Block de Concreto
                'application_type' => 'muro',
                'yield_per_unit' => 12.5,
                'unit_measure' => 'm²',
                'notes' => '12.5 blocks por m² de muro'
            ],
            
            // Yeso
            [
                'material_id' => 8, // Yeso
                'application_type' => 'acabado',
                'yield_per_unit' => 8.0,
                'unit_measure' => 'm²',
                'notes' => '8 m² de acabado por kg de yeso'
            ],
            
            // Pintura Vinílica
            [
                'material_id' => 9, // Pintura Vinílica
                'application_type' => 'pintura',
                'yield_per_unit' => 25.0,
                'unit_measure' => 'm²',
                'notes' => '25 m² por galón (2 manos)'
            ]
        ];

        foreach ($yields as $yield) {
            DB::table('material_yields')->insert($yield);
        }
    }
} 