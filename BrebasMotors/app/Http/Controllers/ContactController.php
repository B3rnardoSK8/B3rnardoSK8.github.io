<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $recipient = config('mail.from.address');
        $subject = $data['subject'] ?: 'Novo contacto do site';

        if (empty($recipient)) {
            return back()->withInput()->withErrors([
                'contact' => 'O email de destino nao esta configurado. Verifique MAIL_FROM_ADDRESS no .env.',
            ]);
        }

        try {
            $htmlBody = '<h2>Nova mensagem de contacto</h2>'
                . '<p><strong>Nome:</strong> ' . e($data['name']) . '</p>'
                . '<p><strong>Assunto:</strong> ' . e($subject) . '</p>'
                . '<hr>'
                . '<p><strong>Mensagem:</strong></p>'
                . '<p>' . nl2br(e($data['message'])) . '</p>';

            Mail::send([], [], function ($mail) use ($recipient, $subject, $data, $htmlBody) {
                $mail->to($recipient)
                    ->from($data['email'], $data['name'])
                    ->replyTo($data['email'], $data['name'])
                    ->subject($subject)
                    ->html($htmlBody);
            });
        } catch (\Throwable $exception) {
            Log::error('Falha ao enviar contacto.', [
                'error' => $exception->getMessage(),
            ]);

            return back()->withInput()->withErrors([
                'contact' => 'Nao foi possivel enviar a mensagem neste momento. Tente novamente.',
            ]);
        }

        return back()->with('contact_success', 'Mensagem enviada com sucesso. Obrigado pelo contacto!');
    }
}
