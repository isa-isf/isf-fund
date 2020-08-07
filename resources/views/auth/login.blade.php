@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6">
    <div class="flex justify-center">
        <div class="md:w-2/3">
            <div class="bg-white rounded shadow">
                <div class="px-4 py-2 border border bg-gray-100 rounded-t">登入</div>

                <div class="p-4 border border-t-0 rounded-b">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="flex items-center flex-wrap mb-4">
                            <label for="email" class="px-2 md:w-1/3 text-right text-md">E-Mail</label>

                            <div class="flex flex-col px-2">
                                <input
                                    id="email"
                                    type="email"
                                    class="
                                        px-4 py-1
                                        border rounded shadow-inner

                                        @error('email')
                                            border-red-500 hover:border-red-600
                                        @else
                                            border-gray-600 hover:border-gray-700
                                        @enderror
                                    "
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    autofocus
                                >
                                @error('email')
                                    <span class="text-sm text-red-700" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>

                        <div class="flex items-center flex-wrap mb-4">
                            <label for="password" class="px-2 md:w-1/3 text-right text-md">密碼</label>

                            <div class="flex flex-col px-2">
                                <input id="password" type="password"
                                    class="
                                        px-4 py-1
                                        border rounded shadow-inner

                                        @error('password')
                                            border-red-500 hover:border-red-600
                                        @else
                                            border-gray-600 hover:border-gray-700
                                        @enderror
                                    "
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                >

                                @error('password')
                                    <span class="text-sm text-red-700" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <div class="md:w-2/3 mb-4">
                                <div>
                                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label for="remember">
                                        記住我
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mb-0">
                            <div class="md:w-2/3">
                                <button type="submit" class="px-4 py-1 border border-gray-700 rounded bg-white hover:bg-gray-100">
                                    登入
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
