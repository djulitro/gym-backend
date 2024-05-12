<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AdminCreateRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function register(AdminCreateRequest $request)
    {
        $request = $request->safe()->all();

        $user = User::create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'confirm_email' => 1,
        ]);

        $admin = Admin::create([
            'user_id' => $user->id,
            'name' => $request['name'],
            'last_name' => $request['last_name'],
            'status' => 1,
        ]);


        return response()->json([
            'message' => 'Admin created successfully!',
            'admin' => $admin,
        ], 201);
    }

    public function update(AdminUpdateRequest $request, $id)
    {
        $request = $request->safe()->all();

        $admin = Admin::find($id);

        if (!$admin) {
            return response()->json([
                'message' => 'Admin not found!',
            ], 404);
        }

        $admin->name = $request['name'];
        $admin->last_name = $request['last_name'];
        if (key_exists('password', $request) && key_exists('confirm_password', $request)) {
            $admin->user->password = Hash::make($request['password']);
        }

        $admin->save();

        return response()->json([
            'message' => 'Admin updated successfully!',
            'admin' => $admin,
        ], 200);
    }

    public function getAll()
    {
        $admins = Admin::all();

        return response()->json($admins, 200);
    }

    public function delete($id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return response()->json([
                'message' => 'Admin not found!',
            ], 404);
        }

        $admin->delete();

        return response()->json([
            'message' => 'Admin deleted successfully!',
        ], 200);
    }
}
