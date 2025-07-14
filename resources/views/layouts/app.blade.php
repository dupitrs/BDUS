<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mums pieder pasaule') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;700&display=swap" rel="stylesheet">

    <!-- ABYS fonts, ja pieejams lokāli -->
    <style>
        @font-face {
            font-family: 'ABYS';
            src: url('/fonts/ABYS.ttf') format('truetype');
        }

        body {
            font-family: 'Josefin Sans', sans-serif;
            background-color: #B3EFEB; /* viena no pamata krāsām */
            color: #2c3e50;
        }

        header {
            background-color: #08A398; /* virsrakstu fona krāsa */
            color: white;
        }

        a {
            color: #C71616; /* akcenta krāsa */
        }

        a:hover {
            color: #F66B1A;
        }

        .btn-primary {
            background-color: #08A398;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
        }

        .btn-primary:hover {
            background-color: #007d6a;
        }
    </style>

    <!-- Laravel Vite assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen">

        {{-- Navigācija --}}
        @include('layouts.navigation')

        {{-- Lapas virsraksts --}}
        @isset($header)
            <header class="shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Saturs --}}
        <main class="py-6 px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>

    </div>
</body>
</html>
