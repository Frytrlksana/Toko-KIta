<?php

namespace App\Http\Controllers\API;

use App\Mail\SendMail;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email|required|unique:users',
            'notelp' => 'required',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['info' => 'Email sudah terdaftar!'], 201);
        }

        $otp = rand(100000, 999999);

        $user = User::create([
            'role' => 'user',
            'name' => $request->name,
            'email' => $request->email,
            'notelp' => $request->notelp,
            'password' => Hash::make($request->password),
            'status' => 'nonaktif',
            'otp' => $otp,
        ]);

        if ($user) {
            Mail::to($user->email)->send(new SendMail($otp));
        }

        return response()->json([
            'success' => true,
            'message' => 'Sukses',
            'data' => [
                'info' => 'Berhasil membuat akun. Silakan cek email untuk kode OTP.',
            ],
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['info' => 'Salah!'], 201);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['info' => 'Email belum terdaftar!'], 400);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['info' => 'Password salah!'], 400);
        }

        if ($user->status !== 'aktif') {
            return response()->json(['info' => 'Akun belum diverifikasi!'], 403);
        }

        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'success' => true,
            'message' => 'Sukses',
            'data' => [
                'info' => 'Login berhasil',
                'token' => $token,
            ],
        ];

        return response()->json($response, 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|integer',
        ]);

        $user = User::where('email', $request->email)->where('otp', $request->otp)->first();

        if (!$user) {
            return response()->json(['info' => 'OTP salah atau email tidak ditemukan.'], 400);
        }

        $user->status = 'aktif';
        $user->otp = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil diverifikasi.',
        ], 200);
    }

    public function logout()
    {
        Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        $response = [
            'success' => true,
            'message' => 'Sukses',
            'data' => [
                'info' => 'Logout berhasil!',
            ],
        ];

        return response()->json($response, 200);
    }
}



