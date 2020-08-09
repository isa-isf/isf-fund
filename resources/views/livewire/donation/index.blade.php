<div class="flex">
    @foreach ($donations as $donation)
        {{ $donation->name }}
    @endforeach

    {{ $donations->links() }}
</div>
