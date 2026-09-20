<?php

namespace App\Livewire;

class InventoryExit extends Inventory
{
    public function save(): void
    {
        $this->recordMovement('exit');
    }

    public function render()
    {
        if ($this->modal) {
            return view('livewire.inventory-form', array_merge($this->formData(), ['movementType' => 'exit']));
        }

        return view('livewire.inventory-exit', $this->formData())
            ->extends('layouts.auth2')
            ->section('content');
    }
}