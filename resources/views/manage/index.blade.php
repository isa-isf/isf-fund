@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6">
    <div class="flex mb-6">
        <div class="w-1/4 flex flex-col mx-2">
            <x-card title="目前有效每月定期定額" class="mb-4">
                <div class="text-right text-2xl">
                    NT${{ number_format($analytics['monthly']) }}
                </div>
            </x-card>
            <x-card title="" class="mb-4">
                <x-slot name="title">
                    本月已收月捐
                </x-slot>
                <div class="text-right text-2xl">
                    NT${{ number_format($analytics['monthly_collected_amount']) }}
                </div>
                <div class="text-right text-base text-gray-800">
                    <span class="mr-1">{{ $analytics['monthly_collected_count'] }} / {{ $donations->count() }} 人</span> |
                    {{ round($analytics['monthly_collected_amount'] / $analytics['monthly'] * 100, 2) }}%
                </div>
                <div class="text-right text-base">
                </div>
            </x-card>
            <x-card title="" class="mb-4">
                <x-slot name="title">
                    本月預計 <small>(有效月捐 + 已收一次性)</small>
                </x-slot>
                <div class="text-right text-2xl">
                    NT${{ number_format($analytics['forecast']) }}
                </div>
            </x-card>
        </div>
        <div class="flex-grow flex flex-col mx-2">
            <h3 class="mb-4 text-xl text-gray-900">
                一次性捐款 <small class="text-sm">（近 30 天）</small>
            </h3>

            <x-card class="w-full">
                <table class="w-full">
                    <thead><tr>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold text-right" width="80">捐款#</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold" width="180">姓名</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold">留言</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold text-right" width="100">金額</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold text-right" width="130">日期時間</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold text-right" width="130"></td>
                    </tr></thead>
                    <tbody>
                        @forelse($recentOneTime as $record)
                            <tr>
                                <td class="px-2 py-1 border-b border-gray-200 text-right">{{ $record->hashid }}</td>
                                <td class="px-2 py-1 border-b border-gray-200">{{ $record->name }}</td>
                                <td class="px-2 py-1 border-b border-gray-200">
                                    {{ $record->message }}
                                </td>
                                <td class="px-2 py-1 border-b border-gray-200 text-right">NT${{ number_format($record->amount) }}</td>
                                <td class="px-2 py-1 border-b border-gray-200 text-right">{{ $record->created_at->format('n-d H:i') }}</td>
                                <td class="px-2 py-1 border-b border-gray-200 text-right">
                                    <div class="flex justify-end">
                                        <a
                                            href="{{ url("manage/donations/{$record->hashid}") }}"
                                            class="flex flex-wrap items-center px-2 py-1 border border-blue-600 rounded bg-blue-500 hover:bg-blue-400 text-white"
                                        >
                                            <span class="oi mr-1" data-glyph="eye"></span>
                                            檢視
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <span>無資料</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>
        </div>
    </div>
    <div class="mb-6">
        <div class="flex-grow flex flex-col mx-2">
            <h3 class="mb-4 text-xl text-gray-900">
                月捐
            </h3>

            <x-card class="w-full">
                <table class="w-full">
                    <thead><tr>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold text-right" width="80">捐款#</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold text-center" width="130">本月已收？</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold">姓名</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold text-right" width="100">金額</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold text-right" width="180">建立</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold text-right" width="130">最近付款</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold text-right" width="130"></td>
                    </tr></thead>
                    <tbody>
                        @foreach($donations as $record)
                            <tr>
                                <td class="px-2 py-1 border-b border-gray-200 text-right">{{ $record->hashid }}</td>
                                <td class="px-2 py-1 border-b border-gray-200 text-center">
                                    @if(optional($record->getLastPaymentTime())->isCurrentMonth())
                                        <span class="oi" data-glyph="check"></span>
                                    @elseif(optional($record->getLastPaymentTime())->lt(now()->subMonths(2)))
                                        <span class="oi text-yellow-500" data-glyph="warning" title="超過 2 個月未收到款項"></span>
                                    @endif
                                </td>
                                <td class="px-2 py-1 border-b border-gray-200">{{ $record->name }}</td>
                                <td class="px-2 py-1 border-b border-gray-200 text-right">NT${{ number_format($record->amount) }}</td>
                                <td class="px-2 py-1 border-b border-gray-200 text-right">{{ $record->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-2 py-1 border-b border-gray-200 text-right">
                                    {{ optional($record->getLastPaymentTime())->format('n-d H:i') ?? '' }}
                                </td>
                                <td class="px-2 py-1 border-b border-gray-200 text-right flex justify-end">
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
                </table>
            </x-card>
        </div>
    </div>
</div>
@endsection
