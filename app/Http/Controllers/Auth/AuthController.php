<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas!',
            ], 401);
        }

        if($user->confirm_email==0){
            return response()->json([
                'message' => 'Correo electronico no verificado.',
            ], 401);
        }

        if($user->password_recovery != null){
            $user->password_recovery = null;
            $user->save();
        }

        $expiredAt = now()->addDays(5);

        $token = $user->createToken($request->header('User-Agent'), [], $expiredAt)->plainTextToken;

        $admin = Admin::where('user_id', $user->id)->first();
        $client = Client::where('user_id', $user->id)->first();

        $data = [
            'token' => $token,
            'user' => $user,
            'admin' => $admin,
            'client' => $client,
            'expiredAt' => $expiredAt->format('Y-m-d H:i:s'),
        ];

        return response()->json($data, 200);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Token eliminado correctamente.',
        ], 200);
    }
}
