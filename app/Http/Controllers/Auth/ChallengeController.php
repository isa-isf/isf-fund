<?php

namespace App\Http\Controllers\Auth;

use App\Enums\LoginResult;
use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use App\Models\User;
use App\Notifications\UserChallenge;
use App\Providers\AuthServiceProvider;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

final class ChallengeController extends Controller
{
    const REDIRECT = 'manage';
    const EXPIRES = 60 * 15;

    public function form()
    {
        if (($expirationCheck = $this->checkExpiration()) !== true) {
            return $expirationCheck;
        }

        $user = User::findOrFail(Session::get('challenging.user'));

        if (!Session::get('challenging.sent', false)) {
            $code = $this->getCode($user, $this->getExpires());
            $user->notify(new UserChallenge($code));

            Session::put('challenging.sent', true);
        }

        return view('auth.passwords.challenge', [
            'user' => $user,
        ]);
    }

    public function confirm(Request $request)
    {
        if (($expirationCheck = $this->checkExpiration()) !== true) {
            return $expirationCheck;
        }

        $user = User::findOrFail(Session::get('challenging.user'));
        $code = $this->getCode($user, $this->getExpires());

        if (!hash_equals($code, $request->input('code'))) {
            LoginLog::createFromRequest($request, $user, LoginResult::FAILED_CHALLENGE());
            throw ValidationException::withMessages([
                'code' => ['驗證碼不正確'],
            ]);
        }

        Auth::login($user, Session::get('challenging.remember'));
        Session::forget('challenging');
        LoginLog::createFromRequest($request, $user, LoginResult::SUCCESS());

        return redirect(self::REDIRECT);
    }

    private function checkExpiration()
    {
        if ($this->getExpires()->isPast()) {
            Session::forget('challenging');
            return redirect('login')->with('message', '二步驟驗證逾時，請重新登入');
        }

        return true;
    }

    private function getExpires()
    {
        return Date
            ::createFromTimestamp(Session::get('challenging.time'))
            ->addSeconds(self::EXPIRES);
    }

    private function getCode(User $user, CarbonInterface $expires)
    {
        $cacheKey = "{$user->id}:challenging:{$expires}";

        return Cache::get("{$cacheKey}:code", function () use ($cacheKey, $expires) {
            $code = str_pad(random_int(0, 1000000), 6, '0', STR_PAD_LEFT);
            Cache::set("{$cacheKey}:code", $code, $expires);

            return $code;
        });
    }
}
