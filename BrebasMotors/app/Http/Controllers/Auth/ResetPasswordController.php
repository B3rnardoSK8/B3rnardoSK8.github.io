<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null): View
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $email = $this->resolveEmailFromToken($request->input('token'));

        if (! $email) {
            return back()->withErrors([
                'token' => 'O link de recuperação é inválido ou expirou.',
            ]);
        }

        $status = Password::reset(
            [
                'email' => $email,
                'password' => $request->input('password'),
                'password_confirmation' => $request->input('password_confirmation'),
                'token' => $request->input('token'),
            ],
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        return back()->withErrors([
            'token' => __($status),
        ]);
    }

    private function resolveEmailFromToken(string $plainToken): ?string
    {
        $tokens = DB::table('password_reset_tokens')->select('email', 'token')->get();

        foreach ($tokens as $row) {
            if (Hash::check($plainToken, $row->token)) {
                return $row->email;
            }
        }

        return null;
    }
}
