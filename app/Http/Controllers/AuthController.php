<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->string('name'),
            'email' => $request->string('email'),
            'username' => $request->string('username'),
            'password' => Hash::make($request->string('password')),
            
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = [
            'message' => "User ". $user->name . " registered successfully!!",
            'token' => $token
        ];
        return response()->json($response, 200); 
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->string('email'))->first();

        if(!$user || !Hash::check($request->string('password'), $user->password))
        {
            return response()->json(["message" => "user not found"], 404);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $response = [
            'message' => "User ". $user->name . " logedin successfully!!",
            'token' => $token
        ];
        return response()->json($response, 200);     
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'user loged out successfully'], 200);
    }
}
