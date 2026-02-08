<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:190|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $u = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $u->createToken('api')->plainTextToken;
        return response()->json(['user' => $u, 'token' => $token], 201);
    }

    public function login(Request $r)
    {
        $data = $r->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $u = User::where('email', $data['email'])->first();
        if (!$u || !Hash::check($data['password'], $u->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $u->createToken('api')->plainTextToken;
        return response()->json(['user' => $u, 'token' => $token]);
    }

    public function logout(Request $r)
    {
        $r->user()->currentAccessToken()->delete();
        return response()->json(['ok' => true]);
    }

    public function me(Request $r)
    {
        return response()->json(['user' => $r->user()]);
    }
}
