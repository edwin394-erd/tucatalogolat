<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Catalogo;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $country;
    public $city;
    public $telephone;
    public $role = 'user';
    public $address;

    protected $rules = [
        'name' => 'required|string|max:255|unique:users,name',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'country' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'telephone' => 'required|regex:/^[0-9]+$/|max:20',
        'role' => 'required|string|max:50',
        'address' => 'required|string|max:255',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.unique' => 'El nombre ya está en uso.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'El correo electrónico no es válido.',
        'email.unique' => 'El correo electrónico ya está registrado.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'country.required' => 'El país es obligatorio.',
        'city.required' => 'La ciudad es obligatoria.',
        'telephone.required' => 'El teléfono es obligatorio.',
        'telephone.regex' => 'El teléfono debe contener solo números.',
        'role.required' => 'El rol es obligatorio.',
        'address.required' => 'La dirección es obligatoria.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.register')
            ->extends('layouts.guest')
            ->section('content');
    }

    public function register()
    {
        $this->validate();

        // Create the user
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'country' => $this->country,
            'city' => $this->city,
            'address' => $this->address,
            'telephone' => $this->telephone,
            'role' => 'user',
        ]);

        Catalogo::create([
            'name' => $this->name,
            'user_id' => $user->id,
            'telefono_contacto' => $this->telephone,
        ]);

        // Redirect or show a success message
        session()->flash('message', 'Registro exitoso. Por favor, inicia sesión.');
        return redirect()->route('dashboard');
    }
    }

