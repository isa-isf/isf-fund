<?php /** @var \Illuminate\Support\Collection|\App\Models\Donation[] $donations */ ?>
@extends('layouts.manage')

@section('title', '收件地址列表')

@section('content')
    <div class="container mx-auto px-6">
        <div class="flex flex-col mb-6 mx-2">
            <h2 class="mb-0 text-gray-900 text-3xl font-semibold">
                收件地址列表
            </h2>
            <div
                class="flex items-end text-gray-800"
                x-data="{
                    from: {
                        month: {{ $fromDate->format('n') }},
                        year: {{ $fromDate->format('Y') }},
                    },
                    to: {
                        month: {{ $toDate->subMonth()->format('n') }},
                        year: {{ $toDate->format('Y') }},
                    },
                }"
            >
                <div class="mr-2 text-2xl">
                    <select x-model="from.year">
                        @foreach(range(2021, date('Y')) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    年
                    <select x-model="from.month">
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}">{{ $month }}</option>
                        @endforeach
                    </select>
                    月
                </div>
                <div class="mr-2">至</div>
                <div class="mr-2 text-2xl">
                    <select x-model="to.year">
                        @foreach(range(2021, date('Y')) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    年
                    <select x-model="to.month">
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}">{{ $month }}</option>
                        @endforeach
                    </select>
                    月
                </div>
                <x-button
                    class="text-sm"
                    @click="
                        const url = new URL(window.location.href)
                        url.searchParams.set('from[year]', from.year)
                        url.searchParams.set('from[month]', from.month)
                        url.searchParams.set('to[year]', to.year)
                        url.searchParams.set('to[month]', to.month)
                        window.location.assign(url)
                    "
                >套用</x-button>
            </div>
        </div>
        <div class="flex mx-2 overflow-x-auto">
            <x-card class="w-full" style="min-width: 760px" title="期間內有效付款">
                <table class="table w-full mb-6">
                    <thead><tr>
                        <th>#</th>
                        <th>姓名</th>
                        <th>聯絡</th>
                        <th>地址</th>
                        <th>備註</th>
                    </tr></thead>
                    <tbody>
                        @foreach ($donations as $donation)
                            <tr>
                                <td>{{ $donation->hashid }}</td>
                                <td>{{ $donation->name }}</td>
                                <td>
                                    {{ $donation->email }}<br />
                                    {{ $donation->phone }}
                                </td>
                                <td>{{ $donation->address }}</td>
                                <td>{{ $donation->message }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-card>
        </div>
    </div>
@endsection
