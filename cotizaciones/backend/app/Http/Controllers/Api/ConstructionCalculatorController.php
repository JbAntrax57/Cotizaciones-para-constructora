<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Service;
use App\Models\ConstructionType;
use App\Models\ConstructionTypeMaterial;
use App\Models\ConstructionTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConstructionCalculatorController extends Controller
{
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'construction_type' => 'required|string',
            'length' => 'required|numeric|min:0.1',
            'width' => 'required|numeric|min:0.1',
            'height' => 'required|numeric|min:0.1',
        ]);

        $length = $validated['length'];
        $width = $validated['width'];
        $height = $validated['height'];
        $constructionType = $validated['construction_type'];

        // Calcular áreas y volúmenes
        $floorArea = $length * $width;
        $wallArea = 2 * ($length + $width) * $height;
        $perimeter = 2 * ($length + $width);

        // Obtener el tipo de construcción
        $constructionTypeModel = ConstructionType::where('name', $constructionType)->first();
        
        if (!$constructionTypeModel) {
            return response()->json(['error' => 'Tipo de construcción no encontrado'], 404);
        }

        // Calcular materiales necesarios usando la nueva estructura
        $materials = $this->calculateMaterials($floorArea, $wallArea, $perimeter, $height, $constructionTypeModel);
        
        // Calcular servicios usando la nueva estructura
        $services = $this->calculateServices($floorArea, $wallArea, $perimeter, $constructionTypeModel);

        // Calcular totales
        $materialsSubtotal = collect($materials)->sum('total_cost');
        $servicesSubtotal = collect($services)->sum('total_cost');
        $total = $materialsSubtotal + $servicesSubtotal;

        return response()->json([
            'construction_type' => $constructionType,
            'dimensions' => [
                'length' => $length,
                'width' => $width,
                'height' => $height,
                'floor_area' => $floorArea,
                'wall_area' => $wallArea,
                'perimeter' => $perimeter
            ],
            'materials' => $materials,
            'services' => $services,
            'totals' => [
                'materials_subtotal' => $materialsSubtotal,
                'services_subtotal' => $servicesSubtotal,
                'total' => $total
            ]
        ]);
    }

    private function calculateMaterials($floorArea, $wallArea, $perimeter, $height, $constructionType)
    {
        $materials = [];
        
        // Obtener materiales configurados para este tipo de construcción
        $constructionTypeMaterials = $constructionType->materials()
            ->with('material')
            ->orderBy('sort_order')
            ->get();

        foreach ($constructionTypeMaterials as $ctm) {
            $material = $ctm->material;
            
            // Calcular cantidad usando la fórmula configurada
            $quantity = $this->evaluateFormula($ctm->calculation_formula, [
                'floor_area' => $floorArea,
                'wall_area' => $wallArea,
                'perimeter' => $perimeter,
                'height' => $height
            ]);

            if ($quantity > 0) {
                $materials[] = [
                    'material_id' => $material->id,
                    'name' => $material->name,
                    'quantity' => $quantity,
                    'unit' => $ctm->unit_measure,
                    'unit_cost' => $material->unit_cost,
                    'total_cost' => $quantity * $material->unit_cost,
                    'application' => $ctm->application_type,
                    'notes' => $ctm->notes
                ];
            }
        }

        return $materials;
    }

    private function calculateServices($floorArea, $wallArea, $perimeter, $constructionType)
    {
        $services = [];
        
        // Obtener servicios configurados para este tipo de construcción
        $constructionTypeServices = $constructionType->services()
            ->with('service')
            ->orderBy('sort_order')
            ->get();

        foreach ($constructionTypeServices as $cts) {
            $service = $cts->service;
            
            // Calcular cantidad usando la fórmula configurada
            $quantity = $this->evaluateFormula($cts->calculation_formula, [
                'floor_area' => $floorArea,
                'wall_area' => $wallArea,
                'perimeter' => $perimeter
            ]);

            if ($quantity > 0) {
                $services[] = [
                    'service_id' => $service->id,
                    'name' => $service->name,
                    'work_type' => $cts->work_type,
                    'description' => $service->description,
                    'quantity' => $quantity,
                    'unit' => $cts->unit_measure,
                    'rate_per_unit' => $cts->rate_per_unit,
                    'total_cost' => $quantity * $cts->rate_per_unit,
                    'notes' => $cts->notes
                ];
            }
        }

        return $services;
    }

    private function evaluateFormula($formula, $variables)
    {
        // Reemplazar variables en la fórmula
        foreach ($variables as $key => $value) {
            $formula = str_replace($key, $value, $formula);
        }
        
        // Evaluar la fórmula de manera segura
        try {
            // Crear una función anónima para evaluar la expresión
            $result = eval("return $formula;");
            return is_numeric($result) ? $result : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function getConstructionTypes()
    {
        $types = ConstructionType::all();
        return response()->json($types);
    }
} 