@extends('layouts.app')

@section('title', e($donation->name) . ": 捐款#{$donation->hashid}")

@section('content')
    <div class="container mx-auto px-6 flex flex-col">
        <div class="flex flex-col mx-2 mb-6">
            <h2 class="mb-4 text-2xl font-semibold">捐款#{{ $donation->hashid }}</h2>
        </div>

        <div class="flex flex-col-reverse md:flex-row">
            <x-card title="基本資料" class="w-1/3 mx-2">
                <div class="mb-2">
                    <dd class="text-black">姓名</dd>
                    <dt class="ml-4 text-gray-900">
                        {{ $donation->name }}
                    </dt>
                </div>
                <div class="mb-2">
                    <dd class="text-black">聯絡電話</dd>
                    <dt class="ml-4 text-gray-900">
                        {{ $donation->phone }}
                    </dt>
                </div>
                <div class="mb-2">
                    <dd class="text-black">電子郵件</dd>
                    <dt class="ml-4 text-gray-900">
                        {{ $donation->email }}
                    </dt>
                </div>
                <div class="mb-2">
                    <dd class="text-black">收件地址</dd>
                    <dt class="ml-4 text-gray-900">
                        {{ $donation->address }}
                    </dt>
                </div>
                <div class="mb-2">
                    <dd class="text-black">留言</dd>
                    <dt class="ml-4 text-gray-900">
                        {!! nl2br(e($donation->message)) !!}
                    </dt>
                </div>
            </x-card>

            <div class="flex-grow mx-2">
                <x-card title="捐款金額" class="mb-4">
                    <div class="mb-2">
                        <dd class="text-black">狀態</dd>
                        <dt class="ml-4 text-gray-900">
                            @if(\App\Enums\DonationStatus::ACTIVE()->equals($donation->status))
                                有效
                            @elseif(\App\Enums\DonationStatus::INACTIVE()->equals($donation->status))
                                失效
                            @elseif(\App\Enums\DonationStatus::CREATED()->equals($donation->status))
                                未付款
                            @endif
                        </dt>
                    </div>
                    <div class="mb-2">
                        <dd class="text-black">捐款類型</dd>
                        <dt class="ml-4 text-gray-900">
                            @if(\App\Enums\DonationType::ONE_TIME()->equals($donation->type))
                                一次性
                            @elseif(\App\Enums\DonationType::MONTHLY()->equals($donation->type))
                                每月定期定額
                            @endif
                        </dt>
                    </div>
                    <div class="mb-2">
                        <dd class="text-black">捐款金額</dd>
                        <dt class="ml-4 text-gray-900">
                            NT${{ number_format($donation->amount) }}
                        </dt>
                    </div>
                </x-card>
                <x-card title="收款歷史">
                    <div>
                        累計已收款：NT${{ number_format($donation->payments->filter(fn ($p) => \App\Enums\PaymentStatus::PAID()->equals($p->status))->sum('amount')) }}
                    </div>
                    <ol class="list-decimal pl-6">
                        @foreach($donation->payments as $payment)
                            <li>
                                @if(\App\Enums\PaymentStatus::PAID()->equals($payment->status))
                                    [已付款]
                                @elseif(\App\Enums\PaymentStatus::CREATED()->equals($payment->status))
                                    <span class="text-red-600">[未付款]</span>
                                @elseif(\App\Enums\PaymentStatus::FAILED()->equals($payment->status))
                                    <span class="text-red-600">[付款失敗]</span>
                                @endif
                                {{ $payment->created_at }}
                            </li>
                        @endforeach
                    </ol>
                </x-card>
            </div>
        </div>
    </div>
@endsection
