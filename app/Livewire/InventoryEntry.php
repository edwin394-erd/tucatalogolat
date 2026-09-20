<?php

namespace App\Livewire;

class InventoryEntry extends Inventory
{
    public function render()
    {
        if ($this->modal) {
            return view('livewire.inventory-form', array_merge($this->formData(), ['movementType' => 'entry']));
        }

        return view('livewire.inventory-entry', $this->formData())
            ->extends('layouts.auth2')
            ->section('content');
    }
}