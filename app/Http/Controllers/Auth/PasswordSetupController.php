<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordSetupController extends Controller
{
    public function form(User $user, Request $request)
    {
        abort_if(!empty($user->password), 403);

        return view('auth.passwords.setup', [
            'user' => $user,
            'expires' => $request->input('expires'),
        ]);
    }

    public function store(User $user, Request $request)
    {
        abort_if(!empty($user->password), 403);

        $this->validate($request, [
            'password' => ['min:8', 'confirmed'],
        ]);

        $user->password = Hash::make($request->input('password'));
        $user->saveOrFail();

        return redirect('login')->with('message', '已設定密碼。');
    }
}
