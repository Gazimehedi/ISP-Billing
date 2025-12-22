<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'mobile' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'user' => Auth::user(),
                'message' => 'Logged in successfully'
            ]);
        }

        return response()->json([
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['mobile' => 'required|string']);
        
        // In a real app, send SMS/Token. For demo, just return success.
        return response()->json(['message' => 'Password reset instructions sent to your mobile.']);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,mobile', // or unique:users,username if added
            'mobile' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'company_name' => 'required|string',
            'division' => 'required|string',
            'district' => 'required|string',
            'thana' => 'required|string',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
            'role' => 'client', // Default to client
        ]);

        return response()->json(['user' => $user, 'message' => 'Account created successfully'], 201);
    }
}
