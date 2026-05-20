<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/sidebar.css'])
    <title>@yield('title', 'MUMS')</title>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <img src="..." alt="Logo MUMS" class="logo-img">
                <h1 class="logo-text">MUMS</h1>
            </div>

            <nav class="menu">
                <ul>
                    <li><a href="{{ route('tasks.index') }}">Task Management</a></li>
                </ul>

                <ul class="menu-bottom">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="menu-logout-button">Logout</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            @yield('content')
        </main>
    </div>
</body>
</html>
