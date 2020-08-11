<div {{ $attributes->merge(['class' => "flex flex-col justify-start bg-{$color} rounded shadow"]) }}>
    @if(!empty($title))
        <div class="px-4 py-2 border border bg-{{ $titleColor }}-100 rounded-t">{{ $title ?? '' }}</div>
    @endif

    <div
        class="
            flex-grow p-4 border
            @if(empty($title))
                rounded
            @else
                border-t-0
                rounded-b
            @endif
        "
    >
        {{ $slot }}
    </div>
</div>
