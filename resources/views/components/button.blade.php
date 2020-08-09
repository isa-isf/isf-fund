<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'px-4 py-1 border border-gray-700 rounded bg-white hover:bg-gray-100',
    ]) }}
>
    {{ $slot }}
</button>
