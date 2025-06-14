<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    // register a new user
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            // 'password' => bcrypt($request->password),
            'password' => $request->password,
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    // public function login(Request $request): JsonResponse
    // {
    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
    //     ]);

    //     $user = User::where('email', $credentials['email'])->first();

    //     if (!$user || !Hash::check($credentials['password'], $user->password)) {
    //         return response()->json([
    //             'message' => 'Credenciais inválidas.'
    //         ], 401);
    //     }

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'message' => 'Login realizado com sucesso.',
    //         'user' => $user,
    //         'token' => $token,
    //     ]);
    // }
    public function login(Request $request): JsonResponse
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        return response()->json([
            'message' => 'Credenciais inválidas.'
        ], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login realizado com sucesso.',
        'user' => $user,
        'token' => $token,
    ]);
}

}
