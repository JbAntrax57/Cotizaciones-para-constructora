<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $clients = Client::all();
        return $this->successResponse($clients, 'Clientes obtenidos exitosamente');
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

        $client = Client::create($validated);
        return $this->createdResponse($client, 'Cliente creado exitosamente');
    }

    public function show(Client $client)
    {
        return $this->successResponse($client, 'Cliente obtenido exitosamente');
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
        return $this->updatedResponse($client, 'Cliente actualizado exitosamente');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return $this->deletedResponse('Cliente eliminado exitosamente');
    }
}