<input
    type="{{ $type }}"
    class="
        {{ $attributes['class'] }}
        px-4 py-1
        border rounded shadow-inner

        @if($hasErrors)
            mb-1 {{-- for display error message below --}}
            border-red-500 hover:border-red-600
        @else
            border-gray-600 hover:border-gray-700
        @endif
    "
    {{ $attributes->except('class') }}
>
