<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return Client::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'rfc' => 'required|string|max:13',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email'
        ]);

        return Client::create($validated);
    }

    public function show(Client $client)
    {
        return $client;
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'rfc' => 'required|string|max:13',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email'
        ]);

        $client->update($validated);
        return $client;
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return response()->noContent();
    }
}