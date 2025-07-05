<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Driver;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:drivers,email',
            'password' => 'required|confirmed|min:6',
        ]);

        try {
            $driver = Driver::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json(['message' => 'Registered successfully', 'driver' => $driver], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Registration failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function completeProfile(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ]);

        try {
            $driver = Driver::find($request->driver_id);
            
            if (!$driver) {
                return response()->json(['message' => 'Driver not found'], 404);
            }

            $driver->update($request->only(['first_name', 'last_name', 'phone_number']));

            return response()->json(['message' => 'Profile completed', 'driver' => $driver], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Profile update failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $driver = Driver::where('email', $request->email)->first();

            if (!$driver || !Hash::check($request->password, $driver->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $token = $driver->createToken('driver_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'driver' => $driver,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Login failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        // التحقق من وجود المستخدم
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Logged out successfully']);
    }

    // تسجيل الدخول عبر Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $driver = Driver::firstOrCreate([
            'email' => $googleUser->getEmail(),
        ], [
            'first_name' => $googleUser->getName(),
            'password' => bcrypt(uniqid()),
        ]);
        $token = $driver->createToken('driver_token')->plainTextToken;
        return response()->json([
            'message' => 'Login with Google successful',
            'token' => $token,
            'driver' => $driver,
        ]);
    }
    // تسجيل الدخول عبر Facebook
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }
    public function handleFacebookCallback()
    {
        $fbUser = Socialite::driver('facebook')->stateless()->user();
        $driver = Driver::firstOrCreate([
            'email' => $fbUser->getEmail(),
        ], [
            'first_name' => $fbUser->getName(),
            'password' => bcrypt(uniqid()),
        ]);
        $token = $driver->createToken('driver_token')->plainTextToken;
        return response()->json([
            'message' => 'Login with Facebook successful',
            'token' => $token,
            'driver' => $driver,
        ]);
    }
    // تسجيل الدخول عبر Twitter
    public function redirectToTwitter()
    {
        return Socialite::driver('twitter')->stateless()->redirect();
    }
    public function handleTwitterCallback()
    {
        $twUser = Socialite::driver('twitter')->stateless()->user();
        $driver = Driver::firstOrCreate([
            'email' => $twUser->getEmail(),
        ], [
            'first_name' => $twUser->getName(),
            'password' => bcrypt(uniqid()),
        ]);
        $token = $driver->createToken('driver_token')->plainTextToken;
        return response()->json([
            'message' => 'Login with Twitter successful',
            'token' => $token,
            'driver' => $driver,
        ]);
    }
}
