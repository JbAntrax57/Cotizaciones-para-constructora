<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    public function run()
    {
        $materials = [
            [
                'name' => 'Cemento Portland',
                'supplier' => 'CEMEX',
                'unit' => 'saco',
                'unit_cost' => 85.00,
                'description' => 'Cemento gris para construcción'
            ],
            [
                'name' => 'Ladrillo Rojo',
                'supplier' => 'Ladrillera del Norte',
                'unit' => 'pieza',
                'unit_cost' => 2.50,
                'description' => 'Ladrillo estándar 7x14x28 cm'
            ],
            [
                'name' => 'Arena de Río',
                'supplier' => 'Arenera Central',
                'unit' => 'm³',
                'unit_cost' => 450.00,
                'description' => 'Arena fina para mortero'
            ],
            [
                'name' => 'Grava 3/4"',
                'supplier' => 'Gravera del Sur',
                'unit' => 'm³',
                'unit_cost' => 380.00,
                'description' => 'Grava para concreto'
            ],
            [
                'name' => 'Varilla 3/8"',
                'supplier' => 'Aceros del Norte',
                'unit' => 'pieza',
                'unit_cost' => 45.00,
                'description' => 'Varilla de 6 metros'
            ],
            [
                'name' => 'Alambre Recocido',
                'supplier' => 'Alambres del Centro',
                'unit' => 'kg',
                'unit_cost' => 25.00,
                'description' => 'Alambre para amarre'
            ],
            [
                'name' => 'Block de Concreto',
                'supplier' => 'Blockera del Este',
                'unit' => 'pieza',
                'unit_cost' => 8.50,
                'description' => 'Block 15x20x40 cm'
            ],
            [
                'name' => 'Yeso',
                'supplier' => 'Yesos del Norte',
                'unit' => 'kg',
                'unit_cost' => 12.00,
                'description' => 'Yeso para acabados'
            ],
            [
                'name' => 'Pintura Vinílica',
                'supplier' => 'Pinturas del Centro',
                'unit' => 'galón',
                'unit_cost' => 180.00,
                'description' => 'Pintura para interiores'
            ],
            [
                'name' => 'Cable Eléctrico',
                'supplier' => 'Electro Norte',
                'unit' => 'm',
                'unit_cost' => 15.00,
                'description' => 'Cable calibre 12'
            ],
            [
                'name' => 'Tubo PVC 2"',
                'supplier' => 'Plásticos del Sur',
                'unit' => 'm',
                'unit_cost' => 35.00,
                'description' => 'Tubo para drenaje'
            ],
            [
                'name' => 'Cemento Blanco',
                'supplier' => 'CEMEX',
                'unit' => 'kg',
                'unit_cost' => 8.50,
                'description' => 'Cemento para acabados'
            ]
        ];

        foreach ($materials as $material) {
            DB::table('materials')->insert($material);
        }
    }
} 