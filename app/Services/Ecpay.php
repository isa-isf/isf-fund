<?php

namespace App\Services;

use App\Eloquent\Payment;
use App\Enums\DonationType;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class Ecpay
 */
class Ecpay
{
    public const ENCRYPT_TYPE_MD5 = '0';
    public const ENCRYPT_TYPE_SHA256 = '1';

    protected const DOT_NET_URL_ENCODE_TABLE = [
        '%2d' => '-',
        '%5f' => '_',
        '%2e' => '.',
        '%21' => '!',
        '%2a' => '*',
        '%28' => '(',
        '%29' => ')',
        '%20' => '+',
    ];

    protected const STAGE_MERCHANT_IDS = [
        '2000132',
        '2000214',
    ];

    /** @var \GuzzleHttp\Client */
    protected $guzzle;

    protected $merchant_id;

    protected $hash_key;

    protected $hash_iv;

    public function __construct(GuzzleClient $guzzle)
    {
        $this->guzzle = $guzzle;
        $this->merchant_id = config('services.ecpay.merchant_id');
        $this->hash_key = config('services.ecpay.hash_key');
        $this->hash_iv = config('services.ecpay.hash_iv');
    }

    public function createFrom(Payment $payment): array
    {
        $objectId = Str::before($payment->donation->uuid, '-');
        $objectId .= str_pad($payment->donation_id, 4, '0', STR_PAD_LEFT);
        $objectId .= str_pad($payment->id, 4, '0', STR_PAD_LEFT);
        $data = [
            'MerchantID' => $this->merchant_id,
            'MerchantTradeNo' => $objectId,
            'MerchantTradeDate' => $payment->created_at->format('Y/m/d H:i:s'),
            'PaymentType' => 'aio',
            'TotalAmount' => (int)$payment->amount,
            'TradeDesc' => urlencode('雜誌購買/訂閱'),
            'ItemName' => '社會主義者雜誌',
            'ReturnURL' => url("donations/{$payment->donation->uuid}/first-callback"),
            'ChoosePayment' => 'Credit',
            'ClientBackURL' => url('/'),
            'EncryptType' => 1,
            // 'OrderResultUrl' => url("donations/{$objectId}/completed"),
        ];

        if (DonationType::MONTHLY()->equals($payment->donation->type)) {
            $data['PeriodAmount'] = (int)$payment->amount;
            $data['PeriodType'] = 'M';
            $data['Frequency'] = 1;
            $data['ExecTimes'] = $payment->donation->count ?: 36;
            $data['PeriodReturnURL'] = url("donations/{$payment->donation->uuid}/period-callback");
        }

        $data['CheckMacValue'] = $this->generateCheckSum($data);

        return $data;
    }

    public function generateCheckSum(array $data, string $type = null): string
    {
        $data = Arr::except($data, 'CheckMacValue');
        ksort($data);
        $str = implode('&', array_map(function ($key, $value) {
            return "{$key}=" . ($value ?? '');
        }, array_keys($data), array_values($data)));
        $str = 'HashKey=' . $this->hash_key . '&' . $str;
        $str .= '&HashIV=' . $this->hash_iv;
        $str = strtolower(urlencode($str));
        $str = str_replace(array_keys(self::DOT_NET_URL_ENCODE_TABLE), array_values(self::DOT_NET_URL_ENCODE_TABLE), $str);

        switch ('' . ($type ?? optional($data)['EncryptType'])) {
            case '0':
                $hash = md5($str);
                break;
            default:
            case '1':
                $hash = hash('sha256', $str);
                break;
        }

        return strtoupper($hash);
    }

    public function getFullUrl(string $path): string
    {
        if (\in_array($this->merchant_id, self::STAGE_MERCHANT_IDS, true)) {
            return "https://payment-stage.ecpay.com.tw{$path}";
        }

        return "https://payment.ecpay.com.tw{$path}";
    }
}
