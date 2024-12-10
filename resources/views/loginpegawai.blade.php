<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back</title>
    <link rel="stylesheet" href="{{ asset('css/styles3.css') }}">
</head>
<body>
<div class="container">
        <div class="left-section">
            <img src="{{ asset('images/logo.png') }}" alt="Toko Kita Logo" class="logo">
        </div>
        <div class="right-section">
            <h2>WELCOME BACK</h2>
            <p>Please enter your details</p>
            <form action="/register" method="POST">
                @csrf
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <p><a href="forgot-password" class="forgot-password">Forgot password?</a></p>
                <button type="submit">LOG IN</button>
            </form>
            <p>Don't Have an account? <a href="register">Sign Up</a></p>
        </div>
</div>
</body>
</html>
