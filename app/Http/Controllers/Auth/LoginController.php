<?php

namespace App\Http\Controllers\Auth;

use App\Enums\LoginResult;
use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

final class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where('email', $request->input('email'))->first();
        if ($user === null) {
            LoginLog::createFromRequest($request, $user, LoginResult::FAILED_UNKNOWN_USER());
            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            LoginLog::createFromRequest($request, $user, LoginResult::FAILED_PASSWORD());
            return $this->sendFailedLoginResponse($request);
        }

        LoginLog::createFromRequest($request, $user, LoginResult::CHALLENGING());

        return $this->sendChallengeLoginResponse($request, $user);
    }

    private function sendChallengeLoginResponse(Request $request, User $user)
    {
        $request->session()->put('challenging', [
            'user' => $user->id,
            'time' => time(),
            'remember' => $request->filled('remember'),
        ]);

        return redirect('challenge');
    }
}
