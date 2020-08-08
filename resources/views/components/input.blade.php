<input
    type="{{ $type }}"
    class="
        px-4 py-1
        border rounded shadow-inner

        @if($hasErrors)
            border-red-500 hover:border-red-600
        @else
            border-gray-600 hover:border-gray-700
        @endif
    "
    {{ $attributes }}
>
