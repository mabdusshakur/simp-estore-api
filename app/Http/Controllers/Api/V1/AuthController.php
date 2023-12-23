<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email',
                'phone_number' => 'required|numeric|unique:users,phone_number',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validatedData->fails()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => $validatedData->errors(),
                    ],
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('registerToken')->plainTextToken;

            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'User created successfully',
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => $th->getMessage(),
                ],
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'email' => 'sometimes|required_without:phone_number|string|email',
                'phone_number' => 'sometimes|required_without:email|numeric',
                'password' => 'required|string|min:8',
            ]);

            if ($validatedData->fails()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => $validatedData->errors(),
                    ],
                ], 401);
            }

            $credentials = $request->only('email', 'phone_number', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Invalid login details',
                    ],
                ], 401);
            }

            $user = $request->user();
            $token = $user->createToken('loginToken')->plainTextToken;

            return response()->json([
                'data' => [
                    'status' => 'success',
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => $th->getMessage(),
                ],
            ], 500);
        }

    }
}
