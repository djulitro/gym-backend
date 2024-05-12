<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\ClientCreateRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function register(ClientCreateRequest $request)
    {
        $request = $request->safe()->all();

        $user = User::create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $client = Client::create([
            'user_id' => $user->id,
            'name' => $request['name'],
            'last_name' => $request['last_name'],
            'dni' => $request['dni'],
            'phone' => $request['phone'],
            'address' => $request['address'],
            'status' => 1,
        ]);

        // Implementar la funcionalidad para enviar correo electrónico al cliente para confirmar email

        return response()->json([
            'message' => 'Client created successfully!',
            'client' => $client,
        ], 201);

    }

    public function update(Request $request, $id)
    {
        $request = $request->safe()->all();

        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'message' => 'Client not found!',
            ], 404);
        }

        $client->name = $request['name'];
        $client->last_name = $request['last_name'];
        $client->dni = $request['dni'];
        $client->phone = $request['phone'];
        $client->address = $request['address'];

        if(key_exists('password', $request) && key_exists('confirm_password', $request)) {
            $client->user->password = Hash::make($request['password']);
        }

        $client->save();

        return response()->json([
            'message' => 'Client updated successfully!',
            'client' => $client,
        ], 200);
    }

    public function getAll()
    {
        $clients = Client::all();

        return response()->json([
            'clients' => $clients,
        ], 200);
    }

    public function delete($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'message' => 'Client not found!',
            ], 404);
        }

        $client->delete();

        return response()->json([
            'message' => 'Client deleted successfully!',
        ], 200);
    }
}
