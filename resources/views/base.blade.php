<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <title>@yield('title')</title>
</head>
<body>
    <header>
        <!-- @include('composant.nav') -->
         @yield('header')
    </header>

    <main class="w-full bg-gray-100">
        @yield('main')
    </main>


    <footer>
        @yield('footer')
    </footer>
</body>
</html>