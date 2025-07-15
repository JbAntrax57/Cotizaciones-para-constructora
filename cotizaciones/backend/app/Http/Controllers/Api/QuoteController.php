<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\QuoteService;
use App\Models\QuoteMaterial;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $quotes = Quote::with(['client', 'project'])->get();
        return $this->successResponse($quotes, 'Cotizaciones obtenidas exitosamente');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'required|exists:projects,id',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'status' => 'required|in:draft,sent,approved,rejected',
            'services' => 'array',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|numeric|min:1',
            'services.*.unit_price' => 'required|numeric|min:0',
            'materials' => 'array',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|numeric|min:1',
            'materials.*.unit_price' => 'required|numeric|min:0'
        ]);

        $quote = Quote::create([
            'client_id' => $validated['client_id'],
            'project_id' => $validated['project_id'],
            'user_id' => auth()->id() ?? 1,
            'subtotal' => $validated['subtotal'],
            'tax' => $validated['tax'],
            'discount' => $validated['discount'],
            'total' => $validated['total'],
            'status' => $validated['status']
        ]);

        // Crear servicios de la cotización
        if (!empty($validated['services'])) {
            foreach ($validated['services'] as $service) {
                QuoteService::create([
                    'quote_id' => $quote->id,
                    'service_id' => $service['service_id'],
                    'quantity' => $service['quantity'],
                    'unit_price' => $service['unit_price'],
                    'total' => $service['quantity'] * $service['unit_price']
                ]);
            }
        }

        // Crear materiales de la cotización
        if (!empty($validated['materials'])) {
            foreach ($validated['materials'] as $material) {
                QuoteMaterial::create([
                    'quote_id' => $quote->id,
                    'material_id' => $material['material_id'],
                    'quantity' => $material['quantity'],
                    'unit_price' => $material['unit_price'],
                    'total' => $material['quantity'] * $material['unit_price']
                ]);
            }
        }

        $quote->load(['client', 'project', 'services', 'materials']);
        return $this->createdResponse($quote, 'Cotización creada exitosamente');
    }

    public function show(Quote $quote)
    {
        $quote->load(['client', 'project', 'services.service', 'materials.material']);
        return $this->successResponse($quote, 'Cotización obtenida exitosamente');
    }

    public function update(Request $request, Quote $quote)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'required|exists:projects,id',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'status' => 'required|in:draft,sent,approved,rejected',
            'services' => 'array',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|numeric|min:1',
            'services.*.unit_price' => 'required|numeric|min:0',
            'materials' => 'array',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|numeric|min:1',
            'materials.*.unit_price' => 'required|numeric|min:0'
        ]);

        $quote->update([
            'client_id' => $validated['client_id'],
            'project_id' => $validated['project_id'],
            'subtotal' => $validated['subtotal'],
            'tax' => $validated['tax'],
            'discount' => $validated['discount'],
            'total' => $validated['total'],
            'status' => $validated['status']
        ]);

        // Actualizar servicios de la cotización
        if (!empty($validated['services'])) {
            // Eliminar servicios existentes
            $quote->services()->delete();
            
            // Crear nuevos servicios
            foreach ($validated['services'] as $service) {
                QuoteService::create([
                    'quote_id' => $quote->id,
                    'service_id' => $service['service_id'],
                    'quantity' => $service['quantity'],
                    'unit_price' => $service['unit_price'],
                    'total' => $service['quantity'] * $service['unit_price']
                ]);
            }
        }

        // Actualizar materiales de la cotización
        if (!empty($validated['materials'])) {
            // Eliminar materiales existentes
            $quote->materials()->delete();
            
            // Crear nuevos materiales
            foreach ($validated['materials'] as $material) {
                QuoteMaterial::create([
                    'quote_id' => $quote->id,
                    'material_id' => $material['material_id'],
                    'quantity' => $material['quantity'],
                    'unit_price' => $material['unit_price'],
                    'total' => $material['quantity'] * $material['unit_price']
                ]);
            }
        }

        $quote->load(['client', 'project', 'services', 'materials']);
        return $this->updatedResponse($quote, 'Cotización actualizada exitosamente');
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();
        return $this->deletedResponse('Cotización eliminada exitosamente');
    }
}