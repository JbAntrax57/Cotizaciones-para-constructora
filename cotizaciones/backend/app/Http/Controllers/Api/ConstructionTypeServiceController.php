<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConstructionTypeService;
use App\Models\ConstructionType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ConstructionTypeServiceController extends Controller
{
    public function index(ConstructionType $constructionType): JsonResponse
    {
        $services = $constructionType->services()
            ->with('service')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($cts) {
                return [
                    'id' => $cts->id,
                    'service_id' => $cts->service_id,
                    'service_name' => $cts->service->name,
                    'work_type' => $cts->work_type,
                    'rate_per_unit' => $cts->rate_per_unit,
                    'unit_measure' => $cts->unit_measure,
                    'calculation_formula' => $cts->calculation_formula,
                    'is_required' => $cts->is_required,
                    'sort_order' => $cts->sort_order,
                    'notes' => $cts->notes
                ];
            });

        return response()->json($services);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'construction_type_id' => 'required|exists:construction_types,id',
            'service_id' => 'required|exists:services,id',
            'work_type' => 'required|string|max:255',
            'rate_per_unit' => 'required|numeric|min:0',
            'unit_measure' => 'required|string|max:50',
            'calculation_formula' => 'required|string|max:500',
            'is_required' => 'boolean',
            'sort_order' => 'integer|min:0',
            'notes' => 'nullable|string|max:1000'
        ]);

        $constructionTypeService = ConstructionTypeService::create($validated);

        return response()->json($constructionTypeService, 201);
    }

    public function show(ConstructionTypeService $constructionTypeService): JsonResponse
    {
        $constructionTypeService->load('service');
        
        return response()->json($constructionTypeService);
    }

    public function update(Request $request, ConstructionTypeService $constructionTypeService): JsonResponse
    {
        $validated = $request->validate([
            'service_id' => 'sometimes|required|exists:services,id',
            'work_type' => 'sometimes|required|string|max:255',
            'rate_per_unit' => 'sometimes|required|numeric|min:0',
            'unit_measure' => 'sometimes|required|string|max:50',
            'calculation_formula' => 'sometimes|required|string|max:500',
            'is_required' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0',
            'notes' => 'nullable|string|max:1000'
        ]);

        $constructionTypeService->update($validated);

        return response()->json($constructionTypeService);
    }

    public function destroy(ConstructionTypeService $constructionTypeService): JsonResponse
    {
        $constructionTypeService->delete();

        return response()->json(['message' => 'Servicio eliminado correctamente']);
    }
} 