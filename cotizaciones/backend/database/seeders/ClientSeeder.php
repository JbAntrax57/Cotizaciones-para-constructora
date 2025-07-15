<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $clients = [
            [
                'business_name' => 'Constructora del Norte S.A. de C.V.',
                'rfc' => 'CON123456789',
                'address' => 'Av. Principal 123, Col. Centro, Monterrey, NL',
                'phone' => '81-1234-5678',
                'email' => 'contacto@constructoranorte.com'
            ],
            [
                'business_name' => 'Desarrollos Residenciales del Sur',
                'rfc' => 'DRS987654321',
                'address' => 'Blvd. Reforma 456, Col. Moderna, Guadalajara, Jal',
                'phone' => '33-9876-5432',
                'email' => 'info@desarrollosur.com'
            ],
            [
                'business_name' => 'Ingeniería y Construcción Integral',
                'rfc' => 'ICI456789123',
                'address' => 'Calle Comercial 789, Col. Industrial, Querétaro, Qro',
                'phone' => '442-4567-8901',
                'email' => 'ventas@ingenieriaintegral.com'
            ],
            [
                'business_name' => 'Arquitectura y Diseño Contemporáneo',
                'rfc' => 'ADC789123456',
                'address' => 'Paseo de la Reforma 321, Col. Juárez, CDMX',
                'phone' => '55-7890-1234',
                'email' => 'proyectos@arquitecturadc.com'
            ],
            [
                'business_name' => 'Constructora Regional del Este',
                'rfc' => 'CRE321654987',
                'address' => 'Av. Tecnológico 654, Col. Universitaria, Puebla, Pue',
                'phone' => '222-3216-5498',
                'email' => 'contacto@constructoraeste.com'
            ],
            [
                'business_name' => 'Desarrollos Urbanos del Pacífico',
                'rfc' => 'DUP147258369',
                'address' => 'Blvd. Costero 147, Col. Marina, Acapulco, Gro',
                'phone' => '744-1472-5836',
                'email' => 'info@desarrollospacifico.com'
            ],
            [
                'business_name' => 'Constructora Especializada en Vivienda',
                'rfc' => 'CEV963852741',
                'address' => 'Calle Residencial 963, Col. Las Palmas, Mérida, Yuc',
                'phone' => '999-9638-5274',
                'email' => 'ventas@constructoraespecializada.com'
            ],
            [
                'business_name' => 'Ingeniería Estructural del Centro',
                'rfc' => 'IEC852741963',
                'address' => 'Av. Industrial 852, Col. Parque Industrial, León, Gto',
                'phone' => '477-8527-4196',
                'email' => 'proyectos@ingenieriaestructural.com'
            ]
        ];

        foreach ($clients as $client) {
            DB::table('clients')->insert($client);
        }
    }
} 