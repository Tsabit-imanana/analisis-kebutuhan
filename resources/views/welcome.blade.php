<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/login.css'])
    <title>Welcome</title>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <img src="..." alt="Logo MUMS" class="logo-img">
                <h1 class="logo-text">MUMS</h1>
            </div>
            <h3>Sign In</h3>
            <form method="POST" action="...">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" placeholder="Masukkan Email Terdaftar" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" placeholder="Masukkan Password" required>
                </div>
                <div class="form-group">
                    <label for="remember" class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember">Remember Me
                    </label>
                </div>
                <button type="submit" class="button-login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
