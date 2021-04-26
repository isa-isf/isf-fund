@extends('layouts.manage')

@section('title', '收款報表')

@section('content')
    <div class="container mx-auto px-6">
        <div class="flex flex-col mb-6 mx-2">
            <h2 class="mb-0 text-gray-900 text-3xl font-semibold">
                月捐收款情形報表
            </h2>
            <div
                class="flex items-end text-gray-800"
                x-data="{
                    years: Array.from({ length: {{ date('Y') }} - 2018 + 1 }, (_, i) => 2018 + i),
                    months: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                    current: {
                    month: 1,
                    year: 2018,
                    },
                }"
                x-init="() => Object.assign(current, { month: {{ $baseDate->format('n') }}, year: {{ $baseDate->format('Y') }} })"
            >
                <div class="mr-2 text-2xl">
                    <select x-model="current.year">
                        <template x-for="year of years">
                            <option :value="year" x-text="year"></option>
                        </template>
                    </select>
                    年
                    <select x-model="current.month">
                        <template x-for="month of months">
                            <option :value="month" x-text="month"></option>
                        </template>
                    </select>
                    月
                </div>
                <x-button
                    class="text-sm"
                    @click="
                        const url = new URL(window.location.href)
                        url.searchParams.set('year', current.year)
                        url.searchParams.set('month', current.month)
                        window.location.assign(url)
                    "
                >套用</x-button>
            </div>
        </div>
        <div class="flex flex-col md:flex-row items-stretch mb-6">
            @if ($donations->isNotEmpty())
                <x-card title="當月份有效月捐" class="md:w-1/5 mx-2 mb-4 md:mb-0">
                    <div class="text-2xl text-right">
                        NT${{ number_format($donations->sum('amount')) }}
                    </div>
                </x-card>
                <x-card title="已收款" class="md:w-1/5 mx-2 mb-4 md:mb-0">
                    <div class="text-2xl text-right">
                        NT${{ number_format($donations->filter(fn ($d) => optional($d->getLastPaymentTime())->isSameMonth($baseDate))->sum('amount')) }}
                    </div>
                </x-card>
                <x-card title="達成率" class="md:w-1/5 mx-2">
                    <div class="text-2xl text-right">
                        {{ round($donations->filter(fn ($d) => optional($d->getLastPaymentTime())->isSameMonth($baseDate))->sum('amount') / $donations->sum('amount') * 100, 2) }}%
                    </div>
                </x-card>
            @endif
        </div>
        <div class="flex mx-2 overflow-x-auto">
            <x-card class="w-full" style="min-width: 760px">
                <table class="table w-full mb-6">
                    <thead><tr>
                        <th class="text-right" width="80">捐款#</th>
                        <th class="text-center" width="130">已收？</th>
                        <th>姓名</th>
                        <th class="text-right" width="100">金額</th>
                        <th class="text-right" width="180">建立</th>
                        <th class="text-right" width="180">收款時間</th>
                        <th class="text-right" width="130"></th>
                    </tr></thead>
                    <tbody>
                        @foreach($donations as $record)
                            <tr>
                                <td class="text-right">{{ $record->hashid }}</td>
                                <td class="text-center">
                                    @if(optional($record->getLastPaymentTime())->isSameMonth($baseDate))
                                        <span class="oi" data-glyph="check"></span>
                                    @elseif(optional($record->getLastPaymentTime())->lt($baseDate->subMonths(3)))
                                        <span class="oi text-yellow-500" data-glyph="warning" title="超過 2 個月未收到款項"></span>
                                    @endif
                                </td>
                                <td>{{ $record->name }}</td>
                                <td class="text-right">NT${{ number_format($record->amount) }}</td>
                                <td class="text-right">{{ $record->created_at->format('Y-m-d') }}</td>
                                @if(optional($record->getLastPaymentTime())->isSameMonth($baseDate))
                                    <td class="text-right">
                                        {{ $record->getLastPaymentTime()->format('d日 H:i') }}
                                    </td>
                                @else
                                    <td class="text-right">-</td>
                                @endif
                                <td class="text-right flex justify-end">
                                    <a
                                        href="{{ url("manage/donations/{$record->hashid}") }}"
                                        class="flex flex-wrap items-center px-2 py-1 border border-blue-600 rounded bg-blue-500 hover:bg-blue-400 text-white"
                                    >
                                        <span class="oi mr-1" data-glyph="eye"></span>
                                        檢視
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            </x-card>
        </div>
    </div>
@endsection
