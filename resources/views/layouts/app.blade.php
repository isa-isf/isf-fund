<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">

    @livewireStyles
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic.min.css" integrity="sha512-LeCmts7kEi09nKc+DwGJqDV+dNQi/W8/qb0oUSsBLzTYiBwxj0KBlAow2//jV7jwEHwSCPShRN2+IWwWcn1x7Q==" crossorigin="anonymous" />
</head>
</head>
<body>
    <nav class="mb-4 bg-gray-200">
        <div class="container mx-auto px-6 flex">
            <a class="px-2 py-4 text-lg font-semibold text-gray-900 hover:text-gray-700" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>
