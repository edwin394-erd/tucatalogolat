<?php

namespace App\Livewire;

use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
    public $showPassword = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    protected $messages = [
        'email.required' => 'El campo de correo electrónico es obligatorio.',
        'email.email' => 'El correo electrónico debe ser una dirección de correo electrónico válida.',
        'password.required' => 'El campo de contraseña es obligatorio.',
    ];

    public function render()
    {
        return view('livewire.login')
        ->extends('layouts.guest')
        ->section('content');
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Implement login logic here
        if (auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            $catalogo = auth()->user()->catalogo;
            $destination = $catalogo && ! $catalogo->isConfigurationComplete()
                ? route('configuracion')
                : route('dashboard');

            return $this->redirect($destination);
        }

        // Authentication failed
        $this->addError('email', 'Las credenciales proporcionadas son incorrectas.');
    }
}
