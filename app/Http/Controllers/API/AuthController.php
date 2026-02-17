<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
                'role' => 'required|string|in:' . User::ROLE_STUDENT . ',' . User::ROLE_LECTURER,
            ]);

            $validatedData['password'] = bcrypt($validatedData['password']);

            User::create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
            ], status: 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
            ], status: 500);
        }
    }


    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'status' => 'success',
                    'message' => 'User logged in successfully',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ], status: 200);
            } else {
                throw new \Exception('Invalid email or password');
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
            ], status: 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->tokens()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User logged out successfully',
            ], status: 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
            ], status: 500);
        }
    }
}
