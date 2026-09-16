<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email = '';

    public bool $sent = false;

    public function sendResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Escribe un correo electrónico válido.',
        ]);

        $status = Password::sendResetLink([
            'email' => $this->email,
        ]);

        if ($status !== Password::RESET_LINK_SENT) {
            $this->addError('email', match ($status) {
                'passwords.throttled' => 'Ya solicitaste un enlace recientemente. Espera un minuto antes de intentarlo otra vez.',
                'passwords.user' => 'No encontramos una cuenta con ese correo electrónico.',
                default => 'No pudimos enviar el enlace. Inténtalo nuevamente en unos minutos.',
            });

            return;
        }

        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.forgot-password')
            ->extends('layouts.guest')
            ->section('content');
    }
}
