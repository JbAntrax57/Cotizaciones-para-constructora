<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $projects = [
            [
                'client_id' => 1,
                'name' => 'Casa Habitación Residencial Norte',
                'description' => 'Construcción de casa habitación de 120m² con 3 recámaras, sala, cocina y 2 baños',
                'location' => 'Col. Residencial Norte, Monterrey, NL',
                'start_date' => '2024-02-01',
                'end_date' => '2024-08-15',
                'status' => 'in_progress'
            ],
            [
                'client_id' => 2,
                'name' => 'Edificio de Oficinas Corporativas',
                'description' => 'Construcción de edificio de 5 niveles con 20 oficinas y estacionamiento',
                'location' => 'Blvd. Corporativo, Guadalajara, Jal',
                'start_date' => '2024-01-15',
                'end_date' => '2024-12-30',
                'status' => 'planning'
            ],
            [
                'client_id' => 3,
                'name' => 'Plaza Comercial Centro',
                'description' => 'Centro comercial con 15 locales comerciales y área de estacionamiento',
                'location' => 'Av. Comercial, Querétaro, Qro',
                'start_date' => '2024-03-01',
                'end_date' => '2024-10-31',
                'status' => 'in_progress'
            ],
            [
                'client_id' => 4,
                'name' => 'Departamento de Lujo',
                'description' => 'Remodelación de departamento de 80m² con acabados de lujo',
                'location' => 'Col. Polanco, CDMX',
                'start_date' => '2024-02-15',
                'end_date' => '2024-06-30',
                'status' => 'planning'
            ],
            [
                'client_id' => 5,
                'name' => 'Escuela Primaria Pública',
                'description' => 'Construcción de escuela con 6 aulas, dirección y área administrativa',
                'location' => 'Col. Educativa, Puebla, Pue',
                'start_date' => '2024-01-01',
                'end_date' => '2024-07-31',
                'status' => 'completed'
            ],
            [
                'client_id' => 6,
                'name' => 'Hotel Boutique Costero',
                'description' => 'Hotel de 12 habitaciones con restaurante y alberca',
                'location' => 'Zona Hotelera, Acapulco, Gro',
                'start_date' => '2024-04-01',
                'end_date' => '2024-11-30',
                'status' => 'planning'
            ],
            [
                'client_id' => 7,
                'name' => 'Conjunto Habitacional Las Palmas',
                'description' => 'Conjunto de 24 casas de 2 recámaras con áreas comunes',
                'location' => 'Col. Las Palmas, Mérida, Yuc',
                'start_date' => '2024-03-15',
                'end_date' => '2024-09-30',
                'status' => 'in_progress'
            ],
            [
                'client_id' => 8,
                'name' => 'Nave Industrial Automotriz',
                'description' => 'Nave industrial de 2000m² para ensamblaje automotriz',
                'location' => 'Parque Industrial, León, Gto',
                'start_date' => '2024-02-01',
                'end_date' => '2024-08-31',
                'status' => 'in_progress'
            ]
        ];

        foreach ($projects as $project) {
            DB::table('projects')->insert($project);
        }
    }
} 