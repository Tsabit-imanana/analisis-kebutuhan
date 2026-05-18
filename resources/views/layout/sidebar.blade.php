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
                <img src="..." alt="Logo MUMS" class="logo-img"> {{-- Logo MUMS --}}
                <h1 class="logo-text">MUMS</h1>
            </div>

            <nav class="menu">
                <ul>
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Weekly Log</a></li>
                    <li><a href="#">Task Management</a></li>
                    <li><a href="#">Docs Management</a></li>
                    <li><a href="#">Finance Budgeting</a></li>
                </ul>

                <ul class="menu-bottom">
                    <li><a href="#">My Profile</a></li>
                    <li><a href="#">Role Management</a></li>
                    <li><a href="#">Settings & Security</a></li>
                    <li><a href="#">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            @yield('content')
        </main>
    </div>
</body>
</html>
