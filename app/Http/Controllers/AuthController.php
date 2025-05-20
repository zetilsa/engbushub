<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\LoginOtp;
use App\Mail\OtpLoginMail;
use Illuminate\Support\Str;
use App\Mail\LoginAlertMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users,username',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'city_id' => 'required|exists:cities,id',
            'phone_number' => ['required', 'string', 'unique:users,phone_number', 'regex:/^08[0-9]{8,11}$/'],
        ], [
            'phone_number.regex' => 'Nomor telepon harus diawali dengan 08 dan memiliki 10-13 digit.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' =>  $request->email,
            'password' => bcrypt($request->password),
            'city_id' => $request->city_id,
            'phone_number' => $request->phone_number
        ]);

        $user->load('city.province'); 

        $token = $user->createToken('accessToken')->plainTextToken;

        return response()->json([
            'message' => 'Register Success',
            'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'city' => [
                'id' => $user->city->id,
                'name' => $user->city->name,
                'province' => [
                    'id' => $user->city->province->id,
                    'name' => $user->city->province->name
                ]
            ]
        ],
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors(),
        ], 422);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid email or password',
        ], 401);
    }

    $otp = rand(100000, 999999);

    LoginOtp::create([
        'user_id' => $user->id,
        'otp' => $otp,
        'expires_at' => now()->addMinutes(10),
    ]);

    Mail::to($user->email)->send(new OtpLoginMail($otp));

    return response()->json([
        'status' => 'otp_required',
        'message' => 'OTP telah dikirim ke email. Silakan verifikasi.',
        'email' => $user->email
    ]);
}   

    public function logout(Request $request) {
        $user = $request->user();
        try {

            $user->tokens()->delete();

            return response()->json([
                'message' => 'Logout successful',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => [
                    'server' => ['Something went wrong. Please try again later.'],
                ],
            ], 500);
        }
    
    }

public function verifyLoginOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'otp' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'User tidak ditemukan'], 404);
    }

    $otpRecord = LoginOtp::where('user_id', $user->id)
        ->where('otp', $request->otp)
        ->where('expires_at', '>', now())
        ->latest()
        ->first();

    if (!$otpRecord) {
        return response()->json(['message' => 'OTP salah atau sudah kedaluwarsa'], 400);
    }

    $token = $user->createToken('accessToken')->plainTextToken;

    Mail::to($user->email)->send(new LoginAlertMail($user));

    $otpRecord->delete();

    return response()->json([
        'message' => 'Login berhasil',
        'token' => $token,
        'user' => $user
    ]);
}

public function resendOtp(Request $request) {
    $request->validate([
        'email' => 'required|email',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'message' => 'User tidak ditemukan.'
        ], 404);
    }

    LoginOtp::where('user_id', $user->id)->delete();

    $otp = rand(100000, 999999);

    LoginOtp::create([
        'user_id' => $user->id,
        'otp' => $otp,
        'expires_at' => now()->addMinutes(10),
    ]);

    Mail::to($user->email)->send(new OtpLoginMail($otp));

    return response()->json([
        'message' => 'OTP baru telah dikirim.'
    ]);
}



}

