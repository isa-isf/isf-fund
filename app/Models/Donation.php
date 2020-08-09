<?php

namespace App\Models;

use App\Enums\DonationStatus;
use App\Enums\DonationType;
use Binota\LaravelHashidHelpers\Concerns\GetHashid;
use Binota\LaravelHashidHelpers\Concerns\HasHashid;
use Binota\LaravelHashidHelpers\Concerns\QueryWithHashid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Donation
 *
 * @property int $id
 * @property string $status
 * @property string $uuid
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property string $type
 * @property int $count
 * @property float $amount
 * @property string $message
 * @property string|null $archive_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $hashid
 * @property-read \App\Models\Payment|null $latest_payment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment[] $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation findHashid($hashId, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation findHashidOrFail($hashId, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation findHashidOrNew($hashId, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation findManyHashid($hashIds, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Donation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereArchiveAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereHashidKey($hashId)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Donation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Donation withoutTrashed()
 * @mixin \Eloquent
 */
class Donation extends Model
{
    use SoftDeletes;
    use HasHashid, GetHashid, QueryWithHashid;

    public function setStatusAttribute(DonationStatus $value)
    {
        $this->attributes['status'] = $value;
    }

    public function getStatusAttribute($value)
    {
        if ($value instanceof DonationStatus) {
            return $value;
        }

        return new DonationStatus($value);
    }

    public function setTypeAttribute(DonationType $value)
    {
        $this->attributes['type'] = $value;
    }

    public function getTypeAttribute($value)
    {
        if ($value instanceof DonationType) {
            return $value;
        }

        return new DonationType($value);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latest_payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latest();
    }

    public function getLastPaymentTime(): ?Carbon
    {
        return optional($this->latest_payment)->updated_at;
    }
}
