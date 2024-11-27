<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return Service::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'estimated_cost' => 'required|numeric|min:0'
        ]);

        return Service::create($validated);
    }

    public function show(Service $service)
    {
        return $service;
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'estimated_cost' => 'required|numeric|min:0'
        ]);

        $service->update($validated);
        return $service;
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return response()->noContent();
    }
}