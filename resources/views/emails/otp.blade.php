<!DOCTYPE html>
<html>
<head>
    <title>{{ 'Verification Your OTP' }}</title>
</head>
<body>
    <h2>{{ 'Verification Your OTP' }}</h2>
    <p>Dear User,</p>
    <p>Thank you for signing up! Please use the OTP code below to verify your email address:</p>
    <h1 style="font-size: 24px; color: #4CAF50;">{{ $otp }}</h1>
    <p>This code will expire in 10 minutes.</p>
    <p>If you did not request this verification, please ignore this email.</p>
    <br>
    <p>Best Regards,</p>
    <p>Toko Kita</p>
</body>
</html>
