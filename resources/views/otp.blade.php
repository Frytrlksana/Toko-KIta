<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <!-- Link CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles6.css') }}">
</head>
<body>
    <div class="main-container">
        <!-- Logo di kiri -->
        <div class="logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
        
        <!-- Form OTP di kanan -->
        <div class="otp-container">
            <h2>OTP</h2>
            <p>Please enter your OTP</p>
            <p></p>
            <p>Code sent to email unless you already have an account </p>
            <form action="{{ route('otp.verify') }}" method="POST">
                @csrf
                <div class="otp-input-container">
                    <input type="text" name="otp[]" maxlength="1" class="otp-input" oninput="moveToNext(this, 1)" autofocus>
                    <input type="text" name="otp[]" maxlength="1" class="otp-input" oninput="moveToNext(this, 2)">
                    <input type="text" name="otp[]" maxlength="1" class="otp-input" oninput="moveToNext(this, 3)">
                    <input type="text" name="otp[]" maxlength="1" class="otp-input" oninput="moveToNext(this, 4)">
                    <input type="text" name="otp[]" maxlength="1" class="otp-input" oninput="moveToNext(this, 5)">
                    <input type="text" name="otp[]" maxlength="1" class="otp-input" oninput="moveToNext(this, 6)">
                </div>
                @error('otp')
                    <div class="error">{{ $message }}</div>
                @enderror
                <p><a href="#">Resend OTP</a></p>
                <button type="submit" class="button">VERIFICATION</button>
            </form>
        </div>
    </div>

    <script>
        function moveToNext(current, nextIndex) {
            if (current.value.length === 1) {
                const nextInput = document.querySelectorAll('.otp-input')[nextIndex];
                if (nextInput) nextInput.focus();
            }
        }
    </script>
</body>
</html>
