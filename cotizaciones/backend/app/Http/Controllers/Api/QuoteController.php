<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        return Quote::with(['client', 'project', 'services', 'materials'])->get();
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
            'services' => 'required|array',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|integer|min:1',
            'services.*.unit_price' => 'required|numeric|min:0',
            'materials' => 'required|array',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|integer|min:1',
            'materials.*.unit_price' => 'required|numeric|min:0'
        ]);

        $quote = Quote::create($validated);
        $this->syncQuoteItems($quote, $request);
        return $quote->load(['client', 'project', 'services', 'materials']);
    }

    private function syncQuoteItems($quote, $request)
    {
        foreach ($request->services as $service) {
            $quote->services()->create([
                'service_id' => $service['service_id'],
                'quantity' => $service['quantity'],
                'unit_price' => $service['unit_price'],
                'total' => $service['quantity'] * $service['unit_price']
            ]);
        }

        foreach ($request->materials as $material) {
            $quote->materials()->create([
                'material_id' => $material['material_id'],
                'quantity' => $material['quantity'],
                'unit_price' => $material['unit_price'],
                'total' => $material['quantity'] * $material['unit_price']
            ]);
        }
    }

    // Continúa con show, update y destroy...
}