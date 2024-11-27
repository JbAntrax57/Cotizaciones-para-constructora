<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        return Material::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
            'unit' => 'required|string',
            'unit_cost' => 'required|numeric|min:0'
        ]);

        return Material::create($validated);
    }

    public function show(Material $material)
    {
        return $material;
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
            'unit' => 'required|string',
            'unit_cost' => 'required|numeric|min:0'
        ]);

        $material->update($validated);
        return $material;
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return response()->noContent();
    }
}