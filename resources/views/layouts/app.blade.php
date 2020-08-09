<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(View::hasSection('title'))
        <title>@yield('title') - 國際社會主義前進籌款系統</title>
    @else
        <title>國際社會主義前進籌款系統</title>
    @endif

    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">

    @livewireStyles
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic.min.css" integrity="sha512-LeCmts7kEi09nKc+DwGJqDV+dNQi/W8/qb0oUSsBLzTYiBwxj0KBlAow2//jV7jwEHwSCPShRN2+IWwWcn1x7Q==" crossorigin="anonymous" />
</head>
<body>
    <header class="mb-4 bg-gray-200">
        <div class="container mx-auto px-6">
            <div class="flex items-stretch justify-between mx-2">
                <a class="px-2 py-4 text-lg font-semibold text-gray-900 hover:text-gray-700" href="{{ url('manage') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <nav class="flex items-stretch">
                    @auth
                        <a href="{{ url('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit()" class="flex items-center px-2 text-gray-900 hover:bg-gray-100">登出</a>

                        <form id="logout-form" class="hidden" action="{{ url('logout') }}" method="post">
                            @csrf
                        </div>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="py-4">
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>
