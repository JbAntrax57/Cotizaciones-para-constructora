<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConstructionTypeMaterialSeeder extends Seeder
{
    public function run()
    {
        $constructionTypeMaterials = [
            // Recámara - Materiales
            [
                'construction_type_id' => 1, // Recámara
                'material_id' => 1, // Cemento Portland
                'application_type' => 'muro_ladrillo',
                'quantity_per_unit' => 0.014, // 1 saco por 70 ladrillos (20 ladrillos/m²)
                'unit_measure' => 'saco',
                'calculation_formula' => 'wall_area * 0.014',
                'is_required' => true,
                'sort_order' => 1,
                'notes' => 'Cemento para muros de ladrillo'
            ],
            [
                'construction_type_id' => 1, // Recámara
                'material_id' => 2, // Ladrillo Rojo
                'application_type' => 'muro',
                'quantity_per_unit' => 20.0, // 20 ladrillos por m²
                'unit_measure' => 'pieza',
                'calculation_formula' => 'wall_area * 20',
                'is_required' => true,
                'sort_order' => 2,
                'notes' => 'Ladrillos para muros'
            ],
            [
                'construction_type_id' => 1, // Recámara
                'material_id' => 1, // Cemento Portland
                'application_type' => 'piso_concreto',
                'quantity_per_unit' => 1.0, // 1 saco por m²
                'unit_measure' => 'saco',
                'calculation_formula' => 'floor_area * 1',
                'is_required' => true,
                'sort_order' => 3,
                'notes' => 'Cemento para piso'
            ],
            [
                'construction_type_id' => 1, // Recámara
                'material_id' => 3, // Arena de Río
                'application_type' => 'mortero_concreto',
                'quantity_per_unit' => 0.15, // 0.15 m³ por m²
                'unit_measure' => 'm³',
                'calculation_formula' => '(wall_area * 0.05) + (floor_area * 0.1)',
                'is_required' => true,
                'sort_order' => 4,
                'notes' => 'Arena para mortero y concreto'
            ],
            [
                'construction_type_id' => 1, // Recámara
                'material_id' => 4, // Grava 3/4"
                'application_type' => 'concreto',
                'quantity_per_unit' => 0.1, // 0.1 m³ por m²
                'unit_measure' => 'm³',
                'calculation_formula' => 'floor_area * 0.1',
                'is_required' => true,
                'sort_order' => 5,
                'notes' => 'Grava para concreto'
            ],
            [
                'construction_type_id' => 1, // Recámara
                'material_id' => 5, // Varilla 3/8"
                'application_type' => 'castillo',
                'quantity_per_unit' => 0.25, // 1 varilla por 4 castillos
                'unit_measure' => 'pieza',
                'calculation_formula' => 'ceil(perimeter / 4) / 4',
                'is_required' => true,
                'sort_order' => 6,
                'notes' => 'Varilla para castillos'
            ],

            // Sala - Materiales
            [
                'construction_type_id' => 2, // Sala
                'material_id' => 1, // Cemento Portland
                'application_type' => 'muro_ladrillo',
                'quantity_per_unit' => 0.014,
                'unit_measure' => 'saco',
                'calculation_formula' => 'wall_area * 0.014',
                'is_required' => true,
                'sort_order' => 1,
                'notes' => 'Cemento para muros de ladrillo'
            ],
            [
                'construction_type_id' => 2, // Sala
                'material_id' => 2, // Ladrillo Rojo
                'application_type' => 'muro',
                'quantity_per_unit' => 20.0,
                'unit_measure' => 'pieza',
                'calculation_formula' => 'wall_area * 20',
                'is_required' => true,
                'sort_order' => 2,
                'notes' => 'Ladrillos para muros'
            ],
            [
                'construction_type_id' => 2, // Sala
                'material_id' => 1, // Cemento Portland
                'application_type' => 'piso_concreto',
                'quantity_per_unit' => 1.0,
                'unit_measure' => 'saco',
                'calculation_formula' => 'floor_area * 1',
                'is_required' => true,
                'sort_order' => 3,
                'notes' => 'Cemento para piso'
            ],
            [
                'construction_type_id' => 2, // Sala
                'material_id' => 3, // Arena de Río
                'application_type' => 'mortero_concreto',
                'quantity_per_unit' => 0.15,
                'unit_measure' => 'm³',
                'calculation_formula' => '(wall_area * 0.05) + (floor_area * 0.1)',
                'is_required' => true,
                'sort_order' => 4,
                'notes' => 'Arena para mortero y concreto'
            ],
            [
                'construction_type_id' => 2, // Sala
                'material_id' => 4, // Grava 3/4"
                'application_type' => 'concreto',
                'quantity_per_unit' => 0.1,
                'unit_measure' => 'm³',
                'calculation_formula' => 'floor_area * 0.1',
                'is_required' => true,
                'sort_order' => 5,
                'notes' => 'Grava para concreto'
            ],
            [
                'construction_type_id' => 2, // Sala
                'material_id' => 5, // Varilla 3/8"
                'application_type' => 'castillo',
                'quantity_per_unit' => 0.25,
                'unit_measure' => 'pieza',
                'calculation_formula' => 'ceil(perimeter / 4) / 4',
                'is_required' => true,
                'sort_order' => 6,
                'notes' => 'Varilla para castillos'
            ],

            // Baño - Materiales (incluye plomería)
            [
                'construction_type_id' => 3, // Baño
                'material_id' => 1, // Cemento Portland
                'application_type' => 'muro_ladrillo',
                'quantity_per_unit' => 0.014,
                'unit_measure' => 'saco',
                'calculation_formula' => 'wall_area * 0.014',
                'is_required' => true,
                'sort_order' => 1,
                'notes' => 'Cemento para muros de ladrillo'
            ],
            [
                'construction_type_id' => 3, // Baño
                'material_id' => 2, // Ladrillo Rojo
                'application_type' => 'muro',
                'quantity_per_unit' => 20.0,
                'unit_measure' => 'pieza',
                'calculation_formula' => 'wall_area * 20',
                'is_required' => true,
                'sort_order' => 2,
                'notes' => 'Ladrillos para muros'
            ],
            [
                'construction_type_id' => 3, // Baño
                'material_id' => 1, // Cemento Portland
                'application_type' => 'piso_concreto',
                'quantity_per_unit' => 1.0,
                'unit_measure' => 'saco',
                'calculation_formula' => 'floor_area * 1',
                'is_required' => true,
                'sort_order' => 3,
                'notes' => 'Cemento para piso'
            ],
            [
                'construction_type_id' => 3, // Baño
                'material_id' => 3, // Arena de Río
                'application_type' => 'mortero_concreto',
                'quantity_per_unit' => 0.15,
                'unit_measure' => 'm³',
                'calculation_formula' => '(wall_area * 0.05) + (floor_area * 0.1)',
                'is_required' => true,
                'sort_order' => 4,
                'notes' => 'Arena para mortero y concreto'
            ],
            [
                'construction_type_id' => 3, // Baño
                'material_id' => 4, // Grava 3/4"
                'application_type' => 'concreto',
                'quantity_per_unit' => 0.1,
                'unit_measure' => 'm³',
                'calculation_formula' => 'floor_area * 0.1',
                'is_required' => true,
                'sort_order' => 5,
                'notes' => 'Grava para concreto'
            ],
            [
                'construction_type_id' => 3, // Baño
                'material_id' => 5, // Varilla 3/8"
                'application_type' => 'castillo',
                'quantity_per_unit' => 0.25,
                'unit_measure' => 'pieza',
                'calculation_formula' => 'ceil(perimeter / 4) / 4',
                'is_required' => true,
                'sort_order' => 6,
                'notes' => 'Varilla para castillos'
            ],
            [
                'construction_type_id' => 3, // Baño
                'material_id' => 6, // Tubería PVC
                'application_type' => 'plomeria',
                'quantity_per_unit' => 5.0, // 5 metros por baño
                'unit_measure' => 'metro',
                'calculation_formula' => '5',
                'is_required' => true,
                'sort_order' => 7,
                'notes' => 'Tubería para plomería'
            ]
        ];

        foreach ($constructionTypeMaterials as $material) {
            DB::table('construction_type_materials')->insert($material);
        }
    }
} 