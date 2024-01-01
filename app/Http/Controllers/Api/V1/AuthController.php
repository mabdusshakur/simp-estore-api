<?php

namespace App\Http\Controllers\Api\v1;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mail;

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
                    'status' => 'error',
                    'message' => $validatedData->errors(),
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
            ]);

            // Send welcome email
            Mail::to($user->email)->send(new WelcomeMail([
                'subject' => 'Welcome to ' . config('app.name'),
                'company_name' => config('app.name'),
                'name' => $user->name,
            ]));

            $token = $user->createToken('registerToken')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
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
                    'status' => 'error',
                    'message' => $validatedData->errors(),
                ], 401);
            }

            $credentials = $request->only('email', 'phone_number', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid login details',
                ], 401);
            }

            $user = $request->user();
            $token = $user->createToken('loginToken')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->noContent();
    }
}
