<?php

namespace App\Eloquent;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Eloquent\Payment
 *
 * @property int $id
 * @property int $status
 * @property int $donation_id
 * @property float $amount
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Eloquent\Donation $donation
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Payment onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Payment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Payment whereDonationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Payment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Payment withoutTrashed()
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use SoftDeletes;

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    public static function createFromDonation(Donation $donation)
    {
        return tap(new static, function (self $model) use ($donation) {
            $model->status = PaymentStatus::CREATED();
            $model->amount = $donation->amount;
            $donation->payments()->save($model);
        });
    }
}
