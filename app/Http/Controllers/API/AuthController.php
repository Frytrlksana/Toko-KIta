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

    public function adminLogin(Request $request)
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

        // Tambahkan logika untuk memastikan user adalah admin
        if ($user->role !== 'admin') {
            return response()->json(['info' => 'Hanya admin yang dapat login di sini!'], 403);
        }

        $token = $user->createToken('admin_token')->plainTextToken;

        $response = [
            'success' => true,
            'message' => 'Sukses',
            'data' => [
                'info' => 'Login admin berhasil',
                'token' => $token,
            ],
        ];

        return response()->json($response, 200);
    }

    public function userLogin(Request $request)
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

        // Tambahkan logika untuk memastikan user bukan admin
        if ($user->role !== 'user') {
            return response()->json(['info' => 'Hanya pengguna biasa yang dapat login di sini!'], 403);
        }

        $token = $user->createToken('user_token')->plainTextToken;

        $response = [
            'success' => true,
            'message' => 'Sukses',
            'data' => [
                'info' => 'Login user berhasil',
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

    public function adminLogout(Request $request)
    {
        $user = Auth::user();

        // Memeriksa apakah pengguna sudah terautentikasi dan apakah mereka admin
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak terautentikasi.',
            ], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat melakukan logout.',
            ], 403);
        }

        // Menghapus semua token admin yang terkait dengan sesi saat ini
        $user->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil untuk admin.',
        ], 200);
    }

    public function userLogout(Request $request)
    {
        $user = Auth::user();

        // Memeriksa apakah pengguna sudah terautentikasi dan apakah mereka user biasa
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak terautentikasi.',
            ], 401);
        }

        if ($user->role !== 'user') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengguna biasa yang dapat melakukan logout.',
            ], 403);
        }

        // Menghapus semua token user yang terkait dengan sesi saat ini
        $user->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil untuk user.',
        ], 200);
    }

}



