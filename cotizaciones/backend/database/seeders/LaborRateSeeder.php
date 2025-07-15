<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaborRateSeeder extends Seeder
{
    public function run()
    {
        $rates = [
            [
                'work_type' => 'ladrillo_pegado',
                'description' => 'Pegado de ladrillo rojo',
                'rate_per_unit' => 6.50,
                'unit_measure' => 'pieza',
                'is_active' => true
            ],
            [
                'work_type' => 'block_pegado',
                'description' => 'Pegado de block de concreto',
                'rate_per_unit' => 8.00,
                'unit_measure' => 'pieza',
                'is_active' => true
            ],
            [
                'work_type' => 'piso_colado',
                'description' => 'Colado de piso de concreto',
                'rate_per_unit' => 150.00,
                'unit_measure' => 'm²',
                'is_active' => true
            ],
            [
                'work_type' => 'cimiento_colado',
                'description' => 'Colado de cimiento',
                'rate_per_unit' => 80.00,
                'unit_measure' => 'm³',
                'is_active' => true
            ],
            [
                'work_type' => 'castillo_colado',
                'description' => 'Colado de castillo',
                'rate_per_unit' => 200.00,
                'unit_measure' => 'castillo',
                'is_active' => true
            ],
            [
                'work_type' => 'yeso_aplicado',
                'description' => 'Aplicación de yeso',
                'rate_per_unit' => 45.00,
                'unit_measure' => 'm²',
                'is_active' => true
            ],
            [
                'work_type' => 'pintura_aplicada',
                'description' => 'Aplicación de pintura',
                'rate_per_unit' => 35.00,
                'unit_measure' => 'm²',
                'is_active' => true
            ],
            [
                'work_type' => 'instalacion_electrica',
                'description' => 'Instalación eléctrica básica',
                'rate_per_unit' => 120.00,
                'unit_measure' => 'm²',
                'is_active' => true
            ],
            [
                'work_type' => 'instalacion_plomeria',
                'description' => 'Instalación de plomería',
                'rate_per_unit' => 180.00,
                'unit_measure' => 'm²',
                'is_active' => true
            ],
            [
                'work_type' => 'excavacion',
                'description' => 'Excavación manual',
                'rate_per_unit' => 60.00,
                'unit_measure' => 'm³',
                'is_active' => true
            ],
            [
                'work_type' => 'relleno_compactado',
                'description' => 'Relleno y compactación',
                'rate_per_unit' => 40.00,
                'unit_measure' => 'm³',
                'is_active' => true
            ],
            [
                'work_type' => 'impermeabilizacion',
                'description' => 'Impermeabilización de azotea',
                'rate_per_unit' => 85.00,
                'unit_measure' => 'm²',
                'is_active' => true
            ]
        ];

        foreach ($rates as $rate) {
            DB::table('labor_rates')->insert($rate);
        }
    }
} 