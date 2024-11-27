<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuoteMaterial;
use Illuminate\Http\Request;

class QuoteMaterialController extends Controller
{
    public function update(Request $request, QuoteMaterial $quoteMaterial)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0'
        ]);

        $validated['total'] = $validated['quantity'] * $validated['unit_price'];
        $quoteMaterial->update($validated);
        return $quoteMaterial;
    }

    public function destroy(QuoteMaterial $quoteMaterial)
    {
        $quoteMaterial->delete();
        return response()->noContent();
    }
}