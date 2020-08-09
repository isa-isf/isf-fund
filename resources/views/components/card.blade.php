<div {{ $attributes->merge(['class' => "flex flex-col justify-start bg-{$color} rounded shadow"]) }}>
    <div class="px-4 py-2 border border bg-{{ $titleColor }}-100 rounded-t">{{ $title ?? '' }}</div>

    <div class="flex-grow p-4 border border-t-0 rounded-b">
        {{ $slot }}
    </div>
</div>
