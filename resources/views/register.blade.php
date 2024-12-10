<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="{{ asset('css/styles2.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <img src="{{ asset('images/logo.png') }}" alt="Toko Kita Logo" class="logo">
        </div>
        <div class="right-section">
            <h2>Create an Account</h2>
            <p>Please enter your details</p>
            <form action="/register" method="POST">
                @csrf
                <input type="text" name="nama" placeholder="Nama" required>
                <input type="text" name="no_telepon" placeholder="No. Telepon" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">CREATE ACCOUNT</button>
            </form>
            <p>Have an account? <a href="loginpegawai">Login</a></p>
        </div>
    </div>
    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Sukses!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
</body>
</html>
