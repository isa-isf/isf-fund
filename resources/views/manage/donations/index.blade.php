@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row md:justify-between mx-2 mb-4">
            <h2 class="text-2xl mb-2 md:mb-0">捐款列表</h3>
            <div
                class="flex flex-col"
                x-data="{ type: '{{ Request::input('type') }}', status: '{{ Request::input('status') }}' }"
            >
                <div class="flex items-center mb-2">
                    <div class="mr-2">類型：</div>
                    <select name="type" x-model="type">
                        <option value="">全部</option>
                        <option value="one-time">一次性</option>
                        <option value="monthly">月捐</option>
                    </select>
                </div>
                <div class="flex items-center mb-2">
                    <div class="mr-2">狀態：</div>
                    <select name="type" x-model="status">
                        <option value="">全部</option>
                        <option value="{{ \App\Enums\DonationStatus::ACTIVE() }}">有效</option>
                        <option value="{{ \App\Enums\DonationStatus::INACTIVE() }}">無效</option>
                    </select>
                </div>
                <div>
                    <x-button @click="
                        const url = new URL(window.location.href);
                        url.searchParams.set('status', status);
                        url.searchParams.set('type', type);
                        window.location.assign(url.toString());
                    ">套用</x-button>
                </div>
            </div>
        </div>
        <div class="flex mx-2">
            <x-card class="w-full" style="min-width:900px">
                <table class="w-full mb-6">
                    <thead><tr>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold text-right" width="80">捐款#</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold text-center" width="80">類型</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold text-center" width="130">本月已收？</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold">姓名</td>
                        <td class="px-2 py-1 border-b-2 border-gray-300 font-semibold">聯絡方式</td>
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
                                    @if(\App\Enums\DonationType::ONE_TIME()->equals($record->type))
                                        一次性
                                    @elseif(\App\Enums\DonationType::MONTHLY()->equals($record->type))
                                        月捐
                                    @endif
                                </td>
                                <td class="px-2 py-1 border-b border-gray-200 text-center">
                                    @if(optional($record->getLastPaymentTime())->isCurrentMonth())
                                        <span class="oi" data-glyph="check"></span>
                                    @elseif(optional($record->getLastPaymentTime())->lt(now()->subMonths(2)))
                                        <span class="oi text-yellow-500" data-glyph="warning" title="超過 2 個月未收到款項"></span>
                                    @endif
                                </td>
                                <td class="px-2 py-1 border-b border-gray-200">{{ $record->name }}</td>
                                <td class="px-2 py-1 border-b border-gray-200">
                                    {{ $record->phone }}<br>
                                    {{ $record->email }}
                                </td>
                                <td class="px-2 py-1 border-b border-gray-200 text-right">NT${{ number_format($record->amount) }}</td>
                                <td class="px-2 py-1 border-b border-gray-200 text-right">{{ $record->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-2 py-1 border-b border-gray-200 text-right">
                                    {{ optional($record->getLastPaymentTime())->format('n-d H:i') ?? '' }}
                                </td>
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
                        @endforeach
                    </tbody>
                </table>
                <div class="text-right">
                    {{ $donations->withQueryString()->links() }}
                </div>
            </x-card>
        </div>
    </div>
@endsection
