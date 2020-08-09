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
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Donation $donation
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment findHashid($hashId, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment findHashidOrFail($hashId, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment findHashidOrNew($hashId, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment findManyHashid($hashIds, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Payment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereDonationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereHashidKey($hashId)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Payment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Payment withoutTrashed()
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use SoftDeletes;
    use HasHashid, GetHashid, QueryWithHashid;

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
