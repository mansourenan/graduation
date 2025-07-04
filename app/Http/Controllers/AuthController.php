<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Driver;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:drivers,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $driver = Driver::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['message' => 'Registered successfully', 'driver' => $driver], 201);
    }

    public function completeProfile(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|string',
        ]);

        $driver = Driver::find($request->driver_id);
        $driver->update($request->only(['first_name', 'last_name', 'phone_number']));

        return response()->json(['message' => 'Profile completed', 'driver' => $driver], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $driver = Driver::where('email', $request->email)->first();

        if (!$driver || !Hash::check($request->password, $driver->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // ✅ توليد التوكن باستخدام Sanctum
        $token = $driver->createToken('driver_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'driver' => $driver,
        ]);
    }

    public function logout(Request $request)
    {
        // لو انت مشغل Laravel Sanctum أو Passport الكود ده يشتغل
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
