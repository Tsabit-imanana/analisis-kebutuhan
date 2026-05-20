<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SPV Dashboard</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #e0f2fe, #f8fafc);
            color: #0f172a;
        }

        .card {
            width: min(92vw, 720px);
            padding: 32px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
            text-align: center;
        }

        .badge {
            display: inline-block;
            margin-bottom: 16px;
            padding: 8px 14px;
            border-radius: 999px;
            background: #0f172a;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .logout-button {
            margin-top: 20px;
            padding: 12px 18px;
            border: none;
            border-radius: 12px;
            background: #0f172a;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <main class="card">
        <span class="badge">SPV</span>
        <h1>Login berhasil sebagai SPV</h1>
        <p>Halaman dashboard SPV masih sederhana untuk sementara. Role ini sudah lolos autentikasi dan redirect berjalan benar.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </main>
</body>
</html>