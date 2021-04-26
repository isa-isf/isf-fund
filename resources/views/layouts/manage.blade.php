<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    @if(View::hasSection('title'))
        <title>@yield('title') - {{ config('app.name') }}</title>
    @else
        <title>{{ config('app.name') }}</title>
    @endif

    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">

    <link rel="stylesheet" href="{{ mix('assets/vendor/open-iconic/css/open-iconic.min.css') }}">
</head>
<body>
    <header class="mb-4 bg-gray-200 border-t-4 border-red-500">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row flex-wrap items-stretch justify-between mx-2">
                <a class="flex-shrink px-2 py-4 mr-4 text-lg font-semibold text-gray-900 hover:text-gray-700 hover:bg-gray-100" href="{{ url('manage') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <div class="flex-grow flex justify-between">
                    @auth
                        <nav class="flex items-stretch -mx-2">
                            <a href="{{ url('manage/income-report') }}" class="flex items-center mx-2 p-2 text-gray-900 hover:bg-gray-100">
                                <span class="oi mr-1" data-glyph="graph"></span>
                                收款報表
                            </a>
                            <a href="{{ url('manage/address') }}" class="flex items-center mx-2 p-2 text-gray-900 hover:bg-gray-100">
                                <span class="oi mr-1" data-glyph="envelope-closed"></span>
                                收件地址列表
                            </a>
                        </nav>

                        <nav class="flex items-stretch">
                            <a href="{{ url('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit()" class="flex items-center p-2 text-gray-900 hover:bg-gray-100">登出</a>

                            <form id="logout-form" class="hidden" action="{{ url('logout') }}" method="post">
                                @csrf
                            </div>
                        </nav>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="py-4">
        @yield('content')
    </main>

    <script src="{{ mix('assets/js/app.js') }}"></script>
</body>
</html>
