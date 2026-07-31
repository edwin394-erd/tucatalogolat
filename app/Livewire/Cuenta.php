<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Cuenta extends Component
{
    public $correo;
    public $telefono;
    public $codigodearea;
    public $plan;
    public $password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $user = auth()->user();
        $this->correo = $user->email;
        $this->telefono = $user->telephone;
        $this->plan = $user->subscriptions->last() ? $user->subscriptions->last()->plan->name : __('messages.no_plan');
    }

    public function saveChanges()
    {
        $user = auth()->user();

        $this->validate([
            'correo' => 'required|email|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'new_password' => 'nullable|string|min:8|confirmed',
        ], $this->validationMessages());

        if (! Hash::check($this->password, $user->password)) {
            $this->addError('password', __('messages.current_password_required'));
            return;
        }

        $data = [
            'email' => $this->correo,
            'telephone' => $this->telefono,
        ];

        if ($this->new_password) {
            $data['password'] = Hash::make($this->new_password);
        }

        $user->update($data);

        $this->password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';

        session()->flash('message', __('messages.settings_updated'));

        return redirect()->route('cuenta');
    }

    protected function validationMessages(): array
    {
        return [
            'correo.required' => __('messages.email_required'),
            'correo.email' => __('messages.email_invalid'),
            'correo.unique' => __('messages.email_unique'),
            'telefono.string' => __('messages.telephone_string'),
            'telefono.max' => __('messages.telephone_max'),
            'password.required' => __('messages.current_password_required'),
            'password.string' => __('messages.password_string'),
            'password.min' => __('messages.password_min'),
            'new_password.string' => __('messages.new_password_string'),
            'new_password.min' => __('messages.new_password_min'),
            'new_password.confirmed' => __('messages.new_password_confirmed'),
        ];
    }

    public function save()
    {
        $this->saveChanges();
    }

    public function render()
    {
        $catalogo = auth()->user()->catalogo;
        $user = auth()->user();
        $correo = $user->email;
        $telefono = $user->telephone;
        $plan = $user->subscriptions->last() ? $user->subscriptions->last()->plan->name : __('messages.no_plan');

        return view('livewire.cuenta', compact('correo', 'telefono', 'plan'))
            ->extends('layouts.auth2')
            ->section('content');
    }
}
