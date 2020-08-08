@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6">
    <div class="flex justify-center">
        <div class="md:w-2/3">
            <x-card title="登入">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="flex items-center flex-wrap mb-4">
                        <label for="email" class="px-2 md:w-1/3 text-right text-md">E-Mail</label>

                        <div class="flex flex-col px-2">
                            <x-input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                autofocus
                                :hasErrors="$errors->has('email')"
                            />
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
                            <x-input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                :hasErrors="$errors->has('password')"
                            />

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
                            <x-button type="submit">
                                登入
                            </x-button>
                        </div>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</div>
@endsection
