<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'full_name' => $request->full_name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'user', // Ensure role is set during registration
        ]);

        $token = $user->createToken($request->username)->plainTextToken;

        $user->token = $token; // Include token in the response data

        return response()->json([
            'status' => 'success',
            'message' => 'User registration successful',
            'data' => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        // Attempt login with the User guard
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken($request->username)->plainTextToken;
            $user->token = $token; // Include token in the response data

            return response()->json([
                'status' => 'success',
                'message' => 'User login successful',
                'data' => $user
            ], 201);
        }

        // Attempt login with the Administrator guard
        if (Auth::guard('administrator')->attempt(['username' => $request->username, 'password' => $request->password])) {
            $admin = Auth::guard('administrator')->user();
            $token = $admin->createToken($request->username)->plainTextToken;
            $admin->role = 'admin'; // Ensure role is set for admins
            $admin->token = $token; // Include token in the response data

            return response()->json([
                'status' => 'success',
                'message' => 'Admin login successful',
                'data' => $admin
            ], 201);
        }

        return response()->json([
            'status' => 'authentication_failed',
            'message' => 'The username or password you entered is incorrect'
        ], 401);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        
        if ($user) {
            $user->tokens()->delete(); // Revoke all tokens
            Auth::logout(); // Logout the user
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logout successful'
        ], 200);
    }
}
