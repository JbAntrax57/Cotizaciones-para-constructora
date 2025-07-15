<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConstructionTypeServiceSeeder extends Seeder
{
    public function run()
    {
        $constructionTypeServices = [
            // Recámara - Servicios
            [
                'construction_type_id' => 1, // Recámara
                'service_id' => 1, // Albañilería
                'work_type' => 'ladrillo_pegado',
                'rate_per_unit' => 6.50,
                'unit_measure' => 'pieza',
                'calculation_formula' => 'wall_area * 20 * 6.50',
                'is_required' => true,
                'sort_order' => 1,
                'notes' => 'Pegado de ladrillo rojo'
            ],
            [
                'construction_type_id' => 1, // Recámara
                'service_id' => 2, // Concreto
                'work_type' => 'piso_colado',
                'rate_per_unit' => 150.00,
                'unit_measure' => 'm²',
                'calculation_formula' => 'floor_area * 150',
                'is_required' => true,
                'sort_order' => 2,
                'notes' => 'Colado de piso de concreto'
            ],
            [
                'construction_type_id' => 1, // Recámara
                'service_id' => 3, // Estructura
                'work_type' => 'castillo_colado',
                'rate_per_unit' => 200.00,
                'unit_measure' => 'castillo',
                'calculation_formula' => 'ceil(perimeter / 4) * 200',
                'is_required' => true,
                'sort_order' => 3,
                'notes' => 'Colado de castillos'
            ],

            // Sala - Servicios
            [
                'construction_type_id' => 2, // Sala
                'service_id' => 1, // Albañilería
                'work_type' => 'ladrillo_pegado',
                'rate_per_unit' => 6.50,
                'unit_measure' => 'pieza',
                'calculation_formula' => 'wall_area * 20 * 6.50',
                'is_required' => true,
                'sort_order' => 1,
                'notes' => 'Pegado de ladrillo rojo'
            ],
            [
                'construction_type_id' => 2, // Sala
                'service_id' => 2, // Concreto
                'work_type' => 'piso_colado',
                'rate_per_unit' => 150.00,
                'unit_measure' => 'm²',
                'calculation_formula' => 'floor_area * 150',
                'is_required' => true,
                'sort_order' => 2,
                'notes' => 'Colado de piso de concreto'
            ],
            [
                'construction_type_id' => 2, // Sala
                'service_id' => 3, // Estructura
                'work_type' => 'castillo_colado',
                'rate_per_unit' => 200.00,
                'unit_measure' => 'castillo',
                'calculation_formula' => 'ceil(perimeter / 4) * 200',
                'is_required' => true,
                'sort_order' => 3,
                'notes' => 'Colado de castillos'
            ],

            // Baño - Servicios (incluye plomería)
            [
                'construction_type_id' => 3, // Baño
                'service_id' => 1, // Albañilería
                'work_type' => 'ladrillo_pegado',
                'rate_per_unit' => 6.50,
                'unit_measure' => 'pieza',
                'calculation_formula' => 'wall_area * 20 * 6.50',
                'is_required' => true,
                'sort_order' => 1,
                'notes' => 'Pegado de ladrillo rojo'
            ],
            [
                'construction_type_id' => 3, // Baño
                'service_id' => 2, // Concreto
                'work_type' => 'piso_colado',
                'rate_per_unit' => 150.00,
                'unit_measure' => 'm²',
                'calculation_formula' => 'floor_area * 150',
                'is_required' => true,
                'sort_order' => 2,
                'notes' => 'Colado de piso de concreto'
            ],
            [
                'construction_type_id' => 3, // Baño
                'service_id' => 3, // Estructura
                'work_type' => 'castillo_colado',
                'rate_per_unit' => 200.00,
                'unit_measure' => 'castillo',
                'calculation_formula' => 'ceil(perimeter / 4) * 200',
                'is_required' => true,
                'sort_order' => 3,
                'notes' => 'Colado de castillos'
            ],
            [
                'construction_type_id' => 3, // Baño
                'service_id' => 4, // Plomería
                'work_type' => 'instalacion_plomeria',
                'rate_per_unit' => 180.00,
                'unit_measure' => 'm²',
                'calculation_formula' => 'floor_area * 180',
                'is_required' => true,
                'sort_order' => 4,
                'notes' => 'Instalación de plomería'
            ]
        ];

        foreach ($constructionTypeServices as $service) {
            DB::table('construction_type_services')->insert($service);
        }
    }
} 