<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(
            [
                'email' => ['required', 'email', 'exists:users,email'],
            ],
            [
                'email.exists' => 'Não existe nenhuma conta com este email.',
            ]
        );

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()
                ->with('status', 'Enviámos o link de recuperação com sucesso.')
                ->with('email_sent_to', $request->input('email'));
        }

        return back()->withErrors([
            'email' => __($status),
        ]);
    }
}
