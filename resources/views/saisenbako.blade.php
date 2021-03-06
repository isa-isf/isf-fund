<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>贊助支持我們 | 國際社會主義道路 International Socialist Alternative</title>

    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">
</head>
<body>
<div
    id="app"
    class=" min-h-screen flex flex-col md:flex-row"
    x-data="{{ json_encode([
        'profile' => [
            'name' => old('profile.name', ''),
            'phone' => old('profile.phone', ''),
            'email' => old('profile.email', ''),
            'address' => old('profile.address', ''),
        ],
        'payment' => [
            'amount' => old('payment.amount', 1000),
            'custom_amount' => old('payment.custom_amount', 1000),
            'count' => old('payment.count', 99),
            'type' => old('payment.monthly', 'monthly'),
            'message' => old('payment.amount', ''),
        ],
        'form_errors' => [],
        'submitting' => false,
    ]) }}"
>
    <section class="lg:w-2/5 py-10 px-8 bg-red-600 text-white lg:text-right">
        <h2 class="text-4xl">國際社會主義道路</h2>
        <h3 class="text-md">International Socialist Alternative</h3>
    </section>
    <main class="lg:w-3/5 py-10 px-8 flex flex-col">
        <h1 class="mb-2 text-4xl font-semibold">贊助支持我們</h1>
        <div class="mb-4">
            <noscript>
                <p class="mb-2 py-4 px-6 bg-yellow-400 rounded">
                    您的瀏覽器似乎未啟用 JavaScript。<br>
                    請注意：雖然本表單頁面可在無 JavaScript 的環境下正常運作，但金流平台的頁面（我們使用<a href="https://www.ecpay.com.tw/" target="_blank">綠界科技</a>）必須啟用 JavaScript 才可使用。
                </p>
            </noscript>
            <p>《國際社會主義道路》志於建立一個工人階級鬥爭組織。在各樣的群眾運動中，提出我們致勝的綱領與訴求，並參與實際鬥爭以爭取群眾支持。我們需要您的贊助支持 <span class="text-gray-500 text-xs">[* 註]</span>！用於抗爭運動與日常會議之中。對於我們的長期發展而言，如有您穩定的贊助與支持，亦能讓我們能有穩定的運作。</p>
            <p>為了保持政治獨立性，我們不接受財團及政府的資助，所有贊助均來自工人和青年的贊助。有您的支持我們才能鬥爭到底！</p>
        </div>

        <div id="errors" class="px-4 py-2 mb-4 rounded bg-red-500 text-white" x-show="form_errors.length">
            <strong>您填寫的資料存在錯誤：</strong>
            <template x-for="error in form_errors">
                <ul>
                    <template x-for="message in error">
                        <li x-text="message"></li>
                    </template>
                </ul>
            </template>
        </div>

        <form
            action="{{ url('payments') }}"
            method="post"
            @submit.prevent="
                submitting = true;
                axios
                  .post('{{ url('_/donations') }}', { profile: profile, payment: payment })
                  .then(function (response) { window.location.assign(response.data.redirect); })
                  .catch(function (error) {
                    notyf.error('發生錯誤');
                    if ((error.response || {}).data) {
                      form_errors = Object.values(error.response.data.errors);
                      window.location.assign('#errors')
                    }
                    submitting = false;
                  });
            "
        >
            @csrf

            <div class="flex flex-col mb-4">
                <label for="profile-name" class="mb-2">姓名 <small class="text-red-400">*</small></label>

                <input
                    id="profile-name"
                    class="px-3 py-2 border border-gray-400 focus:border-blue-700 rounded bg-white"
                    type="text"
                    name="profile[name]"
                    placeholder="請填寫您的全名或稱呼"
                    x-model="profile.name"
                    required
                >
            </div>

            <div class="flex flex-col mb-4">
                <label for="profile-name" class="mb-2">電話 <small class="text-red-400">*</small></label>

                <input
                    id="profile-phone"
                    class="px-3 py-2 border border-gray-400 focus:border-blue-700 rounded bg-white"
                    type="tel"
                    name="profile[phone]"
                    placeholder="請填寫電話號碼"
                    x-model="profile.phone"
                    required
                >
            </div>

            <div class="flex flex-col mb-4">
                <label for="profile-name" class="mb-2">電子郵件 <small class="text-red-400">*</small></label>

                <input
                    id="profile-email"
                    class="px-3 py-2 border border-gray-400 focus:border-blue-700 rounded bg-white"
                    type="email"
                    name="profile[email]"
                    placeholder="請填寫電子郵件"
                    x-model="profile.email"
                    required
                >
            </div>

            <div class="flex flex-col mb-4">
                <label for="profile-name" class="mb-2">收件地址 <small class="text-red-400">*</small></label>

                <input
                    id="profile-address"
                    class="px-3 py-2 border border-gray-400 focus:border-blue-700 rounded bg-white"
                    type="text"
                    name="profile[address]"
                    placeholder="我們將寄送雜誌給您，請填寫收件地址"
                    x-model="profile.address"
                    required
                >
            </div>

            <noscript>
                <div class="flex flex-col mb-4">
                    <label for="payment-amount" class="mb-2">贊助金額 <small class="text-red-400">*</small></label>

                    <input
                        id="payment-amount"
                        class="w-64 px-3 py-2 border border-gray-400 focus:border-blue-700 rounded bg-white"
                        type="number"
                        name="payment[amount]"
                        placeholder="請填寫贊助金額"
                        x-model="payment.amount"
                        value="500"
                        required
                    >
                </div>
            </noscript>

            <template x-if="true">
                <div class="flex flex-col mb-4">
                    <label for="payment-amount" class="mb-2">贊助金額 <small class="text-red-400">*</small></label>

                    <div class="inline-block relative w-64">
                        <select
                            id="payment-amount"
                            class="block appearance-none w-full px-3 py-2 pr-8 border border-gray-400 rounded focus:border-blue-700 bg-white"
                            name="payment[amount]"
                            x-model="payment.amount"
                        >
                            <option value="500">NT$500</option>
                            <option value="1000" selected>NT$1,000</option>
                            <option value="2000">NT$2,000</option>
                            <option value="3000">NT$3,000</option>
                            <option value="4000">NT$4,000</option>
                            <option value="5000">NT$5,000</option>
                            <option value="0">其它金額</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <template x-if="payment.amount === '0'">
                    <div class="flex flex-col mb-4">
                        <label for="payment-amount" class="mb-2">其他金額 <small class="text-red-400">*</small></label>

                        <input
                            id="payment-amount"
                            class="w-64 px-3 py-2 border border-gray-400 focus:border-blue-700 rounded bg-white"
                            type="number"
                            name="payment[amount]"
                            min="50"
                            max="10000"
                            placeholder="請填寫贊助金額"
                            x-model="payment.custom_amount"
                            value="500"
                            required
                        >
                    </div>
                </template>
            </template>

            <div class="flex flex-col mb-4">
                <label for="payment-type" class="mb-2">贊助方式 <small class="text-red-400">*</small></label>

                <div class="inline-block relative w-64">
                    <select
                        id="payment-type"
                        class="block appearance-none w-full px-3 py-2 pr-8 border border-gray-400 rounded focus:border-blue-700 bg-white"
                        name="payment[type]"
                        x-model="payment.type"
                    >
                        <option value="{{ \App\Enums\DonationType::MONTHLY()->getValue() }}">每月定期</option>
                        <option value="{{ \App\Enums\DonationType::ONE_TIME()->getValue() }}">一次性贊助</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-col mb-4" x-show="payment.type === '{{ \App\Enums\DonationType::MONTHLY()->getValue() }}'">
                <label for="payment-count" class="mb-2">贊助期數 (月)<small class="text-red-400">*</small></label>

                <div class="inline-block relative w-64">
                    <select
                        id="payment-count"
                        class="block appearance-none w-full px-3 py-2 pr-8 border border-gray-400 rounded focus:border-blue-700 bg-white"
                        name="payment[count]"
                        x-model="payment.count"
                    >
                        <option value="12">12 期</option>
                        <option value="24">24 期</option>
                        <option value="36">36 期</option>
                        <option value="48">48 期</option>
                        <option value="99">99 期</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-col mb-4">
                <label for="payment-message" class="mb-2">留言</label>

                <textarea
                    id="payment-message"
                    class="px-3 py-2 border border-gray-400 focus:border-blue-700 rounded bg-white"
                    name="payment[message]"
                    placeholder="您可在此留下留言"
                    x-model="payment.message"
                ></textarea>
            </div>

            <div class="mb-4">
                <noscript>
                    <button
                        type="submit"
                        class="px-3 py-2 rounded bg-red-600 hover:bg-red-500 text-white"
                    >前往付款</button>
                </noscript>
                <template x-if="true">
                    <template x-if="submitting">
                        <button
                            type="submit"
                            class="px-3 py-2 rounded bg-red-500 text-white"
                            disabled
                        >前往付款</button>
                    </template>
                    <template x-if="!submitting">
                        <button
                            type="submit"
                            class="px-3 py-2 rounded bg-red-600 hover:bg-red-500 text-white"
                        >前往付款</button>
                    </template>
                </template>
            </div>

            <div class="field">
                <p class="text-gray-500">* 恕無法提供收據</p>
            </div>
        </form>
    </main>
</div> {{-- /#app --}}

<script src="{{ mix('assets/js/app.js') }}"></script>

</body>
</html>
