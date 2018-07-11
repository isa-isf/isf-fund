<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>捐款支持我們 | 國際社會主義前進 International Socialist Forward</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">
</head>
<body>
<div id="app" class="columns is-fullheight">
    <section class="column is-two-fifths is-primary">
        <div class="section">
            <div class="has-text-right">
                <h2 class="title">國際社會主義前進</h2>
                <h3 class="subtitle">International Socialist Forward</h3>
            </div>
        </div>
    </section>
    <main class="section column">
        <div class="column">
            <h1 class="title">捐款支持我們</h1>
            <div class="content">
                <noscript>
                    <p class="notification is-warning">
                        您的瀏覽器似乎未啟用或不支援 JavaScript。<br>
                        請注意：雖然本表單頁面可在無 JavaScript 的環境下正常運作，但金流平台的頁面（我們使用<a href="https://www.ecpay.com.tw/" target="_blank">綠界科技</a>）必須啟用 JavaScript 才可使用。
                    </p>
                </noscript>
                <p>《國際社會主義前進》志於建立一個工人階級鬥爭組織。在各樣的群眾運動中，提出我們致勝的綱領與訴求，並參與實際鬥爭以爭取群眾支持。我們需要您的捐款支持！用於抗爭運動與日常會議之中。對於我們的長期發展而言，如有您穩定的捐款與支持，亦能讓我們能有穩定的運作。</p>
                <p>為了保持政治獨立性，我們不接受財團及政府的資助，所有捐款均來自工人和青年的捐款。有您的支持我們才能鬥爭到底！</p>
            </div>

            <div class="content" v-if="form_errors.length">
                <div class="notification is-danger">
                    <strong>您填寫的資料存在錯誤：</strong>
                    <ul>
                        <template v-for="error in form_errors">
                            <li v-for="message in error">@{{ message }}</li>
                        </template>
                    </ul>
                </div>
            </div>

            <form action="{{ url('payments') }}" method="post" @submit.prevent="submit">
                {!! csrf_field() !!}

                <div class="field">
                    <label for="profile-name" class="label required">姓名</label>

                    <div class="control">
                        <input id="profile-name" class="input" type="text" name="profile[name]" placeholder="請填寫您的全名" v-model="profile.name" required>
                    </div>
                </div> {{-- /.field --}}

                <div class="field">
                    <label for="profile-phone" class="label required">電話</label>

                    <div class="control">
                        <input id="profile-phone" class="input" type="tel" name="profile[phone]" placeholder="請填寫電話號碼" v-model="profile.phone" required>
                    </div>
                </div> {{-- /.field --}}

                <div class="field">
                    <label for="profile-email" class="label required">電子郵件</label>

                    <div class="control">
                        <input id="profile-email" class="input" type="email" name="profile[email]" placeholder="請填寫電子郵件位置" v-model="profile.email" required>
                    </div>
                </div> {{-- /.field --}}

                <div class="field">
                    <label for="profile-address" class="label">收件地址</label>

                    <div class="control">
                        <input id="profile-address" class="input" type="text" name="profile[address]" placeholder="我們將寄送雜誌給您，請填寫收件地址。" v-model="profile.address">
                    </div>
                </div> {{-- /.field --}}

                <div class="field">
                    <label for="payment-amount" class="label required">捐款金額</label>

                    <div class="control">
                        <div class="select">
                            <select id="payment-amount" name="profile[amount]" v-model="payment.amount">
                                <option value="500">NT$500</option>
                                <option value="1000" selected>NT$1,000</option>
                                <option value="2000">NT$2,000</option>
                                <option value="3000">NT$3,000</option>
                                <option value="4000">NT$4,000</option>
                                <option value="5000">NT$5,000</option>
                                <option value="0">其它金額</option>
                            </select>
                        </div>
                    </div>
                </div> {{-- /.field --}}

                <div class="field" v-if="payment.amount === '0'">
                    <label for="payment-amount" class="label">其他金額</label>

                    <div class="control">
                        <input id="payment-amount" class="input" type="text" name="payment[custom_amount]" v-model="payment.custom_amount">
                    </div>
                </div>

                <div class="field">
                    <label for="payment-type" class="label required">贊助方式</label>

                    <div class="control">
                        <div class="select">
                            <select id="payment-type" name="payment[type]" v-model="payment.type">
                                <option value="{{ \App\Enums\DonationType::MONTHLY()->getValue() }}" selected>每月定期</option>
                                <option value="{{ \App\Enums\DonationType::ONE_TIME()->getValue() }}" selected>一次性贊助</option>
                            </select>
                        </div>
                    </div> {{-- /.control --}}
                </div> {{-- /.field --}}

                <div class="field" v-if="payment.type === '{{ \App\Enums\DonationType::MONTHLY()->getValue() }}'">
                    <label for="payment-count" class="label required">定期付款期數</label>

                    <div class="control">
                        <div class="select">
                            <select id="payment-count" name="payment[count]" v-model="payment.count">
                                <option :value="12">12 個月</option>
                                <option :value="24">24 個月</option>
                                <option :value="36" selected>36 個月</option>
                                <option :value="99" selected>99 個月</option>
                            </select>
                        </div>
                    </div> {{-- /.control --}}
                </div> {{-- /.field --}}

                <div class="field">
                    <label for="payment-message" class="label">留言</label>

                    <div class="control">
                        <textarea id="payment-message" class="textarea" name="payment[message]" placeholder="您可在此留下留言" v-model="payment.message"></textarea>
                    </div>
                </div> {{-- /.field --}}

                <div class="field">
                    <button class="button is-primary" v-if="!submitting">前往付款</button>
                    <button class="button is-primary is-loading" v-else disabled>前往付款</button>
                </div>
            </form>
        </div> {{-- /.column --}}
    </main>
</div> {{-- /#app --}}

<script src="{{ mix('assets/js/app.js') }}"></script>
</body>
</html>
