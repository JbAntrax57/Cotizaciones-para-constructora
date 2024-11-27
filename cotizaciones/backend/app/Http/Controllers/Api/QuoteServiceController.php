<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuoteService;
use Illuminate\Http\Request;

class QuoteServiceController extends Controller
{
    public function update(Request $request, QuoteService $quoteService)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0'
        ]);

        $validated['total'] = $validated['quantity'] * $validated['unit_price'];
        $quoteService->update($validated);
        return $quoteService;
    }

    public function destroy(QuoteService $quoteService)
    {
        $quoteService->delete();
        return response()->noContent();
    }
}