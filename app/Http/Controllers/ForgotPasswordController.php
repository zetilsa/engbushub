<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\PasswordOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class ForgotPasswordController extends Controller
{
        public function requestOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $otp = rand(100000, 999999);

        PasswordOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // kirim email
        Mail::to($request->email)->send(new OtpMail($otp));


        return response()->json(['message' => 'OTP telah dikirim ke email.'], 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        $otpData = PasswordOtp::where('email', $request->email)
                    ->where('otp', $request->otp)
                    ->where('expires_at', '>', now())
                    ->latest()
                    ->first();

        if (!$otpData) {
            return response()->json(['message' => 'OTP salah atau sudah kedaluwarsa.'], 400);
        }

        return response()->json(['message' => 'OTP valid.'], 200);
    }

public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'otp' => 'required|string',
        'password' => 'required|string|confirmed|min:6',
    ]);

    $otpData = PasswordOtp::where('email', $request->email)
                ->where('otp', $request->otp)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

    if (!$otpData) {
        return response()->json(['message' => 'OTP salah atau sudah kedaluwarsa.'], 400);
    }

    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // Hapus OTP agar tidak bisa digunakan ulang
    PasswordOtp::where('email', $request->email)->delete();

    return response()->json(['message' => 'Password berhasil direset.'], 200);
}

}
