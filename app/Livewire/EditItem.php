<?php

namespace App\Livewire;

use Livewire\Component;

class EditItem extends Component
{

    public $model;
    public $id;

    public function render()
    {


        return view('livewire.edit-item')
            ->extends('layouts.auth2')
            ->section('content')
            ->with([
                'model' => $this->model,
                'id' => $this->id,
            ]);
    }
}
