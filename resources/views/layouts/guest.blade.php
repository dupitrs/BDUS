<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mums pieder pasaule') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;700&display=swap" rel="stylesheet">

    <!-- ABYS font (ja pieejams lokāli) -->
    <style>
        @font-face {
            font-family: 'ABYS';
            src: url('/fonts/ABYS.ttf') format('truetype');
        }

        body {
            font-family: 'Josefin Sans', sans-serif;
            background-color: #B3EFEB;
            color: #2c3e50;
        }

        a {
            color: #C71616;
            transition: color 0.2s ease-in-out;
        }

        a:hover {
            color: #F66B1A;
        }

        .btn-primary {
            background-color: #08A398;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #007d6a;
        }
    </style>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

        <div>
            <a href="/">
                <x-application-logo class="w-10 h-10 fill-current text-[#08A398]" style="height: 20px;" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg border border-[#ADE9A1]">
            {{ $slot }}
        </div>

    </div>
</body>
</html>
