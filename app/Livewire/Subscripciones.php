<?php

namespace App\Livewire;

use Livewire\Component;

class Subscripciones extends Component
{
    public $model;
    public function mount()
    {
        $this->model = "Subscription";
    }
    public function render()
    {
        return view('livewire.subscripciones')
        ->extends('layouts.auth2')
        ->section('content')
        ->with('model', $this->model);
    }
}
