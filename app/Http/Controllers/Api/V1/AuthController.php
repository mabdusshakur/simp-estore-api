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

            // Create access token (30 minutes)
            $accessToken = $user->createToken('access_token', ['*'], now()->addMinutes(30))->plainTextToken;
            
            // Create refresh token (2 hours)
            $refreshToken = $user->createToken('refresh_token', ['refresh'], now()->addHours(2))->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type' => 'Bearer',
                'expires_in' => 1800, // 30 minutes in seconds
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
            
            // Create access token (30 minutes)
            $accessToken = $user->createToken('access_token', ['*'], now()->addMinutes(30))->plainTextToken;
            
            // Create refresh token (2 hours)
            $refreshToken = $user->createToken('refresh_token', ['refresh'], now()->addHours(2))->plainTextToken;

            return response()->json([
                'status' => 'success',
                'user' => $user,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type' => 'Bearer',
                'expires_in' => 1800, // 30 minutes in seconds
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

    /**
     * Verify the current token and return user information
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyToken()
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or expired token',
                ], 401);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Token is valid',
                'user' => $user,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Refresh tokens by revoking current refresh token and creating new access and refresh tokens
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken()
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or expired token',
                ], 401);
            }

            // Check if the current token has refresh ability
            if (!$user->tokenCan('refresh')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This token cannot be used to refresh. Please use a refresh token.',
                ], 403);
            }

            // Revoke the current refresh token
            $user->currentAccessToken()->delete();

            // Create new access token (30 minutes) with reset expiration
            $accessToken = $user->createToken('access_token', ['*'], now()->addMinutes(30))->plainTextToken;
            
            // Create new refresh token (2 hours) with reset expiration
            $refreshToken = $user->createToken('refresh_token', ['refresh'], now()->addHours(2))->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Token refreshed successfully',
                'user' => $user,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type' => 'Bearer',
                'expires_in' => 1800, // 30 minutes in seconds
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
