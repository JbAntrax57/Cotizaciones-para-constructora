<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConstructionTypeMaterial;
use App\Models\ConstructionType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ConstructionTypeMaterialController extends Controller
{
    public function index(ConstructionType $constructionType): JsonResponse
    {
        $materials = $constructionType->materials()
            ->with('material')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($ctm) {
                return [
                    'id' => $ctm->id,
                    'material_id' => $ctm->material_id,
                    'material_name' => $ctm->material->name,
                    'application_type' => $ctm->application_type,
                    'quantity_per_unit' => $ctm->quantity_per_unit,
                    'unit_measure' => $ctm->unit_measure,
                    'calculation_formula' => $ctm->calculation_formula,
                    'is_required' => $ctm->is_required,
                    'sort_order' => $ctm->sort_order,
                    'notes' => $ctm->notes
                ];
            });

        return response()->json($materials);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'construction_type_id' => 'required|exists:construction_types,id',
            'material_id' => 'required|exists:materials,id',
            'application_type' => 'required|string|max:255',
            'quantity_per_unit' => 'required|numeric|min:0',
            'unit_measure' => 'required|string|max:50',
            'calculation_formula' => 'required|string|max:500',
            'is_required' => 'boolean',
            'sort_order' => 'integer|min:0',
            'notes' => 'nullable|string|max:1000'
        ]);

        $constructionTypeMaterial = ConstructionTypeMaterial::create($validated);

        return response()->json($constructionTypeMaterial, 201);
    }

    public function show(ConstructionTypeMaterial $constructionTypeMaterial): JsonResponse
    {
        $constructionTypeMaterial->load('material');
        
        return response()->json($constructionTypeMaterial);
    }

    public function update(Request $request, ConstructionTypeMaterial $constructionTypeMaterial): JsonResponse
    {
        $validated = $request->validate([
            'material_id' => 'sometimes|required|exists:materials,id',
            'application_type' => 'sometimes|required|string|max:255',
            'quantity_per_unit' => 'sometimes|required|numeric|min:0',
            'unit_measure' => 'sometimes|required|string|max:50',
            'calculation_formula' => 'sometimes|required|string|max:500',
            'is_required' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0',
            'notes' => 'nullable|string|max:1000'
        ]);

        $constructionTypeMaterial->update($validated);

        return response()->json($constructionTypeMaterial);
    }

    public function destroy(ConstructionTypeMaterial $constructionTypeMaterial): JsonResponse
    {
        $constructionTypeMaterial->delete();

        return response()->json(['message' => 'Material eliminado correctamente']);
    }
} 