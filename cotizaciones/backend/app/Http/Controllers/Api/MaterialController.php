<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $materials = Material::all();
        return $this->successResponse($materials, 'Materiales obtenidos exitosamente');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'unit_cost' => 'required|numeric|min:0',
            'description' => 'required|string'
        ]);

        $material = Material::create($validated);
        return $this->createdResponse($material, 'Material creado exitosamente');
    }

    public function show(Material $material)
    {
        return $this->successResponse($material, 'Material obtenido exitosamente');
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'unit_cost' => 'required|numeric|min:0',
            'description' => 'required|string'
        ]);

        $material->update($validated);
        return $this->updatedResponse($material, 'Material actualizado exitosamente');
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return $this->deletedResponse('Material eliminado exitosamente');
    }
}