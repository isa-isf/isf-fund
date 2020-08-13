@extends('layouts.manage')

@section('content')
    <div class="container mx-auto px-6">
        <div class="flex justify-center">
            <x-card title="二步驟驗證" class="md:w-1/3">
                <form method="POST" action="{{ url()->route('challenge') }}">
                    @csrf
                    <div class="mb-4">
                        一封含有驗證碼的簡訊已經傳送至你的手機，請輸入驗證碼
                    </div>

                    <div class="flex flex-col mb-4">
                        <label for="code" class="mb-1 text-gray-900">簡訊驗證碼:</label>
                        <x-input id="code" name="code" :hasErrors="$errors->has('code')" />
                        @error('code')
                            <span class="text-sm text-red-700" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <x-button type="submit">確定</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
