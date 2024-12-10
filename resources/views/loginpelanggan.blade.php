<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back</title>
    <link rel="stylesheet" href="{{ asset('css/styles4.css') }}">
</head>
<body>
<div class="container">
        <div class="left-section">
            <img src="{{ asset('images/logo.png') }}" alt="Toko Kita Logo" class="logo">
        </div>
        <div class="right-section">
            <h2>WELCOME !</h2>
            <p>Please enter your details</p>
            <form action="/register" method="POST">
                @csrf
                <input type="text" name="nama" placeholder="Nama" required>
                <input type="text" name="no_telepon" placeholder="No. Telepon" required>
                <button type="submit">LOG IN</button>
            </form>
        </div>
</div>
</body>
</html>
