<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    @if(View::hasSection('title'))
        <title>@yield('title') - 國際社會主義前進籌款系統</title>
    @else
        <title>國際社會主義前進籌款系統</title>
    @endif

    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">

    <link rel="stylesheet" href="{{ mix('assets/vendor/open-iconic/css/open-iconic.min.css') }}">
</head>
<body>
    <header class="mb-4 bg-gray-200 border-t-4 border-red-500">
        <div class="container mx-auto px-6">
            <div class="flex items-stretch justify-between mx-2">
                <div class="flex">
                    <a class="px-2 py-4 mr-4 text-lg font-semibold text-gray-900 hover:text-gray-700 hover:bg-gray-100" href="{{ url('manage') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>

                    @auth
                        <a href="{{ url('manage/income-report') }}" class="flex items-center px-2 text-gray-900 hover:bg-gray-100">
                            <span class="oi mr-1" data-glyph="graph"></span>
                            收款報表
                        </a>
                    @endauth
                </div>

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

    <script src="{{ mix('assets/js/app.js') }}"></script>
</body>
</html>
