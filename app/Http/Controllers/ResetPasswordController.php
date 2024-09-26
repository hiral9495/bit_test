<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirmPassword' => 'required|same:password|min:6'
        ]);
        if ($validator->fails()) {
            session()->flash('password', $request->password);
            session()->flash('confirmPassword', $request->confirmPassword);
    
            // Redirect back with errors and input except passwords
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}
