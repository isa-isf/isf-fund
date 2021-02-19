<?php

namespace App\Models;

use Binota\LaravelHashidHelpers\Concerns\GetHashid;
use Binota\LaravelHashidHelpers\Concerns\HasHashid;
use Binota\LaravelHashidHelpers\Concerns\HashidRouteBinding;
use Binota\LaravelHashidHelpers\Concerns\QueryWithHashid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property int $is_active
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LoginLog[] $login_logs
 * @property-read int|null $login_logs_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User findHashid($hashId, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User findHashidOrFail($hashId, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User findHashidOrNew($hashId, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User findManyHashid($hashIds, $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereHashidKey($hashId)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasHashid, GetHashid, QueryWithHashid;
    use HashidRouteBinding;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function login_logs()
    {
        return $this->hasMany(LoginLog::class)->latest();
    }

    public function routeNotificationForSms()
    {
        return $this->phone;
    }
}
