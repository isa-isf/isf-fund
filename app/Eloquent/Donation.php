<?php

namespace App\Eloquent;

use App\Enums\DonationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Eloquent\Donation
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property DonationType $type
 * @property float $amount
 * @property string $message
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Eloquent\Donation[] $payments
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Donation onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Donation whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Donation whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Donation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Donation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Donation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Donation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Donation whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Donation wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Donation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Donation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Donation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Donation withoutTrashed()
 * @mixin \Eloquent
 * @property string $email
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Donation whereEmail($value)
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Donation whereUuid($value)
 * @property int $count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Donation whereCount($value)
 * @property string|null $archive_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquent\Donation whereArchiveAt($value)
 */
class Donation extends Model
{
    use SoftDeletes;

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
}
