<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{asset('build/assets/app-Bi08MZ8-.js')}}">
    <link rel="stylesheet" href="{{asset('build/assets/app-wVnth9Db.css')}}">
    <link rel="stylesheet" href="{{asset('build/manifest.json')}}">
    <link rel="icon" type="image/x-icon" href="{{asset('img/icon.jpeg')}}">    
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>

</head>
<body class="max-w-screen-full">
    <header>
         @yield('header')
    </header>

    <main class="w-full bg-gray-100 ">
        @yield('main')
    </main>


    <footer>
        @yield('footer')
    </footer>
</body>
</html>