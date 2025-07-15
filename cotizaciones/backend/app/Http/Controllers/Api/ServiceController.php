<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $services = Service::all();
        return $this->successResponse($services, 'Servicios obtenidos exitosamente');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimated_cost' => 'required|numeric|min:0'
        ]);

        $service = Service::create($validated);
        return $this->createdResponse($service, 'Servicio creado exitosamente');
    }

    public function show(Service $service)
    {
        return $this->successResponse($service, 'Servicio obtenido exitosamente');
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimated_cost' => 'required|numeric|min:0'
        ]);

        $service->update($validated);
        return $this->updatedResponse($service, 'Servicio actualizado exitosamente');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return $this->deletedResponse('Servicio eliminado exitosamente');
    }
}