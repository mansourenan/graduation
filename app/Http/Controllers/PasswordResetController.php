<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Driver;

class PasswordResetController extends Controller
{
    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $driver = Driver::where('email', $request->email)->first();
        if (!$driver) return response()->json(['message' => 'Driver not found'], 404);

        $code = rand(100000, 999999);
        Cache::put('reset_code_' . $driver->email, $code, now()->addMinutes(10));

        Mail::raw("Your reset code is: $code", function ($message) use ($driver) {
            $message->to($driver->email)->subject('Reset Password Code');
        });

        return response()->json(['message' => 'Code sent to email.']);
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['email' => 'required|email', 'code' => 'required']);

        $cachedCode = Cache::get('reset_code_' . $request->email);
        if (!$cachedCode || $cachedCode != $request->code)
            return response()->json(['message' => 'Invalid or expired code'], 400);

        Cache::put('reset_verified_' . $request->email, true, now()->addMinutes(10));

        return response()->json(['message' => 'Code verified successfully']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $isVerified = Cache::get('reset_verified_' . $request->email);
        if (!$isVerified)
            return response()->json(['message' => 'Email not verified for reset'], 400);

        $driver = Driver::where('email', $request->email)->first();
        if (!$driver)
            return response()->json(['message' => 'Driver not found'], 404);

        $driver->password = Hash::make($request->password);
        $driver->save();

        Cache::forget('reset_code_' . $request->email);
        Cache::forget('reset_verified_' . $request->email);

        return response()->json(['message' => 'Password has been reset successfully']);
    }

    /**
     * Handle a forgot password request (Laravel style)
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $driver = Driver::where('email', $request->email)->first();
        if (!$driver) {
            return response()->json(['message' => 'البريد الإلكتروني غير مسجل.'], 404);
        }

        // يمكنك هنا استخدام الكود الحالي لإرسال كود أو رابط
        $code = rand(100000, 999999);
        \Cache::put('reset_code_' . $driver->email, $code, now()->addMinutes(10));

        \Mail::raw("Your reset code is: $code", function ($message) use ($driver) {
            $message->to($driver->email)->subject('Reset Password Code');
        });

        return response()->json(['message' => 'تم إرسال كود إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.']);
    }
}
