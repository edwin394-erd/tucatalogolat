<?php

namespace App\Livewire;

use Livewire\Component;

class Usuarios extends Component
{

public $model;
    public function mount()
    {
        $this->model = 'User';
    }
    public function render()
    {
        return view('livewire.usuarios')
        ->extends('layouts.auth2')
        ->section('content')
        ->with('model', $this->model);
    }
}
