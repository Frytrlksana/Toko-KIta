<?php

use Illuminate\Support\Facades\Route;

// Route ke halaman login utama
Route::get('/', function () {
    return view('login'); // Menampilkan halaman login utama
});

// Route ke halaman login pegawai
Route::get('/login/pegawai', function () {
    return "Halaman Login Pegawai"; // Menampilkan teks untuk halaman login pegawai
});

// Route ke halaman login pelanggan
Route::get('/login/pelanggan', function () {
    return "Halaman Login Pelanggan"; // Menampilkan teks untuk halaman login pelanggan
});

// Route ke halaman registrasi
Route::get('/register', function () {
    return view('register'); // Menampilkan halaman registrasi
});

// Route untuk memproses data registrasi
Route::post('/register', function () {
    return redirect()->back()->with('success', 'Registrasi berhasil!'); // Redirect kembali ke halaman dengan pesan sukses
});

// Route ke halaman login pegawai dengan penamaan
Route::get('/loginpegawai', function () {
    return view('loginpegawai'); // Menampilkan halaman login pegawai
})->name('loginpegawai');

// Route ke halaman login pelanggan dengan penamaan
Route::get('/loginpelanggan', function () {
    return view('home'); // Menampilkan halaman home
})->name('loginpelanggan');

