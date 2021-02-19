<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Binota\LaravelHashidHelpers\Concerns\GetHashid;
use Binota\LaravelHashidHelpers\Concerns\HasHashid;
use Binota\LaravelHashidHelpers\Concerns\QueryWithHashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Payment
 *
 * @property int $id
 * @property int $status
 * @property int $donation_id
 * @property string $amount
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property \Carbon\CarbonImmutable|null $deleted_at
 * @property-read \App\Models\Donation $donation
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder|Payment findHashid($hashId, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Payment findHashidOrFail($hashId, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Payment findHashidOrNew($hashId, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Payment findManyHashid($hashIds, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Query\Builder|Payment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDonationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereHashidKey($hashId)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Payment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Payment withoutTrashed()
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use SoftDeletes;
    use HasHashid, GetHashid, QueryWithHashid;

    protected $hashidConnection = 'payment';

    public function setStatusAttribute(PaymentStatus $value)
    {
        $this->attributes['status'] = $value;
    }

    public function getStatusAttribute($value)
    {
        if ($value instanceof PaymentStatus) {
            return $value;
        }

        return new PaymentStatus($value);
    }

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    public static function createFromDonation(Donation $donation): self
    {
        return tap(new static, function (self $model) use ($donation) {
            $model->status = PaymentStatus::CREATED();
            $model->amount = $donation->amount;
            $donation->payments()->save($model);
        });
    }
}
