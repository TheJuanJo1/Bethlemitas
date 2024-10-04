<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
</head>
<body>
    <div class="flex flex-col items-center justify-center w-full h-screen px-6 py-12 bg-center bg-cover lg:px-8" style="background-image: url('{{ asset('img/colegio1.jpg') }}');">
        @vite('resources/css/app.css')
        @yield('contentLogin')
    </div>
</body>
</html>