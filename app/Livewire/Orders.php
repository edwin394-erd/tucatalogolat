<?php

namespace App\Livewire;

use Livewire\Component;

class Orders extends Component
{
    public $model = 'Order';

    public function render()
    {
        return view('livewire.orders')
            ->extends('layouts.auth2')
            ->section('content');
    }
}
