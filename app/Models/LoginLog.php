<?php

namespace App\Models;

use App\Enums\LoginResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * App\Models\LoginLog
 *
 * @property int $id
 * @property string $email
 * @property \App\Enums\LoginResult $result
 * @property string $ip_address
 * @property string $user_agent
 * @property int|null $user_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereUserId($value)
 * @mixin \Eloquent
 */
class LoginLog extends Model
{
    protected $table = 'login_logs';

    public function setResultAttribute(LoginResult $value)
    {
        return $this->attributes['result'] = $value->__toString();
    }

    public function getResultAttribute($value): LoginResult
    {
        if ($value instanceof LoginResult) {
            return $value;
        }

        return new LoginResult($value);
    }

    static public function createFromRequest(Request $request, ?User $user, LoginResult $result)
    {
        $instance = new self;
        $instance->email = $request->input('email');
        $instance->user_id = optional($user)->id;
        $instance->ip_address = $request->ip();
        $instance->user_agent = $request->userAgent();
        $instance->result = $result;

        return tap($instance)->save();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
