@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6">
        <div class="flex justify-center">
            <x-card title="設定密碼" class="md:w-1/3">
                <form method="POST" action="{{ URL::temporarySignedRoute('password-setup-store', $expires, ['user' => $user->hashid]) }}">
                    @csrf
                    <div class="mb-4">
                        為下列帳號設定密碼：<br />
                        <code class="px-1 bg-gray-100">{{ $user->email }}</code><br />

                        密碼應至少有 8 個字元。建議需同時包含字母與數字。
                    </div>

                    <div class="flex flex-col mb-4">
                        <label for="password" class="mb-1 text-gray-900">密碼:</label>
                        <x-input type="password" id="password" name="password" :hasErrors="$errors->has('password')" />
                        @error('password')
                            <span class="text-sm text-red-700" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col mb-4">
                        <label for="password-confirmation" class="mb-1 text-gray-900">確認密碼:</label>
                        <x-input type="password" id="password-confirmation" name="password_confirmation" />
                    </div>

                    <div class="flex justify-end">
                        <x-button type="submit">設定密碼</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
