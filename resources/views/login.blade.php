<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Kue</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Toko Kue Logo">
        </div>
        <div class="login-container">
            <div class="login">
                <h3>Anda ingin login sebagai...</h3>
            </div>
            <div class="buttons">
                <a href="loginpegawai"><h4>Pegawai</h4></a>
                <a href="loginpelanggan"><h4>Pelanggan</h4>
            </div>
        </div>
    </div>
</body>
</html>
