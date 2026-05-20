<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/welcome.css'])
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <div class="logo-mark">M</div>
                <div>
                    <p class="logo-subtitle">PT. MUMS</p>
                    <h1 class="logo-text">Multi Role Login</h1>
                </div>
            </div>
            <p class="login-description">Masuk menggunakan email dan password sesuai role akun Anda.</p>

            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" placeholder="Masukkan email terdaftar" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" placeholder="Masukkan password" required>
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="remember" class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember"> Remember Me
                    </label>
                </div>
                <button type="submit" class="button-login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
