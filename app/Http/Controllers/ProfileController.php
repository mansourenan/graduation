<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name ?? $user->first_name,
            'email' => $user->email,
            'phone_number' => $user->phone_number ?? null,
            'avatar' => $user->avatar ?? null,
            'notifications_enabled' => $user->notifications_enabled ?? true,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return response()->json(['message' => 'Profile updated', 'user' => $user]);
    }

    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('avatar')->store('avatars', 'public');

        $user->avatar = $path;
        $user->save();

        return response()->json(['message' => 'Avatar updated', 'avatar_url' => asset('storage/' . $path)]);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(6)],
            'new_password_confirmation' => 'required',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 403);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function toggleNotifications(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'enabled' => 'required|boolean',
        ]);
        $user->notifications_enabled = $request->enabled;
        $user->save();
        return response()->json([
            'message' => 'Notification status updated',
            'notifications_enabled' => $user->notifications_enabled
        ]);
    }
}
