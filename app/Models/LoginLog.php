<?php

namespace App\Models;

use App\Enums\LoginResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
