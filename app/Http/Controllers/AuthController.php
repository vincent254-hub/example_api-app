<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
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
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([$user, $token => 'token', 201]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if(!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);

        }

        $user = User::where('email', $request->email)->firstOrFail();
        
        // $user = User::where('email', $request->email)->first();

        // if (! $user || ! Hash::check($request->password, $user->password)) {
        //     throw ValidationException::withMessages([
        //         'email' => ['The provided credentials are incorrect.'],
        //     ]);
        // }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        // $request->user()->currentAccessToken()->delete();
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function user()
    {
        return response()->json(Auth::user());
    }
}
